<?php
class M_qrcode extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->username = $this->session->userdata('username');
	}

	function prosesSPB()
	{
		$username = $this->username;
		$id_stok = $_POST["id_stok"];
		$plh_satuan = $_POST["plh_satuan"];
		$qty = $_POST["qty"];
		$i_qty1 = $_POST["i_qty1"];
		$i_qty2 = $_POST["i_qty2"];
		$i_qty3 = $_POST["i_qty3"];
		$ket_pengadaan = $_POST["ket_pengadaan"];
		$plh_departemen = $_POST["plh_departemen"];
		$plh_bagian = $_POST["plh_bagian"];
		// CEK
		$tgl = date('Y-m-d');
		$spd = $this->db->query("SELECT*FROM trs_spb_detail WHERE no_spb IS NULL AND id_spbh IS NULL AND status_spd='Open' AND tgl_spb='$tgl' AND id_stok='$id_stok' AND creat_by='$username'");
		if($qty == '' || $qty == 0 || $qty < 0 || !preg_match("/^[0-9.]*$/", $qty)){
			$data = false; $msg = 'HARAP ISI QTY!';
		}else if($plh_departemen == ''){
			$data = false; $msg = 'HARAP PILIH DEPARTEMEN!';
		}else if($plh_bagian == ''){
			$data = false; $msg = 'HARAP PILIH BAGIAN!';
		}else if($spd->num_rows() != 0){
			$data = false; $msg = 'DATA SUDAH ADA!';
		}else{
			// STOK
			$stok = $this->db->query("SELECT*FROM m_stok WHERE id_stok='$id_stok'")->row();
			$spbd = $this->db->query("SELECT SUM(xqty3) AS qty FROM trs_spb_detail WHERE id_stok='$id_stok' GROUP BY id_mbh,id_mbd");
			($spbd->num_rows() == 0) ? $qq = 0 : $qq = $spbd->row()->qty;
			$stok3 = $stok->sqty3 - $qq;
			$barang = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$stok->id_mbh' AND id_mbd='$stok->id_mbd'")->row();
			// SATUAN
			if($barang->p_satuan == 1){
				$qty1 = null; $qty2 = null; $qty3 = $i_qty3;
				$satuan1 = null; $satuan2 = null; $satuan3 = $barang->satuan3;
			}
			if($barang->p_satuan == 2){
				$qty1 = $i_qty1; $qty2 = null; $qty3 = $i_qty3;
				$satuan1 = $barang->satuan1; $satuan2 = null; $satuan3 = $barang->satuan3;
			}
			if($barang->p_satuan == 3){
				$qty1 = $i_qty1; $qty2 = $i_qty2; $qty3 = $i_qty3;
				$satuan1 = $barang->satuan1; $satuan2 = $barang->satuan2; $satuan3 = $barang->satuan3;
			}
			if($qty3 > $stok3){
				$data = false; $msg = 'QTY MELEBIHI STOK!';
			}else{
				// CEK URUT
				$username = $this->username;
				$cek = $this->db->query("SELECT*FROM trs_spb_detail WHERE no_spb IS NOT NULL AND id_spbh IS NOT NULL AND status_spd='Close' AND tgl_spb='$tgl' AND creat_by='$username' GROUP BY creat_by,xurut DESC LIMIT 1");
				if($cek->num_rows() == 0){
					$urut = 1;
				}else{
					$urut = $cek->row()->xurut + 1;
				}
				$spd = [
					'tgl_spb' => $tgl,
					'no_spb' => null,
					'id_spbh' => null,
					'id_stok' => $id_stok,
					'id_mbh' => $stok->id_mbh,
					'id_mbd' => $stok->id_mbd,
					'status_spd' => 'Open',
					'xkode_dpt' => $plh_departemen,
					'xkode_bagian' => $plh_bagian,
					'x_satuan' => $stok->s_satuan,
					'xsatuan' => $plh_satuan,
					'xqty1' => $qty1,
					'xsatuan1' => $satuan1,
					'xqty2' => $qty2,
					'xsatuan2' => $satuan2,
					'xqty3' => $qty3,
					'xsatuan3' => $satuan3,
					'xket' => ($ket_pengadaan == '') ? '-' : $ket_pengadaan,
					'xurut' => $urut,
					'creat_by' => $this->username,
					'creat_at' => date('Y-m-d H:i:s'),
				];
				$data = $this->db->insert('trs_spb_detail', $spd);
				$msg = 'OK!';
			}
		}
		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

	function hapusdSPB()
	{
		$id_spbd = $_POST["id_spbd"];
		// CEK
		$cek = $this->db->query("SELECT*FROM trs_spb_detail WHERE id_spbd='$id_spbd' AND status_spd='Close'");
		if($cek->num_rows() != 0){
			$data = false; $msg = 'SPB SUDAH DI CLOSE!';
		}else{
			$this->db->where('id_spbd', $id_spbd);
			$data = $this->db->delete('trs_spb_detail');
			$msg = 'TERHAPUS!';
		}
		return [
			'data' => $data,
			'msg' => $msg,
		];
	}

	function simpandSPB()
	{
		$username = $this->username;
		$tgl = date('Y-m-d');
		$xurut = $_POST["h_urut"];
		$pemohon_spb = $_POST["pemohon_spb"];
		// CEK JIKA SUDAH TERSIMPAN
		$cekSimpan = $this->db->query("SELECT*FROM trs_spb_detail WHERE status_spd='Close' AND tgl_spb='$tgl' AND xurut='$xurut' AND creat_by='$username' GROUP BY xurut");
		if($cekSimpan->num_rows() != 0){
			$header = false; $detail = false; $msg = 'DATA SPB SUDAH TERSIMPAN!';
		}else if($pemohon_spb == ''){
			$header = false; $detail = false; $msg = 'HARAP ISI NAMA PEMOHON!';
		}else{
			// MEMBUAT NO. SPB
			$tahun = substr(date('Y'),2,2);
			$cek = $this->db->query("SELECT*FROM trs_spb_header WHERE no_spb LIKE 'SPB/$tahun/%' ORDER BY no_spb DESC LIMIT 1");
			if($cek->num_rows() == 0){
				$no_spb = 'SPB/'.$tahun.'/0001';
			}else{
				$r = str_replace('SPB/'.$tahun.'/', '', $cek->row()->no_spb);
				$no = str_pad($r+1, 4, "0", STR_PAD_LEFT);
				$no_spb = 'SPB/'.$tahun.'/'.$no;
			}
			// INPUT SPB HEADER
			$sph = [
				'tgl_spb' => $tgl,
				'no_spb' => $no_spb,
				'status_spb' => 'Open',
				'pemohon_spb' => $pemohon_spb,
				'creat_by' => $username,
				'crat_at' => date('Y-m-d H:i:s'),
			];
			$header = $this->db->insert('trs_spb_header', $sph);
			if($header){
				// UPDATE SPB DETAIL
				$qspbh = $this->db->query("SELECT*FROM trs_spb_header WHERE no_spb='$no_spb' AND tgl_spb='$tgl'")->row();
				$this->db->set('tgl_spb', $tgl);
				$this->db->set('no_spb', $no_spb);
				$this->db->set('id_spbh', $qspbh->id_spbh);
				$this->db->set('status_spd', 'Close');
				$this->db->where('no_spb', null);
				$this->db->where('id_spbh', null);
				$this->db->where('creat_by', $username);
				$detail = $this->db->update('trs_spb_detail');
			}
			$msg = 'OK!';
		}
		return [
			'header' => $header,
			'detail' => $detail,
			'msg' => $msg,
		];
	}
}
