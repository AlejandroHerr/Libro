<?php
namespace AlejandroHerr\Exception\Arr;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpException extends RuntimeException implements HttpExceptionInterface
{
    protected $headers;
    protected $statusCode;

    public function __construct($statusCode, $message = null, \Exception $e = null, array $headers = array(), $code = 0)
    {
        $this->setArrayMessage($message);

        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $e);
    }
    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
