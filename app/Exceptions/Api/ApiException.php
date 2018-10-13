<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 11/02/2017
 * Time: 1:49 PM
 */

namespace App\Exceptions\Api;

use Exception;

/**
 * Class ApiException
 * @package App\Exceptions\Api
 */
class ApiException extends Exception
{
    /**
     * @var
     */
    private $response_code;

    /**
     * @var
     */
    private $param;


    /**
     * ApiException constructor.
     * @param string $code
     * @param int $message
     * @param int $response_code
     * @param array $param
     */
    public function __construct($code, $message, $response_code=400, $param=array())
    {
        $this->code = $code;
        $this->message = $message;
        $this->response_code = $response_code;
        $this->param = $param;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param mixed $response_code
     */
    public function setResponseCode($response_code)
    {
        $this->response_code = $response_code;
    }

    /**
     * @param mixed $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }
}