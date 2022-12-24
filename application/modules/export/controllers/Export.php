<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

// use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
// use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
// use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
// use Box\Spout\Common\Entity\Row;
// use Box\Spout\Common\Entity\Style\Border;
// use Box\Spout\Common\Entity\Style\CellAlignment;
// use Box\Spout\Common\Entity\Style\Color;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;

class Export extends MX_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        // $this->M_global->firstload();
        $this->load->model(["M_dashboard", "M_absensi", "M_register_b2b"]);
        ini_set('memory_limit', '-1');
    }

    public function index()
    {
        ob_start();
        $i = 0;
        $i++;
        $data['title'] = "Export DaTA";
        $this->load->view('index', $data);
        ob_end_flush();
        $this->flush_buffer();
        $start = 0;
        $total_item = 12;
        $maks = 100;
        $time = date("d M Y H:i:s");
        echo "<script>";
        echo "  $('#log-wrapper').html('<p>$time - Preparing Data</p>')";
        echo "</script>";
        $increment = $maks / $total_item;
        $item = $start;
        $limit = ($maks + $increment - 1);

        for ($i = $start, $j = $item; $i < $limit; $i += $increment) {
            $temp = $i;
            if ($i > 100) $i = 100;
            $this->update_progress('progressbar-download', $i, $item, $total_item);
            $item += 1;
            $j += 1;
            if ($i > 100) $i = $temp;
        }
        $time = date("d M Y H:i:s");
        echo "<script>";
        echo "  $('#log-wrapper').prepend('<p>$time - Ready to Download</p>');";
        echo "  $('#button-download').removeAttr('disabled');";
        echo "  $('#button-download').attr('class', 'btn btn-sm btn-success');";
        echo "  $('#button-download').html('Download');";
        echo "</script>";
        $this->flush_buffer();
        ob_end_clean();
    }
    protected function update_progress($id, $progress, $current_item, $total)
    {
        $percent = number_format($progress, 2);
        echo "<script>";
        if ($current_item > $total) {
            $current_item = $total;
        }
        if ($current_item > 0) {
            $time = date("d M Y H:i:s");
            echo "  $('#log-wrapper').prepend('<p>$time - Loading Item ( " . ($current_item) . " )</p>');";
        }

        echo '  document.getElementById("item-label").innerHTML = "Item : ' . $current_item . ' / ' . $total . '";';
        echo '  document.getElementById("' . $id . '").innerHTML = "' . $percent . ' %";';
        echo "  $('#$id').css('width', '" . ($percent) . "%')";
        echo "</script>";
        $this->flush_buffer();
        sleep(1);
    }
    protected function flush_buffer()
    {
        ob_flush();
        flush();
        ob_end_flush();
        ob_start();
    }

    public function download_absensi()
    {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
        log_message('error', "======================== x-x-x-x =========================");
        ob_end_flush();
        ob_start();
        $data['name']  = 'MASTER PACKAGING';
        // $this->data_master_packaging();
        

		$starttime = microtime(true); // Top of page
		// Code
		
		$month = $_GET["month"] ?: date("m");
		$year = $_GET["year"] ?: date("Y");
		log_message('error', "month  : " . $month);
		
		// $data_log = $this->log_excel($month, $year);
		// $data_absen = $this->ajax_excel($month, $year);
        ob_end_clean();
    }

    public function xlsx()
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        // $writer = WriterEntityFactory::createODSWriter();
        // $writer = WriterEntityFactory::createCSVWriter();

        // $a = $writer->setTempFolder('./assets/temp');
        // $baseTemp = sys_get_temp_dir();
        $baseTemp = APPPATH . "storage/file_export";
        $a = $writer->setTempFolder($baseTemp);
        $namaFile = 'data_item' . date('YmdHis') . '.xlsx';
        // $filePath = FCPATH . 'assets/temp/' . $namaFile;
        $filePath = $baseTemp . '/' . $namaFile;
        // $http_path = base_url() . '/assets/temp/' . $namaFile;
        $http_path = base_url() . 'export/download_file?filename=' . $namaFile;

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Arial')
            ->setFontSize(10)
            ->setShouldWrapText(false)
            ->build();
        $writer->setDefaultRowStyle($defaultStyle)
            ->openToFile($filePath);
        // $writer->openToFile($filePath);
        // $writer->openToBrowser($fileName); // stream data directly to the browser

        $borderDefa = (new BorderBuilder())
            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->build();

        $styleHeader = (new StyleBuilder())
            ->setFontBold()
            ->setBorder($borderDefa)
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->build();
        $styleData = (new StyleBuilder())
            ->setBorder($borderDefa)
            ->build();

        $cells = [
            WriterEntityFactory::createCell('Carl'),
            WriterEntityFactory::createCell('is'),
            WriterEntityFactory::createCell('great!'),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($cells, $styleHeader);
        $writer->addRow($singleRow);

        /** Shortcut: add a row from an array of values */
        $values = ['Carl', 'is', 'great!'];
        $rowFromValues = WriterEntityFactory::createRowFromArray($values, $styleData);
        $writer->addRow($rowFromValues);

        $writer->close();

        echo "done";
    }

    public function download_rekap()
    {
        $day = $this->input->get('day');
		$month = $this->input->get('month') ? $this->input->get('month') : date("m");
		$year = $this->input->get('year') ? $this->input->get('year') : date("Y");

        ini_set('max_execution_time', 3600);
        ini_set('max_input_time', 3600);
        set_time_limit(3600);
        ini_set('memory_limit', '16384M');

        $data['name']  = 'Rekap Absensi';
        ob_end_flush();
        ob_start();
        echo $this->load->view('export/download', $data, true);
        echo "<script>  update_progress(1,'Fetching Data...')        </script>";
        $this->flush_buffer();
        $this->get_data_rekap_absensi($day,$month,$year);
    }

    public function get_data_rekap_absensi($day,$month,$year)
    {
        echo "<script>  update_progress(15,'Fetching Data...')        </script>";
        $this->flush_buffer();

        $day_param = $day;
        $month_param = $month;
		$year_param = $year;
		
        $data = $this->M_absensi->fetch_v3_export($day_param,$month_param,$year_param);

        echo "<script>  update_progress(25,'Preparing Data (fill to memory holder)...')        </script>";
        $this->flush_buffer();
        // echo "b"; die();

        $dataHeader = array('Payrol ID','Nama Lengkap','Organization','Nama Shift','Durasi Shift','Shift Mulai','Shift Akhir','Waktu Masuk','Waktu Pulang','Waktu Telat Masuk','Pulang Lebih Awal','Total Kerja','Total Lembur Awal','Total Lembur Akhir','Tempat Masuk','Tempat Keluar','Status Lembur','Remark Lembur');

        $writer = WriterEntityFactory::createXLSXWriter();
        // $a = $writer->setTempFolder('./assets/temp');
        // $baseTemp = sys_get_temp_dir();
        $baseTemp = APPPATH . "storage/file_export";
        $a = $writer->setTempFolder($baseTemp);
        $namaFile = 'data_item' . date('YmdHis') . '.xlsx';
        // $filePath = FCPATH . 'assets/temp/' . $namaFile;
        $filePath = $baseTemp . '/' . $namaFile;
        // $http_path = base_url() . '/assets/temp/' . $namaFile;
        $http_path = base_url() . 'export/download_file?filename=' . $namaFile;

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Arial')
            ->setFontSize(10)
            ->setShouldWrapText(false)
            ->build();
        $writer->setDefaultRowStyle($defaultStyle)
            ->openToFile($filePath);

        $borderDefa = (new BorderBuilder())
            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
            ->build();

        $styleHeader = (new StyleBuilder())
            ->setFontBold()
            ->setBorder($borderDefa)
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->build();

        // $writer->addRowWithStyle($dataHeader, $styleHeader);
        $row = WriterEntityFactory::createRowFromArray($dataHeader, $styleHeader);
        $writer->addRow($row);

        $styleData = (new StyleBuilder())
            ->setBorder($borderDefa)
            ->build();

        $count = count($data['data']);

        $x = 0;
        foreach ($data['data'] as $key) {
            $row = WriterEntityFactory::createRowFromArray($key, $styleData);
            $writer->addRow($row);
            $progress = number_format(25 + ($x / $count * 74), 1);

            echo "<script>  update_progress(" . $progress . ",'Writing File... (" . $progress . "%)')        </script>";
            $this->flush_buffer();
            $x++;
        }

        $writer->close();
        echo "<script>  update_progress(100,'Export Data Ready <br> <a class=\"btn btn-warning\"  href=\"" . $http_path . "\" download>Download</a>')        </script>";
        $this->flush_buffer();
    }

    public function download_file()
    {
        $namaFile = $_GET['filename'] ?: "";
        if ($namaFile) {
            $fullPath = APPPATH . "storage/file_export" . "/$namaFile";
            if (file_exists($fullPath)) {
                $this->load->helper('download');
                force_download($fullPath, NULL);
                redirect('data_item');
                return;
            }
        }
        echo json_encode((object) [
            "status" => "Error",
            "message" => "File not found"
        ]);
    }
}
