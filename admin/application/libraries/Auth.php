<?php

class Auth {

    var $CI;
    var $_username;
    var $_table = array(
        'users' => 'users',
        'groups' => 'roles'
    );

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->helper('string');
        $this->CI->load->helper('cookie');
    }

    function Auth() {
        self::__construct();
    }

    function restrict($restrict_to = NULL, $redirect_to = NULL) {
        $redirect_to = ($redirect_to == NULL) ? $this->CI->config->item('base_url') . "admin" : $redirect_to;
        if ($restrict_to !== NULL) {
            if ($this->logged_in() == TRUE) {
                if ($this->CI->session->userdata('group_id') >= $restrict_to) {
                    return TRUE;
                } else {
                    show_error("You do not have sufficient rights to access this page!");
                    //redirect($redirect_to);	
                }
            } else {
                //show_error("You do not have sufficient rights to access this page!");
                redirect($redirect_to);
            }
        } else {
            show_error("You do not have sufficient rights to access this page!");
        }
    }

    function username_exists($username) {
        $this->CI->db->select('nick_name');
        $query = $this->CI->db->get_where($this->_table['users'], array('nick_name' => $username), 1);

        if ($query->num_rows() !== 1) {
            return FALSE;
        } else {
            $this->_username = $username;
            return TRUE;
        }
    }

    function forgot_password($email, $redirect_to = NULL, $error_view = NULL) {
        $this->CI->load->library('Email');
        $query = $this->CI->db->get_where('users', array(
            'email' => $email,
            'group_id' => '3'
                ), 1
        );

        if ($query->num_rows() === 1) {
            $row = $query->row();
            $new_password = $this->generateStrongPassword();
            $data = array('password' => $this->encrypt($new_password));

            /* Update admin password starts */
            $query = $this->CI->db->set($data)
                    ->where('group_id', '3')
                    ->where('email', $email)
                    ->update('users');
            /* Update admin password ends */

            /* Send newly generated password to admin email */


            $this->CI->email->from('your@example.com', 'Your Name');
            $this->CI->email->to($row->email);

            $this->CI->email->subject('Rhapsody Admin:PasswordRecovery request');
            $this->CI->email->message('"Hi,
                                                ' . $row->nick_name . '
                                                You have been notified that your new password successfully changed.Your account details is as below.
    
                                                Email Id : ' . $row->email . ' 
                                                Password : ' . $new_password . '

                                                Thank you for using our services!

                                                With Regards
                                                Rhapsody Administrator');

            $this->CI->email->send();
            //send_email
            $this->CI->session->set_flashdata('success', $this->CI->lang->line('success_email_password'));
            redirect($redirect_to);
        } else {
            $this->CI->session->set_flashdata('error', $this->CI->lang->line('forgot_password_error'));
            redirect($redirect_to);
        }
    }

    function check_password($password) {
        $this->CI->db->select('password');
        $query = $this->CI->db->where($this->_table['users'], array('nick_name' => $this->_username), 1)->row();

        if ($query->password == $this->encrypt($password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_string_length($string) {
        $string = trim($string);
        return strlen($string);
    }

    function encrypt($data) {
        if ($this->CI->config->item('encryption_key') !== NULL) {
            return sha1($this->CI->config->item('encryption_key') . $data);
        } else {
            show_error('Please set an encryption key in your config file. <a href="javascript:history.back();">back</a>');
        }
    }

    function login($email, $password, $redirect_to = NULL, $error_view = NULL) {
        $query = $this->CI->db->get_where('users', array(
            'email' => $email,
            'password' => $this->encrypt($password),
            'group_id'=>'3'
                ), 1
        );
        //echo $this->CI->db->last_query();exit;	
        if ($query->num_rows() === 1) {

            $row = $query->row();
            $data = array(
                'logged_in' => TRUE,
                'sess_expire_on_close' => TRUE,
                'username' => $row->nick_name,
                'user_id' => $row->id,
                'name' => $row->first_name . " " . $row->last_name,
                'group_id' => $row->group_id
            );
            $this->CI->session->set_userdata($data);

            $cookie = array(
                'name' => 'cifm',
                'value' => md5('fm_pass'),
                'prefix' => 'ci_',
                'expire' => '0',
                'path' => '/'
            );
            set_cookie($cookie);

            redirect($redirect_to);
        } else {
            if ($error_view != NULL) {
                $data['error'] = $this->CI->lang->line('access_denied');
                $this->CI->load->view($error_view, $data);
            } else {
                redirect($redirect_to);
            }
        }
    }

    function logged_in() {
        return $this->CI->session->userdata('logged_in');
    }

    function logout($redirect_to = NULL) {
        $this->CI->session->sess_destroy();
        delete_cookie('ci_cifm');
        if ($redirect_to != NULL) {
            redirect($redirect_to);
        }
    }

    // Generates a strong password of N length containing at least one lower case letter,
    // one uppercase letter, one digit, and one special character. The remaining characters
    // in the password are chosen at random from those four sets.
    //
    // The available characters in each set are user friendly - there are no ambiguous
    // characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
    // makes it much easier for users to manually type or speak their passwords.
    //
    // Note: the $add_dashes option will increase the length of the password by
    // floor(sqrt(N)) characters.

    function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds') {
        $sets = array();
        if (strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if (strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if (strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if (strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if (!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */