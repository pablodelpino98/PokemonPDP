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
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Esto carga el tema según la cookie -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Bienvenido a Pokémon PDP</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Inicio</h2>
    </header>

    <!-- Contenido -->
    <div class="inicio">
        <h2>Iniciar Sesión</h2>
        <form method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario" required><br>
            
            <label>Contraseña:</label>
            <input type="password" name="password" required><br><br>

            <button type="submit" name="login">Iniciar sesión</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['login'])) {
                // Inicio de sesión
                $usuario = $_POST['usuario'];
                $password = $_POST['password'];

                // Comprobar que el usuario exista
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
                $stmt->execute(['usuario' => $usuario]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Iniciar la sesión
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario'] = $user['usuario'];
                    header('Location: menu.php'); // Reedirigir al menú
                    exit();
                } else {
                    echo "<p style='color:red'>Usuario o contraseña incorrectos.</p>";
                }
            }
        }
        ?>
    </div>

    <div class="registrar">
        <form method="POST" action="registrar_usuario.php">
            <label>¿Eres nuevo?</label><br><br>
            <button type="submit" name="registro">Registrarme</button>
        </form>
    </div>

    <div class="back">
        <form method="POST" action="index.php">
            <button type="submit" name="volver">Salir</button>
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