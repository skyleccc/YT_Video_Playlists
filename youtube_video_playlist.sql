-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 03:19 PM
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
-- Database: `youtube_video_playlist`
--

-- --------------------------------------------------------

--
-- Table structure for table `finals_playlists`
--

CREATE TABLE `finals_playlists` (
  `playlist_id` int(12) NOT NULL,
  `playlistTitle` varchar(60) DEFAULT NULL,
  `playlistDesc` varchar(255) DEFAULT NULL,
  `fileType` varchar(8) DEFAULT '.png',
  `defaultPic` tinyint(1) DEFAULT 1,
  `dateCreated` date DEFAULT current_timestamp(),
  `favorited` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finals_playlists`
--

INSERT INTO `finals_playlists` (`playlist_id`, `playlistTitle`, `playlistDesc`, `fileType`, `defaultPic`, `dateCreated`, `favorited`) VALUES
(1, 'Song Covers', '', '.jfif', 0, '2024-06-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `finals_videos`
--

CREATE TABLE `finals_videos` (
  `video_id` int(16) NOT NULL,
  `playlist_id` int(12) NOT NULL,
  `videoTitle` varchar(100) NOT NULL,
  `videoChannel` varchar(30) NOT NULL,
  `videoAdded` date NOT NULL DEFAULT current_timestamp(),
  `videoLink` varchar(100) NOT NULL,
  `lastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finals_videos`
--

INSERT INTO `finals_videos` (`video_id`, `playlist_id`, `videoTitle`, `videoChannel`, `videoAdded`, `videoLink`, `lastModified`) VALUES
(0, 1, 'fromis_9 (프로미스나인) \'DM\' Official MV', 'HYBE LABELS', '2024-06-21', 'https://www.youtube.com/watch?v=adEgbTc4LXE', '2024-06-20 22:31:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `finals_playlists`
--
ALTER TABLE `finals_playlists`
  ADD PRIMARY KEY (`playlist_id`);

--
-- Indexes for table `finals_videos`
--
ALTER TABLE `finals_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `FK_playlist_id` (`playlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `finals_playlists`
--
ALTER TABLE `finals_playlists`
  MODIFY `playlist_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `finals_videos`
--
ALTER TABLE `finals_videos`
  ADD CONSTRAINT `FK_playlist_id` FOREIGN KEY (`playlist_id`) REFERENCES `finals_playlists` (`playlist_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
