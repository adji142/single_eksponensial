<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemMaster extends CI_Controller {

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

		$kodeitem = $this->input->post('kodeitem');
		
		if ($kodeitem == '') {
			$rs = $this->ModelsExecuteMaster->GetData('titemmasterdata');
		}
		else{
			$where = array(
				'kodeitem' => $kodeitem
			);
			$rs = $this->ModelsExecuteMaster->FindData($where,'titemmasterdata');
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

		$kodeitem 	= $this->input->post('kodeitem');
		$namaitem 	= $this->input->post('namaitem');
		$Harga 		= $this->input->post('Harga');
		$formtype 	= $this->input->post('formtype');


		$config['upload_path']="./Assets/images/upload";
        $config['allowed_types']='gif|jpg|png';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload',$config);

		if ($formtype == 'add') {
			if($this->upload->do_upload("file")){
	            $data = array('upload_data' => $this->upload->data());
	 
	            $judul= $this->input->post('judul');
	            $image= $data['upload_data']['file_name']; 
	             
	            // $result= $this->m_upload->simpan_upload($judul,$image);
	            // echo json_decode($result);
	        }

			$param = array(
				'kodeitem'	=> $kodeitem,
				'namaitem'	=> $namaitem,
				'Harga'		=> $Harga,
				'Image'	=> $image
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'titemmasterdata');
				$data['success'] = true;
			} catch (Exception $e) {
				$data['success'] = false;
				$data['message'] = "Gagal memproses data ". $e->getMessage();
			}
		}
		elseif ($formtype == 'edit') {
			if($this->upload->do_upload("file")){
	            $data = array('upload_data' => $this->upload->data());
	 
	            $judul= $this->input->post('judul');
	            $image= $data['upload_data']['file_name']; 
	             
	            // $result= $this->m_upload->simpan_upload($judul,$image);
	            // echo json_decode($result);
	        }

			$param = array(
				'kodeitem'	=> $kodeitem,
				'namaitem'	=> $namaitem,
				'Harga'		=> $Harga,
				'Image'	=> $image
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('kodeitem'=> $kodeitem),'titemmasterdata');
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
		
		$kodeitem = $this->input->post('kodeitem');
		
		try {
			$where = array(
				'kodeitem'	=> $kodeitem
			);
			$rs = $this->ModelsExecuteMaster->DeleteData($where,'titemmasterdata');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
}