<?php
// 1. Buat Koneksi ke Database
$servername = "localhost"; // Nama host
$username = "root";        // Username database
$password = "";            // Password database
$dbname = "form_database"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}