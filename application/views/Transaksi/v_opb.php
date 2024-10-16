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
							<div class="col-md-2">DEPARTEMEN <span style="color:#f00">*</span></div>
							<div class="col-md-5" style="padding-bottom:3px">
								<select id="plh_departemen" class="form-control select2" onchange="pilihBarang()">
									<?php
										$level = $this->session->userdata('level');
										$bagian = $this->db->query("SELECT b.id_group,b.kode_departemen,d.main_menu,d.nama FROM m_modul_group m 
										INNER JOIN m_departemen_bagian b ON m.id_group=b.id_group
										INNER JOIN m_departemen d ON b.kode_departemen=d.kode
										WHERE m.val_group='$level' AND d.main_menu='0'
										GROUP BY b.id_group,b.kode_departemen");
										$html1 ='';
										$html1 .='<option value="">PILIH</option>';
										foreach($bagian->result() as $r){
											$html1 .='<option value="'.$r->kode_departemen.'">'.$r->nama.'</option>';
										}
										echo $html1
									?>
								</select>
							</div>
							<div class="col-md-5"></div>
						</div>
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
		$("#destroy").load("<?php echo base_url('Transaksi/destroy') ?>")
		$(".select2").select2()
		// load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function tambah()
	{
		$("#id_cart").val(0)
		$("#plh_departemen").val('').trigger('change').prop('disabled', false)
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
		let plh_departemen = $("#plh_departemen").val()
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
				plh_departemen, id_mbh, id_mbh_lama, jenistipe, material, ukuran, merk
			}),
			success: function(res){
				data = JSON.parse(res);
				console.log(data);
				(plh_departemen != '') ? prop = true : prop = false;
				$("#plh_departemen").prop('disabled', prop)
				$("#id_mbh").val(id_mbh)
				$(".list-detail").html(data.html)
				$("#jenistipe").html(data.htmlJT).val((id_mbh == id_mbh_lama) ? jenistipe : '')
				$("#material").html(data.htmlM).val((id_mbh == id_mbh_lama) ? material : '')
				$("#ukuran").html(data.htmlS).val((id_mbh == id_mbh_lama) ? ukuran : '')
				$("#merk").html(data.htmlMr).val((id_mbh == id_mbh_lama) ? merk : '')
				$(".select2").select2()
			}
		})
	}

	function pilihSatuan(i)
	{
		$("#qty"+i).val('')
		$(".txtsatuan"+i).html('')
		$(".hitungqty"+i).html('')
		$(".ketsatuan"+i).html('')
		$("#i_qty1_"+i).val('')
		$("#i_qty2_"+i).val('')
	}

	function pengadaaan(i)
	{
		const rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let h_satuan = parseInt($("#h_satuan"+i).val())
		let plh_satuan = $("#plh_satuan"+i).val()
		let i_qty = parseInt($("#qty"+i).val().split('.').join(''));
		(isNaN(i_qty) || i_qty < 0 || i_qty.toString().length >= 7) ? i_qty = 0 : i_qty = i_qty;
		$("#qty"+i).val(rupiah.format(i_qty));
		let qty1 = parseInt($("#h_qty1_"+i).val())
		let qty2 = parseInt($("#h_qty2_"+i).val())
		let qty3 = parseInt($("#h_qty3_"+i).val())
		let satuan1 = $("#h_satuan1_"+i).val()
		let satuan2 = $("#h_satuan2_"+i).val()
		let satuan3 = $("#h_satuan3_"+i).val()
		// PERHITUNGAN
		let besar = 0; let tengah = 0; let kecil = 0; let x_besar = 0; let x_tengah = 0; let x_kecil = 0;
		if(h_satuan == 1){
			$(".txtsatuan"+i).html(`TERKECIL`)
			$(".hitungqty"+i).html(`${rupiah.format(i_qty)}`)
			$(".ketsatuan"+i).html(`${satuan3}`)
		}
		if(h_satuan == 2){
			if(plh_satuan == 'TERBESAR'){
				besar = i_qty * qty1; kecil = i_qty * qty3;
				x_besar = besar; x_kecil = kecil;
			}
			if(plh_satuan == 'TERKECIL'){
				if(i_qty >= qty3){
					kecil = parseFloat(i_qty / qty3).toFixed(2).split('.00').join('');
					(kecil >= qty1) ? besar = parseFloat(kecil / qty1).toFixed(2).split('.00').join('') : besar = 0;
				}else{
					kecil = 0
				}
				x_besar = parseFloat(kecil * qty1).toFixed(2).split('.00').join('')
				x_kecil = i_qty
			}
			$(".txtsatuan"+i).html(`TERBESAR<br>TERKECIL`)
			$(".hitungqty"+i).html(`${rupiah.format(x_besar)}<br>${rupiah.format(x_kecil)}`)
			$(".ketsatuan"+i).html(`${satuan1}<br>${satuan3}`)
		}
		if(h_satuan == 3){
			if(plh_satuan == 'TERBESAR'){
				besar = i_qty * qty1; tengah = besar * qty2; kecil = tengah * qty3;
				x_besar = besar; x_tengah = tengah; x_kecil = kecil;
			}
			if(plh_satuan == 'TENGAH'){
				kecil = i_qty * qty3;
				(i_qty >= qty2) ? besar = parseFloat(i_qty / qty2) : besar = 0;
				x_besar = parseFloat(besar * qty1).toFixed(2).split('.00').join('')
				x_tengah = i_qty
				x_kecil = kecil
			}
			if(plh_satuan == 'TERKECIL'){
				if(i_qty >= qty3){
					kecil = parseFloat(i_qty / qty3).toFixed(2).split('.00').join('')
					if(kecil >= qty2){
						tengah = parseFloat(kecil / qty2).toFixed(2).split('.00').join('');
						(tengah >= qty1) ? besar = parseFloat(tengah / qty1).toFixed(2).split('.00').join('') : besar = 0;
					}else{
						tengah = 0
					}
				}else{
					kecil = 0
				}
				x_besar = parseFloat(tengah * qty1).toFixed(2).split('.00').join('')
				x_tengah = kecil
				x_kecil = i_qty
			}
			$(".txtsatuan"+i).html(`TERBESAR<br>TENGAH<br>TERKECIL`)
			$(".hitungqty"+i).html(`${rupiah.format(x_besar)}<br>${rupiah.format(x_tengah)}<br>${rupiah.format(x_kecil)}`)
			$(".ketsatuan"+i).html(`${satuan1}<br>${satuan2}<br>${satuan3}`)
		}
		$("#i_qty1_"+i).val(x_besar)
		$("#i_qty2_"+i).val(x_tengah)
	}

	function addCartOPB(i)
	{
		let id_cart = parseInt($("#id_cart").val()) + 1;
		$("#id_cart").val(id_cart)
		let id_mbh = $("#h_id_mbh"+i).val()
		let id_mbd = $("#h_id_mbd"+i).val()
		let plh_bagian = $("#plh_bagian"+i).val()
		let plh_satuan = $("#plh_satuan"+i).val()
		let i_qty1 = $("#i_qty1_"+i).val()
		let i_qty2 = $("#i_qty2_"+i).val()
		let i_qty3 = $("#qty"+i).val().split('.').join('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/addCartOPB')?>',
			type: "POST",
			data : ({
				id_cart, id_mbh, id_mbd, plh_bagian, plh_satuan, i_qty1, i_qty2, i_qty3, status
			}),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
			}
		})
	}
</script>
