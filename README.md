Building
---
```bash
$ composer global require "fxp/composer-asset-plugin:~1.3"
$ composer install
```

Running tests locally:
---
Edit src/config/db-test.php to set test database params.
```bash
$ php src/yii_test migrate/up
$ src/vendor/bin/codecept/run
```

Running app in docker container:
---
Edit docker-compose.yml environment variables if needed.
```bash
$ docker-compose up -d
```

REST API INFORMATION
===

**Insert organizations**
----
Creates organizations and their relations from array of organization names and their children 
* **URL** /v1/organizations
* **Method:** `POST`
* **URL Params** None
* **Data Params**  `{ org_name : [string], daughters : [array] }`
* **Success Response:**
  * **Code:** 200 <br />
    **Content:** `{ data : null }`
* **Error Response:**
  * **Code:** 403 FORBIDDEN <br />
    **Content:** `{ error : {message : "Organization name is required" } }` <br />
    **Content:** `{ error : {message : "Organization can't be child of itself" } }`

**Get organization relationships**
----
Returns child, parent and daughter relationships for provided organization
* **URL** /v1/organization/relationships
* **Method:** `GET`
* **URL Params** 
  * **Required:** name=[string]
* **Data Params** None
* **Success Response:**
  * **Code:** 200 <br />
    - **Content:** `{ data : [ { org_name : [string], relationship_type : [string] } ] }`
* **Error Response:**
  * **Code:** 404 NOT FOUND <br />
    - **Content:** `{ error : {message : "Organization with name: $name not found" } }`

**Delete organizations**
----
Deletes all organizations and their relationships from the database
* **URL** /v1/organizations
* **Method:** `DELETE`
* **URL Params** None
* **Data Params**  None
* **Success Response:**
  * **Code:** 200 <br />
    **Content:** `{ data : null }`