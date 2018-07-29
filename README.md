## BirthdayEmail
This is a naive tool for organizations or associations to automatically send birthday emails every day once connected with the member management system

### Usage
Write configurations in `Tool/Config.php` according to samples provided inside `Tool/Config.example.php`.

Run test file via command line to check whether text functions work properly:

```php
php CLITest.php
```

Besides text related test results on screen, you should also see `images/` directory is created, in which all the cards are generated using test names, with or without reference lines.

The alternative font will be chosen if there is text which the default font does not support.

Be aware that the database, email, and log functions are not tested now, you should test them via the actual working command shown below with TEST CONFIGURATIONS:

```php
php index.php
```

Till now, if all seems to be fine, just change configurations to the production environment, and set the command as a timed task to `crontab`, for example:

```
0 8 * * * cd /path/to/directory/ && php index.php
```

In this example, the tool will automatically run at 08:00 a.m. every day, generate birthday card and send email for each member that is celebrating a birthday.

If any error occurs, each email address in `Config::$mail['error']['admin']` will receive an alert.

### Notes
It is recommended **NOT** to deploy on the `www-root` directory on the web server since the operation runs under command line interface.

More significantly, there are directories that may contain privacy information:

- `images/`: All the birthday cards, every subdirectory is named by date
- `logs/`
  - `data.log`: Members' data from the database
  - `mail.log`: Name and destination of successfully sent emails
  - `fail.log`: Name, destination and error messages of failure operations

Remember to make accurate access control if it has to be deployed on the web folder, and rename `index.php` to some random string will be even better.
