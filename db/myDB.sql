--First Create the database
CREATE DATABASE choreboard;

--Connect to the database
\c choreboard

--Create Chores Table
CREATE TABLE public.chores (
    id SERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    rewards_id INT NOT NULL REFERENCES public.rewards(id),
    date_created DATE NOT NULL,
    date_completed DATE,
    date_due DATE,
    is_repeatable BOOLEAN NOT NULL,
    xp_reward INT NOT NULL,
    assigned_to_user INT REFERENCES public.user(id)

);

--Create Rewards table
CREATE TABLE public.rewards (
    id SERIAL NOT NULL PRIMARY KEY,
    name VARCHAR (100) NOT NULL,
    description TEXT NOT NULL,
    awarded_to_user INT REFERENCES public.user(id)
);

--Create User table
CREATE TABLE public.user
(
	id SERIAL NOT NULL PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(100) NOT NULL,
	display_name VARCHAR(100) NOT NULL,
    is_parent BOOLEAN NOT NULL,
    xp_user INT NOT NULL
    household_id INT REFERENCES public.household(id),
    email VARCHAR(255) NOT NULL
);

--Create household table
CREATE TABLE public.household (
	id SERIAL NOT NULL PRIMARY KEY,
	name_of_household VARCHAR(250) NOT NULL UNIQUE
);

--adding new column xp_user to user
ALTER TABLE public.user
ADD xp_user INT NOT NULL;

--adding new column xp_reward to rewards
ALTER TABLE public.chores
ADD xp_reward INT NOT NULL;

--adding new column awarded_to_user to rewards
ALTER TABLE public.rewards
ADD awarded_to_user INT REFERENCES public.user(id);

--adding new column assigned_to_user to chores
ALTER TABLE public.chores
ADD assigned_to_user INT REFERENCES public.user(id);

--adding new column to household
ALTER TABLE public.user
ADD household_id INT NOT NULL REFERENCES public.household(id);

--adding new column email to user
ALTER TABLE public.user
ADD email VARCHAR NOT NULL;

--###Adding testing data###--
--Reward
Insert INTO public.rewards (name, description)
VALUES ('Walk with Family', 'Go on a walk with the family');
Insert INTO public.rewards (name, description)
VALUES ('Go on an adventure', 'Go on an adventure with mom or dad');
Insert INTO public.rewards (name, description)
VALUES ('1 hr computer time', 'Get an extra hour of computer time');

--Chores
--Chores with out a completed date or due date
Insert INTO public.chores (name, description, date_created, is_repeatable, xp_reward)
VALUES('Living Room Floor', 'Clean the living room floor', current_date, FALSE, 50);

--Chores with a completed date and due date
Insert INTO public.chores (name, description, date_created, date_completed, date_due, is_repeatable, xp_reward)
VALUES('Clean Garage', 'Clean and Organize the Garage', '2021-02-02', '2021-02-04', '2021-07-01', FALSE, 200);

--Repeatable chore
Insert INTO public.chores (name, description, date_created, is_repeatable, xp_reward)
VALUES('Clean Room', 'Clean your room well', current_date, TRUE, 10);

--Repeatable chore
Insert INTO public.chores (name, description, date_created, is_repeatable, xp_reward)
VALUES('Do the Dishes', 'Empty and fill the dishwasher and start it', current_date, TRUE, 20);

--Completed Repeatable chore
Insert INTO public.chores (name, description, date_created, date_completed, is_repeatable, xp_reward)
VALUES('Clean Room', 'Clean your room well', current_date, '2021-02-03', TRUE, 10);

--Users
Insert INTO public.user (username, password, display_name,  is_parent)

--drop columns
ALTER TABLE public.user
DROP COLUMN chores_id;

ALTER TABLE public.user
DROP COLUMN rewards_id;
