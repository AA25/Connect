create database connect;

use connect;

create table developers ( devId int not null auto_increment primary key,
    -> firstName varchar(56) not null,
    -> lastName varchar(56) not null,
    -> dob date not null,
    -> languages varchar(500) not null,
    -> email varchar(56) not null unique,
    -> password varchar(500) not null,
    -> speciality varchar(56) not null,
    -> devBio varchar(500) not null),
    -> currentProj int,
    -> username varchar(100) not null unique;

create table businesses (busId int not null auto_increment primary key,
    -> busName varchar(56) not null,
    -> busIndustry varchar(56) not null,
    -> busBio varchar(500) not null,
    -> firstName varchar(56) not null,
    -> lastName varchar(56) not null,
    -> password varchar(500) not null,
    -> email varchar(56) not null unique,
    -> phone varchar(56) not null),
    -> username varchar(100) not null unique;

    create table projects (projectId int not null auto_increment primary key,
    -> businessId int not null,
    -> projectCategory varchar(56) not null,
    -> businessBio varchar(500) not null,
    -> projectBio varchar(500) not null,
    -> projectBudget varchar(56) not null,
    -> projectDeadline date,
    -> projectCountry varchar(56) not null,
    -> projectLanguage varchar(56) not null,
    -> projectCurrency varchar(56) not null,
    -> dateEntered date not null,
    -> startDate date not null,
    -> projectStatus int,
    -> projectName varchar(56) not null);

    create table projectState (projectState int not null auto_increment primary key,
    -> projectId int not null,
    -> currentState int not null);

    create table projectRequests (projectReqId int not null auto_increment primary key, projectId int, devId int, devMsg varchar(500) not null, status varchar(15) not null);

    create table projectDevelopers (projectDevId int not null auto_increment primary key, devId int, projectId int);

-- A splendid query that joins 3 tables together and extract certain information based on a few conditions
select projectRequests.projectReqId, projectRequests.projectId, projects.projectCategory, projects.projectBio developers.firstName, developers.lastName, developers.email, projectRequests.status, projectRequests.devMsg 
from (((projectRequests inner join projects on projectRequests.projectId = projects.projectId) 
inner join developers on  projectRequests.devId = developers.devId) 
inner join businesses on projectRequests.busId = businesses.busId) 
where businesses.email = 'test@test.com' and projectRequests.status = 'pending';

-- Updates the project request of a specific developer to a specific projecct to accepted or declined
update projectRequests inner join businesses on projectRequests.busId = businesses.busId
inner join projects on projectRequests.projectId = projects.projectId 
set projectRequests.status = 'Rejected'
where businesses.email = 'test1@test.com' and projectRequests.projectId=1 
and projectRequests.devId=1 and projectRequests.status = 'Pending' and (projects.projectStatus = 0 or projects.projectStatus = 1);

-- Returns the requests made by a developer
select projectRequests.projectReqId, projectRequests.projectId, projects.projectName, projectRequests.devMsg, projectRequests.status 
from ((projectRequests inner join developers on projectRequests.devId = developers.devId)
inner join projects on projectRequests.projectId = projects.projectId)
where developers.email = 'testingCurrentProj@test.com';

-- Deletes a project request made by a specific developer (when dev clicks delete)
delete projectRequests from projectRequests inner join developers on projectRequests.devId = developers.devId where developers.email = 'testingCurrentProj@test.com' and projectReqId = 7;

-- Return all the developers working on each of your business projects
select projects.projectName, developers.firstName, developers.lastName 
from ((projects inner join projectDevelopers on projects.projectId = projectDevelopers.projectId) 
inner join developers on developers.devId = projectDevelopers.devId) 
where projects.businessId = 4;

-- Returns the id of the projects a business owns based on their business email
select projects.projectId from projects inner join businesses on projects.businessId = businesses.busId where businesses.email = 'test4@test.com'

-- Return all the developers currently accepted for a project
select developers.firstName, developers.lastName, developers.username 
from ((developers inner join projectDevelopers on developers.devId = projectDevelopers.devId) 
inner join projects on projectDevelopers.projectId = projects.projectId) 
where projects.projectId = 4;

-- Update project status of a project owned by a specific project
update projects inner join businesses on projects.businessId = businesses.busId set projects.projectStatus = 2 where businesses.email = 'test4@test.com' and projects.projectId = 5;

-- Returns all the projects owned by a specific business
select projects.projectId, projects.projectName, projects.projectCategory, projects.projectBio, projects.projectBudget, projects.projectCountry, projects.projectCurrency, projects.projectStatus 
from projects inner join businesses on projects.businessId = businesses.busId 
where businesses.email = 'test4@test.com' order by dateEntered desc

-- Return current project a specific developer is on
select projects.projectId, projects.projectName, projects.projectCategory, projects.projectBio, projects.projectBudget, projects.projectCountry, projects.projectCurrency, projects.projectStatus
from ((projects inner join projectDevelopers on projects.projectId = projectDevelopers.projectId)
inner join developers on projectDevelopers.devId = developers.devId)
where developers.email = 'test2@test.com';