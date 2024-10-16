<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right"></ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card card-list">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<button type="button" style="font-family:Cambria;" class="tambah_data btn btn-info pull-right" onclick="tambahKembali('tambah')"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<br><br>
				<div style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="text-align:center;width:5%">NO</th>
								<th style="text-align:center;width:85%">SATUAN</th>
								<th style="text-align:center;width:10%">AKSI</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- INPUT DATA -->
		<div class="row row-input" style="display:none">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT MASTER SATUAN</h3>
					</div>
					<div class="card-body" style="padding:12px">
						<div style="margin-bottom:12px">
							<button type="button" class="btn btn-sm btn-info pull-right" onclick="tambahKembali('kembali')"><i class="fas fa-arrow-left"></i> <b>Kembali</b></button>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">KODE SATUAN <span style="color:#f00">*</span></div>
							<div class="col-md-10">
								<input type="text" id="kode_satuan" class="form-control" placeholder="-" oninput="this.value=this.value.toUpperCase()">
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">KETERANGAN</div>
							<div class="col-md-10">
								<input type="text" id="ket_satuan" class="form-control" placeholder="-" oninput="this.value=this.value.toUpperCase()">
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<button type="button" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpanSatuan()"><i class="fas fa-save"></i> SIMPAN</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_satuan" value="">
		</div>
	</section>
</div>

<script type="text/javascript">
	status = "insert";
	$(document).ready(function() {
		$(".select2").select2()
		load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function kosong()
	{
		$("#id_satuan").val('')
		$("#kode_satuan").val('')
		$("#ket_satuan").val('')
		status = 'insert';
		swal.close()
	}

	function tambahKembali(opsi)
	{
		if(opsi == 'tambah'){
			$(".card-list").hide()
			$(".row-input").show()
		}else{
			$(".row-input").hide()
			$(".card-list").show()
			reloadTable()
		}
		kosong()
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
				"url": '<?php echo base_url(); ?>Master/loadDataSatuan',
				"type": "POST",
			},
			responsive: false,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	$("#kode_satuan").on({
		keydown: function(e) {
			if (e.which === 32) return false
		},
		keyup: function() {
			this.value = this.value.toUpperCase()
		},
		change: function() {
			this.value = this.value.replace(/\s/g, "")
		}
	});

	function simpanSatuan()
	{
		let id = $("#id_satuan").val()
		let kode_satuan = $("#kode_satuan").val()
		let ket_satuan = $("#ket_satuan").val()
		$.ajax({
			url: '<?php echo base_url('Master/simpanSatuan')?>',
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
				id, kode_satuan, ket_satuan, status
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					toastr.success(`<b>BERHASIL</b>`)
					tambahKembali('kembali')
				}else{
					toastr.error(`<b>${data.msg}</b>`)
					swal.close()
				}
			}
		})
	}

	function editSatuan(id)
	{
		$(".card-list").hide()
		$(".row-input").show()
		$.ajax({
			url: '<?php echo base_url('Master/editSatuan')?>',
			type: "POST",
			data: ({ id }),
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
			success: function(res){
				data = JSON.parse(res)
				$("#id_satuan").val(id)
				$("#kode_satuan").val(data.satuan.kode_satuan)
				$("#ket_satuan").val(data.satuan.ket_satuan)
				status = 'update'
				swal.close()
			}
		})
	}

	function hapusSatuan(id)
	{
		let id_mbh = $("#id_mbh").val()
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Master/hapus')?>',
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
				data : ({
					jenis: 'm_satuan', field: 'id', id
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data){
						toastr.success(`<b>BERHASIL HAPUS SATUAN</b>`)
						tambahKembali('kembali')
					}
				}
			})
		});
	}
</script>
