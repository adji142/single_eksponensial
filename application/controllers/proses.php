<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class proses extends CI_Controller {

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
		$this->load->model('LoginMod');
	}

	public function GetInitalData()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array(), 'dataOpt'=> array(), 'dataForcast'=> array(), 'dataFinal'=> array());

		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		$query = "
			SELECT 
				CAST(CONCAT(YEAR(TglTransaksi),MONTH(TglTransaksi)) AS INTEGER) BulanIndex,
				CONCAT(MONTHNAME(TglTransaksi), ' ' ,YEAR(TglTransaksi)) Bulan,
				SUM(Qty) Total
			FROM transaksi WHERE TglTransaksi BETWEEN  '".$TglAwal."' AND '".$TglAkhir."'
			GROUP BY CONCAT(MONTHNAME(TglTransaksi), ' ' ,YEAR(TglTransaksi))
			ORDER BY CAST(CONCAT(YEAR(TglTransaksi),MONTH(TglTransaksi)) AS INTEGER)
		";

		// var_dump($query);
		$rs = $this->db->query($query);

		if ($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();

			$query = "
				SELECT
					SUM(Qty) Total,
					COUNT(Qty) Count,
					ROUND(2/COUNT(Qty),1) Alpha
				FROM transaksi WHERE TglTransaksi BETWEEN  '".$TglAwal."' AND '".$TglAkhir."'
			";

			$rs2 = $this->db->query($query);

			if ($rs2) {
				$data['dataOpt'] = $rs2->result();

				// Generate Data

				$row = 0;

				$dataForecast = [];

				$tempForecast = array('Indicator'=>'','Aktual'=>0,'Forcast'=>0,'Error'=>0,'RSFE'=>0,'AbsError'=> 0,'AbsTotal'=>0,'MAD'=>0,'TrackingSignal'->0);

				$dataRow = $rs->result_array();
				for ($i=0; $i < count($dataRow) ; $i++) { 
					// var_dump($dataRow[$i]);
					$tempForecast['Aktual'] = $dataRow[$i]['Total'];
					if ($i == 0) {
						$tempForecast['Indicator'] = 'F'.$i;
						$tempForecast['Forcast'] = $rs2->row()->Total / $rs2->row()->Count;
					}
					else{
						$tempForecast['Indicator'] = 'F'.$i;
						$tempForecast['Forcast'] = $dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']);
						// var_dump($dataForecast[$i-1]['Value']);
					}

					// var_dump($tempForecast);
					array_push($dataForecast, $tempForecast);
				}
				// var_dump($dataForecast);
				$data['dataForcast'] = $dataForecast;
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mengambil data';
		}

		echo json_encode($data);
	}
}
