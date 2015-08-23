<?php
/**
 * Created by PhpStorm.
 * User: murdock
 * Date: 8/23/15
 * Time: 10:59 AM
 */

class ExceleratorError {

    var $table;

    public function error() {
        return "error";
    }

    public function __call($name, $args) {
        $this->table = null;
        return $this->error();
    }

    public function genericError() {
        return "Generic error message.";
    }

}