<?php 

class M_login extends CI_Model{	

	function cek_login($username,$password){
		$query = "SELECT * FROM tb_user WHERE username = '".$username."' AND password = '".$password."'";
		return $this->db->query($query);

	}


}