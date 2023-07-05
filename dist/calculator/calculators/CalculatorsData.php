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
                'image' => [
                    'style' => ['position: relative; left: -3px; top: -10px;', 'position: relative; left: 7px; top: -4px;'],
                    'src' =>  [
                        'calculator/assets/images/plate/basic_01.png',
                        'calculator/assets/images/plate/basic_02.png'
                    ]
                ],
                'config' => [
                    // Размеры
                    'dimensions' => [
                        'title' => 'Размеры фундамента:',
                        'options' => [
                            [
                                'key' => 'length',
                                'title' => 'Длина',
                                'required' => true,
                                'placeholder' => 'Длина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/plate/param_01.png'
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
                                    'src' => 'calculator/assets/images/plate/param_02.png'
                                ]
                            ],
                            [
                                'key' => 'plate_height',
                                'title' => 'Высота плиты',
                                'default' => 0.3,
                                'values' => [0.15, 0.2, 0.25, 0.3, 0.35, 0.4, 0.5],
                                'unit' => 'м',
                                'image' => [
                                    'style' => 'left: 0; top: -96px; ',
                                    'src' => 'calculator/assets/images/plate/param_03.png'
                                ]
                            ],
                            [
                                'key' => 'plate_square',
                                'title' => 'Площадь фундамента',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'default' => 0,
                                'unit' => 'м2',
                                'image' => [
                                    'style' => 'left: 51px; top: -176px;',
                                    'src' => 'calculator/assets/images/plate/param_04.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_perimeter',
                                'title' => 'Периметр фундамента',
                                'description' => '(это расчетная длина ленты, если у вас свое значение, подставьте его)',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'image' => [
                                    'style' => 'left: 51px; top: -183px;',
                                    'src' => 'calculator/assets/images/plate/param_05.png'
                                ]
                            ],

                            [
                                'key' => 'cushion_thickness_1',
                                'title' => 'Толщина песчаной подушки',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'cushion_thickness_2',
                                'title' => 'Толщина щебёночной подушки',
                                'default' => 0,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9],
                                'unit' => 'м',
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
                                'key' => 'additionally_1',
                                'title' => 'Укладка геомембраны',
                                'description' => '(используется вместо подбетонного основания)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_01.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_2',
                                'title' => 'Подбетонное основание',
                                'description' => '(100мм, бетон в7,5)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_02.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_3',
                                'title' => 'Устройство дренажа',
                                'description' => '(по периметру фундамента)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_03.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_4',
                                'title' => 'Гидроизоляция подошвы плиты',
                                'description' => '(рулонная, Технониколь)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_04.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_5',
                                'title' => 'Утепление подошвы плиты',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_05.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_6',
                                'title' => 'Гидроизоляция цоколя плиты',
                                'description' => '(оклеечная, Технониколь)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_06.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_7',
                                'title' => 'Утепление цоколя плиты',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_07.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_8',
                                'title' => 'Устройство отмостки',
                                'description' => '(ширина 1м)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_08.png'
                                ]
                            ],

                            [
                                'key' => 'additionally_9',
                                'title' => 'Разводка канализации',
                                'description' => '(система труб d110мм, укладывается в подушку фундамента)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_09.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_10',
                                'title' => 'Обеспечение электричеством',
                                'description' => '(аренда генератора)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_10.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_11',
                                'title' => 'Аренда вагончика для проживания бригады',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/plate/extra_11.png'
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            'plate_grillage_low' => [
                'title' => 'Плита с нижним ростверком',
                'image' => [
                    'style' => ['', 'position: relative; left: 42px; top: -25px;'],
                    'src' =>  [
                        'calculator/assets/images/grillagelow/basic_01.png',
                        'calculator/assets/images/grillagelow/basic_02.png'
                    ]
                ],
                'config' => [
                    'dimensions' => [
                        'title' => 'Размеры фундамента:',
                        "options" => [
                            [
                                'key' => 'length',
                                'title' => 'Длина',
                                'required' => true,
                                'placeholder' => 'Длина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_01.png'
                                ]
                            ],
                            [
                                'key' => 'width',
                                'title' => 'Ширина',
                                'required' => true,
                                'placeholder' => 'Ширина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_02.png'
                                ]
                            ],
                            [
                                'key' => 'plate_thickness',
                                'title' => 'Толщина плиты',
                                'default' => 0.2,
                                'values' => [0.15, 0.2, 0.25, 0.3, 0.35, 0.4, 0.45, 0.5],
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_03.png'
                                ]
                            ],
                            [
                                'key' => 'plate_area',
                                'title' => 'Площадь плиты',
                                'default' => 0,
                                'unit' => 'м2',
                                'description' => '(это расчетная величина, если у вас свое значение, подставьте его)',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_04.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_perimeter',
                                'title' => 'Периметр фундамент',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'description' => '(это расчетная величина, если у вас свое значение, подставьте его)',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_05.png'
                                ]
                            ],
                            [
                                'key' => 'cushion_thickness_1',
                                'title' => 'Толщина песчаной подушки',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'cushion_thickness_2',
                                'title' => 'Толщина щебёночной подушки',
                                'default' => 0,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'grillage_length',
                                'title' => 'Общая длина ростверка',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'description' => '(рассчитывается под все несущие стены и перегородки, если у вас свое значение, подставьте его)',

                            ],
                            [
                                'key' => 'grillage_height',
                                'title' => 'Высота ростверка',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'grillage_width',
                                'title' => 'Ширина ростверка',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_10.png'
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
                    'additionally' => [
                        'title' => 'Размеры фундамента:',
                        "options" => [
                            [
                                'key' => 'additionally_1',
                                'title' => 'Укладка геомембраны',
                            ],
                            [
                                'key' => 'additionally_2',
                                'title' => 'Подбетонное основание',
                                'description' => '(под нижний ростверк)',
                            ],
                            [
                                'key' => 'additionally_3',
                                'title' => 'Устройство дренажа',
                                'description' => '(по периметру фундамента)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_03.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_4',
                                'title' => 'Гидроизоляция ростверка и плиты',
                                'description' => '(Технониколь)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_04.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_5',
                                'title' => 'Утепление боковых стенок',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_05.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_6',
                                'title' => 'Утепление подошвы',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_06.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_7',
                                'title' => 'Устройство отмостки',
                                'description' => '(ширина 1м)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_07.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_8',
                                'title' => 'Разводка канализации',
                                'description' => '(система труб d110мм, укладывается в подушку фундамента)',
                            ],
                            [
                                'key' => 'additionally_9',
                                'title' => 'Обеспечение электричеством',
                                'description' => '(аренда генератора)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_09.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_10',
                                'title' => 'Аренда вагончика для проживания бригады',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillagelow/extra_10.png'
                                ]
                            ],

                        ]
                    ]
                ]
            ],
            'plate_grillage_up' => [
                'title' => 'Плита с верхним ростверком',
                'image' => [
                    'style' => ['', 'position: relative; left: 42px; top: -25px;'],
                    'src' =>  [
                        'calculator/assets/images/grillageup/basic_01.png',
                        'calculator/assets/images/grillageup/basic_02.png'
                    ]
                ],
                'config' => [
                    'dimensions' => [
                        'title' => 'Размеры фундамента:',
                        "options" => [
                            [
                                'key' => 'length',
                                'title' => 'Длина',
                                'required' => true,
                                'placeholder' => 'Длина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_01.png'
                                ]
                            ],
                            [
                                'key' => 'width',
                                'title' => 'Ширина',
                                'required' => true,
                                'placeholder' => 'Ширина',
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_02.png'
                                ]
                            ],
                            [
                                'key' => 'plate_thickness',
                                'title' => 'Толщина плиты',
                                'default' => 0.25,
                                'values' => [0.15, 0.2, 0.25, 0.3, 0.35, 0.4, 0.45, 0.5],
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_03.png'
                                ]
                            ],
                            [
                                'key' => 'plate_area',
                                'title' => 'Площадь плиты',
                                'default' => 0,
                                'unit' => 'м2',
                                'description' => '(это расчетная величина, если у вас свое значение, подставьте его)',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_04.png'
                                ]
                            ],
                            [
                                'key' => 'foundation_perimeter',
                                'title' => 'Периметр фундамент',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'description' => '(это расчетная величина, если у вас свое значение, подставьте его)',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_05.png'
                                ]
                            ],
                            [
                                'key' => 'cushion_thickness_1',
                                'title' => 'Толщина песчаной подушки',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'cushion_thickness_2',
                                'title' => 'Толщина щебёночной подушки',
                                'default' => 0,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'grillage_length',
                                'title' => 'Общая длина ростверка',
                                'default' => 0,
                                'unit' => 'м.п.',
                                'description' => '(рассчитывается под все несущие стены и перегородки, если у вас свое значение, подставьте его)',

                            ],
                            [
                                'key' => 'grillage_height',
                                'title' => 'Высота ростверка',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                            ],
                            [
                                'key' => 'grillage_width',
                                'title' => 'Ширина ростверка',
                                'default' => 0.3,
                                'values' => [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
                                'unit' => 'м',
                                'image' => [
                                    'style' => ' left: 0; top: -33px; ',
                                    'src' => 'calculator/assets/images/grillagelow/param_10.png'
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
                    'additionally' => [
                        'title' => 'Размеры фундамента:',
                        "options" => [
                            [
                                'key' => 'additionally_1',
                                'title' => 'Укладка геомембраны',
                                'description' => '(используется вместо подбетонного основания)'
                            ],
                            [
                                'key' => 'additionally_2',
                                'title' => 'Подбетонное основание',
                                'description' => '(100мм, бетон в7,5)',
                            ],
                            [
                                'key' => 'additionally_3',
                                'title' => 'Устройство дренажа',
                                'description' => '(по периметру фундамента)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_03.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_4',
                                'title' => 'Гидроизоляция подошвы',
                                'description' => '(рулонная, Технониколь)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_04.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_5',
                                'title' => 'Утепление подошвы',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_05.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_6',
                                'title' => 'Гидроизоляция боковых стенок',
                                'description' => '(оклеечная, Технониколь)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_06.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_7',
                                'title' => 'Утепление боковых стенок',
                                'description' => '(Пеноплекс 50мм)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_07.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_8',
                                'title' => 'Устройство отмостки',
                                'description' => '(ширина 1м)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_08.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_9',
                                'title' => 'Разводка канализации',
                                'description' => '(система труб d110мм, укладывается в подушку фундамента)',
                            ],
                            [
                                'key' => 'additionally_10',
                                'title' => 'Обеспечение электричеством',
                                'description' => '(аренда генератора)',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_10.png'
                                ]
                            ],
                            [
                                'key' => 'additionally_11',
                                'title' => 'Аренда вагончика для проживания бригады',
                                'image' => [
                                    'style' => 'left: 33px; top: -131px;z-index:1',
                                    'src' => 'calculator/assets/images/grillageup/extra_11.png'
                                ]
                            ],

                        ]
                    ]
                ]
            ],
            'usp' => [
                'title' => 'УШП',
                'image' => [
                    'style' => ['', 'position: relative; left: 42px; top: -25px;'],
                    'src' =>  [
                        'calculator/assets/images/usp/basic_01.png',
                        'calculator/assets/images/usp/basic_02.png'
                    ]
                ],
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
            ],
            'plate' => [
                'works' => [
                    'title' => 'Работы',
                    'options' => [
                        'breakdown_axes' => [
                            'title' => 'Планировка, разбивка осей',
                            'quantity' => 1,
                            'basic_price' => [5000],
                            'unit' => 'шт'
                        ],
                        'excavation' => [
                            'title' => 'Земляные работы (рытье траншеи)',
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile_laying' => [
                            'title' => 'Укладка геотекстиля',
                            'quantity_coeff' => [0.3],
                            'basic_price' => [20],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'gravel_pad_device' => [
                            'title' => 'Устройство песчано-гравийной подушки',
                            'quantity_coeff' => [1.3, 1.3],
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'concrete_works' => [
                            'title' => 'Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона)',
                            'basic_coeff' => 'УСЛОВИЕ: <10 | >=10 ; <15 | -',
                            'quantity_coeff' => [1.05],
                            'basic_price' => [3500],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'installation_mortgages' => [
                            'title' => 'Монтаж закладных под воду, электричество и канализацию',
                            'basic_price' => [2000],
                            'unit' => 'шт'
                        ]
                    ]
                ],
                'materials' => [
                    'title' => 'Материалы',
                    'options' => [
                        'sand_delivery' => [
                            'title' => 'Песок с доставкой',
                            'basic_price' => [550],
                            'quantity_coeff' => [1.3],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'rock_20_40' => [
                            'title' => 'Щебень гр.фр.20-40 с доставкой',
                            'quantity_coeff' => [1, 3],
                            'basic_price' => [1300],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'geotextile' => [
                            'title' => 'Геотекстиль',
                            'quantity_coeff' => [0.3, 1.2],
                            'basic_price' => [35],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'technical_film' => [
                            'title' => 'Пленка техническая',
                            'quantity_coeff' =>  [1.2],
                            'basic_price' => [15],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'formwork' => [
                            'title' => 'Опалубка, пиломатериалы',
                            'quantity_coeff' => [1.2],
                            'basic_price' => [8500],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'armature_a500С_d12mm' => [
                            'title' => 'Арматура А500С d12мм',
                            'quantity_coeff' => [0.888, 1.2, 1000],
                            'basic_price' => [41200],
                            'unit' => 'т'
                        ],
                        'armature_a500С_d8mm' => [
                            'title' => 'Арматура А500С d8мм',
                            'quantity_coeff' => [0.495, 1.2, 1000],
                            'basic_price' => [45900],
                            'unit' => 'т'
                        ],
                        'commercial_concrete' => [
                            'title' => 'Бетон товарный В22,5М300П3 с доставкой',
                            'basic_coeff' => [30, 450, 15],
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
                            'basic_coeff' => [0.05],
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
                        'geomembrane_installation' => [
                            'title' => 'Укладка геомембраны Planter',
                            'basic_price' => [120],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м.п.'
                        ],
                        'concrete_foundation_device' => [
                            'title' => 'Устройство подбетонного основания, 100мм',
                            'basic_price' => [2700],
                            'basic_coeff' => [15, 0.1, 200],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м.п.'
                        ],
                        'drainage_device' => [
                            'title' => 'Устройство дренажа по периметру фундамента с отводом в сторону на 10м',
                            'basic_price' => [1100],
                            'unit' => 'м.п.'
                        ],
                        'foundation_waterproofing' => [
                            'title' => 'Гидроизоляция подошвы фундамента, технониколь, 1 слой',
                            'basic_price' => [310],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_insulation' => [
                            'title' => 'Утепление подошвы фундамента, пеноплекс 50мм',
                            'basic_price' => [350],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_waterproofing_plinth' => [
                            'title' => 'Гидроизоляция цоколя фундамента (цоколя), технониколь, 1 слой',
                            'basic_price' => [360],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'side_wall_insulation' => [
                            'title' => 'Утепление боковых стенок фундамента (цоколя), пеноплекс 50мм',
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
                            'basic_price' => [1200],
                            'unit' => 'м.п.'
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
            ],
            'plate_grillage_low' => [
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
                            'title' => 'Земляные работы (разработка котлована механизированным способом, ручная доработка)',
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile_laying' => [
                            'title' => 'Укладка геотекстиля',
                            'basic_price' => [20],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'gravel_pad_device' => [
                            'title' => 'Устройство песчано-гравийной подушки с послойным уплотнением виброплитой',
                            'quantity_coeff' => [1.3, 1.3],
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'concrete_works' => [
                            'title' => 'Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона)',
                            'quantity_coeff' => [1.05],
                            'basic_price' => [4500],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'installation_mortgages' => [
                            'title' => 'Монтаж закладных под воду, электричество и канализацию',
                            'basic_price' => [2000],
                            'unit' => 'шт'
                        ]
                    ]
                ],
                'materials' => [
                    'title' => 'Материалы',
                    'options' => [
                        'sand_delivery' => [
                            'title' => 'Песок с доставкой',
                            'basic_price' => [550],
                            'quantity_coeff' => [1.3],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'rock' => [
                            'title' => 'Щебень гр.фр.20-40 с доставкой',
                            'quantity_coeff' => [1.3],
                            'basic_price' => [1300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile' => [
                            'title' => 'Геотекстиль',
                            'quantity_coeff' => [1.3],
                            'basic_price' => [35],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'technical_film' => [
                            'title' => 'Пленка техническая',
                            'quantity_coeff' =>  [2, 1.2],
                            'basic_price' => [15],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'formwork' => [
                            'title' => 'Опалубка, пиломатериалы',
                            'quantity_coeff' => [1.2],
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
                            'quantity_coeff' => [0.495, 1.2, 1000],
                            'basic_price' => [44900],
                            'unit' => 'т'
                        ],
                        'commercial_concrete' => [
                            'title' => 'Бетон товарный В22,5М300П3 с доставкой',
                            'basic_coeff' => [30, 450, 15],
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
                            'basic_coeff' => [0.05],
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
                        'geomembrane_installation' => [
                            'title' => 'Укладка геомембраны Planter',
                            'basic_price' => [120],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'concrete_foundation_device' => [
                            'title' => 'Устройство подбетонного основания, 100мм',
                            'basic_price' => [2700],
                            'basic_coeff' => [15, 0.1, 200],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'drainage_device' => [
                            'title' => 'Устройство дренажа по периметру фундамента с отводом в сторону на 10м',
                            'basic_price' => [1100],
                            'unit' => 'м.п.'
                        ],
                        'foundation_insulation' => [
                            'title' => 'Утепление подошвы плиты и ростверка, внутренних стенок фундамента (отсечка «мостов» холода), пеноплекс 50мм',
                            'basic_price' => [600],
                            'quantity_coeff' => [2],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_waterproofing_plinth' => [
                            'title' => 'Гидроизоляция нижнего ростверка и плиты фундамента, технониколь, 1 слой',
                            'basic_price' => [360],
                            'quantity_coeff' => [2],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'side_wall_insulation' => [
                            'title' => 'Утепление боковых стенок фундамента (цоколя), пеноплекс 50мм',
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
                            'basic_price' => [1200],
                            'unit' => 'м.п.'
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
            ],
            'plate_grillage_up' => [
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
                            'title' => 'Земляные работы (разработка котлована механизированным способом, ручная доработка)',
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile_laying' => [
                            'title' => 'Укладка геотекстиля',
                            'basic_price' => [20],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'gravel_pad_device' => [
                            'title' => 'Устройство песчано-гравийной подушки с послойным уплотнением виброплитой',
                            'quantity_coeff' => [1.3, 1.3],
                            'basic_price' => [300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'concrete_works' => [
                            'title' => 'Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона)',
                            'quantity_coeff' => [1.05],
                            'basic_price' => [4500],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'installation_mortgages' => [
                            'title' => 'Монтаж закладных под воду, электричество и канализацию',
                            'basic_price' => [2000],
                            'unit' => 'шт'
                        ]
                    ]
                ],
                'materials' => [
                    'title' => 'Материалы',
                    'options' => [
                        'sand_delivery' => [
                            'title' => 'Песок с доставкой',
                            'basic_price' => [550],
                            'quantity_coeff' => [1.3],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'rock' => [
                            'title' => 'Щебень гр.фр.20-40 с доставкой',
                            'quantity_coeff' => [1.3],
                            'basic_price' => [1300],
                            'unit' => 'м<sup>3</sup>'
                        ],
                        'geotextile' => [
                            'title' => 'Геотекстиль',
                            'quantity_coeff' => [1.3],
                            'basic_price' => [35],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'technical_film' => [
                            'title' => 'Пленка техническая',
                            'quantity_coeff' =>  [2, 1.2],
                            'basic_price' => [15],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'formwork' => [
                            'title' => 'Опалубка, пиломатериалы',
                            'quantity_coeff' => [1.2],
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
                            'quantity_coeff' => [0.495, 1.2, 1000],
                            'basic_price' => [44900],
                            'unit' => 'т'
                        ],
                        'commercial_concrete' => [
                            'title' => 'Бетон товарный В22,5М300П3 с доставкой',
                            'basic_coeff' => [30, 450, 15],
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
                            'basic_coeff' => [0.05],
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
                        'geomembrane_installation' => [
                            'title' => 'Укладка геомембраны Planter',
                            'basic_price' => [120],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'concrete_foundation_device' => [
                            'title' => 'Устройство подбетонного основания, 100мм',
                            'basic_price' => [2700],
                            'basic_coeff' => [15, 0.1, 200],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'drainage_device' => [
                            'title' => 'Устройство дренажа по периметру фундамента с отводом в сторону на 10м',
                            'basic_price' => [1100],
                            'unit' => 'м.п.'
                        ],
                        'foundation_waterproofing_insulation' => [
                            'title' => 'Гидроизоляция подошвы фундамента, технониколь, 1 слой',
                            'basic_price' => [310],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_insulation' => [
                            'title' => 'Утепление подошвы фундамента, пеноплекс 50мм',
                            'basic_price' => [600],
                            'quantity_coeff' => [0.1],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'foundation_waterproofing_plinth' => [
                            'title' => 'Гидроизоляция боковых стенок фундамента (цоколя), технониколь, 1 слой',
                            'basic_price' => [360],
                            'unit' => 'м<sup>2</sup>'
                        ],
                        'side_wall_insulation' => [
                            'title' => 'Утепление боковых стенок фундамента (цоколя), пеноплекс 50мм',
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
                            'basic_price' => [1200],
                            'unit' => 'м.п.'
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
            ],
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
