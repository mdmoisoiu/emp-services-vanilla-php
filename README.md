## About

This is the vanilla PHP version of my pet project "Employee Directory".<br/>
It contains a set or entities that expose CRUD functionality over HTTP.<br/>
This was my setup in the period when I was creating common AMFPHP backend services for Flex and Angular.

## Setup

1. Run "database.sql" in a new MySQL database
2. Update "config.db.php" to point to your database
3. Update "config.php" to match your checkout path and your url in local apache setup

## Functionality
#####The backend will expose the following functionality( per entity) :

####Country:
	- get countries list

####Employee:
	- get employees list
	- get employee by id

	- reserve employee id
	- add employee
	
	- update employee
	- set employee picture
	
	- delete employee

#####Position:
	- get positions list
	- get vacant positions list
	- get position by id
	
	- reserve position id
	- add position
	 
	- update position
	- set position employee
	- remove position employee
	
	- delete employee
