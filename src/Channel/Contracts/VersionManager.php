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
 * Version property manager
 */
trait VersionManager {

  /**
   * Gets the ver field.
   */
  public function getVer() {
    if (array_key_exists('ver', $this->_data)) { return $this->_data['ver']; }
    return NULL;
  }

  /**
   * Sets the ver field.
   */
  public function setVer($ver) {
    $this->_data['ver'] = $ver;
  }
}
