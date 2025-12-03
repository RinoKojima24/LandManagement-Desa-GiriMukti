<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LahanSeeder extends Seeder
{
    public function run()
    {

        // 1. Seeder Wilayah Administratif
        DB::table('wilayah_administratif')->insert([
            [
                'nama_wilayah' => 'Desa Samarinda Ulu',
                'kode_wilayah' => 'DSA001',
                'jenis_wilayah' => 'desa',
                'wilayah_induk_id' => null,
                'luas_total' => 1250.50,
                'koordinat_batas' => '-0.5021,117.1526;-0.5045,117.1598;-0.5087,117.1565;-0.5063,117.1493',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Dusun Makmur',
                'kode_wilayah' => 'DUS001',
                'jenis_wilayah' => 'dusun',
                'wilayah_induk_id' => 1,
                'luas_total' => 425.75,
                'koordinat_batas' => '-0.5021,117.1526;-0.5032,117.1555;-0.5041,117.1542;-0.5030,117.1513',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Dusun Sejahtera',
                'kode_wilayah' => 'DUS002',
                'jenis_wilayah' => 'dusun',
                'wilayah_induk_id' => 1,
                'luas_total' => 380.25,
                'koordinat_batas' => '-0.5045,117.1598;-0.5055,117.1625;-0.5068,117.1612;-0.5058,117.1585',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'RT 01',
                'kode_wilayah' => 'RT001',
                'jenis_wilayah' => 'rt',
                'wilayah_induk_id' => 2,
                'luas_total' => 125.50,
                'koordinat_batas' => '-0.5021,117.1526;-0.5026,117.1540;-0.5032,117.1535;-0.5027,117.1521',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'RT 02',
                'kode_wilayah' => 'RT002',
                'jenis_wilayah' => 'rt',
                'wilayah_induk_id' => 2,
                'luas_total' => 130.25,
                'koordinat_batas' => '-0.5026,117.1540;-0.5032,117.1555;-0.5038,117.1550;-0.5032,117.1535',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 2. Seeder Jenis Tanah
        DB::table('jenis_tanah')->insert([
            [
                'nama_jenis' => 'Tanah Pertanian',
                'kode_jenis' => 'TANI',
                'deskripsi' => 'Tanah yang digunakan untuk kegiatan pertanian seperti sawah, ladang, dan kebun',
                'created_at' => now()
            ],
            [
                'nama_jenis' => 'Tanah Pemukiman',
                'kode_jenis' => 'MUKIM',
                'deskripsi' => 'Tanah yang digunakan untuk permukiman dan perumahan',
                'created_at' => now()
            ],
            [
                'nama_jenis' => 'Tanah Komersial',
                'kode_jenis' => 'KOMERS',
                'deskripsi' => 'Tanah yang digunakan untuk kegiatan perdagangan dan bisnis',
                'created_at' => now()
            ],
            [
                'nama_jenis' => 'Tanah Industri',
                'kode_jenis' => 'INDUST',
                'deskripsi' => 'Tanah yang digunakan untuk kegiatan industri dan manufaktur',
                'created_at' => now()
            ],
            [
                'nama_jenis' => 'Tanah Fasilitas Umum',
                'kode_jenis' => 'FASUM',
                'deskripsi' => 'Tanah untuk fasilitas umum seperti sekolah, rumah sakit, tempat ibadah',
                'created_at' => now()
            ]
        ]);

        // 3. Seeder Status Kepemilikan
        DB::table('status_kepemilikan')->insert([
            [
                'nama_status' => 'Hak Milik',
                'kode_status' => 'HM',
                'deskripsi' => 'Hak turun temurun yang terkuat dan terpenuh yang dapat dipunyai orang atas tanah',
                'created_at' => now()
            ],
            [
                'nama_status' => 'Hak Guna Bangunan',
                'kode_status' => 'HGB',
                'deskripsi' => 'Hak untuk mendirikan dan mempunyai bangunan-bangunan atas tanah',
                'created_at' => now()
            ],
            [
                'nama_status' => 'Hak Guna Usaha',
                'kode_status' => 'HGU',
                'deskripsi' => 'Hak untuk mengusahakan tanah yang dikuasai langsung oleh negara',
                'created_at' => now()
            ],
            [
                'nama_status' => 'Hak Pakai',
                'kode_status' => 'HP',
                'deskripsi' => 'Hak untuk menggunakan dan/atau memungut hasil dari tanah',
                'created_at' => now()
            ],
            [
                'nama_status' => 'Tanah Adat',
                'kode_status' => 'ADAT',
                'deskripsi' => 'Tanah yang dimiliki berdasarkan hukum adat setempat',
                'created_at' => now()
            ]
        ]);

        // 4. Seeder Penduduk
        DB::table('penduduk')->insert([
            [
                'nik' => '6472010101850001',
                'nama_lengkap' => 'Budi Santoso',
                'tempat_lahir' => 'Samarinda',
                'tanggal_lahir' => '1985-01-01',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Mawar No. 15, RT 01',
                'rt_rw' => '01/02',
                'id_wilayah' => 4,
                'no_telepon' => '081234567891',
                'pekerjaan' => 'Petani',
                'status_perkawinan' => 'kawin',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'SMA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '6472010202900002',
                'nama_lengkap' => 'Siti Aminah',
                'tempat_lahir' => 'Samarinda',
                'tanggal_lahir' => '1990-02-02',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Melati No. 8, RT 02',
                'rt_rw' => '02/02',
                'id_wilayah' => 5,
                'no_telepon' => '081234567892',
                'pekerjaan' => 'Guru',
                'status_perkawinan' => 'kawin',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'S1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '6472010303880003',
                'nama_lengkap' => 'Ahmad Hidayat',
                'tempat_lahir' => 'Tenggarong',
                'tanggal_lahir' => '1988-03-03',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Kenanga No. 22, RT 01',
                'rt_rw' => '01/02',
                'id_wilayah' => 4,
                'no_telepon' => '081234567893',
                'pekerjaan' => 'Pedagang',
                'status_perkawinan' => 'belum_kawin',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'SMA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '6472010404920004',
                'nama_lengkap' => 'Rina Sari',
                'tempat_lahir' => 'Balikpapan',
                'tanggal_lahir' => '1992-04-04',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Anggrek No. 12, RT 02',
                'rt_rw' => '02/02',
                'id_wilayah' => 5,
                'no_telepon' => '081234567894',
                'pekerjaan' => 'Wiraswasta',
                'status_perkawinan' => 'kawin',
                'agama' => 'Kristen',
                'pendidikan_terakhir' => 'D3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '6472010505870005',
                'nama_lengkap' => 'Joko Widodo',
                'tempat_lahir' => 'Kutai Kartanegara',
                'tanggal_lahir' => '1987-05-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Cempaka No. 18, RT 01',
                'rt_rw' => '01/02',
                'id_wilayah' => 4,
                'no_telepon' => '081234567895',
                'pekerjaan' => 'Buruh',
                'status_perkawinan' => 'cerai_hidup',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'SMP',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        // 6. Seeder Bidang Tanah
        DB::table('bidang_tanah')->insert([
            [
                'nomor_bidang' => 'BT-001-2024',
                'nib' => 'NIB001234567890',
                'luas_tanah' => 500.00,
                'id_jenis_tanah' => 1,
                'id_status_kepemilikan' => 1,
                'id_wilayah' => 4,
                'alamat_tanah' => 'Blok A, Dusun Makmur, RT 01',
                'koordinat_tanah' => '-0.5025,117.1530',
                'batas_utara' => 'Jalan Kampung',
                'batas_selatan' => 'Sungai Kecil',
                'batas_timur' => 'Tanah Sdr. Ahmad',
                'batas_barat' => 'Tanah Sdr. Siti',
                'keterangan' => 'Tanah sawah subur dengan irigasi teknis',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nomor_bidang' => 'BT-002-2024',
                'nib' => 'NIB001234567891',
                'luas_tanah' => 300.00,
                'id_jenis_tanah' => 2,
                'id_status_kepemilikan' => 1,
                'id_wilayah' => 5,
                'alamat_tanah' => 'Blok B, Dusun Makmur, RT 02',
                'koordinat_tanah' => '-0.5030,117.1545',
                'batas_utara' => 'Tanah Kas Desa',
                'batas_selatan' => 'Jalan Lingkungan',
                'batas_timur' => 'Parit',
                'batas_barat' => 'Tanah Sdr. Budi',
                'keterangan' => 'Tanah untuk rumah tinggal',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nomor_bidang' => 'BT-003-2024',
                'nib' => 'NIB001234567892',
                'luas_tanah' => 750.00,
                'id_jenis_tanah' => 3,
                'id_status_kepemilikan' => 2,
                'id_wilayah' => 3,
                'alamat_tanah' => 'Blok C, Dusun Sejahtera',
                'koordinat_tanah' => '-0.5055,117.1610',
                'batas_utara' => 'Jalan Raya',
                'batas_selatan' => 'Kebun Kelapa',
                'batas_timur' => 'Sungai Besar',
                'batas_barat' => 'Tanah Negara',
                'keterangan' => 'Tanah untuk usaha warung dan toko',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nomor_bidang' => 'BT-004-2024',
                'nib' => 'NIB001234567893',
                'luas_tanah' => 200.00,
                'id_jenis_tanah' => 2,
                'id_status_kepemilikan' => 1,
                'id_wilayah' => 5,
                'alamat_tanah' => 'Blok D, Dusun Makmur, RT 02',
                'koordinat_tanah' => '-0.5035,117.1548',
                'batas_utara' => 'Jalan Setapak',
                'batas_selatan' => 'Kebun Tetangga',
                'batas_timur' => 'Tanah Sdr. Rina',
                'batas_barat' => 'Parit Kecil',
                'keterangan' => 'Tanah pekarangan untuk rumah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nomor_bidang' => 'BT-005-2024',
                'nib' => 'NIB001234567894',
                'luas_tanah' => 1200.00,
                'id_jenis_tanah' => 1,
                'id_status_kepemilikan' => 3,
                'id_wilayah' => 2,
                'alamat_tanah' => 'Blok E, Dusun Makmur',
                'koordinat_tanah' => '-0.5028,117.1535',
                'batas_utara' => 'Hutan Lindung',
                'batas_selatan' => 'Jalan Tani',
                'batas_timur' => 'Sungai Irigasi',
                'batas_barat' => 'Tanah HGU PT. Kelapa Sawit',
                'keterangan' => 'Tanah perkebunan kelapa sawit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('kepemilikan_tanah')->insert([
            [
                'id_bidang_tanah' => 1,
                'id_penduduk' => 1,
                'jenis_kepemilikan' => 'pemilik',
                'persentase_kepemilikan' => 100.00,
                'tanggal_mulai' => '2020-01-15',
                'tanggal_berakhir' => null,
                'nomor_sertifikat' => 'SHM-001/2020',
                'jenis_sertifikat' => 'SHM',
                'is_active' => true,
                'keterangan' => 'Kepemilikan penuh atas tanah sawah',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 2,
                'id_penduduk' => 2,
                'jenis_kepemilikan' => 'pemilik',
                'persentase_kepemilikan' => 100.00,
                'tanggal_mulai' => '2021-03-20',
                'tanggal_berakhir' => null,
                'nomor_sertifikat' => 'SHM-002/2021',
                'jenis_sertifikat' => 'SHM',
                'is_active' => true,
                'keterangan' => 'Kepemilikan penuh atas tanah pekarangan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 3,
                'id_penduduk' => 3,
                'jenis_kepemilikan' => 'penyewa',
                'persentase_kepemilikan' => 100.00,
                'tanggal_mulai' => '2023-01-01',
                'tanggal_berakhir' => '2025-12-31',
                'nomor_sertifikat' => null,
                'jenis_sertifikat' => null,
                'is_active' => true,
                'keterangan' => 'Sewa tanah untuk usaha 3 tahun',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 4,
                'id_penduduk' => 4,
                'jenis_kepemilikan' => 'pemilik',
                'persentase_kepemilikan' => 100.00,
                'tanggal_mulai' => '2022-06-10',
                'tanggal_berakhir' => null,
                'nomor_sertifikat' => 'SHM-003/2022',
                'jenis_sertifikat' => 'SHM',
                'is_active' => true,
                'keterangan' => 'Kepemilikan penuh hasil pembelian',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 5,
                'id_penduduk' => 5,
                'jenis_kepemilikan' => 'penggarap',
                'persentase_kepemilikan' => 100.00,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_berakhir' => '2028-07-01',
                'nomor_sertifikat' => null,
                'jenis_sertifikat' => null,
                'is_active' => true,
                'keterangan' => 'Hak garap untuk perkebunan kelapa sawit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 1,
                'id_penduduk' => 2,
                'jenis_kepemilikan' => 'ahli_waris',
                'persentase_kepemilikan' => 25.00,
                'tanggal_mulai' => '2024-01-01',
                'tanggal_berakhir' => null,
                'nomor_sertifikat' => null,
                'jenis_sertifikat' => null,
                'is_active' => false,
                'keterangan' => 'Warisan dari orangtua, sudah dialihkan ke Budi',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        // 8. Seeder Penggunaan Tanah
        DB::table('penggunaan_tanah')->insert([
            [
                'id_bidang_tanah' => 1,
                'jenis_penggunaan' => 'pertanian',
                'luas_penggunaan' => 500.00,
                'deskripsi_penggunaan' => 'Sawah padi dengan 2 kali tanam per tahun',
                'tanggal_mulai' => '2020-01-15',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 2,
                'jenis_penggunaan' => 'pemukiman',
                'luas_penggunaan' => 250.00,
                'deskripsi_penggunaan' => 'Rumah tinggal dengan halaman',
                'tanggal_mulai' => '2021-03-20',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 2,
                'jenis_penggunaan' => 'taman',
                'luas_penggunaan' => 50.00,
                'deskripsi_penggunaan' => 'Taman keluarga di bagian belakang rumah',
                'tanggal_mulai' => '2021-03-20',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 3,
                'jenis_penggunaan' => 'perdagangan',
                'luas_penggunaan' => 750.00,
                'deskripsi_penggunaan' => 'Kompleks pertokoan dan warung makan',
                'tanggal_mulai' => '2023-01-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 4,
                'jenis_penggunaan' => 'pemukiman',
                'luas_penggunaan' => 150.00,
                'deskripsi_penggunaan' => 'Rumah tinggal sederhana',
                'tanggal_mulai' => '2022-06-10',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 4,
                'jenis_penggunaan' => 'pertanian',
                'luas_penggunaan' => 50.00,
                'deskripsi_penggunaan' => 'Kebun sayuran untuk konsumsi rumah tangga',
                'tanggal_mulai' => '2022-06-10',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_bidang_tanah' => 5,
                'jenis_penggunaan' => 'perkebunan',
                'luas_penggunaan' => 1200.00,
                'deskripsi_penggunaan' => 'Perkebunan kelapa sawit dengan umur tanaman 8 tahun',
                'tanggal_mulai' => '2023-07-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
