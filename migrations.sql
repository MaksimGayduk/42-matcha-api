create table users (id SERIAL PRIMARY KEY, login VARCHAR(32) unique , first_name VARCHAR(32), last_name VARCHAR(32), age int, password VARCHAR(32), email VARCHAR(32), checked BOOLEAN DEFAULT FALSE, date_create TIMESTAMP DEFAULT NOW());
insert into users (user_name, first_name, last_name, age, password, email, date_create) VALUES ('test1', 'fn1', 'ln1', 1, 'Abcd123@a', 'Abcd123@gmail.com', NOW());
insert into users (user_name, first_name, last_name, age, password, email, date_create) VALUES ('test2', 'fn2', 'ln2', 2, 'Abcd123@b', 'Bbcd123@gmail.com', NOW());
insert into users (user_name, first_name, last_name, age, password, email, date_create) VALUES ('test3', 'fn3', 'ln3', 3, 'Abcd123@c', 'Cbcd123@gmail.com', NOW());

create table comments (id SERIAL PRIMARY KEY, users_id INT REFERENCES users (id), text VARCHAR(100), date_create TIMESTAMP DEFAULT NOW());
insert into comments (users_id, text, date_create) VALUES (1, 'some text 1', NOW());
insert into comments (users_id, text, date_create) VALUES (2, 'some text 1', NOW());
insert into comments (users_id, text, date_create) VALUES (2, 'some text 1', NOW());

create table likes(id SERIAL PRIMARY KEY, users_id INT REFERENCES users (id), comments_id INT REFERENCES comments (id));
insert into likes (users_id, comments_id) VALUES (1, 1);
insert into likes (users_id, comments_id) VALUES (1, 1);
insert into likes (users_id, comments_id) VALUES (2, 2);
insert into likes (users_id, comments_id) VALUES (2, 2);
