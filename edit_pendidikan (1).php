<?php
session_start();
include 'koneksi.php';

// Ambil ID pendidikan dan profile_id dari URL
$id_pendidikan = isset($_GET['id_pendidikan']) ? intval($_GET['id_pendidikan']) : 0;
$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

// Debugging untuk melihat apakah nilai id_pendidikan dan profile_id diterima dengan benar
echo "ID Pendidikan: " . $id_pendidikan . "<br>";
echo "Profile ID: " . $profile_id . "<br>";

// Pastikan ID pendidikan dan profile_id valid sebelum melakukan operasi lebih lanjut
if ($id_pendidikan <= 0 || $profile_id <= 0) {
    echo "ID Pendidikan atau Profile ID tidak valid.";
    exit();
}

// Ambil data pendidikan berdasarkan ID pendidikan dan profile_id
$sql = "SELECT * FROM pendidikan WHERE id = ? AND profile_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_pendidikan, $profile_id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah ada data yang diambil
if ($result->num_rows === 0) {
    echo "Data pendidikan tidak ditemukan untuk ID Pendidikan: " . $id_pendidikan . " dan Profile ID: " . $profile_id;
    exit();
}

$row = $result->fetch_assoc(); // Data yang diambil akan digunakan di form

// Jika form disubmit (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $jenjang = $_POST['jenjang'];
    $tahun = $_POST['tahun'];

    // Pastikan data yang diinput valid
    if (empty($jenjang) || !is_numeric($tahun)) {
        echo "Data yang dimasukkan tidak valid. Jenjang tidak boleh kosong dan tahun harus berupa angka.";
        exit();
    }

    // Update data pendidikan
    $sql_update = "UPDATE pendidikan SET jenjang = ?, tahun = ? WHERE id = ? AND profile_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssii", $jenjang, $tahun, $id_pendidikan, $profile_id);

    if ($stmt_update->execute()) {
        // Redirect ke halaman profil dengan profile_id yang sama
        header("Location: index.php?id=" . $profile_id);
        exit();
    } else {
        echo "Terjadi kesalahan saat mengupdate data: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendidikan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .kotak {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
        }
        .judul h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="kotak">
        <div class="judul">
            <h1>Edit Riwayat Pendidikan</h1>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="jenjang">Jenjang:</label>
                <select name="jenjang" id="jenjang" required>
                    <option value="" disabled>Pilih Jenjang</option>
                    <option value="SD" <?php echo ($row['jenjang'] === 'SD') ? 'selected' : ''; ?>>SD</option>
                    <option value="SMP" <?php echo ($row['jenjang'] === 'SMP') ? 'selected' : ''; ?>>SMP</option>
                    <option value="SMA/SEDERAJAT" <?php echo ($row['jenjang'] === 'SMA/SEDERAJAT') ? 'selected' : ''; ?>>SMA/Sederajat</option>
                    <option value="S1" <?php echo ($row['jenjang'] === 'S1') ? 'selected' : ''; ?>>S1</option>
                    <option value="S2" <?php echo ($row['jenjang'] === 'S2') ? 'selected' : ''; ?>>S2</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="number" name="tahun" id="tahun" value="<?php echo htmlspecialchars($row['tahun']); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
