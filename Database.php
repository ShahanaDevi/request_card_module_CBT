<?php

class database
{
    private mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli('localhost', 'root', 'SQL@123', 'card_module');

        if ($this->connection->connect_error) {
            die("Connection Error: " . $this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection->close();
    }
}
