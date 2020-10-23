<?php


namespace HalloVerden\ApplicationInsights\Channel\Contracts;

/**
* Data contract class for type Application.
*/
trait JsonSerializer {

    /**
    * Implements JSON serialization for a class.
    */
    public function jsonSerialize() {
        return Utils::removeEmptyValues($this->_data);
    }
}
