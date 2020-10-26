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
