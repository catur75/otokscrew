<?php
// 1. Buat Koneksi ke Database
require "koneksi.php";

// 2. Tulis Query SQL untuk Menghapus Semua Data
$sql = "DELETE FROM profile";

// 3. Eksekusi Query SQL
if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Semua data telah dihapus.');
            window.location.href = 'daftar.php'; // Redirect ke halaman daftar setelah penghapusan
          </script>";
} else {
    echo "<script>
            alert('Terjadi kesalahan: " . $conn->error . "');
            window.location.href = 'daftar.php'; // Redirect ke halaman daftar jika terjadi kesalahan
          </script>";
}

// 4. Menutup koneksi
$conn->close();
?>
