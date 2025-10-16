// ดึงข้อมูลตอนโหลดหน้า
fetch('api.php?action=read')
  .then(res => res.json())
  .then(data => renderTable(data));

const nameInput = document.getElementById('nameInput');
const addBtn = document.getElementById('addBtn');
const nameList = document.getElementById('nameList');

// เพิ่มข้อมูลใหม่
addBtn.onclick = () => {
  const name = nameInput.value.trim();
  if (!name) return alert("กรุณากรอกชื่อ");
  fetch('api.php?action=add', {
    method: 'POST',
    body: new URLSearchParams({ name })
  })
  .then(res => res.json())
  .then(() => {
    nameInput.value = '';
    refresh();
  });
};

// ลบข้อมูล
function deleteName(id) {
  fetch('api.php?action=delete&id=' + id)
    .then(res => res.json())
    .then(() => refresh());
}

// แก้ไขข้อมูล
function editName(id, oldName) {
  const newName = prompt("แก้ไขชื่อ:", oldName);
  if (newName && newName !== oldName) {
    fetch('api.php?action=update', {
      method: 'POST',
      body: new URLSearchParams({ id, name: newName })
    })
    .then(res => res.json())
    .then(() => refresh());
  }
}

// แสดงข้อมูลในตาราง
function renderTable(data) {
  nameList.innerHTML = '';
  data.forEach(row => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${row.id}</td>
      <td>${row.name}</td>
      <td>
        <button onclick="editName(${row.id}, '${row.name}')">แก้ไข</button>
        <button onclick="deleteName(${row.id})">ลบ</button>
      </td>
    `;
    nameList.appendChild(tr);
  });
}

// โหลดใหม่หลังจากเปลี่ยนข้อมูล
function refresh() {
  fetch('api.php?action=read')
    .then(res => res.json())
    .then(data => renderTable(data));
}
