<?php

use PHPUnit\Framework\TestCase;

class DAOTest extends TestCase {
    protected $DAO;

    protected function setUp(): void {
        $this->DAO = new DAO("localhost", "root", "", "daotest");
    }

    protected function tearDown(): void {
        $queryData = new Query;
        $queryData->table = "test";
        $queryData->columns = ["id"];
        $this->DAO->delete($queryData, []);
    }

    public function testConnectionInvalid(): void {
        $this->expectException(Error::class);
        $invalidDAO = new DAO("", "", "", "");
    }

    public function testCreateWithInvalidQuery(): void {
        $this->expectException(Error::class);
        $invalidQuery = new Query;
        $this->DAO->create($invalidQuery, []);
    }

    public function testCreateWithValidQuery(): void {
        $expected = 1;

        $queryData = new Query;
        $queryData->table = "test";
        $queryData->columns = ["id"];
        $this->DAO->create($queryData, [1]);

        $queryData->conditions = new Conditions(
            new OperatorCondition("id", "=")
        );
        $column = $this->DAO->read($queryData, [1]);
        $result = $column["id"];

        $this->assertEquals($expected, $result);
    }
}
