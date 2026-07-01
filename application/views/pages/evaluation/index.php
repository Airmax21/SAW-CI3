<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Penilaian Anak</h2>
        <p class="text-outline font-medium text-lg">Input tingkat perkembangan anak berdasarkan kriteria yang telah ditentukan.</p>
    </div>

    <form action="" method="GET" class="flex flex-wrap items-center gap-3 bg-surface-container-lowest p-3 rounded-[2rem] shadow-sm border border-outline-variant">

        <?php if ($this->session->userdata('role') === 'admin'): ?>
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
                <div class="flex items-center gap-2 px-4 border-r border-outline-variant text-sm font-bold text-on-surface-variant">
                    <span class="material-symbols-outlined text-secondary text-sm">group</span>
                    <span>Kelas: <?= html_escape($guru_class_name) ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex items-center gap-2 px-4">
            <span class="material-symbols-outlined text-secondary text-sm">calendar_month</span>
            <input type="month" name="period" value="<?= html_escape($selected_period) ?>"
                class="border-none focus:ring-0 font-bold text-on-surface-variant bg-transparent cursor-pointer text-sm">
        </div>
    </form>
</div>

<?php $this->load->view('components/alert'); ?>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-8 right-8 z-50 pointer-events-none space-y-3"></div>

<form action="<?= base_url('evaluation/store') ?>" method="POST" id="formEvaluation">
    <input type="hidden" name="period" id="periodInput" value="<?= html_escape($selected_period) ?>">

    <div class="bg-surface-container-lowest rounded-[2.5rem] shadow-[0_12px_40px_rgba(0,0,0,0.03)] border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant min-w-[280px]">Nama Lengkap Anak</th>
                        <?php foreach ($criterias as $crit): ?>
                            <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant text-center min-w-[170px]">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-primary font-bold"><?= html_escape($crit->code) ?></span>
                                    <span class="text-[9px] text-on-surface-variant normal-case font-bold"><?= html_escape($crit->criteria_name) ?></span>
                                </div>
                            </th>
                        <?php endforeach; ?>
                        <th class="p-8 text-[10px] font-black uppercase text-outline tracking-[0.2em] border-b border-outline-variant text-center min-w-[160px]">Tanda / Simpan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="<?= count($criterias) + 2 ?>" class="p-20 text-center">
                                <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">person_off</span>
                                <p class="text-outline font-bold">
                                    <?php if ($this->session->userdata('role') === 'guru' && empty($selected_class_id)): ?>
                                        Anda belum ditugaskan ke kelas manapun. Silakan hubungi Admin.
                                    <?php else: ?>
                                        Belum ada data anak terdaftar.
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-primary-fixed/5 transition-colors group">
                            <td class="p-6">
                                <div class="flex items-center gap-4 pl-2">
                                    <div class="w-12 h-12 bg-secondary-container text-secondary rounded-2xl flex items-center justify-center font-black shadow-sm group-hover:scale-110 transition-transform">
                                        <?= strtoupper(substr($student->full_name ? $student->full_name : 'A', 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-background text-lg leading-tight"><?= html_escape($student->full_name) ?></p>
                                        <p class="text-[10px] font-black text-outline uppercase tracking-wider mt-1">Anak Aktif</p>
                                    </div>
                                </div>
                            </td>

                            <?php foreach ($criterias as $crit): ?>
                                <td class="p-6">
                                    <?php
                                    // Mengambil nilai scores dari data matrix map yang di-generate level model
                                    $val = isset($student->scores[$crit->id]) ? $student->scores[$crit->id] : 0;
                                    ?>
                                    <div class="relative max-w-[150px] mx-auto">
                                        <select name="scores[<?= $student->id ?>][<?= $crit->id ?>]"
                                            onchange="updateSelectStyle(this); updateRowStatus(<?= $student->id ?>);"
                                            <?= $this->session->userdata('role') === 'admin' ? 'disabled' : '' ?>
                                            style="background-image: none;"
                                            class="eval-select w-full p-4 pr-10 border-2 rounded-2xl font-bold focus:border-primary-fixed focus:bg-white transition-all appearance-none cursor-pointer text-xs"
                                            data-student-id="<?= $student->id ?>"
                                            data-criteria-id="<?= $crit->id ?>"
                                            data-value="<?= $val ?>">
                                            <option value="0" <?= $val == 0 ? 'selected' : '' ?>>-- Pilih --</option>
                                            <option value="1" <?= $val == 1 ? 'selected' : '' ?>>BB (Belum Berkembang)</option>
                                            <option value="2" <?= $val == 2 ? 'selected' : '' ?>>MB (Mulai Berkembang)</option>
                                            <option value="3" <?= $val == 3 ? 'selected' : '' ?>>SH (Sesuai Harapan)</option>
                                            <option value="4" <?= $val == 4 ? 'selected' : '' ?>>SB (Sangat Baik)</option>
                                        </select>
                                        <?php if ($this->session->userdata('role') !== 'admin'): ?>
                                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xl">expand_more</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endforeach; ?>

                            <td class="p-6 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <!-- Hitung status lengkap di level PHP -->
                                    <?php
                                    $is_complete = TRUE;
                                    foreach ($criterias as $crit) {
                                        $val_check = isset($student->scores[$crit->id]) ? $student->scores[$crit->id] : 0;
                                        if ($val_check == 0) {
                                            $is_complete = FALSE;
                                            break;
                                        }
                                    }
                                    ?>
                                    <!-- Badge Status Per Baris -->
                                    <div id="status-badge-<?= $student->id ?>" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 min-w-[100px] justify-center transition-all duration-300
                                        <?= $is_complete ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-600 border border-amber-200' ?>">
                                        <span class="material-symbols-outlined text-xs flex-shrink-0" id="status-icon-<?= $student->id ?>"><?= $is_complete ? 'check_circle' : 'pending' ?></span>
                                        <span id="status-text-<?= $student->id ?>"><?= $is_complete ? 'Selesai' : 'Belum' ?></span>
                                    </div>

                                    <!-- Tombol Simpan Per Baris (Hanya untuk Guru) -->
                                    <?php if ($this->session->userdata('role') === 'guru'): ?>
                                        <button type="button" onclick="saveRow(<?= $student->id ?>, '<?= html_escape($student->full_name) ?>')"
                                            class="w-full max-w-[100px] px-3 py-2 bg-[#7c52aa] text-white hover:bg-[#e040a0] rounded-xl text-[10px] font-black uppercase tracking-wider hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-1 shadow-sm">
                                            <span class="material-symbols-outlined text-xs">save</span> Simpan
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

    <!-- Container tombol simpan massal (Hanya muncul untuk Guru) -->
    <?php if ($this->session->userdata('role') === 'guru' && !empty($students)): ?>
        <div class="mt-10 bg-surface-container-lowest p-6 rounded-[2.5rem] shadow-[0_12px_40px_rgba(124,82,170,0.15)] border border-primary-fixed flex flex-col sm:flex-row justify-between items-center gap-4 sticky bottom-8 z-30 backdrop-blur-md bg-white/90">
            <div class="flex items-center gap-4">
                <div class="bg-primary-container text-on-primary-container p-3 rounded-2xl">
                    <span class="material-symbols-outlined">assignment_turned_in</span>
                </div>
                <div>
                    <p class="font-black text-on-surface leading-tight text-sm uppercase tracking-wider">Simpan Semua Perubahan</p>
                    <p class="text-[11px] font-medium text-outline">Nilai yang diinput akan diproses untuk perhitungan SAW.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="reset" class="px-8 py-4 text-outline font-bold hover:bg-surface-container-high rounded-full transition-all text-sm">
                    Reset
                </button>
                <button type="submit" class="flex-1 sm:flex-none bg-primary text-on-primary font-black text-sm uppercase tracking-[0.15em] px-10 py-5 rounded-full shadow-[0_8px_24px_rgba(224,64,160,0.35)] hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Semua Penilaian
                </button>
            </div>
        </div>
    <?php endif; ?>
</form>

<script>
    /**
     * Mengubah warna select element berdasarkan nilai tingkat perkembangan secara real-time
     */
    function updateSelectStyle(select) {
        // Hapus kelas warna bawaan lama
        select.classList.remove(
            'bg-red-50', 'text-red-600', 'border-red-200',
            'bg-amber-50', 'text-amber-600', 'border-amber-200',
            'bg-blue-50', 'text-blue-600', 'border-blue-200',
            'bg-green-50', 'text-green-600', 'border-green-200',
            'bg-surface-container-low', 'text-on-surface', 'border-transparent'
        );

        const val = select.value;
        if (val === "1") {
            select.classList.add('bg-red-50', 'text-red-600', 'border-red-200');
        } else if (val === "2") {
            select.classList.add('bg-amber-50', 'text-amber-600', 'border-amber-200');
        } else if (val === "3") {
            select.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
        } else if (val === "4") {
            select.classList.add('bg-green-50', 'text-green-600', 'border-green-200');
        } else {
            select.classList.add('bg-surface-container-low', 'text-on-surface', 'border-transparent');
        }
    }

    /**
     * Memeriksa dan memperbarui badge status Selesai/Belum per baris secara dinamis di klien
     */
    function updateRowStatus(studentId) {
        const selects = document.querySelectorAll(`.eval-select[data-student-id="${studentId}"]`);
        let complete = true;
        
        selects.forEach(select => {
            if (select.value === "0" || select.value === "") {
                complete = false;
            }
        });

        const badge = document.getElementById('status-badge-' + studentId);
        const icon = document.getElementById('status-icon-' + studentId);
        const text = document.getElementById('status-text-' + studentId);

        if (badge && icon && text) {
            badge.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 min-w-[100px] justify-center transition-all duration-300";
            if (complete) {
                badge.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
                icon.textContent = "check_circle";
                text.textContent = "Selesai";
            } else {
                badge.classList.add('bg-amber-50', 'text-amber-600', 'border', 'border-amber-200');
                icon.textContent = "pending";
                text.textContent = "Belum";
            }
        }
    }

    /**
     * Mengirim dan menyimpan penilaian satu baris (single student) menggunakan AJAX Fetch API
     */
    function saveRow(studentId, studentName) {
        const period = document.getElementById('periodInput').value;
        const selects = document.querySelectorAll(`.eval-select[data-student-id="${studentId}"]`);
        
        const scores = {};
        selects.forEach(select => {
            const criteriaId = select.dataset.criteriaId;
            scores[criteriaId] = parseInt(select.value);
        });

        const payload = {
            period: period,
            scores: {
                [studentId]: scores
            }
        };

        // Buat efek visual loading pada tombol simpan
        showToast('Sedang menyimpan penilaian ' + studentName + '...', 'info');

        fetch('<?= base_url("evaluation/store") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Penilaian untuk ' + studentName + ' berhasil disimpan!', 'success');
                updateRowStatus(studentId);
            } else {
                showToast(data.message || 'Gagal menyimpan penilaian.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Kesalahan koneksi saat menyimpan penilaian.', 'error');
        });
    }

    /**
     * Menampilkan pesan Toast melayang yang interaktif dan modern
     */
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = "flex items-center gap-3 px-5 py-4 rounded-2xl bg-white border shadow-xl text-xs font-bold transition-all duration-300 pointer-events-auto transform translate-y-2 opacity-0";
        
        let icon = 'info';
        let colorClass = 'text-blue-600 border-blue-100 bg-blue-50';
        if (type === 'success') {
            icon = 'check_circle';
            colorClass = 'text-green-600 border-green-100 bg-green-50';
        } else if (type === 'error') {
            icon = 'error';
            colorClass = 'text-red-600 border-red-100 bg-red-50';
        }

        toast.innerHTML = `
            <span class="material-symbols-outlined text-sm ${type === 'success' ? 'text-green-500' : (type === 'error' ? 'text-red-500' : 'text-blue-500')}">${icon}</span>
            <span class="text-gray-800">${message}</span>
        `;
        
        container.appendChild(toast);

        // Memicu transisi masuk
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        }, 10);

        // Hapus otomatis setelah beberapa detik
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3500);
    }

    // Inisialisasi awal saat halaman selesai di-render
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