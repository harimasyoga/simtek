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
		if($this->session->userdata('level'))
		{
			$this->load->view('Transaksi/v_opb', $data);
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


}
