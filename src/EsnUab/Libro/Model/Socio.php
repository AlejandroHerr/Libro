<?php

namespace EsnUab\Libro\Model;

use EsnUab\Libro\Model\Base\Socio as BaseSocio;
use EsnUab\Libro\Model\Map\SocioTableMap;
use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Skeleton subclass for representing a row from the 'socio' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Socio extends BaseSocio
{
    public function getAllVersionsInfo()
    {
        $versions = [];
        foreach ($this->getAllVersions() as $version) {
            $versions[] = [
                'id' => $version->getId(),
                'version' => $version->getVersion(),
                'createdBy' => $version->getVersionCreatedBy(),
                'createdAt' => $version->getVersionCreatedAt(),
                'comment' => $version->getVersionComment(),
            ];
        }

        return $versions;
    }
    public function getVersionInfo()
    {
    }
    public function preInsert(ConnectionInterface $con = null)
    {
        /* DEFAULT VALUES */
        if (null === $this->getAlta()) {
            $this->setAlta(date('Y-m-d'));
        }
        if (null === $this->getVersionComment()) {
            $this->setVersionComment('Socio creado.');
        }

        return true;
    }

    public function preSave(ConnectionInterface $con = null)
    {
        return $this->validate();
    }

    public function preUpdate(ConnectionInterface $con = null)
    {
        if (!$this->isReallyModified()) {
            return false;
        }

        if (null === $this->getVersionComment()) {
            $this->setVersionComment('Socio editado.');
        }

        return true;
    }

    public function setNombre($nombre)
    {
        $nombre = trim($nombre);
        $nombre = ucwords($nombre);

        return parent::setNombre($nombre);
    }

    public function setApellido($apellido)
    {
        $apellido = trim($apellido);
        $apellido = ucwords($apellido);

        return parent::setApellido($apellido);
    }

    public function setEsncard($email)
    {
        $email = trim($email);
        $email = strtoupper($email);

        return parent::setEsncard($email);
    }

    public function setEmail($email)
    {
        $email = trim($email);
        $email = strtolower($email);

        return parent::setEmail($email);
    }

    public function setDni($dni)
    {
        $dni = trim($dni);
        $dni = strtoupper($dni);

        return parent::setDni($dni);
    }

    /**
     * Checks if any other column than the version's fields are modified.
     *
     * @return bool True if it has been really modified
     */
    private function isReallyModified()
    {
        $versionFields = ['socio.version' , 'socio.version_created_at' , 'socio.version_comment'];
        $modifiedFields = array_unique(array_merge($this->getModifiedColumns(), $versionFields));

        return (count($modifiedFields) > 3);
    }

    public function validate(ValidatorInterface $validator = null)
    {
        parent::validate($validator);

        if (null == $failureMap = $this->getValidationFailures()) {
            $failureMap = new ConstraintViolationList();
        }

        if ($this->isColumnModified(SocioTableMap::COL_ESNCARD)) {
            if (SocioQuery::create()->exist($this->getEsncard(), 'esncard')) {
                $violation = new ConstraintViolation(
                    'The ESNcard already exists in the db.',
                    'The ESNcard already exists in the db.',
                    [],
                    $this,
                    'esncard',
                    $this->getEsncard()
                );
                $failureMap->add($violation);
            }
        }

        if ($this->isColumnModified(SocioTableMap::COL_EMAIL)) {
            if (SocioQuery::create()->exist($this->getEmail(), 'email')) {
                $violation = new ConstraintViolation(
                    'The email already exists in the db.',
                    'The email already exists in the db.',
                    [],
                    $this,
                    'email',
                    $this->getEmail()
                );
                $failureMap->add($violation);
            }
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));
    }
}
