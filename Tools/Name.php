<?php
namespace Tools;

class Name
{
    public $origin, $render, $clear;

    function __construct($name) {
        $this->origin = $name;

        // Process for interval of long names
        $this->render = str_replace('·', "\n", $name);
        $this->clear = str_replace('·', '', $name);
    }
}
