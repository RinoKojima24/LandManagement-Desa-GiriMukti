{{-- components/menu_section.blade.php --}}
<div class="menu-section">
    <div class="menu-title">
        <span>Menu Pelayanan</span>
    </div>

    {{-- Main Menu: 3 Menu Utama --}}
    <div class="main-menu">
        <a href="{{ route('permohonan_form') }}" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Pengajuan<br>Surat Tanah</span>
        </a>

        <a href="{{ route('keterangan_form') }}" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Pengajuan<br>Surat Keterangan</span>
        </a>

        <a href="{{ url('/tanah') }}" class="menu-item">
            <i class="fas fa-file-invoice"></i>
            <span>Data Peta<br>Tanah Desa</span>
        </a>
    </div>

    {{-- Secondary Menu: 8 Menu Grid --}}
    <div class="secondary-menu">
        <a href="{{ route('pengajuanSurat.index') }}" class="secondary-item">
            <div class="icon-circle yellow">
                <i class="fas fa-search"></i>
            </div>
            <span>Cari Berkas</span>
        </a>

        <a href="{{ url('/tanah') }}" class="secondary-item">
            <div class="icon-circle green">
                <i class="fas fa-download"></i>
            </div>
            <span>Swaplopting</span>
        </a>

        <a href="{{ url('/data-tanah/titik') }}" class="secondary-item">
            <div class="icon-circle purple">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <span>Lokasi Bidang</span>
        </a>

        <a href="{{ route('info-layanan') }}" class="secondary-item">
            <div class="icon-circle blue">
                <i class="fas fa-info-circle"></i>
            </div>
            <span>Info Layanan</span>
        </a>

        <a href="{{ route('surat.unverified') }}" class="secondary-item">
            <div class="icon-circle pink">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <span>Validasi<br>Masuk</span>
        </a>

        <a href="{{ route('antrean.create') }}" class="secondary-item">
            <div class="icon-circle indigo">
                <i class="fas fa-list"></i>
            </div>
            <span>Antrian Online</span>
        </a>

        <a href="{{ route('kritik_saran.create') }}" class="secondary-item">
            <div class="icon-circle cyan">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span>Appointment &<br>Saran</span>
        </a>
    </div>
</div>
