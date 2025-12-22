@if($_GET['list_surat'] == "0")
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Permohonan Penguasaan Fisik Bidang Tanah</title>
        <style>
            /* CSS Umum */
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                margin: 0;
                padding: 0;
                line-height: 1.5;
            }

            .container {
                width: 90%;
                margin: auto;
                padding: 20px 0;
            }

            /* Header dan Alamat */
            .header {
                text-align: right;
                margin-bottom: 20px;
            }

            .hal {
                margin-top: 20px;
                margin-bottom: 30px;
                line-height: 1.8;
            }

            /* Tabel Data Diri */
            .data-diri table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .data-diri table td {
                padding: 2px 0;
            }

            .data-diri .kolom-kiri {
                width: 15%; /* Lebar untuk Nama, NIK, dll */
                vertical-align: top;
            }

            .data-diri .kolom-pemisah {
                width: 2%;
                text-align: center;
                vertical-align: top;
            }

            .data-diri .kolom-kanan {
                width: 83%; /* Lebar untuk isian */
                border-bottom: 1px dotted #000; /* Garis putus-putus */
            }

            /* Bagian A dan B */
            .section-title {
                font-weight: bold;
                margin-top: 15px;
                margin-bottom: 5px;
            }

            .isian-form table {
                width: 100%;
                border-collapse: collapse;
            }

            .isian-form table td {
                padding: 2px 0;
            }

            .isian-form .kolom-isian {
                border-bottom: 1px dotted #000;
            }

            /* Pembatas Halaman */
            .page-break {
                page-break-before: always;
            }

            /* Kolom untuk Batas Tanah dan Tanda Tangan */
            .kolom-ganda {
                overflow: hidden; /* Clearfix */
            }

            .kolom-kiri-setengah, .kolom-kanan-setengah {
                width: 48%;
                float: left;
                box-sizing: border-box;
                padding: 0 1%;
            }

            .kolom-kiri-setengah {
                margin-right: 2%;
            }

            /* Bagian C dan D (Halaman 2) */
            .isian-garis-bawah {
                border-bottom: 1px dotted #000;
            }

            .batas-tanah table {
                width: 100%;
                border-collapse: collapse;
            }

            .batas-tanah td {
                padding: 5px 0;
            }

            .batas-tanah .garis-batas {
                border-bottom: 1px dotted #000;
            }

    .dokumen-list {
            margin-left: 0;
            padding-left: 20px;
            list-style-type: none; /* <-- HAPUS INI */
        }

        .dokumen-list li {
            position: relative;
            margin-bottom: 5px;
        }
        /* HAPUS SEMUA BLOCK INI: */
        .dokumen-list li:before {
            /* content: counter(item, decimal) "."; */
            counter-increment: item;
            position: absolute;
            left: -20px;
        }

            .tanda-tangan {
                width: 300px;
                margin-left: auto;
                margin-top: 50px;
                text-align: center;
            }

            .tanda-tangan .garis-nama {
                margin-top: 80px;
                border-bottom: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                Girilmukti, {{ date('d F Y', strtotime($query->created_at)) }}
            </div>

            <div class="hal">
                <table>
                    <tr>
                        <td style="vertical-align: top;">Hal</td>
                        <td style="vertical-align: top;">:</td>
                        <td>
                            Permohonan Penandatanganan Mengetahui Dan/Atau Pencatatan <br>
                            Dalam Buku Register Atas Surat Pernyataan Penguasaan Fisik Bidang <br>
                            Tanah Yang Berasal Dari Tanah Negara
                        </td>
                    </tr>
                </table>
            </div>

            <div class="yth">
                Kepada Yth.:<br>
                Kepala Desa Girilmukti
            </div>

            <p>Yang bertanda tangan di bawah ini:</p>

            <div class="data-diri">
                <table>
                    <tr>
                        <td class="kolom-kiri">Nama</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->nama ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">NIK</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->nik ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Tempat/Tanggal Lahir</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ ($query->tempat.", ".$query->tanggal_lahir) ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Agama</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->agama ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Pekerjaan</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->pekerjaan ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Alamat</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->alamat ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <p>Dengan ini mengajukan Permohonan Penandatanganan Mengetahui Dan/Atau Pencatatan Dalam Buku Register Atas Surat Pernyataan Penguasaan Fisik Bidang Tanah Yang Berasal Dari Tanah Negara sebagai berikut:</p>

            <div class="section-a isian-form">
                <div class="section-title">A. Letak Tanah</div>
                <table>
                    <tr>
                        <td style="width: 20%;">Jalan, RT/RW</td>
                        <td style="width: 2%;">:</td>
                        <td class="kolom-isian" style="width: 78%;">{{ $query->jalan ?? '' }}, {{ $query->rt_rt ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Desa</td>
                        <td>:</td>
                        <td class="kolom-isian">Girilmukti</td>
                    </tr>
                    <tr>
                        <td>Kecamatan</td>
                        <td>:</td>
                        <td class="kolom-isian">Penajam</td>
                    </tr>
                    <tr>
                        <td>Kabupaten</td>
                        <td>:</td>
                        <td class="kolom-isian">Penajam Paser Utara</td>
                    </tr>
                    <tr>
                        <td>Provinsi</td>
                        <td>:</td>
                        <td class="kolom-isian">Kalimantan Timur</td>
                    </tr>
                </table>
            </div>

            <div class="section-b isian-form">
                <div class="section-title">B. Ukuran Tanah</div>
                <table>
                    <tr>
                        <td style="width: 20%;">Panjang</td>
                        <td style="width: 2%;">:</td>
                        <td class="kolom-isian" style="width: 78%;">{{ $query->panjang ?? '' }} m</td>
                    </tr>
                    <tr>
                        <td>Lebar</td>
                        <td>:</td>
                        <td class="kolom-isian">{{ $query->lebar ?? '' }} m</td>
                    </tr>
                    <tr>
                        <td>Luas</td>
                        <td>:</td>
                        <td class="kolom-isian">{{ $query->luas ?? '' }} m<sup>2</sup></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="page-break"></div>
        <div class="container">

            <div class="section-c batas-tanah">
                <div class="section-title">C. Batas-batas Tanah</div>
                <table>
                    <tr>
                        <td style="width: 30%;">Sebelah Utara</td>
                        <td style="width: 70%;" class="garis-batas">{{ $query->sebelah_utara ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Sebelah Timur</td>
                        <td class="garis-batas">{{ $query->sebelah_timur ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Sebelah Selatan</td>
                        <td class="garis-batas">{{ $query->sebelah_selatan ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Sebelah Barat</td>
                        <td class="garis-batas">{{ $query->sebelah_barat ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section-d">
                <div class="section-title">D. Keadaan Tanah</div>
                <table>
                    <tr>
                        <td style="width: 30%;">Kondisi Fisik</td>
                        <td style="width: 2%;">:</td>
                        <td style="width: 68%;" class="isian-garis-bawah">{{ $query->kondisi_fisik ?? '(pertanian basah, pertanian kering, perkebunan, belukar, tanah kosong, bangunan, pekarangan, dsb)' }}</td>
                    </tr>
                    <tr>
                        <td>Dasar Perolehan</td>
                        <td>:</td>
                        <td class="isian-garis-bawah">{{ $query->dasar_perolehan ?? '(pelepasan kawasan hutan, jual-beli, tukar menukar, ganti rugi tanam tumbuh, waris, penggarapan, perjanjian pemanfaatan, hibah, dsb)' }}</td>
                    </tr>
                </table>

                <p>Bidang tanah tersebut telah selesai saya kuasai dengan itikad baik, secara terbuka dan terus menerus sejak tahun {{ $query->tahun_dikuasai ?? '<span class="isian-garis-bawah" style="display: inline-block; width: 100px;"></span>' }}.</p>
            </div>

            <div class="dokumen">
                <p>Dokumen yang dilampirkan:</p>
                <ul class="dokumen-list">
                    <li>1. Copy Kartu Tanda Penduduk pemohon;</li>
                    <li>2. Copy Kartu Tanda Penduduk pihak yang berbatasan;</li>
                    <li>3. Copy Kartu Tanda Penduduk saksi-saksi;</li>
                    <li>4. Bukti perolehan tanah sebelumnya (pelepasan kawasan hutan, jual-beli, tukar menukar, ganti rugi tanam tumbuh, waris, penggarapan, perjanjian pemanfaatan, hibah, dsb);</li>
                    <li>5. Surat Pernyataan Pemasangan Tanda Batas dan Persetujuan Pihak Yang Berbatasan;</li>
                    <li>6. Koordinat bidang tanah.</li>
                </ul>
            </div>

            <div class="tanda-tangan">
                <span style="text-align: left; !important; transform: translateX(-45px);">Pemohon</span>
                <div class="garis-nama">{{ $query->nama ?? '............................................' }}</div>
            </div>

        </div>
    </body>
    </html>
@endif

@if ($_GET['list_surat'] == "1")
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Surat Pernyataan Pemasangan Tanda Batas</title>
        <style>
            /* CSS Umum */
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                margin: 0;
                padding: 0;
                line-height: 1.5;
            }

            .container {
                width: 90%;
                margin: auto;
                padding: 20px 0;
            }

            /* Judul */
            .judul {
                font-weight: bold;
                text-align: center;
                margin-bottom: 30px;
                line-height: 1.2;
            }

            /* Tabel Data Diri Pemohon (Pengisi) */
            .data-diri table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .data-diri table td {
                padding: 2px 0;
            }

            .data-diri .kolom-kiri {
                width: 30%; /* Lebar untuk Nama, NIK, dll */
                vertical-align: top;
            }

            .data-diri .kolom-pemisah {
                width: 2%;
                text-align: center;
                vertical-align: top;
            }

            .data-diri .kolom-kanan {
                width: 80%; /* Lebar untuk isian */
                border-bottom: 1px dotted #000; /* Garis putus-putus */
            }

            /* Paragraf Utama */
            .paragraf-isian {
                margin-bottom: 20px;
                text-align: justify;
            }

            .isian-garis-bawah {
                border-bottom: 1px dotted #000;
                display: inline-block;
                min-width: 50px;
            }

            .luas-satuan {
                min-width: 100px;
                text-align: center;
            }

            /* Daftar Pernyataan (1, 2, 3) */
            .daftar-pernyataan {
                padding-left: 20px;
            }

            .daftar-pernyataan li {
                margin-bottom: 10px;
                text-align: justify;
            }


            .pihak-berbatasan {
                margin-bottom: 15px;
                border: 1px solid #000;
                padding: 10px;
            }

            .pihak-berbatasan table {
                width: 100%;
                border-collapse: collapse;
            }

            .pihak-berbatasan td {
                padding: 2px 0;
            }

            .pihak-berbatasan .nama-nik {
                width: 20%;
            }

            .pihak-berbatasan .isian-batas {
                border-bottom: 1px dotted #000;
                padding: 0 5px;
            }

            .materai {
                text-align: center;
                margin-top: 20px;
            }

            /* Tanda Tangan Bawah */
            .tanda-tangan-bawah table {
                width: 100%;
                margin-top: 50px;
                text-align: center;
            }

            .tanda-tangan-bawah td {
                width: 33.33%;
            }

            .ttd-garis {
                margin-top: 70px;
                border-bottom: 1px solid #000;
                display: inline-block;
                width: 80%;
            }

            .page-break {
                /* Memaksa halaman baru sebelum elemen dengan class ini */
                page-break-before: always;
            }

            /* Atau jika Anda ingin memisahkannya setelah elemen, gunakan: */
            .page-break-after {
                page-break-after: always;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="judul">
                SURAT PERNYATAAN <br>
                Pemasangan Tanda Batas dan Persetujuan Pihak Yang Berbatasan
            </div>

            <p>Yang bertanda tangan di bawah ini:</p>

            <div class="data-diri">
                <table>
                    <tr>
                        <td class="kolom-kiri">Nama</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->nama ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">NIK</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->nik ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Tempat/Tanggal Lahir</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->tempat ?? '' }}, {{ $query->tanggal_lahir ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Agama</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->agama ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Pekerjaan</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->pekerjaan ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="kolom-kiri">Alamat</td>
                        <td class="kolom-pemisah">:</td>
                        <td class="kolom-kanan">{{ $query->alamat ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="paragraf-isian">
                Sebagai Pihak yang menguasai sebidang tanah yang berasal dari Tanah Negara, dengan ukuran seluas
                <span class="isian-garis-bawah luas-satuan">{{ $query->luas ?? '' }}</span> m&sup2;, yang terletak di Jalan
                <span class="isian-garis-bawah">{{ $query->jalan ?? '' }}</span> RT
                <span class="isian-garis-bawah" style="min-width: 30px;">{{ $query->rt_rw ?? '' }}</span> / RW
                <span class="isian-garis-bawah" style="min-width: 30px;">{{ $rw ?? '000' }}</span>, Desa Girilmukti, Kecamatan Penajam, Kabupaten Penajam Paser Utara, Provinsi Kalimantan Timur. Dengan ini menyatakan bahwa tanah tersebut:
            </div>

            <ol class="daftar-pernyataan">
                <li>Telah dipasang patok/tanda batas;</li>
                <li>Terhadap patok/tanda batas yang dipasang tersebut tidak ada Pihak yang berkeberatan;</li>
                <li>Bersedia menerima hasil ukur Kementerian Agraria dan Tata Ruang Badan Pertanahan Nasional dalam hal terdapat selisih ukur antara yang dinyatakan dalam Surat Pernyataan ini dan/atau Surat Pernyataan Penguasaan Fisik Bidang Tanah dengan hasil pengukuran ulang Kementerian Agraria Tata Ruang/Badan Pertanahan Nasional, baik dalam rangka pendaftaran Tanah untuk memperoleh Sertifikat atau dalam hal tanah menjadi objek Pengadaan Tanah Bagi Pembangunan untuk Kepentingan Umum.</li>
            </ol>

            <div class="paragraf-isian" style="margin-top: 20px;">
                Surat Pernyataan ini saya buat dengan sebenar-benarnya dengan penuh tanggung jawab secara hukum baik perdata maupun pidana. Apabila dalam Surat Pernyataan ini terdapat data, informasi, dan unsur-unsur yang tidak benar atau melawan hukum, dan/atau terjadi konflik/sengketa/perkara, maka segala akibat hukum yang timbul menjadi tanggung jawab saya sepenuhnya, dan saya bersedia untuk dituntut sesuai dengan ketentuan peraturan perundang-undangan dengan tidak melibatkan pihak lain.
            </div>

            <div class="page-break"></div>
            <table style="width: 100%; margin-top: 30px; border-collapse: collapse;">
                <tr>
                    <td colspan="2">Menyetujui Pihak yang Berbatasan</td>
                    <td></td>
                    <td style="text-align: center">Yang Membuat Pernyataan</td>
                </tr>
                <tr>
                    <td colspan="2">1. Sebelah Utara</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 50px;">Nama</td>
                    <td>: {{ $query->Pernyataan1->sebelah_utara_nama }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>: {{ $query->Pernyataan1->sebelah_utara_nik }}</td>
                    <td></td>
                    <td style="text-align: center">(Materai 10000)</td>
                </tr>
                <tr>
                    <td style="height: 30px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">..................................................</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">2. Sebelah Timur</td>
                    <td></td>
                    <td style="text-align: center">{{ $query->Pernyataan1->pembuat_pernyataan }}</td>
                </tr>
                <tr>
                    <td style="width: 50px;">Nama</td>
                    <td>: {{ $query->Pernyataan1->sebelah_timur_nama }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>: {{ $query->Pernyataan1->sebelah_timur_nik }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 30px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">..................................................</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">3. Sebelah Selatan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 50px;">Nama</td>
                    <td>: {{ $query->Pernyataan1->sebelah_selatan_nama }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>: {{ $query->Pernyataan1->sebelah_selatan_nik }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 30px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">..................................................</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">4. Sebelah Barat</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 50px;">Nama</td>
                    <td>: {{ $query->Pernyataan1->sebelah_barat_nama }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>: {{ $query->Pernyataan1->sebelah_barat_nik }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 30px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">..................................................</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <div class="tanda-tangan-bawah">
                <table>
                    <tr>
                        <td></td>
                        <td>Mengetahui,</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Ketua <span class="isian-garis-bawah" style="min-width: 100px;">{{ $query->Pernyataan1->rt ?? '' }}</span> / Desa Girilmukti</td>
                        <td></td>
                        <td>Kepala Desa Girilmukti</td>
                    </tr>
                    <tr>
                        <td><div class="ttd-garis"></div> <br>{{ $query->Pernyataan1->nama_ketua_rt ?? '' }}</td>
                        <td></td>
                        <td>
                            <div class="ttd-garis"></div> <br>
                            (Hendra Jatmiko Sormin, S.Si., M.Ling)
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
    </html>
@endif

@if ($_GET['list_surat'] == "2")
    <!DOCTYPE html>
    <html>
    <head>
        <title>Berita Acara Pengukuran Bidang Tanah</title>
        <style>
            @page {
                margin: 1cm;
            }
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                line-height: 1.5;
                padding: 0;
                margin: 0;
            }
            .container {
                width: 100%;
                margin: auto;
            }
            .header, .section {
                margin-bottom: 15px;
            }
            h3, h4 {
                margin-top: 0;
                margin-bottom: 5px;
                font-size: 12pt;
            }
            .underline-text {
                border-bottom: 1px solid black;
                display: inline-block;
                min-width: 150px; /* Minimal width for the line */
                padding-right: 5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            td {
                padding: 2px 0;
                vertical-align: top;
            }
            .indent {
                padding-left: 20px;
            }
            .page-break {
                page-break-after: always;
            }
            .page-2-content {
                padding-top: 20px; /* Add some space at the top of the second page */
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="header" style="text-align: center;">
                <h3>Berita Acara Pengukuran Bidang Tanah</h3>
            </div>
            @php
                $tanggal_input = $query->BeritaAcara->tanggal_dilaksanakan;

                // 1. Ambil nama hari dalam Bahasa Inggris
                $nama_hari_en = date('l', strtotime($tanggal_input));
                // 'l' menghasilkan nama hari lengkap dalam bahasa Inggris (e.g., 'Monday')

                // 2. Buat array pemetaan
                $map_hari = [
                    'Sunday'    => 'Minggu',
                    'Monday'    => 'Senin',
                    'Tuesday'   => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday'  => 'Kamis',
                    'Friday'    => 'Jumat',
                    'Saturday'  => 'Sabtu'
                ];

                // 3. Ambil nama hari dalam Bahasa Indonesia dari array
                $nama_hari_indonesia = $map_hari[$nama_hari_en];
            @endphp
            <div class="section">
                <p>
                    Pada hari {{ $nama_hari_indonesia }} tanggal {{ date('d', strtotime($query->BeritaAcara->tanggal_dilaksanakan)) }} bulan {{ date('F', strtotime($query->BeritaAcara->tanggal_dilaksanakan)) }} tahun {{ date('Y', strtotime($query->BeritaAcara->tanggal_dilaksanakan)) }} telah dilakukan Pengukuran Bidang Tanah terhadap Permohonan Penandatanganan mengetahui Dan/Atau Pencatatan Dalam Buku Register Atas Surat Pernyataan Penguasaan Fisik Bidang Tanah Yang Berasal Dari Tanah Negara, sebagai berikut:
                </p>
            </div>

            <div class="section">
                <h4>A. LETAK TANAH</h4>
                <table>
                    <tr>
                        <td style="width: 25%;">Jalan, RT/RW</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 80%;">{{ $query->jalan }}, {{ $query->rt_rw }}</span></td>
                    </tr>
                    <tr>
                        <td>Desa</td>
                        <td>:</td>
                        <td><span class="underline-text">Girimukti</span></td>
                    </tr>
                    <tr>
                        <td>Kecamatan</td>
                        <td>:</td>
                        <td><span class="underline-text">Penajam</span></td>
                    </tr>
                    <tr>
                        <td>Kabupaten</td>
                        <td>:</td>
                        <td><span class="underline-text">Penajam Paser Utara</span></td>
                    </tr>
                    <tr>
                        <td>Provinsi</td>
                        <td>:</td>
                        <td><span class="underline-text">Kalimantan Timur</span></td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h4>B. KETERANGAN PEMOHON</h4>
                <table>
                    <tr>
                        <td style="width: 25%;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 80%;">{{ $query->nama }}</span></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->nik }}</span></td>
                    </tr>
                    <tr>
                        <td>Tempat Tanggal Lahir</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->tempat }}, {{ $query->tanggal_lahir }}</span></td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->agama }}</span></td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->pekerjaan }}</span></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->alamat }}</span></td>
                    </tr>
                </table>

                <p style="margin-top: 15px;">
                    Bahwa untuk Pengukuran Bidang Tanah ini, saya telah memasang dan menunjukkan sendiri tanda batas pada setiap batas bidang tanah sesuai dengan kesepakatan para pihak yang berbatasan, dan saya bertanggungjawab penuh secara hukum terhadap kebenaran dan keabsahan data dan informasi yang saya sampaikan dalam pengukuran ini.
                </p>

                <div style="text-align: right; margin-top: 40px; margin-bottom: 20px;">
                    <p style="margin-right: 100px;">Pemohon</p>
                    <p style="margin-top: 80px;"><span class="underline-text" style="text-align: center;">{{ $query->nama }}</span></p>
                </div>
            </div>

        </div> <div class="page-break"></div>

        <div class="container page-2-content">

            <div class="section">
                <h4>C. KETERANGAN PENGUKUR</h4>
                <table>
                    <tr>
                        <td style="width: 5%;">1.</td>
                        <td style="width: 20%;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 70%;">{{ $query->BeritaAcara->nama_1 }}</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>NIP</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->BeritaAcara->nip_1 }}</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->BeritaAcara->jabatan_1 }}</span></td>
                    </tr>
                    <tr>
                        <td style="width: 5%;">2.</td>
                        <td style="width: 20%;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 70%;">{{ $query->BeritaAcara->nama_2 }}</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>NIP</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->BeritaAcara->nip_2 }}</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->BeritaAcara->jabatan_2 }}</span></td>
                    </tr>
                </table>

                <p style="margin-top: 15px;">
                    Pengukuran Bidang Tanah dilaksanakan berdasarkan tanda batas yang telah dipasang dan ditunjukkan sendiri oleh Pemohon dan mendapat persetujuan dari Pihak yang berbatasan sebagaimana diterangkan dalam Surat Pernyataan Pemasangan Tanda Batas dan Persetujuan Pihak yang Berbatasan yang dibuat oleh Pemohon pada tanggal {{ date('d, F Y', strtotime($query->created_at)) }}
                </p>

                <table style="width: 60%; margin-left: auto;">
                    <tr>
                        <td style="width: 40%;">Petugas Ukur : </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. {{ $query->BeritaAcara->nama_1 }}</td>
                        <td>{{ $query->BeritaAcara->tugas_1 }}</td>
                    </tr>
                    <tr>
                        <td>2. {{ $query->BeritaAcara->nama_2 }}</td>
                        <td>{{ $query->BeritaAcara->tugas_2 }}</td>
                    </tr>
                </table>
            </div>

            <div class="section" style="margin-top: 30px;">
                <h4>D. SKET LOKASI</h4>
                <div style="min-height: 250px; border: 1px solid #ccc; background-color: #f9f9f9; padding: 5px;">
                    </div>
            </div>

        </div> </body>
    </html>
@endif

@if ($_GET['list_surat'] == "3")
    <!DOCTYPE html>
    <html>
    <head>
        <title>Surat Pernyataan Penguasaan Fisik Bidang Tanah</title>
        <style>
            @page {
                margin: 1cm;
            }
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                line-height: 1.5;
                padding: 0;
                margin: 0;
            }
            .container {
                width: 100%;
                margin: auto;
            }
            .header {
                margin-bottom: 20px;
                text-align: center;
            }
            .section {
                margin-bottom: 15px;
            }
            .underline-text {
                border-bottom: 1px solid black;
                display: inline-block;
                min-width: 150px; /* Minimal width for the line */
                padding-right: 5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            td {
                padding: 2px 0;
                vertical-align: top;
            }
            .indent-table {
                margin-left: 20px;
            }
            .bordered-box {
                border: 1px solid black;
                padding: 2px;
                display: inline-block;
                min-width: 100px;
                text-align: center;
            }
            ol {
                padding-left: 20px;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            li {
                padding-left: 5px;
                margin-bottom: 5px;
            }
            .page-break {
                page-break-after: always;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="header">
                <h3>SURAT PERNYATAAN PENGUASAAN FISIK BIDANG TANAH</h3>
            </div>

            <div class="section">
                <p>Yang bertanda tangan di bawah ini:</p>
                <table>
                    <tr>
                        <td style="width: 20%;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 70%;">{{ $query->nama }}</span></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->nik }}</span></td>
                    </tr>
                    <tr>
                        <td>Tempat Tanggal Lahir</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->tempat }}, {{ $query->tanggal_lahir }}</span></td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->agama }}</span></td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->pekerjaan }}</span></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->alamat }}</span></td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p>Dengan ini menyatakan dengan sesungguhnya dan sebenarnya serta dengan itikad baik bahwa saya menguasai sebidang tanah sebagai berikut:</p>
            </div>

            <div class="section">
                <p>E. Letak Tanah</p>
                <table class="indent-table">
                    <tr>
                        <td style="width: 25%;">Jalan, RT/RW</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text" style="min-width: 80%;">{{ $query->jalan }}, {{ $query->rt_rw }}</span></td>
                    </tr>
                    <tr>
                        <td>Desa</td>
                        <td>:</td>
                        <td><span class="underline-text">Girimukti</span></td>
                    </tr>
                    <tr>
                        <td>Kecamatan</td>
                        <td>:</td>
                        <td><span class="underline-text">Penajam</span></td>
                    </tr>
                    <tr>
                        <td>Kabupaten</td>
                        <td>:</td>
                        <td><span class="underline-text">Penajam Paser Utara</span></td>
                    </tr>
                    <tr>
                        <td>Provinsi</td>
                        <td>:</td>
                        <td><span class="underline-text">Kalimantan Timur</span></td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p>F. Ukuran Tanah</p>
                <table class="indent-table">
                    <tr>
                        <td style="width: 25%;">Panjang</td>
                        <td style="width: 5%;">:</td>
                        <td style="width: 30%;"><span class="underline-text" style="min-width: 100%;">{{ $query->panjang }} m</span></td>
                        <td style="width: 40%;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Lebar</td>
                        <td>:</td>
                        <td><span class="underline-text" style="min-width: 100%;">{{ $query->lebar }} m</span></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Luas</td>
                        <td>:</td>
                        <td><span class="underline-text" style="min-width: 100%;">{{ $query->luas }} m<sup>2</sup></span></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p>G. Batas-Batas Tanah (dilengkapi tanda tangan Pihak yang berbatasan)</p>
                <table class="indent-table">
                    <tr>
                        <td style="width: 25%;">Sebelah Utara</td>
                        <td style="width: 5%;">:</td>
                        <td><span class="underline-text">{{ $query->Pernyataan1->sebelah_utara_nama }}</span> <span style="font-size: 8pt;">(........................)</span></td>
                    </tr>
                    <tr>
                        <td>Sebelah Timur</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->Pernyataan1->sebelah_timur_nama }}</span> <span style="font-size: 8pt;">(........................)</span></td>
                    </tr>
                    <tr>
                        <td>Sebelah Selatan</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->Pernyataan1->sebelah_selatan_nama }}</span> <span style="font-size: 8pt;">(........................)</span></td>
                    </tr>
                    <tr>
                        <td>Sebelah Barat</td>
                        <td>:</td>
                        <td><span class="underline-text">{{ $query->Pernyataan1->sebelah_barat_nama }}</span> <span style="font-size: 8pt;">(........................)</span></td>
                    </tr>
                </table>
            </div>

            <div class="page-break"></div>
            <div class="section">
                <p>Bahwa :</p>
                <ol>
                    <li>Bidang tanah tersebut tidak dalam penguasaan dan/atau kepemilikan Pihak lain;</li>
                    <li>Bidang tanah tersebut merupakan Tanah Negara yang telah saya kuasai secara fisik sejak tahun {{ $query->Pernyataan2->tahun_kuasa }} dan sampai saat ini masih saya kuasai secara terus menerus;</li>
                    <li>
                        Bidang tanah tersebut saya peroleh dari {{ $query->Pernyataan2->nama_peroleh }} ({{ $query->dasar_perolehan }}) </span> sejak tahun {{ $query->Pernyataan2->tahun_kuasa }}.
                    </li>
                    <li>Penguasaan bidang tanah tersebut dengan itikad baik dan secara terbuka oleh saya sebagai yang berhak atas bidang tanah tersebut;</li>
                    <li>Penguasaan bidang tanah tersebut tidak terdapat konflik/sengketa/perkara dan/atau keberatan dalam bentuk apapun dari pihak manapun;</li>
                    <li>Bidang tanah tersebut tidak dijadikan/menjadi jaminan sesuatu utang/tidak terdapat keberatan dari pihak Kreditur (apabila dijadikan/menjadi jaminan sesuatu utang);</li>
                    <li>Bidang tanah tersebut bukan merupakan aset atau barang yang dimiliki dan/atau dikuasai oleh Pemerintah, Pemerintah Daerah, Pemerintah Desa, Badan Usaha Milik Negara, Badan Usaha Milik Daerah, atau Badan Usaha Milik Desa;</li>
                    <li>Bidang tanah tersebut berada di luar kawasan hutan, di luar areal yang diberikan perizinannya pada hutan alam primer dan lahan gambut;</li>
                    <li>Bersedia untuk tidak mengurung atau menutup akses bidang tanah dari lalu lintas umum, akses publik dan/atau jalan air;</li>
                    <li>Bersedia untuk memasang atau menempatkan tanda batas yang jelas;</li>
                    <li>Bersedia untuk memelihara, mengusahakan, dan/atau mengusahakan tanah sesuai keadaan, sifat, dan peruntukannya serta tidak menelantarkan tanah. Apabila saya melakukan kewajiban tersebut dan terbukti menelantarkan tanah dan/atau membiarkan tanah tersebut dikuasai pihak lain tanpa ada hubungan hukum dengan saya, secara terus menerus selama 20 tahun, maka saya bersedia secara hukum dinyatakan telah melepaskan hak atas tanah tersebut, dan tanah tersebut menjadi tanah yang dikuasai langsung oleh Negara atau pihak lain yang menguasai dengan itikad baik dan terbuka;</li>
                    <li>Bersedia menerima hasil ukur Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional dalam hal terdapat selisih antara ukuran yang dinyatakan dalam Surat Pernyataan Penguasaan Fisik Bidang Tanah dengan hasil pengukuran ulang Kementerian Agraria/Tata Ruang/Badan Pertanahan Nasional, baik dalam rangka Pengadaan Tanah untuk memperoleh Sertifikat atau dalam hal menjadi objek Pengadaan Tanah bagi Pembangunan untuk Kepentingan Umum.</li>
                </ol>
            </div>
        </div> <div class="page-break"></div>

        <div class="container" style="padding-top: 20px;">

            <div class="section" style="margin-top: 20px;">
                <p>
                    Surat Pernyataan ini saya buat dengan sebenar-benarnya dengan penuh tanggung jawab secara hukum baik perdata maupun pidana. Apabila dalam Surat Pernyataan ini terdapat data, informasi, dan unsur-unsur yang tidak benar atau melawan hukum, dan/atau terjadi konflik/sengketa/perkara, maka segala akibat hukum yang timbul menjadi tanggung jawab saya sepenuhnya, dan saya bersedia untuk dituntut sesuai dengan ketentuan peraturan perundang-undangan dengan tidak melibatkan pihak lain termasuk Ketua RT, Kepala Desa dan Camat.
                </p>
                <p>
                    Berita Acara Pengukuran dan Gambar Skets/Sket Bidang merupakan satu kesatuan yang tidak terpisahkan dengan Surat Pernyataan ini.
                </p>
            </div>

            <div class="section">
                <div style="text-align: right; margin-right: 15px;">
                    <p>Yang membuat pernyataan,</p>
                    <br>
                    <p>(materai 10000)</p>
                    <br>
                </div>
                <div style="text-align: right; margin-right: 10px; margin-top: 10px; margin-bottom: 20px;">
                    <span style="font-weight: bold; font-size: 12pt;">{{ $query->Pernyataan2->pembuat_pernyataan }}</span>
                </div>

                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <p>Saksi-saksi</p>
                            <table>
                                <tr>
                                    <td style="width: 10%;">1.</td>
                                    <td style="width: 25%;">Nama</td>
                                    <td style="width: 5%;">:</td>
                                    <td><span class="underline-text">{{ $query->Pernyataan2->nama_saksi_1 }}</span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>NIK</td>
                                    <td>:</td>
                                    <td><span class="underline-text" style="min-width: 180px;">{{ $query->Pernyataan2->nik_saksi_1 }}</span></td>
                                </tr>
                                <tr><td colspan="4" style="height: 40px;"></td></tr>
                                <tr>
                                    <td style="width: 10%;">2.</td>
                                    <td style="width: 25%;">Nama</td>
                                    <td style="width: 5%;">:</td>
                                    <td><span class="underline-text">{{ $query->Pernyataan2->nama_saksi_2 }}</span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>NIK</td>
                                    <td>:</td>
                                    <td><span class="underline-text" style="min-width: 180px;">{{ $query->Pernyataan2->nik_saksi_1 }}</span></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <p>Nomor : <span class="underline-text" style="min-width: 30px;">{{ $query->Pernyataan2->nomor_rt }}/ RT. {{ $query->Pernyataan2->rt }}/{{ date("Y") }}</span></p>
                            <p>Tanggal : <span class="underline-text">{{ date("d, F Y", strtotime($query->Pernyataan2->tanggal_rt)) }}</span></p>
                            <p style="margin-top: 10px;">
                                <span style="font-weight: bold;">Ketua RT. {{ $query->Pernyataan2->rt }} Desa Girimukti</span>
                            </p>
                            <p style="margin-top: 80px; font-weight: bold;">{{ $query->Pernyataan2->nama_rt }}</p>
                        </td>
                    </tr>
                </table>

                <p style="text-align: center; margin-top: 20px;">Mengetahui,</p>
                <table style="width: 100%; text-align: center;">
                    <tr>
                        <td style="width: 50%;">
                            <p>Nomor : <span class="underline-text" style="min-width: 30px;">592.2/{{ $query->Pernyataan2->nomor_kades }}/Pem-DG/2025</span></p>
                            <p>Tanggal : <span class="underline-text">{{ date('d, F Y', strtotime($query->Pernyataan2->tanggal_kades)) }}</span></p>
                            <p style="margin-top: 10px;">
                                <span style="font-weight: bold;">Kepala Desa Girimukti</span>
                            </p>
                            <p style="margin-top: 80px; font-weight: bold;">HENDRO JATMIKO SORMIM, S.Si., M.Ling</p>
                        </td>
                        <td style="width: 50%;">
                            <p>Nomor : <span class="underline-text" style="min-width: 30px;">592.2/{{ $query->Pernyataan2->nomor_camat }}/PPSDA/2025</span></p>
                            <p>Tanggal : <span class="underline-text">{{ date('d, F Y', strtotime($query->Pernyataan2->tanggal_penajam)) }}</span></p>
                            <p style="margin-top: 10px;">
                                <span style="font-weight: bold;">Camat Penajam</span>
                            </p>
                            <p style="margin-top: 80px; font-weight: bold;">DAHLAN, S.IP</p>
                            <p style="font-size: 10pt;">NIP. 198604092007011001</p>
                        </td>
                    </tr>
                </table>
            </div>

        </div> </body>
    </html>
@endif
