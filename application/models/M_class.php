<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_class extends CI_Model {

    // Mendefinisikan nama tabel secara eksplisit
    private $table = 'classes';

    public function __construct()
    {
        parent::__construct();
        // Memastikan library database bawaan CI3 termuat
        $this->load->database();
    }

    /**
     * Rangkuman dari: GetAllClassesService
     * Mengambil semua data kelas dari SQLite
     * @return array Kumpulan stdClass Object
     */
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    /**
     * Rangkuman dari: CreateClassesService
     * Menyimpan data kelas baru ke database
     * Menangani pemfilteran field secara manual menggantikan Entity di CI4
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        // Menyaring data secara ketat sesuai kolom yang diizinkan (Allowed Fields)
        $payload = array(
            'class_name'    => isset($data['class_name']) ? $data['class_name'] : null,
            'academic_year' => isset($data['academic_year']) ? $data['academic_year'] : null
        );

        return $this->db->insert($this->table, $payload);
    }

    /**
     * Rangkuman dari: DeleteClassesService
     * Menghapus data kelas berdasarkan ID
     * @param int $id
     * @return bool
     */
    public function delete_class($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}