-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-07-09 13:00:27
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `php_album`
--

-- --------------------------------------------------------

--
-- 表的结构 `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '相册ID',
  `pid` int(10) unsigned NOT NULL COMMENT '上级相册ID',
  `path` text NOT NULL COMMENT '相册路径',
  `name` varchar(12) NOT NULL COMMENT '相册名',
  `cover` varchar(255) NOT NULL COMMENT '封面图地址',
  `total` int(10) unsigned NOT NULL COMMENT '图片数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `album`
--

INSERT INTO `album` (`id`, `pid`, `path`, `name`, `cover`, `total`) VALUES
(1, 0, '0,', '风景', '2019-05/19/ada188bafabb40ba19b02e935a43022f.jpg', 1),
(4, 0, '0,', '搞黄色', '2019-05/20/9dc37279e657dde14919d70d3fcb9566.jpg', 4),
(6, 4, '0,4,', '的', '2019-05/20/4bd806ee8733e8ef192a5845f65fe94e.JPG', 1),
(7, 4, '0,4,', '沙雕', '2019-05/19/704673dde699a6b7ff27079b39130676.jpg', 1),
(8, 1, '0,1,', '山脉', '2019-05/20/f5b6c3ad73d0307caa8af5b4ec92f690.jpg', 1),
(9, 0, '0,', '测试', '2019-05/26/a1101020fb5c1d380f6f12471c3b2b7c.jpg', 1);

-- --------------------------------------------------------

--
-- 表的结构 `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片ID',
  `pid` int(10) unsigned NOT NULL COMMENT '所属相册ID',
  `name` varchar(80) NOT NULL COMMENT '图片名',
  `save` varchar(255) NOT NULL COMMENT '保存地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- 转存表中的数据 `picture`
--

INSERT INTO `picture` (`id`, `pid`, `name`, `save`) VALUES
(30, 0, 'Google-Pixel', '2019-05/19/ca107d4e852e2603ca2879ac262451f1.jpg'),
(33, 0, '3c426783dca4', '2019-05/19/6a65e12ed34e98444837555a2512a482.jpg'),
(34, 0, 'cb4a3e9fcee4', '2019-05/19/14396d3c82ae4680705285462ef5a4e6.jpeg'),
(35, 4, '8c8451e33aa8', '2019-05/19/e9d777ad024784dfa29bfcdf277c2bc2.jpeg'),
(39, 7, 'QQ图片201809', '2019-05/19/704673dde699a6b7ff27079b39130676.jpg'),
(40, 8, '10', '2019-05/20/f5b6c3ad73d0307caa8af5b4ec92f690.jpg'),
(42, 0, '39564d540923', '2019-05/20/af04ed4958d2d08871b114c360eb37d0.jpg'),
(43, 6, 'IMG_0042', '2019-05/20/4bd806ee8733e8ef192a5845f65fe94e.JPG'),
(44, 4, 'QQ图片201709', '2019-05/20/9dc37279e657dde14919d70d3fcb9566.jpg'),
(46, 0, '9tB1Z8n_M0mB', '2019-05/20/4413f293605bbc48e3ea6f4cdcc1177b.jpg'),
(47, 0, '53d89daca5c2', '2019-05/20/d9f6fd1d8b8ae6b90d48fceb4f7a335e.jpg'),
(48, 0, 'QQ图片201710', '2019-05/20/4dada9a834d63504d1dd6aa858c4f340.jpg'),
(50, 0, '_20181210223', '2019-05/20/4db114bb513a3bdce1a46e32a609e58e.jpg'),
(51, 0, '2', '2019-05/26/f9906b147712d6ea5bb386eff6a39d53.jpg'),
(52, 9, 'wallhaven-18', '2019-05/26/a1101020fb5c1d380f6f12471c3b2b7c.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
