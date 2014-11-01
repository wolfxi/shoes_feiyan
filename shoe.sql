-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-11-01 09:08:21
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shoe`
--

-- --------------------------------------------------------

--
-- 表的结构 `delivergoods`
--

CREATE TABLE IF NOT EXISTS `delivergoods` (
  `dg_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gl_id` bigint(20) DEFAULT NULL,
  `o_id` bigint(20) DEFAULT NULL,
  `dg_person` varchar(45) DEFAULT NULL,
  `dg_customer` varchar(45) DEFAULT NULL,
  `dg_totalnum` int(11) DEFAULT NULL,
  `dg_totalprice` float DEFAULT NULL,
  `dg_status` varchar(45) DEFAULT NULL,
  `dg_time` datetime DEFAULT NULL,
  PRIMARY KEY (`dg_id`),
  KEY `FK_Relationship_10` (`o_id`),
  KEY `FK_Relationship_16` (`gl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `delivergoods`
--

INSERT INTO `delivergoods` (`dg_id`, `gl_id`, `o_id`, `dg_person`, `dg_customer`, `dg_totalnum`, `dg_totalprice`, `dg_status`, `dg_time`) VALUES
(1, NULL, 9, '测试人 范美希', '罗马尼亚建光', 800, 26800, '发货完结', '2014-10-23 22:34:42'),
(2, NULL, 13, 'test', '罗马利亚剑网', 2747, 82405, '发货完结', '2014-11-01 15:33:34');

-- --------------------------------------------------------

--
-- 表的结构 `epiboly`
--

CREATE TABLE IF NOT EXISTS `epiboly` (
  `e_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fp_id` bigint(20) DEFAULT NULL,
  `e_contractor` varchar(45) DEFAULT NULL,
  `e_number` int(11) DEFAULT NULL,
  `e_iscallback` tinyint(1) DEFAULT NULL,
  `e_posttime` datetime DEFAULT NULL,
  `e_gettime` datetime DEFAULT NULL,
  `e_signer` varchar(45) DEFAULT NULL,
  `e_models` varchar(45) DEFAULT NULL,
  `e_producename` varchar(45) DEFAULT NULL,
  `o_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`e_id`),
  KEY `FK_Relationship_8` (`fp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `epiboly`
--

INSERT INTO `epiboly` (`e_id`, `fp_id`, `e_contractor`, `e_number`, `e_iscallback`, `e_posttime`, `e_gettime`, `e_signer`, `e_models`, `e_producename`, `o_id`) VALUES
(1, NULL, '鸿基', 400, 0, '2014-10-21 19:45:16', NULL, '测试人', 'Tbaotouchen', '压边', NULL),
(2, 1, '鸿基', 400, 0, '2014-10-21 19:50:13', NULL, '测试人', 'Tbaotouchen', '压边', NULL),
(3, 1, '鸿基', 400, 0, '2014-10-21 19:51:11', NULL, '测试人', 'Tbaotouchen', '压边', 9),
(4, 5, '小吴', 200, 1, '2014-10-31 15:38:13', '2014-10-31 15:39:20', '玲玲', 'Tbaotouchen', ' 	测试包头衬', 12);

-- --------------------------------------------------------

--
-- 表的结构 `firm`
--

CREATE TABLE IF NOT EXISTS `firm` (
  `f_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) DEFAULT NULL,
  `f_phone` varchar(45) DEFAULT NULL,
  `f_address` varchar(45) DEFAULT NULL,
  `f_remarks` varchar(255) DEFAULT NULL,
  `f_kind` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `firm`
--

INSERT INTO `firm` (`f_id`, `f_name`, `f_phone`, `f_address`, `f_remarks`, `f_kind`) VALUES
(1, '新疆客户', '18267712345', '新疆', '新疆客户', '零售');

-- --------------------------------------------------------

--
-- 表的结构 `followproduce`
--

CREATE TABLE IF NOT EXISTS `followproduce` (
  `fp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `od_id` bigint(20) DEFAULT NULL,
  `o_id` bigint(20) DEFAULT NULL,
  `fp_starttime` datetime DEFAULT NULL,
  `fp_endtime` datetime DEFAULT NULL,
  `fp_progress` varchar(45) DEFAULT NULL,
  `fp_status` varchar(45) DEFAULT NULL,
  `fp_logo` varchar(45) DEFAULT NULL,
  `fp_models` varchar(45) DEFAULT NULL,
  `fp_number` int(11) DEFAULT NULL,
  `fp_totalnum` int(11) DEFAULT NULL,
  `fp_finishnum` int(11) DEFAULT NULL,
  PRIMARY KEY (`fp_id`),
  KEY `FK_Reference_16` (`od_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `followproduce`
--

INSERT INTO `followproduce` (`fp_id`, `od_id`, `o_id`, `fp_starttime`, `fp_endtime`, `fp_progress`, `fp_status`, `fp_logo`, `fp_models`, `fp_number`, `fp_totalnum`, `fp_finishnum`) VALUES
(1, 1, 9, '2014-10-21 14:44:31', '2014-10-23 22:34:42', '交易结束', '发货完结', NULL, 'B511-11L', 400, 400, 400),
(2, 7, 9, '2014-10-22 20:53:22', '2014-10-23 22:34:42', '交易结束', '发货完结', NULL, '511-11LW', 500, 500, 500),
(3, 8, 10, '2014-10-24 21:34:59', NULL, '刚创建跟踪', '下单投产', NULL, '511-11LM', 400, 400, 0),
(4, 11, 11, '2014-10-31 15:18:40', NULL, '刚创建跟踪', '下单投产', NULL, '511-11LW', 400, 400, 0),
(5, 13, 12, '2014-10-31 15:23:49', NULL, '截断', '下单投产', NULL, 'B511-11L', 200, 200, 100),
(6, 18, 13, '2014-11-01 15:12:45', '2014-11-01 15:33:33', '交易结束', '发货完结', NULL, '132-6L', 1374, 1374, 1374),
(7, 19, 13, '2014-11-01 15:28:03', '2014-11-01 15:33:34', '交易结束', '发货完结', NULL, 'abcde', 1373, 1373, 1373);

-- --------------------------------------------------------

--
-- 表的结构 `goodskind`
--

CREATE TABLE IF NOT EXISTS `goodskind` (
  `gk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gk_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`gk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `goodskind`
--

INSERT INTO `goodskind` (`gk_id`, `gk_name`) VALUES
(1, '成品鞋'),
(2, '包头衬'),
(3, '鞋包'),
(4, '鞋底'),
(5, '中底'),
(6, '印花'),
(7, '内盒'),
(8, '外箱'),
(9, '拉链'),
(10, '扣件');

-- --------------------------------------------------------

--
-- 表的结构 `goodslist`
--

CREATE TABLE IF NOT EXISTS `goodslist` (
  `gl_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gk_id` bigint(20) DEFAULT NULL,
  `gl_name` varchar(45) DEFAULT NULL,
  `gl_models` varchar(45) DEFAULT NULL,
  `gl_number` int(11) DEFAULT NULL,
  `gl_color` varchar(45) DEFAULT NULL,
  `gl_material` varchar(45) DEFAULT NULL,
  `gl_logo` varchar(45) DEFAULT NULL,
  `gl_format` varchar(45) DEFAULT NULL,
  `gl_price` float DEFAULT NULL,
  `gl_measurementunits` varchar(45) DEFAULT NULL,
  `gl_totalprice` float DEFAULT NULL,
  `gl_supplier` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`gl_id`),
  KEY `FK_Relationship_3` (`gk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `goodslist`
--

INSERT INTO `goodslist` (`gl_id`, `gk_id`, `gl_name`, `gl_models`, `gl_number`, `gl_color`, `gl_material`, `gl_logo`, `gl_format`, `gl_price`, `gl_measurementunits`, `gl_totalprice`, `gl_supplier`) VALUES
(1, 2, '测试包头衬', 'Tbaotouchen', 498600, '黑色', '', 'Tlogo', '', 10, '双', NULL, '');

-- --------------------------------------------------------

--
-- 表的结构 `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `i_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `s_id` bigint(20) DEFAULT NULL,
  `i_url` varchar(255) DEFAULT NULL,
  `i_introduce` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`i_id`),
  KEY `FK_Relationship_5` (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `image`
--

INSERT INTO `image` (`i_id`, `s_id`, `i_url`, `i_introduce`) VALUES
(1, 1, '2014-10-17/5440dd212dde9.jpg', NULL),
(2, 2, '2014-10-17/54411c1d34213.jpg', NULL),
(4, 4, '2014-10-17/54411d5c23fb7.jpg', NULL),
(5, 5, '2014-10-31/54533060263c5.jpg', NULL),
(6, 5, '2014-10-31/5453306031a95.jpg', NULL),
(7, 6, '2014-10-31/5453323ca483d.jpg', NULL),
(8, 7, '2014-10-31/545332efdd930.jpg', NULL),
(9, 8, '2014-10-31/5453335a4cfdb.jpg', NULL),
(10, 9, '2014-10-31/5453339f29154.jpg', NULL),
(11, 10, '2014-11-01/54547a8023dd2.jpg', NULL),
(12, 11, '2014-11-01/54547aeed2d9f.jpg', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `m_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mk_id` bigint(20) DEFAULT NULL,
  `m_account` varchar(45) DEFAULT NULL,
  `m_passwords` char(32) DEFAULT NULL,
  `m_name` varchar(45) DEFAULT NULL,
  `m_price` float DEFAULT NULL,
  `m_gender` varchar(5) DEFAULT NULL,
  `m_phone` varchar(45) DEFAULT NULL,
  `m_idcard` varchar(45) DEFAULT NULL,
  `m_address` varchar(75) DEFAULT NULL,
  `m_remark` varchar(255) DEFAULT NULL,
  `m_quit` tinyint(1) DEFAULT NULL,
  `m_isadmin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`m_id`),
  KEY `FK_Relationship_1` (`mk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`m_id`, `mk_id`, `m_account`, `m_passwords`, `m_name`, `m_price`, `m_gender`, `m_phone`, `m_idcard`, `m_address`, `m_remark`, `m_quit`, `m_isadmin`) VALUES
(1, 1, 'admin', '0192023a7bbd73250516f069df18b500', '范美希', 1000, '男', '18767708820', '530381199211010639', '云南省宣威市来宾镇', '测试人员', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `memberjj`
--

CREATE TABLE IF NOT EXISTS `memberjj` (
  `mjj_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dg_id` bigint(20) DEFAULT NULL,
  `m_id` bigint(20) DEFAULT NULL,
  `sk_id` bigint(20) DEFAULT NULL,
  `mjj_ordersid` varchar(75) DEFAULT NULL,
  `mjj_number` int(11) DEFAULT NULL,
  `mjj__totalmoney` float DEFAULT NULL,
  `mjj_time` datetime DEFAULT NULL,
  `mjj_over` tinyint(1) DEFAULT NULL,
  `mjj_price` float DEFAULT NULL,
  PRIMARY KEY (`mjj_id`),
  KEY `FK_Relationship_11` (`dg_id`),
  KEY `FK_Relationship_12` (`m_id`),
  KEY `FK_Relationship_13` (`sk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `memberkind`
--

CREATE TABLE IF NOT EXISTS `memberkind` (
  `mk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mk_name` varchar(45) DEFAULT NULL,
  `mk_price` float DEFAULT NULL,
  `mk_isadmin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `memberkind`
--

INSERT INTO `memberkind` (`mk_id`, `mk_name`, `mk_price`, `mk_isadmin`) VALUES
(1, '管理员', 0, NULL),
(2, '仓库管理员', NULL, NULL),
(3, '财务管理员', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `o_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) DEFAULT NULL,
  `o_displayid` varchar(45) DEFAULT NULL,
  `oc_id` varchar(45) DEFAULT NULL,
  `o_customer` varchar(45) DEFAULT NULL,
  `o_number` int(11) DEFAULT NULL,
  `o_totalprice` float DEFAULT NULL,
  `o_remark` varchar(255) DEFAULT NULL,
  `o_time` datetime DEFAULT NULL,
  `o_isdelete` tinyint(1) DEFAULT NULL,
  `o_isproduce` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`o_id`),
  KEY `FK_Relationship_14` (`os_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`o_id`, `os_id`, `o_displayid`, `oc_id`, `o_customer`, `o_number`, `o_totalprice`, `o_remark`, `o_time`, `o_isdelete`, `o_isproduce`) VALUES
(9, 4, '2014-10-13罗马尼亚建光', 'orders-test1992', '罗马尼亚建光', 800, 26800, 'kjhkjhkhk  ', '2014-10-18 18:02:35', 0, 1),
(10, 2, '2014-10-24赵姐', 'orders-test1992', '赵姐', 800, 26800, '', '2014-10-24 13:25:50', 0, 1),
(11, 2, '2014-10-24测试', '哦人', '哦人', 1200, 39600, '', '2014-10-25 04:17:51', 0, 1),
(12, 2, '20141031', '20141031xj', '新疆客户', 800, 32000, '准时发货', '2014-10-31 15:12:21', 0, 1),
(13, 4, '2014-11-1罗马利亚', 'test', '罗马利亚剑网', 2747, 82405, 'SD卡', '2014-11-01 14:27:43', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ordersdetail`
--

CREATE TABLE IF NOT EXISTS `ordersdetail` (
  `od_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `o_id` bigint(20) DEFAULT NULL,
  `s_id` bigint(20) DEFAULT NULL,
  `od_number` int(11) DEFAULT NULL,
  `od_bunchnum` int(11) DEFAULT NULL,
  `od_price` float DEFAULT NULL,
  `od_sizes` varchar(250) DEFAULT NULL,
  `s_models` varchar(250) DEFAULT NULL,
  `od_isproduce` tinyint(1) DEFAULT NULL,
  `od_attribute` text,
  `od_totalprice` float DEFAULT NULL,
  PRIMARY KEY (`od_id`),
  KEY `FK_Reference_14` (`o_id`),
  KEY `FK_Reference_15` (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `ordersdetail`
--

INSERT INTO `ordersdetail` (`od_id`, `o_id`, `s_id`, `od_number`, `od_bunchnum`, `od_price`, `od_sizes`, `s_models`, `od_isproduce`, `od_attribute`, `od_totalprice`) VALUES
(1, 9, 1, 400, 8, 35, '31-36', 'B511-11L', 1, 'a:32:{s:5:"shoes";a:8:{s:10:"c_sampleid";s:12:"ketenhuohao ";s:5:"mould";s:6:"单鞋";s:4:"dadi";s:10:"黑+灰沿";s:5:"macha";s:5:"05-06";s:11:"zhencheinfo";s:44:" 小心加工雅！！！  \r\n						 \r\n						";s:13:"chengxinginfo";s:32:"														 \r\n						 \r\n						";s:6:"remark";s:38:"							精品样鞋	 \r\n						 \r\n						";s:16:"xiaoliaopipiinfo";s:68:"					c的详细信息笑嘻嘻笑嘻嘻惺惺惜惺惺xxxxxx 							";}s:9:"material1";a:3:{s:6:"models";s:7:"面料1";s:4:"name";s:7:"面料1";s:6:"method";s:6:"压边";}s:9:"material2";a:3:{s:6:"models";s:7:"面料2";s:4:"name";s:7:"面料2";s:6:"method";s:6:"复合";}s:9:"material3";a:3:{s:6:"models";s:7:"面料3";s:4:"name";s:7:"面料3";s:6:"method";s:6:"烫平";}s:8:"toupaili";a:3:{s:6:"models";s:9:"头排里";s:4:"name";s:0:"";s:6:"method";s:9:"偷拍里";}s:10:"zhongpaili";a:3:{s:6:"models";s:12:"三翻四覆";s:4:"name";s:18:"撒酒疯斯蒂芬";s:6:"method";s:23:"送到附近斯蒂芬io";}s:8:"zhugenli";a:3:{s:6:"models";s:12:"斯蒂芬级";s:4:"name";s:13:"三季度范i";s:6:"method";s:12:"撒酒疯ig ";}s:7:"xiedian";a:3:{s:6:"models";s:19:"思考的积分igj ";s:4:"name";s:17:"三极管igfkjdfi";s:6:"method";s:16:"过户费电话 ";}s:6:"baotou";a:2:{s:6:"models";s:12:"似懂非懂";s:6:"format";s:9:"速度更";}s:8:"bangmian";a:2:{s:6:"models";s:6:"嘎斯";s:6:"format";s:15:"阿斯顿发送";}s:7:"zhongdi";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:15:"算法的撒旦";}s:10:"weikouliao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:10:"xieyanchen";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:8:"mianxian";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:10:"kousheliao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:7:"songjin";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"dixian";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:8:"outerbox";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:32:"														 \r\n						 \r\n						";s:4:"back";s:32:"														 \r\n						 \r\n						";}s:5:"sizes";a:6:{i:31;s:0:"";i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:28:"2014-10-20/54450c04810bc.jpg";s:7:"neiliao";s:28:"2014-10-20/54450c048136d.jpg";}}', 14000),
(7, 9, 2, 500, 8, 50, '31-35', '511-11LW', 0, NULL, 25000),
(8, 10, 4, 400, 8, 32, '32-36', '511-11LM', 0, NULL, 12800),
(9, 10, 2, 400, 12, 35, '31-39', '511-11LW', 0, NULL, 14000),
(10, 11, 1, 400, 8, 32, '31-38', 'B511-11L', 0, NULL, 12800),
(11, 11, 2, 400, 8, 33, '31-38', '511-11LW', 1, 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:0:"";s:5:"mould";s:6:"单鞋";s:4:"dadi";s:0:"";s:5:"macha";s:0:"";s:5:"color";s:0:"";s:8:"material";s:0:"";s:11:"zhencheinfo";s:16:"							 \r\n						";s:13:"chengxinginfo";s:16:"							 \r\n						";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:10:"zhongpaili";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"zhugenli";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:7:"xiedian";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"dixian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:16:"							 \r\n						";s:4:"back";s:16:"							 \r\n						";}s:5:"sizes";a:8:{i:31;s:0:"";i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";i:37;s:0:"";i:38;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:0:"";s:7:"neiliao";s:0:"";}}', 13200),
(12, 11, 4, 400, 8, 34, '31-38', '511-11LM', 0, NULL, 13600),
(13, 12, 1, 200, 8, 40, '31,36', 'B511-11L', 0, NULL, 8000),
(14, 12, 2, 200, 8, 40, '31,36', '511-11LW', 0, NULL, 8000),
(15, 12, 4, 200, 8, 40, '31,36', '511-11LM', 0, NULL, 8000),
(16, 12, 9, 200, 8, 40, '31,36', 'abcdef', 0, NULL, 8000),
(18, 13, 10, 1374, 16, 25, '34-38', '132-6L', 1, 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:5:"12345";s:5:"mould";s:6:"凉鞋";s:4:"dadi";s:11:"云溪13061";s:5:"macha";s:3:"5mm";s:5:"color";s:6:"黑色";s:8:"material";s:9:"水牛皮";s:11:"zhencheinfo";s:71:"							 1.机心拥边要均匀,\r\n	2.边排粗线车顺畅,针距\r\n				";s:13:"chengxinginfo";s:49:"							 \r\n			1.前帮夹正,机心位置以确			";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:5:"N1101";s:4:"name";s:25:"鸡爪纹+金巴利浅棕";s:6:"method";s:14:"复1.5MM切片";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:6:"N1101T";s:4:"name";s:9:"双面平";s:6:"method";s:9:" 1.1MMEVA";}s:10:"zhongpaili";a:3:{s:6:"models";s:6:"N1101P";s:4:"name";s:5:"PU里";s:6:"method";s:42:"复0.6MM切片(后跟小皮里不复合)		";}s:8:"zhugenli";a:3:{s:6:"models";s:6:"N1101M";s:4:"name";s:9:"毛织里";s:6:"method";s:14:"复0.6MM切片";}s:7:"xiedian";a:3:{s:6:"models";s:6:"N1101X";s:4:"name";s:29:"PU里复1.3MM切片+乳胶片";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:6:"N1101B";s:6:"format";s:9:"白细布";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:6:"N1101Z";s:6:"format";s:26:"1.5特克松+半插+普钢";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:6:"N1101S";s:6:"format";s:6:"5CM宽";}s:6:"dixian";a:2:{s:6:"models";s:7:"N1101DX";s:6:"format";s:9:"顺里色";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:2:"34";s:1:"b";s:2:"35";s:1:"c";s:2:"36";s:1:"d";s:2:"37";s:1:"e";s:2:"38";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:8:"ΛΛΛΛ";s:1:"b";s:3:"∏";s:1:"c";s:5:"∏Λ";s:1:"d";s:7:"∏ΛΛ";s:1:"e";s:9:"∏ΛΛΛ";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:30:"			1.0MM化学片				 \r\n						";s:4:"back";s:30:"							 1.2MM化学片\r\n						";}s:5:"sizes";a:5:{i:34;s:3:"274";i:35;s:3:"275";i:36;s:3:"274";i:37;s:3:"274";i:38;s:3:"274";}s:5:"image";a:2:{s:8:"mianliao";s:28:"2014-11-01/5454848e3ca94.jpg";s:7:"neiliao";s:28:"2014-11-01/5454848e3edbd.jpg";}}', 34350),
(19, 13, 8, 1373, 8, 35, '32-38', 'abcde', 1, 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:0:"";s:5:"mould";s:5:"abcde";s:4:"dadi";s:12:"云溪130611";s:5:"macha";s:3:"5mm";s:5:"color";s:6:"黄色";s:8:"material";s:9:"黄牛皮";s:11:"zhencheinfo";s:16:"							 \r\n						";s:13:"chengxinginfo";s:16:"							 \r\n						";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:7:"N1101ML";s:4:"name";s:25:"鸡爪纹+金巴利浅棕";s:6:"method";s:14:"复1.5MM切片";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:8:"N1101TPL";s:4:"name";s:9:"双面平";s:6:"method";s:11:"复1.1MMEVA";}s:10:"zhongpaili";a:3:{s:6:"models";s:7:"N1101PU";s:4:"name";s:5:"PU里";s:6:"method";s:40:"复0.6MM切片(后跟小皮里不复合)";}s:8:"zhugenli";a:3:{s:6:"models";s:7:"N1101MZ";s:4:"name";s:9:"毛织里";s:6:"method";s:11:"复0.6MM切";}s:7:"xiedian";a:3:{s:6:"models";s:7:"N1101XD";s:4:"name";s:29:"PU里复1.3MM切片+乳胶片";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:7:"N1101BT";s:6:"format";s:9:"白细布";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:1:"N";s:6:"format";s:26:"1.5特克松+半插+普钢";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"dixian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:16:"							 \r\n						";s:4:"back";s:16:"							 \r\n						";}s:5:"sizes";a:7:{i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";i:37;s:0:"";i:38;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:0:"";s:7:"neiliao";s:0:"";}}', 48055);

-- --------------------------------------------------------

--
-- 表的结构 `orderstatus`
--

CREATE TABLE IF NOT EXISTS `orderstatus` (
  `os_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`os_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `orderstatus`
--

INSERT INTO `orderstatus` (`os_id`, `os_name`) VALUES
(1, '下单预产'),
(2, '下单投产'),
(3, '生产完结'),
(4, '发货完结');

-- --------------------------------------------------------

--
-- 表的结构 `sample`
--

CREATE TABLE IF NOT EXISTS `sample` (
  `s_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(45) DEFAULT NULL,
  `s_models` varchar(45) DEFAULT NULL,
  `s_material` varchar(45) DEFAULT NULL,
  `s_neili` varchar(45) DEFAULT NULL,
  `s_dadi` varchar(45) DEFAULT NULL,
  `s_chexian` varchar(45) DEFAULT NULL,
  `s_sizes` varchar(45) DEFAULT NULL,
  `s_price` varchar(10) DEFAULT NULL,
  `s_mould` varchar(45) DEFAULT NULL,
  `s_attribute` text,
  `s_soldout` tinyint(1) DEFAULT NULL,
  `s_isproduce` tinyint(1) DEFAULT NULL,
  `s_remark` text NOT NULL,
  `s_color` varchar(45) NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `sample`
--

INSERT INTO `sample` (`s_id`, `s_name`, `s_models`, `s_material`, `s_neili`, `s_dadi`, `s_chexian`, `s_sizes`, `s_price`, `s_mould`, `s_attribute`, `s_soldout`, `s_isproduce`, `s_remark`, `s_color`) VALUES
(1, '黑色凉鞋', 'B511-11L', '温港210+蓝磨', '黑色', '黑+灰沿', '灰线', '31-36', '36', '单鞋', 'a:32:{s:5:"shoes";a:8:{s:10:"c_sampleid";s:12:"ketenhuohao ";s:5:"mould";s:6:"单鞋";s:4:"dadi";s:10:"黑+灰沿";s:5:"macha";s:5:"05-06";s:11:"zhencheinfo";s:44:" 小心加工雅！！！  \r\n						 \r\n						";s:13:"chengxinginfo";s:32:"														 \r\n						 \r\n						";s:6:"remark";s:38:"							精品样鞋	 \r\n						 \r\n						";s:16:"xiaoliaopipiinfo";s:68:"					c的详细信息笑嘻嘻笑嘻嘻惺惺惜惺惺xxxxxx 							";}s:9:"material1";a:3:{s:6:"models";s:7:"面料1";s:4:"name";s:7:"面料1";s:6:"method";s:6:"压边";}s:9:"material2";a:3:{s:6:"models";s:7:"面料2";s:4:"name";s:7:"面料2";s:6:"method";s:6:"复合";}s:9:"material3";a:3:{s:6:"models";s:7:"面料3";s:4:"name";s:7:"面料3";s:6:"method";s:6:"烫平";}s:8:"toupaili";a:3:{s:6:"models";s:9:"头排里";s:4:"name";s:0:"";s:6:"method";s:9:"偷拍里";}s:10:"zhongpaili";a:3:{s:6:"models";s:12:"三翻四覆";s:4:"name";s:18:"撒酒疯斯蒂芬";s:6:"method";s:23:"送到附近斯蒂芬io";}s:8:"zhugenli";a:3:{s:6:"models";s:12:"斯蒂芬级";s:4:"name";s:13:"三季度范i";s:6:"method";s:12:"撒酒疯ig ";}s:7:"xiedian";a:3:{s:6:"models";s:19:"思考的积分igj ";s:4:"name";s:17:"三极管igfkjdfi";s:6:"method";s:16:"过户费电话 ";}s:6:"baotou";a:2:{s:6:"models";s:12:"似懂非懂";s:6:"format";s:9:"速度更";}s:8:"bangmian";a:2:{s:6:"models";s:6:"嘎斯";s:6:"format";s:15:"阿斯顿发送";}s:7:"zhongdi";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:15:"算法的撒旦";}s:10:"weikouliao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:10:"xieyanchen";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:8:"mianxian";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:10:"kousheliao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:7:"songjin";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"dixian";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:8:"outerbox";a:2:{s:6:"models";s:12:"斯蒂芬萨";s:6:"format";s:12:"斯蒂芬萨";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:32:"														 \r\n						 \r\n						";s:4:"back";s:32:"														 \r\n						 \r\n						";}s:5:"sizes";a:6:{i:31;s:0:"";i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:28:"2014-10-20/54450c04810bc.jpg";s:7:"neiliao";s:28:"2014-10-20/54450c048136d.jpg";}}', 0, 1, '测试修改过', ''),
(2, '精品牛皮鞋', '511-11LW', '雪地米+蓝磨', '米色', '', '白线', '31-36', '36', '单鞋', 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:0:"";s:5:"mould";s:6:"单鞋";s:4:"dadi";s:0:"";s:5:"macha";s:0:"";s:5:"color";s:0:"";s:8:"material";s:0:"";s:11:"zhencheinfo";s:16:"							 \r\n						";s:13:"chengxinginfo";s:16:"							 \r\n						";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:10:"zhongpaili";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"zhugenli";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:7:"xiedian";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"dixian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:16:"							 \r\n						";s:4:"back";s:16:"							 \r\n						";}s:5:"sizes";a:8:{i:31;s:0:"";i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";i:37;s:0:"";i:38;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:0:"";s:7:"neiliao";s:0:"";}}', 0, 1, '', ''),
(4, '黑色凉鞋', '511-11LM', '黑磨+温港210', '黑色', '', '灰线', '31-36', '36', '单鞋', NULL, 0, 0, '', ''),
(5, '单鞋', 'A001', '真皮', '哈希', '白加黑', '中性', '35,40', '40', '0051', NULL, 1, 0, '的空间付款的空间付款', '黑色'),
(6, 'abc', 'abc', '普通皮', '倒扣分', 'abc', '黑色', '32,45', '44', 'abc', NULL, 0, 0, 'abc', '黑色'),
(7, 'abcd', 'abcd ', 'abcd', 'abcd', 'ABCD', '灰色', '25,36', '34', 'ABCD', NULL, 0, 0, 'ABCD', '黄色'),
(8, 'abcde', 'abcde', '水牛皮', '红色', '云溪130611', '棕色', '25,40', '50', 'abcde', 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:0:"";s:5:"mould";s:5:"abcde";s:4:"dadi";s:12:"云溪130611";s:5:"macha";s:3:"5mm";s:5:"color";s:6:"黄色";s:8:"material";s:9:"黄牛皮";s:11:"zhencheinfo";s:16:"							 \r\n						";s:13:"chengxinginfo";s:16:"							 \r\n						";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:7:"N1101ML";s:4:"name";s:25:"鸡爪纹+金巴利浅棕";s:6:"method";s:14:"复1.5MM切片";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:8:"N1101TPL";s:4:"name";s:9:"双面平";s:6:"method";s:11:"复1.1MMEVA";}s:10:"zhongpaili";a:3:{s:6:"models";s:7:"N1101PU";s:4:"name";s:5:"PU里";s:6:"method";s:40:"复0.6MM切片(后跟小皮里不复合)";}s:8:"zhugenli";a:3:{s:6:"models";s:7:"N1101MZ";s:4:"name";s:9:"毛织里";s:6:"method";s:11:"复0.6MM切";}s:7:"xiedian";a:3:{s:6:"models";s:7:"N1101XD";s:4:"name";s:29:"PU里复1.3MM切片+乳胶片";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:7:"N1101BT";s:6:"format";s:9:"白细布";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:1:"N";s:6:"format";s:26:"1.5特克松+半插+普钢";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"dixian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:0:"";s:1:"b";s:0:"";s:1:"c";s:0:"";s:1:"d";s:0:"";s:1:"e";s:0:"";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:16:"							 \r\n						";s:4:"back";s:16:"							 \r\n						";}s:5:"sizes";a:7:{i:32;s:0:"";i:33;s:0:"";i:34;s:0:"";i:35;s:0:"";i:36;s:0:"";i:37;s:0:"";i:38;s:0:"";}s:5:"image";a:2:{s:8:"mianliao";s:0:"";s:7:"neiliao";s:0:"";}}', 0, 1, 'abcde', '黑色'),
(9, 'abcdef', 'abcdef', '黄牛皮', '打卡机', 'abcdef', '黑色', '35,41', '50', 'abcdef', NULL, 0, 0, '大房间打开方式京东方', '黑色'),
(10, '凉鞋', '132-6L', '蓝色绒面PU（打孔）', '内里PU垫脚猪二层', '云溪13061', '棕色', '32,37', '32', '凉鞋', 'a:32:{s:5:"shoes";a:10:{s:10:"c_sampleid";s:5:"12345";s:5:"mould";s:6:"凉鞋";s:4:"dadi";s:11:"云溪13061";s:5:"macha";s:3:"5mm";s:5:"color";s:6:"黑色";s:8:"material";s:9:"水牛皮";s:11:"zhencheinfo";s:71:"							 1.机心拥边要均匀,\r\n	2.边排粗线车顺畅,针距\r\n				";s:13:"chengxinginfo";s:49:"							 \r\n			1.前帮夹正,机心位置以确			";s:6:"remark";s:16:"							 \r\n						";s:16:"xiaoliaopipiinfo";s:13:"													";}s:9:"material1";a:3:{s:6:"models";s:5:"N1101";s:4:"name";s:25:"鸡爪纹+金巴利浅棕";s:6:"method";s:14:"复1.5MM切片";}s:9:"material2";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:9:"material3";a:3:{s:6:"models";s:0:"";s:4:"name";s:0:"";s:6:"method";s:0:"";}s:8:"toupaili";a:3:{s:6:"models";s:6:"N1101T";s:4:"name";s:9:"双面平";s:6:"method";s:9:" 1.1MMEVA";}s:10:"zhongpaili";a:3:{s:6:"models";s:6:"N1101P";s:4:"name";s:5:"PU里";s:6:"method";s:42:"复0.6MM切片(后跟小皮里不复合)		";}s:8:"zhugenli";a:3:{s:6:"models";s:6:"N1101M";s:4:"name";s:9:"毛织里";s:6:"method";s:14:"复0.6MM切片";}s:7:"xiedian";a:3:{s:6:"models";s:6:"N1101X";s:4:"name";s:29:"PU里复1.3MM切片+乳胶片";s:6:"method";s:0:"";}s:6:"baotou";a:2:{s:6:"models";s:6:"N1101B";s:6:"format";s:9:"白细布";}s:8:"bangmian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"zhongdi";a:2:{s:6:"models";s:6:"N1101Z";s:6:"format";s:26:"1.5特克松+半插+普钢";}s:10:"weikouliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"xieyanchen";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"mianxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:10:"kousheliao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"songjin";a:2:{s:6:"models";s:6:"N1101S";s:6:"format";s:6:"5CM宽";}s:6:"dixian";a:2:{s:6:"models";s:7:"N1101DX";s:6:"format";s:9:"顺里色";}s:6:"lalian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"chongzi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:11:"fengbaoxian";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:7:"fuqiang";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xieyan";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"xiedai";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"koushi";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"disubiao";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"innerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:8:"outerbox";a:2:{s:6:"models";s:0:"";s:6:"format";s:0:"";}s:6:"fangma";a:8:{s:1:"a";s:2:"34";s:1:"b";s:2:"35";s:1:"c";s:2:"36";s:1:"d";s:2:"37";s:1:"e";s:2:"38";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:6:"yajian";a:8:{s:1:"a";s:8:"ΛΛΛΛ";s:1:"b";s:3:"∏";s:1:"c";s:5:"∏Λ";s:1:"d";s:7:"∏ΛΛ";s:1:"e";s:9:"∏ΛΛΛ";s:1:"f";s:0:"";s:1:"g";s:0:"";s:1:"h";s:0:"";}s:7:"gangbao";a:2:{s:3:"pre";s:30:"			1.0MM化学片				 \r\n						";s:4:"back";s:30:"							 1.2MM化学片\r\n						";}s:5:"sizes";a:5:{i:34;s:3:"274";i:35;s:3:"275";i:36;s:3:"274";i:37;s:3:"274";i:38;s:3:"274";}s:5:"image";a:2:{s:8:"mianliao";s:28:"2014-11-01/5454848e3ca94.jpg";s:7:"neiliao";s:28:"2014-11-01/5454848e3edbd.jpg";}}', 0, 1, '', '黑色'),
(11, '单鞋', '132-1L', '蓝色绒面PU（打孔）', '全部杏色猪二层', '上白下蓝TPR', '黑色', '32,37', '35', '单鞋', NULL, 0, 0, '', '黄色');

-- --------------------------------------------------------

--
-- 表的结构 `shoeskinds`
--

CREATE TABLE IF NOT EXISTS `shoeskinds` (
  `sk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sk_name` varchar(45) DEFAULT NULL,
  `sk_price` float DEFAULT NULL,
  PRIMARY KEY (`sk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `storerecord`
--

CREATE TABLE IF NOT EXISTS `storerecord` (
  `sr_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `f_id` bigint(20) DEFAULT NULL,
  `e_id` bigint(20) DEFAULT NULL,
  `gl_id` bigint(20) DEFAULT NULL,
  `sr_number` int(11) DEFAULT NULL,
  `sr_time` datetime DEFAULT NULL,
  `sr_signer` varchar(45) DEFAULT NULL,
  `sr_orderid` varchar(45) DEFAULT NULL,
  `sr_totalpeice` float DEFAULT NULL,
  `sr_payedmoney` float DEFAULT NULL,
  `sr_settled` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`sr_id`),
  KEY `FK_Relationship_15` (`e_id`),
  KEY `FK_Relationship_4` (`gl_id`),
  KEY `FK_Relationship_9` (`f_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `storerecord`
--

INSERT INTO `storerecord` (`sr_id`, `f_id`, `e_id`, `gl_id`, `sr_number`, `sr_time`, `sr_signer`, `sr_orderid`, `sr_totalpeice`, `sr_payedmoney`, `sr_settled`) VALUES
(1, NULL, NULL, 1, 500000, '2014-10-21 19:44:35', NULL, NULL, 5000000, 5000000, 1),
(2, NULL, 1, 1, 400, '2014-10-21 19:45:16', '测试人', NULL, NULL, NULL, 1),
(3, NULL, 2, 1, 400, '2014-10-21 19:50:13', '测试人', NULL, NULL, NULL, 1),
(4, NULL, 3, 1, 400, '2014-10-21 19:51:11', '测试人', NULL, NULL, NULL, 1),
(5, NULL, NULL, NULL, 400, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(6, NULL, NULL, NULL, 400, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(7, NULL, NULL, NULL, 50, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(8, NULL, NULL, NULL, 500, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(9, NULL, NULL, NULL, 500, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(10, NULL, NULL, NULL, 63, '2014-10-23 22:34:42', '测试人 范美希', '9', NULL, NULL, 0),
(11, NULL, 4, 1, 200, '2014-10-31 15:38:13', '玲玲', NULL, NULL, NULL, 1),
(12, NULL, NULL, NULL, 1374, '2014-11-01 15:33:33', 'test', '13', NULL, NULL, 0),
(13, NULL, NULL, NULL, 1374, '2014-11-01 15:33:33', 'test', '13', NULL, NULL, 0),
(14, NULL, NULL, NULL, 86, '2014-11-01 15:33:33', 'test', '13', NULL, NULL, 0),
(15, NULL, NULL, NULL, 1373, '2014-11-01 15:33:34', 'test', '13', NULL, NULL, 0),
(16, NULL, NULL, NULL, 1373, '2014-11-01 15:33:34', 'test', '13', NULL, NULL, 0),
(17, NULL, NULL, NULL, 172, '2014-11-01 15:33:34', 'test', '13', NULL, NULL, 0);

--
-- 限制导出的表
--

--
-- 限制表 `delivergoods`
--
ALTER TABLE `delivergoods`
  ADD CONSTRAINT `FK_Relationship_10` FOREIGN KEY (`o_id`) REFERENCES `orders` (`o_id`),
  ADD CONSTRAINT `FK_Relationship_16` FOREIGN KEY (`gl_id`) REFERENCES `goodslist` (`gl_id`);

--
-- 限制表 `epiboly`
--
ALTER TABLE `epiboly`
  ADD CONSTRAINT `FK_Relationship_8` FOREIGN KEY (`fp_id`) REFERENCES `followproduce` (`fp_id`);

--
-- 限制表 `followproduce`
--
ALTER TABLE `followproduce`
  ADD CONSTRAINT `FK_Reference_16` FOREIGN KEY (`od_id`) REFERENCES `ordersdetail` (`od_id`);

--
-- 限制表 `goodslist`
--
ALTER TABLE `goodslist`
  ADD CONSTRAINT `FK_Relationship_3` FOREIGN KEY (`gk_id`) REFERENCES `goodskind` (`gk_id`);

--
-- 限制表 `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_Relationship_5` FOREIGN KEY (`s_id`) REFERENCES `sample` (`s_id`);

--
-- 限制表 `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `FK_Relationship_1` FOREIGN KEY (`mk_id`) REFERENCES `memberkind` (`mk_id`);

--
-- 限制表 `memberjj`
--
ALTER TABLE `memberjj`
  ADD CONSTRAINT `FK_Relationship_11` FOREIGN KEY (`dg_id`) REFERENCES `delivergoods` (`dg_id`),
  ADD CONSTRAINT `FK_Relationship_12` FOREIGN KEY (`m_id`) REFERENCES `member` (`m_id`),
  ADD CONSTRAINT `FK_Relationship_13` FOREIGN KEY (`sk_id`) REFERENCES `shoeskinds` (`sk_id`);

--
-- 限制表 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_Relationship_14` FOREIGN KEY (`os_id`) REFERENCES `orderstatus` (`os_id`);

--
-- 限制表 `ordersdetail`
--
ALTER TABLE `ordersdetail`
  ADD CONSTRAINT `FK_Reference_14` FOREIGN KEY (`o_id`) REFERENCES `orders` (`o_id`),
  ADD CONSTRAINT `FK_Reference_15` FOREIGN KEY (`s_id`) REFERENCES `sample` (`s_id`);

--
-- 限制表 `storerecord`
--
ALTER TABLE `storerecord`
  ADD CONSTRAINT `FK_Relationship_15` FOREIGN KEY (`e_id`) REFERENCES `epiboly` (`e_id`),
  ADD CONSTRAINT `FK_Relationship_4` FOREIGN KEY (`gl_id`) REFERENCES `goodslist` (`gl_id`),
  ADD CONSTRAINT `FK_Relationship_9` FOREIGN KEY (`f_id`) REFERENCES `firm` (`f_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
