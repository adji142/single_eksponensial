<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

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
	public function index()
	{
		$this->load->view('Dashboard');
	}
	public function login()
	{
		$this->load->view('index');
	}
	// --------------------------------------- Master ----------------------------------------------------
	public function itemmaster()
	{
		$this->load->view('itemmasterdata');
	}
	public function itemgalery()
	{
		$this->load->view('itemgalery');
	}
	public function transaksi()
	{
		$this->load->view('transaksi');
	}
	public function proses()
	{
		$this->load->view('proses');
	}
}
