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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabel-saldo" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Debit</th>
                                                <th>Kredit</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataPKas as $K)
                                            <tr>
                                                <td>{{ $K->created_at }}</td>
                                                <td>{{ $K->noted }}</td>
                                                <td>Rp {{ number_format($K->out_debit, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($K->in_kredit, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($K->saldo_akhir, 0, ',', '.') }}</td>
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
       $('#tabel-saldo').DataTable({
            "columnDefs": [
                {
                    "targets": [0],
                    "type": "date"
                }
            ]
        });
    });

    $(document).on('click','.but-delete',function(e) {

        e.preventDefault();
        const href1 = $(this).attr('href');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan Dihapus Secara Permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#fd7e14',
            confirmButtonText: 'HAPUS',
            cancelButtonText: 'BATAL'
            }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href1;
            }
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
