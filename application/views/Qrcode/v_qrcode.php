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
						<h3 class="card-title" style="font-weight:bold;font-size:18px">DATA QR CODE</h3>
					</div>
					<div class="card-body" style="padding:12px 6px">
						<div class="list-barang"></div>
					</div>
				</div>
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
