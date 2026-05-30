<?php
$old_weights = $this->session->flashdata('old_weights');
$old_input = $this->session->flashdata('old_input');

// Hitung total bobot awal secara dinamis
$totalWeight = 0;
foreach ($criterias as $c) {
    // Gunakan nilai dari state input lama jika ada error validasi sebelumnya
    $current_w = isset($old_weights[$c->id]) ? (float) $old_weights[$c->id] : (float) $c->weight;
    $totalWeight += $current_w;
}
?>

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Pengaturan Kriteria</h2>
        <p class="text-outline font-medium text-lg">Sesuaikan bobot prioritas untuk setiap aspek perkembangan anak.</p>
    </div>

    <div class="flex items-center gap-3 bg-secondary-container px-5 py-3 rounded-full shadow-[0_4px_16px_rgba(124,82,170,0.1)] transition-colors duration-500"
        id="weightContainer">
        <span class="font-bold text-secondary">Total Bobot:</span>
        <span id="totalWeightDisplay" class="text-xl font-black text-primary"><?= $totalWeight ?>%</span>
    </div>
</div>

<?php
$errors = $this->session->flashdata('errors');
if (!empty($errors)):
?>
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-xs font-bold flex items-start gap-2">
        <span class="material-symbols-outlined text-sm mt-0.5">error</span>
        <div>
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php
$success = $this->session->flashdata('success');
if (!empty($success)):
?>
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-2xl text-xs font-bold flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        <p><?= $success ?></p>
    </div>
<?php endif; ?>

<form action="<?= base_url('criteria/update') ?>" method="POST">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <?php foreach ($criterias as $crit): ?>
            <?php
            // Tetapkan nilai bobot per kriteria (cek state lama jika ada)
            $item_weight = isset($old_weights[$crit->id]) ? (float) $old_weights[$crit->id] : (float) $crit->weight;
            ?>
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(224,64,160,0.08)] border-2 border-primary-fixed hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-fixed rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>

                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                    <button type="button"
                        onclick="openDeleteModal('<?= base_url('criteria/delete/' . $crit->id) ?>', '<?= html_escape($crit->criteria_name) ?>')"
                        class="w-10 h-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>

                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="text-2xl font-bold text-on-background"><?= html_escape($crit->criteria_name) ?></h3>
                            <span class="bg-primary-fixed text-on-primary-fixed-variant text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider"><?= html_escape($crit->type) ?></span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-primary mb-1" id="label-<?= $crit->id ?>">
                            <?= $item_weight ?>%
                        </div>
                        <div class="text-sm font-medium text-outline"><?= html_escape($crit->code) ?></div>
                    </div>
                </div>

                <div class="mb-8">
                    <input name="weights[<?= $crit->id ?>]"
                        class="weight-slider w-full h-3 bg-surface-variant rounded-full appearance-none slider-thumb outline-none focus:ring-4 focus:ring-primary-fixed"
                        max="100" min="0" type="range" value="<?= $item_weight ?>"
                        data-id="<?= $crit->id ?>" />
                </div>

                <div class="bg-surface-container-low rounded-lg p-4">
                    <h4 class="font-bold text-secondary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">subdirectory_arrow_right</span>
                        Sub-kriteria Penilaian
                    </h4>
                    <div class="space-y-3">
                        <?php if (!empty($crit->subs)): ?>
                            <?php foreach ($crit->subs as $sub): ?>
                                <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded-lg border border-outline-variant shadow-sm hover:border-primary-fixed transition-colors">
                                    <span class="font-medium text-on-surface-variant"><?= html_escape($sub->sub_name) ?></span>
                                    <span class="bg-secondary-container text-secondary font-bold px-3 py-1 rounded-full text-[10px]">SAW</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-xs text-outline italic">Belum ada indikator penilaian.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="button" onclick="document.getElementById('modalAdd').classList.remove('hidden')"
            class="bg-surface-container-low rounded-xl p-6 border-2 border-dashed border-outline-variant hover:border-primary hover:bg-primary-fixed/20 transition-all duration-300 flex flex-col items-center justify-center min-h-[300px] group">
            <div class="bg-surface-container-lowest p-4 rounded-full shadow-sm mb-4 group-hover:scale-110 group-hover:bg-primary group-hover:text-on-primary transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-outline group-hover:text-on-primary">add</span>
            </div>
            <h3 class="text-xl font-bold text-secondary group-hover:text-primary">Tambah Kriteria Baru</h3>
            <p class="text-outline font-medium mt-2 text-center max-w-xs">Tambahkan aspek penilaian lainnya seperti Sosial-Emosional.</p>
        </button>
    </div>

    <div class="mt-10 bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_30px_rgba(224,64,160,0.15)] border border-primary-fixed flex flex-col sm:flex-row justify-between items-center gap-4 sticky bottom-6 z-10 backdrop-blur-sm bg-white/90">
        <div class="flex items-center gap-3">
            <div class="bg-tertiary-container text-on-tertiary-container p-2 rounded-full">
                <span class="material-symbols-outlined">info</span>
            </div>
            <p class="font-medium text-on-surface">Pastikan total bobot kriteria mencapai 100% sebelum menyimpan.</p>
        </div>
        <button type="submit"
            class="w-full sm:w-auto bg-primary text-on-primary font-bold text-lg px-8 py-4 rounded-full shadow-[0_4px_16px_rgba(224,64,160,0.4)] hover:scale-105 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">save</span>
            Simpan Pengaturan
        </button>
    </div>
</form>

<?php $this->load->view('components/modal_add_criteria'); ?>
<?php $this->load->view('components/modal_delete_criteria'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sliders = document.querySelectorAll('.weight-slider');
        const totalDisplay = document.getElementById('totalWeightDisplay');
        const weightContainer = document.getElementById('weightContainer');

        function updateTotal() {
            let total = 0;
            sliders.forEach(s => total += parseFloat(s.value));
            totalDisplay.textContent = total + '%';

            if (total === 100) {
                weightContainer.classList.replace('bg-secondary-container', 'bg-primary-container');
                totalDisplay.classList.replace('text-primary', 'text-on-primary-container');
            } else {
                weightContainer.classList.replace('bg-primary-container', 'bg-secondary-container');
                totalDisplay.classList.replace('text-on-primary-container', 'text-primary');
            }
        }

        sliders.forEach(slider => {
            slider.addEventListener('input', function() {
                document.getElementById('label-' + this.dataset.id).textContent = this.value + '%';
                updateTotal();
            });
        });

        updateTotal();
    });
</script>