<?php

namespace EsnUab\Libro\Model;

use EsnUab\Libro\Model\Base\SocioQuery as BaseSocioQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'socio' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class SocioQuery extends BaseSocioQuery
{
    public function exist($value = null, $field = 'id')
    {
        $fn = 'filterBy'.$field;

        return (bool) ($this->$fn($value)->count() > 0);
    }

    public function orderByMany(array $order = [])
    {
        foreach ($order as $value) {
            $fn = 'orderBy'.ucwords($value['field']);
            $this->$fn($value['sign']);
        }

        return $this;
    }
}
