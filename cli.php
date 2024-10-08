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

define('OW_USE_CONTEXT', 8); // CLI context
define('DS', DIRECTORY_SEPARATOR);
define('OW_DIR_ROOT', __DIR__ . DS);

require_once OW_DIR_ROOT . 'ow_includes' . DS . 'init.php';
require_once OW_DIR_SYSTEM_PLUGIN . 'base' . DS . 'classes' . DS . 'cli_err_output.php';

OW_ErrorManager::getInstance()->setErrorOutput(new BASE_CLASS_CliErrOutput());

// setting default time zone
date_default_timezone_set(OW::getConfig()->getValue('base', 'site_timezone'));

OW_Auth::getInstance()->setAuthenticator(new OW_SessionAuthenticator());

/* @var OW_CliApplication $application */
$application = OW::getApplication();

OW::getPluginManager()->initPlugins();
$event = new OW_Event(OW_EventManager::ON_PLUGINS_INIT);
OW::getEventManager()->trigger($event);

$event = new OW_Event(OW_EventManager::ON_CLI_RUN);
OW::getEventManager()->trigger($event);

$commands = $event->getData();
foreach ($commands as $c) {
    $application->addCommand($c);
}
$application->run();
