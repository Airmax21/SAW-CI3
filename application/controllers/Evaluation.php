<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Evaluation extends MY_Controller
{

    public function __construct()
    {
        // Menjalankan konstruktor induk untuk pengecekan session login
        parent::__construct();

        // Memuat model evaluasi tunggal hasil rangkuman service layer
        $this->load->model('m_evaluation');

        // Memuat model kelas untuk kebutuhan dropdown filter di view
        $this->load->model('m_class');
    }

    /**
     * Menampilkan matriks lembar input nilai massal siswa per periode dan kelas
     * Menggantikan fungsionalitas GetDataEvaluationService
     */
    public function index()
    {
        // Ambil parameter period via GET request, default ke bulan-tahun saat ini (Y-m)
        $period = $this->input->get('period', TRUE);
        if (empty($period)) {
            $period = date('Y-m');
        }

        // Ambil parameter class_id berdasarkan role
        if ($this->session->userdata('role') === 'guru') {
            $this->load->model('m_teacher');
            $teacher = $this->m_teacher->get_by_id($this->session->userdata('teacher_id'));
            $class_id = $teacher ? (int)$teacher->class_id : NULL;
        } else {
            // Ambil parameter class_id via GET request dan sanitasi menjadi integer atau null
            $class_id = $this->input->get('class_id', TRUE);
            $class_id = ($class_id !== NULL && $class_id !== '') ? (int) $class_id : NULL;
        }

        // Ambil core logic penyusunan matriks nilai dari model (M_evaluation)
        $data = $this->m_evaluation->get_evaluation_matrix($period, $class_id);

        // Tambahkan master data kelas untuk kebutuhan menu tab filter di view
        $data['classes'] = $this->m_class->get_all();
        $data['selected_class_id'] = $class_id;
        $data['selected_period'] = $period;

        // Metadata judul halaman untuk di-render di file header
        $data['title'] = 'Penilaian Siswa';

        // Render halaman menggunakan tumpukan Cara 1 (Header -> Konten -> Footer)
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/evaluation/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses penyimpanan / pembaruan nilai massal siswa (Bulk Upsert)
     * Menggantikan fungsionalitas CreateBulkEvaluationService
     */
    public function store()
    {
        // Mengambil seluruh data input POST secara murni
        $post_data = $this->input->post(NULL, TRUE);

        if (empty($post_data)) {
            $this->session->set_flashdata('errors', array('Tidak ada data penilaian yang dikirim.'));
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // Cek jika guru, apakah data penilaian yang dikirim adalah untuk siswa di kelasnya saja
        if ($this->session->userdata('role') === 'guru') {
            $this->load->model('m_teacher');
            $teacher = $this->m_teacher->get_by_id($this->session->userdata('teacher_id'));
            $class_id = $teacher ? (int)$teacher->class_id : NULL;

            if (empty($class_id)) {
                $this->session->set_flashdata('errors', array('Anda belum ditugaskan ke kelas manapun.'));
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }

            // Validasi apakah siswa yang di-update berada di kelas guru ini
            if (!empty($post_data['scores']) && is_array($post_data['scores'])) {
                $student_ids = array_keys($post_data['scores']);
                if (!empty($student_ids)) {
                    $invalid_students = $this->db->where_in('id', $student_ids)
                        ->where('class_id !=', $class_id)
                        ->get('students')
                        ->num_rows();
                    if ($invalid_students > 0) {
                        $this->session->set_flashdata('errors', array('Anda hanya dapat menilai siswa di kelas Anda sendiri.'));
                        redirect($_SERVER['HTTP_REFERER']);
                        return;
                    }
                }
            }
        }

        // Eksekusi proses penyimpanan massal berbasis transaksi database di level Model
        if ($this->m_evaluation->save_bulk_evaluation($post_data)) {
            $this->session->set_flashdata('success', 'Penilaian berhasil disimpan.');
        } else {
            $this->session->set_flashdata('errors', array('Gagal menyimpan penilaian.'));
        }

        // Mengembalikan user ke halaman sebelumnya secara aman (Gaya redirect back CI3)
        redirect($_SERVER['HTTP_REFERER']);
    }
}
