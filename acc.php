<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acc extends CI_Controller {

	/**
	 * @author : M David
	 * @web : http://muhammaddavid.blogspot.com
	 * @keterangan : Controller untuk halaman profil
	 **/

    function __construct(){
        parent::__construct();
        
		  $this->load->library(array('template','form_validation','pagination','upload'));
			$this->load->helper('finance');
			$this->load->library('fpdf');
			define('FPDF_FONTPATH',$this->config->item('fonts_path'));
	}
    
	function index()	{
        $data['title']="Terimakasih Telah Mengunjungi Web Ini";
		$this->load->view('lihat/index', $data);
	}
	
// ACC PO & Jurnal
    function acc_beli($id){
		$key= md5('jagad', true); $id= decrypt($id, $key);
		$login='1'; 
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('login');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}
		//jika doble acc lompat
		if(!$this->bbm_model->JurnalBeli($id)==''){$this->session->set_userdata('SUCCESSMSG', 'PO ini telah di ACC dan di Jurnal.'); redirect('jurnal_proyek/view/'.$this->bbm_model->JurnalBeli($id));}
		
		//jika Cash / Credit
		if($this->bbm_model->BayarBeli($id)=='0'){
			$voucher_id='12'; $status='3'; $AkunBeliTotal = $this->akun_model->AkunBeliTotal($this->bbm_model->WPBeli($id)) ;
		}else{
			$voucher_id='7'; $status='2'; $AkunBeliTotal = $this->akun_model->AkunBeliAP($this->bbm_model->WPBeli($id)) ;}
		$no_jurnal=$this->db->count_all('jurnal')+1; 
		$data=array('status'=>$status,'jurnal_id'=>$no_jurnal,'admin_id'=>$login);
		$this->db->where('id',$id); $this->db->update('beli',$data);
		$this->db->where('wp_id', $this->bbm_model->WPBeli($id)); $this->db->where('voucher_id', $voucher_id); $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => $no_jurnal, 'no' => $no_jurnal, 'voucher_id' => $voucher_id, 'no_voucher' => $nomor_voucher,
				'customer_id' => '1', 'tgl' => $this->bbm_model->TglBeli($id), 'tempo' => $this->bbm_model->TempoBeli($id), 'f_id' => '1',
				'login_id' => $login, 'user_id' => $login, 'wp_id'=> $this->bbm_model->WPBeli($id),
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
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><br><b>PO Telah Di Setujui (ACC):<b><br><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpo($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBeli($id), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$login );
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
		$this->session->set_userdata('SUCCESSMSG', 'ACC PO Success. Silahkan di Login'); redirect('login');
    }
                        
// Acc Pay / Jurnal Bayar Beli
    function acc_belipay($id){
		$key= md5('jagad', true); $id= decrypt($id, $key);
        $kode=$this->uri->segment(4); 
		$key2= md5('jagad', true); $kode= decrypt($kode, $key2);
		$login=$this->pajak_model->Accpay($this->bbm_model->WPBeli($id)); 
		// if(!$this->user_model->check_login_IP()) { } else { redirect('login/index/beli/acc_pay/'.$id.'/'.$kode); }
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('login');}
        if(!$kode){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembelian.'); redirect('login');}
		if($this->bbm_model->check_beli($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}
		if($this->bbm_model->check_beli_bayar($kode)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}

		$no_jurnal=$this->db->count_all('jurnal')+1; 
		$data=array('status'=>'4','admin_id'=>$login); $this->db->where('id',$id); $this->db->update('beli',$data);
		$info=array('bunker_id'=>$no_jurnal,'admin_id'=>$login); $this->db->where('id',$kode); $this->db->update('beli_bayar',$info);
		
		date_default_timezone_set("Asia/Jakarta");
		$this->db->where('wp_id', $this->bbm_model->WPBeli($id));  $this->db->where('voucher_id', '8');  $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => no_jurnal, 'no' => $no_jurnal, 'voucher_id' => '8', 'no_voucher' => $nomor_voucher, 
				'supplier_id' => $this->bbm_model->SupplierBeli($id), 'customer_id' => '1', 
				'tgl' => $this->bbm_model->TglBayarPembelian($kode), 'tempo' => $this->bbm_model->TempoBeli($id), 'f_id' => '1',
				'login_id' => $login, 'user_id' => $login, 'wp_id'=> $this->bbm_model->WPBeli($id),
				'keterangan' => $this->bbm_model->KetBayarPembelian($kode), 'waktu_post' => date("Y-m-d H:i:s") );
			$this->db->insert('jurnal',$jurnal);
			
		//Save Jurnal Detail
			//Total Beli / Bank
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '1',
				'akun_id' => $this->bbm_model->AkunBayarPembelian($kode),
				'kategori_id' => $this->akun_model->JenisAkun($this->bbm_model->AkunBayarPembelian($kode)),
				'debit_kredit' => '1',
				'nilai' => $this->bbm_model->JmlBayarPembelian($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

			//AR - Hutang Pembelian
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '2',
				'akun_id' => $this->akun_model->AkunBeliBayar($this->bbm_model->WPBeli($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunBeliBayar($this->bbm_model->WPBeli($id))),
				'debit_kredit' => '0',
				'nilai' => $this->bbm_model->JmlBayarPembelian($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

		// Notifikasi ACC
			$IDBeli=$this->bbm_model->IDBeli($id);
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$cetak= anchor(site_url()."beli/view_pay/".$id, ' CETAK ', $btn); $subject= 'Pembayaran Nomor '.$IDBeli.' Telah Di Setujui (ACC)';
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><br><b>PO Telah Di Setujui (ACC):<b><br><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpay($this->bbm_model->WPBeli($id))=='0'){ }else{ 
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBayarPembelian($kode), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$login );
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
		$this->session->set_userdata('SUCCESSMSG', 'ACC Pelunasan PO Success. Silahkan di Login'); redirect('login');
    }
	    
    function acc_do($id){
		$key= md5('jagad', true); $id= decrypt($id, $key);
        $login=$this->uri->segment(4); if(!$login){ redirect('login');}
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu DO.'); redirect('login');}
		if($this->bbm_model->check_do($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}
		//jika doble acc lompat
		if(!$this->bbm_model->JurnalDO($id)==''){$this->session->set_userdata('SUCCESSMSG', 'DO ini telah di ACC dan di Jurnal.'); redirect('jurnal_proyek/view/'.$this->bbm_model->JurnalDO($id));}
		// Jurnal 
		$no_jurnal=$this->db->count_all('jurnal')+1; 
		$data=array('status'=>'0','jurnal_id'=>$no_jurnal,'admin_id'=>$login); $this->db->where('id',$id);$this->db->update('do',$data);
		$this->db->where('wp_id', $this->bbm_model->WPDO($id));  $this->db->where('voucher_id', '11');  $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => no_jurnal, 'no' => $no_jurnal, 'voucher_id' => '11', 'no_voucher' => $nomor_voucher, 'customer_id' => $this->bbm_model->CustomerDO($id), 
				'tgl' => $this->bbm_model->TglDO($id), 'tempo' => $this->bbm_model->TglDO($id), 'f_id' => '1',
				'login_id' => $login, 'user_id' => $login, 'wp_id'=> $this->bbm_model->WPDO($id),
				'keterangan' => 'Pengiriman HSD ('.$this->bbm_model->VolumeDO($id).' L) kirim '.$this->jurnal_model->tgl_singkatan($this->bbm_model->TglKirim($id)).' HPP '.$this->jurnal_model->GrafikAvgBeliCabang($this->bbm_model->TglKirim($id),$this->bbm_model->WPDO($id)), 'waktu_post' => date("Y-m-d H:i:s") );
			$this->db->insert('jurnal',$jurnal);

			//Debet
			$jurnal_detail1=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '1',
				'akun_id' => $this->akun_model->AkunDeliveryDebet($this->bbm_model->WPDO($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunDeliveryDebet($this->bbm_model->WPDO($id))),
				'debit_kredit' => '1', 'nilai' => ($this->jurnal_model->GrafikAvgBeliCabang($this->bbm_model->TglKirim($id),$this->bbm_model->WPDO($id))*$this->bbm_model->VolumeDO($id)) );
			$this->db->insert('jurnal_detail',$jurnal_detail1);
			//Kredit
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '2',
				'akun_id' => $this->akun_model->AkunDeliveryKredit($this->bbm_model->WPDO($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunDeliveryKredit($this->bbm_model->WPDO($id))),
				'debit_kredit' => '0', 'nilai' => ($this->jurnal_model->GrafikAvgBeliCabang($this->bbm_model->TglKirim($id),$this->bbm_model->WPDO($id))*$this->bbm_model->VolumeDO($id)) );
			$this->db->insert('jurnal_detail',$jurnal_detail9);
							
		// Notifikasi System 
		$IDJual=$this->bbm_model->IDJual($id);
		$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;'; 
		$cetak= anchor(site_url()."jual/view_do/".$id, ' CETAK ', $btn); 
		$subject= 'DO Nomor '.$IDJual.' Telah Di Setujui (ACC)';
		$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><br><b>DO Telah Di Setujui (ACC):<b><br><br><b>Untuk Mencetak DO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System 
		if($this->pajak_model->Accdo($this->bbm_model->WPDO($id))=='0'){ }else{ 
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array(
				'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginDO($id), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$login
			);
			$this->m_pesan->simpan($info);
		}
		// email
		if($this->pajak_model->Emaildo($this->bbm_model->WPDO($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginDO($id))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
		$this->session->set_userdata('SUCCESSMSG', 'ACC DO Success. Silahkan di Login'); redirect('login');
    }
    
// Jurnal Jual & ACC
    function acc_jual($id){
		$key= md5('jagad', true); $id= decrypt($id, $key);
        $login=$this->pajak_model->Accinv($this->bbm_model->WPJual($id)); if(!$login){ redirect('login');}
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Penjualan.'); redirect('login');}
		if($this->bbm_model->check_jual($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}

		//jika doble acc lompat
		if(!$this->bbm_model->JurnalJual($id)==''){$this->session->set_userdata('SUCCESSMSG', 'Invoice ini telah di ACC dan di Jurnal.'); redirect('jurnal_proyek/view/'.$this->bbm_model->JurnalJual($id));}

		//Save Jurnal
		$no_jurnal=$this->db->count_all('jurnal')+1;
		$data=array('status'=>'2','admin_id'=>$login,'jurnal_id'=>$no_jurnal);
		$this->db->where('id',$id); $this->db->update('jual',$data);
		$this->db->where('wp_id', $this->bbm_model->WPJual($id)); $this->db->where('voucher_id', '9'); $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => $no_jurnal, 'no' => $no_jurnal, 'voucher_id' => '9', 'no_voucher' => $nomor_voucher, 'customer_id' => $this->bbm_model->CustomerJual($id), 
				'tgl' => $this->bbm_model->TglJual($id), 'tempo' => $this->bbm_model->TempoJual($id), 'f_id' => '1',
				'login_id' => $login, 'user_id' => $login, 'wp_id'=> $this->bbm_model->WPJual($id),
				'keterangan' => 'Piutang Penjualan BBM / Invoice ('.$this->bbm_model->JmlJual($id).' L) Kirim '.$this->jurnal_model->tgl_singkatan($this->bbm_model->KirimJual($id)), 'waktu_post' => date("Y-m-d H:i:s") );
			$this->db->insert('jurnal',$jurnal);
			
		//Save Jurnal Detail
			//AR - Piutang Penjualan
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail3=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '1',
				'akun_id' => $this->akun_model->AkunJualTotal($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualTotal($this->bbm_model->WPJual($id))),
				'debit_kredit' => '1', 'nilai' => $this->bbm_model->Total9Jual($id)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail3);
				
			//Subtotal Pendapatan Penjualan
			$this->db->where('jurnal_id', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail1=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualSubtotal($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualSubtotal($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total1Jual($id) );
			$this->db->insert('jurnal_detail',$jurnal_detail1);
			
			//discount
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail2=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualDiscount($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualDiscount($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total2Jual($id)
			);
			if($this->bbm_model->Total2Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail2); }

			//ppn
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail4=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualPPN($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualPPN($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total4Jual($id)
			);
			if($this->bbm_model->Total4Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail4); }

			//pbbkb
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail5=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualPajak($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualPajak($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total5Jual($id)
			);
			if($this->bbm_model->Total5Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail5); }
		
			//pph
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail6=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualPajak($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualPajak($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total6Jual($id)
			);
			if($this->bbm_model->Total6Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail6); }
			
			//Transport Jual
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail7=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualTransport($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualTransport($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total7Jual($id)
			);
			if($this->bbm_model->Total7Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail7); }

			//ppn Transport Jual
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal')); $noitem=$this->db->count_all('jurnal_detail')+1;
			$jurnal_detail8=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => $noitem,
				'akun_id' => $this->akun_model->AkunJualPPNTransport($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualPPNTransport($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0', 'nilai' => $this->bbm_model->Total8Jual($id)
			);
			if($this->bbm_model->Total8Jual($id)=='0'){ }else{ $this->db->insert('jurnal_detail',$jurnal_detail8); }
					
		// Notifikasi ACC
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';	
			$IDJual=$this->bbm_model->IDJual($id);
			$cetak= anchor(site_url()."jual/pdf/".$id, ' CETAK ', $btn); $subject= 'PO Nomor '.$IDJual.' Telah Di Setujui (ACC)';
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><br><b>PO Telah Di Setujui (ACC):<b><br><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accinv($this->bbm_model->WPJual($id))=='0'){ }else{ 
			$info=array( 
				'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginJual($id), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$login );
			$this->m_pesan->simpan($info);
		}
		// Email
		if($this->pajak_model->Emailinv($this->bbm_model->WPJual($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginJual($id))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
		$this->session->set_userdata('SUCCESSMSG', 'ACC Invoice Success. Silahkan di Login'); redirect('login');
    }
                        
// Acc Pay / Jurnal Bayar Jual
    function acc_jualpay($id){
		$key= md5('jagad', true); $id= decrypt($id, $key);
        $kode=$this->uri->segment(4); 
		$key2= md5('jagad', true); $kode= decrypt($kode, $key2);
		$login=$this->pajak_model->Accpay($this->bbm_model->WPBeli($id)); 
        if(!$login){ redirect('login');}
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Penjualan.'); redirect('login');}
		if($this->bbm_model->check_jual($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}
        if(!$kode){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Pembayaran.'); redirect('login');}
		if($this->bbm_model->check_jual_bayar($kode)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('login');}

        $no_jurnal=$this->db->count_all('jurnal')+1; 
		$data=array('status'=>'4','admin_id'=>$login); $this->db->where('id',$id); $this->db->update('jual',$data);
		$info=array('bunker_id'=>$no_jurnal,'admin_id'=>$login); $this->db->where('id',$kode); $this->db->update('jual_bayar',$info);
		
		date_default_timezone_set("Asia/Jakarta");
		$this->db->where('wp_id', $this->bbm_model->WPJual($id));  $this->db->where('voucher_id', '10');  $nomor_voucher=$this->db->count_all_results('jurnal')+1;
			$jurnal=array(
				'id' => no_jurnal, 'no' => $no_jurnal, 'voucher_id' => '10', 'no_voucher' => $nomor_voucher, 'customer_id' => $this->bbm_model->CustomerJual($id), 
				'tgl' => $this->bbm_model->TglBayarPenjualan($kode), 'tempo' => $this->bbm_model->TempoJual($id), 'f_id' => '1',
				'login_id' => $login, 'user_id' => $login, 'wp_id'=> $this->bbm_model->WPJual($id),
				'keterangan' => $this->bbm_model->KetBayarPenjualan($kode), 'waktu_post' => date("Y-m-d H:i:s") );
			$this->db->insert('jurnal',$jurnal);
			
		//Save Jurnal Detail
			//Total Jual / Bank
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '1',
				'akun_id' => $this->bbm_model->AkunBayarPenjualan($kode),
				'kategori_id' => $this->akun_model->JenisAkun($this->bbm_model->AkunBayarPenjualan($kode)),
				'debit_kredit' => '1',
				'nilai' => $this->bbm_model->JmlBayarPenjualan($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

			//AR - Piutang Penjualan
			$this->db->where('jurnal_detail', $this->db->count_all('jurnal'));
			$jurnal_detail9=array(
				'jurnal_id' => $this->db->count_all('jurnal'), 'item' => '2',
				'akun_id' => $this->akun_model->AkunJualBayar($this->bbm_model->WPJual($id)),
				'kategori_id' => $this->akun_model->JenisAkun($this->akun_model->AkunJualBayar($this->bbm_model->WPJual($id))),
				'debit_kredit' => '0',
				'nilai' => $this->bbm_model->JmlBayarPenjualan($kode)
			);
			$this->db->insert('jurnal_detail',$jurnal_detail9);

		// Notifikasi ACC
			$IDJual=$this->bbm_model->IDJual($id);
			$btn['class']='btn btn-success btn-flat'; $btn['style']='cursor: pointer;';
			$cetak= anchor(site_url()."jual/view_pay/".$id, ' CETAK ', $btn); $subject= 'Pembayaran Nomor '.$IDJual.' Telah Di Setujui (ACC)';
			$message= $this->setting_model->Header_mail()."<div class='row invoice-info text-center'><br><br><b>PO Telah Di Setujui (ACC):<b><br><br><b>Untuk Mencetak PO silahkan klik dibawah ini :</b><br>".$cetak."<br></div>".$this->setting_model->Footer_mail();
		// Pesan System
		if($this->pajak_model->Accpay($this->bbm_model->WPJual($id))=='0'){ }else{ 
			date_default_timezone_set("Asia/Jakarta"); $tgl=date('Y-m-d H:i:s');
			$info=array( 'id'=>'id', 'tgl'=>$tgl, 'kepada'=>$this->bbm_model->LoginBayarPenjualan($kode), 'judul'=>$subject, 'pesan'=>$message, 'dari'=>$login );
			$this->m_pesan->simpan($info);
		}
		// Email
		if($this->pajak_model->Emailpay($this->bbm_model->WPJual($id))==''){ }else{ 
			$config = array(); $config['charset'] = 'utf-8'; $config['useragent'] = 'Codeigniter';
			$config['protocol']= $this->setting_model->Protocol(); $config['mailtype']= "html";
			$config['smtp_host']= $this->setting_model->Host(); $config['smtp_port']= $this->setting_model->Port();
			$config['smtp_timeout']= "30"; $config['smtp_user']= $this->setting_model->Email(); $config['smtp_pass']= $this->setting_model->Password();
			$config['crlf']="\r\n"; $config['newline']="\r\n"; $config['wordwrap'] = TRUE;
			$this->email->initialize($config); $this->email->from($this->setting_model->Email());
			$this->email->to($this->user_model->EmailUser($this->bbm_model->LoginBayarPenjualan($kode))); 
			$this->email->subject($subject); $this->email->message($message);	
			if($this->email->send()){$this->session->set_userdata('SUCCESSMSG', 'Email Terkirim');}else{$this->session->set_userdata('SUCCESSMSG', 'Email Tidak Terkirim');}
		}
		$this->session->set_userdata('SUCCESSMSG', 'ACC Pembayaran Invoice Success. Silahkan di Login'); redirect('login');
    }
	
    function _set_rules(){
        $this->form_validation->set_rules('id','ID','required|max_length[15]');
        $this->form_validation->set_error_delimiters("<div class='alert alert-danger'>","</div>");
    }
}