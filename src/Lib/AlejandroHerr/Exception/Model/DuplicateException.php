<?php
namespace AlejandroHerr\Exception\Model;

use AlejandroHerr\Exception\Arr\AbstractException;

class DuplicateException extends AbstractException
{
    public function __construct($field, $code = 0, \Exception $e = null)
    {
        $message = sprintf('Field %s already exist', $field);
        $this->setArrayMessage($message);

        parent::__construct($message);
    }
}
