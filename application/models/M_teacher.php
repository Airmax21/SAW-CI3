<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_teacher extends CI_Model
{

    private $table = 'teachers';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('teachers.*, classes.class_name');
        $this->db->from($this->table);
        $this->db->join('classes', 'classes.id = teachers.class_id', 'left');
        $this->db->order_by('teachers.name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function create($data)
    {
        $payload = array(
            'username'   => isset($data['username']) ? $data['username'] : null,
            'name'       => isset($data['name']) ? $data['name'] : null,
            'password'   => isset($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null,
            'role'       => isset($data['role']) ? $data['role'] : 'guru',
            'class_id'   => (isset($data['role']) && $data['role'] === 'guru' && !empty($data['class_id'])) ? (int)$data['class_id'] : null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        return $this->db->insert($this->table, $payload);
    }

    public function update_teacher($id, $data)
    {
        $teacher = $this->get_by_id($id);
        if (!$teacher) return FALSE;

        $payload = array(
            'username'   => isset($data['username']) ? $data['username'] : $teacher->username,
            'name'       => isset($data['name']) ? $data['name'] : $teacher->name,
            'role'       => isset($data['role']) ? $data['role'] : $teacher->role,
            'class_id'   => (isset($data['role']) && $data['role'] === 'guru') ? (!empty($data['class_id']) ? (int)$data['class_id'] : null) : null,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($data['password'])) {
            $payload['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $payload);
    }

    public function delete_teacher($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Rangkuman dari: LoginService (Bagian Verifikasi Database)
     * Memvalidasi akun guru berdasarkan username dan verifikasi password hash
     * @param string $username
     * @param string $password
     * @return object|bool Mengembalikan data objek guru jika sukses, false jika gagal
     */
    public function verify_login($username, $password)
    {
        $this->db->where('username', $username);
        $teacher = $this->db->get($this->table)->row();

        // Jika guru ditemukan, lakukan verifikasi password hash BCRYPT
        if ($teacher && password_verify($password, $teacher->password)) {
            return $teacher;
        }

        return FALSE;
    }
}
