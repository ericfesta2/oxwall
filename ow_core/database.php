<?php

/**
 * EXHIBIT A. Common Public Attribution License Version 1.0
 * The contents of this file are subject to the Common Public Attribution License Version 1.0 (the “License”);
 * you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * http://www.oxwall.org/license. The License is based on the Mozilla Public License Version 1.1
 * but Sections 14 and 15 have been added to cover use of software over a computer network and provide for
 * limited attribution for the Original Developer. In addition, Exhibit A has been modified to be consistent
 * with Exhibit B. Software distributed under the License is distributed on an “AS IS” basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language
 * governing rights and limitations under the License. The Original Code is Oxwall software.
 * The Initial Developer of the Original Code is Oxwall Foundation (http://www.oxwall.org/foundation).
 * All portions of the code written by Oxwall Foundation are Copyright (c) 2011. All Rights Reserved.

 * EXHIBIT B. Attribution Information
 * Attribution Copyright Notice: Copyright 2011 Oxwall Foundation. All rights reserved.
 * Attribution Phrase (not exceeding 10 words): Powered by Oxwall community software
 * Attribution URL: http://www.oxwall.org/
 * Graphic Image as provided in the Covered Code.
 * Display of Attribution Information is required in Larger Works which are defined in the CPAL as a work
 * which combines Covered Code or portions thereof with code not governed by the terms of the CPAL.
 */

/**
 * Core database connection class.
 *
 * @author Sardar Madumarov <madumarov@gmail.com>, Nurlan Dzhumakaliev <nurlanj@live.com>
 * @package ow_core
 * @since 1.0
 */
final class OW_Database
{
    // private const bool DEFAULT_CACHE_LIFETIME = false;
    private const string NO_CACHE_ENTRY = 'ow_db_no_cache_entry';

    private static array $classInstances;

    // MySQL connection object
    private ?PDO $connection;

    // Number of rows affected by the last SQL statement
    private int $affectedRows;

    // Logger data
    private array $queryLog;

    private bool $debugMode;

    private $isProfilerEnabled;

    private UTIL_Profiler $profiler;

    // Last executed query
    private float $queryExecTime;

    private float $totalQueryExecTime;

    private int $queryCount;

    private bool $useCashe;

    public function getQueryLog(): array
    {
        return $this->queryLog;
    }

    public function getQueryExecTime(): int
    {
        return $this->queryExecTime;
    }

    public function getTotalQueryExecTime(): float
    {
        return $this->totalQueryExecTime;
    }

    public function getQueryCount(): int
    {
        return $this->queryCount;
    }

    public function getUseCashe(): bool
    {
        return $this->useCashe;
    }

    public function setUseCashe(bool $useCashe): void
    {
        $this->useCashe = $useCashe;
    }

    private function __construct(array $params)
    {
        $port = isset($params['port']) ? (int) $params['port'] : null;
        $socket = $params['socket'] ?? null;

        try {
            if ($socket === null) {
                $dsn = "mysql:host={$params['host']};";
                if ($port !== null) {
                    $dsn .= "port={$params['port']};";
                }
            } else {
                $dsn = "mysql:unix_socket={$socket};";
            }
            $dsn .= "dbname={$params['dbname']}";

            $this->connection = new PDO(
                dsn: $dsn,
                username: $params['username'],
                password: $params['password'],
                options: [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8MB4;',
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    PDO::MYSQL_ATTR_SSL_CA => true,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ]
            );

            if (!$this->isMysqlValidVersion()) {
                throw new InvalidArgumentException(
                    'Can\'t connect to database. Connection needs MySQL version 8.0 or higher!'
                );
            }

            $this->prepareMysql();

            if (!empty($params['profilerEnable'])) {
                $this->isProfilerEnabled = true;
                $this->profiler = UTIL_Profiler::getInstance('db');
                $this->queryCount = 0;
                $this->queryExecTime = 0;
                $this->totalQueryExecTime = 0;
                $this->queryLog = [];
            }

            if (!empty($params['debugMode'])) {
                $this->debugMode = true;
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->useCashe = false;
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Returns the instance of class for $params
     *
     * @param array $params ( host, username, password, dbname, [socket], [port] )
     *
     * @return OW_Database
     *
     */
    public static function getInstance(array $params): OW_Database
    {
        if (!isset(self::$classInstances)) {
            self::$classInstances = [];
        }

        ksort($params);

        $connectionKey = serialize($params);

        if (empty(self::$classInstances[$connectionKey])) {
            if (!isset($params['host'], $params['username'], $params['password'], $params['dbname'])) {
                throw new InvalidArgumentException(
                    'Can\'t connect to database. Please provide valid connection attributes.'
                );
            }

            self::$classInstances[$connectionKey] = new self($params);
        }

        return self::$classInstances[$connectionKey];
    }

    public function queryForColumn(
        string $sql,
        ?array $params = null,
        int $cacheLifeTime = 0,
        array $tags = []
    ): mixed {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchColumn(); // (PDO::FETCH_COLUMN);
        $stmt->closeCursor();

        if ($result === false) {
            $result = null;
        }

        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    public function queryForObject(
        string $sql,
        string $className,
        ?array $params = null,
        int $cacheLifeTime = 0,
        array $tags = []
    ): mixed {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
        $result = $stmt->fetch();
        $stmt->closeCursor();

        if ($result === false) {
            $result = null;
        } else {
            $result->generateFieldsHash();
        }

        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    /**
     *
     * @return array
     */
    public function queryForObjectList(
        string $sql,
        string $className,
        ?array $params = null,
        int $cacheLifeTime = 0,
        array $tags = []
    ) {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, $className);

        foreach ($result as $item) {
            $item->generateFieldsHash();
        }

        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    public function setTimezone(): void
    {
        $date = new DateTime();
        $this->query('SET TIME_ZONE = ?', [
            $date->format('P')
        ]);
    }

    public function queryForRow(string $sql, ?array $params = null, int $cacheLifeTime = 0, array $tags = []): array
    {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($result === false) {
            $result = [];
        }

        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    public function queryForList(string $sql, array $params = null, int $cacheLifeTime = 0, array $tags = []): array
    {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    public function queryForColumnList(
        string $sql,
        ?array $params = null,
        int $cacheLifeTime = 0,
        array $tags = []
    ): array|false {
        $dataFromCache = $this->getFromCache($sql, $params, $cacheLifeTime);

        if ($dataFromCache !== self::NO_CACHE_ENTRY) {
            return $dataFromCache;
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $this->saveToCache($result, $sql, $params, $cacheLifeTime, $tags);
        return $result;
    }

    public function query(string $sql, ?array $params = null): int
    {
        $stmt = $this->execute($sql, $params);
        $rowCount = $stmt->rowCount();
        $stmt->closeCursor();
        return $rowCount;
    }

    public function delete(string $sql, ?array $params = null): int
    {
        return $this->query($sql, $params);
    }

    /**
     * insert data and return last insert id
     *
     * @param string $sql
     * @param array $params
     * @return int last_insert_id
     */
    public function insert(string $sql, ?array $params = null)
    {
        $stmt = $this->execute($sql, $params);
        $lastInsertId = $this->connection->lastInsertId();
        $stmt->closeCursor();
        return $lastInsertId;
    }

    public function update(string $sql, ?array $params = null): int
    {
        return $this->query($sql, $params);
    }

    /**
     * Insert object $obj to table $tableName. Returns last_insert_id
     * throws InvalidArgumentException
     *
     * @param string $tableName
     * @param object $obj
     * @return int
     */
    public function insertObject(string $tableName, $obj, bool $delayed = false): int
    {
        if ($obj !== null && is_object($obj)) {
            $params = get_object_vars($obj);
            $paramNames = array_keys($params);
            $columns = UTIL_String::arrayToDelimitedString($paramNames, ',', '`', '`');
            $values = UTIL_String::arrayToDelimitedString($paramNames, ',', ':');
            $sql = 'INSERT' . ($delayed ? ' DELAYED' : '') . " INTO `{$tableName}` ({$columns}) VALUES ({$values})";

            return $this->insert($sql, $params);
        }


        throw new InvalidArgumentException('object expected');
    }

    /**
     * Insert object lisy $obj to table $tableName. Returns last_insert_id
     * throws InvalidArgumentException
     *
     * @param string $tableName
     * @param object[] $objList
     * @return integer
     */
    public function insertObjectList(string $tableName, array $objList, bool $delayed = false): int
    {
        $sqlList = '';
        $params = [];
        $paramNames = [];
        $number = 1;

        foreach ($objList as $obj) {
            if ($obj !== null && is_object($obj)) {
                $objectVars = get_object_vars($obj);
                foreach ($objectVars as $objKey => $objVar) {
                    $params["{$objKey}_$number"] = $objVar;
                    $paramNames[] = "{$objKey}_$number";
                }
                $columns = UTIL_String::arrayToDelimitedString(array_keys($objectVars), ',', '`', '`');
                $values = UTIL_String::arrayToDelimitedString($paramNames, ',', ':');
                $sqlList .= 'INSERT' . ($delayed ? ' DELAYED' : '') . " INTO `{$tableName}` ({$columns}) VALUES ({$values});";

                $paramNames = [];
                ++$number;
            }
        }

        $this->execute($sqlList, $params)->closeCursor();

        return (int) $this->connection->lastInsertId();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function updateObject(
        string $tableName,
        OW_Entity $obj,
        string $primaryKeyName = 'id',
        bool $lowPriority = false
    ) {
        if ($obj !== null && is_object($obj)) {
            $params = get_object_vars($obj);

            if (!array_key_exists($primaryKeyName, $params)) {
                throw new InvalidArgumentException('object property not found');
            }

            $fieldsToUpdate = $obj->getEntinyUpdatedFields();

            if (empty($fieldsToUpdate)) {
                return true;
            }

            $updateArray = [];
            foreach ($params as $key => $value) {
                if ($key !== $primaryKeyName) {
                    if (in_array($key, $fieldsToUpdate)) {
                        $updateArray[] = '`' . $key . '`=:' . $key;
                    } else {
                        unset($params[$key]);
                    }
                }
            }

            $updateStmt = UTIL_String::arrayToDelimitedString($updateArray);
            $sql = 'UPDATE' . ($lowPriority ? ' LOW_PRIORITY' : '') . " `{$tableName}` SET {$updateStmt} WHERE {$primaryKeyName}=:{$primaryKeyName}";
            return $this->update($sql, $params);
        }


        throw new InvalidArgumentException('object expected');
    }

    public function updateObjectList(
        string $tableName,
        array $objList,
        string $primaryKeyName = 'id',
        bool $lowPriority = false
    ): PDOStatement|bool {
        $sqlList = '';
        $number = 1;
        $updatedParams = [];

        foreach ($objList as $obj) {
            if ($obj !== null && is_object($obj)) {
                $params = get_object_vars($obj);

                if (!array_key_exists($primaryKeyName, $params)) {
                    continue;
                }

                $fieldsToUpdate = $obj->getEntinyUpdatedFields();

                if (empty($fieldsToUpdate)) {
                    continue;
                }

                $updateArray = [];
                foreach ($params as $key => $value) {
                    if ($key !== $primaryKeyName) {
                        if (in_array($key, $fieldsToUpdate)) {
                            $updateArray[] = "`$key`=:{$key}_$number";
                            $updatedParams["{$key}_$number"] = $value;
                        } else {
                            unset($params[$key]);
                        }
                    } else {
                        $updatedParams["{$primaryKeyName}_$number"] = $value;
                    }
                }

                $updateStmt = UTIL_String::arrayToDelimitedString($updateArray);
                $sqlList .= 'UPDATE' . ($lowPriority ? ' LOW_PRIORITY' : '') . " `{$tableName}` SET {$updateStmt} WHERE {$primaryKeyName}=:{$primaryKeyName}_$number;";

                ++$number;
            }
        }

        if (empty($sqlList)) {
            return false;
        }

        return $this->execute($sqlList, $updatedParams);
    }

    public function mergeInClause(array $valueList): string
    {
        if ($valueList === null) {
            return '';
        }

        $result = '';
        foreach ($valueList as $value) {
            $result .= ('\'' . $this->escapeString($value) . '\',');
        }

        return mb_substr($result, 0, mb_strlen($result) - 1);
    }

    public function batchInsertOrUpdateObjectList(string $tableName, $objects, int $batchSize = 50): void
    {
        if ($objects !== null && is_array($objects)) {
            if (count($objects) > 0) {
                $columns = '';
                $paramNames = [];

                if (is_object($objects[0])) {
                    $params = get_object_vars($objects[0]);
                    $paramNames = array_keys($params);
                    $columns = UTIL_String::arrayToDelimitedString($paramNames, ',', '`', '`');
                } else {
                    throw new InvalidArgumentException('Array of objects expected');
                }

                $i = 0;
                $totalInsertsCount = 0;
                $objectsCount = count($objects);
                $inserts = [];

                foreach ($objects as $obj) {
                    $values = '(';
                    foreach ($paramNames as $property) {
                        if ($obj->$property !== null) {
                            $values .= ('\'' . $this->escapeString($obj->$property) . '\',');
                        } else {
                            $values .= 'NULL,';
                        }
                    }
                    $values = mb_substr($values, 0, mb_strlen($values) - 1);
                    $values .= ')';
                    $inserts[] = $values;

                    ++$i;
                    ++$totalInsertsCount;

                    if ($i === $batchSize || $totalInsertsCount === $objectsCount) {
                        $sql = "REPLACE INTO `$tableName` ($columns) VALUES" . implode(',', $inserts);
                        $inserts = [];
                        $i = 0;
                        $this->execute($sql)->closeCursor();
                        //$this->connection->query($sql)->closeCursor();
                    }
                }
            }
        } else {
            throw new InvalidArgumentException('Array expected');
        }
    }

    public function escapeString(?string $string): string
    {
        $quotedString = $this->connection->quote($string ?? ''); // real_escape_string( $string );
        return mb_substr($quotedString, 1, mb_strlen($quotedString) - 2); //dirty hack to delete quotes
    }
    /*     * 206.123.0
     * Returns affected rows
     *
     * @return integer
     */

    public function getAffectedRows(): int
    {
        return $this->affectedRows;
    }

    /**
     * Returns last insert id
     *
     * @return integer
     */
    public function getInsertId(?string $seqname = null)
    {
        return $this->connection->lastInsertId($seqname);
    }

    /**
     * Class destruct actions
     */
    public function __destruct()
    {
        if (isset($this->connection)) {
            $this->connection = null;
        }
    }

    /**
     * Returns current PDOStatement
     *
     * @return PDOStatement
     */
    private function execute(string $sql, ?array $params = null)
    {
        if ($this->isProfilerEnabled) {
            $this->profiler->reset();
        }

        /* @var $stmt PDOStatement */
        $stmt = $this->connection->prepare($sql);
        if ($params !== null) {
            foreach ($params as $key => $value) {
                $paramType = PDO::PARAM_STR;
                if (is_int($value)) {
                    $paramType = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $paramType = PDO::PARAM_BOOL;
                }

                $stmt->bindValue(is_int($key) ? $key + 1 : $key, $value, $paramType);
            }
        }
        OW::getEventManager()->trigger(new OW_Event('core.sql.exec_query', ['sql' => $sql, 'params' => $params]));
        $stmt->execute(); //TODO setup profiler
        $this->affectedRows = $stmt->rowCount();

        if ($this->isProfilerEnabled) {
            $this->queryExecTime = $this->profiler->getTotalTime();
            $this->totalQueryExecTime += $this->queryExecTime;

            ++$this->queryCount;
            $this->queryLog[] = ['query' => $sql, 'execTime' => $this->queryExecTime, 'params' => $params];
        }

        return $stmt;
    }

    /**
     * Check if MySQL version is 8+
     */
    private function isMysqlValidVersion(): bool
    {
        $verArray = explode('.', $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION));
        return intval($verArray[0]) >= 8;
    }

    /**
     * Set additional MySQL server settings
     */
    private function prepareMysql(): void
    {
        if ($this->connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $verArray = explode('.', $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION));

            if (intval($verArray[0]) === 5 && intval($verArray[1]) >= 7 && intval($verArray[2]) >= 9) {
                $this->connection->exec('SET SESSION sql_mode = ""');
            }
        }
    }

    private function getCacheKeyForQuery(string $query, $params): string
    {
        return 'core.sql.' . md5(trim($query) . serialize($params));
    }

    private function cacheEnabled($expTime): bool
    {
        return !OW_DEV_MODE && $this->useCashe && ($expTime === false || $expTime > 0);
    }

    private function getCacheManager(): OW_CacheManager
    {
        return OW::getCacheManager();
    }

    private function getFromCache(string $sql, $params, int $cacheLifeTime)
    {
        if ($this->cacheEnabled($cacheLifeTime)) {
            $cacheKey = $this->getCacheKeyForQuery($sql, $params ? $params : []);
            $cacheData = $this->getCacheManager()->load($cacheKey);

            if ($cacheData !== null) {
                return unserialize($cacheData);
            }
        }

        $data = OW::getEventManager()->call('core.sql.get_query_result', ['sql' => $sql, 'params' => $params]);

        if (is_array($data) && isset($data['result']) && $data['result'] === true) {
            return $data['value'];
        }

        return self::NO_CACHE_ENTRY;
    }

    private function saveToCache($result, string $sql, $params, int $cacheLifeTime, array $tags): void
    {
        if ($this->cacheEnabled($cacheLifeTime)) {
            $cacheKey = $this->getCacheKeyForQuery($sql, $params ? $params : []);
            $this->getCacheManager()->save(serialize($result), $cacheKey, $tags, $cacheLifeTime);
        }

        OW::getEventManager()->trigger(
            new OW_Event(
                'core.sql.set_query_result',
                [
                    'sql' => $sql,
                    'params' => $params,
                    'result' => $result
                ]
            )
        );
    }
}
