
--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `last_login` timestamp NULL DEFAULT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(64) NOT NULL,
    `salt` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
