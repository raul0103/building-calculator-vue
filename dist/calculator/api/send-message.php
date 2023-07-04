<?php
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/config/config.php';

$subject = 'Сообщение с калькулятора ' . SITE_NAME;
$headers = 'From: ' . MAIL_FROM . "\r\n" .
    'Reply-To: ' . MAIL_FROM . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$message = '';
$valid_form = true;
$invalid_fields = [];
foreach (json_decode($_POST['fields']) as $field) {
    $message .= "$field->name: $field->value\n";

    if (!$field->valid) {
        $invalid_fields[] = '"' . $field->name . '"';
        $valid_form = false;
    }
}

if (!$valid_form) {
    echo json_encode([
        'status' => false,
        'message' => 'Форма не отправлена. Поля которые не прошли проверку ' . implode(',', $invalid_fields)
    ]);
    return;
} else {
    mail(MAIL_TO, $subject, $message, $headers);
    echo json_encode([
        'status' => true,
        'message' => 'Ваша заявка принята'
    ]);
    return;
}
