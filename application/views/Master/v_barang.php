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

	<style>
		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>

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
					<button type="button" style="font-family:Cambria;" class="tambah_data btn btn-info pull-right" onclick="tambahKembali('tambah')">
						<i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b>
					</button>
				</div>
				<div style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="width:10%;text-align:center">KODE</th>
								<th style="width:85%;text-align:center">NAMA BARANG</th>
								<th style="width:5%;text-align:center">AKSI</th>
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
						<div style="margin-bottom:12px">
							<div class="btn-tambah"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">NAMA BARANG <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="i_barang" class="form-control select2" onchange="namaBarang('lama')">
								</select>
							</div>
							<div class="col-md-4">
								<div id="p_barang" style="display:none">
									<input type="text" id="n_barang" class="form-control" style="font-weight:bold" autocomplete="off" onchange="namaBarang('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="NAMA BARANG BARU">
									<div id="k_barang"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">JENIS / TIPE <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="i_jenis_tipe" class="form-control select2" onchange="jenisTipe('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-4">
								<div id="p_jenis_tipe" style="display:none">
									<input type="text" id="n_jenis_tipe" class="form-control" style="font-weight:bold" autocomplete="off" onchange="jenisTipe('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="-">
									<div id="k_jenis_tipe"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MATERIAL <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="i_material" class="form-control select2" onchange="material('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-4">
								<div id="p_material" style="display:none">
									<input type="text" id="n_material" class="form-control" style="font-weight:bold" autocomplete="off" onchange="material('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="-">
									<div id="k_material"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">SIZE <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="i_size" class="form-control select2" onchange="ukuran('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-4">
								<div id="p_size" style="display:none">
									<input type="text" id="n_size" class="form-control" style="font-weight:bold" autocomplete="off" onchange="ukuran('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="-">
									<div id="k_size"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 16px">
							<div class="col-md-2">MERK <span style="color:#f00">*</span></div>
							<div class="col-md-4">
								<select id="i_merk" class="form-control select2" onchange="merk('lama')" disabled>
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-4">
								<div id="p_merk" style="display:none">
									<input type="text" id="n_merk" class="form-control" style="font-weight:bold" autocomplete="off" onchange="merk('baru')" oninput="this.value=this.value.toUpperCase()" placeholder="-">
									<div id="k_merk"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">PILIH SATUAN <span style="color:#f00">*</span></div>
							<div class="col-md-2">
								<select id="pilih_satuan" class="form-control select2" onchange="pilihSatuan()" disabled>
									<option value="">PILIH</option>
									<option value="1">1 SATUAN</option>
									<option value="2">2 SATUAN</option>
									<option value="3">3 SATUAN</option>
								</select>
							</div>
							<div class="col-md-8"></div>
						</div>
						<div class="card-body row row-satuan-terbesar" style="display:none">
							<div class="col-md-2">SATUAN TERBESAR <span style="color:#f00">*</span></div>
							<div class="col-md-2" style="padding-bottom:3px">
								<input type="number" id="satuan_terbesar" class="form-control" style="font-weight:bold;text-align:right" onkeyup="keyupSatuan('satuan_terbesar')" placeholder="0">
							</div>
							<div class="col-md-2" style="padding-bottom:3px">
								<select id="p_satuan_terbesar" class="form-control select2" onchange="keyupSatuan('satuan_terbesar')">
									<option value="">PILIH</option>
									<option value="BOX">BOX</option>
									<option value="PCS">PCS</option>
									<option value="ROLL">ROLL</option>
								</select>
							</div>
							<div class="col-md-6"></div>
						</div>
						<div class="card-body row row-satuan-tengah" style="display:none">
							<div class="col-md-2">SATUAN TENGAH <span style="color:#f00">*</span></div>
							<div class="col-md-2" style="padding-bottom:3px">
								<input type="number" id="satuan_tengah" class="form-control" style="font-weight:bold;text-align:right" onkeyup="keyupSatuan('satuan_tengah')" placeholder="0">
							</div>
							<div class="col-md-2" style="padding-bottom:3px">
								<select id="p_satuan_tengah" class="form-control select2" onchange="keyupSatuan('satuan_tengah')">
									<option value="">PILIH</option>
									<option value="BOX">BOX</option>
									<option value="PCS">PCS</option>
									<option value="ROLL">ROLL</option>
								</select>
							</div>
							<div class="col-md-6"></div>
						</div>
						<div class="card-body row row-satuan-terkecil" style="display:none">
							<div class="col-md-2">SATUAN TERKECIL <span style="color:#f00">*</span></div>
							<div class="col-md-2" style="padding-bottom:3px">
								<input type="number" id="satuan_terkecil" class="form-control" style="font-weight:bold;text-align:right" onkeyup="keyupSatuan('satuan_terkecil')" placeholder="0">
							</div>
							<div class="col-md-2" style="padding-bottom:3px">
								<select id="p_satuan_terkecil" class="form-control select2" onchange="keyupSatuan('satuan_terkecil')">
									<option value="">PILIH</option>
									<option value="BOX">BOX</option>
									<option value="PCS">PCS</option>
									<option value="ROLL">ROLL</option>
								</select>
							</div>
							<div class="col-md-6"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<div id="simpan_barang"></div>
							</div>
						</div>

						<div style="overflow:auto;white-space:nowrap">
							<div class="list-barang"></div>
							<div class="vlist-barang"></div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_mbh" value="">
			<input type="hidden" id="id_mbd" value="">
			<input type="hidden" id="id_cart" value="0">
			<input type="hidden" id="destroy">
		</div>
		<!-- INPUT DATA -->
	</section>
</div>

<script type="text/javascript">
	status = "insert";
	$(document).ready(function() {
		$("#destroy").load("<?php echo base_url('Master/destroy') ?>")
		$(".select2").select2()
		load_data()
		editBarang('')
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function tambahKembali(opsi)
	{
		status = 'insert';
		$("#destroy").load("<?php echo base_url('Master/destroy') ?>")
		$("#id_mbh").val('')
		$("#id_mbd").val('')
		$("#id_cart").val(0)
		$(".btn-tambah").html(`<button type="button" class="btn btn-sm btn-info pull-right" onclick="tambahKembali('kembali')"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;<b>Kembali</b></button>`)
		$(".list-barang").html('')
		$(".vlist-barang").html('')
		$("#i_barang").val('').trigger('change').prop('disabled', false)
		if(opsi == 'kembali'){
			load_data()
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
				"url": '<?php echo base_url(); ?>Master/loadDataBarang',
				"type": "POST",
			},
			responsive: false,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function hideAll(opsi){
		$("#i_"+opsi).html('<option value="">PILIH</option>').prop('disabled', true)
		$("#p_"+opsi).attr('style', 'display:none')
		$("#n_"+opsi).val('').removeClass('is-valid').removeClass('is-invalid')
		$("#k_"+opsi).html('')
		$("#pilih_satuan").val('').trigger('change').prop('disabled', true)
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
					$("#i_jenis_tipe").html(data.html).prop('disabled', false)
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
					$("#i_material").html(data.html).prop('disabled', false)
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
					$("#i_size").html(data.html).prop('disabled', false)
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

	$("#n_size").on({
		keydown: function(e) {
			if (e.which === 32)
				return false;
		},
		keyup: function(){
			this.value = this.value.toUpperCase();
		},
		change: function() {
			this.value = this.value.replace(/\s/g, "");
		}
	});

	function loadMerk(opsi)
	{
		$("#pilih_satuan").val('').trigger('change').prop('disabled', true)
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
					$("#i_merk").html(data.html).prop('disabled', false)
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
			$("#pilih_satuan").val('').trigger('change').prop('disabled', (merk != '+' && merk != '') ? false : true)
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
					}else{
						$("#n_merk").removeClass('is-valid').addClass('is-invalid')
						$("#k_merk").html(`${data.msg}`).attr('style', 'font-style:italic;font-size:12px;color:#f00')
					}
					$("#n_merk").val(data.cleanTxt)
					$("#pilih_satuan").val('').trigger('change').prop('disabled', (data.data) ? false : true)
				}
			})
		}
	}

	function pilihSatuan()
	{
		let pilih_satuan = $("#pilih_satuan").val()
		let id_mbd = $("#id_mbd").val()
		$("#simpan_barang").html('')
		$("#satuan_terbesar").val('')
		$("#p_satuan_terbesar").val('').trigger('change')
		$("#satuan_tengah").val('')
		$("#p_satuan_tengah").val('').trigger('change')
		$("#satuan_terkecil").val('')
		$("#p_satuan_terkecil").val('').trigger('change')
		if(pilih_satuan == 1){
			$(".row-satuan-terbesar").attr('style', 'display:none')
			$(".row-satuan-tengah").attr('style', 'display:none')
			$(".row-satuan-terkecil").attr('style', 'font-weight:bold;padding:0 0 4px')
		}else if(pilih_satuan == 2){
			$(".row-satuan-terbesar").attr('style', 'font-weight:bold;padding:0 0 4px')
			$(".row-satuan-tengah").attr('style', 'display:none')
			$(".row-satuan-terkecil").attr('style', 'font-weight:bold;padding:0 0 4px')
		}else if(pilih_satuan == 3){
			$(".row-satuan-terbesar").attr('style', 'font-weight:bold;padding:0 0 4px')
			$(".row-satuan-tengah").attr('style', 'font-weight:bold;padding:0 0 4px')
			$(".row-satuan-terkecil").attr('style', 'font-weight:bold;padding:0 0 4px')
		}else{
			$(".row-satuan-terbesar").attr('style', 'display:none')
			$(".row-satuan-tengah").attr('style', 'display:none')
			$(".row-satuan-terkecil").attr('style', 'display:none')
		}
		if(pilih_satuan != ''){
			if(status == 'insert'){
				$("#simpan_barang").html(`<button type="button" class="btn btn-sm btn-success" style="font-weight:bold" onclick="addBarang()"><i class="fas fa-plus"></i> TAMBAH</button>`)
			}
			if(status == 'update'){
				$("#simpan_barang").html(`<button type="button" class="btn btn-sm btn-warning" style="font-weight:bold" onclick="addBarang()"><i class="fas fa-plus"></i> EDIT</button>`)
			}
		}
	}

	function keyupSatuan(opsi)
	{
		const rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let nominal = $("#"+opsi).val().split('.').join('')
		let pilihan = $("#p_"+opsi).val();
		(nominal < 0 || nominal == '' || nominal.length >= 7) ? nominal = 0 : nominal = nominal
		$("#"+opsi).val(rupiah.format(nominal))
	}

	function addBarang()
	{
		let id_mbh = $("#id_mbh").val()
		let id_mbd = $("#id_mbd").val()
		let id_cart = parseInt($("#id_cart").val()) + 1;
		$("#id_cart").val(id_cart)
		let i_barang = $("#i_barang").val()
		let n_barang = $("#n_barang").val()
		let i_jenis_tipe = $("#i_jenis_tipe").val()
		let n_jenis_tipe = $("#n_jenis_tipe").val()
		let i_material = $("#i_material").val()
		let n_material = $("#n_material").val()
		let i_size = $("#i_size").val()
		let n_size = $("#n_size").val()
		let i_merk = $("#i_merk").val()
		let n_merk = $("#n_merk").val()
		let pilih_satuan = $("#pilih_satuan").val()
		let satuan_terbesar = $("#satuan_terbesar").val()
		let p_satuan_terbesar = $("#p_satuan_terbesar").val()
		let satuan_tengah = $("#satuan_tengah").val()
		let p_satuan_tengah = $("#p_satuan_tengah").val()
		let satuan_terkecil = $("#satuan_terkecil").val()
		let p_satuan_terkecil = $("#p_satuan_terkecil").val()
		$.ajax({
			url: '<?php echo base_url('Master/addBarang')?>',
			type: "POST",
			data : ({
				id_mbh, id_mbd, id_cart, i_barang, n_barang, i_jenis_tipe, n_jenis_tipe, i_material, n_material, i_size, n_size, i_merk, n_merk, pilih_satuan, satuan_terbesar, p_satuan_terbesar, satuan_tengah, p_satuan_tengah, satuan_terkecil, p_satuan_terkecil, status
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(data.data && status == 'insert'){
					cartBarang()
				}
			}
		})
	}

	function cartBarang()
	{
		$(".list-barang").html('')
		$.ajax({
			url: '<?php echo base_url('Master/cartBarang')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				$(".list-barang").html(data.html)
			}
		})
	}

	function hapusCart(rowid)
	{
		$.ajax({
			url: '<?php echo base_url('Master/hapusCart')?>',
			type: "POST",
			data: ({ rowid }),
			success: function(res){
				cartBarang()
			}
		})
	}

	function viewBarang(id_mbh)
	{
		$("#id_mbh").val(id_mbh)
		let id_mbd = $("#id_mbd").val()
		$(".vlist-barang").html('')
		$.ajax({
			url: '<?php echo base_url('Master/viewBarang')?>',
			type: "POST",
			data: ({ id_mbh, id_mbd }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(id_mbd == ''){
					$("#i_barang").val(id_mbh).trigger('change').prop('disabled', true)
					$(".btn-tambah").html(`<button type="button" class="btn btn-sm btn-info pull-right" onclick="tambahKembali('kembali')"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;<b>Kembali</b></button>
					<button type="button" class="btn btn-sm btn-success pull-right" onclick="editBarang('')"><i class="fas fa-plus"></i>&nbsp;&nbsp;<b>Tambah</b></button>`)
				}
				$(".vlist-barang").html(data.html)
			}
		})
	}

	function editBarang(id_mbd)
	{
		let id_mbh = $("#id_mbh").val()
		$("#id_mbd").val(id_mbd)
		$("#destroy").load("<?php echo base_url('Master/destroy') ?>")
		$(".list-barang").html('')
		$("#simpan_barang").html('')
		$.ajax({
			url: '<?php echo base_url('Master/editBarang')?>',
			type: "POST",
			data: ({ id_mbh, id_mbd, status }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				if(id_mbd != ''){
					$("#i_jenis_tipe").html(data.jenis_tipe).prop('disabled', false)
					$("#i_material").html(data.material).prop('disabled', false)
					$("#i_size").html(data.size).prop('disabled', false)
					$("#i_merk").html(data.merk).prop('disabled', false)
					$("#pilih_satuan").val(data.detail.p_satuan).trigger('change').prop('disabled', false)
					$("#satuan_terbesar").val((data.detail.qty1 == null) ? 0 : parseInt(data.detail.qty1))
					$("#p_satuan_terbesar").val(data.detail.satuan1).trigger('change')
					$("#satuan_tengah").val((data.detail.qty2 == null) ? 0 : parseInt(data.detail.qty2))
					$("#p_satuan_tengah").val(data.detail.satuan2).trigger('change')
					$("#satuan_terkecil").val((data.detail.qty3 == null) ? 0 : parseInt(data.detail.qty3))
					$("#p_satuan_terkecil").val(data.detail.satuan3).trigger('change')
					$("#simpan_barang").html(`<button type="button" class="btn btn-sm btn-warning" style="font-weight:bold" onclick="addBarang()"><i class="fas fa-plus"></i> EDIT</button>`)
					status = 'update'
				}else{
					$("#i_barang").html(data.header).prop('disabled', false)
					status = 'insert'
				}
				viewBarang(id_mbh)
			}
		})
	}

	function btnEditBarang(id_mbd)
	{
		let id_mbh = $("#id_mbh").val()
		$.ajax({
			url: '<?php echo base_url('Master/btnEditBarang')?>',
			type: "POST",
			data: ({ id_mbh, id_mbd }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
			}
		})
	}

	function simpanBarang()
	{
		$.ajax({
			url: '<?php echo base_url('Master/simpanBarang')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
			}
		})
	}
</script>
