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
							<th style="width:15%">Username</th>
							<th style="width:22%">Nama</th>
							<th style="width:15%">Password</th>
							<th style="width:10%">Level</th>
							<th style="width:10%">Aksi</th>
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
						<label class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="username" placeholder="USERNAME">
							<input autocomplete="off" type="hidden" class="form-control" id="username_lama">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Nama user</label>
						<div class="col-sm-10">
							<input autocomplete="off" type="text" class="form-control" id="nm_user" placeholder="NAMA USER">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input autocomplete="off" type="password" class="form-control" id="password" placeholder="PASSWORD">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Level</label>
						<div class="col-sm-10">
							<select class="form-control select2" id="level">
								<!-- <option value="">PILIH</option>
								<?php if($this->session->userdata('level') != 'PPIC') { ?>
									<option value="Owner">Owner</option>
									<option value="Penjualan">Penjualan</option>
									<option value="Marketing">Marketing</option>
									<option value="User">Operator</option>
									<option value="Keuangan1">Keuangan</option>
								<?php } ?>
									<option value="PPIC">PPIC</option>
									<option value="Corrugator">Corrugator</option>
									<option value="Flexo">Flexo</option>
									<option value="Finishing">Finishing</option> -->
							</select>
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
		load_group();
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
				"url": '<?php echo base_url(); ?>Master/load_data/user',
				"type": "POST",
			},
			responsive: true,
			"pageLength": 25,
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
		username    = $("#username").val();
		password    = $("#password").val();
		nm_user     = $("#nm_user").val();
		level       = $("#level").val();

		if (username == '' || password == '' || nm_user == '') {
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
				username,
				password,
				nm_user,
				level,
				jenis: 'tb_user',
				status: status
			}),
			dataType: "JSON",
			success: function(data) {
				if (data) {
					toastr.success('Berhasil Disimpan');
					kosong();
					$("#modalForm").modal("hide");
					reloadTable();
				} else {
					toastr.error('Username Sudah Ada');
				}
				swal.close()
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan atau username sudah tersedia');
			}
		});

	}

	function kosong() {
		$("#username").val('');
		$("#username_lama").val('');
		$("#nm_user").val('');
		$("#password").val('');
		status = 'insert';
		$("#btn-simpan").show();
		$("#username").prop("readonly", false);
	}

	function load_group()
	{
		option = "";
		$.ajax({
			type: 'POST',
			url: "<?= base_url(); ?>Master/load_group",
			dataType: 'json',
			success:function(data){								
				option = "<option>-- Pilih --</option>";
				$.each(data, function(index, val) {
				option += "<option value='"+val.val_group+"'>"+val.nm_group+"</option>";
				});

				$('#level').html(option);
			}
			
		});
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
