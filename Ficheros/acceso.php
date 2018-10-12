<?php
	session_start();
	if (isset($_SESSION['id'])){
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Página Principal</title>
<?php
	echo "<script>var sesion_id='".$_SESSION['id']."';</script>";
?>
	<script>
		// Muestra la cámara de vigilancia.
		function verCamara(){
			var salida = "<div class='camara'>" +
				"<img src=\"http://proyecto-asir.ddns.net:8081\" width='520' height='390'>" +
				"</div>" +
				"<div class='configuracion'>" +
				"<table>" +
<?php
	if ($_SESSION['id'] == "1"){
?>
				"<tr><td>Brillo </td><td size='50'><input type='text' id='brillo'></td></tr>" +
				"<tr><td>Contraste </td><td size='50'><input type='text' id='contraste'></td></tr>" +
				"<tr><td>Saturación </td><td size='50'><input type='text' id='saturacion'></td></tr>" +
<?php
	}else{
?>
				"<tr><td>Brillo </td><td size='50'><input type='text' id='brillo' disabled></td></tr>" +
				"<tr><td>Contraste </td><td size='50'><input type='text' id='contraste' disabled></td></tr>" +
				"<tr><td>Saturación </td><td size='50'><input type='text' id='saturacion' disabled></td></tr>" +
<?php
	}
?>
				"</table><br>" +
				"<button class='button' style='vertical-align:middle' onclick=\"Capturar('privadas')\">" +
				"<span>Captura Privada</span>" +
				"<button class='button' style='vertical-align:middle' onclick=\"Capturar('publicas')\">" +
				"<span>Captura Pública</span>" +
<?php
	if ($_SESSION['id'] == "1"){
?>
				"<button class='button' style='vertical-align:middle' onclick=\"saveConfig()\">" +
				"<span>Guardar</span>" +
<?php
	}
?>
				"</div>";
			
			document.getElementById("seleccion").innerHTML = salida;
			verConfig();
		}
		// Muestra la configuración de Brillo, Contraste y Saturación de la cámara.
		function verConfig(){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					var configuracion = JSON.parse(this.responseText);
					document.getElementById("brillo").value = configuracion[0];
					document.getElementById("contraste").value = configuracion[1];
					document.getElementById("saturacion").value = configuracion[2];
				}
			}
			xhr.open("POST","ver_configuracion.php");
			xhr.send();
		}
		function saveConfig(){
			var brillo = parseInt(document.getElementById("brillo").value);
			var contraste = parseInt(document.getElementById("contraste").value);
			var saturacion = parseInt(document.getElementById("saturacion").value);
			
			var xhr = new XMLHttpRequest();
			xhr.open("POST","guardar_configuracion.php");
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send("brillo="+brillo+"&contraste="+contraste+"&saturacion="+saturacion);
		}
		// Realiza una captura de la cámara.
		function Capturar(seleccion){
			var xhr = new XMLHttpRequest();
			xhr.open("POST","capturar.php");
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send("captura="+seleccion);
		}
		// Muestra imágenes propias o públicas dependiendo del botón pulsado.
		function verImagenes(seleccion){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("seleccion").innerHTML = "";
					var imagenes = JSON.parse(this.responseText);
					var salida = "";
					// Una vez disponemos de las imagenes, las mostramos por pantalla.
					for (i = 0;i < imagenes.length; i++){
						salida += "<div class='responsive'>";
						salida += "<div class='gallery'>";
						salida += "<img src='"+imagenes[i]+"' width='300' height='200'>";
						
						salida += "<div class='desc'>";
						salida += formatoFecha(imagenes[i]);
						salida += "</div>";
						
						if (seleccion == "propias"){
							if (imagenes[i].includes("privadas")){
								salida += "<input type='image' class='corner_publico' ";
								salida += "src='./imagenes/nopublico.png' height='22' width='22' ";
								salida += "onclick='Publicar(this)' name='"+imagenes[i]+"'/>";
							}else{
								salida += "<input type='image' class='corner_publico' ";
								salida += "src='./imagenes/publico.png' height='22' width='22' ";
								salida += "onclick='Publicar(this)' name='"+imagenes[i]+"'/>";
							}
							salida += "<input type='image' class='corner_eliminar' src='./imagenes/eliminar.png' height='22' width='22' onclick='bImagen(this)' name='"+imagenes[i]+"'/>";
						}
						salida += "</div>";
						salida += "</div>";
					}
					document.getElementById("seleccion").innerHTML = salida;
				}
			}
			xhr.open("POST","listado_imagenes.php");
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send("seleccion="+seleccion);
		}
		// Prepara el formato para la fecha y hora que aparecerá en la descripción de la imagen.
		function formatoFecha(ruta){
			var salida = "";
			
			var array_ruta = ruta.split("/");
			var nombre = array_ruta[array_ruta.length -1];
			var array_nombre = nombre.split("-");
			var fecha = array_nombre[array_nombre.length -1];

			salida = fecha.substring(8,10) + ":" + fecha.substring(10,12) + ":" + fecha.substring(12,14);
			salida += " - " + fecha.substring(6,8) + "/" + fecha.substring(4,6);
			salida += "/" + fecha.substring(0,4);
			
			return salida;
		}
		// Elimina la imagen seleccionada.
		function bImagen(imagen){
			var ruta = imagen.name;
			var xhr = new XMLHttpRequest();
			xhr.open("POST","eliminar_imagen.php");
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send("ruta="+ruta);
			
			verImagenes("propias");
		}
		// Cambiamos de directorio la imagen para que en vez de privada sea pública o viceversa.
		function Publicar(imagen){
			var ruta = imagen.name;
			if (ruta.includes("privadas")){
				var nueva_ruta = ruta.replace("privadas","publicas");
				imagen.src = './imagenes/publico.png';
			}else{
				var nueva_ruta = ruta.replace("publicas","privadas");
				imagen.src = './imagenes/nopublico.png';
			}
			var xhr = new XMLHttpRequest();
			xhr.open("POST","publicar_imagen.php");
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send("ruta="+ruta+"&nueva_ruta="+nueva_ruta);
			
			imagen.name = nueva_ruta;
		}
		//Muestra los vídeos de los eventos sobre detección de movimiento.
		function verEventos(){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					var videos = JSON.parse(this.responseText);
					var salida = "";
					for (i = 0;i < videos.length; i++){
						salida += "<video width='320' height='240' controls>"+
  							"<source src='"+videos[i]+"' type='video/mp4'>"+
  							"Your browser does not support the video tag.</video>"
					}
					document.getElementById("seleccion").innerHTML = salida;
				}
			}
			xhr.open("POST","eventos.php");
			xhr.send();
		}
		// Carga las capturas programadas del archivo programacion.txt.
		function verTareas(){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
						var auxiliar = this.responseText;
						document.getElementById("seleccion").innerHTML = auxiliar;
						var fichero = auxiliar.split("-");
						mostrarTabla();
						mostrarProgramacion(fichero);
					}
				}
			xhr.open("POST","programacion.php");
			xhr.send();
		}
		// Crea la tabla que mostrará la programación de capturas.
		function mostrarTabla(){
			var salida = "<center><h2>Capturas Programadas</h2></center>";
			
			salida += "<table id='mitabla'>";
			salida += "<tr>";
			salida += "<th></th>"
			for (i = 0; i < 24; i++){
				if (i < 10){
					salida += "<th>0" + i + "</th>";
				}else{
					salida += "<th>" + i + "</th>";
				}
			}
			salida += "</tr>";
			
			var codigo = "";

			for (i = 0; i <= 45; i+=15){
					salida += "<tr>";

					if (i == 0){
							salida += "<th>00'</th>";
					}else{
							salida += "<th>" + i + "'</th>";
					}

					for (j = 0; j < 24; j++){
						if (j < 10){
							codigo = "0" + j;
						}else{
							codigo += j;
						}
						if (i < 10){
							codigo += "0" + i;
						}else{
							codigo += i;
						}
						salida += "<td id = '" + codigo + "' onclick = 'modificarTarea(this)'></td>";
						codigo = "";
					}
				
					salida += "</tr>";
			}
			salida += "</table>";
			document.getElementById("seleccion").innerHTML = salida;
		}
		// Muestra la programación de capturas en la tabla creada con la función mostrarTabla().
		function mostrarProgramacion(fichero){
			var codigo = "";
			var salida = "";
			for (i = 0; i < fichero.length; i++){
				if (fichero[i].charAt(fichero[i].length - 2) == ":"){
					codigo = fichero[i].split(":");
					document.getElementById(codigo[0]).setAttribute("name","disponible");
					document.getElementById(codigo[0]).innerHTML = "";
				}else if (fichero[i].charAt(fichero[i].length - 2) == "*"){
					codigo = fichero[i].split(":");
					document.getElementById(codigo[0]).setAttribute("name","ocupado");
					document.getElementById(codigo[0]).innerHTML = "X";
				}else{
					codigo = fichero[i].split(":");
					identificadores = codigo[1].split(".");
					if (identificadores.indexOf(sesion_id) != -1){
						document.getElementById(codigo[0]).setAttribute("name","solicitado");
						salida = "<img src='./imagenes/captura.png' height='24' width='24'>";
						document.getElementById(codigo[0]).innerHTML = salida;
					}else{
						document.getElementById(codigo[0]).setAttribute("name","disponible");
						document.getElementById(codigo[0]).innerHTML = "";
					}
				}
			}
		}
		// Solicita o elimina una solicitud de captura del horario seleccionado.
		function modificarTarea(elemento){
			var salida = "";
			
			if (elemento.getAttribute("name") != "ocupado") {
				if (elemento.getAttribute("name") == "disponible"){
					// Cambiar a solicitado.
					elemento.setAttribute("name","solicitado");
					salida = "<img src='./imagenes/captura.png' height='24' width='24'>";
					elemento.innerHTML = salida;
					var solicitar = true;
				}else{
					// Cambiar a disponible.
					elemento.setAttribute("name","disponible");
					elemento.innerHTML = "";
					var solicitar = false;
				}

				var xhr = new XMLHttpRequest();
				xhr.open("POST","actualizar.php");
				xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				xhr.send("solicitar="+solicitar+"&"+"codigo="+elemento.id);
			}
		}
		// Muestra una tabla con los usuarios registrados en la página. Sólo disponible para el administrador.
		function verUsuarios(){
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
						document.getElementById("seleccion").innerHTML = "";
						var usuarios = JSON.parse(this.responseText);
						var salida = "<center><h2>Usuarios del Sistema</h2></center>";
						salida += "<table id='mitabla' border=1>"+
							"<tr>"+
							"<th>ID</th>"+
							"<th>Nombre</th>"+
							"<th>Correo Electrónico</th>"+
							"<th>¿Contraseña?</th>"+
							"<th>¿Borrar?</th>"+
							"</tr>";
						for (var i=0;i<usuarios.length;i++){
							salida += "<tr>"+
							"<td>"+usuarios[i].id+"</td>"+
							"<td contenteditable='true'>"+usuarios[i].nombre+"</td>"+
							"<td contenteditable='true'>"+usuarios[i].correo+"</td>"+
							"<td><button class='button' style='vertical-align:middle' onclick='camContra(this)'><span>Cambiar Contraseña</span></td>" +
							"<td><button class='button' style='vertical-align:middle' onclick='Borrar(this)'><span>Borrar</span></td>" +
							"</tr>";
						}
						salida += "</table>";
						salida += "<br>";
						salida += "<button class='button' style='vertical-align:middle' onClick='Nuevo()'><span>Nuevo</span>";
						salida += "<button class='button' style='vertical-align:middle' onClick='Guardar()'><span>Guardar</span>";
						document.getElementById("seleccion").innerHTML = salida;
					}
				}
			xhr.open("POST","listado_usuarios.php");
			xhr.send();
		}
		// Inserta un nuevo registro en la tabla de usuarios.
		function Nuevo(){
			var table = document.getElementById("mitabla");
			var row = table.insertRow(table.rows.length);
			
			row.name = "nuevo_usuario";
			row.innerHTML = "<td>0</td>"+
				"<td contenteditable='true'>Untitled</td>"+
				"<td contenteditable='true'>undefined</td>"+
				"<td></td>"+
				"<td><button onclick='Borrar(this)'>Borrar</button></td>";
		}
		// Elimina un usuario de la tabla de usuarios y de la base de datos.
		function Borrar(row){
			if(confirm("¿Quiere eliminar al usuario?")){
				var table = document.getElementById("mitabla");
				var index = row.parentNode.parentNode.rowIndex;

				if (table.rows[index].cells[0].innerHTML != "#"){
					var id = table.rows[index].cells[0].innerHTML;

					var xhr = new XMLHttpRequest();
					xhr.open("POST","eliminar_usuario.php");
					xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					xhr.send("id="+id);
				}

				table.deleteRow(index);
			}
		}
		// Actualiza la información de los usuarios en la base de datos con los de la tabla.
		function Guardar(){
			var xhr = [], salida;
			var table = document.getElementById("mitabla");

			for (var i=1;i<table.rows.length;i++){
				xhr[i-1] = new XMLHttpRequest();
				xhr[i-1].open("POST","guardar_usuarios.php");
				xhr[i-1].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				
				var id = table.rows[i].cells[0].innerHTML;
				var nombre = table.rows[i].cells[1].innerHTML;
				var correo = table.rows[i].cells[2].innerHTML;
				
				xhr[i-1].send("id="+id+"&nombre="+nombre+"&correo="+correo);
			}

			verUsuarios();
		}
		// Cambia la contraseña de un usuario ya existentes en la base de datos.
		function camContra(row){
			pass = prompt('Nueva Contraseña:','');

			if (pass != null){
				var table = document.getElementById("mitabla");
				var index = row.parentNode.parentNode.rowIndex;
				var id = table.rows[index].cells[0].innerHTML;
				
				var xhr = new XMLHttpRequest();
				xhr.open("POST","cambiar_pass.php");
				xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				xhr.send("id="+id+"&"+"pass="+pass);
			}
		}
		// Redirige a logout.php que cierra la sesión de usuario.
		function cerrarSesion(){
			location.href ="http://proyecto-asir.ddns.net/logout.php";
		}
	</script>
</head>
<body>
<div class="bg"><h1>Prying Eyes</h1></div>
<hr>
<div class="bt" id="selectores">
	<button class="button" style="vertical-align:middle" onclick="verCamara()"><span>Cámara</span>
	<button class="button" style="vertical-align:middle" onclick="verImagenes('propias')"><span>Capturas</span>
	<button class="button" style="vertical-align:middle" onclick="verImagenes('publicas')"><span>Galería</span>
	<button class="button" style="vertical-align:middle" onclick="verTareas()"><span>Tareas</span>
	<button class="button" style="vertical-align:middle" onclick="verEventos()"><span>Eventos</span>

<?php
	if ($_SESSION['id'] == "1"){
?>
	<button class="button" style="vertical-align:middle" onclick="verUsuarios()"><span>Configuración</span>
<?php
	}
?>
	<button class="button" style="vertical-align:middle" onclick="cerrarSesion()"><span>Cerrar Sesión</span>
</div>
<hr>
<div id="seleccion"></div>
<div class='footer'>Proyecto Integrado ASIR 2017/2018, por Salvador Fernández Morilla</div>
</body>
</html>

<?php
	}else{
		echo "Usted no está autorizado para ver esta página.";
	}
?>