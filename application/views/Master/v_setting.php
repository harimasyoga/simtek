

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pengaturan </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li> -->
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Sistem</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form role="form" method="post" id="myForm">
        
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Aplikasi</label>
                  <input type="text" name="nm_aplikasi" id="nm_aplikasi" class="form-control" value="<?= $data->nm_aplikasi ?>">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Nama Toko</label>
                  <input type="text" name="nm_toko" id="nm_toko" value="<?= $data->nm_toko ?>" class="form-control">
                  
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Singkatan</label>
                  <input type="text" name="singkatan" id="singkatan" class="form-control" value="<?= $data->singkatan ?>">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" id="alamat" name="alamat"><?= $data->alamat ?></textarea>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-6">
                
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Diskon Member (%)</label>
                  <input type="text" name="diskon_member" id="diskon_member" value="<?= $data->diskon_member ?>" class="angka form-control">
                  
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>No Telepon</label>
                  <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?= $data->no_telp ?>">
                </div>
                
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-6">
                
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Gambar</label>
                  <input type="file" name="logo" id="logo" class="form-control">
                  <br>
                  <img src="<?= base_url('assets/gambar/').$data->logo ?>" width="50%">
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()">Simpan</button>
          </form>
         
        </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<script type="text/javascript">
  $(document).ready(function () {

  });

  
  function simpan(){
     var data = new FormData();
        data.append('logo', $("#logo")[0].files[0]);  
        data.append('nm_aplikasi', $("#nm_aplikasi").val());
        data.append('singkatan', $("#singkatan").val());
        data.append('diskon_member', $("#diskon_member").val());
        data.append('nm_toko', $("#nm_toko").val());
        data.append('alamat', $("textarea#alamat").val());
        data.append('no_telp', $("#no_telp").val());
        data.append('status', 'update');
        data.append('jenis', 'm_setting');

      $.ajax({
          url      : '<?php echo base_url(); ?>/master/insert',
          type: "POST",
          dataType: "JSON",
          data:data,  
          processData: false,
          contentType: false,  
          success: function(data)
          {           
              if (data) {
                toastr.success('Berhasil Disimpan'); 
                setTimeout(function () {
                  location.reload();  
                },1000);
                
              }else{
                toastr.error('Gagal Update'); 
              }
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             toastr.error('Terjadi Kesalahan'); 
          }
      });
  }

  
</script>