<?php
namespace AlejandroHerr\Exception\Model;

use AlejandroHerr\Exception\Arr\AbstractException;

class ResourceNotFoundException extends AbstractException
{
    public function __construct($type, $id, $code = 0, \Exception $e = null)
    {
        $message = sprintf('Resource \'%s\' with id %u not found.', $type, $id);
        $this->setArrayMessage($message);

        parent::__construct($message, $code, $e);
    }
}
