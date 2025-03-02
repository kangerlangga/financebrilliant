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
                            <form method="POST" action="{{ route('prof.update.pass') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="Old-Pass">Password Lama</label>
                                            <input type="password" class="form-control" id="Old-Pass" name="oldPass" value="{{ old('oldPass') }}" placeholder="Masukkan Password Lama" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('newPass') has-error has-feedback @enderror">
                                            <label for="newPass">Password Baru</label>
                                            <input type="password" class="form-control" id="newPass" name="newPass" value="{{ old('newPass') }}" placeholder="Masukkan Password Baru" required>
                                            @error('newPass')
                                            <small id="newPass" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('password_confirmation') has-error has-feedback @enderror">
                                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Masukkan Password Baru" required>
                                            @error('password_confirmation')
                                            <small id="password_confirmation" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <label class="form-check-label" for="lihat-password">
                                                <input class="form-check-input" type="checkbox" value="" id="lihat-password">
                                                <span class="form-check-sign">Tampilkan Password</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success fw-bold text-uppercase">
                                                <i class="fas fa-save mr-2"></i>Simpan
                                            </button>
                                            <a href="{{ route('admin.dash') }}" class="btn btn-warning fw-bold text-uppercase but-back">
                                                <i class="fas fa-chevron-circle-left mr-2"></i>Kembali
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        icon: 'success',
        title: "{{ session('success') }}",
        text: 'Anda akan logout dalam beberapa detik!',
        showConfirmButton: false,
        timer: 5000
        })
        setTimeout(
        function(){
            document.getElementById('logout-admin').submit();
        },
        5000);
    @elseif(session('error'))
        Swal.fire({
            icon: "error",
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    $(document).ready(function() {
        $('#lihat-password').click(function() {
            if ($(this).is(':checked')) {
                $('#Old-Pass').attr('type', 'text');
                $('#newPass').attr('type', 'text');
                $('#password_confirmation').attr('type', 'text');
            } else {
                $('#Old-Pass').attr('type', 'password');
                $('#newPass').attr('type', 'password');
                $('#password_confirmation').attr('type', 'password');
            }
        });
    });

    $(document).on('click','.but-back',function(e) {

        e.preventDefault();
        const href1 = $(this).attr('href');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Perubahan tidak akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#fd7e14',
            confirmButtonText: 'KEMBALI',
            cancelButtonText: 'BATAL'
            }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href1;
            }
        });
    });
</script>
@endsection

<body>
    @yield('content')
</body>
</html>
