<div class="content-wrapper">
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
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<div style="margin-bottom:16px">
					<button type="button" style="font-family:Cambria;" class="tambah_data btn btn-info pull-right">
						<i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b>
					</button>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="text-align:center">NO</th>
								<th style="text-align:center">SUPPLIER</th>
								<th style="text-align:center">KODE BARANG</th>
								<th style="text-align:center">NAMA BARANG</th>
								<th style="text-align:center">KETERANGAN</th>
								<th style="text-align:center">HARGA</th>
								<th style="text-align:center">AKSI</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- INPUT DATA -->
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT MASTER BARANG</h3>
					</div>
					<div class="card-body" style="padding:12px">
						<div style="margin-bottom:6px">
							<button type="button" class="btn btn-sm btn-info pull-right">
								<i class="fas fa-arrow-left"></i>&nbsp;&nbsp;<b>Kembali</b>
							</button>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">NAMA BARANG</div>
							<div class="col-md-5">
								<select id="i_barang" class="form-control select2" onchange="namaBarang('lama')">
									<?php
										$query = $this->db->query("SELECT*FROM m_barang_header ORDER BY nm_barang");
										$html = '';
										$html .='<option value="">PILIH</option>';
										foreach($query->result() as $r){
											$html .='<option value="'.$r->id_mbh.'">'.$r->nm_barang.'</option>';
										}
										$html .='<option value="+">+</option>';
										echo $html
									?>
								</select>
							</div>
							<div class="col-md-5">
								<div id="p_barang" style="display:none">
									<input type="text" id="n_barang" class="form-control" style="font-weight:bold" autocomplete="off" onchange="namaBarang('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="NAMA BARANG BARU">
									<div id="k_barang"></div>
								</div>
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">JENIS / TIPE</div>
							<div class="col-md-5">
								<select id="i_jenis_tipe" class="form-control select2" onchange="jenisTipe('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5">
								<div id="p_jenis_tipe" style="display:none">
									<input type="text" id="n_jenis_tipe" class="form-control" style="font-weight:bold" autocomplete="off" onchange="jenisTipe('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="JENIS / TIPE BARU">
									<div id="k_jenis_tipe"></div>
								</div>
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MATERIAL</div>
							<div class="col-md-5">
								<select id="i_material" class="form-control select2" onchange="material('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5">
								<div id="p_material" style="display:none">
									<input type="text" id="n_material" class="form-control" style="font-weight:bold" autocomplete="off" onchange="material('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="MATERIAL BARU">
									<div id="k_material"></div>
								</div>
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">SIZE</div>
							<div class="col-md-5">
								<select id="i_size" class="form-control select2" onchange="ukuran('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5">
								<div id="p_size" style="display:none">
									<input type="text" id="n_size" class="form-control" style="font-weight:bold" autocomplete="off" onchange="ukuran('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="SIZE BARU">
									<div id="k_size"></div>
								</div>
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MERK</div>
							<div class="col-md-5">
								<select id="i_merk" class="form-control select2" onchange="merk('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5">
								<div id="p_merk" style="display:none">
									<input type="text" id="n_merk" class="form-control" style="font-weight:bold" autocomplete="off" onchange="merk('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="MERK BARU">
									<div id="k_merk"></div>
								</div>
							</div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<button type="button" class="btn btn-sm btn-success" style="font-weight:bold" onclick="addBarang()">ADD</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_cart" value="0">
		</div>
		<!-- INPUT DATA -->
	</section>
</div>

<script type="text/javascript">
	status = "insert";
	$(document).ready(function() {
		$(".select2").select2()
		// load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function kosong() 
	{			
		status = 'insert';
	}

	function hideAll(opsi){
		$("#i_"+opsi).html('<option value="">PILIH</option>').prop('disabled', true)
		$("#p_"+opsi).attr('style', 'display:none')
		$("#n_"+opsi).val('').removeClass('is-valid').removeClass('is-invalid')
		$("#k_"+opsi).html('')
	}
		
	function namaBarang(opsi)
	{
		if(opsi == 'lama'){
			let barang = $("#i_barang").val()
			$("#n_barang").val('').removeClass('is-valid').removeClass('is-invalid')
			$("#k_barang").html('');
			(barang == '+') ? $("#p_barang").attr('style', 'padding-top:4px') : $("#p_barang").attr('style', 'display:none');
			loadJenisTipe('lama')
		}
		if(opsi == 'baru'){
			$("#n_barang").removeClass('is-invalid').removeClass('is-valid')
			let n_barang = $("#n_barang").val()
			$.ajax({
				url: '<?php echo base_url('Master/cekNamaBarang')?>',
				type: "POST",
				data : ({ n_barang }),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					if(data.data){
						$("#n_barang").removeClass('is-invalid').addClass('is-valid')
						$("#k_barang").html('')
						loadJenisTipe('baru')
					}else{
						$("#n_barang").removeClass('is-valid').addClass('is-invalid')
						$("#k_barang").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
						loadJenisTipe('lama')
					}
					$("#n_barang").val(data.cleanTxt)
				}
			})
		}
	}

	function loadJenisTipe(opsi)
	{
		let barang = $("#i_barang").val()
		$("#p_jenis_tipe").attr('style', 'display:none')
		if(opsi == 'lama' && barang != '+' && barang != ''){
			$("#i_jenis_tipe").prop('disabled', true)
			$.ajax({
				url: '<?php echo base_url('Master/loadJenisTipe')?>',
				type: "POST",
				data : ({
					barang, cari: '',
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_jenis_tipe").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
		}else{
			$("#i_jenis_tipe").html('<option value="">PILIH</option>').prop('disabled', true)
		}

		if(opsi == 'baru'){
			$.ajax({
				url: '<?php echo base_url('Master/loadJenisTipe')?>',
				type: "POST",
				data : ({
					barang, cari: 'jenis_tipe',
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_jenis_tipe").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
			let jenis_tipe = $("#i_jenis_tipe").val()
			$("#n_jenis_tipe").val('').removeClass('is-valid').removeClass('is-invalid');
			(jenis_tipe == '+') ? $("#p_jenis_tipe").attr('style', 'padding-top:4px') : $("#p_jenis_tipe").attr('style', 'display:none');
		}

		// HIDE
		hideAll('material')
		hideAll('size')
		hideAll('merk')
	}

	function jenisTipe(opsi)
	{
		if(opsi == 'lama'){
			let jenis_tipe = $("#i_jenis_tipe").val()
			$("#n_jenis_tipe").val('').removeClass('is-valid').removeClass('is-invalid')
			$("#k_jenis_tipe").html('');
			(jenis_tipe == '+') ? $("#p_jenis_tipe").attr('style', 'padding-top:4px') : $("#p_jenis_tipe").attr('style', 'display:none');
			loadMaterial('lama')
		}
		if(opsi == 'baru'){
			$("#n_jenis_tipe").removeClass('is-invalid').removeClass('is-valid')
			let id_mbh = $("#i_barang").val()
			let n_jenis_tipe = $("#n_jenis_tipe").val()
			$.ajax({
				url: '<?php echo base_url('Master/cekJenisTipe')?>',
				type: "POST",
				data : ({
					n_jenis_tipe, id_mbh
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						$("#n_jenis_tipe").removeClass('is-invalid').addClass('is-valid')
						$("#k_jenis_tipe").html('')
						loadMaterial('baru')
					}else{
						$("#n_jenis_tipe").removeClass('is-valid').addClass('is-invalid')
						$("#k_jenis_tipe").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
						loadMaterial('lama')
					}
					$("#n_jenis_tipe").val(data.cleanTxt)
				}
			})
		}
	}

	function loadMaterial(opsi)
	{
		let barang = $("#i_barang").val()
		let jenis_tipe = $("#i_jenis_tipe").val()
		$("#p_material").attr('style', 'display:none')
		if(opsi == 'lama' && jenis_tipe != '+' && jenis_tipe != ''){
			$("#i_material").prop('disabled', true)
			$.ajax({
				url: '<?php echo base_url('Master/loadMaterial')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, cari: ''
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_material").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
		}else{
			$("#i_material").html('<option value="">PILIH</option>').prop('disabled', true)
		}

		if(opsi == 'baru'){
			$.ajax({
				url: '<?php echo base_url('Master/loadMaterial')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, cari: 'material'
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_material").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
			let material = $("#i_material").val()
			$("#n_material").val('').removeClass('is-valid').removeClass('is-invalid');
			(material == '+') ? $("#p_material").attr('style', 'padding-top:4px') : $("#p_material").attr('style', 'display:none');
		}

		// HIDE
		hideAll('size')
		hideAll('merk')
	}

	function material(opsi)
	{
		if(opsi == 'lama'){
			let jenis_tipe = $("#i_material").val()
			$("#n_material").val('').removeClass('is-valid').removeClass('is-invalid')
			$("#k_material").html('');
			(jenis_tipe == '+') ? $("#p_material").attr('style', 'padding-top:4px') : $("#p_material").attr('style', 'display:none');
			loadSize('lama')
		}
		if(opsi == 'baru'){
			$("#n_material").removeClass('is-invalid').removeClass('is-valid')
			let id_mbh = $("#i_barang").val()
			let i_jenis_tipe = $("#i_jenis_tipe").val()
			let n_material = $("#n_material").val()
			$.ajax({
				url: '<?php echo base_url('Master/cekMaterial')?>',
				type: "POST",
				data : ({
					id_mbh, i_jenis_tipe, n_material
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						$("#n_material").removeClass('is-invalid').addClass('is-valid')
						$("#k_material").html('')
						loadSize('baru')
					}else{
						$("#n_material").removeClass('is-valid').addClass('is-invalid')
						$("#k_material").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
						loadSize('lama')
					}
					$("#n_material").val(data.cleanTxt)
				}
			})
		}
	}

	function loadSize(opsi)
	{
		let barang = $("#i_barang").val()
		let jenis_tipe = $("#i_jenis_tipe").val()
		let material = $("#i_material").val()
		$("#p_size").attr('style', 'display:none')
		if(opsi == 'lama' && material != '+' && material != ''){
			$("#i_size").prop('disabled', true)
			$.ajax({
				url: '<?php echo base_url('Master/loadSize')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, material, cari: ''
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_size").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
		}else{
			$("#i_size").html('<option value="">PILIH</option>').prop('disabled', true)
		}

		if(opsi == 'baru'){
			$.ajax({
				url: '<?php echo base_url('Master/loadSize')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, material, cari: 'size'
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_size").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
			let size = $("#i_size").val()
			$("#n_size").val('').removeClass('is-valid').removeClass('is-invalid');
			(size == '+') ? $("#p_size").attr('style', 'padding-top:4px') : $("#p_size").attr('style', 'display:none');
		}

		// HIDE
		hideAll('merk')
	}

	function ukuran(opsi)
	{
		if(opsi == 'lama'){
			let size = $("#i_size").val()
			$("#n_size").val('').removeClass('is-valid').removeClass('is-invalid')
			$("#k_size").html('');
			(size == '+') ? $("#p_size").attr('style', 'padding-top:4px') : $("#p_size").attr('style', 'display:none');
			loadMerk('lama')
		}
		if(opsi == 'baru'){
			$("#n_size").removeClass('is-invalid').removeClass('is-valid')
			let id_mbh = $("#i_barang").val()
			let i_jenis_tipe = $("#i_jenis_tipe").val()
			let i_material = $("#i_material").val()
			let n_size = $("#n_size").val()
			$.ajax({
				url: '<?php echo base_url('Master/cekSize')?>',
				type: "POST",
				data : ({
					id_mbh, i_jenis_tipe, i_material, n_size
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						$("#n_size").removeClass('is-invalid').addClass('is-valid')
						$("#k_size").html('')
						loadMerk('baru')
					}else{
						$("#n_size").removeClass('is-valid').addClass('is-invalid')
						$("#k_size").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
						loadMerk('lama')
					}
					$("#n_size").val(data.cleanTxt)
				}
			})
		}
	}

	function loadMerk(opsi)
	{
		let barang = $("#i_barang").val()
		let jenis_tipe = $("#i_jenis_tipe").val()
		let material = $("#i_material").val()
		let size = $("#i_size").val()
		$("#p_merk").attr('style', 'display:none')
		if(opsi == 'lama' && size != '+' && size != ''){
			$("#i_merk").prop('disabled', true)
			$.ajax({
				url: '<?php echo base_url('Master/loadMerk')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, material, size, cari: ''
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_merk").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
		}else{
			$("#i_merk").html('<option value="">PILIH</option>').prop('disabled', true)
		}

		if(opsi == 'baru'){
			$.ajax({
				url: '<?php echo base_url('Master/loadMerk')?>',
				type: "POST",
				data : ({
					barang, jenis_tipe, material, size, cari: 'merk'
				}),
				success: function(res){
					data = JSON.parse(res)
					console.log(data)
					$("#i_merk").html(data.html).prop('disabled', (data.data) ? false : true)
				}
			})
			let merk = $("#i_merk").val()
			$("#n_merk").val('').removeClass('is-valid').removeClass('is-invalid');
			(merk == '+') ? $("#p_merk").attr('style', 'padding-top:4px') : $("#p_merk").attr('style', 'display:none');
		}
	}

	function merk(opsi)
	{
		if(opsi == 'lama'){
			let merk = $("#i_merk").val()
			$("#n_merk").val('').removeClass('is-valid').removeClass('is-invalid')
			$("#k_merk").html('');
			(merk == '+') ? $("#p_merk").attr('style', 'padding-top:4px') : $("#p_merk").attr('style', 'display:none');
			// load('lama')
		}
		if(opsi == 'baru'){
			$("#n_merk").removeClass('is-invalid').removeClass('is-valid')
			let id_mbh = $("#i_barang").val()
			let i_jenis_tipe = $("#i_jenis_tipe").val()
			let i_material = $("#i_material").val()
			let i_size = $("#i_size").val()
			let n_merk = $("#n_merk").val()
			$.ajax({
				url: '<?php echo base_url('Master/cekMerk')?>',
				type: "POST",
				data : ({
					id_mbh, i_jenis_tipe, i_material, i_size, n_merk
				}),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						$("#n_merk").removeClass('is-invalid').addClass('is-valid')
						$("#k_merk").html('')
						// load('baru')
					}else{
						$("#n_merk").removeClass('is-valid').addClass('is-invalid')
						$("#k_merk").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
						// load('lama')
					}
					$("#n_merk").val(data.cleanTxt)
				}
			})
		}
	}

	// function addBarang()
	// {
	// 	let id_cart = parseInt($("#id_cart").val()) + 1;
	// 	$("#id_cart").val(id_cart)
	// 	let i_barang = $("#i_barang").val()
	// 	let n_barang = $("#n_barang").val()
	// 	$.ajax({
	// 		url: '<?php echo base_url('Master/addBarang')?>',
	// 		type: "POST",
	// 		data : ({
	// 			id_cart, i_barang, n_barang
	// 		}),
	// 		success: function(res){
	// 			data = JSON.parse(res)
	// 			console.log(data)
	// 		}
	// 	})
	// }

	// $("#kode_barang").on({
	// 	keydown: function(e) {
	// 		if (e.which === 32)
	// 			return false;
	// 	},
	// 	keyup: function(){
	// 		this.value = this.value.toUpperCase();
	// 	},
	// 	change: function() {
	// 		this.value = this.value.replace(/\s/g, "");
	// 	}
	// });

	// function load_data() 
	// {
	// 	var table = $('#datatable').DataTable();
	// 	table.destroy();
	// 	tabel = $('#datatable').DataTable({
	// 		"processing": true,
	// 		"pageLength": true,
	// 		"paging": true,
	// 		"ajax": {
	// 			"url": '<?php echo base_url(); ?>Master/load_data/supplier',
	// 			"type": "POST",
	// 		},
	// 		responsive: true,
	// 		"pageLength": 10,
	// 		"language": {
	// 			"emptyTable": "Tidak ada data.."
	// 		}
	// 	});
	// }
</script>
