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
