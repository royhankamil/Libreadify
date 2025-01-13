<?php
$host = 'localhost';
$db = 'libreadify_db';
$user = 'root';
$pass = '';

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_book'])) {
        // Tambah buku
        $isbn = $_POST['isbn'];
        $title = $_POST['title'];
        $publisher = $_POST['publisher'];
        $author = $_POST['author'];

        $stmt = $conn->prepare("INSERT INTO books (isbn, title, publisher, author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $isbn, $title, $publisher, $author);

        if ($stmt->execute()) {
            echo "Buku berhasil ditambahkan!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    } elseif (isset($_POST['delete_book'])) {
        // Hapus buku
        $title = $_POST['title'];
        $author = $_POST['author'];

        $stmt = $conn->prepare("DELETE FROM books WHERE title = ? AND author = ?");
        $stmt->bind_param("ss", $title, $author);

        if ($stmt->execute()) {
            echo "Buku berhasil dihapus!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    } elseif (isset($_POST['edit_book'])) {
        // Edit buku
        $title = $_POST['title'];
        $author = $_POST['author'];
        $new_title = $_POST['new_title'];
        $new_publisher = $_POST['new_publisher'];
        $new_author = $_POST['new_author'];

        $stmt = $conn->prepare("UPDATE books SET title = ?, publisher = ?, author = ? WHERE title = ? AND author = ?");
        $stmt->bind_param("sssss", $new_title, $new_publisher, $new_author, $title, $author);

        if ($stmt->execute()) {
            echo "Buku berhasil diperbarui!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    } elseif (isset($_POST['show_books'])) {
        // Tampilkan semua buku
        $result = $conn->query("SELECT * FROM books");
        echo "<h3>Data Buku:</h3>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Publisher</th>
                    <th>Author</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['isbn']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['publisher']}</td>
                    <td>{$row['author']}</td>
                  </tr>";
        }
        echo "</table>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Buku</title>
</head>
<body>
    <h1>CRUD Buku</h1>
    <h2>Tambah Buku</h2>
    <form method="post">
        <label>ISBN:</label>
        <input type="number" name="isbn" required><br>
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Publisher:</label>
        <input type="text" name="publisher"><br>
        <label>Author:</label>
        <input type="text" name="author" required><br>
        <button type="submit" name="add_book">Tambah</button>
    </form>

    <h2>Hapus Buku</h2>
    <form method="post">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Author:</label>
        <input type="text" name="author" required><br>
        <button type="submit" name="delete_book">Hapus</button>
    </form>

    <h2>Edit Buku</h2>
    <form method="post">
        <label>Cari Title:</label>
        <input type="text" name="title" required><br>
        <label>Cari Author:</label>
        <input type="text" name="author" required><br>
        <label>Title Baru:</label>
        <input type="text" name="new_title" required><br>
        <label>Publisher Baru:</label>
        <input type="text" name="new_publisher"><br>
        <label>Author Baru:</label>
        <input type="text" name="new_author" required><br>
        <button type="submit" name="edit_book">Edit</button>
    </form>

    <h2>Tampilkan Semua Buku</h2>
    <form method="post">
        <button type="submit" name="show_books">Tampilkan</button>
    </form>
</body>
</html>