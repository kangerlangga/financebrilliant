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
                            <form method="POST" action="{{ route('program.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="category">Kategori Program</label>
                                            <select class="form-control" id="category" name="category">
                                                <option name='category' value='Kelas Offline'>Kelas Offline</option>
                                                <option name='category' value='Kelas Online'>Kelas Online</option>
                                                <option name='category' value='Kelas Offline + Holiday'>Kelas Offline + Holiday</option>
                                                <option name='category' value='Paket Liburan (Khusus Member)'>Paket Liburan (Khusus Member)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Title') has-error has-feedback @enderror">
                                            <label for="Title">Judul Program</label>
                                            <input type="text" id="Title" name="Title" value="{{ old('Title') }}" class="form-control" placeholder="Masukkan Judul" required>
                                            @error('Title')
                                            <small id="Title" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Subtitle') has-error has-feedback @enderror">
                                            <label for="Subtitle">Subjudul Program</label>
                                            <input type="text" id="Subtitle" name="Subtitle" value="{{ old('Subtitle') }}" class="form-control" placeholder="Masukkan Subjudul (Contoh : 90 Hari)" required>
                                            @error('Subtitle')
                                            <small id="Subtitle" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Price') has-error has-feedback @enderror">
                                            <label for="Price">Harga Program</label>
                                            <input type="number" id="Price" name="Price" value="{{ old('Price') }}" class="form-control" min="1" placeholder="Masukkan hanya Angka (Contoh : 125000)" required oninput="this.value = this.value.replace(/\D/g, '')">
                                            @error('Price')
                                            <small id="Price" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('AdminP') has-error has-feedback @enderror">
                                            <label for="AdminP">Biaya Admin (Gunakan 0 Jika Gratis)</label>
                                            <input type="number" id="AdminP" name="AdminP" value="{{ old('AdminP') }}" class="form-control" min="0" placeholder="Masukkan hanya Angka (Contoh : 125000)" required oninput="this.value = this.value.replace(/\D/g, '')">
                                            @error('AdminP')
                                            <small id="AdminP" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group @error('Benefit') has-error has-feedback @enderror">
                                            <label for="Benefit">Benefit (Baris Baru / Enter untuk Poin Baru)</label>
                                            <textarea class="form-control" id="Benefit" name="Benefit" placeholder="Benefit 1 &#10;Benefit 2">{{ old('Benefit') }}</textarea>
                                            @error('Benefit')
                                            <small id="Benefit" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="visibility">Visibilitas</label>
                                            <select class="form-control" id="visibility" name="visibility">
                                                <option name='visibility' value='Showing'>Tampilkan</option>
                                                <option name='visibility' value='Hiding'>Sembunyikan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary fw-bold text-uppercase">
                                                <i class="fas fa-save mr-2"></i>Simpan
                                            </button>
                                            <button type="reset" class="btn btn-warning fw-bold text-uppercase">
                                                <i class="fas fa-undo mr-2"></i>Reset
                                            </button>
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
