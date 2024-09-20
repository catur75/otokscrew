<?php
session_start();

// Cek apakah user sudah login, jika belum, arahkan ke halaman login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menerima ID dari Parameter URL (gunakan session ID jika tidak ada parameter)
$id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// Menyertakan File Koneksi Database
include 'koneksi.php';

// Periksa apakah profile_id ada di tabel profile
$sql_check = "SELECT id FROM profile WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows == 0) {
    echo "Profile ID tidak valid.";
    exit();
}

// Mengambil data profil berdasarkan ID
$sql_profile = "SELECT * FROM profile WHERE id = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$row = $result_profile->fetch_assoc();

// Mengambil data riwayat pendidikan berdasarkan ID profil
$sql_pendidikan = "SELECT * FROM pendidikan WHERE profile_id = ?";
$stmt_pendidikan = $conn->prepare($sql_pendidikan);
$stmt_pendidikan->bind_param("i", $id);
$stmt_pendidikan->execute();
$result_pendidikan = $stmt_pendidikan->get_result();

// Jika form disubmit (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenjang = $_POST['jenjang'];
    $tahun = $_POST['tahun'];

    // Insert into pendidikan table
    $sql_insert = "INSERT INTO pendidikan (profile_id, jenjang, tahun) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iss", $id, $jenjang, $tahun);

    if ($stmt_insert->execute()) {
        echo "Riwayat pendidikan berhasil ditambahkan!";
    } else {
        echo "Terjadi kesalahan: " . $stmt_insert->error;
    }

    $stmt_insert->close();
}

$stmt_check->close();
$stmt_profile->close();
$stmt_pendidikan->close();
// $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata Saya</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .kotak {
            width: 80%;
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
        .buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .buttons .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .buttons .button:hover {
            background-color: #0056b3;
        }
        .blok {
            display: flex;
            margin-bottom: 20px;
        }
        .kiri {
            flex: 1;
            padding-right: 20px;
        }
        .kiri img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .kanan {
            flex: 2;
        }
        .kanan table {
            width: 100%;
            border-collapse: collapse;
        }
        .kanan th, .kanan td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .kanan th {
            background-color: #f2f2f2;
        }
        .riwayat-pendidikan {
            margin-top: 20px;
        }
        .riwayat-pendidikan h2 {
            color: #333;
        }
        .riwayat-pendidikan table {
            width: 100%;
            border-collapse: collapse;
        }
        .riwayat-pendidikan th, .riwayat-pendidikan td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .riwayat-pendidikan th {
            background-color: #f2f2f2;
        }
        .riwayat-pendidikan tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="kotak">
        <div class="judul">
            <h1>Biodata Saya</h1>
        </div>

        <div class="buttons">
            <a href="show.php" class="button">Show All Data</a>
        </div>

        <div class="blok">
            <div class="kiri">
                <!-- Menampilkan gambar -->
                <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Profil">
            </div>

            <div class="kanan">
                <table>
                    <tr>
                        <th>Nama</th>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    </tr>
                    <tr>
                        <th>Tempat</th>
                        <td><?php echo htmlspecialchars($row['tempat']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="riwayat-pendidikan">
    <h2>Riwayat Pendidikan</h2>
    <table>
        <tr>
            <th>Jenjang</th>
            <th>Tahun Lulus</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result_pendidikan->num_rows > 0) {
            while($row_pendidikan = $result_pendidikan->fetch_assoc()) {
                $id_pendidikan = $row_pendidikan['id'];
                echo "<tr>
                        <td>" . htmlspecialchars($row_pendidikan['jenjang']) . "</td>
                        <td>" . htmlspecialchars($row_pendidikan['tahun']) . "</td>
                        <td>
                            <div class='aksi'>
                                <a href='edit_pendidikan.php?id_pendidikan=" . htmlspecialchars($row_pendidikan['id']) . "&profile_id=" . htmlspecialchars($row_pendidikan['profile_id']) . "' class='button'>Edit</a>
                                <a href='hapus_pendidikan.php?id_pendidikan=" . htmlspecialchars($row_pendidikan['id']) . "&profile_id=" . htmlspecialchars($row_pendidikan['profile_id']) . "' class='button' onclick=\"return confirm('Anda yakin ingin menghapus riwayat pendidikan ini?');\">Hapus</a>
                                <a href='tambah_nilai.php?id_pendidikan=" . htmlspecialchars($row_pendidikan['id']) . "' class='button'>Tambah Nilai</a>
                            </div>
                        </td>
                      </tr>";
                              if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pendidikan, mapel, dan nilai
$sql = "
    SELECT p.id, p.jenjang, p.tahun, m.nama_mapel, n.nilai 
    FROM pendidikan p 
    JOIN nilai n ON p.id = n.id_pendidikan 
    JOIN mapel m ON m.id = n.id_mapel
    WHERE id_pendidikan = $id_pendidikan
";
$result = $conn->query($sql);

// Menampilkan data
if ($result->num_rows > 0) {
    $currentId = null;

    while($row = $result->fetch_assoc()) {
        if ($currentId !== $row['id']) {
            if ($currentId !== null) {
                echo "</table>";
            }
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th colspan='2' style='background-color: yellow;'>ID: " . $row['id'] . " - Jenjang: " . $row['jenjang'] . " - Tahun: " . $row['tahun'] . "</th></tr>";
            echo "<tr><th>Mapel</th><th>Nilai</th></tr>";
            $currentId = $row['id'];
        }
        echo "<tr><td>" . $row['nama_mapel'] . "</td><td>" . $row['nilai'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data";
}

                    }
                } else {
                    echo "<tr><td colspan='3'>Tidak ada riwayat pendidikan yang ditemukan</td></tr>";
                }
                ?>
            </table>
            <div class="buttons">
                <a href="form_pendidikan.php?profile_id=<?php echo htmlspecialchars($row['id']); ?>" class="button">Tambah Riwayat Pendidikan</a>
            </div>
        </div>
    </div>
</body>
</html>

