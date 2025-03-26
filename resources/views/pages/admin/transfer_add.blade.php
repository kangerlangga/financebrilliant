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
                            <form method="POST" id="transfer-add" action="{{ route('transfer.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="TabunganAsal">Tabungan Asal</label>
                                            <select class="form-control" id="TabunganAsal" name="TabunganAsal" required>
                                                <option value="" selected disabled>Pilih Tabungan</option>
                                                @foreach ($DataT as $T)
                                                    <option value="{{ $T->id_tabungans }}">
                                                        {{ $T->nama_tabungans }} 
                                                        @if ($T->category_tabungans === 'Non-Bank') 
                                                            (Tanpa Rekening)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="TabunganTujuan">Tabungan Tujuan</label>
                                            <select class="form-control" id="TabunganTujuan" name="TabunganTujuan" required>
                                                <option value="" selected disabled>Pilih Tabungan</option>
                                                @foreach ($DataT as $T)
                                                    <option value="{{ $T->id_tabungans }}">
                                                        {{ $T->nama_tabungans }} 
                                                        @if ($T->category_tabungans === 'Non-Bank') 
                                                            (Tanpa Rekening)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group d-none" id="saldo-awal-container">
                                            <label for="Saldo-Awal">Saldo Tabungan Asal</label>
                                            <input class="form-control" name="Saldo-Awal" id="Saldo-Awal" readonly style="cursor: not-allowed">
                                        </div>
                                    </div>                                    
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Nominal') has-error has-feedback @enderror">
                                            <label for="Nominal">Nominal</label>
                                            <input type="number" id="Nominal" name="Nominal" value="{{ old('Nominal', 0) }}" class="form-control" min="1" placeholder="Masukkan hanya Angka (Contoh : 125000)" required>
                                            @error('Nominal')
                                            <small id="Nominal" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Admin') has-error has-feedback @enderror">
                                            <label for="Admin">Biaya Admin (Gunakan 0 Jika Gratis)</label>
                                            <input type="number" id="Admin" name="Admin" value="{{ old('Admin', 0) }}" class="form-control" min="0" placeholder="Masukkan hanya Angka (Contoh : 125000)" required>
                                            @error('Admin')
                                            <small id="Admin" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group @error('Keterangan') has-error has-feedback @enderror">
                                            <label for="Keterangan">Keterangan Pindah Dana</label>
                                            <input type="text" id="Keterangan" name="Keterangan" value="{{ old('Keterangan') }}" class="form-control" placeholder="Masukkan Keterangan" required>
                                            @error('Keterangan')
                                            <small id="Keterangan" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-1">
                                        <div class="form-group">
                                            <button type="button" id="btn-simpan" class="btn btn-primary fw-bold text-uppercase">
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
        const tabunganAsal = document.getElementById("TabunganAsal");
        const tabunganTujuan = document.getElementById("TabunganTujuan");
        const nominal = document.getElementById("Nominal");
        const admin = document.getElementById("Admin");
        const saldoAwal = document.getElementById("Saldo-Awal");
        const saldoAwalContainer = document.getElementById("saldo-awal-container");

        tabunganAsal.addEventListener("change", function () {
            if (tabunganAsal.value) {
                saldoAwalContainer.classList.remove("d-none");
            } else {
                saldoAwalContainer.classList.add("d-none");
            }
        });

        let saldoTabungan = {};

        function fetchSaldo(tabungan) {
            if (!tabungan) return;

            $.ajax({
                url: "/get-saldo/" + tabungan,
                type: "GET",
                success: function (response) {
                    saldoTabungan[tabungan] = parseInt(response.saldo) || 0;
                    saldoAwal.value = new Intl.NumberFormat('id-ID').format(saldoTabungan[tabungan]);
                },
                error: function () {
                    saldoTabungan[tabungan] = 0;
                    saldoAwal.value = "0";
                }
            });
        }

        function validateTabungan() {
            if (tabunganAsal.value === tabunganTujuan.value) {
                Swal.fire({
                    icon: "error",
                    title: "Tabungan Asal dan Tujuan tidak boleh sama!",
                    showConfirmButton: false,
                    timer: 3000
                });
                tabunganTujuan.value = "";
            }
        }

        function validateNominal() {
            let saldo = saldoTabungan[tabunganAsal.value] || 0;
            let totalTransfer = parseInt(nominal.value || 0) + parseInt(admin.value || 0);

            if (totalTransfer > saldo) {
                Swal.fire({
                    icon: "error",
                    title: "Nominal transfer + Biaya Admin melebihi saldo tabungan asal!",
                    showConfirmButton: false,
                    timer: 3000
                });
                nominal.value = 0;
                admin.value = 0;
            }
        }

        tabunganAsal.addEventListener("change", function () {
            fetchSaldo(tabunganAsal.value);
            validateTabungan();
        });

        tabunganTujuan.addEventListener("change", validateTabungan);
        nominal.addEventListener("input", validateNominal);
        admin.addEventListener("input", validateNominal);
    });

    document.getElementById("btn-simpan").addEventListener("click", function() {
        let form = document.getElementById("transfer-add");
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        Swal.fire({
            title: 'Apakah data sudah benar?',
            text: "Data yang disimpan tidak bisa diubah.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#fd7e14',
            confirmButtonText: 'SIMPAN',
            cancelButtonText: 'BATAL'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
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
