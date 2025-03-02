<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
<style>
.card-pricing2.card-info {
    border-bottom-color: #404285;
}
.card-pricing2.card-info:before {
    background-color: #404285;
}
.card-pricing2 .pricing-content li.disable:before, .card-pricing2 .pricing-content li:before {
    background-color: #2bd67b;
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
                        <a href="{{ route('program.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Program Baru
                        </a>
                    </ul>
                </div>
                <div class="row">
                    @foreach ($DataP as $P)
                    <div class="col-md-4 pl-md-0">
                        <div class="card-pricing2 card-info">
                            <div class="pricing-header pb-0">
                                <h3 class="fw-bold">
                                    {{ $P->title_programs }} 
                                    @if ($P->visib_programs == 'Showing')
                                        <i class="fas fa-fw fa-solid fa-globe"></i>
                                    @elseif ($P->visib_programs == 'Hiding')
                                        <i class="fas fa-fw fa-solid fa-lock"></i>
                                    @endif
                                </h3>
                                <h1 class="fw-bold">Rp {{ number_format($P->price_programs, 0, ',', '.') }}</h1>
                                <span class="sub-title">{{ $P->category_programs }}</span>
                                <span class="sub-title">{{ $P->subtitle_programs }}</span>
                                <span class="sub-title">Admin : <b>Rp {{ number_format($P->admin_programs, 0, ',', '.') }}</b></span>
                            </div>
                            <ul class="pricing-content">
                                @foreach(explode("\n", $P->benefit_programs) as $bp)
                                    <li>{{ $bp }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('program.edit', $P->id_programs) }}">
                                <button type="button" class="btn btn-icon btn-round btn-warning">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </a>
                            <a href="{{ route('program.delete', $P->id_programs) }}" class="but-delete">
                                <button type="button" class="btn btn-icon btn-round btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </a>
                            @if (Auth::user()->level == 'Super Admin')
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-icon btn-round btn-success" data-toggle="modal" data-target="#{{ $P->id_programs }}">
                                    <i class="fas fa-history"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="{{ $P->id_programs }}" tabindex="-1" role="dialog" aria-labelledby="{{ $P->id_programs }}Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content" style="color: black">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="{{ $P->id_programs }}Label"><b>Activity History</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="text-align: left;">
                                                <p>Created : <br>{{ $P->created_by }} <b>({{ $P->created_at }})</b></p>
                                                <p>Last Modified : <br>{{ $P->modified_by }} <b>({{ $P->updated_at }})</b></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @include('layouts.admin.footer')
    </div>
</div>
@include('layouts.admin.script')
<script>
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
