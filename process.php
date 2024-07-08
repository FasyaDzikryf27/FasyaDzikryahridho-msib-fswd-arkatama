<!-- process.php -->
<?php

// Koneksi ke database
$host = 'localhost'; // Sesuaikan dengan host database Anda
$dbname = 'skill_test'; // Nama database
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = $_POST['input_data'];

    // Pisahkan data berdasarkan spasi
    $data = explode(' ', $inputData);

    // Ambil Nama (dari index 0 sampai sebelum index terakhir)
    $name = implode(' ', array_slice($data, 0, -2));
    $name = strtoupper($name); // Ubah ke uppercase

    // Ambil Usia (ambil angka terakhir)
    $age = implode(' ', array_slice($data, 1, -1));
    // Cek apakah Usia berisi kata 'TAHUN', 'THN', atau 'TH'
    if (preg_match('/(\d+)\s*(TAHUN|THN|TH)/i', $age, $matches)) {
        $age = $matches[1];
    } else {
        if(!$age){
            $age = 0;
        }
    }

    // Ambil Kota (ambil satu kata terakhir)
    $city = end($data);
    $city = strtoupper($city); // Ubah ke uppercase

    // Simpan ke database
    $created_at = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO users (name, age, city, created_at) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$name, $age, $city, $created_at]);
        echo "Data berhasil disimpan.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>