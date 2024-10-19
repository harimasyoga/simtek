<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class M_fungsi extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	// Tampilkan semua master data fungsi
	//function getAll($limit, $offset)
    function getAll($tabel,$field1,$limit, $offset)
	{
		$this->db->SELECT('*');
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		$this->db->limit($limit,$offset);
		return $this->db->get();
	}

    function getcari($tabel,$field,$field1,$limit, $offset,$lccari)
	{
		$this->db->SELECT('*');
		$this->db->from($tabel);
        $this->db->or_like($field, $lccari);  
        $this->db->or_like($field1, $lccari);      
		$this->db->order_by($field, 'asc');
        $this->db->limit($limit,$offset);
		return $this->db->get();
	}
    
    function getAllc($tabel,$field1)
	{
		$this->db->SELECT('*');
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	// Total jumlah data
	function get_count($tabel)
	{
		return $this->db->get($tabel)->num_rows();
	}
    
	function get_count_cari($tabel,$field1,$field2,$data)
	{
        $this->db->SELECT('*');
		$this->db->from($tabel);
        $this->db->or_like($field1, $data);  
        $this->db->or_like($field2, $data);      
		$this->db->order_by($field1, 'asc');
		return $this->db->get()->num_rows();
		//return $this->db->get('ms_fungsi')->num_rows();
	}

    function get_count_teang($tabel,$field,$field1,$lccari)
	{
        $this->db->SELECT('*');
		$this->db->from($tabel);
        $this->db->or_like($field, $lccari);  
        $this->db->or_like($field1, $lccari);      
		$this->db->order_by($field, 'asc');
		return $this->db->get()->num_rows();
		//return $this->db->get('ms_fungsi')->num_rows();
	}

	// Ambil by ID
	function get_by_id($tabel,$field1,$id)
	{
		$this->db->SELECT('*');
		$this->db->from($tabel);
		$this->db->where($field1, $id);
		return $this->db->get();
	}

	//cari
    function cari($tabel,$field1,$field2,$limit, $offset,$data)
	{
		$this->db->SELECT('*');
		$this->db->from($tabel);
        $this->db->or_like($field2, $data);  
        $this->db->or_like($field1, $data);      
		$this->db->order_by($field1, 'asc');
		return $this->db->get();
	}

	// Simpan data
	function save($tabel,$data)
	{
		$this->db->insert($tabel, $data);
	}
	
	// Update data
	function update($tabel,$field1,$id, $data)
	{
		$this->db->where($field1, $id);
		$this->db->update($tabel, $data); 	
	}
	
	// Hapus data
	function delete($tabel,$field1,$id)
	{
		$this->db->where($field1, $id);
		$this->db->delete($tabel);
	}
    
  	function depan($number)
	{
		$number = abs($number);
		$nomor_depan = array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
		$depans = "";
		
		if($number<12){
			$depans = " ".$nomor_depan[$number];
		}
		else if($number<20){
			$depans = $this->depan($number-10)." belas";
		}
		else if($number<100){
			$depans = $this->depan($number/10)." puluh ".$this->depan(fmod($number,10));
		}
		else if($number<200){
			$depans = "seratus ".$this->depan($number-100);
		}
		else if($number<1000){
			$depans = $this->depan($number/100)." ratus ".$this->depan(fmod($number,100));
		//$depans = $this->depan($number/100)." Ratus ".$this->depan($number%100);
		}
		else if($number<2000){
			$depans = "seribu ".$this->depan($number-1000);
		}
		else if($number<1000000){
			$depans = $this->depan($number/1000)." ribu ".$this->depan(fmod($number,1000));
		}
		else if($number<1000000000){
			$depans = $this->depan($number/1000000)." juta ".$this->depan(fmod($number,1000000));
		}
		else if($number<1000000000000){
			$depans = $this->depan($number/1000000000)." milyar ".$this->depan(fmod($number,1000000000));
			//$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

		}
		else if($number<1000000000000000){
			$depans = $this->depan($number/1000000000000)." triliun ".$this->depan(fmod($number,1000000000000));
			//$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

		}				
		else{
			$depans = "Undefined";
		}
		return $depans;
	}

	function belakang($number)
	{
		$number = abs($number);
		$number = stristr($number,".");
		$nomor_belakang = array("nol","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");

		$belakangs = "";
		$length = strlen($number);
		$i = 1;
		while($i<$length)
		{
			$get = substr($number,$i,1);
			$i++;
			$belakangs .= " ".$nomor_belakang[$get];
		}
		return $belakangs;
	}

	function terbilang($number)
	{
		if (!is_numeric($number))
		{
			return false;
		}
		
		if($number<0)
		{
			$hasil = "Minus ".trim($this->depan($number));
			$poin = trim($this->belakang($number));

		}
		else{
			$poin = trim($this->belakang($number));
			$hasil = trim($this->depan($number));
		}
   
		if($poin)
		{
			$hasil = $hasil." koma ".$poin." Rupiah";
		}
		else{
			$hasil = $hasil." Rupiah";
		}
		return $hasil;  
	}
	
	function terbilang_angka($number)
	{
		if (!is_numeric($number))
		{
			return false;
		}
		
		if($number<0)
		{
			$hasil = "Minus ".trim($this->depan($number));
			$poin = trim($this->belakang($number));

		}
		else{
			$poin = trim($this->belakang($number));
			$hasil = trim($this->depan($number));
		}
   
		if($poin)
		{
			$hasil = $hasil." koma ".$poin;
		}
		else{
			$hasil = $hasil;
		}
		return $hasil;  
	}    
        
    function  tanggal_format_indonesia($tgl)
	{
        $tanggal    = explode('-',$tgl);
        $bulan      = $this-> getBulan($tanggal[1]);
        $tahun      = $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
    }

	function urut_transaksi($kode)
	{
		$this->db->query("UPDATE m_urut set no_urut=no_urut+1 where kode='$kode' ");
		
		$query = $this->db->query("SELECT * from m_urut where kode='$kode' ")->row();
		$nomor_urut = str_pad($query->no_urut, 4, "0", STR_PAD_LEFT);
		return $nomor_urut;
	}
	
	function tampil_no_urut($kode)
	{
		$query = $this->db->query("SELECT * from m_urut where kode='$kode' ")->row();
		$nomor_urut = str_pad($query->no_urut, 4, "0", STR_PAD_LEFT);
		return $nomor_urut;
	}

    function  periode_indonesia($tgl)
	{
            
        $tanggal    = explode('-',$tgl);
        $bulan      = $this-> getBulan($tanggal[1]);
        $tahun      = $tanggal[0];
        return  $bulan.' '.$tahun;

    }
	
	function  tanggal_format_indonesia_sebelum($tgl)
	{
        $tanggal    = explode('-',$tgl);
		$tanggal1         = $tanggal[2]-1;
		$bulan            = $this-> getBulan($tanggal[1]);
		$tahun            = $tanggal[0];
        return  $tanggal1.' '.$bulan.' '.$tahun;
    }

	function tglIndSkt($tgl){
        $tanggal = explode('-',$tgl); 
        $bulan  = $this->getBlnSkt($tanggal[1]);
        $tahun  =  substr($tanggal[0],2,2);
        return  $tanggal[2].'-'.$bulan.'-'.$tahun;
    }
    
    function  tanggal_ind($tgl)
	{        
        $tanggal    = explode('-',$tgl);
        $bulan      = $tanggal[1];
        $tahun      = $tanggal[0];
        return  $tanggal[2].'-'.$bulan.'-'.$tahun;

    }
        
    function  getBulan($bln)
	{
        switch  ($bln)
		{
			case  1:
				return  "Januari";
			break;

			case  2:
				return  "Februari";
			break;

			case  3:
				return  "Maret";
			break;

			case  4:
				return  "April";
			break;

			case  5:
				return  "Mei";
			break;

			case  6:
				return  "Juni";
			break;

			case  7:
				return  "Juli";
			break;

			case  8:
				return  "Agustus";
			break;

			case  9:
				return  "September";
			break;

			case  10:
				return  "Oktober";
			break;

			case  11:
				return  "November";
			break;

			case  12:
				return  "Desember";
			break;
		}
    }

	function getBlnSkt($bln) {
        switch($bln) {
			case  1:
				return  "Jan";
			break;
			case  2:
				return  "Feb";
			break;
			case  3:
				return  "Mar";
			break;
			case  4:
				return  "Apr";
			break;
			case  5:
				return  "Mei";
			break;
			case  6:
				return  "Jun";
			break;
			case  7:
				return  "Jul";
			break;
			case  8:
				return  "Agt";
			break;
			case  9:
				return  "Sep";
			break;
			case  10:
				return  "Okt";
			break;
			case  11:
				return  "Nov";
			break;
			case  12:
				return  "Des";
			break;
		}
    }
    
    function right($value, $count)
	{
		return substr($value, ($count*-1));
    }

    function left($string, $count)
	{
		return substr($string, 0, $count);
    }    
    
    function  dotrek($rek)
	{
				$nrek=strlen($rek);
				switch ($nrek) {
                case 1:
				$rek = $this->left($rek,1);								
       			 break;
    			case 2:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1);								
       			 break;
    			case 3:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1);								
       			 break;
    			case 5:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2);								
        		break;
    			case 7:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2).'.'.substr($rek,5,2);								
        		break;
                case 29:
					$rek = $this->left($rek,21).'.'.substr($rek,23,1).'.'.substr($rek,24,1).'.'.substr($rek,25,1).'.'.substr($rek,26,2).'.'.substr($rek,28,2);								
        		break;
    			default:
				$rek = "";	
				}
				return $rek;
    }
    
	function template_kop($judul, $jdl_save, $body, $position, $cekpdf)
	{
		$param    = $judul;
		$unit     = $this->session->userdata('unit');
		$avatar   = $this->session->userdata('avatar_cabang');

		$profile  = $this->db->query("SELECT*FROM m_setting")->row();
		$nm_toko  = $profile->nm_toko;
		$alamat   = $profile->alamat;
		$alamat2  = $profile->alamat2;
		$phone    = $profile->no_telp;
		$whatsapp = $profile->no_telp;
		$kodepos  = $profile->kode_pos;
		$npwp     = '-';
		$chari    = '';
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
			 <thead>
				  <tr>
					   <td rowspan=\"5\" align=\"center\">
							<img src=\"" . base_url() . "assets/gambar/ppi.png\"  width=\"80\" height=\"70\" />
					   </td>
					   <td colspan=\"20\">
							<b>
								 <tr>
									  <td align=\"center\" style=\"font-size:28;border-bottom: none;\"><b>$nm_toko</b></td>
								 </tr>
								 <tr>
									  <td align=\"center\" style=\"font-size:10px;\">$alamat</td>
								 </tr>
								 <tr>
									  <td align=\"center\" style=\"font-size:10px;\">$alamat2  Kode Pos $kodepos </td>
								 </tr>
								 <tr>
									  <td align=\"center\" style=\"font-size:10px;\">Wa : $whatsapp  |  Telp : $phone </td>
								 </tr>
							</b>
					   </td>
				  </tr>
			 </table>";
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:6px\" width=\"100%\" align=\"center\" border=\"0\">
				  <tr>
					   <td> &nbsp; </td>
				  </tr> 
			 </table>";
								 
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
				  <tr>
					   <td colspan=\"20\" style=\"border-top: none;border-right: none;border-left: none;\"></td>
				  </tr> 
			 </table>";
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:4px\" width=\"100%\" align=\"center\" border=\"1\">     
				  <tr>
					   <td colspan=\"20\" style=\"border-top: none;border-right: none;border-left: none;border-bottom: 2px solid black;font-size:5px\"></td>
				  </tr> 
			 </table>";
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:8px\" width=\"100%\" align=\"center\" border=\"0\">     
				  <tr>
					   <td>&nbsp;</td>
				  </tr> 
			 </table>";
		$chari .= "
			 <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				  <tr>
					   <td colspan=\"20\" width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>" . $param . "</b></td>
				  </tr>
			 </table>";
			 
		$chari .= $body;
		$data['prev']    = $chari;
		$judul           = $param;

		switch ($cekpdf) {
			case 0;
				echo ("<title>$judul</title>");
				echo ($chari);
				break;

			case 1;
				// $this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
			//   $this->mpdf( $judul,$chari,'','', $position);
				$this->_mpdf_hari($position, 'A4', $judul, $chari, $jdl_save.'.pdf', 5, 5, 5, 10);
			


				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('app/master_cetak', $data);
				break;
		}

	}

	function _mpdf_hari($orientasi='', $uk='', $judul='', $isi='', $jdlsave='', $lMargin='',$rMargin='', $tMargin='', $bMargin='', $font=10, $hal='',$tab='')
    {
        ini_set("memory_limit", "-1");
        ini_set("MAX_EXECUTION_TIME","-1");
		ini_set("pcre.backtrack_limit", "5000000");
		set_time_limit(0);
		
		$this->load->library('Mpdf');

		// $this->mpdf = new \Mpdf\Mpdf( array(190,236),$size,'',$lMargin,$rMargin,$tMargin);
		
        $jam = date("H:i:s");
		if ($hal==''){
			$hal1=1;
		} 
		if($hal!==''){
			$hal1=$hal;
		}

		if ($font==''){
			$size=12;
		}else{
			$size=$font;
		} 

		$tMargin = ( $tMargin =='' ? 5 : $tMargin );
		$bMargin = ( $bMargin =='' ? 5 : $bMargin );
		$lMargin = ( $lMargin =='' ? 5 : $lMargin );
		$rMargin = ( $rMargin =='' ? 5 : $rMargin );

		$this->mpdf->AddPageByArray(array(
			'orientation' => $orientasi,
			'margin-top' => $tMargin,
			'margin-right' => $rMargin,
			'margin-bottom' => $bMargin,
			'margin-left' => $lMargin,
		));

		// $this->mpdf->AddPage($orientasi,$uk);

		$this->mpdf->SetFooter('Tercetak PPI - {DATE j-m-Y ( H:i:s )} |Halaman {PAGENO} / {nb}| ');

		$this->mpdf->setTitle($judul);

		$this->mpdf->writeHTML($isi);

		$this->mpdf->output($jdlsave,'I');
    }

	
	function mPDFP($html)
	{
		$mpdf = new \Mpdf\Mpdf;
		$mpdf = new \Mpdf\Mpdf([
			'default_font_size' => 9
		]);
		$mpdf->AddPage('P','','','','',10,10,10,10);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}
    
    
    function _mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=0,$orientasi='') 
	{
        
        ini_set("memory_limit","512M");
        // $this->load->library('mpdf');
        $this->load->library('Mpdf');
        
        $this->mpdf->defaultheaderfontsize = 6;	/* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;	/* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
      
        $this->mpdf->AddPage($orientasi,'','','','',$lMargin,$rMargin);
        
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }


    function _mpdf_margin($judul='',$isi='',$lMargin='',$rMargin='',$tMargin='',$bMargin='',$font=0,$orientasi='',$jdlsave='') 
	{
        
        ini_set("memory_limit","512M");
        $this->load->library('mpdf');
        
        $this->mpdf->defaultheaderfontsize = 6;	/* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;	/* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        $jam = date("H:i:s");
        
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output($jdlsave,'I');
        
    }
    
	function  rev_date($tgl)
	{
		$t=explode("-",$tgl);
		$tanggal  =  $t[0];
		$bulan    =  $t[1];
		$tahun    =  $t[2];
		return  $tahun.'-'.$bulan.'-'.$tanggal;

	}
	
	function get_sclient($hasil,$tabel)
	{
		$this->db->SELECT($hasil);
		$q        = $this->db->get($tabel);
		$data     = $q->result_array();
		$baris    = $q->num_rows();
		return $data[0][$hasil];
	}

	function get_nama($kode,$hasil,$tabel,$field)
	{
		$this->db->SELECT($hasil);
		$this->db->where($field, $kode);
		$q        = $this->db->get($tabel);
		$data     = $q->result_array();
		$baris    = $q->num_rows();
		return $data[0][$hasil];
	}
	
    function rp_minus($nilai)
	{
        if($nilai<0){
            $nilai = $nilai * (-1);
            $nilai = '('.number_format($nilai,"2",",",".").')';    
        }else{
            $nilai = number_format($nilai,"2",",","."); 
        }
        
        return $nilai;
    }  	        

    function persen($nilai,$nilai2)
	{
            if($nilai != 0){
                $persen = $this->rp_minus((($nilai2 - $nilai)/$nilai)*100);
            }else{
                if($nilai2 == 0){
                    $persen = $this->rp_minus(0);
                }else{
                    $persen = $this->rp_minus(100);
                }
            } 
          return $persen;  
	 }

    function persen_real($ang,$real)
	{
            if($ang != 0){
                $persen = $this->rp_minus(($real * 100)/$ang);
            }else{
                if($real == 0){
                    $persen = $this->rp_minus(0);
                }else{
                    $persen = '~';
                }
            } 
          return $persen;  
	}

    function q_ttd($ttd,$kode)
	{
        $hasil = 0;
        $csql ="SELECT nip,nama,jabatan,pangkat from ms_ttd where nip='$ttd' and kode='$kode'";
        $hasil = $this->db->query($csql);
        return $hasil;
    } 	


    function cek_menu_user($user,$menuid)
	{
        $hasil = 0;
        $csql ="SELECT dbo.cek_menu_user('$user','$menuid') as jumlah";
        $hasil = $this->db->query($csql);
        $hasil = $hasil->row('jumlah');
        return $hasil;        
    }

	function tglPlan($tgl)
	{
        $tanggal = explode('-',$tgl);
        return  $tanggal[2].'/'.$tanggal[1].'/'.substr($tanggal[0], 2, 2);
    }

	function newMpdf($judul,$cetak,$html,$top,$right,$bottom,$left,$orientasi,$kertas,$ctk = 'mpdf.pdf'){
		$this->load->library('mpdf');

		$this->mpdf->setTitle($judul);

		if($kertas == 'F4'){
			$orr = array(210, 330);
		}else{ // A4
			$orr = array(210, 297);
		}
		$this->mpdf->AddPageByArray(array(
			'orientation' => $orientasi,
			'margin-top' => $top,
			'margin-right' => $right,
			'margin-bottom' => $bottom,
			'margin-left' => $left,
			'sheet-size' => $orr,
		));

		if($cetak != ''){
			$this->mpdf->SetFooter('Tercetak {DATE j-m-Y H:i:s}');
		}
		$this->mpdf->writeHTML($html);         
        $this->mpdf->Output($ctk, 'I');
	}

	function haru($tgl){
		$namaHari = date('l', strtotime($tgl));
		switch($namaHari){
			case 'Sunday':
				$hari_ini = "Minggu";
			break;
			case 'Monday':			
				$hari_ini = "Senin";
			break;
			case 'Tuesday':
				$hari_ini = "Selasa";
			break;
			case 'Wednesday':
				$hari_ini = "Rabu";
			break;
			case 'Thursday':
				$hari_ini = "Kamis";
			break;
			case 'Friday':
				$hari_ini = "Jumat";
			break;
			case 'Saturday':
				$hari_ini = "Sabtu";
			break;
			default:
				$hari_ini = "";		
			break;
		}
		return $hari_ini;
	}

}
