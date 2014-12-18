<?php
namespace AlejandroHerr\Exception\Arr;

class RuntimeException extends AbstractException
{
    public function __construct($message = null, $code = 0, \Exception $e = null)
    {
        $this->setArrayMessage($message);

        parent::__construct($message, $code, $e);
    }
}
