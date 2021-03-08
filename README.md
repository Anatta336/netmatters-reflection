# Netmatters Homepage Reflection

## About
This is a learning project created as part of the *Netmatters Ltd.* SCS training scheme.

## Building
Assuming you already have [npm](https://www.npmjs.com/get-npm) installed on your machine, navigate to the root directory of the project and run the command:
```
$ npm install
```

If you're working on files in `src` or checking out a commit and want the project to automatically be rebuilt with those changes you can run:
```
$ npm run watch
```

To manually trigger a rebuild of the project's `dist/` directory without `npm install` you can run:
```
$ npm run prepare
```

## Usage
After building, start a local server using:
```
$ php -S localhost:8000 -t dist/
```

You should then be able to access the site by loading `http://localhost:8000/` in a web browser.
