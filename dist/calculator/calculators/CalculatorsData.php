<?php

/**
 * Здесь храняться данные по калькуляторам
 * Так же от сюда производятся основные действия с данными 
 * TODO: В будущем можно эти данные сделать редактируемыми и хранить например в json формате
 */

class CalculatorsData
{
    public $types;
    public $additional_expenses;

    public function __construct()
    {
        /** 
         * Типы калькулятор - лента, плита и тд. 
         * 
         * Названия типов tape,plate и тд. 
         * Используются в для фронта
         */
        $this->types = [
            'tape' => [
                'title' => 'Лента',
                'image' => [
                    'style' => ['', 'position: relative; left: 42px; top: -25px;'],
                    'src' =>  [
                        'calculator/assets/images/tape/basic_01.png',
                        'calculator/assets/images/tape/basic_02.png'
                    ]
                ],
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
                         * @param 'image' - для фронта
                         * @param 'x'
                         */
                        'options' => [
                            [
                                'key' => 'length',
                                'title' => 'Длина',
                                'required' => true,
                                'placeholder' => 'Длина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/tape/param_01.png'
                                ]
                            ],
                            [
                                'key' => 'width',
                                'title' => 'Ширина',
                                'required' => true,
                                'placeholder' => 'Ширина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 220px; top: -108px; ',
                                    'src' => 'calculator/assets/images/tape/param_02.png'
                                ]
                            ],
                            [
                                'key' => 'tape_height',
                                'title' => 'Высота ленты',
                                'default' => 0.6,
                                'values' => [0.5, 0.6, 0.7, 0.8, 0.9, 1.0, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8],
                                'unit' => 'м',
                                'image' => [
                                    'style' => 'left: 0; top: -96px; ',
                                    'src' => 'calculator/assets/images/tape/param_03.png'
                                ]
                            ],
                            [
                                'key' => 'tape_width',
                                'title' => 'Ширина ленты',
                                'default' => 0.3,
                                'values' => [0.2, 0.25, 0.3, 0.35, 0.4, 0.45, 0.5, 0.55, 0.6],
                                'unit' => 'м',
                                'image' => [
                                    'style' => 'left: 152px; top: -58px; ',
                                    'src' => 'calculator/assets/images/tape/param_04.png'
                                ]
                            ],
                            [
                                'key' => 'tape_length',
                                'title' => 'Длина ленты',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'image' => [
                                    'style' => 'left: 65px; top: -128px;',
                                    'src' => 'calculator/assets/images/tape/param_05.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_perimeter',
                                'title' => 'Периметр фундамента',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'image' => [
                                    'style' => 'left: 46px; top: -179px;',
                                    'src' => 'calculator/assets/images/tape/param_06.png'
                                ]
                            ],
                            [
                                'key' => 'distance_from_cad',
                                'title' => 'Расстояние от КАД',
                                'required' => true,
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
                                'description' => '(по периметру фундамента)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/tape/extra_01.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_waterproofing',
                                'title' => 'Гидроизоляция фундамента',
                                'description' => '(подошва и цоколь)',
                                'image' => [
                                    'style' => 'left: 41px; top: -120px;z-index:1',
                                    'src' => 'calculator/assets/images/tape/extra_02.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_insulation',
                                'title' => 'Утепление фундамента',
                                'description' => '(цоколь по периметру)',
                                'image' => [
                                    'style' => 'left: 45px; top: -121px;z-index:2',
                                    'src' => 'calculator/assets/images/tape/extra_03.png'
                                ]
                            ],
                            [
                                'key' => 'blind_area_device',
                                'title' => 'Устройство отмостки',
                                'description' => '(система канализационных труб с отводом от фундамента)',
                                'image' => [
                                    'style' => 'top: -80px; left: 235px;z-index:1',
                                    'src' => 'calculator/assets/images/tape/extra_04.png'
                                ]
                            ],
                            [
                                'key' => 'sewer_wiring',
                                'title' => 'Разводка канализации',
                                'description' => '(система канализационных труб с отводом от фундамента)'
                            ],
                            [
                                'key' => 'installation_mortgages',
                                'title' => 'Монтаж закладных под воду, электричество и канализацию'
                            ],
                            [
                                'key' => 'electricity_supply',
                                'title' => 'Обеспечение электричеством',
                                'description' => '(аренда генератора на весь этап строительства)',
                                'image' => [
                                    'style' => 'left: 44px; top: -185px;z-index:3px',
                                    'src' => 'calculator/assets/images/tape/extra_07.png'
                                ]
                            ],
                            [
                                'key' => 'trailer_rental',
                                'title' => 'Аренда вагончика для проживания бригады',
                                'image' => [
                                    'style' => 'left: 91px; top: -218px; z-index: 0;',
                                    'src' => 'calculator/assets/images/tape/extra_08.png'
                                ]
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
            ],
            'plate_grillage' => [
                'title' => 'Плита с нижним ростверком',
                'config' => [
                    'dimensions' => [],
                    'additionally' => []
                ]
            ]
        ];

        /**
         * Дополнительные расходы
         * 
         * Используются для расчета в calculators/ и выводятся с результатами
         * Так же делятся на типы tape,plate и тд.
         */
        $this->additional_expenses = [
            'tape' => [
                'works' => [
                    'title' => 'Работы',
                    'options' => [
                        'breakdown_axes' => [
                            'title' => 'Планировка, разбивка осей',
                            'quantity' => 1,
                            'basic_price' => [6000],
                            'unit' => 'шт'
                        ],
                        'excavation' => [
                            'title' => 'Земляные работы (рытье траншеи)',
                            'basic_price' => [1000],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile_laying' => [
                            'title' => 'Укладка геотекстиля',
                            'quantity_coeff' => [0.6, 1.15],
                            'basic_price' => [20],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'gravel_pad_device' => [
                            'title' => 'Устройство песчано-гравийной подушки',
                            'quantity_coeff' => [1.3],
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'concrete_works' => [
                            'title' => 'Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона)',
                            'basic_coeff' => 'УСЛОВИЕ: <10 | >=10 ; <15 | -',
                            'quantity_coeff' => [1.05],
                            'basic_price' => [8000, 7000, 6000],
                            'unit' => 'м<sup>3</sup>'
                        ]
                    ]
                ],
                'materials' => [
                    'title' => 'Материалы',
                    'options' => [
                        'sand_delivery' => [
                            'title' => 'Песок с доставкой',
                            'basic_coeff' => 'УСЛОВИЕ: > 10 | -',
                            'basic_price' => [550, 800],
                            'quantity_coeff' => [1.3],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile' => [
                            'title' => 'Геотекстиль',
                            'quantity_coeff' => [0.6, 1.15],
                            'basic_price' => [35],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'technical_film' => [
                            'title' => 'Пленка техническая',
                            'quantity_coeff' =>  [0.1, 1.2],
                            'basic_price' => [15],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'formwork' => [
                            'title' => 'Опалубка',
                            'quantity_coeff' => [1.15],
                            'basic_price' => [8500],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'armature_a500С_d12mm' => [
                            'title' => 'Арматура А500С d12мм',
                            'quantity_coeff' => [0.888, 1.25, 1000],
                            'basic_price' => [41200],
                            'unit' => 'т'
                        ],
                        'armature_a500С_d8mm' => [
                            'title' => 'Арматура А500С d8мм',
                            'quantity_coeff' => [0.495, 1.15, 1000],
                            'basic_price' => [45900],
                            'unit' => 'т'
                        ],
                        'commercial_concrete' => [
                            'title' => 'Бетон товарный В22,5М300П3 с доставкой',
                            'basic_coeff' => '30 | 450 | 15',
                            'quantity_coeff' => [1.05],
                            'basic_price' => [3350],
                            'unit' => 'м<sup>3</sup>'
                        ]
                    ]
                ],
                'overhead_transport' => [
                    'title' => 'Накладные и транспортные расходы',
                    'options' => [
                        'overhead' => [
                            'title' => 'Накладные и прочие расходы (фиксаторы, саморезы, гвозди, подставки, вязальная проволока и прочее)',
                            'basic_coeff' => [0.1],
                            'quantity' => 1,
                            'unit' => 'шт'
                        ],
                        'transport' => [
                            'title' => 'Транспортные и логистические расходы',
                            'basic_coeff' => [2, 45, 0.02],
                            'basic_price' => [6000],
                            'quantity' => 1,
                            'unit' => 'шт'
                        ]
                    ]
                ],
                'additional_work' => [
                    'title' => 'Дополнительные работы и услуги',
                    'options' => [
                        'drainage_device' => [
                            'title' => 'Устройство дренажа по периметру фундамента с отводом в сторону на 10м',
                            'basic_price' => [1300],
                            'unit' => 'м.п.'
                        ],
                        'foundation_waterproofing' => [
                            'title' => 'Гидроизоляция подошвы и боковых стенок фундамента, технониколь, 1 слой',
                            'basic_price' => [310],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_insulation' => [
                            'title' => 'Утепление фундамента (по периметру), пеноплекс 50мм',
                            'basic_price' => [600],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'blind_area_device' => [
                            'title' => 'Устройство бетонной отмостки',
                            'basic_price' => [2000],
                            'unit' => 'м.п.'
                        ],
                        'sewer_wiring' => [
                            'title' => 'Разводка канализационных труб, d110мм',
                            'basic_price' => [1400],
                            'unit' => 'м.п.'
                        ],
                        'installation_mortgages' => [
                            'title' => 'Монтаж закладных под воду, электричество и канализацию',
                            'basic_price' => [2000],
                            'unit' => 'шт'
                        ],
                        'electricity_supply' => [
                            'title' => 'Аренда генератора (если нет электричества на участке)',
                            'basic_price' => [6000],
                            'basic_coeff' => [50000, 800],
                            'quantity' => 1,
                            'unit' => 'шт'
                        ],
                        'trailer_rental' => [
                            'title' => 'Аренда вагончика для проживания бригады',
                            'basic_price' => [7000],
                            'basic_coeff' => [2, 50, 2, 6000],
                            'quantity' => 1,
                            'unit' => 'шт'
                        ]
                    ]
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

    /** Отдает все данные по калькуляторам для отрисовки на фронте */
    public function getCalculatorData()
    {
        return $this->types;
    }

    /**
     * Отдает данные по дополнительным раходам
     * 
     * @param type - Тип калькулятора tape,plate и тд.
     */
    public function getAdditionalExpenses($type)
    {
        return $this->additional_expenses[$type];
    }
}
