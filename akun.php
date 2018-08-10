<?php

class Akun extends CI_Controller {

	function Akun()
	{
		parent::__construct();
        $this->load->library(array('auth','template','form_validation','pagination','upload'));
		$this->load->library('fpdf');
		define('FPDF_FONTPATH',$this->config->item('fonts_path'));
		$this->auth->check_user_authentification();
		if($this->session->userdata('ADMIN')>'1'){$this->session->set_userdata('SUCCESSMSG', 'Maaf anda tidak memiliki akses.'); redirect('home'); }	
	}

	function index()
	{
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Akun";
		$data['main_content'] = 'akun/display';
		$data['account_data'] = $this->akun_model->get_all_data();
		$this->load->view('lte/live', $data);
	}

	function cetak()
	{
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Akun";
		$data['main_content'] = 'akun/display';
		$data['account_data'] = $this->akun_model->get_all_data();
		$this->load->view('akun/cetak', $data);
	}

	function detail_akun()
	{
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Detail Akun";
		$data['main_content'] = 'akun/detail_akun';
		$data['account_data'] = $this->akun_model->get_all_data();
		$this->load->view('lte/live', $data);
	}

	function saldo_awal()
	{
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Saldo Awal";
		$data['main_content'] = 'akun/saldo_awal';
		$this->akun_model->set_saldo(3);
		$data['account_data'] = $this->akun_model->get_all_data();
		$this->load->view('lte/tabel', $data);
	}

	function add()
	{
		$data['title'] = "Tambah Akun";
		$data['main_content'] = 'akun/form';
		$data['act'] = 'add';
		$data['form_act'] = 'insert';
		$data['account_data'] = FALSE;
		$data['wp_groups'] = $this->pajak_model->get_cabang();
		$data['account_groups'] = $this->akun_model->get_all_account_groups();
		$data['jenis_account_groups'] = $this->akun_model->get_all_jenis_account_groups();
		$this->load->view('lte/live', $data);
	}

	function view()
	{
		$id = $this->uri->segment(3);
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Akun.'); redirect('akun');}
		if($this->bbm_model->check_akun($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('akun');}
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Lihat Akun";
		$data['main_content'] = 'akun/form';
		$data['act'] = 'view';
		$data['form_act'] = '';
		$data['account_data'] = $this->akun_model->get_data_by_id($id);
		$data['wp_groups'] = $this->pajak_model->get_cabang();
		$data['account_groups'] = $this->akun_model->get_all_account_groups();
		$data['jenis_account_groups'] = $this->akun_model->get_all_jenis_account_groups();
		$this->load->view('lte/live', $data);
	}

	function edit()
	{
		$id = $this->uri->segment(3);
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Akun.'); redirect('akun');}
		if($this->bbm_model->check_akun($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('akun');}
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		$data['title'] = "Edit Akun";
		$data['main_content'] = 'akun/form';
		$account_data = $this->akun_model->get_data_by_id($id);
		if($this->_check_jurnal_exist($id))
		{
			$this->session->set_userdata('ERRMSG_ARR', 'Akun '.$account_data['nama'].' telah dipakai di jurnal, harap berhai hati untuk edit.');
			$data['act'] = 'edit';
			$data['form_act'] = 'update/'.$id;
		}
		else
		{
			$data['act'] = 'edit';
			$data['form_act'] = 'update/'.$id;
		}
		$data['account_data'] = $account_data;
		$data['wp_groups'] = $this->pajak_model->get_cabang();
		$data['account_groups'] = $this->akun_model->get_all_account_groups();
		$data['jenis_account_groups'] = $this->akun_model->get_all_jenis_account_groups();
		$this->load->view('lte/live', $data);
	}

	function insert()
	{
		if (!$this->_akun_validation())
		{
			$this->session->set_userdata('ERRMSG_ARR', validation_errors());
			$this->add();
		}
		else
		{
			$this->akun_model->fill_data();
			$this->akun_model->insert_data();
			$this->session->set_userdata('SUCCESSMSG', 'Akun baru sukses');
			redirect('akun');
		}
	}

	function update()
	{
		$id = $this->uri->segment(3);
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Akun.'); redirect('akun');}
		if($this->bbm_model->check_akun($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('akun');}
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		if (!$this->_akun_validation())
		{
			$this->session->set_userdata('ERRMSG_ARR', validation_errors());
			$this->edit();
		}
		else
		{
			$this->akun_model->fill_data();
			$this->akun_model->update_data($id);
			$this->session->set_userdata('SUCCESSMSG', 'Update Akun sukses ;)');
			redirect('akun');
		}
	}

	function delete()
	{
		$id = $this->uri->segment(3);
        if(!$id){$this->session->set_userdata('SUCCESSMSG', 'Anda Harus pilih salah satu Akun.'); redirect('akun');}
		if($this->bbm_model->check_akun($id)) { $this->session->set_userdata('SUCCESSMSG', 'ID yang Anda pilih tidak ada.'); redirect('akun');}
		if($this->session->userdata('ADMIN')>='2'){ $this->akun_model->set_wp_id($this->session->userdata('SESS_WP_ID')); }	
		if($this->session->userdata('ADMIN')>='3'){$this->session->set_userdata('SUCCESSMSG', 'Anda tidak memiliki akses.'); redirect('akun');}
		$account_data = $this->akun_model->get_data_by_id($id);
		if($this->_check_jurnal_exist($id))
		{
			$msg = 'GAGAL # Akun '.$account_data['nama'].' tidak dapat dihapus karena telah dipakai di jurnal.';
		}
		else
		{
			if($this->akun_model->delete_data($id))
			{
				$msg = 'SUKSES # Akun '.$account_data['nama'].' telah dihapus.';
			}
			else
			{
				$msg .= 'ERROR # Terjadi kesalahan dalam menghapus data akun '.$account_data['nama'].'. Harap coba lagi.';
			}
		}
		$this->session->set_userdata('SUCCESSMSG', ''.$msg);
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/akun'>";				
	}

	function input_saldo_awal()
	{
		if (!$this->_saldo_awal_validation())
		{
			$this->session->set_userdata('ERRMSG_ARR', validation_errors());
			$this->saldo_awal();
		}
		else
		{
			$error_message = $this->_check_sum();
			if($error_message != '')
			{
				$this->session->set_userdata('ERRMSG_ARR', $error_message);
				$this->saldo_awal();
			}
			else
			{
				//Update Saldo Awal
				if($this->akun_model->set_saldo_awal())
				{
					$this->session->set_userdata('SUCCESSMSG', 'Input Saldo Awal sukses ;)');
					redirect('akun/saldo_awal');
				}
			}
		}
	}

	function _check_sum()
	{
		$error_message = '';
		$debit_sum = 0;
		$kredit_sum = 0;
		$id = $this->input->post('id');
		for ($i = 1; $i <= count($id); $i++)
		{
			$debit = $this->input->post('debit'.$i);
			$kredit = $this->input->post('kredit'.$i);
			$debit_sum += $debit;
			$kredit_sum += $kredit;
		}
		if($debit_sum != $kredit_sum)
		{
			$error_message = "Jumlah debit harus sama dengan jumlah kredit.";
		}
		return $error_message;
	}
	
	function _check_jurnal_exist($id)
	{
		$this->load->model('jurnal_model');
		$this->jurnal_model->set_account_id($id);
		$journal_data = $this->jurnal_model->get_data();
		if ($journal_data)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function _akun_validation()
	{
		$this->form_validation->set_rules('nama', 'Nama Akun', 'trim|required');
		$this->form_validation->set_rules('kode', 'Kode Akun', 'trim|required|numeric');

		return $this->form_validation->run();
	}

	function _saldo_awal_validation()
	{
		$id = $this->input->post('id');
		for ($i = 1; $i <= count($id); $i++)
		{
			$this->form_validation->set_rules('debit'.$i, 'Debit', 'trim|is_natural');
			$this->form_validation->set_rules('kredit'.$i, 'Kredit', 'trim|is_natural');
		}

		return $this->form_validation->run();
	}

}
/* End of file akun.php */
/* Location: ./application/controllers/akun.php */
