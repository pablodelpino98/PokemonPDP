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
    <h2>¿Qué tipo de carta quieres registrar?</h2>
        <form method="POST" action="../Insert/insert_pokemon.php">
            <button type="submit">Pokémon Personalizado</button>
        </form><br>
        <form method="POST" action="../Insert/insert_partidario.php">
            <button type="submit">Partidario Personalizado</button>
        </form><br>
        <form method="POST" action="../Insert/insert_objeto.php">
            <button type="submit">Objeto Personalizado</button>
        </form>
    </div>
    <div class="back">
        <form method="POST" action="registrar_carta.php">
            <button type="submit" name="volver">Atrás</button>
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
