<?php
// Se inicia sesión y se incluye conexion.php y tema.php
session_start();
require 'conexion.php';
require 'tema.php';

// Comprobar que el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Reedirigir a index.php si no está logueado
    exit();
}

// Obtener los datos del usuario desde la base de datos necesarios para esta página
$stmt = $pdo->prepare("SELECT usuario, nombre, correo, imagen_perfil FROM Usuarios WHERE id = :id");
$stmt->execute(['id' => $_SESSION['usuario_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el usuario, redirige a la página de login
if (!$user) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Esto carga el tema según la cookie -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Pokémon</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Perfil de Usuario</h2>
    </header>

    <!-- Contenido -->
    <div class="perfil-container">
        <h2>Mi Perfil</h2><br>

        <?php
        // Comprobar si el usuario ya tiene una imagen de perfil. Si no, mostrar la genérica
        $imagenPerfil = !empty($user['imagen_perfil']) ? 'userfiles/' . $user['imagen_perfil'] : 'media/generic-avatar.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($imagenPerfil); ?>" alt="avatar" class="perfil-image">
        
        <h3>Username:</h3>
        <p><?php echo htmlspecialchars($user['usuario']); ?></p>

        <h3>Nombre:</h3>
        <p><?php echo htmlspecialchars($user['nombre']); ?></p>

        <h3>Correo Electrónico:</h3>
        <p><?php echo htmlspecialchars($user['correo']); ?></p>

        <div class="perfil-button">
            <form method="POST" action="edit-perfil.php">
                <button type="submit" name="editar">Editar Perfil</button>
            </form>
        </div>
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
