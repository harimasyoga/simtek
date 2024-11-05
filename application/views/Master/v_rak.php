<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"></div>
				<div class="col-sm-6"></div>
			</div>
		</div>
	</section>

	<style>
		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>

	<section class="content">
		<div class="card card-rak">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<div style="margin-bottom:16px">
					<button type="button" style="font-family:Cambria;" class="btn btn-info pull-right" onclick="addBack('tambah')">
						<i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b>
					</button>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="width:90%;text-align:center">RAK</th>
								<th style="width:10%;text-align:center">AKSI</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- INPUT DATA -->
		<div class="row row-input-data" style="display:none">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT MASTER RAK</h3>
					</div>
					<div class="card-body" style="padding:12px">
						<div style="margin-bottom:12px">
							<button type="button" class="btn btn-sm btn-info pull-right" onclick="addBack('kembali')"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;<b>Kembali</b></button>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">KATEGORI <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="kategori" class="form-control select2" <?php ($approve == 'ALL') ? '' : 'disabled'?>>
									<?php
										if($approve == 'OFFICE'){
											echo '<option value="OFFICE">OFFICE</option>';
										}else if($approve == 'GUDANG'){
											echo '<option value="GUDANG">GUDANG</option>';
										}else{
											echo '<option value="OFFICE">OFFICE</option><option value="GUDANG">GUDANG</option>';
										}
									?>
								</select>
							</div>
							<div class="col-md-6"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">NAMA RAK</div>
							<div class="col-md-4">
								<input type="hidden" id="h_id_rak" value="">
								<input type="hidden" id="h_nm_rak" value="">
								<input type="text" id="nm_rak" class="form-control" placeholder="NAMA RAK" onchange="namaRak()" onkeyup="clearBg('nm_rak')" oninput="this.value=this.value.toUpperCase()">
								<div id="k_nm_rak"></div>
							</div>
							<div class="col-md-6"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<div class="simpan_barang"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- INPUT DATA -->
	</section>
</div>

<script type="text/javascript">
	status = "insert";
	const urlAuth = '<?= $this->session->userdata('level')?>';

	$(document).ready(function() {
		$(".select2").select2()
		load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function addBack(opsi)
	{
		status = 'insert';
		$("#h_nm_rak").val('')
		$("#nm_rak").val('').removeClass('is-valid').removeClass('is-invalid')
		$("#k_nm_rak").html('')
		if(opsi == 'tambah'){
			$(".card-rak").hide()
			$(".row-input-data").show()
			$(".simpan_barang").html('<button type="button" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpanRak()"><i class="fas fa-plus"></i> TAMBAH</button>')
		}else{
			reloadTable()
			$(".card-rak").show()
			$(".row-input-data").hide()
			$(".simpan_barang").html('<button type="button" class="btn btn-sm btn-warning" style="font-weight:bold" onclick="simpanRak()"><i class="fas fa-edit"></i> EDIT</button>')
		}
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
				"url": '<?php echo base_url(); ?>Master/loadDataRak',
				"type": "POST"
			},
			responsive: false,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function namaRak()
	{
		let kategori = $("#kategori").val()
		let nm_rak = $("#nm_rak").val()
		$.ajax({
			url: '<?php echo base_url('Master/cekNamaRak')?>',
			type: "POST",
			data : ({ kategori, nm_rak }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					$("#nm_rak").removeClass('is-invalid').addClass('is-valid')
					$("#k_nm_rak").html('')
				}else{
					$("#nm_rak").removeClass('is-valid').addClass('is-invalid')
					$("#k_nm_rak").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
				}
				$("#nm_rak").val(data.cleanTxt)
			}
		})
	}

	function clearBg(opsi)
	{
		$("#"+opsi).removeClass('is-invalid').removeClass('is-valid')
		$("#"+opsi).html('')
	}

	function simpanRak()
	{
		let kategori = $("#kategori").val()
		let h_id_rak = $("#h_id_rak").val()
		let h_nm_rak = $("#h_nm_rak").val()
		let nm_rak = $("#nm_rak").val()
		$.ajax({
			url: '<?php echo base_url('Master/simpanRak')?>',
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
			data : ({ kategori, h_id_rak, h_nm_rak, nm_rak, status }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					toastr.success(`<b>BERHASIL SIMPAN!</b>`)
					addBack('kembali')
				}else{
					toastr.error(`<b>${data.msg}</b>`)
				}
				swal.close()
			}
		})
	}

	function editRak(id_rak)
	{
		$(".card-rak").hide()
		$(".row-input-data").show()
		$.ajax({
			url: '<?php echo base_url('Master/editRak')?>',
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
			data : ({ id_rak }),
			success: function(res){
				data = JSON.parse(res)
				status = 'update'
				$("#kategori").val(data.rak.kategori_rak)
				$("#h_id_rak").val(id_rak)
				$("#h_nm_rak").val(data.rak.nm_rak)
				$("#nm_rak").val(data.rak.nm_rak)
				$(".simpan_barang").html('<button type="button" class="btn btn-sm btn-warning" style="font-weight:bold" onclick="simpanRak()"><i class="fas fa-edit"></i> EDIT</button>')
				swal.close()
			}
		})
	}

	function hapusRak(id_rak)
	{
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Master/hapusRak')?>',
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
				data : ({ id_rak }),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					if(data.data){
						toastr.success(`<b>BERHASIL HAPUS!</b>`)
						reloadTable()
					}else{
						toastr.error(`<b>TERJADI KESALAHAN</b>`)
					}
					swal.close()
				}
			})
		})
	}
</script>
