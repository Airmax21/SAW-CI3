<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Ranking extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Memuat model matematika SAW hasil rangkuman service layer
        $this->load->model('m_saw');

        // Memuat model kelas untuk kebutuhan dropdown filter
        $this->load->model('m_class');

        // Memuat model kriteria untuk menyuplai kolom dinamis ke PDF
        $this->load->model('m_criteria');

        // Memuat Custom Library Dompdf
        $this->load->library('export_pdf');
    }

    /**
     * Menampilkan halaman hasil kalkulasi matriks normalisasi dan perangkingan SAW
     */
    public function index()
    {
        $period = $this->input->get('period', TRUE);
        if (empty($period)) {
            $period = date('Y-m');
        }

        $class_id = $this->input->get('class_id', TRUE);
        $class_id = ($class_id !== NULL && $class_id !== '') ? (int) $class_id : NULL;

        $data = $this->m_saw->calculate_ranking($period, $class_id);

        $data['classes'] = $this->m_class->get_all();
        $data['selected_class_id'] = $class_id;
        $data['selected_period'] = $period;
        $data['title'] = 'Ranking SAW';

        $this->load->view('layouts/header', $data);
        $this->load->view('pages/ranking/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Mengonversi hasil render template view menjadi file PDF
     */
    public function exportPdf()
    {
        $period = $this->input->get('period', TRUE);
        if (empty($period)) {
            $period = date('Y-m');
        }

        $class_id = $this->input->get('class_id', TRUE);
        $class_id = ($class_id !== NULL && $class_id !== '') ? (int) $class_id : NULL;

        // 1. Ambil data kalkulasi matriks SAW dari model
        $data = $this->m_saw->calculate_ranking($period, $class_id);

        // 2. SOLUSI FIX: Ambil master data kriteria untuk disuplai ke tabel template PDF
        $data['criterias'] = $this->m_criteria->get_all();

        // 3. Ambil metadata detail nama kelas untuk penamaan file ekspor
        $class = $class_id ? $this->m_class->get_by_id($class_id) : NULL;
        $class_name = $class ? $class->class_name : 'Semua Kelas';

        $data['className'] = $class_name;
        $data['period'] = $period;

        // Simpan hasil render view ke memori buffer string
        $html = $this->load->view('components/pdf_template', $data, TRUE);

        // Susun nama file keluaran dokumen
        $filename = 'Ranking-SAW-' . str_replace(' ', '-', $class_name) . '-' . $period . '.pdf';

        // Eksekusi generator melalui custom library Export_pdf
        $this->export_pdf->generate($html, $filename, 'A4', 'portrait');
    }
}
