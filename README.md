TeamLeader Challenge
===================

This documentation describes the development, installation and how to use the API to get the results from this challenge.

----------

Details
-------------
This project is a Micro Service developed in Lumen Framework, and it has a simulation to using more two Micro Services (product and customer).
There is some concepts demonstrated like Factory Design Pattern, Dependency Injection, Docker Environment, Mock in Tests.

Requirements
-------------

This project needs docker and docker-compose and git installation to run:

 - https://docs.docker.com/install/
 - https://docs.docker.com/compose/install/

The project was developed at Ubuntu Linux 18.04. So the IPs configured inside the project is considering the parameters and default configuration from Docker and Linux. 
If the IP of php container is another one (it could happen in a Osx installation), please make the change at this file #projectdirectory#/lumenapi/app/Business/ApiFactory.php

Information
-------------

I am using the json customers and products to simulate a "database" with collections from eloquent.

All the project and tests were build with the data provided to facilitate the tests and understanding of the results.

There is some technical debts described at the code to show the understanding of agile development.

The project will run at port 80, so it is necessary to shutdown services running at this port.

There is a database registering the discounts applied to the orders.

I am not considering and doing validations about the order, I used the orders json examples to developed it. 

After the project properly installed, we have this endpoints:

 - POST http://localhost/v1/getdiscount (endpoint that returns the discounts applied according to json order format)
 - GET http://localhost/product/getproduct/#ID# (endpoint that simulates the product return from product Micro Service)
 - GET http://localhost/customer/getcustomer/#ID# (endpoint that simulates the customer return from customer Micro Service)

Project Install
-------------
1 - Clone the repository 

    $ git clone https://github.com/rodrigosaraiva/teamleadertest

2 - Inside the project directory run this following commands

    $ docker-compose up --build -d
    $ docker-compose exec php /var/init.sh

Tests
-------------

To run the tests, run this following command:

    $ docker-compose exec php php "/var/www/html/vendor/bin/phpunit" "/var/www/html/tests"