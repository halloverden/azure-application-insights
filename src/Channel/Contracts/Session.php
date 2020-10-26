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
* Data contract class for type Session.  
*/
class Session
{
    use JsonSerializer;

    /**
    * Data array that will store all the values. 
    */
    private $_data;

    /**
    * Creates a new Session. 
    */
    function __construct()
    {
        $this->_data = array();
    }

    /**
    * Gets the id field. Session ID - the instance of the user's interaction with the app. Information in the session context fields is always about the end user. When telemetry is sent from a service, the session context is about the user that initiated the operation in the service. 
    */
    public function getId()
    {
        if (array_key_exists('ai.session.id', $this->_data)) { return $this->_data['ai.session.id']; }
        return NULL;
    }

    /**
    * Sets the id field. Session ID - the instance of the user's interaction with the app. Information in the session context fields is always about the end user. When telemetry is sent from a service, the session context is about the user that initiated the operation in the service. 
    */
    public function setId($id)
    {
        $this->_data['ai.session.id'] = $id;
    }

    /**
    * Gets the isFirst field. Boolean value indicating whether the session identified by ai.session.id is first for the user or not. 
    */
    public function getIsFirst()
    {
        if (array_key_exists('ai.session.isFirst', $this->_data)) { return $this->_data['ai.session.isFirst']; }
        return NULL;
    }

    /**
    * Sets the isFirst field. Boolean value indicating whether the session identified by ai.session.id is first for the user or not. 
    */
    public function setIsFirst($isFirst)
    {
        $this->_data['ai.session.isFirst'] = var_export($isFirst, TRUE);
    }
}
