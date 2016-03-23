--
-- create DATABASE: mvc :in PhpMyAdmin first
--
CREATE TABLE posts (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(128) NOT NULL,
    content text NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump data into table posts
--
INSERT INTO posts (title, content) VALUES
('first post', 'this is 1 post'),
('second post', 'this is 2 post!'),
('third post', 'this is 3 post!!');