/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : psi

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 27/08/2021 14:43:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hr_attachment
-- ----------------------------
DROP TABLE IF EXISTS `hr_attachment`;
CREATE TABLE `hr_attachment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单id',
  `category` tinyint(4) NOT NULL DEFAULT 1 COMMENT '附件或图片（1附件 2图片）',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `key` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '链接地址',
  `size` int(11) DEFAULT NULL COMMENT '文件大小',
  `extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '扩展名',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `order_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '订单类型: 1采购单 2销售单',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '附件' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_attachment
-- ----------------------------
INSERT INTO `hr_attachment` VALUES (1, '2', 1, 'd3a21cbb9186981b45fe82379d0492b6', 'attachment/d3a21cbb9186981b45fe82379d0492b6', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/d3a21cbb9186981b45fe82379d0492b6', 766588, 'zip', 2, 1, 1622612617, 0);
INSERT INTO `hr_attachment` VALUES (2, '8', 1, '861d2c93dacd406b2bdec0903ea17aac', 'attachment/861d2c93dacd406b2bdec0903ea17aac', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/861d2c93dacd406b2bdec0903ea17aac', 766588, 'zip', 2, 1, 1622613812, 0);
INSERT INTO `hr_attachment` VALUES (3, '4', 1, '13626a00fdb90bbcae5a81c7693c897a', 'attachment/13626a00fdb90bbcae5a81c7693c897a', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/13626a00fdb90bbcae5a81c7693c897a', 766588, 'zip', 2, 2, 1622617370, 0);
INSERT INTO `hr_attachment` VALUES (4, '15', 1, '08573011c1fc8091d4d4ddbf7622b4ba', 'attachment/08573011c1fc8091d4d4ddbf7622b4ba', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/08573011c1fc8091d4d4ddbf7622b4ba', 766588, 'zip', 2, 1, 1622617508, 0);
INSERT INTO `hr_attachment` VALUES (6, '11', 1, 'd0234ec485af5ff8fc8dff754194bb25', 'attachment/d0234ec485af5ff8fc8dff754194bb25', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/d0234ec485af5ff8fc8dff754194bb25', 766588, 'zip', 2, 2, 1622701453, 0);
INSERT INTO `hr_attachment` VALUES (7, '26', 1, '8c7093b688cb3f96', 'attachment/8c7093b688cb3f96', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/8c7093b688cb3f96', 766588, 'zip', 2, 1, 1622702653, 0);
INSERT INTO `hr_attachment` VALUES (8, '17', 1, '447a457a451516b3', 'attachment/447a457a451516b3', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/447a457a451516b3', 766588, 'zip', 2, 2, 1622704258, 0);
INSERT INTO `hr_attachment` VALUES (9, '36', 1, '1f8b9bb32e3a3be8', 'attachment/1f8b9bb32e3a3be8', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/1f8b9bb32e3a3be8', 766588, 'zip', 2, 1, 1622772298, 0);
INSERT INTO `hr_attachment` VALUES (10, '25', 1, '55887260ceb3be40', 'attachment/55887260ceb3be40', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/55887260ceb3be40', 766588, 'zip', 2, 2, 1622772444, 0);
INSERT INTO `hr_attachment` VALUES (11, '67', 1, '184c44a477f419ab', 'attachment/184c44a477f419ab', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/184c44a477f419ab', 36346, 'png', 45, 1, 1623736895, 0);
INSERT INTO `hr_attachment` VALUES (12, '70', 1, 'a3c9d9aa2174c3ef', 'attachment/a3c9d9aa2174c3ef', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/a3c9d9aa2174c3ef', 80400, 'jpg', 47, 1, 1623808422, 0);
INSERT INTO `hr_attachment` VALUES (13, '77', 1, 'd4d43b2161555329', 'attachment/d4d43b2161555329', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/d4d43b2161555329', 87182, 'jpg', 47, 1, 1624255048, 0);
INSERT INTO `hr_attachment` VALUES (14, '154', 1, '9f50d3910f02f064', 'attachment/9f50d3910f02f064', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/9f50d3910f02f064', 36346, 'png', 54, 1, 1624605441, 0);
INSERT INTO `hr_attachment` VALUES (15, '157', 1, '7af51678a787925a', 'attachment/7af51678a787925a', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/attachment/7af51678a787925a', 22861, 'jpg', 44, 1, 1624703856, 0);

-- ----------------------------
-- Table structure for hr_category
-- ----------------------------
DROP TABLE IF EXISTS `hr_category`;
CREATE TABLE `hr_category`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_pid` int(11) DEFAULT 0 COMMENT 'PID',
  `category_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '类别名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `lock_version` int(11) DEFAULT 0 COMMENT '锁版本',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '软删除时间',
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_category
-- ----------------------------
INSERT INTO `hr_category` VALUES (1, 0, '衣服', 100, 2, 1624846092, 1624846092, 1, 0);
INSERT INTO `hr_category` VALUES (2, 0, '短裤', 100, 0, 1624846128, 1624846128, 1, 0);
INSERT INTO `hr_category` VALUES (3, 0, '衣服', 100, 0, 1624846142, 1624846142, 2, 0);
INSERT INTO `hr_category` VALUES (4, 0, '衬衣', 100, 0, 1624846146, 1624846146, 1, 0);
INSERT INTO `hr_category` VALUES (5, 0, '羽绒服', 100, 0, 1624846152, 1624846152, 1, 0);
INSERT INTO `hr_category` VALUES (6, 0, '衣服', 100, 0, 1624848912, 1624848912, 3, 0);
INSERT INTO `hr_category` VALUES (7, 0, '黑色', 100, 0, 1624862176, 1624862176, 1, 0);
INSERT INTO `hr_category` VALUES (8, 0, '衣服', 100, 0, 1624929816, 1624929816, 4, 0);
INSERT INTO `hr_category` VALUES (9, 0, '衣服', 100, 0, 1624936884, 1624936884, 5, 0);
INSERT INTO `hr_category` VALUES (10, 0, '衣服', 100, 0, 1625107837, 1625107837, 6, 0);
INSERT INTO `hr_category` VALUES (11, 0, '衣服', 100, 0, 1625110509, 1625110509, 7, 0);
INSERT INTO `hr_category` VALUES (12, 0, '衣服', 100, 0, 1629878732, 1629878732, 8, 0);
INSERT INTO `hr_category` VALUES (15, 0, '秋裤', 100, 0, 1629882908, 1629882908, 8, 0);

-- ----------------------------
-- Table structure for hr_client_account
-- ----------------------------
DROP TABLE IF EXISTS `hr_client_account`;
CREATE TABLE `hr_client_account`  (
  `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `client_id` int(10) UNSIGNED DEFAULT NULL COMMENT '客户ID',
  `account_money` decimal(10, 2) DEFAULT 0.00 COMMENT '账户金额',
  `account_fmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '冻结金额',
  `account_ptmoney` decimal(12, 2) DEFAULT 0.00 COMMENT '采购总额',
  `account_potmoney` decimal(12, 2) DEFAULT 0.00 COMMENT '采购原价总额',
  `account_number` int(11) DEFAULT 0 COMMENT '采购总数',
  `account_last_money` decimal(10, 2) DEFAULT 0.00 COMMENT '尾单金额',
  `account_last_time` int(11) DEFAULT 0 COMMENT '尾单日期',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`account_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '客户账号' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_client_account
-- ----------------------------
INSERT INTO `hr_client_account` VALUES (1, 1, -8062.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1624846092, 1624846092, 5, 1, NULL);
INSERT INTO `hr_client_account` VALUES (2, 2, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1624846142, 1624846142, 0, 2, NULL);
INSERT INTO `hr_client_account` VALUES (3, 3, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1624848912, 1624848912, 0, 3, NULL);
INSERT INTO `hr_client_account` VALUES (4, 4, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1624929816, 1624929816, 0, 4, NULL);
INSERT INTO `hr_client_account` VALUES (5, 5, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1624936884, 1624936884, 0, 5, NULL);
INSERT INTO `hr_client_account` VALUES (6, 6, -760.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1625107837, 1625107837, 2, 6, NULL);
INSERT INTO `hr_client_account` VALUES (7, 7, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1625110509, 1625110509, 0, 7, NULL);
INSERT INTO `hr_client_account` VALUES (8, 8, -540.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1629878732, 1629878732, 1, 8, NULL);
INSERT INTO `hr_client_account` VALUES (9, 9, 10000.00, 0.00, 0.00, 0.00, 0, 0.00, 0, 1629967237, 0, 0, 8, NULL);

-- ----------------------------
-- Table structure for hr_client_base
-- ----------------------------
DROP TABLE IF EXISTS `hr_client_base`;
CREATE TABLE `hr_client_base`  (
  `client_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '客户编号',
  `client_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客户名称',
  `client_category_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客户类别',
  `client_discount` tinyint(4) DEFAULT 100 COMMENT '默认折扣',
  `client_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客户电话',
  `client_email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '电子邮件',
  `client_address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客户地址',
  `client_story` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客户描述',
  `client_status` tinyint(4) DEFAULT 1 COMMENT '客户状态(-1禁用 1启用)',
  `create_time` int(11) DEFAULT 0 COMMENT '建立时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`client_id`) USING BTREE,
  UNIQUE INDEX `client_code`(`client_code`) USING BTREE,
  INDEX `client_phone`(`client_phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '客户' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_client_base
-- ----------------------------
INSERT INTO `hr_client_base` VALUES (1, 'M210628100812700', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1624846092, 1624846092, 0, 1, NULL);
INSERT INTO `hr_client_base` VALUES (2, 'M210628100902551', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1624846142, 1624846142, 0, 2, NULL);
INSERT INTO `hr_client_base` VALUES (3, 'M210628105512565', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1624848912, 1624848912, 0, 3, NULL);
INSERT INTO `hr_client_base` VALUES (4, 'M210629092336689', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1624929816, 1624929816, 0, 4, NULL);
INSERT INTO `hr_client_base` VALUES (5, 'M210629112124439', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1624936884, 1624936884, 0, 5, NULL);
INSERT INTO `hr_client_base` VALUES (6, 'M210701105037936', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1625107837, 1625107837, 0, 6, NULL);
INSERT INTO `hr_client_base` VALUES (7, 'M210701113509939', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1625110509, 1625110509, 0, 7, NULL);
INSERT INTO `hr_client_base` VALUES (8, 'M210825160532978', '默认客户', NULL, 100, '18888888888', NULL, NULL, NULL, 1, 1629878732, 1629878732, 0, 8, NULL);
INSERT INTO `hr_client_base` VALUES (9, 'FZ00105523', '基多拉', NULL, 100, '15555205520', '121212@qq.com', '朝外101号', '心宽体胖大肥膘', 1, 1629967237, 0, 0, 8, NULL);

-- ----------------------------
-- Table structure for hr_color
-- ----------------------------
DROP TABLE IF EXISTS `hr_color`;
CREATE TABLE `hr_color`  (
  `color_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `color_group` int(11) DEFAULT 0 COMMENT '色组',
  `color_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '类别名称',
  `color_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '色码编码',
  `sort` int(11) DEFAULT 100 COMMENT '排序',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`color_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_color
-- ----------------------------
INSERT INTO `hr_color` VALUES (1, 0, '黑色', 'black1', 100, 0, 1624846092, 1624846092, 1);
INSERT INTO `hr_color` VALUES (2, 0, '红色', 'red1', 100, 0, 1624846092, 1624846092, 1);
INSERT INTO `hr_color` VALUES (3, 0, '黑色', 'black1', 100, 0, 1624846142, 1624846142, 2);
INSERT INTO `hr_color` VALUES (4, 0, '红色', 'red1', 100, 0, 1624846142, 1624846142, 2);
INSERT INTO `hr_color` VALUES (5, 0, '黑色', 'black1', 100, 0, 1624848912, 1624848912, 3);
INSERT INTO `hr_color` VALUES (6, 0, '红色', 'red1', 100, 0, 1624848912, 1624848912, 3);
INSERT INTO `hr_color` VALUES (7, 0, '紫色', 'zi1', 100, 0, 1624865599, 1624865599, 1);
INSERT INTO `hr_color` VALUES (8, 0, '绿色', 'red1', 100, 0, 1624865637, 1624865637, 1);
INSERT INTO `hr_color` VALUES (9, 0, '白色', 'b1', 100, 0, 1624865655, 1624865655, 1);
INSERT INTO `hr_color` VALUES (10, 0, '灰色', 'hui1', 100, 0, 1624865679, 1624865679, 1);
INSERT INTO `hr_color` VALUES (11, 0, '蓝色', 'blue1', 100, 0, 1624865700, 1624865700, 1);
INSERT INTO `hr_color` VALUES (12, 0, '茶灰色', 'cha1', 100, 0, 1624865723, 1624865723, 1);
INSERT INTO `hr_color` VALUES (13, 0, '黑色', 'black1', 100, 0, 1624929816, 1624929816, 4);
INSERT INTO `hr_color` VALUES (14, 0, '红色', 'red1', 100, 0, 1624929816, 1624929816, 4);
INSERT INTO `hr_color` VALUES (15, 0, '黑色', 'black1', 100, 0, 1624936884, 1624936884, 5);
INSERT INTO `hr_color` VALUES (16, 0, '红色', 'red1', 100, 0, 1624936884, 1624936884, 5);
INSERT INTO `hr_color` VALUES (17, 0, '黑色', 'black1', 100, 0, 1625107837, 1625107837, 6);
INSERT INTO `hr_color` VALUES (18, 0, '红色', 'red1', 100, 0, 1625107837, 1625107837, 6);
INSERT INTO `hr_color` VALUES (19, 0, '黑色', 'black1', 100, 0, 1625110509, 1625110509, 7);
INSERT INTO `hr_color` VALUES (20, 0, '红色', 'red1', 100, 0, 1625110509, 1625110509, 7);
INSERT INTO `hr_color` VALUES (21, 0, '黑色', 'black1', 100, 0, 1629878732, 1629878732, 8);
INSERT INTO `hr_color` VALUES (22, 0, '红色', 'red1', 100, 0, 1629878732, 1629878732, 8);
INSERT INTO `hr_color` VALUES (23, 0, '绿色', 'green1', 100, 0, 1629960915, 1629960915, 8);

-- ----------------------------
-- Table structure for hr_company
-- ----------------------------
DROP TABLE IF EXISTS `hr_company`;
CREATE TABLE `hr_company`  (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '企业超级管理员账号',
  `company_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '企业名字',
  `company_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '企业联系电话',
  `company_contact` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '企业联系人',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '企业' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_company
-- ----------------------------
INSERT INTO `hr_company` VALUES (1, 1, 'ABC', '13290929596', '杜飞', 1624846092);
INSERT INTO `hr_company` VALUES (2, 2, '123', '18111111111', '张三', 1624846142);
INSERT INTO `hr_company` VALUES (3, 3, '西瓜', '13233333333', '西瓜', 1624848912);
INSERT INTO `hr_company` VALUES (4, 4, 'tomcat', '13623940957', 'tomcat', 1624929816);
INSERT INTO `hr_company` VALUES (5, 6, '测试一号', '13213213213', '测试一', 1624936884);
INSERT INTO `hr_company` VALUES (6, 7, '测试公司', '16895223592', '范冰冰', 1625107837);
INSERT INTO `hr_company` VALUES (7, 8, '企业名字', '15866668888', '联系人', 1625110509);
INSERT INTO `hr_company` VALUES (8, 9, '帝王', '15088888888', '哥斯拉', 1629878732);

-- ----------------------------
-- Table structure for hr_config
-- ----------------------------
DROP TABLE IF EXISTS `hr_config`;
CREATE TABLE `hr_config`  (
  `config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `config_group` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '配置类别',
  `config_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '配置名称',
  `config_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '配置值',
  `lock_version` int(11) DEFAULT 0 COMMENT '版本锁',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`config_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_diary_client
-- ----------------------------
DROP TABLE IF EXISTS `hr_diary_client`;
CREATE TABLE `hr_diary_client`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT 0 COMMENT '客户ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `user_id` int(11) DEFAULT 0 COMMENT '操作人ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `account_id` int(11) DEFAULT 0 COMMENT '账目类别ID',
  `pmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '应收金额',
  `rmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '实收金额',
  `pbalance` decimal(10, 2) DEFAULT 0.00 COMMENT '应收余额',
  `settlement_balance` decimal(12, 2) DEFAULT 0.00 COMMENT '结算账户余额',
  `client_balance` decimal(12, 2) DEFAULT 0.00 COMMENT '客户账户余额',
  `create_time` int(11) DEFAULT 0 COMMENT '业务时间',
  `item_type` int(11) DEFAULT 0 COMMENT '记录类型',
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`details_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_diary_client
-- ----------------------------
INSERT INTO `hr_diary_client` VALUES (1, 1, 'SO210629092436216', 1, 2, 38, 760.00, 760.00, 0.00, 0.00, -760.00, 1624929876, 0, NULL, 0, 1, NULL);
INSERT INTO `hr_diary_client` VALUES (2, 1, 'SO210629093001239', 1, 2, 38, 1896.00, 1896.00, -760.00, 0.00, -2656.00, 1624930201, 0, NULL, 0, 1, NULL);
INSERT INTO `hr_diary_client` VALUES (3, 1, 'SO210629173121802', 1, 2, 38, 1422.00, 1422.00, -2656.00, 0.00, -4078.00, 1624959081, 0, NULL, 0, 1, NULL);
INSERT INTO `hr_diary_client` VALUES (4, 1, 'SO210629173956845', 1, 2, 38, 1140.00, 1140.00, -4078.00, 0.00, -5218.00, 1624959596, 0, NULL, 0, 1, NULL);
INSERT INTO `hr_diary_client` VALUES (5, 1, 'SO210629180617062', 1, 2, 38, 2844.00, 2844.00, -5218.00, 0.00, -8062.00, 1624961177, 0, NULL, 0, 1, NULL);
INSERT INTO `hr_diary_client` VALUES (6, 6, 'SO210702163011053', 7, 7, 38, 380.00, 380.00, 0.00, 0.00, -380.00, 1625214611, 0, NULL, 0, 6, NULL);
INSERT INTO `hr_diary_client` VALUES (7, 6, 'SO210702172102633', 7, 7, 38, 380.00, 380.00, -380.00, 0.00, -760.00, 1625217662, 0, NULL, 0, 6, NULL);
INSERT INTO `hr_diary_client` VALUES (8, 8, 'SO210827102029310', 9, 9, 38, 540.00, 540.00, 0.00, 0.00, -540.00, 1630030829, 0, NULL, 0, 8, NULL);

-- ----------------------------
-- Table structure for hr_diary_finance
-- ----------------------------
DROP TABLE IF EXISTS `hr_diary_finance`;
CREATE TABLE `hr_diary_finance`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `account_id` int(11) DEFAULT 0 COMMENT '账目类别ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `counterparty` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '交易方',
  `user_id` int(11) DEFAULT 0 COMMENT '操作人ID',
  `income` decimal(10, 2) DEFAULT 0.00 COMMENT '收入',
  `expend` decimal(10, 2) DEFAULT 0.00 COMMENT '支出',
  `surplus` decimal(10, 2) DEFAULT 0.00 COMMENT '盈余',
  `settlement_balance` decimal(12, 2) DEFAULT 0.00 COMMENT '结算账户余额',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '数据添加时间',
  `item_type` int(11) DEFAULT 0 COMMENT '记录类型',
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) NOT NULL DEFAULT 0 COMMENT '门店ID',
  `order_date` int(11) NOT NULL DEFAULT 0 COMMENT '业务时间',
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`details_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_diary_supplier
-- ----------------------------
DROP TABLE IF EXISTS `hr_diary_supplier`;
CREATE TABLE `hr_diary_supplier`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT 0 COMMENT '供应商ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `user_id` int(11) DEFAULT 0 COMMENT '操作人ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `account_id` int(11) DEFAULT 0 COMMENT '账目类别ID',
  `pmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '应付金额',
  `rmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '实付金额',
  `pbalance` decimal(12, 2) DEFAULT 0.00 COMMENT '应付余额',
  `settlement_balance` decimal(12, 2) DEFAULT 0.00 COMMENT '结算账户余额',
  `supplier_balance` decimal(12, 2) DEFAULT 0.00 COMMENT '供应商账户余额',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `item_type` int(11) DEFAULT 0 COMMENT '记录类型：\r\n9103：采购支出\r\n9104：采购退货\r\n9101：销售收入\r\n9102：销售退货\r\n9106：零售\r\n9108：优惠金额\r\n9109：充值\r\n9199：收款\r\n9198：支出\r\n9200:期初调整',
  `orders_date` int(11) DEFAULT NULL COMMENT '业务时间',
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`details_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '供应商对账单据' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_diary_supplier
-- ----------------------------
INSERT INTO `hr_diary_supplier` VALUES (1, 2, 'PO210629092415835', 0, 0, 0, 400.00, 400.00, 0.00, 0.00, -400.00, 1624929856, 1624929856, 9103, 1624896000, '', 0, 1, NULL);
INSERT INTO `hr_diary_supplier` VALUES (2, 2, 'PO210629092937014', 0, 0, 0, 1200.00, 1200.00, 0.00, 0.00, -1600.00, 1624930178, 1624930178, 9103, 1624896000, '', 0, 1, NULL);
INSERT INTO `hr_diary_supplier` VALUES (3, 2, 'PO210629173102853', 0, 0, 0, 900.00, 900.00, 0.00, 0.00, -2500.00, 1624959063, 1624959063, 9103, 1624896000, '', 0, 1, NULL);
INSERT INTO `hr_diary_supplier` VALUES (4, 7, 'PO210629173920640', 0, 0, 0, 800.00, 800.00, 0.00, 0.00, 400.00, 1624959561, 1624959561, 9103, 1624896000, '', 0, 1, NULL);
INSERT INTO `hr_diary_supplier` VALUES (5, 7, 'PO210629180551123', 0, 0, 0, 1800.00, 1800.00, 0.00, 0.00, -1400.00, 1624961151, 1624961151, 9103, 1624896000, '', 0, 1, NULL);
INSERT INTO `hr_diary_supplier` VALUES (6, 8, 'PO210702115651721', 0, 0, 0, 4000.00, 4000.00, 0.00, 0.00, -4000.00, 1625198224, 1625198224, 9103, 1625155200, '', 0, 6, NULL);
INSERT INTO `hr_diary_supplier` VALUES (7, 8, 'PO210702171936729', 0, 0, 0, 6000.00, 6000.00, 0.00, 0.00, -10000.00, 1625217577, 1625217577, 9103, 1625155200, '', 0, 6, NULL);
INSERT INTO `hr_diary_supplier` VALUES (8, 4, 'PO210629174407240', 0, 0, 0, 200.00, 2002.00, -1802.00, 0.00, -2002.00, 1625457632, 1625457632, 9103, 1624809600, '213654', 0, 3, NULL);
INSERT INTO `hr_diary_supplier` VALUES (9, 12, 'PO210827112608276', 0, 0, 0, 1000.00, 1000.00, 0.00, 0.00, 0.00, 1630034768, 1630034768, 9103, 1629993600, '', 0, 8, NULL);

-- ----------------------------
-- Table structure for hr_dict
-- ----------------------------
DROP TABLE IF EXISTS `hr_dict`;
CREATE TABLE `hr_dict`  (
  `dict_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `dict_pid` int(11) NOT NULL DEFAULT 0,
  `dict_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '字典分类类型',
  `dict_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '字典名称',
  `dict_value` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '字典值2',
  `dict_text` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '字典值1',
  `sort` int(11) NOT NULL DEFAULT 0,
  `lock_version` int(11) DEFAULT 0 COMMENT '锁版本',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `dict_status` tinyint(4) DEFAULT 0 COMMENT '字段状态',
  `dict_disabled` tinyint(4) DEFAULT 0 COMMENT '不可编辑',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID,为0时说明全网通用',
  `delete_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`dict_id`) USING BTREE,
  INDEX `dict_type`(`dict_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 66 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_dict
-- ----------------------------
INSERT INTO `hr_dict` VALUES (1, 0, 'account', '账户互转', 'Y', '系统原有账目类型，不可修改。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (3, 0, 'account', '期初调整', 'Y', '系统原有账目类型，不可修改。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (38, 0, 'account', '销售收入', 'Y', '系统原有账目类型，不可修改。对应销售单等收入项目。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (39, 0, 'account', '销售退货', 'Y', '系统原有账目类型，不可修改。对应销售退货单等支出项目。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (40, 0, 'account', '采购支出', 'Y', '系统原有账目类型，不可修改。对应采购单等支出项目。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (45, 0, 'account', '销售撤销', 'Y', '系统原有账目类型，不可修改。对应销售单等收入项目。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (46, 0, 'account', '销售退货单撤销', 'Y', '系统原有账目类型，不可修改。对应销售单等收入项目。', 0, 0, 0, NULL, 1, 1, 0, 0);
INSERT INTO `hr_dict` VALUES (61, 0, 'delivery', '顺风快递', '0', '0', 0, 0, 0, NULL, 0, 0, NULL, 0);
INSERT INTO `hr_dict` VALUES (62, 0, 'delivery', '申通快递', '0', '0', 0, 0, 0, NULL, 0, 0, NULL, 0);
INSERT INTO `hr_dict` VALUES (63, 0, 'delivery', '韵达快递', '0', '0', 0, 0, 0, NULL, 0, 0, NULL, 0);
INSERT INTO `hr_dict` VALUES (64, 0, 'delivery', '邮政快递', '0', '0', 0, 0, 0, NULL, 0, 0, NULL, 0);
INSERT INTO `hr_dict` VALUES (65, 0, 'delivery', '其它发货方式', '0', '0', 0, 0, 0, NULL, 0, 0, NULL, 0);

-- ----------------------------
-- Table structure for hr_goods
-- ----------------------------
DROP TABLE IF EXISTS `hr_goods`;
CREATE TABLE `hr_goods`  (
  `goods_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品货号',
  `goods_serial` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品序号',
  `goods_barcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品条码',
  `goods_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品名称',
  `goods_pprice` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '采购价',
  `goods_wprice` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '批发价',
  `goods_srprice` decimal(10, 2) DEFAULT 0.00 COMMENT '建议零售价',
  `goods_rprice` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '零售价',
  `goods_bnumber` int(11) DEFAULT 0 COMMENT '起订量',
  `category_id` int(10) DEFAULT 0 COMMENT '分类编号',
  `brand_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '品牌编号',
  `material_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '材质编号',
  `goods_sex` tinyint(4) DEFAULT 0 COMMENT '商品性别',
  `unit_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单位编号',
  `goods_year` year DEFAULT 2000 COMMENT '商品年份',
  `goods_season` int(4) DEFAULT 0 COMMENT '商品季节',
  `goods_llimit` int(11) DEFAULT 0 COMMENT '库存下限',
  `goods_ulimit` int(11) DEFAULT 50 COMMENT '库存上限',
  `goods_sort` int(11) DEFAULT 100 COMMENT '商品排序',
  `goods_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '商品状态(1上架 0未上架)',
  `goods_story` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '详细描述',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `update_time` int(11) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `integral` int(11) UNSIGNED DEFAULT 0 COMMENT '积分',
  `style_id` int(11) NOT NULL DEFAULT 0 COMMENT '款式编号',
  PRIMARY KEY (`goods_id`, `goods_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE,
  INDEX `goods_name`(`goods_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品基本信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_goods
-- ----------------------------
INSERT INTO `hr_goods` VALUES (10, 'GOOD210825160533562', 'GOOD210825160533562', 'GOOD210825160533562', '服装A', 100.00, 110.00, 200.00, 190.00, 100, 12, NULL, NULL, 0, NULL, 2000, 0, 0, 50, 100, 1, NULL, 1629878732, 0, 1629878732, 8, 0, 0);
INSERT INTO `hr_goods` VALUES (11, 'GOOD210628100810025', NULL, 'GOOD210628100810025', '潇洒秋裤', 50.00, 0.00, 0.00, 60.00, 0, 15, NULL, NULL, 0, NULL, 2000, 0, 0, 50, 100, 1, NULL, 1629885570, 0, 1629885570, 8, 20, 0);

-- ----------------------------
-- Table structure for hr_goods_assist
-- ----------------------------
DROP TABLE IF EXISTS `hr_goods_assist`;
CREATE TABLE `hr_goods_assist`  (
  `assist_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品ID',
  `assist_category` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '附件或图片',
  `assist_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '文件名',
  `assist_extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '扩展名',
  `assist_url` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '链接地址',
  `assist_size` int(11) DEFAULT NULL COMMENT '文件大小',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `assist_sort` int(11) DEFAULT 0 COMMENT '排序',
  `assist_md5` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'md5标记',
  `assist_sha1` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'sha1标记',
  `assist_status` tinyint(4) DEFAULT 1 COMMENT '附件状态',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '上传类型： localWeb（web端后台上传）',
  PRIMARY KEY (`assist_id`) USING BTREE,
  INDEX `goods_id`(`goods_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品图片' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_goods_assist
-- ----------------------------
INSERT INTO `hr_goods_assist` VALUES (9, '10', 'image', 'image/0be19511e17148c7', 'jpg', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/image/0be19511e17148c7', NULL, 1629878732, 1629878732, 0, 'image/0be19511e17148c7', 'image/0be19511e17148c7', 1, 8, '');
INSERT INTO `hr_goods_assist` VALUES (10, '11', 'image', 'image/fc287a1a39f19452', 'jpg', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/image/fc287a1a39f19452', NULL, NULL, NULL, 1, 'image/fc287a1a39f19452', 'image/fc287a1a39f19452', 1, 8, '');
INSERT INTO `hr_goods_assist` VALUES (11, '11', 'image', 'image/8c5caf19e6de5ac7', 'jpg', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/image/8c5caf19e6de5ac7', NULL, NULL, NULL, 0, 'image/8c5caf19e6de5ac7', 'image/8c5caf19e6de5ac7', 1, 8, '');
INSERT INTO `hr_goods_assist` VALUES (12, '11', 'image', 'image/f63a8d54e4a07929', 'jpg', 'psi-test-1256793516.cos.ap-beijing.myqcloud.com/image/f63a8d54e4a07929', NULL, NULL, NULL, 2, 'image/f63a8d54e4a07929', 'image/f63a8d54e4a07929', 1, 8, '');

-- ----------------------------
-- Table structure for hr_goods_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_goods_details`;
CREATE TABLE `hr_goods_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `goods_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品ID',
  `color_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '颜色ID',
  `size_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '尺码ID',
  `history_number` int(11) NOT NULL COMMENT '累计数量',
  `goods_scode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单品货号',
  `goods_sbarcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单品条码',
  `lock_version` int(11) NOT NULL DEFAULT 0 COMMENT '锁版本',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`details_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 89 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品对应尺码色码' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_goods_details
-- ----------------------------
INSERT INTO `hr_goods_details` VALUES (81, 'GOOD210825160533562', '10', '21', '18', 0, NULL, NULL, 0, 1629878732, 8, 1629878732);
INSERT INTO `hr_goods_details` VALUES (82, 'GOOD210825160533562', '10', '21', '19', 0, NULL, NULL, 0, 1629878732, 8, 1629878732);
INSERT INTO `hr_goods_details` VALUES (83, 'GOOD210825160533562', '10', '22', '18', 0, NULL, NULL, 0, 1629878732, 8, 1629878732);
INSERT INTO `hr_goods_details` VALUES (84, 'GOOD210825160533562', '10', '22', '19', 0, NULL, NULL, 0, 1629878732, 8, 1629878732);
INSERT INTO `hr_goods_details` VALUES (85, 'GOOD210628100810025', '11', '21', '18', 0, NULL, NULL, 0, 1629885570, 8, 1629885570);
INSERT INTO `hr_goods_details` VALUES (86, 'GOOD210628100810025', '11', '21', '19', 0, NULL, NULL, 0, 1629885570, 8, 1629885570);
INSERT INTO `hr_goods_details` VALUES (87, 'GOOD210628100810025', '11', '22', '18', 0, NULL, NULL, 0, 1629885570, 8, 1629885570);
INSERT INTO `hr_goods_details` VALUES (88, 'GOOD210628100810025', '11', '22', '19', 0, NULL, NULL, 0, 1629885571, 8, 1629885571);

-- ----------------------------
-- Table structure for hr_goods_stock
-- ----------------------------
DROP TABLE IF EXISTS `hr_goods_stock`;
CREATE TABLE `hr_goods_stock`  (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库ID',
  `goods_id` int(11) UNSIGNED DEFAULT NULL COMMENT '商品id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品code',
  `color_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '颜色ID',
  `size_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '尺码ID',
  `stock_number` int(11) DEFAULT 0 COMMENT '库存数量',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `update_time` int(10) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `last_orders_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '最后盘点单号',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`stock_id`) USING BTREE,
  INDEX `goods_id`(`goods_code`) USING BTREE,
  INDEX `goods_only`(`warehouse_id`, `goods_code`, `color_id`, `size_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 125 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品库存' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_goods_stock
-- ----------------------------
INSERT INTO `hr_goods_stock` VALUES (113, 9, 10, 'GOOD210701105038299', '17', '14', 12, 4, 1625221451, 'SOL210702182411147', 6, 0, 1625198223);
INSERT INTO `hr_goods_stock` VALUES (114, 9, 10, 'GOOD210701105038299', '17', '15', 14, 2, 1625221451, 'SOL210702182411147', 6, 0, 1625198223);
INSERT INTO `hr_goods_stock` VALUES (115, 9, 10, 'GOOD210701105038299', '18', '14', 14, 2, 1625221452, 'SOL210702182411147', 6, 0, 1625198223);
INSERT INTO `hr_goods_stock` VALUES (116, 9, 10, 'GOOD210701105038299', '18', '15', 14, 2, 1625221452, 'SOL210702182411147', 6, 0, 1625198224);
INSERT INTO `hr_goods_stock` VALUES (117, 3, 11, 'GOOD210628105513015', '5', '7', 1, 0, 1625457631, 'PO210629174407240', 3, 0, 1625457631);
INSERT INTO `hr_goods_stock` VALUES (118, 3, 11, 'GOOD210628105513015', '5', '8', 1, 0, 1625457631, 'PO210629174407240', 3, 0, 1625457631);
INSERT INTO `hr_goods_stock` VALUES (119, 12, 11, 'GOOD210628100810025', '21', '18', 2, 2, 1630034918, 'TF210827112838256', 8, 0, 1630030829);
INSERT INTO `hr_goods_stock` VALUES (120, 12, 11, 'GOOD210628100810025', '21', '19', 3, 2, 1630034918, 'TF210827112838256', 8, 0, 1630030829);
INSERT INTO `hr_goods_stock` VALUES (121, 12, 11, 'GOOD210628100810025', '22', '18', 0, 1, 1630034858, 'SI210827112738644', 8, 0, 1630030829);
INSERT INTO `hr_goods_stock` VALUES (122, 12, 11, 'GOOD210628100810025', '22', '19', 0, 1, 1630034858, 'SI210827112738644', 8, 0, 1630030829);
INSERT INTO `hr_goods_stock` VALUES (123, 11, 11, 'GOOD210628100810025', '21', '18', 3, 1, 1630034918, 'TF210827112838256', 8, 1, 1630034918);
INSERT INTO `hr_goods_stock` VALUES (124, 11, 11, 'GOOD210628100810025', '21', '19', 2, 1, 1630034918, 'TF210827112838256', 8, 1, 1630034918);

-- ----------------------------
-- Table structure for hr_hjtr_organization
-- ----------------------------
DROP TABLE IF EXISTS `hr_hjtr_organization`;
CREATE TABLE `hr_hjtr_organization`  (
  `org_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `org_pid` int(10) UNSIGNED DEFAULT 0 COMMENT '父ID',
  `org_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '组织机构名称',
  `org_head` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '组织机构负责人',
  `org_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '联系电话',
  `org_type` tinyint(4) DEFAULT 0 COMMENT '组织机构类型',
  `org_status` tinyint(4) DEFAULT 0 COMMENT '组织机构状态',
  `sort` int(11) DEFAULT 100 COMMENT '排序',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `update_time` int(10) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`org_id`) USING BTREE,
  INDEX `org_pid`(`org_pid`) USING BTREE,
  INDEX `org_name`(`org_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '组织机构' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_inventory_orders
-- ----------------------------
DROP TABLE IF EXISTS `hr_inventory_orders`;
CREATE TABLE `hr_inventory_orders`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '盘点总数',
  `goods_plnumber` int(11) DEFAULT 0 COMMENT '盈亏数量',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态0、未保存，1、草稿，9、完成',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '盘点日期',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '盘点单基础信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_inventory_orders
-- ----------------------------
INSERT INTO `hr_inventory_orders` VALUES (1, 'SI210702163515170', 9, 2, -36, 7, 9, '', 1625155200, 1625214915, 1625214915, 0, 6, 0);
INSERT INTO `hr_inventory_orders` VALUES (2, 'SI210705120052855', 0, 8, 8, 3, 1, '', 1625414400, 1625457652, 1625457652, 0, 3, 0);
INSERT INTO `hr_inventory_orders` VALUES (3, 'SI210827112738644', 12, 10, -1, 9, 9, '', 1629993600, 1630034858, 1630034858, 0, 8, 0);

-- ----------------------------
-- Table structure for hr_inventory_orders_children
-- ----------------------------
DROP TABLE IF EXISTS `hr_inventory_orders_children`;
CREATE TABLE `hr_inventory_orders_children`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) DEFAULT NULL COMMENT '父单据ID',
  `user_id` int(11) UNSIGNED DEFAULT NULL COMMENT '用户id',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `serial_number` tinyint(4) DEFAULT NULL COMMENT '子单据序号',
  `children_code` int(11) DEFAULT NULL COMMENT '子单据编号',
  `children_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '子单据名称',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `curr_use` tinyint(4) DEFAULT 0 COMMENT '当前使用',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新事件',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `children_code`(`children_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '盘点单-子单据' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_inventory_orders_children
-- ----------------------------
INSERT INTO `hr_inventory_orders_children` VALUES (1, 1, 7, 'SI210702163515170', 71, 71, '单据1', 1, 1, 1625214915, 1625214915, 0, 6);
INSERT INTO `hr_inventory_orders_children` VALUES (2, 2, 3, 'SI210705120052855', 31, 31, '单据1', 1, 1, 1625457652, 1625457652, 0, 3);
INSERT INTO `hr_inventory_orders_children` VALUES (3, 3, 9, 'SI210827112738644', 91, 91, '单据1', 1, 1, 1630034858, 1630034858, 0, 8);

-- ----------------------------
-- Table structure for hr_inventory_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_inventory_orders_details`;
CREATE TABLE `hr_inventory_orders_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL COMMENT '仓库id',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `children_code` int(11) DEFAULT NULL COMMENT '子单据编号',
  `user_id` int(11) DEFAULT 0 COMMENT '用户ID',
  `goods_id` int(11) DEFAULT 0 COMMENT '商品id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '盘点数量',
  `goods_anumber` int(11) DEFAULT NULL COMMENT '盘点前数量',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '盘点单商品库存-草稿' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_inventory_orders_details
-- ----------------------------
INSERT INTO `hr_inventory_orders_details` VALUES (1, 0, 2, 'SI210705120052855', 31, 3, 3, 'GOOD210628105513015', 5, 7, 2, NULL, 1, 1625457653, 1625457653, 0, 3, 1);
INSERT INTO `hr_inventory_orders_details` VALUES (2, 0, 2, 'SI210705120052855', 31, 3, 3, 'GOOD210628105513015', 5, 8, 2, NULL, 1, 1625457653, 1625457653, 0, 3, 1);
INSERT INTO `hr_inventory_orders_details` VALUES (3, 0, 2, 'SI210705120052855', 31, 3, 3, 'GOOD210628105513015', 6, 7, 2, NULL, 1, 1625457653, 1625457653, 0, 3, 1);
INSERT INTO `hr_inventory_orders_details` VALUES (4, 0, 2, 'SI210705120052855', 31, 3, 3, 'GOOD210628105513015', 6, 8, 2, NULL, 1, 1625457653, 1625457653, 0, 3, 1);

-- ----------------------------
-- Table structure for hr_inventory_orders_details_c
-- ----------------------------
DROP TABLE IF EXISTS `hr_inventory_orders_details_c`;
CREATE TABLE `hr_inventory_orders_details_c`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) UNSIGNED DEFAULT NULL COMMENT '仓库id',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `children_code` int(11) DEFAULT NULL COMMENT '子单据编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '盘点数量',
  `goods_anumber` int(11) DEFAULT NULL COMMENT '盘点前数量',
  `goods_status` tinyint(4) DEFAULT NULL COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店id',
  `goods_lnumber` int(11) DEFAULT 0 COMMENT '盈亏数量',
  `goods_lmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '盈亏金额',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '盘点单商品库存信息-正式' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_inventory_orders_details_c
-- ----------------------------
INSERT INTO `hr_inventory_orders_details_c` VALUES (1, 9, 1, 'SI210702163515170', 71, 7, 7, 'GOOD210701105038299', 17, 14, 1, 9, 1, 1625214915, 1625214915, 0, 6, 1, -8, -800.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (2, 9, 1, 'SI210702163515170', 71, 7, 7, 'GOOD210701105038299', 17, 15, 1, 9, 1, 1625214916, 1625214916, 0, 6, 1, -8, -800.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (3, 9, 1, 'SI210702163515170', 71, 7, 7, 'GOOD210701105038299', 18, 14, 0, 10, 1, 1625214916, 1625214916, 0, 6, 1, -10, -1000.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (4, 9, 1, 'SI210702163515170', 71, 7, 7, 'GOOD210701105038299', 18, 15, 0, 10, 1, 1625214916, 1625214916, 0, 6, 1, -10, -1000.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (5, 12, 3, 'SI210827112738644', 91, 9, 11, 'GOOD210628100810025', 21, 18, 5, 7, 1, 1630034858, 1630034858, 0, 8, 1, -2, -100.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (6, 12, 3, 'SI210827112738644', 91, 9, 11, 'GOOD210628100810025', 21, 19, 5, 4, 1, 1630034858, 1630034858, 0, 8, 1, 1, 50.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (7, 12, 3, 'SI210827112738644', 91, 9, 11, 'GOOD210628100810025', 22, 18, 0, 0, 1, 1630034858, 1630034858, 0, 8, 1, 0, 0.00);
INSERT INTO `hr_inventory_orders_details_c` VALUES (8, 12, 3, 'SI210827112738644', 91, 9, 11, 'GOOD210628100810025', 22, 19, 0, 0, 1, 1630034858, 1630034858, 0, 8, 1, 0, 0.00);

-- ----------------------------
-- Table structure for hr_member_account
-- ----------------------------
DROP TABLE IF EXISTS `hr_member_account`;
CREATE TABLE `hr_member_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员帐号',
  `account_points` int(11) DEFAULT 0 COMMENT '账户积分',
  `account_money` decimal(10, 2) DEFAULT 0.00 COMMENT '账户金额',
  `freeze_money` decimal(10, 2) DEFAULT 0.00 COMMENT '冻结金额',
  `total_sum` decimal(12, 2) DEFAULT 0.00 COMMENT '购物总额',
  `total_sum_original` decimal(12, 2) DEFAULT 0.00 COMMENT '购物原价总额',
  `total_number` int(11) DEFAULT 0 COMMENT '购物总数',
  `total_orders_count` int(11) DEFAULT 0 COMMENT '订单总数',
  `last_money` decimal(10, 2) DEFAULT 0.00 COMMENT '尾单金额',
  `last_time` int(11) DEFAULT NULL COMMENT '尾单日期',
  `first_money` decimal(10, 2) DEFAULT 0.00 COMMENT '首单金额',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建日期',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员_账户信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_member_account
-- ----------------------------
INSERT INTO `hr_member_account` VALUES (1, '20210628111618359922', 0, 0.00, 0.00, 0.00, 0.00, 0, 0, 0.00, NULL, 0.00, NULL, 1624850178, 1);
INSERT INTO `hr_member_account` VALUES (2, '20210702165640659184', 0, 0.00, 0.00, 76025935.00, 1140.00, 0, 3, 0.00, NULL, 0.00, 1625221452, 1625216200, 6);

-- ----------------------------
-- Table structure for hr_member_base
-- ----------------------------
DROP TABLE IF EXISTS `hr_member_base`;
CREATE TABLE `hr_member_base`  (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员帐号',
  `member_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员名称',
  `member_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员手机',
  `member_idcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '证件号',
  `user_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '接待导购员编号',
  `member_qq` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'QQ',
  `member_wechat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '微信号',
  `member_sex` tinyint(4) DEFAULT 0 COMMENT '性别0女1男',
  `category_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员类别编号',
  `member_age` tinyint(4) DEFAULT NULL COMMENT '年龄',
  `member_height` tinyint(4) DEFAULT NULL COMMENT '身高',
  `city_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '居住城市编号',
  `member_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '电子邮件',
  `member_birthday` int(11) DEFAULT NULL COMMENT '会员生日',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `member_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员地址',
  `member_story` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '会员描述',
  `member_status` tinyint(4) DEFAULT 1 COMMENT '会员状态0禁用1启用',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`member_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员_基本信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_member_base
-- ----------------------------
INSERT INTO `hr_member_base` VALUES (1, '20210628111618359922', '林西顾', '13290929596', '410411198910235524', '1', '312037099', 'taco海外购', 0, '', 31, 127, '', '312037099@qq.com', 0, 1624850178, '河南省平顶山市', '爱买套装 喜欢白色系', 1, 1, NULL);
INSERT INTO `hr_member_base` VALUES (2, '20210702165640659184', '张三', '15362559632', '410411199405135551', '7', '', '', 1, '', 0, 0, '', '', 0, 1625216200, '', '', 1, 6, NULL);

-- ----------------------------
-- Table structure for hr_member_card
-- ----------------------------
DROP TABLE IF EXISTS `hr_member_card`;
CREATE TABLE `hr_member_card`  (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '微信OpenID',
  `cardid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '会员卡类型ID',
  `usercardcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '会员卡编号',
  `member_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '会员姓名',
  `member_sex` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '会员性别',
  `member_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '手机号',
  `member_birthday` int(11) DEFAULT 0 COMMENT '生日',
  `member_address` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '会员地址',
  `card_time` int(11) DEFAULT 0 COMMENT '会员卡领取时间',
  `member_status` tinyint(4) DEFAULT 0 COMMENT '状态',
  `member_accumulative_integral` int(11) DEFAULT 0 COMMENT '会员累计积分',
  `member_new_integral` int(11) DEFAULT 0 COMMENT '会员当前积分',
  `member_accumulative_share` int(11) DEFAULT 0 COMMENT '会员累计分享次数',
  `member_now_share` int(11) DEFAULT 0 COMMENT '会员当前分享次数',
  `member_accumulative_money` decimal(9, 2) DEFAULT 0.00 COMMENT '会员累计金额',
  `member_now_money` decimal(9, 2) DEFAULT 0.00 COMMENT '会员当前金额',
  `lock_version` int(10) DEFAULT 0 COMMENT '锁版本',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业id',
  PRIMARY KEY (`oid`, `openid`, `cardid`, `usercardcode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '小程序会员_基本信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_member_integral_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_member_integral_details`;
CREATE TABLE `hr_member_integral_details`  (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `sales_openid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '导购员OpenID',
  `sales_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '导购员姓名',
  `cardid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '会员卡类型ID',
  `usercardcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '会员卡编号',
  `member_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '会员姓名',
  `member_before_integral` int(11) DEFAULT 0 COMMENT '会员之前积分',
  `member_current_integral` int(11) DEFAULT 0 COMMENT '会员获得积分',
  `member_after_integral` int(11) DEFAULT 0 COMMENT '会员之后积分',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `commodity_remark` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `member_openid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '会员OpenID',
  `member_account_id` int(11) DEFAULT NULL COMMENT '会员账户id',
  PRIMARY KEY (`oid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '小程序会员_积分明细' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_member_integral_details
-- ----------------------------
INSERT INTO `hr_member_integral_details` VALUES (1, '7', '范冰冰', '0', '', '张三', 0, 0, 0, 1625216244, '', '2', 2);
INSERT INTO `hr_member_integral_details` VALUES (2, '7', '范冰冰', '0', '', '张三', 0, 0, 0, 1625216897, '', '2', 2);
INSERT INTO `hr_member_integral_details` VALUES (3, '7', '范冰冰', '0', '', '张三', 0, 0, 0, 1625221450, '', '2', 2);

-- ----------------------------
-- Table structure for hr_module_controller
-- ----------------------------
DROP TABLE IF EXISTS `hr_module_controller`;
CREATE TABLE `hr_module_controller`  (
  `mc_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mc_code` int(11) DEFAULT 0 COMMENT '模型控制器编号',
  `module_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '模块名称',
  `controller_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '控制器名称',
  `action_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '操作名称',
  `node_code` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '权限节点编号',
  `node_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '权限节点名称',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`mc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限-模块控制器' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_order_assist
-- ----------------------------
DROP TABLE IF EXISTS `hr_order_assist`;
CREATE TABLE `hr_order_assist`  (
  `assist_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单编号',
  `assist_category` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '附件或图片',
  `assist_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '文件名',
  `assist_extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '扩展名',
  `assist_url` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '链接地址',
  `assist_size` int(11) DEFAULT NULL COMMENT '文件大小',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `assist_sort` int(11) DEFAULT 0 COMMENT '排序',
  `assist_md5` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'md5标记',
  `assist_sha1` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'sha1标记',
  `assist_status` tinyint(4) DEFAULT 1 COMMENT '附件状态',
  `orders_categories` tinyint(4) DEFAULT 0 COMMENT '图片所属单据，10：采购单；20：销售单。其他单据往后排序',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '上传类型： localWeb（web端后台上传）',
  PRIMARY KEY (`assist_id`) USING BTREE,
  INDEX `orders_id`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_organization
-- ----------------------------
DROP TABLE IF EXISTS `hr_organization`;
CREATE TABLE `hr_organization`  (
  `org_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `org_pid` int(10) UNSIGNED DEFAULT 0 COMMENT '父ID',
  `org_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '组织机构名称',
  `org_head` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '组织机构负责人',
  `org_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '联系电话',
  `org_type` tinyint(4) DEFAULT 0 COMMENT '组织机构类型0:内部机构 1：外部门店 2：仓库',
  `org_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '组织机构状态',
  `sort` int(11) DEFAULT 100 COMMENT '排序',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`org_id`) USING BTREE,
  INDEX `org_pid`(`org_pid`) USING BTREE,
  INDEX `org_name`(`org_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_organization
-- ----------------------------
INSERT INTO `hr_organization` VALUES (1, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1624846092, 1624846092, 1);
INSERT INTO `hr_organization` VALUES (2, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1624846142, 1624846142, 2);
INSERT INTO `hr_organization` VALUES (3, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1624848912, 1624848912, 3);
INSERT INTO `hr_organization` VALUES (4, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1624929816, 1624929816, 4);
INSERT INTO `hr_organization` VALUES (5, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1624936884, 1624936884, 5);
INSERT INTO `hr_organization` VALUES (6, 0, 'taco海外仓', '杜小鸟', '13290929596', 2, 1, 100, 0, 1624959442, 1624959442, 1);
INSERT INTO `hr_organization` VALUES (7, 6, '自由港青岛仓', '李艳会', '13288987878', 1, 1, 100, 0, 1624959499, 1624959499, 1);
INSERT INTO `hr_organization` VALUES (8, 0, 'ASM ANNA 海外旗舰仓', '安娜', '16767876546', 0, 1, 100, 0, 1624961113, 1624961113, 1);
INSERT INTO `hr_organization` VALUES (9, 0, '旗舰店', '管理员', '18888888888', 0, 1, 100, 0, 1625107837, 1625218955, 6);
INSERT INTO `hr_organization` VALUES (10, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1625110509, 1625110509, 7);
INSERT INTO `hr_organization` VALUES (11, 0, '旗舰店', '管理员', '18888888888', 1, 1, 100, 0, 1629878732, 1629878732, 8);
INSERT INTO `hr_organization` VALUES (12, 0, '自由港', 'hero', '15523232323', 2, 1, 100, 0, 1629962091, 1629962091, 8);

-- ----------------------------
-- Table structure for hr_power_node
-- ----------------------------
DROP TABLE IF EXISTS `hr_power_node`;
CREATE TABLE `hr_power_node`  (
  `node` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_node_id` int(11) DEFAULT 0 COMMENT '父ID',
  `node_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '节点名称',
  `node_code` int(11) DEFAULT 0 COMMENT '节点编号',
  `node_category` tinyint(4) DEFAULT 0 COMMENT '节点类型',
  `node_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '节点Action',
  `node_status` tinyint(4) DEFAULT 0 COMMENT '默认状态',
  `sort` int(11) DEFAULT 0 COMMENT '节点排序',
  `menu_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '菜单URL',
  `node_group` tinyint(4) DEFAULT 0 COMMENT '菜单分组',
  `is_leaf` tinyint(4) DEFAULT 0 COMMENT '是否叶子节点菜单',
  `node_ico` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '菜单图标',
  `node_remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限-权限节点' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_orders`;
CREATE TABLE `hr_purchase_orders`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '单据编号',
  `supplier_id` int(11) DEFAULT 0 COMMENT '供应商ID',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `orders_pmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '应付金额',
  `orders_rmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '实付金额',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `other_type` int(11) DEFAULT 0 COMMENT '其他费用',
  `other_money` decimal(9, 2) DEFAULT 0.00 COMMENT '其他费用金额',
  `erase_money` decimal(9, 2) DEFAULT 0.00 COMMENT '抹零金额',
  `user_id` int(11) DEFAULT NULL COMMENT '制单人ID',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  UNIQUE INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '采购单' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_purchase_orders
-- ----------------------------
INSERT INTO `hr_purchase_orders` VALUES (1, 'PO210629092415835', 2, 1, 0, 400.00, 400.00, 4, 1, 0.00, 0.00, 1, 9, '', 1624896000, 1624929855, 1624929855, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (2, 'PO210629092937014', 2, 1, 0, 1200.00, 1200.00, 12, 1, 0.00, 0.00, 1, 9, '', 1624896000, 1624930177, 1624930177, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (3, 'PO210629173102853', 2, 1, 0, 900.00, 900.00, 9, 1, 0.00, 0.00, 1, 9, '', 1624896000, 1624959062, 1624959062, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (4, 'PO210629173920640', 7, 6, 0, 800.00, 800.00, 8, 1, 0.00, 0.00, 1, 9, '', 1624896000, 1624959560, 1624959560, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (5, 'PO210629174407240', 4, 3, 0, 200.00, 2002.00, 2, 1, 7.00, 6.00, 3, 9, '213654', 1624809600, 1624959847, 1625457631, 1, 3, NULL);
INSERT INTO `hr_purchase_orders` VALUES (6, 'PO210629180551123', 7, 8, 0, 1800.00, 1800.00, 18, 1, 0.00, 0.00, 1, 9, '', 1624896000, 1624961151, 1624961151, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (7, 'PO210702115651721', 8, 9, 0, 4000.00, 4000.00, 40, 1, 0.00, 0.00, 7, 9, '', 1625155200, 1625198211, 1625198223, 1, 6, NULL);
INSERT INTO `hr_purchase_orders` VALUES (8, 'PO210702171936729', 8, 9, 0, 6000.00, 6000.00, 60, 1, 0.00, 0.00, 7, 9, '', 1625155200, 1625217576, 1625217576, 0, 6, NULL);
INSERT INTO `hr_purchase_orders` VALUES (9, 'PO210825104309893', 0, 0, 0, 0.00, 0.00, 0, 0, 0.00, 0.00, NULL, 7, NULL, 0, 1629859389, 1629859389, 0, 1, NULL);
INSERT INTO `hr_purchase_orders` VALUES (10, 'PO210827112608276', 12, 12, 0, 1000.00, 1000.00, 20, 1, 110.00, 0.80, 9, 9, '', 1629993600, 1630034768, 1630034768, 0, 8, NULL);

-- ----------------------------
-- Table structure for hr_purchase_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_orders_details`;
CREATE TABLE `hr_purchase_orders_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '颜色ID',
  `size_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '尺码ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT 0.00 COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '金额',
  `goods_discount` tinyint(4) DEFAULT 100 COMMENT '折扣',
  `goods_daprice` decimal(10, 2) DEFAULT 0.00 COMMENT '折后价',
  `goods_tdamoney` decimal(10, 2) DEFAULT 0.00 COMMENT '折后金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_purchase_orders_details
-- ----------------------------
INSERT INTO `hr_purchase_orders_details` VALUES (1, 1, 'PO210629092415835', 'GOOD210628100813162', '1', '1', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624929855, 1624929855, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (2, 1, 'PO210629092415835', 'GOOD210628100813162', '1', '2', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624929855, 1624929855, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (3, 1, 'PO210629092415835', 'GOOD210628100813162', '1', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624929855, 1624929855, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (4, 1, 'PO210629092415835', 'GOOD210628100813162', '1', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624929855, 1624929855, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (5, 2, 'PO210629092937014', 'HB1322678', '10', '11', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (6, 2, 'PO210629092937014', 'HB1322678', '10', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (7, 2, 'PO210629092937014', 'HB1322678', '10', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (8, 2, 'PO210629092937014', 'HB1322678', '9', '11', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (9, 2, 'PO210629092937014', 'HB1322678', '9', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (10, 2, 'PO210629092937014', 'HB1322678', '9', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (11, 2, 'PO210629092937014', 'HB1322678', '8', '11', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (12, 2, 'PO210629092937014', 'HB1322678', '8', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (13, 2, 'PO210629092937014', 'HB1322678', '8', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (14, 2, 'PO210629092937014', 'HB1322678', '7', '11', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (15, 2, 'PO210629092937014', 'HB1322678', '7', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (16, 2, 'PO210629092937014', 'HB1322678', '7', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624930177, 1624930177, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (17, 3, 'PO210629173102853', 'HB1322678', '1', '11', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624959062, 1624959062, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (18, 3, 'PO210629173102853', 'HB1322678', '1', '3', 7, 100.00, 700.00, 100, 0.00, 0.00, 0, 1624959062, 1624959062, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (19, 3, 'PO210629173102853', 'HB1322678', '1', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624959062, 1624959062, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (20, 4, 'PO210629173920640', 'GOOD210628100813162', '1', '1', 5, 100.00, 500.00, 100, 0.00, 0.00, 0, 1624959560, 1624959560, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (21, 4, 'PO210629173920640', 'GOOD210628100813162', '1', '2', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624959560, 1624959560, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (22, 4, 'PO210629173920640', 'GOOD210628100813162', '1', '3', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624959560, 1624959560, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (23, 4, 'PO210629173920640', 'GOOD210628100813162', '1', '4', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1624959560, 1624959560, 0, 1, 1);
INSERT INTO `hr_purchase_orders_details` VALUES (28, 6, 'PO210629180551123', 'HB1322678', '1', '11', 5, 100.00, 500.00, 100, 0.00, 0.00, 0, 1624961151, 1624961151, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (29, 6, 'PO210629180551123', 'HB1322678', '1', '3', 6, 100.00, 600.00, 100, 0.00, 0.00, 0, 1624961151, 1624961151, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (30, 6, 'PO210629180551123', 'HB1322678', '1', '4', 7, 100.00, 700.00, 100, 0.00, 0.00, 0, 1624961151, 1624961151, 0, 1, 5);
INSERT INTO `hr_purchase_orders_details` VALUES (35, 7, 'PO210702115651721', 'GOOD210701105038299', '17', '14', 10, 100.00, 1000.00, 100, 0.00, 0.00, 0, 1625198223, 1625198223, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (36, 7, 'PO210702115651721', 'GOOD210701105038299', '17', '15', 10, 100.00, 1000.00, 100, 0.00, 0.00, 0, 1625198223, 1625198223, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (37, 7, 'PO210702115651721', 'GOOD210701105038299', '18', '14', 10, 100.00, 1000.00, 100, 0.00, 0.00, 0, 1625198223, 1625198223, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (38, 7, 'PO210702115651721', 'GOOD210701105038299', '18', '15', 10, 100.00, 1000.00, 100, 0.00, 0.00, 0, 1625198223, 1625198223, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (39, 8, 'PO210702171936729', 'GOOD210701105038299', '17', '14', 15, 100.00, 1500.00, 100, 0.00, 0.00, 0, 1625217576, 1625217576, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (40, 8, 'PO210702171936729', 'GOOD210701105038299', '17', '15', 15, 100.00, 1500.00, 100, 0.00, 0.00, 0, 1625217576, 1625217576, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (41, 8, 'PO210702171936729', 'GOOD210701105038299', '18', '14', 15, 100.00, 1500.00, 100, 0.00, 0.00, 0, 1625217576, 1625217576, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (42, 8, 'PO210702171936729', 'GOOD210701105038299', '18', '15', 15, 100.00, 1500.00, 100, 0.00, 0.00, 0, 1625217576, 1625217576, 0, 6, 7);
INSERT INTO `hr_purchase_orders_details` VALUES (43, 5, 'PO210629174407240', 'GOOD210628105513015', '5', '7', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1625457631, 1625457631, 0, 3, 3);
INSERT INTO `hr_purchase_orders_details` VALUES (44, 5, 'PO210629174407240', 'GOOD210628105513015', '5', '8', 1, 100.00, 100.00, 100, 0.00, 0.00, 0, 1625457631, 1625457631, 0, 3, 3);
INSERT INTO `hr_purchase_orders_details` VALUES (45, 10, 'PO210827112608276', 'GOOD210628100810025', '21', '18', 10, 50.00, 500.00, 100, 0.00, 0.00, 0, 1630034768, 1630034768, 0, 8, 11);
INSERT INTO `hr_purchase_orders_details` VALUES (46, 10, 'PO210827112608276', 'GOOD210628100810025', '21', '19', 10, 50.00, 500.00, 100, 0.00, 0.00, 0, 1630034768, 1630034768, 0, 8, 11);

-- ----------------------------
-- Table structure for hr_purchase_plan
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_plan`;
CREATE TABLE `hr_purchase_plan`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `supplier_id` int(11) DEFAULT 0 COMMENT '供应商编号',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库编号',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `orders_pmoney` decimal(10, 2) DEFAULT NULL COMMENT '单据金额',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `plan_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_purchase_plan
-- ----------------------------
INSERT INTO `hr_purchase_plan` VALUES (1, 'PO210825114555378', 0, 0, 0, 0, NULL, 7, NULL, 0, 1629863155, 1629863155, 0, 1, NULL);

-- ----------------------------
-- Table structure for hr_purchase_plan_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_plan_details`;
CREATE TABLE `hr_purchase_plan_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) DEFAULT NULL COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色ID',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_rprice` decimal(10, 2) DEFAULT NULL COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '合计金额',
  `goods_discount` tinyint(4) DEFAULT 0 COMMENT '折扣',
  `goods_daprice` decimal(10, 2) DEFAULT 0.00 COMMENT '折后价',
  `goods_tdamoney` decimal(10, 2) DEFAULT 0.00 COMMENT '折后合计金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品表ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `plan_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_purchase_reject
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_reject`;
CREATE TABLE `hr_purchase_reject`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `supplier_id` int(11) DEFAULT 0 COMMENT '供应商ID',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `orders_pmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '应收金额',
  `orders_rmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '实收金额',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `reject_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `reject_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_purchase_reject
-- ----------------------------
INSERT INTO `hr_purchase_reject` VALUES (1, 'PR210825114638555', 0, 0, 0, 0.00, 0.00, 0, 0, 7, NULL, 0, 1629863198, 1629863198, 0, 1, NULL);

-- ----------------------------
-- Table structure for hr_purchase_reject_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_purchase_reject_details`;
CREATE TABLE `hr_purchase_reject_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品货号',
  `color_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '颜色ID',
  `size_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '尺码ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT 0.00 COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '金额',
  `goods_discount` tinyint(4) DEFAULT 100 COMMENT '折扣',
  `goods_daprice` decimal(10, 2) DEFAULT 0.00 COMMENT '折后价',
  `goods_tdamoney` decimal(10, 2) DEFAULT 0.00 COMMENT '折后金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_role
-- ----------------------------
DROP TABLE IF EXISTS `hr_role`;
CREATE TABLE `hr_role`  (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '角色名称',
  `role_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '角色备注',
  `power_node` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '角色权限节点',
  `role_menu` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '角色菜单节点',
  `wx_power_node` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '微信角色权限节点',
  `com_id` int(11) NOT NULL DEFAULT 0 COMMENT '企业ID',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限-角色' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_role
-- ----------------------------
INSERT INTO `hr_role` VALUES (1, '超级管理员', '超级管理员拥有所有权限', NULL, NULL, '-1', 0, 0);
INSERT INTO `hr_role` VALUES (25, '采购/库管', '采购', NULL, NULL, ',1,54,2,3,4,5,6,55,7,8,9,10,11,56,12,13,14,15,57,16,17,18,19,61,29,30,31,32,62,33,34,35,36,63,37,38,39,40,64,41,42,43,44,65,45,46,47,48,66,49,50,51,73,74,77,', 0, 1623729076);
INSERT INTO `hr_role` VALUES (27, '销售/员工', '销售/员工', NULL, NULL, ',6,55,7,8,9,10,11,56,12,13,14,15,57,16,17,18,19,20,59,21,22,23,24,60,25,26,27,28,61,29,30,31,32,63,37,38,39,40,64,41,42,43,44,65,45,46,47,48,66,49,50,51,67,68,70,71,72,73,74,75,76,77,', 0, 1624269512);
INSERT INTO `hr_role` VALUES (29, '测试1', '无库存分析', NULL, NULL, ',1,54,2,3,4,5,6,55,7,8,9,10,11,56,12,13,14,15,57,16,17,18,19,20,73,74,75,', 3, 1624936506);

-- ----------------------------
-- Table structure for hr_sale_orders
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_orders`;
CREATE TABLE `hr_sale_orders`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `client_id` int(11) DEFAULT 0 COMMENT '顾客ID',
  `member_id` int(11) UNSIGNED DEFAULT 0 COMMENT '会员id,销售单类型为1时用会员id,为0时用client_id',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `delivery_id` int(11) DEFAULT 0 COMMENT '发货方式ID',
  `orders_pmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '应收金额',
  `orders_rmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '实收金额',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `other_type` int(11) DEFAULT 0 COMMENT '其他费用',
  `other_money` decimal(9, 2) DEFAULT 0.00 COMMENT '其他费用金额',
  `erase_money` decimal(9, 2) DEFAULT 0.00 COMMENT '抹零金额',
  `profit` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '毛利',
  `salesman_id` int(11) DEFAULT NULL COMMENT '销售员ID',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  `orders_type` tinyint(1) DEFAULT 0 COMMENT '销售单类型:0销售单,1零售单',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_sale_orders
-- ----------------------------
INSERT INTO `hr_sale_orders` VALUES (1, 'SO210629092436216', 1, 0, 1, 2, 61, 760.00, 760.00, 4, 1, 0.00, 0.00, 360.00, 1, 1, 9, NULL, 1624896000, 1624929876, 1624929876, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (2, 'SO210629093001239', 1, 0, 1, 2, 61, 1896.00, 1896.00, 12, 1, 0.00, 0.00, 696.00, 1, 1, 9, NULL, 1624896000, 1624930201, 1624930201, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (3, 'SO210629173121802', 1, 0, 1, 2, 61, 1422.00, 1422.00, 9, 1, 0.00, 0.00, 522.00, 1, 1, 9, NULL, 1624896000, 1624959081, 1624959081, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (4, 'SO210629173956845', 1, 0, 6, 2, 61, 1140.00, 1140.00, 6, 1, 0.00, 0.00, 540.00, 1, 1, 9, NULL, 1624896000, 1624959596, 1624959596, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (5, 'SO210629180617062', 1, 0, 8, 2, 61, 2844.00, 2844.00, 18, 1, 0.00, 0.00, 1044.00, 1, 1, 9, NULL, 1624896000, 1624961177, 1624961177, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (6, 'SO210702163011053', 6, 0, 9, 7, 61, 380.00, 380.00, 2, 1, 0.00, 0.00, 180.00, 7, 7, 9, NULL, 1625155200, 1625214611, 1625214611, 0, 6, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (7, 'SOL210702165724707', 0, 2, 9, 7, 0, 190.00, 190.00, 1, 0, 0.00, 0.00, 90.00, 7, 7, 9, NULL, 1625216244, 1625216244, 1625216244, 0, 6, NULL, 1);
INSERT INTO `hr_sale_orders` VALUES (8, 'SOL210702170817464', 0, 2, 9, 7, 0, 190.00, 190.00, 1, 0, 0.00, 0.00, 90.00, 7, 7, 9, NULL, 1625216897, 1625216897, 1625216897, 0, 6, NULL, 1);
INSERT INTO `hr_sale_orders` VALUES (9, 'SO210702172102633', 6, 0, 9, 7, 61, 380.00, 380.00, 2, 1, 0.00, 0.00, 180.00, 7, 7, 9, NULL, 1625155200, 1625217662, 1625221274, 1, 6, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (10, 'SOL210702182411147', 0, 2, 9, 7, 0, 760.00, 9999999.99, 4, 0, 0.00, 5000000.00, 360.00, 7, 7, 9, NULL, 1625221450, 1625221450, 1625221450, 0, 6, NULL, 1);
INSERT INTO `hr_sale_orders` VALUES (11, 'SO210825114719777', 0, 0, 0, 0, 0, 0.00, 0.00, 0, 0, 0.00, 0.00, 0.00, NULL, 0, 7, NULL, 0, 1629863239, 1629863239, 0, 1, NULL, 0);
INSERT INTO `hr_sale_orders` VALUES (12, 'SO210827102029310', 8, 0, 12, 9, 61, 540.00, 540.00, 9, 1, 122.00, 0.70, 90.00, 9, 9, 9, NULL, 1629993600, 1630030829, 1630030829, 0, 8, NULL, 0);

-- ----------------------------
-- Table structure for hr_sale_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_orders_details`;
CREATE TABLE `hr_sale_orders_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT 0.00 COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '合计金额',
  `goods_discount` tinyint(4) DEFAULT 0 COMMENT '折扣',
  `goods_daprice` decimal(10, 2) DEFAULT 0.00 COMMENT '折后价',
  `goods_tdamoney` decimal(10, 2) DEFAULT 0.00 COMMENT '折后金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 159 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_sale_orders_details
-- ----------------------------
INSERT INTO `hr_sale_orders_details` VALUES (1, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 1, 1, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (2, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 1, 2, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (3, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 1, 3, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (4, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 1, 4, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (5, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 2, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (6, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 2, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (7, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 2, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (8, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 2, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (9, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 7, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (10, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 7, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (11, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 7, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (12, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 7, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (13, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 8, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (14, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 8, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (15, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 8, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (16, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 8, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (17, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 9, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (18, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 9, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (19, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 9, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (20, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 9, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (21, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 10, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (22, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 10, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (23, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 10, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (24, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 10, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (25, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 11, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (26, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 11, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (27, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 11, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (28, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 11, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (29, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 12, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (30, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 12, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (31, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 12, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (32, 1, 'SO210629092436216', 'GOOD210628100813162', 1, 12, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624929876, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (33, 2, 'SO210629093001239', 'HB1322678', 5, 1, 11, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (34, 2, 'SO210629093001239', 'HB1322678', 5, 1, 3, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (35, 2, 'SO210629093001239', 'HB1322678', 5, 1, 4, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (36, 2, 'SO210629093001239', 'HB1322678', 5, 10, 11, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (37, 2, 'SO210629093001239', 'HB1322678', 5, 10, 3, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (38, 2, 'SO210629093001239', 'HB1322678', 5, 10, 4, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (39, 2, 'SO210629093001239', 'HB1322678', 5, 9, 11, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (40, 2, 'SO210629093001239', 'HB1322678', 5, 9, 3, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (41, 2, 'SO210629093001239', 'HB1322678', 5, 9, 4, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (42, 2, 'SO210629093001239', 'HB1322678', 5, 8, 11, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (43, 2, 'SO210629093001239', 'HB1322678', 5, 8, 3, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (44, 2, 'SO210629093001239', 'HB1322678', 5, 8, 4, 1, 158.00, 158.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (45, 2, 'SO210629093001239', 'HB1322678', 5, 7, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (46, 2, 'SO210629093001239', 'HB1322678', 5, 7, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (47, 2, 'SO210629093001239', 'HB1322678', 5, 7, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (48, 2, 'SO210629093001239', 'HB1322678', 5, 2, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (49, 2, 'SO210629093001239', 'HB1322678', 5, 2, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (50, 2, 'SO210629093001239', 'HB1322678', 5, 2, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (51, 2, 'SO210629093001239', 'HB1322678', 5, 11, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (52, 2, 'SO210629093001239', 'HB1322678', 5, 11, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (53, 2, 'SO210629093001239', 'HB1322678', 5, 11, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (54, 2, 'SO210629093001239', 'HB1322678', 5, 12, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (55, 2, 'SO210629093001239', 'HB1322678', 5, 12, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (56, 2, 'SO210629093001239', 'HB1322678', 5, 12, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624930201, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (57, 3, 'SO210629173121802', 'HB1322678', 5, 1, 11, 3, 158.00, 474.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (58, 3, 'SO210629173121802', 'HB1322678', 5, 1, 3, 3, 158.00, 474.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (59, 3, 'SO210629173121802', 'HB1322678', 5, 1, 4, 3, 158.00, 474.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (60, 3, 'SO210629173121802', 'HB1322678', 5, 10, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (61, 3, 'SO210629173121802', 'HB1322678', 5, 10, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (62, 3, 'SO210629173121802', 'HB1322678', 5, 10, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (63, 3, 'SO210629173121802', 'HB1322678', 5, 9, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (64, 3, 'SO210629173121802', 'HB1322678', 5, 9, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (65, 3, 'SO210629173121802', 'HB1322678', 5, 9, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (66, 3, 'SO210629173121802', 'HB1322678', 5, 8, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (67, 3, 'SO210629173121802', 'HB1322678', 5, 8, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (68, 3, 'SO210629173121802', 'HB1322678', 5, 8, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (69, 3, 'SO210629173121802', 'HB1322678', 5, 7, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (70, 3, 'SO210629173121802', 'HB1322678', 5, 7, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (71, 3, 'SO210629173121802', 'HB1322678', 5, 7, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (72, 3, 'SO210629173121802', 'HB1322678', 5, 2, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (73, 3, 'SO210629173121802', 'HB1322678', 5, 2, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (74, 3, 'SO210629173121802', 'HB1322678', 5, 2, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (75, 3, 'SO210629173121802', 'HB1322678', 5, 11, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (76, 3, 'SO210629173121802', 'HB1322678', 5, 11, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (77, 3, 'SO210629173121802', 'HB1322678', 5, 11, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (78, 3, 'SO210629173121802', 'HB1322678', 5, 12, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (79, 3, 'SO210629173121802', 'HB1322678', 5, 12, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (80, 3, 'SO210629173121802', 'HB1322678', 5, 12, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624959081, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (81, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 1, 1, 6, 190.00, 1140.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (82, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 1, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (83, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 1, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (84, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 1, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (85, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 2, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (86, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 2, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (87, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 2, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (88, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 2, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (89, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 7, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (90, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 7, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (91, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 7, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (92, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 7, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (93, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 8, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (94, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 8, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (95, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 8, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (96, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 8, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (97, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 9, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (98, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 9, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (99, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 9, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (100, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 9, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (101, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 10, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (102, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 10, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (103, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 10, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (104, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 10, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (105, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 11, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (106, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 11, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (107, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 11, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (108, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 11, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (109, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 12, 1, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (110, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 12, 2, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (111, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 12, 3, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (112, 4, 'SO210629173956845', 'GOOD210628100813162', 1, 12, 4, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1624959596, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (113, 5, 'SO210629180617062', 'HB1322678', 5, 1, 11, 6, 158.00, 948.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (114, 5, 'SO210629180617062', 'HB1322678', 5, 1, 3, 6, 158.00, 948.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (115, 5, 'SO210629180617062', 'HB1322678', 5, 1, 4, 6, 158.00, 948.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (116, 5, 'SO210629180617062', 'HB1322678', 5, 10, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (117, 5, 'SO210629180617062', 'HB1322678', 5, 10, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (118, 5, 'SO210629180617062', 'HB1322678', 5, 10, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (119, 5, 'SO210629180617062', 'HB1322678', 5, 9, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (120, 5, 'SO210629180617062', 'HB1322678', 5, 9, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (121, 5, 'SO210629180617062', 'HB1322678', 5, 9, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (122, 5, 'SO210629180617062', 'HB1322678', 5, 8, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (123, 5, 'SO210629180617062', 'HB1322678', 5, 8, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (124, 5, 'SO210629180617062', 'HB1322678', 5, 8, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (125, 5, 'SO210629180617062', 'HB1322678', 5, 7, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (126, 5, 'SO210629180617062', 'HB1322678', 5, 7, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (127, 5, 'SO210629180617062', 'HB1322678', 5, 7, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (128, 5, 'SO210629180617062', 'HB1322678', 5, 2, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (129, 5, 'SO210629180617062', 'HB1322678', 5, 2, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (130, 5, 'SO210629180617062', 'HB1322678', 5, 2, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (131, 5, 'SO210629180617062', 'HB1322678', 5, 11, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (132, 5, 'SO210629180617062', 'HB1322678', 5, 11, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (133, 5, 'SO210629180617062', 'HB1322678', 5, 11, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (134, 5, 'SO210629180617062', 'HB1322678', 5, 12, 11, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (135, 5, 'SO210629180617062', 'HB1322678', 5, 12, 3, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (136, 5, 'SO210629180617062', 'HB1322678', 5, 12, 4, 0, 158.00, 0.00, 0, 0.00, 0.00, 0, 1624961177, 0, 0, 1);
INSERT INTO `hr_sale_orders_details` VALUES (137, 6, 'SO210702163011053', 'GOOD210701105038299', 7, 17, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1625214611, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (138, 6, 'SO210702163011053', 'GOOD210701105038299', 7, 17, 15, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1625214611, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (139, 6, 'SO210702163011053', 'GOOD210701105038299', 7, 18, 14, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1625214611, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (140, 6, 'SO210702163011053', 'GOOD210701105038299', 7, 18, 15, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1625214611, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (141, 7, 'SOL210702165724707', 'GOOD210701105038299', 7, 17, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625216244, 1625216244, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (142, 8, 'SOL210702170817464', 'GOOD210701105038299', 7, 17, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625216897, 1625216897, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (147, 9, 'SO210702172102633', 'GOOD210701105038299', 7, 17, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1625221274, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (148, 9, 'SO210702172102633', 'GOOD210701105038299', 7, 17, 15, 1, 190.00, 190.00, 0, 0.00, 0.00, 0, 1625221274, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (149, 9, 'SO210702172102633', 'GOOD210701105038299', 7, 18, 14, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1625221274, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (150, 9, 'SO210702172102633', 'GOOD210701105038299', 7, 18, 15, 0, 190.00, 0.00, 0, 0.00, 0.00, 0, 1625221274, 0, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (151, 10, 'SOL210702182411147', 'GOOD210701105038299', 7, 17, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625221450, 1625221450, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (152, 10, 'SOL210702182411147', 'GOOD210701105038299', 7, 17, 15, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625221450, 1625221450, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (153, 10, 'SOL210702182411147', 'GOOD210701105038299', 7, 18, 14, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625221450, 1625221450, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (154, 10, 'SOL210702182411147', 'GOOD210701105038299', 7, 18, 15, 1, 190.00, 190.00, 0, 0.00, 0.00, 1, 1625221450, 1625221450, 0, 6);
INSERT INTO `hr_sale_orders_details` VALUES (155, 12, 'SO210827102029310', 'GOOD210628100810025', 11, 21, 18, 3, 60.00, 180.00, 0, 0.00, 0.00, 0, 1630030829, 0, 0, 8);
INSERT INTO `hr_sale_orders_details` VALUES (156, 12, 'SO210827102029310', 'GOOD210628100810025', 11, 21, 19, 6, 60.00, 360.00, 0, 0.00, 0.00, 0, 1630030829, 0, 0, 8);
INSERT INTO `hr_sale_orders_details` VALUES (157, 12, 'SO210827102029310', 'GOOD210628100810025', 11, 22, 18, 0, 60.00, 0.00, 0, 0.00, 0.00, 0, 1630030829, 0, 0, 8);
INSERT INTO `hr_sale_orders_details` VALUES (158, 12, 'SO210827102029310', 'GOOD210628100810025', 11, 22, 19, 0, 60.00, 0.00, 0, 0.00, 0.00, 0, 1630030829, 0, 0, 8);

-- ----------------------------
-- Table structure for hr_sale_plan
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_plan`;
CREATE TABLE `hr_sale_plan`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `client_id` int(11) DEFAULT 0 COMMENT '顾客ID',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `orders_pmoney` decimal(10, 2) DEFAULT NULL COMMENT '单据金额',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_sale_plan
-- ----------------------------
INSERT INTO `hr_sale_plan` VALUES (1, 'SP210825114715947', 0, 0, 0, NULL, 7, NULL, 0, 1629863235, 1629863235, 0, 1, NULL);

-- ----------------------------
-- Table structure for hr_sale_plan_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_plan_details`;
CREATE TABLE `hr_sale_plan_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT NULL COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT NULL COMMENT '金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新事件',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_sale_reject_apply
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_reject_apply`;
CREATE TABLE `hr_sale_reject_apply`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `client_id` int(11) DEFAULT 0 COMMENT '顾客ID',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `orders_pmoney` decimal(10, 2) DEFAULT NULL COMMENT '单据金额',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_sale_reject_apply_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_reject_apply_details`;
CREATE TABLE `hr_sale_reject_apply_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT NULL COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT NULL COMMENT '金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新事件',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_sale_reject_orders
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_reject_orders`;
CREATE TABLE `hr_sale_reject_orders`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `client_id` int(11) DEFAULT 0 COMMENT '顾客ID',
  `orders_date` int(11) DEFAULT 0 COMMENT '开单日期',
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库ID',
  `settlement_id` int(11) DEFAULT 0 COMMENT '结算账户ID',
  `delivery_id` int(11) DEFAULT 0 COMMENT '发货方式ID',
  `orders_pmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '应付金额',
  `orders_rmoney` decimal(9, 2) DEFAULT 0.00 COMMENT '实付金额',
  `orders_emoney` decimal(9, 2) DEFAULT 0.00 COMMENT '抹零金额',
  `salesman_id` int(11) DEFAULT NULL COMMENT '销售员ID',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_sale_reject_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_sale_reject_orders_details`;
CREATE TABLE `hr_sale_reject_orders_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `goods_type` tinyint(4) DEFAULT 2 COMMENT '商品类型1、 销售2、退货',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `goods_price` decimal(10, 2) DEFAULT 0.00 COMMENT '单价',
  `goods_tmoney` decimal(10, 2) DEFAULT 0.00 COMMENT '合计金额',
  `goods_discount` tinyint(4) DEFAULT 0 COMMENT '折扣',
  `goods_daprice` decimal(10, 2) DEFAULT 0.00 COMMENT '折后价',
  `goods_tdamoney` decimal(10, 2) DEFAULT 0.00 COMMENT '折后金额',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hr_settlement
-- ----------------------------
DROP TABLE IF EXISTS `hr_settlement`;
CREATE TABLE `hr_settlement`  (
  `settlement_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `settlement_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '账户名称',
  `settlement_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '帐号',
  `account_holder` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '开户人',
  `subjection_store` int(11) DEFAULT 0 COMMENT '隶属门店ID',
  `account_type` int(11) DEFAULT 0 COMMENT '账户类型ID',
  `settlement_money` decimal(10, 2) DEFAULT 0.00 COMMENT '账户金额',
  `settlement_status` tinyint(4) DEFAULT 0 COMMENT '状态',
  `settlement_remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `lock_version` int(11) DEFAULT 0 COMMENT '锁版本',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`settlement_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '结算账户' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_settlement
-- ----------------------------
INSERT INTO `hr_settlement` VALUES (1, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1624843221, 1624843221, 1);
INSERT INTO `hr_settlement` VALUES (2, '默认结算', '88888888', '管理员', 0, 0, 8062.00, 1, NULL, 0, 5, 1624846092, 1624846092, 1);
INSERT INTO `hr_settlement` VALUES (3, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1624846142, 1624846142, 2);
INSERT INTO `hr_settlement` VALUES (4, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1624848912, 1624848912, 3);
INSERT INTO `hr_settlement` VALUES (5, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1624929816, 1624929816, 4);
INSERT INTO `hr_settlement` VALUES (6, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1624936884, 1624936884, 5);
INSERT INTO `hr_settlement` VALUES (7, '默认结算', '88888888', '管理员', 0, 0, 760.00, 1, NULL, 0, 2, 1625107837, 1625107837, 6);
INSERT INTO `hr_settlement` VALUES (8, '默认结算', '88888888', '管理员', 0, 0, 0.00, 1, NULL, 0, 0, 1625110509, 1625110509, 7);
INSERT INTO `hr_settlement` VALUES (9, '默认结算', '88888888', '管理员', 0, 0, 540.00, 1, NULL, 0, 1, 1629878732, 1629878732, 8);

-- ----------------------------
-- Table structure for hr_size
-- ----------------------------
DROP TABLE IF EXISTS `hr_size`;
CREATE TABLE `hr_size`  (
  `size_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `size_group` int(11) DEFAULT NULL COMMENT '色组',
  `size_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '类别名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `lock_version` int(11) DEFAULT 0 COMMENT '锁版本',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `size_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '尺码编码',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`size_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_size
-- ----------------------------
INSERT INTO `hr_size` VALUES (1, NULL, 'L', NULL, 0, 1624843221, 'L1', 1, 1624843221);
INSERT INTO `hr_size` VALUES (2, NULL, 'XL', NULL, 0, 1624843221, 'XL1', 1, 1624843221);
INSERT INTO `hr_size` VALUES (3, NULL, 'L', NULL, 0, 1624846092, 'L1', 1, 1624846092);
INSERT INTO `hr_size` VALUES (4, NULL, 'XL', NULL, 0, 1624846092, 'XL1', 1, 1624846092);
INSERT INTO `hr_size` VALUES (5, NULL, 'L', NULL, 0, 1624846142, 'L1', 2, 1624846142);
INSERT INTO `hr_size` VALUES (6, NULL, 'XL', NULL, 0, 1624846142, 'XL1', 2, 1624846142);
INSERT INTO `hr_size` VALUES (7, NULL, 'L', NULL, 0, 1624848912, 'L1', 3, 1624848912);
INSERT INTO `hr_size` VALUES (8, NULL, 'XL', NULL, 0, 1624848912, 'XL1', 3, 1624848912);
INSERT INTO `hr_size` VALUES (9, NULL, 'L', NULL, 0, 1624929816, 'L1', 4, 1624929816);
INSERT INTO `hr_size` VALUES (10, NULL, 'XL', NULL, 0, 1624929816, 'XL1', 4, 1624929816);
INSERT INTO `hr_size` VALUES (11, NULL, 'M', NULL, 0, 1624930114, 'M1', 1, 1624930114);
INSERT INTO `hr_size` VALUES (12, NULL, 'L', NULL, 0, 1624936884, 'L1', 5, 1624936884);
INSERT INTO `hr_size` VALUES (13, NULL, 'XL', NULL, 0, 1624936884, 'XL1', 5, 1624936884);
INSERT INTO `hr_size` VALUES (14, NULL, 'L', NULL, 0, 1625107837, 'L1', 6, 1625107837);
INSERT INTO `hr_size` VALUES (15, NULL, 'XL', NULL, 0, 1625107837, 'XL1', 6, 1625107837);
INSERT INTO `hr_size` VALUES (16, NULL, 'L', NULL, 0, 1625110509, 'L1', 7, 1625110509);
INSERT INTO `hr_size` VALUES (17, NULL, 'XL', NULL, 0, 1625110509, 'XL1', 7, 1625110509);
INSERT INTO `hr_size` VALUES (18, NULL, 'L', NULL, 0, 1629878732, 'L1', 8, 1629878732);
INSERT INTO `hr_size` VALUES (19, NULL, 'XL', NULL, 0, 1629878732, 'XL1', 8, 1629878732);
INSERT INTO `hr_size` VALUES (20, NULL, 'XXL', NULL, 0, 1629960944, 'XXL1', 8, 1629960944);

-- ----------------------------
-- Table structure for hr_stock_diary
-- ----------------------------
DROP TABLE IF EXISTS `hr_stock_diary`;
CREATE TABLE `hr_stock_diary`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT 0 COMMENT '仓库编号',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_id` int(11) UNSIGNED DEFAULT NULL COMMENT '商品id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `stock_number` int(11) DEFAULT 0 COMMENT '库存数量',
  `create_time` int(11) DEFAULT 0 COMMENT '业务时间',
  `orders_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '业务类别',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 205 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '库存日志' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_stock_diary
-- ----------------------------
INSERT INTO `hr_stock_diary` VALUES (1, 1, 'PO210629092415835', 1, 'GOOD210628100813162', 1, 1, NULL, NULL, 1624929856, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (2, 1, 'PO210629092415835', 1, 'GOOD210628100813162', 1, 2, NULL, NULL, 1624929856, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (3, 1, 'PO210629092415835', 1, 'GOOD210628100813162', 1, 3, NULL, NULL, 1624929856, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (4, 1, 'PO210629092415835', 1, 'GOOD210628100813162', 1, 4, NULL, NULL, 1624929856, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (5, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 1, 1, 1, 0, 1624929876, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (6, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 1, 2, 1, 0, 1624929876, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (7, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 1, 3, 1, 0, 1624929876, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (8, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 1, 4, 1, 0, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (9, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 2, 1, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (10, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 2, 2, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (11, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 2, 3, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (12, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 2, 4, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (13, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 7, 1, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (14, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 7, 2, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (15, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 7, 3, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (16, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 7, 4, NULL, NULL, 1624929877, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (17, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 8, 1, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (18, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 8, 2, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (19, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 8, 3, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (20, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 8, 4, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (21, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 9, 1, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (22, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 9, 2, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (23, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 9, 3, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (24, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 9, 4, NULL, NULL, 1624929878, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (25, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 10, 1, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (26, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 10, 2, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (27, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 10, 3, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (28, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 10, 4, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (29, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 11, 1, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (30, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 11, 2, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (31, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 11, 3, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (32, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 11, 4, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (33, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 12, 1, NULL, NULL, 1624929879, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (34, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 12, 2, NULL, NULL, 1624929880, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (35, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 12, 3, NULL, NULL, 1624929880, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (36, 1, 'SO210629092436216', 1, 'GOOD210628100813162', 12, 4, NULL, NULL, 1624929880, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (37, 1, 'PO210629092937014', 5, 'HB1322678', 10, 11, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (38, 1, 'PO210629092937014', 5, 'HB1322678', 10, 3, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (39, 1, 'PO210629092937014', 5, 'HB1322678', 10, 4, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (40, 1, 'PO210629092937014', 5, 'HB1322678', 9, 11, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (41, 1, 'PO210629092937014', 5, 'HB1322678', 9, 3, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (42, 1, 'PO210629092937014', 5, 'HB1322678', 9, 4, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (43, 1, 'PO210629092937014', 5, 'HB1322678', 8, 11, NULL, NULL, 1624930177, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (44, 1, 'PO210629092937014', 5, 'HB1322678', 8, 3, NULL, NULL, 1624930178, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (45, 1, 'PO210629092937014', 5, 'HB1322678', 8, 4, NULL, NULL, 1624930178, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (46, 1, 'PO210629092937014', 5, 'HB1322678', 7, 11, NULL, NULL, 1624930178, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (47, 1, 'PO210629092937014', 5, 'HB1322678', 7, 3, NULL, NULL, 1624930178, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (48, 1, 'PO210629092937014', 5, 'HB1322678', 7, 4, NULL, NULL, 1624930178, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (49, 1, 'SO210629093001239', 5, 'HB1322678', 1, 11, NULL, NULL, 1624930201, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (50, 1, 'SO210629093001239', 5, 'HB1322678', 1, 3, NULL, NULL, 1624930201, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (51, 1, 'SO210629093001239', 5, 'HB1322678', 1, 4, NULL, NULL, 1624930201, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (52, 1, 'SO210629093001239', 5, 'HB1322678', 10, 11, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (53, 1, 'SO210629093001239', 5, 'HB1322678', 10, 3, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (54, 1, 'SO210629093001239', 5, 'HB1322678', 10, 4, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (55, 1, 'SO210629093001239', 5, 'HB1322678', 9, 11, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (56, 1, 'SO210629093001239', 5, 'HB1322678', 9, 3, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (57, 1, 'SO210629093001239', 5, 'HB1322678', 9, 4, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (58, 1, 'SO210629093001239', 5, 'HB1322678', 8, 11, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (59, 1, 'SO210629093001239', 5, 'HB1322678', 8, 3, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (60, 1, 'SO210629093001239', 5, 'HB1322678', 8, 4, 1, 0, 1624930202, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (61, 1, 'SO210629093001239', 5, 'HB1322678', 7, 11, 0, 1, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (62, 1, 'SO210629093001239', 5, 'HB1322678', 7, 3, 0, 1, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (63, 1, 'SO210629093001239', 5, 'HB1322678', 7, 4, 0, 1, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (64, 1, 'SO210629093001239', 5, 'HB1322678', 2, 11, NULL, NULL, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (65, 1, 'SO210629093001239', 5, 'HB1322678', 2, 3, NULL, NULL, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (66, 1, 'SO210629093001239', 5, 'HB1322678', 2, 4, NULL, NULL, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (67, 1, 'SO210629093001239', 5, 'HB1322678', 11, 11, NULL, NULL, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (68, 1, 'SO210629093001239', 5, 'HB1322678', 11, 3, NULL, NULL, 1624930203, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (69, 1, 'SO210629093001239', 5, 'HB1322678', 11, 4, NULL, NULL, 1624930204, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (70, 1, 'SO210629093001239', 5, 'HB1322678', 12, 11, NULL, NULL, 1624930204, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (71, 1, 'SO210629093001239', 5, 'HB1322678', 12, 3, NULL, NULL, 1624930204, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (72, 1, 'SO210629093001239', 5, 'HB1322678', 12, 4, NULL, NULL, 1624930204, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (73, 1, 'PO210629173102853', 5, 'HB1322678', 1, 11, 1, 0, 1624959063, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (74, 1, 'PO210629173102853', 5, 'HB1322678', 1, 3, 7, 6, 1624959063, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (75, 1, 'PO210629173102853', 5, 'HB1322678', 1, 4, 1, 0, 1624959063, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (76, 1, 'SO210629173121802', 5, 'HB1322678', 1, 11, 3, -3, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (77, 1, 'SO210629173121802', 5, 'HB1322678', 1, 3, 3, 3, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (78, 1, 'SO210629173121802', 5, 'HB1322678', 1, 4, 3, -3, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (79, 1, 'SO210629173121802', 5, 'HB1322678', 10, 11, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (80, 1, 'SO210629173121802', 5, 'HB1322678', 10, 3, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (81, 1, 'SO210629173121802', 5, 'HB1322678', 10, 4, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (82, 1, 'SO210629173121802', 5, 'HB1322678', 9, 11, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (83, 1, 'SO210629173121802', 5, 'HB1322678', 9, 3, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (84, 1, 'SO210629173121802', 5, 'HB1322678', 9, 4, 0, 0, 1624959082, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (85, 1, 'SO210629173121802', 5, 'HB1322678', 8, 11, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (86, 1, 'SO210629173121802', 5, 'HB1322678', 8, 3, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (87, 1, 'SO210629173121802', 5, 'HB1322678', 8, 4, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (88, 1, 'SO210629173121802', 5, 'HB1322678', 7, 11, 0, 1, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (89, 1, 'SO210629173121802', 5, 'HB1322678', 7, 3, 0, 1, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (90, 1, 'SO210629173121802', 5, 'HB1322678', 7, 4, 0, 1, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (91, 1, 'SO210629173121802', 5, 'HB1322678', 2, 11, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (92, 1, 'SO210629173121802', 5, 'HB1322678', 2, 3, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (93, 1, 'SO210629173121802', 5, 'HB1322678', 2, 4, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (94, 1, 'SO210629173121802', 5, 'HB1322678', 11, 11, 0, 0, 1624959083, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (95, 1, 'SO210629173121802', 5, 'HB1322678', 11, 3, 0, 0, 1624959084, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (96, 1, 'SO210629173121802', 5, 'HB1322678', 11, 4, 0, 0, 1624959084, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (97, 1, 'SO210629173121802', 5, 'HB1322678', 12, 11, 0, 0, 1624959084, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (98, 1, 'SO210629173121802', 5, 'HB1322678', 12, 3, 0, 0, 1624959084, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (99, 1, 'SO210629173121802', 5, 'HB1322678', 12, 4, 0, 0, 1624959084, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (100, 6, 'PO210629173920640', 1, 'GOOD210628100813162', 1, 1, NULL, NULL, 1624959560, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (101, 6, 'PO210629173920640', 1, 'GOOD210628100813162', 1, 2, NULL, NULL, 1624959560, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (102, 6, 'PO210629173920640', 1, 'GOOD210628100813162', 1, 3, NULL, NULL, 1624959561, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (103, 6, 'PO210629173920640', 1, 'GOOD210628100813162', 1, 4, NULL, NULL, 1624959561, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (104, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 1, 1, 6, -1, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (105, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 1, 2, 0, 1, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (106, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 1, 3, 0, 1, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (107, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 1, 4, 0, 1, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (108, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 2, 1, NULL, NULL, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (109, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 2, 2, NULL, NULL, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (110, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 2, 3, NULL, NULL, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (111, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 2, 4, NULL, NULL, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (112, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 7, 1, NULL, NULL, 1624959597, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (113, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 7, 2, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (114, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 7, 3, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (115, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 7, 4, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (116, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 8, 1, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (117, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 8, 2, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (118, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 8, 3, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (119, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 8, 4, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (120, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 9, 1, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (121, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 9, 2, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (122, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 9, 3, NULL, NULL, 1624959598, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (123, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 9, 4, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (124, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 10, 1, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (125, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 10, 2, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (126, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 10, 3, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (127, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 10, 4, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (128, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 11, 1, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (129, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 11, 2, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (130, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 11, 3, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (131, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 11, 4, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (132, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 12, 1, NULL, NULL, 1624959599, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (133, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 12, 2, NULL, NULL, 1624959600, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (134, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 12, 3, NULL, NULL, 1624959600, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (135, 6, 'SO210629173956845', 1, 'GOOD210628100813162', 12, 4, NULL, NULL, 1624959600, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (136, 8, 'PO210629180551123', 5, 'HB1322678', 1, 11, NULL, NULL, 1624961151, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (137, 8, 'PO210629180551123', 5, 'HB1322678', 1, 3, NULL, NULL, 1624961151, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (138, 8, 'PO210629180551123', 5, 'HB1322678', 1, 4, NULL, NULL, 1624961151, '采购单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (139, 8, 'SO210629180617062', 5, 'HB1322678', 1, 11, 6, -1, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (140, 8, 'SO210629180617062', 5, 'HB1322678', 1, 3, 6, 0, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (141, 8, 'SO210629180617062', 5, 'HB1322678', 1, 4, 6, 1, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (142, 8, 'SO210629180617062', 5, 'HB1322678', 10, 11, NULL, NULL, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (143, 8, 'SO210629180617062', 5, 'HB1322678', 10, 3, NULL, NULL, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (144, 8, 'SO210629180617062', 5, 'HB1322678', 10, 4, NULL, NULL, 1624961177, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (145, 8, 'SO210629180617062', 5, 'HB1322678', 9, 11, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (146, 8, 'SO210629180617062', 5, 'HB1322678', 9, 3, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (147, 8, 'SO210629180617062', 5, 'HB1322678', 9, 4, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (148, 8, 'SO210629180617062', 5, 'HB1322678', 8, 11, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (149, 8, 'SO210629180617062', 5, 'HB1322678', 8, 3, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (150, 8, 'SO210629180617062', 5, 'HB1322678', 8, 4, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (151, 8, 'SO210629180617062', 5, 'HB1322678', 7, 11, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (152, 8, 'SO210629180617062', 5, 'HB1322678', 7, 3, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (153, 8, 'SO210629180617062', 5, 'HB1322678', 7, 4, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (154, 8, 'SO210629180617062', 5, 'HB1322678', 2, 11, NULL, NULL, 1624961178, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (155, 8, 'SO210629180617062', 5, 'HB1322678', 2, 3, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (156, 8, 'SO210629180617062', 5, 'HB1322678', 2, 4, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (157, 8, 'SO210629180617062', 5, 'HB1322678', 11, 11, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (158, 8, 'SO210629180617062', 5, 'HB1322678', 11, 3, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (159, 8, 'SO210629180617062', 5, 'HB1322678', 11, 4, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (160, 8, 'SO210629180617062', 5, 'HB1322678', 12, 11, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (161, 8, 'SO210629180617062', 5, 'HB1322678', 12, 3, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (162, 8, 'SO210629180617062', 5, 'HB1322678', 12, 4, NULL, NULL, 1624961179, '销售单', 1, 0);
INSERT INTO `hr_stock_diary` VALUES (163, 9, 'PO210702115651721', 7, 'GOOD210701105038299', 17, 14, NULL, NULL, 1625198223, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (164, 9, 'PO210702115651721', 7, 'GOOD210701105038299', 17, 15, NULL, NULL, 1625198223, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (165, 9, 'PO210702115651721', 7, 'GOOD210701105038299', 18, 14, NULL, NULL, 1625198223, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (166, 9, 'PO210702115651721', 7, 'GOOD210701105038299', 18, 15, NULL, NULL, 1625198224, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (167, 9, 'SO210702163011053', 7, 'GOOD210701105038299', 17, 14, 1, 9, 1625214611, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (168, 9, 'SO210702163011053', 7, 'GOOD210701105038299', 17, 15, 1, 9, 1625214611, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (169, 9, 'SO210702163011053', 7, 'GOOD210701105038299', 18, 14, 0, 10, 1625214611, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (170, 9, 'SO210702163011053', 7, 'GOOD210701105038299', 18, 15, 0, 10, 1625214612, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (171, 9, 'SI210702163515170', 7, 'GOOD210701105038299', 17, 14, 1, 9, 1625214916, '盘点单-提交正式', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (172, 9, 'SI210702163515170', 7, 'GOOD210701105038299', 17, 15, 1, 9, 1625214916, '盘点单-提交正式', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (173, 9, 'SI210702163515170', 7, 'GOOD210701105038299', 18, 14, 0, 10, 1625214916, '盘点单-提交正式', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (174, 9, 'SI210702163515170', 7, 'GOOD210701105038299', 18, 15, 0, 10, 1625214916, '盘点单-提交正式', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (175, 9, 'SOL210702165724707', 7, 'GOOD210701105038299', 17, 14, -1, 1, 1625216245, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (176, 9, 'SOL210702170817464', 7, 'GOOD210701105038299', 17, 14, -1, 0, 1625216898, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (177, 9, 'PO210702171936729', 7, 'GOOD210701105038299', 17, 14, 15, 14, 1625217577, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (178, 9, 'PO210702171936729', 7, 'GOOD210701105038299', 17, 15, 15, 16, 1625217577, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (179, 9, 'PO210702171936729', 7, 'GOOD210701105038299', 18, 14, 15, 15, 1625217577, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (180, 9, 'PO210702171936729', 7, 'GOOD210701105038299', 18, 15, 15, 15, 1625217577, '采购单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (181, 9, 'SO210702172102633', 7, 'GOOD210701105038299', 17, 14, 1, 13, 1625221274, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (182, 9, 'SO210702172102633', 7, 'GOOD210701105038299', 17, 15, 1, 15, 1625221274, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (183, 9, 'SO210702172102633', 7, 'GOOD210701105038299', 18, 14, 0, 15, 1625221275, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (184, 9, 'SO210702172102633', 7, 'GOOD210701105038299', 18, 15, 0, 15, 1625221275, '销售单', 6, 0);
INSERT INTO `hr_stock_diary` VALUES (185, 9, 'SOL210702182411147', 7, 'GOOD210701105038299', 17, 14, -1, 13, 1625221452, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (186, 9, 'SOL210702182411147', 7, 'GOOD210701105038299', 17, 15, -1, 15, 1625221452, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (187, 9, 'SOL210702182411147', 7, 'GOOD210701105038299', 18, 14, -1, 15, 1625221452, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (188, 9, 'SOL210702182411147', 7, 'GOOD210701105038299', 18, 15, -1, 15, 1625221452, '零售单-出库', 6, NULL);
INSERT INTO `hr_stock_diary` VALUES (189, 3, 'PO210629174407240', 3, 'GOOD210628105513015', 5, 7, NULL, NULL, 1625457631, '采购单', 3, 0);
INSERT INTO `hr_stock_diary` VALUES (190, 3, 'PO210629174407240', 3, 'GOOD210628105513015', 5, 8, NULL, NULL, 1625457631, '采购单', 3, 0);
INSERT INTO `hr_stock_diary` VALUES (191, 12, 'SO210827102029310', 11, 'GOOD210628100810025', 21, 18, NULL, NULL, 1630030829, '销售单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (192, 12, 'SO210827102029310', 11, 'GOOD210628100810025', 21, 19, NULL, NULL, 1630030829, '销售单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (193, 12, 'SO210827102029310', 11, 'GOOD210628100810025', 22, 18, NULL, NULL, 1630030829, '销售单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (194, 12, 'SO210827102029310', 11, 'GOOD210628100810025', 22, 19, NULL, NULL, 1630030829, '销售单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (195, 12, 'PO210827112608276', 11, 'GOOD210628100810025', 21, 18, 10, 7, 1630034768, '采购单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (196, 12, 'PO210827112608276', 11, 'GOOD210628100810025', 21, 19, 10, 4, 1630034768, '采购单', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (197, 12, 'SI210827112738644', 11, 'GOOD210628100810025', 21, 18, 5, 7, 1630034858, '盘点单-提交正式', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (198, 12, 'SI210827112738644', 11, 'GOOD210628100810025', 21, 19, 5, 4, 1630034858, '盘点单-提交正式', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (199, 12, 'SI210827112738644', 11, 'GOOD210628100810025', 22, 18, 0, 0, 1630034858, '盘点单-提交正式', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (200, 12, 'SI210827112738644', 11, 'GOOD210628100810025', 22, 19, 0, 0, 1630034858, '盘点单-提交正式', 8, 0);
INSERT INTO `hr_stock_diary` VALUES (201, 11, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 18, 3, 0, 1630034918, '调拨单-入库', 8, 1);
INSERT INTO `hr_stock_diary` VALUES (202, 11, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 19, 2, 0, 1630034918, '调拨单-入库', 8, 1);
INSERT INTO `hr_stock_diary` VALUES (203, 12, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 18, -3, 5, 1630034918, '调拨单-出库', 8, 1);
INSERT INTO `hr_stock_diary` VALUES (204, 12, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 19, -2, 5, 1630034918, '调拨单-出库', 8, 1);

-- ----------------------------
-- Table structure for hr_supplier
-- ----------------------------
DROP TABLE IF EXISTS `hr_supplier`;
CREATE TABLE `hr_supplier`  (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supplier_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商名称',
  `supplier_director` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '负责人',
  `supplier_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商电话',
  `supplier_mphone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商手机',
  `supplier_discount` tinyint(4) DEFAULT 100 COMMENT '默认折扣',
  `supplier_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商电子邮件',
  `supplier_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商地址',
  `supplier_status` tinyint(4) DEFAULT 1 COMMENT '供应商状态',
  `supplier_money` decimal(10, 2) DEFAULT 0.00 COMMENT '账户金额',
  `supplier_story` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '供应商描述',
  `sort` int(11) DEFAULT 100 COMMENT '排序',
  `update_time` int(10) UNSIGNED DEFAULT 0 COMMENT '更新时间',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`supplier_id`) USING BTREE,
  INDEX `supplier_name`(`supplier_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '供应商信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_supplier
-- ----------------------------
INSERT INTO `hr_supplier` VALUES (10, '默认供应商', '管理员', '18888888888', '18888888888', 10, '123@qq.com', '默认地址', 1, 0.00, NULL, 100, 1629878732, 1629878732, 4, 1);
INSERT INTO `hr_supplier` VALUES (11, '自由港码头', '老白', NULL, '15102562325', 10, NULL, '朝外大街111号自由港码头', 1, 1000.00, NULL, 100, 1629961025, 1629961025, 0, 1);
INSERT INTO `hr_supplier` VALUES (12, '自由港码头', '老白', NULL, '15102562325', 10, NULL, '朝外大街111号自由港码头', 1, 0.00, NULL, 100, 1629961025, 1629961025, 0, 8);

-- ----------------------------
-- Table structure for hr_transfer_orders
-- ----------------------------
DROP TABLE IF EXISTS `hr_transfer_orders`;
CREATE TABLE `hr_transfer_orders`  (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `out_warehouse_id` int(11) DEFAULT 0 COMMENT '调出仓库编号',
  `in_warehouse_id` int(11) DEFAULT 0 COMMENT '调入仓库编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '商品数量',
  `orders_date` int(11) DEFAULT 0 COMMENT '单据日期',
  `user_id` int(11) DEFAULT 0 COMMENT '制单人ID',
  `orders_status` tinyint(4) DEFAULT 0 COMMENT '单据状态0草稿9完成',
  `orders_remark` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `shop_id` int(11) DEFAULT NULL COMMENT '门店ID',
  PRIMARY KEY (`orders_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `out_warehouse_id`(`out_warehouse_id`) USING BTREE,
  INDEX `in_warehouse_id`(`in_warehouse_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '调拨单基础信息' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_transfer_orders
-- ----------------------------
INSERT INTO `hr_transfer_orders` VALUES (1, 'TF210827112838256', 12, 11, 5, 1629993600, 9, 9, '', 1630034918, 1630034918, 0, 8, 1);

-- ----------------------------
-- Table structure for hr_transfer_orders_details
-- ----------------------------
DROP TABLE IF EXISTS `hr_transfer_orders_details`;
CREATE TABLE `hr_transfer_orders_details`  (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) DEFAULT NULL COMMENT '单据ID',
  `orders_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '单据编号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `goods_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '商品货号',
  `color_id` int(11) DEFAULT 0 COMMENT '颜色编号',
  `size_id` int(11) DEFAULT 0 COMMENT '尺码编号',
  `goods_number` int(11) DEFAULT 0 COMMENT '数量',
  `out_warehouse_number` int(11) DEFAULT 0 COMMENT '调出仓库当前数量',
  `in_warehouse_number` int(11) DEFAULT 0 COMMENT '调入仓库当前数量',
  `goods_status` tinyint(4) DEFAULT 0 COMMENT '商品状态',
  `create_time` int(11) DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) DEFAULT 0 COMMENT '更新时间',
  `lock_version` int(10) UNSIGNED DEFAULT 0 COMMENT '锁版本',
  `com_id` int(11) DEFAULT NULL COMMENT '企业ID',
  PRIMARY KEY (`details_id`) USING BTREE,
  INDEX `orders_code`(`orders_code`) USING BTREE,
  INDEX `goods_code`(`goods_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '调拨单详情' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_transfer_orders_details
-- ----------------------------
INSERT INTO `hr_transfer_orders_details` VALUES (1, 1, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 18, 3, 5, 0, 1, 1630034918, 1630034918, 0, 1);
INSERT INTO `hr_transfer_orders_details` VALUES (2, 1, 'TF210827112838256', 11, 'GOOD210628100810025', 21, 19, 2, 5, 0, 1, 1630034918, 1630034918, 0, 1);

-- ----------------------------
-- Table structure for hr_user
-- ----------------------------
DROP TABLE IF EXISTS `hr_user`;
CREATE TABLE `hr_user`  (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户名称',
  `user_login_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户名称用户登录密码',
  `pass_key` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `user_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户手机',
  `user_idcode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户证件号',
  `user_role_ids` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '用户角色 1超级管理员',
  `user_login_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户编号',
  `org_id` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '组织机构ID',
  `user_story` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户描述',
  `user_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '用户状态',
  `open_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信openid(唯一标示)',
  `user_last_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `user_last_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '最后登录IP',
  `shop_id` int(11) NOT NULL DEFAULT 0 COMMENT '门店ID',
  `com_id` int(11) NOT NULL DEFAULT 0 COMMENT '企业ID',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `user_login_code`(`user_login_code`) USING BTREE,
  INDEX `user_phone`(`user_phone`) USING BTREE,
  INDEX `user_name`(`user_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_user
-- ----------------------------
INSERT INTO `hr_user` VALUES (1, '海外购', '56466d807ce7a525a2d4862727d9a2da', 'QpNBFj', '13290929596', '410411198910235524', ',1,', '', '', '', 1, 'oB8kZ41spHp2T4k54aWwK7lERfxc', 1625018726, '172.168.1.50', 0, 1, 1625018726, 1624846093);
INSERT INTO `hr_user` VALUES (2, '123', '8f7a5b93bfa5cb3a27d6707e510637f6', 'pGRYSm', '18011111111', '410411199405135551', ',1,', '', '', '', 1, '', 1624848157, '172.168.1.65', 0, 2, 1624848157, 1624846143);
INSERT INTO `hr_user` VALUES (3, 'xigua', '10e495746691e7d2dd4a38bef883c1d6', 'q17XrQ', '13233333333', '410403199103025533', ',1,', '', '', '', 1, '', 1625457601, '127.0.0.1', 0, 3, 1625457601, 1624848913);
INSERT INTO `hr_user` VALUES (4, 'tomcat', 'e436e75c4dfff6d68dea038ead43f0b3', 'dm6WWw', '13623940957', '330781198509072613', ',1,', '', '', '', 1, 'oB8kZ4663esOEEMxURPVVjQ3UyJg', 1624931652, '172.168.1.58', 0, 4, 1624931652, 1624929817);
INSERT INTO `hr_user` VALUES (5, 'xigua2', '27b25d411612d419d6c068367d2b40cc', '9Np2sz', '13222233333', '410403199103025533', ',29,', '', ',3,', '', 1, '', 1624937014, '127.0.0.1', 0, 3, 1624937014, 1624936374);
INSERT INTO `hr_user` VALUES (6, '李文奇', 'c5f0b0ba53706d76ad7b1530f1e1207a', 'cDPJFb', '13213213213', '330781198509074395', ',1,', '', '', '', 1, '', 1624936885, '172.168.1.238', 0, 5, 1624936885, 1624936885);
INSERT INTO `hr_user` VALUES (7, '范冰冰', '83886a17ae1fe336c4329b1559247d55', 'H2amJd', '18695447562', '410403199103025533', ',1,', '', '', '', 1, '', 1625279543, '127.0.0.1', 0, 6, 1625279543, 1625107838);
INSERT INTO `hr_user` VALUES (8, 'helloworld', '6f78a72c68699adc2129a95624e868b2', 'GOs2ph', '15866668888', '110101199003074354', ',1,', '', '', '', 1, 'oB8kZ41j1bj6eqrPyKsq7AoWe98E', 1625196443, '172.168.1.251', 0, 7, 1625196443, 1625110510);
INSERT INTO `hr_user` VALUES (9, 'admin', '9ed53957988e3937c7d467e31d1e9625', '6ncyiD', '18588888888', '130425199110091812', ',1,', '', '', '', 1, '', 1630032248, '127.0.0.1', 0, 8, 1630032248, 1629878733);

-- ----------------------------
-- Table structure for hr_wx_power_node
-- ----------------------------
DROP TABLE IF EXISTS `hr_wx_power_node`;
CREATE TABLE `hr_wx_power_node`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父节点',
  `node_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '节点名称',
  `node_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单url',
  `controller_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '方法名',
  `create_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 78 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信小程序菜单节点' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_wx_power_node
-- ----------------------------
INSERT INTO `hr_wx_power_node` VALUES (1, 0, '进货', 'pages/purchase/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (2, 54, '采购单列表', 'pages/example4/list/list', 'purchaseorders', 'getPurchaseOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (3, 54, '采购单新增', 'pages/example4/index', 'purchaseorders', 'savePurchaseOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (4, 54, '采购单编辑', 'pages/example4/index', 'purchaseorders', 'savePurchaseOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (5, 54, '采购单删除', 'pages/example4/list/list', 'PurchaseOrders', 'delPurchaseOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (6, 0, '销售', 'pages/sale/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (7, 55, '销售单列表', 'pages/sale/sale_order_list/index', 'sale', 'getSaleOrdersList', 0);
INSERT INTO `hr_wx_power_node` VALUES (8, 55, '销售单新增', 'pages/sale/sale_order/index', 'sale', 'saveSaleOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (9, 55, '销售单编辑', 'pages/sale/sale_order/index', 'sale', 'saveSaleOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (10, 55, '销售单删除', 'pages/sale/sale_order_list/index', 'sale', 'delSaleOrders', 0);
INSERT INTO `hr_wx_power_node` VALUES (11, 0, '库存', 'pages/stock/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (12, 56, '盘点单列表', 'pages/common/common_list/index', 'inventory', 'list', 0);
INSERT INTO `hr_wx_power_node` VALUES (13, 56, '盘点单新增', 'pages/stock/check_order/index', 'inventory', 'submit', 0);
INSERT INTO `hr_wx_power_node` VALUES (14, 56, '盘点单编辑', 'pages/stock/check_order/index', 'inventory', 'submit', 0);
INSERT INTO `hr_wx_power_node` VALUES (15, 56, '盘点单删除', 'pages/common/common_list/index', 'inventory', 'delete', 0);
INSERT INTO `hr_wx_power_node` VALUES (16, 57, '调拨单列表', 'pages/common/common_list/index', 'TransferOrders', 'list', 0);
INSERT INTO `hr_wx_power_node` VALUES (17, 57, '调拨单新增', 'pages/stock/allot_order/index', 'TransferOrders', 'submit', 0);
INSERT INTO `hr_wx_power_node` VALUES (18, 57, '调拨单编辑', 'pages/stock/allot_order/index', 'TransferOrders', 'submit', 0);
INSERT INTO `hr_wx_power_node` VALUES (19, 57, '调拨单删除', 'pages/common/common_list/index', 'TransferOrders', 'delete', 0);
INSERT INTO `hr_wx_power_node` VALUES (20, 0, '收银台', 'pages/stock/allot_order/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (21, 59, '客户列表', 'pages/client_base/index', 'client', 'getList', 0);
INSERT INTO `hr_wx_power_node` VALUES (22, 59, '客户新增', 'pages/client_base/edit', 'client', 'addClient', 0);
INSERT INTO `hr_wx_power_node` VALUES (23, 59, '客户编辑', 'pages/client_base/edit', 'client', 'addClient', 0);
INSERT INTO `hr_wx_power_node` VALUES (24, 59, '客户删除', 'pages/client_base/index', 'client', 'delClient', 0);
INSERT INTO `hr_wx_power_node` VALUES (25, 60, '结算账户列表', 'pages/count_account/index', 'settlement', 'loadSettlement', 0);
INSERT INTO `hr_wx_power_node` VALUES (26, 60, '结算账户新增', 'pages/count_account/index', 'settlement', 'saveSettlement', 0);
INSERT INTO `hr_wx_power_node` VALUES (27, 60, '结算账户编辑', 'pages/count_account/index', 'settlement', 'editSettlement', 0);
INSERT INTO `hr_wx_power_node` VALUES (28, 60, '结算账户删除', 'pages/count_account/index', 'settlement', 'delSettlement', 0);
INSERT INTO `hr_wx_power_node` VALUES (29, 61, '仓库列表', 'pages/warehouse/index', 'organization', 'getList', 0);
INSERT INTO `hr_wx_power_node` VALUES (30, 61, '仓库新增', 'pages/warehouse/edit', 'organization', 'saveOrg', 0);
INSERT INTO `hr_wx_power_node` VALUES (31, 61, '仓库编辑', 'pages/warehouse/edit', 'organization', 'editorg', 0);
INSERT INTO `hr_wx_power_node` VALUES (32, 61, '仓库删除', 'pages/warehouse/index', 'organization', 'delOrg', 0);
INSERT INTO `hr_wx_power_node` VALUES (33, 62, '供应商列表', 'pages/supplier/index', 'supplier', 'getSupplierList', 0);
INSERT INTO `hr_wx_power_node` VALUES (34, 62, '供应商新增', 'pages/supplier/edit', 'supplier', 'saveSupplier', 0);
INSERT INTO `hr_wx_power_node` VALUES (35, 62, '供应商编辑', 'pages/supplier/edit', 'supplier', 'editSupplier', 0);
INSERT INTO `hr_wx_power_node` VALUES (36, 62, '供应商删除', 'pages/supplier/index', 'supplier', 'delSupplier', 0);
INSERT INTO `hr_wx_power_node` VALUES (37, 63, '尺码列表', 'pages/settings/size/index', 'size', 'loadSizeList', 0);
INSERT INTO `hr_wx_power_node` VALUES (38, 63, '尺码新增', 'pages/settings/size/index', 'size', 'saveSize', 0);
INSERT INTO `hr_wx_power_node` VALUES (39, 63, '尺码编辑', 'pages/settings/size/index', 'size', 'editSize', 0);
INSERT INTO `hr_wx_power_node` VALUES (40, 63, '尺码删除', 'pages/settings/size/index', 'size', 'delSize', 0);
INSERT INTO `hr_wx_power_node` VALUES (41, 64, '色码列表', 'pages/settings/color/index', 'color', 'loadColorList', 0);
INSERT INTO `hr_wx_power_node` VALUES (42, 64, '色码新增', 'pages/settings/color/index', 'color', 'saveColor', 0);
INSERT INTO `hr_wx_power_node` VALUES (43, 64, '色码编辑', 'pages/settings/color/index', 'color', 'editColor', 0);
INSERT INTO `hr_wx_power_node` VALUES (44, 64, '色码删除', 'pages/settings/color/index', 'color', 'delColor', 0);
INSERT INTO `hr_wx_power_node` VALUES (45, 65, '分类列表', 'pages/settings/color/index', 'Category', 'loadCategory', 0);
INSERT INTO `hr_wx_power_node` VALUES (46, 65, '分类新增', 'pages/settings/color/index', 'Category', 'saveCategory', 0);
INSERT INTO `hr_wx_power_node` VALUES (47, 65, '分类编辑', 'pages/settings/color/index', 'Category', 'editCategory', 0);
INSERT INTO `hr_wx_power_node` VALUES (48, 65, '分类删除', 'pages/settings/color/index', 'Category', 'delCategory', 0);
INSERT INTO `hr_wx_power_node` VALUES (49, 66, '商品列表', 'pages/example6/index', 'goods', 'loadGoodsWX', 0);
INSERT INTO `hr_wx_power_node` VALUES (50, 66, '商品新增', 'pages/example2/index', '', 'saveGoods', 0);
INSERT INTO `hr_wx_power_node` VALUES (51, 66, '商品编辑', '', '', 'updateGoodsDetails', 0);
INSERT INTO `hr_wx_power_node` VALUES (52, 0, '权限管理', 'pages/authority_management/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (54, 1, '采购单', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (55, 6, '销售单', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (56, 11, '盘点单', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (57, 11, '调拨单', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (59, 0, '客户管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (60, 0, '结算账户管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (61, 0, '仓库管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (62, 0, '供应商管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (63, 0, '尺码管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (64, 0, '色码管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (65, 0, '分类管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (66, 0, '商品管理', '', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (67, 0, '财务', 'pages/finance/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (68, 0, '会员', 'pages/member_card/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (70, 68, '会员列表', 'pages/member/index', 'memberbase', 'getListData', 0);
INSERT INTO `hr_wx_power_node` VALUES (71, 68, '会员新增', 'pages/member/edit', 'memberbase', 'addMember', 0);
INSERT INTO `hr_wx_power_node` VALUES (72, 68, '会员编辑', 'pages/member/edit', 'memberbase', 'addMember', 0);
INSERT INTO `hr_wx_power_node` VALUES (73, 0, '报表管理', 'pages/reportform/index', '', '', 0);
INSERT INTO `hr_wx_power_node` VALUES (74, 73, '进货分析', 'pages/reportform/purchase_analysis/index', 'purchaseOrders', 'purchaseAnalysis', 0);
INSERT INTO `hr_wx_power_node` VALUES (75, 73, '销售日报', 'pages/reportform/sales_daily/index', 'statistics', 'salesDaily', 0);
INSERT INTO `hr_wx_power_node` VALUES (76, 73, '库存分析', 'pages/reportform/Inventory_analysis/index', 'statistics', 'stockAnalysis', 0);
INSERT INTO `hr_wx_power_node` VALUES (77, 73, '库存查询', 'pages/reportform/Inventory_query/index', 'statistics', 'stockQuery', 0);

-- ----------------------------
-- Table structure for hr_wx_user
-- ----------------------------
DROP TABLE IF EXISTS `hr_wx_user`;
CREATE TABLE `hr_wx_user`  (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `open_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信openid(唯一标示)',
  `nickName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信昵称',
  `avatarUrl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信头像',
  `gender` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '城市',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `wxapp_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '小程序id',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `openid`(`open_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信用户记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_wx_user
-- ----------------------------
INSERT INTO `hr_wx_user` VALUES (1, 'oB8kZ41spHp2T4k54aWwK7lERfxc', 'taco海外购', 'https://thirdwx.qlogo.cn/mmopen/vi_32/gb9JnUNFE01HibojgVym7UXzIIQiaxmRcvHjxRMFrzo4fQAicTu4Pic8sKCiaVeQKDA4ibp2lBpvaENlEiciaxdj5AEd7A/132', 2, 'China', 'Henan', '', 0, 10001, 0, 0);
INSERT INTO `hr_wx_user` VALUES (4, 'oB8kZ4663esOEEMxURPVVjQ3UyJg', '南城以北', 'https://thirdwx.qlogo.cn/mmopen/vi_32/lsTBWic1pKJNrlFh7MibfEYUqnCGUIMCLQmMfIYTbowkEnmtE6lOqWGONa9RCiachAj0GwUJC9OhZFZjsxmibEGgvg/132', 1, 'Brazil', 'Acre', '', 0, 10001, 0, 0);
INSERT INTO `hr_wx_user` VALUES (8, 'oB8kZ41j1bj6eqrPyKsq7AoWe98E', '高培铧', 'https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTL2bKb4V9XgAc4w1YrLaIns3GXB6BGqa2LeTJvEwxPqkWutBzub47nicASGNmILSM4xSuTExOjh5Aw/132', 1, 'China', 'Henan', '', 0, 10001, 0, 0);

-- ----------------------------
-- Table structure for hr_wxapp
-- ----------------------------
DROP TABLE IF EXISTS `hr_wxapp`;
CREATE TABLE `hr_wxapp`  (
  `wxapp_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '小程序id',
  `app_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '小程序AppID',
  `app_secret` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '小程序AppSecret',
  `mchid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信商户号id',
  `apikey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信支付密钥',
  `cert_pem` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '证书文件cert',
  `key_pem` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '证书文件key',
  `is_recycle` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否回收',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `low_withdraw` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '最低提现金额',
  `rate` decimal(6, 4) NOT NULL DEFAULT 0.0000 COMMENT '手续费,百分比',
  `hight_withdraw` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '最高提现金额',
  `max_withdraw` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '当日最多提现',
  `count_withdraw` int(10) NOT NULL DEFAULT 0 COMMENT '当日提现次数',
  `auto_withdraw` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '金额内自动打款,0为不自动打款',
  `mobile` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '客服电话',
  PRIMARY KEY (`wxapp_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10002 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信小程序记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hr_wxapp
-- ----------------------------
INSERT INTO `hr_wxapp` VALUES (10001, 'wx536ee369aea8e866', 'b2a00b7a7a06b522f408e9e038d3609c', '', '', NULL, NULL, 0, 0, 0, 0, 0.00, 0.0000, 0.00, 0.00, 0, 0.00, NULL);

SET FOREIGN_KEY_CHECKS = 1;
