<nav class="hidden md:flex flex-col h-full py-8 gap-4 bg-white dark:bg-gray-900 font-DM_SANS font-medium text-base h-screen w-64 rounded-r-[40px] sticky left-0 top-0 border-r-2 border-purple-100 dark:border-gray-800 shadow-[10px_0_30px_rgba(124,82,170,0.05)] z-40">
    <div class="px-8 mb-8 flex items-center gap-4">
        <img alt="School Logo" class="w-12 h-12 rounded-xl object-cover" data-alt="A stylized, colorful logo for a preschool named 'PAUD Mekar Sari'. It features playful, bubbly shapes in hot pink, purple, and sky blue. The design is modern, vector-style, and joyful, fitting a candy-colored aesthetic. Soft, clean lines, white background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAn_hE1Sy4CPBrzb-llXGuIifd-QjX_oGcE_24TJQA0MywbBhpEU_kgRxytliSN7iEZWnmNRLT1a3dffKRqfK2kAoNyPo5kadiUNmHFPgEZQIUKekR1r_StvXYZGIFWeV6w7ITz69rEE_cL5fVJuc7VN_A7zF6q_0IOpcsMVg1wYrKxmi-zXhCtilr5P_e8kqOHvcBU4VIvLR_n_V8VZPGB3bxnRSDBsKL33pTpb0FxU0pu-EALkYP8BucuxYByIA1I9RFBDUjiPrnc" />
        <div>
            <h1 class="text-xl font-black text-[#7c52aa] font-headline tracking-tight leading-tight">SAW Dashboard</h1>
            <p class="text-sm text-outline font-medium">PAUD Mekar Sari</p>
        </div>
    </div>

    <div class="flex-1 px-4 space-y-2">
        <?php
        // Mengambil nama controller/segmen pertama dari URL aktif saat ini di CI3
        $current_segment = $this->uri->segment(1);

        // Helper untuk menentukan class CSS berdasarkan URL aktif
        $activeClass = "bg-[#e040a0] text-white shadow-[0_4px_12px_rgba(224,64,160,0.3)] scale-105 spring-bounce";
        $inactiveClass = "text-[#7c52aa] hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:translate-x-2";
        ?>

        <!-- Dashboard (Aktif jika segmen pertama kosong atau bernama 'dashboard') -->
        <?php $is_dashboard = (empty($current_segment) || $current_segment === 'dashboard'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_dashboard ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('dashboard') ?>">
            <span class="material-symbols-outlined" style="<?= $is_dashboard ? "font-variation-settings: 'FILL' 1;" : "" ?>">grid_view</span>
            <span>Dashboard</span>
        </a>

        <!-- Siswa -->
        <?php $is_student = ($current_segment === 'student' || $current_segment === 'studentcontroller'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_student ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('student') ?>">
            <span class="material-symbols-outlined" style="<?= $is_student ? "font-variation-settings: 'FILL' 1;" : "" ?>">face</span>
            <span>Siswa</span>
        </a>

        <!-- Kelas -->
        <?php $is_class = ($current_segment === 'class' || $current_segment === 'classcontroller'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_class ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('classcontroller') ?>">
            <span class="material-symbols-outlined" style="<?= $is_class ? "font-variation-settings: 'FILL' 1;" : "" ?>">school</span>
            <span>Kelas</span>
        </a>

        <!-- Kriteria -->
        <?php $is_criteria = ($current_segment === 'criteria'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_criteria ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('criteria') ?>">
            <span class="material-symbols-outlined" style="<?= $is_criteria ? "font-variation-settings: 'FILL' 1;" : "" ?>">category</span>
            <span>Kriteria</span>
        </a>

        <!-- Penilaian -->
        <?php $is_evaluation = ($current_segment === 'evaluation'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_evaluation ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('evaluation') ?>">
            <span class="material-symbols-outlined" style="<?= $is_evaluation ? "font-variation-settings: 'FILL' 1;" : "" ?>">edit_note</span>
            <span>Penilaian</span>
        </a>

        <!-- Ranking -->
        <?php $is_ranking = ($current_segment === 'ranking'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_ranking ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('ranking') ?>">
            <span class="material-symbols-outlined" style="<?= $is_ranking ? "font-variation-settings: 'FILL' 1;" : "" ?>">leaderboard</span>
            <span>Ranking</span>
        </a>

        <!-- Guru / Pengguna -->
        <?php $is_teacher = ($current_segment === 'teacher'); ?>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full mx-2 transition-all duration-200 <?= $is_teacher ? $activeClass : $inactiveClass ?>"
            href="<?= base_url('teacher') ?>">
            <span class="material-symbols-outlined" style="<?= $is_teacher ? "font-variation-settings: 'FILL' 1;" : "" ?>">manage_accounts</span>
            <span>Guru</span>
        </a>
    </div>

    <!-- Bagian Info User Login & Logout -->
    <div class="px-4 border-t border-purple-50 dark:border-gray-800 pt-5 space-y-4">
        <?php
        // Mengambil data guru dari native session userdata CI3
        $teacher_name = $this->session->userdata('teacher_name');
        $username     = $this->session->userdata('username');
        ?>
        <!-- Info Profil Guru -->
        <div class="flex items-center gap-3 px-4 py-1">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-950 text-[#7c52aa] rounded-xl flex items-center justify-center font-black text-sm shadow-inner shrink-0">
                <?= strtoupper(substr($teacher_name ? $teacher_name : 'G', 0, 2)) ?>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate leading-tight"><?= $teacher_name ? html_escape($teacher_name) : 'Guru PAUD' ?></p>
                <p class="text-xs text-outline font-medium mt-0.5 truncate">@<?= $username ? html_escape($username) : 'teacher' ?></p>
            </div>
        </div>

        <!-- Button Logout (Diarahkan ke endpoint logout controller auth CI3) -->
        <a class="flex items-center gap-4 px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-full mx-2 transition-all hover:translate-x-2 duration-200 font-bold text-sm"
            href="<?= base_url('auth/logout') ?>">
            <span class="material-symbols-outlined text-red-500">logout</span>
            <span>Keluar Sistem</span>
        </a>
    </div>
</nav>