<?php

class Cabang extends CI_Controller {

	function Cabang()
	{
		parent::__construct();
        $this->load->library(array('auth','template','form_validation','pagination','upload'));
		$this->auth->check_user_authentification();
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Maaf anda tidak memiliki akses.'); redirect('home'); }
	}
	
	function index()
	{
		if($this->session->userdata('ADMIN')=='2'){ redirect('cabang/edit/'.$this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Cabang";
		$data['main_content'] = 'cabang/index';		
		$this->load->view('lte/live', $data);
	}
			
			
    function add(){
		$data['title'] = "Cabang";
        $data['no']=$this->db->count_all_results('wp')+1;
        $nocabang=$this->db->count_all_results('wp')+1;
        $noakun=$this->db->count_all_results('akun')+1;
        if($nocabang >'9'){ $nocab = $nocabang ; }else{ $nocab = '0'.$nocabang ;}
        $this->_set_rules();
        if($this->form_validation->run()==true){//jika validasi dijalankan dan benar
			$info=array(
				'id'=>$this->input->post('id'),
				'nama'=>$this->input->post('nama'),
				'npwp'=>$this->input->post('npwp'),
				'kode'=>$this->input->post('kode'),
				'kode_akun'=>$nocab,
				'pbbkb'=>$this->input->post('pbbkb'),
				'alamat'=>$this->input->post('alamat'),
				'alamat1'=>$this->input->post('alamat1'),
				'kelurahan'=>$this->input->post('kelurahan'),
				'kecamatan'=>$this->input->post('kecamatan'),
				'kota'=>$this->input->post('kota'),
				'provinsi'=>$this->input->post('provinsi'),
				'telp'=>$this->input->post('telp'),
				'fax'=>$this->input->post('fax'),
				'email'=>$this->input->post('email'),
				'email1'=>$this->input->post('email1'),
				'email2'=>$this->input->post('email2'),
				'bank'=>$this->input->post('bank'),
				'namarek'=>$this->input->post('namarek'),
				'rekening'=>$this->input->post('rekening'),
				'pemilik'=>$this->input->post('pemilik'),
				'kepala'=>$this->input->post('kepala'),
				'keuangan'=>$this->input->post('keuangan'),
				'pembelian'=>$this->input->post('pembelian'),
				'penjualan'=>$this->input->post('penjualan'),
				'operasional'=>$this->input->post('operasional'),
				'pemasaran'=>$this->input->post('pemasaran'),
				'keterangan'=>$this->input->post('keterangan'),
				'warna'=>$this->setting_model->getWarna($this->input->post('id')),
				'login_id'=>$this->session->userdata('SESS_USER_ID')
			);
			$this->db->insert('wp',$info);

        $akun_standart=$this->db->get_where("akun_standar",array('cek'=>'0'));
        $allakun=$akun_standart->result();
        foreach($allakun as $row){
        if($nocabang >'9'){ $nocab = $nocabang ; }else{ $nocab = '0'.$nocabang ;}
		$jenis_akun_id = $row->jenis_akun_id;
		$kategori_akun_id = $this->akun_model->KategoriId($jenis_akun_id);
		$kelompok_akun_id = $this->akun_model->KelompokAkunKategori($kategori_akun_id);
            $info=array(
                'nama'=>$row->nama,
                'kode'=>$nocab.'00'.$row->kode,
				'kelompok_akun_id'=>$kelompok_akun_id,
				'kategori_akun_id'=>$kategori_akun_id,
                'jenis_akun_id'=>$jenis_akun_id,
                'po'=>$row->po,
                'do'=>$row->do,
                'inv'=>$row->inv,
                'pay'=>$row->pay,
				'wp_id'=>$nocabang,
				'login_id'=>$this->session->userdata('SESS_USER_ID')
            );
			$this->db->insert("akun",$info);
        }
			redirect('cabang');
        }else{
        if($nocabang >'9'){ $data['nocab'] = $nocabang  ;}else{ $data['nocab'] = '0'.$nocabang  ;}
            $data['message']="";
			$data['main_content'] = 'cabang/tambah';
			$this->load->view('lte/live', $data);
        }
    }
    			
	function view($id)
	{
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Cabang.'); redirect('cabang');}
		if($this->bbm_model->check_wp($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('cabang');}
		if($this->session->userdata('ADMIN')=='2'){ $id=$this->session->userdata('SESS_WP_ID'); }	
		$data['title'] = "Cabang";
		$data['main_content'] = 'cabang/view';		
		$data['info'] = $this->pajak_model->get_data($id);
		$this->load->view('lte/live', $data);
	}
			
	function buat_akun()
	{
		if($this->session->userdata('ADMIN')=='2'){$this->session->set_userdata('SUCCESSMSG', 'Maaf anda tidak memiliki akses.'); redirect('home'); }
        $akun_standart=$this->db->get_where("akun_standar",array('cek'=>'0'));
        $allakun=$akun_standart->result();
        foreach($allakun as $row){
        if($row->login_id >'9'){ $nocab = $row->login_id ; }else{ $nocab = '0'.$row->login_id ;}
			$jenis_akun_id = $row->jenis_akun_id;
			$kategori_akun_id = $this->akun_model->KategoriId($jenis_akun_id);
			$kelompok_akun_id = $this->akun_model->KelompokAkunKategori($kategori_akun_id);
            $info=array(
                'nama'=>$row->nama,
                'kode'=>$nocab.'00'.$row->kode,
				'kelompok_akun_id'=>$kelompok_akun_id,
				'kategori_akun_id'=>$kategori_akun_id,
                'jenis_akun_id'=>$jenis_akun_id,
                'po'=>$row->po,
                'do'=>$row->do,
                'inv'=>$row->inv,
                'pay'=>$row->pay,
				'wp_id'=>$row->login_id,
				'login_id'=>$this->session->userdata('SESS_USER_ID')
            );
			$this->db->insert("akun",$info);
        }
			redirect('cabang');
	}
			
	function pemutihan()
	{
        // if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu customer.'); redirect('jurnal');}
		// if($this->bbm_model->check_jurnal_detail($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('jurnal');}
		if($this->session->userdata('ADMIN')>='2'){ $this->session->set_userdata('SUCCESSMSG', 'Bahaya untuk Anda'); redirect('cabang');}
		$data=array('emailpo'=>'','emaildo'=>'','emailinv'=>'','emailpay'=>''); 
		$this->db->update('wp',$data);
		redirect('cabang');
	}
	
    function hapus_standart($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu ID.'); redirect('cabang');}
		if($this->bbm_model->check_jual($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('cabang');}
		if($this->session->userdata('ADMIN')>'2'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('cabang');}
		$this->db->where('login_id >', $id); $this->db->delete('akun_standar'); redirect('cabang');		
    }
    
    function edit($id){
		$data['title'] = "Cabang";
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Cabang.'); redirect('cabang');}
		if($this->bbm_model->check_wp($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('cabang');}
		if($this->session->userdata('ADMIN')>'1'){ $id=$this->session->userdata('SESS_WP_ID'); }	
        $this->_set_rules(); if($this->form_validation->run()==true){
            $info=array(
				'nama'=>$this->input->post('nama'),
				'npwp'=>$this->input->post('npwp'),
				'kode'=>$this->input->post('kode'),
				'pbbkb'=>$this->input->post('pbbkb'),
				'alamat'=>$this->input->post('alamat'),
				'alamat1'=>$this->input->post('alamat1'),
				'kelurahan'=>$this->input->post('kelurahan'),
				'kecamatan'=>$this->input->post('kecamatan'),
				'kota'=>$this->input->post('kota'),
				'provinsi'=>$this->input->post('provinsi'),
				'telp'=>$this->input->post('telp'),
				'fax'=>$this->input->post('fax'),
				'email'=>$this->input->post('email'),
				'email1'=>$this->input->post('email1'),
				'email2'=>$this->input->post('email2'),
				'bank'=>$this->input->post('bank'),
				'namarek'=>$this->input->post('namarek'),
				'rekening'=>$this->input->post('rekening'),
				'pemilik'=>$this->input->post('pemilik'),
				'kepala'=>$this->input->post('kepala'),
				'keuangan'=>$this->input->post('keuangan'),
				'pembelian'=>$this->input->post('pembelian'),
				'penjualan'=>$this->input->post('penjualan'),
				'operasional'=>$this->input->post('operasional'),
				'pemasaran'=>$this->input->post('pemasaran'),
				'accso'=>$this->input->post('accso'),
				'accsos'=>$this->input->post('accsos'),
				// 'accpo'=>$this->input->post('accpo'),
				'accpo'=>'1',
				'accdo'=>$this->input->post('accdo'),
				'accinv'=>$this->input->post('accinv'),
				'accpay'=>$this->input->post('accpay'),
				'emailso'=>$this->input->post('emailso'),
				// 'emailpo'=>$this->input->post('emailpo'),
				'emailpo'=>$this->setting_model->Web(),
				'emaildo'=>$this->input->post('emaildo'),
				'emailinv'=>$this->input->post('emailinv'),
				'emailpay'=>$this->input->post('emailpay'),
				'keterangan'=>$this->input->post('keterangan'),
				'login_id'=>$this->session->userdata('SESS_USER_ID')
            );

			$this->db->where('id',$id);
			$this->db->update('wp',$info);
		if($this->session->userdata('ADMIN')=='2'){ redirect('cabang/view/'.$this->session->userdata('SESS_WP_ID')); }	
			$data['info'] = $this->pajak_model->get_data($id);
            $data['message']="<div class='alert alert-success'>Data berhasil diupdate</div>";
			$data['main_content'] = 'cabang/edit';		
			$this->load->view('lte/live', $data);
        }else{
            $data['message']="";
			$data['info'] = $this->pajak_model->get_data($id);
			$data['main_content'] = 'cabang/edit';		
			$this->load->view('lte/live', $data);
        }
    }
            
    function _set_rules(){
        $this->form_validation->set_rules('id','ID','required|max_length[15]');
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>","</div>");
    }

}
/* End of file cabang.php */
/* Location: ./application/controllers/cabang.php */
