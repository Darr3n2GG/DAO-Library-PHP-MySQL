<?php

use PHPUnit\Framework\TestCase;

class DAOTest extends TestCase {
    private $DAO;

    protected function setUp(): void {
        $this->DAO = new DAO("localhost", "root", "", "daotest");
    }

    public function testConnectionInvalid(): void {
        $this->expectException(Error::class);
        $invalidDAO = new DAO("", "", "", "");
    }

    public function testCreateWithInvalidQuery(): void {
        $this->expectException(Error::class);
        $invalidQuery = "";
        $this->DAO->create($invalidQuery, []);
    }
}
