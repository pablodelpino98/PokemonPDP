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
    // Obtener las Cartas de tipo 'Objeto' de la base de datos, haciendo un JOIN con la tabla Cartas
    $stmt = $pdo->prepare("
        SELECT c.id, c.nombre AS carta_objeto_nombre, o.descripcion
        FROM Cartas c
        JOIN Objetos o ON c.id = o.carta_id
        WHERE c.tipo = 'Objeto'
    ");
    $stmt->execute();
    $cartas_objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener las Cartas Objetos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Obtener Objeto</title>
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
        <h2>Insertar Carta Objeto</h2>
    </header>

    <!-- Contenido -->
    <div class="pokemon-register">
        <h2>Selecciona una Carta Objeto</h2>

        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['carta_objeto_id'])) {
                // Recoger el ID de la Carta Objeto seleccionada
                $carta_objeto_id = $_POST['carta_objeto_id'];

                try {
                    // Comprobar si la Carta Objeto existe en la base de datos
                    $stmt = $pdo->prepare("SELECT c.id, c.nombre AS carta_objeto_nombre 
                                           FROM Cartas c 
                                           WHERE c.id = :carta_objeto_id AND c.tipo = 'Objeto'");
                    $stmt->execute(['carta_objeto_id' => $carta_objeto_id]);
                    $carta_objeto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($carta_objeto) {
                        // Insertar la Carta Objeto en la tabla Usuario_cartas para asociarla al usuario
                        $stmt = $pdo->prepare("INSERT INTO Usuario_cartas (usuario_id, carta_id) VALUES (:usuario_id, :carta_id)");
                        $stmt->execute([
                            'usuario_id' => $_SESSION['usuario_id'], // ID del usuario logueado
                            'carta_id' => $carta_objeto['id'] // ID de la carta objeto asociada
                        ]);
                        ?>
                        <!-- Mensaje tras insertar objeto-->
                            <!-- Mostrar imagen de carta con el mismo nombre que la carta -->
                            <img style="width: 200px" src="../Listar/cartas/<?= htmlspecialchars($carta_objeto['carta_objeto_nombre']); ?>.png" alt="carta objeto">
                            <p style="color: green">Carta Objeto insertada correctamente.</p>
                            <form method="POST" action="insert_real_objeto.php">
                                <button type="submit">Añadir otra Carta Objeto</button>
                            </form>
                        <?php
                        // Cambiar a False para NO mostrar formulario después del registro exitoso
                        $mostrarFormulario = false;
                    }
                } catch (PDOException $e) {
                    echo "Error al obtener la Carta Objeto: " . $e->getMessage();
                }
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
            <form method="POST">
                <select name="carta_objeto_id" id="carta_objeto_id" required>
                    <option value="">-- Selecciona una Carta Objeto --</option>
                    <!-- Mostrar todas las cartas tipo Objeto de la base de datos -->
                    <?php foreach ($cartas_objetos as $carta_objeto) { ?>
                        <option value="<?= $carta_objeto['id']; ?>"><?= htmlspecialchars($carta_objeto['carta_objeto_nombre']); ?></option>
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
