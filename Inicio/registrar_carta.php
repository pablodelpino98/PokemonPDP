<?php
// Se inicia sesión y se incluye conexion.php y tema.php
session_start();
require 'conexion.php';
require '../Inicio/tema.php';

// Comprobar que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php'); // Reedirigir a index.php si no está logueado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Carta</title>
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Esto carga el tema según la cookie -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Pokémon PDP</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Menú</h2>
    </header>

    <!-- Contenido -->
    <div class="inicio">
    <h2>Elige tu carta</h2>
        <form method="POST" action="registrar_real.php">
            <button type="submit">Carta Real</button>
        </form><br>
        <form method="POST" action="registrar_custom.php">
            <button type="submit">Carta Personalizada</button>
        </form><br>
    </div>
    <div class="back">
        <form method="POST" action="menu.php">
            <button type="submit" name="volver">Ir a menú</button>
        </form>
    </div>
    
    <!-- Footer -->
    <footer>
        <form method="POST">
            <button type="submit" name="theme">Cambiar Tema</button>
        </form>

        <p>Pablo del Pino</p>
    </footer>
</body>
</html>
