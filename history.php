<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://muhammaddavid.blogspot.com
	 * @keterangan : Controller untuk halaman profil
	 **/
	
    function __construct(){
        parent::__construct();
        $this->load->library(array('auth','template','pagination','form_validation','upload','email'));
		$this->auth->check_user_authentification();
		if($this->session->userdata('ADMIN')>'1'){$this->session->set_userdata('SUCCESSMSG', 'Maaf anda tidak memiliki akses.'); redirect('home'); }
	}
    
	public function index()	{
		$data['title']="History";
		$this->db->order_by($order_column='id',$order_type='desc');
		$this->db->limit(500);
		$data['info'] = $this->db->get('history')->result();
			$data['main_content'] = 'history';
			$this->load->view('lte/live', $data);
	}
	
	public function login()	{
		$data['title']="Login History";
		$this->db->order_by($order_column='id',$order_type='desc');
		$this->db->limit(500);
		$data['info'] = $this->db->get('login_history')->result();
			$data['main_content'] = 'login_history';
			$this->load->view('lte/live', $data);
	}
	
	public function view()	{
        $kode=$this->uri->segment(3);
        $this->db->where('id',$kode);
		$this->db->update('history',array('cek'=>'1'));
                redirect('history');
	}
	
}