<?php
session_start();

// Unset all variables and destroy the session
session_unset();
session_destroy();

header("Location: /TAPNLOG/index.php");

exit();
?>
