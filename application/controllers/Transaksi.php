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

	function etaPO()
	{
		$data = [
			'judul' => "ETA PO",
		];
		$this->load->view('header',$data);
		$this->load->view('Transaksi/v_eta_po');
		$this->load->view('footer');
	}

	function rekap_sales()
	{
		$data = [
			'judul' => "Rekap Sales",
		];
		$this->load->view('header',$data);
		$this->load->view('Transaksi/v_rekap_sales');
		$this->load->view('footer');
	}

	function hitung_rekap()
	{
		
		$bulan = $this->input->post('bulan');

		if($bulan)
		{
			$ket= "and a.tgl_po like '%$bulan%'";
		}else{
			$ket='';
		}

		$html ='';

		$query = $this->db->query("SELECT id_sales,nm_sales,sum(ton)ton ,sum(exclude)exc, (sum(exclude)/sum(ton))avg from(
		
		select a.no_po,b.id_sales ,c.nm_sales, (a.price_exc*a.qty)exclude, a.ton
		from trs_po_detail a 
		join m_pelanggan b ON a.id_pelanggan=b.id_pelanggan
		join m_sales c ON b.id_sales=c.id_sales
		WHERE a.status <> 'Reject' 
		$ket 
		)p group by id_sales,nm_sales")->result();

		$html .='<div class="card-body row" style="padding-bottom:20px;font-weight:bold">';
		$html .='<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th style="text-align:center">NO</th>
				<th style="text-align:center">Nama Sales</th>
				<th style="text-align:center">Total PO</th>
				<th style="text-align:center">Harga Rata2 / Kg</th>
			</tr>
		</thead>';
		$i = 0;
		$total =0;
		$total_rata =0;
		if($query)
		{
		foreach($query as $r){
			$i++;
			$html .= '</tr>
				<td style="text-align:center">'.$i.'</td>
				<td style="text-align:left">'.$r->nm_sales.'</td>
				<td style="text-align:right">'.number_format($r->ton, 0, ",", ".").'</td>
				<td style="text-align:right">'.number_format($r->avg, 0, ",", ".").'</td>
			</tr>';
			$total += $r->ton; 
			$total_rata += $r->exc;
		}
		$total_all = $total_rata/$total;
		
		$html .='<tr>
				<th style="text-align:center" colspan="2" >Total</th>
				<th style="text-align:right">'.number_format($total, 0, ",", ".").'</th>
				<th style="text-align:right">'.number_format($total_all, 0, ",", ".").'</th>
			</tr>
			';
		
		$html .='</table>
		</div>';
		}else{
			$html .='<tr>
				<th style="text-align:center" colspan="4" >Data Kosong</th>
			</tr>
			';
		
		$html .='</table>
		</div>';
		}

		echo $html;
		
	}
    
    function load_so()
    {

        $query = $this->db->query("SELECT * 
		FROM trs_so_detail a
		JOIN m_produk b ON a.id_produk=b.id_produk
		JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
		WHERE status='Open' ")->result();

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
    
	function cek_plan_sementara()
    {
        
		$no_po        = $this->input->post('no_po');
		$id_produk    = $this->input->post('id_produk');

        $query = $this->db->query("SELECT * FROM plan_cor_sementara WHERE no_po = '$no_po 'and id_produk = '$id_produk' ")->num_rows();

        echo json_encode($query);
    }
	
	function plan_sementara()
    {
        
		$no_po        = $this->input->post('no_po');
		$id_produk    = $this->input->post('id_produk');

        $query = $this->db->query("SELECT * FROM plan_cor_sementara a 
		JOIN m_produk b ON a.id_produk=b.id_produk 
		WHERE a.no_po = '$no_po 'and a.id_produk = '$id_produk' ")->row();

        echo json_encode($query);
    }

    function set_ukuran()
    {
        
		$fl       = $this->input->post('fl');
        $query    = $this->db->query("SELECT * FROM m_scoring where jenis_flute    = '$fl' ")->row();

        echo json_encode($query);
    }

    function cek_kode()
    {
        $kode_po    = $this->input->post('kode_po');
        $query      = $this->db->query("SELECT count(*)jum FROM trs_po where kode_po    = '$kode_po' ")->row();

        echo json_encode($query);
    }


	public function SO()
	{
		$data = array(
			'judul' => "Sales Order",
			'getPO' => $this->db->query("SELECT * FROM trs_po WHERE Status = 'Approve' order by id")->result(),
			// 'getNoPO' => "PO-".date('Y')."-"."000000". $this->m_master->get_data_max("trs_po","no_po")
		);

		$this->load->view('header', $data);
		$this->load->view('Transaksi/v_so', $data);
		$this->load->view('footer');
	}

	public function WO()
	{
		$data = array(
			'judul' => "Work Order",
			'getSO' => $this->db->query("SELECT b.nm_produk,c.*,a.* 
            FROM trs_so_detail a
            JOIN m_produk b ON a.id_produk=b.id_produk
            JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
            WHERE status='Open' ")->result(),
		);


		$this->load->view('header', $data);
		$this->load->view('Transaksi/v_wo', $data);
		$this->load->view('footer');
	}

	public function SuratJalan()
	{
		$data = array(
			'judul' => "Surat Jalan",
			'getPO' => $this->db->query("SELECT
                                                      a.no_po
                                                    FROM
                                                      trs_po_detail a
                                                      LEFT JOIN
                                                        (SELECT
                                                          no_po,
                                                          kode_mc,
                                                          SUM(qty) AS qty_sj
                                                        FROM
                                                          `trs_surat_jalan`
                                                        WHERE STATUS <> 'Batal'
                                                        GROUP BY no_po,
                                                          kode_mc) AS t_sj
                                                        ON a.`no_po` = t_sj.no_po
                                                        AND a.kode_mc = t_sj.kode_mc
                                                        WHERE  (a.qty - IFNULL(qty_sj,0)) <> 0
                                                        GROUP BY no_po")->result(),
			// 'getNoPO' => "PO-".date('Y')."-"."000000". $this->m_master->get_data_max("trs_po","no_po")
		);


		$this->load->view('header', $data);
		$this->load->view('Transaksi/v_surat_jalan', $data);
		$this->load->view('footer');
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

	function update_plan()
	{
		$jenis    = $this->input->post('jenis');
		$status   = $this->input->post('status');
		$result   = $this->m_transaksi->update_plan($jenis, $status);
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


	function get_edit()
	{
		$id    = $this->input->post('id');
		$jenis    = $this->input->post('jenis');
		$field    = $this->input->post('field');

		if ($jenis == "trs_po") {
			$header =  $this->m_master->get_data_one($jenis, $field, $id)->row();
			// $data = $this->m_master->get_data_one("trs_po_detail", "no_po", $header->no_po)->result();

			if($header->img_po==null || $header->img_po=='') {
				$url_foto = base_url('assets/gambar_po/foto.jpg');
			}else{
				$url_foto = base_url('assets/gambar_po/') . $header->img_po;
			}

			$detail = $this->db->query("SELECT * FROM trs_po a 
                    JOIN trs_po_detail b ON a.no_po = b.no_po
                    JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
                    LEFT JOIN m_kab d ON c.kab=d.kab_id
                    LEFT JOIN m_produk e ON b.id_produk=e.id_produk
					WHERE a.no_po = '".$header->no_po."'
				")->result();

			$data = ["header" => $header, "detail" => $detail, "url_foto" => $url_foto];

		} else if ($jenis == "trs_so_detail") {
			$data =  $this->m_master->query(
				"SELECT * 
                FROM trs_so_detail a
                JOIN m_produk b ON a.id_produk=b.id_produk
                JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
                WHERE id = '$id' "
			)->row();
		} else if ($jenis == "trs_wo") {
			// $header =  $this->m_master->get_data_one($jenis, $field, $id)->row();
			$header =  $this->db->query("SELECT a.* ,CONCAT(b.no_so,'.',urut_so,'.',rpt) as no_so_1 from $jenis a LEFT JOIN trs_so_detail b ON a.no_so = b.id WHERE a.id='$id' ")->row();
			$detail = $this->m_master->get_data_one("trs_wo_detail", "no_wo", $header->no_wo)->row();

			// $data =  $this->m_master->query(
			// 	"SELECT a.id as id_wo,a.*,b.*,c.*,d.*,e.* FROM trs_wo a 
			// 	JOIN trs_wo_detail b ON a.no_wo=b.no_wo 
			// 	JOIN m_produk c ON a.id_produk=c.id_produk 
			// 	JOIN m_pelanggan d ON a.id_pelanggan=d.id_pelanggan 				
			// 	JOIN trs_so_detail e ON a.no_so=concat(e.no_so,'.',e.urut_so,'.',e.rpt)
			// 	WHERE a.id= '".$id."'
			// 	order by a.id
            //     "
			// )->row();

			$data = ["header" => $header, "detail" => $detail];
		} else if ($jenis == "SJ") {
			$header =  $this->m_master->query("SELECT a.*,IFNULL(qty_sj,0)qty_sj FROM trs_po_detail a 
                                    LEFT JOIN 
                                    (
                                    SELECT no_po,kode_mc,SUM(qty) AS qty_sj FROM `trs_surat_jalan` WHERE STATUS <> 'Batal' GROUP BY no_po,kode_mc
                                    )AS t_sj
                                    ON a.`no_po` = t_sj.no_po
                                    AND a.kode_mc = t_sj.kode_mc
                                    WHERE a.no_po ='$id' AND (a.qty - ifnull(qty_sj,0)) <> 0")->result();

			$data = ["header" => $header, "detail" => ""];
		} else if ($jenis == "SJView") {
			$header =  $this->m_master->query("SELECT a.*,IFNULL(qty_sj,0)qty_sj FROM trs_po_detail a 
                                    LEFT JOIN 
                                    (
                                    SELECT no_po,kode_mc,SUM(qty) AS qty_sj FROM `trs_surat_jalan` WHERE STATUS <> 'Batal' GROUP BY no_po,kode_mc
                                    )AS t_sj
                                    ON a.`no_po` = t_sj.no_po
                                    AND a.kode_mc = t_sj.kode_mc
                                    WHERE a.no_po ='$id' ")->result();

			$data = ["header" => $header, "detail" => ""];
		} else {
			$data =  $this->m_master->get_data_one($jenis, $field, $id)->row();
		}
		echo json_encode($data);
	}

	function status()
	{
		$jenis      = $this->input->post('jenis');
		$status      = $this->input->post('status');
		$id      = $this->input->post('id');
		$field      = $this->input->post('field');

		$result = $this->m_master->update_status($status, $id, $jenis, $field);

		echo json_encode($result);
	}

	public function print_invoice()
	{
		$id = $this->input->get('id');

		$data['id_penjualan'] = $id;

		$this->load->view('Transaksi/print_invoice', $data);
	}

	function checkout()
	{
		// $params =(object)$this->input->post();

		$valid = $this->m_transaksi->checkout();
		echo json_encode($valid);
	}

    function cek_bcf()
    {
        $kualitas = $this->input->post("kd");
        echo json_encode(array(
			"bcf" => cek_subs_bcf($kualitas)
		));
    }

    function cek_flute()
    {
        $kualitas   = $this->input->post("kd");
        $flute      = $this->input->post("flute");
        echo json_encode(array(
			"flute" => cek_subs_flute($kualitas,$flute)
		));
    }

	function Cetak_PO()
	{
		$id  = $_GET['no_po'];

		// $query = $this->m_master->get_data_one("trs_po_detail", "no_po", $id);
        $query_header = $this->db->query("SELECT * FROM trs_po a 
        JOIN m_pelanggan b ON a.id_pelanggan=b.id_pelanggan 
        WHERE a.no_po = '$id' ");
        
        $data = $query_header->row();
        
        $query = $this->db->query("SELECT * FROM trs_po a 
        JOIN trs_po_detail b ON a.no_po = b.no_po
        JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
        LEFT JOIN m_kab d ON c.kab=d.kab_id
        LEFT JOIN m_produk e ON b.id_produk=e.id_produk
        WHERE a.no_po = '$id' ");

		$html = '';


		if ($query->num_rows() > 0) {

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="15" align="center">
                            <b>( No. ' . $id . ' )</b>
                            </td>
                        </tr>
                 </table><br>';

            $html .= '<table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;">

            <tr>
                <td width="10 %"  align="left">Tgl PO</td>
                <td width="5%" > : </td>
                <td width="85 %" > '. $this->m_fungsi->tanggal_format_indonesia($data->tgl_po) .'<td>
            </tr>
            <tr>
                <td align="left">Customer</td>
                <td> : </td>
                <td> '. $data->nm_pelanggan .'<td>
            </tr>
            </table><br>';

			$html .= '<table width="100%" border="1" cellspacing="1" cellpadding="3" style="border-collapse:collapse;font-size:12px;font-family: ;">
                        <tr style="background-color: #cccccc">
                            <th width="2%" align="center">No</th>
                            <th width="10%" align="center">Item</th>
                            <th width="12%" align="center">Flute : RM : BB</th>
                            <th width="10%" align="center">Uk. Box</th>
                            <th width="8%" align="center">Uk. Sheet</th>
                            <th width="10%" align="center">Creasing </th>
                            <th width="10%" align="center">Kualitas</th>							
							<th width="10%" align="center">ETA</th>
                            <th width="8%" align="center">Qty</th>';
			if($this->session->userdata("level")!="PPIC"){

							$html .='
							<th width="10%" align="center">Harga <br> (Rp)</th>
							<th width="10%" align="center">Total <br> (Rp)</th>
							';
			}
					$html .='</tr>';
			$no = 1;
			$tot_qty = $tot_value = $tot_total = 0;
			foreach ($query->result() as $r) {

                $total = $r->price_inc*$r->qty;
				$html .= '

                            <tr >
                                <td align="center">' . $no . '</td>
                                <td align="center">' . $r->nm_produk . '</td>
                                <td align="center">' . $r->flute . ' : ' . $r->rm . ' : ' . $r->bb . '</td>
                                <td align="center">' . $r->l_panjang . ' x ' . $r->l_lebar . ' x ' . $r->l_tinggi . '</td>
                                <td align="center">' . $r->ukuran_sheet . '</td>
                                <td align="center">' . $r->creasing . ' : ' . $r->creasing2 . ' : ' . $r->creasing3 . '</td>
                                <td align="left">' . $r->kualitas . '</td>
                                <td align="center" style="color:red">' . $this->m_fungsi->tanggal_ind($r->eta) . '</td>
                                <td align="right">' . number_format($r->qty, 0, ",", ".") . '</td>								';
				if($this->session->userdata("level")!="PPIC"){
						$html .= '
								<td align="right">' . number_format($r->price_inc, 0, ",", ".") . '</td>
                                <td align="right">' . number_format($total, 0, ",", ".") . '</td>
								';
				}
						$html .= '</tr>';

				$no++;
				$tot_qty += $r->qty;
				$tot_price_inc += $r->price_inc;
				$tot_total += $total;
			}
			$html .='
                        <tr style="background-color: #cccccc">
                            <td align="center" colspan="8"><b>Total</b></td>
                            <td align="right" ><b>' . number_format($tot_qty, 0, ",", ".") . '</b></td>						
							';
			if($this->session->userdata("level")!="PPIC"){
					$html .= '
							<td align="right" ><b>' . number_format($tot_price_inc, 0, ",", ".") . '</b></td>
                            <td align="right" ><b>' . number_format($tot_total, 0, ",", ".") . '</b></td>';
			}
					$html .= '</tr>';
			$html .= '
                 </table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		// $this->m_fungsi->_mpdf($html);
		$this->m_fungsi->template_kop('PURCHASE ORDER',$id,$html,'L','1');
		// $this->m_fungsi->mPDFP($html);
	}

    function Cetak_wa_po()
	{
		$id  = $_GET['no_po'];

		// $query = $this->m_master->get_data_one("trs_po_detail", "no_po", $id);
        $query = $this->db->query("SELECT * FROM trs_po a 
        JOIN trs_po_detail b ON a.no_po = b.no_po
        JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
        LEFT JOIN m_kab d ON c.kab=d.kab_id
        LEFT JOIN m_produk e ON b.id_produk=e.id_produk
        WHERE a.no_po = '$id' ");

		$html = '';


		if ($query->num_rows() > 0) {

            $data   = $query->row();

			if($this->session->userdata('level')=='Admin')
			{
				$kode_po ='<br> ( ' . $data->kode_po . ' )';
			}else{
				$kode_po ='';
	
			}
	

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;">
                        <tr style="font-weight: bold;">
                            <td colspan="15" align="center">
                            ( No. ' . $id . ' )
                            </td>
                        </tr>
                 </table><br>';

				$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:22px;">
				<tr align="left" style="background-color: #cccccc">
					<th>PO '.substr($data->kategori,2,10).' '. $data->nm_pelanggan .' '.$kode_po.'</th>
				</tr>
				<tr align="left">
					<th>ITEM </th>';
				 
				$no = 1;
				foreach ($query->result() as $r) { 
					$html .= '
								<tr>
									<td>' . $no . '. ' . $r->nm_produk . '</td>
								</tr>';
					$no++;
				}
	 
				$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:22px;">
					<tr align="left">
						<th>RM </th>';
                        
			$no = 1;
			foreach ($query->result() as $r) { 
				$html .= '
                            <tr>
                                <td>' . $no . '. ' . number_format($r->rm, 0, ",", ".") . '</td>
                            </tr>';
				$no++;
			}

            $html .= '
            <tr align="left">
                <th>Harga / kg</th>
            </tr>';

            $no       = 1;
            $toton    = 0;
            foreach ($query->result() as $r) { 
				$harga_kg   = round($r->price_exc / $r->berat_bersih);
				$html .= '
                            <tr>
                                <td>' . $no . '. ' . number_format($harga_kg, 0, ",", ".") . '</td>
                            </tr>';
                $toton += $r->ton;
				$no++;
			}
			
			$html .= '
            <tr align="left">
                <th>Berat Bersih : Tonase</th>
            </tr>';
			
            $no       = 1;
            $toton    = 0;
            foreach ($query->result() as $r) { 
				$html .= '
                            <tr>
                                <td>' . $no . '. ' . str_replace(".",",",$r->bb) . ' : ' . number_format($r->ton, 0, ",", ".") . ' Kg</td>
                            </tr>';
                $toton += $r->ton;
				$no++;
			}

            $html .= '
            </th>
            <tr align="left">
                <th>Total Tonase PO : '. number_format($toton, 0, ",", ".") .' Kg</th>
            </tr>';
            
			if($data->kategori=='K_SHEET')
			{

				$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:22px;">
				<tr align="left">
					<th>Harga P11</th>';

				$no       = 1;
				foreach ($query->result() as $r) { 
					$html .= '</th>
							<tr align="left">
								<td>'.$no.'. ( '. $data->p11 .' )</td>
							</tr>';
					$no++;
				}
				
			}
			

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:22px;">
				<tr align="left">
					<th>ETA Item</th>';
				 
				$no = 1;
				foreach ($query->result() as $r) { 
					$html .= '
								<tr>
									<td>' . $no . '. ' . $this->m_fungsi->tanggal_format_indonesia($r->eta) . '</td>
								</tr>';
					$no++;
				}

			$html .= '
			</th>
			<tr align="left">
				<th>Roll Produksi Sudah Ada</th>
			</tr>';

            $html .= '<tr align="left">
                <th>Cust Bisa Menyesuaikan Kita</th>
            </tr>
            ';

                        
			$html .= '</table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		// $this->m_fungsi->_mpdf($html);
		$this->m_fungsi->template_kop('PURCHASE ORDER', $id ,$html,'L','0');
		// $this->m_fungsi->mPDFP($html);
	}
   
	function Cetak_img_po()
	{
		$id  = $_GET['no_po'];

		// $query = $this->m_master->get_data_one("trs_po_detail", "no_po", $id);
        $query = $this->db->query("SELECT * FROM trs_po a 
        JOIN trs_po_detail b ON a.no_po = b.no_po
        JOIN m_pelanggan c ON a.id_pelanggan=c.id_pelanggan
        LEFT JOIN m_kab d ON c.kab=d.kab_id
        LEFT JOIN m_produk e ON b.id_produk=e.id_produk
        WHERE a.no_po = '$id' ");

		$html = '';


		if ($query->num_rows() > 0) {

            $data   = $query->row();

			$html .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" style=\"font-size:14px;\">
                        <tr style=\"font-weight: bold;\">
							<td align=\"center\">
								<img src=\"" . base_url() . "assets/gambar_po/$data->img_po\"  />
						</td>
                        </tr>
                 </table><br>";


                        
			$html .= '</table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		// $this->m_fungsi->_mpdf($html);
		// $this->m_fungsi->template_kop($id, $id ,$html,'P','1');
		$this->m_fungsi->_mpdf_hari('P', 'A4', $data->kode_po, $html, $data->kode_po.'.pdf', 5, 5, 5, 10);
		// $this->m_fungsi->mPDFP($html);
	}

	function Cetak_SO()
	{
		$id  = $_GET['no_so'];
		$query = $this->m_master->get_data_one("trs_so_detail", "no_so", $id);

		$html = '';

		if ($query->num_rows() > 0) {
			$data = $query->row();

			$style_top = "border-top:1px solid;";
			$style_top_bold = "border-top:3px solid;";

			$total = $data->harga * $data->qty;
			$ppn = round($total * 0.1);
			$sub_total = $total + $ppn;

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;">  
                        <tr>
                            <td width="15%" style="' . $style_top . '">Kode PO</td>
                            <td style="' . $style_top . '">' . $data->kode_po . '</td>
                            <td width="30%" style="' . $style_top . '"></td>
                            <td width="15%" style="' . $style_top . '">Input Date</td>
                            <td width="20%" style="' . $style_top . '">' . $data->tgl_so . '</td>
                        </tr>  
                        <tr>
                            <td style="">No PO</td>
                            <td style="">' . $data->no_po . '</td>
                            <td style=""></td>
                            <td style="">Created By</td>
                            <td style="">' . $data->add_user . '</td>
                        </tr> 
                        <tr>
                            <td style="">Sales</td>
                            <td style="">' . $data->salesman . '</td>
                            <td style=""></td>
                            <td style=""></td>
                            <td style=""></td>
                        </tr>
                        <tr>
                            <td style="' . $style_top . 'padding-top:10px">Customer</td>
                            <td style="' . $style_top . '"></td>
                            <td style="' . $style_top . '"></td>
                            <td style="' . $style_top . '">TOP</td>
                            <td style="' . $style_top . '">' . $data->top . '</td>
                        </tr> 
                        <tr>
                            <td  style="padding-left:20px" colspan="3">' . $data->nm_pelanggan . '</td>
                            <td  style="">PO Date</td>
                            <td style="">' . $data->tgl_po . '</td>
                        </tr>  
                        <tr>
                            <td  style="padding-left:20px" colspan="3">' . $data->alamat . '</td>
                            <td  style="">Phone NO.</td>
                            <td style="">' . $data->no_telp . '</td>
                        </tr>  
                        <tr>
                            <td  style="padding-left:20px" colspan="3"></td>
                            <td  style="">Fax NO.</td>
                            <td style="">' . $data->fax . '</td>
                        </tr> 
                        <tr>
                            <td style="' . $style_top . 'padding-top:10px" >Shipped To  </td>
                            <td style="' . $style_top . 'padding-top:10px" colspan="4">: ' . $data->alamat_kirim . '</td>
                        </tr>  
                        <tr>
                            <td style="" >Location </td>
                            <td style="" colspan="4">: ' . $data->lokasi . ' </td>
                        </tr> 
                        <tr>
                            <td style="' . $style_top . 'padding-top:10px">Description</td>
                            <td style="' . $style_top . '"></td>
                            <td style="' . $style_top . '"></td>
                            <td style="' . $style_top . '" colspan="2">
                                <table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;"> 
                                    <tr>
                                        <td width="30%">Order Qty</td>
                                        <td width="30%">Price / Unit</td>
                                        <td width="30%">Ammount</td>
                                    </tr>
                                </table>
                            </td>
                        </tr> 
                        <tr>
                            <td style="">Kode PO</td>
                            <td style="">' . $data->kode_po . '</td>
                            <td style=""></td>
                            <td style="" colspan="2">
                                <table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;"> 
                                    <tr>
                                        <td width="30%" align="right" style="padding-right:5px">' . number_format($data->qty) . '</td>
                                        <td width="30%" align="right" style="padding-right:5px">' . number_format($data->harga) . '</td>
                                        <td width="30%" align="right" style="padding-right:5px">' . number_format($total) . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr> 
                        <tr>
                            <td style="">Kode MC</td>
                            <td style="" colspan="4">' . $data->kode_mc . '</td>
                        </tr>  
                        <tr>
                            <td style="">Produk</td>
                            <td style="" colspan="4">' . $data->nm_produk . '</td>
                        </tr>  
                        <tr>
                            <td style="">Uk. Box</td>
                            <td style="" colspan="4">' . $data->ukuran . '</td>
                        </tr>   
                        <tr>
                            <td style="">Material</td>
                            <td style="" colspan="4">' . $data->material . '</td>
                        </tr>   
                        <tr>
                            <td style="">Flute</td>
                            <td style="" colspan="4">' . $data->flute . '</td>
                        </tr>   
                        <tr>
                            <td style="">Creasing</td>
                            <td style="" colspan="4">' . $data->creasing . '</td>
                        </tr> 
                        <tr>
                            <td style="' . $style_top . 'padding-top:10px;border-style: dotted;"></td>
                            <td style="' . $style_top . 'border-style: dotted;"></td>
                            <td style="' . $style_top . 'border-style: dotted;"></td>
                            <td style="' . $style_top . 'border-style: dotted;" colspan="2">
                                <table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;"> 
                                    <tr>
                                        <td width="30%" align="right" style="padding-right:5px">' . number_format($data->qty) . '</td>
                                        <td width="30%" align="right" style="padding-right:5px">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td width="30%" align="right" style="padding-right:5px">' . number_format($total) . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>  
                        <tr>
                            <td style="' . $style_top . 'padding-top:10px;" colspan="3" valign="top">
                                REMARK PO ORI-JANGAN GEMBOS
                            </td>
                            <td style="' . $style_top . '" colspan="2">
                                <table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;"> 
                                    <tr>
                                        <td width="30%"  align="left" style="padding-right:5px">Total Value</td>
                                        <td width="30%" colspan="2" align="right" style="padding-right:5px">' . number_format($total) . '</td>
                                    </tr>
                                    <tr>
                                        <td width="30%"  align="left" style="padding-right:5px">PPN 10%</td>
                                        <td width="30%" colspan="2" align="right" style="padding-right:5px">' . number_format($ppn) . '</td>
                                    </tr>
                                    <tr>
                                        <td width="30%"  align="left" style="padding-right:5px">Final Ammount</td>
                                        <td width="30%" colspan="2" align="right" style="padding-right:5px">' . number_format($sub_total) . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr> 

                        <tr>
                            <td colspan="5" style="' . $style_top_bold . 'padding-top:10px">
                                <table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;"> 
                                    <tr>
                                        <td width="25%" align="center">Sales / Marketing</td>
                                        <td width="25%" align="center">Costing</td>
                                        <td width="25%" align="center">Menyetujui</td>
                                        <td width="25%" align="center">Mengetahui</td>
                                    </tr> 
                                </table>
                            </td>
                        </tr> 

                      </table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		$this->m_fungsi->_mpdf($html);
		// $this->m_fungsi->mPDFP($html);
	}

	function Cetak_WO()
	{
		$id  = $_GET['no_wo'];
		// $query = $this->m_master->get_data_one("trs_wo", "no_wo", $id);
		$query = $this->db->query("SELECT a.id as id_wo,a.*,b.*,c.*,d.*,e.* FROM trs_wo a 
				JOIN trs_wo_detail b ON a.no_wo=b.no_wo 
				JOIN m_produk c ON a.id_produk=c.id_produk 
				JOIN m_pelanggan d ON a.id_pelanggan=d.id_pelanggan 				
				JOIN trs_so_detail e ON a.no_so=e.id
				WHERE a.no_wo='$id'
				order by a.id	");
		$data = $query->row();

		if ($data->sambungan == 'G'){
			$join = 'Glue';
		} else if ($data->sambungan == 'S'){
			$join = 'Stitching';
		} else if ($data->sambungan == 'D'){
			$join = 'Die Cut';
		} else if ($data->sambungan == 'DS'){
			$join = 'Double Stitching';
		} else if ($data->sambungan == 'GS'){
			$join = 'Glue Stitching';
		}else {
			$join = '-';
		}
		
		$tgl_wo        = ($data->tgl_wo == null || $data->tgl_wo == '0000-00-00' ? '0000-00-00' : $data->tgl_wo);
		$eta_so        = ($data->eta_so == null || $data->eta_so == '0000-00-00' ? '0000-00-00' : $data->eta_so);
		
		$data_detail = $this->m_master->get_data_one("trs_wo_detail", "no_wo", $id)->row();

		$html       = '';
		$box        = "border: 1px solid black";
		$angka_b    = 'style="text-align: center;color:red;font-weight:bold;"';
		$angka_s    = 'style="text-align: left;color:red;font-weight:bold"';
		$bottom     = "border-bottom: 1px solid black;";
		$top        = "border-top: 1px solid black;";

		if ($query->num_rows() > 0) {
			
			if($data->kategori=="K_BOX")
			{
				$ukuran_sheet_p    = $data->ukuran_sheet_p+5;
				$trim = '5 mm';
			}else{
				$ukuran_sheet_p    = $data->ukuran_sheet_p;
				$trim ='-';
			}

			$html .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px;font-family: ;">
                            
                            <tr style="font-weight: bold;">
                                <td colspan="15" align="center">
                                <b> ( ' . $data->no_wo . ' )</b>
                                </td>
                            </tr>
                     </table><br>';

					 $html .= '<table width="100%" cellspacing="1" cellpadding="3" border="0" style="font-size:12px;font-family: ;">  
					 <tr>
						 <td width="15%" >CUSTOMER</td>
						 <td width="30%" >: <b>' . $data->nm_pelanggan . '</b></td>
						 <td width="10%" > </td>
						 <td width="15%" >Tgl Wo</td>
						 <td width="30%" >: <b>' . $this->m_fungsi->tanggal_format_indonesia($tgl_wo) . '</b></td>
					 </tr>
					 <tr>
						 <td>Item</td>
						 <td>: <b>' . $data->nm_produk . '</b></td>
						 <td></td>
						 <td width="15%" >No Po</td>
						 <td width="20%" >: <b>' . $data->no_po . '</b></td>
					 </tr>
					 <tr>
						 <td>Ukuran Box</td>
						 <td>: <b>' . $data->ukuran . '</b></td>
						 <td></td>
						 <td>Out</td>
						 <td>: <b>' . $data->line . '</b></td>
					 </tr>
					 <tr>
						 <td>Ukuran Sheet</td>
						 <td >: <b>' . $ukuran_sheet_p . ' x ' . $data->ukuran_sheet_l . '</b></td>
						 <td></td>
						 <td>Tgl Kirim</td>
						 <td style="color:red" >: <b>' . $this->m_fungsi->tanggal_format_indonesia($tgl_wo) . '</b></td>

					 </tr>
					 <tr>
						<td>Trim</td>
						<td>: <b>'. $trim .'</b></td>
						<td></td>
						<td>ETA</td>
						<td style="color:red" >: <b>' . $this->m_fungsi->tanggal_format_indonesia($eta_so) . '
						</b></td>

					 </tr>
					 <tr>
						<td>Kualitas</td>
						<td >: <b>' . $data->kualitas . '</b></td>	
						<td></td>
						<td>No Batch</td>
						<td>: <b>' . $data->batchno . '</b></td>
					 </tr>
					 <tr>
						<td>Type Box</td>
						<td>: <b>' . $data->tipe_box . '</b></td>					 
						<td></td>
						<td>Berat Box</td>
						<td>: <b>' . $data->berat_bersih . ' Kg</b></td>
					 </tr>
					 <tr>
						<td>Warna</td>
						<td>: <b>' . $data->warna . '</b></td>
						<td></td>
						<td>Flute</td>
						<td>: <b>' . $data->flute . '</b></td>
					 </tr>
					 <tr>
						<td style="">Jumlah Order</td>
						<td style="">: <b>' . number_format($data->qty, 0, ",", ".") . '</b> PCS</td>
						<td></td>
						<td>Joint</td>
						<td>: <b>' . $join . '</b></td>
					 </tr><br>
				 </table>';

			if ( $data->kategori == 'K_BOX')
			{
				$html .='<br><br>
				<table border="0" cellspacing="0" width="100%" id="tabel_box">
					<tr>
					
					<td width="15%" > </td>
					<td width="5%" style="border-right: 1px solid black;"> </td>
					<td width="15%" style=" '. $box .'" > </td>
					<td width="15%" style=" '. $box .'" > <br>&nbsp;</br> </td>
					<td width="15%" style=" '. $box .'" > </td>
					<td width="15%" style=" '. $box .'" > </td>
					<td width="20%" style="border-left: 1px solid black;border-left: 1px solid black;" ><b> &nbsp; ' . number_format($data->flap1, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td style="" > 
						<br>&nbsp;</br>
					</td>
					<td style="background-size: 30% 100%;" ><img src="'.base_url('assets/gambar/kupingan1.png').'" width="50" height="50"> </td>
					<td style="'. $box .'" > </td>
					<td style="'. $box .'" > </td>
					<td style="'. $box .'" > </td>
					<td style="'. $box .'" > </td>
					<td style="border-left: 1px solid black;" ><b> &nbsp; ' . number_format($data->creasing2, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td> <br>&nbsp;</br></td>
					<td style="border-right: 1px solid black;" align="right"><b>' . $data->kupingan . '</b></td>
					<td style =" '. $box .'" > </td>
					<td style =" '. $box .'" > </td>
					<td style =" '. $box .'" > </td>
					<td style =" '. $box .'" > </td>
					<td style=" border-left: 1px solid black;" ><b> &nbsp; ' . number_format($data->flap2, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td align="center" > <br>&nbsp;</br>
					</td>
					<td align="center"> 
					</td>
					<td align="center" ><b> ' . number_format($data->p1, 0, ",", ".") . '
					</b></td>
					<td align="center" ><b> ' . number_format($data->l1, 0, ",", ".")  . '
					</b></td>
					<td align="center" ><b> ' . number_format($data->p2, 0, ",", ".") . '
					</b></td>
					<td align="center" ><b> ' . number_format($data->l2, 0, ",", ".") . '
					</b></td>
					<td align="center" > </td>
					</tr>

				</table>';

			}else{
				
				$html .='<br><br>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" id="tabel_sheet">
					<tr>
					<td width="15%"> <br>&nbsp;</td>
					<td width="5%"> </td>
					<td width="15%" style="'. $top .'border-left: 1px solid #000" > </td>
					<td width="15%" style="'. $top .'"></td>
					<td width="15%" style="'. $top .'"> </td>
					<td width="15%" style="'. $top .'border-right: 1px solid #000"></td>
					<td width="20%"><b> &nbsp; ' . number_format($data->flap1, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td> 
						<br>&nbsp;</br>
					</td>
					<td> </td>
					<td style="'. $top .'border-left: 1px solid #000" > </td>
					<td style="'. $top .'"> </td>
					<td style="'. $top .'"> </td>
					<td style="'. $top .'border-right: 1px solid #000" > </td>
					<td><b> &nbsp; ' . number_format($data->creasing2, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td> <br>&nbsp;</br></td>
					<td></td>
					<td style="'. $top .''.$bottom.' border-left: 1px solid #000" > </td>
					<td style="'. $top .''.$bottom.'" > </td>
					<td style="'. $top .''.$bottom.'" > </td>
					<td style="'. $top .''.$bottom.' border-right: 1px solid #000" > </td>
					<td><b> &nbsp; ' . number_format($data->flap2, 0, ",", ".") . '
					</b></td>
					</tr>
					<tr>
					<td align="center" > <br>&nbsp;</br>
					</td>
					<td align="center" > 
					</td>
					<td align="center" colspan="4"><b> '. number_format($data->p1_sheet, 0, ",", ".") .'
					</b></td>
					<td align="center"> </td>
					</tr>

				</table> ';

			}

			
			$query_detail = $this->db->query("SELECT*FROM plan_cor where no_wo ='$id' ");

			if( $query_detail->num_rows()>0 )
			{				
				foreach($query_detail->result() as $rinci)
				{
	
					$tgl_plan    = ($rinci->tgl_plan == null || $rinci->tgl_plan == '0000-00-00' ? '0000-00-00' : $rinci->tgl_plan);
	
					$tgl_ok      = $this->m_fungsi->tanggal_format_indonesia($rinci->tgl_plan);
	
					
					$html .= '<br>
						<table width="100%" border="1" cellspacing="0" cellpadding="3" style="font-size:12px;font-family: ;">  
							<tr>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" ><b>No</b></td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" ><b>PROSES PRODUKSI</b></td>
								<td align="center" width="%" colspan="2" style="background-color: #cccccc" ><b>HASIL PRODUKSI</b></td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" ><b>RUSAK</b></td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" ><b>HASIL BAIK</b></td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" ><b>KETERANGAN</b></td>
							</tr>
							<tr>
								<td align="center" width="%" style="background-color: #cccccc"><b>TGL</b></td>
								<td align="center" width="%" style="background-color: #cccccc"><b>HASIL JADI</b></td>
							</tr>
	
							<tr>
								<td align="center" width="5%" >1</td>
								<td align="" width="20%" >CORUUGATOR</td>
								<td align="center" width="20%" >' . $tgl_ok . '</td>
								<td align="center" width="1%" >' . number_format($rinci->total_cor_p, 0, ",", ".")  . '</td>
								<td align="center" width="15%" >' . number_format($rinci->bad_cor_p, 0, ",", ".")  . '</td>
								<td align="center" width="15%" >' . number_format($rinci->good_cor_p, 0, ",", ".")  . '</td>
								<td align="" width="15%" >' . number_format($rinci->ket_plan, 0, ",", ".")  . '</td>
							</tr>
							<tr>
								<td align="center">2</td>
								<td align="" >FLEXO</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_flx . '</td>
								<td align="center" >' . $data_detail->rusak_flx . '</td>
								<td align="center" >' . $data_detail->baik_flx . '</td>
								<td align="" >' . $data_detail->ket_flx . '</td>
							</tr>
							<tr>
								<td align="center" rowspan="8" valign="middle">3</td>
								<td align="" >FINISHING</td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;"></td>
							</tr>
							<tr>
								<td align="right" >Glue</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $tgl_ok . '</td>
	
								<td align="center" style="border-top:hidden;border-right:hidden;border-right:hidden">' . $data_detail->hasil_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->rusak_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->baik_glu . '</td>
								<td align="" style="border-top:hidden;">' . $data_detail->ket_glu . '</td>
							</tr>
							<tr>
								<td align="right" >Stitching</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_stc . '</td>
								<td align="center" >' . $data_detail->rusak_stc . '</td>
								<td align="center" >' . $data_detail->baik_stc . '</td>
								<td align="" >' . $data_detail->ket_stc . '</td>
							</tr>
							<tr>
								<td align="right" >Die Cut</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_dic . '</td>
								<td align="center" >' . $data_detail->rusak_dic . '</td>
								<td align="center" >' . $data_detail->baik_dic . '</td>
								<td align="" >' . $data_detail->ket_dic . '</td>
							</tr>
							<tr>
								<td align="right" >Glue Stitching</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $tgl_ok . '</td>
	
								<td align="center" style="border-top:hidden;border-right:hidden;border-right:hidden">' . $data_detail->hasil_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->rusak_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->baik_glu . '</td>
								<td align="" style="border-top:hidden;">' . $data_detail->ket_glu . '</td>
							</tr>
							<tr>
								<td align="right" >Double Stitching</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $tgl_ok . '</td>
	
								<td align="center" style="border-top:hidden;border-right:hidden;border-right:hidden">' . $data_detail->hasil_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->rusak_glu . '</td>
								<td align="center" style="border-top:hidden;border-right:hidden">' . $data_detail->baik_glu . '</td>
								<td align="" style="border-top:hidden;">' . $data_detail->ket_glu . '</td>
							</tr>
							<tr>
								<td align="right" >Asembly Partisi</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_dic . '</td>
								<td align="center" >' . $data_detail->rusak_dic . '</td>
								<td align="center" >' . $data_detail->baik_dic . '</td>
								<td align="" >' . $data_detail->ket_dic . '</td>
							</tr>
							<tr>
								<td align="right" >Slitter Manual</td>
								<td align="center" >' . $tgl_ok . '</td>
	
								<td align="center" >' . $data_detail->hasil_dic . '</td>
								<td align="center" >' . $data_detail->rusak_dic . '</td>
								<td align="center" >' . $data_detail->baik_dic . '</td>
								<td align="" >' . $data_detail->ket_dic . '</td>
							</tr>
							<tr>
								<td align="center" >4</td>
								<td align="" >GUDANG</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_gdg . '</td>
								<td align="center" >' . $data_detail->rusak_gdg . '</td>
								<td align="center" >' . $data_detail->baik_gdg . '</td>
								<td align="" >' . $data_detail->ket_gdg . '</td>
							</tr>
							<tr>
								<td align="center" >5</td>
								<td align="" >EXPEDISI / PENGIRIMAN</td>
								<td align="center" >' . $tgl_ok . '</td>
								<td align="center" >' . $data_detail->hasil_exp . '</td>
								<td align="center" >' . $data_detail->rusak_exp . '</td>
								<td align="center" >' . $data_detail->baik_exp . '</td>
								<td align="" >' . $data_detail->ket_exp . '</td>
							</tr>
						</table>';
								
				}
			}else{
				$html .= '<br>
						<table width="100%" border="1" cellspacing="0" cellpadding="3" style="font-size:12px;font-family: ;">  
							<tr>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" >No</td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" >PROSES PRODUKSI</td>
								<td align="center" width="%" colspan="2" style="background-color: #cccccc" >HASIL PRODUKSI</td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" >RUSAK</td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" >HASIL BAIK</td>
								<td align="center" width="%" rowspan="2" style="background-color: #cccccc" >KETERANGAN</td>
							</tr>
							<tr>
								<td align="center" width="%" style="background-color: #cccccc">TGL</td>
								<td align="center" width="%" style="background-color: #cccccc">HASIL JADI</td>
							</tr>
	
							<tr>
								<td align="center" width="5%" >1</td>
								<td align="" width="20%" >CORUUGATOR</td>
								<td align="center" width="20%" >0</td>
								<td align="center" width="1%" >0</td>
								<td align="center" width="15%" >0</td>
								<td align="center" width="15%" >0</td>
								<td align="" width="15%" >0</td>
							</tr>
							<tr>
								<td align="center">2</td>
								<td align="" >FLEXO</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="center" rowspan="8" valign="middle">3</td>
								<td align="" >FINISHING</td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;border-right:hidden"></td>
								<td align="" style="border-bottom:hidden;"></td>
							</tr>
							<tr>
								<td align="right" >Glue</td>
								<td align="center" style="border-top:0;border-right:0">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="" style="border-top:0;">0</td>
							</tr>
							<tr>
								<td align="right" >Stitching</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="right" >Die Cut</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="right" >Glue Stitching</td>
								<td align="center" style="border-top:0;border-right:0">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="" style="border-top:0;">0</td>
							</tr>
							<tr>
								<td align="right" >Double Stitching</td>
								<td align="center" style="border-top:0;border-right:0">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="center" style="border-top:0;border-right:0;">0</td>
								<td align="" style="border-top:0;">0</td>
							</tr>
							<tr>
								<td align="right" >Asembly Partisi</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="right" >Slitter Manual</td>
								<td align="center" >0</td>
	
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="center" >4</td>
								<td align="" >GUDANG</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
							<tr>
								<td align="center" >5</td>
								<td align="" >EXPEDISI / PENGIRIMAN</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="center" >0</td>
								<td align="" >0</td>
							</tr>
						</table>';				

			}

	
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		// $this->m_fungsi->_mpdf($html);
		
		$this->m_fungsi->template_kop('WORK ORDER',$id ,$html,'P','1');
		// $this->m_fungsi->mPDFP($html);
	}

	function Cetak_WO_()
	{
		$id  = 'WO-2021-0000000002';
		$query = $this->m_master->get_data_one("trs_wo", "no_wo", $id);
		$data_detail = $this->m_master->get_data_one("trs_wo_detail", "no_wo", $id)->row();

		$html = '';

		if ($query->num_rows() > 0) {
			$data = $query->row();

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                            <tr style="font-weight: bold;">
                                <td colspan="15" align="center">
                                  <u><h3> ORDER PRODUKSI </h3></u>
                                </td>
                            </tr>
                            <tr style="font-weight: bold;">
                                <td colspan="15" align="center">
                                  ' . $data->no_wo . '
                                </td>
                            </tr>
                     </table><br>';

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">  
                            <tr>
                                <td width="20%" >No SO</td>
                                <td width="30%">: ' . $data->no_so . '</td>
                                <td width="30%" /td>
                                <td width="15%" >TGL WO</td>
                                <td width="20%" >' . $data->tgl_wo . '</td>
                            </tr>
                            <tr>
                                <td>TGL</td>
                                <td>: ' . $data->tgl_so . '</td>
                                <td></td>
                                <td>Line</td>
                                <td>' . $data->line . '</td>
                            </tr>
                            <tr>
                                <td>NAMA PELANGGAN</td>
                                <td>: ' . $data->nm_pelanggan . '</td>
                                <td></td>
                                <td>Tgl Kirim</td>
                                <td>' . $data->tgl_wo . '</td>
                            </tr>
                            <tr>
                                <td>JENIS PRODUK</td>
                                <td>: ' . $data->jenis_produk . '</td>
                                <td></td>
                                <td>No Batch</td>
                                <td>' . $data->batchno . '</td>
                            </tr>
                            <tr>
                                <td>NO. ARTIKEL</td>
                                <td colspan="4">: ' . $data->no_artikel . '</td>
                            </tr>
                            <tr>
                                <td>NAMA BARANG</td>
                                <td colspan="4">: ' . $data->nm_produk . '</td>
                            </tr>
                            <tr>
                                <td>UKURAN SHEET</td>
                                <td colspan="4">: ' . $data->ukuran . '</td>
                            </tr>
                            <tr>
                                <td>KUALITAS</td>
                                <td colspan="4">: ' . $data->kualitas . '</td>
                            </tr>
                            <tr>
                                <td>TYPE BOX</td>
                                <td colspan="4">: ' . $data->tipe_box . '</td>
                            </tr>
                            <tr>
                                <td>WARNA</td>
                                <td colspan="4">: ' . $data->warna . '</td>
                            </tr>
                            <tr>
                                <td style="border-bottom:1px solid;">JUMLAH ORDER</td>
                                <td style="border-bottom:1px solid;">: ' . number_format($data->qty) . '</td>
                                <td colspan="3"></td>
                            </tr>
                        </table>';

			$html .= '<br>
                        <table width="60%" border="0" cellspacing="0" cellpadding="0" style="font-size:10px;font-family: ;">  
                            <tr>
                                <td align="center" width="3%" style=""><br><br>&nbsp;</td>
                                <td align="center" width="8%" style="border-top:1px solid;border-left:1px solid" ><i>11</i></td>
                                <td align="center" width="20%" style="border-top:1px solid" valign="top">00</td>
                                <td align="center" width="20%" style="border-top:1px solid;border-left:1px solid" valign="top">00</td>
                                <td align="center" width="20%" style="border-top:1px solid;border-left:1px solid" valign="top">00</td>
                                <td align="center" width="20%" style="border-top:1px solid;border-left:1px solid" valign="top">00</td>
                                <td align="center" width="8%" style="border-top:1px solid;border-left:1px solid;border-right:1px solid"></td>
                            </tr> 
                            <tr>
                                <td align="center" width="3%" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid;" valign="midle"><i>11</i></td>
                                <td align="center" width="8%" style="" ><br><br>&nbsp;</td>
                                <td align="center" width="20%" style="" valign="top"></td>
                                <td align="center" width="20%" style="" valign="top"></td>
                                <td align="center" width="20%" style="" valign="top"></td>
                                <td align="center" width="20%" style="" valign="top"></td>
                                <td align="center" width="8%" style="border-right:1px solid"><i>11</i></td>
                            </tr>
                            <tr>
                                <td align="center" width="3%" style=""><br><br>&nbsp;</td>
                                <td align="center" width="8%" style="border-bottom:1px solid;border-left:1px solid" ><i>11</i></td>
                                <td align="center" width="20%" style="border-bottom:1px solid" valign="bottom">00</td>
                                <td align="center" width="20%" style="border-bottom:1px solid;border-left:1px solid" valign="bottom">00</td>
                                <td align="center" width="20%" style="border-bottom:1px solid;border-left:1px solid" valign="bottom">00</td>
                                <td align="center" width="20%" style="border-bottom:1px solid;border-left:1px solid" valign="bottom">00</td>
                                <td align="center" width="8%" style="border-bottom:1px solid;border-left:1px solid;border-right:1px solid"></td>
                            </tr> 
                        </table>
                        ';

			$html .= '<br>
                        <table width="100%" border="1" cellspacing="0" style="font-size:12px;font-family: ;">  
                            <tr>
                                <td align="center" width="%" rowspan="2">No</td>
                                <td align="center" width="%" rowspan="2">PROSES PRODUKSI</td>
                                <td align="center" width="%" colspan="2">HASIL PRODUKSI</td>
                                <td align="center" width="%" rowspan="2">RUSAK</td>
                                <td align="center" width="%" rowspan="2">HASIL BAIK</td>
                                <td align="center" width="%" rowspan="2">KETERANGAN</td>
                            </tr>
                            <tr>
                                <td align="center" width="%" >TGL</td>
                                <td align="center" width="%" >HASIL JADI</td>
                            </tr>

                            <tr>
                                <td align="center" width="3%" >1</td>
                                <td align="" width="20%" >CORUUGATOR</td>
                                <td align="" width="10%" >' . (($data_detail->tgl_crg) == '0000-00-00' ? '' : $data_detail->tgl_crg) . '</td>
                                <td align="" width="10%" >' . $data_detail->hasil_crg . '</td>
                                <td align="" width="15%" >' . $data_detail->rusak_crg . '</td>
                                <td align="" width="15%" >' . $data_detail->baik_crg . '</td>
                                <td align="" width="15%" >' . $data_detail->ket_crg . '</td>
                            </tr>
                            <tr>
                                <td align="center" width="%" >2</td>
                                <td align="" width="%" >FLEXO</td>
                                <td align="" width="%" >' . (($data_detail->tgl_flx) == '0000-00-00' ? '' : $data_detail->tgl_flx) . '</td>
                                <td align="" width="%" >' . $data_detail->hasil_flx . '</td>
                                <td align="" width="%" >' . $data_detail->rusak_flx . '</td>
                                <td align="" width="%" >' . $data_detail->baik_flx . '</td>
                                <td align="" width="%" >' . $data_detail->ket_flx . '</td>
                            </tr>
                            <tr>
                                <td align="center" width="%" rowspan="4" valign="middle">3</td>
                                <td align="" width="%" >CONVERTING</td>
                                <td align="" width="%" style="border-bottom:hidden;border-right:hidden"></td>
                                <td align="" width="%" style="border-bottom:hidden;border-right:hidden"></td>
                                <td align="" width="%" style="border-bottom:hidden;border-right:hidden"></td>
                                <td align="" width="%" style="border-bottom:hidden;border-right:hidden"></td>
                                <td align="" width="%" style="border-bottom:hidden;"></td>
                            </tr>
                            <tr>
                                <td align="right" width="%" >GLUE</td>
                                <td align="" width="%" style="border-top:hidden;border-right:hidden">' . (($data_detail->tgl_glu) == '0000-00-00' ? '' : $data_detail->tgl_glu) . '</td>
                                <td align="" width="%" style="border-top:hidden;border-right:hidden;border-right:hidden">' . $data_detail->hasil_glu . '</td>
                                <td align="" width="%" style="border-top:hidden;border-right:hidden">' . $data_detail->rusak_glu . '</td>
                                <td align="" width="%" style="border-top:hidden;border-right:hidden">' . $data_detail->baik_glu . '</td>
                                <td align="" width="%" style="border-top:hidden;">' . $data_detail->ket_glu . '</td>
                            </tr>
                            <tr>
                                <td align="right" width="%" >STITCHING</td>
                                <td align="" width="%" >' . (($data_detail->tgl_stc) == '0000-00-00' ? '' : $data_detail->tgl_stc) . '</td>
                                <td align="" width="%" >' . $data_detail->hasil_stc . '</td>
                                <td align="" width="%" >' . $data_detail->rusak_stc . '</td>
                                <td align="" width="%" >' . $data_detail->baik_stc . '</td>
                                <td align="" width="%" >' . $data_detail->ket_stc . '</td>
                            </tr>
                            <tr>
                                <td align="right" width="%" >DIE CUT</td>
                                <td align="" width="%" >' . (($data_detail->tgl_dic) == '0000-00-00' ? '' : $data_detail->tgl_dic) . '</td>
                                <td align="" width="%" >' . $data_detail->hasil_dic . '</td>
                                <td align="" width="%" >' . $data_detail->rusak_dic . '</td>
                                <td align="" width="%" >' . $data_detail->baik_dic . '</td>
                                <td align="" width="%" >' . $data_detail->ket_dic . '</td>
                            </tr>
                            <tr>
                                <td align="center" width="%" >4</td>
                                <td align="" width="%" >GUDANG</td>
                                <td align="" width="%" >' . (($data_detail->tgl_gdg) == '0000-00-00' ? '' : $data_detail->tgl_gdg) . '</td>
                                <td align="" width="%" >' . $data_detail->hasil_gdg . '</td>
                                <td align="" width="%" >' . $data_detail->rusak_gdg . '</td>
                                <td align="" width="%" >' . $data_detail->baik_gdg . '</td>
                                <td align="" width="%" >' . $data_detail->ket_gdg . '</td>
                            </tr>
                            <tr>
                                <td align="center" width="%" >5</td>
                                <td align="" width="%" >EXPEDISI / PENGIRIMAN</td>
                                <td align="" width="%" >' . (($data_detail->tgl_exp) == '0000-00-00' ? '' : $data_detail->tgl_exp) . '</td>
                                <td align="" width="%" >' . $data_detail->hasil_exp . '</td>
                                <td align="" width="%" >' . $data_detail->rusak_exp . '</td>
                                <td align="" width="%" >' . $data_detail->baik_exp . '</td>
                                <td align="" width="%" >' . $data_detail->ket_exp . '</td>
                            </tr>
                        </table>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		$this->m_fungsi->_mpdf($html);
	}

	function Cetak_SuratJalan()
	{
		$id  = $_GET['no_surat_jalan'];
		$query = $this->m_master->get_data_one("trs_surat_jalan", "no_surat_jalan", $id);
		$data_pelanggan = $this->m_master->get_data_one("m_pelanggan", "id_pelanggan", $query->row('id_pelanggan'))->row();

		$html = '';

		if ($query->num_rows() > 0) {
			$data = $query->result();

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;">
                            <tr>
                                <td colspan="7" align="center"><h2><u>SURAT JALAN</u></h2><br>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="18%">TANGGAL</td>
                                <td width="2%">:</td>
                                <td width="20%">' . $data[0]->tgl_surat_jalan . '</td>
                                <td width="10%"></td>
                                <td width="18%">KEPADA</td>
                                <td width="2%">:</td>
                                <td width="40%">' . $data[0]->nm_pelanggan . '</td>
                            </tr>
                            <tr>
                                <td>NO. SURAT JALAN</td>
                                <td>:</td>
                                <td>' . $data[0]->no_surat_jalan . '</td>
                                <td></td>
                                <td>ALAMAT</td>
                                <td>:</td>
                                <td>' . $data_pelanggan->alamat . '</td>
                            </tr>
                            <tr>
                                <td>Kode PO</td>
                                <td>:</td>
                                <td>' . $data[0]->kode_po . '</td>
                                <td></td>
                                <td>ATTN</td>
                                <td>:</td>
                                <td>' . $data[0]->nm_pelanggan . '</td>
                            </tr>
                            <tr>
                                <td>NO. PKB</td>
                                <td>:</td>
                                <td>' . $data[0]->no_pkb . '</td>
                                <td></td>
                                <td>NO.TELP / HP</td>
                                <td>:</td>
                                <td>' . $data_pelanggan->no_telp . '</td>
                            </tr>
                            <tr>
                                <td>NO. KENDARAAN</td>
                                <td>:</td>
                                <td>' . $data[0]->no_kendaraan . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                     </table><br>';

			$html .= '<table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;">
                            <tr>
                                <td width="4%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid"><b>NO</td>
                                <td width="20%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid"><b>NO.PO</td>
                                <td width="25%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid"><b>ITEM DESCRIPTION</td>
                                <td width="20%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid"><b>FLUTE</td>
                                <td width="10%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid"><b>QTY</td>
                                <td width="20%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid;border-right:1px solid"><b>KETERANGAN</td>
                            </tr>';
			$no = $tot_qty = 0;

			foreach ($data as $r) {
				$no++;
				$html .= '
                        <tr>
                                <td style="border-bottom:1px solid;border-left:1px solid">
                                    ' . $no . '
                                    
                                </td>
                                <td style="border-bottom:1px solid;border-left:1px solid">
                                    ' . $r->kode_po . '
                                    
                                </td>
                                <td style="border-bottom:1px solid;border-left:1px solid">
                                    ' . $r->nm_produk . '
                                    
                                </td>
                                <td style="border-bottom:1px solid;border-left:1px solid">
                                    ' . $r->flute . '
                                    
                                </td>
                                <td style="border-bottom:1px solid;border-left:1px solid" align="right">
                                    ' . number_format($r->qty) . '
                                    
                                </td>
                                <td style="border-bottom:1px solid;border-left:1px solid;border-right:1px solid">
                                    
                                    
                                </td>
                            </tr>';
				$tot_qty += $r->qty;
			}
			$html .= ' 

                            <tr>
                                <td style="border-bottom:1px solid;border-left:1px solid" colspan="3" align="center"><b>TOTAL</td>
                                <td style="border-bottom:1px solid;border-left:1px solid"><b> </td>
                                <td style="border-bottom:1px solid;border-left:1px solid"><b> ' . number_format($tot_qty) . ' PCS</td>
                                <td style="border-bottom:1px solid;border-left:1px solid;border-right:1px solid"><b> </td>
                            </tr>    
                     </table><br>';

			$html .= '<br><table width="100%" border="0" cellspacing="0" style="font-size:12px;font-family: ;">
                            <tr>
                                <td width="16%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid">DIBUAT</td>
                                <td width="17%" align="center" colspan="2" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid">DI KELUARKAN OLEH</td>
                                <td width="16%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid">DIKETAHUI</td>
                                <td width="16%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid">DISETUJUI</td>
                                <td width="16%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid">SOPIR</td>
                                <td width="16%" align="center" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid;border-right:1px solid">DITERIMA OLEH</td>
                            </tr>
                            <tr>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    <br><br><br><br><br>&nbsp;
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    <br><br><br><br><br>&nbsp;
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    <br><br><br><br><br>&nbsp;
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    <br><br><br><br><br>&nbsp;
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    <br><br><br><br><br>&nbsp;
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid">
                                    
                                </td>
                                <td align="center" style="border-bottom:1px solid;border-left:1px solid;border-right:1px solid">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid">
                                    <br>.................. <br>
                                    ADMIN
                                </td>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid">
                                    <br>.................. <br>
                                    DIREKSI
                                </td>
                                <td width="10%" align="center" style="border-left:1px solid;border-bottom:1px solid">
                                    <br>.................. <br>
                                    SPV
                                </td>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid">
                                    <br>.................. <br>
                                    MGR GUDANG
                                </td>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid">
                                    <br>.................. <br>
                                    GM
                                </td>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid"></td>
                                <td align="center" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid"></td>
                            </tr>
                     </table><br>';

			$html .= '<br><br><br><table width="100%" border="0" cellspacing="0" style="font-size:10px;font-family: ;">
                            <tr>
                                <td colspan="4">NOTE :</td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="10%">WHITE</td>
                                <td width="2%">:</td>
                                <td width="">PEMBELI / CUSTOMER</td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="10%">PINK</td>
                                <td width="2%">:</td>
                                <td width="">FINANCE</td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="10%">YELLOW</td>
                                <td width="2%">:</td>
                                <td width="">ACC</td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="10%">GREEN</td>
                                <td width="2%">:</td>
                                <td width="">ADMIN</td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="10%">BLUE</td>
                                <td width="2%">:</td>
                                <td width="">EXPEDISI</td>
                            </tr>
                     </table><br>';
		} else {
			$html .= '<h1> Data Kosong </h1>';
		}

		$this->m_fungsi->_mpdf($html);
	}

	function soPlhNoPO()
	{
		$po = $this->db->query("SELECT c.kode_unik,c.nm_pelanggan,s.nm_sales,p.*,d.eta FROM trs_po p
		INNER JOIN trs_po_detail d ON p.no_po=d.no_po AND p.kode_po=d.kode_po
		INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
		INNER JOIN m_sales s ON c.id_sales=s.id_sales
		WHERE status_app1='Y' AND status_app2='Y' AND status_app3='Y' AND d.no_so IS NULL AND d.tgl_so IS NULL AND d.status_so IS NULL
		GROUP BY p.no_po,p.kode_po ORDER BY c.nm_pelanggan,p.no_po")->result();
		echo json_encode(array(
			'po' => $po,
		));
	}

	function soPlhItems()
	{
		$no_po = $_POST["no_po"];
		$poDetail = $this->db->query("SELECT p.nm_produk,p.kode_mc,p.ukuran,p.ukuran_sheet,p.flute,p.kualitas,d.eta,d.* FROM trs_po_detail d
		INNER JOIN trs_po o ON d.no_po=o.no_po AND d.kode_po=o.kode_po
		INNER JOIN m_produk p ON d.id_produk=p.id_produk
		WHERE d.status='Approve' AND d.no_po='$no_po' AND no_so IS NULL AND tgl_so IS NULL")->result();
		echo json_encode(array(
			'po_detail' => $poDetail,
		));
	}

	function soNoSo()
	{
		$item = $_POST["item"];
		$data = $this->db->query("SELECT d.kode_po FROM trs_po_detail d
		INNER JOIN m_produk p ON d.id_produk=p.id_produk
		WHERE d.id='$item'")->row();
		echo json_encode(array(
			'data' => $data,
		));
	}

	function destroySO()
	{
		$this->cart->destroy();
	}

	function addItems()
	{
		if($_POST["no_so"] == ""){
			echo json_encode(array('data' => false, 'isi' => 'NO. SO TIDAK BOLEH KOSONG!'));
			// return;
		}else{
			$data = array(
				'id' => $_POST['idpodetail'],
				'name' => $_POST['idpodetail'],
				'price' => 0,
				'qty' => 1,
				'options' => array(
					'nm_produk' => $_POST['nm_produk'],
					'no_po' => $_POST['no_po'],
					'kode_po' => $_POST['kode_po'],
					'id_produk' => $_POST['item'],
					'id_pelanggan' => $_POST['idpelanggan'],
					'no_so' => $_POST['no_so'],
					'jml_so' => $_POST['jml_so'],
					'rm' => $_POST['rm'],
					'ton' => $_POST['ton'],
					'eta_po' => $_POST['eta_po'],
				)
			);
			if($this->cart->total_items() != 0){
				foreach($this->cart->contents() as $r){
					if($r['id'] == $_POST["idpodetail"]){
						echo json_encode(array('data' => false, 'isi' => 'ITEM SUDAH ADA!'));
						return;
					}
				}
				$this->cart->insert($data);
				echo json_encode(array('data' => true, 'isi' => $data));
			}else{
				$this->cart->insert($data);
				echo json_encode(array('data' => true, 'isi' => $data));
			}
		}
	}

	function showCartItem()
	{
		$html = '';
		if($this->cart->total_items() != 0){
			$html .='<table class="table table-bordered table-striped" style="width:100%">';
			$html .='<thead>
				<tr>
					<input type="hidden" id="table-nopo-value" value="isi">
					<th style="width:5%">NO.</th>
					<th style="width:25%">ITEM</th>
					<th style="width:25%">NO. PO</th>
					<th style="width:25%">NO. SO</th>
					<th style="width:10%">QTY SO</th>
					<th style="width:10%">AKSI</th>
				</tr>
			</thead>';
		}

		$i = 0;
		foreach($this->cart->contents() as $r){
			$i++;
			$html .='<tr>
				<td>'.$i.'</td>
				<td>'.$r['options']['nm_produk'].'</td>
				<td>'.$r['options']['no_po'].'</td>
				<td>'.$r['options']['no_so'].'</td>
				<td>'.number_format($r['options']['jml_so']).'</td>
				<td>
					<button class="btn btn-danger btn-sm" onclick="hapusCartItem('."'".$r['rowid']."'".','."hapusCartItem".','."'showCartItem'".')"><i class="fas fa-times"></i> BATAL</button>
				</td>
			</tr>';
		}

		if($this->cart->total_items() != 0){
			$html .= '</table>';
		}

		echo $html;
	}

	function hapusCartItem()
	{
		$data = array(
			'rowid' => $_POST['rowid'],
			'qty' => 0,
		);
		$this->cart->update($data);
	}

	function simpanSO()
	{
		$result = $this->m_transaksi->simpanSO();
		echo json_encode($result);
	}

	function detailSO()
	{
		$id = $_POST["id"];
		$no_po = $_POST["no_po"];
		$kode_po = $_POST["kode_po"];
		$aksi = $_POST["aksi"];

		$html = '';
		$html .='<table class="table table-bordered table-striped" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%">NO.</th>
					<th style="width:25%">KODE MC</th>
					<th style="width:50%">ITEM</th>
					<th style="width:10%">QTY PO</th>
					<th style="width:10%;text-align:center">AKSI</th>
				</tr>
			</thead>';

		$getSO = $this->db->query("SELECT p.kode_mc,p.nm_produk,p.ukuran_sheet_l,p.ukuran_sheet_p,p.berat_bersih,d.* FROM trs_po_detail d
		INNER JOIN m_produk p ON d.id_produk=p.id_produk
		WHERE no_po='$no_po' AND kode_po='$kode_po'");

		$i = 0;
		foreach($getSO->result() as $r){
			$i++;
			$idPoSo = $r->id;
			($r->id == $id) ? $bHead = 'background:#ccc;border:1px solid #888;' : $bHead = '';
			($r->id == $id) ? $bold = 'font-weight:bold;"' : $bold = 'font-weight:normal;';
			($r->id == $id) ? $borLf = 'border-left:3px solid #0f0;' : $borLf = '';
			if($aksi == 'detail'){
				$btnBagi = '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-minus"></i></button>';
			}else{
				($r->id == $id) ?
					$btnBagi = '<button type="button" class="btn btn-success btn-sm" id="addBagiSO" onclick="addBagiSO('."'".$r->id."'".')"><i class="fas fa-plus"></i></button>
						<button type="button" class="btn btn-danger btn-sm" id="hapusListSO" onclick="hapusListSO('."'".$r->id."'".')"><i class="fas fa-trash"></i></button>' :
					$btnBagi = '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-minus"></i></button>';
			}
			$html .='<tr style="'.$borLf.'">
				<td style="'.$bold.'" class="text-center">'.$i.'</td>
				<td style="'.$bold.'">'.$r->kode_mc.'</td>
				<td style="'.$bold.'">'.$r->nm_produk.'</td>
				<td style="'.$bold.'">'.number_format($r->qty).'</td>
				<td style="'.$bold.'" class="text-center">'.$btnBagi.'</td>
			</tr>';

			$dataSO = $this->db->query("SELECT p.nm_produk,p.ukuran_sheet_l,p.ukuran_sheet_p,p.berat_bersih,s.* FROM trs_so_detail s
			INNER JOIN m_produk p ON s.id_produk=p.id_produk
			WHERE s.id_produk='$r->id_produk' AND s.no_po='$r->no_po' AND s.kode_po='$r->kode_po' AND s.no_so='$r->no_so'");
			
			if($dataSO->num_rows() != 0){
				$html .='<tr style="'.$borLf.'">
					<td colspan="5">
						<table class="table table-bordered table-striped" style="margin:0;border:0;width:100%">
							<thead>
								<tr>
									<th style="width:5%;'.$bHead.''.$bold.'" class="text-center">NO.</th>
									<th style="width:10%;'.$bHead.''.$bold.'">ETA SO</th>
									<th style="width:21%;'.$bHead.''.$bold.'">NO. SO</th>
									<th style="width:15%;'.$bHead.''.$bold.'">QTY SO</th>
									<th style="width:20%;'.$bHead.''.$bold.'">KETERANGAN</th>
									<th style="width:5%;'.$bHead.''.$bold.'" class="text-center">-</th>
									<th style="width:7%;'.$bHead.''.$bold.'">RM</th>
									<th style="width:7%;'.$bHead.''.$bold.'">TON</th>
									<th style="width:10%;'.$bHead.''.$bold.'" class="text-center">AKSI</th>
								</tr>
							</thead>';

				$dataHapusSO = $this->db->query("SELECT COUNT(so.rpt) AS jml_rpt FROM trs_po_detail ps
				INNER JOIN trs_so_detail so ON ps.no_po=so.no_po AND ps.kode_po=so.kode_po AND ps.no_so=so.no_so AND ps.id_produk=so.id_produk
				WHERE ps.id='$idPoSo' GROUP BY so.no_po,so.kode_po,so.no_so,so.id_produk");
				
				($r->id == $id) ? $bTd = 'border:1px solid #999;' : $bTd = '';
				$l = 0 ;
				$sumQty = 0 ;
				$sumRm = 0 ;
				$sumTon = 0 ;
				foreach($dataSO->result() as $so){
					$l++;
					if($aksi == 'detail'){
						$btnHapus = '';
					}else{
						if($so->status == 'Close'){
							$btnHapus = '';
						}else{
							if($r->id == $id){
								if($so->rpt == 1){
									$btnHapus = '';
								}else{
									if($dataHapusSO->row()->jml_rpt == $so->rpt){
										$btnHapus = '<button type="button" class="btn btn-danger btn-sm" onclick="batalDataSO('."'".$so->id."'".')"><i class="fas fa-times"></i></button>';
									}else{
										$btnHapus = '';
									}
								}
							}else{
								$btnHapus = '';
							}
						}
					}


					$link = base_url('Transaksi/laporanSO?id=').$so->id;
					$print = '<a href="'.$link.'" target="_blank"><button type="button" class="btn btn-dark btn-sm"><i class="fas fa-print"></i></button></a>';
					if($aksi == 'detail'){
						$diss = 'disabled';
						$rTxt = '1';
						$btnAksi = $print;
					}else{
						if($so->status == 'Close'){
							$btnAksi = $print;
							$rTxt = 1;
							$diss = 'disabled';
						}else{
							($r->id == $id) ? $diss = '' : $diss = 'disabled';
							($r->id == $id) ? $btnAksi = $print.' <button type="button" class="btn btn-warning btn-sm" id="editBagiSO'.$so->id.'" onclick="editBagiSO('."'".$so->id."'".')"><i class="fas fa-edit"></i></button>' : $btnAksi = $print;
							($r->id == $id) ? $rTxt = 2 : $rTxt = 1;
						}
					}

					($so->cek_rm_so == 0) ? $check = '' : $check = 'checked';
					
					$urut_so = str_pad($so->urut_so, 2, "0", STR_PAD_LEFT);
					$rpt = str_pad($so->rpt, 2, "0", STR_PAD_LEFT);
					$html .='<tr>
						<td style="'.$bTd.''.$bold.'" class="text-center">'.$l.'</td>
						<td style="'.$bTd.''.$bold.'"><input type="date" id="edit-tgl-so'.$so->id.'" class="form-control" value="'.$so->eta_so.'" '.$diss.'></td>
						<td style="'.$bTd.''.$bold.'">'.$so->no_so.'.'.$urut_so.'.'.$rpt.'</td>
						<td style="'.$bTd.''.$bold.'"><input type="number" id="edit-qty-so'.$so->id.'" class="form-control" onkeyup="keyUpQtySO('."'".$so->id."'".')" value="'.$so->qty_so.'" '.$diss.'></td>
						<td style="'.$bTd.''.$bold.'"><textarea class="form-control" id="edit-ket-so'.$so->id.'" rows="'.$rTxt.'" style="resize:none" '.$diss.'>'.$so->ket_so.'</textarea></td>
						<td style="'.$bTd.''.$bold.'">
							<input type="checkbox" id="cbso-'.$so->id.'" style="height:25px;width:100%" onclick="keyUpQtySO('."'".$so->id."'".')" value="'.$so->cek_rm_so.'" '.$check.' '.$diss.'>
						</td>
						<td style="'.$bTd.''.$bold.'">'.number_format($so->rm).'<br><span class="span-rm-h-'.$so->id.'"></span></td>
						<td style="'.$bTd.''.$bold.'">'.number_format($so->ton).'<br><span class="span-ton-h-'.$so->id.'"></span></td>
						<td style="'.$bTd.''.$bold.'" class="text-center">
							<input type="hidden" id="ht-ukl-'.$so->id.'" value="'.$so->ukuran_sheet_l.'">
							<input type="hidden" id="ht-ukp-'.$so->id.'" value="'.$so->ukuran_sheet_p.'">
							<input type="hidden" id="ht-bb-'.$so->id.'" value="'.$so->berat_bersih.'">
							<input type="hidden" id="edit-qtypo-so'.$so->id.'" value="'.$r->qty.'">
							'.$btnAksi.' '.$btnHapus.'
						</td>
					</tr>';
					$sumQty += $so->qty_so;
					$sumRm += $so->rm;
					$sumTon += $so->ton;
				}

				if($dataSO->num_rows() > 1){
					$html .='<tr>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0" colspan="3"></td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0">'.number_format($sumQty).'</td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0"></td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0"></td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0">'.number_format($sumRm).'</td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0">'.number_format($sumTon).'</td>
						<td style="background:#fff;padding:3px;font-weight:bold;text-align:center;border:0"></td>
					</tr>';
				}

				$html .= '</table>
						<div>
							<input type="hidden" id="hide-ukl-so'.$r->id.'" value="'.$r->ukuran_sheet_l.'">
							<input type="hidden" id="hide-ukp-so'.$r->id.'" value="'.$r->ukuran_sheet_p.'">
							<input type="hidden" id="hide-bb-so'.$r->id.'" value="'.$r->berat_bersih.'">
							<input type="hidden" id="hide-qtypo-so'.$r->id.'" value="'.$sumQty.'">
							<input type="hidden" id="hide-rmpo-so'.$r->id.'" value="'.$sumRm.'">
							<input type="hidden" id="hide-tonpo-so'.$r->id.'" value="'.$sumTon.'">
						</div>
						<div id="add-bagi-so-'.$r->id.'"></div>
						<div id="list-bagi-so-'.$r->id.'"></div>
					</td>
				</tr>';
			}
		}

		$html .= '</table>';
		echo $html;
	}

	function editBagiSO()
	{
		$result = $this->m_transaksi->editBagiSO();
		echo json_encode($result);
	}

	function btnAddBagiSO()
	{
		if($_POST["fBagiEtaSo"] == "" || $_POST["fBagiQtySo"] == "" || $_POST["fBagiQtySo"] == 0 || $_POST["fBagiQtySo"] < 0){
			echo json_encode(array('data' => false, 'msg' => 'ETA, QTY SO TIDAK BOLEH KOSONG!'));
		}else{
			$id = $_POST["i"];
			$produk = $this->db->query("SELECT p.* FROM m_produk p INNER JOIN trs_po_detail s ON p.id_produk=s.id_produk WHERE s.id='$id' GROUP BY p.id_produk");
			$RumusOut = 1800 / $produk->row()->ukuran_sheet_l;
			(floor($RumusOut) >= 5) ? $out = 5 : $out = (floor($RumusOut));
			$rm = ($produk->row()->ukuran_sheet_p * $_POST["fBagiQtySo"] / $out) / 1000;
			$ton = $_POST["fBagiQtySo"] * $produk->row()->berat_bersih;

			$getData = $this->db->query("SELECT COUNT(so.rpt) AS jml_rpt,so.* FROM trs_po_detail ps
			INNER JOIN trs_so_detail so ON ps.no_po=so.no_po AND ps.kode_po=so.kode_po AND ps.no_so=so.no_so AND ps.id_produk=so.id_produk
			WHERE ps.id='$id'
			GROUP BY so.no_po,so.kode_po,so.no_so,so.id_produk");

			if($this->cart->total_items() != 0){
				foreach($this->cart->contents() as $r){
					if($r['id'] == $_POST["i"]){
						$rpt = $r['options']['rpt'] + 1;
					}
					if($r['options']['qty_po'] == $_POST["hQtyPo"]){
						$hQtyPo = 0;
					}
					if($r['options']['hRmPo'] == $_POST["hRmPo"]){
						$hRmPo = 0;
					}
					if($r['options']['hTonPo'] == $_POST["hTonPo"]){
						$hTonPo = 0;
					}
				}
				$i = $this->cart->total_items()+1;
			}else{
				$rpt = $getData->row()->jml_rpt + 1;
				$hQtyPo = $_POST["hQtyPo"];
				$hRmPo = $_POST["hRmPo"];
				$hTonPo = $_POST["hTonPo"];
				$i = 1;
			}

			$data = array(
				'id' => $_POST['i'],
				'name' => $_POST['i'],
				'price' => 0,
				'qty' => 1,
				'options' => array(
					'id_pelanggan' => $getData->row()->id_pelanggan,
					'id_produk' => $getData->row()->id_produk,
					'no_po' => $getData->row()->no_po,
					'kode_po' => $getData->row()->kode_po,
					'no_so' => $getData->row()->no_so,
					'urut_so' => $getData->row()->urut_so,
					'rpt' => $rpt,
					'eta_so' => $_POST['fBagiEtaSo'],
					'qty_so' => $_POST['fBagiQtySo'],
					'ket_so' => $_POST['fBagiKetSo'],
					'cek_rm_so' => $_POST['fBagiCrmSo'],
					'rm' => round($rm),
					'ton' => round($ton),
					'total_items' => $i,
					'qty_po' => $hQtyPo,
					'hRmPo' => $hRmPo,
					'hTonPo' => $hTonPo,
				)
			);

			if($_POST["fBagiCrmSo"] == 0){
				if($rm < 500){
					echo json_encode(array('data' => false, 'msg' => 'RM '.round($rm).' . RM KURANG!'));
				}else{
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'msg' => $data));
				}
			}else{
				if(round($rm) == 0 || round($ton) == 0 || round($rm) < 0 || round($ton) < 0 || $rm == "" || $ton == "" ){
					echo json_encode(array('data' => false, 'msg' => 'RM '.round($rm). ' . RM / TONASE TIDAK BOLEH KOSONG!'));
				}else{
					$this->cart->insert($data);
					echo json_encode(array('data' => true, 'msg' => $data));
				}
			}
		}
	}

	function ListAddBagiSO(){
		$html = '';
		if($this->cart->total_items() != 0){
			$html .='<table class="table table-bordered table-striped" style="margin:10px 0 0;border:0;width:100%">';
			$html .='<thead>
				<tr>
					<th style="width:5%;background:#ccc;border:1px solid #888" class="text-center">NO.</th>
					<th style="width:10%;background:#ccc;border:1px solid #888">ETA SO</th>
					<th style="width:21%;background:#ccc;border:1px solid #888">NO. SO</th>
					<th style="width:15%;background:#ccc;border:1px solid #888">QTY SO</th>
					<th style="width:20%;background:#ccc;border:1px solid #888">KETERANGAN</th>
					<th style="width:7%;background:#ccc;border:1px solid #888">RM</th>
					<th style="width:7%;background:#ccc;border:1px solid #888">TON</th>
					<th style="width:15%;background:#ccc;border:1px solid #888" class="text-center">AKSI</th>
				</tr>
			</thead>';
		}

		$i = 0;
		$sumQty = 0;
		$sumRm = 0;
		$sumTon = 0;
		foreach($this->cart->contents() as $r){
			$i++;
			$urut_so = str_pad($r['options']['urut_so'], 2, "0", STR_PAD_LEFT);
			$rpt = str_pad($r['options']['rpt'], 2, "0", STR_PAD_LEFT);
			($this->cart->total_items() == $r['options']['total_items']) ?
				$btnAksi = '<button class="btn btn-danger btn-sm" id="hapusCartItemSO" onclick="hapusCartItem('."'".$r['rowid']."'".','."'".$r['id']."'".','."'ListAddBagiSO'".')"><i class="fas fa-times"></i> <b>BATAL</b></button>' : $btnAksi = '-' ;
			$html .='<tr>
				<td style="border:1px solid #999" class="text-center">'.$i.'</td>
				<td style="border:1px solid #999">'.$r['options']['eta_so'].'</td>
				<td style="border:1px solid #999">'.$r['options']['no_so'].'.'.$urut_so.'.'.$rpt.'</td>
				<td style="border:1px solid #999">'.number_format($r['options']['qty_so']).'</td>
				<td style="border:1px solid #999">'.$r['options']['ket_so'].'</td>
				<td style="border:1px solid #999">'.number_format($r['options']['rm']).'</td>
				<td style="border:1px solid #999">'.number_format($r['options']['ton']).'</td>
				<td style="border:1px solid #999" class="text-center">'.$btnAksi.'</td>
			</tr>';

			$sumQty += $r['options']['qty_po'] + $r['options']['qty_so'];
			$sumRm += $r['options']['hRmPo'] + $r['options']['rm'];
			$sumTon += $r['options']['hTonPo'] + $r['options']['ton'];
		}

		if($this->cart->total_items() != 0){
			$html .= '<tr>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center">'.$r['options']['rpt'].'</td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center" colspan="2"></td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center">'.number_format($sumQty).'</td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center"></td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center">'.number_format($sumRm).'</td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center">'.number_format($sumTon).'</td>
				<td style="background:#fff;padding:3px;font-weight:bold;border:0;text-align:center"></td>
			</tr>
			<tr>
				<td style="font-weight:bold;background:#fff;padding:12px 0;border:0" colspan="6">
					<button class="btn btn-primary btn-sm" id="simpanCartItemSO" onclick="simpanCartItemSO()"><i class="fas fa-save"></i> <b>SIMPAN</b></button>
				</td>
			</tr>';
			$html .= '</table>';
		}

		echo $html;
	}

	function simpanCartItemSO()
	{
		$result = $this->m_transaksi->simpanCartItemSO();
		echo json_encode($result);
	}

	function batalDataSO()
	{
		$result = $this->m_transaksi->batalDataSO();
		echo json_encode($result);
	}

	function hapusListSO()
	{
		$result = $this->m_transaksi->hapusListSO();
		echo json_encode($result);
	}

	function laporanSO(){
		$id = $_GET["id"];
		$data = $this->db->query("SELECT c.nm_pelanggan,c.top,c.fax,c.no_telp,c.alamat,s.nm_sales,o.eta,p.tgl_po,o.tgl_so,p.time_app1,p.time_app2,p.time_app3,i.*,d.* FROM trs_so_detail d
		INNER JOIN trs_po p ON p.no_po=d.no_po AND p.kode_po=d.kode_po
		INNER JOIN trs_po_detail o ON o.no_po=d.no_po AND o.kode_po=d.kode_po AND o.no_so=d.no_so AND o.id_produk=d.id_produk
		INNER JOIN m_produk i ON d.id_produk=i.id_produk
		INNER JOIN m_pelanggan c ON p.id_pelanggan=c.id_pelanggan
		INNER JOIN m_sales s ON c.id_sales=s.id_sales
		WHERE d.id='$id'")->row();

		$html = '<table style="margin-bottom:5px;border-collapse:collapse;vertical-align:top;width:100%;font-weight:bold">
			<tr>
				<th style="width:25%"></th>
				<th style="width:75%"></th>
			</tr>
			<tr>
				<td style="border:0;text-align:center" rowspan="4">
					<img src="'.base_url('assets/gambar/ppi.png').'" width="160" height="140">
				</td>
				<td style="border:0;font-size:30px;padding:19px 0 0">PT. PRIMA PAPER INDONESIA</td>
			</tr>
			<tr>
				<td style="border:0;font-size:12px">DUSUN TIMANG KULON, DESA WONOKERTO, KEC.WONOGIRI, KAB.WONOGIRI</td>
			</tr>
			<tr>
				<td style="border:0;font-size:12px;padding:0 0 27px">WONOGIRI - JAWA TENGAH - INDONESIA. KODE POS 57615</td>
			</tr>
		</table>';

		$urutSo = str_pad($data->urut_so, 2, "0", STR_PAD_LEFT);
		$rpt = str_pad($data->rpt, 2, "0", STR_PAD_LEFT);
		($data->ket_so == "") ? $ketSO = '-' : $ketSO = $data->ket_so;

		$expKualitas = explode("/", $data->kualitas);
		if($data->flute == 'BCF'){
			if($expKualitas[1] == 'M125' && $expKualitas[2] == 'M125' && $expKualitas[3] == 'M125'){
				$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
			}else if($expKualitas[1] == 'K125' && $expKualitas[2] == 'K125' && $expKualitas[3] == 'K125'){
				$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
			}else if($expKualitas[1] == 'M150' && $expKualitas[2] == 'M150' && $expKualitas[3] == 'M150'){
				$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
			}else if($expKualitas[1] == 'K150' && $expKualitas[2] == 'K150' && $expKualitas[3] == 'K150'){
				$kualitas = $expKualitas[0].'/'.$expKualitas[1].'x3/'.$expKualitas[4];
			}else{
				$kualitas = $data->kualitas;
			}
		}else{
			$kualitas = $data->kualitas;
		}

		$html .='<table style="font-size:12px;border-collapse:collapse;vertical-align:top;width:100%">
			<tr>
				<td style="width:10%;border:0;padding:0"></td>
				<td style="width:1%;border:0;padding:0"></td>
				<td style="width:55%;border:0;padding:0"></td>
				<td style="width:11%;border:0;padding:0"></td>
				<td style="width:1%;border:0;padding:0"></td>
				<td style="width:22%;border:0;padding:0"></td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000;padding:1px" colspan="6"></td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000;font-size:20px;font-family:Tahoma;padding:15px 0 2xp;text-align:center;font-weight:bold" colspan="6">SALES ORDER</td>
			</tr>
			<tr>
				<td style="font-size:14px;padding:2px 0 25px;font-style:italic;text-align:center" colspan="6">( NO. SO : '.$data->no_so.'.'.$urutSo.'.'.$rpt.' )</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Tanggal SO</td>
				<td>:</td>
				<td style="padding:5px">'.$this->m_fungsi->tanggal_format_indonesia($data->tgl_so).'</td>
				<td style="padding:5px 0">Created By</td>
				<td>:</td>
				<td style="padding:5px">'.$data->add_user.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">No. PO</td>
				<td>:</td>
				<td style="padding:5px">'.$data->no_po.'</td>
				<td style="padding:5px 0;font-weight:bold;color:#f00;font-size:16px;font-family:Tahoma">ETA</td>
			</tr>
			<tr>
				<td style="padding:5px 0 15px">Sales</td>
				<td style="padding:5px 0 15px">:</td>
				<td style="padding:5px 5px 15px">'.strtoupper($data->nm_sales).'</td>
				<td style="padding:5px 0 15px;font-weight:bold;color:#f00;font-size:16px;font-family:Tahoma" colspan="3">'.strtoupper($this->m_fungsi->tanggal_format_indonesia($data->eta_so)).'</td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000" colspan="6"></td>
			</tr>
			<tr>
				<td style="padding:10px 0 5px">Customer</td>
				<td style="padding:10px 0 5px">:</td>
				<td style="padding:10px 5px 5px">'.$data->nm_pelanggan.'</td>
				<td style="padding:10px 0 5px">TOP</td>
				<td style="padding:10px 0 5px">:</td>
				<td style="padding:10px 5px 5px">'.$data->top.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0" rowspan="3">Alamat</td>
				<td style="padding:5px 0" rowspan="3">:</td>
				<td style="padding:5px" rowspan="3">'.$data->alamat.'</td>
				<td style="padding:5px 0">PO. Date</td>
				<td style="padding:5px 0">:</td>
				<td style="padding:5px">'.$this->m_fungsi->tanggal_format_indonesia($data->tgl_po).'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">No. Hp</td>
				<td>:</td>
				<td style="padding:5px">'.$data->no_telp.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0 15px">FAX</td>
				<td style="padding:5px 0 15px">:</td>
				<td style="padding:5px 5px 15px">'.$data->fax.'</td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000" colspan="6"></td>
			</tr>
			<tr>
				<td style="padding:10px 0">Description</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Kode. PO</td>
				<td>:</td>
				<td style="padding:5px">'.$data->kode_po.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Kode. MC</td>
				<td>:</td>
				<td style="padding:5px">'.$data->kode_mc.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Item</td>
				<td>:</td>
				<td style="padding:5px">'.$data->nm_produk.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Uk. Box</td>
				<td>:</td>
				<td style="padding:5px">'.$data->ukuran.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Uk. Sheet</td>
				<td>:</td>
				<td style="padding:5px">'.$data->ukuran_sheet.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Creasing</td>
				<td>:</td>
				<td style="padding:5px">'.$data->creasing.' - '.$data->creasing2.' - '.$data->creasing3.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Kualitas</td>
				<td>:</td>
				<td style="padding:5px">'.$kualitas.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Flute</td>
				<td>:</td>
				<td style="padding:5px">'.$data->flute.'</td>
			</tr>
			<tr>
				<td style="padding:5px 0">Qty PO</td>
				<td>:</td>
				<td style="padding:5px">'.number_format($data->qty_so).'</td>
			</tr>
			<tr>
				<td style="padding:5px 0 15px">Keterangan</td>
				<td style="padding:5px 0 15px">:</td>
				<td style="padding:5px 5px 25px">'.$ketSO.'</td>
			</tr>
			<tr>
				<td style="border-top:1px solid #000" colspan="6"></td>
			</tr>
		</table>';

		$html .='<table style="font-size:12px;text-align:center;border-collapse:collapse;vertical-align:top;width:100%">
			<tr>
				<td style="width:30%;border:0;padding:5px"></td>
				<td style="width:5%;border:0;padding:5px"></td>
				<td style="width:30%;border:0;padding:5px"></td>
				<td style="width:5%;border:0;padding:5px"></td>
				<td style="width:30%;border:0;padding:5px"></td>
			</tr>
			<tr>
				<td style="padding:5px">Marketing</td>
				<td></td>
				<td style="padding:5px">PPIC</td>
				<td></td>
				<td style="padding:5px">Owner</td>
			</tr>
			<tr>
				<td style="padding:5px">'.strtoupper($data->nm_sales).'</td>
				<td></td>
				<td style="padding:5px">DION AGUS PRANOTO</td>
				<td></td>
				<td style="padding:5px">WILLIAM ALEXANDER HARTONO</td>
			</tr>
			<tr>
				<td style="padding:5px">'.$this->m_fungsi->tanggal_format_indonesia(substr($data->time_app1,0,10)).' '.substr($data->time_app1,11,10).'</td>
				<td></td>
				<td style="padding:5px">'.$this->m_fungsi->tanggal_format_indonesia(substr($data->time_app2,0,10)).' '.substr($data->time_app2,11,10).'</td>
				<td></td>
				<td style="padding:5px">'.$this->m_fungsi->tanggal_format_indonesia(substr($data->time_app3,0,10)).' '.substr($data->time_app3,11,10).'</td>
			</tr>
		</table>';

		$judul = 'SO - '.$data->no_so.'.'.$urutSo.'.'.$rpt;
		$this->m_fungsi->newMpdf($judul, 'footer', $html, 10, 10, 10, 10, 'P', 'A4', $judul.'.pdf');
	}

	function pilihanEtaPO()
	{
		$html ='';

		$getData = $this->db->query("SELECT eta_so,COUNT(eta_so) AS jml FROM trs_so_detail
		GROUP BY eta_so");

		$html .='<div class="card-body row" style="padding-bottom:20px;font-weight:bold">';
		$html .='<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th style="text-align:center">NO.</th>
				<th>TANGGAL</th>
				<th style="text-align:center">JUMLAH</th>
			</tr>
		</thead>';
		$i = 0;
		foreach($getData->result() as $r){
			$i++;
			$html .= '</tr>
				<td style="text-align:center">'.$i.'</td>
				<td><a href="javascript:void(0)" onclick="tampilDataEtaPO('."'".$r->eta_so."'".',)">'.strtoupper($this->m_fungsi->tanggal_format_indonesia($r->eta_so)).'<a></td>
				<td style="text-align:center">'.$r->jml.'</td>
			</tr>';
		}
		$html .='</table>
		</div>';

		echo $html;
	}

	function tampilDataEtaPO()
	{
		$html = '';
		$tgl = $_POST["tgl"];

		$html .='<div class="card card-info card-outline">
		<div class="card-body row" style="padding-bottom:20px;font-weight:bold">';
		
		$getData = $this->db->query("SELECT * FROM trs_so_detail so
		INNER JOIN m_pelanggan p ON so.id_pelanggan=p.id_pelanggan
		WHERE so.eta_so='$tgl'");
		if($getData->num_rows() == 0){
			$html .= 'DATA KOSONG!';
		}else{
			$html .='<div class="col-md-12" style="margin-bottom:10px">
				DATA ETA TANGGAL : '.strtoupper($this->m_fungsi->tanggal_format_indonesia($tgl)).'
			</div>';
			$html .='<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="text-align:center">NO.</th>
					<th style="text-align:center">CUSTOMER</th>
					<th style="text-align:center">NO. PO</th>
				</tr>
			</thead>';
			$i = 0;
			foreach($getData->result() as $r){
				$i++;
				$html .='<tr>
					<td style="text-align:center">'.$i.'</td>
					<td>'.$r->nm_pelanggan.'</td>
					<td>'.$r->kode_po.'</td>
				</tr>';
			}

		}

		$html .='</div></div>';

		echo $html;
	}

	public function Hitung_harga()
	{
		$cek 	= $this->session->userdata('username');

		if(($this->session->userdata('level')))
		{
			$data = [
				'menu'  => '<span style="color:red">SIMULASI HARGA *</span>',
				'judul' => "Simulasi Harga",
			];
			
			$this->load->view('header', $data);

			if(($this->session->userdata('username'))=='bujenik')
			{				
				$this->load->view('hitung_harga/v_hitung_harga_jumbo', $data);
			}else{				
				$this->load->view('hitung_harga/v_hitung_harga', $data);
			}
			
			$this->load->view('footer');

		} else {
			header('location:'.base_url());
		}

		
	}

	public function coba_api()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=12",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"key: c479d0aa6880c0337184539462eeec6f"
		),
		));

		$response   = curl_exec($curl);
		$err        = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			// echo $response;
			echo json_encode($response);
		}
	}

}
