<?php

use Curl\Curl;

/**
 * 微信接口工具类
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

    private $_accessToken;
    private $_errmsg;

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
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
        if ($response && $response->errcode == 0) {
            $this->_errmsg = $response->errmsg;
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

}
