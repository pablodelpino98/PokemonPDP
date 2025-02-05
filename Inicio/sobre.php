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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abrir Sobres</title>
    <link rel="stylesheet" href="<?php echo $style; ?>"> <!-- Esto carga el tema según la cookie -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="bienvenido">
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
            <h1>Pokémon PDP</h1>
            <img src="media/pokeball.png" alt="pokeball" class="header-img">
        </div>
        <h2>Sobres</h2>
    </header>

    <!-- Contenido -->
    <div class="inicio">
        <h2>Abrir Sobre</h2>

        <?php
        $mostrarFormulario = true; // Controlar si se muestra el formulario. True: se muestra. False: no se muestra. Por defecto se pone True
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartas'])) {
            try {
                // Obtener una carta aleatoria de la base de datos, limitando el ID a menos de 22 ya que es el total de Cartas reales.
                $stmt = $pdo->prepare("SELECT id, nombre FROM Cartas WHERE id < 22 ORDER BY RAND() LIMIT 1");
                $stmt->execute();
                $carta_aleatoria = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($carta_aleatoria) {
                    // Insertar la carta en la tabla Usuario_cartas
                    $stmt = $pdo->prepare("INSERT INTO Usuario_cartas (usuario_id, carta_id) VALUES (:usuario_id, :carta_id)");
                    $stmt->execute([
                        // Relacionar carta con el usuario
                        'usuario_id' => $_SESSION['usuario_id'],
                        'carta_id' => $carta_aleatoria['id']
                    ]);
                    // Mensaje tras generar la carta
                    ?>
                        <p style="color: green">¡Has obtenido una carta!</p>
                        <!-- Mostrar imagen de la carta usando el nombre de la carta ya que la imágenes se han guardado con el mismo nombre -->
                        <img style="width: 200px" src="../Listar/cartas/<?= strtolower($carta_aleatoria['nombre']); ?>.png" alt="carta"><br><br><br>
                        <form method="POST" action="sobre.php">
                            <!-- Recargar página para que se vuelva a generar el formulario -->
                            <button type="submit">¡Más!</button>
                        </form>
                    <?php
                    $mostrarFormulario = false;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        // Mostrar formulario según su valor True o False. Por defecto se muestra y cuando se envía el formulario correctamente, cambia a False y no se muestra.
        if ($mostrarFormulario) { ?>
            <form method="POST">
                <button type="submit" name="cartas">Abrir</button>
            </form>
            <!-- Mostrar imagen aleatoria del sobre (card1, card2 o card3) -->
            <img src="media/card<?=rand(1,3)?>.png" style="width:240px" alt="sobre">
        <?php } ?>
    </div>

    <div class="back">
        <form method="POST" action="menu.php">
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