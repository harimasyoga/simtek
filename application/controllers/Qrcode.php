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
}
