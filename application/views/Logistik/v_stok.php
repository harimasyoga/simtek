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
		<div class="card card-info card-outline">
			<div class="card-header" style="padding:12px">
				<h3 class="card-title" style="font-weight:bold;font-size:18px">STOK</h3>
			</div>
			<div class="card-body" style="padding:12px 6px">
				<div style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="text-align:center">#</th>
								<th style="text-align:left">KODE BARANG</th>
								<th style="text-align:left">NAMA BARANG</th>
								<th style="text-align:left">JENIS / TIPE</th>
								<th style="text-align:left">MATERIAL</th>
								<th style="text-align:left">SIZE</th>
								<th style="text-align:left">MERK</th>
								<th style="text-align:left">AKSI</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="card card-info card-outline">
			<div class="card-header" style="padding:12px">
				<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST STOK</h3>
			</div>
			<div class="card-body" style="padding:6px">
				<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
					<div class="list-stok"></div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	status = 'insert';
	
	$(document).ready(function() {
		$(".select2").select2()
		load_data()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
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
				"url": '<?php echo base_url(); ?>Logistik/loadDataStok',
				"type": "POST",
			},
			responsive: false,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}

	function cariStok(i)
	{
		$(".list-stok").html('')
		let id_mbh = $("#id_mbh_"+i).val()
		let id_mbd = $("#id_mbd_"+i).val()
		$.ajax({
			url: '<?php echo base_url('Logistik/cariStok')?>',
			type: "POST",
			data: ({ id_mbh, id_mbd }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				$(".list-stok").html(data.html)
			}
		})
	}

	function btnQRCode(i)
	{
		$(".qrqr").hide()
		$(".trqr2-"+i).show()
		let h_tr = $("#h_tr").val()
		if(parseInt(h_tr) == parseInt(i)){
			$("#h_tr").val("")
			$(".qrqr").hide()
		}else{
			$("#h_tr").val(i)
			$(".qrqr").hide()
			$(".trqr2-"+i).show()
		}
	}
</script>
