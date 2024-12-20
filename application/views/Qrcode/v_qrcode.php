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
		<?php if(in_array($this->session->userdata('approve'), ['ALL'])) { ?>
			<div class="row row-opb">
				<div class="col-md-12">
					<div class="card card-secondary card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">RINCIAN DATA OPB</h3>
						</div>
						<div class="card-body" style="padding:6px">
							<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
								<div class="list-opb"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="row row-bapb">
			<div class="col-md-12">
				<div class="card card-info card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">RINCIAN DATA BAPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
							<div class="list-bapb"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row row-stok">
			<div class="col-md-12">
				<div class="card card-secondary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">STOK</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
							<div class="list-stok"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if(in_array($this->session->userdata('approve'), ['ALL', 'OFFICE', 'GUDANG'])) { ?>
			<div class="row row-input">
				<div class="col-md-12">
					<div class="card card-success card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT SPB</h3>
						</div>
						<div class="card-body" style="padding:6px">
							<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
								<div class="list-input-spb"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row row-spb">
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST SPB</h3>
						</div>
						<div class="card-body" style="padding:6px">
							<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
								<div class="list-detail-spb"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</section>
</div>

<script type="text/javascript">
	status = 'insert';
	const urlAppv = '<?= $this->session->userdata('approve')?>';
	const qrcode = '<?= $code ?>';
	
	$(document).ready(function() {
		$(".select2").select2()
		loadBarang()
	});

	function loadBarang()
	{
		$('.list-opb').html('')
		$('.list-bapb').html('')
		$('.list-stok').html('')
		$('.list-input-spb').html('')
		$('.list-detail-spb').html('')
		$.ajax({
			url: '<?php echo base_url('Qrcode/loadBarang')?>',
			type: "POST",
			data: ({ qrcode }),
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
				if(urlAppv == 'ALL'){
					$('.list-opb').html(data.htmlOpb)
				}
				$('.list-stok').html(data.htmlStok)
				$('.list-bapb').html(data.htmlBapb)
				if(urlAppv == 'ALL' || urlAppv == 'OFFICE' || urlAppv == 'GUDANG'){
					$('.list-input-spb').html(data.htmlSpb)
					$('.list-detail-spb').html(data.htmlLSpb)
				}
				$(".select2").select2()
				swal.close()
			}
		})
	}

	function pilihSatuan()
	{
		let h_satuan = parseInt($("#h_satuan").val())
		let satuan1 = $("#h_satuan1_").val()
		let satuan2 = $("#h_satuan2_").val()
		let satuan3 = $("#h_satuan3_").val()
		if(h_satuan == 1){
			$(".txtsatuan").html(`<span style="color:#f00">TERKECIL</span>`)
			$(".hitungqty").html(`<span style="color:#f00">0</span>`)
			$(".ketsatuan").html(`<span style="color:#f00">${satuan3}</span>`)
		}
		if(h_satuan == 2){
			$(".txtsatuan").html(`<span>TERBESAR</span><br><span>TERKECIL</span>`)
			$(".hitungqty").html(`<span>0</span><br><span>0</span>`)
			$(".ketsatuan").html(`<span>${satuan1}</span><br><span>${satuan3}</span>`)
		}
		if(h_satuan == 3){
			$(".txtsatuan").html(`<span>TERBESAR</span><br><span>TENGAH</span><br><span>TERKECIL</span>`)
			$(".hitungqty").html(`<span>0</span><br><span>0</span><br><span>0</span>`)
			$(".ketsatuan").html(`<span>${satuan1}</span><br><span>${satuan2}</span><br><span>${satuan3}</span>`)
		}
		$("#qty").val('')
		$("#i_qty1_").val('')
		$("#i_qty2_").val('')
		$("#i_qty3_").val('')
		$("#plh_departemen").val('')
		$("#plh_bagian").html('<option value="">PILIH</option>')
		$("#ket_pengadaan").val('')
	}

	function pengadaaan()
	{
		const rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let h_satuan = parseInt($("#h_satuan").val())
		let plh_satuan = $("#plh_satuan").val()
		let i_qty = $("#qty").val();
		(isNaN(i_qty) || i_qty == 0 || i_qty < 0 || i_qty.toString().length >= 7) ? i_qty = 0 : i_qty = i_qty;
		$("#qty").val(parseFloat(i_qty).toFixed(2).split('.00').join(''));
		let qty1 = parseInt($("#h_qty1_").val())
		let qty2 = parseInt($("#h_qty2_").val())
		let qty3 = parseInt($("#h_qty3_").val())
		let satuan1 = $("#h_satuan1_").val()
		let satuan2 = $("#h_satuan2_").val()
		let satuan3 = $("#h_satuan3_").val()
		// PERHITUNGAN
		let besar = 0; let tengah = 0; let kecil = 0; let x_besar = 0; let x_tengah = 0; let x_kecil = 0; let style1 = ''; let style2 = ''; let style3 = ''
		if(h_satuan == 1){
			x_kecil = i_qty
			$(".txtsatuan").html(`<span style="color:#f00">TERKECIL</span>`)
			$(".hitungqty").html(`<span style="color:#f00">${i_qty}</span>`)
			$(".ketsatuan").html(`<span style="color:#f00">${satuan3}</span>`)
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
			$(".txtsatuan").html(`<span ${style1}>TERBESAR</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty").html(`<span ${style1}>${x_besar}</span><br><span ${style3}>${x_kecil}</span>`)
			$(".ketsatuan").html(`<span ${style1}>${satuan1}</span><br><span ${style3}>${satuan3}</span>`)
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
			$(".txtsatuan").html(`<span ${style1}>TERBESAR</span><br><span ${style2}>TENGAH</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty").html(`<span ${style1}>${x_besar}</span><br><span ${style2}>${x_tengah}</span><br><span ${style3}>${x_kecil}</span>`)
			$(".ketsatuan").html(`<span ${style1}>${satuan1}</span><br><span ${style2}>${satuan2}</span><br><span ${style3}>${satuan3}</span>`)
		}
		$("#i_qty1_").val(x_besar)
		$("#i_qty2_").val(x_tengah)
		$("#i_qty3_").val(x_kecil)
	}

	function plhDepartemen()
	{
		let plh_departemen = $("#plh_departemen").val()
		$("#plh_bagian").html('<option value="">-</option>')
		$.ajax({
			url: '<?php echo base_url('Qrcode/plhDepartemen')?>',
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
			data: ({ plh_departemen }),
			success: function(res){
				data = JSON.parse(res)
				$("#plh_bagian").html(data.htmlBagian)
				swal.close()
			}
		})
	}

	function prosesSPB()
	{
		let id_stok = $("#id_stok").val()
		let plh_satuan = $("#plh_satuan").val()
		let qty = $("#qty").val()
		let i_qty1 = $("#i_qty1_").val()
		let i_qty2 = $("#i_qty2_").val()
		let i_qty3 = $("#i_qty3_").val()
		let ket_pengadaan = $("#ket_pengadaan").val()
		let plh_departemen = $("#plh_departemen").val()
		let plh_bagian = $("#plh_bagian").val()
		$.ajax({
			url: '<?php echo base_url('Qrcode/prosesSPB')?>',
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
				id_stok, plh_satuan, qty, i_qty1, i_qty2, i_qty3, ket_pengadaan, plh_departemen, plh_bagian
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					loadBarang()
				}else{
					toastr.error(`<b>${data.msg}</b>`)
					swal.close()
				}
			}
		})
	}

	function hapusdSPB(id_spbd)
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
				url: '<?php echo base_url('Qrcode/hapusdSPB')?>',
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
				data: ({ id_spbd }),
				success: function(res){
					data = JSON.parse(res)
					if(data.data){
						loadBarang()
					}else{
						toastr.error(`<b>${data.msg}</b>`)
						swal.close()
					}
				}
			})
		});
	}

	function simpandSPB()
	{
		let h_urut = $("#h_urut").val()
		let pemohon_spb = $("#pemohon_spb").val()
		$.ajax({
			url: '<?php echo base_url('Qrcode/simpandSPB')?>',
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
			data: ({ h_urut, pemohon_spb }),
			success: function(res){
				data = JSON.parse(res)
				if(data.header && data.detail){
						loadBarang()
					}else{
						toastr.error(`<b>${data.msg}</b>`)
						swal.close()
					}
			}
		})
	}
</script>
