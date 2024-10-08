<?php
/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is
 * licensed under The BSD license.

 * ---
 * Copyright (c) 2011, Oxwall Foundation
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and
 *  the following disclaimer.
 *
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *  the following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  - Neither the name of the Oxwall Foundation nor the names of its contributors may be used to endorse or promote products
 *  derived from this software without specific prior written permission.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Groups cron job.
 *
 * @author Sergey Kambalin <greyexpert@gmail.com>
 * @package ow.ow_plugins.groups.bol
 * @since 1.0
 */
final class GROUPS_Cron extends OW_Cron
{
    private const int GROUPS_DELETE_LIMIT = 50;

    #[\Override]
    public function getRunInterval(): int
    {
        return 1;
    }

    #[\Override]
    public function run()
    {
        $config = OW::getConfig();

        // check if uninstall is in progress
        if ( !$config->getValue('groups', 'uninstall_inprogress') )
        {
            return;
        }

        if ( !$config->configExists('groups', 'uninstall_cron_busy') )
        {
            $config->addConfig('groups', 'uninstall_cron_busy', 0);
        }

        // check if cron queue is not busy
        if ( $config->getValue('groups', 'uninstall_cron_busy') )
        {
            return;
        }

        $config->saveConfig('groups', 'uninstall_cron_busy', 1);
        $service = GROUPS_BOL_Service::getInstance();

        try
        {
            $groups = $service->findLimitedList(self::GROUPS_DELETE_LIMIT);

            if ( empty($groups) )
            {
                BOL_PluginService::getInstance()->uninstall('groups');
                OW::getApplication()->setMaintenanceMode(false);

                return;
            }

            foreach ( $groups as $group )
            {
                $service->deleteGroup($group->id);
            }

            $config->saveConfig('groups', 'uninstall_cron_busy', 0);
        }
        catch ( Exception $e )
        {
            $config->saveConfig('groups', 'uninstall_cron_busy', 0);

            throw $e;
        }
    }
}