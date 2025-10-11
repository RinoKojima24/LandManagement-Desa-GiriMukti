<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * Kirim notifikasi ke user tertentu
     * INSERT INTO notifications (user_id, type, title, message, link_url) VALUES (p_user_id, p_type, p_title, p_message, p_link_url);
     * )
     */
        public static function send($userId, $type, $title, $message, $linkUrl = null)
        {
            try {
                DB::statement('CALL create_notification(?, ?, ?, ?, ?)', [
                    $userId,
                    $type,
                    $title,
                    $message,
                    $linkUrl
                ]);
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to send notification: " . $e->getMessage());
                return false;
            }
        }

    /**
     * Notifikasi untuk permohonan surat baru
     */
    public static function newLetterRequest($userId, $permohonanId, $jenisPermohonan)
    {
        return self::send(
            $userId,
            'NEW_REQUEST',
            'Permohonan Baru',
            "Permohonan $jenisPermohonan telah diterima dan sedang diproses.",
            "/permohonan/$permohonanId"
        );
    }

    /**
     * Notifikasi untuk perubahan status permohonan
     */
    public static function requestStatusChanged($userId, $permohonanId, $status)
    {
        $messages = [
            'verifikasi' => 'Permohonan Anda sedang diverifikasi',
            'selesai' => 'Permohonan Anda telah selesai dan siap diambil',
            'reject' => 'Permohonan Anda ditolak. Silakan hubungi admin',
        ];

        $title = 'Status Permohonan: ' . ucfirst($status);
        $message = $messages[$status] ?? "Status permohonan berubah menjadi: $status";

        return self::send(
            $userId,
            'REQUEST_STATUS',
            $title,
            $message,
            "/permohonan/$permohonanId"
        );
    }

    public static function toAdmin($userId, $jenis, $message){
        $adminIds = DB::table('users')->where('role', 'admin')->pluck('id')->toArray();;
        foreach ($adminIds as $adminId) {
            self::send(
                $adminId, // satu per satu ID
                'CRITICISM_AND_SUGGESTIONS',
                $jenis,
                $message,
                $userId
            );
        }
    }
    /**
     * Notifikasi antrean dipanggil
     */
    public static function queueCalled($userId, $nomorAntrean)
    {
        return self::send(
            $userId,
            'QUEUE_CALLED',
            '🔔 Nomor Antrean Dipanggil!',
            "Nomor antrean $nomorAntrean dipanggil. Silakan menuju loket pelayanan.",
            "/antrean"
        );
    }

    /**
     * Notifikasi dokumen siap diambil
     */
    public static function documentReady($userId, $permohonanId)
    {
        return self::send(
            $userId,
            'DOCUMENT_READY',
            'Dokumen Siap Diambil',
            "Dokumen Anda sudah siap dan dapat diambil di kantor.",
            "/permohonan/$permohonanId"
        );
    }

    /**
     * Broadcast ke banyak user
     */
    public static function broadcast(array $userIds, $type, $title, $message, $linkUrl = null)
    {
        $success = 0;
        foreach ($userIds as $userId) {
            if (self::send($userId, $type, $title, $message, $linkUrl)) {
                $success++;
            }
        }
        return $success;
    }

    /**
     * Broadcast ke user dengan role tertentu
     */
    public static function broadcastToRole($role, $type, $title, $message, $linkUrl = null)
    {
        $userIds = DB::table('users')
            ->where('role', $role)
            ->pluck('id')
            ->toArray();

        return self::broadcast($userIds, $type, $title, $message, $linkUrl);
    }

    /**
     * Get jumlah notifikasi belum dibaca
     */
    public static function getUnreadCount($userId)
    {
        $result = DB::select('SELECT count_unread(?) as count', [$userId]);
        return $result[0]->count ?? 0;
    }
}
