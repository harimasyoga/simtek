<?php

class M_login extends CI_Model
{
	function cek_login($username, $password)
	{
		return $this->db->query("SELECT u.*,m.approve FROM tb_user u
		INNER JOIN m_modul_group m ON u.level=m.val_group
		WHERE u.username='$username' AND u.password='$password'");
	}
}
