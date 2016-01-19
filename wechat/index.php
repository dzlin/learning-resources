<?php

header('Content-type:text/html;charset=utf-8');

require 'vendor/autoload.php';
require 'WeChat.php';
require 'WeChatUser.php';

$accessToken = 'your access_token';

/*
  $userid = 'gzdzl001';
  $name = 'gzdzl001';
  $mobile = '18070510301';
  $user = new WeChatUser($userid, $name, $mobile);

  // create
  $wechat = new WeChat($accessToken);
  $bool = $wechat->createUser($user);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'Create success';
  }
 */

/*
  $userid = 'gzdzl001';
  $name = 'gzdzl001_update';
  $mobile = '18070510302';
  // update
  $user = new WeChatUser($userid, $name, $mobile);
  $wechat = new WeChat($accessToken);
  $bool = $wechat->updateUser($user);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'Update success.';
  }
 */

/*
  $userid = 'gzdzl001';
  $wechat = new WeChat($accessToken);
  $bool = $wechat->deleteUser($userid);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'Delete success.';
  }
 */

/*
  $userids = array('zhangsan1', 'zhangsan2');
  $wechat = new WeChat($accessToken);
  $bool = $wechat->batchDeleteUser($userids);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'Batch delete success.';
  }
 */

/*
  $userid = '18070510342';
  $wechat = new WeChat($accessToken);
  $user = $wechat->getUser($userid);
  echo '<pre>';
  var_dump($user);
 */

/*
  echo '<pre>';
  $wechat = new WeChat($accessToken);
  $users = $wechat->getUsersSimple(1);

  var_dump($users);
 */

/*
  echo '<pre>';
  $wechat = new WeChat($accessToken);
  $users = $wechat->getUsersDetail('3');

  var_dump($users);

 */

/*
  $wechat = new WeChat($accessToken);
  $depeid = $wechat->createDepartment('deparment_name_sub_2', 17, 1);
  if ($depeid <= 0) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'Create department success.' . ' id => ' . $depeid;
  }
 */

/*
  $wechat = new WeChat($accessToken);
  $bool = $wechat->updateDepartment(19, 'department_update', 1);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'update department success.';
  }
 */

/*
  $wechat = new WeChat($accessToken);
  $bool = $wechat->deleteDepartment(17);
  if (!$bool) {
  echo 'Error: ' . $wechat->getErrmsg();
  } else {
  echo 'delete department success.';
  }
 */
echo '<pre>';
$wechat = new WeChat($accessToken);
$departments = $wechat->getDepartments(17);
var_dump($departments);
