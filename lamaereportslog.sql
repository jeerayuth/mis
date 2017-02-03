/*
Navicat MySQL Data Transfer

Source Server         : SERVER_MASTER
Source Server Version : 50532
Source Host           : 192.168.1.253:3306
Source Database       : hos

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2017-02-03 11:18:29
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `lamaereportslog`
-- ----------------------------
DROP TABLE IF EXISTS `lamaereportslog`;
CREATE TABLE `lamaereportslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(100) NOT NULL,
  `report` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `viewlog` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=tis620;

-- ----------------------------
-- Records of lamaereportslog
-- ----------------------------
INSERT INTO lamaereportslog VALUES ('1', 'medical', 'report9', 'natty', '2017-02-03 10:53:51');
INSERT INTO lamaereportslog VALUES ('2', 'medical', 'report10', 'natty', '2017-02-03 10:53:58');
INSERT INTO lamaereportslog VALUES ('3', 'medical', 'report9', 'natty', '2017-02-03 10:57:55');
INSERT INTO lamaereportslog VALUES ('4', 'medical', 'report10', 'natty', '2017-02-03 10:57:59');
INSERT INTO lamaereportslog VALUES ('5', 'medical', 'report9', 'natty', '2017-02-03 10:59:11');
INSERT INTO lamaereportslog VALUES ('6', 'medical', 'report10', 'natty', '2017-02-03 10:59:18');
