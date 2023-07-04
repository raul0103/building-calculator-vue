<?php
$filename = $_GET['filename'];

// Установка заголовков для скачивания файла
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT'] . '/calculator/pdf/' . $filename));

// Чтение и вывод содержимого файла
readfile($_SERVER['DOCUMENT_ROOT'] . '/calculator/pdf/' . $filename);
