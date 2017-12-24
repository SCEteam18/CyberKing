CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(30) NOT NULL,
 `username` varchar(20) NOT NULL,
 `password` varchar(200) NOT NULL,
 `firstname` varchar(20) NOT NULL,
 `lastname` varchar(20) NOT NULL,
 `score` int(11) NOT NULL DEFAULT '0',
 `startDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `question_num` int(3) NOT NULL DEFAULT '0',
 `question_id` int(3) NOT NULL DEFAULT '0',
 `max_score` int(11) NOT NULL DEFAULT '0',
  `enabled` int(2) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)) ENGINE=InnoDB;

INSERT INTO users (email, username, password, firstname, lastname) VALUES ('test1@test.com', 'test1', '$2y$10$loLuYsWPhCAzX71lfPQyAOZsNgjpziaBwbP0mBV.6/9zBqdanvN9O', 'David', 'Cohen');
-- the password of test1 is 1qaz2wsx