<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.03.15
 * Time: 13:17
 */

namespace PM\Bundle\YubikeyOtpBundle\Services;

use GuzzleHttp\Client;
use PM\Bundle\YubikeyOtpBundle\Model\ResponseModel;
use PM\Bundle\YubikeyOtpBundle\Util\EncodingUtil;

/**
 * Class ValidationService
 *
 * @package PM\Bundle\YubikeyOtpBundle\Services
 */
class ValidationService
{
    /**
     * @var array
     */
    private $config;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get Nonce
     *
     * @return string
     */
    private function getNonce()
    {
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            return md5(openssl_random_pseudo_bytes(32));
        }

        return md5(microtime() . uniqid());
    }

    /**
     * Get Signatur (Base64, RFC 4648)
     *
     * @param array $parameters
     *
     * @return string
     */
    private function getSignature($parameters)
    {
        /**
         * Sorting
         */
        ksort($parameters);

        /**
         * Build Query
         */
        $parametersQuery = http_build_query($parameters);
        $parametersQuery = utf8_encode(str_replace('%3A', ':', $parametersQuery));

        $apiKey = base64_decode($this->config['server']['client_secret']);

        return EncodingUtil::getRfc4648(base64_encode(hash_hmac('sha1', $parametersQuery, $apiKey, true)));
    }

    /**
     * Get Verify Request Url
     *
     * @param string $oneTimePassword
     *
     * @return string
     */
    private function getVerifyUrl($oneTimePassword)
    {
        $oneTimePassword = trim($oneTimePassword);

        $parameters = array(
            'id'        => $this->config['server']['client_id'],
            'otp'       => $oneTimePassword,
            'nonce'     => $this->getNonce(),
            'timestamp' => '1'
        );

        $parametersQuery = http_build_query($parameters);
        $signature = $this->getSignature($parameters);

        return sprintf("verify?%s&h=%s", $parametersQuery, $signature);
    }

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
        /**
         * Basic Length Validation
         */
        $oneTimePasswordLength = strlen($oneTimePassword);
        if (32 > $oneTimePasswordLength || 48 < $oneTimePasswordLength) {
            return false;
        }

        /**
         * Optional Identity Check
         */
        if (null !== $identity && $identity !== $this->getIdentity($oneTimePassword)) {
            return false;
        }
        /**
         * Create Guzzle Request
         */
        $guzzle = new Client(['base_url' => $this->config['server']['uri']]);

        $response = $guzzle->get($this->getVerifyUrl($oneTimePassword));
        $responseBody = $response->getBody()->getContents();

        $result = new ResponseModel($responseBody);

        /**
         * Validation Response Signature
         */
        if ($this->getSignature($result->getParameters()) !== $result->getSignature()) {
            return false;
        }

        return $result->isStatusOk();
    }

    /**
     * Get Identity By One Time Password
     *
     * @param string $oneTimePassword
     *
     * @return string
     */
    public function getIdentity($oneTimePassword)
    {
        return substr($oneTimePassword, 0, strlen($oneTimePassword) - 32);
    }
}