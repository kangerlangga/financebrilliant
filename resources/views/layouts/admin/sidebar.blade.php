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
                <li class="nav-item {{ Request::is('out*') ? 'active' : '' }}">
                    <a href="{{ route('out.data') }}">
                        <i class="fas fa-upload"></i>
                        <p>Pengeluaran</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('in*') ? 'active' : '' }}">
                    <a href="{{ route('in.data') }}">
                        <i class="fas fa-download"></i>
                        <p>Pemasukan</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('saldo*') ? 'active' : '' }}">
                    <a href="{{ route('saldo.data') }}">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Saldo</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('employee*') ? 'active' : '' }}">
                    <a href="{{ route('employee.data') }}">
                        <i class="fas fa-users"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('program*') ? 'active' : '' }}">
                    <a href="{{ route('program.data') }}">
                        <i class="fas fa-school"></i>
                        <p>Program</p>
                    </a>
                </li>
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
