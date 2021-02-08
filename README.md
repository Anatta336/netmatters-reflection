# HTML and CSS Reflection

## About
This is a learning project created as part of the *Netmatters Ltd.* SCS training scheme.

## Usage
Opening `public/index.html` locally in a web browser should display the page correctly.

If hosting, the `public` directory and its subdirectories should be made available.

## Building
Assuming you already have [npm](https://www.npmjs.com/get-npm) installed on your machine, navigate to the root directory of the project and run the command:
```
npm install
```

If you're working on files in `./src` and want the project to automatically be rebuilt with those changes you can run:
```
npm run watch
```

## Principles
* As far as possible HTML should be semantic. With an element's class name(s) describing what it is, not how it should appear.
* Each section of a page has an associated style in `/layout`, with each file only affecting its associated section.
* Repeated styles should be in `/utility`.
* Readily changed settings such as colours, font sizes, and icon choices should be in `/theme` 
* Aim for consistency across browsers rather than recreating any differences between browsers in the original site.
