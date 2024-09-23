<?php
session_start();

//Eliminando las  cookies  en session
setcookie($_SESSION['id'], "", 1);
setcookie($_SESSION['id'], false);
unset($_COOKIE[$_SESSION['id']]);

unset($_SESSION['id']); // Eliminar el id de usuario
session_unset(); //Eliminar todas las sesiones

//Terminar la sesión:
session_destroy();
$parametros_cookies = session_get_cookie_params();
setcookie(session_name(), 0, 1, $parametros_cookies["path"]);


header("location: ../../public/views/sesiones/login.php?sc=1");
