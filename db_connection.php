<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// .env-Datei laden
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Datenbankverbindung herstellen
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

// Verbindung prüfen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Überprüfen der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

?>