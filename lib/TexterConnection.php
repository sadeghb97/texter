<?php

use Avetify\DB\DBConnection;

class TexterConnection extends DBConnection {
    public function getHost(): string {
        return DB_HOST;
    }

    public function getUser(): string {
        return DB_USER;
    }

    public function getPassword(): string {
        return DB_PASSWORD;
    }

    public function getDBName(): string {
        return DB_NAME;
    }
}