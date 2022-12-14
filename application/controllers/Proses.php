<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Proses extends CI_Controller {

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

	public function GetForcasting(){
		$data = array('success' => false ,'message'=>array(),'data' =>array(), 'dataOpt'=> array(), 'dataForcast'=> array(), 'dataFinal'=> array());

		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		// Get Per Item
		$query = "
				SELECT
					KodeItem,
					SUM(Qty) Total,
					COUNT(Qty) Count,
					ROUND(2/COUNT(Qty),1) Alpha
				FROM transaksi WHERE TglTransaksi BETWEEN  '".$TglAwal."' AND '".$TglAkhir."'
				GROUP BY KodeItem
			";

		// var_dump($query);
		$rs = $this->db->query($query);

		$DataPerItem = [];
		$tempPerItem = array('KodeItem' => '','Total'=>0,'Count'=>0,'Alpha' =>0,'FixForecast'=>0, 'HasilForecast'=>array());
		if ($rs){
			$dataFirst = $rs->result_array();
			for ($x=0; $x < count($dataFirst) ; $x++) { 
				// Read Transaksi Per Item
				$dataForecast = [];

				$tempData = array('Indicator' => 0, 'Item'=> '', 'Aktual'=>'','Forcast'=>'');

				$query = "
					SELECT 
						TglTransaksi,
						KodeItem,
						SUM(Qty) Total
					FROM transaksi WHERE TglTransaksi BETWEEN  '".$TglAwal."' AND '".$TglAkhir."'
					AND KodeItem = '".$dataFirst[$x]['KodeItem']."'
					GROUP BY TglTransaksi,KodeItem
					ORDER BY TglTransaksi
				";

				$rs2 = $this->db->query($query);

				$dataRow = $rs2->result_array();

				$fixForecast = 0;

				if ($rs2) {
					for ($i=0; $i < count($dataRow) ; $i++) { 
						if ($i == count($dataRow)){
							$tempForecast['Aktual'] = 0;
						}
						else{
							$tempForecast['Aktual'] = $dataRow[$i]['Total'];
						}

						if ($i == 0) {
							$tempForecast['Indicator'] = $i+1;
							$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

							$tempForecast['Forcast'] = $dataFirst[$x]['Total'] / $dataFirst[$x]['Count'];
						}
						else{
							$tempForecast['Indicator'] = $i+1;
							$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

							$tempForecast['Forcast'] = $dataForecast[$i-1]['Forcast'] + $dataFirst[$x]['Alpha'] * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']);
						}

						array_push($dataForecast, $tempForecast);
						$fixForecast = $dataForecast[count($dataForecast) - 1]['Forcast'];
					}
				}

				// 'Total'=>0,'Count'=>0,'Alpha' =>0 FixForecast

				$tempPerItem['KodeItem'] = $dataFirst[$x]['KodeItem'];
				$tempPerItem['HasilForecast'] = $dataForecast;
				$tempPerItem['Total'] = $dataFirst[$x]['Total'];
				$tempPerItem['Count'] = $dataFirst[$x]['Count'];
				$tempPerItem['Alpha'] = $dataFirst[$x]['Alpha'];
				$tempPerItem['FixForecast'] = $fixForecast;

				array_push($DataPerItem, $tempPerItem);
			}
			$data['dataForcast'] = $DataPerItem;
			$data['success'] = true;
			$data['data'] = $rs->result();
		}

		echo json_encode($data);
	}
	public function GetInitalData()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array(), 'dataOpt'=> array(), 'dataForcast'=> array(), 'dataFinal'=> array());

		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		$query = "
			SELECT 
				TglTransaksi,
				KodeItem,
				SUM(Qty) Total
			FROM transaksi WHERE TglTransaksi BETWEEN  '".$TglAwal."' AND '".$TglAkhir."'
			GROUP BY TglTransaksi,KodeItem
			ORDER BY TglTransaksi
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

				$tempForecast = array('Indicator'=>'','Tanggal'=>'','Item'=>'','Aktual'=>0,'Forcast'=>0,'Error'=>0,'RSFE'=>0,'AbsError'=> 0,'AbsTotal'=>0,'MAD'=>0,'TrackingSignal'=>0);

				$dataRow = $rs->result_array();
				for ($i=0; $i < count($dataRow) +1 ; $i++) { 
					// var_dump($dataRow[$i]);
					if ($i == count($dataRow)){
						$tempForecast['Aktual'] = 0;
					}
					else{
						$tempForecast['Aktual'] = $dataRow[$i]['Total'];
					}
					if ($i == 0) {
						$tempForecast['Indicator'] = 'F'.($i+1);
						$tempForecast['Tanggal'] = $dataRow[$i]['TglTransaksi'];
						$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

						$tempForecast['Forcast'] = $rs2->row()->Total / $rs2->row()->Count;

						// $tempForecast['Error'] = $dataRow[$i]['Total'] - $rs2->row()->Total / $rs2->row()->Count;
						// $tempForecast['RSFE'] = $dataRow[$i]['Total'] - $rs2->row()->Total / $rs2->row()->Count - 0;
						// $tempForecast['AbsError'] = abs($dataRow[$i]['Total'] - $rs2->row()->Total / $rs2->row()->Count - 0);
						// $tempForecast['AbsTotal'] = abs(abs($dataRow[$i]['Total'] - $rs2->row()->Total / $rs2->row()->Count - 0));
						// $tempForecast['MAD'] = abs(abs($dataRow[$i]['Total'] - $rs2->row()->Total / $rs2->row()->Count - 0)) / ($i+1);
					}
					elseif ($i==0) {
						$tempForecast['Indicator'] = 'F'.($i+1);
						$tempForecast['Tanggal'] = $dataRow[$i]['TglTransaksi'];
						$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

						$tempForecast['Forcast'] = $dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']);
						// $tempForecast['Error'] = $dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']));
						// $tempForecast['RSFE'] = $dataForecast[$i-1]['Error'] + ($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])));

						// $tempForecast['AbsError'] = abs($dataForecast[$i-1]['Error'] + ($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']))));
						// $tempForecast['AbsTotal'] = $dataForecast[$i-1]['AbsTotal'] + abs($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])));

						// $tempForecast['MAD'] = ($dataForecast[$i-1]['AbsTotal'] + abs($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])))) / ($i+1);

					}
					elseif ($i == count($dataRow)) {
						$tempForecast['Indicator'] = 'F'.($i+1);
						$tempForecast['Tanggal'] = $dataRow[$i]['TglTransaksi'];
						$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

						$tempForecast['Forcast'] = $dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']);
						// $tempForecast['Error'] = 0;
						// $tempForecast['RSFE'] = 0;
						// $tempForecast['AbsError'] = 0;
						// $tempForecast['AbsTotal'] = 0;
						// $tempForecast['MAD'] = 0;
					}
					else{
						$tempForecast['Indicator'] = 'F'.($i+1);
						$tempForecast['Tanggal'] = $dataRow[$i]['TglTransaksi'];
						$tempForecast['Item'] = $dataRow[$i]['KodeItem'];

						$tempForecast['Forcast'] = $dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']);
						// $tempForecast['Error'] = $dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']));
						// $tempForecast['RSFE'] = $dataForecast[$i-1]['RSFE'] + ($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])));
						// $tempForecast['AbsError'] = abs($dataForecast[$i-1]['RSFE'] + ($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast']))));
						// $tempForecast['AbsTotal'] = $dataForecast[$i-1]['AbsTotal'] + abs($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])));

						// $tempForecast['MAD'] = ($dataForecast[$i-1]['AbsTotal'] + abs($dataRow[$i]['Total'] - ($dataForecast[$i-1]['Forcast'] + $rs2->row()->Alpha * ($dataRow[$i-1]['Total'] - $dataForecast[$i-1]['Forcast'])))) / ($i+1);
						// var_dump($dataForecast[$i-1]['Value']);
					}

					// var_dump($tempForecast);
					array_push($dataForecast, $tempForecast);
				}

				// for ($x=0; $x < count($dataForecast) ; $x++) { 
				// 	// $tempForecast['Indicator'] = $dataForecast[$x]['Indicator'];
				// 	// $tempForecast['Forcast'] = $dataForecast[$x]['Forcast'];
				// 	// $tempForecast['Error'] = $dataForecast[$x]['Error'];
				// 	// $tempForecast['RSFE'] = $dataForecast[$x]['RSFE'];
				// 	// $tempForecast['AbsError'] = $dataForecast[$x]['AbsError'];
				// 	// $tempForecast['AbsTotal'] = $dataForecast[$x]['AbsTotal'];
				// 	// $tempForecast['MAD'] = $dataForecast[$x]['MAD'];
				// 	// $tempForecast['TrackingSignal'] = $dataForecast[$x]['MAD'] / ($x+1);

				// 	// array_replace($dataForecast, $tempForecast);

				// 	if ($x != count($dataForecast) -1) {
				// 		$dataForecast[$x]['TrackingSignal'] = $dataForecast[$x]['RSFE'] / $dataForecast[$x]['MAD'];
				// 	}
					
					
				// }
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
