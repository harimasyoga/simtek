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
	function Produk()
	{

		$data = array(
			'judul' => "Master Produk",
			'pelanggan' => $this->m_master->get_data("m_pelanggan")->result()
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_produk', $data);
		$this->load->view('footer');
	}

	function Pelanggan()
	{
		$data = array(
			'judul' => "Master Pelanggan"
		);

		$this->load->view('header', $data);
		$this->load->view('Master/v_pelanggan', $data);
		$this->load->view('footer');
	}

	function Sales()
	{
		$data = array(
			'judul' => "Master Sales"
		);
		$this->load->view('header', $data);
		$this->load->view('Master/v_sales', $data);
		$this->load->view('footer');
	}

	function plhWilayah(){
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

		if ($jenis == "pelanggan") {
			$query = $this->m_master->query("SELECT prov.prov_name,kab.kab_name,kec.kec_name,kel.kel_name,les.nm_sales,pel.* FROM m_pelanggan pel
			LEFT JOIN m_provinsi prov ON pel.prov=prov.prov_id
			LEFT JOIN m_kab kab ON pel.kab=kab.kab_id
			LEFT JOIN m_kec kec ON pel.kec=kec.kec_id
			LEFT JOIN m_kel kel ON pel.kel=kel.kel_id
			LEFT JOIN m_sales les ON pel.id_sales=les.id_sales
			ORDER BY pel.nm_pelanggan")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampil_edit('."'".$r->id_pelanggan."'".','."'detail'".')">'.$i."<a></div>";
				$row[] = $r->nm_pelanggan;
				$row[] = $r->alamat_kirim;
				$row[] = ($r->nm_sales == 0) ? '-' : $r->nm_sales;
				$row[] = ($r->top == "") ? '-' : $r->top;

				$idPelanggan = $r->id_pelanggan;
				$cekProduk = $this->db->query("SELECT * FROM m_produk WHERE no_customer='$idPelanggan'")->num_rows();

				if (in_array($this->session->userdata('level'), ['Admin','User']))
				{
					$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_pelanggan."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';
					$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_pelanggan."'".')"><i class="fas fa-times"></i></button>';

				}else{

					$btnEdit = '';
					$btnHapus = '';
				}
				

				$row[] = ($cekProduk == 0) ? $btnEdit.' '.$btnHapus : $btnEdit ;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "produk") {
			$query = $this->m_master->query("SELECT c.nm_pelanggan,p.* FROM m_produk p INNER JOIN m_pelanggan c ON p.no_customer=c.id_pelanggan ORDER BY nm_produk")->result();
			$i = 1;
			foreach ($query as $r) {
				
				if( $r->kategori =='K_SHEET' )
				{
					$kategori='SHEET';
				}else{
					$kategori='BOX';
				}

				$row = array();
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampil_edit('."'".$r->id_produk."'".','."'detail'".')">'.$i."<a></div>";
				$row[] = $r->nm_pelanggan;
				$row[] = $kategori;
				$row[] = $r->nm_produk;
				$row[] = $r->kode_mc;
				$row[] = $r->flute;

				$expKualitas = explode("/", $r->kualitas);
				if($r->flute == 'BCF'){
					$kualitas = $expKualitas[0].' - '.$expKualitas[1].' - '.$expKualitas[2].' - '.$expKualitas[3].' - '.$expKualitas[4];
					if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
						$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
					}else{
						$kualitas = $r->kualitas;
					}
				}else{
					$kualitas = $r->kualitas;
				}
				$row[] = $kualitas;

				$idProduk = $r->id_produk; 
				if (in_array($this->session->userdata('level'), ['Admin','User']))
				{
					$cekPO = $this->db->query("SELECT * FROM trs_po_detail WHERE id_produk='$idProduk'")->num_rows();

					$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_produk."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';

					$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_produk."'".')"><i class="fas fa-times"></i></button>';
				}else{
					$cekPO       = '';
					$btnEdit     = '';
					$btnHapus    = '';
				}

				($cekPO == 0) ? $btnAksi = $btnEdit.' '.$btnHapus : $btnAksi = $btnEdit;
				$row[] = '<div class="text-center">'.$btnAksi.'</div>';
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "sales") {
			$query = $this->m_master->query("SELECT * FROM m_sales ORDER BY nm_sales")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<a href="javascript:void(0)" onclick="tampil_edit('."'".$r->id_sales."'".','."'detail'".')">'.$i."<a>";
				$row[] = $r->nm_sales;
				$row[] = $r->no_sales;

				$idSales = $r->id_sales;
				$cekPO = $this->db->query("SELECT COUNT(c.id_sales) AS jmlSales FROM trs_po p INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
				WHERE c.id_sales='$idSales' GROUP BY c.id_sales")->num_rows();
				$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_sales."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';
				$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_sales."'".')"><i class="fas fa-times"></i></button>';
				$row[] = ($cekPO == 0) ? $btnEdit.' '.$btnHapus : $btnEdit;
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
