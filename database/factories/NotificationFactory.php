<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $types = [
            'NEW_REQUEST',
            'REQUEST_STATUS',
            'QUEUE_CALLED',
            'DOCUMENT_READY',
            'ANNOUNCEMENT',
            'SYSTEM',
            'WARNING',
            'FEEDBACK_NEW',
            'FEEDBACK_RESPONSE',
        ];

        $type = $this->faker->randomElement($types);
        $isRead = $this->faker->boolean(40);

        // Gunakan format string eksplisit untuk MySQL
        $createdAt = $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s');
        $readAt = $isRead ? $this->faker->dateTimeBetween('-7 days', 'now')->format('Y-m-d H:i:s') : null;

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'type' => $type,
            'title' => $this->generateTitle($type),
            'message' => $this->generateMessage($type),
            'link_url' => $this->faker->boolean(60) ? $this->generateLink($type) : null,
            'is_read' => $isRead,
            'read_at' => $readAt,
            'created_at' => $createdAt,
        ];
    }

    /**
     * Generate title berdasarkan tipe
     */
    private function generateTitle($type)
    {
        $titles = [
            'NEW_REQUEST' => [
                'Permohonan Surat Baru',
                'Pengajuan Dokumen Masuk',
                'Permohonan Baru Diterima',
            ],
            'REQUEST_STATUS' => [
                'Status Permohonan Diperbarui',
                'Permohonan Disetujui',
                'Permohonan Ditolak',
                'Permohonan Dalam Proses',
            ],
            'QUEUE_CALLED' => [
                'Nomor Antrean Dipanggil',
                'Giliran Anda Tiba',
                'Antrean Aktif',
            ],
            'DOCUMENT_READY' => [
                'Dokumen Siap Diambil',
                'Surat Telah Selesai',
                'Dokumen Telah Dicetak',
            ],
            'ANNOUNCEMENT' => [
                'Pengumuman Penting',
                'Informasi Terbaru',
                'Pemberitahuan Resmi',
            ],
            'SYSTEM' => [
                'Pembaruan Sistem',
                'Maintenance Terjadwal',
                'Update Keamanan',
            ],
            'WARNING' => [
                'Peringatan Penting',
                'Perhatian Diperlukan',
                'Tindakan Diperlukan',
            ],
            'FEEDBACK_NEW' => [
                'Kritik & Saran Baru',
                'Masukan dari Warga',
                'Feedback Masuk',
            ],
            'FEEDBACK_RESPONSE' => [
                'Tanggapan dari Admin',
                'Balasan Feedback Anda',
                'Respon Kritik & Saran',
            ],
        ];

        return $this->faker->randomElement($titles[$type] ?? ['Notifikasi Baru']);
    }

    /**
     * Generate message berdasarkan tipe
     */
    private function generateMessage($type)
    {
        $messages = [
            'NEW_REQUEST' => [
                'Permohonan surat keterangan Anda telah diterima dan akan segera diproses.',
                'Pengajuan dokumen berhasil masuk ke sistem dan menunggu verifikasi.',
                'Permohonan Anda telah terdaftar dengan nomor registrasi ' . strtoupper($this->faker->bothify('??###')),
            ],
            'REQUEST_STATUS' => [
                'Status permohonan Anda telah diperbarui. Silakan cek detail untuk informasi lebih lanjut.',
                'Permohonan Anda telah disetujui dan siap untuk diproses lebih lanjut.',
                'Mohon maaf, permohonan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
            ],
            'QUEUE_CALLED' => [
                'Nomor antrean ' . strtoupper($this->faker->bothify('?###')) . ' dipanggil. Silakan menuju loket pelayanan.',
                'Giliran Anda telah tiba. Mohon segera menuju loket yang telah ditentukan.',
                'Antrean Anda aktif. Harap segera ke loket untuk dilayani.',
            ],
            'DOCUMENT_READY' => [
                'Dokumen Anda sudah selesai dan siap diambil di kantor.',
                'Surat keterangan telah selesai dicetak dan dapat diambil mulai hari ini.',
                'Dokumen Anda telah selesai diproses. Silakan datang untuk pengambilan.',
            ],
            'ANNOUNCEMENT' => [
                $this->faker->sentence(12),
                'Layanan akan tutup pada ' . $this->faker->date('d F Y') . ' dalam rangka libur nasional.',
                'Fitur baru telah tersedia! Cek sekarang untuk kemudahan layanan.',
            ],
            'SYSTEM' => [
                'Sistem akan maintenance pada ' . $this->faker->dateTimeBetween('+1 day', '+7 days')->format('d F Y, H:i') . ' WIB.',
                'Update keamanan telah diterapkan. Silakan logout dan login kembali.',
                'Pembaruan sistem berhasil dilakukan. Beberapa fitur baru telah ditambahkan.',
            ],
            'WARNING' => [
                'Dokumen Anda akan kadaluarsa dalam ' . rand(7, 30) . ' hari. Segera lakukan perpanjangan.',
                'Data profil Anda belum lengkap. Mohon lengkapi untuk akses penuh layanan.',
                'Terdapat ketidaksesuaian data. Silakan hubungi admin untuk klarifikasi.',
            ],
            'FEEDBACK_NEW' => [
                'Anda memiliki kritik dan saran baru dari warga. Mohon segera ditanggapi.',
                'Masukan baru telah diterima. Silakan tinjau dan berikan tanggapan.',
                'Feedback dari pengguna memerlukan perhatian Anda.',
            ],
            'FEEDBACK_RESPONSE' => [
                'Admin telah menanggapi kritik dan saran Anda. Terima kasih atas masukan yang berharga.',
                'Tanggapan terhadap feedback Anda telah diberikan. Silakan cek untuk detail.',
                'Terima kasih atas masukan Anda. Admin telah memberikan respon.',
            ],
        ];

        return $this->faker->randomElement($messages[$type] ?? [$this->faker->sentence(15)]);
    }

    /**
     * Generate link berdasarkan tipe
     */
    private function generateLink($type)
    {
        $links = [
            'NEW_REQUEST' => '/permohonan/' . rand(1, 100),
            'REQUEST_STATUS' => '/permohonan/' . rand(1, 100),
            'QUEUE_CALLED' => '/antrean',
            'DOCUMENT_READY' => '/permohonan/' . rand(1, 100),
            'ANNOUNCEMENT' => '/announcements',
            'SYSTEM' => null,
            'WARNING' => '/profile',
            'FEEDBACK_NEW' => '/feedback/' . rand(1, 50),
            'FEEDBACK_RESPONSE' => '/feedback/' . rand(1, 50),
        ];

        return $links[$type] ?? null;
    }

    /**
     * State untuk notifikasi yang belum dibaca
     */
    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => 0,
                'read_at' => null,
            ];
        });
    }

    /**
     * State untuk notifikasi yang sudah dibaca
     */
    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => 1,
                'read_at' => $this->faker->dateTimeBetween('-7 days', 'now')->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * State untuk notifikasi dengan prioritas tinggi
     */
    public function urgent()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'WARNING',
                'title' => '⚠️ ' . $this->generateTitle('WARNING'),
            ];
        });
    }
}
