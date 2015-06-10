<?php

namespace Propel\Tests\Helpers\Bookstore;

abstract class LibroEmptyTestBase extends BookstoreTestBase
{
    protected function setUp()
    {
        parent::setUp();
        if (static::$isInitialized) {
            LibroDataPopulator::depopulate($this->con);
        }
    }
}
