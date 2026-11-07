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

// 1. PROSES UPDATE DATA SAAT FORM DIKIRIM (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST['id'] ?? 0;
    $gereja    = trim($_POST['gereja'] ?? '');
    $kalurahan = trim($_POST['kalurahan'] ?? '');
    $kapanewon = trim($_POST['kapanewon'] ?? '');
    $latitude  = $_POST['latitude'] ?? 0;
    $longitude = $_POST['longitude'] ?? 0;

    // Konversi tipe data
    $latitude  = (float) str_replace(',', '.', $latitude);
    $longitude = (float) str_replace(',', '.', $longitude);
    $id        = (int) $id;

    // Prepared statement untuk UPDATE data
    $stmt = $conn->prepare("UPDATE data_gereja SET 
                            gereja = ?, 
                            kalurahan = ?, 
                            kapanewon = ?, 
                            latitude = ?, 
                            longitude = ? 
                            WHERE id = ?");
    
    $stmt->bind_param("sssddi", $gereja, $kalurahan, $kapanewon, $latitude, $longitude, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // Redirect kembali ke tabel utama di luar folder edit/
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
        $stmt->close();
        $conn->close();
    }
} 
// 2. TAMPILKAN FORM SAAT HALAMAN DIBUKA PERTAMA KALI (GET)
else if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM data_gereja WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Edit Data Gereja</title>
</head>
<body>

<h2>Form Edit Data Gereja</h2>

<!-- Form dikirimkan kembali ke file edit.php ini sendiri -->
<form action="edit.php" method="POST">
    <!-- Hidden input untuk ID -->
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <label>Nama Gereja:</label><br>
    <input type="text" name="gereja" value="<?php echo htmlspecialchars($row['gereja']); ?>" required><br><br>

    <label>Kalurahan:</label><br>
    <input type="text" name="kalurahan" value="<?php echo htmlspecialchars($row['kalurahan']); ?>" required><br><br>

    <label>Kapanewon:</label><br>
    <input type="text" name="kapanewon" value="<?php echo htmlspecialchars($row['kapanewon']); ?>" required><br><br>

    <label>Latitude:</label><br>
    <input type="text" name="latitude" value="<?php echo $row['latitude']; ?>" required><br><br>

    <label>Longitude:</label><br>
    <input type="text" name="longitude" value="<?php echo $row['longitude']; ?>" required><br><br>

    <input type="submit" value="Simpan Perubahan">
</form>

</body>
</html>
<?php
    } else {
        echo "Data tidak ditemukan.";
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
}
?>