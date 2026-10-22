<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pagina</title>
	<link rel="stylesheet" type="text/css" href="CSS/Style.css">
	<link rel="stylesheet" type="text/css" href="CSS/fnt.css">
	<link rel="stylesheet" type="text/css" href="CSS/Producto_Php.css">
	<link rel="stylesheet" type="text/css" href="CSS/colores.css">
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

	<?php
		if (isset($_GET["producto"])){
			$productoId = $_GET['producto'];
			$conn = mysqli_connect("localhost", "root", "", "productosaccess");
			$consulta = "SELECT * FROM productos WHERE id_producto=".$productoId;
			$datos = mysqli_query($conn, $consulta);
			$array_result = mysqli_fetch_array($datos);

			$sqlCar = mysqli_query($conn, "SELECT * FROM cart WHERE 1");
			$CantidadEnCarro=0;
			while ($Carro = mysqli_fetch_array($sqlCar)){
				$CantidadEnCarro++;
			}
			
			if (isset($_GET["crt"])){
				$productoCRT = $_GET['crt'];
				$conn = mysqli_connect("localhost", "root", "", "productosaccess");
				$SqlAdd2Cart = "SELECT * FROM cart WHERE producto_id=".$_GET["producto"];
				$Consulta1 = mysqli_query($conn, $SqlAdd2Cart);

				if ($Consulta2 = mysqli_fetch_array($Consulta1)) {
					header("Location: cart.php");
				} else {
					$SqlAdd2CartINSERT = "INSERT INTO cart (producto_id, cantidad) VALUES (".$_GET["producto"].", 1)";
					mysqli_query($conn, $SqlAdd2CartINSERT);
				}
				header("Location: cart.php");
			}
		}
	?>

<body>
	<nav class="topnav">
		<ul>
			<li><a href="cart.php" class="subm cart_class" id="carrito-num"><ion-icon name="cart" size="large"></ion-icon><p class="up"><?php echo $CantidadEnCarro?></p></a></li>
			<li><a href="productos.php" class="subm icon-class topnav-current"><ion-icon name="storefront" size="large"></ion-icon><p class="up">Productos</p></a></li>
		</ul> 
	</nav>

	<div class="p_body">
		<div class="datos">

			<div class="div_img">
				<div class="img_buttons">
					<img class="img_p_element" onclick="CambiarImagen(1)" src="IMG/PRODUCTOS/<?php echo $array_result['codigo']?>/1.jpeg">
					<img class="img_p_element" onclick="CambiarImagen(2)" src="IMG/PRODUCTOS/<?php echo $array_result['codigo']?>/2.jpeg">
					<img class="img_p_element" onclick="CambiarImagen(3)" src="IMG/PRODUCTOS/<?php echo $array_result['codigo']?>/3.jpeg">
				</div>
				<img class="img_p" src="IMG/PRODUCTOS/<?php echo $array_result['codigo']?>/1.jpeg" id="img_id">
			</div>

			<div class="texto_p">
				<h1 class="p_titulo" id="Titulo_id"><?php echo $array_result["descripcion"]?></h1>
				<h1 class="p_clasificacion id=" id="p_class_id"><?php echo $array_result["departamento"]?></h1>
			</div>

			<div class="ComprarDiv">
				<h1 class="p_desc" id="precio_id"><?php echo 'Precio: $'.$array_result["precio"] ?></h1>
				<h1 class="p_num_stock" id="disponibilidad_txt"><?php echo $array_result["cantidad"].' Piezas Disponibles'?></h1>
				<h1 class="p_stock next">Cantidad: </h1> <input value="1" type="text" class="next inputCtd" name="ctd" maxlength="4" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"/>
				<div class="botones_class">
					<a href="pay.php" class="btn_buy">Comprar Ahora</a>
					<a href="javascript:Add2CartScript();" class="btn_buy">Agregar Al Carrito</a>
				</div>
			</div>
			
		</div>
	</div>

	<div class="body_similar" id="AddSimilar">
		<h1>Prductos Similares</h1>
	</div>

	<script type="text/javascript">
		function indexFun(){
			window.location.href ="index.html";
		}

		function Add2CartScript(){
			window.location.href = window.location.href + "&crt=1";
		}

		function CambiarImagen(img){
			document.getElementById("img_id").setAttribute('src', "IMG/PRODUCTOS/<?php echo $array_result['codigo']?>/"+img+".jpeg");
		}
	</script>

	<script>
		window.onload=function(){
		var pos=window.name || 0;
		window.scrollTo(0,pos);
		}
		window.onunload=function(){
		window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
		}
	</script>

	<?php
	$sqlSimi = mysqli_query($conn, "SELECT * FROM productos WHERE departamento='".$array_result["departamento"]."'");
	for ($ContarorSimilar=0; $ContarorSimilar<5; $ContarorSimilar++){
		$PRDCT=mysqli_fetch_array($sqlSimi);
		echo "<script type=\"text/javascript\">";
		echo 'document.getElementById("AddSimilar").innerHTML += `';
		echo '<div class="p_similar">';
		echo '<img src="IMG/PRODUCTOS/'.$PRDCT["codigo"].'/1.jpeg"/>';
		echo '<p class="Nombre">'.$PRDCT["descripcion"].'</p>';
		echo '<p class="dinero">$'.$PRDCT["precio"].'</p></div>`;</script>';
	}
	?>
</body>
</html>