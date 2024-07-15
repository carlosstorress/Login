<?php
session_start();

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cifrar la contraseña con password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "logintest");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Verificar si el nombre de usuario o el correo electrónico ya están en uso
    $sql_verificar = "SELECT * FROM usuarios WHERE username = ? OR correo = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("ss", $username, $correo);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows > 0) {
        // El nombre de usuario o el correo electrónico ya están en uso
        $_SESSION['error_registro'] = "El nombre de usuario o el correo electrónico ya están en uso.";
        header("Location: registro.php");
        exit;
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql_insertar = "INSERT INTO usuarios (nombre, apellidos, correo, telefono, edad, genero, username, password) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insertar = $conexion->prepare($sql_insertar);
        $stmt_insertar->bind_param("ssssisss", $nombre, $apellidos, $correo, $telefono, $edad, $genero, $username, $hashed_password);
        
        if ($stmt_insertar->execute()) {
            // Usuario registrado exitosamente
            // Establecer la sesión con el nombre de usuario
            $_SESSION['username'] = $username;
            // Redirigir al usuario a la página de inicio
            header("Location: inicio.php");
            exit;
        } else {
            // Error al registrar usuario
            echo "Error al registrar usuario: " . $stmt_insertar->error;
        }
    }

    // Cerrar la conexión y las declaraciones preparadas
    $stmt_verificar->close();
    $stmt_insertar->close();
    $conexion->close();
}
?>
