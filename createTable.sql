CREATE DATABASE cyberking;
USE cyberking;

CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `password` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `email` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `firstname` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `lastname` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'player',
 `enabled` int(1) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `questionlevels` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `score` int(5) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `players` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `startDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `question_num` int(3) NOT NULL DEFAULT '0',
 `score` int(5) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `players_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `categories` (
 `id` int(2) NOT NULL AUTO_INCREMENT,
 `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `categoryrecords` (
 `player_id` int(11) NOT NULL,
 `category_id` int(11) NOT NULL,
 `max_score` int(6) NOT NULL,
 KEY `player_id` (`player_id`),
 KEY `category_id` (`category_id`),
 CONSTRAINT `categoryrecords_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
 CONSTRAINT `categoryrecords_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `questions` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `level_id` int(2) NOT NULL,
 `category_id` int(2) NOT NULL,
`title` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `answer1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `answer2` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `answer3` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `answer4` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `right_answer` int(1) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 KEY `level_id` (`level_id`),
 KEY `category_id` (`category_id`),
 CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
 CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `questionlevels` (`id`),
 CONSTRAINT `questions_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `playerquestions` (
 `player_id` int(11) NOT NULL,
 `question_id` int(11) NOT NULL,
 KEY `player_id` (`player_id`),
 KEY `question_id` (`question_id`),
 CONSTRAINT `playerquestions_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`),
 CONSTRAINT `playerquestions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB;

-- insert users--
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `type`, `enabled`) VALUES (NULL, 'mosheli', '$2y$10$Cmy94jDnwhBVmLBQN8AKe.k59QkS9OpWBnV5xj4YPTqTpLlmCN2Ka', 'moshe.lipeles@gmail.com', 'משה', 'ליפלס', 'player', '1');
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `type`, `enabled`) VALUES (NULL, 'michael_1', '$2y$10$Cmy94jDnwhBVmLBQN8AKe.k59QkS9OpWBnV5xj4YPTqTpLlmCN2Ka', 'michael@gmail.com', 'מיכאל', 'זושצ\'יק', 'manager', '1');
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `type`, `enabled`) VALUES (NULL, 'ruff_1', '$2y$10$Cmy94jDnwhBVmLBQN8AKe.k59QkS9OpWBnV5xj4YPTqTpLlmCN2Ka', 'ruff@gmail.com', 'רפי', 'דוד', 'player', '1');
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `type`, `enabled`) VALUES (NULL, 'hadas_ha', '$2y$10$Cmy94jDnwhBVmLBQN8AKe.k59QkS9OpWBnV5xj4YPTqTpLlmCN2Ka', 'hadas_ha@gmail.com', 'הדס', 'חסידים', 'player', '1');
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `type`, `enabled`) VALUES (NULL, 'admin', '$2y$10$Cmy94jDnwhBVmLBQN8AKe.k59QkS9OpWBnV5xj4YPTqTpLlmCN2Ka', 'admin@test.com', 'admin', 'admin', 'manager', '1');

-- insert questionlevels--
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '100');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '150');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '200');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '300');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '400');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '500');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '600');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '700');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '800');
INSERT INTO `questionlevels` (`id`, `score`) VALUES (NULL, '1000');


-- insert categories--
INSERT INTO `categories` (`id`, `name`) VALUES (NULL, 'הצפנות');
INSERT INTO `categories` (`id`, `name`) VALUES (NULL, 'אבטחה');
INSERT INTO `categories` (`id`, `name`) VALUES (NULL, 'רשתות');

-- insert players--
INSERT INTO `players` (`id`, `user_id`, `startDate`, `question_num`, `score`) VALUES (NULL, '1', CURRENT_TIMESTAMP, '30', '1500');
INSERT INTO `players` (`id`, `user_id`, `startDate`, `question_num`, `score`) VALUES (NULL, '4', CURRENT_TIMESTAMP, '6', '100');

-- insert categoryrecords--
INSERT INTO `categoryrecords` (`player_id`, `category_id`, `max_score`) VALUES ('1', '1', '1000');
INSERT INTO `categoryrecords` (`player_id`, `category_id`, `max_score`) VALUES ('2', '2', '50');

-- insert questions--
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '1', '1', 'מה כדאי להצפין במחשב?', 'קבצי וורד', 'שירים', 'קבצי כתבן', 'סיסמאות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '1', 'איך מצפינים סיסמאות?', 'מעבירים אותה ל- database של המחשב', 'מעבירים אותה ב- session', 'על ידי בניית מחרוזת חדשה שהיא מוצפנת', 'על ידי שמירה במחשב', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '3', '1', 'האם ניתן לגלות את הקוד בלי הצפנה?', 'כן', 'לפעמים', 'לא', 'תלוי בסוג מחשב שבו הוכנסה הסיסמא', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '1', 'אם הסיסמא מוצפנת, האם ניתן לדעת את הקוד לאחר הצפנה?', 'לא', 'תלוי ברמת האבטחה של ההצפנה', 'תלוי במחשב שהוכנסה הסיסמא', 'תלוי אם נעשה כיבוי והפעלה של המערכת', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '1', 'בכל כניסה לאתר מסויים, האם הסיסמא מוצפנת?', 'לא', 'תלוי במתכנת', 'תלוי במשתמש', 'כן', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '1', 'איך נקראת דרגת ההצפנה הכי נמוכה?', 'level-l', 'low-level', 'level-0', 'l0', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '1', 'איך נקראת דרגת ההצפנה הכי גבוהה?', 'level-h', 'high-level', 'level-10', 'h10', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '1', 'כמה דרגות יש להצפנה?', '10', '11', 'זה כל הזמן מתקדם', '9', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '9', '1', 'איך נקרא אלגוריתם של הצפנה?', 'encrypting', 'conceal', 'hide', 'encoding', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '1', 'איך נקרא האלגוריתם של דרגת ההצפנה הכי גבוהה?', 'ELHigh', 'ELH', 'ELMH', 'EL10', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '1', '2', 'כיצד ניתן לאבטח סיסמאות?', 'על ידי שמירה ב- session', 'עלי ידי הצפנה', 'על ידי מחיקה מהירה', 'על ידי הכנסה של תווים שונים', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '2', '2', 'האם המחשב שלך מאובטח כאשר אין חיבור לאינטרנט?', 'לא', 'תלוי איזה תוכנות עובדות', 'לפעמים', 'כן', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '2', 'האם המחשב מאובטח כאשר הוא מכובה?', 'תלוי איך כיבינו אותו', 'כן', 'לא', 'תלוי באיזה תוכנות עבדנו לפני', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '2', 'האם חשוב לאבטח את המחשב?', 'כן', 'לא', 'תלוי מה יש לנו בתוך המחשב', 'רק דברים שרוצים להסתיר', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '2', 'איזה תוכנה מתאימה לאבטחה?', 'VISUAL STUDIO', 'PHP', 'FIREWALL', 'XAMPP', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '6', '2', 'מהי רמת האבטחה הנמוכה ביותר?', 'level-l', 'low-level', 'level-0', 'l0', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '2', 'מה רמת האבטחה הגבוהה ביותר?', 'level-h', 'high-level', 'level-10', 'h10', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '2', 'כמה דרגות אבטחה קיימות?', '10', '11', 'זה כל הזמן מתקדם', '9', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '2', 'איך נקרא מחשב לא מאובטח?', 'ISL0', 'ISL', 'ISLevel0', 'ISLow', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '10', '2', 'איך נקראת רמת האבטחה הגבוהה ביותר?', 'ISH10', 'ISH', 'ISLevel10', 'ISHigh', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '3', 'האם ניתן מרשת 1 לחדור לרשת 2?', 'לא', 'תלוי מתי', 'כן', 'רק אם אין שימוש ב- firewall', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '3', 'מה צריך לעשות כדי לעבור מרשת 1 לרשת 2?', 'להכניס את ה- IP של רשת 2 לחיפוש של רשת 1', 'להפעיל את התוכנה שרשת 2 מפעיל', 'לפרוץ לרשת 2', 'לא ניתן לעבור', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '3', 'איך מורכבת רשת ביתית?', 'מחשב וגישה לאינטרנט', 'מחשב ומדפסת', 'מחשב ומקלדת', 'מחשב ודיסק אונקי', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '3', 'איזה מושג מאפיין רשת?', 'www', 'net', 'http', 'firewall', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '3', 'כמה סוגי רשת קיימים ברשת ביתית?', '2', '3', '4', '5', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '3', 'כמה סוגי רשת קיימים?', '10', '15', '16', '17', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '7', '3', 'מהי הרשת הכי פופלרית היום?', 'net-1', 'net-2', 'net-3', 'net-4', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '3', 'באיזה רשת לא ארצה להתמשמש עבור רשת שמכילה 10 מחשבים?', 'net-1', 'net-2', 'net-3', 'net-4', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '3', 'מה השיקולים לבחירת סוג רשת?', 'כמות המחשבים', 'רמות האבטחה בין המחשבים', 'מיקום המחשבים', 'תלוי במספר המחשבים המאובטחים', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '3', 'אם הצלחתי לפרוץ למחשב אחד ברשת, האם הצלחתי לפרוץ לכל השאר?', 'כן', 'לא', 'תלוי באבטחת הרשת', 'תלוי בין האבטחה של המחשב לשאר המחשבים ברשת', '4'); 
