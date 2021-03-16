# Netmatters Homepage Reflection

## About
This is a learning project created as part of the *Netmatters Ltd.* SCS training scheme.

## Building
Assuming you already have [npm](https://www.npmjs.com/get-npm) installed on your machine, navigate to the root directory of the project and run the command:
```
npm install
```

If you're changing files or checking out a commit and want the project to automatically be rebuilt with those changes you can run:
```
npm run watch
```

## Testing
To run the unit tests:
```
npm test
```

Results can be found in `tests/log/`

A coverage report will be available at `tests/log/coverage-report/index.html`

## Usage
After building, start a local server using:
```
php -S localhost:8000 -t public/
```

You should then be able to access the site by loading `http://localhost:8000/` in a web browser. The contact page can be accessed from the main menu, or at `http://localhost:8000/contact.php`. There is also a very basic page that displays all received contact form messages: `http://localhost:8000/messageAdmin.php`

## Validation
Phone validation allows some non-number values, for example `(+44) 01324 345 232` is a valid phone number. This validation is carried out both server side and client side.

The form requires a user to provide either a phone number or an email address (or both) for their message to be accepted. The name and message fields must not be left empty.
