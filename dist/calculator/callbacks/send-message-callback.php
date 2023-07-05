<?php

/**
 *  Отправляет сообщение в CRM
 */
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/env.php';

if (!function_exists('sendMailCallback')) {
    function sendMailCallback($post_fields)
    {
        $fields = [];
        foreach (json_decode($post_fields) as $field) {
            $fields[$field->name] = $field->value;
        }

        $rest_url = $_ENV['REST_URL'];
        $query_data = http_build_query(
            [
                'fields' => [
                    'TITLE' => 'Заявка с калькулятора',
                    'NAME' => $fields['name'],
                    'PHONE' => [[
                        'VALUE' => $fields['phone'],
                        'VALUE_TYPE' => 'WORK',
                    ]],
                    'EMAIL' => [[
                        'VALUE' => $fields['email'],
                        'VALUE_TYPE' => 'WORK',
                    ]],
                    'SOURCE_ID' => $_ENV['SOURCE_ID'],
                    'SOURCE_DESCRIPTION' => ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/calculator/',
                    'COMMENTS' => "<b>Тип:</b> " . $fields['type'] . "<br/>" . "<b>Адрес:</b> " . $fields['address'] . "<br/>" . "<b>Смета:</b> " . $fields['pdf'],
                ],
                'params' => ["REGISTER_SONET_EVENT" => "Y"]
            ]
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0, CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $rest_url, CURLOPT_POSTFIELDS => $query_data));
        curl_exec($curl);
        curl_close($curl);
    }
}
