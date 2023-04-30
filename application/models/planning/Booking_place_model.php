<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_place_model extends MY_Model
{
    public $table = 'booking_place';
    public $pk = 'id';

    public function __construct()
    {
        parent::__construct();

        $this->init($this->table, $this->pk);

    }

    public function getFormDefault($info=array())
    {
        //YEAR, CLASS_NO, TERM, BOOKING_DATE, BOOKING_PERIOD, CAT_ID, ROOM_ID
        $data = array_merge(array(
        			'seq_no' => '',
                    'year' => '',
                    'class_no' => '',
                    'term' => '',
                    'class_name' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'addRoom' => '',
                ),$info);

        return $data;
    }

    public function getVerifyConfig()
    {
        $config = array(
            'year' => array(
                'field' => 'year',
                'label' => '年度',
                'rules' => 'trim|required|max_length[7]',
            ),
            'class_no' => array(
                'field' => 'class_no',
                'label' => '課程代號',
                'rules' => 'trim|required',
            ),
            'term' => array(
                'field' => 'term',
                'label' => '期別',
                'rules' => 'trim|required',
            ),
            'class_name' => array(
                'field' => 'class_name',
                'label' => '名稱',
                'rules' => 'trim|required',
            ),
            'start_date' => array(
                'field' => 'start_date',
                'label' => '使用起日',
                'rules' => 'trim|required',
            ),
            'end_date' => array(
                'field' => 'end_date',
                'label' => '使用迄日',
                'rules' => 'trim|required',
            ),
            'addRoom' => array(
                'field' => 'addRoom',
                'label' => '使用名稱',
                'rules' => 'trim|required',
            ),
        );

        return $config;
    }

    public function _insert($fields=array())
    {
        return $this->insert($fields);
    }

    public function _update($pk, $fields=array())
    {
        return parent::update($pk, $fields);
    }

    public function getListCount($attrs=array())
    {
        $params = array(
            'conditions' => $attrs['conditions'],
        );

        if (isset($attrs['q'])) {
            $params['q'] = $attrs['q'];
        }
        $data = $this->getList($params);
        return count($data);
    }

    public function getList($attrs=array())
    {
        $params = array(
            'select' => 'room_id, room_name',
            'order_by' => 'room_id',
        );
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
        if (isset($attrs['q'])) {
            $params['or_like'] = array(
                'many' => TRUE,
                'data' => array(
                    array('field' => 'item_id', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'name', 'value'=>$attrs['q'], 'position'=>'both'),
                    array('field' => 'remark', 'value'=>$attrs['q'], 'position'=>'both'),
                ),
            );
            // unset
        }

        $data = $this->getData($params);

        return $data;
    }

    public function getChoices()
    {
        $choices = array();
        $attrs['conditions'] = array(
            'enable' => '1',
        );
        $data = $this->getList($attrs);
        foreach ($data as $row) {
            $choices[$row['item_id']] = $row['name'];
        }
        return $choices;
    }

    public function getPlacess()
    {
        $choices = array();
        $attrs['conditions'] = array(
            'enable' => '1',
        );
        $data = $this->getList($attrs);
        foreach ($data as $row) {
            $choices[$row['item_id']] = $row['name'];
        }
        return $choices;
    }

    public function getPlace($conditions=array())
    {

        $room_time = $conditions['room_time'];
        $room_type = $conditions['room_type'];
        $start_date = $conditions['start_date'];
        $end_date = $conditions['end_date'];

        $sql = "select distinct  c.room_id from venue_time c\n";
        // 20191022 客戶修改 教室僅須呈現B、C、E區教室，其餘不須呈現
        $sql .= "left join venue_information cr on c.room_id=cr.room_id \n";
        $sql .= "where c.price_t ='{$room_time}' and cr.room_type='{$room_type}'\n";
        if($room_type == '01'){
            $sql .= "and ( cr.room_id like 'B%' or cr.room_id like 'C%' or cr.room_id like 'E%')";
        }

        $query = $this->db->query($sql);
        $data = $query->result_array();
        $room_id_in = array();
        foreach($data as $row){
        	$room_id_in[] = $row['room_id'];
        }
        if(empty($room_id_in)){
        	$room_id_in = 'not_exist';
        }
        //jd($room_id_in);

        $sql = "select distinct room_id from booking_place b\n";
        $sql .= "left join `require` r ON r.year = b.year AND r.class_no = b.class_no AND r.term = b.term\n";
        $sql .= "where b.booking_date between '{$start_date}' and '{$end_date}' AND (r.is_cancel not in ('1') OR r.is_cancel = '0')\n";

        $query = $this->db->query($sql);
        $data = $query->result_array();
        $room_id_not_in1 = array();
        foreach($data as $row){
        	$room_id_not_in1[] = $row['room_id'];
        }
        //jd($room_id_not_in1);

        $sql = "select distinct room_id from room_use a\n";
        $sql .= "left join `require` c1 ON a.year = c1.year AND a.class_id = c1.class_no AND a.term = c1.term\n";
        $sql .= "where use_date between '{$start_date}' and '{$end_date}' AND (a.appi_id is not null OR c1.is_cancel = '0')\n";

        $query = $this->db->query($sql);
        $data = $query->result_array();
        $room_id_not_in2 = array();
        foreach($data as $row){
        	$room_id_not_in2[] = $row['room_id'];
        }
        //jd($room_id_not_in2);

        $this->db->select("*");
        $this->db->from("venue_information");
        $this->db->where("IFNULL(del_flag, '') = ''");
        $this->db->where("room_type", $room_type);
        $this->db->where("room_bel", "68000");
        $this->db->where_in("room_id", $room_id_in);
        if(!empty($room_id_not_in1)){
        	$this->db->where_not_in("room_id", $room_id_not_in1);
        }
        if(!empty($room_id_not_in2)){
        	$this->db->where_not_in("room_id", $room_id_not_in2);
        }
        $this->db->order_by('room_name asc');
        $query = $this->db->get();
        $data = $query->result_array();

        //jd($data,1);
        return $data;
    }

    public function getBooking($seq_no=NULL)
    {

        $this->db->select("room_id, booking_period, cat_id, min(booking_date) as start_date, max(booking_date) as end_date, seq_no"); //20210806 Roger 增加一個欄位值
        $this->db->from("booking_place");
        $this->db->where("seq_no", $seq_no);
        $this->db->group_by("seq_no, room_id, cre_date");
        $this->db->order_by('start_date, end_date');
        $query = $this->db->get();
        $data = $query->result_array();
        foreach($data as & $row){
        	$this->db->select("room_name");
        	$this->db->from("venue_information");
        	$this->db->where("room_id", $row['room_id']);
        	$query = $this->db->get();
        	$room_data = $query->row_array();
        	$row['room_name'] = $room_data['room_name'];

        }
        // jd($data,1);
        return $data;
    }

    public function get_date_interval($seq_no=NULL)
    {

        $this->db->select("seq_no, min(booking_date) as start_date, MAX(booking_date) as end_date");
        $this->db->from("booking_place");
        $this->db->where("seq_no", $seq_no);
        //$this->db->group_by("room_id");   //mark 2021-06-04 修正end_date無法抓取最大
        //$this->db->order_by('start_date, end_date');  //mark 2021-06-04 修正end_date無法抓取最大
        $query = $this->db->get();
        $data = $query->row_array();

        return $data;
    }

    public function get_room($room_type=NULL, $get_choices=FALSE)
    {
        $this->db->select("room_id, room_name");
        $this->db->from("venue_information");
        $this->db->where("IFNULL(del_flag, '') = ''");
        $this->db->where("room_type", $room_type);
        if($room_type == '01'){
            $this->db->where("room_bel", "68000");
        }
        $this->db->order_by('room_name asc');
        $query = $this->db->get();
        $data = $query->result_array();
        if($get_choices === TRUE){
            $choices = array();
            foreach ($data as $row) {
                $choices[$row['room_id']] = $row['room_name'];
            }
            return $choices;
        }
        // jd($choices,1);
        return $data;
    }

    public function get_room_countby($room_id=NULL)
    {
        $this->db->select("room_countby");
        $this->db->from("venue_information");
        $this->db->where("IFNULL(del_flag, '') = ''");
        $this->db->where("room_id", $room_id);
        $query = $this->db->get();
        $data = $query->row_array();

        // jd($data,1);
        return $data;
    }

    public function get_room_time($room_id=NULL)
    {
        $this->db->select("price_t");
        $this->db->from("venue_time");
        $this->db->where("room_id", $room_id);
        $this->db->order_by('price_t asc');
        $query = $this->db->get();
        $data = $query->result_array();
        foreach($data as & $row){
            $this->db->select("name");
            $this->db->from("reservation_time");
            $this->db->where("item_id", $row['price_t']);
            $query = $this->db->get();
            $time_data = $query->row_array();
            $row['name'] = $time_data['name'];

        }
        // jd($choices,1);
        return $data;
    }

    public function select_booking($conditions=array())
    {
        $this->db->select("room_id,room_name");
        $this->db->from("venue_information");
        $this->db->where("IFNULL(del_flag, '') = ''");
        $this->db->where("room_type", $conditions['cat_id']);
        if(isset($conditions['room_id'])){
            // jd($conditions['room_id']);
            $this->db->where("room_id", $conditions['room_id']);
        }
        if($conditions['cat_id'] == '01'){
            $this->db->where("room_bel", "68000");
        }
        
        $this->db->order_by('room_name asc');
        $query = $this->db->get();
        $data = $query->result_array();
        // jd($data);
        $days = ((strtotime($conditions['end_date'])-strtotime($conditions['start_date'])) / 86400) + 1;

        foreach($data as & $row){
            for($i=0; $i<$days; $i++){
                $select_day = date("Y-m-d",strtotime("+{$i} day",strtotime($conditions['start_date'])));

                $sql = "select distinct * from
                    (
                    SELECT A.id, '1' AS BTYPE, A.ROOM_ID, A.BOOKING_DATE, B.CLASS_NAME, C.start_time AS FROM_TIME, C.end_time AS TO_TIME,A.Year, A.term as TERM FROM booking_place A
                    LEFT JOIN `require` B ON A.year = B.year AND A.CLASS_NO = B.CLASS_NO AND A.TERM = B.TERM AND B.CLASS_STATUS in ('2','3')
                    LEFT JOIN reservation_time C ON A.BOOKING_PERIOD = C.ITEM_ID
                    WHERE A.ROOM_ID = '{$row['room_id']}' and A.BOOKING_DATE = '{$select_day}'  AND (B.is_cancel not in ('1') or B.is_cancel = '0' or B.is_cancel is null)
                    UNION ALL
                    SELECT '' as id, CASE WHEN A1.APPI_ID IS NULL THEN '3' ELSE '2' END AS BTYPE,
                    A1.ROOM_ID, A1.USE_DATE AS BOOKING_DATE, IFNULL(C1.CLASS_NAME,C2.APP_REASON) AS CLASS_NAME,
                    IFNULL(IFNULL(B1.FROM_TIME,B2.FROM_TIME),B3.start_time) AS FROM_TIME,
                    IFNULL(IFNULL(B1.TO_TIME,B2.TO_TIME),B3.end_time) AS TO_TIME , A1.year,A1.term
                    FROM room_use A1
                    LEFT JOIN periodtime B1 ON A1.USE_PERIOD = B1.ID AND A1.YEAR = B1.YEAR AND A1.CLASS_ID = B1.CLASS_NO AND A1.TERM = B1.TERM AND A1.ROOM_ID = B1.ROOM_ID AND A1.USE_ID = B1.COURSE_CODE AND A1.USE_DATE = B1.COURSE_DATE
                    LEFT JOIN periodtime B2 ON A1.USE_PERIOD = B2.ID AND B2.YEAR IS NULL AND B2.CLASS_NO IS NULL AND B2.TERM IS NULL AND B2.ROOM_ID IS NULL AND B2.COURSE_CODE IS NULL AND A1.APPI_ID IS NULL
                    LEFT JOIN reservation_time B3 ON A1.USE_PERIOD = B3.ITEM_ID
                    LEFT JOIN `require` C1 ON A1.YEAR = C1.YEAR AND A1.CLASS_ID = C1.CLASS_NO AND A1.TERM = C1.TERM AND C1.CLASS_STATUS in ('2','3')
                    LEFT JOIN appinfo C2 ON A1.APPI_ID = C2.APPI_ID
                    WHERE A1.ROOM_ID = '{$row['room_id']}' and A1.USE_DATE = '{$select_day}'  AND (C1.is_cancel not in ('1') or C1.is_cancel = '0' or C1.is_cancel is null)
                    ) select_booking
                    order by from_time";

                $query = $this->db->query($sql);
                $data_booking = $query->result_array();
                $row[$select_day] = $data_booking;

            }
        }

        return $data;
    }


    public function select_usage_list($conditions=array())
    {
        $this->db->select("room_id,room_name");
        $this->db->from("venue_information");
        $this->db->where("IFNULL(del_flag, '') = ''");
        $this->db->where("room_type", $conditions['cat_id']);
        if(isset($conditions['room_id'])){
            // jd($conditions['room_id']);
            $this->db->where("room_id", $conditions['room_id']);
        }
        if(isset($conditions['class_room_type'])){
            $class_room_type_sql = '(';
            $type_count = '1';
            foreach($conditions['class_room_type'] as $class_room_type){
                if($type_count>1){
                    $class_room_type_sql.= "or room_name like '%{$class_room_type}%'";
                }else{
                    $class_room_type_sql.= "room_name like '%{$class_room_type}%'";
                }
                $type_count ++;
            }
            $class_room_type_sql .= ')';
            
        }else{
            $class_room_type_sql = "(room_name like '%B%' or room_name like '%C%' or room_name like '%E%' )";
        }
        $this->db->where($class_room_type_sql);
        $this->db->where("room_bel", "68000");
        $this->db->order_by('room_name asc');
        $query = $this->db->get();
        $data = $query->result_array();
        // jd($data);
        $days = ((strtotime($conditions['end_date'])-strtotime($conditions['start_date'])) / 86400) + 1;

        foreach($data as & $row){
            for($i=0; $i<$days; $i++){
                $select_day = date("Y-m-d",strtotime("+{$i} day",strtotime($conditions['start_date'])));

                $sql = "select * from
                    (
                    SELECT '1' AS BTYPE, A.ROOM_ID, A.BOOKING_DATE, B.CLASS_NAME, C.start_time AS FROM_TIME, C.end_time AS TO_TIME,A.Year, A.term as TERM, CASE WHEN B.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = B.WORKER) END CNAME FROM booking_place A
                    LEFT JOIN `require` B ON A.year = B.year AND A.CLASS_NO = B.CLASS_NO AND A.TERM = B.TERM 
                    LEFT JOIN reservation_time C ON A.BOOKING_PERIOD = C.ITEM_ID
                    WHERE A.ROOM_ID = '{$row['room_id']}' and A.BOOKING_DATE = '{$select_day}'  AND (B.is_cancel not in ('1') or B.is_cancel = '0' or B.is_cancel is null)
                    UNION ALL
                    SELECT CASE WHEN A1.APPI_ID IS NULL THEN '3' ELSE '2' END AS BTYPE,
                    A1.ROOM_ID, A1.USE_DATE AS BOOKING_DATE, IFNULL(C1.CLASS_NAME,C2.APP_REASON) AS CLASS_NAME,
                    IFNULL(IFNULL(B1.FROM_TIME,B2.FROM_TIME),B3.start_time) AS FROM_TIME,
                    IFNULL(IFNULL(B1.TO_TIME,B2.TO_TIME),B3.end_time) AS TO_TIME , A1.year,A1.term,
                    CASE WHEN C1.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = C1.WORKER) END CNAME
                    FROM room_use A1
                    LEFT JOIN periodtime B1 ON A1.USE_PERIOD = B1.ID AND A1.YEAR = B1.YEAR AND A1.CLASS_ID = B1.CLASS_NO AND A1.TERM = B1.TERM AND A1.ROOM_ID = B1.ROOM_ID AND A1.USE_ID = B1.COURSE_CODE AND A1.USE_DATE = B1.COURSE_DATE
                    LEFT JOIN periodtime B2 ON A1.USE_PERIOD = B2.ID AND B2.YEAR IS NULL AND B2.CLASS_NO IS NULL AND B2.TERM IS NULL AND B2.ROOM_ID IS NULL AND B2.COURSE_CODE IS NULL AND A1.APPI_ID IS NULL
                    LEFT JOIN reservation_time B3 ON A1.USE_PERIOD = B3.ITEM_ID
                    LEFT JOIN `require` C1 ON A1.YEAR = C1.YEAR AND A1.CLASS_ID = C1.CLASS_NO AND A1.TERM = C1.TERM 
                    LEFT JOIN appinfo C2 ON A1.APPI_ID = C2.APPI_ID
                    WHERE A1.ROOM_ID = '{$row['room_id']}' and A1.USE_DATE = '{$select_day}'  AND (C1.is_cancel not in ('1') or C1.is_cancel = '0' or C1.is_cancel is null)


                    GROUP BY  A1.year,A1.term, A1.class_id, A1.use_date, A1.use_period


                    ) select_booking
                    order by from_time";
                if(isset($conditions['red_class']) && $conditions['red_class']=='Y' && isset($conditions['only_time']) && $conditions['only_time']=='Y'){
                    $sql = "select * from
                        (
                        SELECT CASE WHEN A1.APPI_ID IS NULL THEN '3' ELSE '2' END AS BTYPE,
                        A1.ROOM_ID, A1.USE_DATE AS BOOKING_DATE, IFNULL(C1.CLASS_NAME,C2.APP_REASON) AS CLASS_NAME,
                        MIN(IFNULL(IFNULL(B1.FROM_TIME,B2.FROM_TIME),B3.start_time) ) AS FROM_TIME,
                        MAX(IFNULL(IFNULL(B1.TO_TIME,B2.TO_TIME),B3.end_time)) AS TO_TIME ,
                        A1.Year,A1.term as TERM,
                        CASE WHEN C1.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = C1.WORKER) END CNAME
                        FROM room_use A1
                        LEFT JOIN periodtime B1 ON A1.USE_PERIOD = B1.ID AND A1.YEAR = B1.YEAR AND A1.CLASS_ID = B1.CLASS_NO AND A1.TERM = B1.TERM AND A1.ROOM_ID = B1.ROOM_ID AND A1.USE_ID = B1.COURSE_CODE AND A1.USE_DATE = B1.COURSE_DATE
                        LEFT JOIN periodtime B2 ON A1.USE_PERIOD = B2.ID AND B2.YEAR IS NULL AND B2.CLASS_NO IS NULL AND B2.TERM IS NULL AND B2.ROOM_ID IS NULL AND B2.COURSE_CODE IS NULL AND A1.APPI_ID IS NULL
                        LEFT JOIN reservation_time B3 ON A1.USE_PERIOD = B3.ITEM_ID
                        LEFT JOIN `require` C1 ON A1.YEAR = C1.YEAR AND A1.CLASS_ID = C1.CLASS_NO AND A1.TERM = C1.TERM 
                        LEFT JOIN appinfo C2 ON A1.APPI_ID = C2.APPI_ID
                        WHERE A1.APPI_ID IS NULL and A1.ROOM_ID = '{$row['room_id']}' and A1.USE_DATE = '{$select_day}'  AND (C1.is_cancel not in ('1') or C1.is_cancel = '0' or C1.is_cancel is null)

                        GROUP BY  A1.year,A1.term, A1.class_id, A1.use_date, A1.use_period


                        ) select_booking 
                        order by from_time ";
                }elseif(isset($conditions['red_class']) && $conditions['red_class']=='Y'){
                    $sql = "select * from
                        (
                        SELECT CASE WHEN A1.APPI_ID IS NULL THEN '3' ELSE '2' END AS BTYPE,
                        A1.ROOM_ID, A1.USE_DATE AS BOOKING_DATE, IFNULL(C1.CLASS_NAME,C2.APP_REASON) AS CLASS_NAME,
                        IFNULL(IFNULL(B1.FROM_TIME,B2.FROM_TIME),B3.start_time) AS FROM_TIME,
                        IFNULL(IFNULL(B1.TO_TIME,B2.TO_TIME),B3.end_time) AS TO_TIME , A1.Year,A1.term as TERM,
                        CASE WHEN C1.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = C1.WORKER) END CNAME
                        FROM room_use A1
                        LEFT JOIN periodtime B1 ON A1.USE_PERIOD = B1.ID AND A1.YEAR = B1.YEAR AND A1.CLASS_ID = B1.CLASS_NO AND A1.TERM = B1.TERM AND A1.ROOM_ID = B1.ROOM_ID AND A1.USE_ID = B1.COURSE_CODE AND A1.USE_DATE = B1.COURSE_DATE
                        LEFT JOIN periodtime B2 ON A1.USE_PERIOD = B2.ID AND B2.YEAR IS NULL AND B2.CLASS_NO IS NULL AND B2.TERM IS NULL AND B2.ROOM_ID IS NULL AND B2.COURSE_CODE IS NULL AND A1.APPI_ID IS NULL
                        LEFT JOIN reservation_time B3 ON A1.USE_PERIOD = B3.ITEM_ID
                        LEFT JOIN `require` C1 ON A1.YEAR = C1.YEAR AND A1.CLASS_ID = C1.CLASS_NO AND A1.TERM = C1.TERM 
                        LEFT JOIN appinfo C2 ON A1.APPI_ID = C2.APPI_ID
                        WHERE A1.APPI_ID IS NULL and A1.ROOM_ID = '{$row['room_id']}' and A1.USE_DATE = '{$select_day}'  AND (C1.is_cancel not in ('1') or C1.is_cancel = '0' or C1.is_cancel is null)

                        GROUP BY  A1.year,A1.term, A1.class_id, A1.use_date, A1.use_period


                        ) select_booking
                        order by from_time";
                }elseif(isset($conditions['only_time']) && $conditions['only_time']=='Y'){
                    $sql = "select * from
                    (
                    SELECT '1' AS BTYPE, A.ROOM_ID, A.BOOKING_DATE, B.CLASS_NAME, C.start_time AS FROM_TIME, C.end_time AS TO_TIME,A.Year, A.term as TERM, CASE WHEN B.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = B.WORKER) END CNAME FROM booking_place A
                    LEFT JOIN `require` B ON A.year = B.year AND A.CLASS_NO = B.CLASS_NO AND A.TERM = B.TERM 
                    LEFT JOIN reservation_time C ON A.BOOKING_PERIOD = C.ITEM_ID
                    WHERE A.ROOM_ID = '{$row['room_id']}' and A.BOOKING_DATE = '{$select_day}'  AND (B.is_cancel not in ('1') or B.is_cancel = '0' or B.is_cancel is null)
                    UNION ALL
                    SELECT CASE WHEN A1.APPI_ID IS NULL THEN '3' ELSE '2' END AS BTYPE,
                    A1.ROOM_ID, A1.USE_DATE AS BOOKING_DATE, IFNULL(C1.CLASS_NAME,C2.APP_REASON) AS CLASS_NAME,
                    MIN(IFNULL(IFNULL(B1.FROM_TIME,B2.FROM_TIME),B3.start_time)) AS FROM_TIME,
                    MAX(IFNULL(IFNULL(B1.TO_TIME,B2.TO_TIME),B3.end_time)) AS TO_TIME , A1.year,A1.term,
                    CASE WHEN C1.WORKER IS NULL THEN '' ELSE (SELECT name FROM BS_user Z WHERE Z.idno = C1.WORKER) END CNAME
                    FROM room_use A1
                    LEFT JOIN periodtime B1 ON A1.USE_PERIOD = B1.ID AND A1.YEAR = B1.YEAR AND A1.CLASS_ID = B1.CLASS_NO AND A1.TERM = B1.TERM AND A1.ROOM_ID = B1.ROOM_ID AND A1.USE_ID = B1.COURSE_CODE AND A1.USE_DATE = B1.COURSE_DATE
                    LEFT JOIN periodtime B2 ON A1.USE_PERIOD = B2.ID AND B2.YEAR IS NULL AND B2.CLASS_NO IS NULL AND B2.TERM IS NULL AND B2.ROOM_ID IS NULL AND B2.COURSE_CODE IS NULL AND A1.APPI_ID IS NULL
                    LEFT JOIN reservation_time B3 ON A1.USE_PERIOD = B3.ITEM_ID
                    LEFT JOIN `require` C1 ON A1.YEAR = C1.YEAR AND A1.CLASS_ID = C1.CLASS_NO AND A1.TERM = C1.TERM 
                    LEFT JOIN appinfo C2 ON A1.APPI_ID = C2.APPI_ID
                    WHERE A1.ROOM_ID = '{$row['room_id']}' and A1.USE_DATE = '{$select_day}'  AND (C1.is_cancel not in ('1') or C1.is_cancel = '0' or C1.is_cancel is null)  

                    GROUP BY  A1.year,A1.term, A1.class_id, A1.use_date, A1.use_period

                    
                    ) select_booking
                    order by from_time";
                }

                $query = $this->db->query($sql);
                $data_booking = $query->result_array();
                $row[$select_day] = $data_booking;
            }
        }
        // jd($data);
        return $data;
    }


}