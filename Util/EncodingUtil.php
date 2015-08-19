<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.03.15
 * Time: 18:13
 */

namespace PM\Bundle\YubikeyOtpBundle\Util;

/**
 * Class EncodingUtil
 *
 * @package PM\Bundle\YubikeyOtpBundle\Util
 */
class EncodingUtil
{

    /**
     * Get RFC 4648 Encoded Base64
     *
     * @param string $string
     *
     * @return string
     */
    public static function getRfc4648($string)
    {
        return preg_replace('/\+/', '%2B', $string);
    }
}