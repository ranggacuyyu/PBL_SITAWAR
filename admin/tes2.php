<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_siswa");
$data = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
?>

<table>
    <tr>
        <th>Nama</th>
        <th>Kelas</th>
        <th>NIK</th>
        <th>NIM</th>
        <th>Aksi</th>
    </tr>

    <?php while($r = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $r['nama']; ?></td>
        <td><?= $r['kelas']; ?></td>
        <td><?= $r['nik']; ?></td>
        <td><?= $r['nim']; ?></td>
        <td>
            <button class="btn-update"
            onclick="openModal(
                <?= $r['id']; ?>,
                '<?= $r['nama']; ?>',
                '<?= $r['kelas']; ?>',
                '<?= $r['nik']; ?>',
                '<?= $r['nim']; ?>'
            )">Update</button>
        </td>
    </tr>
    <?php } ?>
</table>
