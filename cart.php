<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pagina</title>
	<link rel="stylesheet" type="text/css" href="CSS/Style.css">
	<link rel="stylesheet" type="text/css" href="CSS/fnt.css">
	<link rel="stylesheet" type="text/css" href="CSS/cart.css">
	<link rel="stylesheet" type="text/css" href="CSS/colores.css">
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
	<nav class="topnav">
		<ul>
			<li><a href="" class="subm cart_class topnav-current"><ion-icon name="cart" size="large"></ion-icon><p class="up">Carrito</p></a></li>
		</ul>
		<ul>
			<li><a href="productos.php?c1=4&c2=5" class="subm icon-class"><ion-icon name="storefront" size="large"></ion-icon><p class="up">Productos</p></a></li>
		</ul> 
	</nav>
	
	<div>
		<form class="content_Total" id="carrito_total_id">
			<h1 class="comprar_titulo">Carrito</h1>
		</form>
		<div class="content" id="carrito"></div>
	</div>

	<?php
        $conn = mysqli_connect("localhost", "root", "", "productosaccess");
        // 2) Preparar la orden SQL
		$consulta ="SELECT * FROM cart WHERE 1";
		$filaId = 0;
		$Precio=0;
		$datos = mysqli_query($conn, $consulta);

		while ($fila=mysqli_fetch_array($datos)){
			$consultaProductos ='SELECT * FROM productos WHERE id_producto='.$fila["producto_id"].'';
			$datos2 = mysqli_query($conn, $consultaProductos);
			$fila2=mysqli_fetch_array($datos2);
			echo "<script type=\"text/javascript\">";
			echo 'document.getElementById("carrito").innerHTML += `';
			echo '<div class="c_producto" id="'.$fila["producto_id"].'I">';
			echo '<img class="c_img" onclick="Redirigir('.$fila["producto_id"].') style="cursor:pointer" " src="IMG/PRODUCTOS/'.$fila2["codigo"].'/1.jpeg">';
			echo '<div class="c_texto" onclick="Redirigir('.$fila["producto_id"].')" style="cursor:pointer">';
			echo '<h1 class="c_titulo">'.$fila2["descripcion"].'</h1><h2 class="c_precio">Precio: $'.$fila2["precio"].'</h2></div>';
			echo '<div class="c_b_delete" id="'.$fila["cart_id"].'" onclick="eliminarElementoCarro(this)"><ion-icon name="trash-outline" class="delete" size="large"></ion-icon></div>';
			echo '<div class="c_controles">';
			echo '<div class="c_b_ControlUp" onclick="Element(this,0)" id="'.$fila["cart_id"].'"><ion-icon class="arrow_c" name="arrow-up" size="large"></ion-icon></div><h1 class="txt_ctd_p">'.$fila["cantidad"].'</h1>';
			echo '<div class="c_b_ControlDown" onclick="Element(this,1)" id="'.$fila["cart_id"].'"><ion-icon class="arrow_c" name="arrow-down" size="large"></ion-icon></div></div></div>`</script>';
			$Precio = $fila2["precio"]*$fila["cantidad"]+$Precio;
		}

		if (!mysqli_num_rows($datos)){
			echo "<script type=\"text/javascript\">";
			echo 'document.getElementById("carrito_total_id").innerHTML += `';
			echo '<h1 class="comprar_txt_body">No Hay Productos En El Carrito Aún</h1>`;</script>';
		} else {
			echo "<script type=\"text/javascript\">";
			echo 'document.getElementById("carrito_total_id").innerHTML += `';
			echo '<h1 class="comprar_txt_body">Precio Total: $'.$Precio.'</h1>';
			echo '<a class="comprar_btn" href="pay.php">Comprar</a>`;</script>';
		}
	?>

	<script type="text/javascript">
		function indexFun(){
			window.location.href ="index.html";
		}
		function Element(component, value){
			let id = component.id;
			window.location.href = window.location.href + "?w3=" + id + "&w4=" + value;
		}

		<?php
				if (isset($_GET["w3"])){
					$IDB = $_GET["w3"];
					$ifValue = $_GET["w4"];
					$conn = mysqli_connect("localhost", "root", "", "productosaccess");
					$consultaSeek ="SELECT * FROM cart WHERE cart_id=".$IDB."";
					$result=mysqli_query($conn, $consultaSeek);
					$array_result=mysqli_fetch_array($result);

					$consultaCarroProducto=mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM cart WHERE cart_id="'.$IDB.'"'));
					$producto_result=mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM productos WHERE id_producto="'.$consultaCarroProducto["producto_id"].'"'));

					if ($ifValue==0 && $producto_result['cantidad']>$array_result['cantidad']) {
						$valor=$array_result['cantidad']+1;
					} else if ($ifValue==1 && $array_result['cantidad']>1) {
						$valor=$array_result['cantidad']-1;
					} else {
						header("Location: cart.php");
					}

					$consulta_p1_UDT ='UPDATE cart SET cantidad='.$valor.'';
					$consulta_p2_UDT =' WHERE cart_id="'.$IDB.'"';
					$consultaUDT = $consulta_p1_UDT.$consulta_p2_UDT;
					mysqli_query($conn, $consultaUDT);
				}
			?>
	</script>

	<script type="text/javascript">
		function eliminarElementoCarro(comp){
			let id = comp.id;
			window.location.href = window.location.href + "?w1=" + id;

			<?php
				if (isset($_GET["w1"])){
					$IDB = $_GET['w1'];
					$connDEL = mysqli_connect("localhost", "root", "", "productosaccess");
					$consultaDEL ="DELETE FROM cart WHERE cart_id=".$IDB."";
					mysqli_query($connDEL, $consultaDEL);
				}
			?>
		}

		if(!window.location.search==""){
			window.location.href="cart.php";
		}

		function Redirigir(id){
			window.location.href = "Producto.php"+"?producto="+id;
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
</body>
</html>