-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2012 at 08:59 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iitdebates`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comid` int(11) NOT NULL AUTO_INCREMENT,
  `score` float NOT NULL,
  `author` bigint(30) NOT NULL,
  `value` longtext NOT NULL,
  `debid` int(11) NOT NULL,
  `foragainst` int(2) NOT NULL,
  `upvotes` longtext NOT NULL,
  `downvotes` longtext NOT NULL,
  PRIMARY KEY (`comid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=203 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comid`, `score`, `author`, `value`, `debid`, `foragainst`, `upvotes`, `downvotes`) VALUES
(81, 0, 653499724, 'topic', 114, 1, '', ''),
(88, 0, 653499724, 'voice', 117, 1, '', ''),
(89, 0, 653499724, 'voice', 117, 0, '', ''),
(90, 0, 653499724, 'fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl fgjdkl ', 117, 1, '', ''),
(91, 0, 653499724, 'fdgd', 117, 1, '', ''),
(92, 0, 653499724, 'the comments...', 118, 1, '', ''),
(93, 0, 653499724, 'those comments...', 118, 1, '', ''),
(94, 0, 653499724, 'those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...those comments...', 118, 1, '', ''),
(95, 0, 653499724, 'dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf ', 118, 0, '', ''),
(96, 0, 653499724, 'dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf dfdfmkldfvfdvf ', 118, 0, '', ''),
(97, 0, 653499724, 'dfsfsf\nsdf\nsdf\nsdfs\ndfsd\nfsdf\nsdf\nsdf\nsdf\nsdf\nsdfsf\ndsfs\ndf', 119, 1, '', ''),
(98, 0, 653499724, 'sdfsdfjkl\nsdf\nfsdf\nsdf\nsdfsdf\nsdfsdf\nsdfdfsdfjskldjfsdf\ns\nsdf\nsdf\nsd', 119, 1, '', ''),
(102, 0, 0, 'dfsfs\nsdf\nsdf\nsdf\nsdf', 122, 1, '', ''),
(103, 0, 0, 'i have nothing against you man, but you really need to understand that this is not about being ', 122, 0, '', ''),
(104, 0, 653499724, 'the topic...', 124, 1, '', ''),
(105, 0, 653499724, 'jfdsjkljsdfklj sdfjklsdfj jsdklfsj', 125, 0, '', ''),
(106, 0, 653499724, 'dshjfksfhjkhjksdfhdskfhsdkfhsdjkf', 125, 1, '', ''),
(107, 0, 653499724, 'sdfdsjkf', 127, 1, '', ''),
(108, 0, 653499724, 'dsfjksdlf', 127, 0, '', ''),
(109, 0, 653499724, 'dfgjkhfd\ndfgld\nd\nfgdfgljdfg\nd\ngdf\ngdfgdsfjksldjf', 127, 1, '', ''),
(110, 0, 653499724, 'dsfjlsk\nsfsdjklfjsdf\nsdfskjdlfsdfds', 127, 0, '', ''),
(111, 0, 653499724, 'sdfjslsdf\nsdfklsdf\nsdfsk', 127, 0, '', ''),
(112, 0, 653499724, 'dsfsdjkflsdf\nsfkdlsfsd\nfsdklfsdjf\nsdsfjklsd', 127, 0, '', ''),
(113, 0, 653499724, 'sdfjksdl\nsdf\nsf\nsdf', 127, 0, '', ''),
(114, 0, 653499724, '\nsdf\ndsfsdfhjsd', 127, 0, '', ''),
(115, 0, 653499724, 'sdfjkls\nsfsdfsnkflsdf\nsd\nfsd\nfsd\nf', 127, 1, '', ''),
(116, 0, 653499724, 'sdfs\nsdf\nsdf\ns', 127, 1, '', ''),
(117, 0, 653499724, 'sdfsd', 127, 0, '', ''),
(118, 0, 653499724, 'dfjskdl', 127, 1, '', ''),
(119, 0, 653499724, 'sdfjlsdf', 127, 0, '', ''),
(124, 0, 0, 'right?', 128, 0, '', ''),
(125, 0, 0, 'right?', 128, 1, '', ''),
(126, 0, 0, 'I have complete faith in the private sector. They will charge more but the food will be eatable. In fact, I would go a step further and talk of privatizing all of the hostels here at IIT. It is clear that IIT can''t take care of it''s students, so we must do so ourselves.', 128, 1, '', ''),
(137, 0, 653499724, 'Yes, I think IIT messes are very poorly managed and hence privatizing them will be a good idea. Although, we must be careful in the process.', 134, 1, '', ''),
(140, 0, 653499724, 'sdfjksldf', 134, 0, '', ''),
(141, 0, 653499724, 'sdjfkl', 134, 0, '', ''),
(142, 0, 653499724, 'dfgjkldfg', 134, 1, '', ''),
(143, 0, 653499724, 'dfjkdflg\ndfgkdlgf\ndfgdfglkdfg\ndfg', 135, 1, '', ''),
(144, 0, 653499724, 'fdjdlj', 136, 1, '653499724,653499724', '653499724'),
(145, 0, 653499724, 'fdjgkfdljglkd', 136, 0, '', ''),
(146, 0, 653499724, 'dfgjklfd', 136, 1, '653499724', ''),
(147, 0, 653499724, 'sgdfg\nsdg\ndf\ngdf\ngdf\n\ndsfgdfg\ndgdfgsdfgd\n\ndsgdfgd\ngdfgdfg\ndfgd\nsgdfsdfgdg\nd\nfsgds\n', 136, 1, '', ''),
(155, 0, 653499724, 'dffdgddfgddfgdfg', 138, 1, '', ''),
(156, 0, 653499724, 'this is the most thought provoking debate i have seen here. it is very important that if nothing else we must work more the core issues.', 144, 1, '', ''),
(157, 0, 653499724, 'the topic is relevant but i think it is not something that is possible.', 148, 1, '', ''),
(158, 0, 653499724, 'sfdsfsddsf', 150, 1, '', ''),
(159, 0, 653499724, 'i have absolutely no idea why you took the last step?', 150, 1, '', ''),
(160, 0, 653499724, 'Damn you man. You are totally insane!', 150, 0, '', ''),
(161, 0, 653499724, 'This is the debate topic', 151, 0, '', ''),
(162, 0, 653499724, 'dsf\nsf\ndsf\nsdf\nsd\nf', 151, 1, '', ''),
(163, 0, 653499724, 'sd\nd\nd\nd\nd', 152, 0, '', ''),
(164, 0, 653499724, 'sdfsdf', 158, 1, '', ''),
(165, 0, 653499724, 'Here are a few comments I have added', 165, 1, '', ''),
(166, 0, 653499724, 'They seem to be enough, right?', 165, 1, '', ''),
(167, 0, 653499724, 'Just so that this side doesn''t feel left out.', 165, 0, '', ''),
(168, 0, 2147483647, 'This is the comment... ', 179, 1, '', ''),
(169, 0, 2147483647, 'dfjsklfjl', 179, 0, '', ''),
(171, 0, 2147483647, 'This is my comment', 179, 1, '', ''),
(172, 0, 2147483647, 'This is the message', 179, 1, '', ''),
(173, 0, 2147483647, 'sdfdklf;gk', 179, 1, '', ''),
(174, 0, 2147483647, 'dsfjsklfj', 179, 1, '', ''),
(175, 0, 2147483647, 'asddkkdskl', 179, 1, '', ''),
(176, 0, 100003955575567, 'sdfjsklfd\ndsfsdf', 184, 1, '2147483647', ''),
(177, 0, 100003955575567, 'Here is one comment I added', 185, 1, '', ''),
(178, 0, 100003955575567, 'Here is another comment', 185, 1, '', ''),
(179, 0, 100003955575567, 'This is against the topic', 185, 0, '', ''),
(180, 0, 100003955575567, 'This has a nice slide down effect', 185, 1, '', ''),
(183, 0, 100003955575567, 'does this work ont he voting as well?\n', 186, 0, '653499724', ''),
(184, 0, 100003955575567, 'oh man this is even active!', 186, 1, '653499724', ''),
(185, 0, 100003955575567, 'This is another commnt', 186, 1, '653499724', ''),
(186, 0, 100003955575567, 'it seems vetter', 186, 1, '', ''),
(187, 0, 2147483647, 'I have extreme confidence in you man', 185, 1, '', ''),
(188, 0, 100003955575567, 'extreme confidence?', 186, 1, '', ''),
(189, 0, 100003955575567, 'sdfsdfsdf\nsf\ndsf\nsdf\nsd', 186, 1, '', ''),
(190, 0, 100003955575567, 'sdfsf\nsdf\nsd\nfsdf\nsdfsdfsdfsf dfsdg vsdfds', 186, 1, '653499724', ''),
(191, 0, 0, 'dfjklsj', 187, 1, '', ''),
(193, 0, 653499724, 'sdfhjsdklfj', 189, 1, '', ''),
(194, 0, 653499724, 'This is a comment', 194, 1, '', ''),
(195, 0, 653499724, 'I think this is not so bad', 194, 1, '100003955575567', ''),
(196, 0, 653499724, 'sdfsdhfkj', 194, 1, '', ''),
(197, 0, 653499724, 'sdfjhsdkfh', 194, 1, '', ''),
(198, 0, 653499724, 'sdfsjdkf', 194, 1, '', ''),
(199, 0, 653499724, 'dfsfdsfjl', 194, 0, '', ''),
(200, 0, 653499724, 'sfsdfsd', 194, 0, '', ''),
(201, 0, 653499724, 'dfgfdg', 194, 1, '', ''),
(202, 0, 100003955575567, 'sdfhjkshdfk', 194, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `debates`
--

CREATE TABLE IF NOT EXISTS `debates` (
  `debid` int(11) NOT NULL AUTO_INCREMENT,
  `debscore` float NOT NULL DEFAULT '0',
  `topic` mediumtext NOT NULL,
  `description` longtext NOT NULL,
  `timelimit` int(11) NOT NULL,
  `themes` mediumtext NOT NULL,
  `participants` mediumtext NOT NULL,
  `followers` mediumtext NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `creator` bigint(30) NOT NULL,
  `startdate` date NOT NULL,
  `winners` mediumtext NOT NULL,
  `privacy` int(2) NOT NULL,
  PRIMARY KEY (`debid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=196 ;

--
-- Dumping data for table `debates`
--

INSERT INTO `debates` (`debid`, `debscore`, `topic`, `description`, `timelimit`, `themes`, `participants`, `followers`, `rating`, `creator`, `startdate`, `winners`, `privacy`) VALUES
(160, 0, 'Should IIT Messes be privatized?', 'IIT Messes for so long now been the object of criticism and ridicule for one and all. But can nothing be done to improve them? Is good food necessarily expensive? Is cheap food always bad? Can good food be scaled to more people?', 40, 'IIT Mess, ', 'rr', '100003955575567', 0, 653499724, '2012-06-11', '', 0),
(184, 0, 'This is a new dummy debate made', '', 10, '', 'rrrrrrr', '', 0, 2147483647, '2012-06-16', '', 0),
(185, 0, 'New Debate', '', 10, '', '', '', 0, 2147483647, '2012-06-16', '', 0),
(186, 0, 'hopefully works now', '', 10, '', 'rrrrrrrr100003955575567', '653499724', 0, 100003955575567, '2012-06-16', '', 0),
(187, 0, 'A new deabte', 'fjdsklfjslk', 10, 'dsklfjsd', '0', '', 0, 0, '2012-06-17', '', 0),
(188, 0, 'sdfjsdjfkl', 'jdskljfdslj', 10, '', '0', '', 0, 0, '2012-06-17', '', 0),
(189, 0, 'This is my first open debates', 'sdkfldjs', 20, 'dsjklf', '502422934,781795150,653499724', '', 0, 653499724, '2012-06-17', '', 0),
(190, 0, 'sdfjklsdjfklj', 'dskfljsdljfsd', 10, 'dksjfs', '781795150,100000901607885,1581768158,', '', 0, 653499724, '2012-06-17', '', 0),
(191, 0, 'dfsdklfjskljf', 'kldsjfskl', 10, 'dkslfjsdklf', '502422934,1468368368,', '', 0, 653499724, '2012-06-17', '', 0),
(192, 0, 'fsdklfjskdljf', 'sdklfjsdkl', 10, '', '502422934,781795150,100000901607885,653499724', '', 0, 653499724, '2012-06-17', '', 0),
(193, 0, 'dfjksdlj', 'df', 10, '', '502422934,781795150,500879050,598509677,653499724', '', 0, 653499724, '2012-06-17', '', 0),
(194, 0, 'Is Aravali too political? Is it good for its culture?', 'This is serious to all of us and our juniors. What legacy are we leaving?', 10, 'IIT Hostels, ', '1581768158,1197526121,100000933803372,653499724,100003955575567', '100003955575567', 0, 653499724, '2012-06-17', '', 0),
(195, 0, 'sdfsdklf;k', 'sdlf;ksd', 10, '', '100003955575567', '', 0, 100003955575567, '2012-06-17', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `follower`
--

CREATE TABLE IF NOT EXISTS `follower` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(30) NOT NULL,
  `follower` bigint(30) NOT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `follower`
--

INSERT INTO `follower` (`fid`, `uid`, `follower`) VALUES
(2, 6, 653499724),
(6, 42, 653499724),
(7, 7, 100003955575567);

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `updateid` int(11) NOT NULL AUTO_INCREMENT,
  `foruid` int(30) NOT NULL,
  `msg` longtext NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`updateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`updateid`, `foruid`, `msg`, `timestamp`) VALUES
(2, 4, 'd159;f', '2012-06-11 20:49:45'),
(3, 5, 'd160;f', '2012-06-11 20:54:41'),
(4, 6, 'd160;f', '2012-06-11 20:56:16'),
(5, 7, 'd160;f', '2012-06-12 17:16:32'),
(6, 8, 'd160;f', '2012-06-14 18:44:36'),
(7, 9, 'd160;f', '2012-06-14 18:44:37'),
(8, 10, 'd160;f', '2012-06-14 18:44:39'),
(9, 11, 'd160;f', '2012-06-14 18:44:40'),
(10, 12, 'd160;f', '2012-06-14 18:44:42'),
(11, 13, 'd160;f', '2012-06-14 18:44:43'),
(12, 14, 'd160;f', '2012-06-14 18:44:45'),
(13, 15, 'd160;f', '2012-06-14 18:44:46'),
(14, 16, 'd160;f', '2012-06-14 18:44:48'),
(15, 17, 'd160;f', '2012-06-14 18:44:49'),
(16, 18, 'd160;f', '2012-06-14 18:44:51'),
(17, 19, 'd160;f', '2012-06-14 18:44:53'),
(18, 20, 'd160;f', '2012-06-14 18:44:54'),
(19, 21, 'd160;f', '2012-06-14 18:44:56'),
(20, 22, 'd160;f', '2012-06-14 18:44:57'),
(21, 23, 'd160;f', '2012-06-14 18:44:59'),
(22, 24, 'd160;f', '2012-06-14 19:01:36'),
(23, 25, 'd160;f', '2012-06-14 19:01:39'),
(24, 26, 'd160;f', '2012-06-14 19:01:41'),
(25, 27, 'd160;f', '2012-06-14 19:01:42'),
(26, 28, 'd160;f', '2012-06-14 19:01:43'),
(27, 29, 'd160;f', '2012-06-14 19:01:45'),
(28, 30, 'd160;f', '2012-06-14 19:01:46'),
(29, 31, 'd160;f', '2012-06-14 19:01:48'),
(30, 32, 'd160;f', '2012-06-14 19:01:49'),
(31, 33, 'd160;f', '2012-06-14 19:01:51'),
(32, 34, 'd160;f', '2012-06-14 19:01:52'),
(33, 35, 'd160;f', '2012-06-14 19:01:54'),
(34, 36, 'd160;f', '2012-06-14 19:01:55'),
(35, 37, 'd160;f', '2012-06-14 19:01:57'),
(36, 38, 'd160;f', '2012-06-14 19:01:59'),
(37, 39, 'd160;f', '2012-06-14 19:02:00'),
(38, 40, 'd160;f', '2012-06-14 19:02:02'),
(39, 41, 'd160;f', '2012-06-14 19:02:03'),
(40, 42, 'd160;f', '2012-06-14 19:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` bigint(30) NOT NULL,
  `name` varchar(80) NOT NULL,
  `interests` longtext NOT NULL,
  `score` float NOT NULL DEFAULT '100',
  `debateswon` longtext NOT NULL,
  `mydebates` longtext NOT NULL,
  `participantdebates` longtext NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `fbid`, `name`, `interests`, `score`, `debateswon`, `mydebates`, `participantdebates`) VALUES
(1, 0, 'Dummy', 'dummy', 0, 'dummy', 'dummy', 'dummy'),
(7, 653499724, 'Ravee Malla', 'Politics, IIT Academics, IIT Mess', 100, '', '', ''),
(42, 100003955575567, 'Iit Debates', 'users, traffic on the site, ability, speed', 100, '', '', ''),
(43, 502422934, 'Pranav Aggarwal', '', 100, '', '', ''),
(44, 781795150, ' Utkarsh Kawatra', '', 100, '', '', ''),
(45, 500879050, ' Sonali Batra', '', 100, '', '', ''),
(46, 598509677, ' Ravi Teja Karusala', '', 100, '', '', ''),
(47, 1581768158, 'Pranay Agarwal', '', 100, '', '', ''),
(48, 1197526121, ' Snehil Basoya', '', 100, '', '', ''),
(49, 100000933803372, ' Mathew George', '', 100, '', '', '');
