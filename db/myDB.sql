--First Create the database
CREATE DATABASE choreboard;

--Connect to the database
\c choreboard

--Create Chores Table
CREATE TABLE public.chores (
    id SERIAL NOT NULL PRIMARY KEY,
    chore_name VARCHAR(100) NOT NULL,
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

--Create table chore_library
CREATE TABLE public.chore_library (
    id SERIAL NOT NULL PRIMARY KEY,
    chore_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    is_repeatable BOOLEAN NOT NULL,
    xp_reward INT NOT NULL,
    reward_library_id INT NOT NULL REFERENCES public.reward_library(id),
    household_id INT NOT NULL REFERENCES piblic.household(id)
);

--Create table reward_library
CREATE TABLE public.reward_library (
    id SERIAL NOT NULL PRIMARY KEY,
    reward_name VARCHAR (100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    household_id INT NOT NULL REFERENCES piblic.household(id)
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

--Select chores from chore library
SELECT chore_library.chore_name, chore_library.description, chore_library.xp_reward, 
	reward_library.id, reward_library.reward_name, household.id, household.name_of_household
    FROM chore_library
    LEFT JOIN reward_library
    ON chore_library.reward_library_id=reward_library.id
	LEFT JOIN public.household
	ON chore_library.household_id=household.id
	Where household.name_of_household = 'Bunker'
    ORDER BY chore_library.chore_name

--Select rewards from reward library
SELECT reward_library.id, reward_library.reward_name, reward_library.description, household.id, 
	household.name_of_household
    FROM reward_library
	LEFT JOIN public.household
	ON reward_library.household_id=household.id
	Where household.name_of_household = 'Bunker'
    ORDER BY reward_library.reward_name

--Insert into Rewards Library
INSERT INTO reward_library(reward_name, description, household_id)
    VALUES ('Picture Walk', 'Go on a walk and take pictures', 2);

--Insert into chore library
INSERT INTO chore_library(chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id)
    Values ('Mow the Grass', 'Mow the grass in the back yard', false, 100, 6, 2);

--Update Rewards Library
UPDATE reward_library
    SET reward_name = 'Family Walk', description = 'Go on walk with family'
    WHERE id = 1 AND household_id = 1;

--Update chores library
UPDATE chore_library
	SET chore_name = 'Bedroom', description ='Clean room completely, except for vacuuming or mopping.', is_repeatable = TRUE,
        xp_reward = 25, reward_library_id = 1
    WHERE id = 27 AND household_id = 1;

--Delete from chore library
DELETE FROM chore_library
    WHERE id = 37;
    
--Delete from rewards library
    --NOTE: can only delete this if no cores with this reward id exist
DELETE FROM reward_library
    WHERE id = 4;

--Insert chores from chores library into chores table
INSERT INTO chores (chore_name, description, is_repeatable, xp_reward, rewards_id, household_id)
SELECT chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id
FROM chore_library
WHERE chore_id = 27;

--Assign a chore to a user.
INSERT INTO chores (chore_name, description, is_repeatable, xp_reward, rewards_id, household_id, assigned_to_user_id)
	  SELECT chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id, (SELECT users.id FROM users WHERE id = 3)
	  FROM chore_library
	  WHERE chore_id = 29;
