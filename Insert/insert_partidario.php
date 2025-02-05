<?php
// Se inicia sesión y se incluye conexion.php y tema.php
session_start();
require '../Inicio/conexion.php';
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
    <title>Registrar partidario personalizado</title>
    <link rel="stylesheet" href="../Inicio/<?php echo $style; ?>">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="../Inicio/media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Pokémon PDP</h1>
            <img src="../Inicio/media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Registrar Partidario</h2>
    </header>

    <!-- Contenido -->
    <div class="inicio">
        <h2>Datos del Partidario</h2>

        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        // Comprueba si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Comprobar si los datos 'nombre' y 'descripcion' están presentes en $_POST
            if (isset($_POST['nombre'], $_POST['descripcion'])) {
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];

                try {
                    // Insertar en la tabla Cartas con tipo 'Partidario'
                    $stmt = $pdo->prepare("INSERT INTO Cartas (nombre, tipo) VALUES (:nombre, 'Partidario')");
                    $stmt->execute(['nombre' => $nombre]);

                    // Obtener el ID de la carta insertada
                    $carta_id = $pdo->lastInsertId();

                    // Asociar la carta con el usuario actual
                    $stmt = $pdo->prepare("
                    INSERT INTO Usuario_cartas (usuario_id, carta_id)
                    VALUES (:usuario_id, :carta_id)
                    ");
                    $stmt->execute([
                        'usuario_id' => $_SESSION['usuario_id'], // ID del usuario en sesión
                        'carta_id' => $carta_id // ID de la carta insertada
                    ]);

                    // Insertar en la tabla Partidarios
                    $stmt = $pdo->prepare("
                        INSERT INTO Partidarios (carta_id, descripcion)
                        VALUES (:carta_id, :descripcion)
                    ");
                    $stmt->execute([
                        'carta_id' => $carta_id,
                        'descripcion' => $descripcion
                    ]);
                    ?>
                    <!-- Mensaje tras insertar objeto-->
                        <p style="color: green">Partidario insertado correctamente.</p>
                        <form method="POST" action="insert_partidario.php">
                            <button type="submit">Añadir otro Partidario</button>
                        </form>
                    <?php
                    // Cambiar a False para NO mostrar formulario después del registro exitoso
                    $mostrarFormulario = false;

                } catch (PDOException $e) {
                    // Capturar y mostrar cualquier error en la inserción
                    echo "Error al insertar Partidario: " . $e->getMessage();
                }
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
            <form method="POST">
                <label>Partidario:</label>
                <input type="text" name="nombre" required><br><br>

                <label>Descripción:</label>
                <textarea name="descripcion" required></textarea><br><br>

                <button type="submit">Guardar</button>
            </form>
        <?php } ?>
    </div>

    <div class="back">
        <form method="POST" action="../Inicio/registrar_carta.php">
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
