<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("Login"));
		}
		$this->load->model('m_master');
		$this->load->model('m_transaksi');
	}

	function Opb()
	{
		$data = [
			'judul' => "OPB",
		];
		$this->load->view('header',$data);
		if(in_array($this->session->userdata('approve'), ['ALL', 'ADMIN', 'ACC', 'OFFICE', 'FINANCE', 'OWNER'])) {
			$this->load->view('Transaksi/v_opb', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}

	function Bapb()
	{
		$data = [
			'judul' => "BAPB",
		];
		$this->load->view('header',$data);
		if(in_array($this->session->userdata('approve'), ['ALL', 'GUDANG', 'OFFICE'])) {
			$this->load->view('Transaksi/v_bapb', $data);
		}else{
			$this->load->view('home');
		}
		$this->load->view('footer');
	}

	public function PO()
	{
		$data = array(
			'judul' => "Purchase Order",
			'produk' => $this->db->query("SELECT * FROM m_produk order by id_produk")->result(),
			'sales' => $this->db->query("SELECT * FROM m_sales order by id_sales")->result(),
			'pelanggan' => $this->db->query("SELECT * FROM m_pelanggan a 
            left join m_kab b on a.kab=b.kab_id 
            Left Join m_sales c on a.id_sales=c.id_sales
            order by id_pelanggan")->result(),
			'level' => $this->session->userdata('level'). "aa",
		);

		$this->load->view('header', $data);
		$this->load->view('Transaksi/v_po', $data);
		$this->load->view('footer');
	}

	function load_produk()
    {
        
		$pl = $this->input->post('idp');
		$kd = $this->input->post('kd');

        if($pl !='' && $kd ==''){
            $cek ="where no_customer = '$pl' ";
        }else if($pl =='' && $kd !=''){
            $cek ="where id_produk = '$kd' ";
        }else {
            $cek ="";
        }

        $query = $this->db->query("SELECT * FROM m_produk $cek order by id_produk ")->result();

            if (!$query) {
                $response = [
                    'message'	=> 'not found',
                    'data'		=> [],
                    'status'	=> false,
                ];
            }else{
                $response = [
                    'message'	=> 'Success',
                    'data'		=> $query,
                    'status'	=> true,
                ];
            }
            $json = json_encode($response);
            print_r($json);
    }
   
    function load_produk_1()
    {
        
		$pl = $this->input->post('idp');
		$kd = $this->input->post('kd');

        if($pl !='' && $kd ==''){
            $cek ="where no_customer = '$pl' ";
        }else if($pl =='' && $kd !=''){
            $cek ="where id_produk = '$kd' ";
        }else {
            $cek ="";
        }

        $query = $this->db->query("SELECT * FROM m_produk $cek order by id_produk ")->row();

        echo json_encode($query);
    }
    
	function getMax()
	{
		$table  = $this->input->post('table');
		$fieald = $this->input->post('fieald');

		$data = [
			'no'       => $this->m_master->get_data_max($table, $fieald),
			'bln'      => $this->m_master->get_romawi(date('m')),
			'tahun'    => date('Y')
		];
		echo json_encode($data);
	}

	function Insert()
	{

		$jenis    = $this->input->post('jenis');
		$status   = $this->input->post('status');

		$result   = $this->m_transaksi->$jenis($jenis, $status);
		echo json_encode($result);
	}
	function load_data()
	{
		$jenis        = $this->uri->segment(3);
		$data         = array();

		if ($jenis == "po") {

			if($this->session->userdata('username')=='ppismg'){
				$cek_data = 'WHERE id_sales in ("2","3")';
			}else{
				$cek_data = '';
			}

			$query = $this->m_master->query("SELECT a.*,b.*,a.add_time as time_input FROM trs_po a join m_pelanggan b on a.id_pelanggan=b.id_pelanggan $cek_data order by a.tgl_po desc, id desc")->result();
			$i = 1;
			foreach ($query as $r) {
				$row        = array();
				$time       = substr($r->tgl_po, 0,10);
				$time_po    = substr($r->time_input, 10,10);

                if($r->status_app1=='N')
                {
                    $btn1   = 'btn-warning';
                    $i1     = '<i class="fas fa-lock"></i>';
					$alasan1 = '';
                }else  if($r->status_app1=='H')
                {
                    $btn1   = 'btn-danger';
                    $i1     = '<i class="far fa-hand-paper"></i>';
					$alasan1 = $r->ket_acc1;
                }else  if($r->status_app1=='R')
                {
                    $btn1   = 'btn-danger';
                    $i1     = '<i class="fas fa-times"></i>';
					$alasan1 = $r->ket_acc1;
                }else{
                    $btn1   = 'btn-success';
                    $i1     = '<i class="fas fa-check-circle"></i>';
					$alasan1 = '';
                }
                
                if($r->status_app2=='N')
                {
                    $btn2   = 'btn-warning';
                    $i2     = '<i class="fas fa-lock"></i>';
					$alasan2 = '';
                }else  if($r->status_app2=='H')
                {
                    $btn2   = 'btn-danger';
                    $i2     = '<i class="far fa-hand-paper"></i>';
					$alasan2 = $r->ket_acc2;
                }else  if($r->status_app2=='R')
                {
                    $btn2   = 'btn-danger';
                    $i2     = '<i class="fas fa-times"></i>';
					$alasan2 = $r->ket_acc2;
                }else{
                    $btn2   = 'btn-success';
                    $i2     = '<i class="fas fa-check-circle"></i>';
					$alasan2 = '';
                }
                
                if($r->status_app3=='N')
                {
                    $btn3   = 'btn-warning';
                    $i3     = '<i class="fas fa-lock"></i>';
					$alasan3 = '';
                }else  if($r->status_app3=='H')
                {
                    $btn3   = 'btn-danger';
                    $i3     = '<i class="far fa-hand-paper"></i>';
					$alasan3 = $r->ket_acc3;
                }else  if($r->status_app3=='R')
                {
                    $btn3   = 'btn-danger';
                    $i3     = '<i class="fas fa-times"></i>';
					$alasan3 = $r->ket_acc3;
                }else{
                    $btn3   = 'btn-success';
                    $i3     = '<i class="fas fa-check-circle"></i>';
					$alasan3 = '';
                }
                
                if($r->status == 'Open')
                {
                    $btn_s   = 'btn-info';
                }else if($r->status == 'Approve')
                {
                    $btn_s   = 'btn-success';
                }else{
                    $btn_s   = 'btn-danger';
                }

				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')">' . $r->no_po . "<a></div>";

				$row[] = '<div class="text-center">'.$this->m_fungsi->tanggal_ind($time).' <br> ('.$time_po.')</div>';

                $time1 = ( ($r->time_app1 == null) ? 'BELUM ACC' : $this->m_fungsi->tanggal_format_indonesia(substr($r->time_app1,0,10))  . ' - ' .substr($r->time_app1,10,9)) ;

                $time2 = ( ($r->time_app2 == null) ? 'BELUM ACC' : $this->m_fungsi->tanggal_format_indonesia(substr($r->time_app2,0,10))  . ' - ' .substr($r->time_app2,10,9));

                $time3 = ( ($r->time_app3 == null) ? 'BELUM ACC' : $this->m_fungsi->tanggal_format_indonesia(substr($r->time_app3,0,10))  . ' - ' .substr($r->time_app3,10,9));

				$row[] = '<div class="text-center"><button type="button" class="btn btn-sm '.$btn_s.' ">'.$r->status.'</button></div>';

				$row[] = '<div class="text-center">'.$r->kode_po.'</div>';
				$row[] = '<div style="display:none">'.$r->kode_po.'</div>';
				// $row[] = $r->total_qty;
				$row[] = '<div class="text-center">'.$r->nm_pelanggan.'</div>';
                
				$row[] = '<div class="text-center">
					<button onclick="data_sementara(`Marketing`,' . "'" . $r->status_app1 . "'" . ',' . "'" . $time1 . "'" . ',' . "'" . $alasan1 . "'" . ',' . "'" . $r->no_po . "'" . ')" type="button" title="'.$time1.'" style="text-align: center;" class="btn btn-sm '.$btn1.' ">'.$i1.'</button><br>
					'.$alasan1.'</div>
				';
				
                $row[] = '<div class="text-center">
					<button onclick="data_sementara(`PPIC`,' . "'" . $r->status_app2 . "'" . ',' . "'" . $time2 . "'" . ',' . "'" . $alasan2 . "'" . ',' . "'" . $r->no_po . "'" . ')"  type="button" title="'.$time2.'"  style="text-align: center;" class="btn btn-sm '.$btn2.' ">'.$i2.'</button><br>
					'.$alasan2.'</div>
				';
                $row[] = '<div class="text-center">
					<button onclick="data_sementara(`Owner`,' . "'" . $r->status_app3 . "'" . ',' . "'" . $time3 . "'" . ',' . "'" . $alasan3 . "'" . ',' . "'" . $r->no_po . "'" . ')"  type="button" title="'.$time3.'"  style="text-align: center;" class="btn btn-sm '.$btn3.' ">'.$i3.'</button><br>
					'.$alasan3.'</div>
				';

				// $aksi = '-';
                $aksi = '';

				if (!in_array($this->session->userdata('level'), ['Admin','Marketing','PPIC','Owner']))
                {

					if ($r->status == 'Open' && $r->status_app1 == 'N') {
						if (in_array($this->session->userdata('level'), ['Keuangan1'])) { 

							$aksi .= ' 
							<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Transaksi/Cetak_PO?no_po=" . $r->no_po . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

							<a target="_blank" class="btn btn-sm btn-success" href="' . base_url("Transaksi/Cetak_wa_po?no_po=" . $r->no_po . "") . '" title="Format WA" ><b><i class="fab fa-whatsapp"></i> </b></a>

							<a target="_blank" class="btn btn-sm btn-primary" href="' . base_url("Transaksi/Cetak_img_po?no_po=" . $r->no_po . "") . '" title="CETAK PO" ><b><i class="fas fa-images"></i> </b></a>

							';
						} else {

							$aksi .= ' 

							<button type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'edit'" . ')" title="EDIT" class="btn btn-info btn-sm">
								<i class="fa fa-edit"></i>
							</button>
							
							<button type="button" title="DELETE"  onclick="deleteData(' . "'" . $r->no_po . "'" . ',' . "'" . $r->no_po . "'" . ')" class="btn btn-danger btn-sm">
								<i class="fa fa-trash-alt"></i>
							</button>  

							<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Transaksi/Cetak_PO?no_po=" . $r->no_po . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

							<a target="_blank" class="btn btn-sm btn-success" href="' . base_url("Transaksi/Cetak_wa_po?no_po=" . $r->no_po . "") . '" title="Format WA" ><b><i class="fab fa-whatsapp"></i> </b></a> 
							
							<a target="_blank" class="btn btn-sm btn-primary" href="' . base_url("Transaksi/Cetak_img_po?no_po=" . $r->no_po . "") . '" title="CETAK PO" ><b><i class="fas fa-images"></i> </b></a>
							';
						}
						
					}else{

						$aksi .= ' 
							<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Transaksi/Cetak_PO?no_po=" . $r->no_po . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

							<a target="_blank" class="btn btn-sm btn-success" href="' . base_url("Transaksi/Cetak_wa_po?no_po=" . $r->no_po . "") . '" title="Format WA" ><b><i class="fab fa-whatsapp"></i> </b></a> 
							
							<a target="_blank" class="btn btn-sm btn-primary" href="' . base_url("Transaksi/Cetak_img_po?no_po=" . $r->no_po . "") . '" title="CETAK PO" ><b><i class="fas fa-images"></i> </b></a>';

					}
					
				}else{
					if ($this->session->userdata('level') == 'Marketing' ) {

						if($r->status_app1 == 'N' || $r->status_app1 == 'H' || $r->status_app1 == 'R')
						{
							$aksi .=  ' 
									<button title="VERIFIKASI DATA" type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')" class="btn btn-info btn-sm">
										<i class="fa fa-check"></i>
									</button>  ';
						}
					}

					if ($this->session->userdata('level') == 'PPIC' && $r->status_app1 == 'Y' ) {

						if($r->status_app2 == 'N' || $r->status_app2 == 'H' || $r->status_app2 == 'R'){

							$aksi .=  ' 
									<button title="VERIFIKASI DATA" type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')" class="btn btn-info btn-sm">
										<i class="fa fa-check"></i>
									</button> ';
						}
					}

					if ($this->session->userdata('level') == 'Owner' && $r->status_app1 == 'Y' && $r->status_app2 == 'Y' ) {
						if($r->status_app3 == 'N' || $r->status_app3 == 'H' || $r->status_app3 == 'R'){

							$aksi .=  ' 
									<button title="VERIFIKASI DATA" type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')" class="btn btn-info btn-sm">
										<i class="fa fa-check"></i>
									</button>  ';
						}
					}

                    if ($this->session->userdata('level') == 'Admin' ) 
					{

						if($r->status_app1 == 'N' || $r->status_app2 == 'N' || $r->status_app3 == 'N' || $r->status_app1 == 'H' || $r->status_app2 == 'H' || $r->status_app3 == 'H' || $r->status_app1 == 'R' || $r->status_app2 == 'R' || $r->status_app3 == 'R'){
							$aksi .=  '
								<button type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'edit'" . ')" title="EDIT" class="btn btn-info btn-sm">
									<i class="fa fa-edit"></i>
								</button>

								<button type="button" title="DELETE"  onclick="deleteData(' . "'" . $r->no_po . "'" . ',' . "'" . $r->no_po . "'" . ')" class="btn btn-danger btn-sm">
									<i class="fa fa-trash-alt"></i>
								</button>  
	                            <button title="VERIFIKASI DATA" type="button" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')" class="btn btn-info btn-sm">
                                    <i class="fa fa-check"></i>
	                            </button>
								<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Transaksi/Cetak_PO?no_po=" . $r->no_po . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

								<a target="_blank" class="btn btn-sm btn-success" href="' . base_url("Transaksi/Cetak_wa_po?no_po=" . $r->no_po . "") . '" title="Format WA" ><b><i class="fab fa-whatsapp"></i> </b></a> 
								
								<a target="_blank" class="btn btn-sm btn-primary" href="' . base_url("Transaksi/Cetak_img_po?no_po=" . $r->no_po . "") . '" title="CETAK PO" ><b><i class="fas fa-images"></i> </b></a>
								';
						}else{
							$aksi .=  '
								<a target="_blank" class="btn btn-sm btn-danger" href="' . base_url("Transaksi/Cetak_PO?no_po=" . $r->no_po . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

								<a target="_blank" class="btn btn-sm btn-success" href="' . base_url("Transaksi/Cetak_wa_po?no_po=" . $r->no_po . "") . '" title="Format WA" ><b><i class="fab fa-whatsapp"></i> </b></a> 
								
								<a target="_blank" class="btn btn-sm btn-primary" href="' . base_url("Transaksi/Cetak_img_po?no_po=" . $r->no_po . "") . '" title="CETAK PO" ><b><i class="fas fa-images"></i> </b></a>
								';

						}

						if($time<date('2023-11-13'))
						{
							
							// 1 itu aktif 0 itu non aktif / po lama
							if($r->aktif=='1')
							{
								$aksi .=  '
								<button type="button" title="NON AKTIF"  onclick="nonaktif(0,' . "'" . $r->id . "'" . ',' . "'" . $r->no_po . "'" . ',' . "'" . $this->m_fungsi->tanggal_ind($time) . "'" . ')" class="btn btn-sm btn-warning">
									<i class="fas fa-power-off"></i>
								</button> 
								';
							}else{
								$aksi .=  '
								<button type="button" title="AKTIF"  onclick="nonaktif(1,'. "'" . $r->id . "'" . ',' . "'" . $r->no_po . "'" . ',' . "'" . $this->m_fungsi->tanggal_ind($time) . "'" . ')" class="btn btn-sm btn-primary">
									<i class="fas fa-power-off"></i>
								</button> 
								';
							}

						}
					}

					
				}

				$row[] = '<div class="text-center">'.$aksi.'</div>';

				$data[] = $row;

				$i++;
			}
		} else if ($jenis == "trs_so_detail") {
			$query = $this->db->query("SELECT d.id AS id_po_detail,p.kode_mc,d.tgl_so,p.nm_produk,d.status_so,COUNT(s.rpt) AS c_rpt,l.nm_pelanggan,s.* FROM trs_po_detail d
			INNER JOIN trs_so_detail s ON d.no_po=s.no_po AND d.kode_po=s.kode_po AND d.no_so=s.no_so AND d.id_produk=s.id_produk
			INNER JOIN m_produk p ON d.id_produk=p.id_produk
			INNER JOIN m_pelanggan l ON d.id_pelanggan=l.id_pelanggan
			WHERE d.no_so IS NOT NULL AND d.tgl_so IS NOT NULL AND d.status_so IS NOT NULL
			GROUP BY d.id DESC")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();
				$row[] = '<div class="text-center"><a href="javascript:void(0)" onclick="tampilEditSO('."'".$r->id_po_detail."'".','."'".$r->no_po."'".','."'".$r->kode_po."'".','."'detail'".')">'.$i."<a></div>";
				$row[] = $r->tgl_so;
				$row[] = $r->kode_mc;
				$row[] = $r->nm_produk;
				$row[] = $r->nm_pelanggan;

				$urut_so = str_pad($r->urut_so, 2, "0", STR_PAD_LEFT);
				$row[] = $r->no_so.'.'.$urut_so.'('.$r->c_rpt.')';
				if ($r->status_so == 'Open') {
					$aksi = '<button type="button" onclick="tampilEditSO('."'".$r->id_po_detail."'".','."'".$r->no_po."'".','."'".$r->kode_po."'".','."'edit'".')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>';
				}else{
					$aksi = '-';
				}
				$row[] = '<div class="text-center">'.$aksi.'</div>';
				$data[] = $row;
				$i++;
			}
		} else if ($jenis == "trs_wo") {
			$query = $this->m_master->query("SELECT a.id as id_wo,a.*,b.*,c.*,d.* FROM trs_wo a 
            JOIN trs_wo_detail b ON a.no_wo=b.no_wo 
            JOIN m_produk c ON a.id_produk=c.id_produk 
            JOIN m_pelanggan d ON a.id_pelanggan=d.id_pelanggan 
            order by a.id desc")->result();
			$i = 1;
			foreach ($query as $r) {

				if($r->kategori=='K_BOX'){
					$type ='BOX';
				}else{
					$type ='SHEET';
				}

				if($r->status == 'Open')
                {
                    $btn_status   = 'btn-info';
                }else if($r->status == 'Approve')
                {
                    $btn_status   = 'btn-success';
                }else{
                    $btn_status   = 'btn-danger';
                }

				$row = array();
				$row[] = '<div class="text-center">'.$i.'</div>';
				$row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->id_wo . "'" . ',' . "'detail'" . ')">' . $r->no_wo . "<a>";
                
				$row[] = '<div class="text-center">'.$type.'</div';
				$row[] = $this->m_fungsi->tanggal_ind($r->tgl_wo);
				// $row[] = $r->no_so;
				$row[] = $this->m_fungsi->tanggal_ind($r->tgl_so);
				$row[] = '<div class="text-center btn btn-sm '.$btn_status.'">'.$r->status.'</div';
				$row[] = $r->kode_mc;
				$row[] = '<div class="text-center">'.number_format($r->qty, 0, ",", ".").'</div';
				// $row[] = $r->id_pelanggan;
				$row[] = $r->nm_pelanggan;

				if ($r->status == 'Open') {

                    $aksi = ' 
							<button type="button" onclick="tampil_edit(' . "'" . $r->id_wo . "'" . ',' . "'edit'" . ')" class="btn btn-info btn-sm">
                                <i class="fa fa-edit"></i>
                            </button>

							<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("Transaksi/Cetak_WO?no_wo=" . $r->no_wo . "") . '" title="Cetak" ><i class="fas fa-print"></i> </a>

                            <button type="button" onclick="deleteData(' . "'" . $r->id_wo . "'" . ',' . "'" . $r->no_wo . "'" . ')" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-alt"></i>
                            </button>  
                            ';

				} else {
					$aksi = '-';
				}

				$row[] = $aksi;

				$data[] = $row;

				$i++;
			}
		} else if ($jenis == "trs_surat_jalan") {
			$query = $this->m_master->query("SELECT *,sum(qty) as tot_qty FROM trs_surat_jalan group by no_surat_jalan,no_po order by id")->result();
			$i = 1;
			foreach ($query as $r) {
				$row = array();

				$row[] = $i;
				$row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' . "'" . $r->id . "'" . ',' . "'detail'" . ')">' . $r->no_surat_jalan . "<a>";
				$row[] = $r->tgl_surat_jalan;
				$row[] = $r->status;
				$row[] = $r->no_po;
				$row[] = $r->id_produk;
				$row[] = $r->tot_qty;
				$row[] = $r->id_pelanggan;
				$row[] = $r->nm_pelanggan;

				if ($r->status == 'Open') {
					$aksi = ' 
                            <button type="button" onclick="deleteData(' . "'" . $r->id . "'" . ')" class="btn btn-danger btn-xs">
                               Batal
                            </button> ';
				} else {
					$aksi = '-';
				}

				$row[] = $aksi;

				$data[] = $row;

				$i++;
			}
		}

		$output = array(
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function hapus()
	{
		$jenis   = $_POST['jenis'];
		$field   = $_POST['field'];
		$id = $_POST['id'];

		if ($jenis == "trs_po") {
			$result = $this->m_master->query("DELETE FROM $jenis WHERE  $field = '$id'");
			$result = $this->m_master->query("DELETE FROM trs_po_detail WHERE  $field = '$id'");
		} else {

			$result = $this->m_master->query("DELETE FROM $jenis WHERE  $field = '$id'");
		}

		echo json_encode($result);
	}

	function batal()
	{
		$jenis   = $_POST['jenis'];
		$field   = $_POST['field'];
		$id = $_POST['id'];

		$result = $this->m_transaksi->batal($id, $jenis, $field);


		echo json_encode($result);
	}

	function prosesData()
	{
		$jenis   = $_POST['jenis'];

		$result = $this->m_transaksi->$jenis();


		echo json_encode($result);
	}

	function Verifikasi_all()
	{
		$id  = $_GET['no_po'];

		if ($this->session->userdata('level') == "Admin") {
			
		}


		echo json_encode($result);
	}

	// OPB

	function destroy()
	{
		$this->cart->destroy();
	}

	function hapusCart()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function simpanOPB()
	{
		$result = $this->m_transaksi->simpanOPB();
		echo json_encode($result);
	}

	function hapusOPB()
	{
		$result = $this->m_transaksi->hapusOPB();
		echo json_encode($result);
	}

	function btnVerifOpb()
	{
		$result = $this->m_transaksi->btnVerifOpb();
		echo json_encode($result);
	}

	function editListOPB()
	{
		$result = $this->m_transaksi->editListOPB();
		echo json_encode($result);
	}

	function prosesBAPB()
	{
		$result = $this->m_transaksi->prosesBAPB();
		echo json_encode($result);
	}

	function hapusBAPB()
	{
		$result = $this->m_transaksi->hapusBAPB();
		echo json_encode($result);
	}

	function loadBarang()
	{
		$html = '';
		$barang = $this->db->query("SELECT*FROM m_barang_header ORDER BY kode_header,nm_barang");
		$html .= '<option value="">PILIH</option>';
		foreach($barang->result() as $r){
			$html .= '<option value="'.$r->id_mbh.'">'.$r->kode_header.' | '.$r->nm_barang.'</option>';
		}
		echo json_encode([
			'html' => $html,
			'material' => '',
			'size' => '',
			'merk' => '',
		]);
	}

	function detailBarang()
	{
		$html = ''; $htmlJT = '<option value="">PILIH</option>'; $htmlM = '<option value="">PILIH</option>'; $htmlS = '<option value="">PILIH</option>'; $htmlMr = '<option value="">PILIH</option>';
		$jenis_opb = $_POST["jenis_opb"];
		$departemen = $_POST["plh_departemen"];
		$id_mbh = $_POST["id_mbh"];
		$id_mbh_lama = $_POST["id_mbh_lama"];
		$jenistipe = $_POST["jenistipe"];
		$material = $_POST["material"];
		$size = $_POST["ukuran"];
		$merk = $_POST["merk"];
		if($id_mbh == $id_mbh_lama){
			($jenistipe == '') ? $wjt = "" : $wjt = "AND jenis_tipe='$jenistipe'";
			($material == '') ? $wM = "" : $wM = "AND material='$material'";
			($size == '') ? $wS = "" : $wS = "AND d.size='$size'";
			($merk == '') ? $wR = "" : $wR = "AND merk='$merk'";
			$where = "$wjt $wM $wS $wR";
		}else{
			$where = "";
		}
		if($jenis_opb == '' || $departemen == '' || $id_mbh == ''){
			$html = '';
		}else{
			$detail = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
			INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
			WHERE d.id_mbh='$id_mbh' $where GROUP BY d.kode_barang,h.nm_barang,d.jenis_tipe,d.material,d.size,d.merk,d.p_satuan");
			if($detail->num_rows() == ""){
				$html = 'DATA KOSONG!';
			}else{
				// BAGIAN
				$level = $this->session->userdata('level');
				$bagian = $this->db->query("SELECT b.id_group,b.kode_departemen,d.nama FROM m_modul_group m 
				INNER JOIN m_departemen_bagian b ON m.id_group=b.id_group
				INNER JOIN m_departemen d ON b.kode_departemen=d.kode
				WHERE m.val_group='$level' AND d.main_menu='$departemen'
				GROUP BY b.id_group,b.kode_departemen");
				$htmlBagian = '';
				foreach($bagian->result() as $b){
					$htmlBagian .= '<option value="'.$b->kode_departemen.'">'.$b->nama.'</option>';
				}
				if($jenis_opb == 'PEMBELIAN'){
					$thOpb = '';
				}else{
					$thOpb = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">HARGA (Rp.)</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 260px 6px 6px;text-align:center">SUPPLIER</th>';
				}
				$html .='<div style="margin:20px 0 0;font-weight:bold">DETAIL BARANG :</div>
				<div style="overflow:auto;white-space:nowrap"><table style="margin:0;border:1px solid #dee2e6">
					<tr>
						<th style="position:sticky;left:0;background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS/TIPE</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">SATUAN</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 12px;text-align:center">PILIH SATUAN</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">QTY</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">PENGADAAN</th>
						'.$thOpb.'
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 50px;text-align:center">KETERANGAN</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 30px;text-align:center">BAGIAN</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">AKSI</th>
					</tr>';
					$i = 100;
					foreach($detail->result() as $r){
						$i++;
						// SATUAN
						if($r->p_satuan == 1){
							$htmlSat = '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">TERKECIL</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($r->qty3,0,',','.').'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->satuan3.'</td>';
							$htmlPlhSatuan = '<option value="TERKECIL">TERKECIL</option>';
						}
						if($r->p_satuan == 2){
							$htmlSat = '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">TERBESAR<br>TERKECIL</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->satuan1.'<br>'.$r->satuan3.'</td>';
							$htmlPlhSatuan = '<option value="TERKECIL">TERKECIL</option><option value="TERBESAR">TERBESAR</option>';
						}
						if($r->p_satuan == 3){
							$htmlSat = '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">TERBESAR<br>TENGAH<br>TERKECIL</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:right">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty2,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->satuan1.'<br>'.$r->satuan2.'<br>'.$r->satuan3.'</td>';
							$htmlPlhSatuan = '<option value="TERKECIL">TERKECIL</option><option value="TENGAH">TENGAH</option><option value="TERBESAR">TERBESAR</option>';
						}
						// OPB STOK ATAU PEMBELIAN
						$hidSup = ''; $htmlSup = '';
						if($jenis_opb == 'STOK'){
							$optSup = '';
							$sup = $this->db->query("SELECT*FROM m_supplier ORDER BY nm_supp");
							foreach($sup->result() as $s){
								$optSup .= '<option value="'.$s->id_supp.'">'.$s->nm_supp.'</option>';
							}
							$htmlSup .= '<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
								<input type="text" id="harga_opb'.$i.'" class="form-control" style="width:120px;padding:3px 4px;text-align:right" onkeyup="hargaOPB('."'".$i."'".')" autocomplete="off" placeholder="0">
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
								<select id="plh_supplier'.$i.'" class="form-control select2">
									<option value="">PILIH</option>
									'.$optSup.'
								</select>
							</td>';
						}else{
							$hidSup .= '<input type="hidden" id="harga_opb'.$i.'" value="">
							<input type="hidden" id="plh_supplier'.$i.'" value="">';
						}
						// PENGADAAN
						$html .= '<tr style="vertical-align:top">
							<td style="position:sticky;left:0;background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
								<input type="hidden" id="h_id_mbh'.$i.'" value="'.$r->id_mbh.'">
								<input type="hidden" id="h_id_mbd'.$i.'" value="'.$r->id_mbd.'">
								<input type="hidden" id="h_satuan'.$i.'" value="'.$r->p_satuan.'">
								<input type="hidden" id="h_qty1_'.$i.'" value="'.round($r->qty1,2).'">
								<input type="hidden" id="h_qty2_'.$i.'" value="'.round($r->qty2,2).'">
								<input type="hidden" id="h_qty3_'.$i.'" value="'.round($r->qty3,2).'">
								<input type="hidden" id="i_qty1_'.$i.'" value="">
								<input type="hidden" id="i_qty2_'.$i.'" value="">
								<input type="hidden" id="i_qty3_'.$i.'" value="">
								<input type="hidden" id="h_satuan1_'.$i.'" value="'.$r->satuan1.'">
								<input type="hidden" id="h_satuan2_'.$i.'" value="'.$r->satuan2.'">
								<input type="hidden" id="h_satuan3_'.$i.'" value="'.$r->satuan3.'">
								'.$hidSup.'
								'.$r->kode_barang.'
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->nm_barang.'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->jenis_tipe.'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->material.'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->size.'</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">'.$r->merk.'</td>
							'.$htmlSat.'
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:center">
								<select id="plh_satuan'.$i.'" class="form-control" style="padding:3px;width:100%" onchange="pilihSatuan('."'".$i."'".')">
									'.$htmlPlhSatuan.'
								</select>
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:center">
								<input type="number" id="qty'.$i.'" class="form-control" style="width:60px;padding:3px 4px;text-align:right" onkeyup="pengadaaan('."'".$i."'".')">
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"></div></td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold;text-align:right"><div class="hitungqty'.$i.'"></div></td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"></div></td>
							'.$htmlSup.'
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;font-weight:bold">
								<textarea id="ket_pengadaan'.$i.'" class="form-control" style="padding:3px 4px;resize:none" rows="2" placeholder="-" oninput="this.value=this.value.toUpperCase()"></textarea>
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px">
								<select id="plh_bagian'.$i.'" class="form-control" style="padding:3px;width:100%">
									<option value="">PILIH</option>
									'.$htmlBagian.'
								</select>
							</td>
							<td style="background:#f2f2f2;border:1px solid #dee2e6;padding:6px;text-align:center">
								<button type="button" class="btn btn-xs btn-success" onclick="addCartOPB('."'".$i."'".')">tambah</button>
							</td>
						</tr>';
						$nr = $detail->num_rows() + 100;
						if($nr != $i){
							$html .= '<tr>
								<td style="padding:2px;border:1px solid #dee2e6;" colspan="13"></td>
							</tr>';
						}
					}
				$html .= '</table></div>';
			}
			// JENIS / TIPE
			$htmlJT .= '<option value="">PILIH</option>';
			$d_jt = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' GROUP BY jenis_tipe");
			foreach($d_jt->result() as $r2){
				$htmlJT .= '<option value="'.$r2->jenis_tipe.'">'.$r2->jenis_tipe.'</option>';
			}
			// MATERIAL
			$htmlM .= '<option value="">PILIH</option>';
			$d_m = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' GROUP BY material");
			foreach($d_m->result() as $r3){
				$htmlM .= '<option value="'.$r3->material.'">'.$r3->material.'</option>';
			}
			// SIZE
			$htmlS .= '<option value="">PILIH</option>';
			$d_s = $this->db->query("SELECT*FROM m_barang_detail d WHERE id_mbh='$id_mbh' GROUP BY d.size");
			foreach($d_s->result() as $r4){
				$htmlS .= '<option value="'.$r4->size.'">'.$r4->size.'</option>';
			}
			// MERK
			$htmlMr .= '<option value="">PILIH</option>';
			$d_mr = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' GROUP BY merk");
			foreach($d_mr->result() as $r5){
				$htmlMr .= '<option value="'.$r5->merk.'">'.$r5->merk.'</option>';
			}
		}
		echo json_encode([
			'html' => $html,
			'htmlJT' => $htmlJT,
			'htmlM' => $htmlM,
			'htmlS' => $htmlS,
			'htmlMr' => $htmlMr,
		]);
	}

	function addCartOPB()
	{
		$jenis_opb = $_POST["jenis_opb"];
		$tgl_opb = $_POST["tgl_opb"];
		$id_opbh = $_POST["id_opbh"];
		$no_opb = $_POST["no_opb"];
		$kode_departemen = $_POST["plh_departemen"];
		$id_mbh = $_POST["id_mbh"];
		$id_mbd = $_POST["id_mbd"];
		$plh_bagian = $_POST["plh_bagian"];
		$plh_satuan = $_POST["plh_satuan"];
		$qty = $_POST["qty"];
		$i_qty1 = $_POST["i_qty1"];
		$i_qty2 = $_POST["i_qty2"];
		$i_qty3 = $_POST["i_qty3"];
		$ket_pengadaan = $_POST["ket_pengadaan"];
		$harga_opb = $_POST["harga_opb"];
		$plh_supplier = $_POST["plh_supplier"];
		$status = $_POST["status"];
		if($jenis_opb == 'STOK' && $plh_supplier == ''){
			echo json_encode(['data' => false, 'msg' => 'HARAP PILIH SUPPLIER!']); return;
		}
		if($tgl_opb == ''){
			echo json_encode(['data' => false, 'msg' => 'HARAP PILIH TANGGAL!']); return;
		}
		if($jenis_opb == 'PEMBELIAN' && ($no_opb == '' || $no_opb < 0 || !preg_match("/^[0-9]*$/", $no_opb))){
			echo json_encode(['data' => false, 'msg' => 'HARAP ISI NO. OPB!']); return;
		}
		if($qty == 0 || $qty == '' || $qty < 0){
			echo json_encode(['data' => false, 'msg' => 'HARAP ISI QTY!']); return;
		}
		if($plh_bagian == ''){
			echo json_encode(['data' => false, 'msg' => 'HARAP PILIH BAGIAN!']); return;
		}
		// CEK DATA OPB
		if($status == 'update'){
			$cekOpb = $this->db->query("SELECT*FROM trs_opb_detail WHERE id_opbh='$id_opbh' AND id_mbh='$id_mbh' AND id_mbd='$id_mbd'");
			if($cekOpb->num_rows() != 0){
				echo json_encode(['data' => false, 'msg' => 'DATA SUDAH MASUK DI LIST!']); return;
			}
		}
		$data = array(
			'id' => $_POST["id_cart"],
			'name' => 'opb_'.$_POST["id_cart"],
			'price' => 0,
			'qty' => 1,
			'options' => array(
				'id_mbh' => $id_mbh,
				'id_mbd' => $id_mbd,
				'kode_departemen' => $kode_departemen,
				'plh_bagian' => $plh_bagian,
				'plh_satuan' => $plh_satuan,
				'i_qty1' => $i_qty1,
				'i_qty2' => $i_qty2,
				'i_qty3' => $i_qty3,
				'ket_pengadaan' => $ket_pengadaan,
				'harga_opb' => $harga_opb,
				'plh_supplier' => $plh_supplier,
			)
		);
		if($this->cart->total_items() != 0){
			foreach($this->cart->contents() as $r){
				if($id_mbh == $r['options']['id_mbh'] && $id_mbd == $r['options']['id_mbd']){
					echo json_encode(array('data' => false, 'msg' => 'DATA SUDAH MASUK DI LIST!')); return;
				}
			}
			$this->cart->insert($data);
			echo json_encode(array('data' => true, 'msg' => $data));
		}else{
			$this->cart->insert($data);
			echo json_encode(array('data' => true, 'msg' => $data));
		}
	}

	function cartOPB()
	{
		$jenis_opb = $_POST["jenis_opb"];
		$html = '';
		if($this->cart->total_items() == 0){
			$html .= '';
		}
		if($this->cart->total_items() != 0){
			($jenis_opb == 'STOK') ? $ss = 14 : $ss = 12;
			if($jenis_opb == 'STOK'){
				$thOPB = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">HARGA (Rp.)</th>
				<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SUPPLIER</th>';
			}else{
				$thOPB = '';
			}
			$html .='<div style="margin:20px 0 0;font-weight:bold">DETAIL LIST BARANG OPB :</div>
			<div style="overflow:auto;white-space:nowrap"><table class="table table-bordered table-striped" style="margin:0">
				<tr>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS/TIPE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">PENGADAAN</th>
					'.$thOPB.'
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KETERANGAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">BAGIAN</th>
					<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">AKSI</th>
				</tr>
				<tr>
					<td style="padding:0;border:0" colspan="12"></td>
				</tr>';
				$i = 0;
				foreach($this->cart->contents() as $r){
					$i++;
					$id_mbh = $r['options']['id_mbh'];
					$id_mbd = $r['options']['id_mbd'];
					$plh_bagian = $r['options']['plh_bagian'];
					$plh_satuan = $r['options']['plh_satuan'];
					$i_qty1 = $r['options']['i_qty1'];
					$i_qty2 = $r['options']['i_qty2'];
					$i_qty3 = $r['options']['i_qty3'];
					$ket_pengadaan = $r['options']['ket_pengadaan'];
					$harga_opb = $r['options']['harga_opb'];
					$plh_supplier = $r['options']['plh_supplier'];
					$b = $this->db->query("SELECT h.nm_barang,d.* FROM m_barang_detail d
					INNER JOIN m_barang_header h ON d.id_mbh=h.id_mbh
					WHERE d.id_mbh='$id_mbh' AND d.id_mbd='$id_mbd'")->row();
					// SATUAN
					if($b->p_satuan == 1){
						$htmlPgd = '<td style="padding:6px;font-weight:bold;color:#f00">TERKECIL</td>
						<td style="padding:6px;text-align:right;font-weight:bold;color:#f00">'.number_format($i_qty3,0,',','.').'</td>
						<td style="padding:6px;font-weight:bold;color:#f00">'.$b->satuan3.'</td>';
					}
					if($b->p_satuan == 2){
						if($plh_satuan == 'TERBESAR'){
							$s1 = 'style="color:#f00"'; $s3 = '';
						}
						if($plh_satuan == 'TERKECIL'){
							$s1 = ''; $s3 = 'style="color:#f00"';
						}
						$htmlPgd = '<td style="padding:6px;font-weight:bold"><div '.$s1.'>TERBESAR</div><div '.$s3.'>TERKECIL</div></td>
						<td style="padding:6px;text-align:right;font-weight:bold"><div '.$s1.'>'.round($i_qty1,2).'</div><div '.$s3.'>'.number_format($i_qty3,0,',','.').'</div></td>
						<td style="padding:6px;font-weight:bold"><div '.$s1.'>'.$b->satuan1.'</div><div '.$s3.'>'.$b->satuan3.'</div></td>';
					}
					if($b->p_satuan == 3){
						if($plh_satuan == 'TERBESAR'){
							$s1 = 'style="color:#f00"'; $s2 = ''; $s3 = '';
						}
						if($plh_satuan == 'TENGAH'){
							$s1 = ''; $s2 = 'style="color:#f00"'; $s3 = '';
						}
						if($plh_satuan == 'TERKECIL'){
							$s1 = ''; $s2 = ''; $s3 = 'style="color:#f00"';
						}
						$htmlPgd = '<td style="padding:6px;font-weight:bold"><div '.$s1.'>TERBESAR</div><div '.$s2.'>TENGAH</div><div '.$s3.'>TERKECIL</div></td>
						<td style="padding:6px;text-align:right;font-weight:bold"><div '.$s1.'>'.round($i_qty1,2).'</div><div '.$s2.'>'.round($i_qty2,2).'</div><div '.$s3.'>'.number_format($i_qty3,0,',','.').'</div></td>
						<td style="padding:6px;font-weight:bold"><div '.$s1.'>'.$b->satuan1.'</div><div '.$s2.'>'.$b->satuan2.'</div><div '.$s3.'>'.$b->satuan3.'</div></td>';
					}
					// KETERANGAN
					($ket_pengadaan == '') ? $keterangan = '-' : $keterangan = $ket_pengadaan;
					// BAGIAN
					$bagian = $this->db->query("SELECT*FROM m_departemen WHERE kode='$plh_bagian'")->row();
					// STOK DAN PEMBELIAN
					if($jenis_opb == 'STOK'){
						// $harga_opb $plh_supplier
						$sup = $this->db->query("SELECT*FROM m_supplier WHERE id_supp='$plh_supplier'")->row();
						$tdOPB = '<td style="padding:6px;text-align:right">'.number_format($harga_opb,0,',','.').'</td>
						<td style="padding:6px">'.$sup->nm_supp.'</td>';
					}else{
						$tdOPB = '';
					}
					$html .= '<tr>
						<td style="padding:6px">'.$b->kode_barang.'</td>
						<td style="padding:6px">'.$b->nm_barang.'</td>
						<td style="padding:6px">'.$b->jenis_tipe.'</td>
						<td style="padding:6px">'.$b->material.'</td>
						<td style="padding:6px">'.$b->size.'</td>
						<td style="padding:6px">'.$b->merk.'</td>
						'.$htmlPgd.'
						'.$tdOPB.'
						<td style="padding:6px">'.$keterangan.'</td>
						<td style="padding:6px">'.$bagian->nama.'</td>
						<td style="padding:6px;text-align:center">
							<button type="button" class="btn btn-sm" onclick="hapusCart('."'".$r['rowid']."'".')"><i class="fas fa-times-circle" style="color:#f00"></i></button>
						</td>
					</tr>';
					if($this->cart->total_items() != $i){
						$html .= '<tr>
							<td style="padding:2px;border:0" colspan="'.$ss.'"></td>
						</tr>';
					}
				}
				$html .= '<tr>
					<td style="padding:6px;text-align:right" colspan="'.$ss.'">
						<button type="button" class="btn btn-sm btn-primary" style="font-weight:bold" onclick="simpanOPB()"><i class="fas fa-save"></i> SIMPAN</button>
					</td>
				</tr>';
			$html .='</table></div>';
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	function loadHeader()
	{
		$username = $this->session->userdata('username');
		$level = $this->session->userdata('level');
		$approve = $this->session->userdata('approve');
		$opsi = $_POST["opsi"];
		// OPSI OPB ATAU BAPB
		if($opsi == 'opb'){
			($approve == 'ADMIN') ? $wApp = "AND h.creat_by='$username'" : $wApp = "";
			($approve == 'OWNER') ? $wOwn = "AND h.acc1='Y' AND h.acc2='Y'" : $wOwn = "";
			$wOpsi = "AND h.status_opb!='Approve'";
		}
		if($opsi == 'bapb'){
			$wApp = ""; $wOwn = ""; $wOpsi = "AND h.status_opb='Approve'";
		}
		// ALL
		$q_all = $this->db->query("SELECT COUNT(h.status_opb) AS aal FROM trs_opb_header h
		INNER JOIN m_departemen_bagian b ON h.kode_dpt=b.kode_departemen
		INNER JOIN m_modul_group g ON b.id_group=g.id_group
		WHERE g.val_group='$level' $wApp $wOwn $wOpsi");
		($q_all->num_rows() == 0) ? $all = '' : $all = $q_all->row()->aal;
		// PER DEPARTEMEN
		$header = $this->db->query("SELECT h.kode_dpt,t.nama,t.icon,COUNT(h.kode_dpt) AS con FROM trs_opb_header h
		INNER JOIN m_departemen t ON h.kode_dpt=t.kode
		INNER JOIN m_departemen_bagian b ON t.kode=b.kode_departemen
		INNER JOIN m_modul_group g ON b.id_group=g.id_group
		WHERE g.val_group='$level' $wApp $wOwn $wOpsi
		GROUP BY h.kode_dpt ORDER BY t.nama");
		$html = '';
		if($header->num_rows() != ''){
			$html .= '<div class="opb-menu-header" style="border-bottom:3px solid #dee2e6;overflow:auto;white-space:nowrap">
				<div style="display:flex">
					<div>
						<button type="button" id="h_0" class="boh btn-opbh-klik" onclick="btnHeader('."'0'".')">
							<span id="ff_0" class="ff ff-klik"><i class="fas fa-inbox"></i>&nbsp;&nbsp;ALL</span>
							<span style="vertical-align:top;padding:1px 4px;font-weight:bold;font-size:12px">'.$all.'</span>
						</button>
					</div>';
					if($header->num_rows() != 1){
						foreach($header->result() as $h){
							$html .= '<div>
								<button type="button" id="h_'.$h->kode_dpt.'" class="boh btn-opbh-all" onclick="btnHeader('."'".$h->kode_dpt."'".')">
									<span id="ff_'.$h->kode_dpt.'" class="ff ff-all"><i class="fas '.$h->icon.'"></i>&nbsp;&nbsp;'.$h->nama.'</span>
									<span style="vertical-align:top;padding:1px 4px;font-weight:bold;font-size:12px">'.$h->con.'</span>
								</button>
							</div>';
						}
					}
				$html .= '</div>
			</div>';
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	function loadList()
	{
		$username = $this->session->userdata('username');
		$approve = $this->session->userdata('approve');
		$level = $this->session->userdata('level');
		$opsi = $_POST["opsi"];
		$kode_dpt = $_POST["kode_dpt"];
		// OPSI OPB DAN BAPB
		if($opsi == 'opb'){
			($approve == 'ADMIN') ? $wApp = "AND h.creat_by='$username'" : $wApp = "";
			($approve == 'OWNER') ? $wOwn = "AND h.acc1='Y' AND h.acc2='Y'" : $wOwn = "";
			$wOpsi = "AND h.status_opb!='Approve'";
		}
		if($opsi == 'bapb'){
			$wApp = ""; $wOwn = ""; $wOpsi = "AND h.status_opb='Approve'";
		}
		($kode_dpt == 0) ? $wKodeDpt = "" : $wKodeDpt = "AND h.kode_dpt='$kode_dpt'";
		// QUERY LIST OPB HEADER
		$header = $this->db->query("SELECT t.nama,t.bg,h.* FROM trs_opb_header h
		INNER JOIN m_departemen t ON h.kode_dpt=t.kode
		INNER JOIN m_departemen_bagian b ON t.kode=b.kode_departemen
		INNER JOIN m_modul_group g ON b.id_group=g.id_group
		WHERE g.val_group='$level' $wKodeDpt $wApp $wOwn $wOpsi
		ORDER BY h.status_opb DESC,h.no_opb,t.nama");
		$html = '';
		if($header->num_rows() != 0){
			$html .= '<table class="table" style="margin:0">';
			$i = 0;
			foreach($header->result() as $r){
				$i++;
				// GET NAMA BAGIAN
				$qBagian = $this->db->query("SELECT t.nama FROM trs_opb_detail d
				INNER JOIN m_departemen t ON d.kode_bagian=t.kode
				WHERE d.id_opbh='$r->id_opbh' AND d.no_opb='$r->no_opb'
				GROUP BY d.kode_bagian ORDER BY t.nama");
				$nmBagian = '';
				$ii = 0;
				foreach($qBagian->result() as $b){
					$ii++;
					$nmBagian .= $b->nama;
					if($qBagian->num_rows() != $ii){
						$nmBagian .= ', ';
					}
				}
				// CEK SUDAH ACC APA BELUM
				if(($r->acc1 == 'N' && in_array($approve, ['ALL', 'ADMIN', 'ACC', 'OFFICE'])) || ($r->acc2 == 'N' && $approve == 'FINANCE') || ($r->acc3 == 'N' && $approve == 'OWNER')){
					$read1 = '<span class="rr_'.$i.'" style="position:absolute;top:6px;right:6px"><i class="fas fa-exclamation-circle" style="color:#1ed760"></i></span>';
				}else{
					$read1 = '';
				}
				// STATUS OPB
				if($r->status_opb == 'Inproses'){
					if($r->acc1 == 'N'){
						$ki = ' acc k. bag';
					}else if($r->acc2 == 'N'){
						$ki = ' acc finance';
					}else if($r->acc3 == 'N'){
						$ki = ' acc owner';
					}else{
						$ki = '';
					}
					$status = '<span class="i-opbh" style="color:#28a745">tunggu'.$ki.'</span>';
				}else if($r->status_opb == 'Hold'){
					$status = '<span class="i-opbh" style="color:#ffc107">hold</span>';
				}else if($r->status_opb == 'Batal'){
					$status = '<span class="i-opbh" style="color:#dc3545">reject</span>';
				}else if($r->status_opb == 'Approve'){
					$status = '<span class="i-opbh" style="color:#007bff">acc</span>';
				}else{
					$status = '';
				}
				$html .='<tr id="toh_'.$i.'" class="toh tr-opbh-all">
					<td class="td-opbh">
					'.$read1.$status.'
						<button type="button" id="bth_'.$i.'" class="btn-opb-header" onclick="btnDetail('."'".$r->id_opbh."'".','."'".$i."'".','."'view'".')"></button>
						'.$r->no_opb.'&nbsp;&nbsp;<span style="background:#'.$r->bg.';padding:1px 10px;color:#fff;font-weight:normal;border-radius:20px">'.$r->nama.'</span>
						<div style="padding:8px;position:relative">
							<span style="position:absolute;top:0;left:0;color:#666;font-weight:normal;font-size:12px">'.$r->creat_by.'</span>
							<span style="position:absolute;top:0;right:0;color:#666;font-weight:normal;font-size:12px">'.substr($this->m_fungsi->haru($r->tgl_opb),0,3).', '.$this->m_fungsi->tglIndSkt($r->tgl_opb).'</span>
						</div>
						<div><span style="font-style:italic;font-weight:normal">'.$nmBagian.'</span></div>
					</td>
				</tr>';
			}
			$html .= '</table>';
		}else{
			$html .= '<div style="padding:6px">TIDAK ADA OPB!</div>';
		}

		echo json_encode([
			'html' => $html,
		]);
	}

	function loadDetail()
	{
		$level = $this->session->userdata('level');
		$username = $this->session->userdata('username');
		$approve = $this->session->userdata('approve');
		$id_opbh = $_POST["id_opbh"];
		$departemen = $_POST["plh_departemen"];
		$opsi = $_POST["opsi"];
		$jenis = $_POST["jenis"];
		// OPB HEADER
		$opbh = $this->db->query("SELECT*FROM trs_opb_header WHERE id_opbh='$id_opbh'")->row();
		// OPB DETAIL
		$detail = $this->db->query("SELECT h.nm_barang,m.nama,d.*,l.* FROM trs_opb_detail l
		INNER JOIN m_barang_detail d ON l.id_mbh=d.id_mbh AND l.id_mbd=d.id_mbd
		INNER JOIN m_barang_header h ON l.id_mbh=h.id_mbh
		INNER JOIN m_departemen m ON l.kode_bagian=m.kode
		WHERE l.id_opbh='$id_opbh'
		GROUP BY l.id_opbh,l.no_opb,l.id_mbh,l.id_mbd
		ORDER BY h.nm_barang,d.kode_barang,d.jenis_tipe,d.material,d.size,d.merk,d.p_satuan");
		// CEK INPUT HARGA DAN SUPPLIER
		$opbd_sup = $this->db->query("SELECT*FROM trs_opb_detail d
		INNER JOIN trs_opb_header h ON d.id_opbh=h.id_opbh
		WHERE d.id_opbh='$id_opbh' AND d.id_supplier IS NULL AND d.dharga IS NULL");
		// VIEW DAN EDIT
		if($opsi == 'view'){
			$bgCard = 'card-primary';
			if($approve == 'ACC' || $approve == 'FINANCE' || $approve == 'OWNER'){
			$btnEdit = ''; $btnHapus = ''; $btnBAPB = ''; $btnClose = '';
			}else{
				if(($approve == 'ALL' || $approve == 'OFFICE') && $opbh->acc3 != 'Y'){
					$btnEdit = '<button type="button" class="btn btn-xs bg-gradient-dark" onclick="editOPB()">Edit</button> - ';
					$btnHapus = ' - <button type="button" class="btn btn-xs bg-gradient-danger" onclick="hapusOPB('."'header'".','."'0'".')">Hapus</button>';
				}else{
					$btnEdit = ''; $btnHapus = '';
				}
				if(($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG') && $opbh->acc3 == 'Y' && $jenis == 'bapb'){
					$btnBAPB = '<button type="button" class="btn btn-xs bg-gradient-success" onclick="editOPB()">Proses</button> - ';
					$btnClose = ' - <button type="button" class="btn btn-xs bg-gradient-danger" onclick="">Close</button>';
				}else{
					$btnBAPB = ''; $btnClose = '';
				}
			}
			$thKode = ''; $thSatuan = '';
			// SUPPLIER
			($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG' || $approve == 'FINANCE' || $approve == 'OWNER') ?
			$thSup = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">HARGA (Rp.)</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JUMLAH (Rp.)</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SUPPLIER</th>' : $thSup = '';
			$thEdit = '';
		}else{
			$bgCard = 'card-secondary';
			$btnEdit = ''; $btnHapus = ''; $btnBAPB = ''; $btnClose = '';
			$thKode = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">KODE BARANG</th>';
			$thSatuan = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">SATUAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 12px;text-align:center">PILIH SATUAN</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">QTY</th>';
			// SUPPLIER
			($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG' || $approve == 'FINANCE' || $approve == 'OWNER') ?
			$thSup = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center">HARGA (Rp.)</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JUMLAH (Rp.)</th>
			<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 260px 6px 6px">SUPPLIER</th>' : $thSup = '';
			($jenis == 'bapb') ? $stat = '<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 32px;text-align:center">STATUS</th>' : $stat = '';
			$thEdit = $stat.'<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 12px;text-align:center">AKSI</th>';
		}
		$htmlDetail = '';
		$htmlDetail .='<div class="card '.$bgCard.' card-outline" style="margin:12px 0 0">
			<div class="card-header" style="padding:10px 6px">
				<h3 class="card-title" style="font-weight:bold;font-size:18px">'.$btnEdit.$btnBAPB.'LIST DETAIL BARANG OPB'.$btnHapus.$btnClose.'</h3>
			</div>
			<div class="ldopb" style="padding:0;overflow:auto;white-space:nowrap">
				<table class="table table-bordered table-striped" style="margin:0">
					<tr>
						'.$thKode.'
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">NAMA BARANG</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">JENIS/TIPE</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MATERIAL</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">SIZE</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px">MERK</th>
						'.$thSatuan.'
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px;text-align:center" colspan="3">PENGADAAN</th>
						'.$thSup.'
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 50px;text-align:center">KETERANGAN</th>
						<th style="background:#e2e2e2;border:1px solid #828282;border-width:0 0 3px;padding:6px 32px;text-align:center">BAGIAN</th>
						'.$thEdit.'
					</tr>
					<tr>
						<td style="padding:0;border:0" colspan="11"></td>
					</tr>';
					$i = 0; $subTotal = 0;
					foreach($detail->result() as $r){
						$i++;
						($opsi == 'view') ? $fwb = ';font-weight:bold' : $fwb = '';
						($opsi == 'view') ? $cx = 13 : $cx = 21;
						// SATUAN
						$htmlSat = '';
						if($r->p_satuan == 1){
							$dqty = round($r->dqty3,2);
							if($jenis == 'opb'){
								$tdPgd = '<td style="padding:6px;font-weight:bold;color:#f00"><div class="txtsatuan'.$i.'">TERKECIL</div></td>
								<td style="padding:6px;text-align:right;font-weight:bold;color:#f00"><div class="hitungqty'.$i.'">'.round($r->dqty3,2).'</div></td>
								<td style="padding:6px;font-weight:bold;color:#f00"><div class="ketsatuan'.$i.'">'.$r->dsatuan3.'</div></td>';
							}else{
								$tdPgd = '<td style="padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold;text-align:right"><div class="hitungqty'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"></div></td>';
							}
							if($jenis == 'opb'){
								$htmlSat .= $tdPgd;
							}else{
								$htmlSat .= '<td style="padding:6px;color:#f00'.$fwb.'">TERKECIL</td>
								<td style="padding:6px;text-align:right;color:#f00'.$fwb.'">'.round($r->dqty3,2).'</td>
								<td style="padding:6px;color:#f00'.$fwb.'">'.$r->dsatuan3.'</td>';
							}
						}
						if($r->p_satuan == 2){
							if($r->dsatuan == 'TERBESAR'){
								$s1 = 'style="color:#f00"'; $s3 = ''; $dqty = round($r->dqty1,2);
							}
							if($r->dsatuan == 'TERKECIL'){
								$s1 = ''; $s3 = 'style="color:#f00"'; $dqty = round($r->dqty3,2);
							}
							if($jenis == 'opb'){
								$tdPgd = '<td style="padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"><div '.$s1.'>TERBESAR</div><div '.$s3.'>TERKECIL</div></div></td>
								<td style="padding:6px;text-align:right;font-weight:bold"><div class="hitungqty'.$i.'"><div '.$s1.'>'.round($r->dqty1,2).'</div><div '.$s3.'>'.round($r->dqty3,2).'</div></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"><div '.$s1.'>'.$r->dsatuan1.'</div><div '.$s3.'>'.$r->dsatuan3.'</div></div></td>';
							}else{
								$tdPgd = '<td style="padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold;text-align:right"><div class="hitungqty'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"></div></td>';
							}
							if($jenis == 'opb'){
								$htmlSat .= $tdPgd;
							}else{
								$htmlSat .= '<td style="padding:6px'.$fwb.'"><div '.$s1.'>TERBESAR</div><div '.$s3.'>TERKECIL</div></td>
								<td style="padding:6px;text-align:right'.$fwb.'"><div '.$s1.'>'.round($r->dqty1,2).'</div><div '.$s3.'>'.round($r->dqty3,2).'</div></td>
								<td style="padding:6px'.$fwb.'"><div '.$s1.'>'.$r->dsatuan1.'</div><div '.$s3.'>'.$r->dsatuan3.'</div></td>';
							}
						}
						if($r->p_satuan == 3){
							if($r->dsatuan == 'TERBESAR'){
								$s1 = 'style="color:#f00"'; $s2 = ''; $s3 = ''; $dqty = round($r->dqty1,2);
							}
							if($r->dsatuan == 'TENGAH'){
								$s1 = ''; $s2 = 'style="color:#f00"'; $s3 = ''; $dqty = round($r->dqty2,2);
							}
							if($r->dsatuan == 'TERKECIL'){
								$s1 = ''; $s2 = ''; $s3 = 'style="color:#f00"'; $dqty = round($r->dqty3,2);
							}
							if($jenis == 'opb'){
								$tdPgd = '<td style="padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"><div '.$s1.'>TERBESAR</div><div '.$s2.'>TENGAH</div><div '.$s3.'>TERKECIL</div></div></td>
								<td style="padding:6px;text-align:right;font-weight:bold"><div class="hitungqty'.$i.'"><div '.$s1.'>'.round($r->dqty1,2).'</div><div '.$s2.'>'.round($r->dqty2,2).'</div><div '.$s3.'>'.round($r->dqty3,2).'</div></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"><div '.$s1.'>'.$r->dsatuan1.'</div><div '.$s2.'>'.$r->dsatuan2.'</div><div '.$s3.'>'.$r->dsatuan3.'</div></div></td>';
							}else{
								$tdPgd = '<td style="padding:6px;font-weight:bold"><div class="txtsatuan'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold;text-align:right"><div class="hitungqty'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ketsatuan'.$i.'"></div></td>';
							}
							if($jenis == 'opb'){
								$htmlSat .= $tdPgd;
							}else{
								$htmlSat .= '<td style="padding:6px'.$fwb.'"><div '.$s1.'>TERBESAR</div><div '.$s2.'>TENGAH</div><div '.$s3.'>TERKECIL</div></td>
								<td style="padding:6px;text-align:right'.$fwb.'"><div '.$s1.'>'.round($r->dqty1,2).'</div><div '.$s2.'>'.round($r->dqty2,2).'</div><div '.$s3.'>'.round($r->dqty3,2).'</div></td>
								<td style="padding:6px'.$fwb.'"><div '.$s1.'>'.$r->dsatuan1.'</div><div '.$s2.'>'.$r->dsatuan2.'</div><div '.$s3.'>'.$r->dsatuan3.'</div></td>';
							}
						}
						// VIEW DAN EDIT
						($r->dharga == null) ? $harga = 0 : $harga = number_format($r->dharga,0,',','.');
						($r->dharga == null) ? $jumlah = 0 : $jumlah = $r->dharga * $dqty;
						if($opsi == 'view'){
							$tdKode = ''; $tdSat = ''; $tdSatuan = ''; $htmlTdSatQty = '';
							if($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG' || $approve == 'FINANCE' || $approve == 'OWNER'){
								$sup = $this->db->query("SELECT*FROM m_supplier WHERE id_supp='$r->id_supplier'");
								($sup->num_rows() == 0) ? $tdsp = '-' : $tdsp = $sup->row()->nm_supp;
								$htmlSup = '<td style="padding:6px;font-weight:bold;text-align:right">'.$harga.'</td>
								<td style="padding:6px;font-weight:bold;text-align:right">'.number_format($jumlah,0,',','.').'</td>
								<td style="padding:6px;font-weight:bold">'.$tdsp.'</td>';
							}else{
								$htmlSup = '';
							}
							$htmlKet = '<td style="padding:6px">'.$r->ket_pengadaan.'</td>';
							$htmlBagian = '<td style="padding:6px">'.$r->nama.'</td>';
							$htmlAksi = '';
						}else{
							$tdKode = '<td style="padding:6px">
								<input type="hidden" id="h_id_opbd_'.$i.'" value="'.$r->id_opbd.'">
								<input type="hidden" id="h_id_mbh'.$i.'" value="'.$r->id_mbh.'">
								<input type="hidden" id="h_id_mbd'.$i.'" value="'.$r->id_mbd.'">
								<input type="hidden" id="h_satuan'.$i.'" value="'.$r->p_satuan.'">
								<input type="hidden" id="h_qty1_'.$i.'" value="'.round($r->qty1,2).'">
								<input type="hidden" id="h_qty2_'.$i.'" value="'.round($r->qty2,2).'">
								<input type="hidden" id="h_qty3_'.$i.'" value="'.round($r->qty3,2).'">
								<input type="hidden" id="i_qty1_'.$i.'" value="'.round($r->dqty1,2).'">
								<input type="hidden" id="i_qty2_'.$i.'" value="'.round($r->dqty2,2).'">
								<input type="hidden" id="i_qty3_'.$i.'" value="'.round($r->dqty3,2).'">
								<input type="hidden" id="h_satuan1_'.$i.'" value="'.$r->satuan1.'">
								<input type="hidden" id="h_satuan2_'.$i.'" value="'.$r->satuan2.'">
								<input type="hidden" id="h_satuan3_'.$i.'" value="'.$r->satuan3.'">
								'.$r->kode_barang.'
							</td>';
							// SATUAN
							if($r->p_satuan == 1){
								$tdSat = '<td style="padding:6px">TERKECIL</td>
								<td style="padding:6px;text-align:right">'.number_format($r->qty3,0,',','.').'</td>
								<td style="padding:6px">'.$r->satuan3.'</td>';
								$st = array('TERKECIL');
							}
							if($r->p_satuan == 2){
								$tdSat = '<td style="padding:6px">TERBESAR<br>TERKECIL</td>
								<td style="padding:6px;text-align:right">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
								<td style="padding:6px">'.$r->satuan1.'<br>'.$r->satuan3.'</td>';
								$st = array('TERKECIL', 'TERBESAR');
							}
							if($r->p_satuan == 3){
								$tdSat = '<td style="padding:6px">TERBESAR<br>TENGAH<br>TERKECIL</td>
								<td style="padding:6px;text-align:right">'.number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty2,0,',','.').'<br>'.number_format($r->qty3,0,',','.').'</td>
								<td style="padding:6px">'.$r->satuan1.'<br>'.$r->satuan2.'<br>'.$r->satuan3.'</td>';
								$st = array('TERKECIL', 'TENGAH', 'TERBESAR');
							}
							// PILIH SATUAN DAN QTY
							$htmlPlhSatuan = '';
							foreach($st as $t){
								if($jenis == 'opb'){
									($r->dsatuan == $t) ? $slt = ' selected' : $slt = '';
								}else{
									$slt = '';
								}
								$htmlPlhSatuan .= '<option value="'.$t.'"'.$slt.'>'.$t.'</option>';
							}
							($jenis == 'opb') ? $vdgty = $dqty : $vdgty = '';
							$tdSatuan = '<td style="padding:6px;text-align:center">
								<select id="plh_satuan'.$i.'" class="form-control" style="padding:3px;width:100%" onchange="pilihSatuan('."'".$i."'".')">
									'.$htmlPlhSatuan.'
								</select>
							</td>
							<td style="padding:6px;text-align:center">
								<input type="number" id="qty'.$i.'" class="form-control" style="width:60px;padding:3px 4px;text-align:right" value="'.$vdgty.'" onkeyup="pengadaaan('."'".$i."'".')">
							</td>';
							$htmlTdSatQty = '';
							if($jenis == 'opb'){
								$htmlTdSatQty .= $tdSatuan;
							}else{
								$htmlTdSatQty .= '<td style="padding:6px">'.$r->dsatuan.'</td>
								<td style="padding:6px;text-align:right">'.number_format($dqty,0,',','.').'</td>';
							}
							// HARGA, JUMLAH, PILIH SUPPLIER //
							$htmlSup = '';
							if(($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG') && $opbh->acc1 == 'Y'){
								$sup = $this->db->query("SELECT*FROM m_supplier ORDER BY nm_supp");
								$optSup = '';
								foreach($sup->result() as $s){
									if($jenis == 'opb'){
										($s->id_supp == $r->id_supplier) ? $slp = ' selected' : $slp = '';
									}else{
										$slp = '';
									}
									$optSup .= '<option value="'.$s->id_supp.'"'.$slp.'>'.$s->nm_supp.'</option>';
								}
								($jenis == 'opb') ? $tharga = $harga : $tharga = '';
								($jenis == 'opb') ? $tjumlah = number_format($jumlah,0,',','.') : $tjumlah = 0;
								$tdSup = '<td style="padding:6px">
									<input type="text" id="harga_opb'.$i.'" class="form-control" style="width:120px;padding:3px 4px;text-align:right" value="'.$tharga.'" autocomplete="off" onkeyup="hargaOPB('."'".$i."'".')" placeholder="0">
								</td>
								<td style="padding:6px">
									<input type="text" id="jumlah_opb'.$i.'" class="form-control" style="width:120px;padding:3px 4px;color:#000;background:none;border:0;font-weight:bold;text-align:right" value="'.$tjumlah.'" disabled>
								</td>
								<td style="padding:6px">
									<select id="plh_supplier'.$i.'" class="form-control select2">
										<option value="">PILIH</option>
										'.$optSup.'
									</select>
								</td>';
							}else{
								$tdSup = '<td style="padding:6px">
									<input type="hidden" id="harga_opb'.$i.'" value="">
									<input type="text" class="form-control" style="width:120px;padding:3px 4px;text-align:right" autocomplete="off" placeholder="0" disabled>
								</td>
								<td style="padding:6px">
									<input type="hidden" id="jumlah_opb'.$i.'" value="">
									<input type="text" class="form-control" style="width:120px;padding:3px 4px;color:#000;background:none;border:0;font-weight:bold;text-align:right" placeholder="0" disabled>
								</td>
								<td style="padding:6px">
									<select id="plh_supplier'.$i.'" class="form-control select2" disabled>
										<option value="">PILIH</option>
									</select>
								</td>';
							}
							if($jenis == 'opb'){
								$htmlSup .= $tdSup;
							}else{
								$nmSupp = $this->db->query("SELECT*FROM m_supplier WHERE id_supp='$r->id_supplier'")->row();
								$htmlSup .= '<td style="padding:6px;text-align:right">'.$harga.'</td>
								<td style="padding:6px;text-align:right">'.number_format($jumlah,0,',','.').'</td>
								<td style="padding:6px">'.$nmSupp->nm_supp.'</td>';
							}
							// KETERANGAN
							$htmlKet = '';
							($jenis == 'opb') ? $tketpgd = $r->ket_pengadaan : $tketpgd = '';
							$tdKet = '<td style="padding:6px">
								<textarea id="ket_pengadaan'.$i.'" class="form-control" style="padding:3px 4px;resize:none" rows="2" placeholder="-" oninput="this.value=this.value.toUpperCase()">'.$tketpgd.'</textarea>
							</td>';
							if($jenis == 'opb'){
								$htmlKet .= $tdKet;
							}else{
								$htmlKet .= '<td style="padding:6px">'.$r->ket_pengadaan.'</td>';
							}
							// BAGIAN
							$bagian = $this->db->query("SELECT b.id_group,b.kode_departemen,d.nama FROM m_modul_group m 
							INNER JOIN m_departemen_bagian b ON m.id_group=b.id_group
							INNER JOIN m_departemen d ON b.kode_departemen=d.kode
							WHERE m.val_group='$level' AND d.main_menu='$departemen'
							GROUP BY b.id_group,b.kode_departemen");
							$optBagian = '';
							$optBagian .= '<option value="">PILIH</option>';
							foreach($bagian->result() as $b){
								if($jenis == 'opb'){
									($r->kode_bagian == $b->kode_departemen) ? $slb = ' selected' : $slb = '';
								}else{
									$slb = '';
								}
								$optBagian .= '<option value="'.$b->kode_departemen.'"'.$slb.'>'.$b->nama.'</option>';
							}
							$htmlBagian = '';
							$tdBagian = '<td style="padding:6px">
								<select id="plh_bagian'.$i.'" class="form-control" style="padding:3px;width:100%">
									'.$optBagian.'
								</select>
							</td>';
							if($jenis == 'opb'){
								$htmlBagian .= $tdBagian;
							}else{
								$htmlBagian .= '<td style="padding:6px">'.$r->nama.'</td>';
							}
							// AKSI
							($detail->num_rows() != 1) ? $d = ' <button type="button" class="btn btn-sm" onclick="hapusOPB('."'detail'".','."'".$i."'".')"><i class="fas fa-times-circle" style="color:#f00"></i></button>' : $d = '';
							($jenis == 'opb') ? $btnAksi = '<button type="button" class="btn btn-sm" onclick="editListOPB('."'".$i."'".')"><i class="fas fa-edit"></i></button>'.$d : $btnAksi = '';
							$htmlAksi = '';
							$tdAksi = '<td style="padding:6px;text-align:center">'.$btnAksi.'</td>';
							if($jenis == 'opb'){
								$htmlAksi .= $tdAksi;
							}else{
								$htmlAksi .= '<td style="padding:6px;text-align:center">-</td>
								<td style="padding:6px;text-align:center">-</td>';
							}
						}
						$htmlDetail .= '<tr>
							'.$tdKode.'
							<td style="padding:6px">'.$r->nm_barang.'</td>
							<td style="padding:6px">'.$r->jenis_tipe.'</td>
							<td style="padding:6px">'.$r->material.'</td>
							<td style="padding:6px">'.$r->size.'</td>
							<td style="padding:6px">'.$r->merk.'</td>
							'.$tdSat.$htmlTdSatQty.$htmlSat.$htmlSup.$htmlKet.$htmlBagian.$htmlAksi.'
						</tr>';
						// TAMPIL OPB YANG SUDAH BAPB
						$htmlBapb = '';
						$cekBapb = $this->db->query("SELECT s.nm_supp,d.nama,l.*,b.* FROM trs_bapb b
						INNER JOIN m_barang_detail l ON b.id_mbh=l.id_mbh AND b.id_mbd=l.id_mbd
						INNER JOIN m_supplier s ON b.bid_supplier=s.id_supp
						INNER JOIN m_departemen d ON b.bkode_bagian=d.kode
						WHERE b.id_opbd='$r->id_opbd' ORDER BY b.tgl_bapb");
						if($cekBapb->num_rows() != 0){
							$sum1 = 0; $sum2 = 0; $sum3 = 0;
							foreach($cekBapb->result() as $p){
								$sum1 += ($p->bqty1 == null) ? 0 : round($p->bqty1,2);
								$sum2 += ($p->bqty2 == null) ? 0 : round($p->bqty2,2);
								$sum3 += ($p->bqty3 == null) ? 0 : round($p->bqty3,2);
								// QTY DAN SATUAN
								if($p->b_satuan == 1){
									$bqty = round($p->bqty3,2);
									$tdQtySatBapb = '<td style="padding:6px;color:#f00">TERKECIL</td>
									<td style="padding:6px;color:#f00;text-align:right">'.round($p->bqty3,2).'</td>
									<td style="padding:6px;color:#f00">'.$p->bsatuan3.'</td>';
								}
								if($p->b_satuan == 2){
									if($p->bsatuan == 'TERBESAR'){
										$p1 = 'style="color:#f00"'; $p3 = ''; $bqty = round($p->bqty1,2);
									}
									if($p->bsatuan == 'TERKECIL'){
										$p1 = ''; $p3 = 'style="color:#f00"'; $bqty = round($p->bqty3,2);
									}
									$tdQtySatBapb = '<td style="padding:6px"><div '.$p1.'>TERBESAR</div><div '.$p3.'>TERKECIL</div></td>
									<td style="padding:6px;text-align:right"><div '.$p1.'>'.round($p->bqty1,2).'</div><div '.$p3.'>'.round($p->bqty3,2).'</div></td>
									<td style="padding:6px"><div '.$p1.'>'.$p->bsatuan1.'</div><div '.$p3.'>'.$p->bsatuan3.'</div></td>';
								}
								if($p->b_satuan == 3){
									if($p->bsatuan == 'TERBESAR'){
										$p1 = 'style="color:#f00"'; $p2 = ''; $p3 = ''; $bqty = round($p->bqty1,2);
									}
									if($p->bsatuan == 'TENGAH'){
										$p1 = ''; $p2 = 'style="color:#f00"'; $p3 = ''; $bqty = round($p->bqty2,2);
									}
									if($p->bsatuan == 'TERKECIL'){
										$p1 = ''; $p2 = ''; $p3 = 'style="color:#f00"'; $bqty = round($p->bqty3,2);
									}
									$tdQtySatBapb = '<td style="padding:6px"><div '.$p1.'>TERBESAR</div><div '.$p2.'>TENGAH</div><div '.$p3.'>TERKECIL</div></td>
									<td style="padding:6px;text-align:right"><div '.$p1.'>'.round($p->bqty1,2).'</div><div '.$p2.'>'.round($p->bqty2,2).'</div><div '.$p3.'>'.round($p->bqty3,2).'</div></td>
									<td style="padding:6px"><div '.$p1.'>'.$p->bsatuan1.'</div><div '.$p2.'>'.$p->bsatuan2.'</div><div '.$p3.'>'.$p->bsatuan3.'</div></td>';
								}
								// JUMLAH
								$pJumlah = $bqty * $p->bharga;
								// KETERANGAN
								($p->bket_pengadaan == null) ? $bket_pengadaan = '-' : $bket_pengadaan = $p->bket_pengadaan;
								// STATUS DAN AKSI
								if($opsi == 'edit'){
									$aksiBAPB = '<td style="padding:6px;font-weight:bold;text-align:center">'.$p->acc_bapb.'</td>
									<td style="padding:6px;text-align:center">
										<button type="button" class="btn btn-sm" onclick="hapusBAPB('."'".$p->id_bapb."'".')"><i class="fas fa-times-circle" style="color:#f00"></i></button>
									</td>';
								}else{
									$aksiBAPB = '';
								}
								($opsi == 'view') ? $cz = 3 : $cz = 9;
								$x = ((rand(50, 100) * rand(1, 10)) - rand(1, 50)) + rand(1, 50);
								($p->acc_bapb == 'STOK') ? $btnQrc = '<button type="button" class="btn btn-sm btn-light" onclick="btnQRCode('."'".$x."'".')"><i class="fas fa-qrcode" style="color:#000"></i></button>' : $btnQrc = '-';
								if($r->id_mbd != $p->id_mbd){
									if($opsi == 'view'){
										$htmlBarangBeda = '<td style="padding:6px;font-weight:bold;text-align:right" colspan="'.$cz.'"><span style="font-size:11px;vertical-align:top">'.$p->acc_bapb.'</span> -</td>';
									}else{
										$htmlBarangBeda = '<td style="padding:6px" colspan="2"></td>
										<td style="padding:6px">'.$p->jenis_tipe.'</td>
										<td style="padding:6px">'.$p->material.'</td>
										<td style="padding:6px">'.$p->size.'</td>
										<td style="padding:6px">'.$p->merk.'</td>
										<td style="padding:6px;text-align:right" colspan="3">'.$btnQrc.'</td>';
									}
								}else{
									if($opsi == 'view'){
										$htmlBarangBeda = '<td style="padding:6px;font-weight:bold;text-align:right" colspan="'.$cz.'"><span style="font-size:11px;vertical-align:top">'.$p->acc_bapb.'</span></td>';
									}else{
										$htmlBarangBeda = '<td style="padding:6px;text-align:right" colspan="'.$cz.'">'.$btnQrc.'</td>';
									}
								}
								// TES QR CODE
								$htmlQrCode = '';
								if($opsi == 'edit' && $jenis == 'bapb'){
									$qr = $this->db->query("SELECT*FROM m_qrcode WHERE id_bapb='$p->id_bapb'");
									if($qr->num_rows() != 0){
										$htmlQrCode .= '<tr class="qrqr trqr1-'.$x.'" style="display:none">
											<td style="padding:2px;border:0" colspan="'.$cx.'"></td>
										</tr>
										<tr class="qrqr trqr2-'.$x.'" style="display:none">
											<td style="padding:0;text-align:right" colspan="9">
												<input type="hidden" id="h_tr" value="">
												<img src="'.base_url('/assets/qrcode/'.$qr->row()->qrcode_path).'" alt="'.$qr->row()->qrcode_data.'" width="200" height="200">
											</td>
											<td style="padding:6px" colspan="14"></td>
										</tr>';
									}
								}
								$htmlBapb .= '<tr>
									<td style="padding:2px;border:0" colspan="'.$cx.'"></td>
								</tr>
								<tr style="font-style:italic">
									'.$htmlBarangBeda.'
									<td style="padding:6px" colspan="2">'.$this->m_fungsi->haru($p->tgl_bapb).', '.$this->m_fungsi->tanggal_format_indonesia($p->tgl_bapb).'</td>
									'.$tdQtySatBapb.'
									<td style="padding:6px;text-align:right">'.number_format($p->bharga,0,',','.').'</td>
									<td style="padding:6px;text-align:right">'.number_format($pJumlah,0,',','.').'</td>
									<td style="padding:6px">'.$p->nm_supp.'</td>
									<td style="padding:6px">'.$bket_pengadaan.'</td>
									<td style="padding:6px">'.$p->nama.'</td>
									'.$aksiBAPB.'
								</tr>'.$htmlQrCode;
							}
							// TOTAL / KEKURANGAN
							$satSat = $this->db->query("SELECT b_satuan FROM trs_bapb WHERE id_opbd='$r->id_opbd' GROUP BY b_satuan")->num_rows();
							if($satSat == 1){
								if($p->b_satuan == 1){
									$hitSum3 = round($sum3,2) - round($r->dqty3,2);
									($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
									$tdTotQtySatBapb = '<td style="padding:6px;font-weight:bold"><div>TERKECIL</div></td>
									<td style="padding:6px;font-weight:bold;text-align:right"><div>'.$thitSum3.'</div></td>
									<td style="padding:6px;font-weight:bold"><div>'.$p->bsatuan3.'</div></td>';
								}
								if($p->b_satuan == 2){
									$hitSum1 = round($sum1,2) - round($r->dqty1,2);
									$hitSum3 = round($sum3,2) - round($r->dqty3,2);
									($hitSum1 > 0) ? $thitSum1 = '+'.round($hitSum1,2) : $thitSum1 = round($hitSum1,2);
									($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
									$tdTotQtySatBapb = '<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TERKECIL</div></td>
									<td style="padding:6px;font-weight:bold;text-align:right"><div>'.$thitSum1.'</div><div>'.$thitSum3.'</div></td>
									<td style="padding:6px;font-weight:bold"><div>'.$p->bsatuan1.'</div><div>'.$p->bsatuan3.'</div></td>';
								}
								if($p->b_satuan == 3){
									$hitSum1 = round($sum1,2) - round($r->dqty1,2);
									$hitSum2 = round($sum2,2) - round($r->dqty2,2);
									$hitSum3 = round($sum3,2) - round($r->dqty3,2);
									($hitSum1 > 0) ? $thitSum1 = '+'.round($hitSum1,2) : $thitSum1 = round($hitSum1,2);
									($hitSum2 > 0) ? $thitSum2 = '+'.round($hitSum2,2) : $thitSum2 = round($hitSum2,2);
									($hitSum3 > 0) ? $thitSum3 = '+'.round($hitSum3,2) : $thitSum3 = round($hitSum3,2);
									$tdTotQtySatBapb = '<td style="padding:6px;font-weight:bold"><div>TERBESAR</div><div>TENGAH</div><div>TERKECIL</div></td>
									<td style="padding:6px;font-weight:bold;text-align:right"><div>'.$thitSum1.'</div><div>'.$thitSum2.'</div><div>'.$thitSum3.'</div></td>
									<td style="padding:6px;font-weight:bold"><div>'.$p->bsatuan1.'</div><div>'.$p->bsatuan2.'</div><div>'.$p->bsatuan3.'</div></td>';
								}
								($opsi == 'view') ? $xz = 5 : $xz = 11;
								($opsi == 'view') ? $zx = 5 : $zx = 7;
								if(($p->b_satuan == 1 && $hitSum3 != 0) || ($p->b_satuan == 2 && ($hitSum1 != 0 || $hitSum3 != 0)) || ($p->b_satuan == 3 && ($hitSum1 != 0 || $hitSum2 != 0 || $hitSum3 != 0))){
									$htmlBapb .= '<tr>
										<td style="padding:2px;border:0" colspan="'.$cx.'"></td>
									</tr>
									<tr style="font-style:italic">
										<td style="padding:6px" colspan="'.$xz.'"></td>
										'.$tdTotQtySatBapb.'
										<td style="padding:6px" colspan="'.$zx.'"></td>
									</tr>';
								}
							}
						}
						$htmlDetail .= $htmlBapb;
						// BAPB
						if($jenis == 'bapb' && $opsi == 'edit'){
							// JENIS / TIPE, MATERIAL, SIZE, MERK, SATUAN
							$pSpek = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$r->id_mbh' ORDER BY kode_barang");
							$sltSpek = '<select id="id_mbd_BAPB_'.$i.'" class="form-control select2" style="width:350px" onchange="pilihBarangBAPB('."'".$i."'".')">';
								foreach($pSpek->result() as $os){
									($r->id_mbd == $os->id_mbd) ? $sld = ' selected' : $sld = '';
									$sltSpek .= '<option value="'.$os->id_mbd.'"'.$sld.'>'.$os->jenis_tipe.' | '.$os->material.' | '.$os->size.' | '.$os->merk.' | '.$os->p_satuan.'</option>';
								}
							$sltSpek .= '</select>';
							$htmlDetail .= '<tr>
								<td style="padding:2px;border:0" colspan="'.$cx.'"></td>
							</tr>
							<tr>
								<td style="padding:6px;font-weight:bold;text-align:right">BAPB :</td>
								<td style="padding:6px;font-weight:bold;text-align:right">
									<input type="date" id="tgl_bapb_'.$i.'" class="form-control"">
								</td>
								<td style="padding:6px;font-weight:bold" colspan="4">'.$sltSpek.'</td>
								<td style="padding:6px;font-weight:bold"><div class="ptd1_'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold;text-align:right"><div class="ptd2_'.$i.'"></div></td>
								<td style="padding:6px;font-weight:bold"><div class="ptd3_'.$i.'"></div></td>
								'.$tdSatuan.$tdPgd.$tdSup.$tdKet.$tdBagian.'
								<td style="padding:6px">
									<select id="app_bapb'.$i.'" class="form-control">
										<option value="">PILIH</option>
										<option value="STOK">STOK</option>
										<option value="REJECT">REJECT</option>
									</select>
								</td>
								<td style="padding:6px;text-align:center">
									<button type="button" class="btn btn-xs btn-success" onclick="prosesBAPB('."'".$i."'".')">proses</button>
								</td>
							</tr>';
						}
						if($detail->num_rows() != $i){
							($jenis == 'bapb') ? $bgb = ';background:#828282' : $bgb = '';
							$htmlDetail .= '<tr>
								<td style="padding:2px;border:0'.$bgb.'" colspan="'.$cx.'"></td>
							</tr>';
						}
						// TOTAL
						$subTotal += $jumlah;
					}
					// TOTAL
					if(($approve == 'ALL' || $approve == 'OFFICE' || $approve == 'GUDANG' || $approve == 'FINANCE' || $approve == 'OWNER') && $jenis == 'opb'){
						if($opsi == 'view'){
							$cs = 9; $c2 = 3;
						}else{
							$cs = 15; $c2 = 6;
						}
						$htmlDetail .= '<tr>
							<td style="padding:2px;border:0" colspan="'.$cx.'"></td>
						</tr>
						<tr>
							<td style="padding:6px;font-weight:bold;text-align:right" colspan="'.$cs.'">TOTAL</td>
							<td style="padding:6px;font-weight:bold;text-align:right">
								<input type="text" class="form-control" style="padding:0;color:#000;height:23px;background:none;border:0;font-weight:bold;text-align:right" value="'.number_format($subTotal,0,',','.').'" disabled>
							</td>
							<td style="padding:6px" colspan="'.$c2.'"></td>
						</tr>';
					}
				$htmlDetail .= '</table>
			</div>
		</div>';
		echo json_encode([
			'opbds' => $opbd_sup->num_rows(),
			'opbh' => $opbh,
			'time1' => ($opbh->time1 == null) ? '' : substr($this->m_fungsi->haru($opbh->time1),0,3).', '.$this->m_fungsi->tglIndSkt(substr($opbh->time1, 0,10)).' ( '.substr($opbh->time1, 10,6).' )',
			'time2' => ($opbh->time2 == null) ? '' : substr($this->m_fungsi->haru($opbh->time2),0,3).', '.$this->m_fungsi->tglIndSkt(substr($opbh->time2, 0,10)).' ( '.substr($opbh->time2, 10,6).' )',
			'time3' => ($opbh->time3 == null) ? '' : substr($this->m_fungsi->haru($opbh->time3),0,3).', '.$this->m_fungsi->tglIndSkt(substr($opbh->time3, 0,10)).' ( '.substr($opbh->time3, 10,6).' )',
			'htmlDetail' => $htmlDetail,
		]);
	}

	function pilihBarangBAPB()
	{
		$id_mbh = $_POST["id_mbh"];
		$id_mbd = $_POST["id_mbd"];
		$r = $this->db->query("SELECT*FROM m_barang_detail WHERE id_mbh='$id_mbh' AND id_mbd='$id_mbd'")->row();
		if($r->p_satuan == 1){
			$td1 = 'TERKECIL';
			$td2 = number_format($r->qty3,0,',','.');
			$td3 = $r->satuan3;
			$st = array('TERKECIL');
		}
		if($r->p_satuan == 2){
			$td1 = 'TERBESAR<br>TERKECIL';
			$td2 = number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty3,0,',','.');
			$td3 = $r->satuan1.'<br>'.$r->satuan3;
			$st = array('TERKECIL', 'TERBESAR');
		}
		if($r->p_satuan == 3){
			$td1 = 'TERBESAR<br>TENGAH<br>TERKECIL';
			$td2 = number_format($r->qty1,0,',','.').'<br>'.number_format($r->qty2,0,',','.').'<br>'.number_format($r->qty3,0,',','.');
			$td3 = $r->satuan1.'<br>'.$r->satuan2.'<br>'.$r->satuan3.'';
			$st = array('TERKECIL', 'TENGAH', 'TERBESAR');
		}
		// PILIH SATUAN DAN QTY
		$optSatuan = '';
		foreach($st as $t){
			$optSatuan .= '<option value="'.$t.'">'.$t.'</option>';
		}
		echo json_encode([
			'barang' => $r,
			'td1' => $td1,
			'td2' => $td2,
			'td3' => $td3,
			'optSatuan' => $optSatuan,
		]);
	}

	function editOPB()
	{
		$id_opbh = $_POST["id_opbh"];
		$opbh = $this->db->query("SELECT*FROM trs_opb_header WHERE id_opbh='$id_opbh'");
		echo json_encode([
			'opbh' => $opbh->row(),
		]);
	}
}
