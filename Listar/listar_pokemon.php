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
    <title>Mis cartas</title>
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
        <h2>Cartas</h2>
    </header>

    <!-- Contenido -->
    <div class="back">
        <form method="POST" action="../Inicio/menu.php">
            <button type="submit" name="volver">Volver al menú</button>
        </form>
    </div>

    <?php
    // Emilinar Carta de la lista y desvincularlo del usuario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Desvincular Pokémon por id único de la tabla UsuarioCarta
        if (isset($_POST['delete'])) {
            $delete_id = $_POST['delete_id'];  // ID de UsuarioCarta
            try {
                $pdo->beginTransaction();
                // Eliminar solo la relación específica entre el usuario y la carta
                $pdo->prepare("DELETE FROM Usuario_cartas WHERE id = :id AND usuario_id = :usuario_id")->execute(['id' => $delete_id, 'usuario_id' => $_SESSION['usuario_id']]);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<br><p style='color: red; text-align: center;'>Error al desvincular Pokémon: " . $e->getMessage() . "</p>";
            }
        }

        // Desvincular Partidario por id único de la tabla UsuarioCarta
        if (isset($_POST['delete_partidario'])) {
            $delete_id = $_POST['delete_id'];  // ID de UusarioCarta
            try {
                $pdo->beginTransaction();
                // Eliminar solo la relación específica entre el usuario y la carta
                $pdo->prepare("DELETE FROM Usuario_cartas WHERE id = :id AND usuario_id = :usuario_id")->execute(['id' => $delete_id, 'usuario_id' => $_SESSION['usuario_id']]);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<br><p style='color: red; text-align: center;'>Error al desvincular Partidario: " . $e->getMessage() . "</p>";
            }
        }

        // Desvincular Objeto por id único de la tabla UsuarioCarta
        if (isset($_POST['delete_objeto'])) {
            $delete_id = $_POST['delete_id'];  // ID de UusarioCarta
            try {
                $pdo->beginTransaction();
                // Eliminar solo la relación específica entre el usuario y la carta
                $pdo->prepare("DELETE FROM Usuario_cartas WHERE id = :id AND usuario_id = :usuario_id")->execute(['id' => $delete_id, 'usuario_id' => $_SESSION['usuario_id']]);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<br><p style='color: red; text-align: center;'>Error al desvincular Objeto: " . $e->getMessage() . "</p>";
            }
        }
    }

    try {
        // Consultas para listar Pokémon
        $stmt = $pdo->prepare("
            SELECT c.nombre AS nombre_pokemon, p.tipo, p.ps, p.ataque, p.dano_ataque, p.energia_necesaria_ataque, p.coste_energia_ataque, p.descripcion, c.id AS carta_id, uc.id AS usuario_cartas_id
            FROM Pokemon p
            JOIN Cartas c ON p.carta_id = c.id
            JOIN Usuario_cartas uc ON uc.carta_id = c.id
            WHERE uc.usuario_id = :usuario_id
        ");
        $stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
        $pokemon = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consultas para listar Partidarios
        $stmt = $pdo->prepare("
            SELECT c.nombre, p.descripcion, c.id AS carta_id, uc.id AS usuario_cartas_id
            FROM Partidarios p
            JOIN Cartas c ON p.carta_id = c.id
            JOIN Usuario_cartas uc ON uc.carta_id = c.id
            WHERE uc.usuario_id = :usuario_id
        ");
        $stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
        $partidarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consultas para listar Objetos
        $stmt = $pdo->prepare("
            SELECT c.nombre, o.descripcion, c.id AS carta_id, uc.id AS usuario_cartas_id
            FROM Objetos o
            JOIN Cartas c ON o.carta_id = c.id
            JOIN Usuario_cartas uc ON uc.carta_id = c.id
            WHERE uc.usuario_id = :usuario_id
        ");
        $stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
        $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo "Error al obtener las cartas: " . $e->getMessage();
    }
    ?>

    <div class="div-tablas">
        <h1 style="text-align: center;">Tus Cartas</h1>
        <div class="lista">

            <!-- Pokémon -->
            <h2>Pokémon</h2>
            <?php if (!empty($pokemon)): ?>
                <table>
                    <thead>
                        <tr>
                            <th class="image-colum">Imagen</th>
                            <th class="name-colum">Nombre</th>
                            <th>Tipo</th>
                            <th>PS</th>
                            <th>Ataque</th>
                            <th>Daño</th>
                            <th>Energía Necesaria</th>
                            <th>Coste Energía</th>
                            <th>Descripción</th>
                            <th class="delete-colum">Acción</th>
                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pokemon as $poke): ?> 
                            <tr>
                                <!-- Mostrar carta con el mismo nombre. Si es una carta Personalizada con otro nombre, muestra una imagen genérica de carta -->
                                <td><img style="width: 100px" src="<?php $imagePath = 'cartas/' . htmlspecialchars($poke['nombre_pokemon']) . '.png';echo file_exists($imagePath) ? $imagePath : 'cartas/generic-card.png';?>" alt="carta">
                                <td><?php echo htmlspecialchars($poke['nombre_pokemon']); ?></td>
                                <td><?php echo htmlspecialchars($poke['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($poke['ps']); ?></td>
                                <td><?php echo htmlspecialchars($poke['ataque']); ?></td>
                                <td><?php echo htmlspecialchars($poke['dano_ataque']); ?></td>
                                <td><?php echo htmlspecialchars($poke['energia_necesaria_ataque']); ?></td>
                                <td><?php echo htmlspecialchars($poke['coste_energia_ataque']); ?></td>
                                <td><?php echo htmlspecialchars($poke['descripcion']); ?></td>
                               
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="delete_id" value="<?php echo $poke['usuario_cartas_id']; ?>">
                                        <button type="submit" name="delete" class="delete-btn">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table><br>
            <?php else: ?>
                <p class="p-tabla">Aún no tienes cartas de Pokémon.</p><br>
            <?php endif; ?>

            <!-- Partidarios -->
            <h2>Partidarios</h2>
            <?php if (!empty($partidarios)): ?>
                <table>
                    <thead>
                        <tr>
                            <th class="image-colum">Imagen</th>
                            <th class="name-colum">Nombre</th>
                            <th>Descripción</th>
                            <th class="delete-colum">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partidarios as $partidario): ?>
                            <tr>
                                <!-- Mostrar carta con el mismo nombre. Si es una carta Personalizada con otro nombre, muestra una imagen genérica de carta -->
                                <td><img style="width: 100px" src="<?php $imagePath = 'cartas/' . htmlspecialchars($partidario['nombre']) . '.png';echo file_exists($imagePath) ? $imagePath : 'cartas/generic-card.png';?>" alt="carta">
                                <td><?php echo htmlspecialchars($partidario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($partidario['descripcion']); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="delete_id" value="<?php echo $partidario['usuario_cartas_id']; ?>">
                                        <button type="submit" name="delete_partidario" class="delete-btn">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table><br>
            <?php else: ?>
                <p class="p-tabla">Aún no tienes cartas de Partidario.</p><br>
            <?php endif; ?>

            <!-- Objetos -->
            <h2>Objetos</h2>
            <?php if (!empty($objetos)): ?>
                <table>
                    <thead>
                        <tr>
                            <th class="image-colum">Imagen</th>
                            <th class="name-colum">Nombre</th>
                            <th>Descripción</th>
                            <th class="delete-colum">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objetos as $objeto): ?>
                            <tr>
                                <!-- Mostrar carta con el mismo nombre. Si es una carta Personalizada con otro nombre, muestra una imagen genérica de carta -->
                                <td><img style="width: 100px" src="<?php $imagePath = 'cartas/' . htmlspecialchars($objeto['nombre']) . '.png';echo file_exists($imagePath) ? $imagePath : 'cartas/generic-card.png';?>" alt="carta">
                                <td><?php echo htmlspecialchars($objeto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($objeto['descripcion']); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="delete_id" value="<?php echo $objeto['usuario_cartas_id']; ?>">
                                        <button type="submit" name="delete_objeto" class="delete-btn">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table><br>
            <?php else: ?>
                <p class="p-tabla">Aún no tienes cartas de Objetos.</p><br>
            <?php endif; ?>
        </div>
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