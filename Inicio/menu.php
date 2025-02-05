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
    <title>Menú de Usuario</title>
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
    <br><br>
    <h1>¡Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>

    <div class="inicio">
    <h2>Menú Principal</h2>
        <form method="POST" action="../Listar/listar_pokemon.php">
            <button type="submit" name="cartas">Mis cartas</button>
        </form><br>
        <form method="POST" action="sobre.php">
            <button type="submit" name="registrar">Abrir sobres</button>
        </form><br>
        <form method="POST" action="registrar_carta.php">
            <button type="submit" name="registrar">Registrar carta</button>
        </form><br>
        <form method="POST" action="perfil.php">
            <button type="submit" name="perfil">Mi perfil</button>
        </form>
    </div>

    <div class="inicio">
        <form method="POST" action="logout.php">
            <button type="submit" name="volver">Cerrar sesión</button>
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