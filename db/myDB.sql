--First Create the database
CREATE DATABASE choreboard;

--Connect to the database
\c choreboard

--Create Chores Table
CREATE TABLE public.chores (
    id SERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    reward VARCHAR (255),
    date_created DATE NOT NULL,
    date_completed DATE,
    date_due DATE,
    is_repeatable BOOLEAN NOT NULL

);

--Create Rewards table
CREATE TABLE public.rewards (
    id SERIAL NOT NULL PRIMARY KEY,
    name VARCHAR (100) NOT NULL,
    description TEXT NOT NULL
);

--Create User table
CREATE TABLE public.user
(
	id SERIAL NOT NULL PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(100) NOT NULL,
	display_name VARCHAR(100) NOT NULL,
    is_parent BOOLEAN NOT NULL,
    chores_id INT NOT NULL REFERENCES public.chores(id),
	rewards_id INT NOT NULL REFERENCES public.rewards(id)
);

--adding new column xp_user to user
ALTER TABLE public.user
ADD xp_user INT NOT NULL;

--adding new column xp_reward to rewards
ALTER TABLE public.chores
ADD xp_reward INT NOT NULL;