<?php
$conn = new mysqli('localhost', 'root', '', 'coatesapp');
if ($conn->connect_error) die('DB error: ' . $conn->connect_error);

$new_pass = md5('admin');

// Cek apakah user admin ada
$res = $conn->query("SELECT id, username, password FROM admin WHERE username='admin'");
if ($row = $res->fetch_assoc()) {
    $conn->query("UPDATE admin SET password='$new_pass' WHERE username='admin'");
    echo "OK — Password admin direset ke 'admin' (md5). ID: " . $row['id'];
} else {
    // Buat user admin baru jika belum ada
    $conn->query("INSERT INTO admin (username, password, nama, email, grup_id, status) VALUES ('admin', '$new_pass', 'Administrator', 'admin@admin.com', 1, 1)");
    echo "OK — User admin dibuat baru. ID: " . $conn->insert_id;
}
$conn->close();
