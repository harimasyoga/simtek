<?php
class M_master extends CI_Model{

	function __construct(){
        parent::__construct();
        
        date_default_timezone_set('Asia/Jakarta');
        $this->username = $this->session->userdata('username');
        
    }

    public function upload($file,$nama){
        // $file = 'foto';
        // unlink('../assets/images/member/'.$nama);
        $config['upload_path'] = './assets/gambar/produk/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        // $config['max_size'] = '20480';
        // $config['remove_space'] = TRUE;
        $config['file_name'] = $nama;
    
        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if($this->upload->do_upload($file)){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    public function upload2($file,$nama){
        // $file = 'foto';
        // unlink('../assets/images/member/'.$nama);
        $config['upload_path'] = './assets/gambar/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        // $config['max_size'] = '20480';
        // $config['remove_space'] = TRUE;
        $config['file_name'] = $nama;
    
        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if($this->upload->do_upload($file)){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    function get_data($table){
        $query = "SELECT * FROM $table";
        return $this->db->query($query);
    }

    function get_count($table){
        $query = "SELECT count(*) as jumlah FROM $table";
        return $this->db->query($query);
    }

    function get_data_one($table,$kolom,$id){
        
        $query = "SELECT * FROM $table WHERE $kolom='$id'";
        return $this->db->query($query);
    }

    function query($query1){
        
        $query = $query1;
        return $this->db->query($query);
    }

    function get_data_max($table,$kolom){
        $query = "SELECT IFNULL(LPAD(MAX(RIGHT($kolom,4))+1,4,0),'0001')AS nomor FROM $table";
        return $this->db->query($query)->row("nomor");
    }

    function delete($tabel, $kolom, $id){
        $result = $this->db->query("DELETE FROM $tabel WHERE $kolom='$id'");
        return $result;
    }
	
    
    function m_customer($table,$status)
	{
		$id_cs        = $_POST["id_cs"];
		$nm_cs        = $_POST["nm_cs"];
		$nm_cs_old    = $_POST["nm_cs_old"];
		
		$cekKode      = $this->db->query("SELECT*FROM m_customer WHERE nm_cs='$nm_cs'");

		if( $status=='insert' && $cekKode->num_rows() > 0 )
		{
			return array(
				'data' => false,
				'isi' => 'NAMA CUSTOMER SUDAH TERPAKAI!',
			);
		}else if( $status=='update' && $cekKode->num_rows() > 0 && $cekKode->row()->nm_cs != $nm_cs_old )
		{
			return array(
				'data' => false,
				'isi' => 'NAMA CUSTOMER SUDAH TERPAKAI!',
			);
		}else{
			$data = array(
				'pimpinan'    => $_POST["pimpinan"],
				'nm_cs'       => $_POST["nm_cs"],
				'alamat'      => $_POST["alamat"],
				'npwp'        => $_POST["npwp"],
				'no_telp'     => $_POST["no_telp"],
				'kode_pos'    => $_POST["kode_pos"],
			);

			if ($status == 'insert') {
				$this->db->set("add_user", $this->username);
				$inputData = $this->db->insert($table, $data);
			}else{
				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$this->db->where("id_cs", $_POST["id_cs"]);
				$inputData = $this->db->update($table, $data);
			}
			
			return array(
				'data' => true,
				'isi' => $inputData,
			);
		}
    }

	function m_supplier($table,$status)
	{
		$id_supp        = $_POST["id_supp"];
		$nm_supp_old    = $_POST["nm_supp_old"];
		$nm_supp        = $_POST["nm_supp"];
		$alamat         = $_POST["alamat"];
		$no_hp          = $_POST["no_hp"];
		$jt             = $_POST["jt"];
		
		$cekKode      = $this->db->query("SELECT*FROM m_supplier WHERE nm_supp='$nm_supp'");

		if( $status=='insert' && $cekKode->num_rows() > 0 )
		{
			return array(
				'data' => false,
				'isi' => 'NAMA SUPPLIER SUDAH TERPAKAI!',
			);
		}else if( $status=='update' && $cekKode->num_rows() > 0 && $cekKode->row()->nm_supp != $nm_supp_old )
		{
			return array(
				'data' => false,
				'isi' => 'NAMA SUPPLIER SUDAH TERPAKAI!',
			);
		}else{
			$data = array(
				'nm_supp'   => $_POST["nm_supp"],
				'alamat'    => $_POST["alamat"],
				'no_hp'     => $_POST["no_hp"],
				'jt'        => $_POST["jt"],
			);

			if ($status == 'insert') {
				$this->db->set("add_user", $this->username);
				$this->db->set("add_time", date('Y-m-d H:i:s'));
				$inputData = $this->db->insert($table, $data);
			}else{
				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$this->db->where("id_supp", $_POST["id_supp"]);
				$inputData = $this->db->update($table, $data);
			}
			
			return array(
				'data' => true,
				'isi' => $inputData,
			);
		}
    }
    
	function m_pelanggan($table,$status)
	{
		$kode_lama = $_POST["kode_lama"];
		$kode_pelanggan = $_POST["kode_pelanggan"];
		$cekKode = $this->db->query("SELECT*FROM m_pelanggan WHERE kode_unik='$kode_pelanggan'")->num_rows();
		if($status == 'update' && $kode_lama != $kode_pelanggan && $cekKode > 0){
			return array(
				'data' => false,
				'isi' => 'KODE PELANGGAN SUDAH ADA!',
			);
		}else if($status == 'insert' && $cekKode > 0){
			return array(
				'data' => false,
				'isi' => 'KODE PELANGGAN SUDAH ADA!',
			);
		}else{
			$data = array(
				'id_sales' => $_POST["id_sales"],
				'kode_unik' => $_POST["kode_pelanggan"],
				'nm_pelanggan' => $_POST["nm_pelanggan"],
				'attn' => $_POST["attn"],
				'alamat' => $_POST["alamat"],
				'alamat_kirim' => $_POST["alamat_kirim"],
				'prov' => ($_POST["provinsi"] == 0 || $_POST["provinsi"] == null || $_POST["provinsi"] == "") ? null : $_POST["provinsi"],
				'kab' => ($_POST["kota_kab"] == 0 || $_POST["kota_kab"] == null || $_POST["kota_kab"] == "") ? null : $_POST["kota_kab"],
				'kec' => ($_POST["kecamatan"] == 0 || $_POST["kecamatan"] == null || $_POST["kecamatan"] == "") ? null : $_POST["kecamatan"],
				'kel' => ($_POST["kelurahan"] == 0 || $_POST["kelurahan"] == null || $_POST["kelurahan"] == "") ? null : $_POST["kelurahan"],
				'kode_pos' => $_POST["kode_pos"],
				'fax' => $_POST["fax"],
				'top' => $_POST["top1"],
				'no_telp' => $_POST["no_telp"],
			);

			if ($status == 'insert') {
				$this->db->set("add_user", $this->username);
				$inputData = $this->db->insert($table, $data);
			}else{
				$this->db->set("edit_user", $this->username);
				$this->db->set("edit_time", date('Y-m-d H:i:s'));
				$this->db->where("id_pelanggan", $_POST["id_pelanggan"]);
				$inputData = $this->db->update($table, $data);
			}
			
			return array(
				'data' => true,
				'isi' => $inputData,
			);
		}
    }

    function tb_user($table,$status){
        
        
        $id = $this->input->post('username');

        $data = array(
                'username'  => $id,
                'nm_user'  	=> $this->input->post('nm_user'),
                'password'  => base64_encode($this->input->post('password')),
                'level'  	=> $this->input->post('level'),
            );

        if ($status == 'insert') {
             $cek = $this->db->query("SELECT * FROM tb_user WHERE username = '$id'
                ")->num_rows();

            if ($cek > 0) {
                return false;
            }

            $result= $this->db->insert('tb_user',$data);
        }else{
            $result= $this->db->update($table,$data,array('username' => $id));
        }
        

        return $result;
    }

	function m_modul_group($table,$status)
	{
		
        $id         = $this->input->post('id_group');
        $nm_group   = $this->input->post('nm_group');
        $val_group  = $this->input->post('val_group');
        $approve  = $this->input->post('approve');
        $data = array(
			'nm_group' => $nm_group,
			'val_group' => $val_group,
			'approve' => $approve,
		);
		$cek = $this->db->query("SELECT*FROM m_modul_group WHERE val_group='$val_group' ")->num_rows();
        if($status == 'insert') {
			if($cek > 0) {
				return false;
			}else{
				$result= $this->db->insert($table,$data);
			}
        }
		// else{
		// 	$s = $this->db->query("SELECT*FROM m_modul_group WHERE id_group='$id'")->row();
		// 	if($cek > 0 != 0 && $s->val_group != $val_group){
		// 		$result = false;
		// 	}else{
		// 		$this->db->where('id_group', $id);
		// 		$result= $this->db->update($table, $data);
		// 	}
        // }
        return $result;
    }

    function edit_modul($table,$status)
	{        
        $id_group   = $this->input->post('id_group');
        $query      = $this->db->query("SELECT*FROM m_modul")->result();

		$delete = $this->db->query("DELETE from m_modul_groupd where id_group='$id_group' ");

		foreach ( $query as $row ) {
			$cek = $this->input->post('status'.$row->kode);
			if($cek == 1)
			{
				$data = [
					'id_group'      => $id_group,
					'kode_modul'    => $row->kode,
					'add'           => '1',
					'edit'          => '1',
					'del'           => '1',
					'cetak'         => '1',
				];

				$result = $this->db->insert("m_modul_groupd", $data);

			}
		}
        

        return $result;
    }

	function simpanBagian()
	{        
        $id_group = $this->input->post('id_group');
        $query = $this->db->query("SELECT*FROM m_departemen")->result();
		$delete = $this->db->query("DELETE from m_departemen_bagian where id_group='$id_group' ");
		if($delete){
			foreach ( $query as $row ) {
				$cek = $this->input->post('status'.$row->kode);
				if($cek == 1) {
					$data = [
						'id_group' => $id_group,
						'kode_departemen' => $row->kode,
					];
					$result = $this->db->insert("m_departemen_bagian", $data);
				}else{
					$result = false;
				}
			}
		}
        return $result;
    }
    
    function simpanBarang()
	{
		// HEADER
		if($this->cart->total_items() != 0){
			foreach($this->cart->contents() as $r){
				$id_mbh = $r['options']['id_mbh'];
				$nm_barang = $r['options']['nm_barang'];
				if($id_mbh == '+'){
					$cekBarang = $this->db->query("SELECT*FROM m_barang_header WHERE nm_barang='$nm_barang'");
				}else{
					$cekBarang = $this->db->query("SELECT*FROM m_barang_header WHERE id_mbh='$id_mbh'");
				}
				if($cekBarang->num_rows() == 0){
					$header = [
						'kode_header' => $r['options']['kode_header'],
						'nm_barang' => $r['options']['nm_barang'],
						'creat_by' => $this->username,
						'creat_at' => date('Y-m-d H:i:s'),
					];
					$i_header = $this->db->insert("m_barang_header", $header);
				}else{
					$i_header = true;
				}
			}
		}else{
			$i_header = false;
		}
		// DETAIL
		if($i_header){
			if($this->cart->total_items() != 0){
				foreach($this->cart->contents() as $r){
					$id_mbh2 = $r['options']['id_mbh'];
					$nm_barang2 = $r['options']['nm_barang'];
					if($id_mbh2 == '+'){
						$cekHeader = $this->db->query("SELECT*FROM m_barang_header WHERE nm_barang='$nm_barang2'");
					}else{
						$cekHeader = $this->db->query("SELECT*FROM m_barang_header WHERE id_mbh='$id_mbh2'");
					}
					if($cekHeader->num_rows() != 0){
						$detail = [
							'id_mbh' => $cekHeader->row()->id_mbh,
							'kode_barang' => $r['options']['kode_barang'].'-'.$r['options']['kode_urut'],
							'jenis_tipe' => ($r['options']['jenis_tipe'] == '') ? '-' : $r['options']['jenis_tipe'],
							'material' => ($r['options']['material'] == '') ? '-' : $r['options']['material'],
							'size' => ($r['options']['size'] == '') ? '-' : $r['options']['size'],
							'merk' => ($r['options']['merk'] == '') ? '-' : $r['options']['merk'],
							'p_satuan' => $r['options']['pilih_satuan'],
							'qty1' => ($r['options']['satuan_terbesar'] == 0) ? null : $r['options']['satuan_terbesar'],
							'satuan1' => ($r['options']['p_satuan_terbesar'] == "") ? null : $r['options']['p_satuan_terbesar'],
							'qty2' => ($r['options']['satuan_tengah'] == 0) ? null : $r['options']['satuan_tengah'],
							'satuan2' => ($r['options']['p_satuan_tengah'] == "") ? null : $r['options']['p_satuan_tengah'],
							'qty3' => $r['options']['satuan_terkecil'],
							'satuan3' => $r['options']['p_satuan_terkecil'],
							'creat_by' => $this->username,
							'creat_at' => date('Y-m-d H:i:s'),
						];
						$i_detail = $this->db->insert("m_barang_detail", $detail);
					}
				}
			}else{
				$i_detail = false;
			}
		}else{
			$i_detail = false;
		}
		return [
			'i_header' => $i_header,
			'i_detail' => $i_detail,
		];
	}

	function editBarang($data = '')
	{
		$kode_lama = $data['options']['detail'];
		$kode_barang = $data['options']['kode_barang'];
		$kode_baru = $kode_barang.'-'.$data['options']['kode_urut'];
		if($kode_lama != $kode_barang){
			$this->db->set('kode_barang', $kode_baru);
		}
		$this->db->set('jenis_tipe', $data['options']['jenis_tipe']);
		$this->db->set('material', $data['options']['material']);
		$this->db->set('size', $data['options']['size']);
		$this->db->set('merk', $data['options']['merk']);
		$this->db->set('p_satuan', $_POST['pilih_satuan']);
		$this->db->set('qty1', ($_POST['satuan_terbesar'] == 0) ? null : $_POST['satuan_terbesar']);
		$this->db->set('satuan1', ($_POST['p_satuan_terbesar'] == "") ? null : $_POST['p_satuan_terbesar']);
		$this->db->set('qty2', ($_POST['satuan_tengah'] == 0) ? null : $_POST['satuan_tengah']);
		$this->db->set('satuan2', ($_POST['p_satuan_tengah'] == "") ? null : $_POST['p_satuan_tengah']);
		$this->db->set('qty3', $_POST['satuan_terkecil']);
		$this->db->set('satuan3', $_POST['p_satuan_terkecil']);
		$this->db->set('edit_by', $this->username);
		$this->db->set('edit_at', date('Y-m-d H:i:s'));
		$this->db->where('id_mbh', $_POST['id_mbh']);
		$this->db->where('id_mbd', $_POST['id_mbd']);
		$update = $this->db->update('m_barang_detail');
		return [
			'data' => $data,
			'update' => $update,
		];
	}

	function hapusBarang()
	{
		$id_mbh = $_POST["id_mbh"];
		$id_mbd = $_POST["id_mbd"];
		$this->db->where('id_mbd', $id_mbd);
		$detail = $this->db->delete('m_barang_detail');
		if($detail){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' GROUP BY id_mbh")->num_rows();
			if($cekBarang == 0){
				$this->db->where('id_mbh', $id_mbh);
				$header = $this->db->delete('m_barang_header');
			}else{
				$header = false;
			}
		}else{
			$header = false;
		}
		return [
			'delete' => $detail,
			'header' => $header,
		];
	}

	function simpanSatuan()
	{
		$id = $_POST["id"];
		$status = $_POST["status"];
		$kode_satuan = $_POST["kode_satuan"];
		$ket_satuan = $_POST["ket_satuan"];
		$cek = $this->db->query("SELECT*FROM m_satuan WHERE kode_satuan='$kode_satuan'")->num_rows();
		$this->db->set('kode_satuan', $kode_satuan);
		$this->db->set('ket_satuan', trim($ket_satuan));
		if($status == 'insert'){
			if($cek == 0){
				$this->db->set('creat_by', $this->username);
				$this->db->set('creat_at', date('Y-m-d H:i:s'));
				$data = $this->db->insert('m_satuan');
				$msg = 'OK!';
			}else{
				$data = false;
				$msg = 'DATA SATUAN SUDAH ADA!';
			}
		}
		if($status == 'update'){
			$s = $this->db->query("SELECT*FROM m_satuan WHERE id='$id'")->row();
			if($s->kode_satuan == $kode_satuan && $s->ket_satuan == $ket_satuan){
				$data = false;
				$msg = 'DATA SATUAN SUDAH ADA!';
			}else if($cek != 0 && $s->kode_satuan != $kode_satuan){
				$data = false;
				$msg = 'DATA SATUAN SUDAH ADA!';
			}else{
				$this->db->set('edit_by', $this->username);
				$this->db->set('edit_at', date('Y-m-d H:i:s'));
				$this->db->where('id', $id);
				$data = $this->db->update('m_satuan');
				$msg = 'OK!';
			}
		}
		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

    function m_setting($table,$status){
        $data = array(
            'nm_aplikasi'  => $this->input->post('nm_aplikasi'),
            'singkatan'  => $this->input->post('singkatan'),
            'nm_toko'  => $this->input->post('nm_toko'),
            'alamat'  => $this->input->post('alamat'),
            'no_telp'  => $this->input->post('no_telp'),
            'diskon_member'  => $this->input->post('diskon_member')
        );
        $upload = $this->m_master->upload2('logo','logo');

        if ($upload['result'] == 'success') {
            $this->db->set("logo", $upload['file']['file_name'] );
        }
        $result= $this->db->update($table,$data);
        
        return $result;
    }

    function update_status($status,$id,$table,$field){
        if ($status == '1') {
            $ubah = '0';
        }else{
            $ubah = '1';
        }
        $this->db->set("status", $ubah);
        $this->db->where($field, $id);

        return $this->db->update($table);

    }

    function  get_romawi($bln){
		switch  ($bln){
			case  1:
			return  "I";
			break;
			case  2:
			return  "II";
			break;
			case  3:
			return  "III";
			break;
			case  4:
			return  "IV";
			break;
			case  5:
			return  "V";
			break;
			case  6:
			return  "VI";
			break;
			case  7:
			return  "VII";
			break;
			case  8:
			return  "VIII";
			break;
			case  9:
			return  "IX";
			break;
			case  10:
			return  "X";
			break;
			case  11:
			return  "XI";
			break;
			case  12:
			return  "XII";
			break;
		}
    }

}

?>
