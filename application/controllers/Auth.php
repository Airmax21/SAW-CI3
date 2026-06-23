<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Memuat library penunjang utama autentikasi
        $this->load->library('session');
        $this->load->model('m_teacher');
        $this->load->helper('url');
    }

    /**
     * Menampilkan Halaman Form Login
     * Menggantikan fungsionalitas CheckAuthService
     */
    public function login()
    {
        // Jika session mencatat user sudah login, tendang langsung ke halaman dashboard
        if ($this->session->userdata('is_logged_in') === TRUE) {
            redirect('dashboard');
        }

        // Menyiapkan data title untuk disisipkan ke layout view
        $data['title'] = 'Login Guru';

        // Me-load view login bawaan CI3 (sesuai struktur folder views kamu)
        $this->load->view('pages/auth/login', $data);
    }

    /**
     * Memproses Verifikasi Kredensial Akun (POST)
     * Menggantikan fungsionalitas LoginService
     */
    public function authenticate()
    {
        // Mengambil data input POST dengan pengamanan XSS Filter bawaan CI3
        $username = $this->input->post('username', TRUE) ? $this->input->post('username', TRUE) : '';
        $password = $this->input->post('password', TRUE) ? $this->input->post('password', TRUE) : '';

        // Eksekusi verifikasi ke level Model Guru (M_teacher)
        $teacher = $this->m_teacher->verify_login($username, $password);

        if ($teacher) {
            // Jika valid, susun data ke array session
            $session_data = array(
                'is_logged_in' => TRUE,
                'teacher_id'   => $teacher->id,
                'teacher_name' => $teacher->name,
                'username'     => $teacher->username,
                'role'         => $teacher->role,
                'class_id'     => $teacher->class_id
            );
            $this->session->set_userdata($session_data);

            // Set flashdata sukses untuk ditarik di view tailwind dashboard/ranking
            $this->session->set_flashdata('success', 'Selamat datang kembali, Ibu/Bapak Guru!');
            redirect('dashboard'); // atau redirect ke 'ranking' sesuai konfigurasi routes kamu
        } else {
            // Jika gagal, set flashdata error (menggantikan ->with('errors') di CI4)
            $this->session->set_flashdata('errors', array('Username atau password salah.'));

            // Mengembalikan input yang salah ke form menggunakan flashdata bawaan 
            // (karena CI3 tidak memiliki fungsi auto ->withInput() seperti CI4)
            $old_input = array(
                'username' => $username
            );
            $this->session->set_flashdata('old_input', $old_input);

            // Redirect balik ke halaman login
            redirect('auth/login');
        }
    }

    /**
     * Memproses Pembersihan Session & Keluar Aplikasi
     * Menggantikan fungsionalitas LogoutService
     */
    public function logout()
    {
        // Menghancurkan seluruh data session yang melekat di browser
        $this->session->sess_destroy();

        // Membuat session flashdata baru untuk notifikasi sukses setelah redirect
        // Perlu memuat library session sejenak jika redirect dilakukan pasca destroy
        $this->load->library('session');
        $this->session->set_flashdata('success', 'Berhasil keluar sistem.');

        redirect('auth/login');
    }
}
