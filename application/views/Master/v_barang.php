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
					<thead class="color-tabel">
						<tr>
							<th style="text-align: center; width:5%">NO</th>
							<th style="text-align: center; width:24%">NAMA</th>
							<th style="text-align: center; width:24%">ALAMAT</th>
							<th style="text-align: center; width:24%">NO HP</th>
							<th style="text-align: center; width:24%">JT</th>
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
						<label class="col-sm-2 col-form-label">NAMA SUPPLIER</label>
						<div class="col-sm-10">
							<input type="hidden" class="form-control" id="id_supp">
							<input type="hidden" class="form-control" id="nm_supp_old" value="">
							<input type="text" class="form-control" id="nm_supp" placeholder="NAMA SUPPLIER" autocomplete="off" maxlength="100" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">ALAMAT</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="alamat" placeholder="ALAMAT" autocomplete="off" oninput="this.value = this.value.toUpperCase()">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">NO HP</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="no_hp" placeholder="NO HP" autocomplete="off" maxlength="15">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">JATUH TEMPO</label>
						<div class="col-sm-10">
							<input type="text" class="angka form-control" id="jt" placeholder="JATUH TEMPO" autocomplete="off" >
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
				"url": '<?php echo base_url(); ?>Master/load_data/supplier',
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
		nm_supp_old   = $("#nm_supp_old").val();
		id_supp       = $("#id_supp").val();
		nm_supp       = $("#nm_supp").val();
		alamat        = $("#alamat").val();
		no_hp         = $("#no_hp").val();
		jt            = $("#jt").val();

		if ( nm_supp == "" || alamat == "" || no_hp == "" || jt == "") {
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
				 id_supp ,nm_supp,alamat,no_hp,jt,nm_supp_old,jenis: 'm_supplier',status: status
			}),
			success: function(res) {
				data = JSON.parse(res)
				console.log(data)
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
		$("#id_supp").val("");
		$("#nm_supp_old").val("");		
		$("#nm_supp").val("");			
		$("#alamat").val("");			
		$("#no_hp").val("");			
		$("#jt").val("");			
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
			url: '<?php echo base_url('Master/edit_supp'); ?>',
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

			$("#id_supp").val(data.supp.id_supp);
			$("#nm_supp_old").val(data.supp.nm_supp);
			$("#nm_supp").val(data.supp.nm_supp);
			$("#alamat").val(data.supp.alamat);
			$("#no_hp").val(data.supp.no_hp);
			$("#jt").val(data.supp.jt);
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
					jenis: 'm_supplier',
					field: 'id_supp'
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
