<?php

namespace EsnUab\Tests\Libro\Model\Helpers;

use EsnUab\Libro\Model\Map\SocioTableMap;
use Propel\Runtime\Propel;

class LibroDataPopulator
{
    public static function populate($con = null)
    {
        if ($con === null) {
            $con = Propel::getServiceContainer()->getConnection(SocioTableMap::DATABASE_NAME);
        }
        $con->beginTransaction();

        //Add dummy data

        $con->commit();
    }

    public static function depopulate($con = null)
    {
        $tableMapClasses = array(
            'EsnUab\Libro\Model\Socio',
            'EsnUab\Libro\Model\SocioVersion',
        );
        // free the memory from existing objects
        foreach ($tableMapClasses as $tableMapClass) {
            foreach ($tableMapClass::$instances as $i) {
                $i->clearAllReferences();
            }
            $tableMapClass::doDeleteAll($con);
        }
    }
}
