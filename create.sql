    create database zaiseki default character set utf8;

use zaiseki;

drop table if exists message;
create table message(
    message_id int(11) not null auto_increment,
    to_user_id int(11) not null ,
    pass_sec varchar(255) not null default '',
    pass_tel varchar(255) not null default '',
    pass_name varchar(255) not null default '',
    msec int(11) not null,
    message varchar(255) not null default '',
    from_user_id int(11) not null,
    modified_at datetime not null default current_timestamp,
    created_at datetime not null default current_timestamp,
    primary key(message_id)
)ENGINE=InnoDB default charset=utf8;

drop table if exists status;
create table status (
    user_id int(11) not null auto_increment,
    present int(11) not null default '0',
    destination varchar(255) not null default '',
    reach_time varchar(255) not null default '',
    memo varchar(255) not null default '',
    modified_at datetime not null default current_timestamp,
    created_at datetime not null default current_timestamp,
    primary key (user_id)
)ENGINE=InnoDB default charset=utf8;

drop table if exists user;
create table user (
    id int(11) not null auto_increment,
    user_name varchar(255) not null,
    password varchar(255) not null,
    name varchar(255)  not null,
    created_at datetime default null,
    primary key (id),
    unique key user_name_index (user_name)
)ENGINE=InnoDB default charset=utf8;