<?php
// Datos de conexión con la base de datps
$host = 'localhost'; // Dirección del servidor de base de datos
$dbname = 'pokemon_db'; // Nombre de la base de datos
$username = 'root'; // Nombre de usuario
$password = ''; // Contraseña

try {
    // Crear conexión a MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");

    // Crear sentencias para las tablas
    $sqlUsuarios = "
        CREATE TABLE IF NOT EXISTS Usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            correo VARCHAR(255) NOT NULL,
            imagen_perfil VARCHAR(255) NULL
        );
    ";
    $sqlCartas = "
        CREATE TABLE IF NOT EXISTS Cartas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255) NOT NULL,
            tipo ENUM('Pokemon', 'Partidario', 'Objeto', 'Energia') NOT NULL
        );
    ";
    $sqlUsuarioCartas = "
        CREATE TABLE IF NOT EXISTS Usuario_cartas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            carta_id INT NOT NULL,
            FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE,
            FOREIGN KEY (carta_id) REFERENCES Cartas(id) ON DELETE CASCADE
        );
    ";
    $sqlPartidarios = "
        CREATE TABLE IF NOT EXISTS Partidarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            carta_id INT NOT NULL,
            descripcion TEXT NOT NULL,
            FOREIGN KEY (carta_id) REFERENCES Cartas(id) ON DELETE CASCADE
        );
    ";
    $sqlPokemon = "
        CREATE TABLE IF NOT EXISTS Pokemon (
            id INT AUTO_INCREMENT PRIMARY KEY,
            carta_id INT NOT NULL,
            tipo VARCHAR(50) NOT NULL,
            ps INT NOT NULL,
            ataque VARCHAR(255) NOT NULL,
            dano_ataque INT NOT NULL,
            coste_energia_ataque INT NULL,
            energia_necesaria_ataque INT NOT NULL,
            descripcion TEXT NULL,
            imagen_url VARCHAR(255) NULL,
            FOREIGN KEY (carta_id) REFERENCES Cartas(id) ON DELETE CASCADE
        );
    ";
    $sqlObjetos = "
        CREATE TABLE IF NOT EXISTS Objetos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            carta_id INT NOT NULL,
            descripcion TEXT NOT NULL,
            FOREIGN KEY (carta_id) REFERENCES Cartas(id) ON DELETE CASCADE
        );
    ";
    $sqlEnergias = "
        CREATE TABLE IF NOT EXISTS Energias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            carta_id INT NOT NULL,
            tipo ENUM('Agua', 'Fuego', 'Planta', 'Normal', 'Lucha', 'Psíquico', 'Rayo', 'Metalica', 'Oscuro') NOT NULL,
            FOREIGN KEY (carta_id) REFERENCES Cartas(id) ON DELETE CASCADE
        );
    ";
    $sqlPokemonEnergias = "
        CREATE TABLE IF NOT EXISTS Pokemon_Energias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pokemon_id INT NOT NULL,
            energia_id INT NOT NULL,
            FOREIGN KEY (pokemon_id) REFERENCES Pokemon(id) ON DELETE CASCADE,
            FOREIGN KEY (energia_id) REFERENCES Energias(id) ON DELETE CASCADE
        );
    ";

    

    // Ejecutar las consultas para crear las tablas
    $pdo->exec($sqlCartas);
    $pdo->exec($sqlUsuarios);
    $pdo->exec($sqlUsuarioCartas);
    $pdo->exec($sqlPartidarios);
    $pdo->exec($sqlPokemon);
    $pdo->exec($sqlObjetos);
    $pdo->exec($sqlEnergias);
    $pdo->exec($sqlPokemonEnergias);

    // Datos de las cartas en array
    $cartas = [
        ['nombre' => 'Charmander', 'tipo' => 'Pokemon'],
        ['nombre' => 'Charmeleon', 'tipo' => 'Pokemon'],
        ['nombre' => 'Charizard', 'tipo' => 'Pokemon'],
        ['nombre' => 'Charizard EX', 'tipo' => 'Pokemon'],
        ['nombre' => 'Charizard EX GOLD', 'tipo' => 'Pokemon'],
        ['nombre' => 'Bulbasaur', 'tipo' => 'Pokemon'],
        ['nombre' => 'Ivysaur', 'tipo' => 'Pokemon'],
        ['nombre' => 'Venasaur', 'tipo' => 'Pokemon'],
        ['nombre' => 'Venasaur EX', 'tipo' => 'Pokemon'],
        ['nombre' => 'Squirtle', 'tipo' => 'Pokemon'],
        ['nombre' => 'Wartortle', 'tipo' => 'Pokemon'],
        ['nombre' => 'Blastoise', 'tipo' => 'Pokemon'],
        ['nombre' => 'Blastoise EX', 'tipo' => 'Pokemon'],
        ['nombre' => 'Pikachu EX', 'tipo' => 'Pokemon'],
        ['nombre' => 'Pikachu EX GOLD', 'tipo' => 'Pokemon'],
        ['nombre' => 'Mewtwo EX GOLD', 'tipo' => 'Pokemon'],
        ['nombre' => 'Koga', 'tipo' => 'Partidario'],
        ['nombre' => 'Misty', 'tipo' => 'Partidario'],
        ['nombre' => 'Investigación de Profesores', 'tipo' => 'Partidario'],
        ['nombre' => 'Poción', 'tipo' => 'Objeto'],
        ['nombre' => 'Poké Ball', 'tipo' => 'Objeto'],
        ['nombre' => 'Velocidad X', 'tipo' => 'Objeto'],
    ];
    
    // Detalles de cada tipo de carta (Pokémon, Partidario y Objeto) en array doble-dimensionado
    $detallesCartas = [
        'Pokemon' => [
            'Charmander' => ['ps' => 60, 'tipo' => 'Fuego', 'ataque' => 'Ascuas', 'dano_ataque' => 30, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 1, 'descripcion' => 'Descarta 1 Energía Fuego de este Pokémon.'],
            'Charmeleon' => ['ps' => 90, 'tipo' => 'Fuego', 'ataque' => 'Garras Fuego', 'dano_ataque' => 90, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 4, 'descripcion' => null],
            'Charizard' => ['ps' => 90, 'tipo' => 'Fuego', 'ataque' => 'Giro Fuego', 'dano_ataque' => 150, 'coste_energia_ataque' => 2, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Descarta 2 Energías Fuego de este Pokémon'],
            'Charizard EX' => ['ps' => 180, 'tipo' => 'Fuego', 'ataque' => 'Tormenta Carmesí', 'dano_ataque' => 200, 'coste_energia_ataque' => 2, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Descarta 2 Energías Fuego de este Pokémon'],
            'Charizard EX GOLD' => ['ps' => 180, 'tipo' => 'Fuego', 'ataque' => 'Tormenta Carmesí', 'dano_ataque' => 200, 'coste_energia_ataque' => 2, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Descarta 2 Energías Fuego de este Pokémon'],
            'Bulbasaur' => ['ps' => 70, 'tipo' => 'Planta', 'ataque' => 'Látigo Cepa', 'dano_ataque' => 40, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 2, 'descripcion' => null],
            'Ivysaur' => ['ps' => 90, 'tipo' => 'Planta', 'ataque' => 'Hoja Afilada', 'dano_ataque' => 60, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 3, 'descripcion' => null],
            'Venusaur' => ['ps' => 160, 'tipo' => 'Planta', 'ataque' => 'Megaagotar', 'dano_ataque' => 80, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Cura 30 puntos de daño a este Pokémon'],
            'Venusaur EX' => ['ps' => 190, 'tipo' => 'Planta', 'ataque' => 'Floración Gigante', 'dano_ataque' => 100, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Cura 30 puntos de daño a este Pokémon'],
            'Venusaur EX GOLD' => ['ps' => 190, 'tipo' => 'Planta', 'ataque' => 'Floración Gigante', 'dano_ataque' => 100, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Cura 30 puntos de daño a este Pokémon'],
            'Squirtle' => ['ps' => 60, 'tipo' => 'Agua', 'ataque' => 'Pistola Agua', 'dano_ataque' => 20, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 1, 'descripcion' => null],
            'Wartortle' => ['ps' => 80, 'tipo' => 'Agua', 'ataque' => 'Chapoteo Ondulante', 'dano_ataque' => 40, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 2, 'descripcion' => null],
            'Blastoise' => ['ps' => 150, 'tipo' => 'Agua', 'ataque' => 'Hidrobomba', 'dano_ataque' => 80, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 3, 'descripcion' => 'Si este Pokémon tiene por lo menos 2 Energías Agua adicionales unidas a él, este ataque hace 60 puntos de daño más.'],
            'Blastoise EX' => ['ps' => 180, 'tipo' => 'Agua', 'ataque' => 'Hidrobazuca', 'dano_ataque' => 100, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 3, 'descripcion' => 'Si este Pokémon tiene por lo menos 2 Energías Agua adicionales unidas a él, este ataque hace 60 puntos de daño más.'],
            'Pikachu EX' => ['ps' => 120, 'tipo' => 'Rayo', 'ataque' => 'Circuito Circular', 'dano_ataque' => 30, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 2, 'descripcion' => 'Este ataque hace 30 puntos de daño por cada uno de tus Pokémon Rayo en Banca.'],
            'Pikachu EX GOLD' => ['ps' => 120, 'tipo' => 'Rayo', 'ataque' => 'Circuito Circular', 'dano_ataque' => 30, 'coste_energia_ataque' => 0, 'energia_necesaria_ataque' => 2, 'descripcion' => 'Este ataque hace 30 puntos de daño por cada uno de tus Pokémon Rayo en Banca.'],
            'Mewtwo EX GOLD' => ['ps' => 150, 'tipo' => 'Psíquico', 'ataque' => 'Guía Psi', 'dano_ataque' => 150, 'coste_energia_ataque' => 2, 'energia_necesaria_ataque' => 4, 'descripcion' => 'Descarta 2 Energías Psiquica de este Pokémon.']
        ],
        'Partidario' => [
            'Koga' => ['descripcion' => 'Pon a tu Muk o a tu Weezing que esté en el Puesto Activo en tu mano.'],
            'Misty' => ['descripcion' => 'Elige 1 de tus Pokémon Agua, y luego lanza 1 moneda hasta que salga cruz. Por cada cara, unde 1 Energía Agua de tu área de Energía a ese Pokémon.'],
            'Investigación de Profesores' => ['descripcion' => 'Roba 2 cartas.'],
        ],
        'Objeto' => [
            'Poción' => ['descripcion' => 'Cura 20 puntos de daño a 1 de tus Pokémon.'],
            'Poké Ball' => ['descripcion' => 'Pon 1 Pokémon Básico aleatorio de tu baraja en tu mano.'],
            'Velocidad X' => ['descripcion' => 'Durante este turno, el Coste de Retirada de tu Pokémon Activo es de 1 menos.'],
        ]
    ];
    
    // Foreach para insertar cartas
    foreach ($cartas as $carta) {
        // Comprobar si la carta ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Cartas WHERE nombre = :nombre");
        $stmt->execute(['nombre' => $carta['nombre']]);
        $existeCarta = $stmt->fetchColumn();

        // Condicional para insertar carta solo si no existe
        if ($existeCarta == 0) {
            $stmt = $pdo->prepare("INSERT INTO Cartas (nombre, tipo) VALUES (:nombre, :tipo)");
            $stmt->execute(['nombre' => $carta['nombre'], 'tipo' => $carta['tipo']]);
        }

        // Obtener el ID de la carta
        $stmt = $pdo->prepare("SELECT id FROM Cartas WHERE nombre = :nombre");
        $stmt->execute(['nombre' => $carta['nombre']]);
        $cartaId = $stmt->fetchColumn();

        // Comprobar si tiene detalles y agregar los detalles según el tipo de carta
        if (isset($detallesCartas[$carta['tipo']][$carta['nombre']])) {
            $detalle = $detallesCartas[$carta['tipo']][$carta['nombre']];

            // Insertar detalles de cratas tipo Pokémon
            if ($carta['tipo'] === 'Pokemon') {
                // Comprobar si la carta Pokémon ya existe en la base de datos
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Pokemon WHERE carta_id = :carta_id");
                $stmt->execute(['carta_id' => $cartaId]);
                $existePokemon = $stmt->fetchColumn();

                // Condicional para insertar carta Pokémon solo si no existe
                if ($existePokemon == 0) {
                    $stmt = $pdo->prepare("
                        INSERT INTO Pokemon (carta_id, tipo, ps, ataque, dano_ataque, coste_energia_ataque, energia_necesaria_ataque, descripcion)
                        VALUES (:carta_id, :tipo, :ps, :ataque, :dano_ataque, :coste_energia_ataque, :energia_necesaria_ataque, :descripcion)
                    ");
                    $stmt->execute([
                        'carta_id' => $cartaId,
                        'tipo' => $detalle['tipo'],
                        'ps' => $detalle['ps'],
                        'ataque' => $detalle['ataque'],
                        'dano_ataque' => $detalle['dano_ataque'],
                        'coste_energia_ataque' => $detalle['coste_energia_ataque'],
                        'energia_necesaria_ataque' => $detalle['energia_necesaria_ataque'],
                        'descripcion' => $detalle['descripcion']
                    ]);
                }
            }
            // Insertar detalles de cartas tipo Partidario
            elseif ($carta['tipo'] === 'Partidario') {
                // Comprobar si la carta Partidario ya existe en la base de datos
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Partidarios WHERE carta_id = :carta_id");
                $stmt->execute(['carta_id' => $cartaId]);
                $existePartidario = $stmt->fetchColumn();

                // Condicional para insertar Partidario solo si no existe
                if ($existePartidario == 0) {
                    $stmt = $pdo->prepare("
                        INSERT INTO Partidarios (carta_id, descripcion)
                        VALUES (:carta_id, :descripcion)
                    ");
                    $stmt->execute([
                        'carta_id' => $cartaId,
                        'descripcion' => $detalle['descripcion']
                    ]);
                }
            }
            // Insertar detalles de Objetos
            elseif ($carta['tipo'] === 'Objeto') {
                // Comprobar si el Objeto ya existe en la base de datos
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Objetos WHERE carta_id = :carta_id");
                $stmt->execute(['carta_id' => $cartaId]);
                $existeObjeto = $stmt->fetchColumn();

                // Condicional para insertar Objeto solo si no existe
                if ($existeObjeto == 0) {
                    $stmt = $pdo->prepare("
                        INSERT INTO Objetos (carta_id, descripcion)
                        VALUES (:carta_id, :descripcion)
                    ");
                    $stmt->execute([
                        'carta_id' => $cartaId,
                        'descripcion' => $detalle['descripcion']
                    ]);
                }
            }
        }
    }
} 
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}