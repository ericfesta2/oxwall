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
 * Base class for HTML document object,
 * handles rendered output context (html document includes, headers, styles, etc).
 *
 * @author Sardar Madumarov <madumarov@gmail.com>
 * @package ow.ow_core
 * @since 1.0
 */
class OW_HtmlDocument extends OW_Document
{
    const META_CONTENT_TYPE = 'Content-type';
    const META_CONTENT_LANGUAGE = 'Content-language';
    const META_EXPIRES = 'expires';
    const META_REFRESH = 'refresh';
    const META_AUTHOR = 'author';
    const META_GENERATOR = 'generator';
    const META_COPYRIGHT = 'copyright';
    const META_ROBOTS = 'robots';
    const META_DOCUMENT_STATE = 'document-state';
    const META_URL = 'url';
    const META_RESOURCE_TYPE = 'resource-type';
    const META_PICS_LABEL = 'pics-label';
    const META_REPLY_TO = 'reply-to';

    /**
     * Included stylesheet file urls.
     */
    protected array $styleSheets = ['added' => [], 'items' => []];

    /**
     * Appended style declarations.
     */
    protected array $styleDeclarations = ['hash' => [], 'items' => []];

    /**
     * Included javascript files.
     */
    protected array $javaScripts = ['added' => [], 'items' => []];

    /**
     * Appended javascript code.
     */
    protected array $javaScriptDeclarations = ['hash' => [], 'items' => []];

    /**
     * Javascript code added before script file includes.
     */
    protected array $preIncludeJavaScriptDeclarations = [];

    /**
     * Appended onload javascript.
     */
    protected array $onloadJavaScript = ['hash' => [], 'items' => []];

    /**
     * Added head area links.
     */
    private array $links = [];

    /**
     * Added meta tags.
     */
    private $meta = [];

    /**
     * Custom head info.
     */
    private array $customHeadInfo = [];

    /**
     * Document master page. (OW_MasterPage)
     */
    private $masterPage;

    /**
     * Content area html code.
     */
    private string $body = '';

    /**
     * HTML code to be appended after document was rendered.
     */
    private string $appendCode = '';

    /**
     * HTML code to be prepended after document was rendered.
     */
    private string $prependCode = '';

    /**
     * Document keywords for meta tags.
     */
    private $keywords;

    /**
     * Document heading.
     */
    private $heading;

    /**
     * Document heading icon class.
     */
    private string $headingIconClass = '';

    private string $bodyClass = '';

    private array $availableMetaAttrs = ['http-equiv', 'name', 'property', 'itemprop'];

    public function getBodyClass(): string
    {
        return $this->bodyClass;
    }

    public function setBodyClass( string $bodyClass )
    {
        $this->bodyClass = trim($bodyClass);
    }

    public function addBodyClass( string $class )
    {
        $this->bodyClass .= ' ' . trim($class);
    }

    /**
     * Returns all stylesheets.
     */
    public function getStyleSheets(): array
    {
        return $this->styleSheets;
    }

    /**
     * Sets whole stylesheets array.
     */
    public function setStyleSheets( array $styleSheets )
    {
        $this->styleSheets = $styleSheets;
    }

    /**
     * Returns all included javascript files.
     */
    public function getJavaScripts(): array
    {
        return $this->javaScripts;
    }

    /**
     * Sets whole scripts array.
     */
    public function setJavaScripts( array $javaScripts )
    {
        $this->javaScripts = $javaScripts;
    }

    /**
     * Returns all meta entries.
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Replaces all meta info.
     */
    public function setMeta( array $meta )
    {
        $this->meta = $meta;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setType(OW_Document::HTML);
    }

    /**
     * Returns document heading icon class.
     */
    public function getHeadingIconClass(): string
    {
        return $this->headingIconClass;
    }

    /**
     * Sets document heading icon class.
     */
    public function setHeadingIconClass( string $headingIconClass )
    {
        $this->headingIconClass = $headingIconClass;
    }

    /**
     * Sets document heading.
     *
     * @param string $heading
     */
    public function setHeading( string $heading )
    {
        $this->throwEvent('core.set_document_heading', ['str' => $heading]);
        $this->heading = $heading;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    /**
     * Returns HTML document keywords.
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * Sets HTML document keywords.
     *
     * @param mixed $keywords
     * @return OW_HtmlDocument
     */
    public function setKeywords( $keywords ): OW_HtmlDocument
    {
        if ( is_array($keywords) )
        {
            $keywords = implode(',', $keywords);
        }

        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Sets html document body code.
     */
    public function setBody( string $code ): OW_HtmlDocument
    {
        $this->body = $code;

        return $this;
    }

    /**
     * Returns html document body.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets master page in html document.
     */
    public function setMasterPage( OW_MasterPage $masterPage )
    {
        $this->masterPage = $masterPage;
    }

    /**
     * Returns document master page.
     */
    public function getMasterPage()
    {
        return $this->masterPage;
    }

    /**
     * Adds stylesheet file to document.
     */
    public function addStyleSheet( string $url, string $media = 'all', ?int $priority = null ): OW_HtmlDocument
    {
        $url = trim($url);

        $media = trim($media);

        if ( in_array($url, $this->styleSheets['added']) )
        {
            return $this;
        }

        $priority = ($priority === null) ? 1000 : (int) $priority;

        $this->styleSheets['added'][] = $url;

        $this->styleSheets['items'][$priority][$media][] = $url;

        return $this;
    }

    /**
     * Adds head style declarations to document.
     */
    public function addStyleDeclaration( string $style, string $media = 'all', ?int $priority = null ): OW_HtmlDocument
    {
        $media = trim(mb_strtolower($media));

        $styleHash = crc32($style);

        if ( in_array($styleHash, $this->styleDeclarations['hash']) )
        {
            return $this;
        }

        $priority = ($priority === null) ? 1000 : (int) $priority;

        $this->styleDeclarations['hash'][] = $styleHash;

        $this->styleDeclarations['items'][$priority][$media][] = $style;

        return $this;
    }

    /**
     * Adds javascript file to document.
     */
    public function addScript( string $url, string $type = 'text/javascript', ?int $priority = null ): OW_HtmlDocument
    {
        $url = trim($url);

        if ( in_array($url, $this->javaScripts['added']) )
        {
            return $this;
        }

        $priority = ($priority === null) ? 1000 : (int) $priority;

        $this->javaScripts['added'][] = $url;

        $this->javaScripts['items'][$priority][$type][] = $url;

        return $this;
    }

    /**
     * Removes javascript file from document.
     *
     * @param string $url
     * @return OW_HtmlDocument
     */
    public function removeScript( $url )
    {
        if ( !in_array($url, $this->javaScripts['added']) )
        {
            return $this;
        }

        $key = array_search($url, $this->javaScripts['added']);
        unset($this->javaScripts['added'][$key]);

        foreach ( $this->javaScripts['items'] as $priorityKey => $priority )
        {
            foreach ( $priority as $typeKey => $type )
            {
                foreach ( $type as $key => $item )
                {
                    if( $item == $url )
                    {
                        unset($this->javaScripts['items'][$priorityKey][$typeKey][$key]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Adds head javascript code  to document.
     *
     * @param string $script
     * @param string $type
     * @return OW_HtmlDocument
     */
    public function addScriptDeclaration( $script, $type = 'text/javascript', $priority = null )
    {
        $type = trim(mb_strtolower($type));

        $scriptHash = crc32($script);

        if ( in_array($scriptHash, $this->javaScriptDeclarations['hash']) )
        {
            return $this;
        }

        $event = new OW_Event('core.onAddScriptDeclaration', ['scriptHash' => $scriptHash, 'type' => $type], $script);
        OW::getEventManager()->trigger($event);

        if ( !empty($event->getData()) )
        {
            $this->javaScriptDeclarations['hash'][] = $scriptHash;

            $priority = ($priority === null) ? 1000 : (int) $priority;

            $this->javaScriptDeclarations['items'][$priority][$type][] = $event->getData();
        }

        return $this;
    }

    /**
     * Adds onload javascript.
     *
     * @param string $script
     */
    public function addOnloadScript( $script, $priority = null )
    {
        $scriptHash = crc32($script);

        if ( in_array($scriptHash, $this->onloadJavaScript['hash']) )
        {
            return $this;
        }

        $this->onloadJavaScript['hash'][] = $scriptHash;

        $priority = ($priority === null) ? 1000 : (int) $priority;

        $this->onloadJavaScript['items'][$priority][] = $script;

        return $this;
    }

    /**
     * Adds head javascript code  to document before script file includes.
     *
     * @param string $script
     * @param string $type
     * @return OW_HtmlDocument
     */
    public function addScriptDeclarationBeforeIncludes( $script, $type = 'text/javascript', $priority = null )
    {
        $type = trim(mb_strtolower($type));

        $scriptHash = crc32($script);

        if ( in_array($scriptHash, $this->javaScriptDeclarations) )
        {
            return $this;
        }

        $priority = ($priority === null) ? 1000 : (int) $priority;

        $this->preIncludeJavaScriptDeclarations[$priority][$type][] = $script;

        return $this;
    }

    /**
     * Sets document favicon.
     *
     * @param string $url
     * @return OW_HtmlDocument
     */
    public function setFavicon( string $url )
    {
        $attributes = ['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => trim($url)];

        $this->links[] = $attributes;

        return $this;
    }

    /**
     * Appends custom HTML to the rendered document.
     *
     * @param string $code
     * @return OW_HtmlDocument
     */
    public function appendBody( $code )
    {
        $this->appendCode .= $code;

        return $this;
    }

    /**
     * Prepends custom HTML code to the rendered document.
     *
     * @param $code
     * @return OW_HtmlDocument
     */
    public function prependBody( $code )
    {
        $this->prependCode .= $code;

        return $this;
    }

    /**
     * Adds meta info to document.
     * You should also specify meta attribute name: `name` or `http-equiv`
     *
     * @param string $name
     * @param string $value
     * @param string $attributeName
     * @return OW_HtmlDocument
     * @throws InvalidArgumentException
     */
    public function addMetaInfo( $name, $value, $attributeName = 'name' )
    {
        if ( !in_array($attributeName, $this->availableMetaAttrs) )
        {
            throw new InvalidArgumentException('Invalid meta attribute name was provided!');
        }

        $this->meta[$attributeName][$name] = $value;

        return $this;
    }

    /**
     * Adds cutom meta info.
     *
     * @param string $infoString
     */
    public function addCustomHeadInfo( $infoString )
    {
        $this->customHeadInfo[] = $infoString;
    }

    /**
     * @return string
     */
    public function render()
    {
        if ( $this->getTemplate() === null )
        {
            $this->setTemplate(OW::getThemeManager()->getMasterPageTemplate('html_document'));
        }

        $this->addMetaInfo(self::META_CONTENT_TYPE, $this->getMime() . '; charset=' . $this->getCharset(), 'http-equiv');
        $this->addMetaInfo(self::META_CONTENT_LANGUAGE, $this->getLanguage(), 'http-equiv');

        $this->getMasterPage()->assign('content', $this->body);
        $this->getMasterPage()->assign('heading', $this->getHeading());
        $this->getMasterPage()->assign('heading_icon_class', $this->getHeadingIconClass());

        $this->throwEvent('core.before_master_page_render');
        $masterPageOutput = $this->getMasterPage()->render();
        $this->throwEvent('core.after_master_page_render');

        $headData = '';
        $jsData = '';

        // META INFO
        if ( $this->getDescription() )
        {
            $headData .= UTIL_HtmlTag::generateTag('meta', ['name' => 'description', 'content' => $this->getDescription()]) . PHP_EOL;
        }

        if ( $this->getKeywords() )
        {
            $headData .= UTIL_HtmlTag::generateTag('meta', ['name' => 'keywords', 'content' => $this->getKeywords()]) . PHP_EOL;
        }

        foreach ( $this->meta as $key => $value )
        {
            if ( in_array($key, $this->availableMetaAttrs) && !empty($value) )
            {
                foreach ( $value as $name => $content )
                {
                    $attrs = [$key => $name, 'content' => $content];
                    $headData .= UTIL_HtmlTag::generateTag('meta', $attrs) . PHP_EOL;
                }
            }
        }

        // CSS FILE INCLUDES
        ksort($this->styleSheets['items']);

        foreach ( $this->styleSheets['items'] as $priority => $scipts )
        {
            foreach ( $scipts as $media => $urls )
            {
                foreach ( $urls as $url )
                {
                    $attrs = ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => $url, 'media' => $media];
                    $headData .= UTIL_HtmlTag::generateTag('link', $attrs) . PHP_EOL;
                }
            }
        }

        // JS PRE INCLUDES HEAD DECLARATIONS
        ksort($this->preIncludeJavaScriptDeclarations);

        foreach ( $this->preIncludeJavaScriptDeclarations as $priority => $types )
        {
            foreach ( $types as $type => $declarations )
            {
                foreach ( $declarations as $declaration )
                {
                    $attrs = ['type' => $type];
                    $jsData .= UTIL_HtmlTag::generateTag('script', $attrs, true, PHP_EOL . $declaration . PHP_EOL) . PHP_EOL;
                }
            }
        }

        // JS FILE INCLUDES
        ksort($this->javaScripts['items']);
        $headJsInclude = '';
        foreach ( $this->javaScripts['items'] as $priority => $types )
        {
            foreach ( $types as $type => $urls )
            {
                foreach ( $urls as $url )
                {
                    $attrs = ['type' => $type, 'src' => $url];

                    //TODO remake temp fix - get JQUERY lib to the head area
                    if ( $priority == -100 )
                    {
                        $headJsInclude .= UTIL_HtmlTag::generateTag('script', $attrs, true) . PHP_EOL;
                    }
                    else
                    {
                        $jsData .= UTIL_HtmlTag::generateTag('script', $attrs, true) . PHP_EOL;
                    }
                }
            }
        }

        // CSS HEAD DECLARATIONS
        ksort($this->styleDeclarations['items']);

        foreach ( $this->styleDeclarations['items'] as $priority => $mediaTypes )
        {
            foreach ( $mediaTypes as $media => $declarations )
            {
                $attrs = ['media' => $media];
                $headData .= UTIL_HtmlTag::generateTag('style', $attrs, true, implode(' ', $declarations));
            }
        }

        // JS HEAD DECLARATIONS
        ksort($this->javaScriptDeclarations['items']);

        foreach ( $this->javaScriptDeclarations['items'] as $priority => $types )
        {
            foreach ( $types as $type => $declarations )
            {
                foreach ( $declarations as $declaration )
                {
                    $attrs = ['type' => $type];
                    $jsData .= UTIL_HtmlTag::generateTag('script', $attrs, true,
                            PHP_EOL . '(function() {' . $declaration . '})();' . PHP_EOL) . PHP_EOL;
                }
            }
        }

        // ONLOAD JS
        $jsData .= '<script type="text/javascript">' . PHP_EOL . '$(function () {' . PHP_EOL;

        ksort($this->onloadJavaScript['items']);

        foreach ( $this->onloadJavaScript['items'] as $priority => $scripts )
        {
            foreach ( $scripts as $script )
            {
                $jsData .= '(function(_scope) {' . $script . '})({});' . PHP_EOL;
            }
        }

        $jsData .= PHP_EOL . '});' . PHP_EOL . '</script>';

        // LINKS
        foreach ( $this->links as $linkInfo )
        {
            $headData .= UTIL_HtmlTag::generateTag('link', $linkInfo) . PHP_EOL;
        }

        $customHeadData = implode('', $this->customHeadInfo);

        $assignArray = [
            'title' => $this->getTitle(),
            'headData' => $headData . $headJsInclude . $customHeadData,
            'language' => $this->language,
            'direction' => $this->direction,
            'pageBody' => $this->prependCode . $masterPageOutput . $this->appendCode . $jsData . OW_Document::APPEND_PLACEHOLDER,
            'bodyClass' => $this->bodyClass
        ];

        $renderer = OW_ViewRenderer::getInstance();
        $renderer->clearAssignedVars();
        $renderer->assignVars($assignArray);
        return $renderer->renderTemplate($this->getTemplate());
    }

    protected function throwEvent( $name, $params = [] )
    {
        OW::getEventManager()->trigger(new OW_Event($name, $params));
    }
}
