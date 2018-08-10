<?php

class Akun_model extends CI_Model {

	var $data;
	
	//Query manual
	function manualQuery($q) { return $this->db->query($q); }

	//Cari Akun Beli
	public function AkunBeliSubtotal($id){		//persediaan - inventori
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='1'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliPPN($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='2'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliPajak($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='3'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliTotal($id){		// bank
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='4'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliAP($id){		// Hutang Usaha - AP
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='5'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliDiscount($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='6'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliTransport($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='7'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunBeliPPNTransport($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND po='2'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	//Cari Akun Pembayaran Beli
	public function AkunBeliBayar($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND pay='1'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	//Cari Akun Delivery
	public function AkunDeliveryDebet($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND do='1'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunDeliveryKredit($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND do='2'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	//Cari Akun Jual
	public function AkunJualTotal($id){		// Piutang Usaha - AR
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='1'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualSubtotal($id){		// Pendapatan Penjualan
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='2'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualPPN($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='3'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualPajak($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='4'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualTransport($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='5'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualDiscount($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='6'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	public function AkunJualPPNTransport($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND inv='3'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }

	//Cari Akun Pembayaran Jual 
	public function AkunJualBayar($id){
		$t = "SELECT * FROM akun WHERE wp_id='$id' AND pay='2'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->id; } }else{ $hasil = ''; } return $hasil; }


	//Tabel akun_kelompok / Kelompok Akun / Level 1
	public function NamaKelompok($id){
		$t = "SELECT * FROM akun_kelompok WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->nama; } }else{ $hasil = ''; } return $hasil; }

	//Tabel akun_kategori / Kategori Akun / Level 2
	public function KodeKategori($id){
		$t = "SELECT * FROM akun_kategori WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kode; } }else{ $hasil = ''; } return $hasil; }

	public function NamaKategori($id){
		$t = "SELECT * FROM akun_kategori WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->nama; } }else{ $hasil = ''; } return $hasil; }

	public function KelompokAkunKategori($id){
		$t = "SELECT * FROM akun_kategori WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kelompok_akun_id; } }else{ $hasil = ''; } return $hasil; }

	//Tabel akun_jenis / Jenis / Level 3
	public function KodeJenis($id){
		$t = "SELECT * FROM akun_jenis WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kode; } }else{ $hasil = ''; } return $hasil; }

	public function NamaJenis($id){
		$t = "SELECT * FROM akun_jenis WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->nama; } }else{ $hasil = ''; } return $hasil; }

	public function KategoriId($id){
		$t = "SELECT * FROM akun_jenis WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kategori_id; } }else{ $hasil = ''; } return $hasil; }

	public function KategoriAging($id){
		$t = "SELECT * FROM akun_jenis WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->aging; } }else{ $hasil = ''; } return $hasil; }

	public function KategoriAgingId($id){
		$t = "SELECT * FROM akun_jenis WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->aging_id; } }else{ $hasil = ''; } return $hasil; }

	//Tabel Akun Standar
	public function NamaAkunStandar($id){
		$t = "SELECT * FROM akun_standar WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->nama; } }else{ $hasil = ''; } return $hasil; }

	public function KodeAkunStandar($id){
		$t = "SELECT * FROM akun_standar WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kode; } }else{ $hasil = ''; } return $hasil; }

	public function JenisAkunIdStandar($id){
		$t = "SELECT * FROM akun_standar WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->jenis_akun_id; } }else{ $hasil = ''; } return $hasil; }

	//Tabel Akun
	public function NamaAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->nama; } }else{ $hasil = ''; } return $hasil; }

	public function JenisAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->jenis_akun_id; } }else{ $hasil = ''; } return $hasil; }

	public function KategoriAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kategori_akun_id; } }else{ $hasil = ''; } return $hasil; }

	public function KelompokAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kelompok_akun_id; } }else{ $hasil = ''; } return $hasil; }

	public function KodeAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->kode; } }else{ $hasil = ''; } return $hasil; }

	public function SaldoAwalAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->saldo_awal; } }else{ $hasil = ''; } return $hasil; }

	public function WPAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->wp_id; } }else{ $hasil = ''; } return $hasil; }

	public function LoginAkun($id){
		$t = "SELECT * FROM akun WHERE id='$id'"; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->login_id; } }else{ $hasil = ''; } return $hasil; }

	public function TotalSaldoAwal(){
		$t = "SELECT sum(saldo_awal) as jml FROM akun "; $d = $this->akun_model->manualQuery($t); $r = $d->num_rows();
		if($r>0){ foreach($d->result() as $h){ $hasil = $h->jml/2; } }else{ $hasil = 0; } return $hasil; }

	function set_saldo($id)
	{ $this->db->where('akun.kelompok_akun_id <=', $id); }

	function set_wp_id($id)
	{ $this->db->where('akun.wp_id', $id); }

	function set_login_id($id)
	{ $this->db->where('akun.login_id', $id); }

	function set_jenis_aging_id($id)
	{ $this->db->where_in('akun_jenis.aging_id', $id); }

	function set_jenis_akun_id($id)
	{ $this->db->where_in('akun.jenis_akun_id', $id); }

	function set_account_group_id($id)
	{ $this->db->where_in('akun.kelompok_akun_id', $id); }

	function get_all_data()
	{
		$this->db->select('akun.id, akun.nama, akun.kode, akun.saldo_awal, akun.saldo, akun.keterangan, akun.wp_id, akun.login_id, akun.kelompok_akun_id, akun.kategori_akun_id, akun.jenis_akun_id, akun_jenis.aging, akun_jenis.aging_id, akun_kelompok.nama AS groups_name');
		$this->db->from('akun');
		$this->db->join('akun_kelompok', 'akun.kelompok_akun_id=akun_kelompok.id', 'INNER');
		$this->db->join('akun_kategori', 'akun.kategori_akun_id=akun_kategori.id', 'LEFT');
		$this->db->join('akun_jenis', 'akun.jenis_akun_id=akun_jenis.id', 'LEFT');
		$this->db->order_by('akun_kelompok.id', 'asc'); $this->db->order_by('akun_kategori.kode', 'asc'); $this->db->order_by('akun_jenis.kode', 'asc'); 
		$this->db->order_by('akun.wp_id', 'asc'); $this->db->order_by('akun.kode', 'asc'); $query = $this->db->get();
		if ($query->num_rows() > 0) { return $query->result(); } else { return FALSE; }	}

	function get_data_by_id($id)
	{ $this->db->where('id', $id); $query = $this->db->get('akun'); if ($query->num_rows() > 0) { return $query->row_array(); } else { return FALSE; }	}

	function get_data_for_dropdown()
	{ $this->db->where('akun.wp_id', $this->session->userdata('SESS_WP_ID')); $this->db->order_by('akun.kelompok_akun_id', 'asc'); $this->db->order_by('akun.kode', 'asc'); $data = $this->get_all_data(); if($data) { foreach ($data as $row) { 	$akun[$row->id] = $row->kelompok_akun_id.' - '.$row->kode.' '.$row->nama; } return $akun; } else { return FALSE; }	}

	function get_id_by_name($name)
	{ $this->db->where('nama', $name); $query = $this->db->get('akun'); if ($query->num_rows() > 0) { $result = $query->row(); return $result->id; } else { return FALSE; }	}	

	function get_all_account_groups()
	{ $query = $this->db->get('akun_kelompok'); if ($query->num_rows() > 0) { foreach ($query->result() as $row) { 	$akun_kelompok[$row->id] = $row->id." - ".$row->nama ; } return $akun_kelompok; } else { return FALSE; }	}

	function get_all_jenis_account_groups()
	{ $this->db->order_by('kategori_id', 'asc'); $this->db->order_by('kode', 'asc'); $query = $this->db->get('akun_jenis'); if ($query->num_rows() > 0) { foreach ($query->result() as $row) { $akun_jenis[$row->id] = $this->akun_model->KelompokAkunKategori($row->kategori_id)."-".$this->akun_model->KodeKategori($row->kategori_id)."".$row->kode." | ".$this->akun_model->NamaKelompok($this->akun_model->KelompokAkunKategori($row->kategori_id))." - ".$this->akun_model->NamaKategori($row->kategori_id)." > ".$row->nama; } return $akun_jenis; } else { return FALSE; }	}

	function fill_data()
	{
		$nocabang = $this->input->post('wp_id');
        if($nocabang >'9'){ $nocab = $nocabang ; }else{ $nocab = '0'.$nocabang ;}
		$this->data = array(
			'nama' => $this->input->post('nama'),
			'kode' => $nocab.''.substr($this->input->post('kode'),2,3),
			'kelompok_akun_id' => $this->akun_model->KelompokAkunKategori($this->akun_model->KategoriId($this->input->post('jenis'))),
			'kategori_akun_id' => $this->akun_model->KategoriId($this->input->post('jenis')),
			'jenis_akun_id' => $this->input->post('jenis'), 	
			'pajak' => $this->input->post('pajak'),
			'keterangan' => $this->input->post('keterangan'),
			'wp_id'=> $nocabang,
			'login_id'=> $this->session->userdata('SESS_USER_ID')
		);
	}
	
	//Check for duplicate account name
	function check_name($id = '')
	{ $this->db->where('nama', $this->data['nama']); if($id != '') $this->db->where('id !=', $id); $query = $this->db->get('akun'); if ($query->num_rows() > 0) { 	return FALSE; } else { 	return TRUE; }	}	

	//Check for duplicate account kode
	function check_code($id = '')
	{ $this->db->where('kode', $this->data['kode']); if($id != '') $this->db->where('id !=', $id); $query = $this->db->get('akun'); if ($query->num_rows() > 0) { 	return FALSE; } else { 	return TRUE; }	}	

	function set_saldo_awal()
	{ $id = $this->input->post('id'); $this->db->trans_start(); for ($i = 1; $i <= count($id); $i++) { $debit = $this->input->post('debit'.$i); $kredit =$this->input->post('kredit'.$i); if($debit) { 	$saldo_awal = $debit; } elseif($kredit) { 	$saldo_awal = -$kredit; } else { 	$saldo_awal = 0; } $this->db->where('id', $id[$i-1]); $update = $this->db->update('akun', array('saldo_awal' => $saldo_awal) ); } $this->db->trans_complete();	return $this->db->trans_status();	}

	function insert_data()
	{ $insert = $this->db->insert('akun', $this->data); return $insert;	}

	function update_data($id)
	{ $this->db->where('id', $id); $update = $this->db->update('akun', $this->data); return $update;	}

	function delete_data($id)
	{ $this->db->where('id', $id); $delete = $this->db->delete('akun'); return $delete;	}

}
/* End of file akun_model.php */
/* Location: ./application/models/akun_model.php */
