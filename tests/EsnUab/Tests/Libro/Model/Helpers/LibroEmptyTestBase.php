<?php

namespace EsnUab\Tests\Libro\Model\Helpers;

abstract class LibroEmptyTestBase extends LibroTestBase
{
    protected function setUp()
    {
        parent::setUp();
        if (static::$isInitialized) {
            LibroDataPopulator::depopulate($this->con);
        }
    }
}
