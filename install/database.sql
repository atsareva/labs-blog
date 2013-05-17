-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 09 2012 г., 21:58
-- Версия сервера: 5.1.62
-- Версия PHP: 5.3.6-13ubuntu3.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `patent_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `access`
--

INSERT INTO `access` (`id`, `title`, `description`) VALUES
(1, 'for_all', 'Для всех'),
(2, 'for_register', 'Для всех зарегистрированных'),
(3, 'for_admin', 'Для аминистраторов'),
(4, 'for_moderator', 'Для модераторов'),
(5, 'for_superadmin', 'Для суперадминистратора');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `text` text,
  `author_id` int(11) DEFAULT NULL,
  `created` varchar(40) NOT NULL,
  `modified` varchar(40) NOT NULL,
  `modified_by` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `favorite` tinyint(1) DEFAULT '0',
  `full_text` text,
  `intro_text` text,
  `order_of` int(11) DEFAULT '0',
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
-- Структура таблицы `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faculty_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Дамп данных таблицы `departments`
--

INSERT INTO `departments` (`id`, `faculty_id`, `name`) VALUES
(1, 1, 'Кафедра физиологии с курсом гражданской обороны'),
(2, 1, 'Кафедра охотоведения и ихтиологии'),
(3, 1, 'Кафедра химии'),
(4, 1, 'Кафедра садово-паркового хозяйства и генетики растений'),
(5, 1, 'Кафедра общей и прикладной экологии и зоологии'),
(6, 1, 'Кафедра иммунологии и биохимии'),
(7, 2, 'Кафедра международной экономики и экономической теории'),
(8, 2, 'Кафедра финансов и кредита'),
(9, 2, 'Кафедра экономической кибернетики'),
(10, 2, 'Кафедра учета и аудита'),
(11, 3, 'Кафедра английской филологии '),
(12, 3, 'Кафедра романской филологии и перевода'),
(13, 3, 'Кафедра преподавания второго иностранного языка'),
(14, 3, 'Кафедра немецкой филологии и перевода '),
(15, 3, 'Кафедра теории и практики перевода c английского языка'),
(16, 3, 'Кафедра иностранных языков профессионального направления'),
(17, 5, 'Кафедра джерелознавства, історіографії та спеціальних історичних дисциплін'),
(18, 5, 'Кафедра історії України'),
(19, 5, 'Кафедра новейшей истории Украины'),
(20, 5, 'Кафедра всесвітньої історії та міжнародних відноси'),
(21, 7, 'Кафедра конституционного и трудового права '),
(22, 7, 'Кафедра административного и хозяйственного права '),
(23, 7, 'Кафедра истории и теории государства и права '),
(24, 7, 'Кафедра уголовного права и правосудия'),
(25, 7, 'Кафедра гражданского права'),
(26, 9, 'Кафедра алгебры и геометрии '),
(27, 9, 'Кафедра математического анализа'),
(28, 9, 'Кафедра ИТ'),
(29, 9, 'Кафедра математического моделирования'),
(30, 10, 'Кафедра общего и славянского языкознания'),
(31, 10, 'Кафедра українознавства'),
(32, 10, 'Кафедра украинского языка'),
(33, 10, 'Кафедра украинской литературы'),
(34, 10, 'Кафедра російської філології'),
(35, 11, 'Кафедра физики металлов'),
(36, 11, 'Кафедра физики и методики ее преподавания'),
(37, 11, 'Кафедра прикладной физики'),
(38, 11, 'Кафедра физики полупроводников'),
(39, 12, 'Кафедра актерского мастерства'),
(40, 12, 'Фотогалерея'),
(41, 12, 'Кафедра педагогики и психологии образовательной дятельности'),
(42, 12, 'Кафедра практической психологии'),
(43, 12, 'Кафедра социальной педагогики');

-- --------------------------------------------------------

--
-- Структура таблицы `faculties`
--

CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `faculties`
--

INSERT INTO `faculties` (`id`, `name`) VALUES
(1, 'Биологический факультет'),
(2, 'Экономический факультет'),
(3, 'Факультет иностранной филологии'),
(4, 'Факультет физического воспитания'),
(5, 'Исторический факультет'),
(6, 'Факультет журналистики'),
(7, 'Юридический факультет'),
(8, 'Факультет менеджмента'),
(9, 'Математический факультет'),
(10, 'Филологический факультет'),
(11, 'Физический факультет'),
(12, 'Факультет социальной педагогики и психологии'),
(13, 'Факультет социологии и управления'),
(14, 'Крымский факультет');

-- --------------------------------------------------------

--
-- Структура таблицы `items_menu`
--

CREATE TABLE IF NOT EXISTS `items_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `order_of` int(11) NOT NULL,
  `for_index` tinyint(4) NOT NULL DEFAULT '0',
  `access_id` int(11) NOT NULL,
  `dash` int(11) NOT NULL,
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `items_menu`
--

INSERT INTO `items_menu` (`id`, `menu_id`, `parent_id`, `material_id`, `title`, `alias`, `path`, `link`, `status`, `order_of`, `for_index`, `access_id`, `dash`, `trash`) VALUES
(45, 1, 0, 0, 'Вход', 'profile', '/profile/login', '', 1, 1, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `favorite` tinyint(1) DEFAULT '0',
  `full_text` text,
  `intro_text` text,
  `author_id` int(11) DEFAULT NULL,
  `author_pseudo` varchar(255) DEFAULT NULL,
  `created` varchar(40) NOT NULL,
  `start_publication` varchar(40) NOT NULL,
  `end_publication` varchar(40) NOT NULL,
  `modified` varchar(40) NOT NULL,
  `modified_by` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `version` int(11) DEFAULT '0',
  `order` int(11) DEFAULT '0',
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `show_title` tinyint(1) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `access_id` int(11) NOT NULL,
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `title`, `show_title`, `position`, `status`, `access_id`, `trash`) VALUES
(1, 'Главное меню', 1, 0, 1, 1, 0);
-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(50) NOT NULL,
  `db_host` varchar(50) NOT NULL,
  `db_name` varchar(50) NOT NULL,
  `db_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `register_date` varchar(40) NOT NULL,
  `last_login` varchar(40) NOT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `access_id` int(11) NOT NULL,
  `block` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Структура таблицы `user_status`
--

CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `user_status`
--

INSERT INTO `user_status` (`id`, `name`) VALUES
(1, 'преподаватель'),
(2, 'студент'),
(3, 'администратор');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
