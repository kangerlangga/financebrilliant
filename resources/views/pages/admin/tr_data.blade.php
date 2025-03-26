<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
<style>
    .nav-pills .nav-link.active {
        background-color: #404285 !important;
    }
    .card .card-header .card-head-row .card-tools {
        margin-left: 0;
    }
    @media (max-width: 768px) {
        .page-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .breadcrumbs {
            padding-left: 0 !important;
            margin-left: 0 !important;
        }
    }
</style>
<div class="wrapper">
    <div class="main-header">
        @include('layouts.admin.nav')
    </div>
    @include('layouts.admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="page-header">
                    <h4 class="page-title">{{ $judul }}</h4>
                    <ul class="breadcrumbs">
                        <a href="{{ route('trans.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Catat Transaksi
                        </a>
                    </ul>
                </div>
    
                <!-- 🔹 NAVIGATION TABS -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-tools">
                                <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
                                    @foreach ($DataTabungan as $index => $tabungan)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                                           id="pills-{{ $tabungan->id_tabungans }}-tab"
                                           data-toggle="pill"
                                           href="#pills-{{ $tabungan->id_tabungans }}"
                                           role="tab"
                                           aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                            {{ $tabungan->nama_tabungans }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
    
                    <!-- 🔹 TAB CONTENT -->
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            @foreach ($DataTabungan as $index => $tabungan)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
                                 id="pills-{{ $tabungan->id_tabungans }}" 
                                 role="tabpanel">
                                <div class="table-responsive">
                                    <table class="display table table-striped table-hover" id="tabel-transaksi{{ $tabungan->id_tabungans }}">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Debit (+)</th>
                                                <th>Kredit (-)</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataTr as $T)
                                                @if ($T->tabungan == $tabungan->id_tabungans)
                                                <tr>
                                                    <td>{{ $T->created_at }}</td>
                                                    <td>{{ $T->noted }}</td>
                                                    <td>Rp {{ number_format($T->in_money, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($T->out_money, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($T->saldo_akhir, 0, ',', '.') }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
        @include('layouts.admin.footer')
    </div>
</div>
@include('layouts.admin.script')
<script>
    $(document).ready(function() {
        $('table[id^="tabel-transaksi"]').each(function() {
            $('#' + this.id).DataTable({
                "order": [[0, "desc"]],
                "columnDefs": [
                    {
                        "targets": [0],
                        "type": "date"
                    }
                ]
            });
        });
    });

    @if(session('success'))
    Swal.fire({
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @elseif(session('error'))
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @endif
</script>
@endsection

<body>
    @yield('content')
</body>
</html>
