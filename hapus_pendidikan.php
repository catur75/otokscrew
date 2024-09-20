<?php
session_start();
include 'koneksi.php';

// Ambil ID pendidikan dan profile_id dari URL
$id_pendidikan = isset($_GET['id_pendidikan']) ? intval($_GET['id_pendidikan']) : 0;
$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

// Pastikan ID pendidikan dan profile_id valid sebelum melakukan operasi lebih lanjut
if ($id_pendidikan <= 0 || $profile_id <= 0) {
    echo "ID Pendidikan atau Profile ID tidak valid.";
    exit();
}

// Hapus data pendidikan berdasarkan ID pendidikan dan profile_id
$sql_delete = "DELETE FROM pendidikan WHERE id = ? AND profile_id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("ii", $id_pendidikan, $profile_id);

if ($stmt_delete->execute()) {
    // Redirect ke halaman profil dengan profile_id yang sama
    header("Location: index.php?id=" . $profile_id);
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus data: " . $stmt_delete->error;
}

$stmt_delete->close();
$conn->close();
?>
