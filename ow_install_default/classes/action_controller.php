<?php

class INSTALL_ActionController extends INSTALL_Renderable
{
    private string $title = 'Install Oxwall';

    public function __construct()
    {
    }

    public function init(array $dispatchAttrs = [], bool $dbReady = false)
    {
    }

    protected function setPageTitle(string $title)
    {
        $this->title = $title;
    }

    public function getPageTitle(): string
    {
        return $this->title;
    }

    /**
     * Makes permanent redirect to provided URL or URI.
     */
    public function redirect(?string $redirectTo = null)
    {
        // if empty redirect location -> current URI is used
        $redirectUrl = $redirectTo ?? OW::getRequest()->getRequestUri();

        // if URI is provided need to add site home URL
        if (!str_contains($redirectUrl, 'http://') && !str_contains($redirectUrl, 'https://')) {
            $redirectUrl = OW::getRouter()->getBaseUrl() . UTIL_String::removeFirstAndLastSlashes($redirectUrl);
        }

        UTIL_Url::redirect($redirectUrl);
    }
}