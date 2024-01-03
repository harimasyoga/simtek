<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Data Master </h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item active"><a href="#"><?= $judul ?></a></li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?= $judul ?></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<button type="button" class="tambah_data btn  btn-outline-primary pull-right">Tambah Data</button>
				<!-- <button type="button" class="btn-cetak btn  btn-outline-success pull-right" onclick="cetak(1)">Export Excel</button> -->
				<br><br>
				<table id="datatable" class="table table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th style="width:10%">Kode MC</th>
							<th style="width:20%">Nama Produk</th>
							<th style="width:20%">Ukuran Box</th>
							<th style="width:10%">Material</th>
							<th style="width:10%">Flute</th>
							<th style="width:10%">Creasing</th>
							<th style="width:10%">Warna</th>
							<th style="width:10%">Aksi</th>
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
			<div class="modal-body">
				<form role="form" method="post" id="myForm">
					<table width="100%" cellspacing="5" cellpadding="5">
						<tr>
							<td width="15%">Kode MC</td>
							<td width="23%">
								<input type="hidden" class="form-control" id="id">
								<input type="hidden" class="form-control" id="kode_mc_lama">
								<input type="text" class="form-control" id="kode_mc" placeholder="Masukan..">
							</td>
							<td width="15%"></td>
							<td width="15%">Nama produk</td>
							<td width="23%"><input type="text" class="form-control" id="nm_produk" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>No customer</td>
							<td><input type="text" class="form-control" id="no_customer" placeholder="Masukan.."></td>
							<td></td>
							<td>customer</td>
							<td><input type="text" class="form-control" id="customer" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Ukuran BOX</td>
							<td><input type="text" class="form-control" id="ukuran" placeholder="Masukan.."></td>
							<td></td>
							<td>Ukuran Sheet</td>
							<td><input type="text" class="form-control" id="ukuran_sheet" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Sambungan</td>
							<td><input type="text" class="form-control" id="sambungan" placeholder="Masukan.."></td>
							<td></td>
							<td>Type</td>
							<td><input type="text" class="form-control" id="tipe" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Material</td>
							<td><input type="text" class="form-control" id="material" placeholder="Masukan.."></td>
							<td></td>
							<td>Wall</td>
							<td><input type="text" class="form-control" id="wall" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Luas Panjang</td>
							<td><input type="text" class="form-control" id="l_panjang" placeholder="Masukan.."></td>
							<td></td>
							<td>Luas Lebar</td>
							<td><input type="text" class="form-control" id="l_lebar" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Creasing</td>
							<td><input type="text" class="form-control" id="creasing" placeholder="Masukan.."></td>
							<td></td>
							<td>Flute</td>
							<td><input type="text" class="form-control" id="flute" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Berat Bersih</td>
							<td><input type="text" class="form-control" id="berat_bersih" placeholder="Masukan.."></td>
							<td></td>
							<td>Luas Bersih</td>
							<td><input type="text" class="form-control" id="luas_bersih" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Kualitas</td>
							<td><input type="text" class="form-control" id="kualitas" placeholder="Masukan.."></td>
							<td></td>
							<td>Warna</td>
							<td><input type="text" class="form-control" id="warna" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>No Design</td>
							<td><input type="text" class="form-control" id="no_design" placeholder="Masukan.."></td>
							<td></td>
							<td>Design</td>
							<td><input type="text" class="form-control" id="design" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Tipe BOX</td>
							<td><input type="text" class="form-control" id="tipe_box" placeholder="Masukan.."></td>
							<td></td>
							<td>Jenis Produk</td>
							<td><input type="text" class="form-control" id="jenis_produk" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Kategori</td>
							<td>
								<select class="form-control" id="kategori">
									<option value="">Pilih</option>
									<option value="Produk sheet">Produk sheet</option>
									<option value="Produk box">Produk box</option>
								</select>
							</td>
							<td></td>
							<td>COA</td>
							<td><input type="text" class="form-control" id="COA" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Jml Ikat</td>
							<td><input type="text" class="form-control" id="jml_ikat" placeholder="Masukan.."></td>
							<td></td>
							<td>Jml Palet</td>
							<td><input type="text" class="form-control" id="jml_palet" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Jml Paku</td>
							<td><input type="text" class="form-control" id="jml_paku" placeholder="Masukan.."></td>
							<td></td>
							<td>No Pisau</td>
							<td><input type="text" class="form-control" id="no_pisau" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>No Karet</td>
							<td><input type="text" class="form-control" id="no_karet" placeholder="Masukan.."></td>
							<td></td>
							<td>Toleransi Kirim</td>
							<td><input type="text" class="form-control" id="toleransi_kirim" placeholder="Masukan.."></td>
						</tr>
						<tr>
							<td>Spesial Request</td>
							<td><input type="text" class="form-control" id="spesial_req" placeholder="Masukan.."></td>
							<td></td>
							<td></td>
							<td></td>
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
	});

	status = "insert";
	$(".tambah_data").click(function(event) {
		kosong();
		$("#modalForm").modal("show");
		$("#judul").html('<h3> Form Tambah Data</h3>');
		status = "insert";
	});


	/* $('.tambah_data').click(function() {
	      toastr.success('Berhasil');
	    });*/

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
				// data  : ({tanggal:tanggal,tanggal_akhir:tanggal_akhir,id_kategori:id_kategori1,id_sub_kategori:id_sub_kategori1}),
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

	function simpan() {
		id = $("#id").val();
		kode_mc = $("#kode_mc").val();
		kode_mc_lama = $("#kode_mc_lama").val();
		nm_produk = $("#nm_produk").val();
		no_customer = $("#no_customer").val();
		customer = $("#customer").val();
		ukuran = $("#ukuran").val();
		ukuran_sheet = $("#ukuran_sheet").val();
		sambungan = $("#sambungan").val();
		tipe = $("#tipe").val();
		material = $("#material").val();
		wall = $("#wall").val();
		l_panjang = $("#l_panjang").val();
		l_lebar = $("#l_lebar").val();
		creasing = $("#creasing").val();
		flute = $("#flute").val();
		berat_bersih = $("#berat_bersih").val();
		luas_bersih = $("#luas_bersih").val();
		kualitas = $("#kualitas").val();
		warna = $("#warna").val();
		no_design = $("#no_design").val();
		design = $("#design").val();
		tipe_box = $("#tipe_box").val();
		jenis_produk = $("#jenis_produk").val();
		kategori = $("#kategori").val();
		COA = $("#COA").val();
		jml_ikat = $("#jml_ikat").val();
		jml_palet = $("#jml_palet").val();
		jml_paku = $("#jml_paku").val();
		no_pisau = $("#no_pisau").val();
		no_karet = $("#no_karet").val();
		toleransi_kirim = $("#toleransi_kirim").val();
		spesial_req = $("#spesial_req").val();

		if (kode_mc == '' || nm_produk == '' || no_customer == '' || customer == '' || ukuran == '' || ukuran_sheet == '' || sambungan == '' || tipe == '' || material == '' || wall == '' || l_panjang == '' || l_lebar == '' || creasing == '' || flute == '' || berat_bersih == '' || luas_bersih == '' || kualitas == '' || warna == '' || no_design == '' || design == '' || tipe_box == '' || jenis_produk == '' || kategori == '' || COA == '' || jml_ikat == '' || jml_palet == '' || jml_paku == '' || no_pisau == '' || no_karet == '' || toleransi_kirim == '' || spesial_req == '') {
			toastr.info('Harap Lengkapi Form');
			return;
		}

		$.ajax({
			url: '<?php echo base_url(); ?>/master/insert/' + status,
			type: "POST",
			data: ({
				id,
				kode_mc,
				kode_mc_lama,
				nm_produk,
				no_customer,
				customer,
				ukuran,
				ukuran_sheet,
				sambungan,
				tipe,
				material,
				wall,
				l_panjang,
				l_lebar,
				creasing,
				flute,
				berat_bersih,
				luas_bersih,
				kualitas,
				warna,
				no_design,
				design,
				tipe_box,
				jenis_produk,
				kategori,
				COA,
				jml_ikat,
				jml_palet,
				jml_paku,
				no_pisau,
				no_karet,
				toleransi_kirim,
				spesial_req,
				jenis: 'm_produk',
				status: status
			}),

			dataType: "JSON",
			success: function(data) {
				if (data) {
					toastr.success('Berhasil Disimpan');
					kosong();
					$("#modalForm").modal("hide");
				} else {
					toastr.error('Gagal Simpan / Kode MC sudah tersedia');
				}
				reloadTable();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan');
			}
		});

	}

	function kosong() {
		$("#id").val("");
		$("#kode_mc").val("");
		$("#nm_produk").val("");
		$("#no_customer").val("");
		$("#customer").val("");
		$("#ukuran").val("");
		$("#ukuran_sheet").val("");
		$("#sambungan").val("");
		$("#tipe").val("");
		$("#material").val("");
		$("#wall").val("");
		$("#l_panjang").val("");
		$("#l_lebar").val("");
		$("#creasing").val("");
		$("#flute").val("");
		$("#berat_bersih").val("");
		$("#luas_bersih").val("");
		$("#kualitas").val("");
		$("#warna").val("");
		$("#no_design").val("");
		$("#design").val("");
		$("#tipe_box").val("");
		$("#jenis_produk").val("");
		$("#kategori").val("");
		$("#COA").val("");
		$("#jml_ikat").val("");
		$("#jml_palet").val("");
		$("#jml_paku").val("");
		$("#no_pisau").val("");
		$("#no_karet").val("");
		$("#toleransi_kirim").val("");
		$("#spesial_req").val("");
		status = 'insert';
		$("#btn-simpan").show();
	}


	function tampil_edit(id, act) {
		kosong();
		status = 'update';
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
				data: {
					id,
					jenis: "m_produk",
					field: 'id'
				},
				dataType: "JSON",
			})
			.done(function(data) {
				$("#id").val(data.id);
				$("#kode_mc").val(data.kode_mc);
				$("#kode_mc_lama").val(data.kode_mc);
				$("#nm_produk").val(data.nm_produk);
				$("#no_customer").val(data.no_customer);
				$("#customer").val(data.customer);
				$("#ukuran").val(data.ukuran);
				$("#ukuran_sheet").val(data.ukuran_sheet);
				$("#sambungan").val(data.sambungan);
				$("#tipe").val(data.tipe);
				$("#material").val(data.material);
				$("#wall").val(data.wall);
				$("#l_panjang").val(data.l_panjang);
				$("#l_lebar").val(data.l_lebar);
				$("#creasing").val(data.creasing);
				$("#flute").val(data.flute);
				$("#berat_bersih").val(data.berat_bersih);
				$("#luas_bersih").val(data.luas_bersih);
				$("#kualitas").val(data.kualitas);
				$("#warna").val(data.warna);
				$("#no_design").val(data.no_design);
				$("#design").val(data.design);
				$("#tipe_box").val(data.tipe_box);
				$("#jenis_produk").val(data.jenis_produk);
				$("#kategori").val(data.kategori);
				$("#COA").val(data.COA);
				$("#jml_ikat").val(data.jml_ikat);
				$("#jml_palet").val(data.jml_palet);
				$("#jml_paku").val(data.jml_paku);
				$("#no_pisau").val(data.no_pisau);
				$("#no_karet").val(data.no_karet);
				$("#toleransi_kirim").val(data.toleransi_kirim);
				$("#spesial_req").val(data.spesial_req);
			})
	}

	function deleteData(id) {
		let cek = confirm("Apakah Anda Yakin?");

		if (cek) {
			$.ajax({
				url: '<?php echo base_url(); ?>Master/hapus',
				data: ({
					id: id,
					jenis: 'm_produk',
					field: 'kode_mc'
				}),
				type: "POST",
				success: function(data) {
					toastr.success('Data Berhasil Di Hapus');
					reloadTable();
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
</script>
