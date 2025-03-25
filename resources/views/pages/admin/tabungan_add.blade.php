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
                            <form method="POST" action="{{ route('tabungan.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group @error('Images') has-error has-feedback @enderror">
                                            <label for="Images" class="form-label" id="imageLabel">
                                                Logo Bank Putih (PNG, JPG, JPEG)
                                                <span class="d-sm-none"><br></span>
                                                <span style="color: red;">Max 3 MB</span>
                                            </label>
											<input type="file" class="form-control-file" id="Images" name="Images" accept=".png, .jpg, .jpeg" required>
                                            @error('Images')
                                            <small id="Images" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="category">Kategori Tabungan</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value='Bank'>Bank (Dengan Rekening)</option>
                                                <option value='Non-Bank'>Non-Bank (Tanpa Rekening)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option name='status' value='Aktif'>Aktif</option>
                                                <option name='status' value='Nonaktif'>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Nama') has-error has-feedback @enderror">
                                            <label for="Nama" id="namaLabel" class="form-label">Nama Tabungan / Bank</label>
                                            <input type="text" id="Nama" name="Nama" value="{{ old('Nama') }}" class="form-control" placeholder="Contoh : Bank Central Asia (BCA)" required>
                                            @error('Nama')
                                            <small id="Nama" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Rekening') has-error has-feedback @enderror">
                                            <label for="Rekening" class="form-label" id="rekeningLabel">Nomor Rekening</label>
                                            <input type="text" id="Rekening" name="Rekening" value="{{ old('Rekening') }}" 
                                                class="form-control" placeholder="Contoh: 131-00-4444298-8" 
                                                oninput="this.value = this.value.replace(/[^0-9\-]/g, '')" required>
                                            @error('Rekening')
                                            <small id="Rekening" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
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
    document.addEventListener("DOMContentLoaded", function () {
        let category = document.getElementById("category");
        let rekeningInput = document.getElementById("Rekening");
        let rekeningLabel = document.getElementById("rekeningLabel");
        let namaLabel = document.getElementById("namaLabel");
        let namaInput = document.getElementById("Nama");
        let imageInput = document.getElementById("Images");
        let imageLabel = document.getElementById("imageLabel");
        function toggleRekening() {
            if (category.value === "Non-Bank") {
                imageInput.classList.add("d-none");
                imageLabel.classList.add("d-none");
                rekeningInput.classList.add("d-none");
                rekeningLabel.classList.add("d-none");
                rekeningInput.value = "";
                namaLabel.textContent = "Nama Tabungan";
                namaInput.placeholder = "Contoh : Tabungan Kas";
                rekeningInput.removeAttribute("required");
                imageInput.removeAttribute("required");
            } else {
                imageInput.classList.remove("d-none");
                imageLabel.classList.remove("d-none");
                rekeningInput.classList.remove("d-none");
                rekeningLabel.classList.remove("d-none");
                namaLabel.textContent = "Nama Bank";
                namaInput.placeholder = "Contoh : Bank Central Asia (BCA)";
                rekeningInput.setAttribute("required", "required");
                imageInput.setAttribute("required", "required");
            }
        }
        category.addEventListener("change", toggleRekening);
        toggleRekening();
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
