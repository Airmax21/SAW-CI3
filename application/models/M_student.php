<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_student extends CI_Model
{

    private $table = 'students';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Rangkuman dari: GetAllStudentsService
     * Mengambil semua data siswa dengan tambahan nama kelas melalui LEFT JOIN
     * @param int|null $class_id Filter berdasarkan ID kelas jika diisi
     * @return array Kumpulan stdClass Object
     */
    public function get_all($class_id = null)
    {
        $this->db->select('students.*, classes.class_name');
        $this->db->from($this->table);
        $this->db->join('classes', 'classes.id = students.class_id', 'left');

        if ($class_id !== null) {
            $this->db->where('students.class_id', $class_id);
        }

        $this->db->order_by('students.full_name', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Rangkuman dari: GetByIdStudentsService
     * Mengambil satu data siswa spesifik berdasarkan ID
     * @param int $id
     * @return object|null Single stdClass Object
     */
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    /**
     * Rangkuman dari: CreateStudentsService
     * Menyimpan data siswa baru ke SQLite
     * Berperan sebagai pengganti filter Entity / $allowedFields di CI4
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $payload = array(
            'class_id'       => isset($data['class_id']) ? (int)$data['class_id'] : null,
            'full_name'      => isset($data['full_name']) ? $data['full_name'] : null,
            'gender'         => isset($data['gender']) ? $data['gender'] : null,
            'nisn'           => isset($data['nisn']) ? $data['nisn'] : null,
            'religion'       => isset($data['religion']) ? $data['religion'] : null,
            'address'        => isset($data['address']) ? $data['address'] : null,
            'parent_name'    => isset($data['parent_name']) ? $data['parent_name'] : null,
            'parent_contact' => isset($data['parent_contact']) ? $data['parent_contact'] : null,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        );

        return $this->db->insert($this->table, $payload);
    }

    /**
     * Rangkuman dari: UpdateStudentsService
     * Memperbarui data siswa berdasarkan ID secara dinamis dan aman
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_student($id, $data)
    {
        $payload = array();

        // Bangun payload hanya dari field yang diizinkan untuk diubah
        if (isset($data['class_id']))  $payload['class_id']  = (int)$data['class_id'];
        if (isset($data['full_name'])) $payload['full_name'] = $data['full_name'];
        if (isset($data['gender']))    $payload['gender']    = $data['gender'];
        if (isset($data['nisn']))      $payload['nisn']      = $data['nisn'];
        if (isset($data['religion']))  $payload['religion']  = $data['religion'];
        if (isset($data['address']))   $payload['address']   = $data['address'];
        if (isset($data['parent_name']))    $payload['parent_name']    = $data['parent_name'];
        if (isset($data['parent_contact'])) $payload['parent_contact'] = $data['parent_contact'];

        $payload['updated_at'] = date('Y-m-d H:i:s');

        if (empty($payload)) {
            return FALSE;
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $payload);
    }

    /**
     * Rangkuman dari: DeleteStudentsService
     * Menghapus data siswa dari sistem berdasarkan ID
     * @param int $id
     * @return bool
     */
    public function delete_student($id)
    {
        // Catatan: Jika ada relasi ke tabel evaluations, record nilai milik siswa ini 
        // sebaiknya ikut dihapus terlebih dahulu jika SQLite tidak dipasang ON DELETE CASCADE.
        $this->db->where('student_id', $id);
        $this->db->delete('evaluations');

        // Hapus data utama siswa
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
