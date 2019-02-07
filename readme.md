# Tic Tac Toe Rest Apı 

This is a sample REST API application for tic tac toe game 

**Technologies used**
- Docker
- Laravel Framework 5.7.24
- Mysql 
- Nginx 
- AWS

## Installation
* Clone the repository 

`https://github.com/umutbariskarasar/tic-tac-toe-api`

* Docker Compose Up

`cd docker`

`docker-compose up`

* You need a db dump or you can create new database as tic-tac-toe and table name as game. 


## Sending Requests
You can send POST requests to the URL below using environments like **Postman.** 

**Base URLs:**
localhost/api/game 
http://ec2-34-246-174-181.eu-west-1.compute.amazonaws.com/api/game ( Instance generally closed )


**Reguests**

| Method | URL            | Header Key    | Header Value     |
| -------|----------------|---------------|----------------- |
| POST   | /api/game/     | Content-Type  | application/json |
| PUT    | api/game/{id}  |               |                  |
| DELETE | api/game/{id}  |               |                  |
| GET    | api/game/      |               |                  |



**Sample Bodies**

```
{
"a1":"X"
}

{
"b1":"O"
}
```

**Sample Results**

```
{
id: 1,
a1: "X",
a2: "O",
a3: "X",
b1: "O",
b2: "O",
b3: "X",
c1: "",
c2: "",
c3: "X",
gamer1: 0,
gamer2: 1,
game_status: "finish",
winner: "X"
}
{
id: 11,
a1: "X",
a2: "O",
a3: "X",
b1: "",
b2: "",
b3: "",
c1: "",
c2: "",
c3: "",
gamer1: 0,
gamer2: 1,
game_status: "playing",
winner: ""
}

{
id: 11,
a1: "X",
a2: "O",
a3: "O",
b1: "O",
b2: "X",
b3: "X",
c1: "X",
c2: "X",
c3: "O",
gamer1: 0,
gamer2: 1,
game_status: "finish",
winner: "draw"
}
```
