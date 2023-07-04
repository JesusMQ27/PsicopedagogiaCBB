<?php

session_start();
//session_destroy();
$_SESSION["psi_user"] = "";
//header("Cache-Control: private");
if ($_COOKIE["login_remember"] == "0") {
    setcookie("login_usuario", "", time() - 3600, "/");
    setcookie("usuario_password", "", time() - 3600, "/");
    setcookie("login_remember", "", time() - 3600, "/");
}
header("Location: ../index.php");
?>