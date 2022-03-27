# La Videoteca

This is a movie manager created with Symfony 5.4 that provides a command to import data via CSV file.  

# Specifications

Symfony 5.4

PHP 7.4.28

Composer

EasyAdmin

# Requests

This project must have three entities: Film, Actor(s), Director(s)

The system must have a 'Command' that loads data from the CSV file.

# How to access Admin

After installation, the manager can be accessed through:

https://localhost:8000/

The admin shows how to start and manage La Videoteca.

# How to import CSV files

The public/uploads folder has several CSV with different number of films to import. You have the option to import any of them:

- IMDb_movies_10 
- IMDb_movies_100 
- IMDb_movies_250
- IMDb_movies_1000
- IMDb_movies_2000
- IMDb_movies_5000
- IMDb_movies_10000
- IMDb_movies_40000
- IMDb_movies_850000

To import the films you must run this command:

```sh

symfony console app:import-csv {name_file}

```
