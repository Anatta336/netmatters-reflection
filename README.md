# Netmatters Homepage Reflection

## About
This is a learning project created as part of the *Netmatters Ltd.* SCS training scheme.

## Building
Assuming you already have [npm](https://www.npmjs.com/get-npm) and [Composer](https://getcomposer.org/) installed on your machine, navigate to the root directory of the project and run:
```
npm install
```

If you're changing files or checking out a commit you should first run:
```
npm run watch
```

## Testing
To run the PHP unit tests:
```
npm test
```

Results can be found in `tests/log/`

A coverage report will be available at `tests/log/coverage-report/index.html`

## Usage
After building - assuming PHP is installed and available in PATH - start a local server using:
```
php -S localhost:8000 -t public/
```

You should then be able to access the site by navigating to `http://localhost:8000/` in a web browser. The contact page can be reached from the main menu, or at `http://localhost:8000/contact.php`. For convenience there is also a very basic page that displays all received contact form messages: `http://localhost:8000/messageAdmin.php`

## Database
This project uses SQLite to store articles and contact form messages in the `db/netmatters.db` file. Swapping to another SQL database type would mean creating one new class that implements the `Netmatters\Database\DatabaseInterface` interface.

Details of the database schema together with SQL to rebuild the sample database can be found in `db/schema.md`.

## Validation
Phone validation allows some non-number values, for example `(+44) 01324 345 232` is a valid phone number. This validation is carried out both server side and client side.

The form requires a user to provide either a phone number or an email address (or both) for their message to be accepted. The name and message fields must not be left empty.
