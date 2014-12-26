<?php
namespace AlejandroHerr\Exception\Model;

use AlejandroHerr\Exception\Arr\AbstractException;

class ValidationException extends AbstractException implements ModelExceptionInterface
{
    public function __construct($message, $code = 0, \Exception $e = null)
    {
        $this->setArrayMessage($message);
        $this->message = $this->serializeMessage($message);
        parent::__construct($this->message, $code, $e);
    }

    /**
     * Serializes exception message
     * @param  array  $arrayMessage [description]
     * @return string
     */
    protected function serializeMessage(array $arrayMessage)
    {
        $message = '';
        foreach ($arrayMessage as $property => $description) {
            $message .= 'Property '.$property.': '.$description.'<br>';
        }

        return $message;
    }
}
