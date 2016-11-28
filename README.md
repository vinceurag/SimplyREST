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
Go to ```<server>/<directory where you put this project>/SimplyREST/about/name/Vince```
You should see something like:

```
Vince
```
Play around with the controllers on ```/api```

## How To Use

``` DO NOT MODIFY ANYTHING IN THE /core FOLDER AND index.php ```

### Controllers

Controllers are located under ```/api```
Every controller MUST extend the SR_Controller.

This REST server supports the most common 4 HTTP Methods: ```GET, POST, PUT, DELETE```.

When creating a function, this should be the format: ```HTTPMETHOD_functionname()```. For example, get_name().
If the user accessed /name using the GET method, this method will be invoked. Else, if the user accessed it via POST method, the post_name will be invoked.

To send a response, use the function:
```
$this->sendResponse($arrayData, 200)
```
```$this->sendResponse()``` takes in two parameters, the data you want to be the response body (in array) and a status code.

To access the POST or PUT json sent to the server, use the function:
```
$anyVariable $this->getJsonData()
```
This function will return the json sent to the server in an array format.

To load the model, use the function:
```
$this->load_model($model_name)
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

### Routes

Routes are the heart and soul of this project. It will determine which class and function will be called.

The structure of the route should always be
```class_name/function_name/```

#### Customizing Routes

## Deployment

Since some shared hosting does not read directly from the .htaccess, you may want to enter it manually or ask some help from your hosting provider.

## Contributing

When contributing to this repository, please first discuss the change you wish to make via issue, email, or any other method with the owners of this repository before making a change.


## Authors

* **Vince Urag** - *Initial work* - [Twitter](https://twitter.com/MrStreetGrid)

See also the list of [contributors](CONTRIBUTORS.MD) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* [JREAM](https://bitbucket.org/JREAM/) - idea for the routing
