<?php
session_start();

// Cek apakah user sudah login, jika belum, alihkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login.php
    header("Location: login.php");
    exit();
}

// Di sini user sudah login, jadi form akan ditampilkan
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama = $_POST['nama'];
    $tempat = $_POST['tempat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    // Proses upload foto jika ada
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Buat folder 'uploads' jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $foto = 'uploads/' . basename($_FILES['foto']['name']);
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto)) {
            // Jika gagal upload, tampilkan pesan error
            echo "Gagal mengupload file.";
            exit();
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO profile (nama, tempat, tanggal_lahir, alamat, email, no_hp, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Bind parameter dengan 7 parameter sesuai dengan query
    $stmt->bind_param("sssssss", $nama, $tempat, $tanggal_lahir, $alamat, $email, $no_hp, $foto);

    if ($stmt->execute()) {
        // Ambil ID terakhir yang disisipkan (ID profil)
        $profile_id = $stmt->insert_id;

        // Arahkan ke form_pendidikan.php dengan ID profil sebagai parameter
        header("Location: form_pendidikan.php?profile_id=" . $profile_id);
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
    <title>Form Profil</title>
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
            max-width: 600px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            color: #555;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        input[type="file"] {
            padding: 5px;
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
    </style>
</head>
<body>
    <form action="form.php" method="POST" enctype="multipart/form-data">
        <h2>Form Profil</h2>

        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" required>
        </div>

        <div class="form-group">
            <label for="tempat">Tempat:</label>
            <input type="text" name="tempat" id="tempat" required>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat:</label>
            <textarea name="alamat" id="alamat" required></textarea>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="no_hp">No. HP:</label>
            <input type="text" name="no_hp" id="no_hp" required>
        </div>

        <div class="form-group">
            <label for="foto">Pilih Gambar:</label>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <div class="submit-container">
            <input type="submit" name="submit" value="Simpan">
        </div>
    </form>
</body>
</html>
