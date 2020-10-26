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
 * Data contract class for type Event_Data.
 */
abstract class BaseData implements DataInterface {
  use JsonSerializer;
  use VersionManager;

  /**
   * Override for the time of the event
   */
  protected $_time;

  /**
   * Data array that will store all the values.
   */
  protected $_data;

  /**
   * Needed to properly construct the JSON envelope.
   */
  protected $_envelopeTypeName;

  /**
   * Needed to properly construct the JSON envelope.
   */
  protected $_dataTypeName;

  /**
   * Gets the envelopeTypeName field.
   */
  public function getEnvelopeTypeName() {
    return $this->_envelopeTypeName;
  }

  /**
   * Gets the dataTypeName field.
   */
  public function getDataTypeName() {
    return $this->_dataTypeName;
  }

  /**
   * Gets the time of the event.
   */
  public function getTime() {
    return $this->_time;
  }

  /**
   * Sets the time of the event.
   */
  public function setTime($time) {
    $this->_time = $time;
  }
}
