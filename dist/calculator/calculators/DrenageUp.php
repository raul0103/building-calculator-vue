<?php

include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/helpers.php';
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/calculators/CalculatorsData.php';

/**
 * Расчет плитного фундамента
 */
class DrenageUp
{
    public $form_values; // Данные из формы
    public $main_data; // Основные данные по которым будут проводиться различные расчеты

    public function __construct($form_values)
    {
        $this->form_values = $form_values;
    }

    public function result()
    {
        /**
         * Расчитывает основные данные.
         * На основе них будет расчет доп.работ
         */
        $this->calculateMain();

        /**
         * Данные для сметы
         * Подтягиваем конфиг по дополнительным затратам
         */
        $calculators_data = new CalculatorsData();
        $additional_expenses = $calculators_data->getAdditionalExpenses('plate_grillage_up');

        /** Работы */
        $additional_expenses['works'] = $this->calculateWorks($additional_expenses['works']);
        /** Материалы */
        $additional_expenses['materials'] = $this->calculateMaterials($additional_expenses['materials']);
        /** Накладные и транспортные расходы */
        $additional_expenses['overhead_transport'] = $this->calculateOverheadTransport($additional_expenses['overhead_transport'], $additional_expenses['materials']['total_cost']);
        /** Дополнительные работы и услуги */
        $additional_expenses['additional_work'] = $this->calculateAdditionalWork($additional_expenses['additional_work'], $additional_expenses['works']['total_cost'], $additional_expenses['materials']['total_cost'], $additional_expenses['overhead_transport']['total_cost']);

        /** Подсчитываем общую сумму за все работы */
        $total =  numberFormat($additional_expenses['works']['total_cost'] + $additional_expenses['materials']['total_cost'] +  $additional_expenses['overhead_transport']['total_cost'] + $additional_expenses['additional_work']['total_cost']);

        /** форматируем все столбцы в нужный формат */
        foreach ($additional_expenses as $key => $additional_expense) {
            // Если сумма равна 0 тогда удалить и не выводить данные
            if ($additional_expenses[$key]['total_cost'] == 0) {
                unset($additional_expenses[$key]);
            } else {
                $additional_expenses[$key]['total_cost'] = numberFormat($additional_expenses[$key]['total_cost']);

                foreach ($additional_expenses[$key]['options'] as $option_key => $option_value) {
                    if ($additional_expenses[$key]['options'][$option_key]['cost'] == 0) {
                        unset($additional_expenses[$key]['options'][$option_key]);
                    } else {
                        $additional_expenses[$key]['options'][$option_key]['quantity'] = numberFormat($additional_expenses[$key]['options'][$option_key]['quantity']);
                        $additional_expenses[$key]['options'][$option_key]['price'] = numberFormat($additional_expenses[$key]['options'][$option_key]['price']);
                        $additional_expenses[$key]['options'][$option_key]['cost'] = numberFormat($additional_expenses[$key]['options'][$option_key]['cost']);
                    }
                }
            }
        }

        return [
            /** Итого */
            'total' => $total,
            /** Расчеты по дополнительным затратам */
            'additional_expenses' => $additional_expenses
        ];
    }

    protected function calculateMain()
    {
        /* площадь плиты фундамента */
        if (empty($this->form_values['plate_area']) || $this->form_values['plate_area'] == 0) {
            $this->form_values['plate_area'] = $this->form_values['length'] * $this->form_values['width'];
        }

        /* периметр фундамента */
        if (empty($this->form_values['foundation_perimeter']) || $this->form_values['foundation_perimeter'] == 0) {
            if ($this->form_values['length'] > 0 && $this->form_values['width'] > 0) {
                $this->form_values['foundation_perimeter'] = ($this->form_values['length'] + $this->form_values['width']) * 2;
            } else {
                $this->form_values['foundation_perimeter'] = 0;
            }
        }

        /* Общая длина ростверка */
        if (empty($this->form_values['grillage_length']) || $this->form_values['grillage_length'] == 0) {
            if ($this->form_values['length'] > 0 && $this->form_values['width'] > 0) {
                $this->form_values['grillage_length'] = roundUp($this->form_values['length'] * $this->form_values['width'] * 0.75, 1);
            } else {
                $this->form_values['grillage_length'] = 0;
            }
        }

        /*** Внутренние данные ***/
        /* объём бетона на плиту */
        $this->main_data['volume'] = $this->form_values['plate_thickness'] * $this->form_values['plate_area'];

        /* Площадь боковых стенок фундамента */
        $this->main_data['side_square'] = $this->form_values['foundation_perimeter'] * ($this->form_values['plate_thickness'] + $this->form_values['grillage_height']);

        /* ячейка арматуры */
        $this->main_data['a_500_cell'] = 0.2;

        /* арматура */
        $this->main_data['a_500'] = roundUp(($this->form_values['length'] / $this->main_data['a_500_cell']) + 1, 0) * $this->form_values['width'] * 2 + roundUp(($this->form_values['width'] / $this->main_data['a_500_cell']) + 1, 0) * $this->form_values['length'] * 2;

        /* арматура на лягушки */
        $this->main_data['a_500_frogs'] = $this->form_values['plate_area'] * 1.5 * (0.3 + $this->form_values['plate_thickness'] + $this->main_data['a_500_cell'] + $this->form_values['plate_thickness'] + 0.3);

        /* подставки под арматуру (стульчики) */
        $this->main_data['a_500_supports'] = roundUp($this->form_values['plate_area'] * 5, 0);

        /* вязальная проволока */
        $this->main_data['low_wire'] = roundUp(($this->form_values['plate_area'] / 10) * 2, 0);

        /* доска, толщина */
        $this->main_data['desk_tickness'] = 0.04;

        /* опалубка (щиты) */
        $this->main_data['desk_shield'] = $this->main_data['desk_tickness'] * ($this->form_values['foundation_perimeter'] * $this->form_values['plate_thickness'] + $this->form_values['grillage_length'] * 2 * $this->form_values['grillage_height']);

        /* опалубка (укрепление) */
        $this->main_data['desk_holder'] = ($this->form_values['foundation_perimeter'] * 3 + $this->form_values['grillage_length'] * 2 * ($this->form_values['plate_thickness'] + 2)) * 0.025 * 0.1;

        /* площадь котлована */
        $this->main_data['pit_square'] = ($this->form_values['plate_area'] + $this->form_values['foundation_perimeter']) * 1;

        /* глубина котлована */
        $this->main_data['pit_depth'] = $this->form_values['cushion_thickness_1'] + $this->form_values['cushion_thickness_2'];

        /* объём котлована */
        $this->main_data['pit_volume'] = $this->main_data['pit_square'] * $this->main_data['pit_depth'];

        /* засыпка внутреннего пространства между ростверком */
        $this->main_data['interior_space'] = ($this->form_values['plate_area'] - $this->form_values['grillage_length'] * $this->form_values['grillage_width']) * $this->form_values['grillage_height'];

        /* количество хлыстов в ростверке */
        switch (true) {
            case ($this->form_values['grillage_height'] <= 0.5):
                $n1 = 2;
                break;
            case ($this->form_values['grillage_height'] > 0.5 && $this->form_values['grillage_height'] <= 0.9):
                $n1 = 3;
                break;
            case ($this->form_values['grillage_height'] > 0.9 && $this->form_values['grillage_height'] <= 1.3):
                $n1 = 4;
                break;
            case ($this->form_values['grillage_height'] > 1.3 && $this->form_values['grillage_height'] <= 1.6):
                $n1 = 5;
                break;
            default:
                $n1 = 6;
        }
        switch (true) {
            case ($this->form_values['grillage_width'] <= 0.4):
                $n2 = 2;
                break;
            case ($this->form_values['grillage_width'] > 0.4 && $this->form_values['grillage_width'] <= 0.7):
                $n2 = 3;
                break;
            default:
                $n2 = 4;
        }
        $this->main_data['grillage_whips'] = $n1 * $n2;

        /* арматура (ростверк) */
        $this->main_data['grillage_a500'] = $this->form_values['grillage_length'] * $this->main_data['grillage_whips'];

        /* шаг связей */
        $this->main_data['grillage_connector_step'] = 0.4;

        /* арматура на связи */
        $this->main_data['grillage_connector_a500'] = roundUp($this->form_values['grillage_length'] / $this->main_data['grillage_connector_step'], 0) * ($this->form_values['grillage_height'] * 2 + $this->form_values['grillage_width'] * 3);

        /* объём ростверка */
        $this->main_data['grillage_volume'] = $this->form_values['grillage_length'] * $this->form_values['grillage_height'] * $this->form_values['grillage_width'];

        /* разводка канализации под фундаментом */
        $this->main_data['interconnection'] = $this->form_values['length'] + $this->form_values['width'];

        /* закладные под воду, эл-во, канализацию */
        $this->main_data['embedded_fittings'] = 3;

        /* дренаж, длина */
        $this->main_data['drainage_length'] = $this->form_values['foundation_perimeter'] + 15;
    }

    protected function calculateWorks($works)
    {
        $works_options = $works['options'];

        /* Планировка, разбивка осей */
        $works_options['breakdown_axes']['price'] = $works_options['breakdown_axes']['basic_price'][0];
        $works_options['breakdown_axes']['cost'] = $works_options['breakdown_axes']['quantity'] * $works_options['breakdown_axes']['price'];

        /* Земляные работы (разработка котлована механизированным способом, ручная доработка) */
        $works_options['excavation']['quantity'] = roundUp($this->main_data['pit_volume'], -1);
        $works_options['excavation']['price'] = $works_options['excavation']['basic_price'][0];
        $works_options['excavation']['cost'] = $works_options['excavation']['quantity'] * $works_options['excavation']['price'];

        /* Укладка геотекстиля */
        $works_options['geotextile_laying']['quantity'] = roundUp($this->main_data['pit_square'], -1);
        $works_options['geotextile_laying']['price'] = $works_options['geotextile_laying']['basic_price'][0];
        $works_options['geotextile_laying']['cost'] = $works_options['geotextile_laying']['quantity'] * $works_options['geotextile_laying']['price'];

        /* Устройство песчано-гравийной подушки */
        $works_options['gravel_pad_device']['quantity'] = roundUp(($this->main_data['pit_square'] * $this->form_values['cushion_thickness_1'] + $this->main_data['interior_space']) * $works_options['gravel_pad_device']['quantity_coeff'][0], -1) + roundUp($this->main_data['pit_square'] * $this->form_values['cushion_thickness_2'] * $works_options['gravel_pad_device']['quantity_coeff'][1], -1);
        $works_options['gravel_pad_device']['price'] = $works_options['gravel_pad_device']['basic_price'][0];
        $works_options['gravel_pad_device']['cost'] = $works_options['gravel_pad_device']['quantity'] * $works_options['gravel_pad_device']['price'];

        /* Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона) */
        $works_options['concrete_works']['quantity'] = roundUp(($this->main_data['volume'] + $this->main_data['grillage_volume']) * $works_options['concrete_works']['quantity_coeff'][0], 0);
        $works_options['concrete_works']['price'] = $works_options['concrete_works']['basic_price'][0];
        $works_options['concrete_works']['cost'] = $works_options['concrete_works']['quantity'] * $works_options['concrete_works']['price'];

        /* Монтаж закладных под воду, электричество и канализацию */
        $works_options['installation_mortgages']['quantity'] = $this->main_data['embedded_fittings'];
        $works_options['installation_mortgages']['price'] = $works_options['installation_mortgages']['basic_price'][0];
        $works_options['installation_mortgages']['cost'] = $works_options['installation_mortgages']['quantity'] * $works_options['installation_mortgages']['price'];

        /* Итого по работам */
        $works['total_cost'] = countParamsTotalCost($works_options);

        $works['options'] = $works_options;

        return $works;
    }

    protected function calculateMaterials($materials)
    {
        $materials_options = $materials['options'];

        /* Песок с доставкой */
        $materials_options['sand_delivery']['quantity'] = roundUp(($this->main_data['pit_square'] * $this->form_values['cushion_thickness_1'] + $this->main_data['interior_space']) * $materials_options['sand_delivery']['quantity_coeff'][0], -1);
        $materials_options['sand_delivery']['price'] = $materials_options['sand_delivery']['basic_price'][0];
        $materials_options['sand_delivery']['cost'] = $materials_options['sand_delivery']['quantity'] * $materials_options['sand_delivery']['price'];

        /* Щебень гр.фр.20-40 с доставкой */
        $materials_options['rock']['quantity'] = roundUp($this->main_data['pit_square'] * $this->form_values['cushion_thickness_2'] * $materials_options['rock']['quantity_coeff'][0], -1);
        $materials_options['rock']['price'] = $materials_options['rock']['basic_price'][0];
        $materials_options['rock']['cost'] = $materials_options['rock']['quantity'] * $materials_options['rock']['price'];

        /* Геотекстиль */
        $materials_options['geotextile']['quantity'] = roundUp($this->main_data['pit_square'] * $materials_options['geotextile']['quantity_coeff'][0], -1);
        $materials_options['geotextile']['price'] = $materials_options['geotextile']['basic_price'][0];
        $materials_options['geotextile']['cost'] = $materials_options['geotextile']['quantity'] * $materials_options['geotextile']['price'];

        /* Пленка техническая */
        $materials_options['technical_film']['quantity'] = roundUp(($this->form_values['plate_area'] + $this->main_data['side_square'] + $this->form_values['grillage_length'] * $materials_options['technical_film']['quantity_coeff'][0]) * $materials_options['technical_film']['quantity_coeff'][1], -1);
        $materials_options['technical_film']['price'] = $materials_options['technical_film']['basic_price'][0];
        $materials_options['technical_film']['cost'] = $materials_options['technical_film']['quantity'] * $materials_options['technical_film']['price'];

        /* Опалубка, пиломатериалы */
        $materials_options['formwork']['quantity'] = roundUp($this->main_data['desk_shield'] + $this->main_data['desk_holder'] * $materials_options['formwork']['quantity_coeff'][0], 1);
        $materials_options['formwork']['price'] = $materials_options['formwork']['basic_price'][0];
        $materials_options['formwork']['cost'] = $materials_options['formwork']['quantity'] * $materials_options['formwork']['price'];

        /* Арматура А500С d12мм */
        $materials_options['armature_a500С_d12mm']['quantity'] = roundUp(($this->main_data['a_500'] + $this->main_data['grillage_a500']) * $materials_options['armature_a500С_d12mm']['quantity_coeff'][0] * $materials_options['armature_a500С_d12mm']['quantity_coeff'][1] / $materials_options['armature_a500С_d12mm']['quantity_coeff'][2], 1);
        $materials_options['armature_a500С_d12mm']['price'] = $materials_options['armature_a500С_d12mm']['basic_price'][0];
        $materials_options['armature_a500С_d12mm']['cost'] = $materials_options['armature_a500С_d12mm']['quantity'] * $materials_options['armature_a500С_d12mm']['price'];

        /* Арматура А500С d8м */
        $materials_options['armature_a500С_d8mm']['quantity'] = roundUp(($this->main_data['a_500_frogs'] + $this->main_data['grillage_connector_a500']) * $materials_options['armature_a500С_d8mm']['quantity_coeff'][0] * $materials_options['armature_a500С_d8mm']['quantity_coeff'][1] / $materials_options['armature_a500С_d8mm']['quantity_coeff'][2], 1);
        $materials_options['armature_a500С_d8mm']['price'] = $materials_options['armature_a500С_d8mm']['basic_price'][0];
        $materials_options['armature_a500С_d8mm']['cost'] = $materials_options['armature_a500С_d8mm']['quantity'] * $materials_options['armature_a500С_d8mm']['price'];

        /* Бетон товарный В22,5М300П3 с доставкой */
        $materials_options['commercial_concrete']['quantity'] = roundUp(($this->main_data['volume'] + $this->main_data['grillage_volume']) * $materials_options['commercial_concrete']['quantity_coeff'][0], 0);
        $materials_options['commercial_concrete']['price'] = $materials_options['commercial_concrete']['basic_price'][0] + (($this->form_values['distance_from_cad'] <= $materials_options['commercial_concrete']['basic_coeff'][0]) ? $materials_options['commercial_concrete']['basic_coeff'][1] : $this->form_values['distance_from_cad'] * $materials_options['commercial_concrete']['basic_coeff'][2]);
        $materials_options['commercial_concrete']['cost'] = $materials_options['commercial_concrete']['quantity'] * $materials_options['commercial_concrete']['price'];

        /* Итого по материалам */
        $materials['total_cost'] = countParamsTotalCost($materials_options);

        $materials['options'] = $materials_options;

        return $materials;
    }

    protected function calculateOverheadTransport($overhead_transport, $materials_total_cost)
    {
        $overhead_transport_options = $overhead_transport['options'];

        /* Накладные и прочие расходы (фиксаторы, саморезы, гвозди, подставки, вязальная проволока и прочее) */
        $overhead_transport_options['overhead']['quantity'] = 1;
        $overhead_transport_options['overhead']['price'] = roundUp($materials_total_cost * $overhead_transport_options['overhead']['basic_coeff'][0], -2);
        $overhead_transport_options['overhead']['cost'] = $overhead_transport_options['overhead']['quantity'] * $overhead_transport_options['overhead']['price'];

        /* Транспортные и логистические расходы */
        $overhead_transport_options['transport']['quantity'] = 1;
        $overhead_transport_options['transport']['price'] = roundUp($overhead_transport_options['transport']['basic_price'][0] + $this->form_values['distance_from_cad'] * $overhead_transport_options['transport']['basic_coeff'][0] * $overhead_transport_options['transport']['basic_coeff'][1] + $materials_total_cost * $overhead_transport_options['transport']['basic_coeff'][2], -2);
        $overhead_transport_options['transport']['cost'] = $overhead_transport_options['transport']['quantity'] * $overhead_transport_options['transport']['price'];

        /* Итого по расходам */
        $overhead_transport['total_cost'] = countParamsTotalCost($overhead_transport_options);

        $overhead_transport['options'] = $overhead_transport_options;

        return $overhead_transport;
    }

    protected function calculateAdditionalWork($additional_work, $works_total_cost, $materials_total_cost, $overhead_transport_total_cost)
    {
        $additional_work_options = $additional_work['options'];

        /* Укладка геомембраны Planter */
        $additional_work_options['geomembrane_installation']['quantity'] = $this->form_values['plate_area'] + $this->form_values['foundation_perimeter'] * $additional_work_options['geomembrane_installation']['quantity_coeff'][0];
        $additional_work_options['geomembrane_installation']['price'] = $additional_work_options['geomembrane_installation']['basic_price'][0];
        $additional_work_options['geomembrane_installation']['cost'] = ($this->form_values['additionally_1'] == 'true') ? $additional_work_options['geomembrane_installation']['quantity'] * $additional_work_options['geomembrane_installation']['price'] : 0;

        /* Укладка подбетонного основания */
        $additional_work_options['concrete_foundation_device']['quantity'] = roundUp($this->form_values['plate_area'] + $this->form_values['grillage_length'] * $additional_work_options['concrete_foundation_device']['quantity_coeff'][0], 0);
        $additional_work_options['concrete_foundation_device']['price'] = ($additional_work_options['concrete_foundation_device']['basic_price'][0] + $this->form_values['distance_from_cad'] * $additional_work_options['concrete_foundation_device']['basic_coeff'][0]) * $additional_work_options['concrete_foundation_device']['basic_coeff'][1] + $additional_work_options['concrete_foundation_device']['basic_coeff'][2];
        $additional_work_options['concrete_foundation_device']['cost'] = ($this->form_values['additionally_2'] == 'true') ? $additional_work_options['concrete_foundation_device']['quantity'] * $additional_work_options['concrete_foundation_device']['price'] : 0;

        /* Устройство дренажа по периметру фундамента с отводом в сторону на 10м */
        $additional_work_options['drainage_device']['quantity'] = roundUp($this->main_data['drainage_length'], 0);
        $additional_work_options['drainage_device']['price'] = $additional_work_options['drainage_device']['basic_price'][0];
        $additional_work_options['drainage_device']['cost'] = ($this->form_values['additionally_3'] == 'true') ? $additional_work_options['drainage_device']['quantity'] * $additional_work_options['drainage_device']['price'] : 0;

        /* Гидроизоляция подошвы фундамента, технониколь, 1 слой */
        $additional_work_options['foundation_waterproofing_insulation']['quantity'] = roundUp($this->form_values['plate_area'] + $this->form_values['foundation_perimeter'] * $additional_work_options['foundation_waterproofing_insulation']['quantity_coeff'][0], 0);
        $additional_work_options['foundation_waterproofing_insulation']['price'] = $additional_work_options['foundation_waterproofing_insulation']['basic_price'][0];
        $additional_work_options['foundation_waterproofing_insulation']['cost'] = ($this->form_values['additionally_4'] == 'true') ? $additional_work_options['foundation_waterproofing_insulation']['quantity'] * $additional_work_options['foundation_waterproofing_insulation']['price'] : 0;

        /* Утепление подошвы фундамента, пеноплекс 50мм */
        $additional_work_options['foundation_insulation']['quantity'] = roundUp($this->form_values['plate_area'] + $this->form_values['foundation_perimeter'] * $additional_work_options['foundation_insulation']['quantity_coeff'][0], 0);
        $additional_work_options['foundation_insulation']['price'] = $additional_work_options['foundation_insulation']['basic_price'][0];
        $additional_work_options['foundation_insulation']['cost'] = ($this->form_values['additionally_5'] == 'true') ? $additional_work_options['foundation_insulation']['quantity'] * $additional_work_options['foundation_insulation']['price'] : 0;

        /* Гидроизоляция боковых стенок фундамента, технониколь, 1 слой */
        $additional_work_options['foundation_waterproofing_plinth']['quantity'] = roundUp($this->form_values['foundation_perimeter'] * ($this->main_data['plate_thickness'] + $this->main_data['grillage_height']), 0);
        $additional_work_options['foundation_waterproofing_plinth']['price'] = $additional_work_options['foundation_waterproofing_plinth']['basic_price'][0];
        $additional_work_options['foundation_waterproofing_plinth']['cost'] = ($this->form_values['additionally_6'] == 'true') ? $additional_work_options['foundation_waterproofing_plinth']['quantity'] * $additional_work_options['foundation_waterproofing_plinth']['price'] : 0;

        /* Утепление боковых стенок фундамента, пеноплекс 50мм */
        $additional_work_options['side_wall_insulation']['quantity'] = roundUp($this->form_values['foundation_perimeter'] * ($this->main_data['plate_thickness'] + $this->main_data['grillage_height']), 0);
        $additional_work_options['side_wall_insulation']['price'] = $additional_work_options['side_wall_insulation']['basic_price'][0];
        $additional_work_options['side_wall_insulation']['cost'] = ($this->form_values['additionally_7'] == 'true') ? $additional_work_options['side_wall_insulation']['quantity'] * $additional_work_options['side_wall_insulation']['price'] : 0;

        /* Устройство бетонной отмостки */
        $additional_work_options['blind_area_device']['quantity'] = roundUp($this->form_values['foundation_perimeter'], 0);
        $additional_work_options['blind_area_device']['price'] = $additional_work_options['blind_area_device']['basic_price'][0];
        $additional_work_options['blind_area_device']['cost'] = ($this->form_values['additionally_8'] == 'true') ? $additional_work_options['blind_area_device']['quantity'] * $additional_work_options['blind_area_device']['price'] : 0;

        /* Разводка канализационных труб, d110мм */
        $additional_work_options['sewer_wiring']['quantity'] = $this->main_data['interconnection'];
        $additional_work_options['sewer_wiring']['price'] = $additional_work_options['sewer_wiring']['basic_price'][0];
        $additional_work_options['sewer_wiring']['cost'] = ($this->form_values['additionally_9'] == 'true') ? $additional_work_options['sewer_wiring']['quantity'] * $additional_work_options['sewer_wiring']['price'] : 0;

        /* Аренда генератора (если нет электричества на участке) */
        $additional_work_options['electricity_supply']['quantity'] = 1;
        $additional_work_options['electricity_supply']['price'] = roundUp($additional_work_options['electricity_supply']['basic_price'][0] + (
            ($works_total_cost + $materials_total_cost + $overhead_transport_total_cost +
                ($additional_work_options['geomembrane_installation']['cost'] + $additional_work_options['concrete_foundation_device']['cost'] + $additional_work_options['drainage_device']['cost'] + $additional_work_options['foundation_waterproofing_insulation']['cost'] + $additional_work_options['foundation_insulation']['cost'] + $additional_work_options['foundation_waterproofing_plinth']['cost'] + $additional_work_options['side_wall_insulation']['cost'] + $additional_work_options['blind_area_device']['cost'] + $additional_work_options['sewer_wiring']['cost'])
            ) / $additional_work_options['electricity_supply']['basic_coeff'][0]) * $additional_work_options['electricity_supply']['basic_coeff'][1], -2);
        $additional_work_options['electricity_supply']['cost'] = ($this->form_values['additionally_10'] == 'true') ? $additional_work_options['electricity_supply']['quantity'] * $additional_work_options['electricity_supply']['price'] : 0;

        /* Аренда вагончика для проживания бригады */
        $additional_work_options['trailer_rental']['quantity'] = 1;
        $additional_work_options['trailer_rental']['price'] = ($additional_work_options['trailer_rental']['basic_price'][0] + $this->form_values['distance_from_cad'] * $additional_work_options['trailer_rental']['basic_coeff'][0] * $additional_work_options['trailer_rental']['basic_coeff'][1]) * $additional_work_options['trailer_rental']['basic_coeff'][2] + $additional_work_options['trailer_rental']['basic_coeff'][3];
        $additional_work_options['trailer_rental']['cost'] = ($this->form_values['additionally_11'] == 'true') ? $additional_work_options['trailer_rental']['quantity'] * $additional_work_options['trailer_rental']['price'] : 0;

        /* Итого по доп.работам */
        $additional_work['total_cost'] = countParamsTotalCost($additional_work_options);

        $additional_work['options'] = $additional_work_options;

        return $additional_work;
    }
}
