<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Tangkap data dari form input.html
    $gereja    = trim($_POST['gereja'] ?? '');
    $kalurahan = trim($_POST['kalurahan'] ?? '');
    $kapanewon = trim($_POST['kapanewon'] ?? '');
    $latitude  = $_POST['latitude'] ?? 0;
    $longitude = $_POST['longitude'] ?? 0;

    // Konversi tipe data koordinat desimal (mengganti koma jadi titik jika ada)
    $latitude  = (float) str_replace(',', '.', $latitude);
    $longitude = (float) str_replace(',', '.', $longitude);

    // Konfigurasi Database
    $servername = "localhost";
    $username   = "root";
    $password   = ""; 
    $dbname     = "gereja";

    // Koneksi ke Database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Menggunakan Prepared Statement
    $stmt = $conn->prepare("INSERT INTO data_gereja (gereja, kalurahan, kapanewon, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdd", $gereja, $kalurahan, $kapanewon, $latitude, $longitude);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // Mengarahkan kembali ke halaman utama
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
        $stmt->close();
        $conn->close();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>