<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Createclass_model extends MY_Model
{
    public $table = 'require';
    public $pk = 'seq_no';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($info=array())
    {
        $data = array_merge(array(
                        'year' => '',
                        'class_no' => '',
                        'term' => '1',
                        // 'base_term' => '',
                        'class_name' => '',
                        'type' => '',
                        'ht_class_type' => 'A01',
                        'no_persons' => '',
                        'classify' => '',
                        'class_cate' => '',
                        'class_cate1' => '',
                        'class_cate2' => '',
                        'range' => '',
                        'range_week' => '',
                        'weights' => '1',
                        'yn_continues' => '',
                        'isappsameclass' => '2',
                        'req_beaurau' => '',
                        'contactor' => '',
                        'tel' => '',
                        'room_code' => '',
                        'room_name' => '',
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
                        'dev_type_name' => '',
                        'beaurau_id' => '',
                        'contactor_email' => '',
                        'ecpa_class_id' => '',
                        'ecpa_class_name' => '',
                        'max_no_persons' => '',
                        'classify' => '',
                        'req_beaurau_name' => '',
                        'is_start' => '',
                        'is_assess' => '',
                        'is_mixed' => '0',
                        'type1' => '',
                        'type2' => '',
                        'type3' => '',
                        'type4' => '',
                        'type5' => '',
                        'type6' => '',
                        'type7' => '',
                        'type8' => '',
                        'map1' => '',
                        'map2' => '',
                        'map3' => '',
                        'map4' => '',
                        'map5' => '',
                        'map6' => '',
                        'map7' => '',
                        'map8' => '',
                        'env_class' => '',
                        'policy_class' => '',
                        'open_retirement' => '',
                        'special_status' => '',
                        'special_status_other' => '',
                        'segmemo' => '',
                        'course_name' => '',
                        'reason' => '',
                    ),$info);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'type' => array(
                'field' => 'type',
                'label' => '系列別代碼',
                'rules' => 'trim|required',
            ),
            'ecpa_class_id' => array(
                'field' => 'ecpa_class_id',
                'label' => '終身學習類別代碼',
                'rules' => 'trim|required',
            ),
            'class_name' => array(
                'field' => 'class_name',
                'label' => '班期名稱',
                'rules' => 'trim|required',
            ),
            'ht_class_type' => array(
                'field' => 'ht_class_type',
                'label' => '鐘點費類別',
                'rules' => 'trim|required',
            ),
            'no_persons' => array(
                'field' => 'no_persons',
                'label' => '本期人數',
                'rules' => 'trim|required',
            ),
            // 'range_week' => array(
            //     'field' => 'range_week',
            //     'label' => '訓練期程(週)',
            //     'rules' => 'trim|required',
            // ),
            'range' => array(
                'field' => 'range',
                'label' => '訓練期程(小時)',
                'rules' => 'trim|required',
            ),
            'weights' => array(
                'field' => 'weights',
                'label' => '權重',
                'rules' => 'trim|required',
            ),
            'req_beaurau' => array(
                'field' => 'req_beaurau',
                'label' => '承辦單位代碼 ',
                'rules' => 'trim|required',
            ),
            'is_assess' => array(
                'field' => 'is_assess',
                'label' => '考核班期',
                'rules' => 'trim|required',
            ),
            'is_mixed' => array(
                'field' => 'is_mixed',
                'label' => '混成班期',
                'rules' => 'trim|required',
            ),
            'env_class' => array(
                'field' => 'env_class',
                'label' => '環境教育班期',
                'rules' => 'required',
            ),
            'policy_class' => array(
                'field' => 'policy_class',
                'label' => '政策行銷班期',
                'rules' => 'required',
            ),
            'open_retirement' => array(
                'field' => 'open_retirement',
                'label' => '開放退休人員選課',
                'rules' => 'required',
            ),
        );

        return $config;
    }

    public function _insert($fields=array())
    {
        return $this->insert($fields);
    }

    public function _update($pk, $fields=array()) {
        return parent::update($pk, $fields);
    }

    public function updateRequire($seq_no, $data=array())
    {
        $this->db->trans_start();

        $this->db->set('way1',null);
        $this->db->set('way2',null);
        $this->db->set('way3',null);
        $this->db->set('way4',null);
        $this->db->set('way5',null);
        $this->db->set('way6',null);
        $this->db->set('way7',null);
        $this->db->set('way8',null);
        $this->db->set('way9',null);
        $this->db->set('way10',null);
        $this->db->set('way11',null);
        $this->db->set('way12',null);
        $this->db->set('way13',null);
        $this->db->set('way14',null);
        $this->db->set('way15',null);
        $this->db->set('way16',null);
        $this->db->set('way17',null);

        $this->db->set('type1',0);
        $this->db->set('type2',0);
        $this->db->set('type3',0);
        $this->db->set('type4',0);
        $this->db->set('type5',0);
        $this->db->set('type6',0);
        $this->db->set('type7',0);
        $this->db->set('type8',0);

        $this->db->set('map1',0);
        $this->db->set('map2',0);
        $this->db->set('map3',0);
        $this->db->set('map4',0);
        $this->db->set('map5',0);
        $this->db->set('map6',0);
        $this->db->set('map7',0);
        $this->db->set('map8',0);

        //2021-06-25 修正特殊情況無法取消勾選問題
        $this->db->set('not_hourfee',null);
        $this->db->set('not_location',null);
        $this->db->set('special_status',null);

        $this->db->where('seq_no',$seq_no);
        $this->db->update('require');

        $this->_update($seq_no,$data);

        $this->db->trans_complete();

        if($this->db->trans_status() === TRUE){
            return true;
        } 

        return false;
    }

    public function getListCount($attrs=array(),$bureau_id)
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['q'])) {
            $params['q'] = $attrs['q'];
        }
        $data = $this->getList($params,$bureau_id);
        return count($data);
    }

    public function getList($attrs=array(),$bureau_id)
    {
        if($bureau_id == '379680000A'){
            $params = array(
                'select' => 'require.seq_no,require.class_status,require.year, require.class_no, require.term, require.class_name, require.contactor, require.tel, require.range, require.room_code, require.room_remark, require.is_cancel, require.ecpa_class_id, series_category.name as series_name, second_category.name as second_name, bureau.name as dev_type_name, venue_information.room_name,5a_is_cancel',
                'order_by' => 'require.class_no,require.term',
            );

            $params['join'] = array(array('table' => 'series_category',
                                    'condition' => 'series_category.item_id = require.type',
                                    'join_type' => 'left'),
                                    array('table' => 'second_category',
                                    'condition' => 'second_category.item_id = require.beaurau_id',
                                    'join_type' => 'left'),
                                    array('table' => 'bureau',
                                    'condition' => 'bureau.bureau_id = require.dev_type',
                                    'join_type' => 'left'),
                                    array('table' => 'venue_information',
                                    'condition' => 'venue_information.room_id = require.room_code',
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
                'select' => 'require.seq_no, require.class_status,require.year, require.class_no, require.term, require.class_name, require.contactor, require.tel, require.range, require.room_code, require.room_remark, require.is_cancel, require.ecpa_class_id, series_category.name as series_name, bureau.name as dev_type_name,venue_information.room_name',
                'order_by' => 'require.class_no,require.term',
            );

            $params['join'] = array(array('table' => 'series_category',
                                    'condition' => 'series_category.item_id = require.type',
                                    'join_type' => 'left'),
                                    array('table' => 'bureau',
                                    'condition' => 'bureau.bureau_id = require.dev_type',
                                    'join_type' => 'left'),
                                    array('table' => 'venue_information',
                                    'condition' => 'venue_information.room_id = require.room_code',
                                    'join_type' => 'left')
                            );

            $level = $this->getBureauLevel($bureau_id);
            $under_bureau_id = $this->getEachUnderBureauId($bureau_id,abs($level['bureau_level']-5));
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
        if(isset($attrs['conditions']['where_special'])){
            $params['where_special']=$attrs['conditions']['where_special'];
            unset($attrs['conditions']['where_special']);
        }

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

    public function getEcpaClassName($ecpa_class_id){
        $this->db->select('desc1,desc2,desc3,desc4,desc5,deep');
        $this->db->where('num',$ecpa_class_id);
        $query = $this->db->get('ecpa_code');
        $result = $query->row_array();

        if(!empty($result)){
            if($result["deep"]==1) {
                return $result["desc1"];
            } else if($result["deep"]==2) {
                return $result["desc1"]."-".$result["desc2"];
            } else if($result["deep"]==3) {
                return $result["desc1"]."-".$result["desc2"]."-".$result["desc3"];
            } else if($result["deep"]==4) {
                return $result["desc1"]."-".$result["desc2"]."-".$result["desc3"]."-".$result["desc4"];
            } else {
                return $result["desc1"]."-".$result["desc2"]."-".$result["desc3"]."-".$result["desc4"]."-".$result["desc5"];
            }
        } else {
            return '';
        }
    }

    public function getRoomName($room_code){
        $this->db->select('room_name');
        $this->db->where('room_id',$room_code);
        $query = $this->db->get('venue_information');

        $result = $query->row_array();

        if(!empty($result)){
            return $result['room_name'];
        } else {
            return '';
        }
    }

    public function getRoom($seq_no){
        $this->db->select('venue_information.room_name,require.start_date1,require.end_date1');
        $this->db->join('venue_information','venue_information.room_id = require.room_code');
        $this->db->where('require.seq_no',$seq_no);
        $query = $this->db->get('require');

        $result = $query->result_array();

        return $result;
    }

    public function getHourlyFee(){
        $this->db->select('item_id,name');
        $query = $this->db->get('hourlyfee_category');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getClassProperty(){
        $this->db->select('item_id,name');
        $query = $this->db->get('class_property');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getStudyWayOne(){
        $this->db->select('item_id,name');
        $query = $this->db->get('studyway_one');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getStudyWayTwo(){
        $this->db->select('item_id,name');
        $query = $this->db->get('studyway_two');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getStudyWayThree(){
        $this->db->select('item_id,name');
        $query = $this->db->get('studyway_three');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getElectionWay(){
        $this->db->select('item_id,name');
        $query = $this->db->get('election_way');
        $result = $query->result_array();

        $data = array();
        for($i=0;$i<count($result);$i++){
            $data[$result[$i]['item_id']] = $result[$i]['name'];
        }
        return $data;
    }

    public function getSecondCategory($type){
        $this->db->select('item_id,name');
        $this->db->where('parent_id',$type);
        $query = $this->db->get('second_category');
        $result = $query->result_array();

        return $result;
    }

    public function getDevTypeName($dev_type){
        $this->db->select('name');
        $this->db->where('bureau_id',$dev_type);
        $query = $this->db->get('bureau');
        $result = $query->row_array();

        if(!empty($result)){
            return $result['name'];
        } else {
            return '';
        }
    }

    public function getSegmemo($year,$class_no){
        $this->db->select('segmemo');
        $this->db->where('year',$year);
        $this->db->where('class_no',$class_no);
        $query = $this->db->get('require_segmemo');
        $result = $query->row_array();

        if(!empty($result)){
            return $result['segmemo'];
        } else {
            return '';
        }
    }

    public function insertSegmemo($segmemo=array()){
        $segmemo = array_map('addslashes', $segmemo);
        if($this->db->insert('require_segmemo',$segmemo)){
            return true;
        }

        return false;
    }

    public function updateSegmemo($segmemo=array()){
        $this->db->where('year',$segmemo['year']);
        $this->db->where('class_no',$segmemo['class_no']);
        $this->db->set('segmemo',$segmemo['segmemo']);
        if($this->db->update('require_segmemo')){
            return true;
        }

        return false;
    }

    public function getCourse($year,$class_no,$term){
        $this->db->select('course_name,material');
        $this->db->where('year',$year);
        $this->db->where('class_no',$class_no);
        $this->db->where('term',$term);
        $query = $this->db->get('require_content');
        $result = $query->result_array();

        return $result;
    }

    public function insertCourse($year,$class_no,$term,$course_name,$material){
        $data = array(
                    'year' => $year,
                    'class_no' => $class_no,
                    'term' => $term,
                    'course_name' => $course_name,
                    'material' => $material
                );
        if($this->db->insert('require_content',$data)){
            return true;
        }

        return false;
    }

    public function deleteCourse($year,$class_no,$term){
        $data = array(
                    'year' => $year,
                    'class_no' => $class_no,
                    'term' => $term
                );
        if($this->db->delete('require_content',$data)){
            return true;
        }

        return false;
    }

    public function getPriority($conditions=array()){
        $this->db->from($this->table);
        $this->db->where('year',$conditions['year']);
        $this->db->where('class_no',$conditions['class_no']);
        $this->db->where('term',$conditions['term']);
        $this->db->where('CURDATE() < IFNULL(apply_s_date2 ,CURDATE() + INTERVAL 1 DAY)');
        $priority = $this->db->count_all_results();
        if ($priority > 0) {
            return 1;
        } else {
            return 2;
        }
    }

    public function getExportData($data=array()){
        $where = '1=1';

        if(!empty($data['query_year'])){
            $where .= sprintf(" and A.year = %s",$this->db->escape(addslashes($data['query_year'])));
        }

        if(!empty($data['query_class_status'])){
            $where .= sprintf(" and A.class_status = %s",$this->db->escape(addslashes($data['query_class_status'])));
        }

        if(!empty($data['query_class_no'])){
            $where .= sprintf(" and A.class_no like %s",$this->db->escape("%%".addslashes(strtoupper($data['query_class_no'])."%%")));
        }

        if(!empty($data['query_class_name'])){
            $where .= sprintf(" and A.class_name like %s",$this->db->escape("%%".addslashes($data['query_class_name']))."%%");
        }

        if(!empty($data['query_type'])){
            $where .= sprintf(" and A.type = %s",$this->db->escape(addslashes($data['query_type'])));
        }

        if(!empty($data['query_second'])){
            $where .= sprintf(" and A.req_beaurau = %s",$this->db->escape(addslashes($data['query_second'])));
        }

        if(!empty($data['query_is_cancel'])){
            $where .= sprintf(" and (A.is_cancel = %s or 5a_is_cancel='Y')",$this->db->escape(addslashes($data['query_is_cancel'])));
        }

        $sql = sprintf("SELECT
                            cl.room_sname,cl.room_name,
                            A.*, C. name AS description, D.name AS dev_type_name, E.name AS ht_class_type_name, F.name AS classify_name, G.name AS studyway_one_name, H.name AS studyway_two_name, I.name AS studyway_three_name, J.name AS app_type_name,K.segmemo,
                            CASE
                        WHEN A.type = 'A' THEN
                            '行政系列'
                        WHEN A.type = 'B' THEN
                            '發展系列'
                        ELSE
                            ''
                        END AS series_name
                        FROM
                            `require` A
                        LEFT JOIN BS_user B ON A.contactor = B.username
                        LEFT JOIN second_category C ON A.beaurau_id = C.item_id
                        LEFT JOIN venue_information cl ON A.room_code = cl.room_id
                        LEFT JOIN bureau D ON A.dev_type = D.bureau_id
                        LEFT JOIN hourlyfee_category E ON A.ht_class_type = E.item_id
                        LEFT JOIN class_property F ON A.classify = F.item_id
                        LEFT JOIN studyway_one G ON A.class_cate = G.item_id
                        LEFT JOIN studyway_two H ON A.class_cate = H.item_id
                        LEFT JOIN studyway_three I ON A.class_cate = I.item_id
                        LEFT JOIN election_way J ON A.app_type = J.item_id
                        LEFT JOIN require_segmemo K ON A.year = K.year AND A.class_no = K.class_no
                        WHERE
                            1 = 1
                        AND %s",$where);
        
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function createClassNoByClassname($class_name){
        $this->db->select('class_no');
        $this->db->where('class_name',$class_name);
        $this->db->group_by('class_no');
        $query = $this->db->get('require');
        $result = $query->result_array();

        if(!empty($result)){
            return $result[0]['class_no'];
        }
        
        return '';
    }

    public function insertRequireOnline($year,$class_no,$term,$is_assess,$is_mixed,$online_course_name,$hours,$elrid){
        $this->db->trans_start();

        $this->db->where('year',$year);
        $this->db->where('class_no',$class_no);
        $this->db->where('term',$term);
        $this->db->delete('require_online');

        //if($is_assess == '1' && $is_mixed == '1'){ //2021-06-09 取消3B.edit *考核班期*影響*混成班級*的設定
            for($i=0;$i<count($online_course_name);$i++){
                $this->db->set('year',$year);
                $this->db->set('class_no',$class_no);
                $this->db->set('term',$term);
                $this->db->set('class_name',$online_course_name[$i]);
                $this->db->set('elearn_id',$elrid[$i]);
                $this->db->set('hours',$hours[$i]);
                $this->db->set('createdate',date('Y-m-d H:i:s'));
                $this->db->insert('require_online');
            }
        //}

        $this->db->trans_complete();

        if($this->db->trans_status() === TRUE){
            return true;
        } 
    }

    public function getRequireOnline($year,$class_no,$term){
        $this->db->select('*');
        $this->db->where('year',$year);
        $this->db->where('class_no',$class_no);
        $this->db->where('term',$term);
        $this->db->order_by('id');
        $query = $this->db->get('require_online');
        $result = $query->result_array();

        return $result;
    }
    
    //mark 2021-06-03
    public function getRequire($seq_no){
        $this->db->select('*');
        $this->db->where('require.seq_no',$seq_no);
        $query = $this->db->get('require');

        $result = $query->result_array();

        return $result;
    }

}