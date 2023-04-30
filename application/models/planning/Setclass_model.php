<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setclass_model extends MY_Model
{
    public $table = 'require';
    public $pk = 'seq_no';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault()
    {
        $data = array(
            'year' => '',
            'class_no' => '',
            'term' => '',
            'class_name' => '',
            'type' => '',
            'ht_class_type' => '',
            'no_persons' => '',
            'classify' => '',
            'class_cate' => '',
            'range' => '',
            'yn_continues' => '',
            'isappsameclass' => '',
            'req_beaurau' => '',
            'contactor' => '',
            'tel' => '',
            'room_code' => '',
            'app_type' => '',
            'start_date1' => '',
            'end_date1' => '',
            'start_date2' => '',
            'end_date2' => '',
            'start_date3' => '',
            'end_date3' => '',
            'way1' => '',
            'way2' => '',
            'way3' => '',
            'way4' => '',
            'way5' => '',
            'way6' => '',
            'way7' => '',
            'way8' => '',
            'way9' => '',
            'way10' => '',
            'way11' => '',
            'way12' => '',
            'way13' => '',
            'way14' => '',
            'way15' => '',
            'way16' => '',
            'way17' => '',
            'obj' => '',
            'content' => '',
            'respondant' => '',
            'class_name_shot' => '',
            'min_no_persons' => '',
            'class_status' => '1',
            'dev_type' => '',
            'beaurau_id' => '',
            'contactor_email' => '',
        );

        return $data;
    }

    public function getOrganFormDefault()
    {
        $data = array(
            'year' => '',
            'class_no' => '',
            'term' => '',
            'class_name' => '',
            'ht_class_type' => '',
            'no_persons' => '',
            'classify' => '',
            'class_cate' => '',
            'range' => '',
            'yn_continues' => '',
            'isappsameclass' => '',
            'req_beaurau' => '',
            'contactor' => '',
            'tel' => '',
            'room_code' => '',
            'app_type' => '',
            'start_date1' => '',
            'end_date1' => '',
            'start_date2' => '',
            'end_date2' => '',
            'start_date3' => '',
            'end_date3' => '',
            'way1' => '',
            'way2' => '',
            'way3' => '',
            'way4' => '',
            'way5' => '',
            'way6' => '',
            'way7' => '',
            'way8' => '',
            'way9' => '',
            'way10' => '',
            'way11' => '',
            'way12' => '',
            'way13' => '',
            'way14' => '',
            'way15' => '',
            'way16' => '',
            'way17' => '',
            'obj' => '',
            'content' => '',
            'respondant' => '',
            'class_name_shot' => '',
            'min_no_persons' => '',
            'class_status' => '1',
            'contactor_email' => '',
        );

        return $data;
    }

    public function createClassNo($type)
    {
        if($type == 'A'){
            $params = array(
                'select' => 'substr(class_no,3,6) cnt',
                'conditions' => array('type' => 'A', 'substr(class_no,1,2)' => 'AA'),
                'order_by' => 'class_no desc',
                'rows' => '1'
            );
            $data = $this->getData($params);
            if(!empty($data)){
                $class_no = 'AA' .str_pad(intval($data[0]['cnt'])+1,4,'0',STR_PAD_LEFT);
                while ($this->checkClassNO($class_no) > 0) {
                    $data[0]['cnt'] = $data[0]['cnt']+1;
                    $class_no = 'AA' .str_pad(intval($data[0]['cnt'])+1,4,'0',STR_PAD_LEFT);
                }
            } else {
                $class_no = 'AA' .str_pad(1,4,'0',STR_PAD_LEFT);
            }
        } elseif ($type == 'B') {
            $params = array(
                'select' => 'substr(class_no,3,6) cnt',
                'conditions' => array('type' => 'B', 'substr(class_no,1,2)' => 'B0'),
                'order_by' => 'class_no desc',
                'rows' => '1'
            );
            $data = $this->getData($params);
            if(!empty($data)){
                $class_no = 'B' .str_pad(intval($data[0]['cnt'])+1,5,'0',STR_PAD_LEFT);
                while ($this->checkClassNO($class_no) > 0) {
                    $data[0]['cnt'] = $data[0]['cnt']+1;
                    $class_no = 'B' .str_pad(intval($data[0]['cnt'])+1,5,'0',STR_PAD_LEFT);
                }
            } else {
                $class_no = 'B' .str_pad(1,5,'0',STR_PAD_LEFT);
            }
        }

        return $class_no;
    }

    public function checkClassNO($class_no){
        $sql = sprintf("select count(1) cnt from `require` where class_no = '%s'",$class_no);
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result[0]['cnt'];
    }

    public function _insert($fields=array())
    {
        return $this->insert($fields);
    }

    public function getListCount($attrs=array(),$bureau_id)
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['query_class_name'])) {
            $params['query_class_name'] = $attrs['query_class_name'];
        }
        $data = $this->getList($params,$bureau_id);
        return count($data);
    }

    public function getList($attrs=array(),$bureau_id)
    {
        if($bureau_id == '379680000A'){
            $params = array(
                'select' => 'require.seq_no, require.class_no, require.class_name, series_category.name as series_name, second_category.name as second_name',
                'order_by' => 'require.class_no',
            );

            $params['join'] = array(array('table' => 'series_category',
                                'condition' => 'series_category.item_id = require.type',
                                'join_type' => 'left'),
                                array('table' => 'second_category',
                                'condition' => 'second_category.item_id = require.beaurau_id',
                                'join_type' => 'left')
                        );

            if (isset($attrs['query_class_name'])) {
                $params['or_like'] = array(
                    'many' => TRUE,
                    'data' => array(
                        array('field' => 'require.class_name', 'value'=>$attrs['query_class_name'], 'position'=>'both'),
                    ),
                );
            }

        } else {
            $params = array(
                'select' => 'require.seq_no, require.class_no, require.class_name, series_category.name as series_name, bureau.name as dev_type_name',
                'order_by' => 'require.class_no',
            );

            $params['join'] = array(array('table' => 'series_category',
                                'condition' => 'series_category.item_id = require.type',
                                'join_type' => 'left'),
                                array('table' => 'bureau',
                                'condition' => 'bureau.bureau_id = require.dev_type',
                                'join_type' => 'left')
                        );

            $level = $this->getBureauLevel($bureau_id);
            $under_bureau_id = $this->getEachUnderBureauId($bureau_id,abs($level['bureau_level']-5));
            // echo '<pre>';

            // print_r($under_bureau_id);
            // die();
            $params['where_in'] = array('field' => 'require.dev_type', 'value' => $under_bureau_id);

            if (isset($attrs['query_class_name'])) {
                $params['or_like'] = array(
                    'many' => TRUE,
                    'data' => array(
                        array('field' => 'require.class_name', 'value'=>$attrs['query_class_name'], 'position'=>'both'),
                    ),
                );
            }
        }

        $params['group_by'] = 'require.class_no,require.class_name,require.type,require.beaurau_id';

        if (isset($attrs['conditions'])) {
            $params['conditions'] = $attrs['conditions'];
        }
        if (isset($attrs['rows'])) {
            $params['rows'] = $attrs['rows'];
        }
        if (isset($attrs['offset'])) {
            $params['offset'] = $attrs['offset'];
        }
        if (isset($attrs['sort'])) {
            $params['order_by'] = $attrs['sort'];
        }

        $data = $this->getData($params);

        return $data;
    }

    public function checkExist($class_no){
        $current = date('Y')-1911;
        $next = date('Y')-1910;
        $year_list = array($current,$next);

        $params = array(
            'select' => 'year',
            'conditions' => array('class_no' => $class_no),
            'where_in' => array('field' => 'year', 'value' => $year_list)
        );

        $data = $this->getData($params);
        return $data;
    }

    public function getEachUnderBureauId($bureau_id,$times){
        $bureau_id_list = array($bureau_id);
        $total_bureau_id = array($bureau_id);
      
        for($i=0;$i<$times;$i++){
            if(isset($bureau_id_list) && !empty($bureau_id_list)){
                $this->db->select('bureau_id');
                $this->db->where_in('parent_id',$bureau_id_list);
                $this->db->where('del_flag','N');
                $query = $this->db->get('bureau');
                $bureau_id_list = array();
                $result = $query->result_array();
               
                foreach ($result as $row){
                    array_push($bureau_id_list, $row['bureau_id']);
                    array_push($total_bureau_id, $row['bureau_id']);
                } 
            } 
        }

        return  $total_bureau_id;
    }

    public function getBureauLevel($bureau_id){
        $this->db->select('bureau_level');
        $this->db->where('bureau_id',$bureau_id);
        $query = $this->db->get('bureau');
        $result = $query->row_array();

        return $result;
    }

    //取使用者所屬局處的上級機關代碼(取層級3)
    public function getUserSuperBureauId($bureau_id){
        $level = $this->getBureauLevel($bureau_id);

        if($level['bureau_level'] == '3'){
            return $bureau_id;
        } else if($level['bureau_level'] == '4'){
            $this->db->select('parent_id');
            $this->db->where('bureau_id',$bureau_id);
            $query_one = $this->db->get('bureau');
            $result_one = $query_one->row_array();

            return $result_one['parent_id'];
        } else if($level['bureau_level'] == '5'){
            $this->db->select('parent_id');
            $this->db->where('bureau_id',$bureau_id);
            $query_one = $this->db->get('bureau');
            $result_one = $query_one->row_array();

            $this->db->select('parent_id');
            $this->db->where('bureau_id',$result_one['parent_id']);
            $query_two = $this->db->get('bureau');
            $result_two = $query_two->row_array();

            return $result_two['parent_id'];
        } else {
            return '';
        }
    }

    public function checkRepeat($year,$class_no,$term){
        $this->db->select('count(1) cnt');
        $this->db->where('year',$year);
        $this->db->where('class_no',$class_no);
        $this->db->where('term',$term);
        $query = $this->db->get('require');
        $result = $query->result_array();

        if($result[0]['cnt'] > 0){
            return true;
        }
        
        return false;
    }

}