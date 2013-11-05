-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 05, 2013 at 06:51 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `p2_mahe-cscie-15_biz`
--
CREATE DATABASE IF NOT EXISTS `p2_mahe-cscie-15_biz` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `p2_mahe-cscie-15_biz`;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `created`, `modified`, `user_id`, `content`) VALUES
(1, 1383326448, 1383326448, 1, 'This is a new post'),
(2, 1383327118, 1383327118, 1, 'This is a new post'),
(3, 1383327147, 1383327147, 1, 'This is another post 3'),
(4, 1383327182, 1383327182, 1, 'This is another post 4'),
(5, 1383327999, 1383327999, 1, 'Let me see if this works #fingerscrossed'),
(6, 1383328126, 1383328126, 1, 'Another one'),
(7, 1383328260, 1383328260, 1, 'Yet another one'),
(8, 1383363831, 1383363831, 1, 'Whole new post'),
(9, 1383370900, 1383370900, 2, 'Without rules, we all might as well be up in a tree flinging our crap at each other.'),
(10, 1383371720, 1383371720, 3, 'I think you''d call it "nailing", Fez. Just like Kelso nailed Hyde''s sister.'),
(11, 1383400562, 1383400562, 2, 'What are you going to put put on your resume: dumbass?'),
(12, 1383408097, 1383408097, 9, 'Look, the sooner you realize I''m a genius, the better off we''ll both be.'),
(13, 1383408180, 1383408180, 9, 'I hate that show. Okay, they have these commercials that you think are real, but they''re not real. And then, you wanna buy the stuff! '),
(14, 1383412629, 1383412629, 9, 'This is a new post #help'),
(15, 1383412791, 1383412791, 9, 'This is another test #yolo'),
(16, 1383412840, 1383412840, 9, 'This is another test #yolo'),
(17, 1383412875, 1383412875, 9, 'This is another test #yolo #winning'),
(18, 1383412944, 1383412944, 9, 'This #indvsaus is #wicked'),
(19, 1383413010, 1383413010, 9, 'This #indvsaus is #wicked'),
(20, 1383413155, 1383413155, 9, 'This #indvsaus is #wicked'),
(21, 1383413246, 1383413246, 9, 'This #indvsaus is #wicked'),
(22, 1383413338, 1383413338, 9, 'This #indvsaus is #wicked'),
(23, 1383413865, 1383413865, 9, 'This #indvsaus is #wicked #time #this'),
(24, 1383415684, 1383415684, 9, 'This is #one #time #thing'),
(25, 1383415785, 1383415785, 9, 'This is #new #thing'),
(26, 1383415882, 1383415882, 9, 'This is #new #thing #too'),
(27, 1383415921, 1383415921, 9, 'This is #new #thing #too'),
(28, 1383415954, 1383415954, 9, 'This is #new #thing #too'),
(29, 1383415967, 1383415967, 9, 'This is #new #thing #too'),
(30, 1383448455, 1383448455, 8, 'I like to say something #too'),
(32, 1383453950, 1383453950, 8, 'This is a "quote"'),
(33, 1383453970, 1383453970, 8, 'This is a ''quote''\r\n!@$%^&*()'),
(34, 1383453995, 1383453995, 8, 'This is #great'),
(35, 1383454241, 1383454241, 8, '&lt;script&gt;'),
(36, 1383454259, 1383454259, 8, '&lt;script&gt;alert(document.cookie);&lt;/script&gt;'),
(37, 1383454294, 1383454294, 8, 'This is #fail'),
(38, 1383499566, 1383499566, 8, '&lt;h1&gt;I can enter HTML script&lt;/h1&gt;&lt;center&gt;&lt;img src=http://images.wikia.com/degrassi/images/0/04/Hello-kitty-face-63-hd-wallpapers.png&gt;&lt;br&gt;&lt;/center&gt;	'),
(39, 1383533439, 1383533439, 8, 'Check #winning'),
(40, 1383541968, 1383541968, 8, 'This is a new post #brand #new'),
(41, 1383546294, 1383546294, 8, '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111'),
(42, 1383546854, 1383546854, 8, 'this is a test this is another test this is a test. This is yet another test. This is further testing. This is even more testing. I don''t know how long I need to type this before I reach 255 chars. Still more to go. Damn you auto-correct. You cannot judge'),
(43, 1383548670, 1383548670, 8, 'This is a post'),
(44, 1383548693, 1383548693, 8, 'This is a post #winning'),
(45, 1383615204, 1383615204, 8, 'Lets see if this will work #fingerscrossed'),
(46, 1383615235, 1383615235, 8, 'That kinda took so long #fail'),
(47, 1383617693, 1383617693, 8, 'this is a test this is another test this is a test. This is yet another test. This is further testing. This is even more testing. I don''t know how long I need to type this before I reach 255 chars. Still more to go. Damn you auto-correct. You cannot #too'),
(48, 1383621326, 1383621326, 9, 'This is a test #winning');

-- --------------------------------------------------------

--
-- Table structure for table `posts_keywords`
--

CREATE TABLE IF NOT EXISTS `posts_keywords` (
  `post_keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` text NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`post_keyword_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `posts_keywords`
--

INSERT INTO `posts_keywords` (`post_keyword_id`, `keyword`, `post_id`) VALUES
(35, 'new', 28),
(36, 'thing', 28),
(37, 'too', 28),
(38, 'new', 29),
(39, 'thing', 29),
(40, 'too', 29),
(41, 'too', 30),
(42, 'great', 34),
(43, 'fail', 37),
(44, 'winning', 39),
(45, 'brand', 40),
(46, 'new', 40),
(47, 'winning', 44),
(48, 'fingerscrossed', 45),
(49, 'fail', 46),
(50, 'too', 47),
(51, 'winning', 48);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` int(11) NOT NULL,
  `timezone` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `created`, `modified`, `token`, `password`, `last_login`, `timezone`, `first_name`, `last_name`, `email`, `avatar`) VALUES
(1, 1383273477, 1383273477, '32ec8313dcd81d4ccd0089efd2882a86c8c68783', '35cd4244e0f4bd85a971f4dc663bcacb7b085f14', 0, 0, 'Mahendran', 'Nair', 'wm_destroy@yahoo.com', '\\uploads\\avatars\\default.gif'),
(2, 1383370764, 1383370764, '29234c14d1b3a4ddde1ca886720816d394e82d3f', '01a7d15753ab8399bd77b87225233699dc713ae3', 0, 0, 'Red', 'Forman', 'red@forman.com', '/uploads/avatars/2_avatar.jpg'),
(3, 1383371645, 1383371645, '485a9c5aea2f651b2c2cdf101b2455ed2f3a9890', 'c2f09d1365b517ef1162c29a09c63b7f8613cc97', 0, 0, 'Eric', 'Forman', 'eric@forman.com', '\\uploads\\avatars\\default.gif'),
(4, 1383403635, 1383403635, 'ff953809a4d206e612abda87a575740653b7a3af', 'dce3d9aca85fa878b106c4f35b6736942af32fa3', 0, 0, 'Donna', 'Pinciatti', 'donnapinciatti.com', '\\uploads\\avatars\\default.gif'),
(5, 1383406424, 1383406424, '2d0c95de3d28a0e90609d237f30e6f9a95bc4c83', 'dce3d9aca85fa878b106c4f35b6736942af32fa3', 0, 0, 'Donna', 'Pinciatti', 'donnapinciatti.com', '\\uploads\\avatars\\default.gif'),
(6, 1383406469, 1383406469, '370419b63200472cadb4a5910c044a6a15b4e764', 'c2f09d1365b517ef1162c29a09c63b7f8613cc97', 0, 0, 'Kitty', 'Forman', 'kitty@forman.com', '\\uploads\\avatars\\default.gif'),
(7, 1383407163, 1383407163, 'af8979441ed74cc0c69790cf151ed52502f664ad', '0ae62bb76c73550a72a9197caef0ff86b94892b8', 0, 0, 'Michael', 'Kelso', 'mike@kelso.com', '\\uploads\\avatars\\default.gif'),
(8, 1383407209, 1383407209, '20233249704980615049d4c361547c2a3a146041', 'f33d744f5cb5387d06d75c3cadc657f17e154dce', 0, 0, 'Steven', 'Hyde', 'steve@hyde.com', '/uploads/avatars/8_avatar.jpg'),
(9, 1383407754, 1383631632, '1f48eb4c9349ab2c25f6b2808f1145712eb40986', '5bfbd0713e24b97506de6e3d692a1f613526e290', 0, 0, 'Jackie', 'Burkhardt', 'jackie@burkhardt.com', '/uploads/avatars/9_avatar.png'),
(10, 1383502509, 1383502509, '68e10c2299e97d5e8cb26faa08c285065c1b0ed5', '6877bad607df66942c814fbd5c72573537e23595', 0, 0, 'Bob', 'Pinciotti', 'bob@pinciotti.com', '/uploads/avatars/10_avatar.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users_users`
--

CREATE TABLE IF NOT EXISTS `users_users` (
  `user_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_followed` int(11) NOT NULL,
  PRIMARY KEY (`user_user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users_users`
--

INSERT INTO `users_users` (`user_user_id`, `created`, `user_id`, `user_id_followed`) VALUES
(1, 1383399622, 2, 3),
(2, 1383408187, 9, 7),
(3, 1383534234, 8, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts_keywords`
--
ALTER TABLE `posts_keywords`
  ADD CONSTRAINT `posts_keywords_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_users`
--
ALTER TABLE `users_users`
  ADD CONSTRAINT `users_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
