<?php

include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/env.php';

$to = $_POST['user_email']; // Адрес получателя
$from = $_ENV['MAIL_FROM']; // Адрес отправителя
$subject = 'Смета с сайта ' . $_ENV['SITE_NAME'];
$message = 'Пожалуйста, найдите вложенный файл в письме.';

// Генерируем уникальный разделитель для MIME-сообщения
$separator = md5(time());

// Заголовки письма
$headers = "From: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Текст сообщения
$body = "--$separator\r\n";
$body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $message . "\r\n";

// Вложение файла
$file_path = $_SERVER['DOCUMENT_ROOT'] . 'calculator/pdf/' . $_POST['smeta_pdf_name']; // Путь к вложенному файлу
$file_content = file_get_contents($file_path);
$file_name = basename($file_path);

$body .= "--$separator\r\n";
$body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment\r\n\r\n";
$body .= chunk_split(base64_encode($file_content)) . "\r\n";

$body .= "--$separator--";

// Отправляем письмо
if (mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => true
    ]);
} else {
    echo json_encode([
        'status' => false
    ]);
}
