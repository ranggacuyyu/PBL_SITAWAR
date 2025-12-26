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
function db_select($koneksi, $query, $types = "", $params = [])
{
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

function db_select_single($koneksi, $query, $types = "", $params = [])
{
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

function db_select_no_assoc($koneksi, $query, $types = "", $params = [])
{
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

    mysqli_stmt_close($stmt);
    return $result;
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
function db_insert($koneksi, $query, $types, $params)
{
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
function db_update($koneksi, $query, $types, $params)
{
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
function db_delete($koneksi, $query, $types, $params)
{
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
function db_exists($koneksi, $query, $types, $params)
{
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
function db_count($koneksi, $query, $types = "", $params = [])
{
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
    return $row ? (int) reset($row) : 0;
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
function db_execute($koneksi, $query, $types = "", $params = [])
{
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
function db_begin_transaction($koneksi)
{
    return mysqli_begin_transaction($koneksi);
}

/**
 * Fungsi untuk transaction COMMIT
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return bool - Return true jika berhasil
 */
function db_commit($koneksi)
{
    return mysqli_commit($koneksi);
}

/**
 * Fungsi untuk transaction ROLLBACK
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return bool - Return true jika berhasil
 */
function db_rollback($koneksi)
{
    return mysqli_rollback($koneksi);
}

/**
 * Fungsi untuk escape string (jika tetap perlu manual escaping)
 * 
 * @param mysqli $koneksi - Koneksi database
 * @param string $string - String yang akan di-escape
 * @return string - String yang sudah di-escape
 */
function db_escape($koneksi, $string)
{
    return mysqli_real_escape_string($koneksi, $string);
}

/**
 * Fungsi untuk mendapatkan error terakhir
 * 
 * @param mysqli $koneksi - Koneksi database
 * @return string - Error message
 */
function db_error($koneksi)
{
    return mysqli_error($koneksi);
}

/**
 * Fungsi untuk sanitize input
 * 
 * @param string $data - Data yang akan di-sanitize
 * @return string - Data yang sudah di-sanitize
 */
function db_sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Helper untuk membuat link pagination Bootstrap 5
 * 
 * @param int $total_data Total seluruh data
 * @param int $per_page Jumlah data per halaman
 * @param int $current_page Halaman saat ini 
 * @param string $url URL dasar (contoh: 'Dokumen_RT.php?')
 * @return string HTML pagination
 */
function db_pagination_links($total_data, $per_page, $current_page, $url)
{
    if ($total_data <= 0)
        return "";

    $total_page = ceil($total_data / $per_page);
    if ($total_page <= 1)
        return "";

    // Pastikan URL memiliki parameter separator yang benar
    $separator = (strpos($url, '?') !== false) ? '&' : '?';

    // HTML output
    $output = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm justify-content-end">';

    // Tombol Previous
    if ($current_page > 1) {
        $prev = $current_page - 1;
        $output .= '<li class="page-item"><a class="page-link" href="' . $url . $separator . 'hal=' . $prev . '">Previous</a></li>';
    } else {
        $output .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Logic number links (tampilkan sekitar current page)
    $start_number = ($current_page > 3) ? $current_page - 2 : 1;
    $end_number = ($current_page < ($total_page - 2)) ? $current_page + 2 : $total_page;

    if ($start_number > 1) {
        $output .= '<li class="page-item"><a class="page-link" href="' . $url . $separator . 'hal=1">1</a></li>';
        if ($start_number > 2) {
            $output .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    for ($i = $start_number; $i <= $end_number; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        $output .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . $separator . 'hal=' . $i . '">' . $i . '</a></li>';
    }

    if ($end_number < $total_page) {
        if ($end_number < ($total_page - 1)) {
            $output .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $output .= '<li class="page-item"><a class="page-link" href="' . $url . $separator . 'hal=' . $total_page . '">' . $total_page . '</a></li>';
    }

    // Tombol Next
    if ($current_page < $total_page) {
        $next = $current_page + 1;
        $output .= '<li class="page-item"><a class="page-link" href="' . $url . $separator . 'hal=' . $next . '">Next</a></li>';
    } else {
        $output .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    $output .= '</ul></nav>';
    return $output;
}
?>