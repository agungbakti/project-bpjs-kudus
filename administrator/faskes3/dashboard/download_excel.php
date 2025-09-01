<?php
require '../database/function_faskes.php';
require '../dashboard/excel/vendor/autoload.php'; // Composer version
// require '../libs/PhpSpreadsheet/vendor/autoload.php'; // Manual version

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Ambil tanggal dari input GET
$startOfWeek = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d');
$endOfWeek = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

// Validasi format tanggal (opsional tapi disarankan)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startOfWeek) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endOfWeek)) {
    die("Format tanggal tidak valid.");
}

// Ambil data
$dataPeserta = ambilnilaiperminggu($startOfWeek, $endOfWeek);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'Keperluan');
$sheet->setCellValue('D1', 'Lokasi');
$sheet->setCellValue('E1', 'Faskes');
$sheet->setCellValue('F1', 'Kabupaten');
$sheet->setCellValue('G1', 'Nama Peserta');
$sheet->setCellValue('H1', 'NIK');
$sheet->setCellValue('I1', 'No HP');
$sheet->setCellValue('J1', 'Email');
$sheet->setCellValue('K1', 'Status');
$sheet->setCellValue('L1', 'Keterangan');

$i = 2;
$no = 1;
foreach ($dataPeserta as $row) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $row['tanggal']);
    $sheet->setCellValue('C' . $i, $row['keperluan']);
    $sheet->setCellValue('D' . $i, $row['lokasi']);
    $sheet->setCellValue('E' . $i, $row['fktp_dan_rumahsakit']);
    $sheet->setCellValue('F' . $i, $row['kabupaten']);
    $sheet->setCellValue('G' . $i, $row['nama_peserta']);
    $sheet->setCellValueExplicit('H' . $i, $row['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValueExplicit('I' . $i, $row['nomorhp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('J' . $i, $row['email']);
    $sheet->setCellValue('K' . $i, $row['status']);
    $sheet->setCellValue('L' . $i, $row['keterangan']);
    $i++;
}


// Output file
$filename = 'data peserta registrasi mobile jkn.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
