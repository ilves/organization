<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\components;

use yii\db\Connection;
use yii\db\Query;

/**
 * MappableQuery allows to map query results into objects
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class MappableQuery extends Query
{
    /**
     * @var callable mapper function, that is applied to results
     */
    public $mapper;

    /**
     * Executes the query and returns all results as an array of mapped objects.
     * @param Connection $db the database connection used to generate the SQL statement.
     * If this parameter is not given, the `db` application component will be used.
     * @return array the query results. If the query results in nothing, an empty array will be returned.
     */
    public function all($db = null)
    {
        $results = parent::all($db);
        return is_callable($this->mapper) ? array_map($this->mapper, $results) : $results;
    }
}