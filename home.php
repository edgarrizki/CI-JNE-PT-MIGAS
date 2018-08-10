<?php

class Home extends CI_Controller {

	function Home()
	{
		parent::__construct();
		$this->load->library('auth');
		$this->auth->check_user_authentification();
	}
	
	function index()
	{
		$data['title'] = "Selamat Datang";
		$data['main_content'] = 'home';
		$this->load->view('lte/template', $data);	
	}
	
	function read($tabel){
		$data['tabel'] = $tabel;
		$this->load->view('home_tabel', $data);	
	}

	function update(){
		$tabel= $this->input->post("tabel"); $id= $this->input->post("id");
		$value= $this->input->post("value"); $modul= $this->input->post("modul");
		$this->db->where(array("id"=>$id)); $this->db->update($tabel,array($modul=>$value));
		echo "{}";
	}

}
/* End of file home.php */
/* Location: ./application/controllers/home.php */
