<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qrcode extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_qrcode');
	}

	function index()
	{
		$approve = $this->session->userdata('approve');
		$code = $_GET["v"];
		$data = array(
			'judul' => "QR Code",
			'code' => $code,
		);
		$this->load->view('header', $data);
		if(in_array($approve, ['ALL', 'ACC', 'OFFICE', 'FINANCE', 'GUDANG', 'OWNER'])){
			$this->load->view('Qrcode/v_qrcode', $data);
		}else{
			$this->session->sess_destroy();
			redirect(base_url('login'));
		}
		$this->load->view('footer');
	}

	function prosesSPB()
	{
		$result = $this->m_qrcode->prosesSPB();
		echo json_encode($result);
	}

	function hapusdSPB()
	{
		$result = $this->m_qrcode->hapusdSPB();
		echo json_encode($result);
	}

	function simpandSPB()
	{
		$result = $this->m_qrcode->simpandSPB();
		echo json_encode($result);
	}

	function loadBarang()
	{
		// GET DATA QRCODE
		$qrcode = $_POST["qrcode"];
		$qr = $this->db->query("SELECT*FROM m_qrcode WHERE qrcode_data='$qrcode'")->row();
		// GET DATA BAPB
		$bapb = $this->db->query("SELECT*FROM trs_bapb WHERE id_bapb='$qr->id_bapb'")->row();
		// SUPPLIER, DEPARTEMEN,  BAGIAN BAPB
		$bsup = $this->db->query("SELECT*FROM m_supplier WHERE id_supp='$bapb->bid_supplier'")->row();
		$bkdpt = $this->db->query("SELECT*FROM m_departemen WHERE kode='$bapb->bkode_dpt'")->row();
		$bkbagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$bapb->bkode_bagian'")->row();
		// GET DATA OPB
		$opbh = $this->db->query("SELECT*FROM trs_opb_header WHERE id_opbh='$bapb->id_opbh'")->row();
		$opbd = $this->db->query("SELECT*FROM trs_opb_detail WHERE id_opbh='$bapb->id_opbh' AND id_opbd='$bapb->id_opbd'")->row();
		// GET DATA BARANG OPB
		$b_opbd = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
		INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
		WHERE d.id_mbd='$opbd->id_mbd' AND d.id_mbh='$opbd->id_mbh'")->row();
		// SUPPLIER, DEPARTEMEN, BAGIAN OPB
		$sup = $this->db->query("SELECT*FROM m_supplier WHERE id_supp='$opbd->id_supplier'")->row();
		$kdpt = $this->db->query("SELECT*FROM m_departemen WHERE kode='$opbd->kode_dpt'")->row();
		$kbagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$opbd->kode_bagian'")->row();
		$htmlOpb = '';
		$htmlOpb .= '<table style="margin:0;padding:0;border:0">
			<tr>
				<th style="background:#e2e2e2;border-bottom:3px solid #828282;padding:6px" colspan="5">DATA BARANG OPB</th>
				<th style="border:1px solid #dee2e6;padding:2px" rowspan="14"></th>
				<th style="background:#e2e2e2;border-bottom:3px solid #828282;padding:6px" colspan="5">DATA OPB</th>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">KODE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->kode_barang.'</td>
				<td style="padding:6px;font-weight:bold">HARI, TGL</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.substr($this->m_fungsi->haru($opbh->tgl_opb),0,3).', '.$this->m_fungsi->tglIndSkt($opbh->tgl_opb).'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">NAMA</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->nm_barang.'</td>
				<td style="padding:6px;font-weight:bold">NO. OPB</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$opbh->no_opb.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">JENIS / TIPE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->jenis_tipe.'</td>
				<td style="padding:6px;font-weight:bold">DEPARTEMEN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$kdpt->nama.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">MATERIAL</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->material.'</td>
				<td style="padding:6px;font-weight:bold">BAGIAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$kbagian->nama.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">SIZE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->size.'</td>
				<td style="padding:6px;font-weight:bold">SUPPLIER</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$sup->nm_supp.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">MERK</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$b_opbd->merk.'</td>
				<td style="padding:6px;font-weight:bold">HARGA</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.number_format($opbd->dharga,0,',','.').'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>';
			// SATUAN BARANG OPB
			if($b_opbd->p_satuan == 1){
				$bo1 = 'TERKECIL';
				$bo2 = round($b_opbd->qty3,2);
				$bo3 = $b_opbd->satuan3;
			}
			if($b_opbd->p_satuan == 2){
				$bo1 = 'TERBESAR<br>TERKECIL';
				$bo2 = round($b_opbd->qty1,2).'<br>'.round($b_opbd->qty3,2);
				$bo3 = $b_opbd->satuan1.'<br>'.$b_opbd->satuan3;
			}
			if($b_opbd->p_satuan == 3){
				$bo1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$bo2 = round($b_opbd->qty1,2).'<br>'.round($b_opbd->qty2,2).'<br>'.round($b_opbd->qty3,2);
				$bo3 = $b_opbd->satuan1.'<br>'.$b_opbd->satuan2.'<br>'.$b_opbd->satuan3.'';
			}
			// SATUAN PENGADAAN OPB
			if($opbd->p_satuan == 1){
				$to1 = 'TERKECIL';
				$to2 = round($opbd->dqty3,2);
				$to3 = $opbd->dsatuan3;
			}
			if($opbd->p_satuan == 2){
				$to1 = 'TERBESAR<br>TERKECIL';
				$to2 = round($opbd->dqty1,2).'<br>'.round($opbd->dqty3,2);
				$to3 = $opbd->dsatuan1.'<br>'.$opbd->dsatuan3;
			}
			if($opbd->p_satuan == 3){
				$to1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$to2 = round($opbd->dqty1,2).'<br>'.round($opbd->dqty2,2).'<br>'.round($opbd->dqty3,2);
				$to3 = $opbd->dsatuan1.'<br>'.$opbd->dsatuan2.'<br>'.$opbd->dsatuan3.'';
			}
			$htmlOpb .= '<tr style="background:#f2f2f2;border:1px solid #dee2e6;vertical-align:top">
				<td style="padding:6px;font-weight:bold">SATUAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px">'.$bo1.'</td>
				<td style="padding:6px;text-align:right">'.$bo2.'</td>
				<td style="padding:6px">'.$bo3.'</td>
				<td style="padding:6px;font-weight:bold">PENGADAAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px">'.$to1.'</td>
				<td style="padding:6px;text-align:right">'.$to2.'</td>
				<td style="padding:6px">'.$to3.'</td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr>
				<td style="padding:6px" colspan="6"></td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 0 1px 1px;padding:6px;font-weight:bold">KETERANGAN</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 0;padding:6px 0;font-weight:bold">:</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 1px 1px 0;padding:6px" colspan="3">'.$opbd->ket_pengadaan.'</td>
			</tr>';
		$htmlOpb .= '</table>';

		// GET DATA BARANG BAPB
		$barang = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
		INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
		WHERE d.id_mbd='$bapb->id_mbd' AND d.id_mbh='$bapb->id_mbh'")->row();
		$htmlBapb = '';
		$htmlBapb .= '<table style="margin:0;padding:0;border:0">
			<tr>
				<th style="background:#e2e2e2;border-bottom:3px solid #828282;padding:6px" colspan="5">DATA BARANG BAPB</th>
				<th style="border:1px solid #dee2e6;padding:2px" rowspan="14"></th>
				<th style="background:#e2e2e2;border-bottom:3px solid #828282;padding:6px" colspan="5">DATA BAPB</th>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">KODE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->kode_barang.'</td>
				<td style="padding:6px;font-weight:bold">HARI, TGL</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.substr($this->m_fungsi->haru($bapb->tgl_bapb),0,3).', '.$this->m_fungsi->tglIndSkt($bapb->tgl_bapb).'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">NAMA</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->nm_barang.'</td>
				<td style="padding:6px;font-weight:bold">NO. OPB</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$bapb->no_opb.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">JENIS / TIPE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->jenis_tipe.'</td>
				<td style="padding:6px;font-weight:bold">DEPARTEMEN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$bkdpt->nama.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">MATERIAL</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->material.'</td>
				<td style="padding:6px;font-weight:bold">BAGIAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$bkbagian->nama.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">SIZE</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->size.'</td>
				<td style="padding:6px;font-weight:bold">SUPPLIER</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$bsup->nm_supp.'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr style="background:#f2f2f2;border:1px solid #dee2e6">
				<td style="padding:6px;font-weight:bold">MERK</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.$barang->merk.'</td>
				<td style="padding:6px;font-weight:bold">HARGA</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px" colspan="3">'.number_format($bapb->bharga,0,',','.').'</td>
			</tr>
			<tr>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>';
			// SATUAN BAPB BARANG
			if($barang->p_satuan == 1){
				$td1 = 'TERKECIL';
				$td2 = round($barang->qty3,2);
				$td3 = $barang->satuan3;
			}
			if($barang->p_satuan == 2){
				$td1 = 'TERBESAR<br>TERKECIL';
				$td2 = round($barang->qty1,2).'<br>'.round($barang->qty3,2);
				$td3 = $barang->satuan1.'<br>'.$barang->satuan3;
			}
			if($barang->p_satuan == 3){
				$td1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$td2 = round($barang->qty1,2).'<br>'.round($barang->qty2,2).'<br>'.round($barang->qty3,2);
				$td3 = $barang->satuan1.'<br>'.$barang->satuan2.'<br>'.$barang->satuan3.'';
			}
			// SATUAN PENERIMAAN BAPB BARANG
			if($bapb->b_satuan == 1){
				$bp1 = '<span>TERKECIL</span>';
				$bp2 = '<span>'.round($bapb->bqty3,2).'</span>';
				$bp3 = '<span>'.$bapb->bsatuan3.'</span>';
				$st = array('TERKECIL');
			}
			if($bapb->b_satuan == 2){
				if($bapb->bsatuan == 'TERBESAR'){
					$p1 = 'style="color:#f00"'; $p3 = '';
				}
				if($bapb->bsatuan == 'TERKECIL'){
					$p1 = ''; $p3 = 'style="color:#f00"';
				}
				$bp1 = '<span '.$p1.'>TERBESAR</span><br><span '.$p3.'>TERKECIL</span>';
				$bp2 = '<span '.$p1.'>'.round($bapb->bqty1,2).'</span><br><span '.$p3.'>'.round($bapb->bqty3,2).'</span>';
				$bp3 = '<span '.$p1.'>'.$bapb->bsatuan1.'</span><br><span '.$p3.'>'.$bapb->bsatuan3.'</span>';
				$st = array('TERKECIL', 'TERBESAR');
			}
			if($bapb->b_satuan == 3){
				if($bapb->bsatuan == 'TERBESAR'){
					$p1 = 'style="color:#f00"'; $p2 = ''; $p3 = '';
				}
				if($bapb->bsatuan == 'TENGAH'){
					$p1 = ''; $p2 = 'style="color:#f00"'; $p3 = '';
				}
				if($bapb->bsatuan == 'TERKECIL'){
					$p1 = ''; $p2 = ''; $p3 = 'style="color:#f00"';
				}
				$bp1 = '<span '.$p1.'>TERBESAR</span><br><span '.$p2.'>TENGAH</span><br><span '.$p3.'>TERKECIL</span>';
				$bp2 = '<span '.$p1.'>'.round($bapb->bqty1,2).'</span><br><span '.$p2.'>'.round($bapb->bqty2,2).'</span><br><span '.$p3.'>'.round($bapb->bqty3,2).'</span>';
				$bp3 = '<span '.$p1.'>'.$bapb->bsatuan1.'</span><br><span '.$p2.'>'.$bapb->bsatuan2.'</span><br><span '.$p3.'>'.$bapb->bsatuan3.'</span>';
				$st = array('TERKECIL', 'TENGAH', 'TERBESAR');
			}
			($bapb->bket_pengadaan == null || $bapb->bket_pengadaan == '') ? $ketbap = '-' : $ketbap = $bapb->bket_pengadaan;
			$htmlBapb .='<tr style="background:#f2f2f2;border:1px solid #dee2e6;vertical-align:top">
				<td style="padding:6px;font-weight:bold">SATUAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px">'.$td1.'</td>
				<td style="padding:6px;text-align:right">'.$td2.'</td>
				<td style="padding:6px">'.$td3.'</td>
				<td style="padding:6px;font-weight:bold">PENERIMAAN</td>
				<td style="padding:6px 0;font-weight:bold">:</td>
				<td style="padding:6px">'.$bp1.'</td>
				<td style="padding:6px;text-align:right">'.$bp2.'</td>
				<td style="padding:6px">'.$bp3.'</td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td style="border:1px solid #dee2e6;padding:2px" colspan="11"></td>
			</tr>
			<tr>
				<td style="padding:6px" colspan="6"></td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 0 1px 1px;padding:6px;font-weight:bold">KETERANGAN</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 0;padding:6px 0;font-weight:bold">:</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;border-width:1px 1px 1px 0;padding:6px" colspan="3">'.$ketbap.'</td>
			</tr>';
		$htmlBapb .= '</table>';

		// STOK
		$stok = $this->db->query("SELECT*FROM m_stok WHERE id_bapb='$qr->id_bapb'")->row();
		// SATUAN PENERIMAAN BAPB BARANG
		if($stok->s_satuan == 1){
			$ss1 = 'TERKECIL';
			$ss2 = round($stok->sqty3,2);
			$ss22 = '0';
			$ss3 = $stok->ssatuan3;
		}
		if($stok->s_satuan == 2){
			$ss1 = 'TERBESAR<br>TERKECIL';
			$ss2 = round($stok->sqty1,2).'<br>'.round($stok->sqty3,2);
			$ss22 = '0<br>0';
			$ss3 = $stok->ssatuan1.'<br>'.$stok->ssatuan3;
		}
		if($stok->s_satuan == 3){
			$ss1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
			$ss2 = round($stok->sqty1,2).'<br>'.round($stok->sqty2,2).'<br>'.round($stok->sqty3,2);
			$ss22 = '0<br>0<br>0';
			$ss3 = $stok->ssatuan1.'<br>'.$stok->ssatuan2.'<br>'.$stok->ssatuan3;
		}
		// PENGAMBILAN
		$ambil = $this->db->query("SELECT*FROM trs_spb_detail
		WHERE id_mbh='$stok->id_mbh' AND id_mbd='$stok->id_mbd'
		GROUP BY status_spd,tgl_spb,no_spb,creat_by");
		$htmlStok = '';
		$htmlStok .= '<table class="table table-bordered table-striped" style="margin:0">
			<tr>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px" colspan="3">
					<input type="hidden" id="id_stok" value="'.$stok->id_stok.'">
					STOK AWAL
				</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px" colspan="3">PENGAMBILAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">HARI, TGL</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NO. SPB</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KETERANGAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">DEPARTEMEN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">BAGIAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">PEMOHON</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">PEMBUAT</th>
			</tr>
			<tr>
				<td style="padding:0"></td>
			</tr>
			<tr style="vertical-align:top">
				<td style="padding:6px;font-weight:bold">'.$ss1.'</td>
				<td style="padding:6px;font-weight:bold;text-align:right">'.$ss2.'</td>
				<td style="padding:6px;font-weight:bold">'.$ss3.'</td>
			';
			if($ambil->num_rows() != 0){
				$n = 0;
				$sum1 = 0; $sum2 = 0; $sum3 = 0;
				foreach($ambil->result() as $a){
					$n++;
					if($n != 1){
						$htmlStok .= '<tr style="vertical-align:top"><td style="padding:6px" colspan="3"></td>';
					}
					// SATUAN PENERIMAAN BAPB BARANG
					if($a->x_satuan == 1){
						$xp1 = '<span>TERKECIL</span>';
						$xp2 = '<span>'.round($a->xqty3,2).'</span>';
						$xp3 = '<span>'.$a->xsatuan3.'</span>';
					}
					if($a->x_satuan == 2){
						if($a->xsatuan == 'TERBESAR'){
							$p1 = 'style="color:#f00"'; $p3 = '';
						}
						if($a->xsatuan == 'TERKECIL'){
							$p1 = ''; $p3 = 'style="color:#f00"';
						}
						$xp1 = '<span '.$p1.'>TERBESAR</span><br><span '.$p3.'>TERKECIL</span>';
						$xp2 = '<span '.$p1.'>'.round($a->xqty1,2).'</span><br><span '.$p3.'>'.round($a->xqty3,2).'</span>';
						$xp3 = '<span '.$p1.'>'.$a->xsatuan1.'</span><br><span '.$p3.'>'.$a->xsatuan3.'</span>';
					}
					if($a->x_satuan == 3){
						if($a->xsatuan == 'TERBESAR'){
							$p1 = 'style="color:#f00"'; $p2 = ''; $p3 = '';
						}
						if($a->xsatuan == 'TENGAH'){
							$p1 = ''; $p2 = 'style="color:#f00"'; $p3 = '';
						}
						if($a->xsatuan == 'TERKECIL'){
							$p1 = ''; $p2 = ''; $p3 = 'style="color:#f00"';
						}
						$xp1 = '<span '.$p1.'>TERBESAR</span><br><span '.$p2.'>TENGAH</span><br><span '.$p3.'>TERKECIL</span>';
						$xp2 = '<span '.$p1.'>'.round($a->xqty1,2).'</span><br><span '.$p2.'>'.round($a->xqty2,2).'</span><br><span '.$p3.'>'.round($a->xqty3,2).'</span>';
						$xp3 = '<span '.$p1.'>'.$a->xsatuan1.'</span><br><span '.$p2.'>'.$a->xsatuan2.'</span><br><span '.$p3.'>'.$a->xsatuan3.'</span>';
					}
					($a->no_spb == null) ? $no_opb = '-' : $no_opb = $a->no_spb;
					// DEPARTEMEN, BAGIAN
					$xkd_dpt = $this->db->query("SELECT*FROM m_departemen WHERE kode='$a->xkode_dpt'")->row();
					$xkd_bagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$a->xkode_bagian'")->row();
					// PEMOHON
					$spbh = $this->db->query("SELECT*FROM trs_spb_header WHERE no_spb='$a->no_spb'");
					($spbh->num_rows() == 0) ? $suhu = '-' : $suhu = $spbh->row()->pemohon_spb ;
					$htmlStok .= '<td style="padding:6px">'.$xp1.'</td>
						<td style="padding:6px;text-align:right">'.$xp2.'</td>
						<td style="padding:6px">'.$xp3.'</td>
						<td style="padding:6px">'.$this->m_fungsi->haru($a->tgl_spb).', '.$this->m_fungsi->tglIndSkt($a->tgl_spb).'</td>
						<td style="padding:6px">'.$no_opb.'</td>
						<td style="padding:6px">'.$a->xket.'</td>
						<td style="padding:6px">'.$xkd_dpt->nama.'</td>
						<td style="padding:6px">'.$xkd_bagian->nama.'</td>
						<td style="padding:6px">'.$suhu.'</td>
						<td style="padding:6px">'.$a->creat_by.'</td>
					</tr>
					<tr>
						<td style="padding:2px" colspan="13"></td>
					</tr>';
					$sum1 += ($a->xqty1 == null) ? 0 : round($a->xqty1,2);
					$sum2 += ($a->xqty2 == null) ? 0 : round($a->xqty2,2);
					$sum3 += ($a->xqty3 == null) ? 0 : round($a->xqty3,2);
				}
				// TOTAL
				$htmlStok .= '<tr>';
				if($stok->s_satuan == 1){
					$hitSum3 = round($sum3,2) - round($stok->sqty3,2);
					($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
					$htmlStok .= '<td style="padding:6px;font-weight:bold"></td>
						<td style="padding:6px;font-weight:bold;text-align:right">'.$thitSum3.'</td>
						<td style="padding:6px;font-weight:bold">'.$stok->ssatuan3.'</td>
						<td style="padding:6px;font-weight:bold">TERKECIL</td>
						<td style="padding:6px;font-weight:bold;text-align:right"><div>'.round($sum3,2).'</div></td>
						<td style="padding:6px;font-weight:bold">'.$stok->ssatuan3.'</td>';
				}
				if($stok->s_satuan == 2){
					$hitSum1 = round($sum1,2) - round($stok->sqty1,2);
					$hitSum3 = round($sum3,2) - round($stok->sqty3,2);
					($hitSum1 > 0) ? $thitSum1 = '+'.round($hitSum1,2) : $thitSum1 = round($hitSum1,2);
					($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
					$htmlStok .= '<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TERKECIL</div></td>
						<td style="padding:6px;font-weight:bold;text-align:right"><div>'.$thitSum1.'</div><div>'.$thitSum3.'</div></td>
						<td style="padding:6px;font-weight:bold"><div>'.$stok->ssatuan1.'</div><div>'.$stok->ssatuan3.'</div></td>
						<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TERKECIL</div></td>
						<td style="padding:6px;font-weight:bold;text-align:right"><div>'.round($sum1,2).'</div><div>'.round($sum3,2).'</div></td>
						<td style="padding:6px;font-weight:bold"><div>'.$stok->ssatuan1.'</div><div>'.$stok->ssatuan3.'</div></td>';
				}
				if($stok->s_satuan == 3){
					$hitSum1 = round($sum1,2) - round($stok->sqty1,2);
					$hitSum2 = round($sum2,2) - round($stok->sqty2,2);
					$hitSum3 = round($sum3,2) - round($stok->sqty3,2);
					($hitSum1 > 0) ? $thitSum1 = '+'.round($hitSum1,2) : $thitSum1 = round($hitSum1,2);
					($hitSum2 > 0) ? $thitSum2 = '+'.round($hitSum2,2) : $thitSum2 = round($hitSum2,2);
					($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
					$htmlStok .= '<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TENGAH</div><div>TERKECIL</div></td>
						<td style="padding:6px;font-weight:bold;text-align:right"><div>'.$thitSum1.'</div><div>'.$thitSum2.'</div><div>'.$thitSum3.'</div></td>
						<td style="padding:6px;font-weight:bold"><div>'.$stok->ssatuan1.'</div><div>'.$stok->ssatuan2.'</div><div>'.$stok->ssatuan3.'</div></td>
						<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TENGAH</div><div>TERKECIL</div></td>
						<td style="padding:6px;font-weight:bold;text-align:right"><div>'.round($sum1,2).'</div><div>'.round($sum2,2).'</div><div>'.round($sum3,2).'</div></td>
						<td style="padding:6px;font-weight:bold"><div>'.$stok->ssatuan1.'</div><div>'.$stok->ssatuan2.'</div><div>'.$stok->ssatuan3.'</div></td>';
				}
				$htmlStok .= '<td style="padding:6px" colspan="7"></td></tr>';
			}else{
				$htmlStok .= '<td style="padding:6px" colspan="10"></td></tr>';
			}
		$htmlStok .= '</table>';

		// INPUT SPB
		$htmlSpb = '';
		$htmlSpb .= '<table style="margin:0;padding:0;border:0">
			<tr>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 12px">PILIH SATUAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;text-align:center;padding:6px">QTY</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px" colspan="3">PENGAMBILAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 80px">KETERANGAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 50px">DEPARTEMEN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 80px">BAGIAN</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;text-align:center;padding:6px">
					<input type="hidden" id="h_satuan" value="'.$b_opbd->p_satuan.'">
					<input type="hidden" id="i_qty1_" value="">
					<input type="hidden" id="i_qty2_" value="">
					<input type="hidden" id="i_qty3_" value="">
					<input type="hidden" id="h_qty1_" value="'.round($b_opbd->qty1,2).'">
					<input type="hidden" id="h_qty2_" value="'.round($b_opbd->qty2,2).'">
					<input type="hidden" id="h_qty3_" value="'.round($b_opbd->qty3,2).'">
					<input type="hidden" id="h_satuan1_" value="'.$b_opbd->satuan1.'">
					<input type="hidden" id="h_satuan2_" value="'.$b_opbd->satuan2.'">
					<input type="hidden" id="h_satuan3_" value="'.$b_opbd->satuan3.'">
					AKSI
				</th>
			</tr>';
			// PILIH SATUAN DAN QTY
			$htmlPlhSatuan = '';
			foreach($st as $t){
				$htmlPlhSatuan .= '<option value="'.$t.'">'.$t.'</option>';
			}
			// DEPARTEMEN
			$dspb = $this->db->query("SELECT*FROM m_departemen WHERE main_menu='0'");
			$optDepartemen = '';
			$optDepartemen .= '<option value="">PILIH</option>';
			foreach($dspb->result() as $d){
				($bapb->bkode_dpt == $d->kode) ? $sld = 'selected' : $sld = '';
				$optDepartemen .= '<option value="'.$d->kode.'"'.$sld.'>'.$d->nama.'</option>';
			}
			$tdDepartemen = '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
				<select id="plh_departemen" class="form-control" style="padding:3px;width:100%" onchange="plhDepartemen()">
					'.$optDepartemen.'
				</select>
			</td>';
			// BAGIAN
			$bspb = $this->db->query("SELECT*FROM m_departemen WHERE main_menu='$bapb->bkode_dpt'");
			$optBagian = '';
			foreach($bspb->result() as $b){
				($bapb->bkode_bagian == $b->kode) ? $slb = 'selected' : $slb = '';
				$optBagian .= '<option value="'.$b->kode.'"'.$slb.'>'.$b->nama.'</option>';
			}
			$tdBagian = '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
				<select id="plh_bagian" class="form-control" style="padding:3px;width:100%">
					'.$optBagian.'
				</select>
			</td>';
			$htmlSpb .= '<tr style="vertical-align:top">
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
					<select id="plh_satuan" class="form-control" style="padding:3px;width:100%" onchange="pilihSatuan()">
						'.$htmlPlhSatuan.'
					</select>
				</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:center">
					<input type="number" id="qty" class="form-control" style="width:60px;padding:3px 4px;text-align:right" placeholder="0" onkeyup="pengadaaan()">
				</td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold"><div class="txtsatuan">'.$ss1.'</div></td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold;text-align:right"><div class="hitungqty">'.$ss22.'</div></td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold"><div class="ketsatuan">'.$ss3.'</div></td>
				<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
					<textarea id="ket_pengadaan" class="form-control" style="padding:3px 4px;resize:none" rows="2" placeholder="-" oninput="this.value=this.value.toUpperCase()"></textarea>
				</td>
				'.$tdDepartemen.'
				'.$tdBagian.'
				<td style="padding:6px;background:#f2f2f2;border:1px solid #dee2e6">
					<button type="button" class="btn btn-xs btn-success" onclick="prosesSPB()">proses</button>
				</td>
			</tr>';
		$htmlSpb .= '</table>';

		// LIST SPB
		$htmlLSpb = '';
		$tgl = date('Y-m-d');
		$username = $this->session->userdata('username');
		$spd = $this->db->query("SELECT h.nm_barang,d.*,s.* FROM trs_spb_detail s
		INNER JOIN m_barang_detail d ON s.id_mbh=d.id_mbh AND s.id_mbd=d.id_mbd
		INNER JOIN m_barang_header h ON s.id_mbh=h.id_mbh
		WHERE s.no_spb IS NULL AND s.id_spbh IS NULL AND s.status_spd='Open' AND s.tgl_spb='$tgl' AND s.creat_by='$username'
		ORDER BY d.kode_barang");
		if($spd->num_rows() != 0){
			$htmlLSpb .= '<table style="margin:0 0 12px;padding:0;border:0;font-weight:bold">
				<tr>
					<td style="padding:6px">TANGGAL</td>
					<td style="padding:6px">:</td>
					<td style="padding:6px">
						<input type="date" class="form-control" value="'.date('Y-m-d').'" disabled>
					</td>
				</tr>
				<tr>
					<td style="padding:6px">NO. SPB</td>
					<td style="padding:6px">:</td>
					<td style="padding:6px">
						<input type="text" class="form-control" style="font-weight:bold" value="AUTO" disabled>
					</td>
				</tr>
				<tr>
					<td style="padding:6px">PEMOHON</td>
					<td style="padding:6px">:</td>
					<td style="padding:6px">
						<input type="text" id="pemohon_spb" class="form-control" placeholder="NAMA PEMOHON" oninput="this.value=this.value.toUpperCase()">
					</td>
				</tr>
			</table>';
			$urut = $this->db->query("SELECT*FROM trs_spb_detail WHERE no_spb IS NULL AND id_spbh IS NULL AND status_spd='Open' AND tgl_spb='$tgl' AND creat_by='$username' GROUP BY xurut DESC LIMIT 1")->row();
			$htmlLSpb .= '<table class="table table-bordered table-striped">
				<tr>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">
						<input type="hidden" id="h_urut" value="'.$urut->xurut.'">#
					</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS / TIPE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px" colspan="3">PENGAMBILAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KETERANGAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">DEPARTEMEN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">BAGIAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">AKSI</th>
				</tr>
				<tr>
					<td style="padding:0" colspan="14"></td>
				</tr>';
				$i = 0;
				foreach($spd->result() as $r){
					$i++;
					// SATUAN PENERIMAAN BAPB BARANG
					if($r->x_satuan == 1){
						$xx1 = '<span style="color:#f00">TERKECIL</span>';
						$xx2 = '<span style="color:#f00">'.round($r->xqty3,2).'</span>';
						$xx3 = '<span style="color:#f00">'.$r->xsatuan3.'</span>';
					}
					if($r->x_satuan == 2){
						if($r->xsatuan == 'TERBESAR'){
							$px1 = 'style="color:#f00"'; $px3 = '';
						}
						if($r->xsatuan == 'TERKECIL'){
							$px1 = ''; $px3 = 'style="color:#f00"';
						}
						$xx1 = '<span '.$px1.'>TERBESAR</span><br><span '.$px3.'>TERKECIL</span>';
						$xx2 = '<span '.$px1.'>'.round($r->xqty1,2).'</span><br><span '.$px3.'>'.round($r->xqty3,2).'</span>';
						$xx3 = '<span '.$px1.'>'.$r->xsatuan1.'</span><br><span '.$px3.'>'.$r->xsatuan3.'</span>';
					}
					if($r->x_satuan == 3){
						if($r->xsatuan == 'TERBESAR'){
							$px1 = 'style="color:#f00"'; $px2 = ''; $px3 = '';
						}
						if($r->xsatuan == 'TENGAH'){
							$px1 = ''; $px2 = 'style="color:#f00"'; $px3 = '';
						}
						if($r->xsatuan == 'TERKECIL'){
							$px1 = ''; $px2 = ''; $px3 = 'style="color:#f00"';
						}
						$xx1 = '<span '.$px1.'>TERBESAR</span><br><span '.$px2.'>TENGAH</span><br><span '.$px3.'>TERKECIL</span>';
						$xx2 = '<span '.$px1.'>'.round($r->xqty1,2).'</span><br><span '.$px2.'>'.round($r->xqty2,2).'</span><br><span '.$px3.'>'.round($r->xqty3,2).'</span>';
						$xx3 = '<span '.$px1.'>'.$r->xsatuan1.'</span><br><span '.$px2.'>'.$r->xsatuan2.'</span><br><span '.$px3.'>'.$r->xsatuan3.'</span>';
					}
					// DEPARTEMEN, BAGIAN
					$xkdpt = $this->db->query("SELECT*FROM m_departemen WHERE kode='$r->xkode_dpt'")->row();
					$xkbagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$r->xkode_bagian'")->row();
					$htmlLSpb .= '<tr>
						<td style="padding:6px;text-align:center">'.$i.'</td>
						<td style="padding:6px">'.$r->kode_barang.'</td>
						<td style="padding:6px">'.$r->nm_barang.'</td>
						<td style="padding:6px">'.$r->jenis_tipe.'</td>
						<td style="padding:6px">'.$r->material.'</td>
						<td style="padding:6px">'.$r->size.'</td>
						<td style="padding:6px">'.$r->merk.'</td>
						<td style="padding:6px">'.$xx1.'</td>
						<td style="padding:6px;text-align:right">'.$xx2.'</td>
						<td style="padding:6px">'.$xx3.'</td>
						<td style="padding:6px">'.$r->xket.'</td>
						<td style="padding:6px">'.$xkdpt->nama.'</td>
						<td style="padding:6px">'.$xkbagian->nama.'</td>
						<td style="padding:6px;text-align:center">
							<button type="button" class="btn btn-sm" onclick="hapusdSPB('.$r->id_spbd.')"><i class="fas fa-times-circle" style="color:#f00"></i></button>
						</td>
					</tr>';
					if($spd->num_rows() != $i){
						$htmlLSpb .= '<tr>
							<td style="padding:3px" colspan="14"></td>
						</tr>';
					}
				}
				$htmlLSpb .= '<tr>
					<td style="padding:6px;text-align:right" colspan="14">
						<button type="button" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpandSPB()"><i class="fas fa-save"></i> SIMPAN</button>
					</td>
				</tr>';
			$htmlLSpb .= '</table>';
		}else{
			$htmlLSpb .= 'LIST OPB KOSONG!';
		}

		echo json_encode([
			'qrcode' => $qrcode,
			'htmlOpb' => $htmlOpb,
			'htmlBapb' => $htmlBapb,
			'htmlStok' => $htmlStok,
			'htmlSpb' => $htmlSpb,
			'htmlLSpb' => $htmlLSpb,
		]);
	}

	function plhDepartemen()
	{
		$kode_dpt = $_POST["plh_departemen"];
		$bagian = $this->db->query("SELECT*FROM m_departemen WHERE main_menu='$kode_dpt'");
		$htmlBagian = '';
		$htmlBagian .= '<option value="">PILIH</option>';
		if($kode_dpt != ''){
			foreach($bagian->result() as $b){
				$htmlBagian .= '<option value="'.$b->kode.'">'.$b->nama.'</option>';
			}
		}
		echo json_encode([
			'htmlBagian' => $htmlBagian,
		]);
	}
}
