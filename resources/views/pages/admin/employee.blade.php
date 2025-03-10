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
                        <a href="{{ route('employee.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tambah Karyawan
                        </a>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tabel Karyawan</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabel-karyawan" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataK as $K)
                                            <tr>
                                                <td>{{ $K->nip_employees }}</td>
                                                <td>{{ $K->name_employees }}</td>
                                                <td class="{{ $K->status_employees == 'Aktif' ? 'text-success' : 'text-danger' }}">{{ $K->status_employees }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        @if ($K->status_employees == 'Aktif')
                                                        <a href="{{ route('employee.nonaktif', $K->id_employees) }}">
                                                            <button type="button" data-toggle="tooltip" class="btn btn-link btn-primary btn-lg" data-original-title="Nonaktifkan">
                                                                <i class="fas fa-toggle-off"></i>
                                                            </button>
                                                        </a>
                                                        @elseif ($K->status_employees == 'Nonaktif')
                                                        <a href="{{ route('employee.aktif', $K->id_employees) }}">
                                                            <button type="button" data-toggle="tooltip" class="btn btn-link btn-primary btn-lg" data-original-title="Aktifkan">
                                                                <i class="fas fa-toggle-on"></i>
                                                            </button>
                                                        </a>
                                                        @endif
                                                        <a href="{{ route('employee.delete', $K->id_employees) }}" class="but-delete">
                                                            <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Hapus">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
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
       $('#tabel-karyawan').DataTable({
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

    //message with sweetalert
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
