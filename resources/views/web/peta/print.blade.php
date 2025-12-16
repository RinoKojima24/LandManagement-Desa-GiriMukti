<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Hak Milik - BPN</title>
    <style>
        @page {
            /* Margin besar untuk meniru bingkai sertifikat BPN */
            margin: 2.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            padding: 0;
            margin: 0;
            /* Latar belakang hijau tipis meniru kertas sertifikat */
            /* background-color: #e6f2e6; */
        }
        .container {
            width: 100%;
            height: 95vh;
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 20pt;
        }
        .garuda {
            width: 80px;
            margin: 10px auto;
        }
        .judul-utama {
            font-size: 24pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            letter-spacing: 2px;
            /* border-top: 2px solid black; */
            /* border-bottom: 2px solid black; */
            padding: 5px 0;
        }
        .sub-judul {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 0;
        }
        .data-section {
            width: 70%;
            margin: 30px auto 10px auto;
            text-align: left;
        }
        .data-section table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
        }
        .data-section td {
            padding: 5px 0;
        }
        .label {
            width: 30%;
        }
        .separator {
            width: 5%;
            text-align: center;
        }
        .value {
            border-bottom: 1px solid black;
        }
        .footer-info {
            width: 70%;
            margin: 30px auto 10px auto;
            text-align: left;
            font-size: 10pt;
        }
        .footer-kode {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }
        .kode-kotak {
            display: inline-block;
            border: 1px solid black;
            padding: 5px 8px;
            margin: 0 2px;
            font-family: monospace;
            font-size: 12pt;
        }
        .kotak-kecil {
             padding: 5px 2px;
             font-size: 10pt;
        }
    </style>
    <style>
        .table-border {
            width: 100%;
            border-collapse: collapse;
        }
        .table-border th,
        .table-border td {
            border: 1px solid #000;
            padding: 6px;
        }
    </style>
</head>
<body>
    @php
        $nib_array = str_split($peta->PendaftaranPertama->nib);
    @endphp
    <div class="page-1">
        <div class="container">
            <div class="header">
                <h3>KEMENTERIAN AGRARIA DAN TATA RUANG /</h3>
                <h3>BADAN PERTANAHAN NASIONAL</h3>
                <h3>REPUBLIK INDONESIA</h3>
            </div>

            <div class="garuda">
                <div style="font-size: 50pt; line-height: 1; color: darkgreen;"><img style="width: 200px;" src="{{ public_path('garuda.png') }}" alt=""></div> </div>

            <div class="judul-utama">
                SERTIFIKAT <br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td style="font-size: 18px;">HAK : {{ $peta->PendaftaranPertama->hak }} <td>
                        <td style="font-size: 18px;">No. {{ $peta->PendaftaranPertama->nomor }} <td>
                    </tr>
                </table>
            </div>

            <br>
            <table style="margin: 0 auto;">
                <tr>
                    <th style="text-align: left">PROVINSI</th>
                    <th>:</th>
                    <td style="font-size: 18px;">{{ $peta->SuratUkur->provinsi }}</td>
                </tr>
                <tr>
                    <th style="text-align: left">KABUPATEN / KOTA</th>
                    <th>:</th>
                    <td style="font-size: 18px;">{{ $peta->SuratUkur->kabupaten }}</td>
                </tr>
                <tr>
                    <th style="text-align: left">KECAMATAN</th>
                    <th>:</th>
                    <td style="font-size: 18px;">{{ $peta->SuratUkur->kecamatan }}</td>
                </tr>
                <tr>
                    <th style="text-align: left">DESA / KELURAHAN</th>
                    <th>:</th>
                    <td style="font-size: 18px;">{{ $peta->SuratUkur->desa }}</td>
                </tr>
            </table>

            <br><br>
            <table style="width: 100%;">
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center;">Daftar Isian 307</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center;">{{ $peta->SuratUkur->no_daftar_isian_307 }}, Tanggal {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center;">Daftar Isian 208</td>
                </tr>
                <tr>
                    <th>KANTOR PERTANAHAN</th>
                    <td></td>
                    <td style="text-align: center;">{{ $peta->SuratUkur->no_daftar_isian_208 }}, Tanggal {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_208)) }}</td>
                </tr>
                <tr>
                    <th>KABUPATEN / KOTA</th>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{ $peta->SuratUkur->kabupaten }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br><br>
            <table style="float:right;">
                <tr>
                    @foreach ($nib_array as $a)
                        <td style="border: 1px solid black; padding: 5px;">{{ $a }}</td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

    <div style="page-break-after: always;"></div>

    <div class="page-2">
        <center>
            <h3>PENDAFTARAN - PERTAMA</h3>
        </center>
        Halaman :
        <table class="table-border">
            <tr>
                <td>
                    <ol style="list-style-type: lower-alpha; margin: 0;">
                        <li>
                            HAK : <br>
                            No : <br>
                            Desa / Kel : <br>
                            Tgl. berakhirnya hak : <br>
                        </li>
                    </ol>
                </td>
                <td rowspan="2">
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="6">
                        <li>
                            <span>
                                NAMA PEMEGANG HAK <br> <span style="margin-left: 20px;">{{ $peta->PendaftaranPertama->nama_pemegang_hak }}</span>  <br> <br> Tanggal lahir / akta pendirian <br> <span style="margin-left: 20px;">{{ date('d/m/Y', strtotime($peta->PendaftaranPertama->tanggal_lahir_akta_pendirian)) }}</span>
                            </span>
                        </li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td>
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="2">
                        <li>
                            NIB : {{  str_replace('.', '', $peta->PendaftaranPertama->nib)  }}<br>
                            Letak Tanah : {{ $peta->PendaftaranPertama->letak_tanah }}<br>
                        </li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td>
                    <ol style="list-style-type: lower-alpha;" start="3">
                        <li>ASAL HAK</li>
                    </ol>
                    <ol>
                       <li>Konversi <br> {{ $peta->PendaftaranPertama->konversi }}</li>
                       <li>Pemberian Hak <br> {{ $peta->PendaftaranPertama->pemberian_hak }}</li>
                       <li>Pemecahan / Pemisahan / Penggabungan bidang <br> {{ $peta->PendaftaranPertama->pemecahan }}</li>

                    </ol>
                </td>
                <td rowspan="2">
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="7">
                        <li>
                            PEMBUKUAN <br>

                            <span style="font-size: 18px">
                                Penajam Paser Utara, {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) }}<br>
                            </span>
                            <center>
                                An. Kepala Kantor Pertanahan<br>
                                Kabupaten / Kota<br>
                                Penajam Paser Utara<br>
                                Ketua Panitia Ajudikasi<br>
                                <br><br><br>
                                <u>ISKANDAR ZULKARNAIN, S.SiT</u><br>
                                NIP   19710124 199303 1 002
                            </center>
                        </li>
                    </ol>


                </td>
            </tr>
            <tr>
                <td rowspan="2">
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="3">
                        <li>DASAR PENDAFTARAN</li>
                    </ol>
                    <ol>
                       <li>Daftar Isian 202 <br>Tgl. {{ $peta->PendaftaranPertama->tgl_konversi ?  date('d/m/Y', strtotime($peta->PendaftaranPertama->tgl_konversi)) : "" }}<br>No. {{ $peta->PendaftaranPertama->no_konversi }}</li>
                       <li>Surat Keputusan <br>Tgl. {{ $peta->PendaftaranPertama->tgl_pemberian_hak ?  date('d/m/Y', strtotime($peta->PendaftaranPertama->tgl_pemberian_hak)) :  "" }}<br>No. {{ $peta->PendaftaranPertama->no_pemberian_hak }}</li>
                       <li>Pemecahan / Pemisahan / Penggabungan bidang <br>Tgl. {{$peta->PendaftaranPertama->tgl_pemecahan ? date('d/m/Y', strtotime($peta->PendaftaranPertama->tgl_pemecahan)) : "" }}<br>No. {{ $peta->PendaftaranPertama->no_pemecahan }}</li>

                    </ol>
                </td>
            </tr>
            <tr>
                <td rowspan="2">
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="8">
                        <li>
                            PENERBITAN SERTIFIKAT <br>

                            <span style="font-size: 18px">
                                Penajam Paser Utara, {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) }}<br>
                            </span>
                            <center>
                                An. Kepala Kantor Pertanahan<br>
                                Kabupaten / Kota<br>
                                Penajam Paser Utara<br>
                                Ketua Panitia Ajudikasi<br>
                                <br><br><br>
                                <u>ISKANDAR ZULKARNAIN, S.SiT</u><br>
                                NIP   19710124 199303 1 002
                            </center>
                        </li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td>
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="5">
                        <li>
                            SURAT UKUR <br>
                            Tgl. {{ date('d/m/Y', strtotime($peta->PendaftaranPertama->tgl_surat_ukur)) }}<br>
                            No. {{ $peta->PendaftaranPertama->no_surat_ukur }}<br>
                            Luas: {{ $peta->PendaftaranPertama->luas_surat_ukur }} m<sup>2</sup>
                        </li>
                    </ol>
                </td>

            </tr>

            <tr>
                <td colspan="2">
                    <ol style="list-style-type: lower-alpha;  margin: 0;" start="9">
                        <li>
                            PENUNJUK <br> asdasd
                        </li>
                    </ol>
                </td>

            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    <div class="page-3">
        <h3>PENDAFTARAN PERALIHAN HAK. PEMBEBANAN DAN PENCATATAN LAINNYA</h3>
        Halaman :
        <table class="table-border">
            <thead>
                <tr>
                    <th>Sebab perubahan <br>Tanggal pendaftaran <br> No. Daftar Isian</th>
                    <th>Nama yang berhak <br>dan <br>Pemegang hak lain-lainnya</th>
                    <th>Tanda tangan Kepala Kantor <br>dan Cap Kantor</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($peta->PendaftaranPeralihan as $a)
                    <tr >
                        <td>{{ $a->sebab }}</td>
                        <td>{{ $a->nama }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @php
        function terbilang($angka) {
            $angka = abs($angka);
            $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

            if ($angka < 12) {
                return $baca[$angka];
            } elseif ($angka < 20) {
                return terbilang($angka - 10) . " Belas";
            } elseif ($angka < 100) {
                return terbilang(floor($angka / 10)) . " Puluh " . terbilang($angka % 10);
            } elseif ($angka < 200) {
                return "Seratus " . terbilang($angka - 100);
            } elseif ($angka < 1000) {
                return terbilang(floor($angka / 100)) . " Ratus " . terbilang($angka % 100);
            } elseif ($angka < 2000) {
                return "Seribu " . terbilang($angka - 1000);
            } elseif ($angka < 1000000) {
                return terbilang(floor($angka / 1000)) . " Ribu " . terbilang($angka % 1000);
            } elseif ($angka < 1000000000) {
                return terbilang(floor($angka / 1000000)) . " Juta " . terbilang($angka % 1000000);
            }

            return "";
        }

    @endphp
    <div style="page-break-after: always;"></div>
    <div class="page-4">
        <table style="width: 100%;">
            <tr>
                <td>
                    <table>
                        <tr>
                            @foreach ($nib_array as $a)
                                <td style="border: 1px solid black; padding: 5px;">{{ $a }}</td>
                            @endforeach
                        </tr>
                    </table>
                </td>
                <td style="text-align: right;">
                    <h3>DAFTAR ISIAN 207</h3>
                    <p><b>NIB : {{ str_replace('.', '', $peta->PendaftaranPertama->nib) }}</b></p>
                </td>
            </tr>
        </table>
        <center>
            <span style="font-size: 26px;"><u><b>SURAT UKUR</b></u></span> <br>
            <span style="font-size: 16px;">Nomor : <u>{{ $peta->PendaftaranPertama->no_surat_ukur }}</u></span>
        </center>
        <br><br>
        <span style="font-size: 18px;"><b>SEBIDANG TANAH TERLETAK DALAM</b></span> <br>
        <table style="width: 100%">
            <tr>
                <td colspan="2">Provinsi : {{ $peta->SuratUkur->provinsi }}</td>
            </tr>
            <tr>
                <td colspan="2">Kabupaten / Kota : {{ $peta->SuratUkur->kabupaten }} </td>
            </tr>
            <tr>
                <td colspan="2">Kecamatan :  {{ $peta->SuratUkur->kecamatan }}</td>
            </tr>
            <tr>
                <td colspan="2">Desa / Kelurahan :  {{ $peta->SuratUkur->desa }}</td>
            </tr>
            <tr>
                <td>Peta : {{ $peta->SuratUkur->peta }}</td>
                <td>Nomor Peta Pendaftaran : {{ $peta->SuratUkur->nomor_peta }}</td>
            </tr>
            <tr>
                <td style="width: 30%;">Lembar : {{ $peta->SuratUkur->lembar }}</td>
                <td>Kotak : {{ $peta->SuratUkur->kotak }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">Keadaan Tanah : {{ $peta->SuratUkur->keadaan_tanah }}</td>
            </tr>
            <tr>
                <td colspan="2">Tanda-tanda batas : {{ $peta->SuratUkur->tanda_tanda_batas }}</td>
            </tr>
            <tr>
                <td colspan="2">Luas : {{ $peta->PendaftaranPertama->luas_surat_ukur }} m<sup>2</sup> ({{ terbilang($peta->PendaftaranPertama->luas_surat_ukur) }} Meter Persegi)</td>
            </tr>
            <tr>
                <td colspan="2">Penunjukan dan penetapan batas : {{ $peta->SuratUkur->penunjukan_dan_penetapan_batas }}
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page-5">
        <center>
            <h2>SKALA 1 : {{ $peta->skala }} </h2>
        </center>
        <div style="font-size: 50pt; line-height: 1; color: darkgreen;"><img style="width: 700px;" src="{{ public_path('storage/'.$peta->foto_denah) }}" alt=""></div> </div>
        <h2>PENJELASAN : {{ $peta->penjelasan }} batas tanah ini</h2>
    </div>

    <div style="page-break-after: always;"></div>
    <div class="page-6">
        <table style="width: 100%">
            <tr>
                <td colspan="2">Hal lain - lain :  {{ $peta->SuratUkur->hal_lain_lain }}</td>
            </tr>
            <tr>
                <td>Daftar Isian 302 Tgl. {{ $peta->SuratUkur->tgl_daftar_isian_302 ? date('d/m/Y', strtotime($peta->SuratUkur->tgl_daftar_isian_302)) : "" }} </td>
                <td>No. {{ $peta->SuratUkur->no_daftar_isian_302 }}</td>
            </tr>
            <tr>
                <td>Daftar Isian 307 Tgl. {{ $peta->SuratUkur->tgl_daftar_isian_307 ? date('d/m/Y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) : ""}}</td>
                <td>No. {{ $peta->SuratUkur->no_daftar_isian_307 }}</td>
            </tr>
            <tr>
                <td colspan="2">Tanggal Penomoran Surat Ukur {{ $peta->SuratUkur->tanggal_penomoran_surat_ukur  ? date('d/m/Y', strtotime($peta->SuratUkur->tanggal_penomoran_surat_ukur)) : "" }}</td>
            </tr>
            <tr>
                <td><br><br><br></td>
            </tr>
            <tr>
                <td>
                    <center>UNTUK SERTIFIKAT</center> <br>

                    <span style="font-size: 16px">
                        Penajam Paser Utara, {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) }}<br>
                    </span>
                    <center>
                        Kepala Kantor Pertanahan<br>
                        Kabupaten / Kota<br>
                        Penajam Paser Utara<br>
                        Ketua Panitia Ajudikasi<br>
                        <br><br><br>
                        <u>ISKANDAR ZULKARNAIN, S.SiT</u><br>
                        NIP   19710124 199303 1 002
                    </center>
                </td>
                <td>
                    <center>
                        <span style="font-size: 16px">
                            Penajam Paser Utara, {{ date('d/m/y', strtotime($peta->SuratUkur->tgl_daftar_isian_307)) }}<br>
                        </span>
                        KEPALA SEKSI INFRASTRUKTUR Pertanahan
                    </center> <br>


                    <center>
                        Kantor Pertanahan<br>
                        Kabupaten / Kota<br>
                        Penajam Paser Utara<br>
                        Ketua Satuan Tugas Fisik<br>
                        <br><br><br>
                        <u>Noor Santy Haqim, S.T</u><br>
                        NIP   197804092006042003
                    </center>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td>Lihat Surat Ukur</td>
                <td>
                    <u>Pemisahan</u><br>
                    <u>Penggabungan</u><br>
                    <u>Pengganti</u><br>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td>Nomor : {{ $peta->SuratUkur->nomor }}  </td>
                <td>Nomor Hak : {{ $peta->SuratUkur->nomor_hak }}</td>
            </tr>
        </table>
        <table class="table-border">
            <thead>
                <tr>
                    <th colspan="2">Dikeluarkan Surat Ukur</th>
                    <th rowspan="2">Luas</th>
                    <th rowspan="2">Nomor Hak</th>
                    <th rowspan="2">Sisa Luas</th>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <th>Nomor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peta->SuratUkur->DikeluarkanSuratUkur as $a)
                    <tr >
                        <td>{{ date('d/m/Y', strtotime($a->tanggal)) }}</td>
                        <td>{{ $a->nomor }}</td>
                        <td>{{ $a->luas }}</td>
                        <td>{{ $a->nomor_hak }}</td>
                        <td>{{ $a->sisa_luas }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="width: 100%;">
            <tr>
                <td>Sisanya diuraikan dalam Surat Ukur Nomor : {{ $peta->SuratUkur->nomor_surat_ukur }}  </td>
                <td>Nomor Hak : {{ $peta->SuratUkur->nomor_hak }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
