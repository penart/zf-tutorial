drop database if exists test1;
drop user if exists test;

create database test1;

create user 'test'@'%' identified by 'test';
grant all privileges on test1.* to 'test'@'%';


create table test1.currency_course(
    id int not null auto_increment,
    dt datetime not null default NOW(),
    numeric_code varchar(10) not null default '',
    char_code varchar(10) not null default '',
    nominal smallint not null default 1,
    name varchar(100) not null default '',
    value decimal(10,4) not null default 0,
    is_active tinyint(1) not null default 0,
    primary key (id)
);
