<footer class="footer">
    <div class="container-fluid">
        <div class="copyright ml-auto">
            Copyright &copy; <?= date("Y")?> by <a href="{{  url('') }}" target="_blank">Finance Brilliant English Course</a>
        </div>
    </div>
</footer>
<form id="logout-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan logout dari akun Anda!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Logout!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-admin').submit();
        }
    });
}
</script>
