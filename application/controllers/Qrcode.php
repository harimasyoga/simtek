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
		$code = $_GET["v"];
		$data = array(
			'judul' => "QR Code",
			'code' => $code,
		);
		$this->load->view('header', $data);
		$this->load->view('Qrcode/v_qrcode', $data);
		// $this->load->view('home');
		$this->load->view('footer');
	}
}
