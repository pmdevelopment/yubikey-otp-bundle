<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.03.15
 * Time: 18:13
 */

namespace PM\Bundle\YubikeyOtpBundle\Model;

use PM\Bundle\YubikeyOtpBundle\Util\EncodingUtil;

/**
 * Class ResponseModel
 *
 * @package PM\Bundle\YubikeyOtpBundle\Model
 */
class ResponseModel
{

    /**
     * Status
     */
    const STATUS_OK = 'OK';

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $signature;

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return ResponseModel
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set Parameter
     *
     * @param string $key
     * @param string $value
     *
     * @return ResponseModel
     */
    private function setParameter($key, $value)
    {
        $parameters = $this->getParameters();
        $parameters[$key] = trim($value);

        return $this->setParameters($parameters);
    }

    /**
     * Get Parameter
     *
     * @param string $key
     *
     * @return mixed|null
     */
    private function getParameter($key)
    {
        $parameters = $this->getParameters();

        if (isset($parameters[$key])) {
            return $parameters[$key];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     *
     * @return ResponseModel
     */
    public function setSignature($signature)
    {
        $this->signature = EncodingUtil::getRfc4648(trim($signature));

        return $this;
    }


    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->setParameters(array());

        foreach (explode("\n", trim($data)) as $responseLine) {
            $responseLinePart = explode("=", $responseLine, 2);
            if (isset($responseLinePart[1])) {
                if ("h" === $responseLinePart[0]) {
                    $this->setSignature($responseLinePart[1]);

                    continue;
                }
                $this->setParameter($responseLinePart[0], $responseLinePart[1]);
            }
        }
    }

    /**
     * Is Status = OK?
     *
     * @return bool
     */
    public function isStatusOk()
    {
        if (self::STATUS_OK === $this->getParameter('status')) {
            return true;
        }

        return false;
    }
}