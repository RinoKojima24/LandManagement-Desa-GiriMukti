<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
$users = User::where('id', '!=', Auth::id())
    ->latest() // sama dengan orderBy('created_at', 'desc')
    ->paginate(10);        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'nama_petugas' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'no_telepon' => 'nullable|string|max:20',
            'role' => 'required|in:admin,super_admin,operator,viewer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
          $userData = $request->except('password_confirmation');
            $userData['password_hash'] = Hash::make($request->password);
            $userData['is_active'] = $request->has('is_active') ? true : false;


User::create($userData);


            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = User::findOrFail($user->id)->first();
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'nama_petugas' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'role' => 'required|in:admin,super_admin,operator,viewer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $userData = $request->except(['password', 'password_confirmation']);

            // Update password only if provided
            if ($request->filled('password')) {
                $userData['password_hash'] = Hash::make($request->password);
            }

            $userData['is_active'] = $request->has('is_active') ? true : false;

            $user->update($userData);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
{
    try {
        $user = User::findOrFail($id);

        // Balikkan nilai is_active (toggle)
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "User berhasil {$status}.");
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    /**
     * Search users
     */
    public function search(Request $request)
    {
        $query = $request->get('search');

        $users = User::where(function($q) use ($query) {
            $q->where('username', 'LIKE', "%{$query}%")
              ->orWhere('nama_petugas', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('jabatan', 'LIKE', "%{$query}%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users', 'query'));
    }

    /**
     * Filter users by role
     */
    public function filterByRole(Request $request)
    {
        $role = $request->get('role');

        $users = User::when($role, function($query) use ($role) {
            return $query->where('role', $role);
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users', 'role'));
    }

    /**
     * Update last login timestamp
     */
}
