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
                            <form method="POST" id="transaksi-add" action="{{ route('trans.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="Tabungan">Tabungan</label>
                                            <select class="form-control" id="Tabungan" name="Tabungan" required>
                                                <option value="" disabled {{ request('rek') ? '' : 'selected' }}>Pilih Tabungan</option>
                                                @foreach ($DataT as $T)
                                                    <option value="{{ $T->id_tabungans }}"
                                                        {{ request('rek') == $T->id_tabungans ? 'selected' : '' }}>
                                                        {{ $T->nama_tabungans }}
                                                        @if ($T->category_tabungans === 'Non-Bank') 
                                                            (Tanpa Rekening)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Tanggal') has-error has-feedback @enderror">
                                            <label for="Tanggal">Tanggal</label>
                                            <input type="date" id="Tanggal" name="Tanggal" value="{{ date('Y-m-d') }}" class="form-control" max="{{ date('Y-m-d') }}" readonly style="cursor: not-allowed">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Debit') has-error has-feedback @enderror">
                                            <label for="Debit">Debit (Pemasukan)</label>
                                            <input type="number" id="Debit" name="Debit" value="{{ old('Debit', 0) }}" class="form-control" min="0" placeholder="Masukkan hanya Angka (Contoh : 125000)" required oninput="this.value = this.value.replace(/\D/g, '')">
                                            @error('Debit')
                                            <small id="Debit" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group @error('Kredit') has-error has-feedback @enderror">
                                            <label for="Kredit">Kredit (Pengeluaran)</label>
                                            <input type="number" id="Kredit" name="Kredit" value="{{ old('Kredit', 0) }}" class="form-control" min="0" placeholder="Masukkan hanya Angka (Contoh : 125000)" required oninput="this.value = this.value.replace(/\D/g, '')">
                                            @error('Kredit')
                                            <small id="Kredit" class="form-text text-muted">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="Saldo-Awal">Saldo Awal (Perkiraan)</label>
                                            <input class="form-control" name="Saldo-Awal" id="Saldo-Awal" readonly style="cursor: not-allowed">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="Saldo-Akhir">Saldo Akhir (Perkiraan)</label>
                                            <input class="form-control" name="Saldo-Akhir" id="Saldo-Akhir" readonly style="cursor: not-allowed">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group @error('Keterangan') has-error has-feedback @enderror">
                                            <label for="Keterangan">Keterangan Transaksi</label>
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
    $(document).ready(function () {
        function hitungSaldoAkhir() {
            var saldoAwal = parseInt($("#Saldo-Awal").val().replace(/\./g, "")) || 0;
            var debit = parseInt($("#Debit").val()) || 0;
            var kredit = parseInt($("#Kredit").val()) || 0;
            var saldoAkhir = saldoAwal - kredit + debit;
            $("#Saldo-Akhir").val(new Intl.NumberFormat('id-ID').format(saldoAkhir));
        }

        $("#Tabungan").change(function () {
            var tabungan = $(this).val();
            $.ajax({
                url: "/get-saldo/" + tabungan,
                type: "GET",
                success: function (response) {
                    var formattedSaldo = new Intl.NumberFormat('id-ID').format(response.saldo || 0);
                    $("#Saldo-Awal").val(formattedSaldo);
                    hitungSaldoAkhir();
                },
                error: function () {
                    $("#Saldo-Awal").val("0");
                    hitungSaldoAkhir();
                }
            });
        });

        $("#Debit, #Kredit, #Tabungan").on("input change", function () {
            hitungSaldoAkhir();
        });

        var tabunganAwal = $("#Tabungan").val();
        if (tabunganAwal) {
            $("#Tabungan").trigger("change");
        }
    });

    document.getElementById("btn-simpan").addEventListener("click", function() {
        let form = document.getElementById("transaksi-add");
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
