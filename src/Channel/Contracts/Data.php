<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights\Channel\Contracts;


/**
* Data contract class for type Data. Data struct to contain both B and C sections. 
*/
class Data {
    use JsonSerializer;

    /**
    * Data array that will store all the values. 
    */
    private $_data;

    /**
    * Creates a new Data. 
    */
    function __construct()
    {
        $this->_data['baseData'] = NULL;
    }

    /**
    * Gets the baseType field. Name of item (B section) if any. If telemetry data is derived straight from this, this should be null. 
    */
    public function getBaseType()
    {
        if (array_key_exists('baseType', $this->_data)) { return $this->_data['baseType']; }
        return NULL;
    }

    /**
    * Sets the baseType field. Name of item (B section) if any. If telemetry data is derived straight from this, this should be null. 
    */
    public function setBaseType($baseType)
    {
        $this->_data['baseType'] = $baseType;
    }

    /**
    * Gets the baseData field. Container for data item (B section). 
    */
    public function getBaseData()
    {
        if (array_key_exists('baseData', $this->_data)) { return $this->_data['baseData']; }
        return NULL;
    }

    /**
    * Sets the baseData field. Container for data item (B section). 
    */
    public function setBaseData($baseData)
    {
        $this->_data['baseData'] = $baseData;
    }
}
