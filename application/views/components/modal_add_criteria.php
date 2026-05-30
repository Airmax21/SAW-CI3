<!-- Ambil data temporary input lama jika ada error validasi sebelumnya -->
<?php
$old_input = $this->session->flashdata('old_input');
$old_code = isset($old_input['code']) ? $old_input['code'] : '';
$old_criteria_name = isset($old_input['criteria_name']) ? $old_input['criteria_name'] : '';
$old_type = isset($old_input['type']) ? $old_input['type'] : 'benefit';
$old_sub_names = isset($old_input['sub_names']) ? $old_input['sub_names'] : '';

// Jika ada error validasi kriteria, otomatis paksa buka kembali modalnya agar user langsung tahu
$errors = $this->session->flashdata('errors');
$show_modal_class = !empty($errors) && (isset($old_input['code']) || isset($old_input['criteria_name'])) ? '' : 'hidden';
?>

<div id="modalAdd" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] <?= $show_modal_class ?> flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-2xl font-black text-gray-900">Tambah Kriteria Baru</h3>
            <button onclick="document.getElementById('modalAdd').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Action diarahkan secara tepat ke endpoint controller Criteria CI3 -->
        <form action="<?= base_url('criteria/store') ?>" method="POST" class="p-8 space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Kode</label>
                    <input type="text" name="code" value="<?= html_escape($old_code) ?>" class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-primary font-bold" placeholder="C1" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Nama Aspek</label>
                    <input type="text" name="criteria_name" value="<?= html_escape($old_criteria_name) ?>" class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-primary font-bold" placeholder="Misal: Kognitif" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Tipe Penilaian</label>
                <select name="type" class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-primary font-bold appearance-none">
                    <option value="benefit" <?= $old_type === 'benefit' ? 'selected' : '' ?>>Benefit (Makin Tinggi Makin Baik)</option>
                    <option value="cost" <?= $old_type === 'cost' ? 'selected' : '' ?>>Cost (Makin Rendah Makin Baik)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Sub-Kriteria (Pisahkan dengan Koma)</label>
                <textarea name="sub_names" class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-primary font-medium text-sm min-h-[100px]" placeholder="Berhitung, Membaca, Mengenal Warna"><?= html_escape($old_sub_names) ?></textarea>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-on-primary rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-gray-900 transition-all">
                Simpan Kriteria
            </button>
        </form>
    </div>
</div>