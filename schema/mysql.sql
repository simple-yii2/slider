create table if not exists `Slider`
(
	`id` int(10) not null auto_increment,
	`active` tinyint(1) default 1,
	`alias` varchar(100) default null,
	`title` varchar(200) default null,
	`imageCount` int(10) default null,
	primary key (`id`),
	key `alias` (`alias`)
) engine InnoDB;

create table if not exists `SliderImage`
(
	`id` int(10) not null auto_increment,
	`slider_id` int(10) not null,
	`file` varchar(200) default null,
	`thumb` varchar(200) default null,
	`title` varchar(100) default null,
	`description` varchar(200) default null,
	`url` varchar(200) default null,
	primary key (`id`),
	foreign key (`slider_id`) references `Slider` (`id`) on delete cascade on update cascade,
	key `slider_id` (`slider_id`)
) engine InnoDB;
