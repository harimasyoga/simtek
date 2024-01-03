<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
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
				<?php if (in_array($this->session->userdata('level'), ['Admin','User'])) { ?>
				<button type="button" style="font-family:Cambria;" class="tambah_data btn  btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>Tambah Data</b></button>
				<br><br>
				<?php } ?>
				<!-- <button type="button" class="btn-cetak btn  btn-outline-success pull-right" onclick="cetak(1)">Export Excel</button> -->
				
				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center;width:5%">NO.</th>
							<th style="text-align: center;width:20%">CUSTOMER</th>
							<th style="text-align: center;width:25%">TYPE</th>
							<th style="text-align: center;width:25%">ITEM</th>
							<th style="text-align: center;width:25%">KODE MC</th>
							<th style="text-align: center;width:5%">FLUTE</th>
							<th style="text-align: center;width:10%">KUALITAS</th>
							<th style="text-align: center;width:10%">AKSI</th>
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
			<div class="modal-body" style="overflow:auto;white-space:nowrap">
				<form role="form" method="post" id="myForm">
					<table width="100%" cellspacing="5">
						<tr>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
							<td style="width:10%;border:0;padding:0"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size:12px;font-weight:bold;font-style:italic;color:#f00">* NAMA | KODE</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">CUSTOMER</td>
							<td style="padding:5px 0" colspan="9">
								<input type="hidden" id="h_kode_unik">
								<input type="hidden" id="kode_unik">
								<input type="hidden" id="h_id_pelanggan">
								<select class="form-control select2" id="no_customer"></select>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">SALES</td>
							<td style="padding:5px 0" colspan="3">
								<input type="text" id="nm_sales" class="form-control" disabled>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">NAMA ITEM</td>
							<td style="padding:5px 0" colspan="7">
								<input type="text" class="form-control" id="nm_produk" placeholder="NAMA PRODUK" autocomplete="off" oninput="this.value = this.value.toUpperCase()">
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">KATEGORI</td>
							<td style="padding:5px 0" colspan="3">
								<select class="form-control select2" id="kategori">
									<!-- <option value="">PILIH</option>
									<option value="K_BOX">PRODUK BOX</option>
									<option value="K_SHEET">PRODUK SHEET</option> -->
								</select>
							</td>
							<td></td>
							<td style="padding:5px 0;font-weight:bold">TIPE BOX</td>
							<td style="padding:5px 0" colspan="2">
								<input type="hidden" id="h_tipe_box">
								<select id="tipe_box" class="form-control select2">
									<option value="">PILIH</option>
								</select>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">FLUTE</td>
							<td style="padding:5px 0" colspan="3">
								<input type="hidden" id="h_flute">
								<select class="form-control select2" id="flute" onchange="cflute()"></select>
								<input type="hidden" id="wall">
							</td>
							<td style="padding:5px 0"></td>
							<td style="padding:5px 0;font-weight:bold">SAMBUNGAN</td>
							<td style="padding:5px 0" colspan="2">
								<input type="hidden" id="h_sambungan">
								<select id="sambungan" class="form-control select2" onchange="cflute()"></select>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">P / L / T</td>
							<td style="padding:5px 2px 5px 0">
								<input type="hidden" id="h_panjang">
								<input type="text" class="form-control" id="l_panjang" placeholder="P" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off" onchange="cflute()">
							</td>
							<td style="padding:5px 2px">
								<input type="hidden" id="h_lebar">
								<input type="text" class="form-control" id="l_lebar" placeholder="L" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off" onchange="cflute()">
							</td>
							<td style="padding:5px 0 5px 2px">
								<input type="hidden" id="h_tinggi">
								<input type="text" class="form-control" id="l_tinggi" placeholder="T" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off" onchange="cflute()">
							</td>
							<td></td>
							<td style="padding:5px 0;font-weight:bold">UKURAN BOX</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="ukuran" placeholder="-" autocomplete="off" disabled></td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">CREASING</td>
							<td style="padding:5px 2px 5px 0"><input type="text" class="form-control" id="creasing" placeholder="1" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off"></td>
							<td style="padding:5px 2px"><input type="text" class="form-control" id="creasing2" placeholder="2" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off"></td>
							<td style="padding:5px 0 5px 2px"><input type="text" class="form-control" id="creasing3" placeholder="3" maxlength="4" onkeypress="return hanyaAngka(event)" autocomplete="off"></td>
							<td></td>
							<td style="padding:5px 0;font-weight:bold">UKURAN SHEET</td>
							<td style="padding:5px 5px 5px 0">
								<input type="text" class="form-control" placeholder="PANJANG" name="ukuran_sheet_p" id="ukuran_sheet_p" onchange="cflute()" autocomplete="off" disabled>
							</td>
							<td style="padding:5px 0 5px 5px">
								<input type="text" class="form-control" placeholder="LEBAR" name="ukuran_sheet_l" id="ukuran_sheet_l" onchange="cflute()" autocomplete="off" disabled>
								<input type="hidden" class="form-control" id="ukuran_sheet" placeholder="-" autocomplete="off" disabled>
							</td>
						</tr>
						<tr>
							<td style="padding:5px">
								<select class="form-control select2" id="M_K" onchange="cflute()">
									<option value="">TIPE</option>
									<option value="M">M</option>
									<option value="K">K</option>
								</select>
							<td style="padding:5px"><input style="text-align:center" type="text" class="form-control" id="F_K" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="TL/AL" onchange="cflute()" autocomplete="off"></td>
							</td>
							<td style="padding:5px">
								<select class="form-control select2" id="M_B" onchange="cflute()">
									<option value="">TIPE</option>
									<option value="M">M</option>
									<option value="K">K</option>
								</select>
							<td style="padding:5px"><input style="text-align:center" type="text" class="form-control" id="F_B" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="B.MF" onchange="cflute()" autocomplete="off"></td>
							</td>
							<td style="padding:5px">
								<select class="form-control select2" id="M_CL" onchange="cflute()">
									<option value="">TIPE</option>
									<option value="M">M</option>
									<option value="K">K</option>
								</select>
							<td style="padding:5px"><input style="text-align:center" type="text" class="form-control" id="F_CL" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="BC" onchange="cflute()" autocomplete="off"></td>
							</td>
							<td style="padding:5px">
								<select class="form-control select2" id="M_C" onchange="cflute()">
									<option value="">TIPE</option>
									<option value="M">M</option>
									<option value="K">K</option>
								</select>
							<td style="padding:5px"><input style="text-align:center" type="text" class="form-control" id="F_C" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="C.MF" onchange="cflute()" autocomplete="off"></td>
							</td>
							<td style="padding:5px">
								<select class="form-control select2" id="M_BL" onchange="cflute()">
									<option value="">TIPE</option>
									<option value="M">M</option>
									<option value="K">K</option>
								</select>
							<td style="padding:5px"><input style="text-align:center" type="text" class="form-control" id="F_BL" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="B/C.L" onchange="cflute()" autocomplete="off"></td>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">KODE MC</td>
							<td style="padding:5px 0" colspan="7">
								<input type="hidden" id="id">
								<input type="hidden" id="h_kode_mc">
								<input type="text" class="form-control" id="kode_mc" placeholder="KODE MC" autocomplete="off" disabled>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">KUALITAS</td>
							<td style="padding:5px 0" colspan="3">
								<input type="hidden" id="h_kualitas">
								<input type="hidden" id="h_kualitas_isi">
								<input type="text" class="form-control" id="kualitas" placeholder="-" disabled>
							</td>
							<td></td>
							<td style="padding:5px 0;font-weight:bold">MATERIAL</td>
							<td style="padding:5px 0" colspan="2">
								<input type="hidden" id="h_material">
								<input type="text" class="form-control" id="material" placeholder="-" disabled>
							</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">BERAT BOX</td>
							<td style="padding:5px 0">
								<input type="hidden" id="h_berat_bersih">
								<input type="text" class="form-control" id="berat_bersih" placeholder="-" disabled>
							</td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">LUAS BOX</td>
							<td style="padding:5px 0">
								<input type="hidden" id="h_luas_bersih">
								<input type="text" class="form-control" id="luas_bersih" placeholder="-" disabled>
							</td>
							<td></td>
							<td style="padding:5px 0;font-weight:bold">TOLERANSI KIRIM</td>
							<td style="padding:5px 0"><input type="text" class="form-control" id="toleransi_kirim" maxlength="3" onkeypress="return hanyaAngka(event)" placeholder="-"></td>
							<td style="padding:5px 0 5px 5px">%</td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">NO DESIGN</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="no_design" placeholder="-" autocomplete="off"></td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">WARNA</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="warna" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">DESIGN</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="design" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">JENIS PRODUK</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="jenis_produk" placeholder="-" autocomplete="off"></td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">JML IKAT</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="jml_ikat" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">JML PALET</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="jml_palet" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">JML PAKU</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="jml_paku" placeholder="-" autocomplete="off"></td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">NO KARET</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="no_karet" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">NO PISAU</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="no_pisau" placeholder="-" autocomplete="off"></td>
						</tr>
						<tr>
							<td style="padding:5px 0;font-weight:bold">SPESIAL REQUEST</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="spesial_req" placeholder="-" autocomplete="off"></td>
							<td style="padding:5px 0 5px 5px;font-weight:bold">COA</td>
							<td style="padding:5px 0" colspan="2"><input type="text" class="form-control" id="COA" placeholder="-" autocomplete="off"></td>
						</tr>
					</table>
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
		$('.select2').select2({
			dropdownAutoWidth: true
		});
	});

	status = "insert";
	$(".tambah_data").click(function(event) {
		status = "insert";
		kosong();
		getPlhCustomer()
		$("#judul").html('<h3> Form Tambah Data</h3>');
		$("#modalForm").modal("show");
	});

	function load_data() {
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Master/load_data/produk',
				"type": "POST",
			},
			responsive: true,
			"pageLength": 10,
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
		$("#btn-simpan").prop("disabled", true);
		id                = $("#id").val();
		kode_mc           = $("#kode_mc").val();
		nm_produk         = $("#nm_produk").val();
		h_id_pelanggan    = $("#h_id_pelanggan").val();
		no_customer       = $("#no_customer").val();
		ukuran            = $("#ukuran").val();
		ukuran_sheet      = $("#ukuran_sheet").val();
		sambungan         = $("#sambungan").val();
		material          = $("#material").val();
		wall              = $("#wall").val();
		l_panjang         = $("#l_panjang").val();
		l_lebar           = $("#l_lebar").val();
		l_tinggi          = $("#l_tinggi").val();
		creasing          = $("#creasing").val();
		creasing2         = $("#creasing2").val();
		creasing3         = $("#creasing3").val();
		flute             = $("#flute").val();
		berat_bersih      = $("#berat_bersih").val();
		luas_bersih       = $("#luas_bersih").val();
		kualitas          = $("#kualitas").val();
		kualitas_isi      = $("#h_kualitas_isi").val()
		warna             = $("#warna").val();
		no_design         = $("#no_design").val();
		design            = $("#design").val();
		tipe_box          = $("#tipe_box").val();
		jenis_produk      = $("#jenis_produk").val();
		kategori          = $("#kategori").val();
		cCOA              = $("#COA").val();
		jml_ikat          = $("#jml_ikat").val();
		jml_palet         = $("#jml_palet").val();
		jml_paku          = $("#jml_paku").val();
		no_pisau          = $("#no_pisau").val();
		no_karet          = $("#no_karet").val();
		toleransi_kirim   = $("#toleransi_kirim").val();
		spesial_req       = $("#spesial_req").val();
		ukuran_sheet_p    = $("#ukuran_sheet_p").val();
		ukuran_sheet_l    = $("#ukuran_sheet_l").val();

		ukuran_sheet = ukuran_sheet_p + ' X '+ukuran_sheet_l
		$("#ukuran_sheet").val(ukuran_sheet)

		if(kategori == 'K_SHEET' && (l_panjang == '' || l_lebar == '')){
			swal("HARAP LENGKAPI FORM!", "", "info")
			$("#btn-simpan").prop("disabled", false)
			return;
		}
		if(kategori == 'K_BOX' && (l_panjang == '' || l_lebar == '' || l_tinggi == '')){
			swal("HARAP LENGKAPI FORM!", "", "info")
			$("#btn-simpan").prop("disabled", false)
			return;
		}

		if (kode_mc == '' || nm_produk == '' || no_customer == '' || ukuran == '' || ukuran_sheet == '' || sambungan == '' || material == '' || wall == '' || creasing == '' || creasing2 == '' || creasing3 == '' || flute == '' || berat_bersih == '' || luas_bersih == '' || kualitas == '' || warna == '' || no_design == '' || design == '' || tipe_box == '' || jenis_produk == '' || kategori == '' || cCOA == '' || jml_ikat == '' || jml_palet == '' || jml_paku == '' || no_pisau == '' || no_karet == '' || toleransi_kirim == '' || spesial_req == '') {
			swal("HARAP LENGKAPI FORM!", "", "info")
			$("#btn-simpan").prop("disabled", false)
			return;
		}

		$.ajax({
			url: '<?php echo base_url('Master/Insert') ?>',
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
				id, kode_mc, nm_produk, h_id_pelanggan, no_customer, ukuran, ukuran_sheet, sambungan, material, wall, l_panjang, l_lebar, l_tinggi, creasing, creasing2, creasing3, flute, berat_bersih, luas_bersih, kualitas, kualitas_isi, warna, no_design, design, tipe_box, jenis_produk, kategori, COA:cCOA, jml_ikat, jml_palet, jml_paku, no_pisau, no_karet, toleransi_kirim, spesial_req, ukuran_sheet_p, ukuran_sheet_l, jenis: 'm_produk', status: status
			}),
			success: function(json) {
				data = JSON.parse(json)
				// console.log(data)
				if(data.result == true) {
					toastr.success('BERHASIL DISIMPAN!');
					kosong();
					$("#modalForm").modal("hide");
					reloadTable();
					swal.close()
				}else{
					swal("ITEM SUDAH ADA!", "", "error")
					$("#btn-simpan").prop("disabled", false);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error('TERJADI KESALAHAN!');
			}
		});
	}

	$('#no_customer').on('change', function() {
		let no_cust = $('#no_customer').val()
		let nm_sales = $('#no_customer option:selected').attr('data-sales')
		let kode_unik = $('#no_customer option:selected').attr('kode_unik')
		$("#h_id_pelanggan").val(no_cust)
		$("#nm_sales").val(nm_sales)
		$("#kode_unik").val(kode_unik)
		if(no_cust == ""){
			kosong()
			$("#no_customer").prop("disabled", false);
		}
		buatKodeMC()
	})

	function getPlhCustomer() {
		$("#no_customer").html(`<option value="">PILIH</option>`).prop("disabled", true)
		$.ajax({
			url: '<?php echo base_url('Master/getPlhCustomer')?>',
			type: "POST",
			success: function(json) {
				data = JSON.parse(json)
				// console.log(data)
				let htmlCust = ''	
				htmlCust += `<option value="">PILIH</option>`
				data.forEach(loadCust)
				function loadCust(r, index) {
					htmlCust += `<option value="${r.id_pelanggan}" data-sales="${r.nm_sales}" kode_unik="${r.kode_unik}">${r.nm_pelanggan} | ${r.kode_unik}</option>`
				}
				$("#no_customer").prop("disabled", false).html(htmlCust)
			}
		})
	}

	function kosong() {
		$("#h_kode_unik").val("")
		$("#h_tipe_box").val("")
		$("#h_flute").val("")
		$("#h_sambungan").val("")
		$("#h_panjang").val("")
		$("#h_lebar").val("")
		$("#h_tinggi").val("")
		$("#h_material").val("")
		$("#h_kualitas").val("")
		$("#h_kualitas_isi").val("")
		$("#h_kode_mc").val("")
		$("#h_berat_bersih").val("")
		$("#h_luas_bersih").val("")

		$("#id").val("");
		$("#kode_mc").val("").prop("disabled", true);
		$("#nm_produk").val("");
		$("#kode_unik").val("");
		$("#h_id_pelanggan").val("");
		$("#no_customer").val("").prop("disabled", true);
		$("#nm_sales").val("-");
		$("#ukuran").val("");
		$("#ukuran_sheet").val("");
		$("#ukuran_sheet_p").val("").prop('disabled', true);
		$("#ukuran_sheet_l").val("").prop('disabled', true);
		$("#sambungan").html(`<option value="">PILIH</option>`).prop("disabled", true);
		$("#material").val("");
		$("#wall").val("");
		$("#l_panjang").val("").prop("disabled", true);
		$("#l_lebar").val("").prop("disabled", true);
		$("#l_tinggi").val("").prop("disabled", true);
		$("#creasing").val("").prop("disabled", false);
		$("#creasing2").val("").prop("disabled", false);
		$("#creasing3").val("").prop("disabled", false);
		$("#flute").html(`</option><option value="">PILIH</option><option value="BCF">BCF</option><option value="CF">CF</option><option value="BF">BF</option>`).prop("disabled", true);
		$("#berat_bersih").val("");
		$("#luas_bersih").val("");
		$("#kualitas").val("");
		$("#warna").val("-");
		$("#no_design").val("-");
		$("#design").val("-");
		$("#tipe_box").html(`<option value="">PILIH</option>`).prop("disabled", true);
		$("#jenis_produk").val("-");
		$("#kategori").html(`<option value="">PILIH</option><option value="K_BOX">PRODUK BOX</option><option value="K_SHEET">PRODUK SHEET</option>`).prop("disabled", false);
		$("#COA").val("-");
		$("#jml_ikat").val("-");
		$("#jml_palet").val("-");
		$("#jml_paku").val("-");
		$("#no_pisau").val("-");
		$("#no_karet").val("-");
		$("#toleransi_kirim").val(0);
		$("#spesial_req").val("-");
		status = 'insert';
		$("#btn-simpan").show().prop("disabled", false);
		zFlute("disable");
	}

	function zFlute(opsi) {
		if(opsi == "disable"){
			ket = true
		} else {
			ket = false
		}

		$("#M_K").val("").prop("disabled", ket);
		$("#F_K").val("").prop("disabled", ket);
		$("#M_B").val("").prop("disabled", ket);
		$("#F_B").val("").prop("disabled", ket);
		$("#M_CL").val("").prop("disabled", ket);
		$("#F_CL").val("").prop("disabled", ket);
		$("#M_C").val("").prop("disabled", ket);
		$("#F_C").val("").prop("disabled", ket);
		$("#M_BL").val("").prop("disabled", ket);
		$("#F_BL").val("").prop("disabled", ket);
		$("#wall").val("");
		$("#material").val("");
		$("#kualitas").val("");
		$("#berat_bersih").val("");
		$("#luas_bersih").val("");
		$("#kode_mc").val("");
	}

	function tampil_edit(id, act) {
		kosong();
		$("#modalForm").modal("show");
		if (act == 'detail') {
			$("#judul").html('<h3> Detail Data</h3>');
			$("#btn-simpan").hide();
		} else {
			$("#judul").html('<h3> Form Edit Data</h3>');
			$("#btn-simpan").show().prop("disabled", true)
		}
		$("#jenis").val('Update');

		status = "update";

		$.ajax({
			url: '<?php echo base_url('Master/getEditProduk'); ?>',
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
			data: ({
				id
			})
		})
		.done(function(json) {
			data = JSON.parse(json)
			// console.log(data)

			$("#id").val(data.produk.id_produk);
			$("#h_id_pelanggan").val(data.produk.no_customer);
			let htmlCust = ''
			htmlCust += `<option value="${data.produk.no_customer}" data-sales="${data.produk.nm_sales}" kode_unik="${data.produk.kode_unik}">
					${data.produk.customer} | ${data.produk.kode_unik}
			</option>`
			if(data.poDetail.length == 0){
				data.pelanggan.forEach(loadCust)
				function loadCust(r, index) {
					htmlCust += `<option value="${r.id_pelanggan}" data-sales="${r.nm_sales}" kode_unik="${r.kode_unik}">
						${r.nm_pelanggan} | ${r.kode_unik}
					</option>`
				}
			}
			$("#kode_unik").val(data.produk.kode_unik)
			$("#no_customer").html(htmlCust).prop("disabled", (data.poDetail.length == 0) ? false : true);
			$("#nm_sales").val(data.produk.nm_sales);

			$("#h_kode_mc").val(data.produk.kode_mc);
			$("#kode_mc").val(data.produk.kode_mc).prop("disabled", true);
			$("#nm_produk").val(data.produk.nm_produk);
			$("#ukuran").val(data.produk.ukuran);
			$("#ukuran_sheet_p").val(data.produk.ukuran_sheet_p).prop('disabled', (data.produk.sambungan == 'D') ? false : true );
			$("#ukuran_sheet_l").val(data.produk.ukuran_sheet_l).prop('disabled', (data.produk.sambungan == 'D') ? false : true );
			$("#material").val(data.produk.material);
			$("#wall").val(data.produk.wall);
			$("#l_panjang").val(data.produk.l_panjang).prop("disabled", (data.poDetail.length == 0) ? false : true)
			$("#l_lebar").val(data.produk.l_lebar).prop("disabled", (data.poDetail.length == 0) ? false : true)
			$("#l_tinggi").val(data.produk.l_tinggi).prop("disabled", (data.poDetail.length == 0 && data.produk.kategori == 'K_BOX') ? false : true)
			$("#creasing").val(data.produk.creasing).prop("disabled", (data.poDetail.length == 0) ? false : true);
			$("#creasing2").val(data.produk.creasing2).prop("disabled", (data.poDetail.length == 0) ? false : true);
			$("#creasing3").val(data.produk.creasing3).prop("disabled", (data.poDetail.length == 0) ? false : true);

			$("#flute").html(`<option value="${data.produk.flute}">${data.produk.flute}</option><option value="">PILIH</option><option value="BCF">BCF</option><option value="CF">CF</option><option value="BF">BF</option>`).prop("disabled", (data.poDetail.length == 0) ? false : true);

			$("#berat_bersih").val(data.produk.berat_bersih);
			$("#luas_bersih").val(data.produk.luas_bersih);
			$("#kualitas").val(data.produk.kualitas);
			$("#warna").val(data.produk.warna);
			$("#no_design").val(data.produk.no_design);
			$("#design").val(data.produk.design);

			$("#tipe_box").html((data.produk.kategori == 'K_BOX') ? `<option value="${data.produk.tipe_box}">${data.produk.tipe_box}</option>` : `<option value="-">-</option>`).prop("disabled", (data.poDetail.length == 0 && data.produk.kategori == 'K_BOX') ? false : true);
			$("#sambungan").html((data.produk.kategori == 'K_BOX') ? `<option value="${data.produk.sambungan}">${data.produk.sambungan}</option><option value="">PILIH</option><option value="G">GLUE</option><option value="S">STICHING</option><option value="GS">GLUE STICHING</option><option value="DS">DOUBLE STICHING</option><option value="D">DIE CUT</option>` : `<option value="-">-</option>`).prop("disabled", (data.poDetail.length == 0 && data.produk.kategori == 'K_BOX') ? false : true);

			$("#jenis_produk").val(data.produk.jenis_produk);

			let nmKategori = ''
			if(data.produk.kategori == 'K_BOX'){
				nmKategori = 'BOX'
			}else{
				nmKategori = ' SHEET'
			}
			$("#kategori").html(`<option value="${data.produk.kategori}">${nmKategori}</option><option value="">PILIH</option><option value="K_BOX">PRODUK BOX</option><option value="K_SHEET">PRODUK SHEET</option>`).prop("disabled", (data.poDetail.length == 0) ? false : true);


			$("#COA").val(data.produk.COA);
			$("#jml_ikat").val(data.produk.jml_ikat);
			$("#jml_palet").val(data.produk.jml_palet);
			$("#jml_paku").val(data.produk.jml_paku);
			$("#no_pisau").val(data.produk.no_pisau);
			$("#no_karet").val(data.produk.no_karet);
			$("#toleransi_kirim").val(data.produk.toleransi_kirim);
			$("#spesial_req").val(data.produk.spesial_req);

			$("#h_kode_unik").val(data.produk.kode_unik)
			$("#h_tipe_box").val(data.produk.tipe_box)
			$("#h_flute").val(data.produk.flute)
			$("#h_sambungan").val(data.produk.sambungan)
			$("#h_panjang").val(data.produk.l_panjang)
			$("#h_lebar").val(data.produk.l_lebar)
			$("#h_tinggi").val(data.produk.l_tinggi)
			$("#h_kualitas").val(data.produk.kualitas)
			$("#h_kualitas_isi").val(data.produk.kualitas_isi)
			$("#h_material").val(data.produk.material)
			$("#h_berat_bersih").val(data.produk.berat_bersih)
			$("#h_luas_bersih").val(data.produk.luas_bersih)

			$("#btn-simpan").prop("disabled", false)

			swal.close()
		})
	}


	function deleteData(id) {
		let cek = confirm("Apakah Anda Yakin?");

		if (cek) {
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
					jenis: 'm_produk',
					field: 'id_produk'
				}),
				type: "POST",
				success: function(data) {
					toastr.success('Data Berhasil Di Hapus');
					reloadTable();
					swal.close()
				},
				error: function(jqXHR, textStatus, errorThrown) {
					toastr.error('Terjadi Kesalahan');
				}
			});
		}
	}

	function cetak(ctk) {
		var url = "<?php echo base_url('Laporan/Laporan_Stok'); ?>";
		window.open(url, '_blank');
	}

	function hanyaAngka(evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
		return true;
	}

	$('#kategori').on('change', function() {
		let plh_tipe = $("#kategori").val();
		let l_panjang = $("#l_panjang").val();
		let l_lebar = $("#l_lebar").val();
		let l_tinggi = $("#l_tinggi").val();

		$("#h_kode_mc").val("")
		$("#kode_mc").val("")
		$("#ukuran").val("-")
		$("#creasing").val(0)
		$("#creasing2").val(0)
		$("#creasing3").val(0)
		$("#ukuran_sheet_p").val("")
		$("#ukuran_sheet_l").val("")

		if(plh_tipe == ""){
			zFlute("disable");
			$("#flute").val("").prop("disabled", true);
			$("#sambungan").html(`<option value="">PILIH</option>`).prop("disabled", true);
			$("#tipe_box").html(`<option value="">PILIH</option>`).prop("disabled", true);
			$("#l_panjang").val("").prop("disabled", true);
			$("#l_lebar").val("").prop("disabled", true);
			$("#l_tinggi").val("").prop("disabled", true);
		} else if(plh_tipe == "K_BOX"){
			zFlute("disable");
			$("#flute").val("").prop("disabled", false);
			$("#sambungan").html(`<option value="">PILIH</option><option value="G">GLUE</option><option value="S">STICHING</option><option value="GS">GLUE STICHING</option><option value="DS">DOUBLE STICHING</option><option value="D">DIE CUT</option>`).prop("disabled", false);
			$("#tipe_box").html(`<option value="A1">A1</option>`).prop("disabled", false);
			$("#l_panjang").val("").prop("disabled", false);
			$("#l_lebar").val("").prop("disabled", false);
			$("#l_tinggi").val("").prop("disabled", false);
		} else if(plh_tipe == "K_SHEET"){
			zFlute("disable");
			$("#flute").val("").prop("disabled", false);
			$("#sambungan").html(`<option value="-">-</option>`).prop("disabled", true);
			$("#tipe_box").html(`<option value="-">-</option>`).prop("disabled", true);
			$("#l_panjang").val("").prop("disabled", false);
			$("#l_lebar").val("").prop("disabled", false);
			$("#l_tinggi").val("").prop("disabled", true);
		}
	})

	$('#flute').on('change', function() {
		let tipee = $("#kategori").val();
		let plh_flute = $("#flute").val();

		if(plh_flute == ""){
			zFlute("disable");
		} else if(plh_flute == "BCF"){
			zFlute("false");
			$("#wall").val("DOUBLE");
		} else if(plh_flute == "CF") {
			zFlute("disable");
			$("#M_K").val("").prop("disabled", false);
			$("#F_K").val("").prop("disabled", false);
			$("#M_C").val("").prop("disabled", false);
			$("#F_C").val("").prop("disabled", false);
			$("#M_BL").val("").prop("disabled", false);
			$("#F_BL").val("").prop("disabled", false);
			$("#wall").val("SINGLE");
		} else if(plh_flute == "BF") {
			zFlute("disable");
			$("#M_K").val("").prop("disabled", false);
			$("#F_K").val("").prop("disabled", false);
			$("#M_B").val("").prop("disabled", false);
			$("#F_B").val("").prop("disabled", false);
			$("#M_BL").val("").prop("disabled", false);
			$("#F_BL").val("").prop("disabled", false);
			$("#wall").val("SINGLE");
		} else {
			zFlute("disable")
		}
	})

	function cflute(){
		let k = document.getElementById('M_K').value;
		let kk = document.getElementById('F_K').value;
		let b = document.getElementById('M_B').value;
		let bb = document.getElementById('F_B').value;
		let cl = document.getElementById('M_CL').value;
		let clcl = document.getElementById('F_CL').value;
		let c = document.getElementById('M_C').value;
		let cc = document.getElementById('F_C').value;
		let bl = document.getElementById('M_BL').value;
		let blbl = document.getElementById('F_BL').value;
		let tipee = $("#kategori").val();
		let plh_flute = $("#flute").val();
		let r_panjang = parseInt(document.getElementById('l_panjang').value);
		let r_lebar = parseInt(document.getElementById('l_lebar').value);
		let r_tinggi = parseInt(document.getElementById('l_tinggi').value);

		let h_kode_mc = $("#h_kode_mc").val()
		let kode_unik = $("#kode_unik").val()
		let h_kode_unik = $("#h_kode_unik").val()
		let h_tipe_box = $("#h_tipe_box").val()
		let h_flute = $("#h_flute").val()
		let sambungan = $("#sambungan").val()
		let h_sambungan = $("#h_sambungan").val()
		let hi_panjang = $("#h_panjang").val()
		let hi_lebar = $("#h_lebar").val()
		let hi_tinggi = $("#h_tinggi").val()
		let kualitas = $("#kualitas").val()
		let h_kualitas = $("#h_kualitas").val()
		let h_material = $("#h_material").val()
		let h_berat_bersih = $("#h_berat_bersih").val()
		let h_luas_bersih = $("#h_luas_bersih").val()

		if(k == "" || kk == ""){
			gabKK = "";
			txtK = "";
		}else{
			gabKK = k + kk;
			txtK = k;
		}

		if(b == "" || bb == ""){
			gabBB = "";
			txtB = "";
		}else{
			gabBB = "/" + b + bb;
			txtB = "/" + b;
		}

		if(cl == "" || clcl == ""){
			gabCL = "";
			txtCL = "";
		}else{
			gabCL = "/" + cl + clcl;
			txtCL = "/" + cl;
		}
		
		if(c == "" || cc == ""){
			gabCC = "";
			txtCC = "";
		}else{
			gabCC = "/" + c + cc;
			txtCC = "/" + c;
		}

		if(bl == "" || blbl == ""){
			gabBL = "";
			txtBL = "";
		}else{
			gabBL = "/" + bl + blbl;
			txtBL = "/" + bl;
		}

		let txtKualitas = ''
		let txtMaterial = ''
		if(plh_flute == "BCF"){
			if(gabKK == "" || gabBB == "" || gabCL == "" || gabCC == "" || gabBL == ""){
				txtKualitas = ''
				txtMaterial = ''
			}else{
				txtKualitas = gabKK + gabBB + gabCL + gabCC + gabBL
				txtMaterial = txtK + txtB + txtCL + txtCC + txtBL
			}
		}else if(plh_flute == "CF"){
			if(gabKK == "" || gabCC == "" || gabBL == ""){
				txtKualitas = ''
				txtMaterial = ''
			}else{
				txtKualitas = gabKK + gabCL + gabCC + gabBL
				txtMaterial = txtK + txtCC + txtBL
			}
		}else if(plh_flute == "BF"){
			if(gabKK == "" || gabBB == "" || gabBL == ""){
				txtKualitas = ''
				txtMaterial = ''
			}else{
				txtKualitas = gabKK + gabBB + gabCL + gabBL
				txtMaterial = txtK + txtB + txtBL
			}
		}else{
			txtKualitas = ''
			txtMaterial = ''
		}

		let cariBF = parseFloat(bb  * 1.36)
		let cariCF = parseFloat(cc  * 1.46)
		let getNilaiFlute = 0
		let kualitasIsi = 0
		if(plh_flute == "BCF"){
			getNilaiFlute = parseFloat((parseInt(kk) + cariBF + parseInt(clcl) + cariCF + parseInt(blbl)) / 1000);
			kualitasIsi = kk+'/'+bb+'/'+clcl+'/'+cc+'/'+blbl;
		} else if(plh_flute == "CF") {
			getNilaiFlute = parseFloat((parseInt(kk) + cariCF + parseInt(blbl)) / 1000);
			kualitasIsi = kk+'/'+cc+'/'+blbl;
		} else if(plh_flute == "BF") {
			getNilaiFlute = parseFloat((parseInt(kk) + cariBF + parseInt(blbl)) / 1000);
			kualitasIsi = kk+'/'+bb+'/'+blbl;
		} else {
			getNilaiFlute = 0
			kualitasIsi = 0
		}
		$("#h_kualitas_isi").val(kualitasIsi)

		if(isNaN(r_panjang)){
			r_panjang = 0;
		}
		if(isNaN(r_lebar)){
			r_lebar = 0;
		}
		if(isNaN(r_tinggi)){
			r_tinggi = 0;
		}

		let ruk_p = '';
		let ruk_l = '';
		let tfx = '';
		if(tipee == "K_BOX"){
			if(r_panjang == '' || r_panjang == 0 || r_lebar == '' || r_lebar == 0 || r_tinggi == 0 || r_tinggi == ''){
				ruk_p = '';
				ruk_l = '';
			}else{
				if(plh_flute == ""){
					ruk_p = '';
					ruk_l = '';
				} else if(plh_flute == "BCF"){
					ruk_p = 2 * (r_panjang + r_lebar) + 61;
					ruk_l = r_lebar + r_tinggi + 23;
				} else if(plh_flute == "CF") {
					ruk_p = 2 * (r_panjang + r_lebar) + 43;
					ruk_l = r_lebar + r_tinggi + 13;
				} else if(plh_flute == "BF") {
					ruk_p = 2 * (r_panjang + r_lebar) + 39;
					ruk_l = r_lebar + r_tinggi + 9;
				} else {
					ruk_p = '';
					ruk_l = '';
				}
			}
			tfx = 4
		}else{
			if(r_panjang == '' || r_panjang == 0 || r_lebar == '' || r_lebar == 0){
				ruk_p = '';
				ruk_l = '';
			}else{
				ruk_p = r_panjang;
				ruk_l = r_lebar;
			}
			tfx = 3
		}

		let panjangDieCut = 0
		let lebarDieCut = 0
		if(sambungan == 'D'){
			if(r_panjang == "" || r_panjang == 0 || r_lebar == "" || r_lebar == 0 || r_tinggi == "" || r_tinggi == 0){
				$("#ukuran_sheet_p").val("").prop('disabled', true)
				$("#ukuran_sheet_l").val("").prop('disabled', true)
			}else{
				panjangDieCut = $("#ukuran_sheet_p").val();
				(panjangDieCut == ruk_p) ? $("#ukuran_sheet_p").val(ruk_p) : $("#ukuran_sheet_p").val();
				$("#ukuran_sheet_p").prop('disabled', false)

				lebarDieCut = $("#ukuran_sheet_l").val();
				(lebarDieCut == ruk_l) ? $("#ukuran_sheet_l").val(ruk_l) : $("#ukuran_sheet_l").val();
				$("#ukuran_sheet_l").prop('disabled', false)

				ruk_p = panjangDieCut
				ruk_l = lebarDieCut
			}
		}else{
			$("#ukuran_sheet_p").val(ruk_p).prop('disabled', true)
			$("#ukuran_sheet_l").val(ruk_l).prop('disabled', true)
		}

		if(tipee == "K_BOX"){
			if(r_panjang == '' || r_panjang == 0 || r_lebar == '' || r_lebar == 0 || r_tinggi == '' || r_tinggi == 0){
				txtPL = "";
			}else{
				txtPL = r_panjang + " X " + r_lebar + " X " + r_tinggi;
			}
		}else{
			txtPL = "-";
		}
		document.getElementById('ukuran').value = txtPL;

		// HITUNG
		let h_panjang = parseFloat(ruk_p / 1000);
		let h_lebar = parseFloat(ruk_l / 1000);
		let nilaiBeratBersih = parseFloat(getNilaiFlute * h_panjang * h_lebar).toFixed(tfx);
		let nilaiLuasBersih = parseFloat(h_panjang * h_lebar).toFixed(3);

		// KONDISI UPDATE
		let cekKualitas = txtKualitas
		let cekMaterial = txtMaterial
		let cekBB = ''
		let cekLB = ''
		// if(status == 'update' && tipee == 'K_BOX' && kode_unik == h_kode_unik && plh_flute == h_flute && sambungan == h_sambungan && r_panjang == hi_panjang && r_lebar == hi_lebar && r_tinggi == hi_tinggi){
		// 	cekBB = h_berat_bersih
		// 	cekLB = h_luas_bersih
		// }else if(status == 'update' && tipee == 'K_SHEET' && kode_unik == h_kode_unik && plh_flute == h_flute && r_panjang == hi_panjang && r_lebar == hi_lebar && r_tinggi == hi_tinggi){
		// 	cekBB = h_berat_bersih
		// 	cekLB = h_luas_bersih
		// }else{
		if(isNaN(getNilaiFlute) || isNaN(h_panjang) || isNaN(h_lebar) || isNaN(nilaiBeratBersih) || nilaiBeratBersih == 0 || isNaN(nilaiLuasBersih) || nilaiLuasBersih == 0){
			cekBB = ""
			cekLB = ""
		}else{
			cekBB = nilaiBeratBersih
			cekLB = nilaiLuasBersih
		}
		// }
		document.getElementById('kualitas').value = cekKualitas
		document.getElementById('material').value = cekMaterial
		document.getElementById('berat_bersih').value = cekBB
		document.getElementById('luas_bersih').value = cekLB

		buatKodeMC()
	}

	function buatKodeMC(){
		$("#kode_mc").val("").attr("placeholder", "CEK PROSES KODE MC . . .")
		let mcNoCust = $("#no_customer").val()
		let mcKodeUnik = $("#kode_unik").val()
		let mcKategori = $("#kategori").val()
		let mcPanjang = $("#l_panjang").val()
		let mcLebar = $("#l_lebar").val()
		let mcTinggi = ($("#l_tinggi").val() == '' || $("#l_tinggi").val() == null) ? 0 : $("#l_tinggi").val()
		let mcPSheet = $("#ukuran_sheet_p").val()
		let mcLSheet = $("#ukuran_sheet_l").val()
		let mcFlute = $("#flute").val()
		let mcTipeBox = $("#tipe_box").val()
		let mcSambungan = $("#sambungan").val()
		let mcKualitas = $("#kualitas").val()

		let h_kode_mc = $("#h_kode_mc").val()
		// console.log(h_kode_mc)
		let h_kode_unik = $("#h_kode_unik").val()
		let h_tipe_box = $("#h_tipe_box").val()
		let h_flute = $("#h_flute").val()
		let h_sambungan = $("#h_sambungan").val()
		let hi_panjang = $("#h_panjang").val()
		let hi_lebar = $("#h_lebar").val()
		let hi_tinggi = $("#h_tinggi").val()
		let h_kualitas = $("#h_kualitas").val()

		let mcKodeMc = ''
		if(status == 'update' && mcKategori == 'K_BOX' && mcKodeUnik == h_kode_unik && mcFlute == h_flute && mcSambungan == h_sambungan && mcPanjang == hi_panjang && mcLebar == hi_lebar && mcTinggi == hi_tinggi){
			document.getElementById('kode_mc').value = h_kode_mc
		}else if(status == 'update' && mcKategori == 'K_SHEET' && mcKodeUnik == h_kode_unik && mcFlute == h_flute && mcPanjang == hi_panjang && mcLebar == hi_lebar && mcTinggi == hi_tinggi){
			document.getElementById('kode_mc').value = h_kode_mc
		}else{
			$.ajax({
				url: '<?php echo base_url('Master/buatKodeMC')?>',
				type: "POST",
				data: ({
					mcNoCust, mcKodeUnik, mcKategori, mcPanjang, mcLebar, mcTinggi, mcFlute, mcTipeBox, mcSambungan, mcKualitas
				}),
				success: function(res){
					data = JSON.parse(res)
					// console.log(data)
					if(mcNoCust == '' || mcKodeUnik == '' || mcKategori == '' || mcSambungan == '' || mcFlute == ''){
						mcKodeMc = ''
					}else{
						if(mcKategori == 'K_BOX'){
							if(mcSambungan == '' && mcTipeBox == ''){
								mcKodeMc = ''
							}else{
								if(mcFlute == ''){
									mcKodeMc = ''
								}else{
									mcKodeMc = `${mcKodeUnik}-${mcTipeBox}-${mcSambungan}-${mcFlute}-${data.mcNoUrut}`
								}
							}
						}else if(mcKategori == 'K_SHEET'){
							if(mcPanjang == ''){
								mcKodeMc = ''
							}else{
								if(mcLebar == ''){
									mcKodeMc = ''
								}else{
									mcKodeMc = `${mcKodeUnik}-${mcPanjang}-${mcLebar}-${mcFlute}-${data.mcNoUrut}`
								}
							}
						}else{
							mcKodeMc = ''
						}
					}
					document.getElementById('kode_mc').value = mcKodeMc
				}
			})
		}
	}
</script>
