<?php
$old_input = $this->session->flashdata('old_input');
$fieldErrors = $this->session->flashdata('errors');

// Sinkronisasi data gabungan: Prioritaskan input lama (jika gagal validasi), lalu data dari database object
$val_full_name = isset($old_input['full_name']) ? $old_input['full_name'] : (isset($student->full_name) ? $student->full_name : '');
$val_gender    = isset($old_input['gender']) ? $old_input['gender'] : (isset($student->gender) ? $student->gender : '');
$val_class_id  = isset($old_input['class_id']) ? $old_input['class_id'] : (isset($student->class_id) ? $student->class_id : '');
?>

<div class="max-w-3xl mx-auto pb-20">
    <div class="flex items-center gap-5 mb-10">
        <a href="<?= base_url('student') ?>" class="w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-4xl font-bold text-gray-900 tracking-tight"><?= html_escape($title) ?></h2>
            <p class="text-gray-500 font-medium mt-1">Lengkapi informasi biodata anak didik dengan benar.</p>
        </div>
    </div>

    <?php $this->load->view('components/alert'); ?>

    <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-xl shadow-gray-200/50 border border-gray-100">
        <form action="<?= $action ?>" method="POST">
            <div class="space-y-8">
                <div class="group">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-3 ml-1">Nama Lengkap Siswa</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 transition-colors <?= isset($fieldErrors['full_name']) ? 'text-red-500' : 'text-gray-400 group-focus-within:text-primary' ?>">person</span>
                        <input type="text" name="full_name"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-2xl border-2 transition-all outline-none <?= isset($fieldErrors['full_name']) ? 'border-red-200 bg-red-50 focus:border-red-400' : 'border-transparent focus:border-primary focus:bg-white' ?>"
                            placeholder="Masukkan nama lengkap anak..."
                            value="<?= html_escape($val_full_name) ?>">
                    </div>
                    <?php if (isset($fieldErrors['full_name'])) : ?>
                        <p class="text-red-600 text-xs mt-2 font-bold flex items-center gap-1 ml-1">
                            <span class="material-symbols-outlined text-sm">info</span> <?= html_escape($fieldErrors['full_name']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-3 ml-1">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="gender" value="L" class="peer hidden" <?= $val_gender === 'L' ? 'checked' : '' ?>>
                                <div class="py-4 text-center rounded-2xl border-2 border-gray-100 bg-gray-50 text-gray-500 font-bold peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                    Laki-laki
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="gender" value="P" class="peer hidden" <?= $val_gender === 'P' ? 'checked' : '' ?>>
                                <div class="py-4 text-center rounded-2xl border-2 border-gray-100 bg-gray-50 text-gray-500 font-bold peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-600 transition-all">
                                    Perempuan
                                </div>
                            </label>
                        </div>
                        <?php if (isset($fieldErrors['gender'])) : ?>
                            <p class="text-red-600 text-xs mt-2 font-bold ml-1"><?= html_escape($fieldErrors['gender']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-3 ml-1">Kelas PAUD</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 <?= isset($fieldErrors['class_id']) ? 'text-red-500' : 'text-gray-400 group-focus-within:text-primary' ?>">school</span>
                            <select name="class_id" class="w-full pl-12 pr-10 py-4 bg-gray-50 rounded-2xl border-2 outline-none transition-all appearance-none <?= isset($fieldErrors['class_id']) ? 'border-red-200 bg-red-50 focus:border-red-400' : 'border-transparent focus:border-primary focus:bg-white' ?>">
                                <option value="">Pilih Kelas</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class->id ?>" <?= $val_class_id == $class->id ? 'selected' : '' ?>>
                                        <?= html_escape($class->class_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                        <?php if (isset($fieldErrors['class_id'])) : ?>
                            <p class="text-red-600 text-xs mt-2 font-bold ml-1"><?= html_escape($fieldErrors['class_id']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row justify-end gap-4">
                    <a href="<?= base_url('student') ?>" class="order-2 md:order-1 px-8 py-4 text-center rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="order-1 md:order-2 px-12 py-4 bg-gray-900 text-white rounded-2xl font-bold shadow-lg shadow-gray-200 hover:bg-primary hover:shadow-primary/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
</style>