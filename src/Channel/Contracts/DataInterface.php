<?php


namespace HalloVerden\ApplicationInsights\Channel\Contracts;

/**
* Interface class for XXXXX_Data.
*/
interface DataInterface {
    /**
    * Gets the envelopeTypeName field.
    */
    public function getEnvelopeTypeName();

    /**
    * Gets the dataTypeName field.
    */
    public function getDataTypeName();

    /**
    * JSON serialization for this class.
    */
    public function jsonSerialize();
}
