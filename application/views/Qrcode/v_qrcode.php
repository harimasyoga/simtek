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
		<div class="row row-opb">
			<div class="col-md-12">
				<div class="card card-secondary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">RINCIAN DATA OPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div style="padding:0;overflow:auto;white-space:nowrap">
							<div class="list-opb"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row row-bapb">
			<div class="col-md-12">
				<div class="card card-secondary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">RINCIAN DATA BAPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div style="padding:0;overflow:auto;white-space:nowrap">
							<div class="list-bapb"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row row-input">
			<div class="col-md-12">
				<div class="card card-secondary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT SPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div style="padding:0;overflow:auto;white-space:nowrap">
							<div class="list-input-spb"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if(in_array($this->session->userdata('approve'), ['ALL', 'OFFICE', 'GUDANG'])) { ?>
			<div class="row row-spb">
				<div class="col-md-12">
					<div class="card card-info card-outline">
						<div class="card-header" style="padding:12px">
							<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST SPB</h3>
						</div>
						<div class="card-body" style="padding:6px">
							<div style="padding:0;overflow:auto;white-space:nowrap">
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
		$('.list-input-spb').html('')
		$.ajax({
			url: '<?php echo base_url('Qrcode/loadBarang')?>',
			type: "POST",
			data: ({ qrcode }),
			success: function(res){
				data = JSON.parse(res)
				console.log(data)
				$('.list-opb').html(data.htmlOpb)
				$('.list-bapb').html(data.htmlBapb)
				if(urlAppv == 'ALL' || urlAppv == 'OFFICE' || urlAppv == 'GUDANG'){
					$('.list-input-spb').html(data.htmlSpb)
				}
			}
		})
	}
</script>
