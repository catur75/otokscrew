<?php
// Menyertakan file koneksi
include 'koneksi.php';

// Mengambil data dari formulir
$id = $_POST['id'];
$nama = $_POST['nama'];
$tempat = $_POST['tempat'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

// Menyiapkan dan menjalankan query UPDATE
$sql = "UPDATE profile SET nama = ?, tempat = ?, tanggal_lahir = ?, alamat = ?, email = ?, no_hp = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $nama, $tempat, $tanggal_lahir, $alamat, $email, $no_hp, $id);
$stmt->execute();

// Menutup koneksi
$stmt->close();
$conn->close();

// Redirect ke halaman index.php setelah mengupdate data
header("Location: index.php");
exit();
?>
