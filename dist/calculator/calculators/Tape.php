<?php

include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/helpers.php';
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/calculators/CalculatorsData.php';

/**
 * Расчет ленточного фундамента
 */
class Tape
{
    public $values;
    public $main_data;

    public function __construct($values)
    {
        $this->values = $values;
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
        $additional_expenses = $calculators_data->getAdditionalExpenses('tape');

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
            /** Итого по ленте */
            'total' => $total,
            /** Расчеты по дополнительным затратам */
            'additional_expenses' => $additional_expenses
        ];
    }

    protected function calculateMain()
    {
        /* длина ленты */
        //TODO отдать на фронт данные в плейсхолдер
        $this->main_data['total_length'] =  $this->values['tape_length'];
        if (empty($this->main_data['total_length']) || $this->main_data['total_length'] == 0) {
            if ($this->values['length'] > 0 && $this->values['width'] > 0) {
                if ($this->values['length'] <= 3 || $this->values['width'] <= 3) {
                    $this->main_data['total_length'] = ($this->values['length'] + $this->values['width']) * 2;
                } else if (($this->values['length'] + $this->values['width']) <= 12) {
                    $this->main_data['total_length'] = $this->values['length'] * 3 + $this->values['width'] * 2;
                } else {
                    $this->main_data['total_length'] = ceil($this->values['length'] * $this->values['width'] * 0.75);
                }
            } else {
                $this->main_data['total_length'] = 0;
            }
        }

        /* периметр фундамента */
        //TODO отдать на фронт данные в плейсхолдер
        $this->main_data['perimetr'] =  $this->values['foundation_perimeter'];
        if (empty($this->main_data['perimetr']) || $this->main_data['perimetr'] == 0) {
            if ($this->values['length'] > 0 && $this->values['width'] > 0) {
                $this->main_data['perimetr'] = ($this->values['length'] + $this->values['width']) * 2;
            } else {
                $this->main_data['perimetr'] = 0;
            }
        }

        /* расстояние от КАД */
        $this->main_data['distance_from_cad'] =  $this->values['distance_from_cad'];

        /*** Внутренние данные ***/
        /* объём бетона */
        $this->main_data['concrete_volume'] = $this->values['tape_height'] * $this->values['tape_width'] * $this->main_data['total_length'];

        /* площадь подошвы фундамента */
        $this->main_data['base_square'] = $this->values['tape_width'] * $this->main_data['total_length'];

        /* Площадь боковых стенок */
        $this->main_data['side_square'] = $this->main_data['total_length'] * $this->values['tape_height'] * 2;

        /* ширина траншеи */
        $this->main_data['trench_width'] = $this->values['tape_width'] + 0.3;

        /* объём траншеи */
        $this->main_data['trench_volume'] = $this->main_data['total_length'] * $this->main_data['trench_width'] *  $this->values['tape_height'];

        /* толщина подушки */
        $this->main_data['pillow_thickness'] = 0.3;

        /* кол-во горизонтальных хлыстов */
        switch ('true') {
            case ($this->values['tape_height'] <= 0.5):
                $n1 = 2;
                break;
            case ($this->values['tape_height'] > 0.5 && $this->values['tape_height'] <= 0.9):
                $n1 = 3;
                break;
            case ($this->values['tape_height'] > 0.9 && $this->values['tape_height'] <= 1.3):
                $n1 = 4;
                break;
            case ($this->values['tape_height'] > 1.3 && $this->values['tape_height'] <= 1.6):
                $n1 = 5;
                break;
            default:
                $n1 = 6;
        }
        switch ('true') {
            case ($this->values['tape_width'] <= 0.4):
                $n2 = 2;
                break;
            case ($this->values['tape_width'] > 0.4 && $this->values['tape_width'] <= 0.7):
                $n2 = 3;
                break;
            default:
                $n2 = 4;
        }

        /* арматура А500С (хлысты) */
        $this->main_data['a500_whip'] = $this->main_data['total_length'] * ($n1 * $n2);

        /* арматура А500С (перемычки) */
        $this->main_data['a500_bridge'] = ceil($this->main_data['total_length'] / 0.3) * ($this->values['tape_height'] * 2 + $this->values['tape_width'] * 3);

        /* опалубка (щиты) */
        $this->main_data['desk_shield'] = $this->main_data['total_length'] * $this->values['tape_height'] * 2 * 0.04;

        /* опалубка (укрепление) */
        $this->main_data['desk_holder'] = $this->main_data['total_length'] * 2 * ($this->values['tape_height'] + 2 + 1.4) * 0.1 * 0.025;

        /* дренаж, длина */
        $this->main_data['drainage_length'] = $this->main_data['perimetr'] + 15;

        /* закладные под воду, эл-во, канализацию */
        $this->main_data['embedded_fittings'] = 3;

        /* разводка канализации под фундаментом */
        $this->main_data['interconnection'] = $this->values['length'] + $this->values['width'];
    }

    protected function calculateWorks($works)
    {
        $works_options = $works['options'];

        /* Планировка, разбивка осей */
        $works_options['breakdown_axes']['price'] = $works_options['breakdown_axes']['basic_price'][0];
        $works_options['breakdown_axes']['cost'] = $works_options['breakdown_axes']['quantity'] * $works_options['breakdown_axes']['price'];

        /* Земляные работы (рытье траншеи) */
        $works_options['excavation']['quantity'] = roundUp($this->main_data['trench_volume'], 0);
        $works_options['excavation']['price'] = $works_options['excavation']['basic_price'][0];
        $works_options['excavation']['cost'] = $works_options['excavation']['quantity'] * $works_options['excavation']['price'];

        /* Укладка геотекстиля */
        $works_options['geotextile_laying']['quantity'] = roundUp(($this->main_data['base_square'] + $works_options['geotextile_laying']['quantity_coeff'][0] * $this->main_data['total_length']) * $works_options['geotextile_laying']['quantity_coeff'][1], -1);
        $works_options['geotextile_laying']['price'] = $works_options['geotextile_laying']['basic_price'][0];
        $works_options['geotextile_laying']['cost'] = $works_options['geotextile_laying']['quantity'] * $works_options['geotextile_laying']['price'];

        /* Устройство песчано-гравийной подушки */
        $works_options['gravel_pad_device']['quantity'] = roundUp($this->main_data['trench_width'] * $this->main_data['total_length'] * $this->main_data['pillow_thickness'] * $works_options['gravel_pad_device']['quantity_coeff'][0], -1);
        $works_options['gravel_pad_device']['price'] = $works_options['gravel_pad_device']['basic_price'][0];
        $works_options['gravel_pad_device']['cost'] = $works_options['gravel_pad_device']['quantity'] * $works_options['gravel_pad_device']['price'];

        /* Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона) */
        $works_options['concrete_works']['basic_coeff'] = conditionToObject($works_options['concrete_works']['basic_coeff']);
        $works_options['concrete_works']['quantity'] = roundUp($this->main_data['concrete_volume'] * $works_options['concrete_works']['quantity_coeff'][0], 0);
        $works_options['concrete_works']['price'] = countPriceByCondition($works_options['concrete_works']);
        $works_options['concrete_works']['cost'] = $works_options['concrete_works']['quantity'] * $works_options['concrete_works']['price'];

        /* Итого по работам */
        $works['total_cost'] = countParamsTotalCost($works_options);

        $works['options'] = $works_options;

        return $works;
    }

    protected function calculateMaterials($materials)
    {
        $materials_options = $materials['options'];

        /* Песок с доставкой */
        $materials_options['sand_delivery']['basic_coeff'] = conditionToObject($materials_options['sand_delivery']['basic_coeff']);
        $materials_options['sand_delivery']['quantity'] = roundUp($this->main_data['trench_width'] * $this->main_data['pillow_thickness'] * $this->main_data['total_length'] * $materials_options['sand_delivery']['quantity_coeff'][0], -1);
        $materials_options['sand_delivery']['price'] = countPriceByCondition($materials_options['sand_delivery']);
        $materials_options['sand_delivery']['cost'] = $materials_options['sand_delivery']['quantity'] * $materials_options['sand_delivery']['price'];

        /* Геотекстиль */
        $materials_options['geotextile']['quantity'] = roundUp(($this->main_data['base_square'] + $materials_options['geotextile']['quantity_coeff'][0] * $this->main_data['total_length']) * $materials_options['geotextile']['quantity_coeff'][1], -1);
        $materials_options['geotextile']['price'] = $materials_options['geotextile']['basic_price'][0];
        $materials_options['geotextile']['cost'] = $materials_options['geotextile']['quantity'] * $materials_options['geotextile']['price'];

        /* Пленка техническая */
        $materials_options['technical_film']['quantity'] = roundUp(($this->main_data['base_square'] + $this->main_data['side_square'] + $materials_options['technical_film']['quantity_coeff'][0] * $this->main_data['total_length']) * $materials_options['technical_film']['quantity_coeff'][1], -1);
        $materials_options['technical_film']['price'] = $materials_options['technical_film']['basic_price'][0];
        $materials_options['technical_film']['cost'] = $materials_options['technical_film']['quantity'] * $materials_options['technical_film']['price'];

        /* Опалубка, пиломатериалы */
        $materials_options['formwork']['quantity'] = roundUp(($this->main_data['desk_shield'] + $this->main_data['desk_holder']) * $materials_options['formwork']['quantity_coeff'][0], 1);
        $materials_options['formwork']['price'] = $materials_options['formwork']['basic_price'][0];
        $materials_options['formwork']['cost'] = $materials_options['formwork']['quantity'] * $materials_options['formwork']['price'];

        /* Арматура А500С d12мм */
        $materials_options['armature_a500С_d12mm']['quantity'] = roundUp($this->main_data['a500_whip'] * $materials_options['armature_a500С_d12mm']['quantity_coeff'][0] * $materials_options['armature_a500С_d12mm']['quantity_coeff'][1] / $materials_options['armature_a500С_d12mm']['quantity_coeff'][2], 1);
        $materials_options['armature_a500С_d12mm']['price'] = $materials_options['armature_a500С_d12mm']['basic_price'][0];
        $materials_options['armature_a500С_d12mm']['cost'] = $materials_options['armature_a500С_d12mm']['quantity'] * $materials_options['armature_a500С_d12mm']['price'];

        /* Арматура А500С d8м */
        $materials_options['armature_a500С_d8mm']['quantity'] = roundUp($this->main_data['a500_bridge'] * $materials_options['armature_a500С_d8mm']['quantity_coeff'][0] * $materials_options['armature_a500С_d8mm']['quantity_coeff'][1] / $materials_options['armature_a500С_d8mm']['quantity_coeff'][2], 1);
        $materials_options['armature_a500С_d8mm']['price'] = $materials_options['armature_a500С_d8mm']['basic_price'][0];
        $materials_options['armature_a500С_d8mm']['cost'] = $materials_options['armature_a500С_d8mm']['quantity'] * $materials_options['armature_a500С_d8mm']['price'];

        /* Бетон товарный В22,5М300П3 с доставкой */
        $materials_options['commercial_concrete']['basic_coeff'] = conditionToObject($materials_options['commercial_concrete']['basic_coeff']);
        $materials_options['commercial_concrete']['quantity'] = roundUp($this->main_data['concrete_volume'] * $materials_options['commercial_concrete']['quantity_coeff'][0], 0);
        $materials_options['commercial_concrete']['price'] = $materials_options['commercial_concrete']['basic_price'][0] + (($this->main_data['distance_from_cad'] <= $materials_options['commercial_concrete']['basic_coeff'][0]) ? $materials_options['commercial_concrete']['basic_coeff'][1] : $this->main_data['distance_from_cad'] * $materials_options['commercial_concrete']['basic_coeff'][2]);
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
        $overhead_transport_options['overhead']['price'] = roundUp($materials_total_cost * $overhead_transport_options['overhead']['basic_coeff'][0], -2);
        $overhead_transport_options['overhead']['cost'] = $overhead_transport_options['overhead']['quantity'] * $overhead_transport_options['overhead']['price'];

        /* Транспортные и логистические расходы */
        $overhead_transport_options['transport']['price'] = roundUp($overhead_transport_options['transport']['basic_price'][0] + $this->main_data['distance_from_cad'] * $overhead_transport_options['transport']['basic_coeff'][0] * $overhead_transport_options['transport']['basic_coeff'][1] + $materials_total_cost * $overhead_transport_options['transport']['basic_coeff'][2], -2);
        $overhead_transport_options['transport']['cost'] = $overhead_transport_options['transport']['quantity'] * $overhead_transport_options['transport']['price'];

        /* Итого по расходам */
        $overhead_transport['total_cost'] = countParamsTotalCost($overhead_transport_options);

        $overhead_transport['options'] = $overhead_transport_options;

        return $overhead_transport;
    }

    protected function calculateAdditionalWork($additional_work, $works_total_cost, $materials_total_cost, $overhead_transport_total_cost)
    {
        $additional_work_options = $additional_work['options'];

        /* Устройство дренажа по периметру фундамента с отводом в сторону на 10м */
        $additional_work_options['drainage_device']['quantity'] = $this->main_data['drainage_length'];
        $additional_work_options['drainage_device']['price'] = $additional_work_options['drainage_device']['basic_price'][0];
        $additional_work_options['drainage_device']['cost'] = ($this->values['drainage_device'] === 'true') ? $additional_work_options['drainage_device']['quantity'] * $additional_work_options['drainage_device']['price'] : 0;

        /* Гидроизоляция подошвы и боковых стенок фундамента, технониколь, 1 слой */
        $additional_work_options['foundation_waterproofing']['quantity'] = $this->main_data['base_square'] + $this->main_data['side_square'];
        $additional_work_options['foundation_waterproofing']['price'] = $additional_work_options['foundation_waterproofing']['basic_price'][0];
        $additional_work_options['foundation_waterproofing']['cost'] = ($this->values['foundation_waterproofing'] === 'true') ? $additional_work_options['foundation_waterproofing']['quantity'] * $additional_work_options['foundation_waterproofing']['price'] : 0;

        /* Утепление фундамента (по периметру), пеноплекс 50мм */
        $additional_work_options['foundation_insulation']['quantity'] = roundUp($this->values['tape_height'] * $this->main_data['perimetr'], 0);
        $additional_work_options['foundation_insulation']['price'] = $additional_work_options['foundation_insulation']['basic_price'][0];
        $additional_work_options['foundation_insulation']['cost'] = ($this->values['foundation_insulation'] === 'true') ? $additional_work_options['foundation_insulation']['quantity'] * $additional_work_options['foundation_insulation']['price'] : 0;

        /* Устройство бетонной отмостки */
        $additional_work_options['blind_area_device']['quantity'] = $this->main_data['perimetr'];
        $additional_work_options['blind_area_device']['price'] = $additional_work_options['blind_area_device']['basic_price'][0];
        $additional_work_options['blind_area_device']['cost'] = ($this->values['blind_area_device'] === 'true') ? $additional_work_options['blind_area_device']['quantity'] * $additional_work_options['blind_area_device']['price'] : 0;

        /* Разводка канализационных труб, d110мм */
        $additional_work_options['sewer_wiring']['quantity'] = $this->main_data['interconnection'];
        $additional_work_options['sewer_wiring']['price'] = $additional_work_options['sewer_wiring']['basic_price'][0];
        $additional_work_options['sewer_wiring']['cost'] = ($this->values['sewer_wiring'] === 'true') ? $additional_work_options['sewer_wiring']['quantity'] * $additional_work_options['sewer_wiring']['price'] : 0;

        /* Монтаж закладных под воду, электричество и канализацию */
        $additional_work_options['installation_mortgages']['quantity'] = $this->main_data['embedded_fittings'];
        $additional_work_options['installation_mortgages']['price'] = $additional_work_options['installation_mortgages']['basic_price'][0];
        $additional_work_options['installation_mortgages']['cost'] = ($this->values['installation_mortgages'] === 'true') ? $additional_work_options['installation_mortgages']['quantity'] * $additional_work_options['installation_mortgages']['price'] : 0;

        /* Аренда генератора (если нет электричества на участке) */
        $additional_work_options['electricity_supply']['price'] = roundUp($additional_work_options['electricity_supply']['basic_price'][0] + (
            ($works_total_cost + $materials_total_cost + $overhead_transport_total_cost +
                ($additional_work_options['drainage_device']['cost'] +
                    $additional_work_options['foundation_waterproofing']['cost'] +
                    $additional_work_options['foundation_insulation']['cost'] +
                    $additional_work_options['blind_area_device']['cost'] +
                    $additional_work_options['sewer_wiring']['cost']
                    //   + $additional_work_options['electricity_supply']['cost'] + 
                    //  $additional_work_options['electricity_supply']['cost']
                )
            ) / $additional_work_options['electricity_supply']['basic_coeff'][0]) * $additional_work_options['electricity_supply']['basic_coeff'][1], -2);
        $additional_work_options['electricity_supply']['cost'] = ($this->values['electricity_supply'] === 'true') ? $additional_work_options['electricity_supply']['quantity'] * $additional_work_options['electricity_supply']['price'] : 0;

        /* Аренда вагончика для проживания бригады */
        $additional_work_options['trailer_rental']['price'] = ($additional_work_options['trailer_rental']['basic_price'][0] + $this->main_data['distance_from_cad'] * $additional_work_options['trailer_rental']['basic_coeff'][0] * $additional_work_options['trailer_rental']['basic_coeff'][1]) * $additional_work_options['trailer_rental']['basic_coeff'][2] + $additional_work_options['trailer_rental']['basic_coeff'][3];
        $additional_work_options['trailer_rental']['cost'] = ($this->values['trailer_rental'] === 'true') ? $additional_work_options['trailer_rental']['quantity'] * $additional_work_options['trailer_rental']['price'] : 0;

        /* Итого по доп.работам */
        $additional_work['total_cost'] = countParamsTotalCost($additional_work_options);

        $additional_work['options'] = $additional_work_options;

        return $additional_work;
    }
}
