<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-4xl font-black text-on-background mb-2">Manajemen Guru</h2>
        <p class="text-outline font-medium text-lg">Kelola hak akses pengguna sistem evaluasi kriteria SAW.</p>
    </div>
    <button onclick="openAddModal()" class="bg-primary text-on-primary font-black text-xs uppercase tracking-widest px-6 py-4 rounded-full shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">person_add</span> Tambah Guru
    </button>
</div>

<?php $this->load->view('components/alert'); ?>

<div class="bg-surface-container-lowest rounded-[2.5rem] shadow-[0_12px_40px_rgba(0,0,0,0.02)] border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low/50">
                    <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant">Informasi Guru</th>
                    <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant">Username</th>
                    <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant">Tanggal Terdaftar</th>
                    <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                <?php
                // Ambil ID guru yang sedang login aktif dari Session Userdata CI3
                $active_teacher_id = (int) $this->session->userdata('teacher_id');
                ?>
                <?php foreach ($teachers as $t): ?>
                    <tr class="hover:bg-primary-fixed/5 transition-colors group">
                        <td class="p-6">
                            <div class="flex items-center gap-4 pl-2">
                                <div class="w-12 h-12 bg-primary-fixed text-primary rounded-2xl flex items-center justify-center font-black text-base shadow-sm group-hover:scale-110 transition-transform">
                                    <?= strtoupper(substr($t->name ? $t->name : 'G', 0, 2)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-on-background text-lg leading-tight"><?= html_escape($t->name) ?></p>
                                    <p class="text-[10px] font-black text-outline uppercase tracking-wider mt-1">
                                        <?= $active_teacher_id === (int)$t->id ? 'Akun Anda Saat Ini' : 'Staf Pengajar' ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 font-bold text-on-surface-variant text-sm">@<?= html_escape($t->username) ?></td>
                        <td class="p-6 text-outline font-medium text-sm"><?= date('d M Y, H:i', strtotime($t->created_at)) ?></td>
                        <td class="p-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal(<?= $t->id ?>, '<?= html_escape($t->name) ?>', '<?= html_escape($t->username) ?>')" class="text-tertiary hover:bg-tertiary-fixed p-2.5 rounded-full transition-colors" title="Edit Data">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <?php if ($active_teacher_id !== (int)$t->id): ?>
                                    <button onclick="openDeleteModal(<?= $t->id ?>, '<?= html_escape($t->name) ?>')" class="text-red-500 hover:bg-red-50 p-2.5 rounded-full transition-colors" title="Hapus Akses">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="teacherModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border border-outline-variant transform scale-95 transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <h3 id="modalTitle" class="text-2xl font-black text-on-surface tracking-tight">Tambah Guru</h3>
            <button onclick="closeModal()" class="text-outline hover:bg-gray-100 p-2 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="teacherForm" action="<?= base_url('teacher/store') ?>" method="POST" class="space-y-5">
            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Nama Lengkap</label>
                <input type="text" name="name" id="formName" required placeholder="Masukkan nama lengkap"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Username</label>
                <input type="text" name="username" id="formUsername" required placeholder="Masukkan username login"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Password</label>
                <input type="password" name="password" id="formPassword" placeholder="Masukkan password"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
                <p id="passwordHelp" class="text-[10px] text-outline font-medium pl-2 hidden">Biarkan kosong jika tidak ingin mengubah password lama.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-3.5 text-outline font-bold hover:bg-gray-100 rounded-full text-sm transition-all">Batal</button>
                <button type="submit" class="bg-primary text-on-primary font-black text-sm uppercase tracking-wider px-8 py-3.5 rounded-full shadow-md shadow-primary/10 hover:scale-105 active:scale-95 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-[2rem] p-6 shadow-2xl border border-outline-variant text-center transform scale-95 transition-all duration-200">
        <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-2xl">no_accounts</span>
        </div>

        <h3 class="text-xl font-black text-on-surface tracking-tight mb-2">Hapus Akses Pengguna?</h3>
        <p class="text-sm text-outline font-medium leading-relaxed px-2">
            Tindakan ini akan menghapus seluruh hak akses log in milik <span id="deleteTargetName" class="font-bold text-on-surface"></span> secara permanen dari sistem.
        </p>

        <div class="flex justify-center gap-3 mt-6 w-full">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3 text-outline font-bold hover:bg-gray-100 rounded-full text-xs uppercase tracking-wider transition-all">
                Batal
            </button>
            <a id="deleteConfirmBtn" href="#" class="flex-1 bg-red-500 text-white font-black text-xs uppercase tracking-wider py-3 rounded-full shadow-md shadow-red-200 hover:bg-red-600 hover:scale-105 active:scale-95 transition-all flex items-center justify-center">
                Ya, Hapus
            </a>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('teacherModal');
    const form = document.getElementById('teacherForm');
    const modalTitle = document.getElementById('modalTitle');
    const formName = document.getElementById('formName');
    const formUsername = document.getElementById('formUsername');
    const formPassword = document.getElementById('formPassword');
    const passwordHelp = document.getElementById('passwordHelp');

    const deleteModal = document.getElementById('deleteModal');
    const deleteTargetName = document.getElementById('deleteTargetName');
    const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');

    function openAddModal() {
        modalTitle.textContent = "Tambah Guru Baru";
        form.action = "<?= base_url('teacher/store') ?>";
        formName.value = "";
        formUsername.value = "";
        formPassword.value = "";
        formPassword.required = true;
        passwordHelp.classList.add('hidden');
        modal.classList.remove('hidden');
    }

    function openEditModal(id, name, username) {
        modalTitle.textContent = "Edit Data Guru";
        form.action = "<?= base_url('teacher/update') ?>/" + id;
        formName.value = name;
        formUsername.value = username;
        formPassword.value = "";
        formPassword.required = false;
        passwordHelp.classList.remove('hidden');
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function openDeleteModal(id, name) {
        deleteTargetName.textContent = name;
        deleteConfirmBtn.href = "<?= base_url('teacher/delete') ?>/" + id;
        deleteModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
    }

    // Listener penutupan modal area luar backdrop yang aman saat DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        window.onclick = function(event) {
            if (event.target == modal) closeModal();
            if (event.target == deleteModal) closeDeleteModal();
        }
    });
</script>