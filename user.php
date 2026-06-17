<?php include 'partials/header.php'; ?>

<div class="main-content">

<div class="page-header">
    <h1><i class="fas fa-users-cog me-2" style="color:#C084FC;"></i>Manajemen <span>User</span></h1>
    <button id="btnTambah" class="btn-snap-primary">
        <i class="fas fa-user-plus"></i> Tambah User
    </button>
</div>

<div class="snap-card">
  <div class="card-body">
    <div style="overflow-x:auto;">
    <table class="snap-table w-100" id="tabel_user">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
  </div>
</div>

</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg,#FF6B9D,#C084FC); color:white; border:none;">
        <h5 class="modal-title" id="userModalLabel" style="font-weight:800; font-family:'Nunito',sans-serif;">
            <i class="fas fa-user-cog me-2"></i>User
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:24px;">
        <form id="userForm">
            <input type="hidden" id="user_id" name="user_id">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-1" style="color:#FF6B9D;"></i>Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-1" style="color:#FF6B9D;"></i>Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
        </form>
      </div>
      <div class="modal-footer" style="border:none; padding:16px 24px; background:#FFF0F5;">
        <button type="button" class="btn" data-bs-dismiss="modal"
                style="border-radius:50px; font-weight:700; background:#f0e0f0; border:none; padding:8px 20px;">
            Batal
        </button>
        <button type="button" id="btnSimpan" class="btn-snap-primary">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
        <button type="button" id="btnUpdate" style="display:none;"
                class="btn" style="background:linear-gradient(135deg,#FFD93D,#FF8C69); color:white; border:none; padding:8px 20px; border-radius:50px; font-weight:700;">
            <i class="fas fa-save me-1"></i> Update
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    toastr.options = {
        positionClass: 'toast-top-right',
        timeOut: 3500, progressBar: true, closeButton: true
    };

    tampilData();

    function tampilData() {
        $.ajax({
            url: "user/read.php",
            method: "GET",
            success: function (data) {
                try { $('#tabel_user').DataTable().clear().destroy(); } catch(e) {}
                $("#tabel_user tbody").html(data);
                $('#tabel_user').DataTable({ language: { url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/id.json' } });
            }
        });
    }

    $('#btnTambah').click(function () {
        $('#userModalLabel').html('<i class="fas fa-user-plus me-2"></i>Tambah User');
        $('#userModal').modal('show');
        $('#btnSimpan').show();
        $('#btnUpdate').hide();
        $('#userForm')[0].reset();
    });

    $('#btnSimpan').click(function () {
        var formData = $('#userForm').serialize();
        $.ajax({
            url: "user/create.php",
            method: "POST",
            data: formData,
            success: function (data) {
                $('#userModal').modal('hide');
                tampilData();
                toastr.success('User baru berhasil ditambahkan! 🎉', '✅ Berhasil!');
            },
            error: function () {
                toastr.error('Gagal menambahkan user!', '❌ Gagal!');
            }
        });
    });

    $('#btnUpdate').click(function () {
        var formData = $('#userForm').serialize();
        $.ajax({
            url: "user/update.php",
            method: "POST",
            data: formData,
            success: function (data) {
                $('#userModal').modal('hide');
                tampilData();
                toastr.success('Data user berhasil diperbarui! ✨', '✅ Berhasil!');
            },
            error: function () {
                toastr.error('Gagal memperbarui data user!', '❌ Gagal!');
            }
        });
    });

    $(document).on('click', '#btnEdit', function () {
        var user_id = $(this).data('id');
        $.ajax({
            url: "user/get.php",
            method: "GET",
            data: { user_id: user_id },
            success: function (data) {
                var user = JSON.parse(data);
                $('#user_id').val(user.id);
                $('#username').val(user.username);
                $('#password').val(user.password);
                $('#userModalLabel').html('<i class="fas fa-user-edit me-2"></i>Edit User');
                $('#userModal').modal('show');
                $('#btnSimpan').hide();
                $('#btnUpdate').show().css({
                    'display': 'inline-flex',
                    'background': 'linear-gradient(135deg,#FFD93D,#FF8C69)',
                    'color': 'white', 'border': 'none',
                    'padding': '10px 22px', 'border-radius': '50px',
                    'font-weight': '700', 'cursor': 'pointer',
                    'align-items': 'center', 'gap': '7px',
                    'font-family': "'Nunito',sans-serif"
                });
            }
        });
    });

    $(document).on('click', '#btnHapus', function () {
        var user_id = $(this).data('id');
        if (confirm('🗑️ Hapus user ini?\nData tidak dapat dikembalikan!')) {
            $.ajax({
                url: "user/delete.php",
                method: "POST",
                data: { user_id: user_id },
                success: function () {
                    tampilData();
                    toastr.success('User berhasil dihapus!', '✅ Berhasil!');
                },
                error: function () {
                    toastr.error('Gagal menghapus user!', '❌ Gagal!');
                }
            });
        }
    });
});
</script>

<?php include 'partials/footer.php'; ?>
