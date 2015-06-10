<?php

namespace EsnUab\Tests\Libro\Model\Helpers;

use EsnUab\Libro\Model\Map\SocioTableMap;
use Propel\Runtime\Propel;

/**
 * Base class contains some methods shared by subclass test cases.
 */
abstract class LibroTestBase extends \PHPUnit_Framework_TestCase
{
    protected static $withDatabaseSchema = true;
    /**
     * @var Boolean
     */
    protected static $isInitialized = false;
    /**
     * @var \PDO
     */
    protected $con;

    /**
     * This is run before each unit test; it populates the database.
     */
    protected function setUp()
    {
        parent::setUp();
        if (true !== self::$isInitialized) {
            $file = './config/config.php';
            if (!file_exists($file)) {
                return;
            }
            Propel::init($file);
            self::$isInitialized = true;
        }
        $this->con = Propel::getServiceContainer()->getConnection(SocioTableMap::DATABASE_NAME);
        $this->con->beginTransaction();
    }

    /**
     * This is run after each unit test. It empties the database.
     */
    protected function tearDown()
    {
        // Only commit if the transaction hasn't failed.
        // This is because tearDown() is also executed on a failed tests,
        // and we don't want to call ConnectionInterface::commit() in that case
        // since it will trigger an exception on its own
        // ('Cannot commit because a nested transaction was rolled back')
        if (null !== $this->con) {
            if ($this->con->isCommitable()) {
                $this->con->commit();
            }
            echo 'hola';
            $this->con = null;
        }
    }

    public static function tearDownAfterClass()
    {
        Propel::getServiceContainer()->closeConnections();
    }
}
