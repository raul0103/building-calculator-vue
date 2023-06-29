<?php
require $_SERVER['DOCUMENT_ROOT'] . '/calculator/vendor/autoload.php';

use Dompdf\Dompdf;

$tableHtml = $_POST['table_html'];

$dompdf = new Dompdf();

$dompdf->loadHtml($tableHtml);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$output = $dompdf->output();

// Путь к папке для сохранения PDF-файла
$dir = $_SERVER['DOCUMENT_ROOT'] . '/calculator/pdf/';
if (!is_dir($dir)) {
    mkdir($dir, 0775, true);
}

$filename = 'smeta_' . time() . '.pdf';

// Сохранение PDF-файла
file_put_contents($dir . $filename, $output);

// Отдаем имя файла для скачивания
echo json_encode(['filename' => $filename], true);
