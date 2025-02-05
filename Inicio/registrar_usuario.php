<?php
// Se inicia sesión y se incluye conexion.php y tema.php
session_start();
require 'conexion.php';
require '../Inicio/tema.php';

// Comprobar que el usuario está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: menu.php'); // Reedirigir a index.php si no está logueado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pokémon PDP: Registro de usuario</title>
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
        <h2>Registro de Usuario</h2>
    </header>

    <!-- Contenido -->
    <div class="registrar">
        <h2>Usuario nuevo</h2>
        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro'])) {
            // Validar que los campos no estén vacíos
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';

            if (!empty($usuario) && !empty($password) && !empty($nombre) && !empty($correo)) {
                // Validar si el usuario ya existe
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
                $stmt->execute(['usuario' => $usuario]);

                if ($stmt->rowCount() > 0) {
                    echo "<p style='color:red'>El nombre de usuario ya existe.</p>";
                } else {
                    // Insertar el nuevo usuario
                    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, nombre, correo) VALUES (:usuario, :password, :nombre, :correo)");
                    $stmt->execute([
                        'usuario' => $usuario,
                        'password' => password_hash($password, PASSWORD_DEFAULT), // Encriptar contraseña
                        'nombre' => $nombre,
                        'correo' => $correo
                    ]);
                    echo "<p style='color:green'>Usuario creado.</p>";
                    $mostrarFormulario = false; // Cambiar a False para NO mostrar formulario después del registro exitoso
                }
            }
        }

        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
            <form method="POST">
                <label>Rellene los siguientes campos:</label><br><br>

                <label>Username:</label>
                <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario ?? ''); ?>" required><br><br>

                <label>Contraseña:</label>
                <input type="password" name="password" required><br><br>

                <label>Nombre real:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required><br><br>

                <label>Correo:</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($correo ?? ''); ?>" required><br><br>

                <button type="submit" name="registro">Registrar</button>
            </form>
        <?php } ?>
    </div>

    <div class="back">
        <form method="POST" action="login.php">
            <button type="submit" name="volver">Ir a iniciar sesión</button>
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