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
				<div class="card card-secondary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST BERITA ACARA PENERIMAAN BARANG</h3>
					</div>
					<div class="card-body" style="padding:0">
						<?php if(in_array($this->session->userdata('approve'), ['ALL', 'OFFICE', 'GUDANG'])) { ?>
							<div style="padding:5px">
								<button type="button" class="btn btn-primary btn-sm" onclick="">Tambah Data</button>
							</div>
						<?php } ?>
						<div class="list-header" style="padding:0"></div>
						<div class="card-body row" style="padding:0">
							<div class="col-md-3">
								<div class="list-opb" style="padding:0"></div>
							</div>
							<div class="col-md-9" style="padding:0 14px 12px">
								<div style="position:sticky;top:12px">
									<div class="list-opb-detail"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_opbh" value="">
			<input type="hidden" id="h_kode_dpt" value="">
			<input type="hidden" id="h_ii" value="">
		</div>

		<div class="row row-input">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT BAPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div class="btn-kembali"></div>
						<div class="lil list-opb-bapb"></div>
					</div>
				</div>
			</div>
			<input type="hidden" id="plh_departemen" value="">
			<input type="hidden" id="id_mbh" value="">
			<input type="hidden" id="id_cart" value="0">
			<input type="hidden" id="destroy">
		</div>
	</section>
</div>

<script type="text/javascript">
	const urlAuth = '<?= $this->session->userdata('level')?>';
	const urlUser = '<?= $this->session->userdata('username')?>';
	const urlAppv = '<?= $this->session->userdata('approve')?>';
	status = 'insert';
	$(document).ready(function() {
		$(".select2").select2()
		loadHeader()
	});

	function loadHeader()
	{
		$(".list-opb-detail").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadHeader')?>',
			type: "POST",
			data: ({ opsi: 'bapb' }),
			success: function(res){
				data = JSON.parse(res)
				$(".list-header").html(data.html)
			}
		})
		btnHeader(0)
	}

	function btnHeader(kode_dpt)
	{
		$("#h_kode_dpt").val(kode_dpt)
		$(".ff").removeClass('ff-klik').addClass('ff-all')
		$(".boh").removeClass('btn-opbh-klik').addClass('btn-opbh-all')
		$("#h_"+kode_dpt).removeClass('btn-opb-header').addClass('btn-opbh-klik')
		$("#ff_"+kode_dpt).removeClass('ff-all').addClass('ff-klik')
		$(".list-opb-detail").html('')
		loadList(kode_dpt)
	}

	function loadList(kode_dpt)
	{
		$("#plh_departemen").val('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadList')?>',
			type: "POST",
			data: ({ opsi: 'bapb', kode_dpt }),
			success: function(res){
				data = JSON.parse(res)
				$(".list-opb").html(data.html)
			}
		})
	}

	function btnDetail(id_opbh, i, opsi)
	{
		let plh_departemen = $("#plh_departemen").val()
		$(".btn-opb-header").prop('disabled', false)
		$(".toh").removeClass('tr-opbh-klik').addClass('tr-opbh-all')
		$("#bth_"+i).prop('disabled', true)
		$(".list-opb-detail").html('')
		$(".lil").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadDetail')?>',
			type: "POST",
			data: ({ id_opbh, plh_departemen, opsi, jenis: 'bapb' }),
			success: function(res){
				data = JSON.parse(res)
				if(opsi == 'view'){
					$("#toh_"+i).removeClass('tr-opbh-all').addClass('tr-opbh-klik')
					$("#id_opbh").val(data.opbh.id_opbh)
					$("#h_ii").val(i)
					$(".list-opb-detail").html(data.htmlDetail)
				}
				if(opsi == 'edit'){
					$(".list-opb-bapb").html(data.htmlDetail)
				}
				$(".select2").select2()
			}
		})
	}

	function kembali()
	{
		// $(".row-list").show()
		// $(".row-input").hide()
		$(".btn-kembali").html('')
		$(".lil").html('')
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		btnDetail(id_opbh, h_ii, 'view')
	}

	function editOPB()
	{
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		$("#destroy").load("<?php echo base_url('Transaksi/destroy') ?>")
		// $(".row-list").hide()
		// $(".row-input").show()
		$("#id_mbh").val('')
		$("#id_cart").val(0)
		$(".list-detail").html('')
		$(".list-cart").html('')
		$(".list-opb-bapb").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/editOPB')?>',
			type: "POST",
			data: ({ id_opbh }),
			success: function(res){
				data = JSON.parse(res)
				$("#plh_departemen").val(data.opbh.kode_dpt)
				$(".btn-kembali").html(`<button type="button" class="btn btn-primary btn-sm" onclick="kembali()">Kembali</button>`)
				btnDetail(id_opbh, h_ii, 'edit')
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
		$("#i_qty3_"+i).val('')
		$("#harga_opb"+i).val('')
		$("#jumlah_opb"+i).val('')
		$("#plh_supplier"+i).val('').trigger('change')
		$("#plh_bagian"+i).val('')
		$("#ket_pengadaan"+i).val('')
	}

	function pengadaaan(i)
	{
		const rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let h_satuan = parseInt($("#h_satuan"+i).val())
		let plh_satuan = $("#plh_satuan"+i).val()
		let i_qty = parseInt($("#qty"+i).val().split('.').join(''));
		(isNaN(i_qty) || i_qty == 0 || i_qty < 0 || i_qty.toString().length >= 7) ? i_qty = 0 : i_qty = i_qty;
		$("#qty"+i).val(rupiah.format(i_qty));
		let qty1 = parseInt($("#h_qty1_"+i).val())
		let qty2 = parseInt($("#h_qty2_"+i).val())
		let qty3 = parseInt($("#h_qty3_"+i).val())
		let satuan1 = $("#h_satuan1_"+i).val()
		let satuan2 = $("#h_satuan2_"+i).val()
		let satuan3 = $("#h_satuan3_"+i).val()
		// PERHITUNGAN
		let besar = 0; let tengah = 0; let kecil = 0; let x_besar = 0; let x_tengah = 0; let x_kecil = 0; let style1 = ''; let style2 = ''; let style3 = ''
		if(h_satuan == 1){
			x_kecil = i_qty
			$(".txtsatuan"+i).html(`<span style="color:#f00">TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span style="color:#f00">${rupiah.format(i_qty)}</span>`)
			$(".ketsatuan"+i).html(`<span style="color:#f00">${satuan3}</span>`)
		}
		if(h_satuan == 2){
			if(plh_satuan == 'TERBESAR'){
				besar = i_qty * qty1; kecil = i_qty * qty3;
				x_besar = besar; x_kecil = kecil;
				style1 = 'style="color:#f00"'; style3 = ''
			}
			if(plh_satuan == 'TERKECIL'){
				kecil = parseFloat(i_qty / qty3).toFixed(2).split('.00').join('');
				besar = parseFloat(kecil / qty1).toFixed(2).split('.00').join('');
				x_besar = parseFloat(kecil * qty1).toFixed(2).split('.00').join('')
				x_kecil = i_qty
				style1 = ''; style3 = 'style="color:#f00"'
			}
			$(".txtsatuan"+i).html(`<span ${style1}>TERBESAR</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span ${style1}>${rupiah.format(x_besar)}</span><br><span ${style3}>${rupiah.format(x_kecil)}</span>`)
			$(".ketsatuan"+i).html(`<span ${style1}>${satuan1}</span><br><span ${style3}>${satuan3}</span>`)
		}
		if(h_satuan == 3){
			if(plh_satuan == 'TERBESAR'){
				besar = i_qty * qty1; tengah = besar * qty2; kecil = tengah * qty3;
				x_besar = besar; x_tengah = tengah; x_kecil = kecil;
				style1 = 'style="color:#f00"'; style2 = ''; style3 = ''
			}
			if(plh_satuan == 'TENGAH'){
				kecil = i_qty * qty3;
				besar = parseFloat(i_qty / qty2).toFixed(2).split('.00').join('')
				x_besar = parseFloat(besar * qty1).toFixed(2).split('.00').join('')
				x_tengah = i_qty
				x_kecil = kecil
				style1 = ''; style2 = 'style="color:#f00"'; style3 = ''
			}
			if(plh_satuan == 'TERKECIL'){
				kecil = parseFloat(i_qty / qty3).toFixed(2).split('.00').join('')
				tengah = parseFloat(kecil / qty2).toFixed(2).split('.00').join('')
				besar = parseFloat(tengah / qty1).toFixed(2).split('.00').join('')
				x_besar = parseFloat(tengah * qty1).toFixed(2).split('.00').join('')
				x_tengah = kecil
				x_kecil = i_qty
				style1 = ''; style2 = ''; style3 = 'style="color:#f00"'
			}
			$(".txtsatuan"+i).html(`<span ${style1}>TERBESAR</span><br><span ${style2}>TENGAH</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span ${style1}>${rupiah.format(x_besar)}</span><br><span ${style2}>${rupiah.format(x_tengah)}</span><br><span ${style3}>${rupiah.format(x_kecil)}</span>`)
			$(".ketsatuan"+i).html(`<span ${style1}>${satuan1}</span><br><span ${style2}>${satuan2}</span><br><span ${style3}>${satuan3}</span>`)
		}
		$("#i_qty1_"+i).val(x_besar)
		$("#i_qty2_"+i).val(x_tengah)
		$("#i_qty3_"+i).val(x_kecil)

		if(urlAppv == 'ALL' || urlAppv == 'OFFICE'){
			hargaOPB(i)
		}
	}

	function hargaOPB(i)
	{
		let qty = ($("#qty"+i).val() == undefined) ? 0 : $("#qty"+i).val().split('.').join('');
		let harga = ($("#harga_opb"+i).val() == undefined) ? 0 : $("#harga_opb"+i).val().split('.').join('');
		$("#harga_opb"+i).val(format_angka(harga))
		let jumlah = parseInt(qty) * parseInt(harga);
		(isNaN(jumlah)) ? jumlah = 0 : jumlah = jumlah;
		$("#jumlah_opb"+i).val(format_angka(jumlah))
	}

	function prosesBAPB(i)
	{
		let kode_dpt = $("#plh_departemen").val()
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		let tgl_bapb = $("#tgl_bapb_"+i).val()
		let id_opbd = $("#h_id_opbd_"+i).val()
		let id_mbh = $("#h_id_mbh"+i).val()
		let id_mbd = $("#h_id_mbd"+i).val()
		let plh_satuan = $("#plh_satuan"+i).val()
		let qty = $("#qty"+i).val()
		let i_qty1 = $("#i_qty1_"+i).val()
		let i_qty2 = $("#i_qty2_"+i).val()
		let i_qty3 = $("#i_qty3_"+i).val()
		let harga = $("#harga_opb"+i).val().split('.').join('')
		let plh_supplier = $("#plh_supplier"+i).val()
		let ket_pengadaan = $("#ket_pengadaan"+i).val()
		let plh_bagian = $("#plh_bagian"+i).val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/prosesBAPB')?>',
			type: "POST",
			async: false,
			data: ({
				kode_dpt, id_opbh, tgl_bapb, id_opbd, id_mbh, id_mbd, plh_satuan, qty, i_qty1, i_qty2, i_qty3, harga, plh_supplier, ket_pengadaan, plh_bagian
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data && data.qrcode){
					btnDetail(id_opbh, h_ii, 'edit')
				}else{
					toastr.error(`<b>${data.msg}</b>`)
				}
			}
		})
	}

	function hapusBAPB(id_bapb)
	{
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Transaksi/hapusBAPB')?>',
				type: "POST",
				data : ({ id_bapb }),
				success: function(res){
					data = JSON.parse(res)
					if(data.hapusBAPB){
						btnDetail(id_opbh, h_ii, 'edit')
					}else{
						toastr.error(`<b>TERJADI KESALAHAN!</b>`)
					}
				}
			})
		});
	}
</script>
