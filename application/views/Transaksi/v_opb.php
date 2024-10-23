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
						<h3 class="card-title" style="font-weight:bold;font-size:18px">LIST OPB</h3>
					</div>
					<div class="card-body" style="padding:0">
						<?php if(in_array($this->session->userdata('approve'), ['ALL','ADMIN', 'OFFICE', 'GUDANG'])) { ?>
							<div style="padding:5px">
								<button type="button" class="btn btn-primary btn-sm" onclick="tambah('tambah')">Tambah Data</button>
							</div>
						<?php } ?>
						<div class="list-header" style="padding:0"></div>
						<div class="card-body row" style="padding:0">
							<div class="col-md-3">
								<div class="list-opb" style="padding:0"></div>
							</div>
							<div class="col-md-9" style="padding:0 14px">
								<div style="position:sticky;top:12px">
									<div class="list-opb-detail"></div>
									<div class="list-opb-verif" style="display:none;">
										<div class="row">
											<div class="col-md-7"></div>
											<div class="col-md-5">
												<div class="card card-success card-outline">
													<div class="card-header" style="padding:12px">
														<h3 class="card-title" style="font-weight:bold;font-size:18px">VERIFIKASI</h3>
													</div>
													<div class="card-body row" style="padding:12px 6px 0;font-weight:bold">
														<div class="col-md-3">KEPALA</div>
														<div class="col-md-9">
															<div class="vvff verif-acc"></div>
															<div class="vvff input-acc"></div>
														</div>
													</div>
													<div class="card-body row" style="padding:6px 6px 0;font-weight:bold">
														<div class="col-md-3">FINANCE</div>
														<div class="col-md-9">
															<div class="vvff verif-finance"></div>
															<div class="vvff input-finance"></div>
														</div>
													</div>
													<div class="card-body row" style="padding:6px 6px 12px;font-weight:bold">
														<div class="col-md-3">OWNER</div>
														<div class="col-md-9">
															<div class="vvff verif-owner"></div>
															<div class="vvff input-owner"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_opbh" value="">
			<input type="hidden" id="h_kode_dpt" value="">
			<input type="hidden" id="h_ii" value="">
		</div>

		<div class="row row-input" style="display:none">
			<div class="col-md-12">
				<div class="card card-primary card-outline">
					<div class="card-header" style="padding:12px">
						<h3 class="card-title" style="font-weight:bold;font-size:18px">INPUT OPB</h3>
					</div>
					<div class="card-body" style="padding:6px">
						<div style="padding:0 0 20px">
							<div class="btn-kembali"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">TANGGAL <span style="color:#f00">*</span></div>
							<div class="col-md-5">
								<input type="date" id="tgl_opb" class="form-control" value="<?php echo date('Y-m-d')?>">
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">NO. OPB <span style="color:#f00">*</span></div>
							<div class="col-md-5">
								<input type="number" id="no_opb" class="form-control" placeholder="NO. OPB">
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 20px">
							<div class="col-md-2">DEPARTEMEN <span style="color:#f00">*</span></div>
							<div class="col-md-5">
								<select id="plh_departemen" class="form-control select2" onchange="pilihBarang()">
									<?php
										$level = $this->session->userdata('level');
										$bagian = $this->db->query("SELECT b.id_group,b.kode_departemen,d.main_menu,d.nama FROM m_modul_group m 
										INNER JOIN m_departemen_bagian b ON m.id_group=b.id_group
										INNER JOIN m_departemen d ON b.kode_departemen=d.kode
										WHERE m.val_group='$level' AND d.main_menu='0'
										GROUP BY b.id_group,b.kode_departemen");
										$html1 ='';
										$html1 .='<option value="">PILIH</option>';
										foreach($bagian->result() as $r){
											$html1 .='<option value="'.$r->kode_departemen.'">'.$r->nama.'</option>';
										}
										echo $html1
									?>
								</select>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">PILIH BARANG <span style="color:#f00">*</span></div>
							<div class="col-md-5">
								<select id="plh_barang" class="form-control select2" onchange="pilihBarang()">
									<option value="">PILIH</option>
								</select>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">JENIS / TIPE</div>
							<div class="col-md-5">
								<div class="jenistipe">
									<select id="jenistipe" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MATERIAL</div>
							<div class="col-md-5">
								<div class="material">
									<select id="material" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">SIZE</div>
							<div class="col-md-5">
								<div class="ukuran">
									<select id="ukuran" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="card-body row" style="font-weight:bold;padding:0 0 4px">
							<div class="col-md-2">MERK</div>
							<div class="col-md-5">
								<div class="merk">
									<select id="merk" class="form-control select2" onchange="pilihBarang()">
										<option value="">PILIH</option>
									</select>
								</div>
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="lil list-detail"></div>
						<div class="lil list-cart"></div>
						<div class="lil list-edit-cart"></div>
					</div>
				</div>
			</div>
			<input type="hidden" id="id_mbh" value="">
			<input type="hidden" id="id_cart" value="0">
			<input type="hidden" id="destroy">
		</div>
	</section>
</div>

<script type="text/javascript">
	const urlAuth = '<?= $this->session->userdata('level')?>';
	const urlUser = '<?= $this->session->userdata('username')?>';
	const urlAppv = '<?= $this->session->userdata('approve')?>';
	status = 'insert';
	$(document).ready(function() {
		$(".select2").select2()
		loadHeader()
	});

	function reloadTable() {
		table = $('#datatable').DataTable();
		tabel.ajax.reload(null, false);
	}

	function tambah(opsi)
	{
		$(".row-list").show()
		$(".row-input").hide()
		$("#id_cart").val(0)
		$("#tgl_opb").val('<?php echo date('Y-m-d')?>');
		$("#no_opb").val('').prop('disabled', false)
		$("#id_opbh").val('')
		$("#id_mbh").val('')
		$("#h_ii").val('')
		$("#plh_departemen").val('').trigger('change').prop('disabled', false)
		$(".lil").html('')
		if(opsi == 'tambah'){
			$(".btn-kembali").html(`<button type="button" class="btn btn-primary btn-sm" onclick="tambah('kembali')">Kembali</button>`)
			$(".row-list").hide()
			$(".row-input").show()
			$("#destroy").load("<?php echo base_url('Transaksi/destroy') ?>")
			loadBarang()
		}
		if(opsi == 'kembali'){
			loadHeader()
		}
		status = 'insert'
	}

	$("#no_opb").on({
		keydown: function(e) {
			if (e.which === 32)
				return false;
		},
		keyup: function(){
			this.value = this.value.toUpperCase();
		},
		change: function() {
			this.value = this.value.replace(/\s/g, "");
		}
	});

	function loadHeader()
	{
		$(".list-opb-detail").html('')
		$(".vvff").html('')
		$(".list-opb-verif").hide()
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadHeader')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				$(".list-header").html(data.html)
			}
		})
		btnHeader(0)
	}

	function btnHeader(kode_dpt)
	{
		$("#h_kode_dpt").val(kode_dpt)
		$(".ff").removeClass('ff-klik').addClass('ff-all')
		$(".boh").removeClass('btn-opbh-klik').addClass('btn-opbh-all')
		$("#h_"+kode_dpt).removeClass('btn-opb-header').addClass('btn-opbh-klik')
		$("#ff_"+kode_dpt).removeClass('ff-all').addClass('ff-klik')
		$(".list-opb-detail").html('')
		$(".vvff").html('')
		$(".list-opb-verif").hide()
		loadList(kode_dpt)
	}

	function loadList(kode_dpt)
	{
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadList')?>',
			type: "POST",
			data: ({ kode_dpt }),
			success: function(res){
				data = JSON.parse(res)
				$(".list-opb").html(data.html)
			}
		})
	}

	function btnDetail(id_opbh, i, opsi)
	{
		let plh_departemen = $("#plh_departemen").val()
		$(".btn-opb-header").prop('disabled', false)
		$(".toh").removeClass('tr-opbh-klik').addClass('tr-opbh-all')
		$("#bth_"+i).prop('disabled', true)
		$(".list-opb-detail").html('')
		$(".lil").html('')
		$(".vvff").html('')
		$(".list-opb-verif").hide()
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadDetail')?>',
			type: "POST",
			data: ({ id_opbh, plh_departemen, opsi }),
			success: function(res){
				data = JSON.parse(res)
				if(opsi == 'view'){
					$("#toh_"+i).removeClass('tr-opbh-all').addClass('tr-opbh-klik')
					$("#id_opbh").val(data.opbh.id_opbh)
					$("#h_ii").val(i)
					$(".list-opb-detail").html(data.htmlDetail)
					$(".list-opb-verif").show()
					// VERIFIKASI DATA
					// VERIF ACC
						if((urlAppv == 'ACC' || urlAppv == 'ALL') && data.opbh.acc2 == 'N' && (data.opbh.acc1 == 'N' || data.opbh.acc1 == 'H' || data.opbh.acc1 == 'R')){
							// BUTTON ACC
							$(".verif-acc").html(`<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-success" onclick="verifOpb('verifikasi','acc')"><i class="fas fa-check"></i> Verifikasi</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-warning" onclick="verifOpb('hold','acc')"><i class="far fa-hand-paper"></i> Hold</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-danger" onclick="verifOpb('reject','acc')"><i class="fas fa-times"></i> Reject</button>`)
							// KETERANGAN ACC
							if(data.opbh.acc1 != 'N'){
								if(data.opbh.acc1 == 'H'){
									callout = 'callout-warning'
									colorbtn = 'btn-warning'
									txtsave = 'HOLD!'
								}else{
									callout = 'callout-danger'
									colorbtn = 'btn-danger'
									txtsave = 'REJECT!'
								}
								$(".input-acc").html(`<div class="callout ${callout}" style="padding:0;margin:2px 0 5px">
									<textarea class="form-control" id="ket_laminasi" style="padding:6px;border:0;resize:none" placeholder="ALASAN" oninput="this.value=this.value.toUpperCase()">${data.opbh.ket1}</textarea>
								</div>
								<div>
									<button type="button" style="text-align:center;font-weight:bold" class="btn btn-xs ${colorbtn}" onclick="btnVerifOpb('${data.opbh.acc1}', 'marketing')"><i class="fas fa-save" style="color:#000">
										</i> <span style="color:#000">${txtsave}</span>
									</button>
								</div>`)
							}
						}else{
							// BUTTON ACC
							if(data.opbh.acc1 == 'N'){
								$(".verif-acc").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-lock"></i></button>`)
							}else if(data.opbh.acc1 == 'H'){
								$(".verif-acc").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-hand-paper"></i></button> ${data.opbh.acc1}`)
							}else if(data.opbh.acc1 == 'R'){
								$(".verif-acc").html(`<button style="text-align:center;font-weight:bold;padding:4px 10px;cursor:default" class="btn btn-sm btn-danger"><i class="fas fa-times" style="color:#000"></i></button> ${data.opbh.acc1}`)
							}else{
								$(".verif-acc").html(`<button title="OKE" style="text-align:center;cursor:default" class="btn btn-sm btn-success "><i class="fas fa-check-circle"></i></button> ${data.time1}`)
							}
							// KETERANGAN ACC
							if(data.opbh.acc1 != 'N'){
								if(data.opbh.acc1 == 'H'){
									callout = 'callout-warning'
								}else if(data.opbh.acc1 == 'R'){
									callout = 'callout-danger'
								}else{
									callout = 'callout-success'
								}
								$(".input-acc").html(`<div>
									<div class="callout ${callout}" style="padding:6px;margin:5px 0">${data.opbh.ket1}</div>
								</div>`)
							}
						}
					// END VERIF ACC
					// VERIF FINANCE
						if((urlAppv == 'FINANCE' || urlAppv == 'ALL') && data.opbds == 0 && data.opbh.acc3 == 'N' && (data.opbh.acc2 == 'N' || data.opbh.acc2 == 'H' || data.opbh.acc2 == 'R')){
							// BUTTON FINANCE
							$(".verif-finance").html(`<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-success" onclick="verifOpb('verifikasi','finance')"><i class="fas fa-check"></i> Verifikasi</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-warning" onclick="verifOpb('hold','finance')"><i class="far fa-hand-paper"></i> Hold</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-danger" onclick="verifOpb('reject','finance')"><i class="fas fa-times"></i> Reject</button>`)
							// KETERANGAN FINANCE
							if(data.opbh.acc2 != 'N'){
								if(data.opbh.acc2 == 'H'){
									callout = 'callout-warning'
									colorbtn = 'btn-warning'
									txtsave = 'HOLD!'
								}else{
									callout = 'callout-danger'
									colorbtn = 'btn-danger'
									txtsave = 'REJECT!'
								}
								$(".input-finance").html(`<div class="callout ${callout}" style="padding:0;margin:2px 0 5px">
									<textarea class="form-control" id="ket_laminasi" style="padding:6px;border:0;resize:none" placeholder="ALASAN" oninput="this.value=this.value.toUpperCase()">${data.opbh.ket2}</textarea>
								</div>
								<div>
									<button type="button" style="text-align:center;font-weight:bold" class="btn btn-xs ${colorbtn}" onclick="btnVerifOpb('${data.opbh.acc2}', 'marketing')"><i class="fas fa-save" style="color:#000">
										</i> <span style="color:#000">${txtsave}</span>
									</button>
								</div>`)
							}
						}else{
							// BUTTON FINANCE
							if(data.opbh.acc2 == 'N'){
								$(".verif-finance").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-lock"></i></button>`)
							}else if(data.opbh.acc2 == 'H'){
								$(".verif-finance").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-hand-paper"></i></button> ${data.opbh.acc2}`)
							}else if(data.opbh.acc2 == 'R'){
								$(".verif-finance").html(`<button style="text-align:center;font-weight:bold;padding:4px 10px;cursor:default" class="btn btn-sm btn-danger"><i class="fas fa-times" style="color:#000"></i></button> ${data.opbh.acc2}`)
							}else{
								$(".verif-finance").html(`<button title="OKE" style="text-align:center;cursor:default" class="btn btn-sm btn-success "><i class="fas fa-check-circle"></i></button> ${data.time2}`)
							}
							// KETERANGAN ACC
							if(data.opbh.acc2 != 'N'){
								if(data.opbh.acc2 == 'H'){
									callout = 'callout-warning'
								}else if(data.opbh.acc2 == 'R'){
									callout = 'callout-danger'
								}else{
									callout = 'callout-success'
								}
								$(".input-finance").html(`<div>
									<div class="callout ${callout}" style="padding:6px;margin:5px 0">${data.opbh.ket2}</div>
								</div>`)
							}
						}
					// END VERIF FINANCE
					// VERIF OWNER
						if((urlAppv == 'OWNER' || urlAppv == 'ALL') && data.opbh.acc2 == 'Y' && (data.opbh.acc3 == 'N' || data.opbh.acc3 == 'H' || data.opbh.acc3 == 'R')){
							// BUTTON OWNER
							$(".verif-owner").html(`<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-success" onclick="verifOpb('verifikasi','owner')"><i class="fas fa-check"></i> Verifikasi</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-warning" onclick="verifOpb('hold','owner')"><i class="far fa-hand-paper"></i> Hold</button>
							<button type="button" style="margin-bottom:3px;text-align:center;font-weight:bold" class="btn btn-sm btn-danger" onclick="verifOpb('reject','owner')"><i class="fas fa-times"></i> Reject</button>`)
							// KETERANGAN OWNER
							if(data.opbh.acc3 != 'N'){
								if(data.opbh.acc3 == 'H'){
									callout = 'callout-warning'
									colorbtn = 'btn-warning'
									txtsave = 'HOLD!'
								}else{
									callout = 'callout-danger'
									colorbtn = 'btn-danger'
									txtsave = 'REJECT!'
								}
								$(".input-owner").html(`<div class="callout ${callout}" style="padding:0;margin:2px 0 5px">
									<textarea class="form-control" id="ket_laminasi" style="padding:6px;border:0;resize:none" placeholder="ALASAN" oninput="this.value=this.value.toUpperCase()">${data.opbh.ket3}</textarea>
								</div>
								<div>
									<button type="button" style="text-align:center;font-weight:bold" class="btn btn-xs ${colorbtn}" onclick="btnVerifOpb('${data.opbh.acc3}', 'marketing')"><i class="fas fa-save" style="color:#000">
										</i> <span style="color:#000">${txtsave}</span>
									</button>
								</div>`)
							}
						}else{
							// BUTTON OWNER
							if(data.opbh.acc3 == 'N'){
								$(".verif-owner").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-lock"></i></button>`)
							}else if(data.opbh.acc3 == 'H'){
								$(".verif-owner").html(`<button style="text-align:center;font-weight:bold;cursor:default" class="btn btn-sm btn-warning"><i class="fas fa-hand-paper"></i></button> ${data.opbh.acc3}`)
							}else if(data.opbh.acc3 == 'R'){
								$(".verif-owner").html(`<button style="text-align:center;font-weight:bold;padding:4px 10px;cursor:default" class="btn btn-sm btn-danger"><i class="fas fa-times" style="color:#000"></i></button> ${data.opbh.acc3}`)
							}else{
								$(".verif-owner").html(`<button title="OKE" style="text-align:center;cursor:default" class="btn btn-sm btn-success "><i class="fas fa-check-circle"></i></button> ${data.time3}`)
							}
							// KETERANGAN ACC
							if(data.opbh.acc3 != 'N'){
								if(data.opbh.acc3 == 'H'){
									callout = 'callout-warning'
								}else if(data.opbh.acc3 == 'R'){
									callout = 'callout-danger'
								}else{
									callout = 'callout-success'
								}
								$(".input-owner").html(`<div>
									<div class="callout ${callout}" style="padding:6px;margin:5px 0">${data.opbh.ket3}</div>
								</div>`)
							}
						}
					// END VERIF OWNER
				}
				if(opsi == 'edit'){
					$(".list-detail").html('')
					$(".list-cart").html('')
					$(".list-edit-cart").html(data.htmlDetail)
				}
				$(".select2").select2()
			}
		})
	}

	function verifOpb(aksi, status_verif)
	{
		if(aksi == 'verifikasi'){
			vrf = 'Y'
			callout = 'callout-success'
			colorbtn = 'btn-success'
			txtsave = 'VERIFIKASI!'
		}else if(aksi == 'hold'){
			vrf = 'H'
			callout = 'callout-warning'
			colorbtn = 'btn-warning'
			txtsave = 'HOLD!'
		}else if(aksi == 'reject'){
			vrf = 'R'
			callout = 'callout-danger'
			colorbtn = 'btn-danger'
			txtsave = 'REJECT!'
		}
		if(status_verif == 'acc'){
			input_verif = 'input-acc';
		}else if(status_verif == 'finance'){
			input_verif = 'input-finance';
		}else if(status_verif == 'owner'){
			input_verif = 'input-owner';
		}
		$("."+input_verif).html(`<div class="callout ${callout}" style="padding:0;margin:2px 0 5px">
			<textarea class="form-control" id="ket_laminasi" style="padding:6px;border:0;resize:none" placeholder="ALASAN" oninput="this.value=this.value.toUpperCase()"></textarea>
		</div>
		<div>
			<button type="button" style="text-align:center;font-weight:bold" class="btn btn-xs ${colorbtn}" onclick="btnVerifOpb('${vrf}', '${status_verif}')"><i class="fas fa-save" style="color:#000">
				</i> <span style="color:#000">${txtsave}</span>
			</button>
		</div>`)
	}

	function btnVerifOpb(aksi, status_verif)
	{
		let id_opbh = $("#id_opbh").val()
		let ket_laminasi = $("#ket_laminasi").val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/btnVerifOpb')?>',
			type: "POST",
			data: ({
				id_opbh, ket_laminasi, aksi, status_verif
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.result){
					loadHeader()
				}else{
					toastr.error(`<b>KETERANGAN TIDAK BOLEH KOSONG!</b>`)
					swal.close()
				}
			}
		})
	}

	function loadBarang()
	{
		$.ajax({
			url: '<?php echo base_url('Transaksi/loadBarang')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				$("#id_mbh").val('')
				$("#id_cart").val(0)
				$("#plh_barang").html(data.html)
				$("#jenistipe").html('<option value="">PILIH</option>')
				$("#material").html('<option value="">PILIH</option>')
				$("#ukuran").html('<option value="">PILIH</option>')
				$("#merk").html('<option value="">PILIH</option>')
				$(".list-detail").html('')
				$(".list-cart").html('')
			}
		})
	}

	function pilihBarang()
	{
		let plh_departemen = $("#plh_departemen").val()
		let id_mbh = $("#plh_barang").val()
		let id_mbh_lama = $("#id_mbh").val()
		let jenistipe = $("#jenistipe").val()
		let material = $("#material").val()
		let ukuran = $("#ukuran").val()
		let merk = $("#merk").val()
		$("#jenistipe").html('<option value="">PILIH</option>')
		$("#material").html('<option value="">PILIH</option>')
		$("#ukuran").html('<option value="">PILIH</option>')
		$(".list-detail").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/detailBarang')?>',
			type: "POST",
			data: ({
				plh_departemen, id_mbh, id_mbh_lama, jenistipe, material, ukuran, merk
			}),
			success: function(res){
				data = JSON.parse(res);
				(plh_departemen != '') ? prop = true : prop = false;
				$("#plh_departemen").prop('disabled', prop)
				$("#id_mbh").val(id_mbh)
				$(".list-detail").html(data.html)
				$("#jenistipe").html(data.htmlJT).val((id_mbh == id_mbh_lama) ? jenistipe : '')
				$("#material").html(data.htmlM).val((id_mbh == id_mbh_lama) ? material : '')
				$("#ukuran").html(data.htmlS).val((id_mbh == id_mbh_lama) ? ukuran : '')
				$("#merk").html(data.htmlMr).val((id_mbh == id_mbh_lama) ? merk : '')
				$(".select2").select2()
			}
		})
	}

	function pilihSatuan(i)
	{
		$("#qty"+i).val('')
		$(".txtsatuan"+i).html('')
		$(".hitungqty"+i).html('')
		$(".ketsatuan"+i).html('')
		$("#i_qty1_"+i).val('')
		$("#i_qty2_"+i).val('')
		$("#i_qty3_"+i).val('')
		$("#plh_bagian"+i).val('')
		$("#ket_pengadaan"+i).val('')
	}

	function pengadaaan(i)
	{
		const rupiah = new Intl.NumberFormat('id-ID', {styles: 'currency', currency: 'IDR'})
		let h_satuan = parseInt($("#h_satuan"+i).val())
		let plh_satuan = $("#plh_satuan"+i).val()
		let i_qty = parseInt($("#qty"+i).val().split('.').join(''));
		(isNaN(i_qty) || i_qty == 0 || i_qty < 0 || i_qty.toString().length >= 7) ? i_qty = 0 : i_qty = i_qty;
		$("#qty"+i).val(rupiah.format(i_qty));
		let qty1 = parseInt($("#h_qty1_"+i).val())
		let qty2 = parseInt($("#h_qty2_"+i).val())
		let qty3 = parseInt($("#h_qty3_"+i).val())
		let satuan1 = $("#h_satuan1_"+i).val()
		let satuan2 = $("#h_satuan2_"+i).val()
		let satuan3 = $("#h_satuan3_"+i).val()
		// PERHITUNGAN
		let besar = 0; let tengah = 0; let kecil = 0; let x_besar = 0; let x_tengah = 0; let x_kecil = 0; let style1 = ''; let style2 = ''; let style3 = ''
		if(h_satuan == 1){
			x_kecil = i_qty
			$(".txtsatuan"+i).html(`<span style="color:#f00">TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span style="color:#f00">${rupiah.format(i_qty)}</span>`)
			$(".ketsatuan"+i).html(`<span style="color:#f00">${satuan3}</span>`)
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
			$(".txtsatuan"+i).html(`<span ${style1}>TERBESAR</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span ${style1}>${rupiah.format(x_besar)}</span><br><span ${style3}>${rupiah.format(x_kecil)}</span>`)
			$(".ketsatuan"+i).html(`<span ${style1}>${satuan1}</span><br><span ${style3}>${satuan3}</span>`)
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
			$(".txtsatuan"+i).html(`<span ${style1}>TERBESAR</span><br><span ${style2}>TENGAH</span><br><span ${style3}>TERKECIL</span>`)
			$(".hitungqty"+i).html(`<span ${style1}>${rupiah.format(x_besar)}</span><br><span ${style2}>${rupiah.format(x_tengah)}</span><br><span ${style3}>${rupiah.format(x_kecil)}</span>`)
			$(".ketsatuan"+i).html(`<span ${style1}>${satuan1}</span><br><span ${style2}>${satuan2}</span><br><span ${style3}>${satuan3}</span>`)
		}
		$("#i_qty1_"+i).val(x_besar)
		$("#i_qty2_"+i).val(x_tengah)
		$("#i_qty3_"+i).val(x_kecil)

		if(urlAppv == 'ALL' || urlAppv == 'OFFICE'){
			hargaOPB(i)
		}
	}

	function hargaOPB(i)
	{
		let h_qty = ($("#h_qty_"+i).val() == undefined) ? 0 : $("#h_qty_"+i).val().split('.').join('');
		let h_harga = ($("#h_harga_"+i).val() == undefined) ? 0 : $("#h_harga_"+i).val().split('.').join('');
		let h_total = ($("#h_total").val() == undefined) ? 0 : $("#h_total").val().split('.').join('');
		let qty = ($("#qty"+i).val() == undefined) ? 0 : $("#qty"+i).val().split('.').join('');
		let harga = ($("#harga_opb"+i).val() == undefined) ? 0 : $("#harga_opb"+i).val().split('.').join('');
		$("#harga_opb"+i).val(format_angka(harga))
		let jumlah = parseInt(qty) * parseInt(harga);
		(isNaN(jumlah)) ? jumlah = 0 : jumlah = jumlah;
		$("#jumlah_opb"+i).val(format_angka(jumlah))
		let hitung_total = (parseInt(h_total) - (parseInt(h_qty) * parseInt(h_harga))) + jumlah;
		(isNaN(hitung_total)) ? hitung_total = 0 : hitung_total = hitung_total;
		$("#total_opb").val(format_angka(hitung_total))
	}

	function addCartOPB(i)
	{
		let id_cart = parseInt($("#id_cart").val()) + 1;
		$("#id_cart").val(id_cart)
		let id_opbh = $("#id_opbh").val()
		let tgl_opb = $("#tgl_opb").val()
		let no_opb = $("#no_opb").val()
		let id_mbh = $("#h_id_mbh"+i).val()
		let id_mbd = $("#h_id_mbd"+i).val()
		let plh_departemen = $("#plh_departemen").val()
		let plh_bagian = $("#plh_bagian"+i).val()
		let plh_satuan = $("#plh_satuan"+i).val()
		let qty = $("#qty"+i).val()
		let i_qty1 = $("#i_qty1_"+i).val()
		let i_qty2 = $("#i_qty2_"+i).val()
		let i_qty3 = $("#i_qty3_"+i).val()
		let ket_pengadaan = $("#ket_pengadaan"+i).val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/addCartOPB')?>',
			type: "POST",
			data : ({
				id_cart, id_opbh, tgl_opb, no_opb, plh_departemen, id_mbh, id_mbd, plh_bagian, plh_satuan, qty, i_qty1, i_qty2, i_qty3, ket_pengadaan, status
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					cartOPB()
				}else{
					toastr.error(`<b>${data.msg}</b>`)
				}
			}
		})
	}

	function cartOPB()
	{
		$(".list-cart").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/cartOPB')?>',
			type: "POST",
			success: function(res){
				data = JSON.parse(res)
				$(".list-cart").html(data.html)
			}
		})
	}

	function hapusCart(rowid)
	{
		$.ajax({
			url: '<?php echo base_url('Transaksi/hapusCart')?>',
			type: "POST",
			data: ({ rowid }),
			success: function(res){
				cartOPB()
			}
		})
	}

	function simpanOPB()
	{
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		let tgl_opb = $("#tgl_opb").val()
		let no_opb = $("#no_opb").val()
		let plh_departemen = $("#plh_departemen").val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/simpanOPB')?>',
			type: "POST",
			data: ({
				id_opbh, tgl_opb, no_opb, plh_departemen, status
			}),
			success: function(res){
				data = JSON.parse(res)
				if(data.data && status == 'insert'){
					tambah('kembali')
				}else if(data.data && status == 'update'){
					kembali()
				}else{
					toastr.error(`<b>${data.msg}</b>`)
				}
			}
		})
	}

	function editOPB()
	{
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		$("#destroy").load("<?php echo base_url('Transaksi/destroy') ?>")
		$(".row-list").hide()
		$(".row-input").show()
		$("#id_mbh").val('')
		$("#id_cart").val(0)
		$(".list-detail").html('')
		$(".list-cart").html('')
		$(".list-edit-cart").html('')
		$.ajax({
			url: '<?php echo base_url('Transaksi/editOPB')?>',
			type: "POST",
			data: ({ id_opbh }),
			success: function(res){
				data = JSON.parse(res)
				$("#tgl_opb").val(data.opbh.tgl_opb)
				$("#no_opb").val(data.opbh.no_opb).prop('disabled', true)
				$("#plh_departemen").val(data.opbh.kode_dpt).trigger('change')
				$(".btn-kembali").html(`<button type="button" class="btn btn-primary btn-sm" onclick="kembali()">Kembali</button>`)
				loadBarang()
				btnDetail(id_opbh, h_ii, 'edit')
				status = 'update'
			}
		})
	}

	function editListOPB(i)
	{
		let kode_dpt = $("#h_kode_dpt").val()
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		let id_opbd = $("#h_id_opbd_"+i).val()
		let id_mbh = $("#h_id_mbh"+i).val()
		let id_mbd = $("#h_id_mbd"+i).val()
		let plh_satuan = $("#plh_satuan"+i).val()
		let qty = $("#qty"+i).val()
		let i_qty1 = $("#i_qty1_"+i).val()
		let i_qty2 = $("#i_qty2_"+i).val()
		let i_qty3 = $("#i_qty3_"+i).val()
		let harga = $("#harga_opb"+i).val().split('.').join('')
		let plh_supplier = $("#plh_supplier"+i).val()
		let ket_pengadaan = $("#ket_pengadaan"+i).val()
		let plh_bagian = $("#plh_bagian"+i).val()
		$.ajax({
			url: '<?php echo base_url('Transaksi/editListOPB')?>',
			type: "POST",
			async: false,
			data: ({ id_opbh, id_opbd, id_mbh, id_mbd, plh_satuan, qty, i_qty1, i_qty2, i_qty3, harga, plh_supplier, ket_pengadaan, plh_bagian }),
			success: function(res){
				data = JSON.parse(res)
				if(data.data){
					editOPB()
					loadList(kode_dpt)
				}else{
					toastr.error(`<b>${data.msg}</b>`)
				}
			}
		})
	}

	function kembali()
	{
		$(".row-list").show()
		$(".row-input").hide()
		$(".lil").html('')
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		btnDetail(id_opbh, h_ii, 'view')
	}

	function hapusOPB(opsi, i)
	{
		let id_opbh = $("#id_opbh").val()
		let h_ii = $("#h_ii").val()
		let id_opbd = $("#h_id_opbd_"+i).val()
		swal({
			title: "Apakah Kamu Yakin?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#C00",
			confirmButtonText: "Delete"
		}).then(function(result) {
			$.ajax({
				url: '<?php echo base_url('Transaksi/hapusOPB')?>',
				type: "POST",
				data : ({ id_opbh, id_opbd, opsi }),
				success: function(res){
					data = JSON.parse(res)
					if(opsi == 'header' && data.opbh && data.opbd){
						loadHeader()
					}
					if(opsi == 'detail' && data.opbd){
						btnDetail(id_opbh, h_ii, 'edit')
					}
				}
			})
		});
	}
</script>
