<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

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

		$id = $this->input->post('id');
		
		if ($id == '') {
			$rs = $this->ModelsExecuteMaster->GetData('transaksi');
		}
		else{
			$where = array(
				'id' => $id
			);
			$rs = $this->ModelsExecuteMaster->FindData($where,'transaksi');
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

	public function ReadByTgl()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$tglawal = $this->input->post('tglawal');
		$tglakhir = $this->input->post('tglakhir');
		
		$query = "select * from transaksi where TglTransaksi between '".$tglawal."' and '".$tglakhir."' ";
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

	public function appendTransaksi(){
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$id 			= $this->input->post('id');
		$TglTransaksi 	= $this->input->post('TglTransaksi');
		$KodeItem 		= $this->input->post('KodeItem');
		$Qty 		= $this->input->post('Qty');
		$Harga 		= $this->input->post('Harga');

		$formtype 		= $this->input->post('formtype');

		if ($formtype == 'add') {
			$param = array(
				'id'	=> $id,
				'TglTransaksi'	=> $TglTransaksi,
				'KodeItem'		=> $KodeItem,
				'Qty'			=> $Qty,
				'Harga'			=> $Harga
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'transaksi');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		elseif ($formtype == 'edit') {
			$param = array(
				'id'	=> $id,
				'TglTransaksi'	=> $TglTransaksi,
				'KodeItem'		=> $KodeItem,
				'Qty'			=> $Qty,
				'Harga'			=> $Harga
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=> $id),'transaksi');
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

	public function remove()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());
		
		$id = $this->input->post('id');
		
		try {
			$where = array(
				'id'	=> $id
			);
			$rs = $this->ModelsExecuteMaster->DeleteData($where,'transaksi');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
}