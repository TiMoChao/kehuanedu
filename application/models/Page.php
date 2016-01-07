<?php

class Kh_Model_Page extends Zend_Db_Table_Abstract
{
    protected $_name = 'core_pages';


    public function getPage($where = null){
        if(is_numeric($where)){
            $row  = $this->find($where)->current();
        }
        if(is_array($where) && count($where) > 0){
            $select = $this->select();
            foreach ($where as $key=>$value) {
                $select->where($key. ' = ?', $value);
            }
            $row = $this->fetchRow($select);
        }
        if($row){
            return $row;
        }
        else{
            return null;
        }
    }

    public function getPages($where = array(),$order = null,$limit = null){
        $select = $this->select();

        if(is_string($where)){
            $select->where($where);
        }
        if(count($where) > 0 && is_array($where)){
            foreach ($where as $key=>$value) {
                $select->where($key. ' = ?',$value);
            }
        }
        if($order != null){
            $select->order($order);
        }
        if($limit != null){
            $select->limit($limit);
        }
        $results = $this->fetchAll($select);
        if($results->count() > 0){
            return $results;
        }
        else{
            return null;
        }
    }

}

