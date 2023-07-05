<?php

/**
 * В функции присутствует callback для другой логики необходимой для разных сайтов 
 */

include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/env.php';
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/config/config.php';
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/callbacks/send-message-callback.php';

// Вызываем функцию обратного вызова, если она определена и разрешена
if (function_exists('sendMailCallback') && MESSAGE_CALLBACK) {
    sendMailCallback($_POST['fields']);
} else {
    sendMessageToEmail();
}

function sendMessageToEmail()
{
    $subject = 'Сообщение с калькулятора ' . $_ENV['SITE_NAME'];
    $headers = 'From: ' . $_ENV['MAIL_FROM'] . "\r\n" .
        'Reply-To: ' . $_ENV['MAIL_FROM'] . "\r\n" .
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
    } else {
        mail($_ENV['MAIL_TO'], $subject, $message, $headers);
        echo json_encode([
            'status' => true,
            'message' => 'Ваша заявка принята'
        ]);
    }
}
