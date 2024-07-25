<p>
    <h1>4Sale Coding Challenge</h1>

## About Project
This project is a small demo for importing data from several data sources and filtering these data accordingly.

## Project Deployment

Steps on how to deploy (Make sure docker-compose is installed on your machine):
- Clone project on your local machine
- Open the command line and change the current directory to the project directory
- Create <b>[.env](./.env)</b> file and copy it's content from the <b>[.env.deploy](./deploy-docker/.env.deploy)</b>
- Run Command ```docker-compose up -d```
- Enjoy! :star_struck:

<b>**Note</b> The deployment seeds the database automatically. Please refer to the <b>[run.sh](./deploy-docker/run.sh)</b> bash file. It contains the commands that the docker container executes after creating the container. 

The project will install 2 docker containers:
- **MySQL Container**
- **Laravel Application Container**

To access any of the containers run ```docker exec -it {container-name} bash```

To Run Unit Tests:
- Access the Application Container ```docker exec -it 4sale-api bash```
- Run the tests command ```./vendor/bin/phpunit```
- The testing environment uses SQL Lite and runs on memory

## Usage
There are 2 main endpoints that can be used throughout this project:
1. **Data Import Endpoint**: 

    **[POST]** ```/app/users/transactions/import```

    This endpoint takes 2 parameters in its body:
    - **provider**: Specifying the DataProvider class that should be used to import the data. It should take a string value from the constants in the [DataProviderUtil](./app/Utils/DataProviderUtil.php). 
    - **file**: The JSON file containing the data.

2. **Transactions Index**: 

    **[GET]** ```/app/users/transactions```
    
    This endpoint can take multiple parameters:
    - **provider**: Filtering the data according to the data provider. It should take a string value from the constants in the [DataProviderUtil](./app/Utils/DataProviderUtil.php).
    - **statusCode**: Filtering the data according to the transaction status. It should take a string value from the constants in the [UserTransactionStatusUtil](./app/Utils/UserTransactionStatusUtil.php).
    - **balanceMin**: Filtering the data according to the transaction balance with values >= filtered value.
    - **balanceMax**: Filtering the data according to the transaction balance with values <= filtered value.
    - **currency**: Filtering the data according to the transaction currency.

   You can combine all filters or don't use any. ****Note**: The index function doesn't support pagination.

## What is Implemented
- Docker Containers & Deployment (Application Container - Database Container)
- Application Configuration
- Database Design & Data [Migrations](./database/migrations)
- Supervisor & Queue worker for managing background jobs
- [Factories](./database/factories) & [Seeders](./database/seeders)
- Clean Code
  - Entities [Controllers](./app/Http/Controllers)
  - Entities [Services](./app/Services)
  - Entities [Models](./app/Models)
  - Constant [Utility Classes](./app/Utils)
- Unit & Feature [Tests](./tests)
- Readme [File](./README.md)

## Project Architecture

The project is developed using **Abstract Factory Design Pattern**

We have at first the:
- **[BaseDataProvider](./app/DataProviders/BaseDataProvider.php)**: This class represents the base implementation of all data providers that should be implemented. It contains all the common variables and main functionalities that will be used be the children classes as well as the abstract methods that should be implemented by the children classes. 
- **[DataProviderX](./app/DataProviders/DataProviderX.php)**: This is the class responsible for importing data for Provider X. It extends the [BaseDataProvider](./app/DataProviders/BaseDataProvider.php) and implements the abstract methods that configures the base to enable importing.
- **[DataProviderY](./app/DataProviders/DataProviderY.php)**: Same as [DataProviderX](./app/DataProviders/DataProviderX.php) but with a different configuration for importing data for Provider Y.

Creating a new data provider is very simple. First, you will create a new DataProvider class which will extend the [BaseDataProvider](./app/DataProviders/BaseDataProvider.php). Then, you should implement the abstract methods which configures the base to enable importing.

<b>**Note</b> Please don't forget to update the constants in the [DataProviderUtil](./app/Utils/DataProviderUtil.php). This class is being used in the validation and creation of data provider objects.

Secondly, all business login is implemented in the [UserTransactionService](./app/Services/UserTransactionService.php). This class contains the methods responsible for importing the data and filtering the records as well.  

<b>**Note</b> The import process uses batches of size 50 record at a time which can be configured in the [.env](./.env) file.

<b>**Note</b> The import process uses the [halaxa/json-machine](https://github.com/halaxa/json-machine) package which has proven to be very efficient in reading large JSON files.