<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Excel & PDF dengan Checkbox</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SheetJS Excel -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-light p-4">

<div class="container">
    <h3 class="mb-3">Data Warga</h3>

    <!-- TOMBOL EXPORT -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalExport">
        Export Data
    </button>

    <!-- TABEL DATA -->
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Umur</th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
    </table>
</div>

<!-- MODAL PILIH KOLOM -->
<div class="modal fade" id="modalExport">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Pilih Kolom</h5>
      </div>

      <div class="modal-body">
        <label><input type="checkbox" class="kolom" value="nik" checked> NIK</label><br>
        <label><input type="checkbox" class="kolom" value="nama" checked> Nama</label><br>
        <label><input type="checkbox" class="kolom" value="alamat" checked> Alamat</label><br>
        <label><input type="checkbox" class="kolom" value="umur" checked> Umur</label>
      </div>

      <div class="modal-footer">
        <button class="btn btn-success" onclick="exportExcel()">Export Excel</button>
        <button class="btn btn-danger" onclick="exportPDF()">Export PDF</button>
      </div>

    </div>
  </div>
</div>

<!-- DATA SIMULASI DARI DATABASE -->
<script>
const dataWarga = [
  { nik: "123456", nama: "Andi", alamat: "Jakarta", umur: 25 },
  { nik: "234567", nama: "Budi", alamat: "Bandung", umur: 30 },
  { nik: "345678", nama: "Citra", alamat: "Surabaya", umur: 28 }
];

// TAMPILKAN KE TABEL
let tbody = document.getElementById("tbody");
dataWarga.forEach(d => {
    tbody.innerHTML += `
        <tr>
            <td>${d.nik}</td>
            <td>${d.nama}</td>
            <td>${d.alamat}</td>
            <td>${d.umur}</td>
        </tr>
    `;
});
</script>

<!-- EXPORT EXCEL -->
<script>
function exportExcel() {
  let kolomDipilih = [];
  document.querySelectorAll(".kolom:checked").forEach(cb => {
      kolomDipilih.push(cb.value);
  });

  let hasil = dataWarga.map(row => {
     let obj = {};
     kolomDipilih.forEach(k => obj[k] = row[k]);
     return obj;
  });

  let worksheet = XLSX.utils.json_to_sheet(hasil);
  let workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "DataWarga");

  XLSX.writeFile(workbook, "data_warga.xlsx");
}
</script>

<!-- EXPORT PDF -->
<script>
function exportPDF() {
  const { jsPDF } = window.jspdf;
  let doc = new jsPDF();

  let kolomDipilih = [];
  document.querySelectorAll(".kolom:checked").forEach(cb => {
      kolomDipilih.push(cb.value.toUpperCase());
  });

  let isi = dataWarga.map(row => {
    return kolomDipilih.map(k => row[k.toLowerCase()]);
  });

  doc.text("DATA WARGA", 14, 15);

  doc.autoTable({
    startY: 20,
    head: [kolomDipilih],
    body: isi
  });

  doc.save("data_warga.pdf");
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
