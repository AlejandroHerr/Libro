<?php

namespace EsnUab\Twig\TwBsExtension;

class TwBsExtension extends \Twig_Extension
{
    protected $lang;

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
    }
    public function getName()
    {
        return 'tw_bs.filters';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'flashbag',
                [$this, 'renderFlashBag'],
                ['is_safe' => ['html'], 'needs_environment' => true, 'needs_context' => true]
            ),
            new \Twig_SimpleFunction(
                'pagination',
                [$this, 'renderPagination'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    public function renderFlashBag(\Twig_Environment $twig, $context)
    {
        return $twig->loadTemplate('twbs_widgets.html.twig')
            ->displayBlock('flashbag', ['app' => $context['app']]);
    }

    public function renderPagination(\Twig_Environment $twig, $pagination, $route)
    {
        return $twig->loadTemplate('twbs_widgets.html.twig')
            ->displayBlock('pagination', ['pagination' => $pagination, 'route' => $route]);
    }
}
