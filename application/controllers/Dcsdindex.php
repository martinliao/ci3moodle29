<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dcsdindex extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //if ($this->flags->is_login === FALSE) {
        //    redirect(base_url('welcome'));
        //}
        $this->load->model('dcsdindex_model');
        //$this->load->model('create_class/course_sch_model');
    }

    public function index()
    {
        $this->data['page_name'] = 'index';

        $appseqArr = $this->dcsdindex_model->checkNotSendPay();
        $username = $this->flags->user['username'];

        //$today = date("Y-m-d", strtotime("-2 day"));
        if (!empty($appseqArr)) {
        }
        //20210623 加入簽核通知
        //$user_idno = $this->course_sch_model->user_idno($username);
        // ....
//debugBreak();
        $this->layout->view('dcsdindex', $this->data);
    }
}
