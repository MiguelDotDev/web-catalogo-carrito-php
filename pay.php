<!DOCTYPE HTML>
<html>
<head>
<title>Cotizar Carrito</title>
<!-- Custom Theme files -->
<link href="CSS/pay.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="stylesheet" type="text/css" href="CSS/colores.css">
<!-- Custom Theme files -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
</head>
<body>
<div class="bdy">
	<!--coact start here-->
	<div class="form">
	<h1>Consultar Compras</h1>
		<div class="contact-main">
		<form method="POST">
		<h3>Nombres</h3>
		<input type="text" placeholder="Tu Nombre" class="hola"  name="customer_name" autocomplete="off" required />
		<h3>Apellidos</h3>
		<input type="text" placeholder="Tu Nombre" class="hola"  name="customer_lastname" autocomplete="off" required />
		<h3>Numero de Telefono</h3>
		<input type="text" maxlength="10" name="customer_phone" placeholder="Tu Telefono" class="hola" required autocomplete="off" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"/>
			<?php
				if (isset($_POST['send'])){
					include("sendemail.php");//Mando a llamar la funcion que se encarga de enviar el correo electronico
					
					/*Configuracion de variables para enviar el correo*/
					$mail_username="";//Correo electronico saliente ejemplo: tucorreo@gmail.com
					$mail_userpassword="";//Tu contraseña de gmail
					$mail_addAddress="";//correo electronico que recibira el mensaje
					$template="";//Ruta de la plantilla HTML para enviar nuestro mensaje
					
					/*Inicio captura de datos enviados por $_POST para enviar el correo */
					$mail_setFromEmail=$_POST['customer_email'];
					$mail_setFromName=$_POST['customer_name'];
					$mail_subject="Carrito de Compras ".$_POST['customer_email'];
					$TELEFONO = $_POST['customer_phone'];
					
					sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template, $TELEFONO);
				}
			?>
		</div>
		<div class="enviar">
			<div class="contact-check">
				
			</div>
			<div class="contact-enviar">
			<input type="submit" value="Consultar" name="send" class="btn_send">
			</div>
			<div class="clear"> </div>
			</form>
	</div>
	</div>
</div>
</body>
</html>