<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Data Pribadi</h2>
        <p class="text-outline font-medium text-lg">Perbarui nama lengkap, username, dan kata sandi masuk Anda.</p>
    </div>
</div>

<?php $this->load->view('components/alert'); ?>

<div class="max-w-xl">
    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-gray-200/30 border border-gray-100">
        <form action="<?= base_url('profile/update') ?>" method="POST" class="space-y-6">
            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Masukkan nama lengkap"
                    value="<?= html_escape($teacher->name) ?>"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Username</label>
                <input type="text" name="username" required placeholder="Masukkan username"
                    value="<?= html_escape($teacher->username) ?>"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Kata Sandi Baru</label>
                <input type="password" name="password" placeholder="Masukkan kata sandi baru"
                    class="w-full px-5 py-3.5 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface focus:border-primary-fixed focus:bg-white transition-all outline-none">
                <p class="text-[10px] text-outline font-medium pl-2">Biarkan kosong jika tidak ingin mengubah kata sandi lama.</p>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-on-primary font-black text-sm uppercase tracking-wider px-8 py-4 rounded-full shadow-md shadow-primary/10 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
