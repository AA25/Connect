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
    -> currentProj int;

create table businesses (busId int not null auto_increment primary key,
    -> busName varchar(56) not null,
    -> busIndustry varchar(56) not null,
    -> busBio varchar(500) not null,
    -> firstName varchar(56) not null,
    -> lastName varchar(56) not null,
    -> password varchar(500) not null,
    -> email varchar(56) not null unique,
    -> phone varchar(56) not null);

    create table projects (projectId int not null auto_increment primary key,
    -> businessId int not null,
    -> projectCategory varchar(56) not null,
    -> businessBio varchar(500) not null,
    -> projectBio varchar(500) not null,
    -> projectBudget varchar(56) not null,
    -> projectDeadline date not null,
    -> projectCountry varchar(56) not null,
    -> projectLanguage varchar(56) not null,
    -> projectCurrency varchar(56) not null,
    -> dateEntered date not null,
    -> startDate date not null,
    -> projectStatus int);

    create table projectState (projectState int not null auto_increment primary key,
    -> projectId int not null,
    -> currentState int not null);

    create table projectRequests (projectReqId int not null auto_increment primary key, projectId int, devId int, devMsg varchar(500) not null, status varchar(15) not null);

    create table projectDevelopers (projectDevId int not null auto_increment primary key, devId int, projectId int);

    -- A splendid query that joins 3 tables together and extract certain information based on a few conditions
    select projectRequests.projectReqId, projectRequests.projectId, projects.projectCategory, developers.firstName, developers.lastName, developers.email, projectRequests.status, projectRequests.devMsg 
    from (((projectRequests inner join projects on projectRequests.projectId = projects.projectId) 
    inner join developers on  projectRequests.devId = developers.devId) 
    inner join businesses on projectRequests.busId = businesses.busId) 
    where businesses.email = 'test2@test.com' and projectRequests.status = 'pending';

    -- Updates the project request of a specific developer to a specific projecct to accepted or declined
    -- Not this would update all requests made by a specific dev to a specific project however it's impossible for a  dev to make more 2 or more requests to one project therefore this is not a concern
    update projectRequests inner join businesses on projectRequests.busId = businesses.busId set projectRequests.status = 'Accepted' 
    where businesses.email = 'test@test.com' and projectRequests.devId=1;