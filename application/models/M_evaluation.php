<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_evaluation extends CI_Model
{

    private $table = 'evaluations';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Rangkuman dari: GetDataEvaluationService
     * Mengambil matriks nilai siswa beserta kriteria dan kelas secara efisien.
     * Menggunakan pendekatan single-query untuk mengambil seluruh evaluasi 
     * guna menghindari masalah N+1 Query pada database SQLite.
     * * @param string $period Format 'Y-m'
     * @param int|null $class_id
     * @return array
     */
    public function get_evaluation_matrix($period, $class_id = null)
    {
        // 1. Ambil data master kelas & master kriteria
        $classes = $this->db->get('classes')->result();

        $this->db->order_by('code', 'ASC');
        $criterias = $this->db->get('criterias')->result();

        // 2. Ambil data siswa (filter kelas jika ada)
        if (!empty($class_id)) {
            $this->db->where('class_id', $class_id);
        }
        $this->db->order_by('full_name', 'ASC');
        $students = $this->db->get('students')->result();

        // 3. Ambil SEMUA data evaluasi pada periode berjalan (Eager Loading Pattern)
        // Cara ini jauh lebih cepat daripada melakukan query di dalam loop foreach siswa
        $this->db->where('period', $period);
        $all_evaluations = $this->db->get($this->table)->result();

        // Petakan evaluasi ke dalam array temporary agar mudah di-lookup
        $eval_map = array();
        foreach ($all_evaluations as $eval) {
            $eval_map[$eval->student_id][$eval->criteria_id] = (int) $eval->value;
        }

        // 4. Pasangkan skor nilai ke masing-masing objek siswa
        foreach ($students as $student) {
            $temp_scores = array();
            foreach ($criterias as $crit) {
                // Jika data nilai ada di map, gunakan. Jika tidak ada, set 0.
                $temp_scores[$crit->id] = isset($eval_map[$student->id][$crit->id])
                    ? $eval_map[$student->id][$crit->id]
                    : 0;
            }
            $student->scores = $temp_scores;
        }

        return array(
            'students'  => $students,
            'criterias' => $criterias,
            'classes'   => $classes,
            'period'    => $period,
            'classId'   => $class_id
        );
    }

    /**
     * Rangkuman dari: CreateBulkEvaluationService
     * Menyimpan atau memperbarui nilai evaluasi secara massal menggunakan Transaksi Database
     * * @param array $data Input payload yang berisi ['period'] dan ['scores']
     * @return bool Status akhir transaksi (TRUE jika berhasil, FALSE jika gagal/rollback)
     */
    public function save_bulk_evaluation($data)
    {
        if (empty($data['scores']) || !is_array($data['scores'])) {
            return FALSE;
        }

        // Mulai transaksi database
        $this->db->trans_start();

        foreach ($data['scores'] as $student_id => $criterias) {
            foreach ($criterias as $criteria_id => $value) {

                // Jika nilai 0 (belum dipilih), lewati sesuai dengan logic bisnis awal
                if ($value == 0) {
                    continue;
                }

                // Cek apakah data evaluasi sudah pernah ada sebelumnya (Upsert Logic)
                $this->db->where(array(
                    'student_id'  => $student_id,
                    'criteria_id' => $criteria_id,
                    'period'      => $data['period']
                ));
                $existing = $this->db->get($this->table)->row();

                if ($existing) {
                    // Update data lama
                    $this->db->where('id', $existing->id);
                    $this->db->update($this->table, array('value' => $value));
                } else {
                    // Insert data baru
                    $payload = array(
                        'student_id'  => $student_id,
                        'criteria_id' => $criteria_id,
                        'value'       => $value,
                        'period'      => $data['period']
                    );
                    $this->db->insert($this->table, $payload);
                }
            }
        }

        // Selesaikan transaksi database
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
