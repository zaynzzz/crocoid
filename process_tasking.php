<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'u692140442_antzyn', '@Antzyn19', 'u692140442_crocoid');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses tambah data
if (isset($_POST['add_note'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $link = $_POST['link'];
    $created_at = date('Y-m-d H:i:s'); // Waktu saat ini

    // Ambil nomor terakhir dari database
    $sql = "SELECT MAX(number) as max_number FROM notes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $next_number = $row['max_number'] + 1;

    // Insert data baru
    $sql = "INSERT INTO notes (number, title, author, link, created_at) VALUES ('$next_number', '$title', '$author', '$link', '$created_at')";
    if ($conn->query($sql) === TRUE) {
        header("Location: tasking.php?success=1");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Proses hapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM notes WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: tasking.php?delete=1");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>