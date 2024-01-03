<?php

    function cek_subs_bcf($kualitas)
    {	
        $CI =& get_instance();	  
        $substance = $CI->db->query("SELECT * FROM m_p11 where '$kualitas'=concat(substance1,'/',substance2,'/',substance3,'/',substance4,'/',substance5) ")->row();
        return $substance->BCF;
    } 

    function cek_subs_flute($kualitas,$flute)
    {	
        $CI =& get_instance();	  
        $substance = $CI->db->query("SELECT * FROM m_p11 where '$kualitas'=concat(substance1,'/',substance2,'/',substance5) ")->row();
        return $substance->$flute;
    } 

?>

