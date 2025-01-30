<div class="nav-header">
            <a href="index.html" class="brand-logo">
				<img src="{{ asset('assets/images/logo_gym.png') }}" alt="logo" style="width:25%;" />
				<div class="brand-title">
					<h2 class="" style="font-size:25px;"><strong>ELITE FITNESS</strong></h2>
					<span class="brand-sub-title">Kediri</span>
				</div>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        
        <div class="header border-bottom">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
							<div class="dashboard_bar">
                                Dashboard
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
							
							<li class="nav-item dropdown  header-profile">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
									{{-- <img src="images/user.jpg" width="56" alt=""> --}}
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="app-profile.html" class="dropdown-item ai-icon">
										<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
										<span class="ms-2">Profile </span>
									</a>
									<a href="email-inbox.html" class="dropdown-item ai-icon">
										<svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
										<span class="ms-2">Inbox </span>
									</a>
									<a href="page-error-404.html" class="dropdown-item ai-icon">
										<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
										<span class="ms-2">Logout </span>
									</a>
								</div>
							</li>
                        </ul>
                    </div>
				</nav>
			</div>
		</div>
        
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    <li><a href="{{ route('admin.dashboard') }}" aria-expanded="false">
							<i class="fas fa-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>	
                    </li>
                    @can('view-member')
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="nav-text">Informasi Member</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.data_member') }}">Data Member</a></li>
                            <li><a href="{{ route('admin.add_member') }}">Tambah Customer</a></li>
                        </ul>
                    </li>
                    @endcan
                    @can('view-trainer')
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-user-check"></i>
                            <span class="nav-text">Informasi Trainer</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.data_trainer') }}">Data Trainer</a></li>
                            <li><a href="{{ route('admin.add_trainer') }}">Tambah Trainer</a></li>
                            <li><a href="{{ route('admin.schedule') }}">Jadwal Trainer</a></li>
                        </ul>
                    </li>
                    @endcan
                    @can('view-laporan')
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-info-circle"></i>
                            <span class="nav-text">Laporan</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.report_allmember') }}">Kunjungan Harian</a></li>
                            <li><a href="{{ route('admin.report_day') }}">Member Baru Harian</a></li>
                            <li><a href="{{ route('admin.report_memberactive') }}">Total Member Aktif</a></li>
                            <li><a href="{{ route('admin.report_membernonactive') }}">Total Member Tidak Aktif</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('create-role')
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-shield-alt"></i>
                            <span class="nav-text">Role & Permission</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('roles') }}">Roles</a></li>
                            <li><a href="{{ url('permissions') }}">Permissions</a></li>
                            <li><a href="{{ url('users') }}">Manage Users</a></li>
                        </ul>
                    </li>
                    @endcan
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fa fa-cog fa-spin"></i>
                            <span class="nav-text">CMS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.report_allmember') }}">Layout</a></li>
                            <li><a href="{{ route('admin.report_day') }}">Social Media</a></li>
                            <li><a href="{{ route('admin.report_memberactive') }}">WA Notif</a></li>
                        </ul>
                    </li>
                </ul>
			</div>
        </div>