<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menginduk ke MY_Controller untuk otomatisasi proteksi login
 */
class Criteria extends MY_Controller
{

    public function __construct()
    {
        // Menjalankan konstruktor induk untuk validasi session
        parent::__construct();

        // Memuat model kriteria tunggal hasil rangkuman services
        $this->load->model('m_criteria');

        // Memuat library penunjang validasi input form
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan halaman daftar kriteria dan sub-kriteria
     * Menggantikan GetCriteriaService
     */
    public function index()
    {
        $data = array(
            'title'     => 'Pengaturan Kriteria & Bobot SAW',
            'criterias' => $this->m_criteria->get_all_with_subs() // Eager loading manual kriteria + subs
        );

        // Render halaman menggunakan tumpukan Cara 1 (Header -> Konten -> Footer)
        $this->load->view('layouts/header', $data);
        $this->load->view('pages/criteria/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Memproses penyimpanan kriteria utama dan pemecahan string sub-kriteria
     * Menggantikan CreateCriteriaService
     */
    public function store()
    {
        // Menyusun aturan validasi form gaya CI3
        $rules = array(
            array(
                'field'  => 'code',
                'label'  => 'Kode Kriteria',
                'rules'  => 'required|is_unique[criterias.code]|max_length[5]',
                'errors' => array(
                    'required'  => '%s wajib diisi.',
                    'is_unique' => '%s sudah digunakan oleh kriteria lain.',
                    'max_length' => '%s maksimal berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'criteria_name',
                'label'  => 'Nama Kriteria',
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'required'   => '%s wajib diisi.',
                    'min_length' => '%s minimal harus berisi %s karakter.'
                )
            ),
            array(
                'field'  => 'type',
                'label'  => 'Tipe Kriteria',
                'rules'  => 'required|in_list[benefit,cost]',
                'errors' => array(
                    'required' => '%s wajib dipilih.',
                    'in_list'  => '%s harus berupa benefit atau cost.'
                )
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === FALSE) {

            // Rekam error validasi ke dalam array
            $validation_errors = array(
                'code'          => form_error('code'),
                'criteria_name' => form_error('criteria_name'),
                'type'          => form_error('type')
            );

            $this->session->set_flashdata('errors', array_filter($validation_errors));

            // Retensi input form lama jika gagal
            $old_input = array(
                'code'          => $this->input->post('code', TRUE),
                'criteria_name' => $this->input->post('criteria_name', TRUE),
                'type'          => $this->input->post('type', TRUE),
                'sub_names'     => $this->input->post('sub_names', TRUE)
            );
            $this->session->set_flashdata('old_input', $old_input);

            redirect('criteria');
        } else {
            // Ambil payload input POST
            $payload = array(
                'code'          => $this->input->post('code', TRUE),
                'criteria_name' => $this->input->post('criteria_name', TRUE),
                'type'          => $this->input->post('type', TRUE),
                'sub_names'     => $this->input->post('sub_names', TRUE) // Berupa string dipisahkan koma
            );

            // Eksekusi model dengan database transaction di dalamnya
            if ($this->m_criteria->create_with_subs($payload)) {
                $this->session->set_flashdata('success', 'Kriteria baru berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('errors', array('Gagal menyimpan kriteria.'));
            }

            redirect('criteria');
        }
    }

    /**
     * Memperbarui bobot kriteria secara massal dengan validasi total 100%
     * Menggantikan UpdateCriteriaService
     */
    public function update()
    {
        $weights = $this->input->post('weights', TRUE);

        if (!$weights) {
            $this->session->set_flashdata('errors', array('Tidak ada data bobot yang dikirim.'));
            redirect('criteria');
        }

        try {
            // Jalankan validasi total bobot 100% di level Model
            $this->m_criteria->update_weights($weights);

            $this->session->set_flashdata('success', 'Bobot kriteria berhasil diperbarui.');
        } catch (Exception $e) {
            // Tangkap pesan error exception dari model jika total bobot tidak sesuai 100%
            $this->session->set_flashdata('errors', array($e->getMessage()));

            // Simpan state input bobot yang gagal agar tidak ter-reset di form
            $this->session->set_flashdata('old_weights', $weights);
        }

        redirect('criteria');
    }

    /**
     * Menghapus data kriteria utama dan sub-kriterianya berdasarkan ID
     * Menggantikan DeleteCriteriaService
     * @param int $id
     */
    public function delete($id)
    {
        $id = (int) $id;

        if ($this->m_criteria->delete_criteria($id)) {
            $this->session->set_flashdata('success', 'Kriteria berhasil dihapus.');
        } else {
            $this->session->set_flashdata('errors', array('Gagal menghapus kriteria.'));
        }

        redirect('criteria');
    }
}
