<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="contenedor-formularios">

    <h1>Registro de Usuario</h1>
    <?php
    // Mostrar mensaje de error si está configurado
    if (isset($_SESSION['error_registro'])) {
        echo '<div id="mensaje-error" style="display: block; color: red;">' . $_SESSION['error_registro'] . '</div>';
        unset($_SESSION['error_registro']); // Limpiar la variable de sesión
    }
    ?>
    <form action="procesar_registro.php" method="post" onsubmit="return validarFormulario()">
        <label for="nombre">Nombre/s:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" required><br>
        <label for="correo">Correo electrónico:</label><br>
        <input type="email" id="correo" name="correo" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"><br>
        <label for="telefono">Número de teléfono:</label><br>
        <input type="tel" id="telefono" name="telefono" required pattern="[0-9]+" maxlength="10" oninput="validarTelefono(this)"><br>
        <label for="edad">Edad:</label><br>
        <input type="number" id="edad" name="edad" required min="1" max="150" oninput="validarEdad(this)"><br>
        <br>
        <label for="genero">Género:</label><br>
        <select id="genero" name="genero" required onchange="mostrarOtroGenero(this)">
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="otro">Otro</option>
        </select><br>
        <div id="otroGenero" style="display: none;"><br>
            <label for="especificarOtroGenero">Especificar otro género:</label>
            <input type="text" id="especificarOtroGenero" name="especificarOtroGenero">
        </div>
        <br>
        <label for="username">Nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required pattern="[A-Za-z0-9]+" title="El nombre de usuario debe contener al menos una letra mayúscula y/o número (opcional), sin caracteres especiales y sin espacios" onfocus="mostrarMensaje()">
        <span id="mensaje-usuario" style="display: none; color: gray; font-size: 0.8em;">El nombre de usuario debe contener al menos una letra mayúscula y/o número (opcional), sin caracteres especiales y sin espacios</span><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required pattern="^(?=.*[A-Z])(?=.*[0-9]).{8,}$" title="La contraseña debe tener al menos una letra mayuscula, un numero, un signo y sin espacios" onfocus="mostrarMensaje()"><br><br>
        <span id="mensaje-usuario" style="display: none; color: gray; font-size: 0.8em;">La contraseña debe tener al menos una letra mayuscula, un numero, un signo y sin espacios</span><br>
        <input type="submit" class="button button-block" value="Registrarse">
    </form><br>
    <p>¿Ya tienes una cuenta? <a href="index.html">Iniciar sesión</a></p>
    </div>

    <script>
        function validarFormulario() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            // Validar nombre de usuario
            if (username === "") {
                alert("Por favor, ingresa un nombre de usuario.");
                return false;
            }

            // Validar contraseña
            if (!password.match(/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/)) {
                alert("La contraseña debe tener al menos una letra mayúscula y un número, y ser de al menos 8 caracteres de longitud.");
                return false;
            }

            return true;
        }

        function validarEdad(input) {
            var edad = parseInt(input.value);
            
            if (isNaN(edad) || edad < 1 || edad > 150) {
                input.setCustomValidity("La edad debe ser un número entre 1 y 150.");
            } else {
                input.setCustomValidity("");
            }
        }
        
        function mostrarOtroGenero(select) {
            var otroGeneroDiv = document.getElementById("otroGenero");
            var especificarOtroGeneroInput = document.getElementById("especificarOtroGenero");
            
            if (select.value === "otro") {
                otroGeneroDiv.style.display = "block";
                especificarOtroGeneroInput.setAttribute("required", "required");
            } else {
                otroGeneroDiv.style.display = "none";
                especificarOtroGeneroInput.removeAttribute("required");
                especificarOtroGeneroInput.value = ""; // Limpiar el campo si estaba lleno
            }
        }

        function validarTelefono(input) {
            // Eliminar caracteres no numéricos de la entrada
            input.value = input.value.replace(/\D/g, '');

            // Limitar la longitud máxima del número de teléfono
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10);
            }
        }
        
    </script>
</body>
</html>
