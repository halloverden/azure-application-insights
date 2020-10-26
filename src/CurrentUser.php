<?php

/*
 * This file is part of the microsoft/application-insights package.
 *
 * (c) Microsoft Corporation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HalloVerden\ApplicationInsights;

use HalloVerden\ApplicationInsights\Channel\Contracts\Utils;

/**
 * Main object used for managing users for other telemetry items.
 */
class CurrentUser {
  /**
   * The current user id.
   */
  public $id;

  /**
   * Initializes a new Current_User.
   */
  function __construct() {
    if (array_key_exists('ai_user', $_COOKIE)) {
      $parts = explode('|', $_COOKIE['ai_user']);
      if (sizeof($parts) > 0) {
        $this->id = $parts[0];
      }
    }

    if ($this->id == NULL) {
      $this->id = Utils::returnGuid();
      $_COOKIE['ai_user'] = $this->id;
    }
  }
}
