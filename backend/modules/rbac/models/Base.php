<?php

namespace rbac\models;

use Yii;
use yii\db\ActiveRecord;

class Base extends ActiveRecord
{

    private $status;
    private $yes_or_no;

    public function init()
    {
        $this->status = [
            0 => '禁用',
            1 => '正常',
        ];
        $this->yes_or_no=[
             0=>'否',
            '1'=>'是'
        ];
    }

    /**
     * 状态
     * @param $status
     * @return mixed
     */
    public function getStatusText($status)
    {
        $statusList = $this->status;
        return $statusList[$status];
    }

    public function getYesOrNoText($yes=0)
    {
        $yes_or_no=$this->yes_or_no;
        return $yes_or_no[$yes];
    }


}