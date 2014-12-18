<?php
namespace AlejandroHerr\Exception\Model;

use AlejandroHerr\Exception\Arr\AbstractException;

class DuplicateException extends AbstractException
{
    public function __construct($field, $code = 0, \Exception $e = null)
    {
        $message = sprintf('Resource \'%s\' with id %u not found.', $type, $id);
        $this->setArrayMessage($message);

        parent::__construct(sprintf('Field %s already exist', $field));
    }
}
