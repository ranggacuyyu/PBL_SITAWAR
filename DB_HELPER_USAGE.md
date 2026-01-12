# 📘 Dokumentasi Database Helper

File `db_helper.php` menyediakan fungsi-fungsi untuk mempermudah penggunaan **prepared statements** dalam proyek PBL.
---

## 🚀 Cara Menggunakan

### 1️⃣ Include Helper di File PHP Anda

```php
<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';
?>
```


---

## 📚 Fungsi-Fungsi yang Tersedia

### 1. `db_select()` - SELECT Multiple Rows

**Deskripsi:** Mengambil banyak baris data dari database.

**Contoh Penggunaan:**

```php
// Mengambil semua warga berdasarkan RT
$data_warga = db_select($koneksi, "SELECT * FROM user_warga WHERE no_rt = ?", "s", [$no_rt]);

foreach ($data_warga as $warga) {
    echo $warga['nama_warga'] . "<br>";
}
```

---

### 2. `db_select_single()` - SELECT Single Row

**Deskripsi:** Mengambil satu baris data dari database.

**Contoh Penggunaan:**

```php
// Mengambil data RT berdasarkan SK RT
$rtData = db_select_single($koneksi, "SELECT no_rt, no_rw FROM user_rt WHERE sk_rt = ?", "s", [$sk_rt]);

if ($rtData) {
    $no_rt = $rtData['no_rt'];
    $no_rw = $rtData['no_rw'];
} else {
    echo "Data tidak ditemukan";
}
```

---

### 3. `db_insert()` - INSERT Data

**Deskripsi:** Menambahkan data baru ke database.

**Contoh Penggunaan:**

```php
// Insert warga baru
$query = "INSERT INTO user_warga (nama_warga, nik_warga, keluarga, no_rt, no_rw, rt, password) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$result = db_insert($koneksi, $query, "sssssss", [
    $nama, 
    $nik, 
    $keluarga, 
    $no_rt, 
    $no_rw, 
    $sk_rt, 
    $password_hash
]);

if ($result) {
    $_SESSION['notif'] = "Data berhasil disimpan";
    $_SESSION['status'] = "sukses";
} else {
    $_SESSION['notif'] = "Gagal menyimpan data";
    $_SESSION['status'] = "gagal";
}
```

---

### 4. `db_update()` - UPDATE Data

**Deskripsi:** Mengupdate data yang sudah ada di database.

**Contoh Penggunaan:**

```php
// Update nama warga
$query = "UPDATE user_warga SET nama_warga = ?, keluarga = ? WHERE nik_warga = ?";

$result = db_update($koneksi, $query, "sss", [$nama_baru, $keluarga_baru, $nik]);

if ($result) {
    $_SESSION['notif'] = "Data berhasil diupdate";
    $_SESSION['status'] = "sukses";
}
```

---

### 5. `db_delete()` - DELETE Data

**Deskripsi:** Menghapus data dari database.

**Contoh Penggunaan:**

```php
// Hapus warga berdasarkan NIK
$result = db_delete($koneksi, "DELETE FROM user_warga WHERE nik_warga = ?", "s", [$nik]);

if ($result) {
    $_SESSION['notif'] = "Data berhasil dihapus";
    $_SESSION['status'] = "sukses";
}
```

---

### 6. `db_exists()` - Cek Keberadaan Data

**Deskripsi:** Mengecek apakah data sudah ada di database (untuk validasi duplikasi).

**Contoh Penggunaan:**

```php
// Cek apakah NIK sudah terdaftar
$exists = db_exists($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga = ?", "s", [$nik]);

if ($exists) {
    $_SESSION['notif'] = "NIK sudah terdaftar!";
    $_SESSION['status'] = "gagal";
} else {
    // Lakukan insert
}
```

---

### 7. `db_count()` - Hitung Jumlah Data

**Deskripsi:** Menghitung jumlah baris data.

**Contoh Penggunaan:**

```php
// Hitung total warga di RT tertentu
$total = db_count($koneksi, "SELECT COUNT(*) as total FROM user_warga WHERE no_rt = ?", "s", [$no_rt]);

echo "Total warga: " . $total;
```

---

### 8. `db_execute()` - Execute Query Custom

**Deskripsi:** Menjalankan query apapun (untuk kasus khusus).

**Contoh Penggunaan:**

```php
// Update status banyak data sekaligus
$result = db_execute($koneksi, "UPDATE user_warga SET status = ? WHERE no_rt = ?", "ss", ["aktif", $no_rt]);
```

---

## 🔐 Fungsi Transaction

### Transaction untuk Multiple Operations

```php
// Mulai transaction
db_begin_transaction($koneksi);

try {
    // Insert ke tabel 1
    $result1 = db_insert($koneksi, "INSERT INTO tabel1 (kolom) VALUES (?)", "s", [$value1]);
    
    // Insert ke tabel 2
    $result2 = db_insert($koneksi, "INSERT INTO tabel2 (kolom) VALUES (?)", "s", [$value2]);
    
    if ($result1 && $result2) {
        // Commit jika semua berhasil
        db_commit($koneksi);
        $_SESSION['notif'] = "Semua data berhasil disimpan";
    } else {
        // Rollback jika ada yang gagal
        db_rollback($koneksi);
        $_SESSION['notif'] = "Gagal menyimpan data";
    }
} catch (Exception $e) {
    db_rollback($koneksi);
    $_SESSION['notif'] = "Error: " . $e->getMessage();
}
```

---

## 🛠️ Fungsi Helper Tambahan

### `db_sanitize()` - Sanitize Input

```php
// Sanitize input dari user
$nama = db_sanitize($_POST['nama']);
$nik = db_sanitize($_POST['nik']);
```

### `db_error()` - Ambil Error Message

```php
$result = db_insert($koneksi, $query, $types, $params);

if (!$result) {
    echo "Error: " . db_error($koneksi);
}
```

---

## 📝 Tipe Data Parameter

Saat menggunakan prepared statement, Anda harus menentukan tipe data:

| Tipe | Deskripsi |
|------|-----------|
| `s` | String |
| `i` | Integer |
| `d` | Double/Float |
| `b` | Blob |

**Contoh:**
- `"sss"` = 3 parameter string
- `"sii"` = 1 string, 2 integer
- `"sdsi"` = string, double, string, integer

---

## ✅ Contoh Lengkap Implementasi

### File: `tambah_warga_new.php` (Contoh Implementasi)

```php
<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// Sanitize input
$nama = db_sanitize($_POST['nama']);
$nik = db_sanitize($_POST['nik']);
$keluarga = db_sanitize($_POST['keluarga']);

// Validasi input kosong
if ($nama == "" || $nik == "" || $keluarga == "") {
    $_SESSION['notif'] = "Data tidak boleh kosong!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
}

// Ambil data RT
$sk_rt = $_SESSION['user_rt']['sk_rt'];
$rtData = db_select_single($koneksi, "SELECT no_rt, no_rw FROM user_rt WHERE sk_rt = ?", "s", [$sk_rt]);

if (!$rtData) {
    $_SESSION['notif'] = "Data RT tidak ditemukan!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
}

$no_rt = $rtData['no_rt'];
$no_rw = $rtData['no_rw'];

// Cek duplikat NIK
if (db_exists($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga = ?", "s", [$nik])) {
    $_SESSION['notif'] = "NIK sudah terdaftar!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
}

// Hash password
$password_hash = password_hash($nik, PASSWORD_DEFAULT);

// Insert data warga
$query = "INSERT INTO user_warga (nama_warga, nik_warga, keluarga, no_rt, no_rw, rt, password) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$result = db_insert($koneksi, $query, "sssssss", [
    $nama, $nik, $keluarga, $no_rt, $no_rw, $sk_rt, $password_hash
]);

if ($result) {
    $_SESSION['notif'] = "Data warga berhasil disimpan";
    $_SESSION['status'] = "sukses";
} else {
    $_SESSION['notif'] = "Gagal menyimpan data!";
    $_SESSION['status'] = "gagal";
}

header("Location: Dashboard_RT.php");
exit();
?>
```

---

## 🎯 Keuntungan Menggunakan Helper Ini

1. ✅ **Kode lebih ringkas** - Tidak perlu menulis `mysqli_stmt_init`, `mysqli_stmt_prepare`, dll berulang-ulang
2. ✅ **Lebih aman** - Menggunakan prepared statement yang melindungi dari SQL Injection
3. ✅ **Mudah dibaca** - Function yang jelas dan deskriptif
4. ✅ **Error handling** - Sudah ada penanganan error bawaan
5. ✅ **Reusable** - Bisa digunakan di semua file PHP dalam proyek
6. ✅ **Consistent** - Semua query menggunakan pattern yang sama

---

## 📌 Catatan Penting

1. **Jangan lupa** include `koneksi.php` sebelum `db_helper.php`
2. **Selalu gunakan** tipe data yang sesuai (`s`, `i`, `d`, `b`)
3. **Parameter array** harus sesuai dengan jumlah placeholder `?` di query
4. **Return value:**
   - `db_select()` → array of arrays (bisa kosong)
   - `db_select_single()` → array atau false
   - `db_insert()` → insert ID atau true/false
   - `db_update()` → true/false
   - `db_delete()` → true/false
   - `db_exists()` → true/false
   - `db_count()` → integer

---

**Happy Coding! 🚀**
