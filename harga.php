<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://muhammaddavid.blogspot.com
	 * @keterangan : Controller untuk halaman profil
	 **/
	
    function __construct(){
        parent::__construct();
        $this->load->library(array('auth','template','form_validation','pagination','upload'));
		$this->auth->check_user_authentification();
		$this->load->helper('finance');
		$this->load->library('fpdf');
		define('FPDF_FONTPATH',$this->config->item('fonts_path'));
	}
    
	function index()	{
		$data['title']="List Harga Rata-Rata BBM";
		if($this->session->userdata('ADMIN')>'1'){ redirect('harga/cabang/'.$this->session->userdata('SESS_WP_ID')); }	
		$this->db->select_min('tgl'); $d = $this->db->get('beli');
		$r=$d->num_rows();if($r>0){foreach($d->result()as$h){$tbl=$h->tgl;}}else{$tbl='';} 
		$all=  (((strtotime(date('Y-m-d')))-(strtotime($tbl)))/86400)+2; 
		$this->db->limit($all);
		$this->db->order_by($order_column='id',$order_type='asc');
		$this->db->group_by("tgl"); 
		$data['info'] = $this->db->get('history')->result();
		$data['main_content'] = 'harga/index';
		$this->load->view('lte/live', $data);
	}
	
	function rumus()	{
		$data['title']="List Harga Rata-Rata BBM";
		if($this->session->userdata('ADMIN')>'1'){ redirect('harga/cabang/'.$this->session->userdata('SESS_WP_ID')); }	
		$this->db->select_min('tgl'); $d = $this->db->get('beli');
		$r=$d->num_rows();if($r>0){foreach($d->result()as$h){$tbl=$h->tgl;}}else{$tbl='';} 
		$all=  (((strtotime(date('Y-m-d')))-(strtotime($tbl)))/86400)+2; 
		$this->db->limit($all);
		$this->db->order_by($order_column='id',$order_type='asc');
		$this->db->group_by("tgl"); 
		$data['info'] = $this->db->get('history')->result();
		$data['main_content'] = 'harga/rumus';
		$this->load->view('lte/live', $data);
	}
	
	function cabang($id)	{
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Cabang.'); redirect('cabang');}
		if($this->bbm_model->check_wp($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('cabang');}
		if($this->session->userdata('ADMIN')>'1'){ $id=$this->session->userdata('SESS_WP_ID'); }
		$data['title']="List Harga Rata-Rata BBM Cabang"; $now = date('Y-m-1');
		$this->db->where('wp_id', $id); $this->db->select_min('tgl'); $d = $this->db->get('beli');
		$r=$d->num_rows();if($r>0){foreach($d->result()as$h){$tbl=$h->tgl;}}else{$tbl='';} 
		if($tbl==0){ $all = 0; }else{ $all=  (((strtotime(date('Y-m-d')))-(strtotime($tbl)))/86400)+2;  }
		$this->db->limit($all);
		$this->db->order_by($order_column='id',$order_type='asc');
		$this->db->group_by("tgl"); 
		$data['info'] = $this->db->get('history')->result();
		$data['limit'] = $all;
		$data['main_content'] = 'harga/cabang';
		$this->load->view('lte/live', $data);
	}
	
	function grafik()
	{
		$data['title'] = "Grafik Harga Rata-Rata BBM";
		$data['main_content'] = 'harga/grafik';
		$this->load->view('lte/template', $data);	
	}
	
}