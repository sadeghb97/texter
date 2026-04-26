<?php
require 'lib/library.php';

$conn = new TexterConnection();
$auth = new TexterAuth();
$auth->logout($conn);
$conn->close()
?>