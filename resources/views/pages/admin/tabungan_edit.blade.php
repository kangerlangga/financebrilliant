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
                            <form method="POST" action="{{ route('tabungan.update', $EditTabungan->id_tabungans) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group @error('Images') has-error has-feedback @enderror">
                                            <label for="Images" class="form-label" id="imageLabel">
                                                Logo Bank Putih (PNG, JPG, JPEG)
                                                <span class="d-sm-none"><br></span>
                                                <span style="color: red;">Max 3 MB</span>
                                            </label>
											<input type="file" class="form-control-file" id="Images" name="Images" accept=".png, .jpg, .jpeg">
                                            @error('Images')
                                            <small id="Images" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="category">Kategori Tabungan (Cannot Be Changed)</label>
                                            <input class="form-control" name="category" value="{{ $EditTabungan->category_tabungans }}" id="category" readonly style="cursor: not-allowed">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option name='status' value='Aktif' {{ $EditTabungan->status_tabungans == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option name='status' value='Nonaktif' {{ $EditTabungan->status_tabungans == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Nama') has-error has-feedback @enderror">
                                            <label for="Nama" id="namaLabel" class="form-label">Nama Tabungan / Bank</label>
                                            <input type="text" id="Nama" name="Nama" value="{{ old('Nama', $EditTabungan->nama_tabungans) }}" class="form-control" placeholder="Contoh : Bank Central Asia (BCA)" required>
                                            @error('Nama')
                                            <small id="Nama" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Rekening') has-error has-feedback @enderror">
                                            <label for="Rekening" class="form-label" id="rekeningLabel">Nomor Rekening</label>
                                            <input type="text" id="Rekening" name="Rekening" value="{{ old('Rekening', $EditTabungan->rekening_tabungans) }}" 
                                                class="form-control" placeholder="Contoh: 131-00-4444298-8" 
                                                oninput="this.value = this.value.replace(/[^0-9\-]/g, '')" required>
                                            @error('Rekening')
                                            <small id="Rekening" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success fw-bold text-uppercase">
                                                <i class="fas fa-save mr-2"></i>Simpan
                                            </button>
                                            <a href="{{ route('tabungan.data') }}" class="btn btn-warning fw-bold text-uppercase but-back">
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
<script type="text/javascript">
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
            let isNonBank = category.value === "Non-Bank";
            imageInput.classList.toggle("d-none", isNonBank);
            imageLabel.classList.toggle("d-none", isNonBank);
            rekeningInput.classList.toggle("d-none", isNonBank);
            rekeningLabel.classList.toggle("d-none", isNonBank);
            rekeningInput.required = !isNonBank;
            rekeningInput.value = isNonBank ? "" : rekeningInput.value;
            namaLabel.textContent = isNonBank ? "Nama Tabungan" : "Nama Bank";
            namaInput.placeholder = isNonBank ? "Contoh : Tabungan Kas" : "Contoh : Bank Central Asia (BCA)";
        }
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
