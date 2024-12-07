<?php
// Data structure for a mysql query

class Query {
    public string $table;
    public array $columns;
    public Conditions $conditions;
    public string $limit;
    public OrderBy $orderBy;
}

class Conditions extends ArrayObject {
    public function __call($function, $argv) {
        if (!is_callable($function) || substr($function, 0, 6) !== 'array_') {
            throw new BadMethodCallException(__CLASS__ . '->' . $function);
        }
        return call_user_func_array($function, array_merge(array($this->getArrayCopy()), $argv));
    }
}

class Condition {
    public $column;
    public $operator;
}

class OperatorCondition extends Condition {
    public function __construct(string $column, string $operator) {
        $this->column = $column;
        $this->operator = $operator;
    }
}

class NullCondition extends Condition {
    public function __construct(string $column) {
        $this->column = $column;
    }
}

class OrderBy {
    const ASC = "ASC";
    const DESC = "DESC";
    public $column;
    public $direction;

    public function __construct(string $column, string $direction) {
        if ($this->checkValidDirection($direction)) {
            throw new Exception("Invalid direction " . $direction . ". Use ASC or DESC.");
        }

        $this->$column = $column;
        $this->direction = $direction;
    }

    private function checkValidDirection($direction) {
        return $direction != OrderBy::ASC || $direction != OrderBy::DESC;
    }
}
