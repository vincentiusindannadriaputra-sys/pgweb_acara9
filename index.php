<?php
// Konfigurasi Database
$servername = "localhost";
$username   = "root";
$password   = ""; 
$dbname     = "gereja";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari tabel data_gereja
$sql = "SELECT * FROM data_gereja";
$result = $conn->query($sql);

echo "<p><a href='input/index.html' style='color: green; text-decoration: underline;'>Input data</a></p>";

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr bgcolor='#f2f2f2'>
            <th>Nomor</th>
            <th>Gereja</th>
            <th>Kalurahan</th>
            <th>Kapanewon</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Aksi</th>
          </tr>";

    $no = 1; // Variabel penomoran otomatis
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td align='center'>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row["gereja"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["kalurahan"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["kapanewon"]) . "</td>";
        echo "<td>" . $row["latitude"] . "</td>";
        echo "<td>" . $row["longitude"] . "</td>";
        echo "<td align='center'>
                <a href='edit/index.php?id=" . $row["id"] . "' style='color: blue;'>edit</a> | 
                <a href='delete.php?id=" . $row["id"] . "' style='color: red;' onclick='return confirm(\"Yakin ingin menghapus data " . htmlspecialchars($row["gereja"], ENT_QUOTES) . "?\")'>hapus</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data.";
}

$conn->close();
?>