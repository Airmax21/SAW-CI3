<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?> | SAW PAUD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..40,100..700,0..1,-50..200" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E040A0',
                        'primary-fixed': '#FFD8EC',
                        'on-primary': '#FFFFFF',
                        surface: '#FEF7FA',
                        'surface-container-lowest': '#FFFFFF',
                        'outline-variant': '#F4E6ED',
                        'on-surface': '#201A1D',
                        'on-surface-variant': '#4F4449',
                        outline: '#80747A',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-surface-container-lowest rounded-[2.5rem] p-8 md:p-10 shadow-[0_16px_48px_rgba(224,64,160,0.08)] border border-outline-variant">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-fixed text-primary rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <span class="material-symbols-outlined text-3xl font-bold" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
            </div>
            <h2 class="text-2xl font-black text-on-surface tracking-tight mb-1">Sistem Evaluasi PAUD</h2>
            <p class="text-sm font-medium text-outline">Masuk sebagai Guru untuk mengelola penilaian SAW.</p>
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

        <?php
        $old_input = $this->session->flashdata('old_input');
        $username_value = isset($old_input['username']) ? $old_input['username'] : '';
        ?>

        <form action="<?= base_url('auth/authenticate') ?>" method="POST" class="space-y-5">

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Username</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">person</span>
                    <input type="text" name="username" value="<?= html_escape($username_value) ?>" required placeholder="Masukkan username"
                        class="w-full pl-12 pr-4 py-4 bg-surface border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface placeholder:text-outline/60 focus:border-primary-fixed focus:bg-white transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black uppercase tracking-widest text-on-surface-variant pl-2">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">lock</span>
                    <input type="password" name="password" required placeholder="Masukkan password"
                        class="w-full pl-12 pr-4 py-4 bg-surface border-2 border-transparent rounded-2xl font-bold text-sm text-on-surface placeholder:text-outline/60 focus:border-primary-fixed focus:bg-white transition-all outline-none">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary font-black text-sm uppercase tracking-[0.15em] py-4 rounded-full shadow-[0_8px_24px_rgba(224,64,160,0.25)] hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 mt-2">
                <span class="material-symbols-outlined text-sm">login</span>
                Masuk Sekarang
            </button>
        </form>
    </div>

</body>

</html>