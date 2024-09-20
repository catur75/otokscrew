<?php
session_start();

// Cek apakah user sudah login, jika belum, arahkan ke halaman login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menerima Data dari Form
$id_pendidikan = isset($_POST['id_pendidikan']) ? intval($_POST['id_pendidikan']) : 0;
$id_mapel = isset($_POST['id_mapel']) ? intval($_POST['id_mapel']) : 0;
$nilai = isset($_POST['nilai']) ? intval($_POST['nilai']) : 0;

// Menyertakan File Koneksi Database
include 'koneksi.php';

// Periksa apakah ID pendidikan valid
$sql_check = "SELECT id FROM pendidikan WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_pendidikan);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows == 0) {
    echo "ID Pendidikan tidak valid.";
    exit();
}

$stmt_check->close();

// Insert nilai ke dalam tabel nilai
$sql_insert = "INSERT INTO nilai (id_pendidikan, id_mapel, nilai) VALUES (?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("iii", $id_pendidikan, $id_mapel, $nilai);

if ($stmt_insert->execute()) {
    header("Location: index.php?id=" . $id_pendidikan); // Arahkan kembali ke halaman index
    exit();
} else {
    echo "Terjadi kesalahan: " . $stmt_insert->error;
}

$stmt_insert->close();
$conn->close();
?>
