<?php

use Avetify\Auth\AvtAuth;

class TexterAuth extends AvtAuth {
    public function __construct() {
        parent::__construct(AppConfigs::APP_ID, 'users', 'id', 'user_tokens','user_id');
    }
}

