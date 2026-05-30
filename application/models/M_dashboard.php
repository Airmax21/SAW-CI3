<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Rangkuman dari: DashboardService
     * Mengambil data statistik makro dan list siswa beserta kelengkapan evaluasinya
     * @param int|null $class_id
     * @return array Data agregat dashboard
     */
    public function get_dashboard_data($class_id = null)
    {
        $current_period = date('Y-m');

        // 1. Ambil semua data master kelas untuk keperluan filter tab di View
        $classes = $this->db->get('classes')->result();

        // 2. Hitung total seluruh siswa di sistem (Statistik Makro)
        $total_students = $this->db->count_all_results('students');

        // 3. Ambil data siswa dengan teknik JOIN dan Sub-query Agregat (Sangat Efisien)
        // Kita hitung jumlah evaluasi langsung di level database menggunakan sub-query SELECT COUNT
        $this->db->select('
            students.*, 
            classes.class_name,
            (SELECT COUNT(*) 
             FROM evaluations 
             WHERE evaluations.student_id = students.id 
             AND evaluations.period = ' . $this->db->escape($current_period) . '
            ) as eval_count
        ');
        $this->db->from('students');
        $this->db->join('classes', 'classes.id = students.class_id', 'left');

        // Filter berdasarkan kelas jika parameter diisi
        if ($class_id !== null) {
            $this->db->where('students.class_id', $class_id);
        }

        $this->db->order_by('students.full_name', 'ASC');
        $students = $this->db->get()->result();

        // 4. Hitung statistik riil siswa yang sudah dievaluasi berdasarkan data yang ditarik
        $evaluated_count = 0;
        foreach ($students as $student) {
            // Konversi properti eval_count dari sub-query menjadi boolean is_evaluated
            $student->is_evaluated = ((int) $student->eval_count > 0);

            if ($student->is_evaluated) {
                $evaluated_count++;
            }

            // Hapus properti eval_count mentah jika ingin menjaga data tetap clean
            unset($student->eval_count);
        }

        // 5. Kembalikan payload array penuh sesuai struktur ekspektasi Controller
        return array(
            'students'       => $students,
            'classes'        => $classes,
            'classId'        => $class_id,
            'totalStudents'  => $total_students,
            'evaluatedCount' => $evaluated_count,
            'pendingCount'   => max(0, $total_students - $evaluated_count),
            'currentMonth'   => date('F') // Mengembalikan nama bulan saat ini (e.g., May)
        );
    }
}
