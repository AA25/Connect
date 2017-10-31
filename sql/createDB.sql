create database connect;

use connect;

create table developers ( devId int not null auto_increment primary key,
    -> firstName varchar(56) not null,
    -> lastName varchar(56) not null,
    -> dob date not null,
    -> languages varchar(500) not null,
    -> email varchar(56) not null,
    -> password varchar(500) not null,
    -> speciality varchar(56) not null,
    -> devBio varchar(500) not null);

create table businesses (ownerId int not null auto_increment primary key,
    -> businessName varchar(56) not null,
    -> buisnessIndustry varchar(56) not null,
    -> buisnessBio varchar(500) not null,
    -> firstName varchar(56) not null,
    -> lastName varchar(56) not null,
    -> password varchar(500) not null,
    -> email varchar(56) not null,
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
    -> startDate date not null);

    create table projectState (projectState int not null auto_increment primary key,
    -> projectId int not null,
    -> currentState int not null);