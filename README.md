## Introduction
Introduction


Tools :

For the use Cases;  you should have the following tools already installed on your local :
Git
IDE like VS Code or PhpStorm 
API Testing tool like Postman
Mysql database 

Requiements :

Framework :  Symfony v5.4
Database : mysql database
Please do not use API Platform Bundle so that we can judge better your coding style.

Use case Test 1 : Entities

Create one Entity User with following fields and constraints:

Fields
Constraints
firstName
Min: 2 chars, Max: 25 chars
lastName
Min: 2 chars, Max: 25 chars
fullName
Auto generated (FirstName + “ “ + LastName)
email
Unique
password
Min: 6 chars, max: 50 chars
At least contains 1 number
active
True/False. True by default
avatar
Url
photos
Array of Photos
createdAt
Auto generated
updatedAt
Auto generated


Create one Entity Photo with following fields and constraints

Fields
Constraints
name
String
url
url
user
User (Owner)
createdAt
Auto generated
updatedAt
Auto generated


Use case Test 2 : APIs Part 1
Create the following APIs. Response format should be a JSON.


API
Authentication
Specifications
POST /api/users/register
Anonymous
- Register new User

- The fullName field will be auto generated and persisted in database from firstName + “ “ + lastName

- User can upload multiple images during registration (to be valid, at least 4 images should be uploaded)

- Photos should be uploaded to the public folder

A user cannot exist in the database without an avatar : 
- If the user did not upload an avatar, a default avatar will be assigned to any new User created from this API endpoint or created elsewhere in this backend API in the future.


POST /api/users/login
Anonymous
Login existing User with email/password
If successful, this should return a JWT token.



Use case Test 3 : Authentication and API Part 2
Before creating the following API endpoint, you need to add Authentication with JWT token to the application, using Bearer token.

API
Authentication
Specifications
GET /api/users/me
Authenticated as User
- Get all current User details
- An authentication token is mandatory


Use case Test 4 : Cron task to send Newsletter
Create a symfony command that is responsible for sending a uniq email to all active users created during the last week.

Trigger: Everyday at 6 P.M.
Sender name: CW
Subject: Your best newsletter
Message: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.

