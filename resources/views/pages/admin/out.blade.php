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
                        <a href="{{ route('out.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tambah Pengeluaran
                        </a>
                    </ul>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabel-pengeluaran" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Nominal</th>
                                                <th>Kategori</th>
                                                <th>Karyawan Terkait</th>
                                                <th>
                                                    @if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Super-User')
                                                    Aksi
                                                    @else
                                                    Keterangan
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataOut as $O)
                                            <tr>
                                                <td>{{ $O->created_at }}</td>
                                                <td class="text-danger">Rp {{ number_format($O->nominal_pengeluaran, 0, ',', '.') }}</td>
                                                <td>{{ $O->category_pengeluarans }}</td>
                                                <td>{{ $O->karyawan ?? '-' }}</td>
                                                @if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Super-User')
                                                <td>
                                                    <div class="form-button-action">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-link btn-success" data-original-title="Riwayat" data-toggle="modal" data-target="#{{ $O->id_pengeluarans }}">
                                                            <i class="fas fa-history"></i>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="{{ $O->id_pengeluarans }}" tabindex="-1" role="dialog" aria-labelledby="{{ $O->id_pengeluarans }}Label" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content" style="color: black">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="{{ $O->id_pengeluarans }}Label"><b>Activity History</b></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: left;">
                                                                        <p>Created : <br>{{ $O->created_by }} <b>({{ $O->created_at }})</b></p>
                                                                        <p>Last Modified : <br>{{ $O->modified_by }} <b>({{ $O->updated_at }})</b></p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                @else
                                                <td>{{ $O->noted ?? '-' }}</td>
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
       $('#tabel-pengeluaran').DataTable({
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
