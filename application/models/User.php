<?php

class Kh_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'core_users';

    /**
     * 创建用户
     * 接收到用户提交的表单数据，将其写入到数据表
     * 接受数据应该是数组，必须保证其键值名和数据表中的字段相对应
     *
     */
    public function createUser($userData){
        $row = $this->createRow();
        if(count($userData) > 0 && is_array($userData)){
            foreach ($userData as $key=>$value) {
                switch($key){
                    case 'password':
                        $row->$key = md5($value);
                        break;
                    case 'password2':
                        break;
                    default:
                        $row->$key = $value;
                }
            }
            $row->role = 'user';
            $row->status = 1;
            $row->time_reg = time();
            $row->save();
            return $row->id;
        }
        else{
           return null;
        }
    }


    /**
     * @param int $id
     * 获取用户
     * 根据所传参数从数据库中查询用户
     */
    public function getUser($where){
        if(is_numeric($where)){
            $row = $this->find($where)->current();
        }
        if(is_array($where) && count($where) > 0){
            $select = $this->select();
            foreach ($where as $key=>$value) {
                $select->where($key.' = ?',$value);
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

    /**
     * 验证用户是否同名
     */
    public function validUser($username){
        $select = $this->select();
        $select->where("username = ?",$username);
        $result = $this->fetchRow($select);
        if($result){
            return $result->id;
        }
        else{
            return FALSE;
        }
    }

    /**
     * 登录时间
     * 记录用户最后一次登录的时间
     */
    public function loginTime($id){
        $row = $this->find($id)->current();
        if($row){
            $row->time_last = time();
            $row->save();
        }
        else{
            return FALSE;
        }
    }

    /**
     * @param $id
     * @param $data
     * @param null $password
     * @return mixed
     * @throws Zend_Db_Table_Exception
     * 编辑用户信息
     */
    public function updateUser($id,$data,$password = null){
        $row = $this->find($id)->current();
        if($row){
            if(count($data) > 0){
                foreach ($data as $key=>$value) {
                    //如果原来有头像从新赋值
                    if($row->avatar){
                        $oldAvatar = $row->avatar;
                    }
                    $row->$key = $value;
                    //如果有上传头像额
                    if($key == 'avatar' && $value != null){
                        unlink('upload/'.$oldAvatar);//删除原来的头像
                        $row->avatar = date("Y-m")."/avatar/".$value;
                    }
                }
            }
            if($password){
                $row->password = md5($password);
            }
            return $row->save();
            return $row->id;
        }
        else{
            return fasle;
        }
    }

    public function changePassword($id,$password){
        $row = $this->find($id)->current();
        if($row){
            $row->password = md5($password);
            $row->save();
        }
        else{
            throw new Zend_Exception('用户不存在，更新密码未成功');
        }
    }
}

