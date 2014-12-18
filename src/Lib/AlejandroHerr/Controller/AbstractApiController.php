<?php
namespace AlejandroHerr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController
{
    public function payloadToJson(Application $app, Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    }
}
