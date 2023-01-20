<?php
/**
 * Carica in automatico tutte le classi necessarie
 * al funzionamento dell'API di spoki
 */
foreach (scandir(dirname(__FILE__)) as $filename) {
    $path = dirname(__FILE__) . DIRECTORY_SEPARATOR. $filename;
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (is_file($path) && $path != __FILE__ && $ext == "php") {
        require $path;
    }
}
