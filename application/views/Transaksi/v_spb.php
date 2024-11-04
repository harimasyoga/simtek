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
				<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST SPB</h3>
			</div>
			<div class="card-body row" style="padding:12px 6px;font-weight:bold">
				<div class="col-md-2" style="padding-bottom:3px">
					<select id="tahun" class="form-control select2" onchange="load_data()">
						<?php 
							$thang = date("Y");
							$thang_maks = $thang + 2;
							$thang_min = $thang - 2;
							for($th = $thang_min; $th <= $thang_maks; $th++){ ?>
								<?php if($th == $thang){ ?>
									<option selected value="<?= $th ?>"> <?= $thang ?> </option>
								<?php }else{ ?>
									<option value="<?= $th ?>"> <?= $th ?> </option>
								<?php }
							}
						?>
					</select>
				</div>
				<div class="col-md-2" style="padding-bottom:3px">
					<?php
						$qbulan = $this->db->query("SELECT*FROM m_bulan");
						$bln_now = date("m");
					?>
					<select id="bulan" class="form-control select2" onchange="load_data()">
						<option value="">SEMUA</option>
						<?php 									
							foreach ($qbulan->result() as $bln_row) {
								if($bln_row->id == $bln_now){
									echo '<option value="'.$bln_row->id_bln.'" selected>'.$bln_row->bulan.'</option>';
								}else{	
									echo '<option value="'.$bln_row->id_bln.'">'.$bln_row->bulan.'</option>';
								}
							}		
						?>  
					</select>
				</div>
				<div class="col-md-8" style="padding-bottom:3px"></div>
			</div>
			<div class="card-body" style="padding:12px 6px">
				<div class="ldopb" style="overflow:auto;white-space:nowrap">
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead class="color-tabel">
							<tr>
								<th style="text-align:center">#</th>
								<th style="text-align:left">HARI, TANGGAL</th>
								<th style="text-align:left">NO. SPB</th>
								<th style="text-align:left">PEMOHON</th>
								<th style="text-align:left">PEMBUAT</th>
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
		let tahun = $("#tahun").val()
		let bulan = $("#bulan").val()
		var table = $('#datatable').DataTable();
		table.destroy();
		tabel = $('#datatable').DataTable({
			"processing": true,
			"pageLength": true,
			"paging": true,
			"ajax": {
				"url": '<?php echo base_url(); ?>Transaksi/loadDataSPB',
				"type": "POST",
				"data": ({
					tahun, bulan
				}),
			},
			responsive: false,
			"pageLength": 10,
			"language": {
				"emptyTable": "Tidak ada data.."
			}
		});
	}
</script>
