<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * SQL Code to create and populate tables
 */

// SQL scripts
$sql_scripts = [

    "CREATE TABLE tv_series (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL unique,
        channel VARCHAR(50) NOT NULL,
        genre VARCHAR(50) NOT NULL
    )",

    "CREATE TABLE tv_series_intervals (
        id_tv_series INT,
        week_day VARCHAR(10) NOT NULL,
        show_time TIME NOT NULL,
        FOREIGN KEY (id_tv_series) REFERENCES tv_series(id),
        UNIQUE(id_tv_series, week_day, show_time) -- composite unique key
    )",

    "INSERT INTO tv_series (title, channel, genre) VALUES
        ('Friends', 'NBC', 'Comedy'),
        ('Game of Thrones', 'HBO', 'Fantasy'),
        ('Breaking Bad', 'AMC', 'Drama'),
        ('Stranger Things', 'Netflix', 'Science Fiction')",

    "INSERT INTO tv_series_intervals (id_tv_series, week_day, show_time) VALUES
        (1, 'Monday', '20:00:00'),
        (1, 'Wednesday', '21:00:00'),
        (2, 'Tuesday', '22:30:00'),
        (2, 'Thursday', '20:00:00'),
        (3, 'Friday', '19:30:00'),
        (3, 'Sunday', '18:45:00'),
        (4, 'Saturday', '19:30:00'),
        (4, 'Sunday', '17:30:00')
        "
];
