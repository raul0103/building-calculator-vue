<?php

/**
 * Основной класс конфига
 * 
 * Здесь храняться данные по калькуляторам
 * Так же от сюда производятся основные действия с данными 
 */

class CalculatorConfig
{
    public $types;

    public function __construct()
    {
        /** 
         * Типы калькулятор - лента, плита и тд. 
         * 
         * Названия типов tape,plate и тд. Используются в расчетах calculator.php
         */
        $this->types = [
            'tape' => [
                'title' => 'Лента',
                'config' => [
                    // Размеры
                    'dimensions' => [
                        'title' => 'Размеры фундамента:',
                        /**
                         * Параметры для расчета
                         * 
                         * @param 'key' - Ключ, он же name у поля в форме
                         * @param 'title' - Текст поля
                         * @param 'required' - Обязателен для заполнения
                         * @param 'placeholder' - плейсхолдер 
                         * @param 'unit' - Единица измерения
                         * @param 'default' - Значение по умолчанию
                         * @param 'values' - Список доступных значений. Откроется через select
                         * @param 'description' - Описание
                         * @param 'step' - Шаг в поле input type="number"
                         * @param 'x'
                         * @param 'x'
                         */
                        'options' => [
                            [
                                'key' => 'length',
                                'title' => 'Длина',
                                'required' => true,
                                'placeholder' => 'Длина',
                                'unit' => 'м'
                            ],
                            [
                                'key' => 'width',
                                'title' => 'Ширина',
                                'required' => true,
                                'placeholder' => 'Ширина',
                                'unit' => 'м'
                            ],
                            [
                                'key' => 'tape_height',
                                'title' => 'Высота ленты',
                                'default' => 0.6,
                                'values' => [0.5, 0.6, 0.7, 0.8, 0.9, 1.0, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8],
                                'unit' => 'м'
                            ],
                            [
                                'key' => 'tape_width',
                                'title' => 'Ширина ленты',
                                'default' => 0.25,
                                'values' => [0.2, 0.25, 0.3, 0.35, 0.4, 0.45, 0.5, 0.55, 0.6],
                                'unit' => 'м'
                            ],
                            [
                                'key' => 'tape_length',
                                'title' => 'Длина ленты',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'placeholder' => 0,
                                'unit' => 'м.п.'
                            ],
                            [
                                'key' => 'foundation_perimeter',
                                'title' => 'Периметр фундамента',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'placeholder' => 0,
                                'unit' => 'м.п.'
                            ],
                            [
                                'key' => 'distance_from_cad',
                                'title' => 'Расстояние от КАД',
                                'default' => 25,
                                'step' => 1,
                                'unit' => 'км'
                            ],
                        ]
                    ],
                    // Доп опции
                    'additionally' => [
                        'title' => 'Дополнительные услуги:',
                        'options' => [
                            [
                                'key' => 'drainage_device',
                                'title' => 'Устройство дренажа',
                                'description' => '(по периметру фундамента)'
                            ],
                            [
                                'key' => 'foundation_waterproofing',
                                'title' => 'Гидроизоляция фундамента',
                                'description' => '(подошва и цоколь)'
                            ],
                            [
                                'key' => 'foundation_insulation',
                                'title' => 'Утепление фундамента',
                                'description' => '(цоколь по периметру)'
                            ],
                            [
                                'key' => 'blind_area_device',
                                'title' => 'Устройство отмостки',
                                'description' => '(система канализационных труб с отводом от фундамента)'
                            ],
                            [
                                'key' => 'sewer_wiring',
                                'title' => 'Разводка канализации',
                                'description' => '(система канализационных труб с отводом от фундамента)'
                            ],
                            [
                                'key' => 'installation_mortgages',
                                'title' => 'Монтаж закладных под воду, электричество и канализацию',
                            ],
                            [
                                'key' => 'electricity_supply',
                                'title' => 'Обеспечение электричеством',
                                'description' => '(аренда генератора на весь этап строительства)'
                            ],
                            [
                                'key' => 'trailer_rental',
                                'title' => 'Аренда вагончика для проживания бригады',
                            ],
                        ]
                    ]
                ]
            ],
            'plate' => [
                'title' => 'Плита',
                'config' => [
                    'dimensions' => [],
                    'additionally' => []
                ]
            ]
        ];
    }

    /** Отдает типы калькуляторов для табов */
    public function getTypesForTabs()
    {
        foreach ($this->types as $type_key => $type_value) {
            $output[] = [
                'key' => $type_key,
                'title' => $type_value['title']
            ];
        }
        return $output;
    }

    /** Отдает все данные по калькуляторам */
    public function getCalculatorData()
    {
        return $this->types;
    }
}
