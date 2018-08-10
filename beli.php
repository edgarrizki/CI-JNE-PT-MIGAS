<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beli extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://muhammaddavid.blogspot.com
	 * @keterangan : Controller untuk halaman profil
	 **/
	 
    function __construct(){
        parent::__construct();
        $this->load->library(array('auth','template','form_validation','pagination','upload','email','fpdf'));
		$this->auth->check_user_authentification();
		define('FPDF_FONTPATH',$this->config->item('fonts_path'));
		$this->load->helper(array('form', 'url','finance','indodate'));
		date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
		if($this->session->userdata('ADMIN')>'4'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('home');} }
    
	function index()	{
		$data['title']="Daftar Pembelian BBM";
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index'; $this->load->view('lte/live', $data); }
	
	function wait()	{
		$data['title']="Draft Pembelian BBM";
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status'=>'0'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index'; $this->load->view('lte/live', $data); }
	
	function prosses()	{
		$data['title']="Pembelian BBM dalam Proses ACC";
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status'=>'1'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index'; $this->load->view('lte/live', $data); }
	
	function cancel()	{
		$data['title']="Pembelian BBM Telah di-Hapus";
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'1'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index'; $this->load->view('lte/live', $data); }
	
	function success()	{
		$data['title']="Pembelian BBM Telah di-ACC";
		if($this->uri->segment(3)==''){ }else{$this->db->where('tgl', $this->uri->segment(3)); }
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status >'=>'1'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index'; $this->load->view('lte/live', $data); }
	
	function prepaid()	{
		$data['title']="Pembelian BBM Telah di-ACC Belum Bayar ke Supplier";
		if($this->uri->segment(3)==''){ }else{$this->db->where('tgl', $this->uri->segment(3)); }
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status'=>'2'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index_bayar'; $this->load->view('lte/live', $data); }
	
	function pay()	{
		$data['title']="Pembelian BBM dalam Proses Bayar";
		if($this->session->userdata('ADMIN')>'3'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status'=>'3'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index_bayar'; $this->load->view('lte/live', $data);	}
	
	function paid()	{
		$data['title']="Pembelian BBM Telah di-Bayar";
		if($this->session->userdata('ADMIN')>'3'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status'=>'4'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/index_bayar'; $this->load->view('lte/live', $data);	}
	
	function rekap()	{
		$data['title']="Rekap Pembelian BBM";
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='id',$order_type='desc'); $beli = $this->db->get_where('beli',array('cek'=>'0','status >='=>'2'));
		$data['info'] = $beli->result(); $data['main_content'] = 'beli/rekap'; $this->load->view('lte/tabel_proyek', $data); }
	
	function filter()	{
		$data['title']="Rekap Pembelian BBM";
		$cabang = $this->uri->segment(3); $from = $this->uri->segment(4); $to = $this->uri->segment(5);
        if($from > $to){$this->session->set_userdata('SUCCESSMSG', 'Tanggal yang anda pilih salah.'); redirect('beli/rekap/');}
		  if($this->uri->segment(4)==''){ $this->db->where('tgl >=', '2000-01-01'); $this->db->where('tgl <=', '2100-01-01');
		  }else{ $this->db->where('tgl >=', $from); $this->db->where('tgl <=', $to); }
		if($this->uri->segment(3)=='0'){ }else{ $this->db->where('wp_id', $cabang); }
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='tgl',$order_type='asc'); $data['info'] = $this->db->get_where('beli',array('cek'=>'0','status >='=>'2'));
		$data['main_content'] = 'beli/filter'; $this->load->view('lte/tabel_proyek', $data); }
	
	function filter_pdf()	{
		$data['title']="Rekap Pembelian BBM";
		$cabang = $this->uri->segment(3); $from = $this->uri->segment(4); $to = $this->uri->segment(5);
        if($from > $to){$this->session->set_userdata('SUCCESSMSG', 'Tanggal yang anda pilih salah.'); redirect('beli/rekap/');}
		  if($this->uri->segment(4)==''){ $this->db->where('tgl >=', '2000-01-01'); $this->db->where('tgl <=', '2100-01-01');
		  }else{ $this->db->where('tgl >=', $from); $this->db->where('tgl <=', $to); }
		if($this->uri->segment(3)=='0'){ }else{ $this->db->where('wp_id', $cabang); }
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='tgl',$order_type='asc'); $data['isi'] = $this->db->get_where('beli',array('cek'=>'0','status >='=>'2'));
        $cek=$this->db->get_where('beli',array('cek'=>'0','status >='=>'2')); $data['info']=$cek->row_array(); $this->load->view('beli/filter_pdf', $data); }
	
	function filter_excel()	{
		$data['title']="Rekap Pembelian BBM";
		$cabang = $this->uri->segment(3); $from = $this->uri->segment(4); $to = $this->uri->segment(5);
        if($from > $to){$this->session->set_userdata('SUCCESSMSG', 'Tanggal yang anda pilih salah.'); redirect('beli/rekap/');}
		  if($this->uri->segment(4)==''){ $this->db->where('tgl >=', '2000-01-01'); $this->db->where('tgl <=', '2100-01-01');
		  }else{ $this->db->where('tgl >=', $from); $this->db->where('tgl <=', $to); }
		if($this->uri->segment(3)=='0'){ }else{ $this->db->where('wp_id', $cabang); }
		if($this->session->userdata('ADMIN')>'1'){ $this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); }  
		$this->db->order_by($order_column='tgl',$order_type='asc'); $data['isi'] = $this->db->get_where('beli',array('cek'=>'0','status >='=>'2'));
        $cek=$this->db->get_where('beli',array('cek'=>'0','status >='=>'2')); $data['info']=$cek->row_array(); $this->load->view('beli/filter_excel', $data); }
	
    function view($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $data['main_content'] = 'beli/view'; $this->load->view('lte/live', $data); }
                        
    function view_bayar($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $data['main_content'] = 'beli/view_bayar'; $this->load->view('lte/live', $data); }
                        
    function cetak($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $this->load->view('beli/cetak', $data); }
                        
    function pdf($id){
		if($this->session->userdata('ADMIN')>'1'){$this->session->set_userdata('SUCCESSMSG', 'anda tidak memiliki akses.'); redirect('beli'); }
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $this->load->view('beli/pdf',$data); }
        
// Hapus Beli
    function hapus($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('beli');} 
        $kode=$this->uri->segment(3); $data=array('cek'=>'1','login_id'=>$this->session->userdata('SESS_USER_ID'));
		$this->db->where('id',$kode); $this->db->update('beli',$data); $this->db->where('id_beli',$kode); $this->db->update('do',$data);
		date_default_timezone_set("Asia/Jakarta");
		$data_jurnal=array('keterangan'=>'Jurnal ini telah dihapus oleh '.$this->user_model->NamaUser($this->session->userdata('SESS_USER_ID'))
		.' pada tanggal '.date('d-m-Y').' Jam '.date('H:i'));
        $jurnal_id=$this->bbm_model->JurnalBeli($kode); $this->db->where('id',$jurnal_id); $this->db->update('jurnal',$data_jurnal);
		$this->db->where('jurnal_id', $jurnal_id); $this->db->delete('jurnal_detail');
		$this->session->set_userdata('SUCCESSMSG', 'Data Pembelian dan Jurnal Telah dihapus.'); echo "<meta http-equiv='refresh' content='0; url=".site_url('beli')."'>";			
    }
    
// Tambah
    function add(){
        $data['title']="Purchase Order - In Tax"; $data['tgl']=date('Y-m-d'); $tgl=date('Ymd'); 
		$thn=date('Y'); $this->db->where('year(tgl)', $thn); 
		$this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); $data['no']=$this->db->count_all_results('beli')+1;
        $this->_set_rules(); if($this->form_validation->run()==true){//jika validasi dijalankan dan benar
			
			// harga    
			$cek_harga	= $this->input->post('cek_harga'); $supplier = $this->input->post('supplier_id'); $jml = $this->input->post('jml'); $h1 = $this->input->post('harga');
			//Pajak
			$discount = $this->input->post('discount'); $ndiscount = $this->input->post('ndiscount');
			$ohp = $this->input->post('ohp'); if($ohp=='0'){ $nohp = '0'; }else{ $nohp = $this->input->post('ppnohp'); } $ppnohp = $nohp;
			$ppn = $this->input->post('ppn'); $pbbkb = $this->input->post('pbbkb'); $npbbkb = $this->input->post('npbbkb'); $pph = $this->input->post('pph');
			if($cek_harga=='0'){ $harga = $h1; }else{ $harga = (($h1/(100+($ppn*10)+($pbbkb*$npbbkb)+($pph*0.3)))*100); } //include
			//Total
			$tot1 = $jml*$harga; if($ndiscount==''){ $tot2 = $tot1*$discount/100; }else{ $tot2 = $ndiscount; }
			$tot3 = $tot1-$tot2; $tot4 = $tot3/10*$ppn; $tot5 = $tot3*($npbbkb/100)*$pbbkb; $tot6 = $tot3*3/1000*$pph; $tot7 = $jml*$ohp;
			$tot8 = $tot7*$ppnohp/10; $tt78	= $tot7+$tot8; $tot9 = $tot3+$tot4+$tot5+$tot6+$tt78;
			$info=array(
				'id'=>$this->input->post('id'), 'id_beli'=>$this->input->post('id_beli'), 'supplier_id'=>$supplier, 
				'import'=>$this->supplier_model->ImportSupplier($supplier), 'transportir'=>$this->input->post('transportir'), 
				'tgl'=>$this->input->post('tgl'), 'sh'=>$this->input->post('sh'), 'sp'=>$this->input->post('sp'), 
				'term'=>$this->input->post('term'), 'tempo'=>$this->input->post('tempo'), 'storage'=>$this->input->post('storage'), 
				'barang_id'=>'1', 'jml'=>$jml, 'harga'=>$harga, 'discount'=>$discount, 'ohp'=>$ohp, 
				'ppnohp'=>$ppnohp, 'ppn'=>$ppn, 'npbbkb'=>$npbbkb, 'pbbkb'=>$pbbkb, 'pph'=>$pph,
				'tot1'=>$tot1, 'tot2'=>$tot2, 'tot3'=>$tot3, 'tot4'=>$tot4, 'tot5'=>$tot5, 
				'tot6'=>$tot6, 'tot7'=>$tot7, 'tot8'=>$tot8, 'tot9'=>$tot9,
				'wp_id'=> $this->session->userdata('SESS_WP_ID'), 'login_id'=> $this->session->userdata('SESS_USER_ID')
			);
			$this->db->insert('beli',$info);
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, Silahkan isi info tambahan dibawah ini'); redirect('beli/rounding/'.$this->db->count_all_results('beli'));
        }else{
            $data['message']=""; $data['main_content'] = 'beli/add'; $this->load->view('lte/live', $data);
        }
    }
    
    function edit($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Edit Harga PO - In Tax"; $data['tgl']=date('Y-m-d'); $tgl=date('Ymd');
		$this->db->where('wp_id', $this->session->userdata('SESS_WP_ID')); $data['no']=$this->db->count_all_results('beli')+1;
        $this->_set_rules(); if($this->form_validation->run()==true){//jika validasi dijalankan dan benar
			
			// harga    
			$cek_harga	= $this->input->post('cek_harga'); $supplier = $this->input->post('supplier_id'); $jml = $this->input->post('jml'); $h1 = $this->input->post('harga');
			//Pajak
			$discount = $this->input->post('discount'); $ndiscount = $this->input->post('ndiscount');
			$ohp = $this->input->post('ohp'); if($ohp=='0'){ $nohp = '0'; }else{ $nohp = $this->input->post('ppnohp'); } $ppnohp = $nohp;
			$ppn = $this->input->post('ppn'); $pbbkb = $this->input->post('pbbkb'); $npbbkb = $this->input->post('npbbkb'); $pph = $this->input->post('pph');
			// $include		11000 = (10000+(10000*((1*10)/100))+(10000*((0*0)/100))+(10000*((0*0.3)/100))); // $harga	9090,909091 = ((10000/(100+(1*10)+(0*0)+(0*0.3)))*100);
			if($cek_harga=='0'){ $harga = $h1; }else{ $harga = (($h1/(100+($ppn*10)+($pbbkb*$npbbkb)+($pph*0.3)))*100); } //include
			//Total
			$tot1 = $jml*$harga; if($ndiscount==''){ $tot2 = $tot1*$discount/100; }else{ $tot2 = $ndiscount; }
			$tot3 = $tot1-$tot2; $tot4 = $tot3/10*$ppn; $tot5 = $tot3*($npbbkb/100)*$pbbkb; $tot6 = $tot3*3/1000*$pph; $tot7 = $jml*$ohp;
			$tot8 = $tot7*$ppnohp/10; $tt78	= $tot7+$tot8; $tot9 = $tot3+$tot4+$tot5+$tot6+$tt78;
			$info=array(
				'supplier_id'=>$supplier, 'tgl'=>$this->input->post('tgl'), 'term'=>$this->input->post('term'), 'tempo'=>$this->input->post('tempo'),
				'storage'=>$this->input->post('storage'), 'barang_id'=>'1', 'jml'=>$jml, 'harga'=>$harga, 
				'discount'=>$discount, 'ohp'=>$ohp, 'ppnohp'=>$ppnohp, 'ppn'=>$ppn, 'npbbkb'=>$npbbkb, 'pbbkb'=>$pbbkb, 'pph'=>$pph,
				'tot1'=>$tot1, 'tot2'=>$tot2, 'tot3'=>$tot3, 'tot4'=>$tot4, 'tot5'=>$tot5, 'tot6'=>$tot6, 'tot7'=>$tot7, 'tot8'=>$tot8, 'tot9'=>$tot9,
				'wp_id'=> $this->session->userdata('SESS_WP_ID'), 'login_id'=> $this->session->userdata('SESS_USER_ID')
			);
			$this->db->where('id',$id); $this->db->update('beli',$info);
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, Silahkan isi info tambahan dibawah ini'); redirect('beli/rounding/'.$this->db->count_all_results('beli'));
        }else{
            $data['message']=""; $cek=$this->db->get_where('beli',array('id'=>$id));
			$data['info']=$cek->row_array(); $data['main_content'] = 'beli/edit'; $this->load->view('lte/live', $data);
        }
    }
    
// Create Edit PO
    function rounding($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Informasi Pembelian BBM"; $IDBeli=$this->bbm_model->IDBeli($id);
		date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');  $data['tgl']=date('Y-m-d');
        $this->_set_rules(); if($this->form_validation->run()==true){
			$info=array( 
				'status'=>'1', 'rounding'=>$this->input->post('rounding'),  'terbilang'=>$this->input->post('terbilang'), 'import'=>$this->input->post('import'),
				'bayar'=>$this->input->post('bayar'), 'tempo'=>$this->input->post('tempo'), 'termbyr'=>$this->input->post('termbyr'),
				'rekening'=>$this->input->post('rekening'), 'depo'=>$this->input->post('depo'), 'tglambil'=>$this->input->post('tglambil'),
				'termambil'=>$this->input->post('termambil'), 'koordinator'=>$this->input->post('koordinator'), 'keterangan'=>$this->input->post('keterangan'),
				'tot9' => $this->input->post('tot9')+$this->input->post('rounding') );
			$this->db->where('id',$id); $this->db->update('beli',$info);		
		// Simpan History
			$this->db->insert('history',array('tgl'=>date('Y-m-d H:i:s'),'login_id'=>$this->session->userdata('SESS_USER_ID'),
			'wp_id'=>$this->session->userdata('SESS_WP_ID'),'kode'=>'1','action'=>'tambah Pembelian','link_id'=>$id));
		// Pesan System
		if($this->pajak_model->Accpo($this->bbm_model->WPBeli($id))=='0'){if($this->pajak_model->Emailpo($this->bbm_model->WPBeli($id))==''){ redirect('beli/jurnal/'.$id);}else{} }else{
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';			
			$create = $this->user_model->NamaUser($this->bbm_model->LoginBeli($id));
			$print = site_url()."lihat/beli/".$id;
			$acc = anchor(site_url()."beli/jurnal/".$id, ' ACC ', $btn); $memo = anchor(site_url()."beli/memo/".$id, ' MEMO ', $btn);
			$subject = $this->setting_model->Subject_po().' Nomor '.$IDBeli;
			$message = $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini PO yang telah dibuat oleh ".$create." :<b><br><iframe src='".$print."' frameborder='0' height='250' width='100%'></iframe><br><b>Untuk ACC silahkan klik dibawah ini :</b><br>".$acc."<br><b>Untuk Memo Perbaikan silahkan klik dibawah ini :</b><br>".$memo."<br></div>".$this->setting_model->Footer_mail();
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->pajak_model->accpo($this->session->userdata('SESS_WP_ID')),
				'judul'=>$subject, 'pesan'=>$message, 'dari'=>$this->session->userdata('SESS_USER_ID') );
			$this->m_pesan->simpan($info);
		}
		// Email  
		if($this->pajak_model->Emailpo($this->bbm_model->WPBeli($id))==''){ }else{ redirect('beli/pdf_kirim/'.$id); }
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, PO yang anda buat menunggu diperiksa'); redirect('beli/view/'.$id);
        }else{
			$cek=$this->db->get_where('beli',array('id'=>$id)); $data['info']=$cek->row_array();
			$data['main_content'] = 'beli/rounding'; $this->bbm_model->WPBeli($id);$this->load->view('lte/live', $data);
        }
    }
    
    function pdf_kirim($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $this->load->view('beli/pdf_kirim',$data); }
        
    function email_acc($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Kirim Email Pembelian BBM"; $IDBeli=$this->bbm_model->IDBeli($id);
		date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');  $data['tgl']=date('Y-m-d');
		// Notif style="cursor: pointer;" onclick="location.href='login'"
			$key = md5('jagad', true);
			$encrypted_id= encrypt($id, $key);
			$create = $this->user_model->NamaUser($this->bbm_model->LoginBeli($id));
			$email = anchor(site_url()."beli/email_acc/".$id, ' KLIK DISINI '); 
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';			
			$acc = anchor(site_url()."acc/acc_beli/".$encrypted_id, ' ACC ', $btn); 
			$memo = anchor(site_url()."memo/beli/".$encrypted_id, ' MEMO ', $btn);
			$subject = $this->setting_model->Subject_po().' Nomor '.$IDBeli;
			$message = $this->load->view('mail')."".$this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini PO yang telah dibuat oleh ".$create." :<b><br><b>Untuk ACC silahkan klik dibawah ini :</b><br>".$acc."<br><b>Untuk Memo Perbaikan silahkan klik dibawah ini :</b><br>".$memo."<br></div>".$this->setting_model->Footer_mail();
		// Email  
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->pajak_model->Emailpo($this->session->userdata('SESS_WP_ID'))); 
			$this->email->subject($subject); $this->email->message($message);
			$this->email->attach('files/po/PO No.'.$id.'.pdf');// Lampiran
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim, <br>Terimakasih, PO yang anda buat menunggu diperiksa');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim, silahkan '.$email.' untuk mengirim ulang email <br>Terimakasih, PO yang anda buat menunggu diperiksa');}
			redirect('beli/view/'.$id);
    }
	
// ACC PO & Jurnal
    function jurnal($id){
		if($this->session->userdata('ADMIN')>'1'){$this->session->set_userdata('SUCCESSMSG', 'anda tidak memiliki akses.'); redirect('beli'); }
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('beli');}
		//jika doble acc lompat
		if(!$this->bbm_model->JurnalBeli($id)==''){$this->session->set_userdata('SUCCESSMSG', 'PO ini telah di ACC dan di Jurnal.'); redirect('jurnal_proyek/view/'.$this->bbm_model->JurnalBeli($id));}
		
		//jika Cash / Credit
		if($this->bbm_model->BayarBeli($id)=='0'){$voucher_id='12'; $status='3'; $AkunBeliTotal = $this->akun_model->AkunBeliTotal($this->bbm_model->WPBeli($id)) ;
			}else{$voucher_id='7'; $status='2'; $AkunBeliTotal = $this->akun_model->AkunBeliAP($this->bbm_model->WPBeli($id)) ;}
		$no_jurnal=$this->db->count_all('jurnal')+1; 
		$data=array('status'=>$status,'jurnal_id'=>$no_jurnal,'admin_id'=>$this->session->userdata('SESS_USER_ID'));
		$this->db->where('id',$id); $this->db->update('beli',$data);
		$this->db->where('wp_id', $this->bbm_model->WPBeli($id)); $this->db->where('voucher_id', $voucher_id); $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => $no_jurnal, 'no' => $no_jurnal, 'voucher_id' => $voucher_id, 'no_voucher' => $nomor_voucher,
				'customer_id' => '1', 'tgl' => $this->bbm_model->TglBeli($id), 'tempo' => $this->bbm_model->TempoBeli($id), 'f_id' => '1',
				'login_id' => $this->session->userdata('SESS_USER_ID'), 'user_id' => $this->session->userdata('SESS_USER_ID'), 'wp_id'=> $this->bbm_model->WPBeli($id),
				'supplier_id' => $this->bbm_model->SupplierBeli($id), 'keterangan' => 'Pembelian HSD '.$this->bbm_model->JmlBeli($id).' L PO '.$this->bbm_model->IDBeli($id).'.'.$this->jurnal_model->ambilTgl($this->bbm_model->TglBeli($id)), 'waktu_post' => date("Y-m-d H:i:s")
			);
			$this->db->insert('jurnal',$jurnal);
			
			//Subtotal / Persediaan
			$this->db->where('jurnal_id', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail1=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliSubtotal($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliSubtotal($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total1Beli($id)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail1);
			
			//discount
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail2=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliDiscount($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliDiscount($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => (-1)*$this->bbm_model->Total2Beli($id)
			);
			if($this->bbm_model->Total2Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail2); }
			
			//ppn
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail4=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliPPN($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliPPN($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total4Beli($id)
			);
			if($this->bbm_model->Total4Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail4); }
		
			//pbbkb
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail5=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliPajak($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliPajak($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total5Beli($id)
			);
			if($this->bbm_model->Total5Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail5); }
			//pph
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail6=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliPajak($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliPajak($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total6Beli($id)
			);
			if($this->bbm_model->Total6Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail6); }
			//transport beli
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail7=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliTransport($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliTransport($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total7Beli($id)
			);
			if($this->bbm_model->Total7Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail7); }

			//ppn transport beli
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail8=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunBeliPPNTransport($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliPPNTransport($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total8Beli($id)
			);
			if($this->bbm_model->Total8Beli($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail8); }
		
			//total beli
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $AkunBeliTotal,
				'kategori_id' => $this->akun_model->JenisAkun($AkunBeliTotal),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total9Beli($id)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);
				
		// Notifikasi ACC
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$IDBeli=$this->bbm_model->IDBeli($id);
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$cetak= anchor(site_url()."beli/pdf/".$id, ' CETAK ', $btn); $subject= 'PO Nomor '.$IDBeli.' Telah Di Setujui (ACC)';
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>PO Telah Di Setujui (ACC):<b><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpo($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBeli($id), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$this->session->userdata('SESS_USER_ID') );
			$this->m_pesan->simpan($info);
		}
		// Email
		if($this->pajak_model->Emailpo($this->bbm_model->WPBeli($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginBeli($id))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
		$this->session->set_userdata('SUCCESSMSG', 'PO Success. jurnal telah dibuat Silahkan di Print'); redirect('beli/view/'.$id);
    }
                        
// Memo
    function memo($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'3'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('beli');}
		date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');  $data['tgl']=date('Y-m-d');
		$data['title']="Memo Pembelian BBM";
        $this->_set_rules(); if($this->form_validation->run()==true){
		// Notifikasi ACC
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$IDBeli=$this->bbm_model->IDBeli($id);
			$edit= anchor(site_url()."beli/rounding/".$id, ' EDIT ', $btn); $subject= 'Memo Perbaikan untuk PO Nomor '.$IDBeli;
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini memo perbaikan yang dibuat :<b><br>".$this->input->post('pesan')."<br><b>Untuk Perbaikan silahkan klik dibawah ini :</b><br>".$edit."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpo($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			$info=array('id'=>'id','tgl'=>$tgl,'kepada'=>$this->bbm_model->LoginBeli($id),'judul'=>$subject,'pesan'=>$message,'dari'=>$this->session->userdata('SESS_USER_ID'));
			$this->m_pesan->simpan($info);
		}
		// Email
		if($this->pajak_model->Emailpo($this->bbm_model->WPBeli($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginBeli($id))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, Memo untuk perbaikan PO telah dikirim'); redirect('beli/view/'.$id);
        }else{
			$cek=$this->db->get_where('beli',array('id'=>$id)); $data['info']=$cek->row_array();
			$data['main_content'] = 'beli/memo'; $this->load->view('lte/mail', $data);
        }
    }
    
//Belum Dipakai
    function add_pay($id){
        $data['title']="Bayar Pelunasan";
		if($this->session->userdata('ADMIN')>'3'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $kode=$this->uri->segment(4); $this->db->where('id',$kode); $this->db->update('history',array('cek'=>'1'));
        $this->_set_rules(); if($this->form_validation->run()==true){
			if($this->bbm_model->JmlBayarBeli($id) < $this->input->post('jmlbyr')){ 
			$this->session->set_userdata('SUCCESSMSG', 'Maaf Pelunasan yang anda '.number_format($this->input->post('jmlbyr'), 0, ',', '.').' melebihi batas.'); redirect('beli/add_pay/'.$id);}

			$id=$this->input->post('id');
			$config['upload_path'] = './files/pelunasan/'; $config['allowed_types'] = 'bmp|gif|jpg|jpeg|png|jp2';
			$config['max_size']	= '100000'; $config['max_width']  = '20000'; $config['max_height']  = '10240';
			$this->upload->initialize($config); if(!$this->upload->do_upload('gbr')){ $gbr=""; }else{ $gbr=$this->upload->file_name; }
            $semua=array( 'status'=>'3' ); $this->db->where('id',$id); $this->db->update('beli',$semua);
            $info=array( 
				'id_beli'=>$this->input->post('id_beli'), 'akunbyr'=>$this->input->post('akunbyr'), 
				'jmlbyr'=>$this->input->post('jmlbyr'), 'tglbyr'=>$this->input->post('tglbyr'), 'keterangan'=>$this->input->post('keterangan'), 
				'wp_id'=> $this->session->userdata('SESS_WP_ID'), 'login_id'=> $this->session->userdata('SESS_USER_ID'), 'bukti'=>$gbr );
			$this->db->insert('beli_bayar',$info);
		// Simpan History
			date_default_timezone_set("Asia/Jakarta");
			$this->db->insert('history',array('tgl'=>date('Y-m-d H:i:s'),'login_id'=>$this->session->userdata('SESS_USER_ID'),'wp_id'=>$this->session->userdata('SESS_WP_ID'),'kode'=>'3','action'=>'Membayar Pelunasan PO','link_id'=>$id));
		// Pesan System
		if($this->pajak_model->Accpay($this->bbm_model->WPBeli($id))=='0'){if($this->pajak_model->Emailpay($this->bbm_model->WPBeli($id))==''){ redirect('beli/acc_pay/'.$id);}else{} }else{
			$IDBeli=$this->bbm_model->IDBeli($id);
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$create= $this->user_model->NamaUser($this->bbm_model->LoginBayar($id)); $print= site_url()."lihat/bayar/".$id;
			$acc= anchor(site_url()."beli/acc_pay/".$id, ' ACC ', $btn); $memo= anchor(site_url()."beli/memo_pay/".$id, ' MEMO ', $btn);
			$subject= $this->setting_model->Subject_pay().' Nomor '.$IDBeli;
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini Pelunasan PO yang telah dibuat oleh ".$create." :<b><br><iframe src='".$print."' frameborder='0' height='250' width='100%'></iframe><br><b>Untuk ACC silahkan klik dibawah ini :</b><br>".$acc."<br><b>Untuk Memo Perbaikan silahkan klik dibawah ini :</b><br>".$memo."<br></div>".$this->setting_model->Footer_mail();
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->pajak_model->accinv($this->session->userdata('SESS_WP_ID')),
				'judul'=>$subject, 'pesan'=>$message, 'dari'=>$this->session->userdata('SESS_USER_ID') );
			$this->m_pesan->simpan($info);
		}
		// email
		if($this->pajak_model->Emailpay($this->bbm_model->WPBeli($id))==''){ }else{ redirect('beli/pay_kirim/'.$id.'/'.$this->db->count_all_results('beli_bayar')); }
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, Pelunasan yang anda buat menunggu diperiksa'); 
			$cek=$this->db->get_where('beli',array('id'=>$id,'cek'=>'0')); $data['info']=$cek->row_array();
            $data['message']="<div class='alert alert-success'>Data berhasil diupdate</div>"; $data['main_content'] = 'beli/add_pay'; $this->load->view('lte/live', $data);
        }else{
            $data['message']=""; $cek=$this->db->get_where('beli',array('id'=>$id,'cek'=>'0')); $data['info']=$cek->row_array();
			$data['main_content'] = 'beli/add_pay'; $this->load->view('lte/live', $data);
        }
    }
    
// Bayar
    function view_pay($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $data['title']="Pembelian BBM"; $data['message']=""; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $data['main_content'] = 'beli/view_pay'; $this->load->view('lte/live', $data); }

    function pdf_pay($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $kode=$this->uri->segment(4); $data['bukti']= $this->bbm_model->BuktiBayarPembelian($kode);
        $data['title']="Pembelian BBM"; $data['message']=""; $cek=$this->db->get_where('beli',array('id'=>$id)); 
		$data['info']=$cek->row_array(); $this->load->view('beli/pdf_pay',$data); }
		
    function pay_kirim($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        $kode=$this->uri->segment(4); $data['bukti']= $this->bbm_model->BuktiBayarPembelian($kode);
		$data['title']="Pelunasan Pembelian BBM"; $cek=$this->db->get_where('beli',array('id'=>$id));
		$data['info']=$cek->row_array(); $this->load->view('beli/pay_kirim',$data); }
        
    function email_accpay($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('beli');}
		$kode=$this->uri->segment(4); 
        $data['title']="Kirim Email Pelunasan Pembelian BBM"; $IDBeli=$this->bbm_model->IDBeli($id);
		date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');  $data['tgl']=date('Y-m-d');
		// Notif
			$key = md5('jagad', true); $encrypted_id= encrypt($id, $key);
			$key2 = md5('jagad', true); $encrypted_kode= encrypt($kode, $key2);
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';			
			$create = $this->user_model->NamaUser($this->bbm_model->LoginBayar($id));
			$print = site_url()."lihat/bayar/".$id;
			$email = anchor(site_url()."beli/email_accpay/".$id."/".$kode, ' KLIK DISINI '); 
			$p = "'";
			$acc_link = site_url()."acc/acc_belipay/".$encrypted_id."/".$encrypted_kode;
			$memo_link = site_url()."memo/pay_beli/".$encrypted_id."/".$encrypted_kode;
			$acc = '<button style="cursor: pointer;" class="btn btn-success" onclick="location.href='.$p.''.$acc_link.''.$p.'"> ACC </button>'; 
			$memo = '<button style="cursor: pointer;" class="btn btn-warning" onclick="location.href='.$p.''.$memo_link.''.$p.'"> MEMO </button>'; 
			$subject = $this->setting_model->Subject_pay().' Nomor '.$IDBeli;
			$message = $this->load->view('mail')."".$this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini Pelunasan PO yang telah dibuat oleh ".$create." :<b><br><iframe src='".$print."' frameborder='0' height='250' width='100%'></iframe><br><b>Untuk ACC silahkan klik dibawah ini :</b><br>".$acc."<br><b>Untuk Memo Perbaikan silahkan klik dibawah ini :</b><br>".$memo."<br></div>".$this->setting_model->Footer_mail();
		// Email  
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->pajak_model->Emailpay($this->session->userdata('SESS_WP_ID'))); 
			$this->email->subject($subject); $this->email->message($message);
			$this->email->attach('files/pay_beli/Pay No.'.$id.'.pdf');// Lampiran
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim, <br>Terimakasih, Invoice yang anda buat menunggu diperiksa');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim, silahkan '.$email.' untuk mengirim ulang email <br>Terimakasih, Invoice yang anda buat menunggu diperiksa');}
			redirect('beli/view_pay/'.$id);
    }
    
// Acc Pay / Jurnal Bayar Beli
    function acc_pay($id){
        $id=$this->uri->segment(3); $kode=$this->uri->segment(4); $no_jurnal=$this->db->count_all('jurnal')+1; 
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli/pay');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
        if(!$kode){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli/pay');}
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('beli');}

		$data=array('status'=>'4','admin_id'=>$this->session->userdata('SESS_USER_ID')); $this->db->where('id',$id); $this->db->update('beli',$data);
		$info=array('bunker_id'=>$no_jurnal,'admin_id'=>$this->session->userdata('SESS_USER_ID')); $this->db->where('id',$kode); $this->db->update('beli_bayar',$info);
		
		date_default_timezone_set("Asia/Jakarta");
		$this->db->where('wp_id', $this->bbm_model->WPBeli($id));  $this->db->where('voucher_id', '8');  $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => '', 'no' => $no_jurnal, 'voucher_id' => '8', 'no_voucher' => $nomor_voucher, 'supplier_id' => $this->bbm_model->SupplierBeli($id), 
				'customer_id' => '1', 'tgl' => $this->bbm_model->TglBayarPembelian($kode), 'tempo' => $this->bbm_model->TempoBeli($id), 'f_id' => '1',
				'login_id' => $this->session->userdata('SESS_USER_ID'), 'user_id' => $this->session->userdata('SESS_USER_ID'), 'wp_id'=> $this->bbm_model->WPBeli($id),
				'keterangan' => $this->bbm_model->KetBayarPembelian($kode), 'waktu_post' => date("Y-m-d H:i:s") );
			$this->db->insert('jurnal',$jurnal);
			
		//Save Jurnal Detail
			//Total Beli / Bank
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '1',
				'akun_id' => $this->bbm_model->AkunBayarPembelian($kode),
				'kategori_id' => $this->akun_model->JenisAkun($this->bbm_model->AkunBayarPembelian($kode)),
				'debit_kredit' => '0',
				'nilai' => $this->bbm_model->JmlBayarPembelian($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

			//AR - Hutang Pembelian
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '2',
				'akun_id' => $this->akun_model->AkunBeliBayar($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliBayar($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '1',
				'nilai' => $this->bbm_model->JmlBayarPembelian($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

		// Notifikasi ACC
			$IDBeli=$this->bbm_model->IDBeli($id);
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$cetak= anchor(site_url()."beli/view_pay/".$id, ' CETAK ', $btn); $subject= 'Pembayaran Nomor '.$IDBeli.' Telah Di Setujui (ACC)';
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>PO Telah Di Setujui (ACC):<b><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpay($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBayarPembelian($kode), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$this->session->userdata('SESS_USER_ID') );
			$this->m_pesan->simpan($info);
		}
		// Email
		if($this->pajak_model->Emailpay($this->bbm_model->WPBeli($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginBayarPembelian($kode))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
		$this->session->set_userdata('SUCCESSMSG', 'Pembayaran Invoice Success. jurnal telah dibuat'); redirect('beli/view_pay/'.$id);
    }
	
// Memo Pay
    function memo_pay($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli/pay');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'2'){ $this->session->set_userdata('SUCCESSMSG', 'Maaf Anda tidak punya akses.'); redirect('beli');}
		date_default_timezone_set("Asia/Jakarta");
        $data['title']="Memo Penerimaan Pembelian BBM"; $data['tgl']=date('Y-m-d'); $tgl=date('Y-m-d H:i:s'); $IDBeli=$this->bbm_model->IDBeli($id);
        $this->_set_rules(); if($this->form_validation->run()==true){
		// Notifikasi Memo
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$edit= anchor(site_url()."beli/edit_pay/".$id, ' EDIT ', $btn); $subject= 'Memo Perbaikan untuk Invoice Nomor '.$IDBeli;
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><b>Berikut ini memo perbaikan yang dibuat :<b><br>".$this->input->post('pesan')."<br><b>Untuk Perbaikan silahkan klik dibawah ini :</b><br>".$edit."<br></div>".$this->setting_model->Footer_mail();
		if($this->pajak_model->Accpay($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBayar($id), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$this->session->userdata('SESS_USER_ID') );
			$this->m_pesan->simpan($info);
		}
		// email
		if($this->pajak_model->Emailpay($this->bbm_model->WPBeli($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginBayar($id))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
			$this->session->set_userdata('SUCCESSMSG', 'Terimakasih, Memo untuk perbaikan Penerimaan Pembelian BBM telah dikirim'); redirect('beli/view/'.$id);
        }else{
			$cek=$this->db->get_where('beli',array('id'=>$id)); $data['info']=$cek->row_array();
			$data['main_content'] = 'beli/memo_pay'; $this->load->view('lte/mail', $data);
        }
    }
    
// Hapus Pay
    function hapus_pay($id){
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('beli/pay');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('beli');}
		if($this->session->userdata('ADMIN')>'2'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('beli');} 
        $kode=$this->uri->segment(3); $data=array('cek'=>'1','login_id'=>$this->session->userdata('SESS_USER_ID'));
		$this->db->where('id',$kode); $this->db->update('beli_bayar',$data);
		date_default_timezone_set("Asia/Jakarta");
		$data_jurnal=array('keterangan'=>'Jurnal ini telah dihapus oleh '.$this->user_model->NamaUser($this->session->userdata('SESS_USER_ID'))
		.' pada tanggal '.date('d-m-Y').' Jam '.date('H:i'));
        $jurnal_id=$this->bbm_model->JurnalBayarPembelian($kode);
		$this->db->where('id',$jurnal_id); $this->db->update('jurnal',$data_jurnal); $this->db->where('jurnal_id', $jurnal_id); $this->db->delete('jurnal_detail');
		$this->session->set_userdata('SUCCESSMSG', 'Data Pembelian dan Jurnal Telah dihapus.'); redirect('beli/view_pay/'.$this->bbm_model->IdPembelian($kode));
    }
    
    function _set_rules(){
        $this->form_validation->set_rules('id','ID','required|max_length[15]');
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>","</div>");
    }
	 
	 function ajaxSupplier(){
		  $search_term = $this->input->post('term');
        $search_result = $this->bbm_model->cari('supplier', array('nama' => $search_term['term']));
        $suggestion_array = array();
        $suggestion_result = array();
        foreach ($search_result->result() as $result) {
            $suggestion_array['id'] = $result->id;
            $suggestion_array['text'] = $result->nama;
				array_push($suggestion_result, $suggestion_array);
		  }
		  echo json_encode($suggestion_result);
	 }
	 
	 function ajaxTransportir(){
		  $search_term = $this->input->post('term');
        $search_result = $this->bbm_model->cari('transportir', array('nama' => $search_term['term']));
        $suggestion_array = array();
        $suggestion_result = array();
        foreach ($search_result->result() as $result) {
            $suggestion_array['id'] = $result->id;
            $suggestion_array['text'] = $result->nama;
				array_push($suggestion_result, $suggestion_array);
		  }
		  echo json_encode($suggestion_result);
	 }
}