<?php

/**
 * EXHIBIT A. Common Public Attribution License Version 1.0
 * The contents of this file are subject to the Common Public Attribution License Version 1.0 (the “License”);
 * you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * http://www.oxwall.org/license. The License is based on the Mozilla Public License Version 1.1
 * but Sections 14 and 15 have been added to cover use of software over a computer network and provide for
 * limited attribution for the Original Developer. In addition, Exhibit A has been modified to be consistent
 * with Exhibit B. Software distributed under the License is distributed on an “AS IS” basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language
 * governing rights and limitations under the License. The Original Code is Oxwall software.
 * The Initial Developer of the Original Code is Oxwall Foundation (http://www.oxwall.org/foundation).
 * All portions of the code written by Oxwall Foundation are Copyright (c) 2011. All Rights Reserved.

 * EXHIBIT B. Attribution Information
 * Attribution Copyright Notice: Copyright 2011 Oxwall Foundation. All rights reserved.
 * Attribution Phrase (not exceeding 10 words): Powered by Oxwall community software
 * Attribution URL: http://www.oxwall.org/
 * Graphic Image as provided in the Covered Code.
 * Display of Attribution Information is required in Larger Works which are defined in the CPAL as a work
 * which combines Covered Code or portions thereof with code not governed by the terms of the CPAL.
 */

/**
 * The class represents the results of an authentication attempt.
 *
 * @author Sardar Madumarov <madumarov@gmail.com>
 * @package ow_core
 * @since 1.0
 */
class OW_AuthResult
{
    /**
     * General Failure.
     */
    const FAILURE = 0;

    /**
     * Identity not found failure.
     */
    const FAILURE_IDENTITY_NOT_FOUND = -1;

    /**
     * Invalid password failure.
     */
    const FAILURE_PASSWORD_INVALID = -2;

    /**
     * Authentication success.
     */
    const SUCCESS = 1;

    /**
     * @var integer
     */
    private $code;
    /**
     * @var array
     */
    private $messages;
    /**
     * @var integer
     */
    private $userId;

    /**
     * Constructor.
     */
    public function __construct( $code, $userId = null, array $messages = [] )
    {
        $code = (int) $code;

        if ( $code < self::FAILURE_PASSWORD_INVALID )
        {
            $code = self::FAILURE;
        }
        elseif ( $code > self::SUCCESS )
        {
            $code = self::SUCCESS;
        }

        $this->code = $code;

        if ( $userId != null )
        {
            $this->userId = (int) $userId;
        }

        $this->messages = $messages;
    }

    /**
     * Checks if authentication result is valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        return ( $this->code > 0 ) ? true : false;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}