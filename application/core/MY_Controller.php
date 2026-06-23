<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Memuat library penunjang utama secara global
        $this->load->library('session');
        $this->load->helper('url');

        // Logic Filter Jalur Autentikasi (Pengganti Filter Auth CI4)
        if (!is_cli() && $this->session->userdata('is_logged_in') !== TRUE) {
            // Jika belum login, tendang balik ke halaman login
            redirect('auth/login');
        }
    }
}
