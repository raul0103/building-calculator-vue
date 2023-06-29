<?php

/**
 * Отдает данные по калькуляторам при загрузке страницы
 */

include '../utils/cors.php';
include '../config/CalculatorConfig.php';

$calculator_config = new CalculatorConfig();

// Данные для табов
$output['calculator_tabs']  = $calculator_config->getTypesForTabs();
// Все данные. Используются для получения опций по калькулятору
$output['calculator_data']  = $calculator_config->getCalculatorData();
// Активный калькулятор. По умолчанию первый
$output['calculator_active_key'] = $output['calculator_tabs'][0]['key'];
// Данные активного калькулятора
$output['calculator_data_active'] = $output['calculator_data'][$output['calculator_active_key']];

echo json_encode($output);
