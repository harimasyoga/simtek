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
			'judul' => "Master Barang",
			'approve' => $this->session->userdata('approve'),
		);
		$this->load->view('header', $data);
		if(in_array($this->session->userdata('approve'), ['ALL', 'OFFICE', 'GUDANG'])) {
			$this->load->view('Master/v_barang', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}

	function Satuan()
	{
		$data = array(
			'judul' => "Master Satuan"
		);
		$this->load->view('header', $data);
		if(in_array($this->session->userdata('approve'), ['ALL', 'OFFICE', 'GUDANG'])) {
			$this->load->view('Master/v_satuan', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}

	// BARANG

	function loadDataBarang()
	{
		$data = array();
		$approve = $this->session->userdata('approve');
		$query = $this->db->query("SELECT*FROM m_barang_header WHERE kategori='$approve' ORDER BY nm_barang ASC")->result();
			$i = 0;
			foreach ($query as $r) {
				$i++;
				$row = array();
				$row[] = $r->kode_header;
				$row[] = $r->nm_barang;
				$row[] = '<div class="text-center">
					<button type="button" class="btn btn-info btn-sm" onclick="viewBarang('."'".$r->id_mbh."'".')"><i class="fas fa-search"></i></button>
				</div>';
				$data[] = $row;
			}
		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function viewBarang()
	{
		$id_mbh = $_POST["id_mbh"];
		$id_mbd = $_POST["id_mbd"];
		$html = '';
		if($id_mbh != ''){
			$html .='<table class="table table-bordered table-striped" style="margin-top:20px">';
				$html .='<tr>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS/TIPE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">SATUAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">AKSI</th>
				</tr>
				<tr>
					<td style="padding:0;border:0" colspan="9"></td>
				</tr>';
				$header = $this->db->query("SELECT*FROM m_barang_header WHERE id_mbh='$id_mbh'")->row();
				$detail = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
				INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
				WHERE h.id_mbh='$id_mbh'
				ORDER BY d.kode_barang,h.nm_barang,d.jenis_tipe,d.material,d.size,d.merk,d.p_satuan");
				$i = 0;
				foreach($detail->result() as $r){
					$i++;
					($r->id_mbd == $id_mbd) ? $style = ';font-weight:bold;background:#ffc107;border:1px solid #e5ad06' : $style = '';
					// SATUAN
					if($r->p_satuan == 1){
						$htmlSat = '<td style="padding:6px'.$style.'">TERKECIL</td>
						<td style="padding:6px;text-align:right'.$style.'">'.number_format($r->qty3,0,',','.').'</td>
						<td style="padding:6px'.$style.'">'.$r->satuan3.'</td>';
					}
					if($r->p_satuan == 2){
						$htmlSat = '<td style="padding:6px'.$style.'">TERBESAR<br>TERKECIL</td>
						<td style="padding:6px;text-align:right'.$style.'">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
						<td style="padding:6px'.$style.'">'.$r->satuan1.'<br>'.$r->satuan3.'</td>';
					}
					if($r->p_satuan == 3){
						$htmlSat = '<td style="padding:6px'.$style.'">TERBESAR<br>TENGAH<br>TERKECIL</td>
						<td style="padding:6px;text-align:right'.$style.'">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty2,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
						<td style="padding:6px'.$style.'">'.$r->satuan1.'<br>'.$r->satuan2.'<br>'.$r->satuan3.'</td>';
					}
					// AKSI
					if($r->id_mbd == $id_mbd){
						$aksi = '-';
					}else{
						$aksi = '<button type="button" class="btn btn-sm" onclick="editBarang('."'".$r->id_mbd."'".')"><i class="fas fa-edit"></i></button>
						<button type="button" class="btn btn-sm" onclick="hapusBarang('."'".$r->id_mbd."'".')"><i class="fas fa-times-circle" style="color:#f00"></i></button>';
					}
					$html .= '<tr>
						<td style="padding:6px'.$style.'">'.$r->kode_barang.'</td>
						<td style="padding:6px'.$style.'">'.$r->nm_barang.'</td>
						<td style="padding:6px'.$style.'">'.$r->jenis_tipe.'</td>
						<td style="padding:6px'.$style.'">'.$r->material.'</td>
						<td style="padding:6px'.$style.'">'.$r->size.'</td>
						<td style="padding:6px'.$style.'">'.$r->merk.'</td>
						'.$htmlSat.'
						<td style="padding:6px;text-align:center'.$style.'">
							'.$aksi.'
						</td>
					</tr>';
					if($detail->num_rows() != $i){
						$html .= '<tr>
							<td style="padding:2px;border:0" colspan="9"></td>
						</tr>';
					}
				}
			$html .= '</table>';
		}else{
			$header = '';
		}


		echo json_encode([
			'header' => $header,
			'html' => $html,
		]);
	}

	function editBarang()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $cKet = "" : $cKet = "WHERE kategori='$kategori'";
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$id_mbd = $_POST["id_mbd"];
		$html_h = '';
		$html_jt = '';
		$html_m = '';
		$html_s = '';
		$html_mr = '';
		// HEADER
		if($id_mbd == ''){
			$header = $this->db->query("SELECT*FROM m_barang_header $cKet ORDER BY nm_barang");
			$html_h .= '<option value="">PILIH</option>';
			foreach($header->result() as $h){
				$html_h .= '<option value="'.$h->id_mbh.'">'.$h->nm_barang.'</option>';
			}
			$html_h .= '<option value="+">+</option>';
		}
		// DETAIL
		$detail = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbd='$id_mbd' $wKet");
		if($id_mbd != ''){
			// JENIS / TIPE
			$jenis_tipe = $this->db->query("SELECT*FROM m_barang_detail $cKet GROUP BY jenis_tipe");
			$html_jt .= '<option value="'.$detail->row()->jenis_tipe.'">'.$detail->row()->jenis_tipe.'</option><option value="">PILIH</option>';
			foreach($jenis_tipe->result() as $jt){
				$html_jt .= '<option value="'.$jt->jenis_tipe.'">'.$jt->jenis_tipe.'</option>';
			}
			$html_jt .= '<option value="+">+</option>';
			// MATERIAL
			$material = $this->db->query("SELECT*FROM m_barang_detail $cKet GROUP BY material");
			$html_m .= '<option value="'.$detail->row()->material.'">'.$detail->row()->material.'</option><option value="">PILIH</option>';
			foreach($material->result() as $m){
				$html_m .= '<option value="'.$m->material.'">'.$m->material.'</option>';
			}
			$html_m .= '<option value="+">+</option>';
			// SIZE
			$size = $this->db->query("SELECT*FROM m_barang_detail d $cKet GROUP BY d.size");
			$html_s .= '<option value="'.$detail->row()->size.'">'.$detail->row()->size.'</option><option value="">PILIH</option>';
			foreach($size->result() as $s){
				$html_s .= '<option value="'.$s->size.'">'.$s->size.'</option>';
			}
			$html_s .= '<option value="+">+</option>';
			// MERK
			$merk = $this->db->query("SELECT*FROM m_barang_detail $cKet GROUP BY merk");
			$html_mr .= '<option value="'.$detail->row()->merk.'">'.$detail->row()->merk.'</option><option value="">PILIH</option>';
			foreach($merk->result() as $mr){
				$html_mr .= '<option value="'.$mr->merk.'">'.$mr->merk.'</option>';
			}
			$html_mr .= '<option value="+">+</option>';
		}

		echo json_encode([
			'header' => $html_h,
			'detail' => ($detail->num_rows() != 0) ? $detail->row() : '',
			'jenis_tipe' => $html_jt,
			'material' => $html_m,
			'size' => $html_s,
			'merk' => $html_mr,
		]);
	}

	function cekNamaBarang()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$n_barang = $_POST["n_barang"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_barang)));
		$cekBarang = $this->db->query("SELECT*FROM m_barang_header WHERE nm_barang='$cleanTxt' $wKet");
		if($n_barang == '' || $cleanTxt == ''){
			$data = false; $msg = 'NAMA BARANG BARU TIDAK BOLEH KOSONG!';
		}else if(!preg_match("/^[A-Z ]*$/", $cleanTxt)){
			$data = false; $msg = 'NAMA BARANG HANYA BOLEH HURUF!';
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

	function loadJenisTipe()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $cKet = "" : $cKet = "WHERE kategori='$kategori'";
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		if($cari == 'jenis_tipe'){
			$cekJenisTipe = $this->db->query("SELECT*FROM m_barang_detail $cKet GROUP BY jenis_tipe");
		}else{
			$cekJenisTipe = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' $wKet GROUP BY jenis_tipe");
		}
		$html = '';
		if($cekJenisTipe->num_rows() != 0){
			$data = true;
			$html .='<option value="">PILIH</option>';
			foreach($cekJenisTipe->result() as $r){
				$html .='<option value="'.$r->jenis_tipe.'">'.$r->jenis_tipe.'</option>';
			}
			$html .='<option value="+">+</option>';
		}else{
			$data = false;
			$html .= '<option value="">PILIH</option><option value="+">+</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekJenisTipe()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$id_mbh = $_POST["id_mbh"];
		$n_jenis_tipe = $_POST["n_jenis_tipe"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_jenis_tipe)));
		if($id_mbh == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE jenis_tipe='$cleanTxt' $wKet");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$cleanTxt' $wKet");
		}
		if(!preg_match("/^[A-Z0-9 .-]*$/", $cleanTxt)){
			$data = false; $msg = 'JENIS / TIPE BARU HANYA BOLEH HURUF ATAU ANGKA!';
		}else if($cekBarang->num_rows() != 0 || $cleanTxt == '+'){
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
		$kategori = $_POST["kategori"];
		($kategori == '') ? $cKet = "" : $cKet = "WHERE kategori='$kategori'";
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$status = $_POST["status"];
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		if($cari == 'material' || $id_mbh == '+' || $jenis_tipe == '-' || $status == 'update'){
			$cekMaterial = $this->db->query("SELECT*FROM m_barang_detail $cKet GROUP BY material");
		}else{
			$cekMaterial = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' $wKet GROUP BY material");
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
			$html .= '<option value="">PILIH</option><option value="+">+</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekMaterial()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$n_material = $_POST["n_material"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_material)));
		if($id_mbh == '+' || $i_jenis_tipe == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE material='$cleanTxt' $wKet");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$cleanTxt' $wKet");
		}
		if(!preg_match("/^[A-Z0-9 ]*$/", $cleanTxt)){
			$data = false; $msg = 'MATERIAL BARU HANYA BOLEH HURUF ATAU ANGKA!';
		}else if($cekBarang->num_rows() != 0 || $cleanTxt == '+'){
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
		$kategori = $_POST["kategori"];
		($kategori == '') ? $cKet = "" : $cKet = "WHERE kategori='$kategori'";
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$status = $_POST["status"];
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		$material = $_POST["material"];
		if($cari == 'size' || $id_mbh == '+' || $jenis_tipe == '+' || $material == '-' || $status == 'update'){
			$cekSize = $this->db->query("SELECT*FROM m_barang_detail s $cKet GROUP BY s.size");
		}else{
			$cekSize = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' AND material='$material' $wKet GROUP BY s.size");
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
			$html .= '<option value="">PILIH</option><option value="+">+</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekSize()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$i_material = $_POST["i_material"];
		$n_size = $_POST["n_size"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_size)));
		if($id_mbh == '+' || $i_jenis_tipe == '+' || $i_material == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE s.size='$cleanTxt' $wKet");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$i_material' AND s.size='$cleanTxt' $wKet");
		}
		if(!preg_match("/^[A-Z0-9 .-]*$/", $cleanTxt)){
			$data = false; $msg = 'SIZE BARU HANYA BOLEH HURUF ATAU ANGKA!';
		}else if($cekBarang->num_rows() != 0 || $cleanTxt == '+'){
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
		$kategori = $_POST["kategori"];
		($kategori == '') ? $cKet = "" : $cKet = "WHERE kategori='$kategori'";
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$status = $_POST["status"];
		$cari = $_POST["cari"];
		$id_mbh = $_POST["barang"];
		$jenis_tipe = $_POST["jenis_tipe"];
		$material = $_POST["material"];
		$size = $_POST["size"];
		if($cari == 'merk' || $id_mbh == '+' || $jenis_tipe == '+' || $material == '+' || $size == '-' || $status == 'update'){
			$cekMerk = $this->db->query("SELECT*FROM m_barang_detail s $cKet GROUP BY merk");
		}else{
			$cekMerk = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$jenis_tipe' AND material='$material' AND s.size='$size' $wKet GROUP BY merk");
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
			$html .= '<option value="">PILIH</option><option value="+">+</option>';
		}

		echo json_encode([
			'data' => $data,
			'html' => $html,
		]);
	}

	function cekMerk()
	{
		$kategori = $_POST["kategori"];
		($kategori == '') ? $wKet = "" : $wKet = "AND kategori='$kategori'";
		$id_mbh = $_POST["id_mbh"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$i_material = $_POST["i_material"];
		$i_size = $_POST["i_size"];
		$n_merk = $_POST["n_merk"];
		$cleanTxt = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $n_merk)));
		if($id_mbh == '+' || $i_jenis_tipe == '+' || $i_material == '+' || $i_size == '+'){
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE merk='$cleanTxt' $wKet");
		}else{
			$cekBarang = $this->db->query("SELECT*FROM m_barang_detail s WHERE id_mbh='$id_mbh' AND jenis_tipe='$i_jenis_tipe' AND material='$i_material' AND s.size='$i_size' AND merk='$cleanTxt' $wKet");
		}
		if(!preg_match("/^[A-Z0-9 ]*$/", $cleanTxt)){
			$data = false; $msg = 'SIZE BARU HANYA BOLEH HURUF ATAU ANGKA!';
		}else if($cekBarang->num_rows() != 0 || $cleanTxt == '+'){
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

	function addBarang()
	{
		$id_mbd = $_POST["id_mbd"];
		$i_kategori = $_POST["i_kategori"];
		$i_barang = $_POST["i_barang"];
		$n_barang = $_POST["n_barang"];
		$i_jenis_tipe = $_POST["i_jenis_tipe"];
		$n_jenis_tipe = $_POST["n_jenis_tipe"];
		$i_material = $_POST["i_material"];
		$n_material = $_POST["n_material"];
		$i_size = $_POST["i_size"];
		$n_size = $_POST["n_size"];
		$i_merk = $_POST["i_merk"];
		$n_merk = $_POST["n_merk"];
		$pilih_satuan = $_POST["pilih_satuan"];
		$satuan_terbesar = $_POST["satuan_terbesar"];
		$p_satuan_terbesar = $_POST["p_satuan_terbesar"];
		$satuan_tengah = $_POST["satuan_tengah"];
		$p_satuan_tengah = $_POST["p_satuan_tengah"];
		$satuan_terkecil = $_POST["satuan_terkecil"];
		$p_satuan_terkecil = $_POST["p_satuan_terkecil"];
		$status = $_POST["status"];

		if($i_kategori == ''){
			echo json_encode(['data' => false, 'isi' => 'HARAP PILIH KATEGORI!']); return;
		}
		if(($satuan_terkecil == 0 || $satuan_terkecil == '') && $pilih_satuan == 1){
			echo json_encode(['data' => false, 'isi' => 'HARAP ISI SATUAN!']); return;
		}
		if($p_satuan_terkecil == '' && $pilih_satuan == 1){
			echo json_encode(['data' => false, 'isi' => 'HARAP PILIH SATUAN!']); return;
		}
		if(($satuan_terbesar == 0 || $satuan_terbesar == '' || $satuan_terkecil == 0 || $satuan_terkecil == '') && $pilih_satuan == 2){
			echo json_encode(['data' => false, 'isi' => 'HARAP ISI SATUAN!']); return;
		}
		if(($p_satuan_terbesar == '' || $p_satuan_terkecil == '') && $pilih_satuan == 2){
			echo json_encode(['data' => false, 'isi' => 'HARAP PILIH SATUAN!']); return;
		}
		if(($satuan_terbesar == 0 || $satuan_terbesar == '' || $satuan_tengah == 0 || $satuan_tengah == '' || $satuan_terkecil == 0 || $satuan_terkecil == '') && $pilih_satuan == 3){
			echo json_encode(['data' => false, 'isi' => 'HARAP ISI SATUAN!']); return;
		}
		if(($p_satuan_terbesar == '' || $p_satuan_tengah == '' || $p_satuan_terkecil == '') && $pilih_satuan == 3){
			echo json_encode(['data' => false, 'isi' => 'HARAP PILIH SATUAN!']); return;
		}
		if($pilih_satuan == 2 && ($p_satuan_terbesar == $p_satuan_terkecil)){
			echo json_encode(['data' => false, 'isi' => 'SATUAN TIDAK BOLEH SAMA!']); return;
		}
		if($pilih_satuan == 3 && ($p_satuan_terbesar == $p_satuan_tengah || $p_satuan_terbesar == $p_satuan_terkecil || $p_satuan_tengah == $p_satuan_terkecil)){
			echo json_encode(['data' => false, 'isi' => 'SATUAN TIDAK BOLEH SAMA!']); return;
		}

		// HEADER
		$kode_barang = '';
		// KATEGORI
		if($i_kategori == 'OFFICE'){
			$kdKat = 'K.';
		}else if($i_kategori == 'GUDANG'){
			$kdKat = 'G.';
		}
		if($i_barang == '+'){
			$nm_barang = $n_barang;
			// KODE NAMA BARANG BARU
			$arr = explode(' ', $n_barang);
			$kode = '';
			foreach($arr as $kata) { $kode .= substr($kata, 0, 1); }
			$cek = $this->db->query("SELECT*FROM m_barang_header WHERE kode_header LIKE '$kdKat$kode-%'");
			if($cek->num_rows() != 0){
				$lastKode = $this->db->query("SELECT*FROM m_barang_header WHERE kode_header LIKE '$kdKat$kode-%' ORDER BY kode_header DESC LIMIT 1")->row();
				$plus = str_pad(preg_replace("/[^0-9]/", "", $lastKode->kode_header)+1, 2, "0", STR_PAD_LEFT);
				$kode_barang .= preg_replace("/[^A-Z.]/", "", $cek->row()->kode_header).'-'.$plus;
			}else{
				$kode_barang .= $kdKat.$kode.'-01';
			}
		}else{
			$nm = $this->db->query("SELECT*FROM m_barang_header WHERE id_mbh='$i_barang'")->row();
			$nm_barang = $nm->nm_barang;
			$kode_barang .= $nm->kode_header;
		}
		$kode_header = $kode_barang;
		// DETAIL
		($i_jenis_tipe == '+') ? $jenis_tipe = $n_jenis_tipe : $jenis_tipe = $i_jenis_tipe;
		($i_material == '+') ? $material = $n_material : $material = $i_material;
		($i_size == '+') ? $size = $n_size : $size = $i_size;
		($i_merk == '+') ? $merk = $n_merk : $merk = $i_merk;
		// KODE TAHUN
		$kode_barang .= '/'.substr(date('Y'),2,2);
		// KODE JENIS / TIPE
		$jt = explode(' ', $jenis_tipe);
		$txtJT = '';
		foreach($jt as $kataJT) { $txtJT .= substr($kataJT, 0, 1); }
		($jenis_tipe == '' || $jenis_tipe == '-') ? $kode_barang .= '' : $kode_barang .= '/'.$txtJT;
		// KODE MATERIAL
		$m = explode(' ', $material);
		$txtM = '';
		foreach($m as $kataM) { $txtM .= substr($kataM, 0, 1); }
		($material == '' || $material == '-') ? $kode_barang .= '' : $kode_barang .= '/'.$txtM;
		// KODE MERK
		$mr = explode(' ', $merk);
		$txtMr = '';
		foreach($mr as $kataMr) { $txtMr .= substr($kataMr, 0, 1); }
		($merk == '' || $merk == '-') ? $kode_barang .= '' : $kode_barang .= '/'.$txtMr;

		// KODE URUT
		$cekKode = $this->db->query("SELECT*FROM m_barang_detail WHERE kode_barang LIKE '$kode_barang-%'");
		$lastDtl = $this->db->query("SELECT*FROM m_barang_detail WHERE kode_barang LIKE '$kode_barang-%' ORDER BY kode_barang DESC LIMIT 1");
		if($this->cart->total_items() != 0){
			foreach($this->cart->contents() as $r){
				if($kode_barang == $r['options']['kode_barang']){
					$kd = $r['options']['no_urut']+1;
				}else{
					($cekKode->num_rows() != 0) ? $kd = str_pad(substr($lastDtl->row()->kode_barang, -3), 3, "0", STR_PAD_LEFT) : $kd = 0;
				}
			}
		}else{
			($cekKode->num_rows() != 0) ? $kd = str_pad(substr($lastDtl->row()->kode_barang, -3), 3, "0", STR_PAD_LEFT) : $kd = 0;
		}
		if($cekKode->num_rows() != 0){
			$pdtl = str_pad($kd+1, 3, "0", STR_PAD_LEFT);
			$kode_urut = $pdtl;
			$no_urut = $kd;
		}else{
			$kode_urut = str_pad($kd+1, 3, "0", STR_PAD_LEFT);
			$no_urut = $kd;
		}

		// CEK DATA BARANG SUDAH DI INPUT
		if($i_barang == '+'){
			$whIdMbh = "AND h.nm_barang='$nm_barang'";
		}else{
			$whIdMbh = "AND h.id_mbh='$i_barang'";
		}
		// SATUAN
		if($status == 'update'){
			$c = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbd='$id_mbd'")->row();
			if($c->p_satuan == 1 && ($c->qty3 != $satuan_terkecil || $c->satuan3 != $p_satuan_terkecil)){
				$whSatuan = "AND d.p_satuan='$pilih_satuan' AND d.qty3='$satuan_terkecil' AND d.satuan3='$p_satuan_terkecil'";
			}else if($c->p_satuan == 2 && ($c->qty1 != $satuan_terbesar || $c->satuan1 != $p_satuan_terbesar || $c->qty3 != $satuan_terkecil || $c->satuan3 != $p_satuan_terkecil)){
				$whSatuan = "AND d.p_satuan='$pilih_satuan' AND d.qty1='$satuan_terbesar' AND d.satuan1='$p_satuan_terbesar' AND d.qty3='$satuan_terkecil' AND d.satuan3='$p_satuan_terkecil'";
			}else if($c->p_satuan == 3 && ($c->qty1 != $satuan_terbesar || $c->satuan1 != $p_satuan_terbesar || $c->qty2 != $satuan_tengah || $c->satuan2 != $p_satuan_tengah || $c->qty3 != $satuan_terkecil || $c->satuan3 != $p_satuan_terkecil)){
				$whSatuan = "AND d.p_satuan='$pilih_satuan' AND d.qty1='$satuan_terbesar' AND d.satuan1='$p_satuan_terbesar' AND d.qty2='$satuan_tengah' AND d.satuan2='$p_satuan_tengah' AND d.qty3='$satuan_terkecil' AND d.satuan3='$p_satuan_terkecil'";
			}else{
				$whSatuan = "";
			}
		}else{
			$whSatuan = "AND d.p_satuan='$pilih_satuan'";
		}
		$cekData = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
		INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
		WHERE d.jenis_tipe='$jenis_tipe' AND d.material='$material' AND d.size='$size' AND d.merk='$merk' $whIdMbh $whSatuan");
		if($cekData->num_rows() != 0){
			echo json_encode(['data' => false, 'isi' => 'DATA SUDAH ADA!']); return;
		}

		// GET KODE BARANG UPDATE
		if($status == 'insert'){
			$detail = '';
		}else{
			$ckd = explode('-', $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbd='$id_mbd'")->row()->kode_barang);
			$detail = $ckd[0].'-'.$ckd[1];
		}

		$data = array(
			'id' => $_POST["id_cart"],
			'name' => 'bb_'.$_POST["id_cart"],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'no_urut' => $no_urut,
				'id_mbh' => $i_barang,
				'id_mbd' => $id_mbd,
				'i_kategori' => $i_kategori,
				'nm_barang' => $nm_barang,
				'kode_header' => $kode_header,
				'detail' => $detail,
				'kode_barang' => $kode_barang,
				'kode_urut' => $kode_urut,
				'i_jenis_tipe' => $i_jenis_tipe,
				'n_jenis_tipe' => $n_jenis_tipe,
				'i_material' => $i_material,
				'n_material' => $n_material,
				'i_size' => $i_size,
				'n_size' => $n_size,
				'i_merk' => $i_merk,
				'n_merk' => $n_merk,
				'jenis_tipe' => $jenis_tipe,
				'material' => $material,
				'size' => $size,
				'merk' => $merk,
				'pilih_satuan' => $pilih_satuan,
				'satuan_terbesar' => $satuan_terbesar,
				'p_satuan_terbesar' => $p_satuan_terbesar,
				'satuan_tengah' => $satuan_tengah,
				'p_satuan_tengah' => $p_satuan_tengah,
				'satuan_terkecil' => $satuan_terkecil,
				'p_satuan_terkecil' => $p_satuan_terkecil,
			)
		);

		if($status == 'insert'){
			if($this->cart->total_items() != 0){
				foreach($this->cart->contents() as $r){
					// CEK KODE BARANG
					$kode_baru = $kode_barang.'-'.$kode_urut;
					$kode_lama = $r['options']['kode_barang'].'-'.$r['options']['kode_urut'];
					if($kode_baru == $kode_lama){
						echo json_encode(array('data' => false, 'isi' => 'KODE BARANG SUDAH TERPAKAI!')); return;
					}
					// CEK INPUTAN
					if($nm_barang == $r['options']['nm_barang'] && $jenis_tipe == $r['options']['jenis_tipe'] && $material == $r['options']['material'] && $size == $r['options']['size'] && $merk == $r['options']['merk'] && $pilih_satuan == $r['options']['pilih_satuan']){
						echo json_encode(array('data' => false, 'isi' => 'DATA SUDAH MASUK DI LIST!')); return;
					}
				}
				$this->cart->insert($data);
				echo json_encode(array('data' => true, 'isi' => $data));
			}else{
				$this->cart->insert($data);
				echo json_encode(array('data' => true, 'isi' => $data));
			}
		}else{
			$result = $this->m_master->editBarang($data);
			echo json_encode($result);
		}
	}

	function destroy()
	{
		$this->cart->destroy();
	}

	function hapusCart()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function simpanBarang()
	{
		$result = $this->m_master->simpanBarang();
		echo json_encode($result);
	}

	function hapusBarang()
	{
		$result = $this->m_master->hapusBarang();
		echo json_encode($result);
	}

	function cartBarang()
	{
		$html = '';
		if($this->cart->total_items() == 0){
			$html .= '';
		}
		if($this->cart->total_items() != 0){
			$html .='<table class="table table-bordered table-striped" style="margin-top:20px">';
				$html .='<tr>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS/TIPE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="2">SATUAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">AKSI</th>
				</tr>
				<tr>
					<td style="padding:0;border:0" colspan="9"></td>
				</tr>';
				$i = 0;
				foreach($this->cart->contents() as $r){
					$i++;
					($r['options']['jenis_tipe'] == '') ? $jenis_tipe = '-' : $jenis_tipe = $r['options']['jenis_tipe'];
					($r['options']['material'] == '') ? $material = '-' : $material = $r['options']['material'];
					($r['options']['size'] == '') ? $size = '-' : $size = $r['options']['size'];
					($r['options']['merk'] == '') ? $merk = '-' : $merk = $r['options']['merk'];
					// SATUAN
					if($r['options']['pilih_satuan'] == 1){
						$htmlSat = '<td style="padding:6px">TERKECIL</td>
						<td style="padding:6px">'.$r['options']['satuan_terkecil'].' '.$r['options']['p_satuan_terkecil'].'</td>';
					}
					if($r['options']['pilih_satuan'] == 2){
						$htmlSat = '<td style="padding:6px">TERBESAR<br>TERKECIL</td>
						<td style="padding:6px">'.$r['options']['satuan_terbesar'].' '.$r['options']['p_satuan_terbesar'].'<br>'.$r['options']['satuan_terkecil'].' '.$r['options']['p_satuan_terkecil'].'</td>';
					}
					if($r['options']['pilih_satuan'] == 3){
						$htmlSat = '<td style="padding:6px">TERBESAR<br>TENGAH<br>TERKECIL</td>
						<td style="padding:6px">'.$r['options']['satuan_terbesar'].' '.$r['options']['p_satuan_terbesar'].'<br>'.$r['options']['satuan_tengah'].' '.$r['options']['p_satuan_tengah'].'<br>'.$r['options']['satuan_terkecil'].' '.$r['options']['p_satuan_terkecil'].'</td>';
					}
					$html .= '<tr>
						<td style="padding:6px">'.$r['options']['kode_barang'].'-'.$r['options']['kode_urut'].'</td>
						<td style="padding:6px">'.$r['options']['nm_barang'].'</td>
						<td style="padding:6px">'.$jenis_tipe.'</td>
						<td style="padding:6px">'.$material.'</td>
						<td style="padding:6px">'.$size.'</td>
						<td style="padding:6px">'.$merk.'</td>
						'.$htmlSat.'
						<td style="padding:6px;text-align:center">
							<button type="button" class="btn btn-sm" onclick="hapusCart('."'".$r['rowid']."'".')"><i class="fas fa-times-circle" style="color:#f00"></i></button>
						</td>
					</tr>';
					if($this->cart->total_items() != $i){
						$html .= '<tr>
							<td style="padding:2px;border:0" colspan="9"></td>
						</tr>';
					}
				}
				$html .= '<tr>
					<td style="padding:6px;text-align:right" colspan="9">
						<button type="button" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpanBarang()"><i class="fas fa-save"></i> SIMPAN</button>
					</td>
				</tr>';
			$html .='</table>';
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	// SATUAN

	function loadDataSatuan()
	{
		$data = array();
		$query = $this->m_master->query("SELECT*FROM m_satuan ORDER BY kode_satuan ASC")->result();
			$i = 0;
			foreach ($query as $r) {
				$i++;
				$row = array();
				($r->ket_satuan == '' || $r->ket_satuan == '-') ? $ket = '' : $ket = ' <span style="font-style:italic;font-size:12px;vertical-align:top">( '.$r->ket_satuan.' )</span>';
				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = $r->kode_satuan.$ket;
				// CEK BARANG
				$cek1 = $this->db->query("SELECT*FROM m_barang_detail WHERE satuan1='$r->kode_satuan' GROUP BY satuan1");
				$cek2 = $this->db->query("SELECT*FROM m_barang_detail WHERE satuan2='$r->kode_satuan' GROUP BY satuan2");
				$cek3 = $this->db->query("SELECT*FROM m_barang_detail WHERE satuan3='$r->kode_satuan' GROUP BY satuan3");
				if($cek1->num_rows() == 0 && $cek2->num_rows() == 0 && $cek3->num_rows() == 0){
					$btnAksi = `<button type="button" class="btn btn-warning btn-sm" onclick="editSatuan('."'".$r->id."'".')"><i class="fas fa-edit"></i></button>
					<button type="button" class="btn btn-danger btn-sm" onclick="hapusSatuan('."'".$r->id."'".')"><i class="fas fa-trash-alt" style="color:#000"></i></button>`;
				}else{
					$btnAksi = '';
				}
				$row[] = '<div class="text-center">'.$btnAksi.'</div>';
				$data[] = $row;
			}
		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function simpanSatuan()
	{
		$result = $this->m_master->simpanSatuan();
		echo json_encode($result);
	}

	function editSatuan()
	{
		$id = $_POST["id"];
		$data = $this->db->query("SELECT*FROM m_satuan WHERE id='$id'")->row();
		echo json_encode([
			'satuan' => $data,
		]);
	}

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
		if(in_array($this->session->userdata('approve'), ['ALL'])) {
			$this->load->view('Master/v_user', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}

	function User_level()
	{
		$data = array(
			'judul' => "Master User Level"
		);

		$this->load->view('header', $data);
		if(in_array($this->session->userdata('approve'), ['ALL'])) {
			$this->load->view('Master/v_user_level', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}
	
	function User_level_add()
	{
		$val_group    = $_GET['val_group'];
		$nama         = $this->db->query("SELECT*FROM m_modul_group a WHERE id_group='$val_group' LIMIT 1 ")->row();
		$query = $this->db->query("SELECT*FROM m_modul a order by kode")->result();
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

	function UserBagian()
	{
		$val_group = $_GET['val_group'];
		$nama = $this->db->query("SELECT*FROM m_modul_group WHERE id_group='$val_group' LIMIT 1 ")->row();
		$query = $this->db->query("SELECT*FROM m_departemen ORDER BY kode")->result();
		$data = array(
			'judul' => "Edit Departemen Bagian",
			'judul2' => "$nama->nm_group",
			'id' => $val_group,
			'query' => $query
		);
		$this->load->view('header', $data);
		$this->load->view('Master/v_user_bagian', $data);
		$this->load->view('footer');
	}

	function load_group()
	{
		$data = $this->db->query("SELECT * FROM m_modul_group ORDER BY nm_group ")->result();
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

	function simpanBagian()
	{
		$result = $this->m_master->simpanBagian();
		echo json_encode($result);
	}

	function editUserLevel()
	{
		$id = $_POST["id"];
		$val = $_POST["val"];
		$cekUser = $this->db->query("SELECT*FROM tb_user u WHERE u.level='$val'")->num_rows();
		$level = $this->db->query("SELECT*FROM m_modul_group WHERE id_group='$id'")->row();

		echo json_encode([
			'level' => $level,
			'num' => $cekUser,
		]);
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
				if (in_array($this->session->userdata('approve'), ['ALL','OFFICE']))
				{
					$btnEdit = '<button type="button" class="btn btn-warning btn-sm" onclick="tampil_edit('."'".$r->id_supp."'".','."'edit'".')"><i class="fas fa-pen"></i></button>';
					$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData('."'".$r->id_supp."'".')"><i class="fa fa-trash-alt"></i></i></button>';
				}else{
					$btnEdit = '';
					$btnHapus = '';
				}
				$row[] = $btnEdit.' '.$btnHapus ;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "user") {
			$query = $this->db->query("SELECT * FROM tb_user u ORDER BY u.id")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<a href="javascript:void(0)" onclick="tampil_edit('."'".$r->username."'".','."'detail'".')">'.$r->username.'</a>';
				$row[] = $r->nm_user;
				$row[] = base64_decode($r->password);
				$row[] = $r->level;
				if($r->level == 'Developer' || $r->level == 'Owner'){
					$aksi = '<a href="javascript:void(0)" onclick="tampil_edit('."'".$r->username."'".','."'edit'".')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
				}else{
					$aksi = '<a href="javascript:void(0)" onclick="tampil_edit('."'".$r->username."'".','."'edit'".')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
					<a href="javascript:void(0)" onclick="deleteData('."'".$r->username."'".')" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>';
				}
				$row[] = $aksi;
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "user_level") {
			$query = $this->db->query("SELECT*FROM m_modul_group ORDER BY val_group")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = $r->nm_group;
				$row[] = $r->approve;
				// AKSI
				// $edit = '<a href="javascript:void(0)" onclick="editUserLevel('."'".$r->id_group."'".','."'".$r->val_group."'".')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
				$row[] = '<div class="text-center">
					<a class="btn btn-sm btn-primary" href="'.base_url("Master/User_level_add?val_group=".$r->id_group."").'" title="EDIT MENU" ><b><i class="fas fa-search"></i> MENU</b></a>
					<a class="btn btn-sm btn-danger" href="'.base_url("Master/UserBagian?val_group=".$r->id_group."").'" title="EDIT BAGIAN" ><b><i class="fas fa-search"></i> BAGIAN</b></a>
				</div>';
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
		$jenis = $_POST['jenis'];
		$field = $_POST['field'];
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
