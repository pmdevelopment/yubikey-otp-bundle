<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 04.05.2017
 * Time: 15:42
 */

namespace PM\Bundle\YubikeyOtpBundle\Test;

use PM\Bundle\YubikeyOtpBundle\Services\ValidationService;

/**
 * Class ValidationTestService
 *
 * @package PM\Bundle\YubikeyOtpBundle\Test
 */
class ValidationTestService extends ValidationService
{
    const CODE_VALID = 'abcdefghijklmnopqrstuvqxyz';
    const CODE_INVALID = 'zzzzzzzzzzzzzzzzzzzzzzzzzz';

    /**
     * Verify OTP. Optional: Verify Identity
     *
     * @param string      $oneTimePassword
     * @param string|null $identity
     *
     * @return bool
     */
    public function verify($oneTimePassword, $identity = null)
    {
        if (self::CODE_VALID === $oneTimePassword) {
            return true;
        }

        return false;
    }


}