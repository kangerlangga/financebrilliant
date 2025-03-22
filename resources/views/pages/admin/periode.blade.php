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
                    @if (Auth::user()->level == 'Finance' || Auth::user()->level == 'Super-User')
                    <ul class="breadcrumbs">
                        <a href="{{ route('periode.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tambah Periode
                        </a>
                    </ul>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tabel Periode</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>Periode</th>
                                                <th>Kategori</th>
                                                <th>Status</th>
                                                @if (Auth::user()->level == 'Finance' || Auth::user()->level == 'Super-User')
                                                <th>Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataP as $P)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($P->date_periodes)->locale('id')->translatedFormat('Y-m-d (l)') }}</td>
                                                <td>{{ $P->category_periodes }}</td>
                                                <td class="{{ $P->status_periodes == 'Aktif' ? 'text-success' : 'text-danger' }}">{{ $P->status_periodes }}</td>
                                                @if (Auth::user()->level == 'Finance' || Auth::user()->level == 'Super-User')
                                                <td>
                                                    <div class="form-button-action">
                                                        @if ($P->status_periodes == 'Aktif')
                                                        <a href="{{ route('periode.nonaktif', $P->id_periodes) }}">
                                                            <button type="button" data-toggle="tooltip" class="btn btn-link btn-primary btn-lg" data-original-title="Nonaktifkan">
                                                                <i class="fas fa-toggle-on"></i>
                                                            </button>
                                                        </a>
                                                        @elseif ($P->status_periodes == 'Nonaktif')
                                                        <a href="{{ route('periode.aktif', $P->id_periodes) }}">
                                                            <button type="button" data-toggle="tooltip" class="btn btn-link btn-primary btn-lg" data-original-title="Aktifkan">
                                                                <i class="fas fa-toggle-off"></i>
                                                            </button>
                                                        </a>
                                                        @endif
                                                        <a href="{{ route('periode.delete', $P->id_periodes) }}" class="but-delete">
                                                            <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Hapus">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                                @endif
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
       $('#basic-datatables').DataTable({
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
