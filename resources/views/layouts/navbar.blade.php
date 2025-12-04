<body>
	<div class="navbar navbar-expand-xl navbar-static shadow iframeable">
		<div class="container-fluid">
			<div class="navbar-brand flex-1">
				<a href="{{ url('home') }}" class="d-inline-flex align-items-center">
					<img src="{{ asset('assets/icon.png') }}" alt="Logo">
                    <span class="ms-2 fs-4 pt-1 text-dark fw-bold">SAKEDAP</span>
				</a>
			</div>
			<div class="d-flex w-100 w-xl-auto overflow-auto overflow-xl-visible scrollbar-hidden border-top border-top-xl-0 order-1 order-xl-0 pt-2 pt-xl-0 mt-2 mt-xl-0">
				<ul class="nav gap-1 justify-content-center flex-nowrap flex-xl-wrap mx-auto">
                    <li class="nav-item">
						<a href="{{ url('auth/profile') }}" class="navbar-nav-link rounded">
							<i class="ph-user-circle me-2"></i>
							Profil
						</a>
					</li>
                    <li class="nav-item">
						<a href="{{ url('auth/change-password') }}" class="navbar-nav-link rounded">
							<i class="ph-lock me-2"></i>
							Ganti Password
						</a>
					</li>
                    <li class="nav-item">
						<a href="javascript:void(0);" class="navbar-nav-link text-danger rounded" onclick="logout()">
							<i class="ph-sign-out me-2"></i>
							Keluar
						</a>
					</li>
				</ul>
			</div>
			<ul class="nav gap-1 flex-xl-1 justify-content-end order-0 order-xl-1">
				<li class="nav-item">
					<a href="javascript:void(0);" class="navbar-nav-link align-items-center rounded-pill p-1 bg-transparent no-click">
                        <img src="{{ asset('assets/user.png') }}" class="w-32px h-32px rounded-pill" alt="">
						<span class="d-none d-md-inline-block mx-md-2">{{ session('name') }}</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
    <div class="navbar navbar-sm shadow navbar-static iframeable">
		<div class="container-fluid">
			<div class="flex-fill overflow-auto overflow-lg-visible scrollbar-hidden">
				<ul class="nav gap-1 flex-nowrap flex-lg-wrap">
					<li class="nav-item">
						<a href="{{ url('dashboard') }}" class="navbar-nav-link rounded {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
							<i class="ph-chart-pie me-2"></i>
							Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ url('dashboard') }}" class="navbar-nav-link rounded {{ Request::segment(1) == 'request-file' ? 'active' : '' }}">
							<i class="ph-file-plus me-2"></i>
							Permintaan File
						</a>
					</li>
					<li class="nav-item nav-item-dropdown-lg dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded {{ Request::segment(1) == 'digital-storage-handover' ? 'active' : '' }}" data-bs-toggle="dropdown">
							<i class="ph-monitor-play me-2"></i>
							Serah Simpan Digital
						</a>
						<div class="dropdown-menu">
							<a href="{{ url('digital-storage-handover/draft') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'draft' ? 'active' : '' }}">Koleksi Draft</a>
                            <a href="{{ url('digital-storage-handover/reject') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'reject' ? 'active' : '' }}">Koleksi Ditolak</a>
                            <a href="{{ url('digital-storage-handover/problem') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'problem' ? 'active' : '' }}">Koleksi Bermasalah</a>
                            <a href="{{ url('digital-storage-handover/review') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'review' ? 'active' : '' }}">Koleksi Ditinjau</a>
                            <a href="{{ url('digital-storage-handover/accept') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'accept' ? 'active' : '' }}">Koleksi Diterima</a>
                            <a href="{{ url('digital-storage-handover/single-upload') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'single-upload' ? 'active' : '' }}">Unggah Tunggal</a>
                            <a href="{{ url('digital-storage-handover/bulk-upload') }}" class="dropdown-item rounded {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'bulk-upload' ? 'active' : '' }}">Unggah Banyak</a>
						</div>
					</li>
                    <li class="nav-item nav-item-dropdown-lg dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded {{ Request::segment(1) == 'physical-delivery' ? 'active' : '' }}" data-bs-toggle="dropdown">
							<i class="ph-archive-box me-2"></i>
							Pengiriman Fisik
						</a>
						<div class="dropdown-menu">
							<a href="{{ url('physical-delivery/form') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'form' ? 'active' : '' }}">Formulir</a>
							<a href="{{ url('physical-delivery/print-label') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'print-label' ? 'active' : '' }}">Cetak Label</a>
							<a href="{{ url('physical-delivery/input-receipt') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'input-receipt' ? 'active' : '' }}">Input Resi</a>
							<a href="{{ url('physical-delivery/in-delivery') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'in-delivery' ? 'active' : '' }}">Dalam Pengiriman</a>
							<a href="{{ url('physical-delivery/package-sent') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'package-sent' ? 'active' : '' }}">Paket Terkirim</a>
							<a href="{{ url('physical-delivery/accept') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'accept' ? 'active' : '' }}">Penerimaan</a>
							<a href="{{ url('physical-delivery/reject') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'reject' ? 'active' : '' }}">Koleksi Ditolak</a>
							<a href="{{ url('physical-delivery/grant') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'grant' ? 'active' : '' }}">Koleksi Dihibahkan</a>
							<a href="{{ url('physical-delivery/retur') }}" class="dropdown-item rounded {{ Request::segment(1) == 'physical-delivery' && Request::segment(2) == 'retur' ? 'active' : '' }}">Koleksi Dikembalikan</a>
						</div>
					</li>
                    <li class="nav-item">
						<a href="{{ url('bill-isbn') }}" class="navbar-nav-link rounded {{ Request::segment(1) == 'bill-isbn' ? 'active' : '' }}">
							<i class="ph-cardholder me-2"></i>
							Tagihan ISBN
						</a>
					</li>
					<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-auto">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded {{ Request::segment(1) == 'documentation' ? 'active' : '' }}" data-bs-toggle="dropdown">
							<i class="ph-books me-2"></i>
							Dokumentasi
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="{{ config('system.fo_url') }}/user-guide" class="dropdown-item rounded" target="_blank">Panduan Pengguna</a>
							<a href="{{ url('documentation/access-api') }}" class="dropdown-item rounded {{ Request::segment(1) == 'documentation' && Request::segment(2) == 'access-api' ? 'active' : '' }}">Akses API</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content-inner">
