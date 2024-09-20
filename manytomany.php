<?php
// Koneksi ke database
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'pendidikan_db');


// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pendidikan, mapel, dan nilai
$sql = "
    SELECT p.id, p.jenjang, p.tahun, m.nama_mapel, n.nilai 
    FROM pendidikan p 
    JOIN nilai n ON p.id = n.id_pendidikan 
    JOIN mapel m ON m.id = n.id_mapel
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

$conn->close();
?>
