<?php

trait DatabaseTrait
{
    protected function executeQuery(mysqli $connection,string $query,string $types = '',array $params = []): mysqli_stmt 
    {

        $statement = $connection->prepare($query);

        if (!$statement) {
            throw new Exception($connection->error);
        }

        if (!empty($params)) {
            $statement->bind_param($types, ...$params);
        }

        $statement->execute();

        return $statement;
    }
}