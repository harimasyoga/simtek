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
		<div class="card shadow mb-3">
			<div class="row-list">
				<div class="card-header" style="font-family:Cambria;">		
						<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?></b></h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
								<i class="fas fa-minus"></i></button>
						</div>
				</div>
				<div class="card-body" >
					<div class="row">
					<?php if(in_array($this->session->userdata('level'), ['Admin','konsul_keu','Laminasi','User'])){ ?>
						<div style="margin-bottom:12px; position: absolute;left: 20px;">
							<button type="button" class="btn btn-sm btn-info" onclick="add_data()"><i class="fa fa-plus"></i> <b>TAMBAH DATA</b></button>
						</div>

						<?php } ?>
					</div>
					<br>
					<br>
					
					<!-- <div style="overflow:auto;white-space:nowrap"> -->
						<table id="datatable_list" class="table table-bordered table-striped table-scrollable" width="100%">
							<thead class="color-tabel">
								<tr>
									<th class="text-center title-white">NO </th>
									<th class="text-center title-white">NO PO</th>
									<th class="text-center title-white">TGL PO</th>
									<th class="text-center title-white">HUB</th>
									<th class="text-center title-white">TONASE PO</th>
									<th class="text-center title-white">TERKIRIM</th>
									<th class="text-center title-white">SISA</th>
									<th class="text-center title-white">HARGA</th>
									<th class="text-center title-white">TOTAL</th>
									<th class="text-center title-white">AKSI</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					<!-- </div> -->
				</div>
			</div>			
		</div>
	</section>

	<section class="content">

		<!-- Default box -->
		<div class="card shadow row-input" style="display: none;">
			<div class="card-header" style="font-family:Cambria;" >
				<h3 class="card-title" style="color:#4e73df;"><b>INPUT <?= $judul ?></b></h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<form role="form" method="post" id="myForm">
				<div class="col-md-12">
								
					<br>
						
					<div class="card-body row" style="padding-bottom:1px;font-weight:bold">			
						
						<div class="col-md-2">NO PO BAHAN</div>
						<div class="col-md-3">
							<input type="hidden" name="sts_input" id="sts_input">
							<input type="hidden" name="no_po_old" id="no_po_old">
							<input type="hidden" name="id_po_bhn" id="id_po_bhn">
							<input type="text" class="angka form-control" name="no_po" id="no_po" value="AUTO" readonly>

						</div>
						<div class="col-md-1"></div>
			
						<div class="col-md-2">TONASE</div>
						<div class="col-md-3">
							<div class="input-group">
								<input type="text" class="form-control" name="ton" id="ton" value ="0" onkeyup="ubah_angka(this.value,this.id),hitung_total()">
								<div class="input-group-append">
									<span class="input-group-text">Kg</span>
								</div>
							</div>
						</div>
					</div>
											
					<div class="card-body row" style="padding-bottom:1px;font-weight:bold">

						<div class="col-md-2">TANGGAL</div>
						<div class="col-md-3">
							<input type="date" class="form-control" name="tgl_po" id="tgl_po" value ="<?= date('Y-m-d') ?>" >
						</div>

						<div class="col-md-1"></div>

						<div class="col-md-2">HARGA / Kg</div>
						<div class="col-md-3">
							<div class="input-group">								
								<div class="input-group-append">
									<span class="input-group-text">Rp</span>
								</div>
								<input type="text" class="form-control" name="harga" id="harga" value ="0" onkeyup="ubah_angka(this.value,this.id),hitung_total()">
							</div>
						</div>

					</div>
					
					<div class="card-body row" style="padding-bottom:1px;font-weight:bold">
										
						<div class="col-md-2">HUB</div>
						<div class="col-md-3">
							<select class="form-control select2" onchange="load_aka()" name="hub" id="hub" style="width: 100%;">
							</select>
							<input type="hidden" name="aka" id="aka">
						</div>

						<div class="col-md-1"></div>
									
						<div class="col-md-2">TOTAL</div>
						<div class="col-md-3">
							<div class="input-group">								
								<div class="input-group-append">
									<span class="input-group-text">Rp</span>
								</div>
								<input type="text" class="form-control" name="total_po" id="total_po" value ="0" readonly>
							</div>
						</div>
						

					</div>
					
					<br>
				
					<div class="card-body row"style="font-weight:bold">
						<div class="col-md-4">
							<button type="button" onclick="kembaliList()" class="btn-tambah-produk btn  btn-danger"><b>
								<i class="fa fa-undo" ></i> Kembali</b>
							</button>

							<span id="btn-simpan"></span>

						</div>
						
						<div class="col-md-6"></div>
						
					</div>

					<br>
					
				</div>
			</form>	
		</div>
		<!-- /.card -->
	</section>
</div>

<script type="text/javascript">

	const urlAuth = '<?= $this->session->userdata('level')?>';

	$(document).ready(function ()
	{
		kosong()
		load_data()
		$('.select2').select2();
	});


	function hitung_total()
	{
		var ton   = $("#ton").val().split('.').join('')
		var harga = $("#harga").val().split('.').join('')

		var total = ton*harga		
		$("#total_po").val(format_angka(total))
		
	}

	function reloadTable() 
	{
		table = $('#datatable_list').DataTable();
		tabel.ajax.reload(null, false);
	}

	function load_data() 
	{
		var list_hub    = $("#list_hub").val()
		let table       = $('#datatable_list').DataTable();
		table.destroy();
		tabel = $('#datatable_list').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url('Transaksi/load_data/po_bahan')?>',
				"type": "POST", 
				"data"  : { id_hub:list_hub },
			},
			"aLengthMenu": [
				[5, 10, 50, 100, -1],
				[5, 10, 50, 100, "Semua"]
			],	
			"responsive": true,
			"pageLength": 10,
			"language": {
				"emptyTable": "TIDAK ADA DATA.."
			}
		})
	}
	
	function edit_data(id,kd_po)
	{
		$(".row-input").attr('style', '');
		$(".row-list").attr('style', 'display:none');
		$("#sts_input").val('edit');

		$("#btn-simpan").html(`<button type="button" onclick="simpan()" class="btn-tambah-produk btn  btn-primary"><b><i class="fa fa-save" ></i> Update</b> </button>`)

		$.ajax({
			url        : '<?= base_url(); ?>Transaksi/load_data_1',
			type       : "POST",
			data       : { id, tbl:'trs_po_bhnbk', jenis :'po_bahan_baku',field :'id_po_bhn' },
			dataType   : "JSON",
			beforeSend: function() {
				swal({
				title: 'loading data...',
				allowEscapeKey    : false,
				allowOutsideClick : false,
				onOpen: () => {
					swal.showLoading();
				}
				})
			},
			success: function(data) {
				if(data){
					// header
					$("#hub").val(data.header.hub).trigger('change');
					$("#id_po_bhn").val(data.header.id_po_bhn);
					$("#no_po_old").val(data.header.no_po_bhn);
					$("#no_po").val(data.header.no_po_bhn);
					$("#tgl_po").val(data.header.tgl_bhn);
					$("#ton").val(format_angka(data.header.ton_bhn));
					$("#harga").val(format_angka(data.header.hrg_bhn));
					$("#aka").val(data.header.aka);
					$("#total_po").val(format_angka(data.header.total));

					swal.close();

				} else {

					swal.close();
					swal({
						title               : "Cek Kembali",
						html                : "Gagal Simpan",
						type                : "error",
						confirmButtonText   : "OK"
					});
					return;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// toastr.error('Terjadi Kesalahan');
				
				swal.close();
				swal({
					title               : "Cek Kembali",
					html                : "Terjadi Kesalahan",
					type                : "error",
					confirmButtonText   : "OK"
				});
				
				return;
			}
		});
	}

	function kosong()
	{
		var tgl_now = '<?= date('Y-m-d') ?>'
		$("#no_po_old").val("")
		$("#id_po_bhn").val("")
		$("#no_po").val("AUTO")
		$("#ton").val("")
		$("#tgl_po").val(tgl_now)
		$("#harga").val("")
		$("#hub").val("")
		$("#total_po").val("")		
		swal.close()
	}

	function simpan() 
	{
		var no_po     = $("#no_po").val();
		var tgl_po    = $("#tgl_po").val();
		var ton       = $("#ton").val().split('.').join('');
		var harga     = $("#harga").val().split('.').join('');
		var total_po  = $("#total_po").val().split('.').join('');
		var hub       = $("#hub").val();
		
		
		if ( ton == '' || ton == 0 || no_po == '' || harga == '' || tgl_po == '' || total_po == '' || total_po == 0 || hub == '' ) 
		{			
			swal.close();
			swal({
				title               : "Cek Kembali",
				html                : "Harap Lengkapi Form Dahulu",
				type                : "info",
				confirmButtonText   : "OK"
			});
			return;
		}

		$.ajax({
			url        : '<?= base_url(); ?>Transaksi/insert_po_bb',
			type       : "POST",
			data       : $('#myForm').serialize(),
			dataType   : "JSON",
			beforeSend: function() {
				swal({
				title: 'loading ...',
				allowEscapeKey    : false,
				allowOutsideClick : false,
				onOpen: () => {
					swal.showLoading();
				}
				})
			},
			success: function(data) {
				if(data == true){
					// toastr.success('Berhasil Disimpan');
					// swal.close();								
					kosong();
					location.href = "<?= base_url()?>Transaksi/PO_bhn_bk";
					swal({
						title               : "Data",
						html                : "Berhasil Disimpan",
						type                : "success",
						confirmButtonText   : "OK"
					});
					
				} else {
					// toastr.error('Gagal Simpan');
					swal.close();
					swal({
						title               : "Cek Kembali",
						html                : "Gagal Simpan",
						type                : "error",
						confirmButtonText   : "OK"
					});
					return;
				}
				reloadTable();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// toastr.error('Terjadi Kesalahan');
				
				swal.close();
				swal({
					title               : "Cek Kembali",
					html                : "Terjadi Kesalahan",
					type                : "error",
					confirmButtonText   : "OK"
				});
				
				return;
			}
		});

	}

	function add_data()
	{
		kosong()
		$(".row-input").attr('style', '')
		$(".row-list").attr('style', 'display:none')
		$("#sts_input").val('add');
		
		$("#btn-simpan").html(`<button type="button" onclick="simpan()" class="btn-tambah-produk btn  btn-primary"><b><i class="fa fa-save" ></i> Simpan</b> </button>`)
	}

	function kembaliList()
	{
		kosong()
		reloadTable()
		$(".row-input").attr('style', 'display:none')
		$(".row-list").attr('style', '')
	}

	function deleteData(id,no_po) 
	{
		// let cek = confirm("Apakah Anda Yakin?");
		swal({
			title: "HAPUS PEMBAYARAN",
			html: "<p> Apakah Anda yakin ingin menghapus file ini ?</p><br>"
			+"<strong>" +no_po+ " </strong> ",
			type               : "question",
			showCancelButton   : true,
			confirmButtonText  : '<b>Hapus</b>',
			cancelButtonText   : '<b>Batal</b>',
			confirmButtonClass : 'btn btn-success',
			cancelButtonClass  : 'btn btn-danger',
			cancelButtonColor  : '#d33'
		}).then(() => {

		// if (cek) {
			$.ajax({
				url: '<?= base_url(); ?>Transaksi/hapus',
				data: ({
					id: id,
					jenis: 'trs_po_bhnbk',
					field: 'id_po_bhn'
				}),
				type: "POST",
				beforeSend: function() {
					swal({
					title: 'loading ...',
					allowEscapeKey    : false,
					allowOutsideClick : false,
					onOpen: () => {
						swal.showLoading();
					}
					})
				},
				success: function(data) {
					toastr.success('Data Berhasil Di Hapus');
					swal.close();

					// swal({
					// 	title               : "Data",
					// 	html                : "Data Berhasil Di Hapus",
					// 	type                : "success",
					// 	confirmButtonText   : "OK"
					// });
					reloadTable();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// toastr.error('Terjadi Kesalahan');
					swal({
						title               : "Cek Kembali",
						html                : "Terjadi Kesalahan",
						type                : "error",
						confirmButtonText   : "OK"
					});
					return;
				}
			});
		// }

		});


	}
</script>
