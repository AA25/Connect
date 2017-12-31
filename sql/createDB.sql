create database connect;

use connect;

-- Table that cotains all the businesses
CREATE TABLE `businesses` (
  `busId` int(11) NOT NULL AUTO_INCREMENT,
  `busName` varchar(56) NOT NULL,
  `busIndustry` varchar(56) NOT NULL,
  `busBio` varchar(500) NOT NULL,
  `firstName` varchar(56) NOT NULL,
  `lastName` varchar(56) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(56) NOT NULL,
  `phone` varchar(56) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  PRIMARY KEY (`busId`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table that contains all the developers
CREATE TABLE `developers` (
  `devId` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(56) NOT NULL,
  `lastName` varchar(56) NOT NULL,
  `dob` date NOT NULL,
  `languages` varchar(500) NOT NULL,
  `email` varchar(56) NOT NULL,
  `password` varchar(500) NOT NULL,
  `devBio` varchar(500) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `currentProject` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  PRIMARY KEY (`devId`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table that contains all the projects posted by businesses where business id is treated as a FK
CREATE TABLE `projects` (
  `projectId` int(11) NOT NULL AUTO_INCREMENT,
  `businessId` int(11) NOT NULL,
  `projectCategory` varchar(56) NOT NULL,
  `projectBio` varchar(500) NOT NULL,
  `projectBudget` varchar(56) NOT NULL,
  `projectDeadline` date DEFAULT NULL,
  `projectCountry` varchar(56) NOT NULL,
  `projectLanguage` varchar(100) NOT NULL,
  `projectCurrency` varchar(56) NOT NULL,
  `dateEntered` datetime DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `projectStatus` int(11) DEFAULT NULL,
  `projectName` varchar(56) DEFAULT NULL,
  PRIMARY KEY (`projectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table that contains all requests that developers make to projects
-- devId, projectId and busId are treated as FKs
CREATE TABLE `projectRequests` (
  `projectReqId` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) DEFAULT NULL,
  `devId` int(11) DEFAULT NULL,
  `devMsg` varchar(500) NOT NULL,
  `status` varchar(15) NOT NULL,
  `busId` int(11) DEFAULT NULL,
  PRIMARY KEY (`projectReqId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table that contains developers whose request have been accepted and are now part of that project
-- devId, projectId are treated as FKs
CREATE TABLE `projectDevelopers` (
  `projectDevId` int(11) NOT NULL AUTO_INCREMENT,
  `devId` int(11) DEFAULT NULL,
  `projectId` int(11) DEFAULT NULL,
  `proceedStatus` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`projectDevId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table that contains all messages between businesses and developers within a project that has been started
-- projectId is treated as a FK
CREATE TABLE `projectMessages` (
  `projectMessageId` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `fromWho` varchar(56) DEFAULT NULL,
  `messageTime` datetime NOT NULL,
  `sentMessage` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`projectMessageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

