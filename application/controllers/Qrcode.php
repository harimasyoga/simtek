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
		$bkbagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$bapb->bkode_bagian	'")->row();
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
				$bo2 = number_format($b_opbd->qty3,0,',','.');
				$bo3 = $b_opbd->satuan3;
			}
			if($b_opbd->p_satuan == 2){
				$bo1 = 'TERBESAR<br>TERKECIL';
				$bo2 = number_format($b_opbd->qty1,0,',','.').'<br>'.number_format($b_opbd->qty3,0,',','.');
				$bo3 = $b_opbd->satuan1.'<br>'.$b_opbd->satuan3;
			}
			if($b_opbd->p_satuan == 3){
				$bo1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$bo2 = number_format($b_opbd->qty1,0,',','.').'<br>'.number_format($b_opbd->qty2,0,',','.').'<br>'.number_format($b_opbd->qty3,0,',','.');
				$bo3 = $b_opbd->satuan1.'<br>'.$b_opbd->satuan2.'<br>'.$b_opbd->satuan3.'';
			}
			// SATUAN PENGADAAN OPB
			if($opbd->p_satuan == 1){
				$to1 = 'TERKECIL';
				$to2 = number_format($opbd->dqty3,0,',','.');
				$to3 = $opbd->dsatuan3;
			}
			if($opbd->p_satuan == 2){
				$to1 = 'TERBESAR<br>TERKECIL';
				$to2 = number_format($opbd->dqty1,0,',','.').'<br>'.number_format($opbd->dqty3,0,',','.');
				$to3 = $opbd->dsatuan1.'<br>'.$opbd->dsatuan3;
			}
			if($opbd->p_satuan == 3){
				$to1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$to2 = number_format($opbd->dqty1,0,',','.').'<br>'.number_format($opbd->dqty2,0,',','.').'<br>'.number_format($opbd->dqty3,0,',','.');
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
		';
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
				$td2 = number_format($barang->qty3,0,',','.');
				$td3 = $barang->satuan3;
			}
			if($barang->p_satuan == 2){
				$td1 = 'TERBESAR<br>TERKECIL';
				$td2 = number_format($barang->qty1,0,',','.').'<br>'.number_format($barang->qty3,0,',','.');
				$td3 = $barang->satuan1.'<br>'.$barang->satuan3;
			}
			if($barang->p_satuan == 3){
				$td1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$td2 = number_format($barang->qty1,0,',','.').'<br>'.number_format($barang->qty2,0,',','.').'<br>'.number_format($barang->qty3,0,',','.');
				$td3 = $barang->satuan1.'<br>'.$barang->satuan2.'<br>'.$barang->satuan3.'';
			}
			// SATUAN PENERIMAAN BAPB BARANG
			if($bapb->b_satuan == 1){
				$bp1 = 'TERKECIL';
				$bp2 = number_format($bapb->bqty3,0,',','.');
				$bp3 = $bapb->bsatuan3;
			}
			if($bapb->b_satuan == 2){
				$bp1 = 'TERBESAR<br>TERKECIL';
				$bp2 = number_format($bapb->bqty1,0,',','.').'<br>'.number_format($bapb->bqty3,0,',','.');
				$bp3 = $bapb->bsatuan1.'<br>'.$bapb->bsatuan3;
			}
			if($bapb->b_satuan == 3){
				$bp1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
				$bp2 = number_format($bapb->bqty1,0,',','.').'<br>'.number_format($bapb->bqty2,0,',','.').'<br>'.number_format($bapb->bqty3,0,',','.');
				$bp3 = $bapb->bsatuan1.'<br>'.$bapb->bsatuan2.'<br>'.$bapb->bsatuan3.'';
			}
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
		';
		$htmlBapb .= '</table>';

		$htmlSpb = '';
		$htmlSpb .= '<table class="table table-bordered table-striped" style="margin:0">
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">PILIH SATUAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">QTY</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px" colspan="3">PENGAMBILAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KETERANGAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">BAGIAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">AKSI</th>
		';
		$htmlSpb .= '</table>';

		echo json_encode([
			'qrcode' => $qrcode,
			'htmlOpb' => $htmlOpb,
			'htmlBapb' => $htmlBapb,
			'htmlSpb' => $htmlSpb,
		]);
	}
}
