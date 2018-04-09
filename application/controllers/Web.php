<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Web extends CI_Controller {

	/**
	 * @author : Gede Lumbung
	 * @web : http://gedelumbung.com
	 * @keterangan : Controller untuk halaman awal ketika aplikasi krs web based diakses
	 **/
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('web_app_model');
	}
	
	public function index()
	{
		$cek = $this->session->userdata('logged_in');
		if(empty($cek))
		{
			$d['judul'] = "Login - Sistem Informasi Akademik Online";
			
			//buat atribut form
			$frm['username'] = array('name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => '',
				'class' => 'input-teks-login',
				'placeholder' => 'Masukkan username.....'
			);
			$frm['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => '',
				'class' => 'input-teks-login',
				'placeholder' => 'Masukkan password.....'
			);
			
			$this->load->view('global/bg_top',$d);
			$this->load->view('web/bg_login',$frm);
			$this->load->view('global/bg_footer',$d);
		}
		else
		{
			$st = $this->session->userdata('stts');
			if($st=='mahasiswa')
			{
				header('location:'.base_url().'mahasiswa');
			}
			else if($st=='dosen')
			{
				header('location:'.base_url().'dosen');
			}
			else if($st=='admin')
			{
				header('location:'.base_url().'admin');
			}
		
		}
	}
	
	public function login()
	{
		$d['judul'] = "Login - Sistem Informasi Akademik Online";
		$usr = $this->input->post('username');
		$psw = $this->input->post('password');

		$ceklogin = $this->web_app_model->getLoginData($usr,$psw);

		if($ceklogin)
		{
			foreach($ceklogin as $row)
			{
				$this->session->set_userdata('username', $row->username);
				$this->session->set_userdata('stts', $row->stts);

				if($this->session->userdata('stts') == "mahasiswa")
				{
					$q_ambil_data = $this->db->get_where('tbl_mahasiswa', array('nim' => $u));
					foreach($q_ambil_data ->result() as $qad)
					{
						$sess_data['logged_in'] = 'yes';
						$sess_data['nim'] = $qad->nim;
						$sess_data['nama'] = $qad->nama_mahasiswa;
						$sess_data['angkatan'] = $qad->angkatan;
						$sess_data['jurusan'] = $qad->jurusan;
						$sess_data['stts'] = 'mahasiswa';
						$sess_data['program'] = $qad->kelas_program;
						$this->session->set_userdata($sess_data);
					}
					header('location:'.base_url().'mahasiswa');
				}
				else if($this->session->userdata('stts') =='dosen')
				{
					$q_ambil_data = $this->db->get_where('tbl_dosen', array('kd_dosen' => $u));
					foreach($q_ambil_data ->result() as $qad)
					{
						$sess_data['logged_in'] = 'yes';
						$sess_data['kd_dosen'] = $qad->kd_dosen;
						$sess_data['nidn'] = $qad->nidn;
						$sess_data['nama'] = $qad->nama_dosen;
						$sess_data['stts'] = 'dosen';
						$this->session->set_userdata($sess_data);
					}
					header('location:'.base_url().'dosen');
				}
				else if($this->session->userdata('stts') == 'admin')
				{
					$q_ambil_data = $this->db->get_where('tbl_admin', array('username' => $usr));
					foreach($q_ambil_data ->result() as $qad)
					{
						$sess_data['logged_in'] = 'yes';
						$sess_data['username'] = $qad->username;
						$sess_data['nama'] = $qad->nama_lengkap;
						$sess_data['stts'] = 'admin';
						$this->session->set_userdata($sess_data);
					}
					header('location:'.base_url().'admin');
				}
			}
		}


	}
	
	public function logout()
	{
		$cek = $this->session->userdata('logged_in');
		if(empty($cek))
		{
			header('location:'.base_url().'web');
		}
		else
		{
			$this->session->sess_destroy();
			header('location:'.base_url().'web');
		}
	}
}

/* End of file web.php */
/* Location: ./application/controllers/web.php */