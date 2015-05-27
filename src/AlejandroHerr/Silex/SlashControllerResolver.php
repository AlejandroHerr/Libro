<?php

namespace AlejandroHerr\Silex;

use Psr\Log\LoggerInterface;
use Silex\Application as App;
use Silex\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;

class SlashControllerResolver extends ControllerResolver
{
    const SERVICE_PATTERN = '/[A-Za-z0-9\._\-]+\/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

    protected $appName;
    protected $resolver;

    public function __construct($appName, App $app, LoggerInterface $logger = null)
    {
        $this->appName = $appName;

        parent::__construct($app, $logger);
    }

    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller', null);

        if (!is_string($controller) || !preg_match(static::SERVICE_PATTERN, $controller)) {
            return parent::getController($request);
        }

        list($service, $method) = explode('/', $controller, 2);
        $service = sprintf('%s.%s.controller', $this->appName, $service);
        $method = sprintf('%sAction', $method);

        if (!isset($this->app[$service])) {
            throw new \InvalidArgumentException(sprintf('Service "%s" does not exist.', $service));
        }

        return array($this->app[$service], $method);
    }
}
