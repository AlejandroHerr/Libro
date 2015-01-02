<?php
namespace Esnuab\Twig;

use Symfony\Component\Intl\Intl;

class EsnuabExtension extends \Twig_Extension
{
    protected $lang;

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
    }
    public function getName()
    {
        return 'esnuab.filters';
    }

    public function getFilters()
    {
        return [
            'countries' => new \Twig_Filter_Method($this, 'countries'),
        ];
    }

    public function getFunctions()
    {
        return [
            'flashbag' => new \Twig_SimpleFunction(
                'flashbag',
                [$this, 'renderFlashBag'],
                [ 'is_safe' => ['html'], 'needs_environment' => true]
            ),
            'paginator' => new \Twig_SimpleFunction(
                'paginator',
                [$this, 'renderPaginator'],
                [ 'is_safe' => ['html'], 'needs_environment' => true]
            )
        ];
    }

    public function countries($countryCode)
    {
        return Intl::getRegionBundle()->getCountryName($countryCode, $this->lang);
    }

    public function renderFlashBag(\Twig_Environment $twig)
    {
        return $twig->render('flashbag.twig');
    }

    public function renderPaginator(\Twig_Environment $twig, $pagination, $urlName)
    {
        return $twig->render('paginator.twig', ['pagination' => $pagination, 'url_name' => $urlName]);
    }
}
