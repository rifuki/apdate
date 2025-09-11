<?php
class RoleCheck {
    public function verify() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');

        // Clear any persistent alert messages that might be stuck from failed logins
        $session_data = $CI->session->userdata('user_dashboard');
        if (!empty($session_data)) {
            // User is logged in - clear any leftover alert messages
            $CI->session->unset_userdata('__ci_flash');
        }

        $uri = $CI->uri->segment(1); // prefix pertama: admin/guru/siswa
        $uri2 = $CI->uri->segment(2); // prefix pertama: admin/guru/siswa
        $role = $CI->session->userdata('user_dashboard')['role'] ?? null;

        

        $redirect_to = "dashboard";
        if ($role == 'admin') {
            $redirect_to = 'dashboard';
        } elseif ($role == 'guru') {
            $redirect_to = 'guru/dashboard';
        } elseif ($role == 'siswa') {
            $redirect_to = 'siswa/dashboard';
        }


        if ($uri === 'dashboard' && $role !== 'admin') {
            redirect($redirect_to);
        }

        if ($uri === 'guru' && !in_array($uri2, ['login', 'logout']) && $role !== 'guru') {
            redirect($redirect_to);
        }

        if ($uri === 'siswa' && !in_array($uri2, ['login', 'logout']) && $role !== 'siswa') {
            redirect($redirect_to);
        }
    }
}
