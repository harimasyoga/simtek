<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<!-- <h1>Data Master </h1> -->
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
			<div class="card-header" style="font-family:Cambria;">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<?php if(in_array($this->session->userdata('level'), ['Admin','PPIC'])) { ?>
					<button type="button" style="font-family:Cambria;" class="tambah_data btn  btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</button>
				<?php } ?>
				
				<br><br>
				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center;width:10%">Id</th>
							<th style="text-align: center;width:50%">Level</th>
							<th style="text-align: center;width:40%">Aksi</th>
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
						<label class="col-sm-2 col-form-label">Nama Level</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nm_group" name="nm_group" placeholder="Nama">
							<input autocomplete="off" type="hidden" class="form-control" id="id_group">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Value</label>
						<div class="col-sm-10">
							<input autocomplete="off" type="text" class="form-control" id="val_group" name="val_group" placeholder="Value">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i> Simpan</button>
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
		load_data();
		// load_group();
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	});

	status = "insert";
	$(".tambah_data").click(function(event) {
		kosong();
		$("#modalForm").modal("show");
		$("#judul").html('<h3> Form Tambah Data</h3>');
		status = "insert";
	});

	function load_data() {
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Master/load_data/user_level',
				"type": "POST",
			},
			"aLengthMenu": [
				[5, 15, 20, 25, -1],
				[5, 15, 20, 25, "Semua"] // change per page values here
			],		
			responsive: true,
			"pageLength": 5,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function simpan() {
		nm_group    = $("#nm_group").val();
		val_group   = $("#val_group").val();

		if (nm_group == '' || val_group == '') {
			toastr.info('Harap Lengkapi Form');
			return;
		}

		$.ajax({
			url: '<?php echo base_url(); ?>/master/insert/' + status,
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
				nm_group,
				val_group,
				jenis: 'm_modul_group',
				status: status
			}),
			dataType: "JSON",
			success: function(data) {
				if (data) {
					swal({
						title               : "Data",
						html                : "Berhasil Disimpan",
						type                : "success",
						confirmButtonText   : "OK"
					});
					kosong();
					$("#modalForm").modal("hide");
					reloadTable();
				} else {
					swal({
						title               : "Cek Kembali",
						html                : "Data Sudah Ada",
						type                : "error",
						confirmButtonText   : "OK"
					});
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal({
					title               : "Cek Kembali",
					html                : "Terjadi Kesalahan atau Data sudah ada",
					type                : "error",
					confirmButtonText   : "OK"
				});
			}
		});

	}

	function kosong() {
		$("#id_group").val('');
		$("#val_group").val('');
		status = 'insert';
		$("#btn-simpan").show();
	}


	function tampil_edit(id, act) {
		kosong();
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

		status = "update";

		$.ajax({
				url: '<?php echo base_url('Master/get_edit'); ?>',
				type: 'POST',
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
				data: {
					id: id,
					jenis: "tb_user",
					field: 'username'
				},
				dataType: "JSON",
			})
			.done(function(data) {
				$("#username").prop("readonly", true);
				$("#username,#username_lama").val(data.username);
				$("#nm_user").val(data.nm_user);
				$("#password").val(atob(data.password));
				$('#level').val(data.level).trigger('change');
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
					jenis: 'tb_user',
					field: 'username'
				}),
				type: "POST",
				success: function(data) {
					toastr.success('Data Berhasil Di Hapus');
					reloadTable();
					swal.close()
				},
				error: function(jqXHR, textStatus, errorThrown) {
					toastr.error('Terjadi Kesalahan');
					swal.close()
				}
			});
		})
	}
</script>
