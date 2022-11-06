<?php
include('koneksi.php');
require 'vendor/autoload.php';
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
 
$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if(isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
 
    $arr_file = explode('.', $_FILES['berkas_excel']['name']);
    $extension = end($arr_file);
 
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }
 
    $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
     
    $sheetData = $spreadsheet->getActiveSheet()->toArray();
	for($i = 1;$i < count($sheetData);$i++)
	{
        $tgl = date_format(date_create($sheetData[$i]['0']),'Y-m-d');
        $ref = 'Import '.$sheetData[$i]['1'];
        $merk = $sheetData[$i]['2'];
        $tipe = $sheetData[$i]['3'];
        $qty = $sheetData[$i]['4'];
        // var_dump($tgl);
        try {
            $sql = "insert into ttransaksi values (0,'$tgl','$ref','$merk','$tipe',$qty)";
            // var_dump($sql);
            mysqli_query($koneksi,$sql);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
        
    }
    header("Location: http://localhost:888/downstore/home/transaksi"); 
}
?>