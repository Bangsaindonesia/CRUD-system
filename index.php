<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/index.css">
    <title>CRUD System</title>
</head>
<body>
    <div class="container">
        <h2>Daftar Pengguna</h2>
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Cari pengguna..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Cari</button>
            <button>
                <a href="index.php" class="btn-reset">Reset</a>
            </button>
        </form>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $conn = new mysqli("localhost", "root", "", "crud_db");
                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    $limit = 5;
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $sql_count = "SELECT COUNT(*) FROM pendaftar WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'";
                    $result_count = $conn->query($sql_count);
                    $total_records = $result_count->fetch_row()[0];
                    $total_pages = ceil($total_records / $limit);

                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $start_from = ($page - 1) * $limit;

                    $sql = "SELECT * FROM pendaftar WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' LIMIT $start_from, $limit";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["email"] . "</td>
                                    <td>" . $row["phone"] . "</td>
                                    <td>
                                        <a href='update.php?id=" . $row["id"] . "' class='btn-edit'>Edit</a>
                                        <a href='delete.php?id=" . $row["id"] . "' class='btn-delete'>Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                    }

                    $conn->close();
                ?>
                </tbody>
            </table>
        </div>
        <a href="create.php" class="btn">Tambah Pengguna Baru</a>
        <!-- Pagination -->
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=1&search=<?php echo urlencode($search); ?>" class="page-link">&#171;</a>
            <?php endif; ?>

            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link prev">&#8592;</a>
            <?php endif; ?>

            <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo "<a href='?page=$i&search=" . urlencode($search) . "' class='page-link " . ($i == $page ? 'active' : '') . "'>$i</a>";
                }
            ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link next">&#8594;</a>
            <?php endif; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>" class="page-link">&#187;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
