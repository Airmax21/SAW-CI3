<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Penilaian Siswa</h2>
        <p class="text-outline font-medium text-lg">Input tingkat perkembangan anak berdasarkan kriteria yang telah ditentukan.</p>
    </div>

    <form action="" method="GET" class="flex flex-wrap items-center gap-3 bg-surface-container-lowest p-3 rounded-[2rem] shadow-sm border border-outline-variant">

        <div class="flex items-center gap-2 px-4 border-r border-outline-variant">
            <span class="material-symbols-outlined text-secondary text-sm">group</span>
            <select name="class_id" class="border-none focus:ring-0 font-bold text-on-surface-variant bg-transparent cursor-pointer text-sm">
                <option value="" <?= empty($selected_class_id) ? 'selected' : '' ?>>Semua Kelas</option>

                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c->id ?>" <?= $selected_class_id == $c->id ? 'selected' : '' ?>>
                        Kelas <?= html_escape($c->class_name) ?> (<?= html_escape($c->academic_year) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-center gap-2 px-4">
            <span class="material-symbols-outlined text-secondary text-sm">calendar_month</span>
            <input type="month" name="period" value="<?= html_escape($selected_period) ?>"
                class="border-none focus:ring-0 font-bold text-on-surface-variant bg-transparent cursor-pointer text-sm">
        </div>

        <button type="submit" class="bg-primary text-on-primary px-6 py-3 rounded-full flex items-center gap-2 hover:bg-gray-900 transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-sm">search</span>
            <span class="text-xs font-black uppercase tracking-widest">Terapkan Filter</span>
        </button>
    </form>
</div>

<?php $this->load->view('components/alert'); ?>

<form action="<?= base_url('evaluation/store') ?>" method="POST" id="formEvaluation">
    <input type="hidden" name="period" value="<?= html_escape($selected_period) ?>">

    <div class="bg-surface-container-lowest rounded-[2.5rem] shadow-[0_12px_40px_rgba(0,0,0,0.03)] border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant min-w-[250px]">Nama Siswa</th>
                        <?php foreach ($criterias as $crit): ?>
                            <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-primary"><?= html_escape($crit->code) ?></span>
                                    <span class="text-[9px] text-on-surface-variant normal-case font-bold"><?= html_escape($crit->criteria_name) ?></span>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="<?= count($criterias) + 1 ?>" class="p-20 text-center">
                                <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">person_off</span>
                                <p class="text-outline font-bold">Belum ada data siswa terdaftar.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-primary-fixed/5 transition-colors group">
                            <td class="p-6">
                                <div class="flex items-center gap-4 pl-2">
                                    <div class="w-12 h-12 bg-secondary-container text-secondary rounded-2xl flex items-center justify-center font-black shadow-sm group-hover:scale-110 transition-transform">
                                        <?= strtoupper(substr($student->full_name ? $student->full_name : 'S', 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-background text-lg leading-tight"><?= html_escape($student->full_name) ?></p>
                                        <p class="text-[10px] font-black text-outline uppercase tracking-wider mt-1">Siswa Aktif</p>
                                    </div>
                                </div>
                            </td>

                            <?php foreach ($criterias as $crit): ?>
                                <td class="p-6">
                                    <?php
                                    // Mengambil nilai scores dari data matrix map yang di-generate level model
                                    $val = isset($student->scores[$crit->id]) ? $student->scores[$crit->id] : 0;
                                    ?>
                                    <div class="relative max-w-[160px] mx-auto">
                                        <select name="scores[<?= $student->id ?>][<?= $crit->id ?>]"
                                            onchange="updateSelectStyle(this)"
                                            class="eval-select w-full p-4 pr-10 bg-surface-container-low border-2 border-transparent rounded-2xl font-bold text-on-surface focus:border-primary-fixed focus:bg-white transition-all appearance-none cursor-pointer text-sm
                                                <?= $val > 0 ? 'border-primary-fixed bg-white shadow-sm' : '' ?>">
                                            <option value="0" <?= $val == 0 ? 'selected' : '' ?>>-- Pilih --</option>
                                            <option value="1" <?= $val == 1 ? 'selected' : '' ?>>BB (Belum Berkembang)</option>
                                            <option value="2" <?= $val == 2 ? 'selected' : '' ?>>MB (Mulai Berkembang)</option>
                                            <option value="3" <?= $val == 3 ? 'selected' : '' ?>>BSH (Sesuai Harapan)</option>
                                            <option value="4" <?= $val == 4 ? 'selected' : '' ?>>BSB (Sangat Baik)</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xl">expand_more</span>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 bg-surface-container-lowest p-6 rounded-[2.5rem] shadow-[0_12px_40px_rgba(124,82,170,0.15)] border border-primary-fixed flex flex-col sm:flex-row justify-between items-center gap-4 sticky bottom-8 z-30 backdrop-blur-md bg-white/90">
        <div class="flex items-center gap-4">
            <div class="bg-primary-container text-on-primary-container p-3 rounded-2xl">
                <span class="material-symbols-outlined">assignment_turned_in</span>
            </div>
            <div>
                <p class="font-black text-on-surface leading-tight text-sm uppercase tracking-wider">Simpan Perubahan</p>
                <p class="text-[11px] font-medium text-outline">Nilai yang diinput akan diproses untuk perhitungan SAW.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button type="reset" class="px-8 py-4 text-outline font-bold hover:bg-surface-container-high rounded-full transition-all text-sm">
                Reset
            </button>
            <button type="submit" class="flex-1 sm:flex-none bg-primary text-on-primary font-black text-sm uppercase tracking-[0.15em] px-10 py-5 rounded-full shadow-[0_8px_24px_rgba(224,64,160,0.35)] hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                <span class="material-symbols-outlined">save</span>
                Simpan Penilaian
            </button>
        </div>
    </div>
</form>

<script>
    /**
     * Mengubah border select jika sudah ada isinya agar lebih kontras
     */
    function updateSelectStyle(select) {
        if (select.value != "0") {
            select.classList.add('border-primary-fixed', 'bg-white', 'shadow-sm');
        } else {
            select.classList.remove('border-primary-fixed', 'bg-white', 'shadow-sm');
        }
    }

    // Inisialisasi style pada saat load halaman selesai
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.eval-select').forEach(select => {
            updateSelectStyle(select);
        });

        // Auto submit form filter ketika elemen select kelas atau input bulan diganti
        document.querySelectorAll('select[name="class_id"], input[name="period"]').forEach(element => {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>

<style>
    /* Menambah area klik untuk input month */
    input[type="month"] {
        position: relative;
        min-width: 150px;
        z-index: 5;
    }

    /* Pastikan calendar picker muncul saat area input diklik di beberapa browser */
    input[type="month"]::-webkit-calendar-picker-indicator {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        cursor: pointer;
        opacity: 0;
    }
</style>