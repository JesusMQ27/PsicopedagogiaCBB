<?php

session_start();
session_destroy();
$_SESSION["psi_user"] = "";
header("Cache-Control: private");
header("Location: ../index.php");
?>