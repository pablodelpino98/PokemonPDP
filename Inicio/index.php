<?php
// Se inicia sesión y se incluye conexion.php y tema.php
session_start();
require 'conexion.php';
require '../Inicio/tema.php';

// Redirigir si el usuario ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: menu.php'); // Reedirigir a index.php si no está logueado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial</title>
 
    <!-- Incluir el archivo de estilo basado en la cookie -->
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Esto carga el estilo correcto según la cookie -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Bienvenido a Pokémon PDP</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Pokémon Pablo del Pino</h2>
    </header>
    
    <!-- Contenido -->
    <div class="div-index">
        <div class="intro1">
            <h1>Pokemon PDP</h1>
            <h2>¡Sea usted bienvenido a Pokémon PDP, la versión web del aclamado juego de cartas de Pokémon!</h2>
            <p>¿Eres un coleccionista, un jugador competitivo o simplemente un fanático del mundo Pokémon? Aquí encontrarás todo lo que necesitas para 
            sumergirte en el fascinante universo de las cartas Pokémon. Desde las cartas clásicas de primera generación hasta las últimas expansiones, ¡todo está aquí!</p>
        </div>
        <img src="media/cards.png" alt="cards">

        <h2>¡Atrápalos todos!</h2>
        <p>Abre sobres, colecciona tus Pokémon favoritos y muéstralos al resto del mundo. Crea barajas y compite contra otros entrenadores ¡Lucha por ser el mejor que habrá jamás!</p>

        <br><h2>¡La aventura te espera!</h2>

        <div class="intro2">
            <img src="media/ash.png" alt="ash">
            <form action="login.php">
                <div class="div-boton">
                    <button type="submit" name="login">Comenzar Aventura</button>
                </div>
            </form>
        </div>
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