create table users (id SERIAL PRIMARY KEY, login VARCHAR(32) unique , first_name VARCHAR(32), last_name VARCHAR(32), age int);
insert into users (login, first_name, last_name, age) VALUES ('test1', 'fn1', 'ln1', 1);
insert into users (login, first_name, last_name, age) VALUES ('test2', 'fn2', 'ln2', 2);
insert into users (login, first_name, last_name, age) VALUES ('test3', 'fn3', 'ln3', 3);
insert into users (login, first_name, last_name, age) VALUES ('test4', 'fn4', 'ln4', 4);
insert into users (login, first_name, last_name, age) VALUES ('test5', 'fn5', 'ln5', 5);
insert into users (login, first_name, last_name, age) VALUES ('test6', 'fn6', 'ln6', 6);
insert into users (login, first_name, last_name, age) VALUES ('test7', 'fn7', 'ln7', 7);
insert into users (login, first_name, last_name, age) VALUES ('test8', 'fn8', 'ln8', 8);
insert into users (login, first_name, last_name, age) VALUES ('test9', 'fn9', 'ln9', 9);
insert into users (login, first_name, last_name, age) VALUES ('test10', 'fn10', 'ln10', 10);

create table comments (id SERIAL PRIMARY KEY, users_id INT REFERENCES users (id), text VARCHAR(100), date_create timestamp);
insert into comments (users_id, text, date_create) VALUES (1, 'some text 1', now());
insert into comments (users_id, text, date_create) VALUES (1, 'some text 1', now());
insert into comments (users_id, text, date_create) VALUES (1, 'some text 1', now());
insert into comments (users_id, text, date_create) VALUES (2, 'some text 1', now());
insert into comments (users_id, text, date_create) VALUES (2, 'some text 1', now());

create table likes(id SERIAL PRIMARY KEY, users_id INT REFERENCES users (id), comments_id INT REFERENCES comments (id));
insert into likes (users_id, comments_id) VALUES (1, 1);
insert into likes (users_id, comments_id) VALUES (1, 1);
insert into likes (users_id, comments_id) VALUES (2, 2);
insert into likes (users_id, comments_id) VALUES (2, 2);