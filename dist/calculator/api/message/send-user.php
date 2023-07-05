<?php
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/env.php';

$to = $_POST['user_email']; // Адрес получателя
$from = $_ENV['MAIL_FROM']; // Адрес отправителя
$subject = 'Сообщение с калькулятора ' . $_ENV['SITE_NAME'];
$headers = 'From: ' . $from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$message = 'Пожалуйста, найдите вложенный файл в письме.';

// Путь к файлу, который нужно вложить
$file_path = $_SERVER['DOCUMENT_ROOT'] . '/calculator/pdf/' . $_POST['smeta_pdf_name']; // Путь к вложенному файлу
$file_name = basename($file_path);
$file_content = file_get_contents($file_path);

// Генерируем уникальный разделитель для MIME-сообщения
$separator = md5(time());

// Заголовки письма
$mail_headers = "From: $from\r\n";
$mail_headers .= "MIME-Version: 1.0\r\n";
$mail_headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"\r\n";
$mail_headers .= "X-Mailer: PHP/" . phpversion();

// Текст сообщения
$mail_body = "--$separator\r\n";
$mail_body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$mail_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$mail_body .= $message . "\r\n";

// Вложение файла
$mail_body .= "--$separator\r\n";
$mail_body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
$mail_body .= "Content-Transfer-Encoding: base64\r\n";
$mail_body .= "Content-Disposition: attachment\r\n\r\n";
$mail_body .= chunk_split(base64_encode($file_content)) . "\r\n";

$mail_body .= "--$separator--";

// Отправляем письмо с вложенным файлом
if (mail($to, $subject, $mail_body, $mail_headers)) {
    echo json_encode([
        'status' => true
    ]);
} else {
    echo json_encode([
        'status' => false
    ]);
}
