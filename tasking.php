<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'u692140442_antzyn', '@Antzyn19', 'u692140442_crocoid');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari database dengan pagination
$limit = 5; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fitur search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = $search ? "WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR link LIKE '%$search%'" : '';

// Query untuk mengambil data
$sql = "SELECT * FROM notes $search_condition ORDER BY number ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Query untuk menghitung total data
$total_sql = "SELECT COUNT(*) as total FROM notes $search_condition";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Cek jika ada parameter success atau delete di URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$delete = isset($_GET['delete']) ? $_GET['delete'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Notes App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css"> <!-- Include CSS Sidebar -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Tambahkan CSS untuk main-content */
        .main-content {
            margin-left: 80px; /* Sesuaikan dengan lebar sidebar saat tidak dihover */
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 280px; /* Sesuaikan dengan lebar sidebar saat dihover */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container flex">
        <?php include 'sidebar.php'; ?> <!-- Include Sidebar -->
        <?php include 'main_content.php'; ?> <!-- Include Main Content -->
    </div>

    <!-- SweetAlert2 untuk alert -->
    <script>
        <?php if ($success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Data has been created successfully.',
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>

        <?php if ($delete): ?>
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Data has been deleted successfully.',
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>
    </script>
</body>
</html>