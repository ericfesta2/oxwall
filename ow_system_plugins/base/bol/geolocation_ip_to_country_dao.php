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
 * Data Access Object for `base_geolocation_ip_to_country` table.
 *
 * @author Nurlan Dzhumakaliev <nurlanj@live.com>
 * @package ow_system_plugins.base.bol
 * @since 1.0
 */
class BOL_GeolocationIpToCountryDao extends OW_BaseDao
{

    /**
     * Constructor.
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }
    /**
     * Singleton instance.
     *
     * @var BOL_GeolocationIpToCountryDao
     */
    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return BOL_GeolocationIpToCountryDao
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * @see OW_BaseDao::getDtoClassName()
     *
     */
    public function getDtoClassName()
    {
        return 'BOL_GeolocationIpToCountry';
    }

    /**
     * @see OW_BaseDao::getTableName()
     *
     */
    public function getTableName()
    {
        $prefix = ( defined('OW_DB_GEOLOCATION_PREFIX') ) ? OW_DB_GEOLOCATION_PREFIX : OW_DB_PREFIX;

        return $prefix . 'base_geolocation_ip_to_country';
    }
    private $cachedItems = [];

    public function ipToCountryCode3( $ip )
    {
        if ( !array_key_exists($ip, $this->cachedItems) )
        {
            $sql = 'SELECT `cc3` FROM `' . $this->getTableName() . '` WHERE inet_aton(:ip) >= ipFrom AND inet_aton(:ip) <= ipTo';

            $this->cachedItems[$ip] = $this->dbo->queryForColumn($sql, array('ip' => $ip));
        }

        return empty($this->cachedItems[$ip]) ? null : $this->cachedItems[$ip];
    }
}