<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display buses available for attendance.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Bus::query()
            ->where('status', 'Active')
            ->with(['school', 'driver', 'route'])
            ->withCount('students');

        if ($user->hasRole('Super Admin')) {
            // All buses.
        } elseif ($user->hasAnyRole(['School Admin', 'Principal'])) {
            $schoolId = $this->getUserSchoolId($user);

            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        } elseif ($user->hasRole('Driver')) {
            $driverId = Driver::where('user_id', $user->id)->value('id');

            if ($driverId) {
                $query->where('driver_id', $driverId);
            }
        }

        $buses = $query->orderBy('bus_number')->get();

        $today = $request->query('date') ?: now()->toDateString();

        $checkedIn = Attendance::query()
            ->whereDate('date', $today)
            ->whereIn('bus_id', $buses->pluck('id'))
            ->whereNotNull('check_in_at')
            ->get()
            ->groupBy('bus_id')
            ->map
            ->count();

        $groupedBySchool = $user->hasRole('Super Admin');

        return view('attendance.index', compact('buses', 'checkedIn', 'today', 'groupedBySchool'));
    }

    /**
     * Display a bus and its assigned students for check-in/check-out.
     */
    public function show(Request $request, Bus $bus)
    {
        $this->authorizeBus($bus);
        $this->ensureBusActive($bus);

        $date = $request->query('date') ?: now()->toDateString();

        $bus->load(['school', 'driver', 'route']);

        $students = $bus->students()
            ->with('parent.user')
            ->orderBy('grade')
            ->orderBy('roll_no')
            ->get();

        $attendance = Attendance::query()
            ->whereDate('date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('attendance.show', compact('bus', 'students', 'attendance', 'date'));
    }

    /**
     * Check a student in or out on a bus.
     */
    public function mark(Request $request, Bus $bus, Student $student)
    {
        $this->authorizeBus($bus);
        $this->ensureBusActive($bus);

        $validated = $request->validate([
            'action' => ['required', 'in:check_in,check_out'],
            'date' => ['nullable', 'date'],
        ]);

        if ((int) $student->bus_id !== (int) $bus->id) {
            abort(403, 'This student is not assigned to this bus.');
        }

        $date = ! empty($validated['date']) ? Carbon::parse($validated['date']) : now();

        $attendance = Attendance::query()
            ->where('student_id', $student->id)
            ->whereDate('date', $date)
            ->first();

        if ($validated['action'] === 'check_in') {
            if ($attendance?->isCheckedIn()) {
                return back()->withErrors(['check_in' => "{$student->full_name} is already checked in."]);
            }

            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'date' => $date],
                [
                    'bus_id' => $bus->id,
                    'check_in_at' => now(),
                    'marked_by' => Auth::id(),
                ]
            );

            $message = "{$student->full_name} checked in successfully.";
        } else {
            if (! $attendance || ! $attendance->isCheckedIn()) {
                return back()->withErrors(['check_out' => "{$student->full_name} must check in before checking out."]);
            }

            if ($attendance->isCheckedOut()) {
                return back()->withErrors(['check_out' => "{$student->full_name} is already checked out."]);
            }

            $attendance->update([
                'check_out_at' => now(),
                'marked_by' => Auth::id(),
            ]);

            $message = "{$student->full_name} checked out successfully.";
        }

        return redirect()
            ->route('attendance.buses.show', ['bus' => $bus, 'date' => $date->toDateString()])
            ->with('success', $message);
    }

    /**
     * Display the attendance history for a bus across dates.
     */
    public function history(Request $request, Bus $bus)
    {
        $this->authorizeBus($bus);

        $bus->load(['school', 'route', 'driver']);

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = ! empty($validated['from'])
            ? Carbon::parse($validated['from'])
            : now()->subDays(30)->startOfDay();

        $to = ! empty($validated['to'])
            ? Carbon::parse($validated['to'])->endOfDay()
            : now()->endOfDay();

        $records = Attendance::query()
            ->with(['student', 'markedBy'])
            ->where('bus_id', $bus->id)
            ->whereBetween('date', [$from, $to])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        $totalRecords = $records->total();

        return view('attendance.history', compact('bus', 'records', 'from', 'to', 'totalRecords'));
    }

    /**
     * Make sure attendance can only be marked on an active bus.
     */
    private function ensureBusActive(Bus $bus): void
    {
        if ($bus->status !== 'Active') {
            abort(403, 'Attendance is only available for active buses.');
        }
    }

    /**
     * Make sure the current user is allowed to access this bus.
     */
    private function authorizeBus(Bus $bus): void
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return;
        }

        if ($user->hasAnyRole(['School Admin', 'Principal'])) {
            $schoolId = $this->getUserSchoolId($user);

            if ($schoolId && (int) $bus->school_id !== (int) $schoolId) {
                abort(403, 'You are not authorized to access this bus.');
            }

            return;
        }

        if ($user->hasRole('Driver')) {
            $driverId = Driver::where('user_id', $user->id)->value('id');

            if ($driverId && (int) $bus->driver_id === (int) $driverId) {
                return;
            }

            abort(403, 'You are not authorized to access this bus.');
        }

        abort(403);
    }

    /**
     * Resolve the school id for school-level admins.
     */
    private function getUserSchoolId(?User $user): ?int
    {
        if (! $user) {
            return null;
        }

        if (! empty($user->school_id)) {
            return (int) $user->school_id;
        }

        $schoolAdmin = SchoolAdmin::where('user_id', $user->id)->first();

        if ($schoolAdmin && ! empty($schoolAdmin->school_id)) {
            return (int) $schoolAdmin->school_id;
        }

        $school = School::where('principal_name', $user->name)
            ->orWhere('email', $user->email)
            ->first();

        return $school?->id;
    }
}
