-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2024 at 11:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'Deepak', '$2y$10$4ciy.OvPrOheU7scriO4a.sLuKHDOu5HrJQ6kqlz5WSxushgDMe/.');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `stream` varchar(100) DEFAULT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `stream_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `stream_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `email`, `mobile`, `address`, `photo`, `qualifications`, `stream_id`, `subject_id`) VALUES
(7, 'Deepak Kumar Sahu', 'dksksng@gmail.com', '7008308551', 'Balsi, Kesinga', 'Screenshot (1).png', NULL, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_qualifications`
--

CREATE TABLE `faculty_qualifications` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `board_name` varchar(100) DEFAULT NULL,
  `institute_name` varchar(100) DEFAULT NULL,
  `full_mark` int(11) DEFAULT NULL,
  `secure_mark` int(11) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_qualifications`
--

INSERT INTO `faculty_qualifications` (`id`, `faculty_id`, `class`, `board_name`, `institute_name`, `full_mark`, `secure_mark`, `percentage`) VALUES
(2, 7, '10th', 'BSE ODISHA', 'Jawahar Ucha Vidyapitha, Balsi', 600, 317, 52.83),
(3, 7, '+2', 'CHSE ODISHA', 'Government Vocational Junior College, Saintala', 650, 363, 55.85),
(4, 7, 'Graduation', 'Sambalpur University', 'Government Autonomous College, Rourkela', 1800, 1516, 84.22),
(5, 7, 'Post Graduation', 'Sambalpur University', 'Government Autonomous College, Rourkela', 1800, 1396, 77.56);

-- --------------------------------------------------------

--
-- Table structure for table `helpdesk`
--

CREATE TABLE `helpdesk` (
  `id` int(11) NOT NULL,
  `college_name` varchar(100) NOT NULL,
  `college_address` text DEFAULT NULL,
  `college_phone` varchar(15) DEFAULT NULL,
  `college_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `important_dates`
--

CREATE TABLE `important_dates` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_link` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_order` int(11) NOT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_name`, `menu_link`, `parent_id`, `menu_order`, `content`) VALUES
(5, 'Profile', 'profile.php', NULL, 1, '<p style=\"text-align: center;\"><font color=\"#ff0000\" face=\"Georgia, serif\"><span style=\"font-size: 20px; font-family: &quot;Comic Sans MS&quot;;\"><b>This is the Official Website of Deepak Kumar Sahu</b></span></font></p><p style=\"text-align: center;\"><img src=\"http://localhost/college/admin/uploads/anime-girl-beach-arch-sunset-digital-art-star-trails-scenic-3840x2160-6719.jpg\" style=\"width: 50%;\"><font color=\"#ff0000\" face=\"Georgia, serif\"><span style=\"font-size: 20px; font-family: &quot;Comic Sans MS&quot;;\"><b><br></b></span></font></p>'),
(6, 'Gallery', '#', NULL, 2, '<p style=\"text-align: center; \"><span style=\"font-family: &quot;Arial Black&quot;;\"><font color=\"#ce0000\">Gallery Update Soon</font></span></p>'),
(9, 'Videos', 'videos', NULL, 3, '<p><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/wynK0CMGIGk\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/TeBP6RY9wlQ\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><br></p>'),
(10, 'Test', 'test', 6, 1, ''),
(11, 'Test 2', 'test3', 6, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `position` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `notice_number` varchar(255) NOT NULL,
  `notice_name` varchar(255) NOT NULL,
  `notice_date` date NOT NULL,
  `notice_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `notice_number`, `notice_name`, `notice_date`, `notice_file`) VALUES
(1, '1', 'Sunday is Funday', '2024-10-13', 'uploads/notices/web_req_spec.pdf'),
(2, '2', 'Computer Science', '2024-10-13', 'uploads/notices/Comp_Sc_Enginnering_P2.pdf'),
(4, '3', 'Image', '2024-10-13', 'uploads/notices/3d-render-gradient-2560x1080-10996.jpg'),
(5, '4', 'Term', '2024-10-13', 'uploads/notices/Term.docx');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `stream_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `total_seats` int(11) DEFAULT NULL,
  `general_seats` int(11) DEFAULT NULL,
  `obc_seats` int(11) DEFAULT NULL,
  `sc_seats` int(11) DEFAULT NULL,
  `st_seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slideshow`
--

CREATE TABLE `slideshow` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slideshow`
--

INSERT INTO `slideshow` (`id`, `image_path`, `created_at`) VALUES
(7, 'uploads/slideshow/3d-render-gradient-2560x1080-10996.jpg', '2024-10-12 17:42:46'),
(8, 'uploads/slideshow/lake-sweden-blue-2560x1080-11151.jpg', '2024-10-12 17:42:55'),
(9, 'uploads/slideshow/mountains-clouds-2560x1080-11077.jpg', '2024-10-12 17:43:01'),
(10, 'uploads/slideshow/sunrise-morning-2560x1080-11167.jpg', '2024-10-12 17:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `id` int(11) NOT NULL,
  `stream_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `stream_name`) VALUES
(1, 'Arts'),
(2, 'Science'),
(3, 'Commerce');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `present_post` varchar(100) DEFAULT NULL,
  `present_block` varchar(100) DEFAULT NULL,
  `present_district` varchar(100) DEFAULT NULL,
  `present_state` varchar(100) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `permanent_post` varchar(100) DEFAULT NULL,
  `permanent_block` varchar(100) DEFAULT NULL,
  `permanent_district` varchar(100) DEFAULT NULL,
  `permanent_state` varchar(100) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `board_10th` varchar(100) DEFAULT NULL,
  `institute_10th` varchar(100) DEFAULT NULL,
  `secure_mark_10th` int(11) DEFAULT NULL,
  `full_mark_10th` int(11) DEFAULT NULL,
  `percentage_10th` decimal(5,2) DEFAULT NULL,
  `board_intermediate` varchar(100) DEFAULT NULL,
  `institute_intermediate` varchar(100) DEFAULT NULL,
  `secure_mark_intermediate` int(11) DEFAULT NULL,
  `full_mark_intermediate` int(11) DEFAULT NULL,
  `percentage_intermediate` decimal(5,2) DEFAULT NULL,
  `board_graduation` varchar(100) DEFAULT NULL,
  `institute_graduation` varchar(100) DEFAULT NULL,
  `secure_mark_graduation` int(11) DEFAULT NULL,
  `full_mark_graduation` int(11) DEFAULT NULL,
  `percentage_graduation` decimal(5,2) DEFAULT NULL,
  `stream_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_academics`
--

CREATE TABLE `student_academics` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `stream_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `stream_id`) VALUES
(1, 'Computer Science', 2),
(2, 'Odia', 1),
(3, 'Commerce', 3),
(4, 'English', 1),
(5, 'Hindi', 1),
(6, 'Physics', 2),
(7, 'Chemistry', 2),
(8, 'Mathematics', 2),
(9, 'Botany', 2),
(10, 'Zoology', 2),
(11, 'Statistics', 2),
(12, 'Anthropology', 1),
(13, 'History', 1),
(14, 'Political Science', 1),
(15, 'Geography', 1),
(16, 'Sociology', 1),
(17, 'Philosophy', 1),
(18, 'Psychology', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject_links`
--

CREATE TABLE `subject_links` (
  `id` int(11) NOT NULL,
  `stream_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stream_id` (`stream_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_qualifications`
--
ALTER TABLE `faculty_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `helpdesk`
--
ALTER TABLE `helpdesk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `important_dates`
--
ALTER TABLE `important_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stream_id` (`stream_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `slideshow`
--
ALTER TABLE `slideshow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `stream_id` (`stream_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `student_academics`
--
ALTER TABLE `student_academics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stream_id` (`stream_id`);

--
-- Indexes for table `subject_links`
--
ALTER TABLE `subject_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stream_id` (`stream_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faculty_qualifications`
--
ALTER TABLE `faculty_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `helpdesk`
--
ALTER TABLE `helpdesk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `important_dates`
--
ALTER TABLE `important_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slideshow`
--
ALTER TABLE `slideshow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_academics`
--
ALTER TABLE `student_academics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subject_links`
--
ALTER TABLE `subject_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`);

--
-- Constraints for table `faculty_qualifications`
--
ALTER TABLE `faculty_qualifications`
  ADD CONSTRAINT `faculty_qualifications_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`),
  ADD CONSTRAINT `seats_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `student_academics`
--
ALTER TABLE `student_academics`
  ADD CONSTRAINT `student_academics_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`);

--
-- Constraints for table `subject_links`
--
ALTER TABLE `subject_links`
  ADD CONSTRAINT `subject_links_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`),
  ADD CONSTRAINT `subject_links_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
