<?php

namespace Esnuab\Libro\Model;

use Esnuab\Libro\Model\Base\Socio as BaseSocio;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'socio' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Socio extends BaseSocio
{
    public function preInsert(ConnectionInterface $conn = null)
    {
        if (null === $this->getAlta()) {
            $this->setAlta(date('Y-m-d'));
        }

        if (null === $this->getVersionComment()) {
            $this->setVersionComment('Socio creado.');
        }

        return true;
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

    private function isReallyModified()
    {
        $versionFields = [ 'socio.version' , 'socio.version_created_at' , 'socio.version_comment'];
        $modifiedFields = array_unique(array_merge($this->getModifiedColumns(), $versionFields));

        return (count($modifiedFields) > 3);
    }

    public function setNombre($v)
    {
        $v = trim($v);
        $v = ucwords($v);

        return parent::setNombre($v);
    }

    public function setApellido($v)
    {
        $v = trim($v);
        $v = ucwords($v);

        return parent::setApellido($v);
    }

    public function setEsncard($v)
    {
        $v = trim($v);
        $v = strtoupper($v);

        return parent::setEsncard($v);
    }

    public function setEmail($v)
    {
        $v = trim($v);
        $v = strtolower($v);

        return parent::setEmail($v);
    }

    public function setDni($v)
    {
        $v = trim($v);
        $v = strtoupper($v);

        return parent::setDni($v);
    }
}
