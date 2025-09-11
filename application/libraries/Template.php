<?php
class Template{
    protected $CI;
    
    function __construct(){
        $this->CI = &get_instance();
    }
    
    function _v($file,$data=array(),$header=TRUE){
        $this->theme = 'admin/';
        $this->CI->load->view('admin/template/header',$data);
        $this->CI->load->view('admin/'.$file,$data);
        $this->CI->load->view('admin/template/footer',$data);
    }

    function _vFront($file,$data=array(),$header=TRUE){
        $this->theme = 'front/';
        $this->CI->load->view('front/template/header',$data);
        $this->CI->load->view('front/'.$file,$data);
        $this->CI->load->view('front/template/footer',$data);
    }

    function _vGuru($file,$data=array(),$header=TRUE){
        $this->theme = 'guru/';
        $session = session();
		$data['active_periode'] = active_periode();
        if (empty($data['wali_kelas'])) {
            $data['wali_kelas']     = wali_kelas($data['active_periode']['periode_id'], $session['guru']['id']);
        }
        // dd($data);
        $this->CI->load->view('guru/template/header',$data);
        $this->CI->load->view('guru/'.$file,$data);
        $this->CI->load->view('guru/template/footer',$data);
    }

    function _vSiswa($file,$data=array(),$header=TRUE){
        $this->theme = 'siswa/';
        $this->CI->load->view('siswa/template/header',$data);
        $this->CI->load->view('siswa/'.$file,$data);
        $this->CI->load->view('siswa/template/footer',$data);
    }
}