/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : db_liuhe

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-09-13 18:08:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_online`
-- ----------------------------
DROP TABLE IF EXISTS `t_online`;
CREATE TABLE `t_online` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Lid` varchar(30) NOT NULL,
  `UserId` int(11) NOT NULL,
  `UserName` varchar(20) NOT NULL,
  `Time` varchar(25) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_online
-- ----------------------------
INSERT INTO `t_online` VALUES ('45', '15052969488456', '4', 'test0105', '1505296948');
INSERT INTO `t_online` VALUES ('46', '15052972994263', '2', 'test0102', '1505297299');
INSERT INTO `t_online` VALUES ('44', '15052969406292', '1', 'test0101', '1505296940');
