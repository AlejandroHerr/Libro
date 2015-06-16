<?php

namespace EsnUab\Libro\Controller;

use EsnUab\Libro\Model\Socio;
use EsnUab\Libro\Model\SocioQuery;
use EsnUab\Libro\Model\Map\SocioTableMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SocioMiddleware
{
    /**
     * Set Socio instance in the request's attributes.
     *
     * @param Request $request
     *
     * @throws HttpException If the Socio doesn't exist
     */
    public function findSocio(Request $request)
    {
        $socio = $request->attributes->get('socio');

        if (null === $socio = SocioQuery::create()->findPK($socio)) {
            throw new HttpException(404, 'El socio no existe!');
        }

        $request->attributes->set('socio', $socio);
    }
    /**
     * Set SocioVersion instance in the request's attributes.
     *
     * @param Request $request
     *
     * @throws HttpException If the Socio doesn't exist
     */
    public function findSocioVersion(Request $request)
    {
        $socio = $request->attributes->get('socio');
        $version = $request->attributes->get('version');

        if (null == $version = $socio->getOneVersion($version)) {
            $version = $socio->getOneVersion($socio->getLastVersionNumber());
        }

        $request->attributes->set('version', $version);
    }

    public function parseSort(Request $request)
    {
        $sort = $request->attributes->get('sort');
        $fieldNames = SocioTableMap::getFieldNames(SocioTableMap::TYPE_CAMELNAME);
        $_sort = explode(',', $sort);
        $parsed_sort = [];
        foreach ($_sort as $value) {
            $sign = trim(substr($value, 0, 1)) == '+' ? 'ASC' : 'DESC';
            $field = trim(strtolower(substr($value, 1)));
            if (false !== strpos($field, '_')) {
                $field = explode('_', $field);
                $field = $field[0].ucwords($field[1]);
            }
            if (!in_array($field, $fieldNames)) {
                continue;
            }
            $parsed_sort[] = [
                'field' => $field,
                'sign' => $sign,
            ];
        }

        if (empty($parsed_sort)) {
            $parsed_sort[] = [
                'field' => 'id',
                'sign' => 'DESC',
            ];
            $sort = '-id';
        }

        $request->attributes->set('sort', $sort);
        $request->attributes->set('parsed_sort', $parsed_sort);
    }
    public function paginate(Request $request)
    {
        $attr = $request->attributes;
        $sort = $attr->get('parsed_sort');
        $pager = SocioQuery::create()->orderByMany($sort)
            ->paginate($attr->get('page'), $attr->get('limit'));

        $attr->set('pager', $pager);
    }
}
