<?php

final readonly class INSTALL_Application
{
    public function init()
    {
        OW_Auth::getInstance()->setAuthenticator(new OW_SessionAuthenticator());

        $router = OW::getRouter();
        $router->setBaseUrl(OW_URL_HOME);
        $uri = OW::getRequest()->getRequestUri();
        $router->setUri($uri);

        $router->setDefaultRoute(new INSTALL_DefaultRoute());

        include INSTALL_DIR_ROOT . 'init.php';
    }

    public function display( bool $dbReady )
    {
        $dispatchAttrs = OW::getRouter()->route();
        $controllerClass = $dispatchAttrs['controller'];

        /* @var $controller INSTALL_ActionController */
        $controller = new $controllerClass();
        $controller->init($dispatchAttrs, $dbReady);

        $params = [];
        if ( !empty($dispatchAttrs['vars']) )
        {
            $params[] = $dispatchAttrs['vars'];
        }

        call_user_func_array([$controller, $dispatchAttrs['action']], $params);

        $template = $controller->getTemplate();
        if ( empty($template) )
        {
            $controllerName = OW::getAutoloader()->classToFilename($controllerClass, false);
            $template = INSTALL_DIR_VIEW_CTRL . $controllerName
                . '_'
                . UTIL_String::capsToDelimiter($dispatchAttrs['action'], '_') . '.php';

            $controller->setTemplate($template);
        }

        $content = $controller->render();

        $viewRenderer = INSTALL::getViewRenderer();

        $viewRenderer->assignVars([
            'pageBody' => $content,
            'pageTitle' => $controller->getPageTitle(),
            'pageHeading' => $controller->getPageHeading(),
            'pageSteps' => INSTALL::getStepIndicator()->render(),
            'pageStylesheetUrl' => INSTALL_URL_VIEW . 'style.css'
        ]);

        echo $viewRenderer->render(INSTALL_DIR_VIEW . 'master_page.php');
    }
}

final class INSTALL_DefaultRoute extends OW_DefaultRoute
{
    #[\Override]
    public function getDispatchAttrs( $uri ): array
    {
        return [
            'controller' => 'INSTALL_CTRL_Error',
            'action' => 'notFound'
        ];
    }
}