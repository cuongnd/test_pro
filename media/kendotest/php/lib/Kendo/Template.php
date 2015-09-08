<?php

namespace Kendo;

class Template extends JavaScriptFunction {
    private $value;
    private $jquery_element;
    function __construct($value,$jquery_element=false) {
        $this->value = $value;
        $this->jquery_element = $jquery_element;
    }

    public function value() {
        if($this->jquery_element)
        {
            return "kendo.template(jQuery('".$this->value."'))";
        }
        else
        {
            return "kendo.template(jQuery('#".$this->value."').html())";
        }
    }
}
?>
