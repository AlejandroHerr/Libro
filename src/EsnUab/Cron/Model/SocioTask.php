<?php

namespace EsnUab\Cron\Model;

use EsnUab\Cron\Model\Map\TaskTableMap;


/**
 * Skeleton subclass for representing a row from one of the subclasses of the 'task' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SocioTask extends Task
{

    /**
     * Constructs a new SocioTask class, setting the type_key column to TaskTableMap::CLASSKEY_1.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTypeKey(TaskTableMap::CLASSKEY_1);
    }

} // SocioTask
