# Buoy

Little handy-dandy tool for running scrips in CI tasks in docker containers and and juggling CI databases.

**Warning**: Do not use this on production databases for very obvious reasons.

Dependant on a database.

## Why

Because CI orchestration with docker-compose becomes a mess quite fast. The goal is to provide a high level CLI app that
lets you focus on what you want to achieve with your CI pipeline, not juggling a thousand lines of build files and losing
clarity in the process.

## Test this locally

Just do docker-compose up -d for a local database, and run the commands to see what happens.

## Running

### Via docker

```
$ docker run --rm -v $(pwd):/project buoy/buoy:latest db:spinup
```

## Parameters and commands

### buoy init

Initialises the buoy config file in the current working directory.

### buoy db:spinup [DATABASE URL]

Create new database on database url. Will return a random name.

## buoy db:remove [DATABASE_NAME]

Removes one database from the database server.

## buoy file:replace [FILE_NAME_1] [FILE_NAME_N] --parameter=[PARAMETER_NAME] --value=[VALUE]

When you use the following format in one of your docker-compose.yml files: {% PARAMETER_NAME %},
buoy will replace all occurrences in the passed file_name. Whilst you can do this with sed, it provides for a
cleaner API by doing it this way. You can pass as many file names as you want.

This works with more than just docker-compose files. This is handy when you want to use a just-built
container for running tests against, or for passing your database name to your app.
