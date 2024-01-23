<?php

final class INSTALL
{
    public static function getStorage(): INSTALL_Storage
    {
        return INSTALL_Storage::getInstance();    
    }

    public static function getFeedback(): INSTALL_FeedBack
    {
        static $installFeedback;

        if ( !isset($installFeedback) ) {
            $installFeedback = new INSTALL_FeedBack();
        }

        return $installFeedback;    
    }

    public static function getStepIndicator(): INSTALL_CMP_Steps
    {
        static $stepIndicator;

        if ( empty($stepIndicator) )
        {
            $stepIndicator = new INSTALL_CMP_Steps( self::getPredefinedPluginList(true) );
        }

        return $stepIndicator;    
    }

    public static function getViewRenderer(): INSTALL_ViewRenderer
    {
        return INSTALL_ViewRenderer::getInstance();
    }

    public static function getPredefinedPluginList(bool $onlyOptional = false): array
    {
        $fileContent = file_get_contents(INSTALL_DIR_FILES . 'plugins.txt');
        $pluginForInstall = explode("\n", $fileContent);
        $resultPluginList = [];

        foreach ( $pluginForInstall as $pluginLine )
        {
            $plInfo = explode(':', $pluginLine);
            $isAutoInstall = !empty($plInfo[1]) && trim($plInfo[1]) === 'auto';

            if ( !$onlyOptional || !$isAutoInstall )
            {
                $resultPluginList[] = [
                    'plugin' => $plInfo[0],
                    'auto' => $isAutoInstall
                ];
            }
        }

        return $resultPluginList;
    }
}
