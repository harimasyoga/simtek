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

	<style>
		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>

	<section class="content">
		<div class="row row-list">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST OPB</h3>
					</div>
					<div class="card-body" style="padding:0">
						<div style="padding:5px">
							<button type="button" class="btn btn-primary btn-sm" onclick="tambah()">Tambah Data</button>
							<button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
							<div class="float-right">
								<button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row row-input">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT OPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">PILIH BARANG <span style="color:#f00">*</span></div>
							<div class="col-md-5">
								<select id="plh_barang" class="form-control select2" onchange="pilihBarang()">
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">JENIS / TIPE</div>
							<div class="col-md-5">
								<div class="jenistipe">
									<select id="jenistipe" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MATERIAL</div>
							<div class="col-md-5">
								<div class="material">
									<select id="material" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">SIZE</div>
							<div class="col-md-5">
								<div class="ukuran">
									<select id="ukuran" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MERK</div>
							<div class="col-md-5">
								<div class="merk">
									<select id="merk" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="padding:0">
							<div class="col-md-12">
								<div style="overflow:auto;white-space:nowrap">
									<div class="list-detail"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_mbh" value="">
			<input type="hidden" id="id_cart" value="0">
			<input type="hidden" id="destroy">
		</div>
	</section>
</div>

<script type="text/javascript">
	const urlAuth = '<?= $this->session->userdata('level')?>';
	status = 'insert';
	$(document).ready(function() {
		// $("#destroy").load("<?php echo base_url('Master/destroy') ?>")
		$(".select2").select2()
		// load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function tambah()
	{
		loadBarang()
	}

	// function load_data() 
	// {
	// 	var table = $('#datatable').DataTable();
	// 	table.destroy();
	// 	tabel = $('#datatable').DataTable({
	// 		"processing": true,
	// 		"pageLength": true,
	// 		"paging": true,
	// 		"ajax": {
	// 			"url": '<?php echo base_url(); ?>Master/loadDataBarang',
	// 			"type": "POST",
	// 		},
	// 		responsive: false,
	// 		"pageLength": 10,
	// 		"language": {
	// 			"emptyTable": "Tidak ada data.."
	// 		}
	// 	});
	// }

	function loadBarang()
	{
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadBarang')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				$("#id_mbh").val('')
				$("#plh_barang").html(data.html)
				$("#jenistipe").html('<option value="">PILIH</option>')
				$("#material").html('<option value="">PILIH</option>')
				$("#ukuran").html('<option value="">PILIH</option>')
				$("#merk").html('<option value="">PILIH</option>')
				$(".list-detail").html('')
			}
		})
	}

	function pilihBarang()
	{
		let id_mbh = $("#plh_barang").val()
		let id_mbh_lama = $("#id_mbh").val()
		let jenistipe = $("#jenistipe").val()
		let material = $("#material").val()
		let ukuran = $("#ukuran").val()
		let merk = $("#merk").val()
		$("#jenistipe").html('<option value="">PILIH</option>')
		$("#material").html('<option value="">PILIH</option>')
		$("#ukuran").html('<option value="">PILIH</option>')
		$(".list-detail").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/detailBarang')?>',
			type: "POST",
			data: ({
				id_mbh, id_mbh_lama, jenistipe, material, ukuran, merk
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				$("#id_mbh").val(id_mbh)
				$(".list-detail").html(data.html)
				$("#jenistipe").html(data.htmlJT).val((id_mbh == id_mbh_lama) ? jenistipe : '')
				$("#material").html(data.htmlM).val((id_mbh == id_mbh_lama) ? material : '')
				$("#ukuran").html(data.htmlS).val((id_mbh == id_mbh_lama) ? ukuran : '')
				$("#merk").html(data.htmlMr).val((id_mbh == id_mbh_lama) ? merk : '')
			}
		})
	}
</script>
