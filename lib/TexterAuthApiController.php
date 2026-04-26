<?php
class TexterAuthApiController extends AuthApiController {
    public function __construct() {
        $conn = new TexterConnection();
        $auth = new TexterAuth();
        parent::__construct($conn, $auth);
    }
}
