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
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '1', 'מיהו אויב בכל מה שקשור להצפנה?', 'צד שרת', 'צד ראשון', 'צד שני', 'צד שלישי', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '1', '1', 'מה לא צריך להצפין?', 'קבצי angolar', 'הרשאת גישה', 'חתימה דיגיטלית', 'סיסמאות', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '1', 'איך מצפינים סיסמאות?', 'מעבירים אותה ל- database של המחשב', 'מעבירים אותה ב- session', 'על ידי בניית מחרוזת חדשה שהיא מוצפנת', 'על ידי שמירה במחשב', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '1', 'איך רואים משהו לאחר הצפנה?', 'מפעילים עליו אינדיקטור', 'מפעילים עליו פונקציה הופכית', 'מפעילים פונקציית ניחוש', 'מפעילים פונקציה אלמנטרית', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '2', '1', 'למי ידוע אלגוריתם ההצפנה?', 'למתכנת', 'ללקוח', 'לצד שלישי', 'לאויב', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '3', '1', 'האם ניתן לגלות את הקוד בלי הצפנה?', 'כן', 'לפעמים', 'לא', 'תלוי בסוג מחשב שבו הוכנסה הסיסמא', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '3', '1', 'מהי ההצפנה העתיקה ביותר?', 'הצפנה לינארית', 'הצפנה ריבועית', 'הצפנה סימטרית', 'הצפנה מרובע', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '3', '1', 'על מה מבוסס צופן סימטרי?', 'צופן זרם', 'צופן מראה', 'צופן בלוקים', 'תשובות א ו- ג נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '1', 'אם הסיסמא מוצפנת, האם ניתן לדעת את הקוד לאחר הצפנה?', 'לא', 'תלוי ברמת האבטחה של ההצפנה', 'תלוי במחשב שהוכנסה הסיסמא', 'תלוי אם נעשה כיבוי והפעלה של המערכת', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '1', 'אלגוריתם של צופן סימטרי מודרני?', 'DDD', 'DES', 'DSS', 'DSD', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '1', 'מהן החסרונות להצפנה סימטרית?', 'העברה', 'אחסון', 'אימות', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '1', 'בכל כניסה לאתר מסויים, האם הסיסמא מוצפנת?', 'לא', 'תלוי במתכנת', 'תלוי במשתמש', 'כן', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '1', 'איזה צופן מוצפן באמצעים אסימטריים?', 'ASR', 'SRA', 'RSA', 'SAR', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '1', 'איך נקרא צד שלישי נאמן?', 'TTP', 'TTT', 'PTT', 'PTP', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '1', 'איך נקראת דרגת ההצפנה הכי נמוכה?', 'level-l', 'low-level', 'level-0', 'l0', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '1', 'איזה אלגוריטם אסימטרי?', 'TNR', 'NTRU', 'NTR', 'NTRA', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '1', 'איך נקרא צופן של סודיות מושלמת?', 'צופן ריבועי', 'צופן אוילר', 'צופן מושלם', 'צופן ורנם', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '1', 'איך נקראת דרגת ההצפנה הכי גבוהה?', 'level-h', 'high-level', 'level-10', 'h10', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '1', 'מהו המונח של תורת ההצפנה?', 'קריפטוגרפיה', 'צופניזציה', 'הסתתרות', 'העלמות', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '1', 'מה מקור השם קריפטולוגיה?', 'רומית', 'יוונית', 'לטינית', 'אין תשובה נכונה', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '1', 'כמה דרגות יש להצפנה?', '10', '11', 'זה כל הזמן מתקדם', '9', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '1', 'מהם אבני בניין של מערכת הצפנה?', 'צופן סימטרי', 'צופן זרם', 'מפתח פומבי', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '1', 'מהם אבני הבניין של מערכת הצפנה', 'פונקציות גיבוב', 'קוד אימות מסרים', 'מחולל פסבדו אקרא', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '9', '1', 'איך נקרא אלגוריתם של הצפנה?', 'encrypting', 'conceal', 'hide', 'encoding', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '9', '1', 'איזה רכיבי תקשורת לא משתלבים עם קריפטוגרפיה?', 'תיקון שגיאות', 'גיבוב', 'איפנון', 'דחיסה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '9', '1', 'במה מטפלת קריפטוגרפיה מודרנית?', 'הבטחת שלמות', 'אימות', 'סודיות', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '1', 'איך נקרא האלגוריתם של דרגת ההצפנה הכי גבוהה?', 'ELHigh', 'ELH', 'ELMH', 'EL10', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '1', 'כמה צעדים יש בבעיית אד הוק?', '2', '3', '4', '5', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '1', 'מהו הצעד השני של בעיית אד הוק?', 'בניית מערכת', 'הגדרת דרישות', 'השערה', 'אין תשובה נכונה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '1', '2', 'כיצד ניתן לאבטח סיסמאות?', 'על ידי שמירה ב- session', 'עלי ידי הצפנה', 'על ידי מחיקה מהירה', 'על ידי הכנסה של תווים שונים', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '2', 'מהם הענפים שבהם עוסק אבטחת מידע?', 'גישה', 'שימוש', 'חשיפה', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '1', '2', 'מהם הענפים שבהם עוסק אבטחת מידע?', 'ציטוט', 'שיבוש', 'העתקה או השמדה של מידע', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '2', '2', 'האם המחשב שלך מאובטח כאשר אין חיבור לאינטרנט?', 'לא', 'תלוי איזה תוכנות עובדות', 'לפעמים', 'כן', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '2', '2', 'מפני מי צריך לאבטח?', 'גורמים שאינם מורשים', 'גורמים זדוניים', 'תשובות א ו- ב נכונות', 'אף תשובה לא נכונה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '2', '2', 'מהי מטרת אבטחת מידע?', 'לספק סודיות', 'לשמור מפני וירוסים', 'לשמור מפני תקלות', 'תשובות ב ו- ג נכונות', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '2', 'האם המחשב מאובטח כאשר הוא מכובה?', 'תלוי איך כיבינו אותו', 'כן', 'לא', 'תלוי באיזה תוכנות עבדנו לפני', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '2', 'מהם היעדים העיקריים של אבטחת מידע?', 'שלמות', 'סודיות', 'זמינות', 'כל התשובות נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '2', 'כמה יעדים עיקריים יש לאבטחת מידע?', '2', '3', '4', '5', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '2', 'האם חשוב לאבטח את המחשב?', 'כן', 'לא', 'תלוי מה יש לנו בתוך המחשב', 'רק דברים שרוצים להסתיר', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '2', 'אילו רבדים כוללת האבטחה?', 'אבטחה פיזית', 'אבטחה של מערכות החומרה', 'תשובות א ו- ב נכונות', 'אין תשובה נכונה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '2', 'אילו רבדים כוללת האבטחה?', 'אבטחת רכיבי אינטרנט', 'אבטחת מידע', 'תשובות א ו- ב נכונות', 'אין תשובה נכונה', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '2', 'איזה תוכנה מתאימה לאבטחה?', 'VISUAL STUDIO', 'PHP', 'FIREWALL', 'XAMPP', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '2', 'האם למאגר מבודל מאינטרנט ניתן לגרום נזק?', 'לא', 'כן', 'תלוי בכמה דברים', 'אף תשובה לא נכונה', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '2', 'מהי שלמות במה שקשור לאבטחה?', 'הגנה מפני שינוי זדוני', 'אבטחה שלמה', 'תשובות א ו- ב נכונות', 'אין תשובה נכונה', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '6', '2', 'מהי רמת האבטחה הנמוכה ביותר?', 'level-l', 'low-level', 'level-0', 'l0', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '6', '2', 'מהי סודיות במה שקשור לאבטחה?', 'שמירה', 'הגבלת גישה', 'חשיפה', 'תשובות ב ו- ג נכונות', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '6', '2', 'מהי זמינות בכל מה שקשור לאבטחה?', 'שמירה על זמינות', 'תשובות א ו- ג נכונות', 'יעילות גישה אל המידע', 'פרמטרי אבטחה זמינים', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '2', 'מה רמת האבטחה הגבוהה ביותר?', 'level-h', 'high-level', 'level-10', 'h10', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '2', 'כמה שכבות יש בהגנת Defense in Depth?', '4', '5', '6', '7', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '7', '2', 'איזה מערכת קבצים מאפשרת שימוש בהרשאות?', 'NTFS', 'DN', 'NDF', 'ND', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '2', 'כמה דרגות אבטחה קיימות?', '10', '11', 'זה כל הזמן מתקדם', '9', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '2', 'לכמה קבוצות ניתן לחלק את אמצעי ההזדהות השונים?', '2', '3', '4', '5', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '2', 'איך נקרא מנגנון ייצור סיסמא חד פעמית?', 'OPN', 'OPT', 'POT', 'OTP', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '2', 'איך נקרא מחשב לא מאובטח?', 'ISL0', 'ISL', 'ISLevel0', 'ISLow', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '2', 'מה כוללת אבטחת IT?', 'מחשב לוח', 'מחשבון', 'כל התשובות נכונות', 'שרת קבצים', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '2', 'מהו DiD?', 'גורס שמירה על איזון בין רמות ההגנה', 'כל התשובות נכונות', 'עלות', 'שיקולי תפעול', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '10', '2', 'איך נקראת רמת האבטחה הגבוהה ביותר?', 'ISH10', 'ISH', 'ISLevel10', 'ISHigh', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '10', '2', 'כיום, בכמה מודלים מתבססים באבטחת מידע?', '2', '3', '4', '5', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '10', '2', 'איך נקראים מודלים האבטחה שמתבססים עליהם כיום?', 'מודל מעגלים', 'מודל 7 השכבות', 'תשובות א ו- ב נכונות', 'אין תשובה נכונה', '3'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '3', 'האם ניתן מרשת 1 לחדור לרשת 2?', 'לא', 'תלוי מתי', 'כן', 'רק אם אין שימוש ב- firewall', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '3', 'איך ניתן לחדור לרשת?', 'סוס טרויאני', 'רוגלה', 'תשובות א ו- ב נכונות', 'אין תשובה נכונה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '1', '3', 'מה מגדירה טופולוגיית רשת?', 'אין תשובה נכונה', 'קשרים בין מתכנתים ', 'קשרים בין רשתות', 'הקשרים בין הקצוות', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '3', 'מה צריך לעשות כדי לעבור מרשת 1 לרשת 2?', 'להכניס את ה- IP של רשת 2 לחיפוש של רשת 1', 'להפעיל את התוכנה שרשת 2 מפעיל', 'לפרוץ לרשת 2', 'לא ניתן לעבור', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '3', 'איזה תחום נכלל בתוך טופולוגיית רשת?', 'רוחב פס', 'אין תשובה נכונה', 'צורת שידור', 'חיבורים פיזייים', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '2', '3', 'באיזה דרך ניתן ליצור רשת עם שישה מחשבים?', 'טבעת', 'כוכב', 'אוטובוס', 'כל התשובות נכונות', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '3', 'איך מורכבת רשת ביתית?', 'מחשב וגישה לאינטרנט', 'מחשב ומדפסת', 'מחשב ומקלדת', 'מחשב ודיסק אונקי', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '3', 'האם יש הפסדים בעלות בין רשת טבעת לטבעת כפולה?', 'אין הבדל', 'טבעת כפולה יקרה פי 2', 'טבעת יקרה פי 2', 'טבעת כפולה יקרה פי 3', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '3', '3', 'באיזה רמה מיושמת טופולוגיית טבעת?', 'ברמה הלוגית', 'ברמה החומרתית', 'ברמת ביחיד', 'ברמה האוניברסלית', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '3', 'איזה מושג מאפיין רשת?', 'www', 'net', 'http', 'firewall', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '3', 'מה היתרון בטופולוגיית אפיק עם קצת מחשבים?', 'יעילות', 'עמידות', 'חסכון בעלויות', 'כל התשובות נכונות', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '4', '3', 'מה החסרון בטופולוגיית אפיק עם הרבה מחשבים?', 'יעילות', 'עמידות', 'חסכון בעלויות', 'אין תשובה נכונה', '1'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '3', 'כמה סוגי רשת קיימים ברשת ביתית?', '2', '3', '4', '5', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '3', 'מה חייב לשים בקצוות בטופולוגיית אפיק?', 'שני מחשבים', 'סיומת המאפשרת התפרקות חשמלית', 'מצד אחד ראווטר ומצד שני מחשב', 'אין תשובה נכונה', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '5', '3', 'מה היתרונות של טופולוגיית אריג?', 'יעילות', 'עמידות', 'חסכון בעלויות', 'כל התשובות נכונות', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '3', 'כמה סוגי רשת קיימים?', '10', '15', '16', '17', '2');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '3', 'מה החסרון העיקרי בטופולוגיית חיבור מלא?', 'לא יעיל', 'לא עמיד', 'בזבזני במיוחד', 'כל התשובות נכונות', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '6', '3', 'מה היתרון העיקרי בטופולוגיית חיבור מלא?', 'עמידות', 'נגישות', 'יעילות', 'אין תשובה נכונה', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '7', '3', 'מהי הרשת הכי פופלרית היום?', 'net-1', 'net-2', 'net-3', 'net-4', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '7', '3', 'כמה חיבורים נצטרך ל- 30 מחשבים במבנה כוכב?', '15', '45', '30', '29', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '7', '3', 'כמה חיבורים נצטרך ל- 30 מחשבים בחיבור מלא?', '435', '300', '350', '400', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '3', 'באיזה רשת לא ארצה להתמשמש עבור רשת שמכילה 10 מחשבים?', 'net-1', 'net-2', 'net-3', 'net-4', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '3', 'מה מהבאים מהווה דוגמא לרשת היררכית?', 'DNS', 'DEM', 'DDS', 'NDN', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '2', '8', '3', 'למה אפשר להשוות רשת היררכית?', 'גרפים', 'רשימה', 'תור', 'עץ', '4'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '3', 'מה השיקולים לבחירת סוג רשת?', 'כמות המחשבים', 'רמות האבטחה בין המחשבים', 'מיקום המחשבים', 'תלוי במספר המחשבים המאובטחים', '1');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '3', 'כמה דרגות יש לטופולוגיית כוכב מורכב?', '1', '2', '2 או יותר', 'אין תשובה נכונה', '3');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '9', '3', 'מה היתרון בשיטת כוכב?', 'כמות המחשבים', 'תחזוקה', 'עלויות', 'עמידות', '2'); 
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '3', 'אם הצלחתי לפרוץ למחשב אחד ברשת, האם הצלחתי לפרוץ לכל השאר?', 'כן', 'לא', 'תלוי באבטחת הרשת', 'תלוי בין האבטחה של המחשב לשאר המחשבים ברשת', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '3', 'מהו גשר?', 'תשובות ג ו- ד נכונות', 'אין תשובה נכונה', 'מחבר בין המחשב לרשת', 'מחבר בין שני חלקי רשת', '4');
INSERT INTO `questions` (`id`, `user_id`, `level_id`, `category_id`, `title`, `answer1`, `answer2`, `answer3`, `answer4`, `right_answer`) VALUES (NULL, '5', '10', '3', 'מה החסרון הגדול בטופולוגיית כוכב?', 'בזבזני במיוחד', 'אין חסרונות', 'לא עמיד', 'אין תשובה נכונה', '1');