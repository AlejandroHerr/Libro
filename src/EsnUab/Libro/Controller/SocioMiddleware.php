<?php

namespace EsnUab\Libro\Controller;

use EsnUab\Libro\Model\Socio;
use EsnUab\Libro\Model\SocioQuery;
use EsnUab\Libro\Model\SocioVersion;
use EsnUab\Libro\Model\Map\SocioTableMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SocioMiddleware
{
    /**
     * Finds a Socio in the DB by ID.
     *
     * @param int $socio Socio's ID
     *
     * @return Socio
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
    /**
     * Sets versión of the Socio.
     *
     * @param int     $version Version's number
     * @param Request $request
     *
     * @return SocioVersion
     *
     * @see [URI | FQSEN] [<description>]
     */
    public function findSocioVersion($version, Request $request)
    {
        $socio = $request->attributes->get('socio');

        if (null == $version = $socio->getOneVersion($version)) {
            $version = $socio->getOneVersion($socio->getLastVersionNumber());
        }

        return $version;
    }
    /**
     * Finds and sets Socio as dissmissed.
     *
     * @param int $socio Socio's ID
     *
     * @return Socio
     *
     * @see SocioMiddleware::findSocio Retrieves the socio from the DB
     */
    public function findAndDismissSocio($socio)
    {
        $socio = $this->findSocio($socio);
        if ($socio->getActivo()) {
            $socio->setActivo(false)
                ->setBaja(date('Y-m-d'))
                ->setVersionComment('Socio dado de baja.');
        }

        return $socio;
    }

    /**
     * Finds and sets Socio as removed.
     *
     * @param int $socio Socio's ID
     *
     * @return Socio
     *
     * @see SocioMiddleware::findSocio Retrieves the socio from the DB
     */
    public function findAndRemoveSocio($socio)
    {
        $socio = $this->findSocio($socio);
        if (!$socio->getRemoved()) {
            $socio->setRemoved(true)
                ->setVersionComment('Socio borrado.');
        }

        return $socio;
    }

    /**
     * Finds and unsets Socio as removed.
     *
     * @param int $socio Socio's ID
     *
     * @return Socio
     *
     * @see SocioMiddleware::findSocio Retrieves the socio from the DB
     */
    public function findAndRestoreSocio($socio)
    {
        $socio = $this->findSocio($socio);
        if ($socio->getRemoved()) {
            $socio->setRemoved(false)
                ->setVersionComment('Socio restaurado.');
        }

        return $socio;
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
