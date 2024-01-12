<?php

class INSTALL_ActionController extends INSTALL_Renderable
{
    private string $title = 'Install Oxwall';
    private string $heading = 'Installation Process';
    
    public function __construct()
    {
        
    }
    
    public function setPageTitle( string $title ): void
    {
        $this->title = $title;
    }
    
    public function getPageTitle(): string
    {
        return $this->title;
    }
    
    public function setPageHeading( string $heading ): void
    {
        $this->heading = $heading;
    }
    
    public function getPageHeading(): string
    {
        return $this->heading;
    }

    /**
     * Makes permanent redirect to provided URL or URI.
     */
    public function redirect( ?string $redirectTo = null ): void
    {
        // if empty redirect location -> current URI is used
        if ( $redirectTo === null )
        {
            $redirectTo = OW::getRequest()->getRequestUri();
        }

        // if URI is provided need to add site home URL
        if ( !strstr($redirectTo, 'http://') && !strstr($redirectTo, 'https://') )
        {
            $redirectTo = OW::getRouter()->getBaseUrl() . UTIL_String::removeFirstAndLastSlashes($redirectTo);
        }

        UTIL_Url::redirect($redirectTo);
    }

    /**
     * Optional method for override.
     * Called before action is called.
     */
    public function init( array $dispatchAttrs = [], bool $dbReady = false ): void
    {
    }
}