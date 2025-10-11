<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman daftar notifikasi (Blade View)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = $request->get('per_page', 15);

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Return ke blade view
        return view('notifications.index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * API: Ambil notifikasi yang belum dibaca (untuk AJAX)
     */
    public function unread()
    {
        $userId = Auth::id();

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $notifications->count(),
            'data' => $notifications
        ]);
    }

    /**
     * API: Hitung jumlah notifikasi belum dibaca (untuk badge)
     */
    public function count()
    {
        $userId = Auth::id();

        $count = DB::select('SELECT count_unread(?) as count', [$userId]);

        return response()->json([
            'success' => true,
            'unread_count' => $count[0]->count ?? 0
        ]);
    }

    /**
     * API: Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $userId = Auth::id();

        DB::statement('CALL mark_as_read(?, ?)', [$id, $userId]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * API: Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();

        DB::statement('CALL mark_all_read(?)', [$userId]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * Hapus notifikasi tertentu
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            // Jika request dari AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dihapus'
                ]);
            }

            // Redirect dengan flash message
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
    }

    /**
     * Hapus semua notifikasi yang sudah dibaca
     */
    public function deleteRead()
    {
        $userId = Auth::id();

        $deleted = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 1)
            ->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "$deleted notifikasi berhasil dihapus"
            ]);
        }

        return redirect()->back()->with('success', "$deleted notifikasi berhasil dihapus");
    }

    /**
     * Halaman form broadcast notifikasi (untuk admin)
     */
    public function createBroadcast()
    {
        // Ambil semua role untuk dropdown
        $roles = DB::table('users')
            ->select('role')
            ->distinct()
            ->get();

        return view('notifications.broadcast', [
            'roles' => $roles
        ]);
    }

    /**
     * Buat notifikasi baru (untuk admin/sistem)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link_url' => 'nullable|string|max:255'
        ]);

        try {
            DB::statement('CALL create_notification(?, ?, ?, ?, ?)', [
                $request->user_id,
                $request->type,
                $request->title,
                $request->message,
                $request->link_url
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dibuat'
                ], 201);
            }

            return redirect()->back()->with('success', 'Notifikasi berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error creating notification: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat notifikasi'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal membuat notifikasi');
        }
    }

    /**
     * Broadcast notifikasi ke banyak user
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link_url' => 'nullable|string|max:255'
        ]);

        $successCount = 0;

        foreach ($request->user_ids as $userId) {
            try {
                DB::statement('CALL create_notification(?, ?, ?, ?, ?)', [
                    $userId,
                    $request->type,
                    $request->title,
                    $request->message,
                    $request->link_url
                ]);
                $successCount++;
            } catch (\Exception $e) {
                Log::error("Failed to create notification for user $userId: " . $e->getMessage());
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Notifikasi berhasil dikirim ke $successCount user"
            ]);
        }

        return redirect()->back()->with('success', "Notifikasi berhasil dikirim ke $successCount user");
    }

    /**
     * Broadcast ke semua user dengan role tertentu
     */
    public function broadcastByRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'type' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link_url' => 'nullable|string|max:255'
        ]);

        // Ambil semua user dengan role tertentu
        $users = DB::table('users')
            ->where('role', $request->role)
            ->pluck('id');

        $successCount = 0;

        foreach ($users as $userId) {
            try {
                DB::statement('CALL create_notification(?, ?, ?, ?, ?)', [
                    $userId,
                    $request->type,
                    $request->title,
                    $request->message,
                    $request->link_url
                ]);
                $successCount++;
            } catch (\Exception $e) {
                Log::error("Failed to create notification for user $userId: " . $e->getMessage());
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Notifikasi berhasil dikirim ke $successCount user dengan role {$request->role}"
            ]);
        }

        return redirect()->back()->with('success', "Notifikasi berhasil dikirim ke $successCount user dengan role {$request->role}");
    }

    /**
     * Get detail notifikasi (Blade View)
     */
    public function show($id)
    {
        $userId = Auth::id();

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            return redirect()->route('notifications.index')->with('error', 'Notifikasi tidak ditemukan');
        }

        // Auto mark as read saat dibuka
        if (!$notification->is_read) {
            DB::statement('CALL mark_as_read(?, ?)', [$id, $userId]);
        }

        // Jika AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $notification
            ]);
        }

        // Return ke blade view
        return view('notifications.show', [
            'notification' => $notification
        ]);
    }

    /**
     * Get statistik notifikasi user
     */
    public function stats()
    {
        $userId = Auth::id();

        $stats = DB::table('v_notification_stats')
            ->where('user_id', $userId)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $stats->total ?? 0,
                'unread' => $stats->unread ?? 0,
                'last_notification' => $stats->last_notification ?? null
            ]
        ]);
    }
}
