<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alamat_kirim extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://muhammaddavid.blogspot.com
	 * @keterangan : Controller untuk halaman profil
	 **/
	
    function __construct(){
        parent::__construct();
        $this->load->library(array('auth','template','pagination','form_validation','upload','email'));
		$this->auth->check_user_authentification();
	}
    
	public function index()	{
		$data['title']="Alamat Kirim";
		$data['main_content'] = 'alamat_kirim/index';
		$this->load->view('lte/live', $data);
	}
	
	public function cetak()	{
		$data['title']="Alamat Kirim";
		if($this->session->userdata('ADMIN')>='2'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }	
		$data['info']=$this->db->get_where('alamat_kirim',array('cek'=>'0'))->result();
		$data['main_content'] = 'alamat_kirim/cetak';
		$this->load->view('lte/live', $data);
	}
	
    function tambah(){
        $data['title']="Alamat Kirim";
        $this->_set_rules();
        if($this->form_validation->run()==true){//jika validasi dijalankan dan benar
			$info=array(
				'id'=>$this->input->post('id'),
				'alamat'=>$this->input->post('alamat'),
				'customer_id'=>$this->input->post('customer_id'),
				'wp_id'=>$this->session->userdata('SESS_WP_ID'),
				'login_id'=>$this->session->userdata('SESS_USER_ID')
			);
			$this->db->insert('alamat_kirim',$info);
			redirect('alamat_kirim');
        }else{
            $data['message']="";
			$data['main_content'] = 'alamat_kirim/tambah';
			$this->load->view('lte/live', $data);
        }
    }
    
    function edit($id){
        $data['title']="Alamat Kirim";
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Penjualan.'); redirect('alamat_kirim');}
		if($this->bbm_model->check_alamat_kirim($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('alamat_kirim');}
		if($this->session->userdata('ADMIN')>='2'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }	
        $this->_set_rules();
        if($this->form_validation->run()==true){
            $id=$this->input->post('id');   
            $info=array( 'alamat'=>$this->input->post('alamat') );
			$this->db->where('id',$id);
			$this->db->update('alamat_kirim',$info);
			redirect('alamat_kirim/edit/'.$id);
        }else{
            $data['message']="";
			$this->db->where('id',$id);
			$cek=$this->db->get('alamat_kirim');
            $data['info']=$cek->row_array();
			$data['main_content'] = 'alamat_kirim/edit';
			$this->load->view('lte/live', $data);
        }
    }
            
    function hapus(){
        $id=$this->uri->segment(3);
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Penjualan.'); redirect('alamat_kirim');}
		if($this->bbm_model->check_alamat_kirim($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('alamat_kirim');}
		if($this->session->userdata('ADMIN')>='2'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }	
		if($this->session->userdata('ADMIN')>='3'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('alamat_kirim');}
		$info=array( 'cek'=>'1' ); $this->db->where('id',$id); $this->db->update('alamat_kirim',$info);
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."alamat_kirim'>";			
    }
    
    function _set_rules(){
        $this->form_validation->set_rules('id','ID','required|max_length[15]');
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>","</div>");
    }
}