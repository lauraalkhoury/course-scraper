--CREATE DATABASE TABLES
--=======================
drop table courses;
create table if not exists courses(
      crn integer primary key NOT NULL,
      term text NOT NULL,
      subject text NOT NULL,
      title text NOT NULL,
      description text NOT NULL
      );

--INSERT DATA
--=======================

-- begin transaction;
-- TEST INSERT
-- insert into courses(crn, term, subject, title, description) values (10002, "W15", "MATH", "Calculus", "Calc 1");
-- end transaction;