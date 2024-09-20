<?php
// Menyertakan file koneksi
include 'koneksi.php';

// Mengambil data dari formulir
$nama = $_POST['nama'];
$tempat = $_POST['tempat'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

// Menyiapkan dan menjalankan query INSERT
$sql = "INSERT INTO profile (nama, tempat, tanggal_lahir, alamat, email, no_hp) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nama, $tempat, $tanggal_lahir, $alamat, $email, $no_hp);
$stmt->execute();

// Menutup koneksi
$stmt->close();
$conn->close();

// Redirect ke halaman index.php setelah menyimpan data
header("Location: index.php");
exit();
?>
