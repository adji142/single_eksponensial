<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apps extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelsExecuteMaster');
		$this->load->model('GlobalVar');
		$this->load->model('Apps_mod');
	}
	
	// =========================== BATAS =========================== 
	public function FindData()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$where_field = $this->input->post('where_field');
		$where_value = $this->input->post('where_value');
		$table 		 = $this->input->post('table');
		
		if ($where_field == '') {
			$rs = $this->ModelsExecuteMaster->GetData($table);
		}
		else{
			$where = array(
				$where_field => $where_value
			);
			$rs = $this->ModelsExecuteMaster->FindData($where,$table);
		}
		if ($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mengambil data';
		}
		echo json_encode($data);
	}
	public function remove()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());
		
		$table = $this->input->post('table');
		$field = $this->input->post('field');
		$value = $this->input->post('value');
		
		try {
			$where = array(
				$field	=> $value
			);
			$rs = $this->ModelsExecuteMaster->DeleteData($where,$table);
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}

	public function appendTransaksi()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$tgltransaksi 	= $this->input->post('tgltransaksi');
		$NoRef 			= '';
		$Merk 			= '';
		$tipe 			= $this->input->post('tipe');
		$jml 			= $this->input->post('jml');

		$formtype 		= $this->input->post('formtype');
		$id 			= $this->input->post('id');

		if ($formtype == 'add') {
			$param = array(
				'Tanggal'	=> $tgltransaksi,
				'NoRef'		=> $NoRef,
				'Merk'		=> $Merk,
				'Tipe'		=> $tipe,
				'Qty'		=> $jml
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'ttransaksi');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		elseif ($formtype == 'edit') {
			$param = array(
				'Tanggal'	=> $tgltransaksi,
				'NoRef'		=> $NoRef,
				'Merk'		=> $Merk,
				'Tipe'		=> $tipe,
				'Qty'		=> $jml
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=> $id),'ttransaksi');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = "Invalid Form Type ";
		}
		echo json_encode($data);
	}

	public function addUsers()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$Username		= $this->input->post('Username');
		$Password		= $this->input->post('Password');
		$tipe 			= $this->input->post('tipe');
		// $akses			= $this->input->post('akses');

		$formtype 		= $this->input->post('formtype');
		$id 			= $this->input->post('id');

		if ($formtype == 'add') {
			$param = array(
				'username'	=> $Username,
				'username'	=> $Username,
				'password'	=> $this->encryption->encrypt($Password)
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'users');
				$xuser = $this->ModelsExecuteMaster->FindData(array('username'=>$Username),'users');
				if ($xuser->num_rows() > 0) {
					if ($tipe == "1") {
						$insert = array(
							'userid' 	=> $xuser->row()->id,
							'roleid'	=> 1,
						);
					}
					elseif ($tipe == "2") {
						$insert = array(
							'userid' 	=> $xuser->row()->id,
							'roleid'	=> 2,
						);
					}
					
					$call_x = $this->ModelsExecuteMaster->ExecInsert($insert,'userrole');
					if ($call_x) {
						$data['success'] = true;
					}
				}
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		else{
			$param = array(
				'username'	=> $Username,
				'username'	=> $Username,
				'password'	=> $this->encryption->encrypt($Password)
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=> $id),'ttransaksi');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		echo json_encode($data);
	}

	public function initialData()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$rs = $this->db->query("
				SELECT 
					CONCAT(DATE_FORMAT(Tanggal,'%b'),'-',YEAR(Tanggal))  Periode,
					SUM(CASE WHEN Tipe = 'Disperindag' THEN Qty else 0 END) Disperindag,
					SUM(CASE WHEN Tipe = 'Kelurahan' THEN Qty else 0 END) Kelurahan,
					SUM(CASE WHEN Tipe = 'Umum' THEN Qty else 0 END) Umum
				FROM ttransaksi 
				WHERE YEAR(Tanggal) = '2020'
				GROUP BY CONCAT(DATE_FORMAT(Tanggal,'%b'),'-',YEAR(Tanggal))
				ORDER BY MONTH(Tanggal)
			");

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
	public function initialData2nd()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		$startmont = $this->input->post('startmont').'-01';
		$endmont = $this->input->post('endmont').'-'.date('t',strtotime($this->input->post('endmont').'-01'));

		// var_dump($startmont.' - '.$endmont);
		$rs = $this->db->query("
				SELECT 
					CONCAT(DATE_FORMAT(Tanggal,'%b'),'-',YEAR(Tanggal))  Periode,
					SUM(CASE WHEN Tipe = 'Disperindag' THEN Qty else 0 END) Disperindag,
					SUM(CASE WHEN Tipe = 'Kelurahan' THEN Qty else 0 END) Kelurahan,
					SUM(CASE WHEN Tipe = 'Umum' THEN Qty else 0 END) Umum
				FROM ttransaksi 
				WHERE Tanggal BETWEEN '".$startmont."' AND '".$endmont."'
 				GROUP BY CONCAT(DATE_FORMAT(Tanggal,'%b'),'-',YEAR(Tanggal))
				ORDER BY Tanggal
			");

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
	public function AddForecast()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$Periode = $this->input->post('Periode');
		$Jenis = $this->input->post('Jenis');
		$Jumlah = $this->input->post('Jumlah');
		$F01 = $this->input->post('F01');
		$F02 = $this->input->post('F02');
		$F03 = $this->input->post('F03');
		$F04 = $this->input->post('F04');
		$F05 = $this->input->post('F05');
		$F06 = $this->input->post('F06');
		$F07 = $this->input->post('F07');
		$F08 = $this->input->post('F08');
		$F09 = $this->input->post('F09');
		$number = $this->input->post('number');

		$param = array(
			'Periode'	=> $Periode,
			'Jenis'		=> $Jenis,
			'Jumlah'	=> $Jumlah,
			'F01'		=> $F01,
			'F02'		=> $F02,
			'F03'		=> $F03,
			'F04'		=> $F04,
			'F05'		=> $F05,
			'F06'		=> $F06,
			'F07'		=> $F07,
			'F08'		=> $F08,
			'F09'		=> $F09,
			'number'	=> $number
		);

		try {
			$this->ModelsExecuteMaster->ExecInsert($param,'tforcast');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
	public function RemoveData()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		$Jenis = $this->input->post('Jenis');
		
		$query = "TRUNCATE tforcast";
		$rs = $this->db->query($query);
		//$rs = $this->ModelsExecuteMaster->DeleteData(array('Jenis' => $Jenis),'tforcast');
		if($rs){
				$data['success'] = true;
		}

		echo json_encode($data);
	}

	public function ShowDataForcast()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$Jenis = $this->input->post('Jenis');

		$rs = $this->db->query("
				SELECT * FROM tforcast where Jenis = '".$Jenis."' order by number
			");

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
	public function RemoveMae()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		$Jenis = $this->input->post('Jenis');
		
		$query = "TRUNCATE tmae";
		$rs = $this->db->query($query);
		//$rs = $this->ModelsExecuteMaster->DeleteData(array('Jenis' => $Jenis),'tmae');
		if($rs){
				$data['success'] = true;
		}

		echo json_encode($data);
	}
	public function AddMae()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$Jenis = $this->input->post('Jenis');
		$F01 = $this->input->post('F01');
		$F02 = $this->input->post('F02');
		$F03 = $this->input->post('F03');
		$F04 = $this->input->post('F04');
		$F05 = $this->input->post('F05');
		$F06 = $this->input->post('F06');
		$F07 = $this->input->post('F07');
		$F08 = $this->input->post('F08');
		$F09 = $this->input->post('F09');

		$param = array(
			'Jenis'		=> $Jenis,
			'F01'		=> $F01,
			'F02'		=> $F02,
			'F03'		=> $F03,
			'F04'		=> $F04,
			'F05'		=> $F05,
			'F06'		=> $F06,
			'F07'		=> $F07,
			'F08'		=> $F08,
			'F09'		=> $F09
		);

		try {
			$this->ModelsExecuteMaster->ExecInsert($param,'tmae');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
	public function ShowDataMAE()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$Jenis = $this->input->post('Jenis');

		$rs = $this->db->query("
				SELECT * FROM tmae where Jenis = '".$Jenis."' order by id
			");

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
	public function GetTransaction()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());
		
		$tipe = $this->input->post('tipe');
		$tglawal = $this->input->post('tglawal');
		$tglakhir = $this->input->post('tglakhir');

		$query = "select * from ttransaksi where Tanggal between '".$tglawal."' and '".$tglakhir."' and tipe = '".$tipe."'";
		// var_dump($query);
		$rs = $this->db->query($query);
		if ($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mengambil data';
		}
		echo json_encode($data);
	}
	public function GetverifiedForecast()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());
		
		$Field = $this->input->post('Field');
		$tipe = $this->input->post('tipe');

		$query = "select number,".$Field." data from tforcast where Jenis = '".$tipe."' order by number";
		// var_dump($query);
		$rs = $this->db->query($query);
		if ($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mengambil data';
		}
		echo json_encode($data);
	}


	public function HPPSetting()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$hargakain		= $this->input->post('hargakain');
		$hargakaret		= $this->input->post('hargakaret');
		$ongkosjait 	= $this->input->post('ongkosjait');
		$ongkospotong	= $this->input->post('ongkospotong');
		$biayakemas 	= $this->input->post('biayakemas');
		$pemakaiankainperpcs = $this->input->post('pemakaiankainperpcs');
		$Jenis = $this->input->post('Jenis');

		$formtype 		= $this->input->post('formtype');
		$id 			= $this->input->post('id');

		if ($formtype == 'add') {
			$param = array(
				'hargakain'		=> $hargakain,
				'hargakaret'	=> $hargakaret,
				'ongkosjait'	=> $ongkosjait,
				'ongkospotong'	=> $ongkospotong,
				'biayakemas'	=> $biayakemas,
				'pemakaiankainperpcs' =>$pemakaiankainperpcs,
				'Jenis'			=> $Jenis
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'thppsetting');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		elseif($formtype == 'edit'){
			$param = array(
				'hargakain'		=> $hargakain,
				'hargakaret'	=> $hargakaret,
				'ongkosjait'	=> $ongkosjait,
				'ongkospotong'	=> $ongkospotong,
				'biayakemas'	=> $biayakemas,
				'pemakaiankainperpcs' => $pemakaiankainperpcs,
				'Jenis'			=> $Jenis
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=> $id),'thppsetting');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		elseif ($formtype == 'delete') {
			try {
				$where = array(
					'id'	=> $id
				);
				$rs = $this->ModelsExecuteMaster->DeleteData($where,'thppsetting');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		echo json_encode($data);
	}
	public function ShowForecstHasilGG()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		$mae = $this->input->post('mae');
		$tipe = $this->input->post('tipe');
		// var_dump($startmont.' - '.$endmont);
		$sql = "
				SELECT 
					Periode,
					Jenis,
					ROUND(".$mae.") Forecast
				FROM tforcast where jenis = '".$tipe."' ORDER BY number DESC LIMIT 1
			";
		// var_dump($sql);
		$rs = $this->db->query($sql);

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
	public function InputHasilForecast()
	{
		$data = array('success' => false ,'message'=>array());

		$PeriodeHasil = $this->input->post('PeriodeHasil');
		$Jenis = $this->input->post('Jenis');
		$Forecast = $this->input->post('Forecast');
		$usedMae = $this->input->post('usedMae');

		$param = array(
			'PeriodeHasil' => $PeriodeHasil,
			'TglProses'	=> date("Y-m-d h:i:sa"),
			'Jenis' => $Jenis,
			'Forecast' => $Forecast,
			'MaE' => $usedMae
		);

		try {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'thasilforecast');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}

		echo json_encode($data);
	}

	public function LaporanHasilForecast()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$tglawal = $this->input->post('tglawal');
		$tglakhir = $this->input->post('tglakhir');
		$Jenis = $this->input->post('Jenis');
		
		$sql = "
				SELECT *,CONCAT(MONTHNAME(PeriodeHasil),' - ',YEAR(PeriodeHasil)) Periode FROM thasilforecast WHERE date(TglProses) BETWEEN '".$tglawal."' AND '".$tglakhir."' AND Jenis = '".$Jenis."' ORDER by TglProses DESC;
			";
		// var_dump($sql);
		$rs = $this->db->query($sql);

		if ($rs){
			$data['success'] = true;
			$data['data']= $rs->result();
		}
		else{
			$data['message']='Gagal Proses Initial Data testing';
		}
		echo json_encode($data);
	}
}