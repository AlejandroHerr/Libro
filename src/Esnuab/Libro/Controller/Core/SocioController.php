<?php
namespace Esnuab\Libro\Controller\Core;

use AlejandroHerr\Exception\Model\DuplicateException;
use AlejandroHerr\Exception\Model\ValidationException;
use Esnuab\Libro\Model\Socio;
use Esnuab\Libro\Model\SocioQuery;
use Esnuab\Libro\Model\Exception\SocioNotFoundException;

abstract class SocioController
{
    /**
     * Creates a new Socio resource
     *
     * @param Socio|array $socio Socio's data
     *
     * @return Socio New created Socio resource
     *
     * @throws DuplicateException if 'esncard' or 'email' field exist
     * @throws ValidateException  if there's problems with validation
     */
    protected function create($socio)
    {
        if (is_array($socio)) {
            $s = new Socio();
            $socio = $s->fromArray($socio);
        }

        if (!$socio->validate()) {
            $m = array();
            foreach ($socio->getValidationFailures() as $failure) {
                $m[$failure->getPropertyPath()] = $failure->getMessage();
            }

            throw new ValidationException($m);
        }

        if ($this->exists($socio->getEsncard(), 'esncard')) {
            throw new DuplicateException('esncard');
        }
        if ($this->exists($socio->getEmail(), 'email')) {
            throw new DuplicateException('email');
        }

        $socio->save();

        return $socio;
    }
    /**
     * Deletes a resource by id
     *
     * @param int $id Id of the resource
     */
    protected function delete($id)
    {
        $socio = $this->read($id);
        $socio->delete();
    }
    /**
     * Read resource by id
     *
     * @param int $id Id of the resource
     *
     * @return Socio Loaded Socio
     *
     * @throws SocioNotFoundException if resources doesn't exist
     */
    protected function read($id)
    {
        $socio = SocioQuery::Create()->findPK($id);

        if (false === $socio instanceof Socio) {
            throw new SocioNotFoundException($id);
        }

        return $socio;
    }
    /**
     * Read resource and versions.
     * @param  int   $id Id of the resource
     * @return array
     */
    protected function readVersion($id, $version = null)
    {
        $socio = $this->read($id);
        if (null !== $version) {
            $socio->toVersion($version);
        }
        $socioVersions = $socio->getAllVersions();
        $socio = $socio->toArray();
        foreach ($socioVersions as $socioVersion) {
            $socio['versions'][$socioVersion->getVersion()] = [
                'version' => $socioVersion->getVersion(),
                'createdBy' => $socioVersion->getVersionCreatedBy(),
                'createdAt' => $socioVersion->getVersionCreatedAt(),
                'comment' => $socioVersion->getVersionComment(),
            ];
        }

        return $socio;
    }
    protected function query($page = 1, $maxPerPage = 50)
    {
        $socioPage = SocioQuery::create()
            ->filterByRemoved(0)
            ->paginate($page, $maxPerPage);

        return $socioPage;
    }
    protected function softDelete($id)
    {
        $socio = $this->read($id);
        $socio->setRemoved(1);
        $socio->save();
    }
    /**
     * Updates a socio record
     * @param  Socio $socio Socio
     * @return Socio Updated socio
     */
    protected function update($socio)
    {
        if (!$socio->validate()) {
            $m = array();
            foreach ($socio->getValidationFailures() as $failure) {
                $m[$failure->getPropertyPath()] = $failure->getMessage();
            }

            throw new ValidationException($m);
        }
        if ($socio->isColumnModified('socio.email')) {
            if ($this->exists($socio->getEmail(), 'email')) {
                throw new DuplicateException('email');
            }
        }
        if ($socio->isColumnModified('socio.esncard')) {
            if ($this->exists($socio->getEsncard(), 'esncard')) {
                throw new DuplicateException('esncard');
            }
        }
        $socio->save();

        return $socio;
    }

    /**
     * Proves if exists a resource in the db that matches the same value for a given field.
     *
     * @param  string $value Value to match
     * @param  string $field Field to find
     * @return bool   Returns ture if exists, false if not
     */
    protected function exists($value, $field = 'id')
    {
        $fn = $this->resolveFilterFunctionName($field);
        $count = SocioQuery::create()
            ->$fn($value)
            ->count();

        return $count === 0 ? false : true;
    }
    /**
     * Resolve the name of propel's filterBy function
     * @param  string $field Field
     * @return string Function's name
     */
    private function resolveFilterFunctionName($field = 'id')
    {
        $field = strtoupper($field);

        return 'filterBy'.$field;
    }
}
