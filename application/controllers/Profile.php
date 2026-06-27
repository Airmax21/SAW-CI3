<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_teacher');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $teacher_id = $this->session->userdata('teacher_id');
        $data = array(
            'title'   => 'Update Data Pribadi',
            'teacher' => $this->m_teacher->get_by_id($teacher_id)
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('pages/profile/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function update()
    {
        $teacher_id = (int) $this->session->userdata('teacher_id');
        $username = $this->input->post('username', TRUE);

        // Validasi keunikan username untuk user selain dirinya sendiri
        $username_exists = $this->db->where('username', $username)
            ->where('id !=', $teacher_id)
            ->get('teachers')
            ->num_rows();

        if ($username_exists > 0) {
            $this->session->set_flashdata('errors', array('username' => 'Username sudah digunakan oleh guru lain.'));
            redirect('profile');
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
            redirect('profile');
        } else {
            $payload = array(
                'username' => $username,
                'name'     => $this->input->post('name', TRUE)
            );

            $password_input = $this->input->post('password');
            if (!empty($password_input)) {
                $payload['password'] = $password_input;
            }

            if ($this->m_teacher->update_teacher($teacher_id, $payload)) {
                // Update session info if name/username changed
                $this->session->set_userdata('teacher_name', $payload['name']);
                $this->session->set_userdata('username', $payload['username']);

                $this->session->set_flashdata('success', 'Profil pribadi berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal memperbarui profil pribadi.'));
            }

            redirect('profile');
        }
    }
}
