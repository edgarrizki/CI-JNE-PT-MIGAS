# CI-JNE-PT-MIGAS
SISTEM INFORMASI MANGEMENT MIGAS JNE
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class A_his extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://edgarrizki.id
	 * @keterangan : Controller untuk halaman profil
	 **/
	
    function __construct(){
        parent::__construct();
        $this->load->library(array('template','form_validation','pagination','upload'));

	}
    
	public function index()	{
		$data['title']="History";
		$this->db->order_by($order_column='id',$order_type='desc');
		$this->db->limit(500);
		$data['info'] = $this->db->get('history')->result();
		$this->load->view('admin/histori',$data);
	}
}
