<?php

/**
 * Расчет ленточного фундамента
 */

class Tape
{
    public $values;

    public function __construct($values)
    {
        $this->values = $values;
    }

    public function result()
    {
        return $this->values['length'] * $this->values['width'];
    }
}
