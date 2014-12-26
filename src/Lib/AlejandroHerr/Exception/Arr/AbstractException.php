<?php
namespace AlejandroHerr\Exception\Arr;

abstract class AbstractException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var array
     */
    protected $arrayMessage;

    public function getArrayMessage()
    {
        return $this->arrayMessage;
    }

    protected function setArrayMessage($message)
    {
        if (is_array($message)) {
            $this->arrayMessage = $message;
        } else {
            $this->arrayMessage[] = $message;
        }
    }
}
