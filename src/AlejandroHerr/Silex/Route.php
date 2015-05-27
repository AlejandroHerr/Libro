<?php
namespace AlejandroHerr\Silex;

use Silex\Route as SilexRoute;

class Route extends SilexRoute
{
    public function template($path)
    {
        $this->setOption('_template', $path);

        return $this;
    }
}
