<?php

use Curl\Curl;

/**
 * 微信接口工具类
 * 
 * @todo 封装创建WeChatUser方法
 * 
 * @link http://qydev.weixin.qq.com/wiki/index.php
 * @author dzlin <zhanglindeng@163.com>
 */
class WeChat
{

    const CREATE_USER = 'https://qyapi.weixin.qq.com/cgi-bin/user/create';
    const UPDATE_USER = 'https://qyapi.weixin.qq.com/cgi-bin/user/update';
    const DELETE_USER = 'https://qyapi.weixin.qq.com/cgi-bin/user/delete';
    const BATCH_DELETE_USER = 'https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete';
    const GET_USER = 'https://qyapi.weixin.qq.com/cgi-bin/user/get';
    const USER_SIMPLE_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/user/simplelist';
    const USER_SIMPLE_DEATIL = 'https://qyapi.weixin.qq.com/cgi-bin/user/list';
    const CREATE_DEPARTMENT = 'https://qyapi.weixin.qq.com/cgi-bin/department/create';
    const UPDATE_DEPARTMENT = 'https://qyapi.weixin.qq.com/cgi-bin/department/update';
    const DELETE_DEPARTMENT = 'https://qyapi.weixin.qq.com/cgi-bin/department/delete';
    const DEPARTMENT_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/department/list';

    private $_accessToken;
    private $_errmsg;

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
    }

    /**
     * 获取部门列表
     * @param integer $id 部门id。获取指定部门及其下的子部门
     * @return array
     */
    public function getDepartments($id = 1)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'id' => (integer) $id,
        );
        $response = $this->curl(self::DEPARTMENT_LIST, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            if ($response->errcode == 0) {
                return $response->department;
            }
        }
    }

    /**
     * 删除成员
     * @param string $id 部门id。不能删除根部门；不能删除含有子部门、成员的部门
     * @return boolean
     */
    public function deleteDepartment($id)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'id' => (integer) $id,
        );
        $response = $this->curl(self::DELETE_DEPARTMENT, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    /**
     * 更新部门
     * @param integer $id 部门id
     * @param string $name 更新的部门名称。长度限制为1~64个字节，字符不能包括\:*?"<>｜
     * @param integer $parentid 父亲部门id。根部门id为1
     * @param integer $order 在父部门中的次序值。order值小的排序靠前。
     */
    public function updateDepartment($id, $name = null, $parentid = null, $order = null)
    {
        $data['id'] = (integer) $id;
        if ($name !== null) {
            $data['name'] = $name;
        }
        if ($parentid !== null) {
            $data['parentid'] = (integer) $parentid;
        }
        if ($order !== null) {
            $data['order'] = (integer) $order;
        }
        $url = self::UPDATE_DEPARTMENT . '?access_token=' . $this->_accessToken;
        $response = $this->curl($url, $data, true);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    /**
     * 创建部门
     * @param string $name 部门名称。长度限制为1~64个字节，字符不能包括\:*?"<>｜
     * @param integer $parentid 父亲部门id。根部门id为1
     * @param integer $order 在父部门中的次序值。order值小的排序靠前
     * @param integer $id 部门id，整型。指定时必须大于1，不指定时则自动生成
     * @return integer 创建成功返回部门id
     */
    public function createDepartment($name, $parentid, $order = 1, $id = null)
    {
        $data = array(
            'name' => $name,
            'parentid' => (integer) $parentid,
            'order' => (integer) $order,
        );
        if ($id !== null) {
            $data['id'] = (integer) $id;
        }
        $url = self::CREATE_DEPARTMENT . '?access_token=' . $this->_accessToken;
        $response = $this->curl($url, $data, true);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            if ($response->errcode == 0) {
                return (integer) $response->id;
            }
        }
    }

    /**
     * 获取部门成员(详情)
     * @param string $departmentId 获取的部门id
     * @param boolean $fetchChild 是否递归获取子部门下面的成员，默认false
     * @param integer $status 0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，
     *                <p> 4获取未关注成员列表。status可叠加，默认为0
     * @return array
     */
    public function getUsersDetail($departmentId, $fetchChild = false, $status = 0)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'department_id' => (integer) $departmentId,
            'fetch_child' => (integer) $fetchChild,
            'status' => (integer) $status,
        );
        $response = $this->curl(self::USER_SIMPLE_DEATIL, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            if ($response->errcode == 0) {
                $users = array();
                foreach ($response->userlist as $user) {
                    $userid = isset($user->userid) ? $user->userid : '';
                    $name = isset($user->name) ? $user->name : '';
                    $department = isset($user->department) ? $user->department : '';
                    $wechatUser = new WeChatUser($userid, $name, null, $department);
                    if (isset($user->mobile)) {
                        $wechatUser->setMobile($user->mobile);
                    }
                    if (isset($user->position)) {
                        $wechatUser->setPosition($user->position);
                    }
                    if (isset($user->gender)) {
                        $wechatUser->setGender($user->gender);
                    }
                    if (isset($user->email)) {
                        $wechatUser->setEmail($user->email);
                    }
                    if (isset($user->weixinid)) {
                        $wechatUser->setWeixinid($user->weixinid);
                    }
                    if (isset($user->avatar)) {
                        $wechatUser->setAvatar($user->avatar);
                    }
                    if (isset($user->status)) {
                        $wechatUser->setStatus($user->status);
                    }
                    if (isset($user->extattr->attrs)) {
                        $wechatUser->setExtattr($user->extattr->attrs);
                    }
                    $users[] = $wechatUser;
                    unset($wechatUser);
                }
                return $users;
            }
        }
    }

    /**
     * 获取部门成员
     * @param string $departmentId 获取的部门id
     * @param boolean $fetchChild 是否递归获取子部门下面的成员，默认false
     * @param integer $status 0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，
     *                <p> 4获取未关注成员列表。status可叠加，默认为0
     * @return array
     */
    public function getUsersSimple($departmentId, $fetchChild = false, $status = 0)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'department_id' => (integer) $departmentId,
            'fetch_child' => (integer) $fetchChild,
            'status' => (integer) $status,
        );
        $response = $this->curl(self::USER_SIMPLE_LIST, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            if ($response->errcode == 0) {
                $users = array();
                foreach ($response->userlist as $user) {
                    $userid = isset($user->userid) ? $user->userid : '';
                    $name = isset($user->name) ? $user->name : '';
                    $department = isset($user->department) ? $user->department : '';
                    $wechatUser = new WeChatUser($userid, $name, null, $department);
                    $users[] = $wechatUser;
                    unset($wechatUser);
                }
                return $users;
            }
        }
    }

    /**
     * 获取成员
     * @param string $userid
     * @return WeChatUser|null
     */
    public function getUser($userid)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'userid' => $userid,
        );
        $response = $this->curl(self::GET_USER, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            if ($response->errcode == 0) {
                $userid = $response->userid;
                $name = isset($response->name) ? $response->name : '';
                $mobile = isset($response->mobile) ? $response->mobile : '';
                $department = isset($response->department) ? $response->department : '';
                $user = new WeChatUser($userid, $name, $mobile, $department);
                if (isset($response->position)) {
                    $user->setPosition($response->position);
                }
                if (isset($response->gender)) {
                    $user->setGender($response->gender);
                }
                if (isset($response->email)) {
                    $user->setEmail($response->email);
                }
                if (isset($response->weixinid)) {
                    $user->setWeixinid($response->weixinid);
                }
                if (isset($response->avatar)) {
                    $user->setAvatar($response->avatar);
                }
                if (isset($response->status)) {
                    $user->setStatus($response->status);
                }
                if (isset($response->extattr->attrs)) {
                    $user->setExtattr($response->extattr->attrs);
                }
                return $user;
            }
        }
    }

    /**
     * 批量删除成员
     * @param array $userids
     * @return boolean
     */
    public function batchDeleteUser(array $userids)
    {
        $data = array(
            'useridlist' => $userids,
        );
        $url = self::BATCH_DELETE_USER . '?access_token=' . $this->_accessToken;
        $response = $this->curl($url, $data, true);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    /**
     * 删除成员
     * @param string $userid
     * @return boolean
     */
    public function deleteUser($userid)
    {
        $data = array(
            'access_token' => $this->_accessToken,
            'userid' => $userid,
        );
        $response = $this->curl(self::DELETE_USER, $data);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    /**
     * 更新成员
     * @param WeChatUser $user
     * @param boolean $enable false禁用成员
     * @return boolean
     */
    public function updateUser(WeChatUser $user, $enable = true)
    {
        $data['userid'] = $user->getUserid();
        $data['enable'] = (integer) $enable;
        if (!empty($user->getName())) {
            $data['name'] = $user->getName();
        }
        if (!empty($user->getDepartment())) {
            $data['name'] = $user->getDepartment();
        }
        if (!empty($user->getPosition())) {
            $data['position'] = $user->getPosition();
        }
        if (!empty($user->getMobile())) {
            $data['mobile'] = $user->getMobile();
        }
        if (!empty($user->getGender())) {
            $data['gender'] = $user->getGender();
        }
        if (!empty($user->getEmail())) {
            $data['email'] = $user->getEmail();
        }
        if (!empty($user->getWeixinid())) {
            $data['weixinid'] = $user->getWeixinid();
        }
        if (!empty($user->getAvatarMediaid())) {
            $data['avatar_mediaid'] = $user->getAvatarMediaid();
        }
        if (!empty($user->getExtattr())) {
            $data['extattr'] = $user->getExtattr();
        }
        $url = self::UPDATE_USER . '?access_token=' . $this->_accessToken;
        $response = $this->curl($url, $data, true);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    /**
     * 创建微信用户
     * @param WeChatUser $user
     * @return boolean
     */
    public function createUser(WeChatUser $user)
    {
        $data = array(
            "userid" => $user->getUserid(),
            "name" => $user->getName(),
            "department" => $user->getDepartment(),
            "position" => $user->getPosition(),
            "mobile" => $user->getMobile(),
            "gender" => $user->getGender(),
            "email" => $user->getEmail(),
            "weixinid" => $user->getWeixinid(),
            "avatar_mediaid" => $user->getAvatarMediaid(),
            'extattr' => $user->getExtattr(),
        );
        $url = self::CREATE_USER . '?access_token=' . $this->_accessToken;
        $response = $this->curl($url, $data, true);
        if ($response) {
            $this->_errmsg = $response->errmsg;
            return $response->errcode == 0;
        }
        return false;
    }

    private function curl($url, array $data = array(), $post = false)
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        if ($post) {
            $data = json_encode($data);
            $curl->post($url, $data);
        } else {
            $curl->get($url, $data);
        }
        if ($curl->error) {
            $this->_errmsg = $curl->errorCode . ': ' . $curl->errorMessage;
        } else {
            return $curl->response;
        }
    }

    public function getErrmsg()
    {
        return $this->_errmsg;
    }

    private function wechatUser()
    {
        
    }

}
