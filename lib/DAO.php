<?php
// DAO class for CRUD functions.

require_once("QueryBuilder.php");

class DAO {
    private $conn;

    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $database,
    ) {
        try {
            $this->conn = mysqli_connect($hostname, $username, $password, $database);
            if ($this->conn->connect_error) {
                throw new Exception("Failed to connect to MySQL: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function __destruct() {
        try {
            $this->conn->close();
        } catch (Exception $e) {
            exit("Unable to close connection: " . $e->getMessage());
        }
    }

    public function create(Query $queryData, array $params): void {
        $QueryBuilder = new QueryBuilder($queryData);
        $query = $QueryBuilder->create();
        $stmt = $this->executeStatement($query, $params);
        if (!$stmt) {
            throw new Exception("No rows were inserted.");
        }
        $stmt->close();
    }

    public function read(Query $queryData, array $params): array {
        $QueryBuilder = new QueryBuilder($queryData);
        $query = $QueryBuilder->read();
        $stmt = $this->executeStatement($query, $params);
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function update(Query $queryData, array $params): void {
        $QueryBuilder = new QueryBuilder($queryData);
        $query = $QueryBuilder->update();
        $stmt = $this->executeStatement($query, $params);
        if (!$stmt) {
            throw new Exception("No rows were updated.");
        }
        $stmt->close();
    }

    public function delete(Query $queryData, array $params): void {
        $QueryBuilder = new QueryBuilder($queryData);
        $query = $QueryBuilder->delete();
        $stmt = $this->executeStatement($query, $params);
        if (!$stmt) {
            throw new Exception("No rows were deleted.");
        }
        $stmt->close();
    }

    private function executeStatement($query, $params = []): mysqli_stmt {
        try {
            if (!$stmt = $this->conn->prepare($query)) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            echo "Message : " . $e->getMessage();
        }
    }
}
