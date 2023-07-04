<?php

/*** Аналог функции из Excel «ОКРВВЕРХ» ***/
function roundUp($aNum, $aPrecision = 0)
{
    $mult = pow(10, abs($aPrecision));
    return $aPrecision < 0 ?
        ceil($aNum / $mult) * $mult :
        ceil($aNum * $mult) / $mult;
}

/*** Выполнение условий ***/
function countPriceByCondition($aItem)
{
    $doCompareCondition = function ($aOperand1, $aOperator, $aOperand2) {
        switch ($aOperator) {
            case '>':
                return $aOperand1 > $aOperand2;
            case '<':
                return $aOperand1 < $aOperand2;
            case '=':
                return $aOperand1 == $aOperand2;
            case '>=':
                return $aOperand1 >= $aOperand2;
            case '<=':
                return $aOperand1 <= $aOperand2;
            case '<>':
                return $aOperand1 != $aOperand2;
        }
    };

    $qty = $aItem['quantity'];
    $prices = $aItem['basic_price'];
    $conditions = $aItem['basic_coeff']['conditions'];
    if (count($prices) != count($conditions)) {
        return "error: prices & conditions mismatch in «" . $aItem['name'] . "»";
    } else {
        $conditionsMap = array_fill(0, count($conditions), 0);

        foreach ($conditions as $i => $condition) {
            if (isset($condition[0]['operator'])) {
                if (count($condition) == 1) {
                    /* single condition */
                    if ($doCompareCondition($qty, $condition[0]['operator'], $condition[0]['operand'])) {
                        $conditionsMap[$i] = 1;
                    } else {
                        $conditionsMap[$i] = 0;
                    }
                } else {
                    /* multiple AND condition */
                    for ($j = 0, $len = count($condition); $j < $len; $j++) {
                        if ($doCompareCondition($qty, $condition[$j]['operator'], $condition[$j]['operand']) == false) {
                            $conditionsMap[$i] = 0;
                            break;
                        } else {
                            if ($j == $len - 1) {
                                $conditionsMap[$i] = 1;
                            }
                        }
                    }
                }
            } else if ($condition[0]['rest']) {
                /* rest condition */
                if (in_array(1, $conditionsMap) == false) {
                    $conditionsMap[$i] = 1;
                }
            }
        }
        foreach ($conditionsMap as $i => $flag) {
            if ($flag == 1) {
                return $prices[$i];
            }
        }
    }
}

/*** Подсчёт общей стоимости по отдельной группе параметров ***/
function countParamsTotalCost($group_params)
{
    $total = 0;
    foreach ($group_params as $param) {
        $total += $param['cost'];
    }
    return $total;
}

/** Строковое условие преобразует в объект */
function conditionToObject($aValStr)
{
    $aValStr = preg_replace('/\s+/', '', str_replace(',', '.', $aValStr));

    if (strpos($aValStr, 'УСЛОВИЕ:') !== false) {
        $conditions = explode('|', str_replace('УСЛОВИЕ:', '', $aValStr));
        /* Построение «дерева» условий */
        if (!function_exists('tokenizeConditions')) {
            function tokenizeConditions(&$aCondStr)
            {
                $condArr = array();
                preg_match('/(?:(>|<|=|>=|<=|<>)(\d+))|(-)*/', $aCondStr, $matches);
                if (count($matches) < 4) {
                    $condArr['operator'] = $matches[1];
                    $condArr['operand'] = $matches[2];
                } else {
                    $condArr['rest'] = $matches[3];
                }
                $aCondStr = $condArr;
            }
        }
        foreach ($conditions as &$val) {
            $val = explode(';', $val);
            foreach ($val as &$subval) {
                tokenizeConditions($subval);
            }
        }
        $aValStr = array('conditions' => $conditions);
    } else {
        $aValStr = explode('|', $aValStr);
    }

    return $aValStr;
}
