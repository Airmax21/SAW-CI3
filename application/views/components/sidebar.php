<nav class="hidden md:flex flex-col h-full py-8 gap-4 bg-white dark:bg-gray-900 font-DM_SANS font-medium text-base h-screen w-64 rounded-r-[40px] sticky left-0 top-0 border-r-2 border-purple-100 dark:border-gray-800 shadow-[10px_0_30px_rgba(124,82,170,0.05)] z-40">
    <div class="px-6 mb-8 flex items-center gap-3">
        <img alt="Logo Paud" class="w-12 h-12 rounded-xl object-cover shrink-0" src="<?php echo base_url('assets/images/logo.png'); ?>"/>
        <div>
            <h1 class="text-[10px] font-black text-[#7c52aa] font-headline tracking-wider uppercase leading-snug">SPK Penilaian Perkembangan Kemampuan Anak Usia Dini</h1>
            <p class="text-xs text-outline font-medium mt-0.5">PAUD Mekar Sari Adong 1</p>
        </div>
    </div>

    <div class="flex-1 px-4 space-y-2">
        <?php
        // Mengambil nama controller/segmen pertama dari URL aktif saat ini di CI3
        $current_segment = $this->uri->segment(1);

        // Helper untuk menentukan class CSS berdasarkan URL aktif
        $activeClass = "bg-[#e040a0] text-white shadow-[0_4px_12px_rgba(224,64,160,0.3)] scale-105 spring-bounce";
        $inactiveClass = "text-[#7c52aa] hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:translate-x-2";
        $is_admin = ($this->session->userdata('role') === 'admin');

        // Definisikan status aktif tiap menu agar konsisten digunakan di desktop & mobile
        $is_dashboard = (empty($current_segment) || $current_segment === 'dashboard');
        $is_student = ($current_segment === 'student' || $current_segment === 'studentcontroller');
        $is_class = ($current_segment === 'class' || $current_segment === 'classcontroller');
        $is_criteria = ($current_segment === 'criteria');
        $is_evaluation = ($current_segment === 'evaluation');
        $is_ranking = ($current_segment === 'ranking');
        $is_profile = ($current_segment === 'profile');
        $is_teacher = ($current_segment === 'teacher');
        ?>

        <!-- Dashboard -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_dashboard ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('dashboard') ?>">
            <span class="material-symbols-outlined" style="<?= $is_dashboard ? "font-variation-settings: 'FILL' 1;" : "" ?>">grid_view</span>
            <span>Dashboard</span>
        </a>

        <?php if ($is_admin): ?>
            <!-- Siswa -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_student ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('student') ?>">
                <span class="material-symbols-outlined" style="<?= $is_student ? "font-variation-settings: 'FILL' 1;" : "" ?>">face</span>
                <span>Anak</span>
            </a>

            <!-- Kelas -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_class ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('classcontroller') ?>">
                <span class="material-symbols-outlined" style="<?= $is_class ? "font-variation-settings: 'FILL' 1;" : "" ?>">school</span>
                <span>Kelas</span>
            </a>

            <!-- Kriteria -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_criteria ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('criteria') ?>">
                <span class="material-symbols-outlined" style="<?= $is_criteria ? "font-variation-settings: 'FILL' 1;" : "" ?>">category</span>
                <span>Kriteria</span>
            </a>
        <?php endif; ?>

        <!-- Penilaian -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_evaluation ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('evaluation') ?>">
            <span class="material-symbols-outlined" style="<?= $is_evaluation ? "font-variation-settings: 'FILL' 1;" : "" ?>">edit_note</span>
            <span>Penilaian</span>
        </a>

        <!-- Ranking -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_ranking ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('ranking') ?>">
            <span class="material-symbols-outlined" style="<?= $is_ranking ? "font-variation-settings: 'FILL' 1;" : "" ?>">leaderboard</span>
            <span>Ranking</span>
        </a>


        <!-- Akun (Hanya muncul jika Role = admin) -->
        <?php if ($is_admin): ?>
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_teacher ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('teacher') ?>">
                <span class="material-symbols-outlined" style="<?= $is_teacher ? "font-variation-settings: 'FILL' 1;" : "" ?>">manage_accounts</span>
                <span>Akun</span>
            </a>
        <?php endif; ?>
    </div>
 
    <!-- Bagian Info User Login & Logout -->
    <div class="px-4 border-t border-purple-50 dark:border-gray-800 pt-5 space-y-4">
        <?php
        // Mengambil data guru dari native session userdata CI3
        $teacher_name = $this->session->userdata('teacher_name');
        $username     = $this->session->userdata('username');
        $role         = $this->session->userdata('role');
        $fallback_name = ($role === 'admin') ? 'Admin' : 'Guru PAUD';
        ?>
        <!-- Info Profil Akun (Link ke Data Pribadi) -->
        <?php $profileActive = $is_profile ? 'bg-purple-100 dark:bg-purple-900/40 border border-primary/30 shadow-sm' : 'border border-transparent'; ?>
        <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 group <?= $profileActive ?>">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-950 text-[#7c52aa] rounded-xl flex items-center justify-center font-black text-sm shadow-inner shrink-0 group-hover:scale-105 transition-transform">
                <?= strtoupper(substr($teacher_name ? $teacher_name : ($role === 'admin' ? 'A' : 'G'), 0, 2)) ?>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate leading-tight group-hover:text-primary transition-colors"><?= $teacher_name ? html_escape($teacher_name) : $fallback_name ?></p>
                <p class="text-xs text-outline font-medium mt-0.5 truncate">@<?= $username ? html_escape($username) : 'user' ?> (<?= $role === 'admin' ? 'Admin' : 'Guru' ?>)</p>
            </div>
        </a>

        <!-- Button Logout (Diarahkan ke endpoint logout controller auth CI3) -->
        <a class="flex items-center gap-4 px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-full mx-2 transition-all hover:translate-x-2 duration-200 font-bold text-sm"
            href="<?= base_url('auth/logout') ?>">
            <span class="material-symbols-outlined text-red-500">logout</span>
            <span>Keluar Sistem</span>
        </a>
    </div>
</nav>

<!-- Mobile Header Top Bar (Hanya tampil di HP) -->
<div class="md:hidden flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-900 border-b border-purple-100 dark:border-gray-800 sticky top-0 z-40 w-full shrink-0">
    <div class="flex items-center gap-3">
        <img alt="Logo Paud" class="w-9 h-9 rounded-lg object-cover" src="<?php echo base_url('assets/images/logo.png'); ?>"/>
        <div>
            <h1 class="text-[10px] font-black text-[#7c52aa] font-headline tracking-wider uppercase leading-none">SPK Penilaian</h1>
            <p class="text-[9px] text-outline font-medium mt-0.5">PAUD Mekar Sari Adong 1</p>
        </div>
    </div>
    <button id="mobile-menu-btn" class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-900/20 text-[#7c52aa] hover:scale-105 active:scale-95 transition-all">
        <span class="material-symbols-outlined">menu</span>
    </button>
</div>

<!-- Mobile Navigation Drawer Overlay (Hanya tampil di HP) -->
<div id="mobile-drawer" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden">
    <div id="mobile-drawer-content" class="absolute top-0 right-0 w-72 h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col py-6 transform translate-x-full transition-transform duration-300 ease-out">
        <div class="flex items-center justify-between px-6 pb-4 border-b border-purple-50 dark:border-gray-800">
            <span class="font-black text-[#7c52aa] uppercase text-xs tracking-wider">Menu Navigasi</span>
            <button id="mobile-close-btn" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:scale-105 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2">
            <!-- Dashboard -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_dashboard ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('dashboard') ?>">
                <span class="material-symbols-outlined" style="<?= $is_dashboard ? "font-variation-settings: 'FILL' 1;" : "" ?>">grid_view</span>
                <span>Dashboard</span>
            </a>

            <?php if ($is_admin): ?>
                <!-- Siswa -->
                <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_student ? $activeClass : $inactiveClass ?>"
                    href="<?= base_url('student') ?>">
                    <span class="material-symbols-outlined" style="<?= $is_student ? "font-variation-settings: 'FILL' 1;" : "" ?>">face</span>
                    <span>Anak</span>
                </a>

                <!-- Kelas -->
                <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_class ? $activeClass : $inactiveClass ?>"
                    href="<?= base_url('classcontroller') ?>">
                    <span class="material-symbols-outlined" style="<?= $is_class ? "font-variation-settings: 'FILL' 1;" : "" ?>">school</span>
                    <span>Kelas</span>
                </a>

                <!-- Kriteria -->
                <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_criteria ? $activeClass : $inactiveClass ?>"
                    href="<?= base_url('criteria') ?>">
                    <span class="material-symbols-outlined" style="<?= $is_criteria ? "font-variation-settings: 'FILL' 1;" : "" ?>">category</span>
                    <span>Kriteria</span>
                </a>
            <?php endif; ?>

            <!-- Penilaian -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_evaluation ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('evaluation') ?>">
                <span class="material-symbols-outlined" style="<?= $is_evaluation ? "font-variation-settings: 'FILL' 1;" : "" ?>">edit_note</span>
                <span>Penilaian</span>
            </a>

            <!-- Ranking -->
            <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_ranking ? $activeClass : $inactiveClass ?>"
                href="<?= base_url('ranking') ?>">
                <span class="material-symbols-outlined" style="<?= $is_ranking ? "font-variation-settings: 'FILL' 1;" : "" ?>">leaderboard</span>
                <span>Ranking</span>
            </a>


            <!-- Akun (Hanya muncul jika Role = admin) -->
            <?php if ($is_admin): ?>
                <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_teacher ? $activeClass : $inactiveClass ?>"
                    href="<?= base_url('teacher') ?>">
                    <span class="material-symbols-outlined" style="<?= $is_teacher ? "font-variation-settings: 'FILL' 1;" : "" ?>">manage_accounts</span>
                    <span>Akun</span>
                </a>
            <?php endif; ?>
        </div>

        <div class="px-6 border-t border-purple-50 dark:border-gray-800 pt-5 space-y-4">
            <!-- Info Profil Akun (Link ke Data Pribadi) -->
            <?php $profileActiveMobile = $is_profile ? 'bg-purple-100 dark:bg-purple-900/40 border border-primary/30 shadow-sm' : 'border border-transparent'; ?>
            <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 group w-full <?= $profileActiveMobile ?>">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-950 text-[#7c52aa] rounded-xl flex items-center justify-center font-black text-sm shadow-inner shrink-0 group-hover:scale-105 transition-transform">
                    <?= strtoupper(substr($teacher_name ? $teacher_name : ($role === 'admin' ? 'A' : 'G'), 0, 2)) ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate leading-tight group-hover:text-primary transition-colors"><?= $teacher_name ? html_escape($teacher_name) : $fallback_name ?></p>
                    <p class="text-xs text-outline font-medium mt-0.5 truncate">@<?= $username ? html_escape($username) : 'user' ?> (<?= $role === 'admin' ? 'Admin' : 'Guru' ?>)</p>
                </div>
            </a>

            <!-- Button Logout -->
            <a class="flex items-center gap-4 px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-full mx-2 transition-all duration-200 font-bold text-sm"
                href="<?= base_url('auth/logout') ?>">
                <span class="material-symbols-outlined text-red-500">logout</span>
                <span>Keluar Sistem</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('mobile-close-btn');
    const drawer = document.getElementById('mobile-drawer');
    const drawerContent = document.getElementById('mobile-drawer-content');

    if (menuBtn && closeBtn && drawer && drawerContent) {
        menuBtn.addEventListener('click', function() {
            drawer.classList.remove('pointer-events-none', 'opacity-0');
            drawer.classList.add('opacity-100');
            drawerContent.classList.remove('translate-x-full');
            drawerContent.classList.add('translate-x-0');
        });

        const closeDrawer = function() {
            drawer.classList.remove('opacity-100');
            drawer.classList.add('opacity-0', 'pointer-events-none');
            drawerContent.classList.remove('translate-x-0');
            drawerContent.classList.add('translate-x-full');
        };

        closeBtn.addEventListener('click', closeDrawer);
        drawer.addEventListener('click', function(e) {
            if (e.target === drawer) {
                closeDrawer();
            }
        });
    }
});
</script>