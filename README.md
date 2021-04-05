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
Testing is provided by PHPUnit, with the tests defined in `tests/src`

To run the PHP unit tests:
```
npm test
```

Results can be found in `tests/log/`, and a coverage report will be available at `tests/log/coverage-report/index.html`

## Usage
After building - assuming PHP is installed and available in PATH - start a local server using:
```
php -S localhost:8000 -t public/
```
*or*
```
npm start
```

You should then be able to access the site by navigating to `http://localhost:8000/` in a web browser. The contact page can be reached from the main menu, or at `http://localhost:8000/contact.php`. For convenience there is also a very basic page that displays all received contact form messages: `http://localhost:8000/messageAdmin.php`

## Database
This project uses SQLite to store articles and contact form messages in the `db/netmatters.db` file. Swapping to another SQL database type would mean creating one new class that implements the `Netmatters\Database\DatabaseInterface` interface.

Details of the database schema together with SQL to rebuild the sample database can be found in `db/schema.md`.

## Validation
Phone validation allows some non-number values, for example `(+44) 01324 345 232` is a valid phone number. This validation is carried out both server side and client side.

The form requires a user to provide either a phone number or an email address (or both) for their message to be accepted. The name and message fields must not be left empty. This validation is carried out on both the client and server side.

## Graceful Degradation
If JavaScript is disabled in the user's browser there is minimal client-side validation, but the form still functions. Server-side validation still occurs and will display error messages within the form indicating what failed.

## Logging
Logging is provided by monolog, which will write any messages into `logs/full.log` and `logs/error.log` with the second only containing the more important messages.

## Future Improvements
* Use AJAX for the contact form submission. If JS is unavailable the form would still behave as it does now. With JS available the submit button click can be intercepted and instead send a POST request and await a response. That way the user doesn't need to see the page reload when they submit a message.
* Cache things like the landing page. As the contents only changes occasionally there's no need to spend time querying the database of recent posts separately for every visitor.
* Direct all HTTP requests to a single PHP script which can read the requested URI from the headers and route the request to create whatever view is required, rather than having discrete .php pages.
