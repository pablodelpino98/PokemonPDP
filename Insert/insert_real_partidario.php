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
    // Obtener las Cartas de tipo 'Partidario' de la base de datos, haciendo un JOIN con la tabla Cartas
    $stmt = $pdo->prepare("
        SELECT c.id, c.nombre AS carta_partidario_nombre, p.descripcion
        FROM Cartas c
        JOIN Partidarios p ON c.id = p.carta_id
        WHERE c.tipo = 'Partidario'
    ");
    $stmt->execute();
    $cartas_partidarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener las Cartas Partidarios: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Obtener partidario</title>
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
        <h2>Insertar Carta Partidaria</h2>
    </header>

    <!-- Contenido -->
    <div class="pokemon-register">
        <h2>Selecciona una Carta Partidaria</h2>

        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['carta_partidaria_id'])) {
                // Recoger el ID de la Carta Partidaria seleccionada
                $carta_partidaria_id = $_POST['carta_partidaria_id'];

                try {
                    // Comprobar si la Carta Partidaria existe en la base de datos
                    $stmt = $pdo->prepare("SELECT c.id, c.nombre AS carta_partidario_nombre 
                                           FROM Cartas c 
                                           WHERE c.id = :carta_partidaria_id AND c.tipo = 'Partidario'");
                    $stmt->execute(['carta_partidaria_id' => $carta_partidaria_id]);
                    $carta_partidaria = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($carta_partidaria) {
                        // Insertar la Carta Partidaria en la tabla Usuario_cartas para asociarla al usuario
                        $stmt = $pdo->prepare("INSERT INTO Usuario_cartas (usuario_id, carta_id) VALUES (:usuario_id, :carta_id)");
                        $stmt->execute([
                            'usuario_id' => $_SESSION['usuario_id'], // ID del usuario logueado
                            'carta_id' => $carta_partidaria['id'] // ID de la carta partidaria asociada
                        ]);
                        ?>
                        <!-- Mensaje tras insertar objeto-->
                            <!-- Mostrar imagen de carta con el mismo nombre que la carta -->
                            <img style="width: 200px" src="../Listar/cartas/<?= htmlspecialchars($carta_partidaria['carta_partidario_nombre']); ?>.png" alt="carta partidaria">
                            <p style="color: green">Carta Partidaria insertada correctamente.</p>
                            <form method="POST" action="insert_real_partidario.php">
                                <button type="submit">Añadir otra Carta Partidaria</button>
                            </form>
                        <?php
                        // Cambiar a False para NO mostrar formulario después del registro exitoso
                        $mostrarFormulario = false;
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener la Carta Partidaria: " . $e->getMessage();
                }
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
        <!-- Formulario de selección de Carta Partidaria -->
            <form method="POST">
                <select name="carta_partidaria_id" id="carta_partidaria_id" required>
                    <option value="">-- Selecciona una Carta Partidaria --</option>
                    <?php foreach ($cartas_partidarios as $carta_partidaria) { ?>
                        <option value="<?= $carta_partidaria['id']; ?>"><?= htmlspecialchars($carta_partidaria['carta_partidario_nombre']); ?></option>
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
