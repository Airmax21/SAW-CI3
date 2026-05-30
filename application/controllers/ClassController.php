<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi filter auth proteksi login
 */
class ClassController extends MY_Controller
{

    public function __construct()
    {
        // Menjalankan constructor induk (MY_Controller) untuk cek session
        parent::__construct();

        // Memuat model kelas tunggal hasil rangkuman services
        $this->load->model('m_class');

        // Memuat library penunjang validasi data form input
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan data kelas
     * Menggantikan fungsionalitas GetAllClassesService
     */
    public function index()
    {
        $data = array(
            'title'   => 'Manajemen Kelas',
            'classes' => $this->m_class->get_all() // Mengambil kumpulan objek kelas
        );

        // Me-load view sesuai struktur folder pages kamu
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/class/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses validasi form dan menyimpan data kelas baru
     * Menggantikan fungsionalitas CreateClassesService & Form Validation CI4
     */
    public function store()
    {
        // Menetapkan aturan validasi dengan gaya array standard CI3 yang clean
        $rules = array(
            array(
                'field'  => 'class_name',
                'label'  => 'Nama Kelas',
                'rules'  => 'required|min_length[2]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'academic_year',
                'label'  => 'Tahun Akademik',
                'rules'  => 'required',
                'errors' => array(
                    'required' => '%s wajib dipilih.'
                )
            )
        );

        $this->form_validation->set_rules($rules);

        // Eksekusi validasi form
        if ($this->form_validation->run() === FALSE) {

            // Mengambil semua pesan error dari form_validation bawaan CI3
            // Kita bungkus ke dalam bentuk array agar formatnya sinkron dengan view alert CI4 lamamu
            $validation_errors = array(
                'class_name'    => form_error('class_name'),
                'academic_year' => form_error('academic_year')
            );

            // Bersihkan baris array yang kosong (tidak ada error)
            $validation_errors = array_filter($validation_errors);

            $this->session->set_flashdata('errors', $validation_errors);

            // Retensi input lama (withInput manual)
            $old_input = array(
                'class_name'    => $this->input->post('class_name', TRUE),
                'academic_year' => $this->input->post('academic_year', TRUE)
            );
            $this->session->set_flashdata('old_input', $old_input);

            // Redirect balik ke halaman form kelas
            redirect('classcontroller');
        } else {
            // Ambil input payload dan amankan dari XSS injection
            $payload = array(
                'class_name'    => $this->input->post('class_name', TRUE),
                'academic_year' => $this->input->post('academic_year', TRUE)
            );

            // Kirim data bersih ke model untuk disimpan ke SQLite
            $this->m_class->create($payload);

            $this->session->set_flashdata('success', 'Kelas berhasil ditambahkan.');
            redirect('classcontroller');
        }
    }

    /**
     * Menghapus data kelas berdasarkan ID
     * Menggantikan fungsionalitas DeleteClassesService
     * @param int $id
     */
    public function delete($id)
    {
        // Konversi parameter ke integer demi keamanan tipe data
        $id = (int) $id;

        $this->m_class->delete_class($id);

        $this->session->set_flashdata('success', 'Kelas berhasil dihapus.');
        redirect('classcontroller');
    }
}
