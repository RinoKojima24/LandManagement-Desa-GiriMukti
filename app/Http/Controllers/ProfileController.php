<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        return view('web.profile.index', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        return view('web.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $updateData = [
            'nama_petugas' => $validated['name'],
            'no_telepon' => $validated['phone'] ?? null,
            'updated_at' => now(),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        // Update user information using Query Builder
        DB::table('users')
            ->where('id', $userId)
            ->update($updateData);

        return redirect()->route('profile.edit')->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min' => 'Password minimal 8 karakter.',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Update password using Query Builder
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete the user's account.
     */
    public function delete(Request $request)
    {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        // Delete user avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout user
        Auth::logout();

        // Delete user account using Query Builder
        DB::table('users')->where('id', $userId)->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}
