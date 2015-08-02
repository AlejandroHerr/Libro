<?php

namespace EsnUab\Libro\Security\Core\User\Base;

use \Exception;
use \PDO;
use EsnUab\Libro\Model\Socio;
use EsnUab\Libro\Security\Core\User\SocioAccount as ChildSocioAccount;
use EsnUab\Libro\Security\Core\User\SocioAccountQuery as ChildSocioAccountQuery;
use EsnUab\Libro\Security\Core\User\Map\SocioAccountTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'socio_account' table.
 *
 *
 *
 * @method     ChildSocioAccountQuery orderBySalt($order = Criteria::ASC) Order by the salt column
 * @method     ChildSocioAccountQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildSocioAccountQuery orderByLastLogin($order = Criteria::ASC) Order by the last_login column
 * @method     ChildSocioAccountQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildSocioAccountQuery groupBySalt() Group by the salt column
 * @method     ChildSocioAccountQuery groupByPassword() Group by the password column
 * @method     ChildSocioAccountQuery groupByLastLogin() Group by the last_login column
 * @method     ChildSocioAccountQuery groupById() Group by the id column
 *
 * @method     ChildSocioAccountQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSocioAccountQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSocioAccountQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSocioAccountQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSocioAccountQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSocioAccountQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSocioAccountQuery leftJoinSocio($relationAlias = null) Adds a LEFT JOIN clause to the query using the Socio relation
 * @method     ChildSocioAccountQuery rightJoinSocio($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Socio relation
 * @method     ChildSocioAccountQuery innerJoinSocio($relationAlias = null) Adds a INNER JOIN clause to the query using the Socio relation
 *
 * @method     ChildSocioAccountQuery joinWithSocio($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Socio relation
 *
 * @method     ChildSocioAccountQuery leftJoinWithSocio() Adds a LEFT JOIN clause and with to the query using the Socio relation
 * @method     ChildSocioAccountQuery rightJoinWithSocio() Adds a RIGHT JOIN clause and with to the query using the Socio relation
 * @method     ChildSocioAccountQuery innerJoinWithSocio() Adds a INNER JOIN clause and with to the query using the Socio relation
 *
 * @method     \EsnUab\Libro\Model\SocioQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSocioAccount findOne(ConnectionInterface $con = null) Return the first ChildSocioAccount matching the query
 * @method     ChildSocioAccount findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSocioAccount matching the query, or a new ChildSocioAccount object populated from the query conditions when no match is found
 *
 * @method     ChildSocioAccount findOneBySalt(string $salt) Return the first ChildSocioAccount filtered by the salt column
 * @method     ChildSocioAccount findOneByPassword(string $password) Return the first ChildSocioAccount filtered by the password column
 * @method     ChildSocioAccount findOneByLastLogin(string $last_login) Return the first ChildSocioAccount filtered by the last_login column
 * @method     ChildSocioAccount findOneById(int $id) Return the first ChildSocioAccount filtered by the id column *

 * @method     ChildSocioAccount requirePk($key, ConnectionInterface $con = null) Return the ChildSocioAccount by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocioAccount requireOne(ConnectionInterface $con = null) Return the first ChildSocioAccount matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSocioAccount requireOneBySalt(string $salt) Return the first ChildSocioAccount filtered by the salt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocioAccount requireOneByPassword(string $password) Return the first ChildSocioAccount filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocioAccount requireOneByLastLogin(string $last_login) Return the first ChildSocioAccount filtered by the last_login column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocioAccount requireOneById(int $id) Return the first ChildSocioAccount filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSocioAccount[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSocioAccount objects based on current ModelCriteria
 * @method     ChildSocioAccount[]|ObjectCollection findBySalt(string $salt) Return ChildSocioAccount objects filtered by the salt column
 * @method     ChildSocioAccount[]|ObjectCollection findByPassword(string $password) Return ChildSocioAccount objects filtered by the password column
 * @method     ChildSocioAccount[]|ObjectCollection findByLastLogin(string $last_login) Return ChildSocioAccount objects filtered by the last_login column
 * @method     ChildSocioAccount[]|ObjectCollection findById(int $id) Return ChildSocioAccount objects filtered by the id column
 * @method     ChildSocioAccount[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SocioAccountQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \EsnUab\Libro\Security\Core\User\Base\SocioAccountQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'libro', $modelName = '\\EsnUab\\Libro\\Security\\Core\\User\\SocioAccount', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSocioAccountQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSocioAccountQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSocioAccountQuery) {
            return $criteria;
        }
        $query = new ChildSocioAccountQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSocioAccount|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SocioAccountTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SocioAccountTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSocioAccount A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT salt, password, last_login, id FROM socio_account WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSocioAccount $obj */
            $obj = new ChildSocioAccount();
            $obj->hydrate($row);
            SocioAccountTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSocioAccount|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SocioAccountTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SocioAccountTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the salt column
     *
     * Example usage:
     * <code>
     * $query->filterBySalt('fooValue');   // WHERE salt = 'fooValue'
     * $query->filterBySalt('%fooValue%'); // WHERE salt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $salt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterBySalt($salt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($salt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $salt)) {
                $salt = str_replace('*', '%', $salt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SocioAccountTableMap::COL_SALT, $salt, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SocioAccountTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the last_login column
     *
     * Example usage:
     * <code>
     * $query->filterByLastLogin('2011-03-14'); // WHERE last_login = '2011-03-14'
     * $query->filterByLastLogin('now'); // WHERE last_login = '2011-03-14'
     * $query->filterByLastLogin(array('max' => 'yesterday')); // WHERE last_login > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastLogin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterByLastLogin($lastLogin = null, $comparison = null)
    {
        if (is_array($lastLogin)) {
            $useMinMax = false;
            if (isset($lastLogin['min'])) {
                $this->addUsingAlias(SocioAccountTableMap::COL_LAST_LOGIN, $lastLogin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLogin['max'])) {
                $this->addUsingAlias(SocioAccountTableMap::COL_LAST_LOGIN, $lastLogin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocioAccountTableMap::COL_LAST_LOGIN, $lastLogin, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @see       filterBySocio()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SocioAccountTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SocioAccountTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocioAccountTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \EsnUab\Libro\Model\Socio object
     *
     * @param \EsnUab\Libro\Model\Socio|ObjectCollection $socio The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSocioAccountQuery The current query, for fluid interface
     */
    public function filterBySocio($socio, $comparison = null)
    {
        if ($socio instanceof \EsnUab\Libro\Model\Socio) {
            return $this
                ->addUsingAlias(SocioAccountTableMap::COL_ID, $socio->getId(), $comparison);
        } elseif ($socio instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SocioAccountTableMap::COL_ID, $socio->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySocio() only accepts arguments of type \EsnUab\Libro\Model\Socio or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Socio relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function joinSocio($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Socio');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Socio');
        }

        return $this;
    }

    /**
     * Use the Socio relation Socio object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \EsnUab\Libro\Model\SocioQuery A secondary query class using the current class as primary query
     */
    public function useSocioQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSocio($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Socio', '\EsnUab\Libro\Model\SocioQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSocioAccount $socioAccount Object to remove from the list of results
     *
     * @return $this|ChildSocioAccountQuery The current query, for fluid interface
     */
    public function prune($socioAccount = null)
    {
        if ($socioAccount) {
            $this->addUsingAlias(SocioAccountTableMap::COL_ID, $socioAccount->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the socio_account table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocioAccountTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SocioAccountTableMap::clearInstancePool();
            SocioAccountTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocioAccountTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SocioAccountTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SocioAccountTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SocioAccountTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SocioAccountQuery
