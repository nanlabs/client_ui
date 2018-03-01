CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);



create table user_dashboards (
  id int(11) not null auto_increment,
  username varchar(50) NOT NULL,
  dashboard_num int not null,
  dashboard_name varchar(50) NOT NULL,
  dashboard_url varchar(50) NOT NULL,
  dashboard_category varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

create table user_reports (
  id int(11) not null auto_increment,
  username varchar(50) NOT NULL,
  report_num int not null,
  report_name varchar(50) NOT NULL,
  repprt_url varchar(50) NOT NULL,
  report_category varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

create table user_file_locations (
  id int(11) not null auto_increment,
  username varchar(50) NOT NULL,
  file_type_num int not null,
  file_type_name varchar(50) NOT NULL,
  file_type_path varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

create table recovery_tokens (
  id int(11) not null auto_increment,
  username varchar(50) not null,
  token varchar(64) not null,
  unique key token_idx (token),
  primary key (id)
);

insert into user_dashboards values (default, 'dave', 1, 'my_dash', 'http://www.example.com/path1/path2/url1', 'first_category');
insert into user_dashboards values (default, 'dave', 2, 'other_dash', 'http://www.example.com/path1/path2/url2', 'second_category');


insert into user_reports values (default, 'dave', 1, 'my_r', 'http://www.example.com/path1/path2/url1', 'first_category');
insert into user_reports values (default, 'dave', 2, 'other_r', 'http://www.example.com/path1/path2/url2', 'second_category');

insert into user_file_locations values (default, 'dave', 1,'amazon', '/uploads/dave/amazon/');
insert into user_file_locations values (default, 'dave', 2,'ebay', '/uploads/dave/ebay/');