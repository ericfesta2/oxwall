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
 * Data Transfer Object for `photo` table.
 *
 * @authors Egor Bulgakov <egor.bulgakov@gmail.com>, Kairat Bakitow <kainisoft@gmail.com>
 * @package ow.plugin.photo.bol
 * @since 1.0
 */
final class PHOTO_BOL_Photo extends OW_Entity
{
    /**
     * @var integer
     */
    public $albumId;
    /**
     * @var string
     */
    public $description;
    /**
     * @var integer
     */
    public $addDatetime;
    /**
     * @var string
     */
    public $status;
    /**
     * 
     * @var integer
     */
    public $hasFullsize;
    /**
     * @var string
     */
    public $privacy;
    /**
     * @var string
     */
    public $hash;
    /**
     * @var string
     */
    public $uploadKey;
    
    public $dimension;

    public string $url;
    
    public function getDimension()
    {
        return $this->dimension;
    }

    public function setDimension( $value )
    {
        $this->dimension = $value;
        
        return $this;
    }
}
