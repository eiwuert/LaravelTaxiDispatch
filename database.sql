-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2018 at 11:42 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobycabs`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `company_driver_share` (IN `driver_id` INT(11))  BEGIN

    DECLARE i INT DEFAULT 1;
    DECLARE totalrow INT;
    DECLARE shared_amount INT DEFAULT 0;
    DECLARE driver_share varchar(100);
    
	DECLARE individual_share CURSOR FOR SELECT sum(r.company_share) as driver_share from wy_ride r JOIN wy_ridedetails rd JOIN wy_cartype ct ON r.id=rd.ride_id and r.car_type=ct.id Where rd.driver_id=driver_id
;
    OPEN individual_share;
		SET totalrow=FOUND_ROWS(); 
        While i <= totalrow DO
			fetch individual_share INTO driver_share;
			    SET shared_amount=shared_amount+driver_share;
			SET i = i+1;
		end while;
	close individual_share;	
    insert into temp_cmpy_drivershare values(trim(driver_id), shared_amount);
	-- select * from employee_master;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `company_share` ()  BEGIN
	Drop table if exists temp_cmpy_drivershare;
    SET @sql = CONCAT('
CREATE TABLE IF NOT EXISTS `temp_cmpy_drivershare` (
  `driver_id` int(11) NOT NULL,
  `share_amount` float NOT NULL
) ;');
    PREPARE stmt FROM @sql;
	EXECUTE stmt;
    
     BEGIN
     	DECLARE var1 INT DEFAULT 1;
		DECLARE totalrow INT;
		DECLARE driver_id INT;
		DECLARE emp_name varchar(100);
			DECLARE driver_details CURSOR FOR SELECT d.id as driver_id FROM wy_driver d JOIN `wy_ridedetails` rd On d.id=rd.driver_id group by d.id order by rd.updated_at DESC
;
			OPEN driver_details;
				SET totalrow=FOUND_ROWS(); 
			   -- select totalrow;
				While var1 <= totalrow DO
					fetch driver_details INTO driver_id;
					-- SELECT driver_id;
					 -- calculation baSED ON DRIVEROF CAR_TYPE BASED
						call company_driver_share(driver_id);
						
					-- eND calculation baSED ON DRIVEROF CAR_TYPE BASED
					-- SELECT first_name from employee_master where id=emp_id;
					SET var1 = var1+1;
				end while;
			close driver_details;	
        
        
        END;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fare_type_mornight_status` (IN `is_time` TIME, IN `ie_time` TIME, IN `f_type` INT(11), IN `taxt_type` INT(11), IN `method` INT(2), IN `f_id` INT(2), OUT `cur_status` INT(11))  BEGIN
	Drop table if exists temp_fare_status;
    SET @sql = CONCAT('CREATE TABLE IF NOT EXISTS `temp_fare_status` (
    `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;');
    PREPARE stmt FROM @sql;
	EXECUTE stmt;
     BEGIN
     	DECLARE var1 INT DEFAULT 1;
		DECLARE totalrow INT;
		DECLARE st_time time;
		DECLARE e_time time;
		DECLARE tmp_start_time datetime;
        DECLARE tmp_end_time datetime;
       -- DECLARE t_type int(100);
       --  declare f_type INT;
        DECLARE input_start_time datetime;
        DECLARE input_end_time datetime;
        DECLARE far_id int;
		DECLARE emp_name varchar(100);
		 
      	DECLARE fare_details_status CURSOR FOR SELECT  ride_start_time AS st_time ,ride_end_time  AS e_time,fare_id as far_id from wy_faredetails where car_id=taxt_type and fare_type IN (2,3);
		OPEN fare_details_status;
				SET totalrow=FOUND_ROWS(); 
               
               -- select totalrow;
				While var1 <= totalrow DO
					fetch fare_details_status INTO st_time,e_time,far_id;
                      -- Check the end time today or tomorrow
                        SET tmp_start_time=concat(CURDATE(),' ',st_time);
                       IF st_time < e_time THEN
							 SET tmp_end_time= concat(CURDATE(),' ',e_time);
						 ELSE
						    SET tmp_end_time= concat(CURDATE()+ INTERVAL 1 DAY,' ',e_time);
					    END IF;
						insert into temp_fare_status values(far_id,tmp_start_time, tmp_end_time);        
					SET var1 = var1+1;
				end while;
			close fare_details_status;	
       			    IF is_time < ie_time THEN
							 SET input_end_time= concat(CURDATE(),' ',ie_time);
						 ELSE
						    SET input_end_time= concat(CURDATE()+ INTERVAL 1 DAY,' ',ie_time);
					    END IF;
						SET input_start_time=concat(CURDATE(),' ',is_time);
                        
		IF method = 1 THEN                
				SELECT count(*) INTO cur_status FROM temp_fare_status where ((end_time > input_start_time) AND (start_time < input_end_time )) ;
			else
				SELECT count(*) INTO cur_status FROM temp_fare_status where ((end_time > input_start_time) AND (start_time < input_end_time )) AND id !=f_id;
			END IF;
        
        END;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fare_type_peekspecial_status` (IN `is_time` TIME, IN `ie_time` TIME, IN `f_type` INT(5), IN `taxt_type` INT(5), IN `method` INT(5), IN `f_id` INT(5), OUT `cur_status` TINYINT(5))  BEGIN
	Drop table if exists temp_fare_status;
    SET @sql = CONCAT('CREATE TABLE IF NOT EXISTS `temp_fare_status` (
    `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;');
    PREPARE stmt FROM @sql;
	EXECUTE stmt;
     BEGIN
     	DECLARE var1 INT DEFAULT 1;
		DECLARE totalrow INT;
		DECLARE st_time time;
		DECLARE e_time time;
		DECLARE tmp_start_time datetime;
        DECLARE tmp_end_time datetime;
       -- DECLARE t_type int(100);
       --  declare f_type INT;
        DECLARE input_start_time datetime;
        DECLARE input_end_time datetime;
        DECLARE far_id int;
		DECLARE emp_name varchar(100);
		 
      	DECLARE fare_details_status CURSOR FOR SELECT  ride_start_time AS st_time ,ride_end_time  AS e_time,fare_id as far_id from wy_faredetails where car_id=taxt_type and fare_type =f_type ;
		OPEN fare_details_status;
				SET totalrow=FOUND_ROWS(); 
               
               -- select totalrow;
				While var1 <= totalrow DO
					fetch fare_details_status INTO st_time,e_time,far_id;
                      -- Check the end time today or tomorrow
                        SET tmp_start_time=concat(CURDATE(),' ',st_time);
                       IF st_time < e_time THEN
							 SET tmp_end_time= concat(CURDATE(),' ',e_time);
						 ELSE
						    SET tmp_end_time= concat(CURDATE()+ INTERVAL 1 DAY,' ',e_time);
					    END IF;
						insert into temp_fare_status values(far_id,tmp_start_time, tmp_end_time);        
					SET var1 = var1+1;
				end while;
			close fare_details_status;	
       			    IF is_time < ie_time THEN
							 SET input_end_time= concat(CURDATE(),' ',ie_time);
						 ELSE
						    SET input_end_time= concat(CURDATE()+ INTERVAL 1 DAY,' ',ie_time);
					    END IF;
						SET input_start_time=concat(CURDATE(),' ',is_time);
                        
		IF method = 1 THEN                
				SELECT count(*) INTO cur_status FROM temp_fare_status where ((end_time > input_start_time) AND (start_time < input_end_time )) ;
			else
				SELECT count(*) INTO cur_status FROM temp_fare_status where ((end_time > input_start_time) AND (start_time < input_end_time )) AND id !=f_id;
			END IF;
        
        END;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `individual_driver_share` (IN `driver_id` INT)  BEGIN

DECLARE i INT DEFAULT 1;
    DECLARE totalrow INT;
	DECLARE shared_amount INT DEFAULT 0;
    DECLARE driver_share varchar(100);
 DECLARE individual_share CURSOR FOR SELECT sum(r.driver_share) as driver_share from wy_ride r JOIN wy_ridedetails rd JOIN wy_cartype ct ON r.id=rd.ride_id and r.car_type=ct.id Where rd.driver_id=driver_id
;
    OPEN individual_share;
		SET totalrow=FOUND_ROWS(); 
        While i <= totalrow DO
			fetch individual_share INTO driver_share;
			    SET shared_amount=shared_amount+driver_share;
			SET i = i+1;
		end while;
	close individual_share;	
      insert into temp_individual_drivershare values(driver_id, shared_amount);
	-- select * from employee_master;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `individual_share` ()  BEGIN
	Drop table if exists temp_individual_drivershare;
    SET @sql = CONCAT('
CREATE TABLE IF NOT EXISTS `temp_individual_drivershare` (
  `driver_id` int(11) NOT NULL,
  `share_amount` float NOT NULL
) ;');
    PREPARE stmt FROM @sql;
	EXECUTE stmt;
    
     BEGIN
     	DECLARE var1 INT DEFAULT 1;
		DECLARE totalrow INT;
		DECLARE driver_id INT;
		DECLARE emp_name varchar(100);
			DECLARE driver_details CURSOR FOR SELECT d.id as driver_id FROM wy_driver d JOIN `wy_ridedetails` rd On d.id=rd.driver_id group by d.id order by rd.updated_at DESC
;
			OPEN driver_details;
				SET totalrow=FOUND_ROWS(); 
			   -- select totalrow;
				While var1 <= totalrow DO
					fetch driver_details INTO driver_id;
						call individual_driver_share(driver_id);
					SET var1 = var1+1;
				end while;
			close driver_details;	
        
        
        END;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`) VALUES
(1, 'Baksa', 1),
(2, 'Barpeta', 1),
(3, 'Biswanath', 1),
(4, 'Bongaigaon', 1),
(5, 'Cachar', 1),
(6, 'Charaideo', 1),
(7, 'Chirang', 1),
(8, 'Darrang', 1),
(9, 'Dhemaji', 1),
(10, 'Dhubri', 1),
(11, 'Dibrugarh', 1),
(12, 'Dima Hassao', 1),
(13, 'Goalpara', 1),
(14, 'Golaghat', 1),
(15, 'Hailakandi', 1),
(16, 'Hojai', 1),
(17, 'Jorhat', 1),
(18, 'Kamrup', 1),
(19, 'Kamrup Metro', 1),
(20, 'Karbi Anglong East', 1),
(21, 'Karbi Anglong West', 1),
(22, 'Karimganj', 1),
(23, 'Kokrajhar', 1),
(24, 'Lakhimpur', 1),
(25, 'Majuli', 1),
(26, 'Morigaon', 1),
(27, 'Nagaon', 1),
(28, 'Nalbari', 1),
(29, 'Sivasagar ', 1),
(30, 'South Salmara-Mankachar', 1),
(31, 'Udalguri', 1),
(32, 'Sonitpur', 1),
(33, 'Tinsukia', 1),
(34, 'Anantnag', 2),
(35, 'Bandipora', 2),
(36, 'Baramulla', 2),
(37, 'Budgam', 2),
(38, 'Doda', 2),
(39, 'Ganderbal', 2),
(40, 'Jammu', 2),
(41, 'Kargil', 2),
(42, 'Kathua', 2),
(43, 'Kishtwar', 2),
(44, 'Kulgam', 2),
(45, 'Kupwara', 2),
(46, 'Leh', 2),
(47, 'Poonch', 2),
(48, 'Pulwama', 2),
(49, 'Rajouri', 2),
(50, 'Ramban', 2),
(51, 'Reasi', 2),
(52, 'Samba ', 2),
(53, 'Shopian', 2),
(54, 'Srinagar', 2),
(55, 'Udhampur', 2),
(56, 'Ahmednagar', 3),
(57, 'Akola', 3),
(58, 'Amravati', 3),
(59, 'Aurangabad', 3),
(60, 'Beed', 3),
(61, 'Bhandara', 3),
(62, 'Buldhana', 3),
(63, 'Chandrapur', 3),
(64, 'Dhule', 3),
(65, 'Gadchiroli', 3),
(66, 'Gondia', 3),
(67, 'Hingoli', 3),
(68, 'Jalgaon', 3),
(69, 'Jalna', 3),
(70, 'Kolhapur', 3),
(71, 'Latur', 3),
(72, 'Mumbai City', 3),
(73, 'Mumbai Suburban', 3),
(74, 'Nagpur', 3),
(75, 'Nanded', 3),
(76, 'Nandurbar', 3),
(77, 'Nashik', 3),
(78, 'Osmanabad', 3),
(79, 'Parbhani', 3),
(80, 'Palghar', 3),
(81, 'Pune', 3),
(82, 'Raigad', 3),
(83, 'Ratnagiri', 3),
(84, 'Sangli', 3),
(85, 'Sindhudurg', 3),
(86, 'Satara', 3),
(87, 'Solapur', 3),
(88, 'Thane', 3),
(89, 'Wardha', 3),
(90, 'Washim', 3),
(91, 'Yavatmal', 3),
(92, 'Agra', 4),
(93, 'Aligarh', 4),
(94, 'Allahabad', 4),
(95, 'Ambedkar Nagar', 4),
(96, 'Amethi', 4),
(97, 'Amroha', 4),
(98, 'Auraiya', 4),
(99, 'Azamgarh', 4),
(100, 'Bagpat', 4),
(101, 'Bahraich', 4),
(102, 'Ballia', 4),
(103, 'Balrampur', 4),
(104, 'Banda', 4),
(105, 'Barabanki', 4),
(106, 'Bareilly', 4),
(107, 'Basti', 4),
(108, 'Bijnor', 4),
(109, 'Budaun', 4),
(110, 'Bulandshahr', 4),
(111, 'Chandauli', 4),
(112, 'Chitrakoot', 4),
(113, 'Deoria', 4),
(114, 'Etah', 4),
(115, 'Etawah', 4),
(116, 'Faizabad', 4),
(117, 'Farrukhabad', 4),
(118, 'Fatehpur', 4),
(119, 'Firozabad', 4),
(120, 'Gautam Buddha Nagar', 4),
(121, 'Ghaziabad', 4),
(122, 'Ghazipur', 4),
(123, 'Gonda', 4),
(124, 'Gorakhpur', 4),
(125, 'Hamirpur', 4),
(126, 'Hapur', 4),
(127, 'Hardoi', 4),
(128, 'Hastinapur', 4),
(129, 'Hathras', 4),
(130, 'Jalaun', 4),
(131, 'Jaunpur', 4),
(132, 'Jhansi', 4),
(133, 'Kannauj', 4),
(134, 'Kasganj', 4),
(135, 'Kanpur Dehat', 4),
(136, 'Kanpur Nagar', 4),
(137, 'Kaushambi', 4),
(138, 'Kushinagar', 4),
(139, 'Lakhimpur Kheri', 4),
(140, 'Lalitpur', 4),
(141, 'Lucknow', 4),
(142, 'Mahoba', 4),
(143, 'Maharajganj', 4),
(144, 'Mainpuri', 4),
(145, 'Mathura', 4),
(146, 'Mau', 4),
(147, 'Meerut', 4),
(148, 'Mirzapur', 4),
(149, 'Moradabad', 4),
(150, 'Muzaffarnagar', 4),
(151, 'Pratapgarh', 4),
(152, 'Raebareli', 4),
(153, 'Rampur', 4),
(154, 'Saharanpur', 4),
(155, 'Sambhal', 4),
(156, 'Sant Kabir Nagar', 4),
(157, 'Sant Ravidas Nagar', 4),
(158, 'Shahjahanpur', 4),
(159, 'Shamli', 4),
(160, 'Shravasti', 4),
(161, 'Siddharthnagar', 4),
(162, 'Sitapur', 4),
(163, 'Sonbhadra', 4),
(164, 'Sultanpur', 4),
(165, 'Unnao', 4),
(166, 'Varanasi', 4),
(167, 'Ahmedabad', 5),
(168, 'Amreli', 5),
(169, 'Anand', 5),
(170, 'Aravalli', 5),
(171, 'Banaskantha', 5),
(172, 'Bharuch', 5),
(173, 'Bhavnagar', 5),
(174, 'Botad', 5),
(175, 'Chhota Udaipur', 5),
(176, 'Dahod', 5),
(177, 'Dang', 5),
(178, 'Devbhumi-Dwarka', 5),
(179, 'Gir Somnath', 5),
(180, 'Gandhinagar', 5),
(181, 'Jamnagar', 5),
(182, 'Junagadh', 5),
(183, 'Kutch', 5),
(184, 'Kheda', 5),
(185, 'Mahisagar', 5),
(186, 'Mehsana', 5),
(187, 'Morbi', 5),
(188, 'Narmada', 5),
(189, 'Navsari', 5),
(190, 'Panchmahal', 5),
(191, 'Patan', 5),
(192, 'Porbandar', 5),
(193, 'Rajkot', 5),
(194, 'Sabarkantha', 5),
(195, 'Surat', 5),
(196, 'Surendranagar', 5),
(197, 'Tapi', 5),
(198, 'Vadodara', 5),
(199, 'Valsad', 5),
(200, 'Anantapur', 6),
(201, 'Chittoor', 6),
(202, 'East Godavari', 6),
(203, 'Guntur', 6),
(204, 'Kadapa', 6),
(205, 'Krishna', 6),
(206, 'Kurnool', 6),
(207, 'Prakasam', 6),
(208, 'Nellore', 6),
(209, 'Srikakulam', 6),
(210, 'Visakhapatnam', 6),
(211, 'Vizianagaram', 6),
(212, 'West Godavari', 6),
(213, 'Bagalkot', 7),
(214, 'Bengaluru Urban', 7),
(215, 'Bengaluru Rural', 7),
(216, 'Belagavi', 7),
(217, 'Ballari', 7),
(218, 'Bidar', 7),
(219, 'Chamarajanagara', 7),
(220, 'Chikaballapur', 7),
(221, 'Chikkamagaluru', 7),
(222, 'Chitradurga', 7),
(223, 'Dakshina Kannada', 7),
(224, 'Davanagere', 7),
(225, 'Dharwad', 7),
(226, 'Gadag', 7),
(227, 'Hassan', 7),
(228, 'Haveri', 7),
(229, 'Kalaburagi', 7),
(230, 'Kodagu', 7),
(231, 'Kolar', 7),
(232, 'Koppal', 7),
(233, 'Mandya', 7),
(234, 'Mysuru', 7),
(235, 'Vijayapura', 7),
(236, 'Raichur', 7),
(237, 'Ramanagara', 7),
(238, 'Shivamogga', 7),
(239, 'Tumakuru', 7),
(240, 'Udupi', 7),
(241, 'Uttara Kannada', 7),
(242, 'Yadgir', 7),
(243, 'Alappuzha', 8),
(244, 'Ernakulam', 8),
(245, 'Idukki', 8),
(246, 'Kannur', 8),
(247, 'Kasaragod', 8),
(248, 'Kochi', 8),
(249, 'Kollam', 8),
(250, 'Kottayam', 8),
(251, 'Kozhikode', 8),
(252, 'Malappuram', 8),
(253, 'Palakkad', 8),
(254, 'Pathanamthitta', 8),
(255, 'Thiruvananthapuram', 8),
(256, 'Thrissur', 8),
(257, 'Wayanad', 8),
(258, 'Alipurduar', 9),
(259, 'Bankura', 9),
(260, 'Bardhaman', 9),
(261, 'Birbhum', 9),
(262, 'Cooch Behar', 9),
(263, 'Darjeeling', 9),
(264, 'East Midnapore', 9),
(265, 'Hooghly', 9),
(266, 'Howrah', 9),
(267, 'Jalpaiguri', 9),
(268, 'Kolkata', 9),
(269, 'Malda', 9),
(270, 'Murshidabad', 9),
(271, 'Nadia', 9),
(272, 'North Dinajpur', 9),
(273, 'North 24 Parganas', 9),
(274, 'Purulia', 9),
(275, 'South 24 Parganas', 9),
(276, 'South Dinajpur', 9),
(277, 'West Midnapore', 9),
(278, 'Dhalai', 10),
(279, 'Sipahijala', 10),
(280, 'Khowai', 10),
(281, 'Gomati', 10),
(282, 'Unakoti', 10),
(283, 'North Tripura', 10),
(284, 'South Tripura', 10),
(285, 'West Tripura', 10),
(286, 'Balod', 11),
(287, 'Baloda Bazar', 11),
(288, 'Bastar', 11),
(289, 'Balrampur', 11),
(290, 'Bemetara', 11),
(291, 'Bilaspur', 11),
(292, 'Bijapur', 11),
(293, 'Dantewada', 11),
(294, 'Durg', 11),
(295, 'Dhamtari', 11),
(296, 'Gariaband', 11),
(297, 'Jashpur', 11),
(298, 'Janjgir-Champa', 11),
(299, 'Kabirdham', 11),
(300, 'Kanker', 11),
(301, 'Kondagaon', 11),
(302, 'Korba', 11),
(303, 'Koriya', 11),
(304, 'Mahasamund', 11),
(305, 'Mungeli', 11),
(306, 'Narayanpur', 11),
(307, 'Raigarh', 11),
(308, 'Raipur', 11),
(309, 'Rajnandgaon', 11),
(310, 'Surajpur', 11),
(311, 'Sukma', 11),
(312, 'Surguja', 11),
(313, 'Amritsar', 12),
(314, 'Barnala', 12),
(315, 'Bathinda', 12),
(316, 'Faridkot', 12),
(317, 'Fazilka', 12),
(318, 'Firozpur', 12),
(319, 'Fatehgarh Sahib', 12),
(320, 'Gurdaspur', 12),
(321, 'Hoshiarpur', 12),
(322, 'Jalandhar', 12),
(323, 'Kapurthala', 12),
(324, 'Mansa', 12),
(325, 'Moga', 12),
(326, 'Mohali', 12),
(327, 'Muktsar', 12),
(328, 'Pathankot', 12),
(329, 'Patiala', 12),
(330, 'Ludhiana', 12),
(331, 'Rupnagar', 12),
(332, 'Sangrur', 12),
(333, 'Shahid Bhagat Singh Nagar', 12),
(334, 'Tarn Taran', 12),
(335, 'Aizawl', 13),
(336, 'Champhai', 13),
(337, 'Kolasib', 13),
(338, 'Lawngtlai', 13),
(339, 'Lunglei', 13),
(340, 'Mamit', 13),
(341, 'Saiha', 13),
(342, 'Serchhip', 13),
(343, 'Ajmer', 14),
(344, 'Alwar', 14),
(345, 'Banswara', 14),
(346, 'Baran', 14),
(347, 'Barmer', 14),
(348, 'Bharatpur', 14),
(349, 'Bhilwara', 14),
(350, 'Bikaner', 14),
(351, 'Bundi', 14),
(352, 'Chittorgarh', 14),
(353, 'Churu', 14),
(354, 'Dausa', 14),
(355, 'Dholpur', 14),
(356, 'Dungarpur', 14),
(357, 'Hanumangarh', 14),
(358, 'Jaipur', 14),
(359, 'Jaisalmer', 14),
(360, 'Jalore', 14),
(361, 'Jhalawar', 14),
(362, 'Jhunjhunu', 14),
(363, 'Jodhpur', 14),
(364, 'Karauli', 14),
(365, 'Kota', 14),
(366, 'Nagaur', 14),
(367, 'Pali', 14),
(368, 'Pratapgarh', 14),
(369, 'Rajsamand', 14),
(370, 'Sri Ganganagar', 14),
(371, 'Sawai Madhopur', 14),
(372, 'Sikar', 14),
(373, 'Sirohi', 14),
(374, 'Tonk', 14),
(375, 'Udaipur', 14),
(376, 'North Goa', 15),
(378, 'South Goa', 15),
(379, 'Almora', 16),
(380, 'Bageshwar', 16),
(381, 'Chamoli', 16),
(382, 'Champawat', 16),
(383, 'Dehradun', 16),
(384, 'Haridwar', 16),
(385, 'Nainital', 16),
(386, 'Pauri Garhwal', 16),
(387, 'Pithoragarh', 16),
(388, 'Rudraprayag', 16),
(389, 'Tehri Garhwal', 16),
(390, 'Udham Singh Nagar', 16),
(391, 'Uttarkashi', 16),
(392, 'Anjaw', 17),
(393, 'Changlang', 17),
(394, 'Dibang Valley', 17),
(395, 'East Kameng', 17),
(396, 'East Siang', 17),
(397, 'Kra Daadi', 17),
(398, 'Kurung Kumey', 17),
(399, 'Lohit', 17),
(400, 'Longding', 17),
(401, 'Lower Dibang Valley', 17),
(402, 'Lower Subansiri', 17),
(403, 'Namsai', 17),
(404, 'Papum Pare', 17),
(405, 'Tawang', 17),
(406, 'Tirap', 17),
(407, 'Upper Siang', 17),
(408, 'Upper Subansiri', 17),
(409, 'West Kameng', 17),
(410, 'West Siang', 17),
(411, 'Siang', 17),
(412, 'Araria', 18),
(413, 'Arwal', 18),
(414, 'Aurangabad', 18),
(415, 'Banka', 18),
(416, 'Begusarai', 18),
(417, 'Bhabhua (Kaimur)', 18),
(418, 'Bhagalpur', 18),
(419, 'Bhojpur', 18),
(420, 'Buxar', 18),
(421, 'Darbhanga', 18),
(422, 'East Champaran', 18),
(423, 'Gaya', 18),
(424, 'Gopalganj', 18),
(425, 'Jamui', 18),
(426, 'Jehanabad', 18),
(427, 'Katihar', 18),
(428, 'Khagaria', 18),
(429, 'Kishanganj', 18),
(430, 'Lakhisarai', 18),
(431, 'Madhepura', 18),
(432, 'Madhubani', 18),
(433, 'Munger', 18),
(434, 'Muzaffarpur', 18),
(435, 'Nalanda', 18),
(436, 'Nawada', 18),
(437, 'Patna', 18),
(438, 'Purnea', 18),
(439, 'Rohtas', 18),
(440, 'Saharsa', 18),
(441, 'Samastipur', 18),
(442, 'Saran', 18),
(443, 'Sheikhpura', 18),
(444, 'Sheohar', 18),
(445, 'Sitamarhi', 18),
(446, 'Siwan', 18),
(447, 'Supaul', 18),
(448, 'Vaishali', 18),
(449, 'West Champaran', 18),
(450, 'Lakshadweep', 19),
(451, 'Kavaratti*', 19),
(452, 'Bokaro', 20),
(453, 'Chatra', 20),
(454, 'Deoghar', 20),
(455, 'Dhanbad', 20),
(456, 'Dumka', 20),
(457, 'East Singhbhum', 20),
(458, 'Garhwa', 20),
(459, 'Giridih', 20),
(460, 'Godda', 20),
(461, 'Gumla', 20),
(462, 'Hazaribagh', 20),
(463, 'Jamtara', 20),
(464, 'Khunti', 20),
(465, 'Koderma', 20),
(466, 'Latehar', 20),
(467, 'Lohardaga', 20),
(468, 'Pakur', 20),
(469, 'Palamu', 20),
(470, 'Ramgarh', 20),
(471, 'Ranchi', 20),
(472, 'Sahibganj', 20),
(473, 'Saraikela', 20),
(474, 'Simdega', 20),
(475, 'West Singhbhum', 20),
(476, 'Dadra and Nagar Haveli', 21),
(477, 'Silvassa', 21),
(478, 'Anugul', 22),
(479, 'Balangir', 22),
(480, 'Boudh', 22),
(481, 'Bhadrak', 22),
(482, 'Bargarh(Baragarh)', 22),
(483, 'Balasore (Baleswar)', 22),
(484, 'Cuttack', 22),
(485, 'Debagarh (Deogarh)', 22),
(486, 'Dhenkanal', 22),
(487, 'Ganjam', 22),
(488, 'Gajapati', 22),
(489, 'Jajapur(Jaipur)', 22),
(490, 'Jagatsinghapur', 22),
(491, 'Jharsuguda', 22),
(492, 'Kalahandi', 22),
(493, 'Kendujhar (Keonjhar)', 22),
(494, 'Kandhamal', 22),
(495, 'Khordha (Bhubaneswar)', 22),
(496, 'Koraput', 22),
(497, 'Kendrapara', 22),
(498, 'Malkangiri', 22),
(499, 'Mayurbhanj', 22),
(500, 'Nabarangapur', 22),
(501, 'Nayagarh', 22),
(502, 'Nuapada', 22),
(503, 'Puri', 22),
(504, 'Rayagada', 22),
(505, 'Sambalpur', 22),
(506, 'Subarnapur (Sonepur)', 22),
(507, 'Sundergarh(Sundargarh)', 22),
(508, 'Ariyalur', 23),
(509, 'Chennai', 23),
(510, 'Coimbatore', 23),
(511, 'Cuddalore', 23),
(512, 'Dharmapuri', 23),
(513, 'Dindigul', 23),
(514, 'Erode', 23),
(515, 'Kanchipuram', 23),
(516, 'Kanyakumari', 23),
(517, 'Karur', 23),
(518, 'Krishnagiri', 23),
(519, 'Madurai', 23),
(520, 'Nagapattinam', 23),
(521, 'Namakkal', 23),
(522, 'Nilgiris', 23),
(523, 'Perambalur', 23),
(524, 'Pudukkottai', 23),
(525, 'Ramanathapuram', 23),
(526, 'Salem', 23),
(527, 'Sivaganga', 23),
(528, 'Thanjavur', 23),
(529, 'Theni', 23),
(530, 'Tiruchirappalli', 23),
(531, 'Tirunelveli', 23),
(532, 'Tiruvallur', 23),
(533, 'Tiruvannamalai', 23),
(534, 'Tiruvarur', 23),
(535, 'Thoothukudi', 23),
(536, 'Vellore', 23),
(537, 'Villupuram', 23),
(538, 'Virudhunagar', 23),
(539, 'Bilaspur', 24),
(540, 'Chamba', 24),
(541, 'Hamirpur', 24),
(542, 'Kangra', 24),
(543, 'Kinnaur', 24),
(544, 'Kullu', 24),
(545, 'Lahaul and Spiti', 24),
(546, 'Mandi', 24),
(547, 'Shimla', 24),
(548, 'Sirmaur', 24),
(549, 'Solan', 24),
(550, 'Una', 24),
(551, 'Ambala', 25),
(552, 'Bhiwani', 25),
(553, 'Charkhi Dadri ', 25),
(554, 'Faridabad', 25),
(555, 'Fatehabad', 25),
(556, 'Gurgaon', 25),
(557, 'Hisar', 25),
(558, 'Jhajjar', 25),
(559, 'Jind', 25),
(560, 'Kaithal', 25),
(561, 'Karnal', 25),
(562, 'Kurukshetra', 25),
(563, 'Mahendragarh', 25),
(564, 'Mewat', 25),
(565, 'Palwal', 25),
(566, 'Panchkula', 25),
(567, 'Panipat', 25),
(568, 'Rewari', 25),
(569, 'Rohtak', 25),
(570, 'Sirsa', 25),
(571, 'Sonipat', 25),
(572, 'Yamunanagar', 25),
(573, 'Alirajpur', 26),
(574, 'Anuppur', 26),
(575, 'Ashok Nagar', 26),
(576, 'Agar Malwa', 26),
(577, 'Balaghat', 26),
(578, 'Barwan', 26),
(579, 'Betul', 26),
(580, 'Bhind', 26),
(581, 'Bhopal', 26),
(582, 'Burhanpur', 26),
(583, 'Chhatarpur', 26),
(584, 'Chhindwara', 26),
(585, 'Damoh', 26),
(586, 'Datia', 26),
(587, 'Dewas', 26),
(588, 'Dhar', 26),
(589, 'Dindori', 26),
(590, 'Guna', 26),
(591, 'Gwalior', 26),
(592, 'Harda', 26),
(593, 'Hoshangabad', 26),
(594, 'Indore', 26),
(595, 'Jabalpur', 26),
(596, 'Jhabua', 26),
(597, 'Katni', 26),
(598, 'Mandla', 26),
(599, 'Mandsaur', 26),
(600, 'Morena', 26),
(601, 'Multai', 26),
(602, 'Murwara', 26),
(603, 'Narsinghpur', 26),
(604, 'Neemuch', 26),
(605, 'Panna', 26),
(606, 'East Nimar', 26),
(607, 'West Nimar', 26),
(608, 'Raisen', 26),
(609, 'Rajgarh', 26),
(610, 'Ratlam', 26),
(611, 'Rewa', 26),
(612, 'Sagar', 26),
(613, 'Satna', 26),
(614, 'Sehore', 26),
(615, 'Seoni', 26),
(616, 'Shahdol', 26),
(617, 'Shajapur', 26),
(618, 'Sheopur', 26),
(619, 'Shivpuri', 26),
(620, 'Sidhi', 26),
(621, 'Singrauli', 26),
(622, 'Tikamgarh', 26),
(623, 'Ujjain', 26),
(624, 'Umaria', 26),
(625, 'Vidisha', 26),
(626, 'Central Delhi', 27),
(627, 'East Delhi', 27),
(628, 'New Delhi', 27),
(629, 'Shahdara', 27),
(630, 'South East Delhi', 27),
(631, 'North Delhi', 27),
(632, 'North East Delhi', 27),
(633, 'North West Delhi', 27),
(634, 'South Delhi', 27),
(635, 'South West Delhi', 27),
(636, 'West Delhi', 27),
(637, 'Chandigarh', 28),
(638, 'Daman', 29),
(639, 'Diu', 29),
(640, 'Dimapur', 30),
(641, 'Kiphire', 30),
(642, 'Kohima', 30),
(643, 'Longleng', 30),
(644, 'Mokokchung', 30),
(645, 'Mon', 30),
(646, 'Peren', 30),
(647, 'Phek', 30),
(648, 'Tuensang', 30),
(649, 'Wokha', 30),
(650, 'Zunheboto', 30),
(651, 'East Sikkim', 31),
(652, 'North Sikkim', 31),
(653, 'South Sikkim', 31),
(654, 'West Sikkim', 31),
(655, 'Bishnupur', 32),
(656, 'Chandel', 32),
(657, 'Churachandpur', 32),
(658, 'Imphal East', 32),
(659, 'Lamphelpat', 32),
(660, 'Senapati', 32),
(661, 'Tamenglong', 32),
(662, 'Thoubal', 32),
(663, 'Ukhrul', 32),
(664, 'West Jaintia Hills (Jowai)', 33),
(665, 'East Jaintia Hills (Khliehriat', 33),
(666, 'East Khasi Hills (Shillong)', 33),
(667, 'West Khasi Hills (Nongstoin)', 33),
(668, 'South West Khasi Hills (Mawkyr', 33),
(669, 'Ri-Bhoi (Nongpoh)', 33),
(670, 'North Garo Hills (Resubelpara)', 33),
(671, 'East Garo Hills (Williamnagar)', 33),
(672, 'South Garo Hills (Baghmara)', 33),
(673, 'West Garo Hills (Tura)', 33),
(674, 'South West Garo Hills (Ampati)', 33),
(675, 'Karaikal', 34),
(676, 'Mahe', 34),
(677, 'Pondicherry*', 34),
(678, 'Yanam', 34),
(679, 'North and Middle Andaman distr', 36),
(680, 'South Andaman district', 36),
(681, 'Nicobar district', 36),
(682, 'Adilabad', 37),
(683, 'Komaram Bheem ', 37),
(684, 'Asifabad', 37),
(685, 'Bhadradi Kothagudem', 37),
(686, 'Jayashankar Bhupalpally', 37),
(687, 'Jogulamba Gadwal', 37),
(688, 'Hyderabad', 37),
(689, 'Jagtial', 37),
(690, 'Jangaon', 37),
(691, 'Kamareddy', 37),
(692, 'Karimnagar', 37),
(693, 'Khammam', 37),
(694, 'Mahabubabad', 37),
(695, 'Mancherial', 37),
(696, 'Mahbubnagar', 37),
(697, 'Medak', 37),
(698, 'Medchal', 37),
(699, 'Nalgonda', 37),
(700, 'Nagarkurnool', 37),
(701, 'Nirmal', 37),
(702, 'Nizamabad', 37),
(703, 'Ranga Reddy', 37),
(704, 'Peddapalli', 37),
(705, 'Rajanna Sircilla', 37),
(706, 'Sangareddy', 37),
(707, 'Siddipet', 37),
(708, 'Suryapet', 37),
(709, 'Wanaparthy', 37),
(710, 'Warangal (urban)', 37),
(711, 'Warangal (rural)', 37),
(712, 'Yadadri Bhuvanagiri', 37);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `sortname`, `name`) VALUES
(101, 'IN', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(12) NOT NULL,
  `state_id` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `name`) VALUES
(1, '1', 'North Andaman'),
(2, '1', 'South Andaman'),
(3, '1', 'Nicobar'),
(4, '2', 'Adilabad'),
(5, '2', 'Anantapur'),
(6, '2', 'Chittoor'),
(7, '2', 'East Godavari'),
(8, '2', 'Guntur'),
(9, '2', 'Hyderabad'),
(10, '2', 'Karimnagar'),
(11, '2', 'Khammam'),
(12, '2', 'Krishna'),
(13, '2', 'Kurnool'),
(14, '2', 'Mahbubnagar'),
(15, '2', 'Medak'),
(16, '2', 'Nalgonda'),
(17, '2', 'Nizamabad'),
(18, '2', 'Prakasam'),
(19, '2', 'Ranga Reddy'),
(20, '2', 'Srikakulam'),
(21, '2', 'Sri Potti Sri Ramulu Nellore'),
(22, '2', 'Vishakhapatnam'),
(23, '2', 'Vizianagaram'),
(24, '2', 'Warangal'),
(25, '2', 'West Godavari'),
(26, '2', 'Cudappah'),
(27, '3', 'Anjaw'),
(28, '3', 'Changlang'),
(29, '3', 'East Siang'),
(30, '3', 'East Kameng'),
(31, '3', 'Kurung Kumey'),
(32, '3', 'Lohit'),
(33, '3', 'Lower Dibang Valley'),
(34, '3', 'Lower Subansiri'),
(35, '3', 'Papum Pare'),
(36, '3', 'Tawang'),
(37, '3', 'Tirap'),
(38, '3', 'Dibang Valley'),
(39, '3', 'Upper Siang'),
(40, '3', 'Upper Subansiri'),
(41, '3', 'West Kameng'),
(42, '3', 'West Siang'),
(43, '4', 'Baksa'),
(44, '4', 'Barpeta'),
(45, '4', 'Bongaigaon'),
(46, '4', 'Cachar'),
(47, '4', 'Chirang'),
(48, '4', 'Darrang'),
(49, '4', 'Dhemaji'),
(50, '4', 'Dima Hasao'),
(51, '4', 'Dhubri'),
(52, '4', 'Dibrugarh'),
(53, '4', 'Goalpara'),
(54, '4', 'Golaghat'),
(55, '4', 'Hailakandi'),
(56, '4', 'Jorhat'),
(57, '4', 'Kamrup'),
(58, '4', 'Kamrup Metropolitan'),
(59, '4', 'Karbi Anglong'),
(60, '4', 'Karimganj'),
(61, '4', 'Kokrajhar'),
(62, '4', 'Lakhimpur'),
(63, '4', 'Morigaon'),
(64, '4', 'Nagaon'),
(65, '4', 'Nalbari'),
(66, '4', 'Sivasagar'),
(67, '4', 'Sonitpur'),
(68, '4', 'Tinsukia'),
(69, '4', 'Udalguri'),
(70, '5', 'Araria'),
(71, '5', 'Arwal'),
(72, '5', 'Aurangabad'),
(73, '5', 'Banka'),
(74, '5', 'Begusarai'),
(75, '5', 'Bhagalpur'),
(76, '5', 'Bhojpur'),
(77, '5', 'Buxar'),
(78, '5', 'Darbhanga'),
(79, '5', 'East Champaran'),
(80, '5', 'Gaya'),
(81, '5', 'Gopalganj'),
(82, '5', 'Jamui'),
(83, '5', 'Jehanabad'),
(84, '5', 'Kaimur'),
(85, '5', 'Katihar'),
(86, '5', 'Khagaria'),
(87, '5', 'Kishanganj'),
(88, '5', 'Lakhisarai'),
(89, '5', 'Madhepura'),
(90, '5', 'Madhubani'),
(91, '5', 'Munger'),
(92, '5', 'Muzaffarpur'),
(93, '5', 'Nalanda'),
(94, '5', 'Nawada'),
(95, '5', 'Patna'),
(96, '5', 'Purnia'),
(97, '5', 'Rohtas'),
(98, '5', 'Saharsa'),
(99, '5', 'Samastipur'),
(100, '5', 'Saran'),
(101, '5', 'Sheikhpura'),
(102, '5', 'Sheohar'),
(103, '5', 'Sitamarhi'),
(104, '5', 'Siwan'),
(105, '5', 'Supaul'),
(106, '6', 'Chandigarh'),
(107, '7', 'Bastar'),
(108, '7', 'Bijapur'),
(109, '7', 'Bilaspur'),
(110, '7', 'Dantewada'),
(111, '7', 'Dhamtari'),
(112, '7', 'Durg'),
(113, '7', 'Jashpur'),
(114, '7', 'Janjgir-Champa'),
(115, '7', 'Korba'),
(116, '7', 'Koriya'),
(117, '7', 'Kanker'),
(118, '7', 'Kabirdham (formerly Kawardha)'),
(119, '7', 'Mahasamund'),
(120, '7', 'Narayanpur'),
(121, '7', 'Raigarh'),
(122, '7', 'Rajnandgaon'),
(123, '7', 'Raipur'),
(124, '7', 'Surguja'),
(125, '8', 'Dadra and Nagar Haveli'),
(126, '9', 'Daman'),
(127, '9', 'Diu'),
(128, '10', 'Central Delhi'),
(129, '10', 'East Delhi'),
(130, '10', 'New Delhi'),
(131, '10', 'North Delhi'),
(132, '10', 'North East Delhi'),
(133, '10', 'North West Delhi'),
(134, '10', 'South Delhi'),
(135, '10', 'South West Delhi'),
(136, '10', 'West Delhi'),
(137, '11', 'North Goa'),
(138, '11', 'South Goa'),
(139, '12', 'Ahmedabad'),
(140, '12', 'Amreli district'),
(141, '12', 'Anand'),
(142, '12', 'Banaskantha'),
(143, '12', 'Bharuch'),
(144, '12', 'Bhavnagar'),
(145, '12', 'Dahod'),
(146, '12', 'The Dangs'),
(147, '12', 'Gandhinagar'),
(148, '12', 'Jamnagar'),
(149, '12', 'Junagadh'),
(150, '12', 'Kutch'),
(151, '12', 'Kheda'),
(152, '12', 'Mehsana'),
(153, '12', 'Narmada'),
(154, '12', 'Navsari'),
(155, '12', 'Patan'),
(156, '12', 'Panchmahal'),
(157, '12', 'Porbandar'),
(158, '12', 'Rajkot'),
(159, '12', 'Sabarkantha'),
(160, '12', 'Surendranagar'),
(161, '12', 'Surat'),
(162, '12', 'Tapi'),
(163, '12', 'Vadodara'),
(164, '12', 'Valsad'),
(165, '13', 'Ambala'),
(166, '13', 'Bhiwani'),
(167, '13', 'Faridabad'),
(168, '13', 'Fatehabad'),
(169, '13', 'Gurgaon'),
(170, '13', 'Hissar'),
(171, '13', 'Jhajjar'),
(172, '13', 'Jind'),
(173, '13', 'Karnal'),
(174, '13', 'Kaithal'),
(175, '13', 'Kurukshetra'),
(176, '13', 'Mahendragarh'),
(177, '13', 'Mewat'),
(178, '13', 'Palwal'),
(179, '13', 'Panchkula'),
(180, '13', 'Panipat'),
(181, '13', 'Rewari'),
(182, '13', 'Rohtak'),
(183, '13', 'Sirsa'),
(184, '13', 'Sonipat'),
(185, '13', 'Yamuna Nagar'),
(186, '14', 'Bilaspur'),
(187, '14', 'Chamba'),
(188, '14', 'Hamirpur'),
(189, '14', 'Kangra'),
(190, '14', 'Kinnaur'),
(191, '14', 'Kullu'),
(192, '14', 'Lahaul and Spiti'),
(193, '14', 'Mandi'),
(194, '14', 'Shimla'),
(195, '14', 'Sirmaur'),
(196, '14', 'Solan'),
(197, '14', 'Una'),
(198, '15', 'Anantnag'),
(199, '15', 'Badgam'),
(200, '15', 'Bandipora'),
(201, '15', 'Baramulla'),
(202, '15', 'Doda'),
(203, '15', 'Ganderbal'),
(204, '15', 'Jammu'),
(205, '15', 'Kargil'),
(206, '15', 'Kathua'),
(207, '15', 'Kishtwar'),
(208, '15', 'Kupwara'),
(209, '15', 'Kulgam'),
(210, '15', 'Leh'),
(211, '15', 'Poonch'),
(212, '15', 'Pulwama'),
(213, '15', 'Rajouri'),
(214, '15', 'Ramban'),
(215, '15', 'Reasi'),
(216, '15', 'Samba'),
(217, '15', 'Shopian'),
(218, '15', 'Srinagar'),
(219, '15', 'Udhampur'),
(220, '16', 'Bokaro'),
(221, '16', 'Chatra'),
(222, '16', 'Deoghar'),
(223, '16', 'Dhanbad'),
(224, '16', 'Dumka'),
(225, '16', 'East Singhbhum'),
(226, '16', 'Garhwa'),
(227, '16', 'Giridih'),
(228, '16', 'Godda'),
(229, '16', 'Gumla'),
(230, '16', 'Hazaribag'),
(231, '16', 'Jamtara'),
(232, '16', 'Khunti'),
(233, '16', 'Koderma'),
(234, '16', 'Latehar'),
(235, '16', 'Lohardaga'),
(236, '16', 'Pakur'),
(237, '16', 'Palamu'),
(238, '16', 'Ramgarh'),
(239, '16', 'Ranchi'),
(240, '16', 'Sahibganj'),
(241, '16', 'Seraikela Kharsawan'),
(242, '16', 'Simdega'),
(243, '16', 'West Singhbhum'),
(244, '17', 'Bagalkot'),
(245, '17', 'Bangalore Rural'),
(246, '17', 'Bangalore Urban'),
(247, '17', 'Belgaum'),
(248, '17', 'Bellary'),
(249, '17', 'Bidar'),
(250, '17', 'Bijapur'),
(251, '17', 'Chamarajnagar'),
(252, '17', 'Chikkamagaluru'),
(253, '17', 'Chikkaballapur'),
(254, '17', 'Chitradurga'),
(255, '17', 'Davanagere'),
(256, '17', 'Dharwad'),
(257, '17', 'Dakshina Kannada'),
(258, '17', 'Gadag'),
(259, '17', 'Gulbarga'),
(260, '17', 'Hassan'),
(261, '17', 'Haveri district'),
(262, '17', 'Kodagu'),
(263, '17', 'Kolar'),
(264, '17', 'Koppal'),
(265, '17', 'Mandya'),
(266, '17', 'Mysore'),
(267, '17', 'Raichur'),
(268, '17', 'Shimoga'),
(269, '17', 'Tumkur'),
(270, '17', 'Udupi'),
(271, '17', 'Uttara Kannada'),
(272, '17', 'Ramanagara'),
(273, '17', 'Yadgir'),
(274, '18', 'Alappuzha'),
(275, '18', 'Ernakulam'),
(276, '18', 'Idukki'),
(277, '18', 'Kannur'),
(278, '18', 'Kasaragod'),
(279, '18', 'Kollam'),
(280, '18', 'Kottayam'),
(281, '18', 'Kozhikode'),
(282, '18', 'Malappuram'),
(283, '18', 'Palakkad'),
(284, '18', 'Pathanamthitta'),
(285, '18', 'Thrissur'),
(286, '18', 'Thiruvananthapuram'),
(287, '18', 'Wayanad'),
(288, '19', 'Lakshadweep'),
(289, '20', 'Agar'),
(290, '20', 'Alirajpur'),
(291, '20', 'Anuppur'),
(292, '20', 'Ashok Nagar'),
(293, '20', 'Balaghat'),
(294, '20', 'Barwani'),
(295, '20', 'Betul'),
(296, '20', 'Bhind'),
(297, '20', 'Bhopal'),
(298, '20', 'Burhanpur'),
(299, '20', 'Chhatarpur'),
(300, '20', 'Chhindwara'),
(301, '20', 'Damoh'),
(302, '20', 'Datia'),
(303, '20', 'Dewas'),
(304, '20', 'Dhar'),
(305, '20', 'Dindori'),
(306, '20', 'Guna'),
(307, '20', 'Gwalior'),
(308, '20', 'Harda'),
(309, '20', 'Hoshangabad'),
(310, '20', 'Indore'),
(311, '20', 'Jabalpur'),
(312, '20', 'Jhabua'),
(313, '20', 'Katni'),
(314, '20', 'Khandwa (East Nimar)'),
(315, '20', 'Khargone (West Nimar)'),
(316, '20', 'Mandla'),
(317, '20', 'Mandsaur'),
(318, '20', 'Morena'),
(319, '20', 'Narsinghpur'),
(320, '20', 'Neemuch'),
(321, '20', 'Panna'),
(322, '20', 'Raisen'),
(323, '20', 'Rajgarh'),
(324, '20', 'Ratlam'),
(325, '20', 'Rewa'),
(326, '20', 'Sagar'),
(327, '20', 'Satna'),
(328, '20', 'Sehore'),
(329, '20', 'Seoni'),
(330, '20', 'Shahdol'),
(331, '20', 'Shajapur'),
(332, '20', 'Sheopur'),
(333, '20', 'Shivpuri'),
(334, '20', 'Sidhi'),
(335, '20', 'Singrauli'),
(336, '20', 'Tikamgarh'),
(337, '20', 'Ujjain'),
(338, '20', 'Umaria'),
(339, '20', 'Vidisha'),
(340, '21', 'Ahmednagar'),
(341, '21', 'Akola'),
(342, '21', 'Amravati'),
(343, '21', 'Aurangabad'),
(344, '21', 'Beed'),
(345, '21', 'Bhandara'),
(346, '21', 'Buldhana'),
(347, '21', 'Chandrapur'),
(348, '21', 'Dhule'),
(349, '21', 'Gadchiroli'),
(350, '21', 'Gondia'),
(351, '21', 'Hingoli'),
(352, '21', 'Jalgaon'),
(353, '21', 'Jalna'),
(354, '21', 'Kolhapur'),
(355, '21', 'Latur'),
(356, '21', 'Mumbai City'),
(357, '21', 'Mumbai suburban'),
(358, '21', 'Nanded'),
(359, '21', 'Nandurbar'),
(360, '21', 'Nagpur'),
(361, '21', 'Nashik'),
(362, '21', 'Osmanabad'),
(363, '21', 'Parbhani'),
(364, '21', 'Pune'),
(365, '21', 'Raigad'),
(366, '21', 'Ratnagiri'),
(367, '21', 'Sangli'),
(368, '21', 'Satara'),
(369, '21', 'Sindhudurg'),
(370, '21', 'Solapur'),
(371, '21', 'Thane'),
(372, '21', 'Wardha'),
(373, '21', 'Washim'),
(374, '21', 'Yavatmal'),
(375, '22', 'Bishnupur'),
(376, '22', 'Churachandpur'),
(377, '22', 'Chandel'),
(378, '22', 'Imphal East'),
(379, '22', 'Senapati'),
(380, '22', 'Tamenglong'),
(381, '22', 'Thoubal'),
(382, '22', 'Ukhrul'),
(383, '22', 'Imphal West'),
(384, '23', 'East Garo Hills'),
(385, '23', 'East Khasi Hills'),
(386, '23', 'Jaintia Hills'),
(387, '23', 'Ri Bhoi'),
(388, '23', 'South Garo Hills'),
(389, '23', 'West Garo Hills'),
(390, '23', 'West Khasi Hills'),
(391, '24', 'Aizawl'),
(392, '24', 'Champhai'),
(393, '24', 'Kolasib'),
(394, '24', 'Lawngtlai'),
(395, '24', 'Lunglei'),
(396, '24', 'Mamit'),
(397, '24', 'Saiha'),
(398, '24', 'Serchhip'),
(399, '25', 'Dimapur'),
(400, '25', 'Kiphire'),
(401, '25', 'Kohima'),
(402, '25', 'Longleng'),
(403, '25', 'Mokokchung'),
(404, '25', 'Mon'),
(405, '25', 'Peren'),
(406, '25', 'Phek'),
(407, '25', 'Tuensang'),
(408, '25', 'Wokha'),
(409, '25', 'Zunheboto'),
(410, '26', 'Angul'),
(411, '26', 'Boudh (Bauda)'),
(412, '26', 'Bhadrak'),
(413, '26', 'Balangir'),
(414, '26', 'Bargarh (Baragarh)'),
(415, '26', 'Balasore'),
(416, '26', 'Cuttack'),
(417, '26', 'Debagarh (Deogarh)'),
(418, '26', 'Dhenkanal'),
(419, '26', 'Ganjam'),
(420, '26', 'Gajapati'),
(421, '26', 'Jharsuguda'),
(422, '26', 'Jajpur'),
(423, '26', 'Jagatsinghpur'),
(424, '26', 'Khordha'),
(425, '26', 'Kendujhar (Keonjhar)'),
(426, '26', 'Kalahandi'),
(427, '26', 'Kandhamal'),
(428, '26', 'Koraput'),
(429, '26', 'Kendrapara'),
(430, '26', 'Malkangiri'),
(431, '26', 'Mayurbhanj'),
(432, '26', 'Nabarangpur'),
(433, '26', 'Nuapada'),
(434, '26', 'Nayagarh'),
(435, '26', 'Puri'),
(436, '26', 'Rayagada'),
(437, '26', 'Sambalpur'),
(438, '26', 'Subarnapur (Sonepur)'),
(439, '26', 'Sundergarh'),
(440, '27', 'Karaikal'),
(441, '27', 'Mahe'),
(442, '27', 'Pondicherry'),
(443, '27', 'Yanam'),
(444, '28', 'Amritsar'),
(445, '28', 'Barnala'),
(446, '28', 'Bathinda'),
(447, '28', 'Firozpur'),
(448, '28', 'Faridkot'),
(449, '28', 'Fatehgarh Sahib'),
(450, '28', 'Fazilka[6]'),
(451, '28', 'Gurdaspur'),
(452, '28', 'Hoshiarpur'),
(453, '28', 'Jalandhar'),
(454, '28', 'Kapurthala'),
(455, '28', 'Ludhiana'),
(456, '28', 'Mansa'),
(457, '28', 'Moga'),
(458, '28', 'Sri Muktsar Sahib'),
(459, '28', 'Pathankot'),
(460, '28', 'Patiala'),
(461, '28', 'Rupnagar'),
(462, '28', 'Ajitgarh (Mohali)'),
(463, '28', 'Sangrur'),
(464, '28', 'Shahid Bhagat Singh Nagar'),
(465, '28', 'Tarn Taran'),
(466, '29', 'Ajmer'),
(467, '29', 'Alwar'),
(468, '29', 'Bikaner'),
(469, '29', 'Barmer'),
(470, '29', 'Banswara'),
(471, '29', 'Bharatpur'),
(472, '29', 'Baran'),
(473, '29', 'Bundi'),
(474, '29', 'Bhilwara'),
(475, '29', 'Churu'),
(476, '29', 'Chittorgarh'),
(477, '29', 'Dausa'),
(478, '29', 'Dholpur'),
(479, '29', 'Dungapur'),
(480, '29', 'Ganganagar'),
(481, '29', 'Hanumangarh'),
(482, '29', 'Jhunjhunu'),
(483, '29', 'Jalore'),
(484, '29', 'Jodhpur'),
(485, '29', 'Jaipur'),
(486, '29', 'Jaisalmer'),
(487, '29', 'Jhalawar'),
(488, '29', 'Karauli'),
(489, '29', 'Kota'),
(490, '29', 'Nagaur'),
(491, '29', 'Pali'),
(492, '29', 'Pratapgarh'),
(493, '29', 'Rajsamand'),
(494, '29', 'Sikar'),
(495, '29', 'Sawai Madhopur'),
(496, '29', 'Sirohi'),
(497, '29', 'Tonk'),
(498, '29', 'Udaipur'),
(499, '30', 'East Sikkim'),
(500, '30', 'North Sikkim'),
(501, '30', 'South Sikkim'),
(502, '30', 'West Sikkim'),
(503, '31', 'Ariyalur'),
(504, '31', 'Chennai'),
(505, '31', 'Coimbatore'),
(506, '31', 'Cuddalore'),
(507, '31', 'Dharmapuri'),
(508, '31', 'Dindigul'),
(509, '31', 'Erode'),
(510, '31', 'Kanchipuram'),
(511, '31', 'Kanyakumari'),
(512, '31', 'Karur'),
(513, '31', 'Krishnagiri'),
(514, '31', 'Madurai'),
(515, '31', 'Nagapattinam'),
(516, '31', 'Nilgiris'),
(517, '31', 'Namakkal'),
(518, '31', 'Perambalur'),
(519, '31', 'Pudukkottai'),
(520, '31', 'Ramanathapuram'),
(521, '31', 'Salem'),
(522, '31', 'Sivaganga'),
(523, '31', 'Tirupur'),
(524, '31', 'Tiruchirappalli'),
(525, '31', 'Theni'),
(526, '31', 'Tirunelveli'),
(527, '31', 'Thanjavur'),
(528, '31', 'Thoothukudi'),
(529, '31', 'Tiruvallur'),
(530, '31', 'Tiruvarur'),
(531, '31', 'Tiruvannamalai'),
(532, '31', 'Vellore'),
(533, '31', 'Viluppuram'),
(534, '31', 'Virudhunagar'),
(535, '32', 'Dhalai'),
(536, '32', 'North Tripura'),
(537, '32', 'South Tripura'),
(538, '32', 'Khowai[7]'),
(539, '32', 'West Tripura'),
(540, '33', 'Agra'),
(541, '33', 'Aligarh'),
(542, '33', 'Allahabad'),
(543, '33', 'Ambedkar Nagar'),
(544, '33', 'Auraiya'),
(545, '33', 'Azamgarh'),
(546, '33', 'Bagpat'),
(547, '33', 'Bahraich'),
(548, '33', 'Ballia'),
(549, '33', 'Balrampur'),
(550, '33', 'Banda'),
(551, '33', 'Barabanki'),
(552, '33', 'Bareilly'),
(553, '33', 'Basti'),
(554, '33', 'Bijnor'),
(555, '33', 'Budaun'),
(556, '33', 'Bulandshahr'),
(557, '33', 'Chandauli'),
(558, '33', 'Chhatrapati Shahuji Maharaj Nagar[8]'),
(559, '33', 'Chitrakoot'),
(560, '33', 'Deoria'),
(561, '33', 'Etah'),
(562, '33', 'Etawah'),
(563, '33', 'Faizabad'),
(564, '33', 'Farrukhabad'),
(565, '33', 'Fatehpur'),
(566, '33', 'Firozabad'),
(567, '33', 'Gautam Buddh Nagar'),
(568, '33', 'Ghaziabad'),
(569, '33', 'Ghazipur'),
(570, '33', 'Gonda'),
(571, '33', 'Gorakhpur'),
(572, '33', 'Hamirpur'),
(573, '33', 'Hardoi'),
(574, '33', 'Hathras'),
(575, '33', 'Jalaun'),
(576, '33', 'Jaunpur district'),
(577, '33', 'Jhansi'),
(578, '33', 'Jyotiba Phule Nagar'),
(579, '33', 'Kannauj'),
(580, '33', 'Kanpur'),
(581, '33', 'Kanshi Ram Nagar'),
(582, '33', 'Kaushambi'),
(583, '33', 'Kushinagar'),
(584, '33', 'Lakhimpur Kheri'),
(585, '33', 'Lalitpur'),
(586, '33', 'Lucknow'),
(587, '33', 'Maharajganj'),
(588, '33', 'Mahoba'),
(589, '33', 'Mainpuri'),
(590, '33', 'Mathura'),
(591, '33', 'Mau'),
(592, '33', 'Meerut'),
(593, '33', 'Mirzapur'),
(594, '33', 'Moradabad'),
(595, '33', 'Muzaffarnagar'),
(596, '33', 'Panchsheel Nagar district (Hapur)'),
(597, '33', 'Pilibhit'),
(598, '33', 'Pratapgarh'),
(599, '33', 'Raebareli'),
(600, '33', 'Ramabai Nagar (Kanpur Dehat)'),
(601, '33', 'Rampur'),
(602, '33', 'Saharanpur'),
(603, '33', 'Sant Kabir Nagar'),
(604, '33', 'Sant Ravidas Nagar'),
(605, '33', 'Shahjahanpur'),
(606, '33', 'Shamli[9]'),
(607, '33', 'Shravasti'),
(608, '33', 'Siddharthnagar'),
(609, '33', 'Sitapur'),
(610, '33', 'Sonbhadra'),
(611, '33', 'Sultanpur'),
(612, '33', 'Unnao'),
(613, '33', 'Varanasi'),
(614, '34', 'Almora'),
(615, '34', 'Bageshwar'),
(616, '34', 'Chamoli'),
(617, '34', 'Champawat'),
(618, '34', 'Dehradun'),
(619, '34', 'Haridwar'),
(620, '34', 'Nainital'),
(621, '34', 'Pauri Garhwal'),
(622, '34', 'Pithoragarh'),
(623, '34', 'Rudraprayag'),
(624, '34', 'Tehri Garhwal'),
(625, '34', 'Udham Singh Nagar'),
(626, '34', 'Uttarkashi'),
(627, '35', 'Bankura'),
(628, '35', 'Bardhaman'),
(629, '35', 'Birbhum'),
(630, '35', 'Cooch Behar'),
(631, '35', 'Dakshin Dinajpur'),
(632, '35', 'Darjeeling'),
(633, '35', 'Hooghly'),
(634, '35', 'Howrah'),
(635, '35', 'Jalpaiguri'),
(636, '35', 'Kolkata'),
(637, '35', 'Maldah'),
(638, '35', 'Murshidabad'),
(639, '35', 'Nadia'),
(640, '35', 'North 24 Parganas'),
(641, '35', 'Paschim Medinipur'),
(642, '35', 'Purba Medinipur'),
(643, '35', 'Purulia'),
(644, '35', 'South 24 Parganas'),
(645, '35', 'Uttar Dinajpur');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `created_at`, `updated_at`, `name`, `description`) VALUES
(1, NULL, NULL, 'Admin', 'Main Control'),
(2, NULL, NULL, 'Driver', 'Driver info'),
(3, NULL, NULL, 'Franchise', 'Franchise ');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country_id`) VALUES
(1, 'Assam ', 101),
(2, 'Jammu and Kashmir', 101),
(3, 'Maharashtra', 101),
(4, 'Uttar Pradesh', 101),
(5, 'Gujarat', 101),
(6, 'Andhra Pradesh', 101),
(7, 'Karnataka', 101),
(8, 'Kerala', 101),
(9, 'West Bengal', 101),
(10, 'Tripura', 101),
(11, 'Chhattisgarh', 101),
(12, 'Punjab', 101),
(13, 'Mizoram', 101),
(14, 'Rajasthan', 101),
(15, 'Goa', 101),
(16, 'Uttarakhand', 101),
(17, 'Arunachal Pradesh', 101),
(18, 'Bihar', 101),
(19, 'Lakshadweep', 101),
(20, 'Jharkhand', 101),
(21, 'Dadra and Nagar Haveli', 101),
(22, 'Orissa', 101),
(23, 'Tamil Nadu', 101),
(24, 'Himachal Pradesh', 101),
(25, 'Haryana', 101),
(26, 'Madhya Pradesh', 101),
(27, 'Delhi', 101),
(28, 'Chandigarh', 101),
(29, 'Daman and Diu', 101),
(30, 'Nagaland', 101),
(31, 'Sikkim', 101),
(32, 'Manipur', 101),
(33, 'Meghalaya', 101),
(34, 'Pondicherry', 101),
(36, 'Andaman and Nicobar Islands', 101),
(37, 'Telangana', 101);

-- --------------------------------------------------------

--
-- Table structure for table `temp_cmpy_drivershare`
--

CREATE TABLE `temp_cmpy_drivershare` (
  `driver_id` int(11) NOT NULL,
  `share_amount` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_cmpy_drivershare`
--

INSERT INTO `temp_cmpy_drivershare` (`driver_id`, `share_amount`) VALUES
(5, 87),
(6, 132),
(1, 26),
(4, 260),
(2, 94),
(3, 31);

-- --------------------------------------------------------

--
-- Table structure for table `temp_fare_status`
--

CREATE TABLE `temp_fare_status` (
  `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_individual_drivershare`
--

CREATE TABLE `temp_individual_drivershare` (
  `driver_id` int(11) NOT NULL,
  `share_amount` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_individual_drivershare`
--

INSERT INTO `temp_individual_drivershare` (`driver_id`, `share_amount`) VALUES
(5, 203),
(6, 308),
(1, 60),
(4, 606),
(2, 219),
(3, 73);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `date_of_run` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Adminstrator', '', 'admin@mobycabs.com', '$2y$10$3tTHeDMlX9g.ejZXzxgZC.2KhJDx.2UMds2VHqGSKX6HWlQOSGjre', 'D7ZZa1Qemu7hXbtiX6c6WRivArneyOo0T2CeYvcUo908yNUPt9hGC6CZoRFX', '2016-10-18 17:46:14', '2018-02-06 06:48:32'),
(2, 'test', 'franchise', 'test@test.com', '$2y$10$awaG.oWMCa/.cfjOEeX8nOKJFZRzkkkxKH/R2b1hX1uAmtHJ3y6la', NULL, '2017-02-08 15:57:09', '2017-02-08 15:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `created_at`, `updated_at`, `user_id`, `role_id`) VALUES
(1, '2016-10-18 19:46:23', '2016-10-18 19:46:23', 1, 1),
(2, '2017-02-08 15:57:09', '2017-02-08 15:57:09', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `wy_alert`
--

CREATE TABLE `wy_alert` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `alert_location` varchar(200) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_alert`
--

INSERT INTO `wy_alert` (`id`, `customer_id`, `ride_id`, `driver_id`, `alert_location`, `lat`, `lng`, `created_at`, `updated_at`) VALUES
(1, 2, 34, 1, 'Abyan Governorate', '13.424408839801309', '45.55899750441313', '2017-03-21 12:45:45', '2017-03-21 12:45:45'),
(2, 2, 34, 1, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', '21.14531360427222', '72.77341570705175', '2017-03-21 12:45:50', '2017-03-21 12:45:50'),
(3, 8, 41, 6, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', '11.016571036624448', '76.95750921964645', '2017-03-21 15:15:33', '2017-03-21 15:15:33'),
(4, 8, 42, 6, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', '11.016585516920422', '76.95751156657934', '2017-03-21 15:32:13', '2017-03-21 15:32:13'),
(5, 8, 46, 6, '22, Doctor Krishnasamy Mudaliyar RoadNear Broke Field Road, Sukrawar Pettai, R.S. PuramCoimbatore, Tamil Nadu 641002', '11.00610850751954', '76.96116104722023', '2017-03-21 17:12:21', '2017-03-21 17:12:21'),
(6, 7, 47, 6, '398, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', '11.01321784163039', '76.95877857506275', '2017-03-21 17:34:08', '2017-03-21 17:34:08'),
(7, 8, 64, 4, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', '11.016657918389607', '76.95750955492258', '2017-03-22 11:42:46', '2017-03-22 11:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `wy_assign_history`
--

CREATE TABLE `wy_assign_history` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_assign_taxi`
--

CREATE TABLE `wy_assign_taxi` (
  `id` int(11) NOT NULL,
  `ride_category` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `car_num` int(30) DEFAULT NULL COMMENT 'car_id -primary key of carlist table',
  `owner_ship` int(1) DEFAULT NULL COMMENT '1-attached, 2-employee',
  `country` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `district` int(12) DEFAULT NULL,
  `status` int(5) DEFAULT '1' COMMENT '1-active, 2-blocked',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_assign_taxi`
--

INSERT INTO `wy_assign_taxi` (`id`, `ride_category`, `driver_id`, `car_num`, `owner_ship`, `country`, `state`, `city`, `district`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 101, 5, 195, NULL, 1, 1, '2017-03-13 18:38:02', 1, '2017-03-13 18:38:02'),
(2, 1, 2, 2, 1, 101, 5, 195, NULL, 1, 1, '2017-03-14 09:55:17', 1, '2017-03-14 09:55:17'),
(3, 1, 3, 3, 1, 101, 5, 195, NULL, 1, 1, '2017-03-14 09:57:00', 1, '2017-03-14 09:57:00'),
(4, 1, 4, 4, 1, 101, 23, 522, NULL, 1, 1, '2017-03-16 16:40:40', 1, '2017-03-16 16:40:40'),
(5, 1, 5, 5, 1, 101, 17, 409, NULL, 1, 1, '2017-03-21 11:40:14', 1, '2017-03-21 11:40:14'),
(6, 1, 6, 6, 1, 101, 19, 451, NULL, 1, 1, '2017-03-21 11:42:47', 1, '2017-03-21 11:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `wy_brand`
--

CREATE TABLE `wy_brand` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `status` int(5) NOT NULL,
  `created_by` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_brand`
--

INSERT INTO `wy_brand` (`id`, `brand`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Maruthi', 1, 1, '2017-03-09 15:58:44', NULL, '2017-03-09 15:58:44'),
(2, 'Datsun', 1, 1, '2017-03-09 15:58:50', 1, '2017-03-10 13:09:20'),
(3, 'Tata', 1, 1, '2017-03-10 12:33:10', 1, '2017-03-10 13:09:09'),
(4, 'Toyota', 1, 1, '2017-03-10 13:08:49', NULL, '2017-03-10 13:08:49'),
(5, 'Nissan', 1, 1, '2017-03-10 13:08:58', NULL, '2017-03-10 13:08:58'),
(6, 'Hyundai', 1, 1, '2017-03-10 13:09:35', NULL, '2017-03-10 13:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `wy_carlist`
--

CREATE TABLE `wy_carlist` (
  `id` int(11) NOT NULL,
  `car_type` varchar(100) NOT NULL,
  `franchise_id` int(11) NOT NULL COMMENT 'Franchise Id ',
  `isfranchise` int(11) NOT NULL DEFAULT '0' COMMENT '0 - Not Franchise, 1 - Franchise',
  `ride_category` int(11) DEFAULT NULL,
  `is_booking_basedon` varchar(10) DEFAULT '1' COMMENT '1-cab, 2-outstation, 3-rental, 4-share',
  `car_no` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT '0',
  `brand` varchar(20) NOT NULL,
  `model` varchar(20) NOT NULL,
  `rc_no` varchar(50) NOT NULL,
  `insurance_expiration_date` date NOT NULL,
  `insurance_image` varchar(100) NOT NULL,
  `rc_image` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `district` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `car_attached` int(5) NOT NULL DEFAULT '1' COMMENT '1-normal, 2-attached cars',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1-Active, -1 Blocked, 0 Non Active',
  `vehical_image` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_carlist`
--

INSERT INTO `wy_carlist` (`id`, `car_type`, `franchise_id`, `isfranchise`, `ride_category`, `is_booking_basedon`, `car_no`, `capacity`, `brand`, `model`, `rc_no`, `insurance_expiration_date`, `insurance_image`, `rc_image`, `city`, `state`, `country`, `district`, `created_by`, `updated_by`, `car_attached`, `status`, `vehical_image`, `created_at`, `updated_at`) VALUES
(1, '3', 0, 0, 1, '1', 'GJ78 S3421', 0, '2', '4', '23444555111222', '2022-03-17', '/uploads/insurance/13032017/227588.jpg', '/uploads/rc_book/13032017/768717.jpg', '195', '5', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/13032017/227588.jpg', '2017-03-13 00:00:00', '2017-03-13 00:00:00'),
(2, '1', 0, 0, 1, '1', 'GJ78 S4377', 0, '1', '11', '111111111222222', '2019-03-07', '/uploads/insurance/14032017/138296.jpg', '/uploads/rc_book/14032017/669031.jpg', '195', '5', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/14032017/138296.jpg', '2017-03-14 00:00:00', '2017-03-14 00:00:00'),
(3, '2', 0, 0, 1, '1', 'Gj87 S33333', 0, '5', '9', '1234567890890999', '2022-03-23', '/uploads/insurance/14032017/804873.jpg', '/uploads/rc_book/14032017/737213.jpg', '195', '5', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/14032017/804873.jpg', '2017-03-14 00:00:00', '2017-03-14 00:00:00'),
(4, '3', 0, 0, 1, '1', 'TN99KK4446', 0, '5', '9', 'NTKGM95KY', '2018-03-08', '/uploads/insurance/16032017/694587.jpg', '/uploads/rc_book/16032017/714914.jpg', '522', '23', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/16032017/694587.jpg', '2017-03-16 00:00:00', '2017-03-16 00:00:00'),
(5, '3', 0, 0, 1, '1', 'TN88KG4443', 0, '1', '1', 'TN85K86HG', '2018-03-07', '/uploads/insurance/21032017/722731.jpg', '/uploads/rc_book/21032017/241679.jpg', '409', '17', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/21032017/722731.jpg', '2017-03-21 00:00:00', '2017-03-21 00:00:00'),
(6, '3', 0, 0, 1, '1', 'TN55HK9992', 0, '5', '7', 'TN88GK547GT', '2018-03-01', '/uploads/insurance/21032017/255618.jpg', '/uploads/rc_book/21032017/719098.jpg', '451', '19', '101', NULL, 1, 1, 2, 1, '/uploads/carpic/21032017/255618.jpg', '2017-03-21 00:00:00', '2017-03-21 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `wy_cartype`
--

CREATE TABLE `wy_cartype` (
  `id` int(11) NOT NULL,
  `ride_category` int(11) NOT NULL,
  `car_type` varchar(50) NOT NULL,
  `car_board` int(11) NOT NULL DEFAULT '0' COMMENT '1-white, 2-yellow',
  `is_category` int(11) NOT NULL DEFAULT '1' COMMENT '1-cab, 2-outstation,3-rental,4-share',
  `cartype_basedon` varchar(10) NOT NULL DEFAULT '1' COMMENT '1-cab, 2-outstation, 3-rental, 4-share',
  `capacity` int(11) NOT NULL,
  `yellow_caricon` varchar(100) NOT NULL,
  `grey_caricon` varchar(100) NOT NULL,
  `black_caricon` varchar(100) NOT NULL,
  `companydriver_share` varchar(10) NOT NULL,
  `attacheddriver_share` varchar(10) NOT NULL,
  `franchise_share` varchar(10) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_cartype`
--

INSERT INTO `wy_cartype` (`id`, `ride_category`, `car_type`, `car_board`, `is_category`, `cartype_basedon`, `capacity`, `yellow_caricon`, `grey_caricon`, `black_caricon`, `companydriver_share`, `attacheddriver_share`, `franchise_share`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mini', 0, 1, '1', 3, '/uploads/cartype/yellow_caricon/20170310/21917.png', '/uploads/cartype/grey_caricon/20170310/81133.png', '', '30', '70', '0', 1, 1, 1, '2017-03-09 16:00:53', '2017-03-10 17:21:55'),
(2, 1, 'Prime', 0, 1, '1', 4, '/uploads/cartype/yellow_caricon/20170310/30237.png', '/uploads/cartype/grey_caricon/20170310/79630.png', '', '30', '70', '0', 1, 1, 1, '2017-03-09 16:01:12', '2017-03-10 17:22:12'),
(3, 1, 'Micro', 0, 1, '1', 2, '/uploads/cartype/yellow_caricon/20170310/37918.png', '/uploads/cartype/grey_caricon/20170310/25960.png', '', '30', '70', '0', 1, 1, 1, '2017-03-10 12:34:53', '2017-03-10 17:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `wy_city`
--

CREATE TABLE `wy_city` (
  `id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_contactus`
--

CREATE TABLE `wy_contactus` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_contactus`
--

INSERT INTO `wy_contactus` (`id`, `customer_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 2, 'Test\nHi hello ', '2017-03-09 16:54:36', '2017-03-09 16:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `wy_customer`
--

CREATE TABLE `wy_customer` (
  `id` int(11) NOT NULL,
  `fb_id` varchar(20) NOT NULL COMMENT 'use when user came thru messenger bot',
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `OTP` varchar(10) NOT NULL,
  `verification_status` int(11) NOT NULL COMMENT '0-not verified, 1-verified',
  `resend otp` int(11) NOT NULL DEFAULT '1',
  `otp_time` datetime NOT NULL,
  `device_type` int(11) NOT NULL COMMENT '1-ios,2-android',
  `device_token` varchar(500) NOT NULL,
  `profile_status` int(4) NOT NULL DEFAULT '1' COMMENT '1-not blocked, 0-blocked',
  `is_deleted` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-yes, 0-no',
  `registered_by` int(1) NOT NULL DEFAULT '1' COMMENT '1-app, 2-through admin, 3-thru site, 4-messenger bot',
  `login_status` enum('1','0') NOT NULL COMMENT '1-login,0-logout',
  `created_by` int(11) NOT NULL,
  `temp_token` varchar(200) NOT NULL,
  `last_logintime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `auth_token` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_customer`
--

INSERT INTO `wy_customer` (`id`, `fb_id`, `name`, `mobile`, `email`, `password`, `OTP`, `verification_status`, `resend otp`, `otp_time`, `device_type`, `device_token`, `profile_status`, `is_deleted`, `registered_by`, `login_status`, `created_by`, `temp_token`, `last_logintime`, `auth_token`, `created_at`, `updated_at`) VALUES
(1, '', 'Kalai', '8056359277', 'sample@gmail.com', 'YzJOamNxQlZXTjFaYW9rVlJ6OHFwUT09', '', 1, 1, '2017-03-14 09:34:24', 1, '28d1e4a814db45b0cdabf929106d5f9dd48d966900f1c591dcff70ecdd6b5e6d', 1, '0', 1, '1', 0, 'MzEyNDg=', '2017-03-22 04:41:32', '8fbe596e3f8dc5960f2d7cebc211ffd6eec656ec', '2017-03-22 04:41:32', '2017-03-14 09:34:24'),
(2, '', 'kanhaiya kamlani', '9898076131', 'k.kamlani@yahoo.com', 'TDBUM292Q3c1cmk5OWlUOGM1KzhuQT09', '', 1, 1, '2017-03-16 17:40:15', 2, 'dXbjLItChLQ:APA91bFD82poMdPDOOqdAeZyqun6Cd2J0BrTuWCayQvhm1ruSqRjfz7KUMJX8v9qg2Eb_HaOGniR9lwmawzYyCAQtnDaQ1h-G38f5a34W0AOZiT2UTiHzxVJNQn-OKEEzw7ZgdHons4w', 1, '0', 1, '1', 0, 'ODI0MDI=', '2017-03-22 21:35:00', 'def52c13d194240da2a3716994fbbd187cdb4596', '2017-03-22 16:05:00', '2017-03-16 11:24:42'),
(6, '', 'Ralph test', '7418350688', 'mraj0045@gmail.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '', 1, 1, '2017-03-16 16:18:40', 2, '', 1, '0', 1, '0', 0, 'MjA3NTI=', '2017-03-21 07:48:46', 'ce54e0edecfab77b3b96200908806c2fd68aaa92', '2017-03-21 07:48:46', '2017-03-16 16:18:40'),
(7, '', 'Armor', '9965524724', 'johnmobiles123@gmail.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '', 1, 1, '2017-03-16 16:33:12', 2, 'eu7EHL2B9Do:APA91bFsDI5lyz7K7OwMPbIWxaan411hsk88Oonoacs-hWTvepnAsFXUqFDurA_XY4fUSSHC3g9rV65At8uRDyKgbu9V-fBfch5YhB1PVplxhZVYzMybg99FqrN600h3auL1nsSI3VVm', 1, '0', 1, '1', 0, 'MzgzNzk=', '2017-03-24 14:56:14', '11b46fd0f2b0ba2030141c83d71cf9a4992929c8', '2017-03-24 09:26:14', '2017-03-16 16:33:12'),
(8, '', 'suganya', '9655119446', 'sugannyapalanisamy@gmail.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '', 1, 1, '2017-03-16 16:47:04', 1, '9853e99d9c8bd02d1bede64d019e79dd763f42fe759f25b7c1e0b16bc48dd906', 1, '0', 1, '1', 0, 'NDkzMTc=', '2017-03-24 15:04:18', 'b8c5253a53ecad32ddb475f6e4816964d014a8a6', '2017-03-24 09:34:18', '2017-03-16 16:47:04'),
(9, '', 'Giri', '9524722184', 'test@test.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '', 1, 1, '2017-03-16 17:08:20', 2, 'dOxHFrZlq7Q:APA91bFd6UAMJb_Tf9OCaG1RScbol1rhAVRdVNiV-28VB9pTOsxjo6I-z-u9wA8kXl2iIkBKGulZ3xIwMigU5b0VyEuQpkuCvnjRFS-Xynhkja5HdIDHGogi6P1tJ15YH2IhB2sU4rnB', 1, '0', 1, '1', 0, 'MjI2MTQ=', '2017-03-22 14:22:46', '1ecd87ec9e3d21bae709058d58b8322113a2ff58', '2017-03-22 08:52:46', '2017-03-16 17:08:20'),
(10, '', 'vipul Parekh ', '9726945145', 'parekh.fenny@yahoo.com', 'elEzNVFKbWJ4djBXdkxhTHh5U2ZTUT09', '', 1, 1, '2017-03-17 17:13:07', 2, 'eiXgTlaeJeE:APA91bHfS39LTw2vziP3wxCEOugUF9emMTNr34MwQ5bbHms9_2S0UTjIR1erFbm9QfEjVEad0ClSnYZn_2OzA088iwYZwS2eOPDaCUaQdIfLFmbWtM2-ScdpQzpH0BI7Wap3XzJv8J9p', 1, '0', 1, '1', 0, 'OTc1MDg=', '2017-03-17 11:43:14', '9d4661fffa8289e17212859c0281f739600a3a72', '2017-03-17 11:43:14', '2017-03-17 17:13:07'),
(11, '', 'nilesh tamaku wala', '7383422785', 'nileshtamaku@gmail.com', 'eVd0VW0veXJ4bFVOR2Y1SEVKS0xLdz09', '', 1, 1, '2017-03-17 17:17:13', 2, 'emvyhre4HqY:APA91bENnHQBdpuesVMY5h_iA5Kbu3d4CZXBxEnvnYOYmQt_nbDJsMXBNg3hAv2byojZpojUr4fJPuh-8PeeRvxr2RLAXZhO4jHHMpWPzoODXVViP80RQhLTIfbApiWQ68Sbbf6t2-Aq', 1, '0', 1, '1', 0, 'MjM5Mzg=', '2017-03-17 11:47:50', '2e1b962c8da2ff287139ab54879fe9d4a0348360', '2017-03-17 11:47:50', '2017-03-17 17:17:13'),
(12, '', 'ketul', '8780294903', 'Kunjansolankii90@gmail.com', 'TkNORWVBNjZJWEVzeHlkRmVBd2Nvdz09', '', 1, 1, '2017-03-17 18:08:21', 2, 'f8sg4LQ06hU:APA91bFQwxBOvHDvqw6vTAYCrFyp4PKIkBWJLiwRqboWqlBs0h663S7m34sXDNVJ4BXlT5s92kQRvCHkXDRdGDKB0_e2B2VgfWgfnYVfxid6twZacOT3vH2RjdsC5qvelJt0wTbnnswv', 1, '0', 1, '1', 0, 'MTc1ODM=', '2017-03-17 12:38:34', '18afd3a78f9e082de26332baff1280078f8ad83e', '2017-03-17 12:38:34', '2017-03-17 18:08:21'),
(13, '', 'hary', '8200769110', 'vibha201@gmail.com', 'c0VCVkl6cDJkQzV1ZEN1RVRxbzd2Zz09', '', 1, 1, '2017-03-17 19:29:27', 2, 'dzjuRgrozjc:APA91bEwU5pEMIRfz3iq3XnCr6gL5T4Gr3QOoITYdHFXtIh2cm9v6DPb_q4AIc28xSN78DvVD2W67Baqv0r1qYSD-th-xups0Bu_VWYthxzfRnAFLeojq8Scq7l_HPS3udI2WO_CKixA', 1, '0', 1, '1', 0, 'ODQ3MjI=', '2017-03-17 14:00:39', '4b25226f4e87ab74baf93e841b6b02c21f925c32', '2017-03-17 14:00:39', '2017-03-17 19:27:30'),
(14, '', 'Mobycabstest', '8940040537', 'moby@test.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '', 1, 1, '2017-03-20 16:20:16', 2, 'fkW6soOyCJ4:APA91bHYb7jLoRS_mR_QVJP45rrUY5BMZvGI0lC5pjWIOeTU9-gBEB62N8I91BeKdSBKAH3u-1xUBzUNdx5863cvAme5y9y9FKw0tOIbAVHz7vM5OAI18OQkC5-WkKsaMDcBfhPdMbwI', 1, '0', 1, '1', 0, 'NzQ4ODg=', '2017-03-20 17:30:29', '6372204812ded47dcb3b0e79630a92b71904808b', '2017-03-20 12:00:29', '2017-03-20 16:20:16'),
(15, '', 'Mohan', '7418350689', 'test@tezt.com', 'NmYzRjIwYUsxczVTdjZXdU9KdGUxZz09', '4235', 0, 1, '2017-03-21 13:24:39', 2, '', 1, '0', 1, '1', 0, 'NjkyODQ=', '2017-03-21 07:54:39', 'fd5b2aedc4acc5a56ea7f8fd82b2953b32fc9bb7', '2017-03-21 07:54:39', '2017-03-21 13:13:26'),
(16, '', 'rahul', '9714408894', 'rahul.kamlani@yahoo.com', 'RUlCaFB4UFNzWHV4aTIwK01GblJZUT09', '', 1, 1, '2017-03-22 08:47:51', 2, 'eI7yBHvEmSo:APA91bEFl4iEsfnUh11IDb3y8uEptwwW8t9qiGgv7GdGXSsDBRLCbiwBM_hESi0kn-3h9n4fyPAJcweCrkYZY5qA6QR3AoaWVuxpFgFbXsElcIYyhqNFJctG9-FUQ8_agRLNbUS6B4cN', 1, '0', 1, '1', 0, 'MjI1MDA=', '2017-03-22 03:17:58', '47b73421588f6ff14d2c954499e336f96317d0e9', '2017-03-22 03:17:58', '2017-03-22 08:47:51'),
(17, '', 'Kalai', '9787154041', 'sample@gmil.com', 'YzJOamNxQlZXTjFaYW9rVlJ6OHFwUT09', '', 1, 1, '2017-03-23 18:05:17', 1, '', 1, '0', 1, '0', 0, 'NDk2ODU=', '2017-03-24 04:28:35', '471b969b9bca4dc1f4ff63658b2a29190f9b4d43', '2017-03-24 04:28:35', '2017-03-23 18:05:17'),
(18, '', 'Sanjaysolanki', '8320235749', 'Sanjaysolanki7084@gmail.com', 'dWpnbEhhbEgwU0NFQk5UK2xBdTNQUT09', '', 1, 1, '2017-03-23 21:51:00', 2, 'fiCnlNxjKyc:APA91bGLC8U-a3p8qAiQ2IOwEUXEiiBzCf4gYofDGENgZdv2BVNZV8t5ykl7aEekxo3TIJ4B6MbykKxkK2Rhogqb0MA4grkyKYsRRikGAQRMnxl7c1NDIalG8O3rfHrYSugi_zUvaLZj', 1, '0', 1, '1', 0, 'ODQwNTM=', '2017-03-23 16:21:06', 'a723a31821aae62c88399d775cb6ca528dad4f51', '2017-03-23 16:21:06', '2017-03-23 21:51:00'),
(19, '', 'Kim', '9820508167', 'kim.menezes10@gmail.com', 'Y0JYek5wWk1hUXhscHc3T2RKRTErdz09', '', 1, 1, '2017-03-24 11:35:34', 2, 'f7m4YUPDQ1c:APA91bFsDDJExCa1yshUtzWKnuvUiU1Fbu3rDAnBsUJYNH0qEaSBl7MUsNL1T0_9OIwCNLCST6mCA4ly-PPGMWWoxDdGRO0rU_lafn-9qR4EU_mduvPc0v90g9VhyE25Kl-n_LcyAWVI', 1, '0', 1, '1', 0, 'NTAwNTE=', '2017-03-24 06:05:49', '9b685ca1e481859d89a2aa531ed878f8b818ce27', '2017-03-24 06:05:49', '2017-03-24 11:35:34'),
(20, '', 'Manuj Laskar', '9864056281', 'manujlaskar@gmail.com', 'enU1SkUvUURRN2QrL2ZGMjY2UXN4dz09', '', 1, 1, '2017-03-24 14:46:10', 2, 'fYFbj_Fy3FY:APA91bEpCbMTvI9hXD-gENpzk2BLgGKVoWEcLEubNxdXuVbkYk1TvFGfs-VxNPcuSGThlQdOeSpRpKkeYAboD_M-9IQQQUAwKeJPQtn3ObVsBXkOc7srooGHlPoytNsPZZ1inubzXpNa', 1, '0', 1, '1', 0, 'NDk3NjM=', '2017-03-24 09:16:24', '59b315629d7d98c14f1c9ec5533cf34129e9f73a', '2017-03-24 09:16:24', '2017-03-24 14:46:10'),
(21, '', 'anbalagan', '9094090942', 'gjanbu91@gmail.com', 'QUtLY3dJcTZwbnpMc3o4eEVxK3Y4dz09', '', 1, 1, '2017-03-24 15:12:46', 2, 'cWzzMgHO3sI:APA91bGTwG3DU_LDW5GuFrlTJ_UGGDyfs3n4FlDFzIIr1SoVqpoBsXlGgU1FLMEq9Abk7jv7u4IxmVt3K7YJmSasdUNyoOvzbT6_FA7NqjmvAnfqQTP3gksku0OI-3vjq0IjOn3ELngW', 1, '0', 1, '1', 0, 'MjY5ODM=', '2017-03-24 09:42:58', 'd90be3c2f498bf930842dc09c8420f18eebe237c', '2017-03-24 09:42:58', '2017-03-24 15:12:46');

--
-- Triggers `wy_customer`
--
DELIMITER $$
CREATE TRIGGER `ins_money` AFTER INSERT ON `wy_customer` FOR EACH ROW insert into wy_taximoney(customer_id,created_at,updated_at) values(NEW.id,NOW(),NOW())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `wy_customerrate`
--

CREATE TABLE `wy_customerrate` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `rating` decimal(5,1) NOT NULL,
  `reason_id` varchar(100) NOT NULL,
  `comments` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_customerrate`
--

INSERT INTO `wy_customerrate` (`id`, `customer_id`, `driver_id`, `ride_id`, `rating`, `reason_id`, `comments`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, '3.5', '', '', '2017-03-14 11:48:51', '2017-03-14 11:48:51'),
(2, 1, 3, 3, '5.0', '', '', '2017-03-16 11:47:34', '2017-03-16 11:47:34'),
(3, 5, 2, 4, '5.0', '', '', '2017-03-16 16:22:38', '2017-03-16 16:22:38'),
(4, 8, 2, 6, '5.0', '', '', '2017-03-16 16:55:12', '2017-03-16 16:55:12'),
(5, 7, 4, 5, '5.0', '', '', '2017-03-16 16:57:35', '2017-03-16 16:57:35'),
(6, 8, 2, 7, '4.5', '', 'tesy', '2017-03-16 16:58:27', '2017-03-16 16:58:27'),
(7, 8, 2, 17, '4.5', '', '', '2017-03-16 18:14:56', '2017-03-16 18:14:56'),
(8, 8, 2, 18, '5.0', '', '', '2017-03-16 18:20:09', '2017-03-16 18:20:09'),
(9, 2, 1, 20, '5.0', '', 'ok', '2017-03-17 13:15:34', '2017-03-17 13:15:34'),
(10, 2, 1, 21, '5.0', '', '', '2017-03-17 15:23:20', '2017-03-17 15:23:20'),
(11, 8, 4, 19, '4.0', '', '', '2017-03-18 10:56:16', '2017-03-18 10:56:16'),
(12, 8, 4, 22, '4.0', '', 'Test the app', '2017-03-18 11:43:56', '2017-03-18 11:43:56'),
(13, 8, 4, 23, '3.0', '', '', '2017-03-18 11:59:54', '2017-03-18 11:59:54'),
(14, 8, 4, 25, '4.0', '', '', '2017-03-18 12:25:10', '2017-03-18 12:25:10'),
(15, 8, 4, 26, '4.0', '', '', '2017-03-18 12:39:17', '2017-03-18 12:39:17'),
(16, 8, 4, 27, '4.0', '', '', '2017-03-18 15:22:47', '2017-03-18 15:22:47'),
(17, 8, 4, 28, '4.0', '', '', '2017-03-18 16:12:49', '2017-03-18 16:12:49'),
(18, 8, 4, 29, '4.0', '', '', '2017-03-18 16:41:40', '2017-03-18 16:41:40'),
(19, 2, 1, 34, '4.5', '', 'ok', '2017-03-21 12:56:32', '2017-03-21 12:56:32'),
(20, 7, 6, 36, '4.5', '', '', '2017-03-21 13:02:34', '2017-03-21 13:02:34'),
(21, 7, 6, 38, '5.0', '', '', '2017-03-21 13:58:09', '2017-03-21 13:58:09'),
(22, 8, 6, 39, '4.0', '', '', '2017-03-21 14:44:51', '2017-03-21 14:44:51'),
(23, 8, 6, 40, '3.5', '', '', '2017-03-21 15:06:22', '2017-03-21 15:06:22'),
(24, 8, 6, 41, '5.0', '', '', '2017-03-21 15:15:49', '2017-03-21 15:15:49'),
(25, 8, 6, 42, '4.0', '', '', '2017-03-21 15:44:54', '2017-03-21 15:44:54'),
(26, 8, 6, 44, '5.0', '', '', '2017-03-21 17:02:09', '2017-03-21 17:02:09'),
(27, 8, 6, 46, '1.5', '', 'dhxhdhd', '2017-03-21 17:13:29', '2017-03-21 17:13:29'),
(28, 7, 6, 47, '4.5', '', '', '2017-03-21 17:35:55', '2017-03-21 17:35:55'),
(29, 2, 6, 49, '4.5', '', '', '2017-03-21 17:51:23', '2017-03-21 17:51:23'),
(30, 7, 4, 51, '4.5', '', '', '2017-03-21 18:25:34', '2017-03-21 18:25:34'),
(31, 7, 4, 52, '3.0', '', '', '2017-03-21 18:27:27', '2017-03-21 18:27:27'),
(32, 8, 4, 55, '4.0', '', '', '2017-03-21 18:29:58', '2017-03-21 18:29:58'),
(33, 8, 4, 58, '5.0', '', '', '2017-03-22 11:10:08', '2017-03-22 11:10:08'),
(34, 8, 4, 64, '5.0', '', ' ', '2017-03-22 11:43:08', '2017-03-22 11:43:08'),
(35, 8, 4, 66, '4.0', '', '', '2017-03-22 12:01:01', '2017-03-22 12:01:01'),
(36, 7, 4, 72, '4.5', '', '', '2017-03-22 12:45:45', '2017-03-22 12:45:45'),
(37, 7, 4, 74, '5.0', '', '', '2017-03-22 14:17:49', '2017-03-22 14:17:49'),
(38, 7, 4, 82, '5.0', '', '', '2017-03-22 14:48:04', '2017-03-22 14:48:04'),
(39, 7, 5, 86, '5.0', '', '', '2017-03-22 16:54:11', '2017-03-22 16:54:11'),
(40, 8, 4, 87, '5.0', '', '', '2017-03-22 18:23:25', '2017-03-22 18:23:25'),
(41, 2, 2, 99, '5.0', '', 'jj', '2017-03-23 17:33:04', '2017-03-23 17:33:04'),
(42, 2, 2, 100, '5.0', '', 'ok', '2017-03-23 18:59:02', '2017-03-23 18:59:02'),
(43, 1, 5, 43, '4.0', '', '', '2017-03-24 09:59:13', '2017-03-24 09:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `wy_customer_places`
--

CREATE TABLE `wy_customer_places` (
  `id` int(11) NOT NULL,
  `customer-id` int(11) NOT NULL,
  `home_address` text NOT NULL,
  `home_lat` varchar(20) NOT NULL,
  `home_lng` varchar(20) NOT NULL,
  `work_address` text NOT NULL,
  `work_lat` varchar(20) NOT NULL,
  `work_lng` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_dailydrive`
--

CREATE TABLE `wy_dailydrive` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL COMMENT '1-login, 0-logout',
  `online_status` enum('1','0') NOT NULL COMMENT '1-online,0-offline',
  `login_date` datetime NOT NULL,
  `logout_date` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_device`
--

CREATE TABLE `wy_device` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `OTP` varchar(10) NOT NULL,
  `verification_status` enum('0','1') NOT NULL,
  `device_type` enum('1','2') NOT NULL COMMENT '1-ios, 2-andriod',
  `device_id` varchar(100) NOT NULL,
  `device_token` varchar(500) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `otp_time` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_dispatcher`
--

CREATE TABLE `wy_dispatcher` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `country` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `is_blocked` int(11) NOT NULL COMMENT '1-blocked, 0-no',
  `is_deleted` int(11) NOT NULL COMMENT '1-yes, 0-no',
  `auth_token` varchar(300) NOT NULL,
  `device_type` int(11) NOT NULL COMMENT '1-ios, 2-andriod',
  `device_token` varchar(300) NOT NULL,
  `is_loggedin` int(11) NOT NULL COMMENT '1-yes, 0-no',
  `last_logintime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profie_pic` varchar(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_driver`
--

CREATE TABLE `wy_driver` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `franchise_id` int(11) DEFAULT '0' COMMENT 'Franchise id',
  `isfranchise` int(11) DEFAULT '0' COMMENT '0 - Not Franchise, 1 - Franchise',
  `driver_id` varchar(20) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `ride_category` varchar(200) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `gender` varchar(30) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `country` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `district` int(12) DEFAULT NULL,
  `address` text,
  `mobile` varchar(20) NOT NULL,
  `licenseid` varchar(20) DEFAULT NULL,
  `license` varchar(100) NOT NULL,
  `profile_status` int(5) DEFAULT '0' COMMENT '1-Active, -1 Blocked, 0 Non Active, 2-delete',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '1-login, 0-logout',
  `online_status` int(11) NOT NULL DEFAULT '0' COMMENT ' 1-online,0-offline',
  `device_type` enum('1','2') DEFAULT NULL COMMENT '1-ios,2-andriod',
  `device_token` varchar(500) DEFAULT NULL,
  `driver_type` varchar(10) DEFAULT '1' COMMENT '1-normal, 2- attached',
  `profile_photo` varchar(200) DEFAULT NULL,
  `auth_token` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_driver`
--

INSERT INTO `wy_driver` (`id`, `company_id`, `franchise_id`, `isfranchise`, `driver_id`, `password`, `ride_category`, `firstname`, `lastname`, `email`, `gender`, `dob`, `country`, `state`, `city`, `district`, `address`, `mobile`, `licenseid`, `license`, `profile_status`, `status`, `online_status`, `device_type`, `device_token`, `driver_type`, `profile_photo`, `auth_token`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 0, 'ID98552', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'demo', 'driver', 'demodriver@gmail.com', 'male', '1991-03-14', '101', '5', '195', NULL, 'Moby cabs, Surat, Gujarat, India', '6665554441', '657222222211', '/uploads/license/13032017/530851.jpg', 1, 1, 0, '2', 'dH-kZk8zHNg:APA91bEpw-gsn8_KW4fxbtslmPKZbr_fFRNttm-nMWGC44C46qOHDhLkMEwCKADtb56gs7ONLBMEdx5t39eur8ohybrelx5T8EhPunaRIL6o6YHGe8Af0n0fJ37ZY_sUKXRXmcBGANdT', '2', '/uploads/driverpic/13032017/227588.jpg', '9fce39f537240d4684783d05e0eafc1194a4fbfe', '2017-03-13 18:38:02', '2017-03-21 18:16:32'),
(2, 0, 0, 0, 'ID69225', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'John', 'mathew', 'mathew@test.com', 'male', '1999-03-24', '101', '5', '195', NULL, 'Moby cabs, Gujarat, Surat, India', '3422222211', '4555555522233', '/uploads/license/14032017/726503.jpg', 1, 1, 0, '2', 'fYSHUlWYAAY:APA91bHgekqhuzS4NE8c7e0hcp8nQK-u-SDYCmIAt3aaWizV9yIl0VxaeegE43X9jx5SGu-7AtMXzqoKVLwgAd2Krn28d6nyQXIH290ssOWj34mSnVZilu4PeD2QREpe1GLfLwRdEgKo', '2', '/uploads/driverpic/14032017/138296.jpg', '12cb9b1e5926a6a7446dac166259ca011cecec22', '2017-03-14 09:55:17', '2017-03-23 17:56:11'),
(3, 0, 0, 0, 'ID30418', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'David', 'Jon', 'david@test.com', 'male', '1980-03-26', '101', '5', '195', NULL, 'Moby cabs, Gujarat, Surat, India', '3334441113', '6789999999000', '/uploads/license/14032017/757941.jpg', 1, 1, 0, '2', 'c5zGNmhw6TQ:APA91bHWwXmouQ1d26f9alrt_8nmYCPtR7ArNIC4KyKAyaQKHOhDVJbu_-FKYX9Nf55QFPSVt7vcUGagjS_x9iw9IqukMgr2gx5liyWxJTQrF5ICoQN5-WpbZaWjvWLSIuf0FZ_6NV5B', '2', '/uploads/driverpic/14032017/804873.jpg', '92a3eeb6746eeee42d08063216eae73970313e92', '2017-03-14 09:57:00', '2017-03-21 19:56:53'),
(4, 0, 0, 0, 'ID96149', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'Frank', 'B. Brooks', 'FrankBBrooks@armyspy.com', 'male', '03-18-1965', '101', '23', '522', NULL, 'NL', ' 646-344-3', 'TNJG96LJ9', '/uploads/license/16032017/597713.jpg', 1, 1, 0, '2', 'eQBg4psT-6o:APA91bGdF9vMKl3bG24xylrKvOC7twODXjI6JJBsl16Mxg7yymmgIee-tnxfMSqysfd9tZy0aeCeIqk-mwYfaSRFsbHDQmxSMat78ew2fJUZo4Eh9QvdtFA2oaEGqm9dUfMLl2qMqpC_', '2', '/uploads/driverpic/16032017/694587.jpg', '123f619d654e57e97affb082b74c8e5d7673b48b', '2017-03-16 16:40:40', '2017-03-23 16:23:48'),
(5, 0, 0, 0, 'ID33301', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'Shane', ' W. Perez', 'ShaneWPerez@rhyta.com', 'male', '03-19-1998', '101', '17', '409', NULL, 'CN', '406-994-45', 'TNKG94K6', '/uploads/license/21032017/217077.jpg', 1, 1, 0, '1', '', '2', '/uploads/driverpic/21032017/722731.jpg', '5e445ae6dca938762b87d49f0c6d26819d346c1d', '2017-03-21 11:40:14', '2017-03-24 10:53:37'),
(6, 0, 0, 0, 'ID30132', 'U2RSVitqNE5ybTIvMGdSaGVCV0dxZz09', '1', 'Jeremy', ' V. Schil', 'JeremyVSchiller@jourrapide.com', 'male', '1998-03-19', '101', '19', '451', NULL, 'LK', '770-913-63', 'TN8GJ57G6', '/uploads/license/21032017/738886.jpg', 1, 1, 0, '2', 'dBYQSgVOkGc:APA91bF4q52PLZ1LwEGpjdTjovyuoAIaX59OdROzN9fdlFi4xGswldT_tAAJ5scsnSD6fjH1n8dSIHUPXHwM-P2POeuEtFZHmVIyem1Yet25_oNVCeTU0EtSrxGWnaUOYd7MsOb_iYYm', '2', '/uploads/driverpic/21032017/255618.jpg', '9fe00882cdf2adbd66e5db04f0ecbf521bd6d637', '2017-03-21 11:42:47', '2017-03-21 16:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `wy_driverlocation`
--

CREATE TABLE `wy_driverlocation` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `startup_time` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_driverlocation`
--

INSERT INTO `wy_driverlocation` (`id`, `driver_id`, `lat`, `lng`, `zipcode`, `startup_time`, `created_date`, `updated_date`) VALUES
(1, 1, '11.0164849', '76.9575097', '641012', '2017-03-21 18:16:51', '2017-03-13 18:40:13', '2017-03-21 18:16:57'),
(2, 3, '11.0166183', '76.9575227', '641012', '2017-03-21 18:15:40', '2017-03-14 10:10:47', '2017-03-21 18:15:51'),
(3, 2, '21.1452894', '72.7733877', '395007', '2017-03-23 14:19:22', '2017-03-16 16:08:01', '2017-03-23 18:08:17'),
(4, 4, '11.0166483', '76.9575091', '641009', '2017-03-23 16:37:51', '2017-03-16 16:47:24', '2017-03-23 16:50:47'),
(5, 6, '11.0166618', '76.9575997', '641012', '2017-03-21 17:54:51', '2017-03-21 12:57:24', '2017-03-21 17:55:04'),
(6, 5, '11.0190815', '76.9585448', '641012', '2017-03-24 09:58:43', '2017-03-21 13:00:57', '2017-03-24 12:09:06');

--
-- Triggers `wy_driverlocation`
--
DELIMITER $$
CREATE TRIGGER `wy_driverlocation_au` AFTER UPDATE ON `wy_driverlocation` FOR EACH ROW update wy_driver set online_status=1 where id=OLD.driver_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `wy_driverrate`
--

CREATE TABLE `wy_driverrate` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `rating` decimal(5,1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_driverrate`
--

INSERT INTO `wy_driverrate` (`id`, `customer_id`, `driver_id`, `ride_id`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, '0.0', '2017-03-14 11:48:52', '2017-03-14 11:48:52'),
(2, 1, 3, 3, '0.0', '2017-03-16 11:46:58', '2017-03-16 11:46:58'),
(3, 5, 2, 4, '0.0', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(4, 8, 2, 6, '0.0', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(5, 7, 4, 5, '0.0', '2017-03-16 16:57:30', '2017-03-16 16:57:30'),
(6, 8, 2, 7, '0.0', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(7, 8, 2, 17, '0.0', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(8, 8, 4, 19, '0.0', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(9, 2, 1, 20, '0.0', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(10, 8, 4, 22, '0.0', '2017-03-18 11:40:13', '2017-03-18 11:40:13'),
(11, 8, 4, 23, '0.0', '2017-03-18 11:59:49', '2017-03-18 11:59:49'),
(12, 8, 4, 25, '0.0', '2017-03-18 12:25:09', '2017-03-18 12:25:09'),
(13, 8, 4, 26, '0.0', '2017-03-18 12:39:13', '2017-03-18 12:39:13'),
(14, 8, 4, 25, '0.0', '2017-03-18 15:15:34', '2017-03-18 15:15:34'),
(15, 8, 4, 27, '0.0', '2017-03-18 15:22:32', '2017-03-18 15:22:32'),
(16, 8, 4, 28, '0.0', '2017-03-18 16:12:50', '2017-03-18 16:12:50'),
(17, 8, 4, 29, '0.0', '2017-03-18 16:41:42', '2017-03-18 16:41:42'),
(18, 2, 1, 21, '0.0', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(19, 7, 6, 36, '0.0', '2017-03-21 13:02:35', '2017-03-21 13:02:35'),
(20, 7, 6, 38, '0.0', '2017-03-21 13:47:03', '2017-03-21 13:47:03'),
(21, 8, 6, 39, '0.0', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(22, 8, 6, 40, '0.0', '2017-03-21 15:06:24', '2017-03-21 15:06:24'),
(23, 8, 6, 41, '0.0', '2017-03-21 15:16:30', '2017-03-21 15:16:30'),
(24, 1, 5, 43, '0.0', '2017-03-21 15:33:48', '2017-03-21 15:33:48'),
(25, 8, 6, 42, '0.0', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(26, 8, 6, 44, '0.0', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(27, 8, 6, 46, '0.0', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(28, 7, 6, 47, '0.0', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(29, 2, 6, 49, '0.0', '2017-03-21 17:51:27', '2017-03-21 17:51:27'),
(30, 7, 4, 51, '0.0', '2017-03-21 18:25:36', '2017-03-21 18:25:36'),
(31, 7, 4, 52, '0.0', '2017-03-21 18:27:24', '2017-03-21 18:27:24'),
(32, 8, 4, 55, '0.0', '2017-03-21 18:34:40', '2017-03-21 18:34:40'),
(33, 8, 4, 58, '0.0', '2017-03-22 11:04:10', '2017-03-22 11:04:10'),
(34, 8, 4, 64, '0.0', '2017-03-22 11:42:58', '2017-03-22 11:42:58'),
(35, 8, 4, 66, '0.0', '2017-03-22 12:01:01', '2017-03-22 12:01:01'),
(36, 7, 4, 72, '0.0', '2017-03-22 12:45:37', '2017-03-22 12:45:37'),
(37, 7, 4, 74, '0.0', '2017-03-22 13:14:51', '2017-03-22 13:14:51'),
(38, 7, 4, 82, '0.0', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(39, 7, 5, 86, '0.0', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(40, 8, 4, 87, '0.0', '2017-03-22 18:15:31', '2017-03-22 18:15:31'),
(41, 2, 2, 99, '0.0', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(42, 2, 2, 100, '0.0', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(43, 1, 5, 101, '0.0', '2017-03-24 10:54:58', '2017-03-24 10:54:58'),
(44, 1, 5, 102, '0.0', '2017-03-24 11:13:33', '2017-03-24 11:13:33'),
(45, 1, 5, 103, '0.0', '2017-03-24 11:46:48', '2017-03-24 11:46:48'),
(46, 1, 5, 104, '0.0', '2017-03-24 12:02:13', '2017-03-24 12:02:13'),
(47, 1, 5, 105, '0.0', '2017-03-24 12:07:20', '2017-03-24 12:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `wy_driver_allocation`
--

CREATE TABLE `wy_driver_allocation` (
  `id` int(11) NOT NULL,
  `ad_allocation` enum('1','2','3') NOT NULL COMMENT '1-zone,2-nearby,3-both',
  `created_by` int(22) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(22) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_emergencycontacts`
--

CREATE TABLE `wy_emergencycontacts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `is_showride` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-dont, 1-show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_emergencycontacts`
--

INSERT INTO `wy_emergencycontacts` (`id`, `customer_id`, `name`, `mobile`, `is_showride`, `created_at`, `updated_at`) VALUES
(1, 8, 'Giri', '(952) 472-2184', '1', '2017-03-16 18:21:33', '2017-03-16 18:21:33'),
(2, 7, 'Armor Gopi', '9894939935', '1', '2017-03-21 12:28:23', '2017-03-21 12:28:23'),
(3, 2, 'Rohan  kamlani', '+919725567600', '1', '2017-03-21 12:45:15', '2017-03-21 12:45:15'),
(4, 8, 'Armor Suganya', '96551 19446', '1', '2017-03-21 15:10:55', '2017-03-21 15:10:55'),
(5, 8, 'Armor Madhula', '+91 99948 80021', '1', '2017-03-21 15:16:24', '2017-03-21 15:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `wy_faredetails`
--

CREATE TABLE `wy_faredetails` (
  `fare_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL COMMENT 'its car_type',
  `ride_category` int(11) NOT NULL,
  `booking_type` int(11) NOT NULL COMMENT '1-flatrate, 2, out,3-rent',
  `package_days` varchar(20) NOT NULL DEFAULT '0',
  `package_id` int(11) NOT NULL DEFAULT '0',
  `district` int(11) NOT NULL DEFAULT '1',
  `fare_type` int(11) NOT NULL COMMENT '1-basefare, 2-mor, 3-night, 4-peak, 5-special',
  `min_time` int(11) NOT NULL DEFAULT '0',
  `min_km` int(10) DEFAULT NULL,
  `min_fare_amount` float DEFAULT NULL,
  `ride_each_km` int(11) DEFAULT NULL,
  `ride_fare` float DEFAULT NULL,
  `fare_percent` float DEFAULT '0' COMMENT 'in percentage%',
  `distance_time` int(11) DEFAULT '1',
  `distance_fare` int(11) DEFAULT NULL,
  `waiting_time` int(11) DEFAULT '1',
  `waiting_charge` float DEFAULT NULL,
  `ride_start_time` time DEFAULT NULL,
  `ride_end_time` time DEFAULT NULL,
  `nit_start_time` time DEFAULT NULL,
  `nit_end_time` time DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '0-inactive, 1active',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_faredetails`
--

INSERT INTO `wy_faredetails` (`fare_id`, `car_id`, `ride_category`, `booking_type`, `package_days`, `package_id`, `district`, `fare_type`, `min_time`, `min_km`, `min_fare_amount`, `ride_each_km`, `ride_fare`, `fare_percent`, `distance_time`, `distance_fare`, `waiting_time`, `waiting_charge`, `ride_start_time`, `ride_end_time`, `nit_start_time`, `nit_end_time`, `status`, `created_by`, `updated_by`, `created_date`, `updated_date`) VALUES
(1, 1, 1, 1, '0', 0, 1, 1, 0, 1, 40, 1, 8, 0, 1, 1, 1, 1, '00:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 1, 1, '2017-03-09 16:34:12', '2017-03-10 13:01:17'),
(2, 2, 1, 1, '0', 0, 1, 1, 0, 1, 50, 1, 10, 0, 1, 1, 1, 1, '00:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 1, 1, '2017-03-09 16:34:30', '2017-03-10 13:01:00'),
(5, 3, 1, 1, '0', 0, 1, 1, 0, 1, 40, 1, 6, 1, 1, 1, 1, 1, '00:00:00', '23:59:00', '05:30:00', '05:30:00', 1, 1, 1, '2017-03-10 13:00:29', '2017-03-10 13:01:55'),
(6, 1, 1, 1, '0', 0, 1, 4, 0, NULL, NULL, 1, NULL, 1, 1, NULL, 1, NULL, '04:00:00', '07:00:00', '17:00:00', '19:00:00', 1, 1, 1, '2017-03-10 13:03:04', '2017-03-17 10:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `wy_franchise`
--

CREATE TABLE `wy_franchise` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(500) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `landline` varchar(20) NOT NULL,
  `service_tax_image` varchar(100) NOT NULL,
  `service_tax_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `company_address` text NOT NULL,
  `country` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - blocked, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_history`
--

CREATE TABLE `wy_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_of_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_model`
--

CREATE TABLE `wy_model` (
  `id` int(11) NOT NULL,
  `ride_category` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `model` varchar(100) NOT NULL,
  `status` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(5) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_model`
--

INSERT INTO `wy_model` (`id`, `ride_category`, `brand_id`, `model`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 1, 'Alto', 1, '2017-03-09 15:59:04', 1, '2017-03-10 13:15:44', '1'),
(2, 1, 2, 'Liva', 1, '2017-03-09 15:59:15', 1, '2017-03-09 15:59:15', NULL),
(3, 1, 3, 'KWID', 1, '2017-03-10 12:33:27', 1, '2017-03-10 12:33:27', NULL),
(4, 1, 2, 'Go', 1, '2017-03-10 13:12:52', 1, '2017-03-10 13:12:52', NULL),
(5, 1, 6, 'Eon', 1, '2017-03-10 13:13:05', 1, '2017-03-10 13:13:05', NULL),
(6, 1, 1, 'Ritz', 1, '2017-03-10 13:13:25', 1, '2017-03-10 13:13:25', NULL),
(7, 1, 5, 'Micra', 1, '2017-03-10 13:13:49', 1, '2017-03-10 13:13:49', NULL),
(8, 1, 3, 'Indica', 1, '2017-03-10 13:14:02', 1, '2017-03-10 13:14:02', NULL),
(9, 1, 5, 'Sunny', 1, '2017-03-10 13:14:20', 1, '2017-03-10 13:14:20', NULL),
(10, 1, 4, 'Etios', 1, '2017-03-10 13:14:28', 1, '2017-03-10 13:14:28', NULL),
(11, 1, 1, 'Swift', 1, '2017-03-10 13:14:48', 1, '2017-03-16 16:21:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `wy_notification`
--

CREATE TABLE `wy_notification` (
  `id` int(11) NOT NULL,
  `type_of_user` enum('1','2') NOT NULL COMMENT '1-Driver,2-Driver',
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0-Readed ,1-Not Readed',
  `date_of_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_offernotification`
--

CREATE TABLE `wy_offernotification` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `usage_count` int(11) NOT NULL DEFAULT '0',
  `used` int(11) NOT NULL DEFAULT '0' COMMENT '0-not used, 1-used',
  `read_status` int(11) NOT NULL DEFAULT '0' COMMENT '1-yes, 0-no',
  `is_experied` int(11) NOT NULL DEFAULT '0' COMMENT '1-yes, 0-no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_offers`
--

CREATE TABLE `wy_offers` (
  `id` int(11) NOT NULL,
  `coupon_basedon` int(6) NOT NULL COMMENT '1-ride count, 2-ride value, 3-cartype, 4-user count, 5-all, 6-free ride',
  `coupon_typevalue` varchar(20) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `coupon_type` int(11) NOT NULL COMMENT '1-cashback, 2-offers, 3-free ride',
  `coupon_value` varchar(30) NOT NULL,
  `coupon_desc` text NOT NULL,
  `usage_count` int(11) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL DEFAULT '0000-00-00',
  `is_experied` int(11) NOT NULL COMMENT '1-yes, 0-no',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_packagelist`
--

CREATE TABLE `wy_packagelist` (
  `id` int(11) NOT NULL,
  `package_name` varchar(20) NOT NULL,
  `package_type` int(11) NOT NULL COMMENT '1-outstation, 2-rental',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_ratecard`
--

CREATE TABLE `wy_ratecard` (
  `id` int(11) NOT NULL,
  `car_type` varchar(10) NOT NULL,
  `label_key` varchar(20) NOT NULL,
  `rate_details` varchar(200) NOT NULL,
  `value` varchar(50) NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_rating_reasons`
--

CREATE TABLE `wy_rating_reasons` (
  `id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 -Active , 1 -Inactive',
  `created_at` datetime NOT NULL,
  `create_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_rating_reasons`
--

INSERT INTO `wy_rating_reasons` (`id`, `rating`, `reason`, `status`, `created_at`, `create_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'Not Good', 1, '2017-03-10 15:14:30', 1, '2017-03-10 19:05:44', 1),
(2, 2, 'Bad', 1, '2017-03-10 15:14:40', 1, '2017-03-10 19:05:54', 1),
(3, 1, 'Fair Ride', 1, '2017-03-10 15:14:59', 1, '2017-03-10 15:15:09', 1),
(4, 3, 'Fair Ride', 1, '2017-03-10 15:15:29', 1, '2017-03-10 19:06:05', 1),
(5, 4, 'Good', 1, '2017-03-10 15:15:47', 1, '2017-03-10 19:06:15', 1),
(6, 5, 'Thumbs Up', 1, '2017-03-10 15:16:27', 1, '2017-03-10 19:06:25', 1),
(7, 1, 'Verybad', 0, '2017-03-11 12:32:04', 1, '2017-03-11 12:32:04', 1),
(8, 2, 'Not bad.', 0, '2017-03-11 12:32:16', 1, '2017-03-11 12:32:16', 1),
(9, 3, 'Fair Ride', 0, '2017-03-11 12:32:22', 1, '2017-03-11 12:32:22', 1),
(10, 4, 'Very Good Ride', 0, '2017-03-11 12:32:31', 1, '2017-03-11 12:32:31', 1),
(11, 5, 'An Awesome Ride', 0, '2017-03-11 12:32:39', 1, '2017-03-11 12:32:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wy_ride`
--

CREATE TABLE `wy_ride` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(300) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `source_location` text NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(50) NOT NULL,
  `source_lat` varchar(20) NOT NULL,
  `source_lng` varchar(20) NOT NULL,
  `car_type` varchar(20) NOT NULL,
  `ride_type` int(11) NOT NULL COMMENT '1-normal, 2-schedule',
  `ride_category` int(11) NOT NULL,
  `booking_type` int(11) NOT NULL,
  `package_type` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_time` time NOT NULL,
  `coupon` int(11) NOT NULL,
  `date_of_ride` date NOT NULL,
  `ride_status` int(11) NOT NULL COMMENT '1-confrimed, 2-cancele, 3-cancel when no driver accept, 4-endride',
  `cancel_notes` varchar(500) NOT NULL,
  `destination_location` text NOT NULL,
  `destination_lat` varchar(20) NOT NULL,
  `destination_lng` varchar(20) NOT NULL,
  `padi_by` int(11) NOT NULL COMMENT '1-cash,2-wrydes money, 3-both',
  `rideing_time` varchar(50) NOT NULL,
  `distance` varchar(20) NOT NULL,
  `waiting_time` varchar(20) NOT NULL,
  `distance_amount` float NOT NULL,
  `minimum_charge` float NOT NULL,
  `ride_charge` float NOT NULL,
  `waiting_charge` float NOT NULL,
  `total_tax` float NOT NULL,
  `total_amount` float NOT NULL,
  `offer_amount` float NOT NULL,
  `final_amount` float NOT NULL,
  `fare_type` int(1) NOT NULL COMMENT '1-basefare, 2-mor, 3-night, 4-peak, 5-special',
  `fare_id` int(11) NOT NULL,
  `peak_percent` float NOT NULL,
  `peak_amount` float NOT NULL,
  `paid_cash` float NOT NULL,
  `paid_taximoney` float NOT NULL,
  `paid_pos` float NOT NULL,
  `cancle_time` datetime NOT NULL,
  `booked_through` int(11) NOT NULL DEFAULT '1' COMMENT '1-app, 2-through admin, 3-thru site',
  `booked_by` int(11) NOT NULL,
  `company_share` float NOT NULL,
  `driver_share` float NOT NULL,
  `franchise_share` float NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `ride_endby` int(11) NOT NULL COMMENT '1-online, 2- offline',
  `deny_count` int(11) NOT NULL DEFAULT '0',
  `crn` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_ride`
--

INSERT INTO `wy_ride` (`id`, `reference_id`, `customer_id`, `source_location`, `city`, `district`, `source_lat`, `source_lng`, `car_type`, `ride_type`, `ride_category`, `booking_type`, `package_type`, `schedule_date`, `schedule_time`, `coupon`, `date_of_ride`, `ride_status`, `cancel_notes`, `destination_location`, `destination_lat`, `destination_lng`, `padi_by`, `rideing_time`, `distance`, `waiting_time`, `distance_amount`, `minimum_charge`, `ride_charge`, `waiting_charge`, `total_tax`, `total_amount`, `offer_amount`, `final_amount`, `fare_type`, `fare_id`, `peak_percent`, `peak_amount`, `paid_cash`, `paid_taximoney`, `paid_pos`, `cancle_time`, `booked_through`, `booked_by`, `company_share`, `driver_share`, `franchise_share`, `coupon_code`, `offer_id`, `ride_endby`, `deny_count`, `crn`, `created_at`, `updated_at`) VALUES
(1, 'WYDSTXCBE001', 1, '11, Patel Road, Ram Nagar,Coimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016616', '76.957509', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-14', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-14 10:11:22', '2017-03-14 10:11:22'),
(2, 'WYDSTXCBE002', 1, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016663', '76.957557', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-14', 4, '', '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', '11.01602', '76.95735', 3, '0 min', '0.0 km', '0 min', 0, 50, 0, 0, 2, 52, 0, 52, 0, 2, 0, 0, 52, 0, 0, '0000-00-00 00:00:00', 1, 0, 15.6, 36.4, 0, '', 0, 1, 0, 0, '2017-03-14 11:48:11', '2017-03-14 11:48:52'),
(3, 'WYDSTXCBE003', 1, '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016020', '76.957350', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '0 min', '0.0 km', '0 min', 0, 50, 0, 0, 2, 52, 0, 52, 0, 2, 0, 0, 52, 0, 0, '0000-00-00 00:00:00', 1, 0, 15.6, 36.4, 0, '', 0, 1, 0, 0, '2017-03-16 11:46:27', '2017-03-16 11:46:58'),
(4, 'WYDSTXCBE004', 5, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016551290765154', '76.9575222954154', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165714', '76.9575097', 1, '1 Min', '0.0 Km', '1 Min', 0, 40, 1, 1, 1, 43, 0, 43, 0, 1, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-16 16:08:33', '2017-03-16 16:22:40'),
(5, 'WYDSTXCBE005', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016589', '76.957567', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.01657', '76.957555', 3, '7 mins', '0.0 km', '0 min', 0, 50, 7, 0, 2, 59, 0, 59, 0, 2, 0, 0, 59, 0, 0, '0000-00-00 00:00:00', 1, 0, 17.7, 41.3, 0, '', 0, 1, 0, 0, '2017-03-16 16:48:45', '2017-03-16 16:57:30'),
(6, 'WYDSTXCBE006', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016568732940932', '76.95753805339336', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', '11, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0166067', '76.9574183', 1, '0 Min', '0.0 Km', '2 Mins', 0, 40, 0, 2, 1, 43, 0, 43, 0, 1, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-16 16:52:27', '2017-03-16 16:55:08'),
(7, 'WYDSTXCBE007', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01656972023387', '76.95753771811724', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', 'Corporation Complex\n49, Patel Road\nRajammal Layout, Ram Nagar\nCoimbatore, Tamil Nadu 641009', '11.0163567', '76.9574167', 1, '1 Min', '0.3 Km', '1 Min', 0, 40, 1, 1, 1, 43, 0, 43, 0, 1, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-16 16:55:17', '2017-03-16 16:58:22'),
(8, 'WYDSTXCBE008', 7, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.018696', '76.958672', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'Xvnsvn', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 16:58:41', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 16:58:29', '2017-03-16 16:58:29'),
(9, 'WYDSTXCBE009', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016590', '76.957556', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'Noun', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 16:59:55', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 16:59:47', '2017-03-16 16:59:47'),
(10, 'WYDSTXCBE0010', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016590', '76.957556', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'Fg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 17:01:20', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 17:00:54', '2017-03-16 17:01:04'),
(11, 'WYDSTXCBE0011', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016579', '76.957536', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'It\'d', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 17:10:38', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 17:10:29', '2017-03-16 17:10:29'),
(12, 'WYDSTXCBE0012', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016586', '76.957537', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'Ghj', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 17:31:27', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 17:30:59', '2017-03-16 17:31:17'),
(13, 'WYDSTXCBE0013', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016558', '76.957508', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'Chcgfh', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 17:43:32', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 17:43:08', '2017-03-16 17:43:08'),
(14, 'WYDSTXCBE0014', 7, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016591', '76.957560', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 17:44:29', '2017-03-16 17:44:29'),
(15, 'WYDSTXCBE0015', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016555898132442', '76.9575571641326', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'yest', '10, Kalyana Sundaram Streetperiyar nagar, Chitra Nagar, Hope College, PeelameduCoimbatore, Tamil Nadu 641004', '11.023810800000001', '77.0197426', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 18:06:03', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 18:05:48', '2017-03-16 18:05:48'),
(16, 'WYDSTXCBE0016', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01654207603035', '76.95756118744612', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 2, 'test', '10, Kalyana Sundaram Streetperiyar nagar, Chitra Nagar, Hope College, PeelameduCoimbatore, Tamil Nadu 641004', '11.023810800000001', '77.0197426', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-16 18:07:46', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-16 18:07:36', '2017-03-16 18:07:36'),
(17, 'WYDSTXCBE0017', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016823454409076', '76.9574847444892', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165717', '76.9575033', 1, '1 Min', '0.1 Km', '2 Mins', 0, 40, 1, 2, 1, 44, 0, 44, 4, 1, 1, 0, 44, 0, 0, '0000-00-00 00:00:00', 1, 0, 13.2, 30.8, 0, '', 0, 1, 0, 0, '2017-03-16 18:08:32', '2017-03-16 18:14:54'),
(18, 'WYDSTXCBE0018', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016530228513766', '76.9575548171997', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-16', 4, '', '22, Doctor Krishnasamy Mudaliyar Road\nNear Broke Field Road, Sukrawar Pettai, R.S. Puram\nCoimbatore, Tamil Nadu 641002', '11.0067122', '76.9607109', 1, '3 Mins', '1.7 Km', '0 Min', 6, 40, 3, 0, 1, 51, 0, 51, 4, 1, 1, 1, 51, 0, 0, '0000-00-00 00:00:00', 1, 0, 15.3, 35.7, 0, '', 0, 2, 0, 0, '2017-03-16 18:15:01', '2017-03-16 18:19:51'),
(19, 'WYDSTXCBE0019', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-17', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165343', '76.9575433', 1, '13 Mins', '0.2 Km', '1 Min', 0, 40, 13, 1, 2, 56, 0, 56, 0, 5, 0, 0, 56, 0, 0, '0000-00-00 00:00:00', 1, 0, 16.8, 39.2, 0, '', 0, 1, 0, 0, '2017-03-17 11:33:04', '2017-03-17 11:57:46'),
(20, 'WYDSTXCBE0020', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.1459499504437', '72.7730868011713', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-17', 4, '', 'G-55, Vesu Main Road\nVesu\nSurat, Gujarat 395007', '21.1450617', '72.7734954', 1, '1 Min', '0.0 Km', '0 Min', 0, 40, 1, 0, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 0, '2017-03-17 13:09:17', '2017-03-17 13:32:25'),
(21, 'WYDSTXCBE0021', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.14460001774652', '72.7740765362978', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-17', 4, '', 'G-55, Vesu Main Road\nVesu\nSurat, Gujarat 395007', '21.1452622', '72.773337', 1, '1 Min', '0.0 Km', '1 Min', 0, 40, 1, 1, 1, 43, 0, 43, 0, 5, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-17 13:32:42', '2017-03-20 12:45:54'),
(22, 'WYDSTXCBE0022', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016588', '76.957527', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016576', '76.957529', 3, '0 min', '0.0 km', '9 mins', 0, 40, 0, 9, 1, 50, 0, 50, 0, 5, 0, 0, 50, 0, 0, '0000-00-00 00:00:00', 1, 0, 15, 35, 0, '', 0, 1, 0, 0, '2017-03-18 11:04:45', '2017-03-18 11:40:13'),
(23, 'WYDSTXCBE0023', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016579', '76.957555', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016586', '76.957534', 3, '0 min', '0.0 km', '2 mins', 0, 40, 0, 2, 1, 43, 0, 43, 0, 5, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-18 11:52:38', '2017-03-18 11:59:49'),
(24, 'WYDSTXCBE0024', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016579', '76.957555', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 2, 'Fggg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-18 12:00:26', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-18 12:00:03', '2017-03-18 12:00:03'),
(25, 'WYDSTXCBE0025', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016579', '76.957555', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016586', '76.957534', 3, '0 min', '0.0 km', '1 min', 0, 40, 0, 1, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 0, '2017-03-18 12:13:13', '2017-03-18 15:15:34'),
(26, 'WYDSTXCBE0026', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016587', '76.957557', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016588', '76.957538', 3, '1 min', '0.0 km', '0 min', 0, 40, 1, 0, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 0, '2017-03-18 12:25:20', '2017-03-18 12:39:13'),
(27, 'WYDSTXCBE0027', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016571', '76.957553', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016498', '76.957556', 3, '2 mins', '0.0 km', '0 min', 0, 40, 2, 0, 1, 43, 0, 43, 0, 5, 0, 0, 43, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.9, 30.1, 0, '', 0, 1, 0, 0, '2017-03-18 15:15:44', '2017-03-18 15:22:32'),
(28, 'WYDSTXCBE0028', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016550', '76.957597', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.01654', '76.957535', 3, '10 mins', '0.0 km', '1 min', 0, 40, 10, 1, 2, 53, 0, 53, 0, 5, 0, 0, 53, 0, 0, '0000-00-00 00:00:00', 1, 0, 15.9, 37.1, 0, '', 0, 1, 0, 0, '2017-03-18 15:24:20', '2017-03-18 16:12:50'),
(29, 'WYDSTXCBE0029', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016589', '76.957553', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 4, '', '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', '11.016587', '76.957546', 3, '0 min', '0.0 km', '25 mins', 0, 40, 0, 25, 2, 67, 0, 67, 0, 5, 0, 0, 67, 0, 0, '0000-00-00 00:00:00', 1, 0, 20.1, 46.9, 0, '', 0, 1, 0, 0, '2017-03-18 16:14:56', '2017-03-18 16:41:42'),
(30, 'WYDSTXCBE0030', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016523', '76.957511', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 2, 'Test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-18 16:55:50', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-18 16:41:47', '2017-03-18 16:41:49'),
(31, 'WYDSTXCBE0031', 8, '597, Cross Cut Road, Near North Fly Over, Gandipuram,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016587', '76.957554', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-18', 2, ' J HC', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-18 16:56:06', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-18 16:55:59', '2017-03-18 16:56:01'),
(32, 'WYDSTXCBE0032', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-20', 2, 'Test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-20 15:07:41', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-20 15:06:24', '2017-03-20 15:06:37'),
(33, 'WYDSTXCBE0033', 1, '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016020', '76.957350', '2', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-20', 2, 'Test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-20 15:09:52', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-20 15:09:29', '2017-03-20 15:09:35'),
(34, 'WYDSTXCBE0034', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.14539303043016', '72.77307037264109', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-20', 2, 'ggg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 18:38:01', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-20 15:52:57', '2017-03-20 15:53:22'),
(35, 'WYDSTXCBE0035', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'ct', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 12:58:50', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-21 12:57:44', '2017-03-21 12:57:49'),
(36, 'WYDSTXCBE0036', 7, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016600326313288', '76.95742908865213', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165841', '76.95756', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 0, '2017-03-21 13:02:04', '2017-03-21 13:02:35'),
(37, 'WYDSTXCBE0037', 1, '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016020', '76.957350', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'Cancel', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 15:27:44', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '2017-03-21 13:04:41', '2017-03-21 13:04:48'),
(38, 'WYDSTXCBE0038', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '1, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0166579', '76.9575097', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 0, '2017-03-21 13:45:43', '2017-03-21 13:47:03'),
(39, 'WYDSTXCBE0039', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165037', '76.9574973', 1, '2 Mins', '0.0 Km', '1 Min', 0, 40, 2, 1, 1, 44, 0, 44, 0, 5, 0, 0, 44, 0, 0, '0000-00-00 00:00:00', 1, 0, 13.2, 30.8, 0, '', 0, 1, 0, 0, '2017-03-21 14:38:29', '2017-03-21 14:44:54'),
(40, 'WYDSTXCBE0040', 8, 'Thiruvenkadam StreetSukrawar Pettai, RanganathapuramCoimbatore, Tamil Nadu 641002', 'Coimbatore', '', '11.014921265511807', '76.95587676018478', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.016528', '76.957507', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 0, '2017-03-21 15:05:17', '2017-03-21 15:06:24'),
(41, 'WYDSTXCBE0041', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165255', '76.9575092', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 0, '2017-03-21 15:14:29', '2017-03-21 15:16:30'),
(42, 'WYDSTXCBE0042', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165705', '76.9575484', 1, '4 Mins', '0.0 Km', '2 Mins', 0, 40, 4, 2, 1, 47, 0, 47, 0, 5, 0, 0, 47, 0, 0, '0000-00-00 00:00:00', 1, 0, 14.1, 32.9, 0, '', 0, 1, 0, 5669, '2017-03-21 15:30:07', '2017-03-21 15:45:00'),
(43, 'WYDSTXCBE0043', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', '11.01602', '76.95735', 3, '1 min', '0.0 km', '0 min', 0, 40, 1, 0, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 7328, '2017-03-21 15:30:42', '2017-03-21 15:33:48'),
(44, 'WYDSTXCBE0044', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165505', '76.9575069', 1, '11 Mins', '0.1 Km', '0 Min', 0, 40, 11, 0, 2, 53, 0, 53, 0, 5, 0, 0, 53, 0, 0, '0000-00-00 00:00:00', 1, 0, 15.9, 37.1, 0, '', 0, 1, 0, 2968, '2017-03-21 15:45:07', '2017-03-21 15:56:55'),
(45, 'WYDSTXCBE0045', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016536810467487', '76.95757661014795', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 16:59:59', 1, 0, 0, 0, 0, '', 0, 0, 0, 1863, '2017-03-21 16:59:30', '2017-03-21 16:59:33'),
(46, 'WYDSTXCBE0046', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '22, Doctor Krishnasamy Mudaliyar Road\nNear Broke Field Road, Sukrawar Pettai, R.S. Puram\nCoimbatore, Tamil Nadu 641002', '11.0061086', '76.9611609', 1, '2 Mins', '1.8 Km', '0 Min', 4.8, 40, 2, 0, 1, 48, 0, 48, 0, 5, 0, 0, 48, 0, 0, '0000-00-00 00:00:00', 1, 0, 14.4, 33.6, 0, '', 0, 1, 0, 5803, '2017-03-21 17:09:19', '2017-03-21 17:13:12'),
(47, 'WYDSTXCBE0047', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016661209365056', '76.957584656775', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '374, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0112573', '76.9595483', 1, '1 Min', '0.9 Km', '0 Min', 0, 40, 1, 0, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 5803, '2017-03-21 17:30:51', '2017-03-21 17:36:08'),
(48, 'WYDSTXCBE0048', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016586833310939', '76.95760007947683', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'rted', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 17:45:53', 1, 0, 0, 0, 0, '', 0, 0, 0, 3402, '2017-03-21 17:41:37', '2017-03-21 17:42:35'),
(49, 'WYDSTXCBE0049', 2, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016661867560147', '76.9575997442007', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0166754', '76.9575879', 1, '0 Min', '0.0 Km', '1 Min', 0, 40, 0, 1, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 5317, '2017-03-21 17:48:00', '2017-03-21 17:51:27'),
(50, 'WYDSTXCBE0050', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'vgg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 18:10:32', 1, 0, 0, 0, 0, '', 0, 0, 0, 3266, '2017-03-21 18:10:20', '2017-03-21 18:10:25'),
(51, 'WYDSTXCBE0051', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01658617511568', '76.95754241198301', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165727', '76.9575319', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 3740, '2017-03-21 18:24:53', '2017-03-21 18:25:36'),
(52, 'WYDSTXCBE0052', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '11, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0166339', '76.9575125', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 4454, '2017-03-21 18:26:34', '2017-03-21 18:27:24'),
(53, 'WYDSTXCBE0053', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016579264065438', '76.95754911750555', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 18:28:44', 1, 0, 0, 0, 0, '', 0, 0, 0, 6178, '2017-03-21 18:28:23', '2017-03-21 18:28:23'),
(54, 'WYDSTXCBE0054', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016577947674882', '76.95754442363977', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 2, 'test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-21 18:29:23', 1, 0, 0, 0, 0, '', 0, 0, 0, 6756, '2017-03-21 18:28:57', '2017-03-21 18:28:57'),
(55, 'WYDSTXCBE0055', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016618426681358', '76.95752263069153', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-21', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0166201', '76.9575309', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 2151, '2017-03-21 18:29:29', '2017-03-21 18:34:40'),
(58, 'WYDSTXCBE0058', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016703004750061', '76.9575548171997', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165608', '76.9575488', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 1868, '2017-03-22 10:58:36', '2017-03-22 11:04:10'),
(57, 'WYDSTXCBE0056', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 8556, '2017-03-22 10:27:13', '2017-03-22 10:27:13'),
(59, 'WYDSTXCBE0059', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 11:10:39', 1, 0, 0, 0, 0, '', 0, 0, 0, 5197, '2017-03-22 11:10:19', '2017-03-22 11:10:23'),
(60, 'WYDSTXCBE0060', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016585187822793', '76.95753335952759', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'test', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 11:15:12', 1, 0, 0, 0, 0, '', 0, 0, 0, 1654, '2017-03-22 11:14:41', '2017-03-22 11:14:41'),
(61, 'WYDSTXCBE0061', 8, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016615135705427', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'tesr', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 11:31:38', 1, 0, 0, 0, 0, '', 0, 0, 0, 9753, '2017-03-22 11:29:30', '2017-03-22 11:30:49'),
(62, 'WYDSTXCBE0062', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016648374560608', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 9180, '2017-03-22 11:35:28', '2017-03-22 11:35:28'),
(63, 'WYDSTXCBE0063', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 1456, '2017-03-22 11:39:37', '2017-03-22 11:39:37'),
(64, 'WYDSTXCBE0064', 8, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.016604', '76.957537', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 1146, '2017-03-22 11:41:15', '2017-03-22 11:42:58'),
(65, 'WYDSTXCBE0065', 8, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016614477510233', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 4276, '2017-03-22 11:54:33', '2017-03-22 11:54:33'),
(66, 'WYDSTXCBE0066', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.01657136572209', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165714', '76.9575097', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 7996, '2017-03-22 12:00:23', '2017-03-22 12:01:01'),
(67, 'WYDSTXCBE0067', 8, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016526608439161', '76.95745322853327', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'hxh', '65, AgraharamKannappa Nagar, Agraharam, SinganallurCoimbatore, Tamil Nadu 641005', '10.998735100000001', '77.0319784', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 12:05:15', 1, 0, 0, 0, 0, '', 0, 0, 0, 8940, '2017-03-22 12:05:05', '2017-03-22 12:05:05'),
(68, 'WYDSTXCBE0068', 8, '448, Patel Road MainRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.015729861844553', '76.95666834712029', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'cbxh', 'Thiruvenkadam StreetSukrawar Pettai, RanganathapuramCoimbatore, Tamil Nadu 641002', '11.014921265511807', '76.95587676018478', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 12:05:36', 1, 0, 0, 0, 0, '', 0, 0, 0, 7497, '2017-03-22 12:05:30', '2017-03-22 12:05:30'),
(69, 'WYDSTXCBE0069', 8, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016640805316685', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'cbbx', '65, AgraharamKannappa Nagar, Agraharam, SinganallurCoimbatore, Tamil Nadu 641005', '10.998735100000001', '77.0319784', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 12:05:52', 1, 0, 0, 0, 0, '', 0, 0, 0, 7899, '2017-03-22 12:05:46', '2017-03-22 12:05:46'),
(70, 'WYDSTXCBE0070', 8, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016634552462857', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'cg', '65, AgraharamKannappa Nagar, Agraharam, SinganallurCoimbatore, Tamil Nadu 641005', '10.998735100000001', '77.0319784', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 12:06:48', 1, 0, 0, 0, 0, '', 0, 0, 0, 5250, '2017-03-22 12:06:33', '2017-03-22 12:06:40'),
(71, 'WYDSTXCBE0071', 7, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016597035337163', '76.9574324414134', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'hg', '10, Kalyana Sundaram Streetperiyar nagar, Chitra Nagar, Hope College, PeelameduCoimbatore, Tamil Nadu 641004', '11.023810800000001', '77.0197426', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 12:41:14', 1, 0, 0, 0, 0, '', 0, 0, 0, 8194, '2017-03-22 12:34:09', '2017-03-22 12:35:04'),
(72, 'WYDSTXCBE0072', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016516735508198', '76.95753369480371', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165202', '76.9575355', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 3692, '2017-03-22 12:45:06', '2017-03-22 12:45:37'),
(73, 'WYDSTXCBE0073', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'stk', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 13:09:09', 1, 0, 0, 0, 0, '', 0, 0, 0, 9633, '2017-03-22 13:08:38', '2017-03-22 13:08:52'),
(74, 'WYDSTXCBE0074', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '11, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.016599', '76.9574999', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 7592, '2017-03-22 13:14:14', '2017-03-22 13:14:51'),
(75, 'WYDSTXCBE0075', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'ghy', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 13:16:19', 1, 0, 0, 0, 0, '', 0, 0, 0, 3742, '2017-03-22 13:15:54', '2017-03-22 13:16:11'),
(76, 'WYDSTXCBE0076', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'sg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 13:25:19', 1, 0, 0, 0, 0, '', 0, 0, 0, 6617, '2017-03-22 13:24:38', '2017-03-22 13:25:11'),
(77, 'WYDSTXCBE0077', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 3200, '2017-03-22 13:26:02', '2017-03-22 13:26:02'),
(78, 'WYDSTXCBE0078', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 8375, '2017-03-22 13:28:32', '2017-03-22 13:28:32'),
(79, 'WYDSTXCBE0079', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'CN', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 13:40:51', 1, 0, 0, 0, 0, '', 0, 0, 0, 5252, '2017-03-22 13:40:33', '2017-03-22 13:40:40'),
(80, 'WYDSTXCBE0080', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 6851, '2017-03-22 14:10:33', '2017-03-22 14:10:33'),
(81, 'WYDSTXCBE0081', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016553923546464', '76.95755012333392', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'hd', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 14:15:22', 1, 0, 0, 0, 0, '', 0, 0, 0, 3116, '2017-03-22 14:14:54', '2017-03-22 14:15:15'),
(82, 'WYDSTXCBE0082', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016657918389607', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '11, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0165573', '76.9574916', 1, '0 Min', '0.0 Km', '1 Min', 0, 40, 0, 1, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 3188, '2017-03-22 14:30:48', '2017-03-22 14:32:47'),
(83, 'WYDSTXCBE0083', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016490736788382', '76.95757895708083', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'chh', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 16:27:21', 1, 0, 0, 0, 0, '', 0, 0, 0, 4163, '2017-03-22 16:26:52', '2017-03-22 16:27:01'),
(84, 'WYDSTXCBE0084', 7, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016596377141932', '76.9574760273099', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'hhf', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 16:38:54', 1, 0, 0, 0, 0, '', 0, 0, 0, 6341, '2017-03-22 16:37:22', '2017-03-22 16:38:38'),
(85, 'WYDSTXCBE0085', 7, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016593744361003', '76.95747770369053', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 7778, '2017-03-22 16:40:27', '2017-03-22 16:40:27'),
(86, 'WYDSTXCBE0086', 7, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.01658387143228', '76.95749212056398', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '11, Patel Road\nRam Nagar\nCoimbatore, Tamil Nadu 641009', '11.0165875', '76.9574893', 1, '0 Min', '0.0 Km', '1 Min', 0, 40, 0, 1, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 3445, '2017-03-22 16:52:04', '2017-03-22 16:53:59'),
(87, 'WYDSTXCBE0087', 8, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016684904387205', '76.95761047303675', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 4, '', '597, Cross Cut Road\nNear North Fly Over, Gandipuram\nCoimbatore, Tamil Nadu 641012', '11.0165714', '76.9575097', 1, '0 Min', '0.0 Km', '0 Min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 2428, '2017-03-22 18:13:20', '2017-03-22 18:15:31'),
(88, 'WYDSTXCBE0088', 2, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016675689656603', '76.9575172662735', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 7976, '2017-03-22 18:31:41', '2017-03-22 18:31:41'),
(89, 'WYDSTXCBE0089', 2, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016665816730624', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 2, 'NFF jg', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-22 18:39:51', 1, 0, 0, 0, 0, '', 0, 0, 0, 3939, '2017-03-22 18:39:35', '2017-03-22 18:39:35'),
(90, 'WYDSTXCBE0090', 2, '11, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016570378429165', '76.95742540061472', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 6696, '2017-03-22 18:40:05', '2017-03-22 18:40:05'),
(91, 'WYDSTXCBE0091', 2, '49-51, Patel RoadRanganathapuramCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.015425445496481', '76.95789746940136', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-22', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 2028, '2017-03-22 18:42:48', '2017-03-22 18:42:48'),
(92, 'WYDSTXCBE0092', 7, '597, Cross Cut RoadNear North Fly Over, GandipuramCoimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.016539114151259', '76.95750016719103', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 2, 'nf', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-23 12:39:05', 1, 0, 0, 0, 0, '', 0, 0, 0, 2141, '2017-03-23 12:38:45', '2017-03-23 12:38:45'),
(93, 'WYDSTXCBE0093', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.14539303043016', '72.77307037264109', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 5113, '2017-03-23 15:48:50', '2017-03-23 15:48:50'),
(94, 'WYDSTXCBE0094', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.145310789958756', '72.77357831597328', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 2970, '2017-03-23 15:55:05', '2017-03-23 15:55:05'),
(95, 'WYDSTXCBE0095', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016665816730624', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 8377, '2017-03-23 16:24:10', '2017-03-23 16:24:10'),
(96, 'WYDSTXCBE0096', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016665816730624', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 6721, '2017-03-23 16:32:44', '2017-03-23 16:32:44'),
(97, 'WYDSTXCBE0097', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016665816730624', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 2, 'bhj', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2017-03-23 16:34:59', 1, 0, 0, 0, 0, '', 0, 0, 0, 9285, '2017-03-23 16:34:06', '2017-03-23 16:34:33'),
(98, 'WYDSTXCBE0098', 7, '1, Patel RoadRam NagarCoimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016665816730624', '76.95750955492258', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 3, '', '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 3459, '2017-03-23 16:35:14', '2017-03-23 16:35:14'),
(99, 'WYDSTXCBE0099', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.145431492688182', '72.77343984693287', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 4, '', 'G-55, Vesu Main Road\nVesu\nSurat, Gujarat 395007', '21.1453654', '72.7733665', 1, '0 Min', '0.0 Km', '1 Min', 0, 40, 0, 1, 1, 42, 0, 42, 4, 1, 1, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 8113, '2017-03-23 17:00:21', '2017-03-23 17:32:34'),
(100, 'WYDSTXCBE00100', 2, 'G-55, Vesu Main RoadVesuSurat, Gujarat 395007', 'Surat', '', '21.145452443670063', '72.77320884168148', '1', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-23', 4, '', 'G-55, Vesu Main Road\nVesu\nSurat, Gujarat 395007', '21.1453369', '72.7734311', 1, '0 Min', '0.9 Km', '6 Mins', 0, 40, 0, 6, 1, 47, 0, 47, 4, 1, 1, 0, 47, 0, 0, '0000-00-00 00:00:00', 1, 0, 14.1, 32.9, 0, '', 0, 1, 0, 4751, '2017-03-23 17:57:36', '2017-03-23 18:05:34'),
(101, 'WYDSTXCBE00101', 1, '49-51, Patel Road, Ranganathapuram,Coimbatore, Tamil Nadu 641009', 'Coimbatore', '', '11.016020', '76.957350', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-24', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '0 min', '0.0 km', '0 min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 8596, '2017-03-24 10:53:56', '2017-03-24 10:54:58'),
(102, 'WYDSTXCBE00102', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-24', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '0 min', '0.0 km', '0 min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 9248, '2017-03-24 11:09:51', '2017-03-24 11:13:33'),
(103, 'WYDSTXCBE00103', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-24', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '0 min', '0.0 km', '0 min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 8953, '2017-03-24 11:14:06', '2017-03-24 11:46:48'),
(104, 'WYDSTXCBE00104', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-24', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '1 min', '0.0 km', '0 min', 0, 40, 1, 0, 1, 42, 0, 42, 0, 5, 0, 0, 42, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.6, 29.4, 0, '', 0, 1, 0, 1731, '2017-03-24 11:47:04', '2017-03-24 12:02:13'),
(105, 'WYDSTXCBE00105', 1, 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', 'Coimbatore', '', '11.019081', '76.958545', '3', 1, 1, 1, 0, '0000-00-00', '00:00:00', 0, '2017-03-24', 4, '', 'Power House Road, Tatabad, Chinniyampalayam, Tatabad,Coimbatore, Tamil Nadu 641012', '11.019082', '76.958545', 3, '0 min', '0.0 km', '0 min', 0, 40, 0, 0, 1, 41, 0, 41, 0, 5, 0, 0, 41, 0, 0, '0000-00-00 00:00:00', 1, 0, 12.3, 28.7, 0, '', 0, 1, 0, 6628, '2017-03-24 12:02:28', '2017-03-24 12:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `wy_ridecancel_reason`
--

CREATE TABLE `wy_ridecancel_reason` (
  `id` int(11) NOT NULL,
  `ride_cancel_reason` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_ridedetails`
--

CREATE TABLE `wy_ridedetails` (
  `id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `accept_status` int(11) NOT NULL COMMENT '1-accept, 2- denied,3-cancel by driver',
  `accept_time` datetime NOT NULL,
  `ride_status` int(11) NOT NULL COMMENT '1-accpet,2-reachedpickup, 3-in ride,4- end ride,5-cancel by customer',
  `cancel_notes` varchar(500) NOT NULL,
  `reach_pickuploc_time` datetime NOT NULL,
  `start_ride_time` datetime NOT NULL,
  `end_ride_time` datetime NOT NULL,
  `cancel_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_ridedetails`
--

INSERT INTO `wy_ridedetails` (`id`, `ride_id`, `driver_id`, `accept_status`, `accept_time`, `ride_status`, `cancel_notes`, `reach_pickuploc_time`, `start_ride_time`, `end_ride_time`, `cancel_time`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, '0000-00-00 00:00:00', 4, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-14 10:11:22', '2017-03-14 10:11:22'),
(2, 2, 3, 1, '2017-03-14 11:48:14', 4, '', '2017-03-14 11:48:24', '2017-03-14 11:48:33', '2017-03-14 11:48:36', '0000-00-00 00:00:00', '2017-03-14 11:48:11', '2017-03-14 11:48:33'),
(3, 3, 3, 1, '2017-03-16 11:46:32', 4, '', '2017-03-16 11:46:36', '2017-03-16 11:46:51', '2017-03-16 11:46:54', '0000-00-00 00:00:00', '2017-03-16 11:46:27', '2017-03-16 11:46:51'),
(4, 4, 2, 1, '2017-03-16 16:08:36', 4, '', '2017-03-16 16:19:46', '2017-03-16 16:21:06', '2017-03-16 16:22:08', '0000-00-00 00:00:00', '2017-03-16 16:08:33', '2017-03-16 16:21:06'),
(5, 5, 4, 1, '2017-03-16 16:48:56', 4, '', '2017-03-16 16:49:04', '2017-03-16 16:50:07', '2017-03-16 16:57:21', '0000-00-00 00:00:00', '2017-03-16 16:48:45', '2017-03-16 16:50:07'),
(6, 6, 2, 1, '2017-03-16 16:52:31', 4, '', '2017-03-16 16:52:38', '2017-03-16 16:54:14', '2017-03-16 16:54:56', '0000-00-00 00:00:00', '2017-03-16 16:52:27', '2017-03-16 16:54:14'),
(7, 7, 2, 1, '2017-03-16 16:55:21', 4, '', '2017-03-16 16:55:30', '2017-03-16 16:56:44', '2017-03-16 16:58:16', '0000-00-00 00:00:00', '2017-03-16 16:55:17', '2017-03-16 16:56:44'),
(8, 8, 3, 0, '0000-00-00 00:00:00', 5, 'Xvnsvn', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 16:58:41', '2017-03-16 16:58:29', '2017-03-16 16:58:29'),
(9, 9, 3, 0, '0000-00-00 00:00:00', 5, 'Noun', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 16:59:55', '2017-03-16 16:59:47', '2017-03-16 16:59:47'),
(10, 10, 4, 3, '2017-03-16 17:01:04', 2, 'Fg', '2017-03-16 17:01:10', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 17:01:20', '2017-03-16 17:00:54', '2017-03-16 17:01:10'),
(11, 11, 4, 0, '0000-00-00 00:00:00', 5, 'It\'d', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 17:10:38', '2017-03-16 17:10:29', '2017-03-16 17:10:29'),
(12, 12, 4, 1, '2017-03-16 17:31:17', 5, 'Ghj', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 17:31:27', '2017-03-16 17:30:59', '2017-03-16 17:31:17'),
(13, 13, 4, 0, '0000-00-00 00:00:00', 5, 'Chcgfh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 17:43:32', '2017-03-16 17:43:08', '2017-03-16 17:43:08'),
(14, 14, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 17:44:29', '2017-03-16 17:44:29'),
(15, 15, 4, 0, '0000-00-00 00:00:00', 5, 'yest', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 18:06:03', '2017-03-16 18:05:48', '2017-03-16 18:05:48'),
(16, 16, 2, 0, '0000-00-00 00:00:00', 5, 'test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-16 18:07:46', '2017-03-16 18:07:36', '2017-03-16 18:07:36'),
(17, 17, 2, 1, '2017-03-16 18:08:36', 4, '', '2017-03-16 18:08:41', '2017-03-16 18:10:10', '2017-03-16 18:12:43', '0000-00-00 00:00:00', '2017-03-16 18:08:32', '2017-03-16 18:10:10'),
(18, 18, 2, 1, '2017-03-16 18:15:05', 4, '', '2017-03-16 18:15:10', '2017-03-16 18:16:20', '2017-03-16 18:19:51', '0000-00-00 00:00:00', '2017-03-16 18:15:01', '2017-03-16 18:16:20'),
(19, 19, 4, 1, '2017-03-17 11:33:12', 4, '', '2017-03-17 11:33:28', '2017-03-17 11:36:35', '2017-03-17 11:49:50', '0000-00-00 00:00:00', '2017-03-17 11:33:04', '2017-03-17 11:36:35'),
(20, 20, 1, 1, '2017-03-17 13:09:37', 4, '', '2017-03-17 13:10:14', '2017-03-17 13:10:34', '2017-03-17 13:11:48', '0000-00-00 00:00:00', '2017-03-17 13:09:17', '2017-03-17 13:10:34'),
(21, 21, 1, 1, '2017-03-17 13:33:00', 4, '', '2017-03-17 13:34:58', '2017-03-17 13:36:22', '2017-03-17 13:37:25', '0000-00-00 00:00:00', '2017-03-17 13:32:42', '2017-03-17 13:36:22'),
(22, 22, 4, 1, '2017-03-18 11:04:51', 4, '', '2017-03-18 11:04:59', '2017-03-18 11:11:59', '2017-03-18 11:17:28', '0000-00-00 00:00:00', '2017-03-18 11:04:45', '2017-03-18 11:11:59'),
(23, 23, 4, 1, '2017-03-18 11:52:42', 4, '', '2017-03-18 11:56:35', '2017-03-18 11:59:11', '2017-03-18 11:59:40', '0000-00-00 00:00:00', '2017-03-18 11:52:38', '2017-03-18 11:59:11'),
(24, 24, 4, 0, '0000-00-00 00:00:00', 5, 'Fggg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-18 12:00:26', '2017-03-18 12:00:03', '2017-03-18 12:00:03'),
(25, 25, 4, 1, '2017-03-18 12:13:16', 4, '', '2017-03-18 12:13:20', '2017-03-18 12:15:02', '2017-03-18 12:20:38', '0000-00-00 00:00:00', '2017-03-18 12:13:13', '2017-03-18 12:15:02'),
(26, 26, 4, 1, '2017-03-18 12:25:29', 4, '', '2017-03-18 12:25:32', '2017-03-18 12:37:50', '2017-03-18 12:39:00', '0000-00-00 00:00:00', '2017-03-18 12:25:20', '2017-03-18 12:37:50'),
(27, 27, 4, 1, '2017-03-18 15:15:47', 4, '', '2017-03-18 15:15:50', '2017-03-18 15:15:58', '2017-03-18 15:18:56', '0000-00-00 00:00:00', '2017-03-18 15:15:44', '2017-03-18 15:15:58'),
(28, 28, 4, 1, '2017-03-18 15:24:23', 4, '', '2017-03-18 15:24:26', '2017-03-18 15:26:28', '2017-03-18 15:37:04', '0000-00-00 00:00:00', '2017-03-18 15:24:20', '2017-03-18 15:26:28'),
(29, 29, 4, 1, '2017-03-18 16:14:59', 4, '', '2017-03-18 16:15:02', '2017-03-18 16:40:29', '2017-03-18 16:41:25', '0000-00-00 00:00:00', '2017-03-18 16:14:56', '2017-03-18 16:40:29'),
(30, 30, 4, 1, '2017-03-18 16:41:49', 5, 'Test', '2017-03-18 16:55:25', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-18 16:55:50', '2017-03-18 16:41:47', '2017-03-18 16:55:25'),
(31, 31, 4, 1, '2017-03-18 16:56:01', 5, ' J HC', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-18 16:56:06', '2017-03-18 16:55:59', '2017-03-18 16:56:01'),
(32, 32, 3, 1, '2017-03-20 15:06:37', 5, 'Test', '2017-03-20 15:06:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-20 15:07:41', '2017-03-20 15:06:24', '2017-03-20 15:06:43'),
(33, 33, 3, 1, '2017-03-20 15:09:35', 5, 'Test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-20 15:09:52', '2017-03-20 15:09:29', '2017-03-20 15:09:35'),
(34, 34, 1, 1, '2017-03-20 15:53:22', 5, 'ggg', '2017-03-20 15:56:40', '2017-03-20 15:56:47', '0000-00-00 00:00:00', '2017-03-22 18:38:01', '2017-03-20 15:52:57', '2017-03-20 15:56:47'),
(35, 35, 6, 1, '2017-03-21 12:57:49', 5, 'ct', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 12:58:50', '2017-03-21 12:57:44', '2017-03-21 12:57:49'),
(36, 36, 6, 1, '2017-03-21 13:02:17', 4, '', '2017-03-21 13:02:20', '2017-03-21 13:02:23', '2017-03-21 13:02:26', '0000-00-00 00:00:00', '2017-03-21 13:02:04', '2017-03-21 13:02:23'),
(37, 37, 5, 1, '2017-03-21 13:04:48', 5, 'Cancel', '2017-03-21 13:04:55', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 15:27:44', '2017-03-21 13:04:41', '2017-03-21 13:04:55'),
(38, 38, 6, 1, '2017-03-21 13:45:57', 4, '', '2017-03-21 13:46:50', '2017-03-21 13:46:55', '2017-03-21 13:46:58', '0000-00-00 00:00:00', '2017-03-21 13:45:43', '2017-03-21 13:46:55'),
(39, 39, 6, 1, '2017-03-21 14:38:34', 4, '', '2017-03-21 14:40:33', '2017-03-21 14:41:42', '2017-03-21 14:44:28', '0000-00-00 00:00:00', '2017-03-21 14:38:29', '2017-03-21 14:41:42'),
(40, 40, 6, 1, '2017-03-21 15:05:27', 4, '', '2017-03-21 15:06:03', '2017-03-21 15:06:11', '2017-03-21 15:06:16', '0000-00-00 00:00:00', '2017-03-21 15:05:17', '2017-03-21 15:06:11'),
(41, 41, 6, 1, '2017-03-21 15:14:33', 4, '', '2017-03-21 15:15:27', '2017-03-21 15:15:30', '2017-03-21 15:15:43', '0000-00-00 00:00:00', '2017-03-21 15:14:29', '2017-03-21 15:15:30'),
(42, 42, 6, 1, '2017-03-21 15:30:30', 4, '', '2017-03-21 15:30:51', '2017-03-21 15:32:08', '2017-03-21 15:38:25', '0000-00-00 00:00:00', '2017-03-21 15:30:07', '2017-03-21 15:32:08'),
(43, 43, 5, 1, '2017-03-21 15:30:55', 4, '', '2017-03-21 15:32:04', '2017-03-21 15:32:19', '2017-03-21 15:33:44', '0000-00-00 00:00:00', '2017-03-21 15:30:42', '2017-03-21 15:32:19'),
(44, 44, 6, 1, '2017-03-21 15:45:13', 4, '', '2017-03-21 15:45:26', '2017-03-21 15:45:33', '2017-03-21 15:56:43', '0000-00-00 00:00:00', '2017-03-21 15:45:07', '2017-03-21 15:45:33'),
(45, 45, 6, 3, '2017-03-21 16:59:33', 2, 'test', '2017-03-21 16:59:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 16:59:59', '2017-03-21 16:59:30', '2017-03-21 16:59:53'),
(46, 46, 6, 1, '2017-03-21 17:09:23', 4, '', '2017-03-21 17:09:26', '2017-03-21 17:09:59', '2017-03-21 17:12:55', '0000-00-00 00:00:00', '2017-03-21 17:09:19', '2017-03-21 17:09:59'),
(47, 47, 6, 1, '2017-03-21 17:31:33', 4, '', '2017-03-21 17:32:28', '2017-03-21 17:33:15', '2017-03-21 17:34:36', '0000-00-00 00:00:00', '2017-03-21 17:30:51', '2017-03-21 17:33:15'),
(48, 48, 6, 1, '2017-03-21 17:42:35', 5, 'rted', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 17:45:53', '2017-03-21 17:41:37', '2017-03-21 17:42:35'),
(49, 49, 6, 1, '2017-03-21 17:48:22', 4, '', '2017-03-21 17:48:53', '2017-03-21 17:50:07', '2017-03-21 17:50:20', '0000-00-00 00:00:00', '2017-03-21 17:48:00', '2017-03-21 17:50:07'),
(50, 50, 2, 1, '2017-03-21 18:10:25', 5, 'vgg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 18:10:32', '2017-03-21 18:10:20', '2017-03-21 18:10:25'),
(51, 51, 4, 1, '2017-03-21 18:25:11', 4, '', '2017-03-21 18:25:19', '2017-03-21 18:25:24', '2017-03-21 18:25:28', '0000-00-00 00:00:00', '2017-03-21 18:24:53', '2017-03-21 18:25:24'),
(52, 52, 4, 1, '2017-03-21 18:26:36', 4, '', '2017-03-21 18:26:40', '2017-03-21 18:27:16', '2017-03-21 18:27:18', '0000-00-00 00:00:00', '2017-03-21 18:26:34', '2017-03-21 18:27:16'),
(53, 53, 4, 0, '0000-00-00 00:00:00', 5, 'test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 18:28:44', '2017-03-21 18:28:23', '2017-03-21 18:28:23'),
(54, 54, 4, 0, '0000-00-00 00:00:00', 5, 'test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-21 18:29:23', '2017-03-21 18:28:57', '2017-03-21 18:28:57'),
(55, 55, 4, 1, '2017-03-21 18:29:32', 4, '', '2017-03-21 18:29:36', '2017-03-21 18:29:50', '2017-03-21 18:29:53', '0000-00-00 00:00:00', '2017-03-21 18:29:29', '2017-03-21 18:29:50'),
(101, 101, 5, 1, '2017-03-24 10:54:11', 4, '', '2017-03-24 10:54:22', '2017-03-24 10:54:36', '2017-03-24 10:54:44', '0000-00-00 00:00:00', '2017-03-24 10:53:56', '2017-03-24 10:54:36'),
(57, 57, 5, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 10:27:13', '2017-03-22 10:28:16'),
(58, 58, 4, 1, '2017-03-22 10:58:48', 4, '', '2017-03-22 11:01:18', '2017-03-22 11:03:58', '2017-03-22 11:04:03', '0000-00-00 00:00:00', '2017-03-22 10:58:36', '2017-03-22 11:03:58'),
(59, 59, 4, 1, '2017-03-22 11:10:23', 5, 'test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:10:39', '2017-03-22 11:10:19', '2017-03-22 11:10:23'),
(60, 60, 4, 0, '0000-00-00 00:00:00', 5, 'test', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:15:12', '2017-03-22 11:14:41', '2017-03-22 11:14:41'),
(61, 61, 4, 3, '2017-03-22 11:30:49', 2, 'tesr', '2017-03-22 11:31:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:31:38', '2017-03-22 11:29:30', '2017-03-22 11:31:01'),
(62, 62, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:35:28', '2017-03-22 11:35:28'),
(63, 63, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:39:37', '2017-03-22 11:39:37'),
(64, 64, 4, 1, '2017-03-22 11:41:30', 4, '', '2017-03-22 11:41:36', '2017-03-22 11:42:41', '2017-03-22 11:42:49', '0000-00-00 00:00:00', '2017-03-22 11:41:15', '2017-03-22 11:42:41'),
(65, 65, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 11:54:33', '2017-03-22 11:55:36'),
(66, 66, 4, 1, '2017-03-22 12:00:34', 4, '', '2017-03-22 12:00:42', '2017-03-22 12:00:50', '2017-03-22 12:00:53', '0000-00-00 00:00:00', '2017-03-22 12:00:23', '2017-03-22 12:00:50'),
(67, 67, 4, 0, '0000-00-00 00:00:00', 5, 'hxh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 12:05:15', '2017-03-22 12:05:05', '2017-03-22 12:05:05'),
(68, 68, 4, 0, '0000-00-00 00:00:00', 5, 'cbxh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 12:05:36', '2017-03-22 12:05:30', '2017-03-22 12:05:30'),
(69, 69, 4, 0, '0000-00-00 00:00:00', 5, 'cbbx', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 12:05:52', '2017-03-22 12:05:46', '2017-03-22 12:05:46'),
(70, 70, 4, 1, '2017-03-22 12:06:40', 5, 'cg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 12:06:48', '2017-03-22 12:06:33', '2017-03-22 12:06:40'),
(71, 71, 4, 1, '2017-03-22 12:35:04', 5, 'hg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 12:41:14', '2017-03-22 12:34:09', '2017-03-22 12:35:04'),
(72, 72, 4, 1, '2017-03-22 12:45:18', 4, '', '2017-03-22 12:45:25', '2017-03-22 12:45:28', '2017-03-22 12:45:32', '0000-00-00 00:00:00', '2017-03-22 12:45:06', '2017-03-22 12:45:28'),
(73, 73, 4, 1, '2017-03-22 13:08:52', 5, 'stk', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:09:09', '2017-03-22 13:08:38', '2017-03-22 13:08:52'),
(74, 74, 4, 1, '2017-03-22 13:14:34', 4, '', '2017-03-22 13:14:38', '2017-03-22 13:14:41', '2017-03-22 13:14:46', '0000-00-00 00:00:00', '2017-03-22 13:14:14', '2017-03-22 13:14:41'),
(75, 75, 4, 3, '2017-03-22 13:16:11', 2, 'ghy', '2017-03-22 13:16:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:16:19', '2017-03-22 13:15:54', '2017-03-22 13:16:14'),
(76, 76, 4, 3, '2017-03-22 13:25:11', 2, 'sg', '2017-03-22 13:25:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:25:19', '2017-03-22 13:24:38', '2017-03-22 13:25:14'),
(77, 77, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:26:02', '2017-03-22 13:27:05'),
(78, 78, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:28:32', '2017-03-22 13:28:32'),
(79, 79, 4, 3, '2017-03-22 13:40:40', 2, 'CN', '2017-03-22 13:40:45', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 13:40:51', '2017-03-22 13:40:33', '2017-03-22 13:40:45'),
(80, 80, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 14:10:33', '2017-03-22 14:10:33'),
(81, 81, 4, 3, '2017-03-22 14:15:15', 2, 'hd', '2017-03-22 14:15:18', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 14:15:22', '2017-03-22 14:14:54', '2017-03-22 14:15:18'),
(82, 82, 4, 1, '2017-03-22 14:31:03', 4, '', '2017-03-22 14:31:20', '2017-03-22 14:31:41', '2017-03-22 14:32:29', '0000-00-00 00:00:00', '2017-03-22 14:30:48', '2017-03-22 14:31:41'),
(83, 83, 4, 1, '2017-03-22 16:27:01', 5, 'chh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 16:27:21', '2017-03-22 16:26:52', '2017-03-22 16:27:01'),
(84, 84, 4, 3, '2017-03-22 16:38:38', 2, 'hhf', '2017-03-22 16:38:44', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 16:38:54', '2017-03-22 16:37:22', '2017-03-22 16:38:44'),
(85, 85, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 16:40:27', '2017-03-22 16:40:27'),
(86, 86, 5, 1, '2017-03-22 16:52:14', 4, '', '2017-03-22 16:52:34', '2017-03-22 16:52:40', '2017-03-22 16:53:49', '0000-00-00 00:00:00', '2017-03-22 16:52:04', '2017-03-22 16:52:40'),
(87, 87, 4, 1, '2017-03-22 18:14:55', 4, '', '2017-03-22 18:15:10', '2017-03-22 18:15:23', '2017-03-22 18:15:27', '0000-00-00 00:00:00', '2017-03-22 18:13:20', '2017-03-22 18:15:23'),
(88, 88, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 18:31:41', '2017-03-22 18:31:41'),
(89, 89, 4, 0, '0000-00-00 00:00:00', 5, 'NFF jg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 18:39:51', '2017-03-22 18:39:35', '2017-03-22 18:39:35'),
(90, 90, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 18:40:05', '2017-03-22 18:40:05'),
(91, 91, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-22 18:42:48', '2017-03-22 18:42:48'),
(92, 92, 2, 0, '0000-00-00 00:00:00', 5, 'nf', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 12:39:05', '2017-03-23 12:38:45', '2017-03-23 12:38:45'),
(93, 93, 2, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 15:48:50', '2017-03-23 15:48:50'),
(94, 94, 2, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 15:55:05', '2017-03-23 15:55:05'),
(95, 95, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 16:24:10', '2017-03-23 16:24:10'),
(96, 96, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 16:32:44', '2017-03-23 16:33:52'),
(97, 97, 4, 1, '2017-03-23 16:34:33', 5, 'bhj', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 16:34:59', '2017-03-23 16:34:06', '2017-03-23 16:34:33'),
(98, 98, 4, 2, '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-03-23 16:35:14', '2017-03-23 16:36:21'),
(99, 99, 2, 1, '2017-03-23 17:00:30', 4, '', '2017-03-23 17:26:30', '2017-03-23 17:30:43', '2017-03-23 17:31:56', '0000-00-00 00:00:00', '2017-03-23 17:00:21', '2017-03-23 17:30:43'),
(100, 100, 2, 1, '2017-03-23 17:57:46', 4, '', '2017-03-23 17:58:04', '2017-03-23 17:58:13', '2017-03-23 18:05:21', '0000-00-00 00:00:00', '2017-03-23 17:57:36', '2017-03-23 17:58:13'),
(102, 102, 5, 1, '2017-03-24 11:10:02', 4, '', '2017-03-24 11:10:10', '2017-03-24 11:10:27', '2017-03-24 11:10:32', '0000-00-00 00:00:00', '2017-03-24 11:09:51', '2017-03-24 11:10:27'),
(103, 103, 5, 1, '2017-03-24 11:14:23', 4, '', '2017-03-24 11:17:47', '2017-03-24 11:18:35', '2017-03-24 11:19:19', '0000-00-00 00:00:00', '2017-03-24 11:14:06', '2017-03-24 11:18:35'),
(104, 104, 5, 1, '2017-03-24 11:47:17', 4, '', '2017-03-24 11:52:32', '2017-03-24 11:52:58', '2017-03-24 11:54:21', '0000-00-00 00:00:00', '2017-03-24 11:47:04', '2017-03-24 11:52:58'),
(105, 105, 5, 1, '2017-03-24 12:02:45', 4, '', '2017-03-24 12:02:58', '2017-03-24 12:05:05', '2017-03-24 12:05:46', '0000-00-00 00:00:00', '2017-03-24 12:02:28', '2017-03-24 12:05:05');

-- --------------------------------------------------------

--
-- Table structure for table `wy_ridetype`
--

CREATE TABLE `wy_ridetype` (
  `id` int(11) NOT NULL,
  `ride_category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_ridetype`
--

INSERT INTO `wy_ridetype` (`id`, `ride_category`, `created_at`, `updated_at`) VALUES
(1, 'Cab', '2017-02-17 07:33:10', '2016-12-08 07:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `wy_ride_location`
--

CREATE TABLE `wy_ride_location` (
  `id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_ride_location`
--

INSERT INTO `wy_ride_location` (`id`, `ride_id`, `lat`, `lng`, `datetime`, `created_at`, `updated_at`) VALUES
(1, 2, '11.0160204', '76.95735', '2017-03-14 06:18:54', '2017-03-14 11:48:52', '2017-03-14 11:48:52'),
(2, 3, '11.0190815', '76.9585448', '2017-03-16 06:17:00', '2017-03-16 11:46:58', '2017-03-16 11:46:58'),
(3, 3, '11.0190815', '76.9585448', '2017-03-16 06:17:00', '2017-03-16 11:46:58', '2017-03-16 11:46:58'),
(4, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:09', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(5, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:14', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(6, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:20', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(7, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:24', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(8, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:31', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(9, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:36', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(10, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:41', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(11, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:46', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(12, 4, '11.0165301', '76.9575547', '2017-03-16 16:21:52', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(13, 4, '11.0165714', '76.9575097', '2017-03-16 16:21:57', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(14, 4, '11.0165714', '76.9575097', '2017-03-16 16:22:02', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(15, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:30', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(16, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:36', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(17, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:42', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(18, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:45', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(19, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:47', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(20, 6, '11.0166083', '76.9575833', '2017-03-16 16:54:54', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(21, 6, '11.016605', '76.957485', '2017-03-16 16:54:59', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(22, 6, '11.0166067', '76.9574183', '2017-03-16 16:55:05', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(23, 5, '11.016569780196', '76.957555236367', '2017-03-16 11:27:31', '2017-03-16 16:57:30', '2017-03-16 16:57:30'),
(24, 5, '11.016569780196', '76.957555236367', '2017-03-16 11:27:31', '2017-03-16 16:57:30', '2017-03-16 16:57:30'),
(25, 7, '11.016555', '76.9574467', '2017-03-16 16:56:58', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(26, 7, '11.016555', '76.9574467', '2017-03-16 16:57:04', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(27, 7, '11.0164983', '76.9574233', '2017-03-16 16:57:10', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(28, 7, '11.0165067', '76.9574217', '2017-03-16 16:57:16', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(29, 7, '11.0165067', '76.9574217', '2017-03-16 16:57:22', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(30, 7, '11.0163317', '76.957435', '2017-03-16 16:57:28', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(31, 7, '11.0162367', '76.9574467', '2017-03-16 16:57:34', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(32, 7, '11.016175', '76.957435', '2017-03-16 16:57:40', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(33, 7, '11.0161133', '76.9574417', '2017-03-16 16:57:45', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(34, 7, '11.0160767', '76.9574367', '2017-03-16 16:57:50', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(35, 7, '11.0160833', '76.9573633', '2017-03-16 16:57:56', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(36, 7, '11.01608', '76.9572917', '2017-03-16 16:58:01', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(37, 7, '11.016095', '76.9572167', '2017-03-16 16:58:06', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(38, 7, '11.0161467', '76.9572283', '2017-03-16 16:58:11', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(39, 7, '11.01623', '76.95728', '2017-03-16 16:58:17', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(40, 7, '11.0163567', '76.9574167', '2017-03-16 16:58:23', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(41, 17, '11.016591', '76.9575154', '2017-03-16 18:10:26', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(42, 17, '11.0165902', '76.9575152', '2017-03-16 18:10:31', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(43, 17, '11.0165895', '76.9575149', '2017-03-16 18:10:36', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(44, 17, '11.0165888', '76.9575148', '2017-03-16 18:10:41', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(45, 17, '11.0165913', '76.9575146', '2017-03-16 18:10:46', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(46, 17, '11.0165906', '76.9575144', '2017-03-16 18:10:51', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(47, 17, '11.0165899', '76.9575142', '2017-03-16 18:10:56', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(48, 17, '11.0165922', '76.9575141', '2017-03-16 18:11:01', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(49, 17, '11.0164233', '76.95738', '2017-03-16 18:11:05', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(50, 17, '11.0164533', '76.9573683', '2017-03-16 18:11:10', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(51, 17, '11.01645', '76.957365', '2017-03-16 18:11:15', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(52, 17, '11.01645', '76.957365', '2017-03-16 18:11:21', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(53, 17, '11.0164883', '76.9574583', '2017-03-16 18:11:27', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(54, 17, '11.01649', '76.9574583', '2017-03-16 18:11:33', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(55, 17, '11.01649', '76.9574583', '2017-03-16 18:11:39', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(56, 17, '11.01649', '76.9574583', '2017-03-16 18:11:44', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(57, 17, '11.01649', '76.9574583', '2017-03-16 18:11:50', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(58, 17, '11.01649', '76.9574583', '2017-03-16 18:11:56', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(59, 17, '11.01649', '76.9574583', '2017-03-16 18:12:02', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(60, 17, '11.01649', '76.9574583', '2017-03-16 18:12:08', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(61, 17, '11.01649', '76.9574583', '2017-03-16 18:12:14', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(62, 17, '11.01649', '76.9574583', '2017-03-16 18:12:20', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(63, 17, '11.01649', '76.9574583', '2017-03-16 18:12:26', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(64, 17, '11.01649', '76.9574583', '2017-03-16 18:12:32', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(65, 17, '11.01649', '76.9574583', '2017-03-16 18:12:37', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(66, 17, '11.01649', '76.9574583', '2017-03-16 18:12:42', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(67, 17, '11.0165717', '76.9575033', '2017-03-16 18:12:48', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(68, 18, '11.0165797', '76.9575217', '2017-03-16 18:16:34', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(69, 18, '11.0165714', '76.9575248', '2017-03-16 18:16:44', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(70, 18, '11.0165714', '76.9575229', '2017-03-16 18:16:53', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(71, 18, '11.0165452', '76.9575383', '2017-03-16 18:17:27', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(72, 18, '11.01647', '76.95794', '2017-03-16 18:17:29', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(73, 18, '11.01671', '76.9579164', '2017-03-16 18:17:30', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(74, 18, '11.01671', '76.9575407', '2017-03-16 18:17:33', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(75, 18, '11.0167179', '76.9572921', '2017-03-16 18:17:35', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(76, 18, '11.0167289', '76.9570448', '2017-03-16 18:17:38', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(77, 18, '11.0165984', '76.9569285', '2017-03-16 18:17:39', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(78, 18, '11.0163786', '76.9570398', '2017-03-16 18:17:42', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(79, 18, '11.0161609', '76.9571595', '2017-03-16 18:17:43', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(80, 18, '11.015945', '76.9572783', '2017-03-16 18:17:45', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(81, 18, '11.0157278', '76.9573977', '2017-03-16 18:17:47', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(82, 18, '11.0155126', '76.9575161', '2017-03-16 18:17:49', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(83, 18, '11.0152957', '76.9576354', '2017-03-16 18:17:52', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(84, 18, '11.0150812', '76.9577534', '2017-03-16 18:17:53', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(85, 18, '11.0148636', '76.957873', '2017-03-16 18:17:56', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(86, 18, '11.0146481', '76.9579915', '2017-03-16 18:17:57', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(87, 18, '11.014432', '76.9581104', '2017-03-16 18:17:59', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(88, 18, '11.0142154', '76.9582311', '2017-03-16 18:18:02', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(89, 18, '11.0139998', '76.9583521', '2017-03-16 18:18:03', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(90, 18, '11.0137845', '76.9584706', '2017-03-16 18:18:05', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(91, 18, '11.0135727', '76.9585965', '2017-03-16 18:18:07', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(92, 18, '11.0133561', '76.9587085', '2017-03-16 18:18:09', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(93, 18, '11.0131367', '76.95882', '2017-03-16 18:18:11', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(94, 18, '11.0129174', '76.9589315', '2017-03-16 18:18:13', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(95, 18, '11.0127055', '76.9590551', '2017-03-16 18:18:15', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(96, 18, '11.0124913', '76.9591789', '2017-03-16 18:18:17', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(97, 18, '11.0121709', '76.9593637', '2017-03-16 18:18:20', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(98, 18, '11.0119579', '76.9594866', '2017-03-16 18:18:23', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(99, 18, '11.0117447', '76.9596096', '2017-03-16 18:18:24', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(100, 18, '11.0114238', '76.9597947', '2017-03-16 18:18:27', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(101, 18, '11.0112944', '76.9597269', '2017-03-16 18:18:30', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(102, 18, '11.0112435', '76.9594819', '2017-03-16 18:18:32', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(103, 18, '11.0111926', '76.959237', '2017-03-16 18:18:33', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(104, 18, '11.0111458', '76.9589948', '2017-03-16 18:18:35', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(105, 18, '11.0110747', '76.9587559', '2017-03-16 18:18:37', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(106, 18, '11.01109', '76.95851', '2017-03-16 18:18:39', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(107, 18, '11.0111189', '76.9582662', '2017-03-16 18:18:42', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(108, 18, '11.0110988', '76.9580204', '2017-03-16 18:18:43', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(109, 18, '11.0110746', '76.9577713', '2017-03-16 18:18:45', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(110, 18, '11.0109564', '76.9575576', '2017-03-16 18:18:47', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(111, 18, '11.0107374', '76.95746', '2017-03-16 18:18:49', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(112, 18, '11.0105205', '76.9575574', '2017-03-16 18:18:52', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(113, 18, '11.0102533', '76.9578164', '2017-03-16 18:18:55', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(114, 18, '11.0100729', '76.9579886', '2017-03-16 18:18:56', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(115, 18, '11.009882', '76.9581473', '2017-03-16 18:18:58', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(116, 18, '11.0096892', '76.9583026', '2017-03-16 18:19:00', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(117, 18, '11.0095053', '76.9584703', '2017-03-16 18:19:03', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(118, 18, '11.00931', '76.9586222', '2017-03-16 18:19:04', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(119, 18, '11.009114', '76.9587703', '2017-03-16 18:19:07', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(120, 18, '11.0089166', '76.9589192', '2017-03-16 18:19:08', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(121, 18, '11.0087235', '76.9590656', '2017-03-16 18:19:10', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(122, 18, '11.0085338', '76.9592271', '2017-03-16 18:19:13', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(123, 18, '11.0083455', '76.9593874', '2017-03-16 18:19:14', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(124, 18, '11.0081559', '76.9595489', '2017-03-16 18:19:17', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(125, 18, '11.0079668', '76.9597099', '2017-03-16 18:19:18', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(126, 18, '11.0076857', '76.9599457', '2017-03-16 18:19:22', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(127, 18, '11.0074905', '76.9600996', '2017-03-16 18:19:24', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(128, 18, '11.007298', '76.9602536', '2017-03-16 18:19:26', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(129, 18, '11.0071036', '76.9604091', '2017-03-16 18:19:28', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(130, 18, '11.0068112', '76.9606351', '2017-03-16 18:19:31', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(131, 18, '11.0067122', '76.9607109', '2017-03-16 18:19:33', '2017-03-16 18:19:51', '2017-03-16 18:19:51'),
(132, 19, '11.0165176', '76.9575521', '2017-03-15 19:59:34', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(133, 19, '11.0165174', '76.9575522', '2017-03-15 19:59:37', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(134, 19, '11.0165254', '76.9575485', '2017-03-15 19:59:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(135, 19, '11.0165286', '76.9575477', '2017-03-15 19:59:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(136, 19, '11.0165232', '76.9575509', '2017-03-15 19:59:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(137, 19, '11.0165223', '76.9575527', '2017-03-15 19:59:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(138, 19, '11.0165223', '76.9575527', '2017-03-15 20:00:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(139, 19, '11.0165235', '76.9575511', '2017-03-15 20:00:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(140, 19, '11.016522', '76.9575505', '2017-03-15 20:00:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(141, 19, '11.016522', '76.9575505', '2017-03-15 20:00:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(142, 19, '11.0165205', '76.957551', '2017-03-15 20:00:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(143, 19, '11.0165202', '76.9575517', '2017-03-15 20:00:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(144, 19, '11.016522', '76.9575536', '2017-03-15 20:00:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(145, 19, '11.0165218', '76.9575534', '2017-03-15 20:00:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(146, 19, '11.0165218', '76.9575537', '2017-03-15 20:00:44', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(147, 19, '11.0165219', '76.9575538', '2017-03-15 20:00:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(148, 19, '11.0165225', '76.9575533', '2017-03-15 20:00:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(149, 19, '11.0165238', '76.9575526', '2017-03-15 20:00:59', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(150, 19, '11.0165212', '76.9575535', '2017-03-15 20:01:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(151, 19, '11.0165213', '76.9575535', '2017-03-15 20:01:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(152, 19, '11.0165213', '76.9575535', '2017-03-15 20:01:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(153, 19, '11.0165213', '76.9575534', '2017-03-15 20:01:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(154, 19, '11.0165207', '76.9575537', '2017-03-15 20:01:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(155, 19, '11.0165207', '76.9575537', '2017-03-15 20:01:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(156, 19, '11.016521', '76.9575536', '2017-03-15 20:01:34', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(157, 19, '11.0165209', '76.9575537', '2017-03-15 20:01:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(158, 19, '11.016521', '76.9575537', '2017-03-15 20:01:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(159, 19, '11.0165215', '76.9575536', '2017-03-15 20:01:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(160, 19, '11.0165217', '76.9575535', '2017-03-15 20:01:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(161, 19, '11.0165216', '76.9575535', '2017-03-15 20:01:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(162, 19, '11.0165205', '76.9575541', '2017-03-15 20:02:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(163, 19, '11.0165213', '76.9575537', '2017-03-15 20:02:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(164, 19, '11.016522', '76.9575535', '2017-03-15 20:02:12', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(165, 19, '11.016528', '76.9575524', '2017-03-15 20:02:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(166, 19, '11.0165271', '76.9575547', '2017-03-15 20:02:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(167, 19, '11.016516', '76.9575499', '2017-03-15 20:02:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(168, 19, '11.0165188', '76.9575476', '2017-03-15 20:02:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(169, 19, '11.0165179', '76.957548', '2017-03-15 20:02:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(170, 19, '11.0165159', '76.9575483', '2017-03-15 20:02:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(171, 19, '11.0165203', '76.9575492', '2017-03-15 20:02:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(172, 19, '11.0165205', '76.9575498', '2017-03-15 20:02:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(173, 19, '11.0165301', '76.9575439', '2017-03-15 20:02:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(174, 19, '11.0165302', '76.9575444', '2017-03-15 20:03:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(175, 19, '11.0165328', '76.957543', '2017-03-15 20:03:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(176, 19, '11.0165335', '76.9575427', '2017-03-15 20:03:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(177, 19, '11.0165336', '76.9575426', '2017-03-15 20:03:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(178, 19, '11.0165334', '76.9575426', '2017-03-15 20:03:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(179, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(180, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(181, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(182, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(183, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(184, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(185, 19, '11.0165334', '76.9575427', '2017-03-15 20:03:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(186, 19, '11.0165334', '76.9575428', '2017-03-15 20:04:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(187, 19, '11.0165334', '76.9575427', '2017-03-15 20:04:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(188, 19, '11.0165332', '76.9575429', '2017-03-15 20:04:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(189, 19, '11.0165327', '76.9575432', '2017-03-15 20:04:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(190, 19, '11.0165327', '76.9575432', '2017-03-15 20:04:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(191, 19, '11.0165328', '76.9575432', '2017-03-15 20:04:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(192, 19, '11.0165328', '76.9575432', '2017-03-15 20:04:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(193, 19, '11.0165328', '76.9575432', '2017-03-15 20:04:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(194, 19, '11.0165328', '76.9575431', '2017-03-15 20:04:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(195, 19, '11.0165328', '76.9575431', '2017-03-15 20:04:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(196, 19, '11.0165328', '76.9575432', '2017-03-15 20:04:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(197, 19, '11.0165328', '76.9575432', '2017-03-15 20:04:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(198, 19, '11.0165328', '76.9575431', '2017-03-15 20:05:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(199, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(200, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(201, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(202, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(203, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(204, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(205, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(206, 19, '11.016533', '76.957543', '2017-03-15 20:05:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(207, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(208, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(209, 19, '11.0165329', '76.9575431', '2017-03-15 20:05:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(210, 19, '11.0165329', '76.9575431', '2017-03-15 20:06:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(211, 19, '11.0165329', '76.9575431', '2017-03-15 20:06:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(212, 19, '11.0165329', '76.9575431', '2017-03-15 20:06:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(213, 19, '11.0165328', '76.9575432', '2017-03-15 20:06:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(214, 19, '11.0165328', '76.9575432', '2017-03-15 20:06:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(215, 19, '11.0165328', '76.9575432', '2017-03-15 20:06:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(216, 19, '11.0165328', '76.9575432', '2017-03-15 20:06:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(217, 19, '11.0165328', '76.9575432', '2017-03-15 20:06:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(218, 19, '11.0165327', '76.9575432', '2017-03-15 20:06:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(219, 19, '11.0165327', '76.9575433', '2017-03-15 20:06:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(220, 19, '11.0165327', '76.9575433', '2017-03-15 20:06:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(221, 19, '11.0165327', '76.9575433', '2017-03-15 20:06:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(222, 19, '11.0165327', '76.9575433', '2017-03-15 20:07:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(223, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(224, 19, '11.0165328', '76.9575432', '2017-03-15 20:07:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(225, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(226, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(227, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(228, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(229, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(230, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(231, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(232, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(233, 19, '11.0165328', '76.9575433', '2017-03-15 20:07:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(234, 19, '11.0165328', '76.9575433', '2017-03-15 20:08:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(235, 19, '11.0165328', '76.9575433', '2017-03-15 20:08:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(236, 19, '11.0165328', '76.9575433', '2017-03-15 20:08:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(237, 19, '11.0165328', '76.9575433', '2017-03-15 20:08:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(238, 19, '11.0165334', '76.9575436', '2017-03-15 20:08:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(239, 19, '11.0165334', '76.9575436', '2017-03-15 20:08:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(240, 19, '11.0165333', '76.9575437', '2017-03-15 20:08:34', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(241, 19, '11.0165333', '76.9575437', '2017-03-15 20:08:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(242, 19, '11.0165333', '76.9575437', '2017-03-15 20:08:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(243, 19, '11.0165333', '76.9575437', '2017-03-15 20:08:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(244, 19, '11.0165333', '76.9575437', '2017-03-15 20:08:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(245, 19, '11.0165332', '76.9575437', '2017-03-15 20:08:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(246, 19, '11.0165332', '76.9575437', '2017-03-15 20:09:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(247, 19, '11.0165333', '76.9575437', '2017-03-15 20:09:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(248, 19, '11.0165333', '76.9575437', '2017-03-15 20:09:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(249, 19, '11.0165333', '76.9575437', '2017-03-15 20:09:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(250, 19, '11.0165333', '76.9575436', '2017-03-15 20:09:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(251, 19, '11.0165333', '76.9575436', '2017-03-15 20:09:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(252, 19, '11.0165333', '76.9575436', '2017-03-15 20:09:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(253, 19, '11.0165334', '76.9575435', '2017-03-15 20:09:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(254, 19, '11.0165335', '76.9575435', '2017-03-15 20:09:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(255, 19, '11.0165337', '76.9575433', '2017-03-15 20:09:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(256, 19, '11.0165337', '76.9575434', '2017-03-15 20:09:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(257, 19, '11.0165338', '76.9575433', '2017-03-15 20:09:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(258, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(259, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(260, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(261, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(262, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(263, 19, '11.0165338', '76.9575433', '2017-03-15 20:10:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(264, 19, '11.0165339', '76.9575433', '2017-03-15 20:10:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(265, 19, '11.0165339', '76.9575433', '2017-03-15 20:10:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(266, 19, '11.0165339', '76.9575433', '2017-03-15 20:10:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(267, 19, '11.0165339', '76.9575433', '2017-03-15 20:10:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(268, 19, '11.0165339', '76.9575433', '2017-03-15 20:10:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(269, 19, '11.0165339', '76.9575434', '2017-03-15 20:10:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(270, 19, '11.0165339', '76.9575434', '2017-03-15 20:11:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(271, 19, '11.0165339', '76.9575434', '2017-03-15 20:11:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(272, 19, '11.0165339', '76.9575434', '2017-03-15 20:11:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(273, 19, '11.0165339', '76.9575434', '2017-03-15 20:11:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(274, 19, '11.0165339', '76.9575434', '2017-03-15 20:11:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(275, 19, '11.016534', '76.9575434', '2017-03-15 20:11:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(276, 19, '11.016534', '76.9575434', '2017-03-15 20:11:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(277, 19, '11.0165341', '76.9575433', '2017-03-15 20:11:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(278, 19, '11.0165341', '76.9575433', '2017-03-15 20:11:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(279, 19, '11.0165342', '76.9575433', '2017-03-15 20:11:48', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(280, 19, '11.0165342', '76.9575433', '2017-03-15 20:11:53', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(281, 19, '11.0165342', '76.9575433', '2017-03-15 20:11:58', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(282, 19, '11.0165341', '76.9575433', '2017-03-15 20:12:03', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(283, 19, '11.0165341', '76.9575433', '2017-03-15 20:12:08', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(284, 19, '11.0165341', '76.9575433', '2017-03-15 20:12:13', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(285, 19, '11.0165342', '76.9575432', '2017-03-15 20:12:18', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(286, 19, '11.0165342', '76.9575432', '2017-03-15 20:12:23', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(287, 19, '11.0165343', '76.9575432', '2017-03-15 20:12:28', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(288, 19, '11.0165342', '76.9575432', '2017-03-15 20:12:33', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(289, 19, '11.0165342', '76.9575433', '2017-03-15 20:12:38', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(290, 19, '11.0165343', '76.9575433', '2017-03-15 20:12:43', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(291, 20, '21.1450602', '72.7734966', '2017-03-17 13:10:12', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(292, 20, '21.1450598', '72.7734971', '2017-03-17 13:10:16', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(293, 20, '21.1450602', '72.7734968', '2017-03-17 13:10:26', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(294, 20, '21.1450643', '72.7734946', '2017-03-17 13:10:29', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(295, 20, '21.1450737', '72.7734899', '2017-03-17 13:10:34', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(296, 20, '21.1450612', '72.7734961', '2017-03-17 13:10:38', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(297, 20, '21.1450653', '72.7734938', '2017-03-17 13:10:43', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(298, 20, '21.1450657', '72.7734934', '2017-03-17 13:10:49', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(299, 20, '21.1450626', '72.7734953', '2017-03-17 13:10:56', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(300, 20, '21.1450628', '72.7734953', '2017-03-17 13:11:00', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(301, 20, '21.1450595', '72.773497', '2017-03-17 13:11:04', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(302, 20, '21.1450596', '72.7734968', '2017-03-17 13:11:09', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(303, 20, '21.1450602', '72.7734963', '2017-03-17 13:11:14', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(304, 20, '21.1450617', '72.7734954', '2017-03-17 13:11:21', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(305, 22, '11.0165755218', '76.957529168648', '2017-03-18 06:10:14', '2017-03-18 11:40:13', '2017-03-18 11:40:13'),
(306, 22, '11.0165755218', '76.957529168648', '2017-03-18 06:10:14', '2017-03-18 11:40:13', '2017-03-18 11:40:13'),
(307, 23, '11.016585705812', '76.957533778694', '2017-03-18 06:29:51', '2017-03-18 11:59:49', '2017-03-18 11:59:49'),
(308, 23, '11.016585705812', '76.957533778694', '2017-03-18 06:29:51', '2017-03-18 11:59:49', '2017-03-18 11:59:49'),
(309, 25, '11.016585705812', '76.957533778694', '2017-03-18 06:55:10', '2017-03-18 12:25:09', '2017-03-18 12:25:09'),
(310, 25, '11.016585705812', '76.957533778694', '2017-03-18 06:55:10', '2017-03-18 12:25:09', '2017-03-18 12:25:09'),
(311, 26, '11.016587843197', '76.957537802008', '2017-03-18 07:09:14', '2017-03-18 12:39:13', '2017-03-18 12:39:13'),
(312, 26, '11.016587843197', '76.957537802008', '2017-03-18 07:09:14', '2017-03-18 12:39:13', '2017-03-18 12:39:13'),
(313, 25, '11.016585705812', '76.957533778694', '2017-03-18 09:45:36', '2017-03-18 15:15:34', '2017-03-18 15:15:34'),
(314, 25, '11.016585705812', '76.957533778694', '2017-03-18 09:45:36', '2017-03-18 15:15:34', '2017-03-18 15:15:34'),
(315, 27, '11.016498366381', '76.957555655462', '2017-03-18 09:52:33', '2017-03-18 15:22:32', '2017-03-18 15:22:32'),
(316, 27, '11.016498366381', '76.957555655462', '2017-03-18 09:52:33', '2017-03-18 15:22:32', '2017-03-18 15:22:32'),
(317, 28, '11.016540066349', '76.957535203618', '2017-03-18 10:42:52', '2017-03-18 16:12:50', '2017-03-18 16:12:50'),
(318, 28, '11.016540066349', '76.957535203618', '2017-03-18 10:42:52', '2017-03-18 16:12:50', '2017-03-18 16:12:50'),
(319, 29, '11.016586544002', '76.957545597178', '2017-03-18 11:11:44', '2017-03-18 16:41:42', '2017-03-18 16:41:42'),
(320, 29, '11.016586544002', '76.957545597178', '2017-03-18 11:11:44', '2017-03-18 16:41:42', '2017-03-18 16:41:42'),
(321, 21, '21.1452482', '72.7733406', '2017-03-17 13:36:01', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(322, 21, '21.1452483', '72.7733408', '2017-03-17 13:36:06', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(323, 21, '21.1452486', '72.7733414', '2017-03-17 13:36:11', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(324, 21, '21.1452497', '72.773341', '2017-03-17 13:36:16', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(325, 21, '21.1452497', '72.7733411', '2017-03-17 13:36:21', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(326, 21, '21.1452495', '72.7733412', '2017-03-17 13:36:26', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(327, 21, '21.1452637', '72.7733364', '2017-03-17 13:36:30', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(328, 21, '21.1452642', '72.7733362', '2017-03-17 13:36:35', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(329, 21, '21.1452643', '72.7733361', '2017-03-17 13:36:40', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(330, 21, '21.1452643', '72.7733361', '2017-03-17 13:36:45', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(331, 21, '21.1452643', '72.7733361', '2017-03-17 13:36:50', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(332, 21, '21.1452622', '72.773337', '2017-03-17 13:36:55', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(333, 21, '21.1452622', '72.773337', '2017-03-17 13:36:59', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(334, 39, '11.016498', '76.9574978', '2017-03-21 14:41:46', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(335, 39, '11.016498', '76.9574979', '2017-03-21 14:41:51', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(336, 39, '11.0164974', '76.9574975', '2017-03-21 14:41:56', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(337, 39, '11.0164971', '76.9574971', '2017-03-21 14:42:01', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(338, 39, '11.0164976', '76.9574972', '2017-03-21 14:42:06', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(339, 39, '11.016498', '76.9574971', '2017-03-21 14:42:11', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(340, 39, '11.0164982', '76.9574969', '2017-03-21 14:42:16', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(341, 39, '11.0164995', '76.9574971', '2017-03-21 14:42:21', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(342, 39, '11.0165009', '76.9574974', '2017-03-21 14:42:26', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(343, 39, '11.0165015', '76.9574975', '2017-03-21 14:42:31', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(344, 39, '11.0165015', '76.9574976', '2017-03-21 14:42:36', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(345, 39, '11.0165018', '76.9574976', '2017-03-21 14:42:41', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(346, 39, '11.0165019', '76.9574978', '2017-03-21 14:42:46', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(347, 39, '11.0165018', '76.9574978', '2017-03-21 14:42:51', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(348, 39, '11.0165017', '76.957498', '2017-03-21 14:42:56', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(349, 39, '11.0165017', '76.957498', '2017-03-21 14:43:01', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(350, 39, '11.0165015', '76.9574979', '2017-03-21 14:43:06', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(351, 39, '11.0165013', '76.957498', '2017-03-21 14:43:11', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(352, 39, '11.0165014', '76.957498', '2017-03-21 14:43:16', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(353, 39, '11.0165014', '76.957498', '2017-03-21 14:43:21', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(354, 39, '11.0165014', '76.9574979', '2017-03-21 14:43:26', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(355, 39, '11.0165015', '76.9574979', '2017-03-21 14:43:31', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(356, 39, '11.0165016', '76.957498', '2017-03-21 14:43:36', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(357, 39, '11.0165017', '76.9574982', '2017-03-21 14:43:41', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(358, 39, '11.0165019', '76.9574983', '2017-03-21 14:43:46', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(359, 39, '11.0165023', '76.9574981', '2017-03-21 14:43:51', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(360, 39, '11.0165024', '76.9574979', '2017-03-21 14:43:56', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(361, 39, '11.0165026', '76.9574976', '2017-03-21 14:44:01', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(362, 39, '11.0165029', '76.9574976', '2017-03-21 14:44:06', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(363, 39, '11.0165032', '76.9574975', '2017-03-21 14:44:11', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(364, 39, '11.0165034', '76.9574973', '2017-03-21 14:44:16', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(365, 39, '11.0165036', '76.9574973', '2017-03-21 14:44:21', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(366, 39, '11.0165037', '76.9574973', '2017-03-21 14:44:26', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(367, 40, '11.016528', '76.957507', '2017-03-21 15:06:15', '2017-03-21 15:06:24', '2017-03-21 15:06:24'),
(368, 41, '11.0165255', '76.9575091', '2017-03-21 15:15:32', '2017-03-21 15:16:30', '2017-03-21 15:16:30'),
(369, 41, '11.0165255', '76.9575092', '2017-03-21 15:15:37', '2017-03-21 15:16:30', '2017-03-21 15:16:30'),
(370, 43, '11.0160204', '76.95735', '2017-03-21 10:03:50', '2017-03-21 15:33:48', '2017-03-21 15:33:48'),
(371, 43, '11.0160204', '76.95735', '2017-03-21 10:03:50', '2017-03-21 15:33:48', '2017-03-21 15:33:48'),
(372, 42, '11.0166183', '76.9575227', '2017-03-21 15:32:13', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(373, 42, '11.016559', '76.9575525', '2017-03-21 15:32:16', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(374, 42, '11.016558', '76.9575517', '2017-03-21 15:32:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(375, 42, '11.0165608', '76.9575498', '2017-03-21 15:32:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(376, 42, '11.0165657', '76.9575488', '2017-03-21 15:32:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(377, 42, '11.016566', '76.9575486', '2017-03-21 15:32:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(378, 42, '11.0165663', '76.9575485', '2017-03-21 15:32:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(379, 42, '11.0165665', '76.9575484', '2017-03-21 15:32:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(380, 42, '11.0165667', '76.9575483', '2017-03-21 15:32:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(381, 42, '11.0165667', '76.9575482', '2017-03-21 15:32:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(382, 42, '11.0165667', '76.9575482', '2017-03-21 15:33:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(383, 42, '11.0165667', '76.9575482', '2017-03-21 15:33:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(384, 42, '11.0165669', '76.9575482', '2017-03-21 15:33:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(385, 42, '11.0165669', '76.9575483', '2017-03-21 15:33:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(386, 42, '11.0165669', '76.9575483', '2017-03-21 15:33:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(387, 42, '11.0165663', '76.9575491', '2017-03-21 15:33:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(388, 42, '11.0165664', '76.957549', '2017-03-21 15:33:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(389, 42, '11.0165664', '76.9575491', '2017-03-21 15:33:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(390, 42, '11.0165665', '76.9575491', '2017-03-21 15:33:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(391, 42, '11.0165665', '76.9575491', '2017-03-21 15:33:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(392, 42, '11.0165664', '76.9575491', '2017-03-21 15:33:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(393, 42, '11.0165664', '76.9575491', '2017-03-21 15:33:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(394, 42, '11.0165664', '76.9575491', '2017-03-21 15:34:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(395, 42, '11.0165665', '76.9575491', '2017-03-21 15:34:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(396, 42, '11.0165666', '76.957549', '2017-03-21 15:34:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(397, 42, '11.0165667', '76.957549', '2017-03-21 15:34:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(398, 42, '11.0165669', '76.957549', '2017-03-21 15:34:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(399, 42, '11.0165673', '76.9575489', '2017-03-21 15:34:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(400, 42, '11.0165683', '76.9575487', '2017-03-21 15:34:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(401, 42, '11.0165691', '76.9575485', '2017-03-21 15:34:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(402, 42, '11.0165692', '76.9575485', '2017-03-21 15:34:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(403, 42, '11.0165695', '76.9575484', '2017-03-21 15:34:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(404, 42, '11.0165695', '76.9575484', '2017-03-21 15:34:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(405, 42, '11.0165694', '76.9575483', '2017-03-21 15:34:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(406, 42, '11.0165696', '76.9575483', '2017-03-21 15:35:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(407, 42, '11.0165696', '76.9575483', '2017-03-21 15:35:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(408, 42, '11.01657', '76.9575484', '2017-03-21 15:35:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(409, 42, '11.0165702', '76.9575484', '2017-03-21 15:35:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(410, 42, '11.0165703', '76.9575485', '2017-03-21 15:35:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(411, 42, '11.0165706', '76.9575486', '2017-03-21 15:35:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(412, 42, '11.016571', '76.9575487', '2017-03-21 15:35:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(413, 42, '11.0165711', '76.9575487', '2017-03-21 15:35:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(414, 42, '11.0165712', '76.9575488', '2017-03-21 15:35:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(415, 42, '11.0165712', '76.9575488', '2017-03-21 15:35:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(416, 42, '11.0165712', '76.9575488', '2017-03-21 15:35:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(417, 42, '11.0165712', '76.9575488', '2017-03-21 15:35:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(418, 42, '11.0165712', '76.9575488', '2017-03-21 15:36:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(419, 42, '11.0165712', '76.9575487', '2017-03-21 15:36:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(420, 42, '11.0165712', '76.9575486', '2017-03-21 15:36:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(421, 42, '11.0165712', '76.9575486', '2017-03-21 15:36:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(422, 42, '11.0165712', '76.9575485', '2017-03-21 15:36:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(423, 42, '11.0165712', '76.9575485', '2017-03-21 15:36:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(424, 42, '11.0165712', '76.9575485', '2017-03-21 15:36:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(425, 42, '11.0165711', '76.9575484', '2017-03-21 15:36:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(426, 42, '11.0165711', '76.9575483', '2017-03-21 15:36:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(427, 42, '11.016571', '76.9575483', '2017-03-21 15:36:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(428, 42, '11.0165708', '76.9575482', '2017-03-21 15:36:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(429, 42, '11.0165707', '76.9575482', '2017-03-21 15:36:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(430, 42, '11.0165707', '76.9575483', '2017-03-21 15:37:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(431, 42, '11.0165707', '76.9575484', '2017-03-21 15:37:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(432, 42, '11.0165706', '76.9575485', '2017-03-21 15:37:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(433, 42, '11.0165706', '76.9575484', '2017-03-21 15:37:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(434, 42, '11.0165705', '76.9575485', '2017-03-21 15:37:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(435, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:27', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(436, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:32', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(437, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:37', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(438, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:42', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(439, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:47', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(440, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:52', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(441, 42, '11.0165705', '76.9575484', '2017-03-21 15:37:57', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(442, 42, '11.0165706', '76.9575484', '2017-03-21 15:38:02', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(443, 42, '11.0165706', '76.9575484', '2017-03-21 15:38:07', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(444, 42, '11.0165706', '76.9575484', '2017-03-21 15:38:12', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(445, 42, '11.0165705', '76.9575484', '2017-03-21 15:38:17', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(446, 42, '11.0165705', '76.9575484', '2017-03-21 15:38:22', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(447, 44, '11.0161073', '76.9576952', '2017-03-21 15:45:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(448, 44, '11.0162536', '76.9576122', '2017-03-21 15:45:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(449, 44, '11.0163585', '76.9575703', '2017-03-21 15:45:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(450, 44, '11.0163941', '76.9574994', '2017-03-21 15:45:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(451, 44, '11.0164533', '76.9574386', '2017-03-21 15:45:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(452, 44, '11.0164533', '76.9574386', '2017-03-21 15:46:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(453, 44, '11.0164747', '76.957464', '2017-03-21 15:46:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(454, 44, '11.0164831', '76.9575027', '2017-03-21 15:46:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(455, 44, '11.0164741', '76.9575298', '2017-03-21 15:46:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(456, 44, '11.016472', '76.957532', '2017-03-21 15:46:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(457, 44, '11.0164992', '76.9575084', '2017-03-21 15:46:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(458, 44, '11.0165308', '76.9575033', '2017-03-21 15:46:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(459, 44, '11.0165398', '76.9575006', '2017-03-21 15:46:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(460, 44, '11.0165409', '76.9575002', '2017-03-21 15:46:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(461, 44, '11.0165487', '76.957494', '2017-03-21 15:46:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(462, 44, '11.0165535', '76.9574941', '2017-03-21 15:46:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(463, 44, '11.0165549', '76.9574951', '2017-03-21 15:46:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(464, 44, '11.0165549', '76.9574951', '2017-03-21 15:47:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(465, 44, '11.0165518', '76.9574981', '2017-03-21 15:47:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(466, 44, '11.0165483', '76.9575019', '2017-03-21 15:47:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(467, 44, '11.0165455', '76.9575151', '2017-03-21 15:47:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(468, 44, '11.0165451', '76.9575177', '2017-03-21 15:47:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(469, 44, '11.0165448', '76.9575178', '2017-03-21 15:47:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(470, 44, '11.0165444', '76.9575187', '2017-03-21 15:47:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(471, 44, '11.0165442', '76.9575189', '2017-03-21 15:47:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55');
INSERT INTO `wy_ride_location` (`id`, `ride_id`, `lat`, `lng`, `datetime`, `created_at`, `updated_at`) VALUES
(472, 44, '11.016544', '76.9575192', '2017-03-21 15:47:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(473, 44, '11.0165439', '76.9575191', '2017-03-21 15:47:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(474, 44, '11.0165439', '76.9575191', '2017-03-21 15:47:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(475, 44, '11.0165439', '76.9575191', '2017-03-21 15:47:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(476, 44, '11.0165439', '76.9575191', '2017-03-21 15:48:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(477, 44, '11.016544', '76.9575191', '2017-03-21 15:48:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(478, 44, '11.0165441', '76.9575191', '2017-03-21 15:48:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(479, 44, '11.0165444', '76.9575167', '2017-03-21 15:48:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(480, 44, '11.0165444', '76.9575166', '2017-03-21 15:48:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(481, 44, '11.0165444', '76.9575156', '2017-03-21 15:48:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(482, 44, '11.0165442', '76.9575151', '2017-03-21 15:48:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(483, 44, '11.016544', '76.9575149', '2017-03-21 15:48:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(484, 44, '11.016544', '76.9575147', '2017-03-21 15:48:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(485, 44, '11.0165442', '76.9575146', '2017-03-21 15:48:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(486, 44, '11.0165443', '76.9575146', '2017-03-21 15:48:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(487, 44, '11.0165444', '76.9575146', '2017-03-21 15:48:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(488, 44, '11.0165447', '76.9575145', '2017-03-21 15:49:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(489, 44, '11.0165451', '76.9575143', '2017-03-21 15:49:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(490, 44, '11.0165454', '76.957514', '2017-03-21 15:49:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(491, 44, '11.0165454', '76.9575142', '2017-03-21 15:49:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(492, 44, '11.0165453', '76.9575142', '2017-03-21 15:49:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(493, 44, '11.0165452', '76.9575145', '2017-03-21 15:49:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(494, 44, '11.0165455', '76.9575141', '2017-03-21 15:49:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(495, 44, '11.0165456', '76.9575143', '2017-03-21 15:49:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(496, 44, '11.0165459', '76.9575139', '2017-03-21 15:49:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(497, 44, '11.016546', '76.9575141', '2017-03-21 15:49:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(498, 44, '11.0165461', '76.9575143', '2017-03-21 15:49:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(499, 44, '11.0165462', '76.957514', '2017-03-21 15:49:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(500, 44, '11.0165463', '76.9575138', '2017-03-21 15:50:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(501, 44, '11.0165463', '76.9575136', '2017-03-21 15:50:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(502, 44, '11.0165464', '76.9575135', '2017-03-21 15:50:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(503, 44, '11.0165465', '76.9575136', '2017-03-21 15:50:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(504, 44, '11.0165468', '76.9575131', '2017-03-21 15:50:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(505, 44, '11.0165469', '76.9575129', '2017-03-21 15:50:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(506, 44, '11.0165468', '76.9575126', '2017-03-21 15:50:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(507, 44, '11.0165468', '76.9575121', '2017-03-21 15:50:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(508, 44, '11.016547', '76.9575118', '2017-03-21 15:50:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(509, 44, '11.0165469', '76.9575119', '2017-03-21 15:50:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(510, 44, '11.0165469', '76.9575118', '2017-03-21 15:50:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(511, 44, '11.0165471', '76.9575117', '2017-03-21 15:50:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(512, 44, '11.0165475', '76.9575112', '2017-03-21 15:51:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(513, 44, '11.0165476', '76.9575108', '2017-03-21 15:51:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(514, 44, '11.0165475', '76.9575105', '2017-03-21 15:51:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(515, 44, '11.0165475', '76.9575103', '2017-03-21 15:51:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(516, 44, '11.0165475', '76.9575102', '2017-03-21 15:51:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(517, 44, '11.0165475', '76.9575101', '2017-03-21 15:51:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(518, 44, '11.0165474', '76.9575102', '2017-03-21 15:51:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(519, 44, '11.0165474', '76.9575103', '2017-03-21 15:51:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(520, 44, '11.0165477', '76.95751', '2017-03-21 15:51:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(521, 44, '11.0165479', '76.9575098', '2017-03-21 15:51:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(522, 44, '11.016548', '76.9575096', '2017-03-21 15:51:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(523, 44, '11.016548', '76.9575096', '2017-03-21 15:51:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(524, 44, '11.016548', '76.9575096', '2017-03-21 15:52:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(525, 44, '11.016548', '76.9575097', '2017-03-21 15:52:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(526, 44, '11.016548', '76.9575097', '2017-03-21 15:52:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(527, 44, '11.0165479', '76.9575098', '2017-03-21 15:52:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(528, 44, '11.0165479', '76.9575098', '2017-03-21 15:52:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(529, 44, '11.0165479', '76.95751', '2017-03-21 15:52:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(530, 44, '11.0165479', '76.95751', '2017-03-21 15:52:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(531, 44, '11.0165479', '76.95751', '2017-03-21 15:52:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(532, 44, '11.016548', '76.95751', '2017-03-21 15:52:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(533, 44, '11.016548', '76.95751', '2017-03-21 15:52:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(534, 44, '11.016548', '76.95751', '2017-03-21 15:52:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(535, 44, '11.016548', '76.9575099', '2017-03-21 15:52:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(536, 44, '11.0165479', '76.9575099', '2017-03-21 15:53:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(537, 44, '11.0165478', '76.9575098', '2017-03-21 15:53:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(538, 44, '11.0165478', '76.9575098', '2017-03-21 15:53:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(539, 44, '11.0165478', '76.9575098', '2017-03-21 15:53:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(540, 44, '11.0165478', '76.9575098', '2017-03-21 15:53:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(541, 44, '11.0165479', '76.95751', '2017-03-21 15:53:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(542, 44, '11.0165477', '76.9575103', '2017-03-21 15:53:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(543, 44, '11.0165476', '76.9575104', '2017-03-21 15:53:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(544, 44, '11.0165475', '76.9575104', '2017-03-21 15:53:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(545, 44, '11.0165475', '76.9575104', '2017-03-21 15:53:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(546, 44, '11.0165483', '76.9575086', '2017-03-21 15:53:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(547, 44, '11.0165496', '76.9575057', '2017-03-21 15:53:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(548, 44, '11.0165497', '76.9575053', '2017-03-21 15:54:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(549, 44, '11.0165501', '76.9575041', '2017-03-21 15:54:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(550, 44, '11.0165501', '76.9575041', '2017-03-21 15:54:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(551, 44, '11.0165501', '76.9575042', '2017-03-21 15:54:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(552, 44, '11.01655', '76.9575042', '2017-03-21 15:54:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(553, 44, '11.0165499', '76.9575047', '2017-03-21 15:54:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(554, 44, '11.0165499', '76.9575048', '2017-03-21 15:54:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(555, 44, '11.0165499', '76.9575048', '2017-03-21 15:54:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(556, 44, '11.01655', '76.9575047', '2017-03-21 15:54:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(557, 44, '11.0165501', '76.9575046', '2017-03-21 15:54:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(558, 44, '11.01655', '76.9575049', '2017-03-21 15:54:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(559, 44, '11.0165502', '76.9575048', '2017-03-21 15:54:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(560, 44, '11.0165502', '76.9575047', '2017-03-21 15:55:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(561, 44, '11.0165501', '76.9575045', '2017-03-21 15:55:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(562, 44, '11.0165501', '76.9575048', '2017-03-21 15:55:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(563, 44, '11.01655', '76.9575051', '2017-03-21 15:55:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(564, 44, '11.01655', '76.9575051', '2017-03-21 15:55:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(565, 44, '11.0165499', '76.9575049', '2017-03-21 15:55:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(566, 44, '11.0165499', '76.9575049', '2017-03-21 15:55:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(567, 44, '11.0165499', '76.9575049', '2017-03-21 15:55:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(568, 44, '11.0165499', '76.9575049', '2017-03-21 15:55:44', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(569, 44, '11.01655', '76.957505', '2017-03-21 15:55:49', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(570, 44, '11.0165501', '76.9575049', '2017-03-21 15:55:54', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(571, 44, '11.0165502', '76.9575052', '2017-03-21 15:55:59', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(572, 44, '11.0165503', '76.9575059', '2017-03-21 15:56:04', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(573, 44, '11.0165503', '76.9575061', '2017-03-21 15:56:09', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(574, 44, '11.0165503', '76.9575063', '2017-03-21 15:56:14', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(575, 44, '11.0165505', '76.9575064', '2017-03-21 15:56:19', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(576, 44, '11.0165506', '76.9575062', '2017-03-21 15:56:24', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(577, 44, '11.0165506', '76.9575064', '2017-03-21 15:56:29', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(578, 44, '11.0165506', '76.9575067', '2017-03-21 15:56:34', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(579, 44, '11.0165505', '76.9575069', '2017-03-21 15:56:39', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(580, 46, '11.0166876', '76.9579216', '2017-03-21 17:10:02', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(581, 46, '11.01671', '76.9576994', '2017-03-21 17:10:04', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(582, 46, '11.0167112', '76.9574597', '2017-03-21 17:10:06', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(583, 46, '11.0167203', '76.9572324', '2017-03-21 17:10:08', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(584, 46, '11.0167243', '76.9569913', '2017-03-21 17:10:10', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(585, 46, '11.0165504', '76.9569453', '2017-03-21 17:10:12', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(586, 46, '11.0162332', '76.9571198', '2017-03-21 17:10:15', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(587, 46, '11.0160258', '76.9572338', '2017-03-21 17:10:17', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(588, 46, '11.0158277', '76.9573428', '2017-03-21 17:10:19', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(589, 46, '11.0156165', '76.9574589', '2017-03-21 17:10:22', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(590, 46, '11.0154191', '76.9575675', '2017-03-21 17:10:23', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(591, 46, '11.0152057', '76.9576848', '2017-03-21 17:10:25', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(592, 46, '11.0149942', '76.9578012', '2017-03-21 17:10:28', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(593, 46, '11.0147862', '76.9579156', '2017-03-21 17:10:29', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(594, 46, '11.0145766', '76.9580309', '2017-03-21 17:10:31', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(595, 46, '11.0143685', '76.9581453', '2017-03-21 17:10:33', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(596, 46, '11.0141562', '76.9582643', '2017-03-21 17:10:35', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(597, 46, '11.0139479', '76.9583807', '2017-03-21 17:10:38', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(598, 46, '11.0137427', '76.9584954', '2017-03-21 17:10:40', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(599, 46, '11.0135378', '76.9586162', '2017-03-21 17:10:42', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(600, 46, '11.0133248', '76.9587244', '2017-03-21 17:10:44', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(601, 46, '11.0131163', '76.9588303', '2017-03-21 17:10:45', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(602, 46, '11.0129017', '76.9589407', '2017-03-21 17:10:48', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(603, 46, '11.0126951', '76.9590612', '2017-03-21 17:10:49', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(604, 46, '11.0124885', '76.9591805', '2017-03-21 17:10:52', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(605, 46, '11.0122894', '76.9592954', '2017-03-21 17:10:54', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(606, 46, '11.0120843', '76.9594137', '2017-03-21 17:10:56', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(607, 46, '11.0118755', '76.9595342', '2017-03-21 17:10:57', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(608, 46, '11.0116694', '76.959653', '2017-03-21 17:10:59', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(609, 46, '11.0114572', '76.9597755', '2017-03-21 17:11:02', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(610, 46, '11.0113036', '76.959771', '2017-03-21 17:11:04', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(611, 46, '11.0112553', '76.9595388', '2017-03-21 17:11:06', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(612, 46, '11.0112069', '76.9593057', '2017-03-21 17:11:08', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(613, 46, '11.0111585', '76.959071', '2017-03-21 17:11:10', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(614, 46, '11.0111002', '76.9588427', '2017-03-21 17:11:12', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(615, 46, '11.0110828', '76.9586118', '2017-03-21 17:11:14', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(616, 46, '11.0111172', '76.9583766', '2017-03-21 17:11:16', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(617, 46, '11.0111086', '76.9581399', '2017-03-21 17:11:18', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(618, 46, '11.01109', '76.9579131', '2017-03-21 17:11:20', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(619, 46, '11.0110395', '76.9576792', '2017-03-21 17:11:22', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(620, 46, '11.0108879', '76.9575053', '2017-03-21 17:11:24', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(621, 46, '11.0106589', '76.9574742', '2017-03-21 17:11:26', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(622, 46, '11.0103856', '76.9576882', '2017-03-21 17:11:29', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(623, 46, '11.0102157', '76.9578523', '2017-03-21 17:11:31', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(624, 46, '11.0100422', '76.9580179', '2017-03-21 17:11:33', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(625, 46, '11.0098593', '76.9581656', '2017-03-21 17:11:35', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(626, 46, '11.0096746', '76.9583154', '2017-03-21 17:11:37', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(627, 46, '11.0095024', '76.9584726', '2017-03-21 17:11:39', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(628, 46, '11.0093151', '76.9586183', '2017-03-21 17:11:41', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(629, 46, '11.0091257', '76.9587615', '2017-03-21 17:11:43', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(630, 46, '11.0089385', '76.9589027', '2017-03-21 17:11:45', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(631, 46, '11.0087493', '76.9590454', '2017-03-21 17:11:47', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(632, 46, '11.0085611', '76.9592038', '2017-03-21 17:11:49', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(633, 46, '11.0083802', '76.9593578', '2017-03-21 17:11:51', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(634, 46, '11.0081984', '76.9595126', '2017-03-21 17:11:53', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(635, 46, '11.0080167', '76.9596673', '2017-03-21 17:11:55', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(636, 46, '11.0078357', '76.9598215', '2017-03-21 17:11:57', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(637, 46, '11.0076568', '76.9599674', '2017-03-21 17:11:59', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(638, 46, '11.0073716', '76.9601947', '2017-03-21 17:12:02', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(639, 46, '11.0071789', '76.9603489', '2017-03-21 17:12:03', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(640, 46, '11.0069857', '76.9605015', '2017-03-21 17:12:05', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(641, 46, '11.0067911', '76.9606505', '2017-03-21 17:12:07', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(642, 46, '11.0066005', '76.9607964', '2017-03-21 17:12:09', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(643, 46, '11.0063099', '76.9610189', '2017-03-21 17:12:12', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(644, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:14', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(645, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:17', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(646, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:19', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(647, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:22', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(648, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:24', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(649, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:26', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(650, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:28', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(651, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:30', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(652, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:32', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(653, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:34', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(654, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:37', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(655, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:38', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(656, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:41', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(657, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:43', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(658, 46, '11.0061086', '76.9611609', '2017-03-21 17:12:47', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(659, 47, '11.0167031', '76.9575547', '2017-03-21 17:33:18', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(660, 47, '11.01657', '76.95793', '2017-03-21 17:33:22', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(661, 47, '11.01671', '76.957703', '2017-03-21 17:33:25', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(662, 47, '11.0167162', '76.9573345', '2017-03-21 17:33:28', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(663, 47, '11.0167267', '76.9570919', '2017-03-21 17:33:30', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(664, 47, '11.0166409', '76.9569157', '2017-03-21 17:33:32', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(665, 47, '11.0163161', '76.9570741', '2017-03-21 17:33:35', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(666, 47, '11.0161078', '76.9571887', '2017-03-21 17:33:37', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(667, 47, '11.0159008', '76.9573025', '2017-03-21 17:33:39', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(668, 47, '11.0156924', '76.9574172', '2017-03-21 17:33:41', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(669, 47, '11.0154855', '76.957531', '2017-03-21 17:33:43', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(670, 47, '11.0152806', '76.9576436', '2017-03-21 17:33:45', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(671, 47, '11.0150731', '76.9577578', '2017-03-21 17:33:47', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(672, 47, '11.014867', '76.9578711', '2017-03-21 17:33:49', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(673, 47, '11.0146569', '76.9579867', '2017-03-21 17:33:51', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(674, 47, '11.0144448', '76.9581034', '2017-03-21 17:33:53', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(675, 47, '11.014236', '76.9582196', '2017-03-21 17:33:55', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(676, 47, '11.0140265', '76.9583371', '2017-03-21 17:33:57', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(677, 47, '11.0138306', '76.9584431', '2017-03-21 17:33:59', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(678, 47, '11.0136236', '76.9585662', '2017-03-21 17:34:01', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(679, 47, '11.0134306', '76.9586707', '2017-03-21 17:34:03', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(680, 47, '11.013218', '76.9587786', '2017-03-21 17:34:05', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(681, 47, '11.0130043', '76.9588872', '2017-03-21 17:34:07', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(682, 47, '11.0128044', '76.9589974', '2017-03-21 17:34:09', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(683, 47, '11.0125953', '76.9591189', '2017-03-21 17:34:11', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(684, 47, '11.0123833', '76.9592412', '2017-03-21 17:34:13', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(685, 47, '11.0121751', '76.9593613', '2017-03-21 17:34:15', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(686, 47, '11.0119833', '76.959472', '2017-03-21 17:34:17', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(687, 47, '11.0117808', '76.9595888', '2017-03-21 17:34:19', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(688, 47, '11.0115764', '76.9597067', '2017-03-21 17:34:21', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(689, 47, '11.0113701', '76.959825', '2017-03-21 17:34:23', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(690, 47, '11.0112823', '76.9596686', '2017-03-21 17:34:25', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(691, 47, '11.0112573', '76.9595483', '2017-03-21 17:34:28', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(692, 47, '11.0112573', '76.9595483', '2017-03-21 17:34:29', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(693, 47, '11.0112573', '76.9595483', '2017-03-21 17:34:31', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(694, 47, '11.0112573', '76.9595483', '2017-03-21 17:34:33', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(695, 49, '11.0166751', '76.9575882', '2017-03-21 17:50:08', '2017-03-21 17:51:27', '2017-03-21 17:51:27'),
(696, 49, '11.0166754', '76.9575879', '2017-03-21 17:50:14', '2017-03-21 17:51:27', '2017-03-21 17:51:27'),
(697, 58, '11.0165608', '76.9575488', '2017-03-22 11:04:01', '2017-03-22 11:04:10', '2017-03-22 11:04:10'),
(698, 64, '11.016604', '76.957537', '2017-03-22 11:42:43', '2017-03-22 11:42:58', '2017-03-22 11:42:58'),
(699, 64, '11.016604', '76.957537', '2017-03-22 11:42:48', '2017-03-22 11:42:58', '2017-03-22 11:42:58'),
(700, 72, '11.0165202', '76.9575355', '2017-03-22 12:45:30', '2017-03-22 12:45:37', '2017-03-22 12:45:37'),
(701, 82, '11.0165573', '76.9574915', '2017-03-22 14:31:46', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(702, 82, '11.0165573', '76.9574915', '2017-03-22 14:31:51', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(703, 82, '11.0165573', '76.9574915', '2017-03-22 14:31:56', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(704, 82, '11.0165573', '76.9574915', '2017-03-22 14:32:01', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(705, 82, '11.0165573', '76.9574915', '2017-03-22 14:32:06', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(706, 82, '11.0165573', '76.9574916', '2017-03-22 14:32:11', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(707, 82, '11.0165573', '76.9574916', '2017-03-22 14:32:16', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(708, 82, '11.0165573', '76.9574916', '2017-03-22 14:32:21', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(709, 82, '11.0165573', '76.9574916', '2017-03-22 14:32:26', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(710, 86, '11.0165848', '76.9574929', '2017-03-22 16:52:45', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(711, 86, '11.0165848', '76.9574929', '2017-03-22 16:52:50', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(712, 86, '11.0165847', '76.957493', '2017-03-22 16:52:55', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(713, 86, '11.0165847', '76.957493', '2017-03-22 16:53:00', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(714, 86, '11.0165864', '76.9574906', '2017-03-22 16:53:05', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(715, 86, '11.0165873', '76.9574894', '2017-03-22 16:53:10', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(716, 86, '11.0165875', '76.9574891', '2017-03-22 16:53:15', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(717, 86, '11.0165874', '76.9574892', '2017-03-22 16:53:20', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(718, 86, '11.0165875', '76.9574891', '2017-03-22 16:53:25', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(719, 86, '11.0165875', '76.9574891', '2017-03-22 16:53:30', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(720, 86, '11.0165875', '76.9574891', '2017-03-22 16:53:35', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(721, 86, '11.0165875', '76.9574892', '2017-03-22 16:53:40', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(722, 86, '11.0165875', '76.9574893', '2017-03-22 16:53:45', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(723, 99, '21.145433', '72.7734946', '2017-03-23 17:30:20', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(724, 99, '21.145433', '72.7734946', '2017-03-23 17:30:24', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(725, 99, '21.1454374', '72.773497', '2017-03-23 17:30:30', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(726, 99, '21.145336', '72.7733793', '2017-03-23 17:30:33', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(727, 99, '21.1453359', '72.7733794', '2017-03-23 17:30:36', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(728, 99, '21.1453656', '72.7733663', '2017-03-23 17:30:40', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(729, 99, '21.1453656', '72.7733663', '2017-03-23 17:30:46', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(730, 99, '21.1453654', '72.7733664', '2017-03-23 17:30:52', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(731, 99, '21.1453654', '72.7733664', '2017-03-23 17:30:56', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(732, 99, '21.1453655', '72.7733664', '2017-03-23 17:31:02', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(733, 99, '21.1453655', '72.7733664', '2017-03-23 17:31:06', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(734, 99, '21.1453655', '72.7733664', '2017-03-23 17:31:13', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(735, 99, '21.1453655', '72.7733664', '2017-03-23 17:31:17', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(736, 99, '21.1453654', '72.7733664', '2017-03-23 17:31:22', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(737, 99, '21.1453654', '72.7733665', '2017-03-23 17:31:27', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(738, 100, '21.1455633', '72.7735014', '2017-03-23 17:57:49', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(739, 100, '21.1455641', '72.7735064', '2017-03-23 17:57:54', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(740, 100, '21.1455647', '72.7735104', '2017-03-23 17:57:59', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(741, 100, '21.1455653', '72.7735143', '2017-03-23 17:58:04', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(742, 100, '21.1453406', '72.7734082', '2017-03-23 17:58:09', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(743, 100, '21.1453406', '72.7734082', '2017-03-23 17:58:13', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(744, 100, '21.1453405', '72.7734083', '2017-03-23 17:58:24', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(745, 100, '21.1453405', '72.7734083', '2017-03-23 17:58:28', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(746, 100, '21.1453405', '72.7734083', '2017-03-23 17:58:36', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(747, 100, '21.1455749', '72.7735744', '2017-03-23 17:58:48', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(748, 100, '21.1453393', '72.7734088', '2017-03-23 17:58:50', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(749, 100, '21.1453393', '72.7734088', '2017-03-23 17:58:53', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(750, 100, '21.1455749', '72.7735744', '2017-03-23 17:59:05', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(751, 100, '21.1453384', '72.7734106', '2017-03-23 17:59:10', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(752, 100, '21.1455749', '72.7735744', '2017-03-23 17:59:20', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(753, 100, '21.1455749', '72.7735744', '2017-03-23 17:59:24', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(754, 100, '21.1453319', '72.7734059', '2017-03-23 17:59:28', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(755, 100, '21.1453322', '72.7734058', '2017-03-23 17:59:38', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(756, 100, '21.1453322', '72.7734058', '2017-03-23 17:59:42', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(757, 100, '21.1453322', '72.7734058', '2017-03-23 17:59:54', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(758, 100, '21.1453322', '72.7734058', '2017-03-23 17:59:57', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(759, 100, '21.1455749', '72.7735744', '2017-03-23 18:00:12', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(760, 100, '21.1455749', '72.7735744', '2017-03-23 18:00:16', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(761, 100, '21.1453321', '72.7734058', '2017-03-23 18:00:19', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(762, 100, '21.1455749', '72.7735744', '2017-03-23 18:00:34', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(763, 100, '21.1455749', '72.7735744', '2017-03-23 18:00:39', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(764, 100, '21.1453312', '72.7734062', '2017-03-23 18:00:44', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(765, 100, '21.1456032', '72.7734136', '2017-03-23 18:00:54', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(766, 100, '21.1455984', '72.7734406', '2017-03-23 18:00:59', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(767, 100, '21.1454664', '72.7735326', '2017-03-23 18:01:04', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(768, 100, '21.1454722', '72.7735348', '2017-03-23 18:01:09', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(769, 100, '21.1454773', '72.7735368', '2017-03-23 18:01:14', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(770, 100, '21.1453312', '72.7734065', '2017-03-23 18:01:19', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(771, 100, '21.1453312', '72.7734065', '2017-03-23 18:01:24', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(772, 100, '21.1454905', '72.7735419', '2017-03-23 18:01:33', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(773, 100, '21.1453313', '72.773407', '2017-03-23 18:01:35', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(774, 100, '21.1453313', '72.773407', '2017-03-23 18:01:38', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(775, 100, '21.1453313', '72.773407', '2017-03-23 18:01:40', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(776, 100, '21.1455749', '72.7735744', '2017-03-23 18:01:54', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(777, 100, '21.1455749', '72.7735744', '2017-03-23 18:01:59', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(778, 100, '21.1453312', '72.7734069', '2017-03-23 18:02:01', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(779, 100, '21.1453312', '72.7734069', '2017-03-23 18:02:04', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(780, 100, '21.1454604', '72.7731295', '2017-03-23 18:02:15', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(781, 100, '21.1453311', '72.7734067', '2017-03-23 18:02:18', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(782, 100, '21.1453311', '72.7734067', '2017-03-23 18:02:21', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(783, 100, '21.1455749', '72.7735744', '2017-03-23 18:02:36', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(784, 100, '21.1453312', '72.7734068', '2017-03-23 18:02:41', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(785, 100, '21.1453389', '72.773435', '2017-03-23 18:02:51', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(786, 100, '21.1453375', '72.7734308', '2017-03-23 18:02:55', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(787, 100, '21.1455749', '72.7735744', '2017-03-23 18:03:09', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(788, 100, '21.1455749', '72.7735744', '2017-03-23 18:03:14', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(789, 100, '21.1453367', '72.7734307', '2017-03-23 18:03:19', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(790, 100, '21.1455749', '72.7735744', '2017-03-23 18:03:29', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(791, 100, '21.1453367', '72.7734307', '2017-03-23 18:03:34', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(792, 100, '21.1453373', '72.7734304', '2017-03-23 18:03:46', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(793, 100, '21.1453373', '72.7734304', '2017-03-23 18:03:49', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(794, 100, '21.1456555', '72.7730644', '2017-03-23 18:04:04', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(795, 100, '21.1456292', '72.7732305', '2017-03-23 18:04:09', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(796, 100, '21.1456159', '72.773315', '2017-03-23 18:04:14', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(797, 100, '21.145337', '72.7734309', '2017-03-23 18:04:18', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(798, 100, '21.145337', '72.7734309', '2017-03-23 18:04:23', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(799, 100, '21.1453371', '72.7734313', '2017-03-23 18:04:35', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(800, 100, '21.1453371', '72.7734312', '2017-03-23 18:04:39', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(801, 100, '21.145337', '72.7734311', '2017-03-23 18:04:44', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(802, 100, '21.1453369', '72.7734311', '2017-03-23 18:04:50', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(803, 101, '11.0190815', '76.9585448', '2017-03-24 05:25:00', '2017-03-24 10:54:58', '2017-03-24 10:54:58'),
(804, 101, '11.0190815', '76.9585448', '2017-03-24 05:25:00', '2017-03-24 10:54:58', '2017-03-24 10:54:58'),
(805, 102, '11.0190815', '76.9585448', '2017-03-24 05:43:34', '2017-03-24 11:13:33', '2017-03-24 11:13:33'),
(806, 103, '11.0190815', '76.9585448', '2017-03-24 06:16:51', '2017-03-24 11:46:48', '2017-03-24 11:46:48'),
(807, 103, '11.0190815', '76.9585448', '2017-03-24 06:16:51', '2017-03-24 11:46:48', '2017-03-24 11:46:48'),
(808, 104, '11.0190815', '76.9585448', '2017-03-24 06:32:15', '2017-03-24 12:02:13', '2017-03-24 12:02:13'),
(809, 104, '11.0190815', '76.9585448', '2017-03-24 06:32:15', '2017-03-24 12:02:13', '2017-03-24 12:02:13'),
(810, 105, '11.0190815', '76.9585448', '2017-03-24 06:37:22', '2017-03-24 12:07:20', '2017-03-24 12:07:20'),
(811, 105, '11.0190815', '76.9585448', '2017-03-24 06:37:22', '2017-03-24 12:07:20', '2017-03-24 12:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `wy_share_wyrades`
--

CREATE TABLE `wy_share_wyrades` (
  `id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0-Not available,1 - Available',
  `available_seat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_surge`
--

CREATE TABLE `wy_surge` (
  `id` int(11) NOT NULL,
  `car_type` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `loc_lat` varchar(20) NOT NULL,
  `loc_lng` varchar(20) NOT NULL,
  `loc_radius` varchar(20) NOT NULL,
  `surge_percentage` float NOT NULL,
  `status` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wy_tax`
--

CREATE TABLE `wy_tax` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(30) NOT NULL,
  `percentage` float NOT NULL,
  `country` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1-active, 2-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_tax`
--

INSERT INTO `wy_tax` (`id`, `tax_name`, `percentage`, `country`, `state`, `created_by`, `created_at`, `updated_at`, `updated_by`, `status`) VALUES
(1, 'Swachh Bharat Cess', 2, 101, 23, 1, '2017-03-10 09:41:56', '2017-03-10 15:11:56', 1, 1),
(2, 'Service Tax', 1, 101, 23, 1, '2017-03-10 15:11:40', '2017-03-10 15:11:40', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wy_taximoney`
--

CREATE TABLE `wy_taximoney` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `offer_id` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_taximoney`
--

INSERT INTO `wy_taximoney` (`id`, `customer_id`, `amount`, `offer_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, '2017-03-14 04:04:24', '2017-03-24 12:07:20'),
(2, 2, 0, 0, '2017-03-16 05:54:42', '2017-03-23 18:05:34'),
(3, 3, 0, 0, '2017-03-16 09:39:40', '2017-03-16 09:39:40'),
(4, 4, 0, 0, '2017-03-16 09:42:16', '2017-03-16 09:42:16'),
(5, 5, 0, 0, '2017-03-16 10:10:55', '2017-03-16 16:22:40'),
(6, 6, 0, 0, '2017-03-16 10:48:40', '2017-03-16 10:48:40'),
(7, 7, 0, 0, '2017-03-16 11:03:12', '2017-03-22 16:53:59'),
(8, 8, 0, 0, '2017-03-16 11:17:04', '2017-03-22 18:15:31'),
(9, 9, 0, 0, '2017-03-16 11:38:20', '2017-03-16 11:38:20'),
(10, 10, 0, 0, '2017-03-17 11:43:07', '2017-03-17 11:43:07'),
(11, 11, 0, 0, '2017-03-17 11:47:14', '2017-03-17 11:47:14'),
(12, 12, 0, 0, '2017-03-17 12:38:21', '2017-03-17 12:38:21'),
(13, 13, 0, 0, '2017-03-17 13:57:30', '2017-03-17 13:57:30'),
(14, 14, 0, 0, '2017-03-20 10:50:16', '2017-03-20 10:50:16'),
(15, 15, 0, 0, '2017-03-21 07:43:26', '2017-03-21 07:43:26'),
(16, 16, 0, 0, '2017-03-22 03:17:51', '2017-03-22 03:17:51'),
(17, 17, 0, 0, '2017-03-23 12:35:17', '2017-03-23 12:35:17'),
(18, 18, 0, 0, '2017-03-23 16:21:00', '2017-03-23 16:21:00'),
(19, 19, 0, 0, '2017-03-24 06:05:35', '2017-03-24 06:05:35'),
(20, 20, 0, 0, '2017-03-24 09:16:10', '2017-03-24 09:16:10'),
(21, 21, 0, 0, '2017-03-24 09:42:46', '2017-03-24 09:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `wy_taximoney_history`
--

CREATE TABLE `wy_taximoney_history` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `based_on` int(11) NOT NULL COMMENT '1-card, 2-cash back',
  `type` int(11) NOT NULL COMMENT '1-received, 2-sent',
  `coupon_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wy_taximoney_history`
--

INSERT INTO `wy_taximoney_history` (`id`, `customer_id`, `amount`, `based_on`, `type`, `coupon_code`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 2, '', '2017-03-14 11:48:52', '2017-03-14 11:48:52'),
(2, 1, 0, 0, 2, '', '2017-03-16 11:46:58', '2017-03-16 11:46:58'),
(3, 5, 0, 0, 2, '', '2017-03-16 16:22:40', '2017-03-16 16:22:40'),
(4, 8, 0, 0, 2, '', '2017-03-16 16:55:08', '2017-03-16 16:55:08'),
(5, 7, 0, 0, 2, '', '2017-03-16 16:57:30', '2017-03-16 16:57:30'),
(6, 8, 0, 0, 2, '', '2017-03-16 16:58:22', '2017-03-16 16:58:22'),
(7, 8, 0, 0, 2, '', '2017-03-16 18:14:54', '2017-03-16 18:14:54'),
(8, 8, 0, 0, 2, '', '2017-03-17 11:57:46', '2017-03-17 11:57:46'),
(9, 2, 0, 0, 2, '', '2017-03-17 13:32:25', '2017-03-17 13:32:25'),
(10, 8, 0, 0, 2, '', '2017-03-18 11:40:13', '2017-03-18 11:40:13'),
(11, 8, 0, 0, 2, '', '2017-03-18 11:59:49', '2017-03-18 11:59:49'),
(12, 8, 0, 0, 2, '', '2017-03-18 12:25:09', '2017-03-18 12:25:09'),
(13, 8, 0, 0, 2, '', '2017-03-18 12:39:13', '2017-03-18 12:39:13'),
(14, 8, 0, 0, 2, '', '2017-03-18 15:15:34', '2017-03-18 15:15:34'),
(15, 8, 0, 0, 2, '', '2017-03-18 15:22:32', '2017-03-18 15:22:32'),
(16, 8, 0, 0, 2, '', '2017-03-18 16:12:50', '2017-03-18 16:12:50'),
(17, 8, 0, 0, 2, '', '2017-03-18 16:41:42', '2017-03-18 16:41:42'),
(18, 2, 0, 0, 2, '', '2017-03-20 12:45:54', '2017-03-20 12:45:54'),
(19, 7, 0, 0, 2, '', '2017-03-21 13:02:35', '2017-03-21 13:02:35'),
(20, 7, 0, 0, 2, '', '2017-03-21 13:47:03', '2017-03-21 13:47:03'),
(21, 8, 0, 0, 2, '', '2017-03-21 14:44:54', '2017-03-21 14:44:54'),
(22, 8, 0, 0, 2, '', '2017-03-21 15:06:24', '2017-03-21 15:06:24'),
(23, 8, 0, 0, 2, '', '2017-03-21 15:16:30', '2017-03-21 15:16:30'),
(24, 1, 0, 0, 2, '', '2017-03-21 15:33:48', '2017-03-21 15:33:48'),
(25, 8, 0, 0, 2, '', '2017-03-21 15:45:00', '2017-03-21 15:45:00'),
(26, 8, 0, 0, 2, '', '2017-03-21 15:56:55', '2017-03-21 15:56:55'),
(27, 8, 0, 0, 2, '', '2017-03-21 17:13:12', '2017-03-21 17:13:12'),
(28, 7, 0, 0, 2, '', '2017-03-21 17:36:08', '2017-03-21 17:36:08'),
(29, 2, 0, 0, 2, '', '2017-03-21 17:51:27', '2017-03-21 17:51:27'),
(30, 7, 0, 0, 2, '', '2017-03-21 18:25:36', '2017-03-21 18:25:36'),
(31, 7, 0, 0, 2, '', '2017-03-21 18:27:24', '2017-03-21 18:27:24'),
(32, 8, 0, 0, 2, '', '2017-03-21 18:34:40', '2017-03-21 18:34:40'),
(33, 8, 0, 0, 2, '', '2017-03-22 11:04:10', '2017-03-22 11:04:10'),
(34, 8, 0, 0, 2, '', '2017-03-22 11:42:58', '2017-03-22 11:42:58'),
(35, 8, 0, 0, 2, '', '2017-03-22 12:01:01', '2017-03-22 12:01:01'),
(36, 7, 0, 0, 2, '', '2017-03-22 12:45:37', '2017-03-22 12:45:37'),
(37, 7, 0, 0, 2, '', '2017-03-22 13:14:51', '2017-03-22 13:14:51'),
(38, 7, 0, 0, 2, '', '2017-03-22 14:32:47', '2017-03-22 14:32:47'),
(39, 7, 0, 0, 2, '', '2017-03-22 16:53:59', '2017-03-22 16:53:59'),
(40, 8, 0, 0, 2, '', '2017-03-22 18:15:31', '2017-03-22 18:15:31'),
(41, 2, 0, 0, 2, '', '2017-03-23 17:32:34', '2017-03-23 17:32:34'),
(42, 2, 0, 0, 2, '', '2017-03-23 18:05:34', '2017-03-23 18:05:34'),
(43, 1, 0, 0, 2, '', '2017-03-24 10:54:58', '2017-03-24 10:54:58'),
(44, 1, 0, 0, 2, '', '2017-03-24 11:13:33', '2017-03-24 11:13:33'),
(45, 1, 0, 0, 2, '', '2017-03-24 11:46:48', '2017-03-24 11:46:48'),
(46, 1, 0, 0, 2, '', '2017-03-24 12:02:13', '2017-03-24 12:02:13'),
(47, 1, 0, 0, 2, '', '2017-03-24 12:07:20', '2017-03-24 12:07:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_alert`
--
ALTER TABLE `wy_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_assign_history`
--
ALTER TABLE `wy_assign_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_assign_taxi`
--
ALTER TABLE `wy_assign_taxi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district` (`district`);

--
-- Indexes for table `wy_brand`
--
ALTER TABLE `wy_brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_carlist`
--
ALTER TABLE `wy_carlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district` (`district`);

--
-- Indexes for table `wy_cartype`
--
ALTER TABLE `wy_cartype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_city`
--
ALTER TABLE `wy_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_contactus`
--
ALTER TABLE `wy_contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_customer`
--
ALTER TABLE `wy_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_customerrate`
--
ALTER TABLE `wy_customerrate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_customer_places`
--
ALTER TABLE `wy_customer_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_dailydrive`
--
ALTER TABLE `wy_dailydrive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_device`
--
ALTER TABLE `wy_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_dispatcher`
--
ALTER TABLE `wy_dispatcher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_driver`
--
ALTER TABLE `wy_driver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district` (`district`);

--
-- Indexes for table `wy_driverlocation`
--
ALTER TABLE `wy_driverlocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_driverrate`
--
ALTER TABLE `wy_driverrate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_driver_allocation`
--
ALTER TABLE `wy_driver_allocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_emergencycontacts`
--
ALTER TABLE `wy_emergencycontacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_faredetails`
--
ALTER TABLE `wy_faredetails`
  ADD PRIMARY KEY (`fare_id`);

--
-- Indexes for table `wy_franchise`
--
ALTER TABLE `wy_franchise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_history`
--
ALTER TABLE `wy_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_model`
--
ALTER TABLE `wy_model`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_notification`
--
ALTER TABLE `wy_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_offernotification`
--
ALTER TABLE `wy_offernotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_offers`
--
ALTER TABLE `wy_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_packagelist`
--
ALTER TABLE `wy_packagelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ratecard`
--
ALTER TABLE `wy_ratecard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_rating_reasons`
--
ALTER TABLE `wy_rating_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ride`
--
ALTER TABLE `wy_ride`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ridecancel_reason`
--
ALTER TABLE `wy_ridecancel_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ridedetails`
--
ALTER TABLE `wy_ridedetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ridetype`
--
ALTER TABLE `wy_ridetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_ride_location`
--
ALTER TABLE `wy_ride_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_surge`
--
ALTER TABLE `wy_surge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_tax`
--
ALTER TABLE `wy_tax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_taximoney`
--
ALTER TABLE `wy_taximoney`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_taximoney_history`
--
ALTER TABLE `wy_taximoney_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=713;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=646;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wy_alert`
--
ALTER TABLE `wy_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `wy_assign_history`
--
ALTER TABLE `wy_assign_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_assign_taxi`
--
ALTER TABLE `wy_assign_taxi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_brand`
--
ALTER TABLE `wy_brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_carlist`
--
ALTER TABLE `wy_carlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_cartype`
--
ALTER TABLE `wy_cartype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wy_city`
--
ALTER TABLE `wy_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_contactus`
--
ALTER TABLE `wy_contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `wy_customer`
--
ALTER TABLE `wy_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `wy_customerrate`
--
ALTER TABLE `wy_customerrate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `wy_customer_places`
--
ALTER TABLE `wy_customer_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_dailydrive`
--
ALTER TABLE `wy_dailydrive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_device`
--
ALTER TABLE `wy_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_dispatcher`
--
ALTER TABLE `wy_dispatcher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_driver`
--
ALTER TABLE `wy_driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_driverlocation`
--
ALTER TABLE `wy_driverlocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_driverrate`
--
ALTER TABLE `wy_driverrate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `wy_driver_allocation`
--
ALTER TABLE `wy_driver_allocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_emergencycontacts`
--
ALTER TABLE `wy_emergencycontacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wy_faredetails`
--
ALTER TABLE `wy_faredetails`
  MODIFY `fare_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wy_franchise`
--
ALTER TABLE `wy_franchise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_history`
--
ALTER TABLE `wy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_model`
--
ALTER TABLE `wy_model`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `wy_notification`
--
ALTER TABLE `wy_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_offernotification`
--
ALTER TABLE `wy_offernotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_offers`
--
ALTER TABLE `wy_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_packagelist`
--
ALTER TABLE `wy_packagelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_ratecard`
--
ALTER TABLE `wy_ratecard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_rating_reasons`
--
ALTER TABLE `wy_rating_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `wy_ride`
--
ALTER TABLE `wy_ride`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `wy_ridecancel_reason`
--
ALTER TABLE `wy_ridecancel_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_ridedetails`
--
ALTER TABLE `wy_ridedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `wy_ridetype`
--
ALTER TABLE `wy_ridetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `wy_ride_location`
--
ALTER TABLE `wy_ride_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=812;
--
-- AUTO_INCREMENT for table `wy_surge`
--
ALTER TABLE `wy_surge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wy_tax`
--
ALTER TABLE `wy_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wy_taximoney`
--
ALTER TABLE `wy_taximoney`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `wy_taximoney_history`
--
ALTER TABLE `wy_taximoney_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `wy_assign_taxi`
--
ALTER TABLE `wy_assign_taxi`
  ADD CONSTRAINT `wy_assign_taxi_ibfk_1` FOREIGN KEY (`district`) REFERENCES `districts` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
