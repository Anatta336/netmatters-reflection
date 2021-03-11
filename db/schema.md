# Database

## Schema
This schema was designed for SQLite 3 so the `TEXT` type is used for all text content.

### Post
One row for each post. Contains the actual post's content.
* id - INTEGER PRIMARY KEY
* categoryId - INTEGER
* typeId - INTEGER
* posterId - INTEGER
* postedDate - DATE
* slug - TEXT - 'netmatters-is-carbon-neutral'
* title - TEXT - 'Netmatters is Carbon Neutral!'
* contentShort - TEXT - 'As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral' - Don't include open and close `<p>` or trailing `...`
* headImageId - INTEGER - Foreign key for image table.

### Category
One for each part of the business. e.g. web design, it support, telecoms, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'web-design'
* name - TEXT - 'Web Design'

### PostType
One for each type of post. e.g. news, careers, guides, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'news'
* name - TEXT - 'News'

### Poster
* id - INTEGER PRIMARY KEY
* name - TEXT - 'Netmatters Ltd'
* imageId - INTEGER - Foreign key for image table.

### Image
* id - INTEGER PRIMARY KEY
* imageUrl - TEXT - img/netmatters-brand/logo-small

### Extension
* id - INTEGER PRIMARY KEY
* extension - TEXT - 'jpg' - Doesn't include the `.` dot.
* pictureType - TEXT - 'image/jpeg' - As used in the HTML `<source srcset="..." type="image/jpeg">`.

### ImageHasExtension
* imageId - INTEGER
* extensionId - INTEGER
* isDefault - BOOLEAN - true if this should be the "fallback" version of the image, should work in as many browsers as possible and provide reasonable image quality.

## SQL to create and populate with sample data
Note that because the `post` table uses foreign key constraints, the other tables should be populated first. Similarly `image`, `extension`, then `imageHasExtension` should be populated before the other tables. Running the commands in the order listed here will satisfy foreign key constraints.

### Image
```sql
CREATE TABLE image (
    id INTEGER PRIMARY KEY,
    imageUrl TEXT
);
```
```sql
INSERT INTO image (imageUrl)
VALUES ('img/netmatters-brand/logo-small'),
    ('img/blog-posts/bethany-shakespeare'),
    ('img/blog-posts/faizel-achieves-the'),
    ('img/blog-posts/february-2021-notable'),
    ('img/blog-posts/netmatters-is-carbon-neutral'),
    ('img/blog-posts/office-administrator');
```

### Extension
```sql
CREATE TABLE extension (
    id INTEGER PRIMARY KEY,
    extension TEXT,
    pictureType TEXT
);
```
```sql
INSERT INTO extension (extension, pictureType)
VALUES ('jpg', 'image/jpeg'),
    ('webp', 'image/webp'),
    ('png', 'image/png');
```

### ImageHasExtension
```sql
CREATE TABLE imageHasExtension (
    imageId INTEGER,
    extensionId INTEGER,
    isDefault BOOLEAN,
    FOREIGN KEY (imageId) REFERENCES image(id),
    FOREIGN KEY (extensionId) REFERENCES extension(id)
);
```
```sql
INSERT INTO imageHasExtension (imageId, extensionId, isDefault)
VALUES (1, 3, true), (1, 2, false),
    (2, 1, true), (2, 2, false),
    (3, 1, true), (3, 2, false),
    (4, 1, true), (4, 2, false),
    (5, 1, true), (5, 2, false),
    (6, 1, true), (6, 2, false);
```

### Poster
```sql
CREATE TABLE poster (
    id INTEGER PRIMARY KEY,
    imageId INTEGER NOT NULL,
    name TEXT,
    FOREIGN KEY (imageId) REFERENCES image(id)
);
```
```sql
INSERT INTO poster (name, imageId)
VALUES ('Netmatters Ltd', 1),
    ('Bethany Shakespeare', 2);
```

### PostType
```sql
CREATE TABLE postType (
    id INTEGER PRIMARY KEY,
    slug TEXT UNIQUE,
    name TEXT
);
```
```sql
INSERT INTO postType (slug, name)
VALUES ('news', 'News'),
    ('case-studies', 'Case Studies'),
    ('portfolio', 'Portfolio'),
    ('guides', 'Guides'),
    ('insights', 'Insights'),
    ('our-careers', 'Careers');
```

### Category
```sql
CREATE TABLE category (
    id INTEGER PRIMARY KEY,
    slug TEXT UNIQUE,
    name TEXT
);
```
```sql
INSERT INTO category (slug, name)
VALUES ('web-design', 'Web Design'),
    ('it-support', 'IT Support'),
    ('telecoms-services', 'Telecoms Services'),
    ('bespoke-sofware', 'Bespoke Software'),
    ('digital-marketing', 'Digital Marketing'),
    ('cyber-security', 'Cyber Security');
```

### Post
```sql
CREATE TABLE post (
    id INTEGER PRIMARY KEY,
    categoryId INTEGER NOT NULL,
    typeId INTEGER NOT NULL,
    posterId INTEGER NOT NULL,
    headImageId INTEGER NOT NULL,
    postedDate DATE CONSTRAINT DF_posts_date DEFAULT (DATE('now')),
    slug TEXT UNIQUE,
    title TEXT,
    contentShort TEXT,
    FOREIGN KEY (categoryId) REFERENCES category(id),
    FOREIGN KEY (typeId) REFERENCES postType(id),
    FOREIGN KEY (posterId) REFERENCES poster(id),
    FOREIGN KEY (headImageId) REFERENCES image(id)
);
```
```sql
INSERT INTO post (categoryId, typeId, posterId, headImageId, postedDate, slug, title, contentShort)
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
The URL stored for an image doesn't include a file extension, as generally both .jpg and .webp versions are stored.

To show all the images and their versions:
```sql
SELECT image.id, image.imageUrl || '.' || extension.extension AS url
FROM imageHasExtension
JOIN image ON imageHasExtension.imageId = image.id
JOIN extension ON imageHasExtension.extensionId = extension.id;
```

To show the default version of each image (should be exactly one per image, although that's not enforced in the schema.)
```sql
SELECT image.id, image.imageUrl || '.' || extension.extension AS url
FROM imageHasExtension
JOIN image ON imageHasExtension.imageId = image.id
JOIN extension ON imageHasExtension.extensionId = extension.id
WHERE isDefault;
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