/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Version : 50623
 Source Host           : localhost
 Source Database       : CUHere

 Target Server Version : 50623
 File Encoding         : utf-8

 Date: 10/12/2015 18:50:39 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `QQList`
-- ----------------------------
DROP TABLE IF EXISTS `QQList`;
CREATE TABLE `QQList` (
  `QQ` int(20) NOT NULL,
  `beizhu` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`QQ`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tb_article`
-- ----------------------------
DROP TABLE IF EXISTS `tb_article`;
CREATE TABLE `tb_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pic` varchar(512) NOT NULL DEFAULT '' COMMENT '主题图片',
  `label` varchar(512) NOT NULL DEFAULT '' COMMENT '标签',
  `landmark` varchar(128) NOT NULL DEFAULT '' COMMENT '地标',
  `latitude` float NOT NULL DEFAULT '0' COMMENT '地标纬度',
  `longitude` float NOT NULL DEFAULT '0' COMMENT '地标经度',
  `describe` varchar(512) NOT NULL DEFAULT '' COMMENT '一句话',
  `supportCount` int(10) NOT NULL DEFAULT '0' COMMENT '帖子赞数量',
  `commentCount` int(10) NOT NULL DEFAULT '0' COMMENT '帖子评论数量',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `toreport` tinyint(1) NOT NULL DEFAULT '0' COMMENT '举报设置 1-举报帖子  默认为0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_comments`
-- ----------------------------
DROP TABLE IF EXISTS `tb_comments`;
CREATE TABLE `tb_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `aid` int(10) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `fid` int(10) NOT NULL DEFAULT '0' COMMENT '父用户ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `comments` varchar(512) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '评论时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_dialog`
-- ----------------------------
DROP TABLE IF EXISTS `tb_dialog`;
CREATE TABLE `tb_dialog` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `sendId` int(10) NOT NULL DEFAULT '0' COMMENT '发送私信用户ID',
  `receiveId` int(10) NOT NULL DEFAULT '0' COMMENT '接收私信用户ID',
  `newLetterNum` int(10) NOT NULL DEFAULT '0' COMMENT '新的私信数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_focus`
-- ----------------------------
DROP TABLE IF EXISTS `tb_focus`;
CREATE TABLE `tb_focus` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `fid` int(10) NOT NULL DEFAULT '0' COMMENT '被关注的用户ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '关注的用户ID',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '关注时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_letter`
-- ----------------------------
DROP TABLE IF EXISTS `tb_letter`;
CREATE TABLE `tb_letter` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `dialogId` int(10) NOT NULL DEFAULT '0' COMMENT '对话ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '发送私信用户ID',
  `content` varchar(512) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '私信时间',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否阅读',
  PRIMARY KEY (`id`),
  KEY `dialogId` (`dialogId`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_manager`
-- ----------------------------
DROP TABLE IF EXISTS `tb_manager`;
CREATE TABLE `tb_manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_support`
-- ----------------------------
DROP TABLE IF EXISTS `tb_support`;
CREATE TABLE `tb_support` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `aid` int(10) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '赞时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_test`
-- ----------------------------
DROP TABLE IF EXISTS `tb_test`;
CREATE TABLE `tb_test` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `pwd` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tb_testuser`
-- ----------------------------
DROP TABLE IF EXISTS `tb_testuser`;
CREATE TABLE `tb_testuser` (
  `id` int(11) NOT NULL,
  `username` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tb_toreport`
-- ----------------------------
DROP TABLE IF EXISTS `tb_toreport`;
CREATE TABLE `tb_toreport` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `aid` int(10) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '举报时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_user`
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `mobile` char(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `passwordTime` int(10) NOT NULL DEFAULT '0' COMMENT '密码修改时间',
  `brief` varchar(512) NOT NULL DEFAULT '' COMMENT '个人简介',
  `headicon` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像',
  `privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '隐私设置 1-关闭  默认打开',
  `focusNum` int(10) NOT NULL DEFAULT '0' COMMENT '用户关注数',
  `fansNum` int(10) NOT NULL DEFAULT '0' COMMENT '用户粉丝数',
  `newFocusNum` int(10) NOT NULL DEFAULT '0' COMMENT '新用户关注数',
  `newfansNum` int(10) NOT NULL DEFAULT '0' COMMENT '新用户粉丝数',
  `newsupportNum` int(10) NOT NULL DEFAULT '0' COMMENT '新赞数',
  `newcommentNum` int(10) NOT NULL DEFAULT '0' COMMENT '新评论数',
  `islock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '锁定用户 1-锁定',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=20066 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tb_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `tb_userinfo`;
CREATE TABLE `tb_userinfo` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一性、自增id',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '属于用户ID',
  `realname` char(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `website` varchar(512) NOT NULL DEFAULT '' COMMENT '个人网站',
  `constellation` char(32) NOT NULL DEFAULT '' COMMENT '星座',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0-男 1-女',
  `province` char(64) NOT NULL DEFAULT '' COMMENT '家乡 省',
  `city` char(64) NOT NULL DEFAULT '' COMMENT '家乡 市',
  `area` char(64) NOT NULL DEFAULT '' COMMENT '家乡 区',
  `citys` varchar(512) NOT NULL DEFAULT '' COMMENT '常出没城市',
  `professional` varchar(512) NOT NULL DEFAULT '' COMMENT '职业',
  `company` varchar(512) NOT NULL DEFAULT '' COMMENT '公司',
  `businessCircle` varchar(512) NOT NULL DEFAULT '' COMMENT '商圈',
  `officeBuildings` varchar(512) NOT NULL DEFAULT '' COMMENT '写字楼',
  `position` varchar(512) NOT NULL DEFAULT '' COMMENT '职位',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

SET FOREIGN_KEY_CHECKS = 1;
