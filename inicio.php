<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Si el usuario no ha iniciado sesión, redirigirlo al formulario de inicio de sesión
    header("Location: login.php");
    exit;
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "logintest");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del usuario desde la base de datos
$username = $_SESSION['username'];
$sql = "SELECT * FROM usuarios WHERE username = ? OR correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se encontraron resultados
if ($resultado->num_rows > 0) {
    // Extraer los datos del usuario
    $usuario = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">    
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="script.js" defer></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway|Ubuntu" rel="stylesheet">
    <title>Página de Inicio</title>
</head>
<body>
<header><br>
<h1>Tu Pagina de Home</h1>
</header>
<nav>
        <button class="menu-toggle">☰ Menú</button>
        <div class="menu">
            <a href="#recomendadas.html">Imágenes Recomendadas</a>
            <a href="#lomasvisto.html">Lo Más Visto</a>
            <a href="#look.html">Look</a>
            <a href="#detuinteres.html">De Tu Interés</a>
            <a href="#sugerido.html">Sugerido</a>
            <a href="#ultimo.html">Último</a>
        </div>
    </nav>
    <div class="inicio-datos">
        <h1>Bienvenido, <?php echo $usuario['nombre']; ?>!</h1>
        <ul>
            <p>Aquí tienes tus datos:</p>
            <div class="color-datos">
                <li>Nombre/s: <?php echo $usuario['nombre']; ?></li>
                <li>Apellidos: <?php echo $usuario['apellidos']; ?></li>
                <li>Correo electrónico: <?php echo $usuario['correo']; ?></li>
                <li>Número de teléfono: <?php echo $usuario['telefono']; ?></li>
                <li>Edad: <?php echo $usuario['edad']; ?></li>
                <li>Género: <?php echo $usuario['genero']; ?></li>
                <li>Nombre de usuario: <?php echo $usuario['username']; ?></li>
            </div>
        </ul>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>
<?php
} else {
    // Si no se encuentran datos del usuario, redirige al inicio de sesión
    header("Location: index.html");
    exit;
}

// Cerrar la conexión y declaración preparada
$stmt->close();
$conexion->close();
?>
