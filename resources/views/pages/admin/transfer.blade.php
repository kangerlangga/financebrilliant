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
                    <ul class="breadcrumbs">
                        <a href="{{ route('transfer.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Pindah Dana
                        </a>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabel-transfer" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Asal</th>
                                                <th>Tujuan</th>
                                                <th>Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataTr as $T)
                                            <tr>
                                                <td>{{ $T->created_at }}</td>
                                                <td>{{ $T->tabungan_asal }}</td>
                                                <td>{{ $T->tabungan_tujuan }}</td>
                                                <td>
                                                    Rp {{ number_format($T->nominal, 0, ',', '.') }} 
                                                    @if ($T->admin > 0)
                                                        (Rp {{ number_format($T->admin, 0, ',', '.') }})
                                                    @endif
                                                </td>                                                
                                                <td>{{ $T->noted }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    $(document).ready(function() {
        $('#tabel-transfer').DataTable({
            "order": [[0, "desc"]],
            "columnDefs": [
                {
                    "targets": [0],
                    "type": "date"
                }
            ]
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
