<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart extends CI_Controller {

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

	public function Read()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$UniqID = $this->input->post('UniqID');
		
		if ($UniqID == '') {
			$rs = $this->ModelsExecuteMaster->GetData('chart');
		}
		else{
			$query = "
				SELECT a.*, b.namaitem, b.Harga AS HargaItem FROM chart a 
				LEFT JOIN titemmasterdata b on a.KodeItem = b.kodeitem
				WHERE a.UniqID = '".$UniqID."'
			";
			$rs = $this->db->query($query);
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

	public function appendTransaksi(){
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$id 	= $this->input->post('id');
		$UniqID 	= $this->input->post('UniqID');
		$KodeItem 	= $this->input->post('KodeItem');
		$Qty 	= $this->input->post('Qty');
		$Harga 	= $this->input->post('Harga');

		$param = array(
				'id'		=> 0,
				'UniqID'	=> $UniqID,
				'KodeItem'	=> $KodeItem,
				'Qty'		=> $Qty,
				'Harga'		=> $Harga
			);
		try {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'chart');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}

	public function CheckOut()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$UniqID 	= $this->input->post('UniqID');
		$TglTransaksi 	= $this->input->post('TglTransaksi');
		$Nama 	= $this->input->post('Nama');
		$NoWA 	= $this->input->post('NoWA');

		$Query = "
			insert into transaksi
			SELECT 0,'".$TglTransaksi."',a.KodeItem,a.Qty,b.Harga,'".$UniqID."','".$Nama."','".$NoWA."' FROM chart a 
			LEFT JOIN titemmasterdata b on a.KodeItem = b.kodeitem
			where a.UniqID = '".$UniqID."';
		";

		try {
			$rs = $this->db->query($Query);	

			$where = array(
				'UniqID'	=> $UniqID
			);
			$rs = $this->ModelsExecuteMaster->DeleteData($where,'chart');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
	public function remove()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());
		
		$UniqID = $this->input->post('UniqID');
		try {
			$where = array(
				'UniqID'	=> $UniqID
			);
			$rs = $this->ModelsExecuteMaster->DeleteData($where,'chart');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
}