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
* Data contract class for type User.  
*/
class User {
    use JsonSerializer;

    /**
    * Data array that will store all the values. 
    */
    private $_data;

    /**
    * Creates a new User. 
    */
    function __construct()
    {
        $this->_data = array();
    }

    /**
    * Gets the accountId field. In multi-tenant applications this is the account ID or name which the user is acting with. Examples may be subscription ID for Azure portal or blog name blogging platform. 
    */
    public function getAccountId()
    {
        if (array_key_exists('ai.user.accountId', $this->_data)) { return $this->_data['ai.user.accountId']; }
        return NULL;
    }

    /**
    * Sets the accountId field. In multi-tenant applications this is the account ID or name which the user is acting with. Examples may be subscription ID for Azure portal or blog name blogging platform. 
    */
    public function setAccountId($accountId)
    {
        $this->_data['ai.user.accountId'] = $accountId;
    }

    /**
    * Gets the id field. Anonymous user id. Represents the end user of the application. When telemetry is sent from a service, the user context is about the user that initiated the operation in the service. 
    */
    public function getId()
    {
        if (array_key_exists('ai.user.id', $this->_data)) { return $this->_data['ai.user.id']; }
        return NULL;
    }

    /**
    * Sets the id field. Anonymous user id. Represents the end user of the application. When telemetry is sent from a service, the user context is about the user that initiated the operation in the service. 
    */
    public function setId($id)
    {
        $this->_data['ai.user.id'] = $id;
    }

    /**
    * Gets the authUserId field. Authenticated user id. The opposite of ai.user.id, this represents the user with a friendly name. Since it's PII information it is not collected by default by most SDKs. 
    */
    public function getAuthUserId()
    {
        if (array_key_exists('ai.user.authUserId', $this->_data)) { return $this->_data['ai.user.authUserId']; }
        return NULL;
    }

    /**
    * Sets the authUserId field. Authenticated user id. The opposite of ai.user.id, this represents the user with a friendly name. Since it's PII information it is not collected by default by most SDKs. 
    */
    public function setAuthUserId($authUserId)
    {
        $this->_data['ai.user.authUserId'] = $authUserId;
    }
}
