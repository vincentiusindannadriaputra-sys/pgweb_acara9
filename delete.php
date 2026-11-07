<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "gereja";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];


    $stmt = $conn->prepare("DELETE FROM data_gereja WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID tidak ditemukan.";
}

$conn->close();
?>