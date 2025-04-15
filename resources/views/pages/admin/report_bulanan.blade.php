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
                    @if (isset($DataTr) && count($DataTr) > 0)
                    <ul class="breadcrumbs">
                        <a href="{{ route('report.bulanan') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Ubah Bulan
                        </a>
                    </ul>
                    @endif
                </div>
                <div class="row">
                    @if (!isset($DataTr))
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <form method="POST" action="{{ route('report.bulanan') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @error('DateR') has-error has-feedback @enderror">
                                            <label for="DateR">Bulan</label>
                                            <input type="month" class="form-control" id="DateR" name="DateR" max="{{ date('Y-m') }}" value="{{ old('DateR', isset($tanggalDipilih) ? \Carbon\Carbon::parse($tanggalDipilih)->format('Y-m') : '') }}" required>
                                            @error('DateR')
                                            <small id="DateR" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>                                    
                                    <div class="col-sm-12 mt-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary fw-bold text-uppercase">
                                                <i class="fas fa-clipboard-list mr-2"></i>Lihat Laporan
                                            </button>
                                            <button type="reset" class="btn btn-warning fw-bold text-uppercase">
                                                <i class="fas fa-undo mr-2"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    @elseif (isset($DataTr) && count($DataTr) > 0)
                    {{-- Hasil Laporan --}}
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Pengeluaran ({{ $tanggalFormatted }})</p>
                                            <h4 class="card-title">Rp {{ number_format($OutM, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-download"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Pemasukan ({{ $tanggalFormatted }})</p>
                                            <h4 class="card-title">Rp {{ number_format($InM, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Grafik Pengeluaran --}}
                    @if (collect($dataKeluar)->every(fn($v) => (int) $v > 0))
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Pengeluaran 6 Bulan Sebelumnya</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="keluarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Grafik Pemasukan --}}
                    @if (collect($dataMasuk)->every(fn($v) => (int) $v > 0))
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Pemasukan 6 Bulan Sebelumnya</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="masukChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Grafik Saldo --}}
                    @if (collect($dataSaldo)->every(fn($v) => (int) $v > 0))
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Saldo 6 Bulan Sebelumnya</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="saldoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title">Laporan Bulan {{ $tanggalFormatted }}</div>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" 
                                                   id="pills-semua-tab"
                                                   data-toggle="pill"
                                                   href="#pills-semua"
                                                   role="tab"
                                                   aria-selected="true">
                                                    Semua
                                                </a>
                                            </li>                                    
                                            @foreach ($DataTabungan as $tabungan)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $loop->first ? '' : '' }}" 
                                                id="pills-{{ $tabungan->id_tabungans }}-tab"
                                                data-toggle="pill"
                                                href="#pills-{{ $tabungan->id_tabungans }}"
                                                role="tab"
                                                aria-selected="false">
                                                    {{ $tabungan->nama_tabungans }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-semua" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="display table table-striped table-hover" id="tabel-laporan-semua">
                                                <thead>
                                                    <tr>
                                                        <th>Waktu</th>
                                                        <th>Tabungan</th>
                                                        <th>Keterangan</th>
                                                        <th>Debit (+)</th>
                                                        <th>Kredit (-)</th>
                                                        <th>Saldo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($DataTr as $T)
                                                    <tr>
                                                        <td>{{ $T->created_at }}</td>
                                                        <td>{{ $T->tabunganRelasi->nama_tabungans ?? $T->tabungan }}</td>
                                                        <td>{{ $T->noted }}</td>
                                                        <td>Rp {{ number_format($T->in_money, 0, ',', '.') }}</td>
                                                        <td>Rp {{ number_format($T->out_money, 0, ',', '.') }}</td>
                                                        <td>Rp {{ number_format($T->saldo_akhir, 0, ',', '.') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>                            
                                    @foreach ($DataTabungan as $index => $tabungan)
                                    <div class="tab-pane fade"
                                        id="pills-{{ $tabungan->id_tabungans }}" 
                                        role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="display table table-striped table-hover" id="tabel-laporan{{ $tabungan->id_tabungans }}">
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
                    @endif
                </div>
            </div>
        </div>
        @include('layouts.admin.footer')
    </div>
</div>
@include('layouts.admin.script')
<script>
    $(document).ready(function() {
        $('table[id^="tabel-laporan"]').each(function() {
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
@if (isset($DataTr) && count($DataTr) > 0)
{{-- Khusus Grafik --}}
<script>
    var keluarChart = document.getElementById('keluarChart').getContext('2d');
    var masukChart = document.getElementById('masukChart').getContext('2d');
    var saldoChart = document.getElementById('saldoChart').getContext('2d');

    var myKeluarChart = new Chart(keluarChart, {
        type: 'bar',
        data: {
            labels: @json($labels6),
            datasets: [{
                label: "Pengeluaran",
                backgroundColor: '#f25961',
                borderColor: '#f25961',
                data: @json($dataKeluar),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var myMasukChart = new Chart(masukChart, {
        type: 'bar',
        data: {
            labels: @json($labels6),
            datasets: [{
                label: "Pemasukan",
                backgroundColor: '#31ce36',
                borderColor: '#31ce36',
                data: @json($dataMasuk),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var mySaldoChart = new Chart(saldoChart, {
        type: 'bar',
        data: {
            labels: @json($labels6),
            datasets: [{
                label: "Saldo",
                backgroundColor: '#404285',
                borderColor: '#404285',
                data: @json($dataSaldo),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
@endif
@endsection

<body>
    @yield('content')
</body>
</html>
