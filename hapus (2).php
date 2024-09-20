<?php
// 1. Buat Koneksi ke Database
require "koneksi.php";

// 2. Menerima ID dari Parameter URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$message = "";
$success = false;

if ($id > 0) {
    // 3. Hapus Data di Tabel Pendidikan yang Terkait dengan Profile
    $sql_pendidikan = "DELETE FROM pendidikan WHERE profile_id = ?";
    $stmt_pendidikan = $conn->prepare($sql_pendidikan);
    $stmt_pendidikan->bind_param("i", $id);
    $stmt_pendidikan->execute();

    // 4. Ambil Nama File Gambar dari Database
    $sql = "SELECT foto FROM profile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $foto = $row['foto'];

    // 5. Hapus Data dari Database Profile
    $sql = "DELETE FROM profile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // 6. Hapus File Gambar dari Direktori (jika ada)
        if (!empty($foto) && file_exists($foto)) {
            unlink($foto); // Menghapus file gambar
        }
        $message = "Data berhasil dihapus.";
        $success = true;
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Menutup Koneksi
    $stmt->close();
} else {
    $message = "ID tidak valid.";
}

$conn->close();

// Redirect ke halaman utama setelah 2 detik
header("refresh:2; url=show.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Penghapusan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
        }
        .message-box {
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .spinner {
            margin-top: 20px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="message-box">
        <?php if ($success): ?>
            <h1 class="success">Berhasil</h1>
        <?php else: ?>
            <h1 class="error">Gagal</h1>
        <?php endif; ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <div class="spinner"></div>
        <p>Anda akan diarahkan ke halaman utama...</p>
    </div>
</body>
</html>
