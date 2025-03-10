<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
@php
    use Carbon\Carbon;
    $months = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    $currentMonth = $months[Carbon::now()->format('F')];
    $currentYear = Carbon::now()->format('Y');
@endphp
<div class="wrapper">
    <div class="main-header">
        @include('layouts.admin.nav')
    </div>
    @include('layouts.admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="panel-header" style="background: linear-gradient(to bottom right, #404285, #34356E);">
                <div class="page-inner">
                    <div class="mt-2 mb-4">
						<h2 class="text-white pb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
						<h5 class="text-white op-7 mb-4">The journey to transformation starts with the self before it reaches the world.</h5>
					</div>
                </div>
            </div>
            <div class="page-inner mt--5">
                <div class="row mt--2">
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small" style="background-color: #404285">
                                            <i class="flaticon-coins"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Saldo Saat Ini</p>
                                            <h4 class="card-title">Rp {{ number_format('999000000000000', 0, ',', '.') }}</h4>
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
                                        <div class="icon-big text-center icon-info bubble-shadow-small" style="background-color: #404285">
                                            <i class="flaticon-stopwatch"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category" id="dynamic-time1">---</p>
                                            <h4 class="card-title" id="dynamic-time2">---</h4>
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
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Pengeluaran ({{ $currentMonth }} {{ $currentYear }})</p>
                                            <h4 class="card-title">Rp {{ number_format('999000000000000', 0, ',', '.') }}</h4>
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
                                            <p class="card-category">Total Penghasilan ({{ $currentMonth }} {{ $currentYear }})</p>
                                            <h4 class="card-title">Rp {{ number_format('999000000000000', 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Pengeluaran 6 Bulan Terakhir</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="keluarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Penghasilan 6 Bulan Terakhir</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="masukChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Grafik Saldo 12 Bulan Terakhir</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="saldoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-12">
                        <div class="card full-height">
                            <div class="card-body">
                                <div class="card-title">Total income & spend statistics</div>
                                <div class="row py-3">
                                    <div class="col-md-4 d-flex flex-column justify-content-around">
                                        <div>
                                            <h6 class="fw-bold text-uppercase text-success op-8">Total Income</h6>
                                            <h3 class="fw-bold">$9.782</h3>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-uppercase text-danger op-8">Total Spend</h6>
                                            <h3 class="fw-bold">$1,248</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="chart-container">
                                            <canvas id="totalIncomeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @if (Auth::user()->level == 'Super Admin')
                    <div class="col-md-4">
                        <div class="card text-white" style="background: linear-gradient(to bottom right, #404285, #34356E);">
                            <div class="card-body">
                                <h4 class="mt-3 b-b1 pb-2 mb-3 fw-bold">Current Active Visitors</h4>
                                <h1 class="mb-4 fw-bold">{{ $cVO }}</h1>
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
<script>
    @if(session('successprof'))
        Swal.fire({
            icon: "success",
            title: "{{ session('successprof') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @elseif(session('successlog'))
        Swal.fire({
            icon: "success",
            title: "{{ session('successlog') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif
    // Client Side
    function updateTime() {
        const timeElement1 = document.getElementById('dynamic-time1');
        const timeElement2 = document.getElementById('dynamic-time2');
        const now = new Date();
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        const formattedDate = now.toLocaleDateString('en-GB', options);
        const formattedTime = now.toLocaleTimeString('en-GB', { hour12: false });
        timeElement1.textContent = `${formattedDate}`;
        timeElement2.textContent = `${formattedTime}`;
    }
    setInterval(updateTime, 1000);
    updateTime();
    // Server Side
    // async function updateTime() {
    //     const timeElement1 = document.getElementById('dynamic-time1');
    //     const timeElement2 = document.getElementById('dynamic-time2');
    //     try {
    //         const response = await fetch('/server-time');
    //         const data = await response.json();
    //         const serverTime = new Date(data.server_time);
    //         const options = { day: 'numeric', month: 'long', year: 'numeric' };
    //         const formattedDate = serverTime.toLocaleDateString('en-GB', options);
    //         const formattedTime = serverTime.toLocaleTimeString('en-GB', { hour12: false });
    //         timeElement1.textContent = formattedDate;
    //         timeElement2.textContent = formattedTime;
    //     } catch (error) {
    //         console.error('Failed to fetch server time:', error);
    //     }
    // }
    // setInterval(updateTime, 1000);
    // updateTime();
</script>
@include('layouts.admin.script')
<script>
    var keluarChart = document.getElementById('keluarChart').getContext('2d'),
    masukChart = document.getElementById('masukChart').getContext('2d'),
    saldoChart = document.getElementById('saldoChart').getContext('2d');
    // var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

    // var mytotalIncomeChart = new Chart(totalIncomeChart, {
    //     type: 'bar',
    //     data: {
    //         labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
    //         datasets : [{
    //             label: "Total Income",
    //             backgroundColor: '#ff9e27',
    //             borderColor: 'rgb(23, 125, 255)',
    //             data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
    //         }],
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         legend: {
    //             display: false,
    //         },
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     display: false //this will remove only the label
    //                 },
    //                 gridLines : {
    //                     drawBorder: false,
    //                     display : false
    //                 }
    //             }],
    //             xAxes : [ {
    //                 gridLines : {
    //                     drawBorder: false,
    //                     display : false
    //                 }
    //             }]
    //         },
    //     }
    // });

    var myKeluarChart = new Chart(keluarChart, {
        type: 'bar',
        data: {
            labels: ["Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Pengeluaran",
                backgroundColor: '#f25961',
                borderColor: '#f25961',
                data: [4, 6, 7, 8, 7, 4],
            }],
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
        }
    });

    var myMasukChart = new Chart(masukChart, {
        type: 'bar',
        data: {
            labels: ["Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Penghasilan",
                backgroundColor: '#31ce36',
                borderColor: '#31ce36',
                data: [4, 6, 7, 8, 7, 4],
            }],
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
        }
    });

    var mySaldoChart = new Chart(saldoChart, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Saldo",
                backgroundColor: '#404285',
                borderColor: '#404285',
                data: [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4],
            }],
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
        }
    });
</script>
@endsection

<body>
    @yield('content')
</body>
</html>
