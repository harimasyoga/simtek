<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_master');
		$data['setting'] = $this->m_master->get_data("m_setting")->row();
	}

	public function index()
	{
		$data = array(
			'judul' => "Dashboard",
		);
		$this->load->view('header',$data );
		$this->load->view('home');
		$this->load->view('footer');
	}
	
	function Customer()
	{
		$data = array(
			'judul' => "Master Customer"
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_customer', $data);
		$this->load->view('footer');
	}
	
	function Produk()
	{

		$data = array(
			'judul' => "Master Produk",
			// 'pelanggan' => $this->m_master->get_data("m_pelanggan")->result()
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_produk', $data);
		$this->load->view('footer');
	}

	
	function Supplier()
	{
		$data = array(
			'judul' => "Master Supplier"
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_supplier', $data);
		$this->load->view('footer');
	}
	
	function Barang()
	{
		$data = array(
			'judul' => "Master Barang"
		);
		$this->load->view('header', $data);
		$this->load->view('Master/v_barang', $data);
		$this->load->view('footer');
	}

	function cekNamaBarang()
	{
		$n_barang = $_POST["n_barang"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_barang)));
		$cekBarang = $this->db->query("SELECT*FROM m_barang_header WHERE nm_barang='$cleanTxt'");
		if($n_barang == '' || $cleanTxt == ''){
			$data = false; $msg = 'NAMA BARANG BARU TIDAK BOLEH KOSONG!';
		}else if(!preg_match("/^[A-Z0-9 ]*$/", $cleanTxt)){
			$data = false; $msg = 'NAMA BARANG HANYA BOLEH HURUF ATAU ANGKA!';
		}else if($cekBarang->num_rows() != 0){
			$data = false; $msg = 'NAMA BARANG SUDAH ADA!';
		}else{
			$data = true; $msg = '';
		}
		echo json_encode([
			'cleanTxt' => $cleanTxt,
			'data' => $data,
			'msg' => $msg,
		]);
	}

	function addBarang()
	{
		$i_barang = $_POST["i_barang"];
		$n_barang = $_POST["n_barang"];
		if($i_barang == '+'){
			$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_barang)));
			$cekBarang = $this->db->query("SELECT*FROM m_barang_header WHERE nm_barang='$cleanTxt'");
			if($n_barang == ""){
				echo json_encode(array('data' => false, 'isi' => 'NAMA BARANG BARU TIDAK BOLEH KOSONG!')); return;
			}else if(!preg_match("/^[A-Z0-9 ]*$/", $cleanTxt)){
				echo json_encode(array('data' => false, 'isi' => 'NAMA BARANG HANYA BOLEH HURUF ATAU ANGKA!')); return;
			}else if($cekBarang->num_rows() != 0){
				echo json_encode(array('data' => false, 'isi' => 'NAMA BARANG SUDAH ADA!')); return;
			}else{
				$nm_barang = $cleanTxt;
				// KODE BARANG
				// $nm = $n_barang;
				// $arr = explode(' ', $nm);
				// $kode = '';
				// foreach($arr as $kata)
				// {
				// 	$kode .= substr($kata, 0, 1);
				// }
				// $cekKode = $this->db->query("SELECT*FROM m_barang_header WHERE kode_barang='$kode'");
			}
		}else{
			if($i_barang == ""){
				echo json_encode(array('data' => false, 'isi' => 'PILIH BARANG DAHULU!')); return;
			}else{
				$b = $this->db->query("SELECT*FROM m_barang_header WHERE id_barang='$i_barang'")->row();
				$nm_barang = $b->nm_barang;
				// $kode_barang = $b->kode_barang;
			}
		}

		$data = array(
			'id' => $_POST["id_cart"],
			'name' => 'barang_'.$_POST["id_cart"],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'nm_barang' => $nm_barang,
				// 'kode_barang' => $kode_barang,
			)
		);
		// $this->cart->insert($data);
		echo json_encode(array('data' => $data, 'isi' => 'OK!'));
	}

	function loadJenisTipe()
	{
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		if($cari == 'jenis_tipe'){
			$cekJenisTipe = $this->db->query("SELECT*FROM m_barang_detail GROUP BY jenis_tipe");
		}else{
			$cekJenisTipe = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' GROUP BY jenis_tipe");
		}
		$html = '';
		if($cekJenisTipe->num_rows() != 0){
			$data = true;
			$html .='<option value="" id_mbh="">PILIH</option>';
			foreach($cekJenisTipe->result() as $r){
				$html .='<option value="'.$r->jenis_tipe.'" id_mbh="'.$id_mbh.'">'.$r->jenis_tipe.'</option>';
			}
			$html .='<option value="+" id_mbh="'.$id_mbh.'">+</option>';
		}else{
			$data = false;
			$html .= '<option value="">PILIH</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekJenisTipe()
	{
		$id_mbh = $_POST["id_mbh"];
		$n_jenis_tipe = $_POST["n_jenis_tipe"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_jenis_tipe)));
		if($id_mbh == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE jenis_tipe='$cleanTxt'");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$cleanTxt'");
		}
		if($n_jenis_tipe == '' || $cleanTxt == ''){
			$data = false; $msg = 'JENIS / TIPE BARU TIDAK BOLEH KOSONG!';
		}else if($cekBarang->num_rows() != 0){
			$data = false; $msg = 'JENIS / TIPE SUDAH ADA!';
		}else{
			$data = true; $msg = '';
		}
		echo json_encode([
			'cleanTxt' => $cleanTxt,
			'data' => $data,
			'msg' => $msg,
		]);
	}

	function loadMaterial()
	{
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		if($cari == 'material' || $id_mbh == '+'){
			$cekMaterial = $this->db->query("SELECT*FROM m_barang_detail GROUP BY material");
		}else{
			$cekMaterial = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' GROUP BY material");
		}
		$html = '';
		if($cekMaterial->num_rows() != 0){
			$data = true;
			$html .='<option value="">PILIH</option>';
			foreach($cekMaterial->result() as $r){
				$html .='<option value="'.$r->material.'">'.$r->material.'</option>';
			}
			$html .='<option value="+">+</option>';
		}else{
			$data = false;
			$html .= '<option value="">PILIH</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekMaterial()
	{
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$n_material = $_POST["n_material"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_material)));
		if($id_mbh == '+' || $i_jenis_tipe == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE material='$cleanTxt'");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$cleanTxt'");
		}
		if($n_material == '' || $cleanTxt == ''){
			$data = false; $msg = 'MATERIAL BARU TIDAK BOLEH KOSONG!';
		}else if($cekBarang->num_rows() != 0){
			$data = false; $msg = 'MATERIAL SUDAH ADA!';
		}else{
			$data = true; $msg = '';
		}
		echo json_encode([
			'cleanTxt' => $cleanTxt,
			'data' => $data,
			'msg' => $msg,
		]);
	}

	function loadSize()
	{
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		$material = $_POST["material"];
		if($cari == 'size' || $id_mbh == '+' || $jenis_tipe == '+'){
			$cekSize = $this->db->query("SELECT*FROM m_barang_detail s GROUP BY s.size");
		}else{
			$cekSize = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' AND material='$material' GROUP BY s.size");
		}
		$html = '';
		if($cekSize->num_rows() != 0){
			$data = true;
			$html .='<option value="">PILIH</option>';
			foreach($cekSize->result() as $r){
				$html .='<option value="'.$r->size.'">'.$r->size.'</option>';
			}
			$html .='<option value="+">+</option>';
		}else{
			$data = false;
			$html .= '<option value="">PILIH</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekSize()
	{
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$i_material = $_POST["i_material"];
		$n_size = $_POST["n_size"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_size)));
		if($id_mbh == '+' || $i_jenis_tipe == '+' || $i_material == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE s.size='$cleanTxt'");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$i_material' AND s.size='$cleanTxt'");
		}
		if($n_size == '' || $cleanTxt == ''){
			$data = false; $msg = 'SIZE BARU TIDAK BOLEH KOSONG!';
		}else if($cekBarang->num_rows() != 0){
			$data = false; $msg = 'SIZE SUDAH ADA!';
		}else{
			$data = true; $msg = '';
		}
		echo json_encode([
			'cleanTxt' => $cleanTxt,
			'data' => $data,
			'msg' => $msg,
		]);
	}

	function loadMerk()
	{
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		$material = $_POST["material"];
		$size = $_POST["size"];
		if($cari == 'merk' || $id_mbh == '+' || $jenis_tipe == '+' || $material == '+'){
			$cekMerk = $this->db->query("SELECT*FROM m_barang_detail s GROUP BY merk");
		}else{
			$cekMerk = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' AND material='$material' AND s.size='$size' GROUP BY merk");
		}
		$html = '';
		if($cekMerk->num_rows() != 0){
			$data = true;
			$html .='<option value="">PILIH</option>';
			foreach($cekMerk->result() as $r){
				$html .='<option value="'.$r->merk.'">'.$r->merk.'</option>';
			}
			$html .='<option value="+">+</option>';
		}else{
			$data = false;
			$html .= '<option value="">PILIH</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekMerk()
	{
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$i_material = $_POST["i_material"];
		$i_size = $_POST["i_size"];
		$n_merk = $_POST["n_merk"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_merk)));
		if($id_mbh == '+' || $i_jenis_tipe == '+' || $i_material == '+' || $i_size == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE merk='$cleanTxt'");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$i_material' AND s.size='$i_size' AND merk='$cleanTxt'");
		}
		if($n_merk == '' || $cleanTxt == ''){
			$data = false; $msg = 'MERK BARU TIDAK BOLEH KOSONG!';
		}else if($cekBarang->num_rows() != 0){
			$data = false; $msg = 'MERK SUDAH ADA!';
		}else{
			$data = true; $msg = '';
		}
		echo json_encode([
			'cleanTxt' => $cleanTxt,
			'data' => $data,
			'msg' => $msg,
		]);
	}

	// function cartBarang()
	// {
	// 	$html = '';
	// 	if($this->cart->total_items() == 0){
	// 		$html .= 'LIST KOSONG!';
	// 	}
	// 	if($this->cart->total_items() != 0){
	// 		$html .='<table class="table table-bordered table-striped">';
	// 		$html .='<tr>
	// 			<th style="padding:6px;text-align:center">NO.</th>
	// 		</tr>';
	// 	}
	// 	$i = 0;
	// 	foreach($this->cart->contents() as $r){
	// 		$i++;
	// 		$r['options']['order_pori'];
	// 	}
	// 	if($this->cart->total_items() != 0){
	// 	}
	// 	echo $html;
	// }

	function plhWilayah()
	{
		$v_prov = $_POST["prov"];
		$v_kab = $_POST["kab"];
		$v_kec = $_POST["kec"];

		if($v_prov == 0 && $v_kab == 0 && $v_kec == 0){
			$prov = $this->db->query("SELECT*FROM m_provinsi")->result();
			$kab = 0;
			$kec = 0;
			$kel = 0;
		}else if($v_prov != 0 && $v_kab == 0 && $v_kec == 0){
			$prov = $this->db->query("SELECT*FROM m_provinsi")->result();
			$kab = $this->db->query("SELECT*FROM m_kab WHERE prov_id='$v_prov'")->result();
			$kec = 0;
			$kel = 0;
		}else if($v_prov != 0 && $v_kab != 0 && $v_kec == 0){
			$prov = $this->db->query("SELECT*FROM m_provinsi")->result();
			$kab = $this->db->query("SELECT*FROM m_kab WHERE prov_id='$v_prov'")->result();
			$kec = $this->db->query("SELECT*FROM m_kec WHERE kab_id='$v_kab'")->result();
			$kel = 0;
		}else if($v_prov != 0 && $v_kab != 0 && $v_kec != 0){
			$prov = $this->db->query("SELECT*FROM m_provinsi")->result();
			$kab = $this->db->query("SELECT*FROM m_kab WHERE prov_id='$v_prov'")->result();
			$kec = $this->db->query("SELECT*FROM m_kec WHERE kab_id='$v_kab'")->result();
			$kel = $this->db->query("SELECT*FROM m_kel WHERE kec_id='$v_kec'")->result();
		}else{
			$prov = 0;
			$kab = 0;
			$kec = 0;
			$kel = 0;
		}

		echo json_encode(array(
			'prov' => $prov,
			'kab' => $kab,
			'kec' => $kec,
			'kel' => $kel,
		));
	}

	function User()
	{
		$data = array(
			'judul' => "Master User"
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_user', $data);
		$this->load->view('footer');
	}

	function User_level()
	{
		$data = array(
			'judul' => "Master User Level"
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_user_level', $data);
		$this->load->view('footer');
	}
	
	function User_level_add()
	{
		
		$val_group    = $_GET['val_group'];
		$nama         = $this->db->query("SELECT*FROM m_modul_group a WHERE id_group='$val_group' LIMIT 1 ")->row();
		
		$query = $this->db->query("SELECT *
		FROM m_modul a order by kode")->result();

		$data = array(
			'judul'  => "Edit Modul",
			'judul2' => "$nama->nm_group",
			'id'     => $val_group,
			'query'  => $query
		);
		

		$this->load->view('header', $data);
		$this->load->view('Master/v_user_level_add', $data);
		$this->load->view('footer');
	}

	function load_group()
	{
		if($this->session->userdata('level') == 'PPIC')
		{
			$cek="WHERE id_group in ('7','8','9') ";
		}else{
			$cek="";
		}
		$data = $this->db->query("SELECT * FROM m_modul_group $cek
		ORDER BY nm_group ")->result();
		echo json_encode($data);
	}

	function Sistem()
	{
		$data = array(
			'data' => $this->m_master->get_data("m_setting")->row(),			
			'judul' => "Master Sistem",
		);

		$this->load->view('header',$data);
		$this->load->view('Master/v_setting', $data);
		$this->load->view('footer');
	}

	function Insert()
	{
		$jenis = $this->input->post('jenis');
		$status = $this->input->post('status');
		
		$result = $this->m_master->$jenis($jenis, $status);
		echo json_encode($result);
	}

	function load_data()
	{
		$jenis = $this->uri->segment(3);

		$data = array();

		if ($jenis == "customer") {
			$query = $this->m_master->query("SELECT * FROM m_customer
			ORDER BY id_cs")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampil_edit('."'".$r->id_cs."'".','."'detail'".')">'.$i."<a></div>";
				$row[] = $r->pimpinan;
				$row[] = $r->nm_cs;
				$row[] = $r->alamat;
				$row[] = ($r->npwp == 0) ? '-' : $r->npwp;
				$row[] = ($r->no_telp == "") ? '-' : $r->no_telp;

				$idPelanggan = $r->id_cs;
				// $cekProduk = $this->db->query("SELECT * FROM m_produk WHERE no_customer='$idPelanggan'")->num_rows();

				if (in_array($this->session->userdata('level'), ['Admin','User']))
				{
					$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_cs."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';
					$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_cs."'".')"><i class="fas fa-times"></i></button>';

				}else{

					$btnEdit = '';
					$btnHapus = '';
				}
				

				// $row[] = ($cekProduk == 0) ? $btnEdit.' '.$btnHapus : $btnEdit ;
				$row[] = $btnEdit.' '.$btnHapus ;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "supplier") {
			$query = $this->m_master->query("SELECT * FROM m_supplier
			ORDER BY id_supp")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampil_edit('."'".$r->id_supp."'".','."'detail'".')">'.$i."<a></div>";

				$row[] = $r->nm_supp;
				$row[] = $r->alamat;
				$row[] = $r->no_hp;
				$row[] = $r->jt;

				$idPelanggan = $r->id_supp;
				// $cekProduk = $this->db->query("SELECT * FROM m_produk WHERE no_customer='$idPelanggan'")->num_rows();

				if (in_array($this->session->userdata('level'), ['Admin','User']))
				{
					$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_supp."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';

					$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_supp."'".')"><i class="fa fa-trash-alt"></i></i></button>';

				}else{

					$btnEdit = '';
					$btnHapus = '';
				}
				

				// $row[] = ($cekProduk == 0) ? $btnEdit.' '.$btnHapus : $btnEdit ;
				$row[] = $btnEdit.' '.$btnHapus ;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "user") {
			if($this->session->userdata('level') == 'PPIC'){
				$where = "WHERE u.level='Corrugator' OR u.level='Flexo' OR u.level='Finishing'";
			}else{
				$where = "";
			}
			$query = $this->m_master->query("SELECT * FROM tb_user u $where ORDER BY u.id")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();

				$row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->username . "'" . ',' . "'detail'" . ')">' . $r->username . "<a>";
				$row[] = $r->nm_user;
				$row[] = base64_decode($r->password);
				$row[] = $r->level;

				if($this->session->userdata('level') == 'Admin' || $this->session->userdata('level') == 'PPIC'){
					if ($r->level == 'Admin') {
						$aksi = '<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->username . "'" . ',' . "'edit'" . ')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>
						';
					}else{
						$aksi = '<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->username . "'" . ',' . "'edit'" . ')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>

						<a href="javascript:void(0)" onclick="deleteData(' . "'" . $r->username . "'" . ')" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm"><i class="fas fa-times"></i>';
					}
				}else{
					$aksi = '-';
				}

				$row[] = $aksi;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "user_level") {
			
			$query = $this->m_master->query("SELECT * FROM m_modul_group ORDER BY id_group")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();

				$row[] = '<div class="text-center">'.$r->id_group.'</div>';
				$row[] = $r->nm_group;

				$row[] = '<div class="text-center">
				<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->val_group . "'" . ',' . "'edit'" . ')" class="btn btn-warning btn-sm">
				<i class="fas fa-edit"></i>
				</a>

				<a class="btn btn-sm btn-primary" href="'. base_url("Master/User_level_add?val_group=" . $r->id_group . "") .'" title="EDIT MENU" ><b>
				<i class="fas fa-search"></i> MENU</b></a>

				</div>
				';

				$data[] = $row;
				$i++;
			}
		}



		$output = array(
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function hapus()
	{
		$jenis   = $_POST['jenis'];
		$field   = $_POST['field'];
		$id = $_POST['id'];

		$result = $this->m_master->query("DELETE FROM $jenis WHERE  $field = '$id'");

		echo json_encode($result);
	}


	function get_edit()
	{
		$id    = $this->input->post('id');
		$jenis    = $this->input->post('jenis');
		$field    = $this->input->post('field');

		$data =  $this->m_master->get_data_one($jenis, $field, $id)->row();
		echo json_encode($data);
	}

	function getPlhCustomer()
	{
		$data = $this->db->query("SELECT s.nm_sales,p.* FROM m_pelanggan p
		LEFT JOIN m_sales s ON p.id_sales=s.id_sales
		ORDER BY p.nm_pelanggan")->result();
		echo json_encode($data);
	}

	function getPlhSales()
	{
		$data = $this->db->query("SELECT*FROM m_sales ORDER BY nm_sales")->result();
		echo json_encode($data);
	}

	function getEditProduk()
	{
		$id = $_POST["id"];
		$data = $this->db->query("SELECT s.nm_sales,c.nm_pelanggan AS customer,c.kode_unik,p.* FROM m_produk p
		INNER JOIN m_pelanggan c ON p.no_customer=c.id_pelanggan
		LEFT JOIN m_sales s ON c.id_sales=s.id_sales
		WHERE p.id_produk='$id'")->row();
		$pelanggan = $this->db->query("SELECT*FROM m_pelanggan pel
		LEFT JOIN m_provinsi prov ON pel.prov=prov.prov_id
		LEFT JOIN m_kab kab ON pel.kab=kab.kab_id
		LEFT JOIN m_kec kec ON pel.kec=kec.kec_id
		LEFT JOIN m_kel kel ON pel.kel=kel.kel_id
		LEFT JOIN m_sales sal ON pel.id_sales=sal.id_sales
		ORDER BY id_pelanggan")->result();
		$id_pelanggan = $data->no_customer;
		// $wilayah = $this->db->query("SELECT prov.prov_name,kab.kab_name,kec.kec_name,kel.kel_name,pel.* FROM m_pelanggan pel
		// LEFT JOIN m_provinsi prov ON pel.prov=prov.prov_id
		// LEFT JOIN m_kab kab ON pel.kab=kab.kab_id
		// LEFT JOIN m_kec kec ON pel.kec=kec.kec_id
		// LEFT JOIN m_kel kel ON pel.kel=kel.kel_id
		// WHERE pel.id_pelanggan='$id_pelanggan'")->row();
		$id_produk = $data->id_produk;
		$poDetail = $this->db->query("SELECT*FROM trs_po_detail WHERE id_produk='$id_produk'")->result();
		echo json_encode(array(
			'produk' => $data,
			'pelanggan' => $pelanggan,
			// 'wilayah' => $wilayah,
			'poDetail' => $poDetail,
		));
	}

	function buatKodeMC(){
		$result = $this->m_master->buatKodeMC();
		echo json_encode($result);
	}

	function edit_supp()
	{
		$id   = $_POST["id"];
		$data = $this->db->query("SELECT* FROM m_supplier WHERE id_supp='$id'")->row();
		
		// $cekPO = $this->db->query("SELECT p.id_pelanggan FROM m_customer p
		// INNER JOIN trs_po o ON p.id_pelanggan=o.id_pelanggan
		// WHERE p.id_pelanggan='$id'
		// GROUP BY p.id_pelanggan")->num_rows();
		echo json_encode(array(
			'supp' => $data,
			// 'cek_po' => $cekPO,
		));
	}
	
	function edit_cs()
	{
		$id   = $_POST["id"];
		$data = $this->db->query("SELECT* FROM m_customer WHERE id_cs='$id'")->row();
		
		// $cekPO = $this->db->query("SELECT p.id_pelanggan FROM m_customer p
		// INNER JOIN trs_po o ON p.id_pelanggan=o.id_pelanggan
		// WHERE p.id_pelanggan='$id'
		// GROUP BY p.id_pelanggan")->num_rows();
		echo json_encode(array(
			'cs' => $data,
			// 'cek_po' => $cekPO,
		));
	}
	
	function getEditPelanggan()
	{
		$id = $_POST["id"];
		$data =  $this->db->query("SELECT s.nm_sales,p.* FROM m_pelanggan p
		INNER JOIN m_sales s ON p.id_sales=s.id_sales
		WHERE p.id_pelanggan='$id'")->row();
		$prov = $this->db->query("SELECT*FROM m_provinsi")->result();
		$sales = $this->db->query("SELECT*FROM m_sales ORDER BY nm_sales")->result();
		$wilayah = $this->db->query("SELECT prov.prov_name,kab.kab_name,kec.kec_name,kel.kel_name,pel.* FROM m_pelanggan pel
		LEFT JOIN m_provinsi prov ON pel.prov=prov.prov_id
		LEFT JOIN m_kab kab ON pel.kab=kab.kab_id
		LEFT JOIN m_kec kec ON pel.kec=kec.kec_id
		LEFT JOIN m_kel kel ON pel.kel=kel.kel_id
		WHERE pel.id_pelanggan='$id'")->row();
		$cekPO = $this->db->query("SELECT p.id_pelanggan FROM m_pelanggan p
		INNER JOIN trs_po o ON p.id_pelanggan=o.id_pelanggan
		WHERE p.id_pelanggan='$id'
		GROUP BY p.id_pelanggan")->num_rows();
		echo json_encode(array(
			'pelanggan' => $data,
			'prov' => $prov,
			'sales' => $sales,
			'wilayah' => $wilayah,
			'cek_po' => $cekPO,
		));
	}
}
