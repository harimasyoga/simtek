<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<!-- <h1><b>Data Master</b></h1> -->
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<!-- <li class="breadcrumb-item active"><a href="#">Sales</a></li> -->
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header" style="font-family:Cambria;">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<button type="button" style="font-family:Cambria;" class="tambah_data btn btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<br><br>
				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="width:5%">NO.</th>
							<th style="width:45%">NAMA</th>
							<th style="width:40%">NO. HP</th>
							<th style="width:10%">AKSI</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modalForm">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="judul"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table style="width:100%" cellspacing="5">
					<tr>
						<td style="border:0;padding:0"></td>
						<td style="border:0;padding:0"></td>
					</tr>
					<tr>
						<td style="font-weight:bold">NAMA SALES</td>
						<td>
							<input type="hidden" id="id_sales">
							<input class="form-control" type="text" name="nm_sales" id="nm_sales" autocomplete="off">
						</td>
					</tr>
					<tr>
						<td style="font-weight:bold">NO. HP / WA</td>
						<td>
							<input class="form-control" type="text" name="no_hp" id="no_hp" autocomplete="off">
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i> Simpan</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	status = "insert";
	$(document).ready(function() {
		load_data();
	});

	$(".tambah_data").click(function(event) {
		status = "insert"
		kosong();
		$("#judul").html('<h3> Form Tambah Data</h3>');
		$("#modalForm").modal("show");
	});

	function kosong(){
		status = "insert"
		$("#id_sales").val("")
		$("#nm_sales").val("")
		$("#no_hp").val("")
		$("#btn-simpan").prop("disabled", false);
	}

	function simpan(){
		$("#btn-simpan").prop("disabled", true);
		let id_sales = $("#id_sales").val()
		let nm_sales = $("#nm_sales").val()
		let no_hp = $("#no_hp").val()

		if(nm_sales == "" || no_hp == ""){
			toastr.info('Harap Lengkapi Form');
			return;
		}

		// alert(nm_sales+" - "+no_hp+" - "+status)
		$.ajax({
			url: '<?php echo base_url('Master/Insert/')?>'+status,
			type: "POST",
			data: ({
				id_sales, nm_sales, no_hp, jenis: "m_sales", status
			}),
			success: function(json){
				data = JSON.parse(json)
				if(data){
					toastr.success('Berhasil Disimpan');
					kosong();
					$("#modalForm").modal("hide");
				}else{
					toastr.error('Gagal Simpan!');
				}
				reloadTable()
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan')
			}
		})
	}

	function load_data() {
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Master/load_data/sales',
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

	function tampil_edit(id, act) {
		kosong();
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
			data: ({
				id,
				jenis: 'm_sales',
				field: 'id_sales',
			})
		})
		.done(function(json) {
			data = JSON.parse(json)
			// console.log(data)
			$("#id_sales").val(data.id_sales);
			$("#nm_sales").val(data.nm_sales);
			$("#no_hp").val(data.no_sales);
		})
	}

	function deleteData(id) {
		let cek = confirm("Apakah Anda Yakin?");
		if (cek) {
			$.ajax({
				url: '<?php echo base_url(); ?>Master/hapus',
				type: "POST",
				data: ({
					id,
					jenis: 'm_sales',
					field: 'id_sales'
				}),
				success: function(data) {
					toastr.success('Data Berhasil Di Hapus');
					reloadTable();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					toastr.error('Terjadi Kesalahan');
				}
			});
		}else{
			toastr.info('Gak Jadi');
		}
	}

</script>
