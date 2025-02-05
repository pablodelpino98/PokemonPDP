<?php
session_start();
require 'conexion.php';

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Rredirigir a la página principal
header('Location: index.php');
exit();