<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pagina</title>
	<link rel="stylesheet" type="text/css" href="CSS/Style.css">
	<link rel="stylesheet" type="text/css" href="CSS/fnt.css">
	<link rel="stylesheet" type="text/css" href="CSS/Productos.css">
	<link rel="stylesheet" type="text/css" href="CSS/colores.css">
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
	<nav class="topnav">
		<ul>
			<li><a href="cart.php" class="subm cart_class" id="carrito-num"><ion-icon name="cart" size="large"></ion-icon></a></li>
			<li><form action="" method="POST"><input class="BtnSeek" type="submit" value="Buscar"/><input type="search" class="SeekInput" name="Sk" placeholder="Busca un producto..."/></form></li>
			<li><a href="productos.php" class="subm icon-class topnav-current"><ion-icon name="storefront" size="large"></ion-icon><p class="up">Productos</p></a></li>
		</ul> 
	</nav>

	<form class="filtros_div">
		<ul class="Categorias">
			<li class="C-Element" href="javascript:void(0);" onclick="redireccion_filtros('0')"><a>Accesorios de Computo</a></li>
			<li class="C-Element" href="javascript:void(0);" onclick="redireccion_filtros('1')"><a>Cables</a></li>
			<li class="C-Element" href="javascript:void(0);" onclick="redireccion_filtros('2')"><a>Papeleria</a></li>
			<li class="C-Element" href="javascript:void(0);" onclick="redireccion_filtros('3')"><a>Redes</a></li>
		</ul>
	</form>

	<script type="text/javascript">
		var Carrito = 0;
		function CarritoFun(valor){
			Carrito = valor;
			console.log(Carrito);
		}
		function producto(id){
			if (Carrito == 0){
				window.location.href = "Producto.php"+"?producto="+id;
			}
		}
		function getParameterByName(name) {
			errorfix=1;
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		function replace_url_param(url, param_name, new_value){
			var base = url.substr(0, url.indexOf('?'));
			var query = url.substr(url.indexOf('?')+1, url.length);
			var a_query = query.split('&');
			for(var i=0; i < a_query.length; i++){
				var name = a_query[i].split('=')[0];
				var value = a_query[i].split('=')[1];
				if (name == param_name) a_query[i] = param_name+'='+new_value;
			}
			return base + '?' + a_query.join('&');
		}
		if(!getParameterByName('page')){ //Cuando no esta en nada la URL
			window.location.href = "productos.php" + "?c1=" + 4 + "&c2=" + 5 + "&page=1";
		}
		function redireccion_filtros(Filtro){
			window.location.href = "productos.php" + "?c1=" + getParameterByName('c1') + "&c2=" + getParameterByName('c2') + "&page=1" + "&Filtro="+Filtro;
		} 
	</script>

	<table class="tabla" id="tabla_id"></table>

	<?php
        $conn = mysqli_connect("localhost", "root", "", "productosaccess");
        // 2) Preparar la orden SQL
		if (isset($_GET['Filtro'])){
			switch($_GET['Filtro']){
				case 0:$filter="Accesorios de Computo";
					break;
				case 1:$filter="Cable";
					break;
				case 2:$filter="Papeleria";
					break;
				case 3:$filter="Redes";
					break;
			}
			$consulta ="SELECT * FROM productos WHERE departamento='".$filter."'";
		} else if (isset($_POST['Sk'])){
			$SeekConsulta = $_POST['Sk'];
			$consulta ="SELECT * FROM productos WHERE descripcion LIKE '%$SeekConsulta'";
		} else {
			$consulta ="SELECT * FROM productos WHERE 1";
		}
		$Columnas = $_GET['c1'];
		$Filas = $_GET['c2'];
		$FilasTotal=$Columnas*$Filas;
		$Registros = 0;
		$IdPage=$_GET['page'];

		$RegistroIdStart = ($FilasTotal*$IdPage)-$FilasTotal;

		$columnaCount = 0;
		$filaId = 0;
		$datos = mysqli_query($conn, $consulta);
		$sqlCar = mysqli_query($conn, "SELECT * FROM cart WHERE 1");


		$CantidadEnCarro=0;
		while ($Carro = mysqli_fetch_array($sqlCar)){
			$CantidadEnCarro++;
		}

		echo "<script type=\"text/javascript\">";
		echo 'document.getElementById("carrito-num").innerHTML += `';
		echo '<p class="up">'.$CantidadEnCarro.'</p>`</script>';

		echo "<script type=\"text/javascript\">";
		echo 'document.getElementById("tabla_id").innerHTML += `';
		echo '<tr id="'.$filaId.'F">`;</script>';
		while($RegistroIdStart>0){
			mysqli_fetch_array($datos);
			$RegistroIdStart--;
		}
		if ($RegistroIdStart==0){
			while ($fila=mysqli_fetch_array($datos) and $Registros<=$FilasTotal-1){
				if ($columnaCount<$Columnas){
					$columnaCount++;
					echo "<script type=\"text/javascript\">";
					echo 'document.getElementById("'.$filaId.'F").innerHTML += `';
					echo '<td id="'.$fila["id_producto"].'P"><div onclick="producto('.$fila["id_producto"].')" class="producto">';
					echo '<img class="p_img" src="IMG/PRODUCTOS/'.$fila["codigo"].'/1.jpeg">';
					echo '<div class="p_txt"><h1 class="p_titulo">' .$fila["descripcion"]. '</h1>';
					echo '<div onclick="Add2Cart(this)" onmouseleave="CarritoFun(0)" onmouseenter="CarritoFun(1)" id="'.$fila["id_producto"].'" class="btn-add-cart"><ion-icon name="cart-outline" size="large"></ion-icon></div></div>';
					echo '<div class="p_money"><center><h1 class="p_moneyValue">$'.$fila["precio"].'</h1></center></div></div></td>`;</script>';
				} else {
					echo "<script type=\"text/javascript\">";
					echo 'document.getElementById("'.$filaId.'F").innerHTML += `</tr>`;</script>';
					$filaId += 1;
					$columnaCount = 0;
					$columnaCount++;
					echo "<script type=\"text/javascript\">";
					echo 'document.getElementById("tabla_id").innerHTML += `';
					echo '<tr id="'.$filaId.'F">`;</script>';

					echo "<script type=\"text/javascript\">";
					echo 'document.getElementById("'.$filaId.'F").innerHTML += `';
					echo '<td id="'.$fila["id_producto"].'P"><div onclick="producto('.$fila["id_producto"].')" class="producto">';
					echo '<img class="p_img" src="IMG/PRODUCTOS/'.$fila["codigo"].'/1.jpeg">';
					echo '<div class="p_txt"><h1 class="p_titulo">' .$fila["descripcion"]. '</h1>';
					echo '<div onclick="Add2Cart(this)" onmouseleave="CarritoFun(0)" onmouseenter="CarritoFun(1)" id="'.$fila["id_producto"].'" class="btn-add-cart"><ion-icon name="cart-outline" size="large"></ion-icon></div></div>';
					echo '<div class="p_money"><center><h1 class="p_moneyValue">$'.$fila["precio"].'</h1></center></div></div></td>`;</script>';
				}
				$Registros++;
			}
		}
		if (isset($_GET['Filtro'])){
			switch($_GET['Filtro']){
				case 0:$filter="Accesorios de Computo";
					break;
				case 1:$filter="Cable";
					break;
				case 2:$filter="Papeleria";
					break;
				case 3:$filter="Redes";
					break;
			}
			$sqlPagesTotal = mysqli_query($conn, "SELECT * FROM productos WHERE departamento='".$filter."'");
		} else {
			$sqlPagesTotal = mysqli_query($conn, "SELECT * FROM productos WHERE 1");
		}
		$CountProductTotal = 0;
		while ($LimPageT = mysqli_fetch_array($sqlPagesTotal)){
			$CountProductTotal++;
		}
		$PageIdFinal = ceil($CountProductTotal/$FilasTotal);
	?>

	<script type="text/javascript">
		function Add2Cart(comp){
			var URL = window.location.href;
			let id = comp.id;

			//PARA EL CAMBIO DE CLASE CSS DEL PRODUCTO AL SELECCIONARLO
			var IdBuscado = id+"P";
			var Producto = document.getElementById(IdBuscado);
			Producto.classList.add("producto-selected"); //Añade la clase del CSS al producto del carrito selecconado.

			window.location.href = window.location.href + "&w1=" + id; //Cambio de URL
			<?php
				if (isset($_GET["w1"])){
					$idSeek = $_GET['w1'];
					$consulta = "SELECT * FROM cart WHERE producto_id=".$idSeek;
					$conn = mysqli_connect("localhost", "root", "", "productosaccess");
					$QuertyExec = mysqli_query($conn, $consulta);
					if($datosConsulta=mysqli_fetch_array($QuertyExec)){
						//SUMAR A REGISTRO EXISTENTE
						$ADD=$datosConsulta['cantidad']+1;
						$consultaSumatoria="UPDATE cart SET cantidad=".$ADD." WHERE producto_id=".$idSeek;
						mysqli_query($conn, $consultaSumatoria);
					} else {
						//AÑADIR REGISTRO NUEVO
						$consultaNuevo="INSERT INTO cart (producto_id, cantidad) VALUES (".$idSeek.", 1)";
						mysqli_query($conn, $consultaNuevo);
					}
				}
			?>
		}

		function SetColumnAndRow(){
			var Fila = document.getElementById("filas_id_in").value;
			var Columna= document.getElementById("columnas_id_in").value;
			window.location.href = "productos.php?c1="+Fila+"&c2="+Columna+"&page="+getParameterByName('page');
		}

		if(getParameterByName('w1')){
			var Fila = getParameterByName('c1');
			var Columna= getParameterByName('c2');
			window.location.href = "productos.php" + "?c1=" +Fila+ "&c2=" +Columna+"&page="+getParameterByName('page');;
		}

		function CambiarPagina(Page){//TRUE Adelante False Atras
			var Fila = getParameterByName('c1');
			var Columna= getParameterByName('c2');
			var PageActual = parseInt(getParameterByName("page"));
			if (Page && PageActual<<?php echo $PageIdFinal ?>){
				window.location.href=replace_url_param(window.location.href, 'page', (parseInt(getParameterByName('page'))+1));
			} else if (!Page && PageActual>1){
				window.location.href=replace_url_param(window.location.href, 'page', (parseInt(getParameterByName('page'))-1));
			}
		}
	</script>

	<div class="div_pages">
		<a href="javascript:void(0);" onclick="CambiarPagina(false)" class="btn_page">Anterior</a>
		<h1 id="tituloPage" class="txt_showPage"></h1>
		<a href="javascript:void(0);" onclick="CambiarPagina(true)" class="btn_page">Siguiente</a>
	</div>

	<script>
		document.getElementById("tituloPage").innerHTML = "Pagina "+getParameterByName('page');
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