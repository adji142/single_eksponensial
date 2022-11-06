/*
 Navicat Premium Data Transfer

 Source Server         : AISServer
 Source Server Type    : MySQL
 Source Server Version : 100240
 Source Host           : localhost:3306
 Source Schema         : forecasting

 Target Server Type    : MySQL
 Target Server Version : 100240
 File Encoding         : 65001

 Date: 27/07/2022 19:10:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissionname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `link` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ico` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `menusubmenu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `multilevel` bit(1) NULL DEFAULT NULL,
  `separator` bit(1) NULL DEFAULT NULL,
  `order` int(255) NULL DEFAULT NULL,
  `status` bit(1) NULL DEFAULT NULL,
  `AllowMobile` bit(1) NULL DEFAULT NULL,
  `MobileRoute` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `MobileLogo` int(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES (2, 'Transaksi', 'home/transaksi', 'fa-pencil-square-o', '0', b'0', b'0', 2, b'1', NULL, NULL, NULL);
INSERT INTO `permission` VALUES (3, 'Perhitungan', 'home/proses', 'fa-refresh', '0', b'0', b'0', 3, b'1', NULL, NULL, NULL);
INSERT INTO `permission` VALUES (4, 'Users', 'home/setting', 'fa-refresh', '0', b'0', b'0', 5, b'1', NULL, NULL, NULL);
INSERT INTO `permission` VALUES (6, 'Laporan', 'home/laporan', 'fa-area-chart', '0', b'0', b'0', 7, b'1', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for permissionrole
-- ----------------------------
DROP TABLE IF EXISTS `permissionrole`;
CREATE TABLE `permissionrole`  (
  `roleid` int(11) NOT NULL,
  `permissionid` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissionrole
-- ----------------------------
INSERT INTO `permissionrole` VALUES (1, 1);
INSERT INTO `permissionrole` VALUES (1, 2);
INSERT INTO `permissionrole` VALUES (1, 3);
INSERT INTO `permissionrole` VALUES (1, 4);
INSERT INTO `permissionrole` VALUES (1, 5);
INSERT INTO `permissionrole` VALUES (2, 2);
INSERT INTO `permissionrole` VALUES (1, 6);
INSERT INTO `permissionrole` VALUES (2, 6);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Admin');
INSERT INTO `roles` VALUES (2, 'Operator');

-- ----------------------------
-- Table structure for tforcast
-- ----------------------------
DROP TABLE IF EXISTS `tforcast`;
CREATE TABLE `tforcast`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Periode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Jenis` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Jumlah` int(255) NOT NULL,
  `F01` double(16, 2) NOT NULL,
  `F02` double(16, 2) NOT NULL,
  `F03` double(16, 2) NOT NULL,
  `F04` double(16, 2) NOT NULL,
  `F05` double(16, 2) NOT NULL,
  `F06` double(16, 2) NOT NULL,
  `F07` double(16, 2) NOT NULL,
  `F08` double(16, 2) NOT NULL,
  `F09` double(16, 2) NOT NULL,
  `number` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tforcast
-- ----------------------------
INSERT INTO `tforcast` VALUES (1, 'Jan-2020', 'Disperindag', 748, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1);
INSERT INTO `tforcast` VALUES (2, 'Feb-2020', 'Disperindag', 733, 747.51, 747.51, 747.51, 747.51, 747.51, 747.51, 747.51, 747.51, 747.51, 2);
INSERT INTO `tforcast` VALUES (3, 'Mar-2020', 'Disperindag', 644, 746.01, 744.51, 743.01, 741.51, 740.00, 738.50, 737.00, 735.50, 734.00, 3);
INSERT INTO `tforcast` VALUES (4, 'Apr-2020', 'Disperindag', 781, 735.82, 724.44, 713.35, 702.57, 692.08, 681.90, 672.01, 662.43, 653.14, 4);
INSERT INTO `tforcast` VALUES (5, 'May-2020', 'Disperindag', 748, 740.37, 735.80, 733.73, 734.05, 736.68, 741.52, 748.49, 757.50, 768.46, 5);
INSERT INTO `tforcast` VALUES (6, 'Jun-2020', 'Disperindag', 626, 741.13, 738.24, 738.00, 739.62, 742.32, 745.39, 748.13, 749.88, 750.02, 6);
INSERT INTO `tforcast` VALUES (7, 'Jul-2020', 'Disperindag', 721, 729.64, 715.84, 704.47, 694.27, 684.28, 673.90, 662.81, 650.97, 638.62, 7);
INSERT INTO `tforcast` VALUES (8, 'Aug-2020', 'Disperindag', 591, 728.73, 716.78, 709.30, 704.79, 702.43, 701.90, 703.24, 706.65, 712.37, 8);
INSERT INTO `tforcast` VALUES (9, 'Sep-2020', 'Disperindag', 749, 714.98, 691.67, 673.87, 659.35, 646.81, 635.47, 624.81, 614.28, 603.31, 9);
INSERT INTO `tforcast` VALUES (10, 'Oct-2020', 'Disperindag', 588, 718.33, 703.04, 696.26, 695.02, 697.66, 703.30, 711.41, 721.67, 734.00, 10);
INSERT INTO `tforcast` VALUES (11, 'Nov-2020', 'Disperindag', 665, 705.25, 679.94, 663.64, 652.02, 642.60, 633.84, 624.69, 614.36, 602.18, 11);
INSERT INTO `tforcast` VALUES (12, 'Dec-2020', 'Disperindag', 586, 701.26, 677.02, 664.16, 657.36, 653.98, 652.75, 653.16, 655.16, 659.04, 12);
INSERT INTO `tforcast` VALUES (13, 'Jan-2021', 'Disperindag', 697, 689.78, 658.91, 640.85, 629.00, 620.22, 612.98, 606.48, 600.21, 593.73, 13);
INSERT INTO `tforcast` VALUES (14, 'Feb-2021', 'Disperindag', 711, 690.55, 666.61, 657.82, 656.37, 658.82, 663.65, 670.14, 677.98, 687.05, 14);
INSERT INTO `tforcast` VALUES (15, 'Mar-2021', 'Disperindag', 561, 692.64, 675.58, 673.91, 678.40, 685.13, 692.32, 699.05, 704.75, 709.00, 15);
INSERT INTO `tforcast` VALUES (16, 'Apr-2021', 'Disperindag', 576, 679.47, 652.65, 640.02, 631.41, 623.04, 613.49, 602.37, 589.70, 575.75, 16);
INSERT INTO `tforcast` VALUES (17, 'May-2021', 'Disperindag', 776, 669.14, 637.36, 620.88, 609.34, 599.63, 591.13, 584.07, 578.92, 576.17, 17);
INSERT INTO `tforcast` VALUES (18, 'Jun-2021', 'Disperindag', 784, 679.79, 665.01, 667.29, 675.83, 687.60, 701.79, 718.12, 736.24, 755.63, 18);
INSERT INTO `tforcast` VALUES (19, 'Jul-2021', 'Disperindag', 654, 690.17, 688.74, 702.20, 718.97, 735.64, 750.93, 764.01, 774.19, 780.88, 19);
INSERT INTO `tforcast` VALUES (20, 'Aug-2021', 'Disperindag', 709, 686.51, 681.70, 687.60, 692.80, 694.59, 692.49, 686.68, 677.67, 666.27, 20);
INSERT INTO `tforcast` VALUES (21, 'Sep-2021', 'Disperindag', 643, 688.76, 687.16, 694.02, 699.27, 701.79, 702.39, 702.30, 702.73, 704.72, 21);
INSERT INTO `tforcast` VALUES (22, 'Oct-2021', 'Disperindag', 715, 684.17, 678.31, 678.68, 676.72, 672.34, 666.70, 660.72, 654.87, 649.08, 22);
INSERT INTO `tforcast` VALUES (23, 'Nov-2021', 'Disperindag', 639, 687.27, 685.67, 689.61, 692.08, 693.73, 695.74, 698.79, 703.06, 708.51, 23);
INSERT INTO `tforcast` VALUES (24, 'Dec-2021', 'Disperindag', 696, 682.48, 676.42, 674.56, 671.02, 666.58, 661.96, 657.25, 652.16, 646.35, 24);
INSERT INTO `tforcast` VALUES (25, 'Jan-2022', 'Disperindag', 721, 683.79, 680.24, 680.85, 680.82, 681.05, 682.10, 684.04, 686.85, 690.60, 25);
INSERT INTO `tforcast` VALUES (26, 'Feb-2022', 'Disperindag', 652, 687.48, 688.33, 692.79, 696.76, 700.86, 705.24, 709.68, 713.91, 717.66, 26);
INSERT INTO `tforcast` VALUES (27, 'Mar-2022', 'Disperindag', 686, 683.96, 681.13, 680.66, 678.99, 676.60, 673.49, 669.54, 664.65, 658.86, 27);
INSERT INTO `tforcast` VALUES (28, 'Apr-2022', 'Disperindag', 633, 684.13, 682.04, 682.17, 681.67, 681.14, 680.81, 680.84, 681.48, 683.01, 28);
INSERT INTO `tforcast` VALUES (29, 'May-2022', 'Disperindag', 699, 679.07, 672.32, 667.55, 662.38, 657.30, 652.39, 647.67, 643.06, 638.41, 29);
INSERT INTO `tforcast` VALUES (30, 'Jun-2022', 'Disperindag', 699, 681.05, 677.63, 676.95, 676.98, 678.09, 680.29, 683.52, 687.72, 692.83, 30);

-- ----------------------------
-- Table structure for thasilforecast
-- ----------------------------
DROP TABLE IF EXISTS `thasilforecast`;
CREATE TABLE `thasilforecast`  (
  `NoProses` int(255) NOT NULL AUTO_INCREMENT,
  `TglProses` datetime(0) NOT NULL,
  `PeriodeHasil` date NOT NULL,
  `Jenis` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Forecast` double(22, 2) NOT NULL,
  `MaE` double(16, 2) NOT NULL,
  PRIMARY KEY (`NoProses`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of thasilforecast
-- ----------------------------
INSERT INTO `thasilforecast` VALUES (1, '2020-08-21 04:11:55', '2020-02-01', 'KARTUN', 2273.00, 0.00);
INSERT INTO `thasilforecast` VALUES (2, '2022-07-27 02:08:53', '2022-06-01', 'Disperindag', 681.00, 55.98);

-- ----------------------------
-- Table structure for thppsetting
-- ----------------------------
DROP TABLE IF EXISTS `thppsetting`;
CREATE TABLE `thppsetting`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hargakain` double(12, 2) NOT NULL,
  `hargakaret` double(12, 2) NOT NULL,
  `ongkosjait` double(12, 2) NOT NULL,
  `ongkospotong` double(12, 2) NOT NULL,
  `Lastupdateddate` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(0),
  `biayakemas` double(12, 2) NOT NULL,
  `pemakaiankainperpcs` double(12, 2) NOT NULL,
  `Jenis` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of thppsetting
-- ----------------------------
INSERT INTO `thppsetting` VALUES (1, 25000.00, 1114.00, 2500.00, 1500.00, '2020-08-21 10:53:10', 200.00, 0.45, 'KARTUN');

-- ----------------------------
-- Table structure for tmae
-- ----------------------------
DROP TABLE IF EXISTS `tmae`;
CREATE TABLE `tmae`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Jenis` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `F01` double(16, 2) NOT NULL,
  `F02` double(16, 2) NOT NULL,
  `F03` double(16, 2) NOT NULL,
  `F04` double(16, 2) NOT NULL,
  `F05` double(16, 2) NOT NULL,
  `F06` double(16, 2) NOT NULL,
  `F07` double(16, 2) NOT NULL,
  `F08` double(16, 2) NOT NULL,
  `F09` double(16, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tmae
-- ----------------------------
INSERT INTO `tmae` VALUES (1, 'Disperindag', 55.98, 57.27, 59.26, 62.17, 65.00, 67.75, 70.46, 73.75, 77.22);

-- ----------------------------
-- Table structure for ttransaksi
-- ----------------------------
DROP TABLE IF EXISTS `ttransaksi`;
CREATE TABLE `ttransaksi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Tanggal` date NOT NULL,
  `NoRef` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Merk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Tipe` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Qty` double(11, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 89 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ttransaksi
-- ----------------------------
INSERT INTO `ttransaksi` VALUES (1, '2020-01-01', '38', 'NN', 'Disperindag', 747.51);
INSERT INTO `ttransaksi` VALUES (2, '2020-02-01', '41', 'NN', 'Disperindag', 732.50);
INSERT INTO `ttransaksi` VALUES (3, '2020-03-01', '17', 'NN', 'Disperindag', 644.16);
INSERT INTO `ttransaksi` VALUES (4, '2020-04-01', '78', 'NN', 'Disperindag', 781.27);
INSERT INTO `ttransaksi` VALUES (5, '2020-05-01', '24', 'NN', 'Disperindag', 747.97);
INSERT INTO `ttransaksi` VALUES (6, '2020-06-01', '49', 'NN', 'Disperindag', 626.24);
INSERT INTO `ttransaksi` VALUES (7, '2020-07-01', '27', 'NN', 'Disperindag', 720.57);
INSERT INTO `ttransaksi` VALUES (8, '2020-08-01', '58', 'NN', 'Disperindag', 591.19);
INSERT INTO `ttransaksi` VALUES (9, '2020-09-01', '31', 'NN', 'Disperindag', 748.52);
INSERT INTO `ttransaksi` VALUES (10, '2020-10-01', '12', 'NN', 'Disperindag', 587.53);
INSERT INTO `ttransaksi` VALUES (11, '2020-11-01', '11', 'NN', 'Disperindag', 665.36);
INSERT INTO `ttransaksi` VALUES (12, '2020-12-01', '66', 'NN', 'Disperindag', 586.47);
INSERT INTO `ttransaksi` VALUES (13, '2021-01-01', '63', 'NN', 'Disperindag', 697.42);
INSERT INTO `ttransaksi` VALUES (14, '2021-02-01', '1', 'NN', 'Disperindag', 711.44);
INSERT INTO `ttransaksi` VALUES (15, '2021-03-01', '98', 'NN', 'Disperindag', 560.94);
INSERT INTO `ttransaksi` VALUES (16, '2021-04-01', '69', 'NN', 'Disperindag', 576.22);
INSERT INTO `ttransaksi` VALUES (17, '2021-05-01', '63', 'NN', 'Disperindag', 775.57);
INSERT INTO `ttransaksi` VALUES (18, '2021-06-01', '96', 'NN', 'Disperindag', 783.68);
INSERT INTO `ttransaksi` VALUES (19, '2021-07-01', '55', 'NN', 'Disperindag', 653.54);
INSERT INTO `ttransaksi` VALUES (20, '2021-08-01', '73', 'NN', 'Disperindag', 708.99);
INSERT INTO `ttransaksi` VALUES (21, '2021-09-01', '23', 'NN', 'Disperindag', 642.90);
INSERT INTO `ttransaksi` VALUES (22, '2021-10-01', '4', 'NN', 'Disperindag', 715.11);
INSERT INTO `ttransaksi` VALUES (23, '2021-11-01', '76', 'NN', 'Disperindag', 639.44);
INSERT INTO `ttransaksi` VALUES (24, '2021-12-01', '13', 'NN', 'Disperindag', 695.52);
INSERT INTO `ttransaksi` VALUES (25, '2022-01-01', '53', 'NN', 'Disperindag', 720.67);
INSERT INTO `ttransaksi` VALUES (26, '2022-02-01', '89', 'NN', 'Disperindag', 652.33);
INSERT INTO `ttransaksi` VALUES (27, '2022-03-01', '76', 'NN', 'Disperindag', 685.69);
INSERT INTO `ttransaksi` VALUES (28, '2022-04-01', '85', 'NN', 'Disperindag', 633.45);
INSERT INTO `ttransaksi` VALUES (29, '2022-05-01', '24', 'NN', 'Disperindag', 698.88);
INSERT INTO `ttransaksi` VALUES (30, '2020-01-01', '8', 'NN', 'Kelurahan', 6217.61);
INSERT INTO `ttransaksi` VALUES (31, '2020-02-01', '47', 'NN', 'Kelurahan', 5878.22);
INSERT INTO `ttransaksi` VALUES (32, '2020-03-01', '35', 'NN', 'Kelurahan', 6253.01);
INSERT INTO `ttransaksi` VALUES (33, '2020-04-01', '92', 'NN', 'Kelurahan', 5304.58);
INSERT INTO `ttransaksi` VALUES (34, '2020-05-01', '18', 'NN', 'Kelurahan', 5940.08);
INSERT INTO `ttransaksi` VALUES (35, '2020-06-01', '48', 'NN', 'Kelurahan', 5508.65);
INSERT INTO `ttransaksi` VALUES (36, '2020-07-01', '75', 'NN', 'Kelurahan', 5936.75);
INSERT INTO `ttransaksi` VALUES (37, '2020-08-01', '29', 'NN', 'Kelurahan', 6042.95);
INSERT INTO `ttransaksi` VALUES (38, '2020-09-01', '90', 'NN', 'Kelurahan', 5260.71);
INSERT INTO `ttransaksi` VALUES (39, '2020-10-01', '53', 'NN', 'Kelurahan', 6079.75);
INSERT INTO `ttransaksi` VALUES (40, '2020-11-01', '2', 'NN', 'Kelurahan', 5407.45);
INSERT INTO `ttransaksi` VALUES (41, '2020-12-01', '75', 'NN', 'Kelurahan', 6027.89);
INSERT INTO `ttransaksi` VALUES (42, '2021-01-01', '55', 'NN', 'Kelurahan', 5873.75);
INSERT INTO `ttransaksi` VALUES (43, '2021-02-01', '42', 'NN', 'Kelurahan', 5033.60);
INSERT INTO `ttransaksi` VALUES (44, '2021-03-01', '24', 'NN', 'Kelurahan', 5691.76);
INSERT INTO `ttransaksi` VALUES (45, '2021-04-01', '88', 'NN', 'Kelurahan', 5800.25);
INSERT INTO `ttransaksi` VALUES (46, '2021-05-01', '8', 'NN', 'Kelurahan', 5181.92);
INSERT INTO `ttransaksi` VALUES (47, '2021-06-01', '86', 'NN', 'Kelurahan', 6037.34);
INSERT INTO `ttransaksi` VALUES (48, '2021-07-01', '5', 'NN', 'Kelurahan', 5680.00);
INSERT INTO `ttransaksi` VALUES (49, '2021-08-01', '91', 'NN', 'Kelurahan', 6244.85);
INSERT INTO `ttransaksi` VALUES (50, '2021-09-01', '34', 'NN', 'Kelurahan', 5503.71);
INSERT INTO `ttransaksi` VALUES (51, '2021-10-01', '68', 'NN', 'Kelurahan', 6189.90);
INSERT INTO `ttransaksi` VALUES (52, '2021-11-01', '67', 'NN', 'Kelurahan', 5389.90);
INSERT INTO `ttransaksi` VALUES (53, '2021-12-01', '2', 'NN', 'Kelurahan', 6089.90);
INSERT INTO `ttransaksi` VALUES (54, '2022-01-01', '16', 'NN', 'Kelurahan', 5889.90);
INSERT INTO `ttransaksi` VALUES (55, '2022-02-01', '41', 'NN', 'Kelurahan', 5289.90);
INSERT INTO `ttransaksi` VALUES (56, '2022-03-01', '5', 'NN', 'Kelurahan', 5397.32);
INSERT INTO `ttransaksi` VALUES (57, '2022-04-01', '41', 'NN', 'Kelurahan', 5989.90);
INSERT INTO `ttransaksi` VALUES (58, '2022-05-01', '34', 'NN', 'Kelurahan', 5419.70);
INSERT INTO `ttransaksi` VALUES (59, '2020-01-01', '95', 'NN', 'Umum', 612.22);
INSERT INTO `ttransaksi` VALUES (60, '2020-02-01', '21', 'NN', 'Umum', 548.20);
INSERT INTO `ttransaksi` VALUES (61, '2020-03-01', '22', 'NN', 'Umum', 626.13);
INSERT INTO `ttransaksi` VALUES (62, '2020-04-01', '89', 'NN', 'Umum', 484.38);
INSERT INTO `ttransaksi` VALUES (63, '2020-05-01', '35', 'NN', 'Umum', 461.79);
INSERT INTO `ttransaksi` VALUES (64, '2020-06-01', '84', 'NN', 'Umum', 589.07);
INSERT INTO `ttransaksi` VALUES (65, '2020-07-01', '72', 'NN', 'Umum', 582.78);
INSERT INTO `ttransaksi` VALUES (66, '2020-08-01', '28', 'NN', 'Umum', 473.61);
INSERT INTO `ttransaksi` VALUES (67, '2020-09-01', '88', 'NN', 'Umum', 564.20);
INSERT INTO `ttransaksi` VALUES (68, '2020-10-01', '16', 'NN', 'Umum', 446.38);
INSERT INTO `ttransaksi` VALUES (69, '2020-11-01', '21', 'NN', 'Umum', 451.25);
INSERT INTO `ttransaksi` VALUES (70, '2020-12-01', '82', 'NN', 'Umum', 551.51);
INSERT INTO `ttransaksi` VALUES (71, '2021-01-01', '37', 'NN', 'Umum', 537.01);
INSERT INTO `ttransaksi` VALUES (72, '2021-02-01', '64', 'NN', 'Umum', 438.53);
INSERT INTO `ttransaksi` VALUES (73, '2021-03-01', '47', 'NN', 'Umum', 533.17);
INSERT INTO `ttransaksi` VALUES (74, '2021-04-01', '29', 'NN', 'Umum', 416.74);
INSERT INTO `ttransaksi` VALUES (75, '2021-05-01', '80', 'NN', 'Umum', 600.29);
INSERT INTO `ttransaksi` VALUES (76, '2021-06-01', '14', 'NN', 'Umum', 518.84);
INSERT INTO `ttransaksi` VALUES (77, '2021-07-01', '31', 'NN', 'Umum', 587.04);
INSERT INTO `ttransaksi` VALUES (78, '2021-08-01', '51', 'NN', 'Umum', 517.66);
INSERT INTO `ttransaksi` VALUES (79, '2021-09-01', '30', 'NN', 'Umum', 607.12);
INSERT INTO `ttransaksi` VALUES (80, '2021-10-01', '22', 'NN', 'Umum', 615.14);
INSERT INTO `ttransaksi` VALUES (81, '2021-11-01', '6', 'NN', 'Umum', 540.79);
INSERT INTO `ttransaksi` VALUES (82, '2021-12-01', '31', 'NN', 'Umum', 610.56);
INSERT INTO `ttransaksi` VALUES (83, '2022-01-01', '51', 'NN', 'Umum', 539.71);
INSERT INTO `ttransaksi` VALUES (84, '2022-02-01', '72', 'NN', 'Umum', 531.34);
INSERT INTO `ttransaksi` VALUES (85, '2022-03-01', '5', 'NN', 'Umum', 602.61);
INSERT INTO `ttransaksi` VALUES (86, '2022-04-01', '57', 'NN', 'Umum', 544.33);
INSERT INTO `ttransaksi` VALUES (87, '2022-05-01', '78', 'NN', 'Umum', 573.64);

-- ----------------------------
-- Table structure for userrole
-- ----------------------------
DROP TABLE IF EXISTS `userrole`;
CREATE TABLE `userrole`  (
  `userid` int(11) NOT NULL,
  `roleid` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`userid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of userrole
-- ----------------------------
INSERT INTO `userrole` VALUES (14, 1);
INSERT INTO `userrole` VALUES (43, 2);
INSERT INTO `userrole` VALUES (52, 1);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdby` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdon` datetime(0) NULL DEFAULT NULL,
  `HakAkses` int(255) NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `verified` bit(1) NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `browser` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 44 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (14, 'admin', 'admin', 'a9bdd47d7321d4089b3b00561c9c621848bd6f6e2f745a53d54913d613789c23945b66de6ded1eb336a7d526f9349a9d964d6f6c3a40e2ac90b4b16c0121f7895Xg53McbkyQ/NmW60Sf4cu3wJsi/8cyZXxeXV7g6b04=', 'mnl', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (43, 'operator', 'Operator', 'a9bdd47d7321d4089b3b00561c9c621848bd6f6e2f745a53d54913d613789c23945b66de6ded1eb336a7d526f9349a9d964d6f6c3a40e2ac90b4b16c0121f7895Xg53McbkyQ/NmW60Sf4cu3wJsi/8cyZXxeXV7g6b04=', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
