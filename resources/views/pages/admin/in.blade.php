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
                    @if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Super Admin')
                    <ul class="breadcrumbs">
                        <a href="{{ route('in.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tambah Pemasukan
                        </a>
                    </ul>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabel-pemasukan" class="display table table-striped table-hover" >
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Nominal</th>
                                                <th>Kategori</th>
                                                <th>Program Terkait</th>
                                                <th>
                                                    @if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Super Admin')
                                                    Aksi
                                                    @else
                                                    Keterangan
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DataIn as $I)
                                            <tr>
                                                <td>{{ $I->created_at }}</td>
                                                <td class="text-danger">Rp {{ number_format($I->nominal_pengeluaran, 0, ',', '.') }}</td>
                                                <td>{{ $I->category_pengeluarans }}</td>
                                                <td>{{ $I->karyawan ?? '-' }}</td>
                                                @if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Super Admin')
                                                <td>
                                                    <div class="form-button-action">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-link btn-success" data-original-title="Riwayat" data-toggle="modal" data-target="#{{ $I->id_pemasukans }}">
                                                            <i class="fas fa-history"></i>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="{{ $I->id_pemasukans }}" tabindex="-1" role="dialog" aria-labelledby="{{ $I->id_pemasukans }}Label" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content" style="color: black">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="{{ $I->id_pemasukans }}Label"><b>Activity History</b></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: left;">
                                                                        <p>Created : <br>{{ $I->created_by }} <b>({{ $I->created_at }})</b></p>
                                                                        <p>Last Modified : <br>{{ $I->modified_by }} <b>({{ $I->updated_at }})</b></p>
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
                                                <td>{{ $I->noted ?? '-' }}</td>
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
       $('#tabel-pemasukan').DataTable({
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
