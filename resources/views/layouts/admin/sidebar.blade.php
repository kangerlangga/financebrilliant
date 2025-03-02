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
                <li class="nav-item {{ Request::is('admin') ? 'active' : '' }}">
                    <a href="{{ route('admin.dash') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/program*') ? 'active' : '' }}">
                    <a href="{{ route('program.data') }}">
                        <i class="fas fa-school"></i>
                        <p>Program</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/periode*') ? 'active' : '' }}">
                    <a href="{{ route('periode.data') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Periode</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/order*') ? 'active' : '' }}">
                    <a href="{{ route('order.data') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Transaksi</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/payment*') ? 'active' : '' }}">
                    <a href="{{ route('payment.data') }}">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Pembayaran</p>
                    </a>
                </li>
                @if (Auth::user()->level == 'Super Admin')
                <li class="nav-item {{ Request::is('admin/user*') ? 'active' : '' }}">
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
