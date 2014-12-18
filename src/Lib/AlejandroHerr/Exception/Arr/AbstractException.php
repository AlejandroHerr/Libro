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
        $this->arrayMessage = ['message' => $message];
    }
}
