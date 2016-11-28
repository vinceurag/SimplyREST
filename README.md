# SimplyREST
Simple REST server written in PHP.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Some things that you need

```
MySQL Server
Web Server
```

### Installing

Clone this repository.

```
git clone https://github.com/vinceurag/SimplyREST.git
```

Verify that your web server and mysql is started.

Modify ```config/database.php``` with your database details.
```
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['password'] = '';
$db['database'] = 'test_db';
```

Test if it's working.
Go to ```<server>/<directory where you put this project>/SimplyREST/```
For example you're using XAMPP and you cloned this project to ```htdocs```, you need to go to
```
http://localhost/SimplyREST/
```
You should see something like:

```
{
"status" : "successful",
"details" : "framework was installed successfully"
}
```

Play around with the controllers on ```/api```

## How To Use

``` DO NOT MODIFY ANYTHING IN THE /core FOLDER AND index.php ```
``` SAMPLE DATABASE SCHEMA IN config/test_db.sql ```

### Controllers

Controllers are located under ```/api```
Every controller MUST extend the SR_Controller.

This REST server supports the most common 4 HTTP Methods: ```GET, POST, PUT, DELETE```.

When creating a function, this should be the format: ```HTTPMETHOD_functionname()```. For example, get_name().
If the user accessed /name using the GET method, this method will be invoked. Else, if the user accessed it via POST method, the post_name will be invoked.

To send a response, use the function:
```
$this->sendResponse($arrayData, HTTP_Status::HTTP_OK)
```
```$this->sendResponse()``` takes in two parameters, the data you want to be the response body (in array) and a status code.

Status codes are defined in ```core/HTTP_Status.php```
```
HTTP_OK = 200

HTTP_CREATED = 201

HTTP_NOT_FOUND = 404

HTTP_UNAUTHORIZED = 401
```

To access the POST or PUT json sent to the server, use the function:
```
$anyVariable $this->getJsonData()
```
This function will return the json sent to the server in an array format.

To load the model, use the function:
```
$this->load("model_name")
```
```$model_name``` is the class name of your model. You should load the model in your controller's constructor. To use the loaded model, you just need to append the ```model_name``` to ```$this->```. For example, I loaded the model ```anothermodel``` in the constructor ```$this->load_model("anothermodel")```, to access it inside the functions in my controller, I can call ```$this->anothermodel->getUser()``` assuming there is a getUser()  function inside my model.

### Models

Models are located under ```/models```
Every model MUST extend the SR_Model.

To make a query to the database, use the function:
```
$this->db->exec("SELECT * FROM tbl_users");
```
If the SQL statement is a ```SELECT``` statement, this function will return an array of the result (which you can directly send as a response in the controller). Else, this will return a bool.

To ```UPDATE``` a row, use the function:
```
$this->db->udapte_record($table, $arrayChanges, "id=condition");
```
If a record was successfully updated, it will return a success json.


### Routes

Routes are the heart and soul of this project. It will determine which class and function will be called.

The structure of the route should always be
```
class_name/function_name/
```

#### Customizing Routes

You can customize the routes in the ```config/routes.php``` folder.

```
$route['/about'] = "test";
```
Here, we are routing ```/about``` to execute the get_index() of the Test class.

```
$route['/about/name/:param'] = "test/name";
```
We can also add parameters by putting ```:param``` in the place wherein we expect a parameter.
This route will invoke the get_name($a) function of Test class, passing any value in place of the ```:param``` to the function.

```
$route['about/name/:param/age/:param'] = "test/nameage";
```
This project also supports multiple parameters. In this example we passed a name and age in that format. By this route, we tell our REST server to invoke the get_nameage($name, $age) method in the Test class. Also, passing the all the parameters.

### Libraries

Libraries are located under ```/libraries```

You should load the needed libraries in the constructor of the controller via the command:
```
$this->load("library_name")
```

#### JSON Web Token Library

The JWT Library can be used to generate a token.

To generate a token:
```
$this->jwt->generate_token($user_id, $arrayPayload)
```
```$user_id``` is the unique identifier of the user.
```$arrayPayload``` is the custom payload you want to add to the token

To check if the user passed a VALID json web token to the authorization header:
```
$this->jwt->check()
```
If it's a valid token, it will return the decoded token and the authorization status ("authorized" or "unauthorized").
Sample response:
```
{
  "consumerKey": "YOUR-CONSUMER-KEY",
  "userId": 1,
  "issuedAt": "11/29/2016 00:47:42 AMNov",
  "data": {
    "name": "New Name",
    "pass": "new_pass"
  },
  "authorization": "authorized"
}
```


## Deployment

Since some shared hosting does not read directly from the .htaccess, you may want to enter it manually or ask some help from your hosting provider.

## Contributing

When contributing to this repository, please first discuss the change you wish to make via issue, email, or any other method with the owners of this repository before making a change.


## Authors

* **Vince Urag** - *Initial work* - [Twitter](https://twitter.com/MrStreetGrid)

See also the list of [contributors](CONTRIBUTORS.md) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details

## Acknowledgments

* [JREAM](https://bitbucket.org/JREAM/) - idea for the routing
* [Neuman Vong](https://github.com/luciferous) - JWT Library
