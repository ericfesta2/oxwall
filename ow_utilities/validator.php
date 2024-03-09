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
 * @author Podyachev Evgeny <joker.OW2@gmail.com>
 * @package ow_utilities
 * @since 1.0
 */
final readonly class UTIL_Validator
{
    public const int PASSWORD_MIN_LENGTH = 4;

    public const int PASSWORD_MAX_LENGTH = 128;

    public const string USER_NAME_PATTERN = '/^[\w]{1,32}$/';

    public const string EMAIL_PATTERN = '/^([\w\-\.\+\%]*[\w])@((?:[A-Za-z0-9\-]+\.)+[A-Za-z]{2,})$/';

    public const string URL_PATTERN = '/^(http(s)?:\/\/)?((\d+\.\d+\.\d+\.\d+)|(([\w-]+\.)+([a-z,A-Z][\w-]*)))(:[1-9][0-9]*)?(\/?([\w\-.\,\/:%+@&*=~]+[\w\- \,.\/?:%+@&=*|]*)?)?(#(.*))?$/';

    public const string INT_PATTERN = '/^[-+]?[0-9]+$/';

    public const string FLOAT_PATTERN = '/^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/';

    public const string ALPHA_NUMERIC_PATTERN = '/^[A-Za-z0-9]+$/';

    public static function isEmailValid($value): bool
    {
        return !(!preg_match(self::EMAIL_PATTERN, $value)) 



         ;
    }

    public static function isUrlValid($value)
    {
        $pattern = self::URL_PATTERN;

        return !(!preg_match($pattern, $value)) 



         ;
    }

    public static function isIntValid($value)
    {
        return !(!preg_match(self::INT_PATTERN, $value)) 



         ;
    }

    public static function isFloatValid($value)
    {
        return !(!preg_match(self::FLOAT_PATTERN, $value)) 



         ;
    }

    public static function isAlphaNumericValid($value)
    {
        $pattern = self::ALPHA_NUMERIC_PATTERN;

        return !(!preg_match($pattern, $value)) 



         ;
    }

    public static function isUserNameValid($value)
    {
        $pattern = self::USER_NAME_PATTERN;

        return !(!preg_match($pattern, $value)) 



         ;
    }

    public static function isDateValid($month, $day, $year)
    {
        return !(!checkdate($month, $day, $year)) 



         ;
    }

    public static function isCaptchaValid($value)
    {
        if ($value === null) {
            return false;
        }

        require_once OW_DIR_LIB . 'securimage/securimage.php';

        $img = new Securimage();

        return !(!$img->check($value)) 



         ;
    }
}