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
     * Middleware to find a Socio by its id.
     *
     * @param int $socio Id
     *
     * @return Socio Found Socio
     *
     * @throws HttpException If the Socio doesn't exist
     */
    public function findSocio($socio)
    {
        if (null === $socio = SocioQuery::create()->findPK($socio)) {
            throw new HttpException(404, 'El socio no existe!');
        }

        return $socio;
    }

    public function findSocioVersion(Request $request)
    {
        $socio = $this->findSocio($request->attributes->get('socio'));
        $version = $socio->getOneVersion($request->attributes->get('version'));

        if (null == $version) {
            $request->attributes->set('version', $socio);
        } else {
            $request->attributes->set('version', $version);
        }

        $request->attributes->set('socio', $socio);
    }

    public function parseQuery(Request $request)
    {
        $query = $request->query;
        //ORDER
        if (null != $sort = $query->get('sort')) {
            $sort = explode(',', $sort);
            foreach ($sort as $value) {
                $value = trim(strtolower($value));
                if (false === strpos($value, '-')) {
                    $parsedSorting[] = ['field' => $value, 'dir' => 'ASC'];
                } else {
                    $parsedSorting[] = ['field' => substr($value, 1), 'dir' => 'DESC'];
                }
            }
        } else {
            $parsedSorting[] = ['field' => 'id', 'dir' => 'desc'];
        }

        //FIELDS
        $listOfFields = SocioTableMap::getFieldNames(SocioTableMap::TYPE_CAMELNAME);
        if (null != $fields = $query->get('fields')) {
            $fields = explode(',', $fields);
            foreach ($fields as $field) {
                if (in_array($field, $listOfFields)) {
                }
            }
        }

        $request->attributes->set('sort', $parsedSorting);
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
