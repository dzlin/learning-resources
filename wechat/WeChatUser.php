<?php

/**
 * Description of WeChatUser
 *
 * @author dzlin <zhanglindeng@163.com>
 */
class WeChatUser
{

    private $_userid;
    private $_name;
    private $_department = array();
    private $_position;
    private $_mobile;
    private $_gender;
    private $_email;
    private $_weixinid;
    private $_avatarMediaid;
    private $_avatar;
    private $_status = 0;
    private $_extattr = array();

    public function __construct($userid, $name, $mobile, $department = array(1))
    {
        $this->_userid = $userid;
        $this->_name = $name;
        $this->_mobile = $mobile;
        $this->_department = $department;
    }

    public function getAvatar()
    {
        return $this->_avatar;
    }

    public function setAvatar($avatar)
    {
        $this->_avatar = $avatar;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getUserid()
    {
        return $this->_userid;
    }

    public function setUserid($userid)
    {
        $this->_userid = $userid;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getDepartment()
    {
        return $this->_department;
    }

    public function setDepartment(array $department)
    {
        $this->_department = $department;
    }

    public function getMobile()
    {
        return $this->_mobile;
    }

    public function setMobile($mobile)
    {
        $this->_mobile = $mobile;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getPosition()
    {
        return $this->_position;
    }

    public function setPosition($position)
    {
        $this->_position = $position;
    }

    public function getGender()
    {
        return $this->_gender;
    }

    public function setGender($gender)
    {
        $this->_gender = (integer) $gender;
    }

    public function getWeixinid()
    {
        return $this->_weixinid;
    }

    public function setWeixinid($weixinid)
    {
        $this->_weixinid = $weixinid;
    }

    public function getAvatarMediaid()
    {
        return $this->_avatarMediaid;
    }

    public function setAvatarMediaid($avatarMediaid)
    {
        $this->_avatarMediaid = $avatarMediaid;
    }

    public function getExtattr()
    {
        return $this->_extattr;
    }

    public function setExtattr(array $extattr)
    {
        $this->_extattr = $extattr;
    }

}
