create database alarm;
use alarm;
create table alarm (
  id int not null auto_increment,
  ts timestamp,
  sound char(80) not null,
  light char(80) not null,
  primary key(id));
GRANT ALL ON alarm.* TO 'alarm'@'localhost' IDENTIFIED BY 'alarm';
FLUSH PRIVILEGES;
