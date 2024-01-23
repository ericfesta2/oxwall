<?php

class INSTALL_Component extends INSTALL_Renderable
{
    public function __construct( ?string $template = null )
    {
        parent::__construct();

        $templateToRender = $template ?? OW::getAutoloader()->classToFilename(get_class($this), false);

        $this->setTemplate(INSTALL_DIR_VIEW_CMP . $templateToRender . '.php');
    }
}