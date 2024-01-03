<?php
class M_laporan extends CI_Model{
 	
 	function __construct(){
        parent::__construct();
        
        date_default_timezone_set('Asia/Jakarta');
        $this->username = $this->session->userdata('username');
        
    }

    function get_periode(){
    	$query = $this->db->query("SELECT DATE_FORMAT(tgl,'%Y-%m') periode FROM tr_absensi GROUP BY DATE_FORMAT(tgl,'%Y-%m') ORDER BY periode DESC");

    	return $query;
    }

    function get_produk($searchTerm="",$jenis){
     if ($jenis == "Produk") {
     	$table = "m_produk";
     	$id = "id_produk";
     	$text = "nm_produk";
     }else{
     	$table = "m_perawatan";
     	$id = "id_perawatan";
     	$text = "nm_perawatan";
     }
     $users = $this->db->query("SELECT * FROM $table where $text like '%$searchTerm%' ")->result_array();

     $data = array();
     
     array_push($data,
                    ['id'      => "-",'text'     => "Semua"]
                );

     foreach($users as $user){
        $data[] = array(
	     	"id"=>$user[$id], 
    		"text"=>$user[$text]
		     
        );
     }
     return $data;
  }

}