<?php

include $_SERVER['DOCUMENT_ROOT'] . '/calculator/utils/helpers.php';
include $_SERVER['DOCUMENT_ROOT'] . '/calculator/calculators/CalculatorsData.php';

/**
 * Расчет плитного фундамента
 */
class USP
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
        $additional_expenses = $calculators_data->getAdditionalExpenses('usp');

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
        /* периметр фундамента */
        if (empty($this->form_values['foundation_perimeter']) || $this->form_values['foundation_perimeter'] == 0) {
            if ($this->form_values['length'] > 0 && $this->form_values['width'] > 0) {
                $this->form_values['foundation_perimeter'] = ($this->form_values['length'] + $this->form_values['width']) * 2;
            } else {
                $this->form_values['foundation_perimeter'] = 0;
            }
        }

        /* площадь плиты */
        if (empty($this->form_values['plate_area']) || $this->form_values['plate_area'] == 0) {
            $this->form_values['plate_area'] = $this->form_values['length'] * $this->form_values['width'];
        }

        /* длина несущих рёбер (сумма всех перегородок и периметра дома) */
        if (empty($this->form_values['rib_length']) || $this->form_values['rib_length'] == 0) {
            if ($this->form_values['length'] > 0 && $this->form_values['width'] > 0) {
                $this->form_values['rib_length'] = $this->form_values['foundation_perimeter'] * 1.8;
            } else {
                $this->form_values['rib_length'] = 0;
            }
        }

        /*** Внутренние данные ***/
        /* толщина плиты */
        $this->main_data['thickness'] = 0.1;

        /* ширина рёбер */
        $this->main_data['edges_width'] = 0.4;

        /* высота рёбер */
        $this->main_data['edges_height'] = $this->form_values['height'] - $this->main_data['thickness'];

        /* объём бетона */
        $this->main_data['concrete_volume'] = $this->form_values['plate_area'] * $this->main_data['thickness'] + ($this->main_data['edges_width'] * $this->main_data['edges_height'] * $this->form_values['rib_length']);

        /* арматура d12 */
        $this->main_data['arma_d12'] = $this->form_values['rib_length'] * 5 * 0.888 / 1000;

        /* арматура d8 (хомуты) */
        $this->main_data['arma_d8'] = (($this->form_values['rib_length'] / 0.3) * ($this->main_data['edges_width'] * 3 + $this->main_data['edges_height'] * 2)) * 0.495 / 1000;

        /* сетка арматурная d8, 150х150мм */
        $this->main_data['setka_d8'] = $this->form_values['plate_area'];

        /* опалубка, пиломатериалы */
        $this->main_data['desk_material'] = $this->form_values['foundation_perimeter'] * ($this->main_data['edges_height'] + $this->main_data['thickness'] + 0.1) * 0.04 + $this->form_values['foundation_perimeter'] * 3 * 0.1 * 0.025;

        /* площадь котлована */
        $this->main_data['pit_square'] = $this->form_values['plate_area'] + $this->form_values['foundation_perimeter'] * 1;

        /* толщина щебёночной подушки */
        $this->main_data['pillow_thickness'] = 0;

        /* глубина котлована */
        $this->main_data['pit_depth'] = $this->form_values['sand_pad_thickness'] + $this->main_data['pillow_thickness'];

        /* разводка канализации под фундаментом */
        $this->main_data['interconnection'] = $this->form_values['length'] + $this->form_values['width'];

        /* закладные под воду, эл-во, канализацию */
        $this->main_data['embedded_fittings'] = 3;

        /* водорозетки, точки потребления */
        $this->main_data['water_sockets'] = 12;

        /* Площадь утеплителя нижний слой, по 100мм, м2 */
        $this->main_data['square_bot'] = $this->form_values['plate_area'] + $this->form_values['foundation_perimeter'] * 0.1;

        /* Площадь утеплителя внутренние слои, по 100мм, м2 */
        $this->main_data['square_inner'] = ($this->form_values['plate_area'] - $this->form_values['rib_length'] * $this->main_data['edges_width']) * ($this->main_data['edges_height'] / 0.1) + ($this->form_values['foundation_perimeter'] * ($this->main_data['edges_height'] + $this->main_data['thickness']));

        /* Общая площадь утеплителя */
        $this->main_data['heater_square'] = $this->main_data['square_bot'] + $this->main_data['square_inner'];

        /* Технониколь Carbon XPS Prof 400, 1180х580х100мм */
        $this->main_data['carbon_xps'] = roundUp($this->main_data['heater_square'] / 0.6844, 0);

        /* дренаж, длина */
        $this->main_data['drainage_length'] = $this->form_values['foundation_perimeter'] + 15;

        /* колодец дренажный смотровой */
        $this->main_data['drainage_well'] = 4;
    }

    protected function calculateWorks($works)
    {
        $works_options = $works['options'];

        /* Планировка территории, разбивка на местности */
        $works_options['breakdown_axes']['price'] = $works_options['breakdown_axes']['basic_price'][0];
        $works_options['breakdown_axes']['cost'] = $works_options['breakdown_axes']['quantity'] * $works_options['breakdown_axes']['price'];

        /* Земляные работы (разработка котлована механизированным способом, ручная доработка) */
        $works_options['excavation']['quantity'] = roundUp($this->main_data['pit_square'] * $this->main_data['pit_depth'], -1);
        $works_options['excavation']['price'] = $works_options['excavation']['basic_price'][0];
        $works_options['excavation']['cost'] = $works_options['excavation']['quantity'] * $works_options['excavation']['price'];

        /* Укладка геотекстиля */
        $works_options['geotextile_laying']['quantity'] = roundUp(($this->main_data['pit_square'] + $this->form_values['foundation_perimeter'] * $this->main_data['pit_depth']) * $works_options['geotextile_laying']['quantity_coeff'][0], -1);
        $works_options['geotextile_laying']['price'] = $works_options['geotextile_laying']['basic_price'][0];
        $works_options['geotextile_laying']['cost'] = $works_options['geotextile_laying']['quantity'] * $works_options['geotextile_laying']['price'];

        /* Устройство песчано-гравийной подушки */
        $works_options['gravel_pad_device']['quantity'] = roundUp($this->form_values['sand_pad_thickness'] * $this->main_data['pit_square'] * $works_options['gravel_pad_device']['quantity_coeff'][0], -1) + roundUp($this->main_data['pillow_thickness'] * $this->main_data['pit_square'] * $works_options['gravel_pad_device']['quantity_coeff'][1], -1);
        $works_options['gravel_pad_device']['price'] = $works_options['gravel_pad_device']['basic_price'][0];
        $works_options['gravel_pad_device']['cost'] = $works_options['gravel_pad_device']['quantity'] * $works_options['gravel_pad_device']['price'];

        /* Укладка утеплителя */
        $works_options['uklad_utep']['quantity'] = $this->main_data['carbon_xps'];
        $works_options['uklad_utep']['price'] = $works_options['uklad_utep']['basic_price'][0];
        $works_options['uklad_utep']['cost'] = $works_options['uklad_utep']['quantity'] * $works_options['uklad_utep']['price'];

        /* Укладка технической плёнки */
        $works_options['uklad_tech_film']['quantity'] = roundUp($this->form_values['plate_area'] * $works_options['gravel_pad_device']['quantity_coeff'][0], -1);
        $works_options['uklad_tech_film']['price'] = $works_options['uklad_tech_film']['basic_price'][0];
        $works_options['uklad_tech_film']['cost'] = $works_options['uklad_tech_film']['quantity'] * $works_options['uklad_tech_film']['price'];

        /* Монтаж труб теплого пола с подключением и опрессовкой коллекторной группы */
        $works_options['mon_trub']['quantity'] = $this->form_values['plate_area'];
        $works_options['mon_trub']['price'] = $works_options['mon_trub']['basic_price'][0];
        $works_options['mon_trub']['cost'] = $works_options['mon_trub']['quantity'] * $works_options['mon_trub']['price'];

        /* Бетонные работы (выставление опалубки, вязка арматуры, заливка бетона, демонтаж опалубки) */
        $works_options['beton_rab']['quantity'] = roundUp($this->main_data['concrete_volume'] * $works_options['beton_rab']['quantity_coeff'][0], 0);
        $works_options['beton_rab']['price'] = $works_options['beton_rab']['basic_price'][0];
        $works_options['beton_rab']['cost'] = $works_options['beton_rab']['quantity'] * $works_options['beton_rab']['price'];

        /* Монтаж закладных под воду, электричество и канализацию */
        $works_options['mon_zak']['quantity'] = $this->main_data['embedded_fittings'];
        $works_options['mon_zak']['price'] = $works_options['mon_zak']['basic_price'][0];
        $works_options['mon_zak']['cost'] = $works_options['mon_zak']['quantity'] * $works_options['mon_zak']['price'];

        /* Итого по работам */
        $works['total_cost'] = countParamsTotalCost($works_options);

        $works['options'] = $works_options;

        return $works;
    }

    protected function calculateMaterials($materials)
    {
        $materials_options = $materials['options'];

        /* Песок с доставкой */
        $materials_options['sand_delivery']['quantity'] = roundUp($this->main_data['pit_square'] * $this->form_values['sand_pad_thickness'] * $materials_options['sand_delivery']['quantity_coeff'][0], -1);
        $materials_options['sand_delivery']['price'] = $materials_options['sand_delivery']['basic_price'][0];
        $materials_options['sand_delivery']['cost'] = $materials_options['sand_delivery']['quantity'] * $materials_options['sand_delivery']['price'];

        /* Щебень гр.фр.20-40 с доставкой */
        $materials_options['rock_20_40']['quantity'] = roundUp($this->main_data['pit_square'] * $this->main_data['pillow_thickness'] * $materials_options['rock_20_40']['quantity_coeff'][0], -1);
        $materials_options['rock_20_40']['price'] = $materials_options['rock_20_40']['basic_price'][0];
        $materials_options['rock_20_40']['cost'] = $materials_options['rock_20_40']['quantity'] * $materials_options['rock_20_40']['price'];

        /* Геотекстиль */
        $materials_options['geotextile']['quantity'] = roundUp(($this->main_data['pit_square'] + $this->form_values['foundation_perimeter'] * $this->main_data['pit_depth']) * $materials_options['geotextile']['quantity_coeff'][0], -1);
        $materials_options['geotextile']['price'] = $materials_options['geotextile']['basic_price'][0];
        $materials_options['geotextile']['cost'] = $materials_options['geotextile']['quantity'] * $materials_options['geotextile']['price'];

        /* Опалубка, пиломатериалы */
        $materials_options['formwork']['quantity'] = roundUp($this->main_data['desk_material'] * $materials_options['formwork']['quantity_coeff'][0], 1);
        $materials_options['formwork']['price'] = $materials_options['formwork']['basic_price'][0];
        $materials_options['formwork']['cost'] = $materials_options['formwork']['quantity'] * $materials_options['formwork']['price'];

        /* Технониколь XPS Carbon Prof 400 */
        $materials_options['technik_400']['quantity'] = roundUp($this->main_data['carbon_xps'] / $materials_options['technik_400']['quantity_coeff'][0], 0);
        $materials_options['technik_400']['price'] = $materials_options['technik_400']['basic_price'][0];
        $materials_options['technik_400']['cost'] = $materials_options['technik_400']['quantity'] * $materials_options['technik_400']['price'];

        /* Пленка техническая */
        $materials_options['technical_film']['quantity'] = roundUp($this->form_values['plate_area'] * $materials_options['technical_film']['quantity_coeff'][0], 0);
        $materials_options['technical_film']['price'] = $materials_options['technical_film']['basic_price'][0];
        $materials_options['technical_film']['cost'] = $materials_options['technical_film']['quantity'] * $materials_options['technical_film']['price'];

        /* Труба теплого пола Pex 20х0,2мм, Valtec */
        $materials_options['trub_pol']['quantity'] = roundUp($this->form_values['plate_area'] * $materials_options['trub_pol']['quantity_coeff'][0], 0);
        $materials_options['trub_pol']['price'] = $materials_options['trub_pol']['basic_price'][0];
        $materials_options['trub_pol']['cost'] = $materials_options['trub_pol']['quantity'] * $materials_options['trub_pol']['price'];

        /* Коллекторная группа */
        $materials_options['coll_group']['quantity'] = 1;
        $materials_options['coll_group']['price'] = $materials_options['coll_group']['basic_price'][0];
        $materials_options['coll_group']['cost'] = $materials_options['coll_group']['quantity'] * $materials_options['coll_group']['price'];

        /* Антифриз */
        $materials_options['antifriz']['quantity'] = roundUp($materials_options['coll_group']['quantity'] * $materials_options['antifriz']['quantity_coeff'][0], 0);
        $materials_options['antifriz']['price'] = $materials_options['antifriz']['basic_price'][0];
        $materials_options['antifriz']['cost'] = $materials_options['antifriz']['quantity'] * $materials_options['antifriz']['price'];

        /* Арматура А500С d12мм */
        $materials_options['armature_a500С_d12mm']['quantity'] = roundUp($this->main_data['arma_d12'] * $materials_options['armature_a500С_d12mm']['quantity_coeff'][0], 1);
        $materials_options['armature_a500С_d12mm']['price'] = $materials_options['armature_a500С_d12mm']['basic_price'][0];
        $materials_options['armature_a500С_d12mm']['cost'] = $materials_options['armature_a500С_d12mm']['quantity'] * $materials_options['armature_a500С_d12mm']['price'];

        /* Арматура А500С d8м */
        $materials_options['armature_a500С_d8mm']['quantity'] = roundUp($this->main_data['arma_d8'] * $materials_options['armature_a500С_d8mm']['quantity_coeff'][0], 1);
        $materials_options['armature_a500С_d8mm']['price'] = $materials_options['armature_a500С_d8mm']['basic_price'][0];
        $materials_options['armature_a500С_d8mm']['cost'] = $materials_options['armature_a500С_d8mm']['quantity'] * $materials_options['armature_a500С_d8mm']['price'];

        /* Сетка арматурная d8, 150х150мм */
        $materials_options['setka_d8']['quantity'] = roundUp($this->main_data['setka_d8'] * $materials_options['setka_d8']['quantity_coeff'][0], 0);
        $materials_options['setka_d8']['price'] = $materials_options['setka_d8']['basic_price'][0];
        $materials_options['setka_d8']['cost'] = $materials_options['setka_d8']['quantity'] * $materials_options['setka_d8']['price'];

        /* Бетон товарный В22,5М300П3 с доставкой */
        $materials_options['commercial_concrete']['quantity'] = roundUp($this->main_data['concrete_volume'] * $materials_options['commercial_concrete']['quantity_coeff'][0], 0);
        $materials_options['commercial_concrete']['price'] = $materials_options['commercial_concrete']['basic_price'][0] + (($this->form_values['distance_from_cad'] <= $materials_options['commercial_concrete']['basic_coeff'][0]) ? $materials_options['commercial_concrete']['basic_coeff'][1] : $this->form_values['distance_from_cad'] * $materials_options['commercial_concrete']['basic_coeff'][2]);
        $materials_options['commercial_concrete']['cost'] = $materials_options['commercial_concrete']['quantity'] * $materials_options['commercial_concrete']['price'];

        /* Работа автобетононасоса (разгрузка бетонной смеси) */
        $materials_options['autonasos']['quantity'] = 1;
        $materials_options['autonasos']['price'] = (roundUp($this->form_values['distance_from_cad'] * $materials_options['autonasos']['basic_coeff'][0] / $materials_options['autonasos']['basic_coeff'][1], 0) + $materials_options['autonasos']['basic_coeff'][2]) * $materials_options['autonasos']['basic_price'][0];
        $materials_options['autonasos']['cost'] = $materials_options['autonasos']['quantity'] * $materials_options['autonasos']['price'];

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
        $overhead_transport_options['transport']['price'] = roundUp($overhead_transport_options['transport']['basic_price'][0] + $this->form_values['distance_from_cad'] * $overhead_transport_options['transport']['basic_coeff'][0] * $overhead_transport_options['transport']['basic_coeff'][1] * $overhead_transport_options['transport']['basic_coeff'][2] + $materials_total_cost * $overhead_transport_options['transport']['basic_coeff'][3], -2);
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
        $additional_work_options['ust_drenage']['quantity'] = roundUp($this->main_data['drainage_length'], 0);
        $additional_work_options['ust_drenage']['price'] = $additional_work_options['ust_drenage']['basic_price'][0];
        $additional_work_options['ust_drenage']['cost'] = ($this->form_values['additionally_1'] == 'true') ? $additional_work_options['ust_drenage']['quantity'] * $additional_work_options['ust_drenage']['price'] : 0;

        /* Устройство ливневой канализации (установка дождеприемников под водосточную систему дома, прокладка труб с отводом от дома на 10м) */
        $additional_work_options['ust_liv']['quantity'] = $this->main_data['drainage_length'];
        $additional_work_options['ust_liv']['price'] = $additional_work_options['ust_liv']['basic_price'][0];
        $additional_work_options['ust_liv']['cost'] = ($this->form_values['additionally_2'] == 'true') ? $additional_work_options['ust_liv']['quantity'] * $additional_work_options['ust_liv']['price'] : 0;

        /* Устройство бетонной отмостки с утеплением 50мм */
        $additional_work_options['ust_beton']['quantity'] = roundUp($this->form_values['foundation_perimeter'], 0);
        $additional_work_options['ust_beton']['price'] = $additional_work_options['ust_beton']['basic_price'][0];
        $additional_work_options['ust_beton']['cost'] = ($this->form_values['additionally_3'] == 'true') ? $additional_work_options['ust_beton']['quantity'] * $additional_work_options['ust_beton']['price'] : 0;

        /* Разводка канализационных труб под фундаментом, d110мм */
        $additional_work_options['razvod_kanal']['quantity'] = $this->main_data['interconnection'];
        $additional_work_options['razvod_kanal']['price'] = $additional_work_options['razvod_kanal']['basic_price'][0];
        $additional_work_options['razvod_kanal']['cost'] = ($this->form_values['additionally_4'] == 'true') ? $additional_work_options['razvod_kanal']['quantity'] * $additional_work_options['razvod_kanal']['price'] : 0;

        /* Коллекторная разводка труб ХВС и ГВС по точкам потребеления */
        $additional_work_options['koll_razvod']['quantity'] = $this->main_data['water_sockets'];
        $additional_work_options['koll_razvod']['price'] = $additional_work_options['koll_razvod']['basic_price'][0];
        $additional_work_options['koll_razvod']['cost'] = ($this->form_values['additionally_5'] == 'true') ? $additional_work_options['koll_razvod']['quantity'] * $additional_work_options['koll_razvod']['price'] : 0;

        /* Монтаж ЛОС, типа «Астра-5» */
        $additional_work_options['mon_los']['quantity'] = 1;
        $additional_work_options['mon_los']['price'] = $additional_work_options['mon_los']['basic_price'][0];
        $additional_work_options['mon_los']['cost'] = ($this->form_values['additionally_6'] == 'true') ? $additional_work_options['mon_los']['quantity'] * $additional_work_options['mon_los']['price'] : 0;

        /* Аренда генератора (если нет электричества на участке) */
        $additional_work_options['aren_gen']['quantity'] = 1;
        $additional_work_options['aren_gen']['price'] = roundUp($additional_work_options['aren_gen']['basic_price'][0] + (
            ($works_total_cost + $materials_total_cost + $overhead_transport_total_cost +
                ($additional_work_options['ust_drenage']['cost'] + $additional_work_options['ust_liv']['cost'] + $additional_work_options['ust_beton']['cost'] + $additional_work_options['razvod_kanal']['cost'] + $additional_work_options['koll_razvod']['cost'] + $additional_work_options['mon_los']['cost'])
            ) / $additional_work_options['aren_gen']['basic_coeff'][0]) * $additional_work_options['aren_gen']['basic_coeff'][1], -2);
        $additional_work_options['aren_gen']['cost'] = ($this->form_values['additionally_7'] == 'true') ? $additional_work_options['aren_gen']['quantity'] * $additional_work_options['aren_gen']['price'] : 0;

        /* Аренда вагончика для проживания бригады */
        $additional_work_options['aren_vag']['quantity'] = 1;
        $additional_work_options['aren_vag']['price'] = ($additional_work_options['aren_vag']['basic_price'][0] + $this->form_values['distance_from_cad'] * $additional_work_options['aren_vag']['basic_coeff'][0] * $additional_work_options['aren_vag']['basic_coeff'][1]) * $additional_work_options['aren_vag']['basic_coeff'][2] + $additional_work_options['aren_vag']['basic_coeff'][3];
        $additional_work_options['aren_vag']['cost'] = ($this->form_values['additionally_8'] == 'true') ? $additional_work_options['aren_vag']['quantity'] * $additional_work_options['aren_vag']['price'] : 0;

        /* Итого по доп.работам */
        $additional_work['total_cost'] = countParamsTotalCost($additional_work_options);

        $additional_work['options'] = $additional_work_options;

        return $additional_work;
    }
}
