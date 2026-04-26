<?php
use Avetify\Auth\FastAuthApiController;

class TexterAuthApiController extends FastAuthApiController {
    public function __construct() {
        $conn = new TexterConnection();
        $auth = new TexterAuth();
        parent::__construct($conn, $auth);
    }
}
