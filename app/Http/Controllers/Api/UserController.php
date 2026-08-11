<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a paginated listing of users.
     *
     * Supports searching by name/email, filtering by role, and a
     * configurable page size. Mirrors the web `UserController::index`.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->string('search')->toString();
        $selectedRole = $request->string('role')->toString();
        $perPage = min((int) $request->integer('per_page', 10) ?: 10, 100);

        $users = User::query()
            ->with(['roles', 'school'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($selectedRole !== '', function ($query) use ($selectedRole) {
                $query->whereHas('roles', function ($query) use ($selectedRole) {
                    $query->where('name', $selectedRole);
                });
            })
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user->load(['roles', 'school']));
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $user = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'status' => $validated['status'],
                ]);

                $user->assignRole($validated['role']);

                return $user;
            });
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to create user.',
            ], 500);
        }

        return response()->json([
            'message' => 'User created successfully.',
            'data' => new UserResource($user->load(['roles', 'school'])),
        ], 201);
    }

    /**
     * Update the specified user.
     *
     * A blank password field means "keep the current password".
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        try {
            DB::transaction(function () use ($validated, $user) {
                $user->update($validated);
                $user->syncRoles([$validated['role']]);
            });
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to update user.',
            ], 500);
        }

        return response()->json([
            'message' => 'User updated successfully.',
            'data' => new UserResource($user->fresh(['roles', 'school'])),
        ], 200);
    }

    /**
     * Remove (soft delete) the specified user.
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        try {
            $user->delete();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete user.',
            ], 500);
        }

        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
