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
    <title>Insertar Pokémon</title>
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
        <h2>Registrar Pokémon personalizado</h2>
    </header>

    <!-- Contenido -->
    <div class="pokemon-register">
        <h2>Datos del Pokémon</h2>
        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
         // Comprueba si el formulario se ha enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Comprueba si todos los campos necesarios están definidos
            if (isset($_POST['nombre'], $_POST['tipo'], $_POST['ps'], $_POST['ataque'], $_POST['dano_ataque'], $_POST['energia_necesaria_ataque'])) {
                
                // Recoger datos del formulario
                $nombre = $_POST['nombre'];
                $tipo = $_POST['tipo'];
                $ps = $_POST['ps'];
                $ataque = $_POST['ataque'];
                $dano_ataque = $_POST['dano_ataque'];
                $energia_necesaria_ataque = $_POST['energia_necesaria_ataque'];
                $coste_energia_ataque = isset($_POST['coste_energia_ataque']) ? $_POST['coste_energia_ataque'] : 0;
                $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

                try {
                    // Insertar en la tabla Cartas
                    $stmt = $pdo->prepare("INSERT INTO Cartas (nombre, tipo) VALUES (:nombre, 'Pokemon')");
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

                    // Insertar en la tabla Pokemon
                    $stmt = $pdo->prepare("
                        INSERT INTO Pokemon (carta_id, tipo, ps, ataque, dano_ataque, energia_necesaria_ataque, coste_energia_ataque, descripcion)
                        VALUES (:carta_id, :tipo, :ps, :ataque, :dano_ataque, :energia_necesaria_ataque, :coste_energia_ataque, :descripcion)
                    ");
                    $stmt->execute([
                        'carta_id' => $carta_id,
                        'tipo' => $tipo,
                        'ps' => $ps,
                        'ataque' => $ataque,
                        'dano_ataque' => $dano_ataque,
                        'energia_necesaria_ataque' => $energia_necesaria_ataque,
                        'coste_energia_ataque' => $coste_energia_ataque,
                        'descripcion' => $descripcion
                    ]);
                    ?>
                    <!-- Mensaje tras insertar objeto-->
                        <p style="color: green">Pokémon insertado correctamente.</p>
                        <form method="POST" action="insert_pokemon.php">
                            <button type="submit">Añadir otro Pokémon</button>
                        </form>
                    <?php
                    // Cambiar a False para NO mostrar formulario después del registro exitoso
                    $mostrarFormulario = false;

                } catch (PDOException $e) {
                    // Capturar y mostrar cualquier error en la inserción
                    echo "Error al insertar Pokémon: " . $e->getMessage();
                }
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Pokémon:</label>
                <input type="text" name="nombre" required>

                <label>Tipo:</label>
                <select name="tipo" required>
                    <option value="Agua">Agua</option>
                    <option value="Fuego">Fuego</option>
                    <option value="Planta">Planta</option>
                    <option value="Normal">Normal</option>
                    <option value="Lucha">Lucha</option>
                    <option value="Psiquica">Psíquica</option>
                    <option value="Rayo">Rayo</option>
                    <option value="Metalica">Metálica</option>
                    <option value="Oscuro">Oscuro</option>
                </select>

                <label>PS:</label>
                <input type="number" name="ps" required>

                <label>Nombre de ataque:</label>
                <input type="text" name="ataque" required>

                <label>Daño de Ataque:</label>
                <input type="number" name="dano_ataque" required>

                <label>Energías necesarias para el ataque:</label>
                <input type="number" name="energia_necesaria_ataque" required>

                <label>Energías que descarta el ataque (opcional):</label>
                <input type="number" name="coste_energia_ataque">

                <label>Descripción del ataque:</label>
                <textarea name="descripcion"></textarea>

                <div class="boton">
                    <button type="submit">Guardar</button>
                </div>
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