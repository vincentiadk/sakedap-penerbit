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
					<li class="nav-item nav-item-dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown" data-bs-auto-close="outside">
							<i class="ph-list me-2"></i>
							Menu
						</a>
						<div class="dropdown-menu p-0">
							<div class="d-flex">
								<div class="d-flex flex-row flex-xl-column bg-light overflow-auto overflow-xl-visible rounded-top rounded-top-xl-0 rounded-start-xl">
									<div class="flex-1 border-bottom border-bottom-xl-0 p-2 p-xl-3">
                                        <div class="fw-bold border-bottom d-none d-xl-block pb-2 mb-2">Main Menu</div>
                                        <div style="max-height:60vh; overflow-y:auto; overflow-x:hidden;">
                                            <ul class="nav nav-pills flex-xl-column flex-nowrap text-nowrap justify-content-center wmin-xl-300" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a href="{{ url('dashboard') }}" class="nav-link rounded {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                                                        <i class="ph-chart-pie me-2"></i>
                                                        Dasboard
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a href="{{ url('request-file') }}" class="nav-link rounded {{ Request::segment(1) == 'request-file' ? 'active' : '' }}">
                                                        <i class="ph-file-plus me-2"></i>
                                                        Permintaan File
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a href="#menu-digital-storage-handover" class="nav-link rounded {{ Request::segment(1) == 'digital-storage-handover' ? 'active' : '' }}" data-bs-toggle="tab" aria-selected="{{ Request::segment(1) == 'digital-storage-handover' ? 'true' : 'false' }}" role="tab">
                                                        <i class="ph-monitor-play me-2"></i>
                                                        Serah Simpan Digital
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
									</div>
								</div>
								<div class="tab-content flex-xl-1 main-menu-sub">
                                    <div class="tab-pane dropdown-scrollable-xl fade p-3 {{ Request::segment(1) == 'digital-storage-handover' ? 'show active' : '' }}" id="menu-digital-storage-handover" role="tabpanel">
                                        <div class="row" style="max-height:65vh; overflow-y:auto; overflow-x:hidden;">
                                            <div class="col-md-12">
                                                <div class="alert alert-info text-center fw-semibold">Sub Menu</div>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="{{ url('digital-storage-handover/draft') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'draft' ? 'active' : '' }}">Koleksi Draft</a>
                                                <a href="{{ url('digital-storage-handover/reject') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'reject' ? 'active' : '' }}">Koleksi Ditolak</a>
                                                <a href="{{ url('digital-storage-handover/problem') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'problem' ? 'active' : '' }}">Koleksi Bermasalah</a>
                                                <a href="{{ url('digital-storage-handover/review') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'review' ? 'active' : '' }}">Koleksi Ditinjau</a>
                                                <a href="{{ url('digital-storage-handover/single-upload') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'single-upload' ? 'active' : '' }}">Unggah Tunggal</a>
                                                <a href="{{ url('digital-storage-handover/bulk-upload') }}" class="dropdown-item rounded pb-0 {{ Request::segment(1) == 'digital-storage-handover' && Request::segment(2) == 'bulk-upload' ? 'active' : '' }}">Unggah Banyak</a>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</li>
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
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content-inner">
