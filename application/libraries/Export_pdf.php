<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memastikan Autoload Composer termuat agar bisa membaca class Dompdf
// Jika kamu mengaktifkan $config['composer_autoload'] = TRUE di application/config/config.php, 
// baris require_once di bawah ini bisa dihapus.
if (file_exists(FCPATH . 'vendor/autoload.php')) {
    require_once FCPATH . 'vendor/autoload.php';
}

use Dompdf\Dompdf;
use Dompdf\Options;

class Export_pdf
{

    /**
     * Rangkuman dari: ExportPDFService
     * Menggenerasi HTML menjadi file PDF dan langsung melempar stream download ke browser
     * * @param string $html Template HTML yang akan dijadikan PDF
     * @param string $filename Nama file output ketika didownload
     * @param string $paper Ukuran kertas (A4, Letter, dll)
     * @param string $orientation Orientasi kertas (portrait / landscape)
     * @return void
     */
    public function generate($html, $filename = 'document.pdf', $paper = 'A4', $orientation = 'portrait')
    {
        // 1. Konfigurasi opsi Dompdf agar mendukung HTML5 dan resource eksternal
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan load CSS/Gambar dari URL lokal/internet

        // 2. Inisialisasi Engine Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);

        // 3. Proses rendering HTML ke PDF
        $dompdf->render();

        // 4. Kirim output file langsung ke browser untuk otomatis Download
        $dompdf->stream($filename, array("Attachment" => true));

        // Menghentikan eksekusi script agar tidak ada output buffer liar dari CI3 yang merusak file PDF
        exit();
    }
}
