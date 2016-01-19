<?php

header('Content-type:text/html;charset=utf-8');

require 'vendor/autoload.php';
require 'WeChat.php';
require 'WeChatUser.php';

$accessToken = 'hmdqROw64hmGGLL2BH05FPrdMdagNZ3Wfer61GVFJhwY1VpXcSSfQYnyYVSQtIf9vc3pC7wLo3lXmYy59KFhUw';

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
$userid = '18070510342';
$wechat = new WeChat($accessToken);
$user = $wechat->getUser($userid);
echo '<pre>';
var_dump($user);
