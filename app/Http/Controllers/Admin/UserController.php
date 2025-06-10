<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
public function index(Request $request)
{
    $query = User::query();

    if ($request->has('role') && in_array($request->role, ['admin', 'client'])) {
        $query->where('role', $request->role);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $clients = User::where('role', 'client')
        ->withCount('reservations') 
        ->paginate(10);

    $admins = User::where('role', 'admin')->paginate(10);

    return view('admin.users.index', compact('clients', 'admins'));
}



/**
 * Show the form to create a new user.
 */
public function create()
{
    $roles = ['admin', 'client'];
    return view('admin.users.create', compact('roles'));
}

/**
 * Store a newly created user.
 */
public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'role' => ['required', Rule::in(['admin', 'client'])],
        'password' => 'required|string|min:8|confirmed',
        'avatar_choose' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'avatar_option' => 'nullable|string',
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);

  

// Handle avatar upload or option
if ($request->hasFile('avatar_choose') && $request->file('avatar_choose')->isValid()) {
    $avatarName = $request->name . '-' . Str::random(10) . '.' . $request->file('avatar_choose')->extension();
    $avatarNameNospaces = preg_replace('/\s+/', '', $avatarName);
    
    
    $path = $request->file('avatar_choose')->storeAs('/images/avatars', $avatarNameNospaces);

    // Save the path with leading slash
    $validatedData['avatar'] = '/' . $path;
} elseif ($request->filled('avatar_option')) {
    $validatedData['avatar'] = $request->avatar_option;
}


    User::create($validatedData);

    return redirect()->route('admin.users.index')
        ->with('success', 'User created successfully');
}
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $reservations = Reservation::where('user_id', $user->id)
            ->with('car')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeReservations = $reservations->where('status', Reservation::STATUS_ACTIVE)->count();
$completedReservations = $reservations->where('status', Reservation::STATUS_COMPLETED)->count();
$cancelledReservations = $reservations->where('status', Reservation::STATUS_CANCELED)->count();
$pendingReservations = $reservations->where('status', Reservation::STATUS_PENDING)->count();
$totalSpent = \App\Models\Payment::whereHas('reservation', function ($query) use ($user) {
    $query->where('user_id', $user->id);
})->sum('amount');



        return view('admin.users.show', compact(
            'user',
            'reservations',
            'activeReservations',
            'completedReservations',
            'cancelledReservations',
            'pendingReservations',
            'totalSpent'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'client'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required', 'email', 'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'role' => ['required', Rule::in(['admin', 'client'])],
        'password' => 'nullable|string|min:8|confirmed',
        'avatar_choose' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'avatar_option' => 'nullable|string',
    ]);

    // Password
    if ($validatedData['password']) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    } else {
        unset($validatedData['password']);
    }

    
// Handle avatar upload or option
if ($request->hasFile('avatar_choose') && $request->file('avatar_choose')->isValid()) {
    // Delete previous uploaded avatar if it exists and is in /images/avatars
    if ($user->avatar && str_starts_with($user->avatar, '/images/avatars') && Storage::exists(ltrim($user->avatar, '/'))) {
        Storage::delete(ltrim($user->avatar, '/'));
    }

    $avatarName = $request->name . '-' . Str::random(10) . '.' . $request->file('avatar_choose')->extension();
    $avatarNameNospaces = preg_replace('/\s+/', '', $avatarName);
    
   
    $path = $request->file('avatar_choose')->storeAs('/images/avatars', $avatarNameNospaces);

    $validatedData['avatar'] = '/' . $path;

} elseif ($request->filled('avatar_option')) {
    // Delete previous uploaded avatar if switching to avatar option
    if ($user->avatar && str_starts_with($user->avatar, '/images/avatars') && Storage::exists(ltrim($user->avatar, '/'))) {
        Storage::delete(ltrim($user->avatar, '/'));
    }

    $validatedData['avatar'] = $request->avatar_option;
}

    $user->update($validatedData);

    return redirect()->route('admin.users.show', $user)
        ->with('success', 'User information updated successfully');
}

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $activeReservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->count();

        if ($activeReservations > 0) {
            return back()->with('error', 'Cannot delete user with active or pending reservations.');
        }

        // // Delete avatar if exists
        // if ($user->avatar && Storage::exists($user->avatar)) {
        //     Storage::delete($user->avatar);
        // }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
