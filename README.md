# helpdeskCZ
This is a school project created during the 2023/2024 school year.

# How it works?
The application is divided into 3 parts:
* **Server REST** <br>
  The rest server is structured to communicate via JSON, so it is a rest server API.
  GET,POST,PUT,DELETE endpoints are implemented.
  The permissions are based on the session id associated with the user account from which the role is taken and checked if it has permissions for that operation.
  The server i created in php with fatfree framework
* **Client Web** <br>
  The web client is created with html,js,css and boostrap 5, it uses the endpoints of the rest server for all operations.
  Only the authentication with google is done in php
* **Client Mobile** <br>
  The mobile client is done in flutter. Floor, classes and devices are stored in a local database. The rest endpoints are used

# Repository
* **helpdesk_API** : Contains the REST API Server
* **web_client** : Contains the Client Web
* **mobile_client** : Contains the Client Mobile

