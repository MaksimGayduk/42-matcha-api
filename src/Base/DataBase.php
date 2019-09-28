<?php

namespace App\Base;

class DataBase
{
    private $pdo;
    private static $dbInstance;

    private function __construct(array $config=null)
    {
        $this->pdo = $this->getConnection($config);
    }

    public static function getInstance($config): DataBase
    {
        if (!self::$dbInstance)
            self::$dbInstance = new self($config);
        return self::$dbInstance;
    }

    private function getConnection(array $config=null): \PDO
    {
        $driver     = $config['driver'];
        $host       = $config['host'];
        $dbname     = $config['dbname'];
        $user       = $config['user'];
        $password   = $config['password'];
        $dsn = "{$driver}:host={$host};dbname={$dbname};user={$user};password={$password}";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return (new \PDO($dsn, $user, $password, $options));
    }

    public function executeQuery(string $query, array $queryParams = null): ?array
    {
       $res = $this->pdo->prepare($query);
       $res->execute($queryParams);
       return $res->fetchAll();
    }

    public function getNewRecord(string $mainEntityName): ?array
    {
        $id = $this->pdo->lastInsertId();
        $query = "SELECT * FROM {$mainEntityName} WHERE id = :id";

        $res = $this->executeQuery($query, ['id' => $id]);

        return $res;
    }
}
