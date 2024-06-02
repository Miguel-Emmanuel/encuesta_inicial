<?php
    session_start();
    session_destroy();
    header("location: ../../public/views/sesiones/login.php")
?>