
  <!-- <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.5
    </div>
    
  </footer> -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/chart.js/Chart.min.js"></script>

<!-- DataTables -->
<script src="<?= base_url('assets/') ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="<?= base_url('assets/') ?>plugins/bootstrap-typeahead.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('assets/') ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/sweetalert2/sweetalert2.js"></script>

<!-- <script src="<?= base_url('assets/') ?>plugins/sweetalert/sweetalert.min.js"></script> -->
<!-- Toastr -->
<script src="<?= base_url('assets/') ?>plugins/toastr/toastr.min.js"></script>


<!-- Ekko Lightbox -->
<script src="<?= base_url('assets/') ?>plugins/ekko-lightbox/ekko-lightbox.min.js"></script>

<!-- Filterizr-->
<script src="<?= base_url('assets/') ?>plugins/filterizr/jquery.filterizr.min.js"></script>

<!-- Select2 -->
<script src="<?= base_url('assets/') ?>plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="<?= base_url('assets/') ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('assets/') ?>dist/js/demo.js"></script>

<script type="text/javascript">
  $(function() {

    // const Toast = Swal.mixin({
    //   toast: true,
    //   position: 'top-end',
    //   showConfirmButton: false,
    //   timer: 3000
    // });

    $("input.angka").keypress(function(event) { //input text number only
      return /\d/.test(String.fromCharCode(event.keyCode));
    });

    
    $("#loading").modal("hide");
    
      
  });

  function ubah_angka(cek,id){
    if(cek==''){
      $("#"+id).val('')
    }else{
      cek1 = cek.split('.').join('')
      hasil= format_angka(parseInt(cek1))
      $("#"+id).val(hasil)
    }
  }
  
  function format_angka(num) 
  {

    num                   = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    num                   = "0";
    sign                  = (num == (num = Math.abs(num)));
    num                   = Math.floor(num*100+0.50000000001);
    cents                 = num%100;
    num                   = Math.floor(num/100).toString();
    if(cents<10)
    cents                 = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num                   = num.substring(0,num.length-(4*i+3))+'.'+
    num.substring(num.length-(4*i+3));
    //return (((sign)?'':'-') + '' + num + '.' + cents);
    return (((sign)?''    : '-') + '' + num);
  }

  function alltrim(kata) {
    b = (kata.split(' ').join(''));
    c = (b.replace(/\s/g, ""));
    return c
  }

  function show_loading()
  {
    $('#loading').show();
    $('#loading').modal('show');
  }
 
  function close_loading()
  {    
    $('#loading').hide();
    $('.modal-backdrop').hide();
  }

</script>

</body>
</html>
