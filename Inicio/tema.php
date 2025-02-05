<?php
// Comprobar si existe una cookie para el tema y asignar el estilo correspondiente
if (isset($_COOKIE['theme'])) {
    $style = $_COOKIE['theme']; // Obtiene el valor de la cookie (puede ser 'styles.css' o 'styles-night.css')
} else {
    // Si no existe la cookie, asigna el archivo de estilo predeterminado.
    $style = 'styles.css';
}

// Alternar el tema
if (isset($_POST['theme'])) {
    // Cambiar entre los temas: 'styles.css' y 'styles-night.css'
    $style = ($style === 'styles.css') ? 'styles-night.css' : 'styles.css';
    
    // Establecer la cookie con el valor del archivo de estilo correspondiente
    setcookie('theme', $style, time() + 60 * 60 * 24 * 365, '/'); // Se crea la cookie por 1 año
    // Recargar la página actual donde se encuentre el usuario para aplicar el cambio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>