CREATE TABLE IF NOT EXISTS `Discussion_Posts`(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    user_id INT NOT NULL,
    post_title VARCHAR(60) NOT NULL,
    content VARCHAR(250) NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`),
    FOREIGN KEY (`topic_id`) REFERENCES Discussion_Topics(`id`),
    UNIQUE (`post_title`)
)