<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusResource;
use App\Http\Resources\ParentProfileResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\StudentResource;
use App\Models\Attendance;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Services\FleetMapService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ParentController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

    /**
     * View the authenticated parent's own profile.
     */
    public function profile(Request $request): ParentProfileResource
    {
        $parent = $this->parentOrFail($request);

        $parent->load(['user', 'school']);
        $parent->loadCount('children');

        return new ParentProfileResource($parent);
    }

    /**
     * List the authenticated parent's own children (with their assigned bus).
     */
    public function students(Request $request): AnonymousResourceCollection
    {
        $parent = $this->parentOrFail($request);

        $students = $parent->children()
            ->with(['bus.driver', 'bus.route'])
            ->orderBy('first_name')
            ->get();

        return StudentResource::collection($students);
    }

    /**
     * View a single one of the authenticated parent's children.
     */
    public function showStudent(Request $request, int $student): StudentResource
    {
        $child = $this->childOrFail($request, $student);

        $child->load(['bus.driver', 'bus.route.stops', 'school']);

        return new StudentResource($child);
    }

    /**
     * View the bus assigned to one of the parent's children.
     */
    public function studentBus(Request $request, int $student): JsonResponse
    {
        $child = $this->childOrFail($request, $student)->load(['bus.driver', 'bus.route']);

        if (! $child->bus) {
            return $this->notFound('This student has no assigned bus.');
        }

        return response()->json([
            'data' => new BusResource($child->bus),
        ]);
    }

    /**
     * View the route (and stops) assigned to one of the parent's children.
     */
    public function studentRoute(Request $request, int $student): JsonResponse
    {
        $child = $this->childOrFail($request, $student)->load('bus.route.stops');

        if (! $child->bus || ! $child->bus->route) {
            return $this->notFound('No route is assigned to this student\'s bus.');
        }

        return response()->json([
            'data' => new RouteResource($child->bus->route),
        ]);
    }

    /**
     * Live GPS location, tracking status, next stop and ETA for the child's bus.
     *
     * Reuses the existing FleetMapService / NazarTrack GPS pipeline that the
     * driver module and parent web views already use. No second GPS system.
     */
    public function studentTracking(Request $request, int $student): JsonResponse
    {
        $child = $this->childOrFail($request, $student)->load('bus');

        if (! $child->bus) {
            return $this->notFound('This student has no assigned bus.');
        }

        $fleet = $this->fleetMap->forSchool(null, collect([$child->bus->id]));

        $busTracking = $fleet['buses'][0] ?? null;
        $route = $fleet['routes'][0] ?? null;

        return response()->json([
            'data' => [
                'bus' => $busTracking,
                'route' => $route,
                'updated_at' => $fleet['updated_at'],
            ],
        ]);
    }

    /**
     * Today's trip status (attendance legs) for one of the parent's children.
     *
     * A trip is either home_to_school (pickup) or school_to_home (drop).
     * Status is derived from the existing Attendance check-in/check-out data
     * marked by the driver module.
     */
    public function studentTrip(Request $request, int $student): JsonResponse
    {
        $child = $this->childOrFail($request, $student);

        $records = Attendance::query()
            ->where('student_id', $child->id)
            ->whereDate('date', now()->toDateString())
            ->get()
            ->keyBy('trip');

        $trips = collect(Attendance::trips())
            ->map(fn (string $label, string $trip) => [
                'trip' => $trip,
                'trip_label' => $label,
                'attendance_id' => $records->get($trip)?->id,
                'check_in_at' => $records->get($trip)?->check_in_at?->toIso8601String(),
                'check_out_at' => $records->get($trip)?->check_out_at?->toIso8601String(),
                'status' => $records->has($trip)
                    ? $this->tripStatus($records->get($trip))
                    : 'not_started',
                'bus_id' => $records->get($trip)?->bus_id,
            ])
            ->values();

        return response()->json([
            'data' => [
                'student' => [
                    'id' => $child->id,
                    'full_name' => $child->full_name,
                ],
                'date' => now()->toDateString(),
                'trips' => $trips,
            ],
        ]);
    }

    /**
     * List the authenticated parent's notifications.
     *
     * Reuses Laravel's built-in notification system (notifications table).
     * Notifications are already scoped to the authenticated user, so a parent
     * can never see another user's notifications.
     */
    public function notifications(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $items = $notifications->getCollection()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'type' => class_basename($notification->type),
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    /**
     * Unread notification count for the authenticated parent.
     */
    public function unreadNotificationsCount(Request $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'count' => $request->user()->unreadNotifications()->count(),
            ],
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markNotificationAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
            'data' => [
                'id' => $notification->id,
            ],
        ]);
    }

    /**
     * Mark all of the authenticated parent's notifications as read.
     */
    public function markAllNotificationsAsRead(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();

        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read.',
            'data' => [
                'marked' => $count,
            ],
        ]);
    }

    /**
     * Resolve the ParentProfile for the authenticated user.
     *
     * @throws ModelNotFoundException
     */
    private function parentOrFail(Request $request): ParentProfile
    {
        return ParentProfile::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
    }

    /**
     * Resolve one of the authenticated parent's own children.
     *
     * Every student-scoped endpoint goes through here so a parent can only
     * ever reach their own students (any other id returns 404).
     *
     * @throws ModelNotFoundException
     */
    private function childOrFail(Request $request, int $studentId): Student
    {
        $parent = $this->parentOrFail($request);

        return $parent->children()
            ->whereKey($studentId)
            ->firstOrFail();
    }

    private function tripStatus(Attendance $attendance): string
    {
        if ($attendance->isCheckedOut()) {
            return 'completed';
        }

        if ($attendance->isCheckedIn()) {
            return 'in_progress';
        }

        return 'not_started';
    }

    private function notFound(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 404);
    }
}
