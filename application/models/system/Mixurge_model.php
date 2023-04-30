<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mixurge_model extends MY_Model
{
    public $table = 'lux_urge_set';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getList()
    {
        
        $params = array(
            'select' => '*',
            'order_by' => 'id',
        );
    
        $data = $this->getData($params);

        return $data;
    }

    public function updateMixurge($data=array()){
        $this->db->trans_start();

        $this->db->set('enable','0');
        $this->db->set('yn_staff','0');
        $this->db->set('yn_stud','0');
        $this->db->update('lux_urge_set');

        if(isset($data['urge']['register']) && !empty($data['urge']['register'])){
            if(isset($data['urge']['register'][-10]) && $data['urge']['register'][-10] == '1'){
                $this->db->set('enable','1');
                $this->db->where('type','register');
                $this->db->where('set_days','-10');
                 $this->db->update('lux_urge_set');
            }

            if(isset($data['urge']['register'][-5]) && $data['urge']['register'][-5] == '1'){
                $this->db->set('enable','1');
                $this->db->where('type','register');
                $this->db->where('set_days','-5');
                $this->db->update('lux_urge_set');
            }
        }

        if(isset($data['urge']['openClass']) && !empty($data['urge']['openClass'])){
            foreach ($data['urge']['openClass'] as $key => $value) {
                if(isset($value['enable']) && $value['enable'] == '1'){
                    $this->db->set('enable','1');
                }

                if(isset($value['staff']) && $value['staff'] == '1'){
                    $this->db->set('yn_staff','1');
                }

                if(isset($value['stu']) && $value['stu'] == '1'){
                    $this->db->set('yn_stud','1');
                }

                $this->db->where('type','openClass');
                $this->db->where('set_days',$key);
                $this->db->update('lux_urge_set');
            }
        }


        $this->db->trans_complete();

        if($this->db->trans_status() === TRUE){
            return true;
        } 

        return false;
    }

}