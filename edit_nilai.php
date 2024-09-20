<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Sertakan koneksi database
include 'koneksi.php';

// Ambil nilai_id dan profile_id dari URL
$nilai_id = isset($_GET['nilai_id']) ? intval($_GET['nilai_id']) : 0;
$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

// Jika nilai_id atau profile_id tidak valid
if ($nilai_id == 0 || $profile_id == 0) {
    echo "ID nilai atau ID profil tidak valid.";
    exit();
}

// Cek apakah data nilai ada
$sql_nilai = "SELECT n.nilai, m.nama_mapel 
              FROM nilai n 
              JOIN mapel m ON n.id_mapel = m.id 
              WHERE n.id = ? AND n.profile_id = ?";
$stmt_nilai = $conn->prepare($sql_nilai);
$stmt_nilai->bind_param("ii", $nilai_id, $profile_id);
$stmt_nilai->execute();
$result_nilai = $stmt_nilai->get_result();

if ($result_nilai->num_rows == 0) {
    echo "Data nilai tidak ditemukan.";
    exit();
}

$row_nilai = $result_nilai->fetch_assoc();

// Jika form disubmit (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nilai_baru = isset($_POST['nilai']) ? floatval($_POST['nilai']) : 0;

    // Update data nilai
    $sql_update = "UPDATE nilai SET nilai = ? WHERE id = ? AND profile_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("dii", $nilai_baru, $nilai_id, $profile_id);

    if ($stmt_update->execute()) {
        echo "Nilai berhasil diperbarui!";
        // Redirect kembali ke halaman biodata
        header("Location: index.php?id=" . $profile_id);
        exit();
    } else {
        echo "Terjadi kesalahan: " . $stmt_update->error;
    }

    $stmt_update->close();
}

$stmt_nilai->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .form-container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Nilai Mata Pelajaran: <?php echo htmlspecialchars($row_nilai['nama_mapel']); ?></h2>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nilai">Nilai:</label>
                <input type="number" name="nilai" id="nilai" value="<?php echo htmlspecialchars($row_nilai['nilai']); ?>" step="0.01" min="0" max="100" required>
            </div>

            <div class="form-group">
                <button type="submit">Update Nilai</button>
            </div>
        </form>
    </div>
</body>
</html>
