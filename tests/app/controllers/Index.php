<?php
/**
 * Created by PhpStorm.
 * User: mgiraldo
 * Date: 8/13/15
 * Time: 4:37 PM
 */

class Index extends Demo{

    var $a_global_variable = "Foo";
    
    function test() {
        $this->a_global_variable .= "Bar-".$this->table;
        return $this->a_global_variable;
    }

} 