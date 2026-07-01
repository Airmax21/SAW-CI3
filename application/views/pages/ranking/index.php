<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Ranking Akhir Semester</h2>
        <p class="text-outline font-medium text-lg">Berdasarkan perhitungan Simple Additive Weighting (SAW)</p>
    </div>

    <!-- Filter Form & Actions -->
    <div class="flex flex-wrap items-center gap-3">
        <!-- Action dikosongkan agar otomatis submit ke URL aktif saat ini -->
        <form action="" method="GET" class="flex items-center gap-2 bg-surface-container-lowest p-1.5 rounded-full shadow-sm border border-outline-variant">

            <?php if ($this->session->userdata('role') === 'admin'): ?>
                <!-- Filter Kelas -->
                <div class="flex items-center gap-1.5 px-3 border-r border-outline-variant">
                    <span class="material-symbols-outlined text-secondary text-sm">group</span>
                    <select name="class_id" class="border-none focus:ring-0 font-bold text-on-surface-variant bg-transparent cursor-pointer text-xs pr-7 py-1">
                        <!-- Opsi Semua Kelas (Gunakan variabel pembanding dari Controller CI3) -->
                        <option value="" <?= empty($selected_class_id) ? 'selected' : '' ?>>Semua Kelas</option>

                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c->id ?>" <?= $selected_class_id == $c->id ? 'selected' : '' ?>>
                                Kelas <?= html_escape($c->class_name) ?> (<?= html_escape($c->academic_year) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <?php
                $guru_class_name = '';
                foreach ($classes as $c) {
                    if ($c->id == $selected_class_id) {
                        $guru_class_name = $c->class_name . ' (' . $c->academic_year . ')';
                        break;
                    }
                }
                ?>
                <?php if (!empty($guru_class_name)): ?>
                    <div class="flex items-center gap-1.5 px-3 border-r border-outline-variant text-xs font-bold text-on-surface-variant">
                        <span class="material-symbols-outlined text-secondary text-sm">group</span>
                        <span>Kelas: <?= html_escape($guru_class_name) ?></span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Filter Tanggal -->
            <div class="flex items-center gap-1.5 px-2 relative min-w-0">
                <span class="material-symbols-outlined text-secondary text-sm pointer-events-none">calendar_month</span>
                <input type="month" name="period" value="<?= html_escape($selected_period) ?>"
                    class="custom-month-input border-none focus:ring-0 font-bold text-on-surface-variant bg-transparent cursor-pointer text-xs p-0 w-32 uppercase tracking-wide">
            </div>

            <!-- Tombol Terapkan Filter -->
            <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-full flex items-center gap-1.5 hover:bg-gray-900 transition-all shadow-md shadow-primary/10 shrink-0">
                <span class="material-symbols-outlined text-xs">search</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Terapkan</span>
            </button>
        </form>

        <!-- Tombol Aksi Ekspor & Cetak (Mengarahkan ke rute endpoint controller Ranking CI3) -->
        <a href="<?= site_url('ranking/exportPdf?class_id=' . $selected_class_id . '&period=' . $selected_period) ?>"
            class="px-4 py-2 rounded-full bg-surface-container-high text-secondary font-bold hover:bg-secondary-container hover:scale-105 transition-all ease-out duration-300 flex items-center gap-1.5 text-xs shrink-0 decoration-none">
            <span class="material-symbols-outlined text-sm">download</span> Ekspor PDF
        </a>

        <!-- Button Cetak memicu fungsi window.print() -->
        <button onclick="window.print()"
            class="px-4 py-2 rounded-full bg-tertiary text-on-tertiary font-bold shadow-[0_4px_12px_rgba(0,150,204,0.15)] hover:scale-105 transition-all ease-out duration-300 flex items-center gap-1.5 text-xs shrink-0">
            <span class="material-symbols-outlined text-sm">print</span> Cetak
        </button>
    </div>
</div>

<div class="lg:col-span-2 space-y-6">

    <?php if (!empty($ranking)): ?>
        <!-- Podium Cards (Hanya muncul jika ada data) -->
        <div class="grid grid-cols-3 gap-4 mb-8 items-end">

            <!-- Rank 2 Card -->
            <?php if (isset($ranking[1])): ?>
                <?php $rank2 = $ranking[1]; ?>
                <div onclick="selectStudent('<?= html_escape($rank2->student_name) ?>', 2, '<?= number_format($rank2->total_score * 100, 1) ?>')" class="bg-surface rounded-[20px] p-4 text-center border-t-4 border-tertiary shadow-[0_8px_24px_rgba(0,150,204,0.1)] hover:-translate-y-2 transition-transform duration-300 flex flex-col items-center cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-tertiary-fixed flex items-center justify-center text-tertiary font-black text-xl mb-3 shadow-[0_4px_12px_rgba(0,150,204,0.2)]">2</div>
                    <div class="w-14 h-14 rounded-full bg-tertiary text-on-tertiary flex items-center justify-center font-black text-sm mb-2 border-2 border-surface shadow-sm">
                        <?= strtoupper(substr($rank2->student_name, 0, 2)) ?>
                    </div>
                    <h3 class="font-bold text-on-surface text-sm truncate w-full"><?= html_escape($rank2->student_name) ?></h3>
                    <p class="text-tertiary font-bold text-sm"><?= number_format($rank2->total_score * 100, 1) ?></p>
                </div>
            <?php endif; ?>

            <!-- Rank 1 Card -->
            <?php if (isset($ranking[0])): ?>
                <?php $rank1 = $ranking[0]; ?>
                <div onclick="selectStudent('<?= html_escape($rank1->student_name) ?>', 1, '<?= number_format($rank1->total_score * 100, 1) ?>')" class="bg-primary-fixed rounded-[20px] p-5 text-center border-t-4 border-primary shadow-[0_12px_32px_rgba(224,64,160,0.15)] hover:-translate-y-2 transition-transform duration-300 flex flex-col items-center cursor-pointer relative z-10 transform scale-105">
                    <span class="material-symbols-outlined absolute -top-4 text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-on-primary font-black text-2xl mb-3 shadow-[0_4px_16px_rgba(224,64,160,0.3)] mt-2">1</div>
                    <div class="w-16 h-16 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-black text-base mb-2 border-2 border-surface shadow-sm">
                        <?= strtoupper(substr($rank1->student_name, 0, 2)) ?>
                    </div>
                    <h3 class="font-bold text-on-surface text-base truncate w-full"><?= html_escape($rank1->student_name) ?></h3>
                    <p class="text-primary font-black text-lg"><?= number_format($rank1->total_score * 100, 1) ?></p>
                </div>
            <?php endif; ?>

            <!-- Rank 3 Card -->
            <?php if (isset($ranking[2])): ?>
                <?php $rank3 = $ranking[2]; ?>
                <div onclick="selectStudent('<?= html_escape($rank3->student_name) ?>', 3, '<?= number_format($rank3->total_score * 100, 1) ?>')" class="bg-surface rounded-[20px] p-4 text-center border-t-4 border-secondary shadow-[0_8px_24px_rgba(124,82,170,0.1)] hover:-translate-y-2 transition-transform duration-300 flex flex-col items-center cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center text-secondary font-black text-xl mb-3 shadow-[0_4px_12px_rgba(124,82,170,0.2)]">3</div>
                    <div class="w-14 h-14 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-black text-sm mb-2 border-2 border-surface shadow-sm">
                        <?= strtoupper(substr($rank3->student_name, 0, 2)) ?>
                    </div>
                    <h3 class="font-bold text-on-surface text-sm truncate w-full"><?= html_escape($rank3->student_name) ?></h3>
                    <p class="text-secondary font-bold text-sm"><?= number_format($rank3->total_score * 100, 1) ?></p>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>

    <!-- Ranking List Data Table -->
    <div class="bg-surface rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden relative">
        <div class="p-6 border-b border-surface-container-high bg-surface-container-lowest">
            <h3 class="font-bold text-lg text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">format_list_numbered</span>
                Daftar Lengkap Kelulusan / Penilaian
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-sm font-bold uppercase tracking-wider">
                        <th class="p-4 rounded-tl-lg">Rank</th>
                        <th class="p-4">Nama Lengkap Anak</th>
                        <th class="p-4">Skor Total</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ranking)): ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center text-on-surface-variant font-medium">
                                <?php if ($this->session->userdata('role') === 'guru' && empty($selected_class_id)): ?>
                                    Anda belum ditugaskan ke kelas manapun. Silakan hubungi Admin.
                                <?php else: ?>
                                    Tidak ada data peringkat pada periode atau kelas ini.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ranking as $index => $row): ?>
                            <?php
                            $rankNum = $index + 1;
                            $finalScore = number_format($row->total_score * 100, 1);

                            // Dynamic Badge Styling
                            if ($finalScore >= 85) {
                                $statusLabel = 'Sangat Baik';
                                $statusClass = 'bg-tertiary-fixed text-tertiary';
                            } elseif ($finalScore >= 75) {
                                $statusLabel = 'Baik';
                                $statusClass = 'bg-secondary-container text-secondary';
                            } else {
                                $statusLabel = 'Cukup';
                                $statusClass = 'bg-surface-dim text-on-surface-variant';
                            }

                            // Row Highlight style for top 3
                            $rowHighlight = ($rankNum <= 3) ? 'bg-primary-fixed/5' : '';
                            $rankColorClass = ($rankNum === 1) ? 'text-primary text-xl' : (($rankNum === 2) ? 'text-tertiary text-lg' : (($rankNum === 3) ? 'text-secondary text-lg' : 'text-on-surface-variant text-sm'));
                            ?>
                            <tr onclick="selectStudent('<?= html_escape($row->student_name) ?>', <?= $rankNum ?>, '<?= $finalScore ?>')" class="border-b border-surface-container-high hover:bg-primary-fixed/10 transition-colors cursor-pointer group <?= $rowHighlight ?>">
                                <td class="p-4 font-black <?= $rankColorClass ?>"><?= $rankNum ?></td>
                                <td class="p-4 font-bold text-on-surface flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-black text-xs shadow-inner">
                                        <?= strtoupper(substr($row->student_name, 0, 2)) ?>
                                    </div>
                                    <?= html_escape($row->student_name) ?>
                                </td>
                                <td class="p-4 font-bold <?= ($rankNum <= 3) ? $rankColorClass : 'text-on-surface' ?>"><?= $finalScore ?></td>
                                <td class="p-4 text-center">
                                    <span class="inline-block px-3 py-1 font-bold text-xs rounded-full <?= $statusClass ?>"><?= $statusLabel ?></span>
                                </td>
                                <td class="p-4 text-right">
                                    <button type="button" class="text-tertiary hover:bg-tertiary-fixed p-2 rounded-full transition-colors opacity-0 group-hover:opacity-100">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit form filter ketika elemen select kelas atau input bulan diganti
        document.querySelectorAll('select[name="class_id"], input[name="period"]').forEach(element => {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>

<style>
    /* Trik CSS: Sembunyikan icon kalender bawaan chrome agar input bisa dipersempit secara ekstrem */
    .custom-month-input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Memastikan text input tidak punya min-width tersembunyi */
    .custom-month-input {
        min-width: 0 !important;
        -moz-appearance: textfield;
    }

    @media print {

        /* Sembunyikan elemen dashboard yang tidak perlu ikut tercetak di kertas */
        aside,
        nav,
        form,
        button,
        a,
        .sticky,
        .lg\:col-span-1 {
            display: none !important;
        }

        /* Lebarkan tabel utama agar memenuhi halaman kertas */
        .lg\:col-span-2 {
            width: 100% !important;
            grid-column: span 3 / span 3 !important;
        }

        main {
            padding: 0 !important;
        }
    }
</style>