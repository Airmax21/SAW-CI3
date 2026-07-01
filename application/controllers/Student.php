<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Student extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Hak akses: Hanya Admin yang bisa mengelola data siswa
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('errors', array('Anda tidak memiliki akses ke halaman manajemen siswa.'));
            redirect('dashboard');
            return;
        }

        // Memuat model data yang dibutuhkan
        $this->load->model('m_student');
        $this->load->model('m_class');

        // Memuat library penunjang validasi input form
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan halaman daftar manajemen anak didik dengan filter kelas
     * Menggantikan GetAllStudentsService
     */
    public function index()
    {
        // Tangkap parameter filter kelas via GET request (XSS Clean aktif)
        $selected_class = $this->input->get('class_id', TRUE);
        $selected_class = ($selected_class !== NULL && $selected_class !== '') ? (int)$selected_class : NULL;

        $data = array(
            'title'         => 'Manajemen Anak',
            'students'      => $this->m_student->get_all($selected_class),
            'classes'       => $this->m_class->get_all(),
            'selectedClass' => $selected_class
        );

        // Render halaman menggunakan tumpukan Cara 1
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/student/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Menampilkan halaman formulir tambah anak didik baru
     */
    public function create()
    {
        // Di CI3, kita gunakan standard array kosong sebagai representasi objek Entity baru
        $student_empty = (object) array(
            'id'             => '',
            'full_name'      => '',
            'gender'         => '',
            'class_id'       => '',
            'nisn'           => '',
            'religion'       => '',
            'address'        => '',
            'parent_name'    => '',
            'parent_contact' => ''
        );

        $data = array(
            'title'   => 'Tambah Anak Baru',
            'student' => $student_empty,
            'classes' => $this->m_class->get_all(),
            'action'  => base_url('student/store')
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('pages/student/form', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses penyimpanan data anak didik baru ke database
     * Menggantikan CreateStudentsService
     */
    public function store()
    {
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            // Catat error validasi form
            $this->session->set_flashdata('errors', $this->form_validation->error_array());

            // Retensi input form lama agar user tidak mengetik ulang
            $this->session->set_flashdata('old_input', $this->input->post(NULL, TRUE));

            redirect('student/create');
        } else {
            $payload = array(
                'full_name'      => $this->input->post('full_name', TRUE),
                'gender'         => $this->input->post('gender', TRUE),
                'class_id'       => (int) $this->input->post('class_id', TRUE),
                'nisn'           => $this->input->post('nisn', TRUE),
                'religion'       => $this->input->post('religion', TRUE),
                'address'        => $this->input->post('address', TRUE),
                'parent_name'    => $this->input->post('parent_name', TRUE),
                'parent_contact' => $this->input->post('parent_contact', TRUE)
            );

            if ($this->m_student->create($payload)) {
                $this->session->set_flashdata('success', 'Data anak berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal menambahkan data anak.'));
            }

            redirect('student');
        }
    }

    /**
     * Menampilkan halaman formulir edit data anak didik berdasarkan ID
     * Menggantikan GetByIdStudentsService
     * @param int $id
     */
    public function edit($id)
    {
        $id = (int) $id;
        $student = $this->m_student->get_by_id($id);

        // Pengganti PageNotFoundException di CI3
        if (!$student) {
            show_404();
            return;
        }

        $data = array(
            'title'   => 'Edit Data Anak',
            'student' => $student,
            'classes' => $this->m_class->get_all(),
            'action'  => base_url('student/update/' . $id)
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('pages/student/form', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses pembaruan data biodata anak didik
     * Menggantikan UpdateStudentsService
     * @param int $id
     */
    public function update($id)
    {
        $id = (int) $id;
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('student/edit/' . $id);
        } else {
            $payload = array(
                'full_name'      => $this->input->post('full_name', TRUE),
                'gender'         => $this->input->post('gender', TRUE),
                'class_id'       => (int) $this->input->post('class_id', TRUE),
                'nisn'           => $this->input->post('nisn', TRUE),
                'religion'       => $this->input->post('religion', TRUE),
                'address'        => $this->input->post('address', TRUE),
                'parent_name'    => $this->input->post('parent_name', TRUE),
                'parent_contact' => $this->input->post('parent_contact', TRUE)
            );

            if ($this->m_student->update_student($id, $payload)) {
                $this->session->set_flashdata('success', 'Data anak berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal memperbarui data anak.'));
            }

            redirect('student');
        }
    }

    /**
     * Menghapus data anak didik dari database SQLite3
     * Menggantikan DeleteStudentsService
     * @param int $id
     */
    public function delete($id)
    {
        $id = (int) $id;

        if ($this->m_student->delete_student($id)) {
            $this->session->set_flashdata('success', 'Data anak berhasil dihapus.');
        } else {
            $this->session->set_flashdata('errors', array('Gagal menghapus data anak.'));
        }

        redirect('student');
    }

    /**
     * Aturan validasi form (Reusable method untuk store dan update)
     */
    private function _set_validation_rules()
    {
        $rules = array(
            array(
                'field'  => 'full_name',
                'label'  => 'Nama Lengkap Anak',
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'gender',
                'label'  => 'Jenis Kelamin',
                'rules'  => 'required|in_list[L,P]',
                'errors' => array(
                    'required' => '%s harus dipilih.',
                    'in_list'  => '%s tidak valid.'
                )
            ),
            array(
                'field'  => 'class_id',
                'label'  => 'Kelas',
                'rules'  => 'required',
                'errors' => array(
                    'required' => '%s wajib ditentukan.'
                )
            ),
            array(
                'field'  => 'nisn',
                'label'  => 'NISN',
                'rules'  => 'trim'
            ),
            array(
                'field'  => 'religion',
                'label'  => 'Agama',
                'rules'  => 'trim'
            ),
            array(
                'field'  => 'address',
                'label'  => 'Alamat',
                'rules'  => 'trim'
            ),
            array(
                'field'  => 'parent_name',
                'label'  => 'Nama Orang Tua',
                'rules'  => 'trim'
            ),
            array(
                'field'  => 'parent_contact',
                'label'  => 'Kontak Orang Tua',
                'rules'  => 'trim'
            )
        );

        $this->form_validation->set_rules($rules);
    }
}
