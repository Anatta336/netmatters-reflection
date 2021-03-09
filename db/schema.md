# Database

## Schema
### Post
One row for each post. Contains the actual post's content.
* id - INTEGER PRIMARY KEY
* categoryId - INTEGER
* typeId - INTEGER
* posterId - INTEGER
* postedDate - DATE
* slug - TEXT - 'netmatters-is-carbon-neutral'
* title - TEXT - 'Netmatters is Carbon Neutral!'
* contentShort - TEXT - '<p>As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral...</p>
* headImageUrl - TEXT - 'images/thumbnails/thumb/netmatters-is-carbon-qa1v'

```sql
CREATE TABLE post (
    id INTEGER PRIMARY KEY,
    categoryId INTEGER NOT NULL,
    typeId INTEGER NOT NULL,
    posterId INTEGER NOT NULL,
    postedDate DATE CONSTRAINT DF_posts_date DEFAULT (DATE('now')),
    slug TEXT UNIQUE,
    title TEXT,
    contentShort TEXT,
    headImageUrl TEXT,
    FOREIGN KEY (categoryId) REFERENCES category(id),
    FOREIGN KEY (typeId) REFERENCES postType(id),
    FOREIGN KEY (posterId) REFERENCES poster(id)
);
```
```sql
INSERT INTO post (categoryId, typeId, posterId, postedDate, slug, title, contentShort, headImageUrl)
VALUES 
    (1, 1, 1, '2021-02-26',
    'news/faizel-achieves-the-long-service-award', 'Faizel Achieves the Long Service Award',
    '<p>Netmatters would like to take this time to congratulate our Commercial Head of Web, Faizel De...</p>',
    'img/blog-posts/faizel-achieves-the'),
    (1, 1, 1, '2021-03-02',
    'netmatters-is-carbon-neutral', 'Netmatters Is Carbon Neutral!',
    '<p>As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral....</p>',
    'img/blog-posts/netmatters-is-carbon-neutral'),
    (2, 1, 1, '2021-03-05',
    'news/february-2021-notable-employee', 'February 2021 Notable Employee',
    '<p>Every month we celebrate the most notable of employees here at Netmatters. Each department he...</p>',
    'img/blog-posts/february-2021-notable'),
    (1, 6, 2, '2021-03-08',
    'our-careers/office-administrator-receptionist', 'Office Administrator / Receptionist',
    '<p>Salary: £18k-£24k + Bonuses + Pension Hours: 40 hours per week, Monday - Friday Location: W...</p>',
    'img/blog-posts/');
```

### Category
One for each part of the business. e.g. web design, it support, telecoms, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'web-design'
* name - TEXT - 'Web Design'

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

### PostType
One for each type of post. e.g. news, careers, guides, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'news'
* name - TEXT - 'News'

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

### Poster
* id - INTEGER PRIMARY KEY
* name - TEXT - 'Netmatters Ltd'
* imageUrl - TEXT - 'images/thumbnails/article_contact_thumb/netmatters-ltd-VXAv'

```sql
CREATE TABLE poster (
    id INTEGER PRIMARY KEY,
    name TEXT,
    imageUrl TEXT
);
```
```sql
INSERT INTO poster (name, imageUrl)
VALUES ('Netmatters Ltd', 'img/netmatters-brand/logo-small'),
    ('Bethany Shakespeare', 'img/blog-posts/bethany-shakespeare');
```

## Images
The URL stored for an image doesn't include a file extension, as generally both .jpg and .webp versions are stored.

## SQLite3 CLI
With several assumptions.

cmd
```
$ "C:\Program Files\sqlite3\sqlite3.exe" db\netmatters.db
```

powershell:
```
& 'C:\Program Files\sqlite3\sqlite3.exe' db\netmatters.db
```
