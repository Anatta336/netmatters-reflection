<?php declare(strict_types=1);
namespace Netmatters\Database;

/**
 * A simple interface to an external database.
 *
 * @package Database
 */
interface DatabaseInterface
{
    /**
     * Executes a given SQL query, optionally binding values to replace placeholders.
     * Returns results as an associative array.
     *
     * @param string $sqlQuery  The SQL query to execute, with any placeholders
     *                          marked by '?'.
     * @param mixed  ...$values 0 or more values that will be bound to placeholders
     *                          in the query.
     *
     * @return array Array of associative arrays which holds the results.
     */
    public function fetchResults(string $sqlQuery, ...$values): array;

    /**
     * Executes a given SQL query, optionally binding values to replace placeholders.
     * Returns boolean indicating if query was executed.
     *
     * @param string $sqlQuery  The SQL query to execute, with any placeholders
     *                          marked by '?'.
     * @param mixed  ...$values 0 or more values that will be bound to placeholders
     *                          in the query.
     *
     * @return bool True if query was executed successfully.
     */
    public function runQuery(string $sqlQuery, ...$values): bool;
}
