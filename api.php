<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "myapp_db");

if ($conn->connect_error) {
  die(json_encode(["error" => "เชื่อมต่อฐานข้อมูลไม่ได้"]));
}

$action = $_GET['action'] ?? '';

if ($action == 'read') {
  $result = $conn->query("SELECT * FROM users");
  echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

if ($action == 'add') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $conn->query("INSERT INTO users (name, email) VALUES ('$name', '$email')");
  echo json_encode(["status" => "ok"]);
}


if ($action == 'delete') {
  $id = $_GET['id'] ?? 0;
  $conn->query("DELETE FROM users WHERE id=$id");
  echo json_encode(["status" => "deleted"]);
}

if ($action == 'update') {
  $id = $_POST['id'] ?? 0;
  $name = $_POST['name'] ?? '';
  $conn->query("UPDATE users SET name='$name' WHERE id=$id");
  echo json_encode(["status" => "updated"]);
}

$conn->close();
?>
