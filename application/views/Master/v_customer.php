<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<!-- <h1><b>Data Master</b></h1> -->
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<!-- <li class="breadcrumb-item active"><a href="#"><?= $judul ?></a></li> -->
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="card">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">

				<button type="button" style="font-family:Cambria;" class="tambah_data btn  btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<br><br>

				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center; width:5%">NO.</th>
							<th style="text-align: center; width:24%">Pimpinan</th>
							<th style="text-align: center; width:24%">Nama Instansi</th>
							<th style="text-align: center; width:34%">Alamat</th>
							<th style="text-align: center; width:34%">NPWP</th>
							<th style="text-align: center; width:34%">No Telp</th>
							<th style="text-align: center; width:10%">AKSI</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- /.card -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modalForm">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" method="post" id="myForm">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">PIMPINAN</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="pimpinan" placeholder="ATAS NAMA" autocomplete="off" maxlength="50" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">NAMA INSTANSI</label>
						<div class="col-sm-10">
							<input type="hidden" class="form-control" id="id_cs">
							<input type="hidden" class="form-control" id="nm_cs_old">
							<input type="text" class="form-control" id="nm_cs" placeholder="NAMA PELANGGAN" autocomplete="off" maxlength="50" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">ALAMAT</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="alamat" placeholder="ALAMAT" oninput="this.value = this.value.toUpperCase()"></textarea>
						</div>
					</div>				
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">NPWP</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="npwp" placeholder="-" autocomplete="off" >
						</div>
					</div>	
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">NO TELP</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="no_telp" placeholder="-" autocomplete="off" maxlength="16">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">KODE POS</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="kode_pos" placeholder="-" autocomplete="off" maxlength="10">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i> Simpan</button>

				<button type="button" class="btn btn-danger" data-dismiss="modalForm" onclick="close_modal();" ><i class="fa fa-times-circle"></i> <b> Batal</b></button>
			</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
	rowNum = 0;
	$(document).ready(function() {
		$(".select2").select2()
		load_data()
	});

	status = "insert";
	$(".tambah_data").click(function(event) {
		kosong()
		$("#modalForm").modal("show")
		$("#judul").html('<h3> Form Tambah Data</h3>')
		status = "insert"
	});

	function close_modal(){
		$('#modalForm').modal('hide');
	}

	function load_data() 
	{
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Master/load_data/customer',
				"type": "POST",
			},
			responsive: true,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function simpan() 
	{
		$("#btn-simpan").prop("disabled", true);
		id_cs       = $("#id_cs").val();
		pimpinan    = $("#pimpinan").val();
		nm_cs       = $("#nm_cs").val();
		nm_cs_old   = $("#nm_cs_old").val();
		alamat      = $("#alamat").val();
		npwp        = $("#npwp").val();
		no_telp     = $("#no_telp").val();
		kode_pos    = $("#kode_pos").val();

		if ( pimpinan == "" || nm_cs == "" || alamat == "" ) {
			swal("HARAP LENGKAPI FORM!", "", "info")
			$("#btn-simpan").prop("disabled", false);
			return;
		}

		$.ajax({
			url: '<?php echo base_url(); ?>/Master/Insert/'+status,
			type: "POST",
			beforeSend: function() {
				swal({
					title: 'Loading',
					allowEscapeKey: false,
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				 id_cs, pimpinan ,nm_cs, nm_cs_old ,alamat ,npwp ,no_telp ,kode_pos, jenis: 'm_customer', status: status
			}),
			success: function(res) {
				data = JSON.parse(res)
				// console.log(data)
				if(data.data){
					swal("BERHASIL DISIMPAN!", "", "success")
					kosong();
					$("#modalForm").modal("hide");
					reloadTable();
				}else{
					swal(data.isi, "", "error")
					$("#btn-simpan").prop("disabled", false);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan');
				swal.close()
			}
		});
	}

	function kosong() 
	{
		$("#id_cs").val("");
		$("#pimpinan").val("");
		$("#nm_cs_old").val("");
		$("#nm_cs").val("");
		$("#alamat").val("");
		$("#npwp").val("");
		$("#no_telp").val("");
		$("#kode_pos").val("");
		status = 'insert';
		$("#btn-simpan").show().prop("disabled", false);
	}

	function tampil_edit(id, act) 
	{
		status = 'update';
		$("#modalForm").modal("show");
		if (act == 'detail') {
			$("#judul").html('<h3> Detail Data</h3>');
			$("#btn-simpan").hide();
		} else {
			$("#judul").html('<h3> Form Edit Data</h3>');
			$("#btn-simpan").show();
		}

		$("#jenis").val('Update');
		$.ajax({
			url: '<?php echo base_url('Master/edit_cs'); ?>',
			type: 'POST',
			beforeSend: function() {
				swal({
					title                : 'Loading',
					allowEscapeKey       : false,
					allowOutsideClick    : false,
					onOpen: () => {
						swal.showLoading();
					}
				});
			},
			data: ({
				id,
			}),
		})
		.done(function(json) {
			data = JSON.parse(json)
			// console.log(data)

			$("#id_cs").val(data.cs.id_cs);
			$("#pimpinan").val(data.cs.pimpinan);
			$("#nm_cs_old").val(data.cs.nm_cs);
			$("#nm_cs").val(data.cs.nm_cs);
			$("#alamat").val(data.cs.alamat);
			$("#npwp").val(data.cs.npwp);
			$("#no_telp").val(data.cs.no_telp);
			$("#kode_pos").val(data.cs.kode_pos);
			swal.close()
		})
	}


	function deleteData(id) {
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url(); ?>Master/hapus',
				beforeSend: function() {
					swal({
						title: 'Loading',
						allowEscapeKey: false,
						allowOutsideClick: false,
						onOpen: () => {
							swal.showLoading();
						}
					});
				},
				data: ({
					id: id,
					jenis: 'm_customer',
					field: 'id_cs'
				}),
				type: "POST",
				success: function(data) {
					swal.close()
					toastr.success('Data Berhasil Di Hapus');
					reloadTable();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					swal.close()
					toastr.error('Terjadi Kesalahan');
				}
			});
		})
	}
</script>
