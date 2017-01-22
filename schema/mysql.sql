create table if not exists `Slider`
(
	`id` int(10) not null auto_increment,
	`tree` int(10),
	`lft` int(10) not null,
	`rgt` int(10) not null,
	`depth` int(10) not null,
	`active` tinyint(1) default 1,
	`alias` varchar(100) default null,
	`height` int(10) not null,
	`file` varchar(200) default null,
	`thumb` varchar(200) default null,
	`title` varchar(200) default null,
	`description` varchar(200) default null,
	`url` varchar(200) default null,
	`background` varchar(10) default null,
	primary key (`id`),
	key `lft` (`lft`),
	key `alias` (`alias`)
) engine InnoDB;
