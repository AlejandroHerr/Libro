<?php
namespace Esnuab\Libro\Model\Exception;

use AlejandroHerr\Exception\Model\ResourceNotFoundException;

class SocioNotFoundException extends ResourceNotFoundException
{
    public function __construct($id)
    {
        parent::__construct('Socio', $id);
    }
}
