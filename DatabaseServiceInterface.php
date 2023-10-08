<?php
interface DatabaseServiceInterface {

    public function executeQuery(string $sql, array $params, string $paramTypes, string $queryType) : mysqli_result | bool;

    public function closeConnection(): void;

    public function getMySqli(): mysqli;
}
