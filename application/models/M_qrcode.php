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
		$spd = $this->db->query("SELECT*FROM trs_spb_detail WHERE no_spb IS NULL AND id_spb IS NULL AND status_spd='Open' AND tgl_spb='$tgl' AND id_stok='$id_stok'");
		if($qty == '' || $qty == 0 || $qty < 0 || !preg_match("/^[0-9.]*$/", $qty)){
			$data = false; $msg = 'HARAP ISI QTY!';
		}else if($plh_departemen == ''){
			$data = false; $msg = 'HARAP PILIH DEPARTEMEN!';
		}else if($plh_bagian == ''){
			$data = false; $msg = 'HARAP PILIH BAGIAN!';
		}else if($spd->num_rows() != 0){
			$data = false; $msg = 'DATA SUDAH ADA!';
		}else{
			$stok = $this->db->query("SELECT*FROM m_stok WHERE id_stok='$id_stok'")->row();
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
			$spd = [
				'tgl_spb' => $tgl,
				'no_spb' => null,
				'id_spb' => null,
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
				'creat_by' => $this->username,
				'creat_at' => date('Y-m-d H:i:s'),
			];
			$data = $this->db->insert('trs_spb_detail', $spd);
			$msg = 'OK!';
		}
		return [
			'data' => $data,
			'msg' => $msg,
		];
	}
}
