<?php 
if (isset($_SESSION['ADMIN_SESS'])) {
// cek sebelum hapus data
if (isset($_GET['id'])) {
$mhsID = intval($_GET['id']);
$del = mysqli_fetch_assoc(mysqli_query($con,"SELECT id_mhs,fotomhs FROM tb_mhs WHERE id_mhs=$mhsID "));
$doc ="../apl/img/".$del['fotomhs']."";

if (file_exists($doc)) {
if (!$del=='user.png') {
  unlink($doc);
}
mysqli_query($con,"DELETE FROM tb_mhs WHERE id_mhs=$del[id_mhs] ");
echo "<script>
alert('Data Mahasiwa Telah dihapus !');
window.location='?pages=mahasiswa';
</script>";
}else{
mysqli_query($con,"DELETE FROM tb_mhs WHERE id_mhs=$del[id_mhs] ");
echo "<script>
alert('Data Mahasiwa Telah dihapus !');
window.location='?pages=mahasiswa';
</script>"; 
}

}
}

?>
  <div class="d-sm-flex align-items-center justify-content-between">
  <h1 class="h3 mb-0 text-gray-800">Mahasiwa</h1>
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="./">Home</a></li>
  <li class="breadcrumb-item">Akademik</li>
  <li class="breadcrumb-item active" aria-current="page">Mahasiwa</li>
  </ol>
  </div>

<div class="row">
      <div class="col-lg-12 mb-4">
        <!-- Simple Tables -->
        <div class="card">
          <div class="card-header bg-gradient-primary text-white">
            <h6 class="mt-3 font-weight-bold"><i class="fa fa-graduation-cap"></i> Daftar Mahasiwa</h6>
            <a href="../apl/report/mahasiswa_print.php" target="_blank" class="btn btn-outline-light text-white btn-sm btn-icon-split mt-2">
              <span class="icon text-white-50">
                <i class="fas fa-print"></i>
              </span>
              <span class="text">Print</span>
            </a>
            <button type="button" class="btn btn-outline-light text-white btn-sm btn-icon-split mt-2" data-toggle="modal" data-target="#exampleModal"><span class="icon text-white-50">
              <i class="fas fa-plus-circle"></i>
              </span>
              <span class="text">Import</span></button>
          </div>
          <div class="card-body">

            <div class="table-responsive">
                  <table class="table table-striped table-hover table-sm align-items-center table-flush mid" id="data">
                    <thead class="table-flush">
                      <tr style="text-transform: uppercase;">
                        <th>NO</th>
                        <th>NIM</th>
                        <th>NAMA</th>
                        <th>PRODI</th>
                        <th>KONSENTRASI</th>
                        
                        <th>TAHUN</th>
                        <th>IMG</th>
                        <th>AKUN</th>
                        <th>ACTION</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $sql = mysqli_query($con,"SELECT
                      tb_mhs.id_mhs,
                      tb_mhs.nim,
                      tb_mhs.nama,
                      tb_mhs.fotomhs,
                      tb_mhs.tahun_angkatan,
                      tb_mhs.prodi_id,
                      tb_mhs.status_akun,
                      tm_prodi.prodi,
                       tm_prodi.konsen
                      
                      FROM tb_mhs
                      JOIN tm_prodi
                      ON tb_mhs.prodi_id=tm_prodi.prodi_id
                      ORDER BY tb_mhs.nim ASC");
                      foreach ($sql as $d) {?>
                      <tr>
                        <td><?=$no++?></td>
                        <td><b><?=$d['nim'] ?></b></td>
                        <td><?=$d['nama'] ?></td>
                        <td><?=$d['prodi'] ?></td>
                        <td><?=$d['konsen'] ?></td>
                        <td><?=$d['tahun_angkatan'] ?></td>
                        <td><img src="../apl/img/<?=$d['fotomhs'] ?>" class="img-thumbnail" style="width: 50px;height: 50px;border-radius: 100%;"></td>
                          <td>
                          <?php 
                          if ($d['status_akun']=='Y') {
                            echo "<span class='badge badge-success'>Aktif</span>";
                          }elseif ($d['status_akun']=='N') {
                            echo "<span class='badge badge-danger'>Non Aktif</span>";
                          }else{
                            echo "<span class='badge badge-warning'>Belum dikonfirmasi</span>";
                          }

                           ?>
                        </td>
                            <td>
                        <a href="#" data-toggle="modal" data-target="#modalEdit<?= $d['id_mhs'] ?>" class="btn btn-primary bg-gradient-primary btn-sm">
                          <i class="fas fa-edit"></i>
                          </a>
                          <a href="?pages=mahasiswa&id=<?= $d['id_mhs'] ?>" onclick="return confirm ('Apakah Yakin ?')" class="btn btn-danger bg-gradient-danger btn-sm"><i class="fa fa-trash"></i></a>


                <!-- Modal Edit -->
               <div class="modal fade" id="modalEdit<?= $d['id_mhs'] ?>" tabindex="-1" role="dialog"
                aria-labelledby="modalEdit" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="edit"><i class="fa fa-plus"></i> Edit Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?= $d['id_mhs'] ?>">
                  <input type="hidden" name="nim" value="<?= $d['nim'] ?>">
                <div class="modal-body">
                <div class="form-group">
                <label for="nip">NIM</label>
                <input type="text" class="form-control" value="<?= $d['nim'] ?>" readonly>
                </div>
                <div class="form-group">
                <label for="nama">Nama Mahasiswa</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $d['nama'] ?>" required>
                </div>

                <div class="form-group">
                <label for="tahun">Tahun Masuk</label>
                <input type="text" id="tahun" name="tahun" class="form-control" value="<?= $d['tahun_angkatan'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="prodi">Prodi</label>
                    <select name="prodi" id="prodi" class="form-control">
                    <?php 
                    $prodi = mysqli_query($con,"SELECT * FROM tm_prodi ORDER BY prodi_id ASC ");
                    foreach ($prodi as $f) {
                    if ($f['prodi_id']==$d['prodi_id']) {
                    $selected = 'selected';
                    }else{
                    $selected = '';
                    }
                    echo "<option value='$f[prodi_id]' $selected>$f[prodi]</option>";
                    }
                    ?>
                    </select>
                    </div>
                    <div class="form-group">
                    <label for="status">Status Akun</label>
                    <select name="status" id="status" class="form-control">
                    <option value="Y" <?php if ($d['status_akun']=='Y') { echo "selected";} ?>>Aktif</option> 
                    <option value="N" <?php if ($d['status_akun']=='N') { echo "selected"; } ?>>Tidak Aktif</option> 
                    </select>
                    </div>

                <div class="form-group">
                <label for="foto">Foto</label>
                <img src="../apl/img/<?=$d['fotomhs'] ?>" class="img-thumbnail" style="width: 70px;height: 70px;border-radius: 100%;">
                <input type="hidden" name="foto" value="<?= $d['fotomhs'] ?>">
                <input type="file" name="file" class="form-control">
                </div>
                    <div class="form-group">
                    <center>
                      <button type="button" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button name="update" type="submit" class="btn btn-primary bg-gradient-primary"><i class="fa fa-check"></i> Save</button>
                    </center>
                </div>


                </div>
                </form>
                  <!-- SCRIPT EDIT DATA MAHASIWA -->
                  <?php 
                  if (isset($_POST['update'])) {
                  
                          $array    = array('jpg','jpeg','png');
                  $id    = intval($_POST['id']);
                  $nim   = htmlspecialchars($_POST['nim']);
                  $nama  = htmlspecialchars($_POST['nama']);
                  $tahun = htmlspecialchars($_POST['tahun']);
                  $status = htmlspecialchars($_POST['status']);
                  $prodi  = $_POST['prodi'];
                  $foto  = $_POST['foto']; 
                  // Post file
                  $filenama = $nim.'_'.time();
                  $file_name    = $_FILES['file']['name'];
                  @$file_ext     = strtolower(end(explode('.', $file_name)));
                  $file_size    = $_FILES['file']['size'];
                  $file_tmp     = $_FILES['file']['tmp_name'];
                  $gambar = $filenama.'.'.$file_ext;

                  if ($file_name=="") {
                  mysqli_query($con,"UPDATE tb_mhs SET nama='$nama',tahun_angkatan='$tahun',prodi_id='$prodi',status_akun='$status' WHERE id_mhs=$id ");
                  echo "
                  <script> alert ('Sukses ! Data berhasil diperbarui .. ');
                  window.location ='?pages=mahasiswa';   
                  </script>";
                  }else{
                  // jika pesan melampirkan file
                  if(in_array($file_ext, $array) === true){
                  if($file_size < 2000000){
                  $lokasi = '../apl/img/'.$filenama.'.'.$file_ext;
                  move_uploaded_file($file_tmp, $lokasi);
                  $is_update= mysqli_query($con,"UPDATE tb_mhs SET nama='$nama',fotomhs='$gambar',tahun_angkatan='$tahun',prodi_id='$prodi',status_akun='$status' WHERE id_mhs=$id ");
                  if ($is_update) {
                      if ($foto !=="user.png") {
                      $doc ="../apl/img/".$foto."";
                      unlink($doc);                    
                      }
                  echo "
                  <script> alert ('Sukses ! Data berhasil diperbarui .. ');
                  window.location ='?pages=mahasiswa';   
                  </script>";
                  }else{
                  echo '<div class="alert alert-danger bg-gradient-danger text-white" id="alert">PESAN TIDAK TERKIRIM: Periksa kembali isi pesan anda ..</div>';
                  }

                  }else{
                  echo '<div class="alert alert-danger bg-gradient-danger text-white" id="alert">ERROR: Ukuran File terlalu besar, Maksimal 2 MB</div>';
                  }
                  }else{
                  echo '<div class="alert alert-danger bg-gradient-danger text-white" id="alert">ERROR: Ekstensi file tidak di izinkan!</div>';
                  }

                  }
            


                  }

                  ?>
                  <!-- END SCRIPT EDIT DATA MAHASIWA -->
                </div>
                </div>
                </div>

                             
                          </td>
                      </tr>
                    <?php } ?>
          
                     
                    </tbody>
                  </table> 
                  <!-- Import Data -->
                  <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <form action="" method="post" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Import Mahasiswa</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="">Import Mahasiswa</label>
                        <input type="file" name="file" class="form-control">
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" name="file" class="btn btn-primary">Import Mahasiswa</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
              <?php


if(isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    
    try {
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getActiveSheet();
        
        $highestRow = $sheet->getHighestRow();
        $mahasiswaArray = array(); // Array untuk menyimpan data mahasiswa
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $nim = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            $nama = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $angkatan = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $id_prodi = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $tmp_lahir = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $tgl_lahir = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $status = 'Y';
            $foto = 'user.png';
            $password = md5($nim); // Menggunakan md5 untuk sementara
            
            // Menambahkan data mahasiswa ke dalam array
            $mahasiswaArray[] = array(
                'nim' => $nim,
                'nama' => $nama,
                'tahun_angkatan' => $angkatan,
                'id_prodi' => $id_prodi,
                'status_akun' => $status,
                'tmp_lahir' => $tmp_lahir,
                'tgl_lahir' => $tgl_lahir,
                'fotomhs' => $foto,
                'password' => $password,
            );
        }
        
        // Simpan atau update data mahasiswa
        foreach ($mahasiswaArray as $mahasiswa) {
            $nim = $mahasiswa['nim'];
            $nama = $mahasiswa['nama'];
            $tahun_angkatan = $mahasiswa['tahun_angkatan'];
            $id_prodi = $mahasiswa['id_prodi'];
            $status_akun = $mahasiswa['status_akun'];
            $fotomhs = $mahasiswa['fotomhs'];
            $password = $mahasiswa['password'];
            $tmp_lahir = $mahasiswa['tmp_lahir'];
            $tgl_lahir = $mahasiswa['tgl_lahir'];
            
            // Cek apakah nim sudah ada dalam database
            $result = mysqli_query($con, "SELECT * FROM tb_mhs WHERE nim='$nim'");
            if(mysqli_num_rows($result) > 0) {
                // Jika nim sudah ada, update data
                $sql = mysqli_query($con, "UPDATE tb_mhs SET nama='$nama', tmp_lahir='$tmp_lahir', tg_lahir='$tgl_lahir', tahun_angkatan='$tahun_angkatan', prodi_id='$id_prodi', status_akun='$status_akun', fotomhs='$fotomhs', password='$password' WHERE nim='$nim'");
                if($sql) {
                    echo "Data mahasiswa dengan NIM $nim berhasil diupdate.<br>";
                } else {
                    echo "Gagal mengupdate data mahasiswa dengan NIM $nim.<br>";
                }
            } else {
                // Jika nim belum ada, tambahkan data
                $sql = mysqli_query($con, "INSERT INTO tb_mhs VALUES (NULL,'$nim', '$nama', '$tmp_lahir','$tgl_lahir', '$password', '$fotomhs', '$tahun_angkatan', '$id_prodi', '', '$status_akun',  '','')");
                if($sql) {
                    echo "Data mahasiswa dengan NIM $nim berhasil ditambahkan.<br>";
                } else {
                    echo "Gagal menambahkan data mahasiswa dengan NIM $nim.<br>";
                }
            }
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


 


             

                </div>
            
          </div>
        </div>
      </div>
      </div>

