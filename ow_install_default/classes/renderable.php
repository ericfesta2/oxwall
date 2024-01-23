<?php

class INSTALL_Renderable extends OW_Renderable
{
    /**
     * Returns rendered markup
     */
    public function render(): string
    {
        $this->onBeforeRender();

        if( !$this->visible )
        {
            return '';
        }

        $viewRenderer = INSTALL::getViewRenderer();
        $vars = $viewRenderer->getAllAssignedVars();

        $viewRenderer->clearAssignedVars();
        $viewRenderer->assignVars($this->assignedVars);

        $renderedMarkup = $viewRenderer->render($this->template);

        $viewRenderer->clearAssignedVars();
        $viewRenderer->assignVars($vars);

        return $renderedMarkup;
    }
}