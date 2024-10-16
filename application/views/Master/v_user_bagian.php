<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
			</div>
		</div>
	</section>

	<section class="content">
		<div class="card">
			<div class="card-header" style="font-family:Cambria;">
				<h3 class="card-title" style="color:#4e73df;"><b><?= $judul ?> - <span style='color:red'>[ <?= $judul2 ?> ]</span></b></h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button>
				</div>
			</div>
			<form role="form" method="post" id="myForm">
				<div class="card-body">		
					<table id="datatable" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th style="text-align: center;width:10%">No</th>
								<th style="text-align: center;width:50%">Nama</th>
								<th style="text-align: center;width:40%" colspan="3">Aksi</th>
							</tr>
						</thead>
						<input type="hidden" name="id_group" id="id_group" value="<?= $id ?>">
						<tbody>
							<?php
								$no=1; 
								foreach($query as $row) { ?>
								<tr class="show1" id="row">
									<td style ="text-align: center;" ><?= $no;?></td>
									<td>
										<?php if($row->lev=='0'){ ?>
											<i class="fa <?=  $row->icon ?>"></i><b>&nbsp;&nbsp;<?= $row->nama;?></b>
										<?php }else{ ?>
											&nbsp; &nbsp;&nbsp; 
											<i class="fa <?=  $row->icon ?>"></i>&nbsp; &nbsp;<?= $row->nama;?>
										<?php } ?>
									</td>
									<td align="center" >
										<?php if($row->lev!='0'){
											$query_cek = $this->db->query("SELECT*FROM m_departemen_bagian a left join m_modul_group b ON a.id_group=b.id_group where a.id_group='$id' and kode_departemen='$row->kode' ")->num_rows();
										?>
											<input id="<?= "status".$row->kode;?>" name="<?= "status".$row->kode;?>" type="checkbox" class="form-control" onchange="cek(<?= $row->kode ?>,this.value)"
										<?php if($query_cek > 0) {  ?>
											checked value="1">
										<?php }else{ ?> 
											value="0">
										<?php }} ?>
									</td>
								</tr>
							<?php $no++;} ?>
						</tbody>
					</table>
					<br>
					<a href="<?= base_url('Master/User_level')?>" class="btn btn-sm btn-danger"><i class="fa fa-undo"></i> <b>Kembali</b></a>
					&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-sm btn-primary " id="btn-simpan" onclick="simpan()"><i class="fas fa-save"></i><b> Simpan </b></button>
				</div>
			</form>
		</div>
	</section>
</div>

<script type="text/javascript">
	rowNum = 0;
	$(document).ready(function() {
		$('.select2').select2({
			dropdownAutoWidth: true
		})
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function simpan() {
		$.ajax({
			url : '<?php echo base_url('Master/simpanBagian')?>',
			type : "POST",
			data : $('#myForm').serialize(),
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
			success: function(res) {
				data = JSON.parse(res)
				console.log(data)
				swal.close()
				swal({
					title : "Data",
					html : "Berhasil Disimpan",
					type : "success",
					confirmButtonText : "OK"
				});
				window.location.href = '<?php echo base_url('Master/User_level')?>'
			}
		});
	}

	function cek(id,vall)
	{
		if(vall == 0) {
			$('#status'+id).val(1);
			$('#isi'+id).val(1);
		}else{
			$('#status'+id).val(0);
			$('#isi'+id).val(0);
		}
	}

</script>
