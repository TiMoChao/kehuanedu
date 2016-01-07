<?php

class Kh_Model_Category extends Zend_Db_Table_Abstract
{
    protected $_name = "core_categories";

    public function getCategories($where = array(),$order = null){
        $select = $this->select();
        if(count($where) > 0){
            foreach($where as $key=>$value){
                $select->where($key.'=?',$value);
            }
        }
        if($order != null){
            $select->order($order);
        }
        $results = $this->fetchAll($select);
        if($results){
            return $results;
        }
        else{
            return null;
        }
    }
}

