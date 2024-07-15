<?php
session_start();

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "logintest");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Preparar la consulta para verificar la información de inicio de sesión
    $sql = "SELECT * FROM usuarios WHERE (username = ? OR correo = ?) LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar la contraseña utilizando password_verify
        if (password_verify($password, $usuario['password'])) {
            // Usuario autenticado
            $_SESSION['username'] = $username;
            // Redirigir al usuario a la página de inicio
            header("Location: inicio.php");
            exit; // Detener la ejecución después de la redirección
        } else {
            // Contraseña incorrecta
            header("Location: index.html?error=1");
            exit;
        }
    } else {
        // Usuario no encontrado
        header("Location: index.html?error=1");
        exit;
    }

    // Cerrar la conexión y declaración preparada
    $stmt->close();
    $conexion->close();
}
?>
