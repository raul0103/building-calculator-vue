<?php

include './utils/cors.php';

if ($_GET['form_error']) {
    echo json_encode([
        'status' => false,
        'message' => 'В форме содержатся ошибки'
    ]);
    return;
};

switch ($_GET['calculator_key_active']) {
        /** Расчет ленточного фундамента */
    case 'tape':
        include './calculate/Tape.php';
        $calculate = new Tape($_GET['values']);
        echo $calculate->result();
        return;
}
