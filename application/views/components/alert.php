<?php
$success_message = $this->session->flashdata('success');
if (!empty($success_message)) :
?>
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl border border-green-200 flex items-center gap-3 animate-fade-in">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="text-sm font-bold"><?= $success_message ?></p>
    </div>
<?php endif; ?>

<?php
$error_messages = $this->session->flashdata('errors');
if (!empty($error_messages)) :
?>
    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 flex items-center gap-3 animate-fade-in">
        <span class="material-symbols-outlined">error</span>
        <div class="text-sm">
            <p class="font-bold mb-1">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside text-xs opacity-80">
                <?php
                // Mengatasi jika errors dikirim dalam bentuk array (validasi form) atau string tunggal
                if (is_array($error_messages)) :
                    foreach ($error_messages as $error) :
                ?>
                        <li><?= $error ?></li>
                    <?php
                    endforeach;
                else :
                    ?>
                    <li><?= $error_messages ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>