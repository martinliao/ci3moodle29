<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dcsdindex_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkNotSendPay()
    {
        $today = date("Y-m-d", strtotime("-2 day"));
        $str = '';

        $sql = sprintf("SELECT
                            T.year,
                            T.class_no,
                            T.term,
                            T.class_name,
                            R.is_cancel,
                            T.use_date,
                            COUNT(1) AS cnt
                        FROM
                            hour_traffic_tax T 
                        LEFT JOIN `require` R ON R.seq_no = T.seq and R.is_cancel is null
                        WHERE
                            T .status = '待確認'
                        AND T .use_date <= '%s'
                        AND T .year >= 106
                        GROUP BY
                            T.year,
                            T.class_no,
                            T.term,
                            T.class_name,
                            R.is_cancel,
                            T.use_date", $today);

        $query = $this->db->query($sql);
        $appseqArr = $query->result_array();

        for ($i = 0; $i < count($appseqArr); $i++) {
            $sql = sprintf(
                "SELECT
                                b.name,
                                b.username
                            FROM
                                `require` A
                            JOIN BS_user b ON A .worker = b.idno
                            WHERE
                                A .class_no = '%s'
                            AND A .year = %s
                            AND A .term = %s",
                $appseqArr[$i]['class_no'],
                $appseqArr[$i]['year'],
                $appseqArr[$i]['term']
            );

            $query = $this->db->query($sql);
            $rows = $query->result_array();

            $appseqArr[$i]['account'] = $rows[0]['username'];
            $appseqArr[$i]['name'] = $rows[0]['name'];
        }



        return $appseqArr;
    }
}
