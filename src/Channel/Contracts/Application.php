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
class Application {
  use JsonSerializer;

  /**
   * Data array that will store all the values.
   */
  private $_data;

  /**
   * Creates a new Application.
   */
  function __construct() {
    $this->_data = array();
  }

  /**
   * Gets the ver field. Application version. Information in the application context fields is always about the application that is sending the telemetry.
   */
  public function getVer() {
    if (array_key_exists('ai.application.ver', $this->_data)) { return $this->_data['ai.application.ver']; }
    return NULL;
  }

  /**
   * Sets the ver field. Application version. Information in the application context fields is always about the application that is sending the telemetry.
   */
  public function setVer($ver) {
    $this->_data['ai.application.ver'] = $ver;
  }
}
