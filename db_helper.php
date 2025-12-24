<?php
/**
 * Database Helper untuk Prepared Statements
 * 
 * File helper ini menyediakan fungsi-fungsi untuk mempermudah
 * penggunaan prepared statements di seluruh aplikasi PBL.
 * 
 * @author PBL Helper
 * @version 1.0
 */

// Pastikan koneksi database sudah di-include sebelum menggunakan helper ini
// require_once 'koneksi.php';


/**
 * Fungsi untuk melakukan SELECT query dengan prepared statement
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return array|false - Return array hasil query atau false jika gagal
 * 
 * Contoh penggunaan:
 * $result = db_select($koneksi, "SELECT * FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
 */
function db_select($koneksi, $query, $types = "", $params = []) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    // Bind parameter jika ada
    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    return $data;
}

/**
 * Fungsi untuk melakukan SELECT dan mengambil satu baris data
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return array|false - Return array satu baris data atau false jika gagal/tidak ada
 * 
 * Contoh penggunaan:
 * $user = db_select_single($koneksi, "SELECT * FROM user_rt WHERE sk_rt = ?", "s", [$sk_rt]);
 */
function db_select_single($koneksi, $query, $types = "", $params = []) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    // Bind parameter jika ada
    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    return $data;
}

/**
 * Fungsi untuk melakukan INSERT query dengan prepared statement
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return bool|int - Return insert ID jika berhasil, false jika gagal
 * 
 * Contoh penggunaan:
 * $id = db_insert($koneksi, "INSERT INTO user_warga (nama_warga, nik_warga) VALUES (?, ?)", "ss", [$nama, $nik]);
 */
function db_insert($koneksi, $query, $types, $params) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    if (mysqli_stmt_execute($stmt)) {
        $insert_id = mysqli_insert_id($koneksi);
        mysqli_stmt_close($stmt);
        return $insert_id > 0 ? $insert_id : true;
    }
    
    mysqli_stmt_close($stmt);
    return false;
}

/**
 * Fungsi untuk melakukan UPDATE query dengan prepared statement
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return bool - Return true jika berhasil, false jika gagal
 * 
 * Contoh penggunaan:
 * $success = db_update($koneksi, "UPDATE user_warga SET nama_warga = ? WHERE nik_warga = ?", "ss", [$nama, $nik]);
 */
function db_update($koneksi, $query, $types, $params) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Fungsi untuk melakukan DELETE query dengan prepared statement
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return bool - Return true jika berhasil, false jika gagal
 * 
 * Contoh penggunaan:
 * $success = db_delete($koneksi, "DELETE FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
 */
function db_delete($koneksi, $query, $types, $params) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Fungsi untuk mengecek apakah data exists di database
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return bool - Return true jika data ada, false jika tidak ada atau gagal
 * 
 * Contoh penggunaan:
 * $exists = db_exists($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
 */
function db_exists($koneksi, $query, $types, $params) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    
    mysqli_stmt_close($stmt);
    return $exists;
}

/**
 * Fungsi untuk menghitung jumlah baris (COUNT)
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL COUNT dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return int|false - Return jumlah baris atau false jika gagal
 * 
 * Contoh penggunaan:
 * $count = db_count($koneksi, "SELECT COUNT(*) as total FROM user_warga WHERE no_rt = ?", "s", [$no_rt]);
 */
function db_count($koneksi, $query, $types = "", $params = []) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    // Bind parameter jika ada
    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    
    // Ambil nilai pertama dari result (biasanya COUNT(*) atau COUNT(kolom))
    return $row ? (int)reset($row) : 0;
}

/**
 * Fungsi untuk execute query apapun (untuk kasus khusus)
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $query - Query SQL dengan placeholder (?)
 * @param string $types - Tipe data parameter (s=string, i=integer, d=double, b=blob)
 * @param array $params - Array parameter yang akan di-bind
 * @return bool - Return true jika berhasil, false jika gagal
 * 
 * Contoh penggunaan:
 * $success = db_execute($koneksi, "UPDATE user_warga SET status = ? WHERE no_rt = ?", "ss", [$status, $no_rt]);
 */
function db_execute($koneksi, $query, $types = "", $params = []) {
    $stmt = mysqli_stmt_init($koneksi);
    
    if (!mysqli_stmt_prepare($stmt, $query)) {
        return false;
    }
    
    // Bind parameter jika ada
    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Fungsi untuk transaction BEGIN
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return bool - Return true jika berhasil
 */
function db_begin_transaction($koneksi) {
    return mysqli_begin_transaction($koneksi);
}

/**
 * Fungsi untuk transaction COMMIT
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return bool - Return true jika berhasil
 */
function db_commit($koneksi) {
    return mysqli_commit($koneksi);
}

/**
 * Fungsi untuk transaction ROLLBACK
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return bool - Return true jika berhasil
 */
function db_rollback($koneksi) {
    return mysqli_rollback($koneksi);
}

/**
 * Fungsi untuk escape string (jika tetap perlu manual escaping)
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $string - String yang akan di-escape
 * @return string - String yang sudah di-escape
 */
function db_escape($koneksi, $string) {
    return mysqli_real_escape_string($koneksi, $string);
}

/**
 * Fungsi untuk mendapatkan error terakhir
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return string - Error message
 */
function db_error($koneksi) {
    return mysqli_error($koneksi);
}

/**
 * Fungsi untuk sanitize input
 * 
 * @param string $data - Data yang akan di-sanitize
 * @return string - Data yang sudah di-sanitize
 */
function db_sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
