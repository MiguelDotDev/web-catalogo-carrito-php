 <?php
function sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject, $template, $TELEFONO){
	require 'PHPMailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->isSMTP();                            // Establecer el correo electrónico para utilizar SMTP
	$mail->Host = 'smtp.gmail.com';             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = $mail_username;          // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail->Password = $mail_userpassword; 		// Tu contraseña de gmail
	$mail->SMTPSecure = 'tls';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = 587;                          // Puerto TCP  para conectarse 
	$mail->setFrom($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe aparecer el correo electrónico. Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente en lugar de la dirección de correo electrónico en sí.
	$mail->addReplyTo($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe responder. El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
	$mail->addAddress($mail_addAddress);   // Agregar quien recibe el e-mail enviado
	$message = str_replace('{{first_name}}', $mail_setFromName, $message);
	$message = str_replace('{{message}}', $txt_message, $message);
	$message = str_replace('{{customer_email}}', $mail_setFromEmail, $message);
	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML
	$mail->Subject = $mail_subject;
	$conn = mysqli_connect("localhost", "root", "", "productosaccess");
	$sqlConsulta = "SELECT * FROM cart WHERE 1";
	$sqlConsulta1AUX = mysqli_query($conn, $sqlConsulta);
	$style_sub = "font-size:20px";
	$style_sub_p = "font-size:15px";

	$mailContent = "<html><body>";
	$mailContent .= "<h1>Carrito de Compras Cliente: ".$mail_setFromName."</h1>";
	$mailContent .= "<center><p style='".$style_sub."'>Lista de Productos Solicitados</p><center><hr>";

	while ($DATOS = mysqli_fetch_array($sqlConsulta1AUX)){
		$DATOS_p = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE id_producto=".$DATOS['producto_id']));
		$mailContent .= "<center><p style='".$style_sub_p."'>".$DATOS_p['descripcion']."</p></center>";
		$mailContent .= "<p>Codigo: ".$DATOS_p['codigo']."</p>";
		$mailContent .= "<p>Departamento: ".$DATOS_p['departamento']."</p>";
		$mailContent .= "<p>Precio Individual del Producto: $".$DATOS_p['precio']."</p>";
		$mailContent .= "<p>Cantidad Solicitada: ".$DATOS['cantidad']."</p>";
		$mailContent .= "<hr>";
	}
	$mailContent .= "<p style='".$style_sub."; padding-top: 30px'>Información del Cliente</p>";
	$mailContent .= "<p>Nombre: ".$mail_setFromName."</p>";
	$mailContent .= "<p>Telefono: ".$TELEFONO."</p>";
	$mailContent .= "</body></html>";

	$mail->Body = $mailContent;

	if(!$mail->send()) {
		echo '<p style="color:red">No se pudo enviar el mensaje..';
		echo 'Error de correo: ' . $mail->ErrorInfo."</p>";
	} else {
		echo '<p style="color:green">Consulta Enviada</p>';
	}
}
?>
