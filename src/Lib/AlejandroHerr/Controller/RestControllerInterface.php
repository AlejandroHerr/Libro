<?php
namespace AlejandroHerr\Controller;

use Symfony\Component\HttpFoundation\Request;

interface RestControllerInterface
{
    public function parsePayload(Request $request);
}
