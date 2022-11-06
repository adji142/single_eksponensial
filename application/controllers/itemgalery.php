<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class itemgalery extends CI_Controller {

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
			$rs = $this->ModelsExecuteMaster->GetData('tgalery');
		}
		else{
			$where = array(
				'id' => $id
			);
			$rs = $this->ModelsExecuteMaster->FindData($where,'tgalery');
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
		$Nama 	= $this->input->post('Nama');
		$Deskripsi 	= $this->input->post('Deskripsi');

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
				'id'	=> 0,
				'Nama'	=> $Nama,
				'Deskripsi'		=> $Deskripsi,
				'Image'	=> $image
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecInsert($param,'tgalery');
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
				'id'	=> $id,
				'Nama'	=> $Nama,
				'Deskripsi'		=> $Deskripsi,
				'Image'	=> $image
			);
			try {
				$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=> $id),'tgalery');
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
			$rs = $this->ModelsExecuteMaster->DeleteData($where,'tgalery');
			$data['success'] = true;
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = "Gagal memproses data ". $e->getMessage();
		}
		echo json_encode($data);
	}
}