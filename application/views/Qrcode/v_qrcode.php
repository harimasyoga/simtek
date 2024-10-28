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
		<div class="card card-barang">
			<div class="card-header" style="font-family:Cambria">
				<h3 class="card-title" style="color:#4e73df;"><b>DATA QR CODE</b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
						<i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body" style="padding:12px 6px">
				<?= $code ?>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	status = 'insert';
	const code = '<?= $code ?>';
	
	$(document).ready(function() {
		$(".select2").select2()
		console.log(code)
	});
</script>
