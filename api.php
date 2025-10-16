<?php
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "name_manager");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
  die(json_encode(["error" => "เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . $conn->connect_error]));
}

// อ่าน action จาก URL
$action = $_GET['action'] ?? '';

// อ่านข้อมูลทั้งหมด
if ($action === 'read') {
  $result = $conn->query("SELECT * FROM names");
  $data = [];

  // ดึงข้อมูลออกมาแบบมีชื่อคีย์ชัดเจน (id, name)
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  echo json_encode($data);
  exit;
}

// เพิ่มข้อมูลใหม่
if ($action === 'add') {
  $name = $_POST['name'] ?? '';

  if ($name !== '') {
    $stmt = $conn->prepare("INSERT INTO names (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    echo json_encode(["status" => "ok"]);
  } else {
    echo json_encode(["status" => "error", "message" => "ไม่มีชื่อ"]);
  }
  exit;
}

// ลบข้อมูล
if ($action === 'delete') {
  $id = $_GET['id'] ?? 0;

  $stmt = $conn->prepare("DELETE FROM names WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  echo json_encode(["status" => "ok"]);
  exit;
}

// แก้ไขข้อมูล
if ($action === 'update') {
  $id = $_POST['id'] ?? 0;
  $name = $_POST['name'] ?? '';

  $stmt = $conn->prepare("UPDATE names SET name = ? WHERE id = ?");
  $stmt->bind_param("si", $name, $id);
  $stmt->execute();

  echo json_encode(["status" => "ok"]);
  exit;
}
?>
