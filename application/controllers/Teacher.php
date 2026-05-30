<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Teacher extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Memuat model guru terisolasi
        $this->load->model('m_teacher');

        // Memuat library penunjang validasi input form
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan halaman manajemen data guru / pengguna sistem
     * Menggantikan GetAllTeachersService
     */
    public function index()
    {
        $data = array(
            'title'    => 'Manajemen Guru',
            'teachers' => $this->m_teacher->get_all()
        );

        // Render halaman menggunakan tumpukan Cara 1
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/teacher/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses penyimpanan akun guru baru beserta enkripsi password BCRYPT
     * Menggantikan CreateTeachersService
     */
    public function store()
    {
        $rules = array(
            array(
                'field'  => 'username',
                'label'  => 'Username',
                'rules'  => 'required|alpha_numeric|min_length[3]|is_unique[teachers.username]',
                'errors' => array(
                    'required'      => '%s wajib diisi.',
                    'alpha_numeric' => '%s hanya boleh berisi huruf dan angka.',
                    'min_length'    => '%s minimal berisi %s karakter.',
                    'is_unique'     => '%s sudah digunakan oleh guru lain.'
                )
            ),
            array(
                'field'  => 'name',
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'password',
                'label'  => 'Kata Sandi',
                'rules'  => 'required|min_length[6]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $this->input->post(NULL, TRUE));
            redirect('teacher');
        } else {
            $payload = array(
                'username' => $this->input->post('username', TRUE),
                'name'     => $this->input->post('name', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT) // Enkripsi aman berperforma tinggi
            );

            if ($this->m_teacher->create($payload)) {
                $this->session->set_flashdata('success', 'Data guru berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal menyimpan data guru baru.'));
            }

            redirect('teacher');
        }
    }

    /**
     * Memproses pembaruan akun guru dengan validasi keunikan username kustom
     * Menggantikan UpdateTeachersService
     * @param int $id
     */
    public function update($id)
    {
        $id = (int) $id;
        $username = $this->input->post('username', TRUE);

        // Validasi is_unique kustom gaya CI3 untuk mengabaikan ID diri sendiri saat update
        $username_exists = $this->db->where('username', $username)
            ->where('id !=', $id)
            ->get('teachers')
            ->num_rows();

        if ($username_exists > 0) {
            $this->session->set_flashdata('errors', array('username' => 'Username sudah digunakan oleh guru lain.'));
            redirect('teacher');
            return;
        }

        $rules = array(
            array(
                'field'  => 'username',
                'label'  => 'Username',
                'rules'  => 'required|alpha_numeric|min_length[3]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'name',
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'password',
                'label'  => 'Kata Sandi',
                'rules'  => 'min_length[6]',
                'errors' => array(
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('teacher');
        } else {
            $payload = array(
                'username' => $username,
                'name'     => $this->input->post('name', TRUE)
            );

            // Jika input password diisi, perbarui enkripsinya. Jika kosong, biarkan password lama.
            $password_input = $this->input->post('password');
            if (!empty($password_input)) {
                $payload['password'] = password_hash($password_input, PASSWORD_BCRYPT);
            }

            if ($this->m_teacher->update($id, $payload)) {
                $this->session->set_flashdata('success', 'Data guru berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal memperbarui data guru.'));
            }

            redirect('teacher');
        }
    }

    /**
     * Memproses penghapusan akun dengan proteksi diri sendiri
     * Menggantikan DeleteTeachersService
     * @param int $id
     */
    public function delete($id)
    {
        $id = (int) $id;

        // Membaca teacher_id yang sedang aktif dari session userdata CI3
        $active_teacher_id = (int) $this->session->userdata('teacher_id');

        if ($active_teacher_id === $id) {
            $this->session->set_flashdata('errors', array('Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.'));
            redirect('teacher');
            return;
        }

        if ($this->m_teacher->delete($id)) {
            $this->session->set_flashdata('success', 'Data guru berhasil dihapus dari sistem.');
        } else {
            $this->session->set_flashdata('errors', array('Gagal menghapus data guru.'));
        }

        redirect('teacher');
    }
}
