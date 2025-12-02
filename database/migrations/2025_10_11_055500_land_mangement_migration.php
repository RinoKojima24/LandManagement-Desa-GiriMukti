<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('nama_petugas', 100);
            $table->string('no_telepon', 20)->nullable();
            $table->enum('role', ['super_admin', 'admin', 'operator', 'viewer', 'warga']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // 2. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // 3. Posts Table
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 4. Tags Table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        // 5. Jenis Surat Table
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id('id_jenis_surat');
            $table->enum('jenis_surat', ['permohonan', 'keterangan'])->nullable();
            $table->string('name')->nullable();
            $table->string('kode_jenis', 20)->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // 6. Surat Keterangan Table
        Schema::create('surat_keterangan', function (Blueprint $table) {
            $table->id('id_permohonan');
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('no_wa', 16);

            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('keperluan')->nullable()->comment('Hanya di gunakan untuk jenis surat_keterangan');
            $table->string('alamat', 244)->nullable();
            $table->string('ktp')->nullable();
            $table->string('dokumen_pendukung')->nullable();
            // $table->unsignedInteger('id_jenis_surat')->nullable();
            $table->enum('status', ['pending', 'verifikasi', 'reject'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('nik', 'idx_penduduk_nik');
            // $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('jenis_surat');

            $table->unsignedBigInteger('id_jenis_surat')->nullable();
            // $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('jenis_surat');
        });

        // 7. Surat Permohonan Table
        Schema::create('surat_permohonan', function (Blueprint $table) {
            $table->id('id_permohonan');
            $table->string('nik', 16);
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('alamat', 244);
            $table->string('ktp')->nullable();
            $table->string('dokumen_pendukung')->nullable();
            // $table->unsignedInteger('id_jenis_surat')->nullable();
            $table->enum('status', ['pending', 'verifikasi', 'reject'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();

            // $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('jenis_surat');

            $table->unsignedBigInteger('id_jenis_surat')->nullable();
            // $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('jenis_surat');

        });

        // 8. Wilayah Administratif Table
        Schema::create('wilayah_administratif', function (Blueprint $table) {
            $table->id('id_wilayah');
            $table->string('nama_wilayah', 100);
            $table->string('kode_wilayah', 20)->unique();
            $table->enum('jenis_wilayah', ['desa', 'dusun', 'rt', 'rw']);
            $table->unsignedBigInteger('wilayah_induk_id')->nullable();
            $table->decimal('luas_total', 10, 2)->nullable();
            $table->text('koordinat_batas')->nullable();
            $table->timestamps();
        });

        Schema::create('penduduk', function (Blueprint $table) {
            $table->id('id_penduduk');
            $table->string('nik', 100);
            $table->string('nama_lengkap');
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin', 5);
            $table->text('alamat');
            $table->string('rt_rw', 80);
            $table->unsignedBigInteger('id_wilayah');
            $table->string('no_telepon', 100);
            $table->string('pekerjaan', 100);
            $table->string('status_perkawinan', 100);
            $table->string('agama', 100);
            $table->string('pendidikan_terakhir', 100);

            $table->timestamps();
        });


        // 9. Jenis Tanah Table
        Schema::create('jenis_tanah', function (Blueprint $table) {
            $table->id('id_jenis_tanah');
            $table->string('nama_jenis', 50);
            $table->string('kode_jenis', 10)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // 10. Status Kepemilikan Table
        Schema::create('status_kepemilikan', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('nama_status', 50);
            $table->string('kode_status', 10)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // 11. Bidang Tanah Table
        Schema::create('bidang_tanah', function (Blueprint $table) {
            $table->id('id_bidang_tanah');
            $table->string('nomor_bidang', 50)->unique();
            $table->string('nib', 30)->nullable();
            $table->decimal('luas_tanah', 10, 2);
            $table->foreignId('id_jenis_tanah')->nullable()->constrained('jenis_tanah', 'id_jenis_tanah');
            $table->foreignId('id_status_kepemilikan')->nullable()->constrained('status_kepemilikan', 'id_status');
            $table->foreignId('id_wilayah')->nullable()->constrained('wilayah_administratif', 'id_wilayah');
            $table->text('alamat_tanah')->nullable();
            $table->text('koordinat_tanah')->nullable();
            $table->text('batas_utara')->nullable();
            $table->text('batas_selatan')->nullable();
            $table->text('batas_timur')->nullable();
            $table->text('batas_barat')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('id_wilayah', 'idx_bidang_tanah_wilayah');
            $table->index('id_jenis_tanah', 'idx_bidang_tanah_jenis');
        });

        // 12. Kepemilikan Tanah Table
        Schema::create('kepemilikan_tanah', function (Blueprint $table) {
            $table->id('id_kepemilikan');
            $table->foreignId('id_bidang_tanah')->constrained('bidang_tanah', 'id_bidang_tanah');
            $table->foreignId('id_penduduk')->constrained('surat_keterangan', 'id_permohonan');
            $table->enum('jenis_kepemilikan', ['pemilik', 'ahli_waris', 'penyewa', 'penggarap', 'sewa_pakai']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('nomor_sertifikat', 100)->nullable();
            $table->string('persentase_kepemilikan', 100)->nullable();

            $table->enum('jenis_sertifikat', ['SHM', 'SHGB', 'SHSRS', 'SHGU', 'girik', 'petok', 'lainnya'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->string('Nama_pemilik', 25)->nullable();
            $table->timestamps();

            $table->index('id_bidang_tanah', 'idx_kepemilikan_bidang');
            $table->index('id_penduduk', 'idx_kepemilikan_penduduk');
        });

        // 13. Penggunaan Tanah Table
        Schema::create('penggunaan_tanah', function (Blueprint $table) {
            $table->id('id_penggunaan');
            $table->foreignId('id_bidang_tanah')->constrained('bidang_tanah', 'id_bidang_tanah');
            $table->enum('jenis_penggunaan', [
                'pertanian', 'perkebunan', 'pemukiman', 'industri', 'perdagangan',
                'perkantoran', 'pendidikan', 'kesehatan', 'peribadatan', 'olahraga',
                'taman', 'kuburan', 'kosong', 'lainnya'
            ]);
            $table->decimal('luas_penggunaan', 10, 2);
            $table->text('deskripsi_penggunaan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 14. Layanan Table
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 100);
            $table->string('kode_layanan', 10)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('kode', 25)->nullable();
            $table->timestamps();
        });

        // 15. Antrean Table
        Schema::create('antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->string('nomor_antrean', 26)->unique();
            $table->date('tanggal');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->string('kode')->nullable();
            $table->timestamps();

            $table->index('user_id', 'fk_antrean_user');
            $table->index('layanan_id', 'fk_antrean_layanan');
        });

        // 16. Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Penerima notifikasi');
            $table->string('type', 30)->comment('Tipe notifikasi (REQUEST_STATUS, QUEUE_CALLED, dll)');
            $table->string('title')->comment('Judul notifikasi');
            $table->text('message')->comment('Isi pesan notifikasi');
            $table->string('link_url')->nullable()->comment('URL untuk detail/aksi');
            $table->boolean('is_read')->default(false)->comment('Status sudah dibaca (0=belum, 1=sudah)');
            $table->timestamp('read_at')->nullable()->comment('Waktu dibaca');
            $table->timestamp('created_at')->nullable();

            $table->index(['user_id', 'is_read'], 'idx_user_read');
            $table->index('created_at', 'idx_created');
        });

        // 17. Kritik Saran Table
        Schema::create('kritik_saran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nama', 100)->nullable();
            $table->enum('jenis', ['kritik', 'saran']);
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'ditanggapi'])->default('baru');
            $table->text('tanggapan')->nullable();
            $table->timestamps();
        });

        // Create Stored Procedures
        DB::unprepared('
            DROP PROCEDURE IF EXISTS create_notification;
            CREATE PROCEDURE create_notification(
              IN p_user_id BIGINT,
              IN p_type VARCHAR(30),
              IN p_title VARCHAR(255),
              IN p_message TEXT,
              IN p_link_url VARCHAR(255)
            )
            BEGIN
              INSERT INTO notifications (user_id, type, title, message, link_url)
              VALUES (p_user_id, p_type, p_title, p_message, p_link_url);
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS mark_as_read;
            CREATE PROCEDURE mark_as_read(
              IN p_notification_id BIGINT,
              IN p_user_id BIGINT
            )
            BEGIN
              UPDATE notifications
              SET is_read = 1,
                  read_at = CURRENT_TIMESTAMP
              WHERE id = p_notification_id
                AND user_id = p_user_id
                AND is_read = 0;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS mark_all_read;
            CREATE PROCEDURE mark_all_read(
              IN p_user_id BIGINT
            )
            BEGIN
              UPDATE notifications
              SET is_read = 1,
                  read_at = CURRENT_TIMESTAMP
              WHERE user_id = p_user_id
                AND is_read = 0;
            END
        ');

        // Create Function
        DB::unprepared('
            DROP FUNCTION IF EXISTS count_unread;
            CREATE FUNCTION count_unread(p_user_id BIGINT)
            RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
              DECLARE v_count INT;

              SELECT COUNT(*) INTO v_count
              FROM notifications
              WHERE user_id = p_user_id AND is_read = 0;

              RETURN v_count;
            END
        ');

        // Create Views
        DB::unprepared('
            CREATE OR REPLACE VIEW v_unread_notifications AS
            SELECT
                id,
                user_id,
                type,
                title,
                message,
                link_url,
                created_at,
                TIMESTAMPDIFF(MINUTE, created_at, NOW()) AS minutes_ago
            FROM notifications
            WHERE is_read = 0
            ORDER BY created_at DESC
        ');

        DB::unprepared('
            CREATE OR REPLACE VIEW v_notification_stats AS
            SELECT
                u.id AS user_id,
                u.username,
                COUNT(n.id) AS total,
                SUM(CASE WHEN n.is_read = 0 THEN 1 ELSE 0 END) AS unread,
                MAX(n.created_at) AS last_notification
            FROM users u
            LEFT JOIN notifications n ON u.id = n.user_id
            GROUP BY u.id, u.username
        ');

        // Create Triggers
        DB::unprepared('
            DROP TRIGGER IF EXISTS notify_queue_called;
            CREATE TRIGGER notify_queue_called
            AFTER UPDATE ON antrean
            FOR EACH ROW
            BEGIN
              IF OLD.status != "dipanggil" AND NEW.status = "dipanggil" THEN
                CALL create_notification(
                  NEW.user_id,
                  "QUEUE_CALLED",
                  "Nomor Antrean Dipanggil!",
                  CONCAT("Nomor antrean ", NEW.nomor_antrean, " dipanggil. Silakan ke loket."),
                  "/antrean"
                );
              END IF;
            END
        ');

        DB::unprepared('
            DROP TRIGGER IF EXISTS notify_surat_status_change;
            CREATE TRIGGER notify_surat_status_change
            AFTER UPDATE ON surat_keterangan
            FOR EACH ROW
            BEGIN
              DECLARE v_user_id BIGINT;
              DECLARE v_title VARCHAR(255);
              DECLARE v_message TEXT;

              IF OLD.status != NEW.status THEN
                SET v_user_id = 1;

                CASE NEW.status
                  WHEN "verifikasi" THEN
                    SET v_title = "Permohonan Diverifikasi";
                    SET v_message = "Permohonan surat keterangan Anda sedang diverifikasi.";
                  WHEN "selesai" THEN
                    SET v_title = "Permohonan Selesai";
                    SET v_message = "Surat keterangan Anda sudah selesai dan siap diambil.";
                  WHEN "reject" THEN
                    SET v_title = "Permohonan Ditolak";
                    SET v_message = "Permohonan Anda ditolak. Hubungi admin untuk info lebih lanjut.";
                  ELSE
                    SET v_title = "Status Berubah";
                    SET v_message = CONCAT("Status permohonan: ", NEW.status);
                END CASE;

                CALL create_notification(
                  v_user_id,
                  "REQUEST_STATUS",
                  v_title,
                  v_message,
                  CONCAT("/permohonan/", NEW.id_permohonan)
                );
              END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS notify_surat_status_change');
        DB::unprepared('DROP TRIGGER IF EXISTS notify_queue_called');

        // Drop views
        DB::unprepared('DROP VIEW IF EXISTS v_notification_stats');
        DB::unprepared('DROP VIEW IF EXISTS v_unread_notifications');

        // Drop function
        DB::unprepared('DROP FUNCTION IF EXISTS count_unread');

        // Drop procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS mark_all_read');
        DB::unprepared('DROP PROCEDURE IF EXISTS mark_as_read');
        DB::unprepared('DROP PROCEDURE IF EXISTS create_notification');

        // Drop tables in reverse order
        Schema::dropIfExists('kritik_saran');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('antrean');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('penggunaan_tanah');
        Schema::dropIfExists('kepemilikan_tanah');
        Schema::dropIfExists('bidang_tanah');
        Schema::dropIfExists('status_kepemilikan');
        Schema::dropIfExists('jenis_tanah');
        Schema::dropIfExists('wilayah_administratif');
        Schema::dropIfExists('surat_permohonan');
        Schema::dropIfExists('surat_keterangan');
        Schema::dropIfExists('jenis_surat');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};
