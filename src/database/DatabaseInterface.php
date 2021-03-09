<?php declare(strict_types=1);
namespace Netmatters;

interface DatabaseInterface
{
    /**
     * Executes a given SQL query, optionally binding values to replace placeholders.
     * @param string $sqlQuery The SQL query to execute, with any placeholders
     *                         marked by '?'.
     * @param mixed ...$values 0 or more values that will be bound to placeholders
     *                         in the query.
     * @return array Array of associative arrays which holds the results.
     */
    public function fetchResults(string $sqlQuery, ...$values): array;
}
