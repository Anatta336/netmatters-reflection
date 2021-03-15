# Database

## Schema
This schema was designed for SQLite 3 so the `TEXT` type is used for all text content.

### post
One row for each post. Contains the actual post's content.
* id - INTEGER PRIMARY KEY
* category_id - INTEGER - Foreign key for category table.
* post_type_id - INTEGER - Foreign key for post_type table.
* poster_id - INTEGER - Foreign key for poster table.
* image_id - INTEGER - Foreign key for image table.
* posted_date - DATE
* slug - TEXT - 'netmatters-is-carbon-neutral'
* title - TEXT - 'Netmatters is Carbon Neutral!'
* content_short - TEXT - 'As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral' - Don't include any HTML (it would be escaped on display) nor trailing `...`

### category
One for each part of the business. e.g. web design, it support, telecoms, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'web-design'
* name - TEXT - 'Web Design'

### post_type
One for each type of post. e.g. news, careers, guides, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'news'
* name - TEXT - 'News'

### poster
* id - INTEGER PRIMARY KEY
* image_id - INTEGER - Foreign key for image table.
* name - TEXT - 'Netmatters Ltd'

### image
* id - INTEGER PRIMARY KEY
* image_url - TEXT - img/netmatters-brand/logo-small

### extension
* id - INTEGER PRIMARY KEY
* extension - TEXT - 'jpg' - Doesn't include the `.` dot.
* picture_type - TEXT - 'image/jpeg' - As used for type attribute in: `<source srcset="..." type="image/jpeg">`.

### image_has_extension
* imageId - INTEGER
* extension_id - INTEGER
* is_default - BOOLEAN - true if this should be the "fallback" version of the image, should work in as many browsers as possible and provide reasonable image quality.

### contact_message
* id - INTEGER PRIMARY KEY
* name - TEXT
* email - TEXT
* phone - TEXT
* marketing_opt_in - BOOLEAN
* message - TEXT
* time_sent - DATETIME

## SQL to create and populate with sample data
Because of foreign key constraints, the order that tables are populated matters. Running the commands in the order listed here will satisfy the constraints.

### image
```sql
CREATE TABLE image (
    id INTEGER PRIMARY KEY,
    image_url TEXT
);

INSERT INTO image (image_url)
VALUES ('img/netmatters-brand/logo-small'),
    ('img/blog-posts/bethany-shakespeare'),
    ('img/blog-posts/faizel-achieves-the'),
    ('img/blog-posts/february-2021-notable'),
    ('img/blog-posts/netmatters-is-carbon-neutral'),
    ('img/blog-posts/office-administrator');
```

### extension
```sql
CREATE TABLE extension (
    id INTEGER PRIMARY KEY,
    extension TEXT,
    picture_type TEXT
);

INSERT INTO extension (extension, picture_type)
VALUES ('jpg', 'image/jpeg'),
    ('webp', 'image/webp'),
    ('png', 'image/png');
```

### image_has_extension
```sql
CREATE TABLE image_has_extension (
    image_id INTEGER,
    extension_id INTEGER,
    is_default BOOLEAN,
    FOREIGN KEY (image_id) REFERENCES image(id),
    FOREIGN KEY (extension_id) REFERENCES extension(id)
);

INSERT INTO image_has_extension (image_id, extension_id, is_default)
VALUES (1, 3, true), (1, 2, false),
    (2, 1, true), (2, 2, false),
    (3, 1, true), (3, 2, false),
    (4, 1, true), (4, 2, false),
    (5, 1, true), (5, 2, false),
    (6, 1, true), (6, 2, false);
```

### poster
```sql
CREATE TABLE poster (
    id INTEGER PRIMARY KEY,
    image_id INTEGER NOT NULL,
    name TEXT,
    FOREIGN KEY (image_id) REFERENCES image(id)
);

INSERT INTO poster (name, image_id)
VALUES ('Netmatters Ltd', 1),
    ('Bethany Shakespeare', 2);
```

### post_type
```sql
CREATE TABLE post_type (
    id INTEGER PRIMARY KEY,
    slug TEXT UNIQUE,
    name TEXT
);

INSERT INTO post_type (slug, name)
VALUES ('news', 'News'),
    ('case-studies', 'Case Studies'),
    ('portfolio', 'Portfolio'),
    ('guides', 'Guides'),
    ('insights', 'Insights'),
    ('our-careers', 'Careers');
```

### category
```sql
CREATE TABLE category (
    id INTEGER PRIMARY KEY,
    slug TEXT UNIQUE,
    name TEXT
);

INSERT INTO category (slug, name)
VALUES ('web-design', 'Web Design'),
    ('it-support', 'IT Support'),
    ('telecoms-services', 'Telecoms Services'),
    ('bespoke-sofware', 'Bespoke Software'),
    ('digital-marketing', 'Digital Marketing'),
    ('cyber-security', 'Cyber Security');
```

### post
```sql
CREATE TABLE post (
    id INTEGER PRIMARY KEY,
    category_id INTEGER NOT NULL,
    post_type_id INTEGER NOT NULL,
    poster_id INTEGER NOT NULL,
    image_id INTEGER NOT NULL,
    posted_date DATE CONSTRAINT default_posted_date DEFAULT (DATE('now')),
    slug TEXT UNIQUE,
    title TEXT,
    content_short TEXT,
    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (post_type_id) REFERENCES post_type(id),
    FOREIGN KEY (poster_id) REFERENCES poster(id),
    FOREIGN KEY (image_id) REFERENCES image(id)
);

INSERT INTO post (category_id, post_type_id, poster_id, head_image_id, posted_date, slug, title, content_short)
VALUES 
    (1, 1, 1, 3, '2021-02-26',
    'news/faizel-achieves-the-long-service-award', 'Faizel Achieves the Long Service Award',
    'Netmatters would like to take this time to congratulate our Commercial Head of Web, Faizel De'),
    (1, 1, 1, 5, '2021-03-02',
    'netmatters-is-carbon-neutral', 'Netmatters Is Carbon Neutral!',
    'As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral.'),
    (2, 1, 1, 4, '2021-03-05',
    'news/february-2021-notable-employee', 'February 2021 Notable Employee',
    'Every month we celebrate the most notable of employees here at Netmatters. Each department he'),
    (1, 6, 2, 6, '2021-03-08',
    'our-careers/office-administrator-receptionist', 'Office Administrator / Receptionist',
    'Salary: £18k-£24k + Bonuses + Pension Hours: 40 hours per week, Monday - Friday Location: W');
```

## Images
The URL stored for an image doesn't include a file extension, as many images have multiple encodings stored in separate files. The path and name of the file is always the same for a single image, but the extension is different.

To show all the images and their versions:
```sql
SELECT image.id, image.image_url || '.' || extension.extension AS url
FROM image_has_extension
JOIN image ON image_has_extension.image_id = image.id
JOIN extension ON image_has_extension.extension_id = extension.id;
```

To show the default version of each image (should be exactly one per image, although that's not enforced in the schema.)
```sql
SELECT image.id, image.image_url || '.' || extension.extension AS default_url
FROM image_has_extension
JOIN image ON image_has_extension.image_id = image.id
JOIN extension ON image_has_extension.extension_id = extension.id
WHERE is_default;
```

## SQLite3 CLI
Assuming its installed in `C:\Program Files\sqlite3\sqlite3.exe`.

cmd:
```
"C:\Program Files\sqlite3\sqlite3.exe" db\netmatters.db
```

powershell:
```
& 'C:\Program Files\sqlite3\sqlite3.exe' db\netmatters.db
```

Make the output more human-readable:
```
.mode column
.headers on
```