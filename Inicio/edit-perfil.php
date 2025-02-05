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

// Obtener los datos del usuario desde la base de datos necesarios para esta página
$stmt = $pdo->prepare("SELECT usuario, nombre, correo, imagen_perfil FROM usuarios WHERE id = :id");
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
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Estilo según la cookie (tema.php) -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Pokémon PDP</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Editar Perfil</h2>
    </header>

    <!-- Contenido -->
    <div class="registrar">
        <h2>Editar Mis Datos</h2><br>

        <?php
        // Procesar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
            // Obtener datos del formulario
            $usuario = $_POST['usuario'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $imagen_perfil = $user['imagen_perfil'];  // Mantener la imagen actual si no se sube una nueva

            // Si se sube una nueva imagen se procesa
            if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] == 0) {
                $uploadDir = 'userfiles/'; // Ruta de destino donde se guardará el archivo subido
                $tmpName = $_FILES['imagen_perfil']['tmp_name']; // Archivo temporal
                // Se formula un nombnre único usando el id del usuario + funcionalidad time()
                $fileName = 'user_id_' . $_SESSION['usuario_id'] . '_' . time() . '.jpg'; // Se cambia la extensión a .jpg
                $uploadFile = $uploadDir . $fileName; // Ruta completa del archivo

                // Comprobar si la carpeta 'userfiles' existe y si tiene permisos de escritura
                if (move_uploaded_file($tmpName, $uploadFile)) {

                    // Actualizar el nombre de la imagen en la base de datos
                    $imagen_perfil = $fileName;

                } else {
                    echo "Error al subir el archivo, compruebe el formato del archvivo.";
                }
            }

            // Actualizar los datos del usuario en la base de datos
            if (!empty($usuario) && !empty($nombre) && !empty($correo)) {
                $stmt = $pdo->prepare("UPDATE usuarios SET usuario = :usuario, nombre = :nombre, correo = :correo, imagen_perfil = :imagen_perfil WHERE id = :id");
                $stmt->execute([
                    'usuario' => $usuario,
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'imagen_perfil' => $imagen_perfil,
                    'id' => $_SESSION['usuario_id']
                ]);

                // Actualizar los datos en la sesión
                $_SESSION['usuario'] = $usuario;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['correo'] = $correo;
                $_SESSION['imagen_perfil'] = $imagen_perfil;

                // Reedirigir al perfil tras realizar los cambios
                echo "<p style='color:green'>Datos actualizados correctamente. Reedirigiendo.</p>";
                header('Location: perfil.php');
                exit();
            }
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Imagen de perfil:</label>
            <input type="file" name="imagen_perfil" accept="image/jpeg, image/png, image/gif"><br><br>

            <label>Username:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($user['usuario']); ?>" required><br><br>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required><br><br>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required><br><br>

            <button type="submit" name="guardar">Guardar Cambios</button>
        </form>
    </div>

    <div class="back">
        <form method="POST" action="perfil.php">
            <button type="submit" name="volver">Volver al perfil</button>
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