/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : testrbac

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-02-14 16:24:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '权限名称',
  `urls` varchar(1000) NOT NULL DEFAULT '' COMMENT 'json 数组',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：有效 0：无效',
  `updated_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新时间',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '插入时间',
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='权限详情表';

-- ----------------------------
-- Records of access
-- ----------------------------
INSERT INTO `access` VALUES ('4', '权限管理', '', '1', '1487041816', '1487041816', '0');
INSERT INTO `access` VALUES ('6', '添加权限', '[\"acces\\/add\"]', '1', '1487043039', '1487043039', '4');
INSERT INTO `access` VALUES ('7', '角色管理', '', '1', '1487043228', '1487043228', '0');
INSERT INTO `access` VALUES ('8', '添加角色', '[\"role\\/add\"]', '1', '1487047569', '1487043290', '7');
INSERT INTO `access` VALUES ('9', '编辑角色', '[\"role\\/edit\"]', '1', '1487047552', '1487044885', '7');
INSERT INTO `access` VALUES ('10', '删除角色', '[\"role\\/delete\"]', '1', '1487047539', '1487047376', '7');
INSERT INTO `access` VALUES ('11', '用户管理', '', '1', '1487047496', '1487047496', '0');
INSERT INTO `access` VALUES ('12', '添加用户', '[\"user\\/add\"]', '1', '1487047526', '1487047526', '11');
INSERT INTO `access` VALUES ('13', '权限列表', '[\"access\\/index\"]', '1', '1487059165', '1487059165', '4');
INSERT INTO `access` VALUES ('15', '添加操作', '[\"access\\/addaction\"]', '1', '1487059289', '1487059289', '4');
INSERT INTO `access` VALUES ('16', '修改权限', '[\"access\\/edit\"]', '1', '1487059334', '1487059334', '4');
INSERT INTO `access` VALUES ('17', '配置权限', '[\"role\\/addaccess\"]', '1', '1487059719', '1487059719', '4');
INSERT INTO `access` VALUES ('18', '角色列表', '[\"role\\/index\"]', '1', '1487060045', '1487060045', '7');
INSERT INTO `access` VALUES ('19', '用户列表', '[\"user\\/index\"]', '1', '1487060076', '1487060076', '11');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：有效 0：无效',
  `updated_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新时间',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '插入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('3', '销售', '1', '1487060273', '1487051920');
INSERT INTO `role` VALUES ('4', '运营', '1', '1487051947', '1487051947');
INSERT INTO `role` VALUES ('5', '普通管理员', '1', '1487060229', '1487060229');

-- ----------------------------
-- Table structure for role_access
-- ----------------------------
DROP TABLE IF EXISTS `role_access`;
CREATE TABLE `role_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `access_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限id',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='角色权限表';

-- ----------------------------
-- Records of role_access
-- ----------------------------
INSERT INTO `role_access` VALUES ('12', '3', '8', '1487059058');
INSERT INTO `role_access` VALUES ('14', '3', '10', '1487059058');
INSERT INTO `role_access` VALUES ('16', '4', '6', '1487059398');
INSERT INTO `role_access` VALUES ('17', '4', '13', '1487059398');
INSERT INTO `role_access` VALUES ('18', '4', '15', '1487059398');
INSERT INTO `role_access` VALUES ('19', '4', '16', '1487059398');
INSERT INTO `role_access` VALUES ('20', '3', '18', '1487060106');
INSERT INTO `role_access` VALUES ('21', '3', '19', '1487060458');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `email` varchar(30) NOT NULL DEFAULT '' COMMENT '邮箱',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是超级管理员 1表示是 0 表示不是',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：有效 0：无效',
  `updated_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新时间',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('11', '张三', 'zhangsan@163.com', '0', '1', '1487059424', '1487051846');
INSERT INTO `user` VALUES ('12', '李四', 'lisi@163.com', '0', '1', '1487059445', '1487059445');
INSERT INTO `user` VALUES ('13', 'admin', 'admin.163.com', '1', '1', '1487059445', '1487059445');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES ('24', '11', '3', '1487059425');
INSERT INTO `user_role` VALUES ('25', '12', '3', '1487059446');
