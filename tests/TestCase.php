<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Start every test from an empty MongoDB test database.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $database = DB::connection('mongodb')->getDatabase();

        if (! str_contains($database->getDatabaseName(), 'test')) {
            throw new RuntimeException('Refusing to drop a database that is not a test database: '.$database->getDatabaseName());
        }

        $database->drop();
    }
}
