<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_criteria extends CI_Model
{

    private $table_criteria = 'criterias';
    private $table_sub      = 'sub_criterias'; // Menyesuaikan relasi tabel sub-kriteria

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mengambil seluruh data kriteria utama dari database SQLite3
     * Sangat efisien untuk kebutuhan rendering kolom matriks
     * @return array Object
     */
    public function get_all()
    {
        return $this->db->get('criterias')->result();
    }

    /**
     * Rangkuman dari: GetCriteriaService
     * Mengambil kriteria beserta sub-kriterianya (Manual Eager Loading)
     * @return array Kumpulan objek kriteria yang sudah ditempeli array sub-kriteria
     */
    public function get_all_with_subs()
    {
        // 1. Ambil semua kriteria utama urut berdasarkan kode (ASC)
        $this->db->order_by('code', 'ASC');
        $criterias = $this->db->get($this->table_criteria)->result();

        // 2. Tempelkan sub-kriteria ke masing-masing kriteria menggunakan loop
        foreach ($criterias as $criteria) {
            $this->db->where('criteria_id', $criteria->id);
            $criteria->subs = $this->db->get($this->table_sub)->result();
        }

        return $criterias;
    }

    /**
     * Rangkuman dari: CreateCriteriaService
     * Menyimpan Kriteria baru sekaligus memecah string sub-kriteria menggunakan transaksi
     * @param array $data
     * @return bool Status transaksi (true/false)
     */
    public function create_with_subs($data)
    {
        // Mulai database transaction bawaan CI3
        $this->db->trans_start();

        // 1. Insert Kriteria Utama
        $payload_criteria = array(
            'code'          => strtoupper($data['code']),
            'criteria_name' => $data['criteria_name'],
            'weight'        => 0,
            'type'          => $data['type'],
        );
        $this->db->insert($this->table_criteria, $payload_criteria);

        // Mengambil ID terakhir yang digenerate oleh SQLite
        $criteria_id = $this->db->insert_id();

        // 2. Proses Sub-Kriteria (Pemisah Koma)
        if (!empty($data['sub_names'])) {
            $input_subs = $data['sub_names'];

            // Jika masih berupa string, pecah menjadi array berdasarkan koma
            $subs_array = is_string($input_subs) ? explode(',', $input_subs) : $input_subs;

            foreach ($subs_array as $s) {
                $name = trim($s);
                if ($name !== "") {
                    $payload_sub = array(
                        'criteria_id' => $criteria_id,
                        'sub_name'    => $name
                    );
                    $this->db->insert($this->table_sub, $payload_sub);
                }
            }
        }

        // Selesaikan transaksi database
        $this->db->trans_complete();

        // Mengembalikan status akhir transaksi (TRUE jika sukses, FALSE jika rollback)
        return $this->db->trans_status();
    }

    /**
     * Rangkuman dari: UpdateCriteriaService
     * Memperbarui bobot kriteria secara massal dengan validasi aturan bisnis total 100%
     * @param array $weights [id => bobot, id => bobot]
     * @return bool
     * @throws Exception jika total bobot tidak sama dengan 100
     */
    public function update_weights($weights)
    {
        // Validasi logika bisnis: Total bobot wajib tepat 100%
        if (array_sum($weights) != 100) {
            throw new Exception('Gagal menyimpan! Total bobot kriteria harus tepat 100%. Saat ini: ' . array_sum($weights) . '%');
        }

        $this->db->trans_start();

        foreach ($weights as $id => $weight) {
            $this->db->where('id', $id);
            $this->db->update($this->table_criteria, array('weight' => $weight));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Terjadi kesalahan saat memperbarui database.');
        }

        return TRUE;
    }

    /**
     * Rangkuman dari: DeleteCriteriaService
     * Menghapus data kriteria utama berdasarkan ID
     * @param int $id
     * @return bool
     */
    public function delete_criteria($id)
    {
        // Catatan: Jika di SQLite kamu tidak mengaktifkan ON DELETE CASCADE, 
        // disarankan menghapus sub-kriterianya secara manual terlebih dahulu di sini.
        $this->db->where('criteria_id', $id);
        $this->db->delete($this->table_sub);

        // Hapus kriteria utama
        $this->db->where('id', $id);
        return $this->db->delete($this->table_criteria);
    }
}