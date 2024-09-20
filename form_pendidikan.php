<?php
session_start();

// Cek apakah user sudah login, jika belum, arahkan ke halaman login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menyertakan File Koneksi Database
include 'koneksi.php';

// Ambil ID profil dari parameter URL
$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 32;

// Validasi apakah profile_id ada di tabel profile
$sql_check = "SELECT id FROM profile WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $profile_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows === 0) {
    echo "Profile ID tidak valid atau tidak ditemukan.";
    exit();
}

// Jika metode request adalah POST, proses data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir pendidikan
    $jenjang = $_POST['jenjang'];
    $tahun = $_POST['tahun'];

    // Simpan data pendidikan ke database
    $sql = "INSERT INTO pendidikan (profile_id, jenjang, tahun) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $profile_id, $jenjang, $tahun);

    if ($stmt->execute()) {
        echo "Data pendidikan berhasil disimpan.";
        // Redirect ke halaman profil atau halaman lain jika diperlukan
        header("Location: index.php?id=" . $profile_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Riwayat Pendidikan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-container {
            display: flex;
            justify-content: flex-end;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <form action="form_pendidikan.php?profile_id=<?php echo htmlspecialchars($profile_id); ?>" method="post">
        <label for="jenjang">Jenjang:</label>
        <select id="jenjang" name="jenjang" required>
            <option value="" disabled selected>Pilih Jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA/Sederajat">SMA/Sederajat</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
        </select>

        <label for="tahun">Tahun Lulus:</label>
        <input type="text" id="tahun" name="tahun" required>

        <div class="submit-container">
            <input type="submit" value="Tambah">
        </div>
    </form>
    <div class="message">
        <!-- Tempat untuk pesan sukses atau error -->
    </div>
</body>
</html>

