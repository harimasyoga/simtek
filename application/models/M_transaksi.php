<?php
class M_transaksi extends CI_Model
{

	function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
		$this->username = $this->session->userdata('username');
		$this->waktu    = date('Y-m-d H:i:s');
		$this->load->model('m_master');
	}

	function get_data_max($table, $kolom)
	{
		$query = "SELECT IFNULL(LPAD(MAX(RIGHT($kolom,4))+1,4,0),'0001')AS nomor FROM $table";
		return $this->db->query($query)->row("nomor");
	}

	function trs_po($table, $status)
	{
		$params       = (object)$this->input->post();

		/* LOGO */
		//$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
		$config['upload_path']   = './assets/gambar_po/'; //path folder
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
		$config['max_size']      = '1024'; //maksimum besar file 2M
		$config['max_width']     = 'none'; //lebar maksimum 1288 px
		$config['max_height']    = 'none'; //tinggi maksimu 768 px
		//$config['file_name'] = $nmfile; //nama yang terupload nantinya

		$this->load->library('upload',$config);
		$this->upload->initialize($config);

		if($_FILES['filefoto']['name'])
		{
			if ($this->upload->do_upload('filefoto'))
			{
				$gbrBukti = $this->upload->data();
				$filefoto = $gbrBukti['file_name'];
				// $filefoto    = $_FILES['filefoto']['name'];
				
			}else{
				$filefoto = 'foto.jpg';
			}
		} else {

			if($params->tgl_po<'2023-11-01')
			{
				$filefoto = 'foto.jpg';
			}else{
				$error = array('error' => $this->upload->display_errors());
				var_dump($error);
				exit;
			}
		}
		/*END LOGO */
		
		$pono         = $this->m_master->get_data_max($table, 'no_po');
		$bln          = $this->m_master->get_romawi(date('m'));
		$tahun        = date('Y');
		$nopo         = 'PO/'.$tahun.'/'.$bln.'/'.$pono;

		$pelanggan    = $this->m_master->get_data_one("m_pelanggan", "id_pelanggan", $params->id_pelanggan)->row();

		$total_qty    = 0;
		foreach ($params->id_produk as $key => $value) {
			// $produk = $this->m_master->get_data_one("m_produk", "kode_mc", $params->id_produk[$key])->row();
			// if($params->cek_rm[$key]== null)
			// {
			// 	$cek_rm = 0;
			// }else{
			// 	$cek_rm = $params->cek_rm[$key];
			// }

			$data = array(
				'tgl_po'          => $params->tgl_po,
				'kode_po'         => $params->kode_po,
				'eta'             => $params->eta_item[$key],
				'eta_ket'         => $params->eta_ket[$key],
				'cek_rm'          => 0,
				'qty'             => str_replace('.', '', $params->qty[$key]),
				'p11'             => $params->p11[$key],
				
				'rm'              => str_replace('.', '', $params->rm[$key]),
				'bb'              => $params->bb[$key],
				'ton'             => str_replace('.', '', $params->ton[$key]),
				'harga_kg'        => str_replace('.', '', $params->hrg_kg[$key]),
				
				'id_produk'       => $params->id_produk[$key],
					
				'id_pelanggan'    => $pelanggan->id_pelanggan,
				'ppn'             => $params->ppn[$key],
				'price_inc'       => str_replace('.', '', $params->price_inc[$key]),
				'price_exc'       => str_replace('.', '', $params->price_exc[$key])
			);

			if ($status == 'insert') {
				$this->db->set("no_po", $nopo);
				$this->db->set("add_user", $this->username);
				$result = $this->db->insert("trs_po_detail", $data);
			} else {

				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$result = $this->db->update(
					"trs_po_detail",
					$data,
					array(
						'no_po' => $params->no_po,
						// 'kode_mc' => $produk->kode_mc
						'id_produk' => $params->id_produk[$key]
					)
				);
			}

			$total_qty +=  str_replace('.', '', $params->qty[$key]);
		}

		$data = array(
			'tgl_po'         => $params->tgl_po,
			'kode_po'        => $params->kode_po,
			// 'eta'            => $params->eta,
			// 'id_sales'       => $params->txt_marketing,
			'id_pelanggan'   => $pelanggan->id_pelanggan,
			// 'nm_pelanggan'   => $pelanggan->nm_pelanggan,
			// 'alamat'         => $pelanggan->alamat,
			// 'alamat_kirim'   => $pelanggan->alamat_kirim,
			// 'lokasi'         => $pelanggan->lokasi,
			// 'kota'           => $pelanggan->kota,
			// 'no_telp'        => $pelanggan->no_telp,
			// 'fax'            => $pelanggan->fax,
			// 'top'            => $pelanggan->top,
			'total_qty'      => $total_qty,
			'img_po'         => $filefoto
		);

		if ($status == 'insert') {
			
			$this->db->set("no_po", $nopo);
			$this->db->set("add_user", $this->username);
			$result = $this->db->insert($table, $data);
		} else {

			$this->db->set("edit_user", $this->username);
			$this->db->set("edit_time", date('Y-m-d H:i:s'));
			$result = $this->db->update($table, $data, array('no_po' => $params->no_po));
		}

		return $result;
	}
	
	function trs_so_detail($table, $status)
	{
		$params = (object)$this->input->post();


		$detail_po = $this->m_master->get_data_one("trs_po", "no_po", $params->no_po)->row();

		$total_qty = 0;
		foreach ($params->id_produk as $key => $value) {
			$produk = $this->m_master->get_data_one("m_produk", "id_produk", $params->id_produk[$key])->row();

			$data = array(
				'no_so'           => $params->no_so,
				'no_po'           => $params->no_po,
				'tgl_so'          => $params->tgl_so,
				'salesman'        => $params->salesman,
				'kode_po'         => $detail_po->kode_po,
				'tgl_po'          => $params->tgl_po,
				'qty'             => $params->qty[$key],

				'kode_mc'         => $produk->kode_mc,
				'nm_produk'       => $produk->nm_produk,
				'ukuran'          => $produk->ukuran,

				'material'        => $produk->material,
				'flute'           => $produk->flute,
				'creasing'        => $produk->creasing,
				'warna'           => $produk->warna,
				'kualitas'        => $produk->kualitas,
				'jenis_produk'    => $produk->jenis_produk,
				'tipe_box'        => $produk->tipe_box,

				'id_pelanggan'    => $detail_po->id_pelanggan,
				'nm_pelanggan'    => $detail_po->nm_pelanggan,
				'alamat'          => $detail_po->alamat,
				'kota'            => $detail_po->kota,
				'no_telp'         => $detail_po->no_telp,
				'fax'             => $detail_po->fax,
				'alamat_kirim'    => $detail_po->alamat_kirim,
				'lokasi'          => $detail_po->lokasi,
				'top'             => $detail_po->top,
			);



			if ($status == 'insert') {
				$this->db->set("add_user", $this->username);
				$result = $this->db->insert("trs_so_detail", $data);
			} else {

				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$result = $this->db->update(
					"trs_so_detail",
					$data,
					array(
						'no_so' => $params->no_so
					)
				);
			}

			// $total_qty += $params->qty[$key];
		}

		// sum detail po from so
		$sum_detail = $this->db->query("SELECT a.`no_po`,a.kode_mc,a.nm_produk,a.qty,IFNULL(b.qty_detail,0)qty_detail FROM `trs_po_detail` a
                        LEFT JOIN 
                        (
                        SELECT no_po,kode_mc,SUM(qty) AS qty_detail FROM `trs_so_detail` WHERE STATUS <> 'Batal'
                        AND no_po = '" . $params->no_po . "'
                        GROUP BY no_po,kode_mc
                        )b
                        ON a.`no_po` = b.no_po
                        AND a.`kode_mc` = b.kode_mc
                        WHERE a.no_po = '" . $params->no_po . "'")->result();

		$status_header = 0;

		foreach ($sum_detail as $r) {
			if ($r->qty_detail >= $r->qty) {
				$this->db->query("UPDATE trs_po_detail SET status ='Closed' 
                                WHERE no_po = '" . $r->no_po . "' AND kode_mc = '" . $r->kode_mc . "' ");
			}

			if ($r->qty_detail < $r->qty) {
				$status_header++;
			}
		}


		if ($status_header == 0) {
			$this->db->query("UPDATE trs_po SET status ='Closed' 
                                WHERE no_po = '" . $r->no_po . "'");
		}


		return $result;
	}

	function update_plan($table, $status)
	{
		$params       = $this->input->post('jenis');

		foreach ($params->id_produk as $key => $value) 
		{
			
			$tl_al   = $params->tl_al[$key];
			$bmf     = $params->bmf[$key];
			$bl      = $params->bl[$key];
			$cmf     = $params->cmf[$key];
			$cl      = $params->cl[$key];

			
			$tl_al_i = str_replace('.', '', $params->tl_al_i[$key]);
			$bmf_i   = str_replace('.', '', $params->bmf_i[$key]);
			$bl_i    = str_replace('.', '', $params->bl_i[$key]);
			$cmf_i   = str_replace('.', '', $params->cmf_i[$key]);
			$cl_i    = str_replace('.', '', $params->cl_i[$key]);

			if($params->flute[$key] == "BCF"){
				$material_plan        = $tl_al+'/'+$bmf+'/'+$bl+'/'+$cmf+'/'+$cl;
				$kualitas_isi_plan    = $tl_al_i+'/'+$bmf_i+'/'+$bl_i+'/'+$cmf_i+'/'+$cl_i;
				$kualitas_plan        = $tl_al+$tl_al_i+'/'+$bmf+$bmf_i+'/'+$bl+$bl_i+'/'+$cmf+$cmf_i+'/'+$cl+$cl_i;

			} else if($params->flute[$key] == "CF") {
				$material_plan        = $tl_al+'/'+$cmf+'/'+$cl;
				$kualitas_isi_plan    = $tl_al_i+'/'+$cmf_i+'/'+$cl_i;
				$kualitas_plan        = $tl_al+$tl_al_i+'/'+$cmf+$cmf_i+'/'+$cl+$cl_i;

			} else if($params->flute[$key] == "BF") {
				$material_plan        = $tl_al+'/'+$bmf+'/'+$bl;
				$kualitas_isi_plan    = $tl_al_i+'/'+$bmf_i+'/'+$bl_i;
				$kualitas_plan        = $tl_al+$tl_al_i+'/'+$bmf+$bmf_i+'/'+$bl+$bl_i;

			} else {
				$material_plan        = 0;
				$kualitas_isi_plan    = 0;
				$kualitas_plan        = 0;
			}

			$data = array(
				'lebar_plan'          => str_replace('.', '', $params->ii_lebar[$key]),
				'qty_plan'            => str_replace('.', '', $params->qty_plan[$key]),
				'lebar_roll_p'        => str_replace('.', '', $params->i_lebar_roll[$key]),
				'out_plan'            => str_replace('.', '', $params->out_plan[$key]),
				'trim_plan'           => str_replace('.', '', $params->trim[$key]),
				'c_off_p'             => $params->c_off[$key],
				'rm_plan'             => $params->rm_plan[$key],
				'tonase_plan'         => $params->ton_plan[$key],
				'material_plan'       => $material_plan,
				'kualitas_isi_plan'   => $kualitas_isi_plan,
				'kualitas_plan'       => $kualitas_plan,
				'status_plan'         => 'Open'


			);

			$cek = $this->db->query("SELECT*FROM plan_cor_sementara WHERE no_po='$params->no_po' and id_produk='$params->id_produk[$key]'")->num_rows();

			if ($cek>0) {
				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$result = $this->db->update(
					"plan_cor_sementara",
					$data,
					array(
						'no_po' => $params->no_po,
						'id_produk' => $params->id_produk[$key]
					)
				);

			} else {
				$this->db->set("no_po", $params->no_po);
				$this->db->set("id_produk", $params->id_produk[$key]);
				$this->db->set("add_user", $this->username);
				$this->db->set("add_time", date('Y-m-d H:i:s'));
				$result = $this->db->insert("plan_cor_sementara", $data);
			}

		}

		return $result;
	}

	function trs_wo($table, $status)
	{
		$params = (object)$this->input->post();

		if (!empty($params->no_so)) {
			// code...
			// $detail_so = $this->m_master->get_data_one("trs_so_detail", "no_so", $params->no_so)->row();

			$detail_so = $this->db->query("SELECT * 
            FROM trs_so_detail a
            JOIN m_produk b ON a.id_produk=b.id_produk
            JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
			JOIN trs_po_detail d ON d.no_po=a.no_po and d.kode_po=a.kode_po and d.no_so=a.no_so and d.id_produk=a.id_produk
            WHERE a.status='Open' and a.id = '$params->no_so' ")->row();

			if($detail_so->kategori=='K_BOX')
			{
				$p1_sheet   = '-';
				$p1         = $params->p1;
				$l1         = $params->l1;
				$p2         = $params->p2;
				$l2         = $params->l2;
				$flap1      = $params->flap1;
				$creasing2  = $params->creasing2;
				$flap2      = $params->flap2;
				$kupingan   = $params->kupingan;
			}else{
				$p1_sheet   = $params->p1_sheet;
				$p1         = '-';
				$l1         = '-';
				$p2         = '-';
				$l2         = '-';
				$flap1      = $params->flap1_sheet;
				$creasing2  = $params->creasing2_sheet;
				$flap2      = $params->flap2_sheet;
				$kupingan   = '-';

			}

			$data = array(
				'no_wo'         => $params->no_wo,
				// 'line'          => $params->line,
				// 'no_artikel'    => $params->no_artikel,
				// 'batchno'       => $params->batchno,
				'tgl_wo'        => $params->tgl_wo,

				'p1_sheet'		=> $params->p1_sheet,
				'p1'  			=> $p1,
				'l1'  			=> $l1,
				'p2'  			=> $p2,
				'l2'  			=> $l2,
				'flap1'  		=> $flap1,
				'creasing2'  	=> $creasing2,
				'flap2'  		=> $flap2,
				'kupingan '  	=> $kupingan,
				'no_so'         => $params->no_so,
				'tgl_so'        => $detail_so->tgl_so,
				'no_po'         => $detail_so->no_po,
				'kode_po'       => $detail_so->kode_po,
				'tgl_po'        => $detail_so->tgl_po,
				'qty'           => $detail_so->qty_so,
				'id_produk'     => $detail_so->id_produk,
				'id_pelanggan'  => $detail_so->id_pelanggan,
				'kategori'      => $detail_so->kategori,
				
			);
		}

		$data_detail = array(
			'no_wo'            => $params->no_wo,
			'tgl_wo'           => $params->tgl_wo,

			// 'tgl_crg'          => $params->tgl_crg,
			// 'hasil_crg'        => $params->hasil_crg,
			// 'rusak_crg'        => $params->rusak_crg,
			// 'baik_crg'         => $params->baik_crg,
			// 'ket_crg'          => $params->ket_crg,

			// 'tgl_flx'          => $params->tgl_flx,
			// 'hasil_flx'        => $params->hasil_flx,
			// 'rusak_flx'        => $params->rusak_flx,
			// 'baik_flx'         => $params->baik_flx,
			// 'ket_flx'          => $params->ket_flx,

			// 'tgl_glu'          => $params->tgl_glu,
			// 'hasil_glu'        => $params->hasil_glu,
			// 'rusak_glu'        => $params->rusak_glu,
			// 'baik_glu'         => $params->baik_glu,
			// 'ket_glu'          => $params->ket_glu,

			// 'tgl_stc'          => $params->tgl_stc,
			// 'hasil_stc'        => $params->hasil_stc,
			// 'rusak_stc'        => $params->rusak_stc,
			// 'baik_stc'         => $params->baik_stc,
			// 'ket_stc'          => $params->ket_stc,

			// 'tgl_dic'          => $params->tgl_dic,
			// 'hasil_dic'        => $params->hasil_dic,
			// 'rusak_dic'        => $params->rusak_dic,
			// 'baik_dic'         => $params->baik_dic,
			// 'ket_dic'          => $params->ket_dic,

			// 'tgl_asembly'      => $params->tgl_asembly,
			// 'hasil_asembly'    => $params->hasil_asembly,
			// 'rusak_asembly'    => $params->rusak_asembly,
			// 'baik_asembly'     => $params->baik_asembly,
			// 'ket_asembly'      => $params->ket_asembly,
			
			// 'tgl_sliter'       => $params->tgl_sliter,
			// 'hasil_sliter'     => $params->hasil_sliter,
			// 'rusak_sliter'     => $params->rusak_sliter,
			// 'baik_sliter'      => $params->baik_sliter,
			// 'ket_sliter'       => $params->ket_sliter,

			// 'tgl_gdg'          => $params->tgl_gdg,
			// 'hasil_gdg'        => $params->hasil_gdg,
			// 'rusak_gdg'        => $params->rusak_gdg,
			// 'baik_gdg'         => $params->baik_gdg,
			// 'ket_gdg'          => $params->ket_gdg,

			// 'tgl_exp'          => $params->tgl_exp,
			// 'hasil_exp'        => $params->hasil_exp,
			// 'rusak_exp'        => $params->rusak_exp,
			// 'baik_exp'         => $params->baik_exp,
			// 'ket_exp'          => $params->ket_exp,
		);



		if ($status == 'insert') {
			$this->db->set("add_user", $this->username);
			$result = $this->db->insert("trs_wo", $data);

			$this->db->set("add_user", $this->username);
			$result = $this->db->insert("trs_wo_detail", $data_detail);

			$this->db->query("UPDATE trs_so_detail SET status ='Close' WHERE id = '" . $params->no_so . "'"); 
		} else {

			
			$p1_sheet   = $params->p1_sheet;
			$p1         = $params->p1;
			$l1         = $params->l1;
			$p2         = $params->p2;
			$l2         = $params->l2;
			$flap1      = $params->flap1;
			$creasing2  = $params->creasing2;
			$flap2      = $params->flap2;

			$data_update = array(
				'no_wo'       	=> $params->no_wo,
				// 'line'       	=> $params->line,
				// 'no_artikel'    => $params->no_artikel,
				// 'batchno'       => $params->batchno,

				'p1_sheet'		=> $p1_sheet,
				'p1'  			=> $p1,
				'l1'  			=> $l1,
				'p2'  			=> $p2,
				'l2'  			=> $l2,
				'flap1'  		=> $flap1,
				'creasing2'  	=> $creasing2,
				'flap2'  		=> $flap2,
				'kupingan'  	=> $params->kupingan,
			);

			$this->db->set("edit_user", $this->username);
			$this->db->set("edit_time", date('Y-m-d H:i:s'));
			$result = $this->db->update(
				"trs_wo",
				$data_update,
				array(
					'no_wo' => $params->no_wo
				)
			);


			$this->db->set("edit_user", $this->username);
			$this->db->set("edit_time", date('Y-m-d H:i:s'));
			$result = $this->db->update( 
				"trs_wo_detail",
				$data_detail,
				array(
					'no_wo' => $params->no_wo
				)
			);
		}



		return $result;
	}

	function trs_surat_jalan($table, $status)
	{
		$params = (object)$this->input->post();


		foreach ($params->id_produk as $key => $value) {

			$detail_po = $this->db->query("SELECT * FROM trs_po_detail WHERE no_po = '" . $params->no_po . "' and kode_mc = '" . $params->id_produk[$key] . "'")->row();

			$data = array(
				'no_surat_jalan'       => $params->no_surat_jalan,
				'tgl_surat_jalan'       => $params->tgl_surat_jalan,
				'no_pkb'       => $params->no_pkb,
				'no_kendaraan'       => $params->no_kendaraan,

				/*'no_so'       => $detail_po->no_so,
                    'tgl_so'       => $detail_po->tgl_so,*/
				'no_po'       => $detail_po->no_po,
				'kode_po'       => $detail_po->kode_po,
				'tgl_po'       => $detail_po->tgl_po,
				'qty'       => $params->qty[$key],
				'kode_mc'       => $detail_po->kode_mc,
				'nm_produk'     => $detail_po->nm_produk,
				'flute'         => $detail_po->flute,
				'id_pelanggan'  => $detail_po->id_pelanggan,                    'nm_pelanggan'  => $detail_po->nm_pelanggan
			);



			if ($status == 'insert') {
				$this->db->set("add_user", $this->username);
				$result = $this->db->insert($table, $data);

				/*$this->db->query("UPDATE trs_wo a 
                                        LEFT JOIN 
                                        (
                                        SELECT no_po,kode_mc,SUM(qty) AS qty_sj FROM `trs_surat_jalan` WHERE STATUS <> 'Batal' GROUP BY no_po,kode_mc
                                        )AS t_sj
                                        ON a.`no_po` = t_sj.no_po
                                        and a.`kode_mc` = t_sj.kode_mc

                                        SET a.`status` = IF(qty = IFNULL(qty_sj,0) ,'Closed','Open')
                                        WHERE 
                                            a.no_po ='".$params->no_po."'
                                            AND a.kode_mc ='".$detail_po->kode_mc."'
                                        ");*/
			} else {


				/*$this->db->set("edit_user", $this->username);
                    $this->db->set("edit_time", date('Y-m-d H:i:s'));
                    $result= $this->db->update($table,$data,array(
                                                                        'no_surat_jalan' => $params->no_surat_jalan
                                                                    )
                                              );*/
			}
		}



		return $result;
	}

	function batal($id, $jenis, $field)
	{

		$this->db->set("Status", 'Batal');
		$this->db->set("edit_user", $this->username);
		$this->db->set("edit_time", date('Y-m-d H:i:s'));
		$this->db->where($field, $id);
		$query = $this->db->update($jenis);

		if ($jenis == "trs_so_detail") {
			$data = $this->db->query("SELECT * FROM trs_so_detail WHERE id ='" . $id . "' ")->row();

			$this->db->set("Status", 'Open');
			$this->db->where("no_po", $data->no_po);
			$this->db->where("kode_mc", $data->kode_mc);
			$query = $this->db->update("trs_po_detail");

			$this->db->set("Status", 'Open');
			$this->db->where("no_po", $data->no_po);
			$query = $this->db->update("trs_po");
		} else if ($jenis == "trs_wo") {
			$data = $this->db->query("SELECT * FROM trs_wo WHERE id ='" . $id . "' ")->row();

			$this->db->set("Status", 'Open');
			$this->db->where("id", $data->no_so);
			$query = $this->db->update("trs_so_detail");

			// $this->db->set("Status", 'Batal');
			$this->db->where("no_wo", $data->no_wo);
			$query = $this->db->delete("trs_wo_detail");

			$this->db->where("no_wo", $data->no_wo);
			$query = $this->db->delete("trs_wo");
		} else if ($jenis == "trs_surat_jalan") {
			$data = $this->db->query("SELECT * FROM trs_surat_jalan WHERE id ='" . $id . "' ")->row();

			$this->db->set("Status", 'Open');
			$this->db->where("no_wo", $data->no_wo);
			$query = $this->db->update("trs_wo");

			$this->db->set("Status", 'Open');
			$this->db->where("no_wo", $data->no_wo);
			$query = $this->db->update("trs_wo_detail");
		}

		return $query;
	}

	function verifPO(){
		$id       = $this->input->post('id');
		$status   = $this->input->post('status');		
		$alasan   = $this->input->post('alasan');

		$app      = "";

		// KHUSUS ADMIN //

		if ($this->session->userdata('level') == "Admin") {
			$app = "3";
			if ($status == 'Y') {
				// header
				
				$this->db->set("status", 'Approve');
				$this->db->set("status_app1", $status);
				$this->db->set("user_app1", $this->username);
				$this->db->set("time_app1", $this->waktu);
				$this->db->set("ket_acc1", $alasan);
				
				$this->db->set("status_app2", $status);
				$this->db->set("user_app2", $this->username);
				$this->db->set("time_app2", $this->waktu);
				$this->db->set("ket_acc2", $alasan);
				
				$this->db->set("status_app3", $status);
				$this->db->set("user_app3", $this->username);
				$this->db->set("time_app3", $this->waktu);
				$this->db->set("ket_acc3", $alasan);

				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po");

				// detail
				$this->db->set("status", 'Approve');
				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po_detail");
			}else if ($status == 'N') {
				// header
				
				$this->db->set("status", 'Hold');
				$this->db->set("status_app1", $status);
				$this->db->set("user_app1", $this->username);
				$this->db->set("time_app1", $this->waktu);
				$this->db->set("ket_acc1", $alasan);
				
				$this->db->set("status_app2", $status);
				$this->db->set("user_app2", $this->username);
				$this->db->set("time_app2", $this->waktu);
				$this->db->set("ket_acc2", $alasan);
				
				$this->db->set("status_app3", $status);
				$this->db->set("user_app3", $this->username);
				$this->db->set("time_app3", $this->waktu);
				$this->db->set("ket_acc3", $alasan);

				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po");

				// detail
				$this->db->set("status", 'Approve');
				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po_detail");
			}else{

				$this->db->set("status", 'Reject');
				$this->db->set("status_app1", $status);
				$this->db->set("user_app1", $this->username);
				$this->db->set("time_app1", $this->waktu);
				$this->db->set("ket_acc1", $alasan);
				
				$this->db->set("status_app2", $status);
				$this->db->set("user_app2", $this->username);
				$this->db->set("time_app2", $this->waktu);
				$this->db->set("ket_acc2", $alasan);
				
				$this->db->set("status_app3", $status);
				$this->db->set("user_app3", $this->username);
				$this->db->set("time_app3", $this->waktu);
				$this->db->set("ket_acc3", $alasan);

				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po");

				// detail
				$this->db->set("status", 'Reject');
				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po_detail");
			}
		}else {

			if ($this->session->userdata('level') == "Marketing") {
				$app = "1";
				if ($status == 'Y') {
					$this->db->set("status", 'Open');
				}
			}else if ($this->session->userdata('level') == "PPIC") {
				$app = "2";
				if ($status == 'Y') {
					$this->db->set("status", 'Open');
				}
			}else if ($this->session->userdata('level') == "Owner") {
				$app = "3";
				if ($status == 'Y') {
					$this->db->set("status", 'Approve');
				}
			}
	
			if ($status == 'R') {
				$this->db->set("status", 'Reject');
			}
	
	
			$this->db->set("status_app".$app, $status);
			$this->db->set("user_app".$app, $this->username);
			$this->db->set("time_app".$app, $this->waktu);
			$this->db->set("ket_acc".$app, $alasan);
	
			$this->db->where("no_po",$id);
			$valid = $this->db->update("trs_po");
	
			if ($this->session->userdata('level') == "Owner") {
				$app = "3";
				if ($status == 'Y') {
					$this->db->set("status", 'Approve');
					$this->db->where("no_po",$id);
					$valid = $this->db->update("trs_po_detail");
				}
			}else if ($this->session->userdata('level') == "PPIC") {
				$app = "2";
				if ($status == 'Y') {
					$this->db->set("status", 'Open');
					$this->db->where("no_po",$id);
					$valid = $this->db->update("trs_po_detail");
				}
			}else if ($this->session->userdata('level') == "Marketing") {
				$app = "2";
				if ($status == 'Y') {
					$this->db->set("status", 'Open');
					$this->db->where("no_po",$id);
					$valid = $this->db->update("trs_po_detail");
				}
			}
	
			if ($status == 'R') {
				$this->db->set("status", 'Reject');
				$this->db->where("no_po",$id);
				$valid = $this->db->update("trs_po_detail");
			}

		}

		

		return $valid;
	}

	function simpanSO()
	{
		foreach($this->cart->contents() as $r){
			$id = $r['id'];
			$no_po = $r['options']['no_po'];
			$kode_po = $r['options']['kode_po'];
			$id_produk = $r['options']['id_produk'];
			$no_so = $r['options']['no_so'];
			$id_pelanggan = $r['options']['id_pelanggan'];
			$jml_so = $r['options']['jml_so'];

			$tmbhUrutSo = $this->db->query("SELECT urut_so FROM trs_so_detail
			WHERE id_pelanggan='$id_pelanggan' AND no_po='$no_po' AND kode_po='$kode_po'
			ORDER BY urut_so DESC LIMIT 1");
			($tmbhUrutSo->num_rows() == 0) ? $urut = 1 : $urut = $tmbhUrutSo->row()->urut_so + 1;
			$data = array(
				'id_pelanggan' => $id_pelanggan,
				'id_produk' => $id_produk,
				'eta_so' => $r['options']['eta_po'],
				'no_po' => $no_po,
				'kode_po' => $kode_po,
				'no_so' => $no_so,
				'urut_so' => $urut,
				'rpt' => 1,
				'qty_so' => $jml_so,
				'status' => 'Open',
				'ket_so' => '',
				'rm' => $r['options']['rm'],
				'ton' => $r['options']['ton'],
				'add_time' => date('Y-m-d H:i:s'),
				'add_user' => $this->username,
			);
			$result = $this->db->insert('trs_so_detail', $data);

			$this->db->set("no_so", $no_so);
			$this->db->set("tgl_so", $_POST["tgl_so"]);
			$this->db->set("status_so", 'Open');
			$this->db->set("add_time_so", date('Y-m-d H:i:s'));
			$this->db->set("add_user_so", $this->username);
			$this->db->where("id", $id);
			$this->db->where("no_po", $no_po);
			$this->db->where("kode_po", $kode_po);
			$this->db->where("id_produk", $id_produk);
			$result = $this->db->update('trs_po_detail');
		}

		return $result;
	}

	function editBagiSO()
	{
		$id = $_POST["i"];

		if($_POST["editTglSo"] == ""){
			$result = array(
				'data' => false,
				'msg' => 'ETA SO TIDAK BOLEH KOSONG!',
			);
		}else if($_POST["editQtySo"] == 0 || $_POST["editQtySo"] == ""){
			$result = array(
				'data' => false,
				'msg' => 'QTY SO TIDAK BOLEH KOSONG!',
			);
		}else if($_POST["editQtySo"] > $_POST["editQtypoSo"]){
			$result = array(
				'data' => false,
				'msg' => 'QTY SO LEBIH DARI QTY PO!',
			);
		}else{
			$produk = $this->db->query("SELECT p.* FROM m_produk p INNER JOIN trs_so_detail s ON p.id_produk=s.id_produk WHERE s.id='$id' GROUP BY p.id_produk");
			$RumusOut = 1800 / $produk->row()->ukuran_sheet_l;
			(floor($RumusOut) >= 5) ? $out = 5 : $out = (floor($RumusOut));
			$rm = ($produk->row()->ukuran_sheet_p * $_POST["editQtySo"] / $out) / 1000;
			$ton = $_POST["editQtySo"] * $produk->row()->berat_bersih;

			$data = array(
				"eta_so" => $_POST["editTglSo"],
				"qty_so" => $_POST["editQtySo"],
				"ket_so" => $_POST["editKetSo"],
				"cek_rm_so" => ($rm < 500) ? $_POST["editCekRM"] : 0,
				"rm" => round($rm),
				"ton" => round($ton),
				"edit_time" => date('Y-m-d H:i:s'),
				"edit_user" => $this->username,
			);

			if($_POST["editCekRM"] == 0){
				if($rm < 500){
					$insert = false;
					$msg = 'RM '.round($rm).' . RM KURANG!';
				}else{
					$this->db->where("id", $id);
					$insert = $this->db->update('trs_so_detail', $data);
					$msg = 'BERHASIL EDIT DATA!';
				}
			}else{
				if(round($rm) == 0 || round($ton) == 0 || round($rm) < 0 || round($ton) < 0 || $rm == "" || $ton == "" ){
					$insert = false;
					$msg = 'RM '.round($rm).' . RM / TONASE TIDAK BOLEH KOSONG!';
				}else{
					$this->db->where("id", $id);
					$insert = $this->db->update('trs_so_detail', $data);
					$msg = 'BERHASIL EDIT DATA!';
				}
			}

			$result = array(
				'data' => $insert,
				'msg' => $msg,
				'p' => $produk->row()->ukuran_sheet_p, 'l' => $produk->row()->ukuran_sheet_l, 'bb' => $produk->row()->berat_bersih, 'RumusOut' => $RumusOut, 'out' => $out, 'rm' => $rm, 'ton' => $ton,
			);
		}

		return $result;
	}

	function simpanCartItemSO()
	{
		foreach($this->cart->contents() as $r){
			$data = array(
				'id_pelanggan' => $r['options']['id_pelanggan'],
				'id_produk' => $r['options']['id_produk'],
				'eta_so' => $r['options']['eta_so'],
				'no_po' => $r['options']['no_po'],
				'kode_po' => $r['options']['kode_po'],
				'no_so' => $r['options']['no_so'],
				'urut_so' => $r['options']['urut_so'],
				'rpt' => $r['options']['rpt'],
				'qty_so' => $r['options']['qty_so'],
				'status' => 'Open',
				'ket_so' => $r['options']['ket_so'],
				'cek_rm_so' => ($r['options']['rm'] < 500) ? $r['options']['cek_rm_so'] : 0,
				'rm' => $r['options']['rm'],
				'ton' => $r['options']['ton'],
				'add_time' => date('Y-m-d H:i:s'),
				'add_user' => $this->username,
			);
			$result = $this->db->insert('trs_so_detail', $data);
		}
		return $result;
	}

	function batalDataSO()
	{
		$this->db->where('id', $_POST["i"]);
		$result = $this->db->delete('trs_so_detail');
		return array(
			'data' => $result,
			'msg' => 'BERHASIL BATAL DATA SO!'
		);
	}

	function hapusListSO()
	{
		$id = $_POST["id"];
		$getSoDetail = $this->db->query("SELECT*FROM trs_po_detail WHERE id='$id'")->row();
		$cekWo = $this->db->query("SELECT*FROM trs_wo
		WHERE no_po='$getSoDetail->no_po' AND kode_po='$getSoDetail->kode_po'
		GROUP BY no_po,kode_po;");

		if($cekWo->num_rows() != 0){
			return array(
				'data' => false,
				'msg' => 'SO SUDAH MASUK WO!'
			);
		}else{
			$this->db->where('no_po', $getSoDetail->no_po);
			$this->db->where('kode_po', $getSoDetail->kode_po);
			$this->db->where('id_produk', $getSoDetail->id_produk);
			$hapusDetailSO = $this->db->delete('trs_so_detail');

			$this->db->set('no_so', null);
			$this->db->set('tgl_so', null);
			$this->db->set('status_so', null);
			$this->db->set('add_time_so', '0000-00-00 00:00:00');
			$this->db->set('add_user_so', null);
			$this->db->where('no_po', $getSoDetail->no_po);
			$this->db->where('kode_po', $getSoDetail->kode_po);
			$this->db->where('id_produk', $getSoDetail->id_produk);
			$updateDetailPO = $this->db->update('trs_po_detail');

			return array(
				'hapusDetailSO' => $hapusDetailSO,
				'updateDetailPO' => $updateDetailPO,
				'data' => true,
				'msg' => 'BERHASIL HAPUS DATA SO!',
			);
		}
	}
}
