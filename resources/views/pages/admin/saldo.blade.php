<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
<style>
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <img src="https://companieslogo.com/img/orig/BMRI.JK.D-f151a370.svg" height="25" alt="Visa Logo">
                                <h2 class="py-4 mb-0">Rp {{ number_format('9000000000000000', 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Bank Mandiri</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">131-00-4444298-8</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">30%</h3>
                                        {{-- <div class="text-small text-uppercase fw-bold op-8">Expired</div> --}}
                                    </div>
                                </div>
                            </div>
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
