drop database if exists test1;
drop user if exists test;

create database test1;

create user 'test'@'%' identified by 'test';
grant all privileges on test1.* to 'test'@'%';


create table test1.currency_course(
    id int not null auto_increment,
    dt datetime not null,
    numeric_code varchar(10) not null,
    char_code varchar(10) not null,
    nominal int not null,
    name varchar(100) not null,
    value decimal(10,4) not null default 0,
    is_active int not null default 0,
    primary key (id)
);
