<?php

class INSTALL_ActionController extends INSTALL_Renderable
{
    private string $title = 'OW Install';
    private string $heading = 'Installation Process';

    public function __construct() {}

    public function setPageTitle( string $title )
    {
        $this->title = $title;
    }

    public function getPageTitle(): string
    {
        return $this->title;
    }

    public function setPageHeading( string $heading )
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
    public function redirect( ?string $redirectTo = null )
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
}