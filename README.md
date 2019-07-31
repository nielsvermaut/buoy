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

# Reference buoy.yml file

```
buoy:
  version: 1

  # Runs good ol' classic webhooks.
  webhooks:
    custom_webhook:

      # Where does the webhook need to be sent
      url: https://example.com/webhook

      # Payload we'll send in the POST data, only applicable on method POST. You can use the buoy variable
      # notation here as well.
      payload: {"payload_to_be_send": "{% PAYLOAD_DATA %}"}

      # How do we send the webhook, only GET and POST are supported
      method: POST

      #Allows you to set headers
      headers:
        Authorization: "Bearer {% WEBHOOK_TOKEN %}"

      # Allows you to order the webhooks, if not set, it will be 999
      order: 1

      # The events/hooks the command will be run on.
      # Available events/hooks: before_spinup, after_spinup, before_cleanup, after_cleanup
      runs_on:
        - 'after_spinup'

      # If set to true, we'll send the database_name and owner with the webhook on respectively the key
      # "buoy_database_name" and "buoy_database_owner". This will only work on "after_spinup".
      include_context: false


    # Allows you to fetch files. It is possible to overwrite the buoy.yml file, if you run buoy files fetch on the
    # first step in your build orchestrator.
    files:

      # Files fetched when running buoy files fetch for all files, or buoy files fetch-group [GROUP]
      some_file:

        # Where is the file accessable
        url: https://example.com/great.zip

        #Allows you to set headers
        headers:
          Authorization: "Bearer {% WEBHOOK_TOKEN %}"

        # Where does the file need to be stored relative to the project root. If you use a single file, this has to represent that path
        # e.g. ./file.yaml. If it is a zip, it can represent a folder e.g. ./extract. Buoy will not create subfolders for you, so
        # make sure that any paths already exist. e.g. if the target is ./static/file.yaml, the ./static folder has to already
        # exist.
        target: ./static

        # If the file is a zip file, we can unzip it on the target.
        unzip: true

        # Similar to the group section to the scripts
        group: static_content
```
