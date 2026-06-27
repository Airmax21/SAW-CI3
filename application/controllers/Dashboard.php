<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Dashboard extends MY_Controller
{

    public function __construct()
    {
        // Menjalankan konstruktor induk untuk pengecekan session login
        parent::__construct();

        // Memuat model dashboard tunggal hasil rangkuman service layer
        $this->load->model('m_dashboard');

        // Memuat model kelas untuk menyediakan data filter dropdown di view
        $this->load->model('m_class');
    }

    /**
     * Menampilkan halaman statistik utama dashboard
     * Menggantikan fungsionalitas DashboardService
     */
    public function index()
    {
        // Tangkap parameter filter berdasarkan role
        if ($this->session->userdata('role') === 'guru') {
            $this->load->model('m_teacher');
            $teacher = $this->m_teacher->get_by_id($this->session->userdata('teacher_id'));
            $class_id = $teacher ? (int)$teacher->class_id : NULL;
        } else {
            $class_id = $this->input->get('class_id', TRUE);
            $class_id = ($class_id !== NULL && $class_id !== '') ? (int) $class_id : NULL;
        }

        // Eksekusi core logic agregat statistik di level Model (M_dashboard)
        $data = $this->m_dashboard->get_dashboard_data($class_id);

        // Tambahkan master data kelas untuk kebutuhan looping element <select> filter di view
        $data['classes'] = $this->m_class->get_all();
        $data['selected_class_id'] = $class_id;

        // Metadata judul halaman untuk di-render di file header
        $data['title'] = 'Beranda Utama';

        // Render halaman menggunakan tumpukan Cara 1 (Header -> Konten -> Footer)
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('layouts/footer', $data);
    }
}
