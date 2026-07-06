<?php

use Avetify\DB\DBConnection;

class TexterConnection extends DBConnection {
    public function getHost(): string {
        return $_ENV['DB_HOST'];
    }

    public function getUser(): string {
        return $_ENV['DB_USERNAME'];
    }

    public function getPassword(): string {
        return $_ENV['DB_PASSWORD'];
    }

    public function getDBName(): string {
        return $_ENV['DB_DATABASE'];
    }
}