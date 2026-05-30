<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_saw extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Rangkuman dari: CalculateRankingService
     * Melakukan kalkulasi metode SAW secara realtime dan efisien untuk SQLite
     * * @param string $period Format 'Y-m'
     * @param int|null $class_id
     * @return array Hasil ranking, master kriteria, dan metadata periode
     */
    public function calculate_ranking($period, $class_id = null)
    {
        // 1. Ambil Semua Kriteria & Bobot
        $this->db->order_by('code', 'ASC');
        $criterias = $this->db->get('criterias')->result();
        if (empty($criterias)) {
            return array();
        }

        // 2. Ambil Data Master Siswa (Filter Kelas jika ada)
        if ($class_id) {
            $this->db->where('class_id', $class_id);
        }
        $this->db->order_by('full_name', 'ASC');
        $students = $this->db->get('students')->result();
        if (empty($students)) {
            return array(
                'ranking'   => array(),
                'criterias' => $criterias,
                'period'    => $period
            );
        }

        // 3. EAGER LOADING DATA EVALUASI (Peningkatan Efisiensi Utama)
        // Ambil semua data evaluasi pada periode terpilih dalam 1 kali query saja
        $this->db->where('period', $period);
        $all_evaluations = $this->db->get('evaluations')->result();

        // Susun ke dalam Temporary Map Memory dan cari Nilai Max/Min secara real-time
        $eval_map = array();
        $raw_values_per_crit = array();

        foreach ($all_evaluations as $eval) {
            $eval_map[$eval->student_id][$eval->criteria_id] = (float) $eval->value;
            $raw_values_per_crit[$eval->criteria_id][] = (float) $eval->value;
        }

        // 4. Bangun Nilai Maksimum & Minimum per Kriteria untuk Pembagi Normalisasi
        $max_min = array();
        foreach ($criterias as $crit) {
            $values = isset($raw_values_per_crit[$crit->id]) ? $raw_values_per_crit[$crit->id] : array();

            if (empty($values)) {
                $max_min[$crit->id] = array('max' => 1.0, 'min' => 1.0);
                continue;
            }

            $current_max = max($values);
            $current_min = min($values);

            $max_min[$crit->id] = array(
                'max' => $current_max > 0 ? $current_max : 1.0,
                'min' => $current_min > 0 ? $current_min : 1.0
            );
        }

        // 5. Hitung Skor SAW Menggunakan Memory Map (Tanpa Hit Database lagi)
        $ranking_result = array();
        foreach ($students as $student) {
            $total_score = 0;
            $matrix = array();

            foreach ($criterias as $crit) {
                // Ambil nilai dari memory map, default 0 jika belum diisi
                $value = isset($eval_map[$student->id][$crit->id]) ? $eval_map[$student->id][$crit->id] : 0.0;

                // Eksekusi Rumus Normalisasi R_ij
                if ($crit->type === 'benefit') {
                    $r = $value / $max_min[$crit->id]['max'];
                } else {
                    $r = ($value == 0.0) ? 0.0 : ($max_min[$crit->id]['min'] / $value);
                }

                // Hitung V_i (Hasil Kali Normalisasi dengan Persentase Bobot)
                $v = $r * ((float) $crit->weight / 100);
                $total_score += $v;

                // Masukkan ke dalam matriks penunjang View
                $matrix[$crit->id] = array(
                    'raw'        => $value,
                    'normalized' => $r
                );
            }

            // Kembalikan dalam bentuk Standard Object (stdClass) agar kompatibel dengan View Entity CI4 lama kamu
            $ranking_result[] = (object) array(
                'student_name' => $student->full_name,
                'matrix'       => $matrix,
                'total_score'  => $total_score
            );
        }

        // 6. Urutkan Hasil Berdasarkan Skor Tertinggi (Ranking Berjalan)
        usort($ranking_result, function ($a, $b) {
            return $b->total_score <=> $a->total_score;
        });

        // 7. Return Payload Bungkus Utama
        return array(
            'ranking'   => $ranking_result,
            'criterias' => $criterias,
            'period'    => $period
        );
    }
}
