# Database

## Schema
### Posts
One row for each post. Contains the actual post's content.
* id - INTEGER PRIMARY KEY
* categoryId - INTEGER
* typeId - INTEGER
* posterId - INTEGER
* slug - TEXT - 'netmatters-is-carbon-neutral'
* title - TEXT - 'Netmatters is Carbon Neutral!'
* contentShort - TEXT - '<p>As a business, Netmatters pledged that 2021 would be the year that we became carbon neutral...</p>
* headImageUrl - TEXT - 'images/thumbnails/thumb/netmatters-is-carbon-qa1v'

### Categories
One for each part of the business. e.g. web design, it support, telecoms, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'web-design'
* name - TEXT - 'Web Design'

### Types
One for each type of post. e.g. news, careers, guides, etc.
* id - INTEGER PRIMARY KEY
* slug - TEXT - 'news'
* name - TEXT - 'News'

### Posters
* id - INTEGER PRIMARY KEY
* name - TEXT - 'Netmatters Ltd'
* imageUrl - TEXT - 'images/thumbnails/article_contact_thumb/netmatters-ltd-VXAv'

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
