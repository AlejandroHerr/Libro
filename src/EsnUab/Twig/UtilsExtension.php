<?php
namespace EsnUab\Twig;

use Symfony\Component\Intl\Intl;

class UtilsExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'utils.filters';
    }

    public function getFilters()
    {
        return [
            'country' => new \Twig_Filter_Method($this, 'countryFilter'),
            'pad' => new \Twig_Filter_Method($this, 'padFilter')
        ];
    }

    public function countryFilter($country, $locale = 'en')
    {
        return Intl::getRegionBundle()->getCountryName($country, $locale);
    }

    public function padFilter($number, $length = 4)
    {
        $symbol = '%0'.$length.'d';

        return sprintf($symbol, $number);
    }
}
