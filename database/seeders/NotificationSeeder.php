<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pastikan ada user di database
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($users)) {
            $this->command->warn('Tidak ada user di database. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Hapus notifikasi lama (opsional)
        // DB::table('notifications')->truncate();

        $this->command->info('Membuat sample notifikasi...');

        // Array tipe notifikasi
        $notificationTypes = [
            'NEW_REQUEST' => [
                'title' => 'Permohonan Surat Baru',
                'message' => 'Permohonan surat keterangan Anda telah diterima dan akan segera diproses.',
                'link_url' => '/permohonan/1'
            ],
            'REQUEST_STATUS' => [
                'title' => 'Status Permohonan Diperbarui',
                'message' => 'Permohonan Anda telah diverifikasi dan sedang dalam proses.',
                'link_url' => '/permohonan/1'
            ],
            'QUEUE_CALLED' => [
                'title' => 'Nomor Antrean Dipanggil',
                'message' => 'Nomor antrean A001 telah dipanggil. Silakan menuju loket pelayanan.',
                'link_url' => '/antrean'
            ],
            'DOCUMENT_READY' => [
                'title' => 'Dokumen Siap Diambil',
                'message' => 'Surat keterangan Anda sudah selesai dan dapat diambil di kantor.',
                'link_url' => '/permohonan/1'
            ],
            'ANNOUNCEMENT' => [
                'title' => 'Pengumuman Penting',
                'message' => 'Layanan akan tutup pada tanggal 17 Agustus 2024 dalam rangka peringatan HUT RI.',
                'link_url' => null
            ],
            'SYSTEM' => [
                'title' => 'Pembaruan Sistem',
                'message' => 'Sistem akan maintenance pada hari Minggu, 20 Oktober 2024 pukul 22.00 - 24.00 WIB.',
                'link_url' => null
            ],
            'WARNING' => [
                'title' => 'Peringatan Kelengkapan Dokumen',
                'message' => 'Dokumen Anda belum lengkap. Harap segera melengkapi untuk proses lebih lanjut.',
                'link_url' => '/permohonan/1'
            ],
        ];

        // Buat notifikasi untuk setiap user
        foreach ($users as $userId) {
            $count = 0;

            foreach ($notificationTypes as $type => $data) {
                // Random: buat 2-3 notifikasi per tipe
                $numNotifications = rand(2, 3);

                for ($i = 0; $i < $numNotifications; $i++) {
                    $isRead = rand(0, 1); // Random read/unread
                    $createdAt = Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23));

                    DB::table('notifications')->insert([
                        'user_id' => $userId,
                        'type' => $type,
                        'title' => $data['title'],
                        'message' => $data['message'],
                        'link_url' => $data['link_url'],
                        'is_read' => $isRead,
                        'read_at' => $isRead ? $createdAt->copy()->addMinutes(rand(5, 120)) : null,
                        'created_at' => $createdAt,
                    ]);

                    $count++;
                }
            }

            $this->command->info("✓ Dibuat $count notifikasi untuk User ID: $userId");
        }

        // Buat notifikasi khusus dengan variasi data
        $this->createSpecialNotifications($users);

        $total = DB::table('notifications')->count();
        $this->command->info("✅ Selesai! Total $total notifikasi berhasil dibuat.");
    }

    /**
     * Buat notifikasi khusus dengan variasi konten
     */
    private function createSpecialNotifications($users)
    {
        $specialNotifications = [
            // Notifikasi Permohonan
            [
                'type' => 'NEW_REQUEST',
                'title' => 'Permohonan Surat Keterangan Tidak Mampu',
                'message' => 'Permohonan SKTM atas nama Budi Santoso telah masuk dan menunggu verifikasi.',
                'link_url' => '/permohonan/2',
            ],
            [
                'type' => 'NEW_REQUEST',
                'title' => 'Permohonan Surat Keterangan Domisili',
                'message' => 'Permohonan surat domisili untuk keperluan pembuatan KTP telah diterima.',
                'link_url' => '/permohonan/3',
            ],

            // Notifikasi Status
            [
                'type' => 'REQUEST_STATUS',
                'title' => '✅ Permohonan Disetujui',
                'message' => 'Selamat! Permohonan surat keterangan Anda telah disetujui dan siap diproses.',
                'link_url' => '/permohonan/4',
            ],
            [
                'type' => 'REQUEST_STATUS',
                'title' => '❌ Permohonan Ditolak',
                'message' => 'Mohon maaf, permohonan Anda ditolak karena dokumen tidak lengkap. Silakan ajukan ulang.',
                'link_url' => '/permohonan/5',
            ],

            // Notifikasi Antrean
            [
                'type' => 'QUEUE_CALLED',
                'title' => '🔔 Antrean B015 Dipanggil',
                'message' => 'Nomor antrean B015 untuk layanan surat keterangan dipanggil. Menuju Loket 2.',
                'link_url' => '/antrean',
            ],
            [
                'type' => 'QUEUE_REMINDER',
                'title' => '⏰ Pengingat Jadwal Antrean',
                'message' => 'Jadwal antrean Anda besok pukul 09.00 WIB. Jangan lupa membawa dokumen asli.',
                'link_url' => '/antrean',
            ],

            // Notifikasi Dokumen
            [
                'type' => 'DOCUMENT_READY',
                'title' => '📄 Surat Keterangan Siap',
                'message' => 'Surat Keterangan Tidak Mampu Anda sudah selesai. Dapat diambil mulai hari ini.',
                'link_url' => '/permohonan/6',
            ],
            [
                'type' => 'DOCUMENT_READY',
                'title' => '📋 Dokumen Telah Dicetak',
                'message' => 'Dokumen Anda telah selesai dicetak dan siap untuk diambil di kantor kelurahan.',
                'link_url' => '/permohonan/7',
            ],

            // Notifikasi Pengumuman
            [
                'type' => 'ANNOUNCEMENT',
                'title' => '📢 Libur Nasional',
                'message' => 'Kantor akan tutup pada tanggal 25 Desember 2024 dalam rangka libur Natal.',
                'link_url' => null,
            ],
            [
                'type' => 'ANNOUNCEMENT',
                'title' => '🎉 Fitur Baru Tersedia',
                'message' => 'Sekarang Anda dapat mengajukan permohonan surat secara online tanpa harus datang ke kantor!',
                'link_url' => '/features',
            ],

            // Notifikasi Sistem
            [
                'type' => 'SYSTEM',
                'title' => '🔧 Maintenance Terjadwal',
                'message' => 'Sistem akan offline untuk maintenance pada Minggu, 15 Oktober 2024, 00.00-04.00 WIB.',
                'link_url' => null,
            ],
            [
                'type' => 'SYSTEM',
                'title' => '✨ Update Keamanan',
                'message' => 'Sistem keamanan telah diperbarui. Silakan logout dan login kembali untuk keamanan maksimal.',
                'link_url' => null,
            ],

            // Notifikasi Peringatan
            [
                'type' => 'WARNING',
                'title' => '⚠️ Dokumen Kadaluarsa',
                'message' => 'KTP Anda akan kadaluarsa dalam 30 hari. Segera lakukan perpanjangan.',
                'link_url' => '/profile',
            ],
            [
                'type' => 'WARNING',
                'title' => '⚠️ Data Tidak Lengkap',
                'message' => 'Data profile Anda belum lengkap. Lengkapi data untuk akses penuh.',
                'link_url' => '/profile/edit',
            ],

            // Notifikasi Feedback
            [
                'type' => 'FEEDBACK_RESPONSE',
                'title' => '💬 Tanggapan dari Admin',
                'message' => 'Admin telah menanggapi kritik dan saran Anda. Terima kasih atas masukan yang berharga.',
                'link_url' => '/feedback/1',
            ],
            [
                'type' => 'FEEDBACK_NEW',
                'title' => '📝 Kritik & Saran Baru',
                'message' => 'Anda memiliki kritik dan saran baru dari warga. Mohon segera ditanggapi.',
                'link_url' => '/feedback/2',
            ],
        ];

        foreach ($users as $userId) {
            // Ambil random 5-8 notifikasi khusus untuk setiap user
            $selectedNotifications = collect($specialNotifications)
                ->random(rand(5, 8));

            foreach ($selectedNotifications as $notif) {
                $isRead = rand(0, 1);
                $createdAt = Carbon::now()->subDays(rand(0, 14))->subHours(rand(0, 23));

                DB::table('notifications')->insert([
                    'user_id' => $userId,
                    'type' => $notif['type'],
                    'title' => $notif['title'],
                    'message' => $notif['message'],
                    'link_url' => $notif['link_url'],
                    'is_read' => $isRead,
                    'read_at' => $isRead ? $createdAt->copy()->addMinutes(rand(5, 120)) : null,
                    'created_at' => $createdAt,
                ]);
            }
        }
    }
}
