<?php

include '../utils/cors.php';

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
        include '../calculators/Tape.php';
        $calculate = new Tape($_GET['values']);
        echo json_encode($calculate->result());
        return;
        /** Расчет плиты */
    case 'plate':
        include '../calculators/Plate.php';
        $calculate = new Plate($_GET['values']);
        echo json_encode($calculate->result());
        return;
        /** Плита с нижним ростверком */
    case 'plate_grillage_low':
        include '../calculators/DrenageLow.php';
        $calculate = new DrenageLow($_GET['values']);
        echo json_encode($calculate->result());
        return;
        /** Плита с верхним ростверком */
    case 'plate_grillage_up':
        include '../calculators/DrenageUp.php';
        $calculate = new DrenageUp($_GET['values']);
        echo json_encode($calculate->result());
        return;
}
