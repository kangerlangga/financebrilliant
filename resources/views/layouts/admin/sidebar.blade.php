<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="{{  url('') }}/assets/admin/img/user.png" alt="..." class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Str::limit(Auth::user()->name, 19) }}
                            <span class="user-level">{{ Str::limit(Auth::user()->jabatan, 19) }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('prof.edit') }}">
                                    <span class="link-collapse">Edit Profil</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('prof.edit.pass') }}">
                                    <span class="link-collapse">Ganti Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="confirmLogout()">
                                    <span class="link-collapse">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-info">
                <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <a href="{{ route('admin.dash') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (Auth::user()->level == 'Marketing' || Auth::user()->level == 'Super-User')
                <li class="nav-item {{ Request::is('coming*') ? 'active' : '' }}">
                    <a href="{{ route('coming.publik') }}">
                        <i class="fas fa-users"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'Frontliner' || Auth::user()->level == 'Super-User')
                <li class="nav-item {{ Request::is('coming*') ? 'active' : '' }}">
                    <a href="{{ route('coming.publik') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Verifikasi</p>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ Request::is('program*') ? 'active' : '' }}">
                    <a href="{{ route('program.data') }}">
                        <i class="fas fa-school"></i>
                        <p>Program</p>
                    </a>
                </li>
                @if (Auth::user()->level == 'Super-User' || Auth::user()->level == 'Finance')
                <li class="nav-item {{ Request::is('transaksi*') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#transaksi">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Transaksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="transaksi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('kas.data') }}">
                                    <span class="sub-item">Kas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('coming.publik') }}">
                                    <span class="sub-item">Bank BCA</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('coming.publik') }}">
                                    <span class="sub-item">Bank BRI</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('coming.publik') }}">
                                    <span class="sub-item">Bank BNI</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('coming.publik') }}">
                                    <span class="sub-item">Bank Mandiri</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Request::is('transfer*') ? 'active' : '' }}">
                    <a href="{{ route('coming.publik') }}">
                        <i class="fas fa-location-arrow"></i>
                        <p>Transfer</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('saldo*') ? 'active' : '' }}">
                    <a href="{{ route('coming.publik') }}">
                        <i class="fas fa-wallet"></i>
                        <p>Saldo</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('report*') ? 'active' : '' }}">
                    <a href="{{ route('coming.publik') }}">
                        <i class="fas fa-file-invoice"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level == 'Super-User')
                <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
                    <a href="{{ route('user.data') }}">
                        <i class="fas fa-user-cog"></i>
                        <p>Akun</p>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
