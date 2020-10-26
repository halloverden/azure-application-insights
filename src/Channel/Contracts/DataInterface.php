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
