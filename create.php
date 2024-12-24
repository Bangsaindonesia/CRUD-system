<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $conn = new mysqli("localhost", "root", "", "crud_db");
    if ($conn->connect_error) {
        $message = "Terjadi kesalahan: " . $conn->connect_error;
        $popupType = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO pendaftar (name, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $phone);

        if ($stmt->execute()) {
            $message = "Data berhasil ditambahkan!";
            $popupType = 'success';
        } else {
            $message = "Terjadi kesalahan: " . $stmt->error;
            $popupType = 'error';
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="assets/create.css">
    <script>
        function showPopup(message, type) {
            var popup = document.getElementById('popup');
            popup.classList.remove('success', 'error');
            popup.classList.add(type);
            popup.querySelector('p').textContent = message;
            popup.style.display = 'block';
        }
        
        window.onload = function() {
            <?php if(isset($message)): ?>
                showPopup('<?php echo $message; ?>', '<?php echo $popupType; ?>');
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="form-page">
        <div class="form-container">
            <form method="POST" action="">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="phone">Telepon:</label>
                <input type="text" id="phone" name="phone" required>
                
                <input type="submit" value="Tambah Pengguna">
            </form>
        </div>
    </div>

    <div id="popup">
        <p></p>
        <button onclick="document.getElementById('popup').style.display='none';">Tutup</button>
        <a href="index.php">
            <button>Kembali</button>
        </a>
    </div>

</body>
</html>
