<?php
//Sesion cerrada correctamente
if(isset($_REQUEST['sc'])){ ?>
	<div class="alert alert-success">La sesión a finalizado con éxito!</div>
<?php }

//Error, no existe el correo cuando se intenta loguear
if(isset($_REQUEST['e'])){ ?>
	<div class="alert alert-danger">Error en las credenciales, revise nuevamente!</div>
<?php } 

?>
<!--- FIN DE LOS MENSAJES PERSONALIZADOS -->