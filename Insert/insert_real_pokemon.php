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

try {
    // Obtener los Pokémon de la base de datos, haciendo un JOIN con la tabla Cartas
    $stmt = $pdo->prepare("
        SELECT p.id, p.carta_id, c.nombre AS carta_nombre
        FROM Pokemon p
        JOIN Cartas c ON p.carta_id = c.id
        WHERE p.id <= 14
    ");
    $stmt->execute();
    $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener los Pokémon: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Obtener Pokémon</title>
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
        <h2>Insertar Pokémon</h2>
    </header>

    <!-- Contenido -->
    <div class="pokemon-register">
        <h2>Selecciona un Pokémon</h2>

        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['pokemon_id'])) {
                // Recoger el ID del Pokémon seleccionado
                $pokemon_id = $_POST['pokemon_id'];

                try {
                    // Comprobar si el Pokémon existe en la base de datos
                    $stmt = $pdo->prepare("SELECT p.id, p.carta_id, c.nombre AS carta_nombre 
                                           FROM Pokemon p 
                                           JOIN Cartas c ON p.carta_id = c.id 
                                           WHERE p.id = :pokemon_id");
                    $stmt->execute(['pokemon_id' => $pokemon_id]);
                    $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($pokemon) {
                        // Verificar que se haya encontrado un carta_id válido
                        if (isset($pokemon['carta_id']) && $pokemon['carta_id'] !== null) {
                            // Insertar el Pokémon en la tabla Usuario_cartas para asociarlo al usuario
                            $stmt = $pdo->prepare("INSERT INTO Usuario_cartas (usuario_id, carta_id) VALUES (:usuario_id, :carta_id)");
                            $stmt->execute([
                                'usuario_id' => $_SESSION['usuario_id'], // ID del usuario logueado
                                'carta_id' => $pokemon['carta_id'] // ID de la carta asociada al Pokémon
                            ]);
                            ?>
                            <!-- Mensaje tras insertar objeto-->
                                <!-- Mostrar imagen de carta con el mismo nombre que la carta -->
                                <img style="width: 200px" src="../Listar/cartas/<?= htmlspecialchars($pokemon['carta_nombre']); ?>.png" alt="carta">
                                <p style="color: green">Pokémon insertado correctamente.</p>
                                <form method="POST" action="insert_real_pokemon.php">
                                    <button type="submit">Añadir otro Pokémon</button>
                                </form>
                            <?php
                            // Cambiar a False para NO mostrar formulario después del registro exitoso
                            $mostrarFormulario = false;
                        } else {
                            echo "<p style='color: red;'>Este Pokémon no tiene una carta asociada válida.</p>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener el Pokémon: " . $e->getMessage();
                }
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
        <!-- Formulario de selección de Pokémon -->
            <form method="POST">
                <select name="pokemon_id" id="pokemon_id" required>
                    <option value="">-- Selecciona un Pokémon --</option>
                    <?php foreach ($pokemons as $pokemon) { ?>
                        <option value="<?= $pokemon['id']; ?>"><?= htmlspecialchars($pokemon['carta_nombre']); ?></option>
                    <?php } ?>
                </select>

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
