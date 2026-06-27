<?php

/**
 * Helper internal untuk menerjemahkan nama bulan ke Bahasa Indonesia
 */
if (!function_exists('translateMonth')) {
    function translateMonth($monthName)
    {
        $months = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];
        return isset($months[$monthName]) ? $months[$monthName] : $monthName;
    }
}
?>

<!-- Header Welcome Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
    <div>
        <!-- Memanggil nama guru secara dinamis menggunakan objek session CI3 -->
        <h2 class="text-4xl font-headline font-bold text-on-background mb-2">
            Selamat Datang, <?= html_escape($this->session->userdata('teacher_name') ? $this->session->userdata('teacher_name') : 'Guru') ?>!
        </h2>
        <p class="text-on-surface-variant font-medium text-lg">Berikut ringkasan perkembangan dan status penilaian anak-anak PAUD Mekar Sari Adong 1.</p>
    </div>

    <!-- Filter Pencarian Cepat Nama Siswa di Beranda (Arahkan ke controller student CI3) -->
    <div class="flex items-center gap-4 w-full md:w-auto">
        <form action="<?= base_url('student') ?>" method="GET" class="relative w-full md:w-64">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input name="search" class="w-full pl-12 pr-4 py-3 bg-surface-container rounded-full border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all text-on-surface shadow-sm text-sm" placeholder="Cari nama siswa..." type="text" />
        </form>
    </div>
</div>

<!-- Stats Bento Grid Dinamis -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(124,82,170,0.08)] hover:-translate-y-1 transition-transform duration-300 flex flex-col justify-between">
        <div class="w-12 h-12 bg-primary-container rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">groups</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-bold mb-1">Total Siswa</p>
            <p class="text-3xl font-headline font-bold text-on-background"><?= $totalStudents ?></p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(124,82,170,0.08)] hover:-translate-y-1 transition-transform duration-300 flex flex-col justify-between">
        <div class="w-12 h-12 bg-secondary-container rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-bold mb-1">Selesai Dinilai</p>
            <p class="text-3xl font-headline font-bold text-green-600"><?= $evaluatedCount ?></p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(124,82,170,0.08)] hover:-translate-y-1 transition-transform duration-300 flex flex-col justify-between">
        <div class="w-12 h-12 bg-tertiary-container rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings: 'FILL' 1;">pending</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-bold mb-1">Belum Dinilai</p>
            <p class="text-3xl font-headline font-bold text-amber-500"><?= $pendingCount ?></p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-primary to-secondary rounded-xl p-6 shadow-[0_8px_24px_rgba(224,64,160,0.2)] hover:-translate-y-1 transition-transform duration-300 flex flex-col justify-between text-white">
        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings: 'FILL' 1;">star</span>
        </div>
        <div>
            <p class="text-white/80 text-sm font-bold mb-1">Bulan Ini</p>
            <p class="text-3xl font-headline font-bold text-white"><?= translateMonth($currentMonth) ?></p>
        </div>
    </div>
</div>

<!-- Student Data Bento/Table View -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0_8px_30px_rgba(124,82,170,0.06)] overflow-hidden">
    <!-- Header Filter Row -->
    <div class="px-6 py-5 border-b border-surface-dim flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-surface-container-low/50">
        <h3 class="text-xl font-headline font-bold text-on-background flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">list_alt</span>
            Status Penilaian Kelas
        </h3>

        <!-- Filter Tab Kelas Aktif (Arahkan ke Dashboard Controller CI3) -->
        <?php if ($this->session->userdata('role') === 'admin'): ?>
            <div class="flex flex-wrap gap-1.5 bg-surface-container-high p-1 rounded-full border border-outline-variant">
                <a href="<?= base_url('dashboard') ?>"
                    class="px-4 py-1.5 text-xs font-black rounded-full transition-all <?= ($selected_class_id === null) ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface' ?>">
                    Semua Kelas
                </a>
                <?php foreach ($classes as $class): ?>
                    <a href="<?= base_url('dashboard?class_id=' . $class->id) ?>"
                        class="px-4 py-1.5 text-xs font-black rounded-full transition-all <?= ($selected_class_id === (int)$class->id) ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface' ?>">
                        Kelas <?= html_escape($class->class_name) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php
            $guru_class_name = '';
            foreach ($classes as $class) {
                if ((int)$class->id === $selected_class_id) {
                    $guru_class_name = $class->class_name;
                    break;
                }
            }
            ?>
            <?php if (!empty($guru_class_name)): ?>
                <span class="px-4 py-1.5 bg-primary-container text-primary text-xs font-black rounded-full">
                    Kelas <?= html_escape($guru_class_name) ?>
                </span>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- List Grid Data Asli -->
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php if (empty($students)): ?>
            <div class="col-span-1 md:col-span-2 xl:col-span-3 p-12 text-center text-on-surface-variant font-medium">
                <?php if ($this->session->userdata('role') === 'guru' && empty($selected_class_id)): ?>
                    Anda belum ditugaskan ke kelas manapun. Silakan hubungi Admin.
                <?php else: ?>
                    Belum ada data siswa terdaftar di kelas ini.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
                <div class="bg-surface rounded-xl border border-surface-dim p-4 flex items-center gap-4 hover:shadow-[0_4px_16px_rgba(124,82,170,0.1)] transition-all hover:border-secondary-fixed group">

                    <!-- Avatar Inisial Teks Dinamis -->
                    <div class="w-16 h-16 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-black text-lg border-2 border-surface shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                        <?= strtoupper(substr($student->full_name ? $student->full_name : 'S', 0, 2)) ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-headline font-bold text-on-background leading-tight truncate" title="<?= html_escape($student->full_name) ?>">
                            <?= html_escape($student->full_name) ?>
                        </h4>
                        <div class="flex items-center gap-2 mt-1 mb-2">
                            <span class="px-2 py-0.5 bg-secondary-container text-on-secondary-container text-[10px] font-black uppercase rounded-full">
                                Kelas <?= html_escape($student->class_name) ?>
                            </span>
                            <span class="text-xs text-outline font-medium">
                                <?= (isset($student->gender) && strtolower($student->gender) === 'l') ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </div>

                        <!-- Status Evaluasi Bulan Berjalan (Akses properti objek result CI3) -->
                        <?php if (isset($student->is_evaluated) && $student->is_evaluated): ?>
                            <div class="flex items-center gap-1 text-sm text-green-600">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                <span class="font-bold">Sudah Dinilai</span>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center gap-1 text-sm text-amber-500">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">pending</span>
                                <span class="font-bold">Belum Dinilai</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Jalan Pintas ke Evaluasi Input Nilai (Arahkan ke rute Evaluation Controller CI3) -->
                    <a href="<?= base_url('evaluation?class_id=' . $student->class_id . '&period=' . date('Y-m')) ?>" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-outline hover:bg-primary hover:text-white transition-colors bouncy-spring shrink-0" title="Input Penilaian">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Footer CTA Menu Utama -->
    <div class="p-4 border-t border-surface-dim bg-surface-container-low/30 text-center">
        <a href="<?= base_url('student') ?>" class="text-primary font-bold hover:text-primary-container transition-colors inline-flex items-center gap-2 text-sm decoration-none">
            Ke Manajemen Data Siswa Lengkap
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
</div>