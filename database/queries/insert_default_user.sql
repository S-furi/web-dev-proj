-- Creating a user that is capable of making only SELECT, INSERT and UPDATE
-- operations, so that no one is able to delete the DB except the administrator.

CREATE USER 'sec_user'@'localhost' IDENTIFIED BY 'eKcGZr59zAa2BEWU';
GRANT SELECT, INSERT, UPDATE ON `secure_login`.* TO 'sec_user'@'localhost';
