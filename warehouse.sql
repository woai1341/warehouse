/*
 Navicat MySQL Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : warehouse

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 17/02/2023 23:03:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '客户名称',
  `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '性别：1--男  2--女',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(68) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '邮箱',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '地址',
  `last_order_time` timestamp NULL DEFAULT NULL COMMENT '上次下单时间 ',
  `deleted` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '删除：1--正常  0---删除',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '客户列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (1, '张海升2', 1, '18819799243', '1013376003@qq.com', '上海市市辖区青浦区朱家角镇', NULL, 1, '2023-02-13 13:55:55', NULL);
INSERT INTO `customers` VALUES (2, '张海升', 1, '123', '12312', '123123', NULL, 1, '2023-02-14 16:30:59', NULL);
INSERT INTO `customers` VALUES (3, '1231', 1, '18630737040', '6e81@80yjj7.com', '213', NULL, 1, '2023-02-14 16:33:08', NULL);

-- ----------------------------
-- Table structure for customers_sale_list
-- ----------------------------
DROP TABLE IF EXISTS `customers_sale_list`;
CREATE TABLE `customers_sale_list`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '产品名称',
  `count` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数量',
  `price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单价',
  `total_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '总金额',
  `unit` tinyint(3) NULL DEFAULT NULL COMMENT '单位',
  `trademark` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '品牌（客户商标）',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `sum_count` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总数量（月数量）',
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号D+唯一订单号',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no`) USING BTREE COMMENT '订单编号唯一检索',
  INDEX `product_name`(`product_name`, `total_price`, `unit`, `trademark`, `sum_count`) USING BTREE COMMENT '搜索'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '客户销售记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customers_sale_list
-- ----------------------------

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `trademark` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '商标',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品名称',
  `size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '尺寸',
  `count` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数量',
  `unit` tinyint(1) NULL DEFAULT 0 COMMENT '单位',
  `is_high` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否高频   0---否    1--高频低阻 ',
  `is_braid` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否编织带 0--否   1---是',
  `foot_distance` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '编带自带脚距，当编带时，必须附带脚距',
  `deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除  0---正常 1---删除',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '仓库现存产品列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (1, 'jwco', '100V100UF', '10x17', 2, 0, 1, 0, '5.0', 0, '2023-02-17 21:00:46', NULL);
INSERT INTO `product` VALUES (2, '123', '123123', '123', 12, 2, 0, 0, '123', 0, '2023-02-17 22:56:53', NULL);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_login_time` int(11) NOT NULL,
  `group_id` int(2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1启用0禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '用户' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '张海升', '1eae7668d42053970a90c2c4d5af60fd', 1676632292, 1, 1);

SET FOREIGN_KEY_CHECKS = 1;
