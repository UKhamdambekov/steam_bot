create table user(
    id_user int AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    login varchar(255),
    password varchar(255),
    role varchar(25),
    active boolean
);

insert into user values(1, 'Администратор', 'admin', '4d07cd0157cb9c3e5b6edb850337d493', 'admin', 1);

create table student(
    id int,
    name varchar(255),
    district varchar(255),
    school varchar(255),
    class int,
    lang varchar(5),
    status boolean
);

create table files(
    id_file int AUTO_INCREMENT PRIMARY KEY,
    id int,
    file varchar(255),
    type int,
    date datetime,
    flag varchar(25)
);

create table putset(
    id int PRIMARY KEY,
    flag varchar(15)
);

create table winner(
    id_winner int AUTO_INCREMENT PRIMARY KEY,
    id int,
    name varchar(255),
    flag varchar(25),
    about varchar(255),
    id_file int,
    CONSTRAINT fk_file FOREIGN KEY(id_file)
    REFERENCES files(id_file)
);

create table lng(
    id int AUTO_INCREMENT PRIMARY KEY,
    ru varchar(255),
    uz varchar(255),
    en varchar(255),
    flag varchar(255)
);

create table classes(
    id int AUTO_INCREMENT PRIMARY KEY,
    command varchar(25) UNIQUE,
    ru varchar(255),
    uz varchar(255),
    en varchar(255),
    active boolean DEFAULT 0
);

create table setting(
    id int AUTO_INCREMENT PRIMARY KEY,
    bottoken varchar(255) UNIQUE,
    url varchar(255)
);

create table template(
    id int AUTO_INCREMENT PRIMARY KEY,
    text varchar(255)
);
