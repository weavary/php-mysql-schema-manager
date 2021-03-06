# PHP MySQL schema manager
This is actually a wrapper for [ADOdb XML schema](http://adodb-xmlschema.sourceforge.net/docs/index.html). We have [Git](http://git-scm.com/) & [Subversion](http://subversion.apache.org/) to control our source code. But what if Database?

Thus, this project come out to simplify our works.

## XML doc

### Field Types

- **C** - Varchar, capped to 255 characters.
- **X** -  Larger varchar, capped to 4000 characters (to be compatible with Oracle).
- **XL** - For Oracle, returns CLOB, otherwise the largest varchar size.
- **C2** - Multibyte varchar
- **X2** - Multibyte varchar (largest size)
- **B** - BLOB (binary large object)
- **D** - Date (some databases do not support this, and we return a datetime type)
- **T** - Datetime or Timestamp
- **L** - Integer field suitable for storing booleans (0 or 1)
- **I** - Integer (mapped to I4)
- **I1** - 1-byte integer
- **I2** - 2-byte integer
- **I4** - 4-byte integer
- **I8** - 8-byte integer
- **F** - Floating point number
- **N** - Numeric or decimal number

### db.xml Field Types to MySQL

- **C** - VARCHAR
- **XL** - LONGTEXT
- **X** - TEXT
- **C2** - VARCHAR
- **X2** - LONGTEXT
- **B** - LONGBLOB
- **D** - DATE
- **TS** - DATETIME
- **T** - DATETIME
- **I4** - INTEGER
- **I** - INTEGER
- **I1** - TINYINT
- **I2** - SMALLINT
- **I8** - BIGINT
- **F** - DOUBLE
- **N** - NUMERIC

### Field Type Modifiers

- **AUTOINCREMENT** - For autoincrement number. Emulated with triggers if not available. Sets NOTNULL also.
- **KEY** - Primary key field. Sets NOTNULL also. Compound keys are supported.
- **PRIMARY** - Same as KEY.
- **DEF** - Synonym for DEFAULT for lazy typists.
- **DEFAULT** - The default value. Character strings are auto-quoted unless the string begins and ends with spaces, eg ' SYSDATE '.
- **NOTNULL** - If field is not null.
- **DEFDATE** - Set default value to call function to get today's date.
- **DEFTIMESTAMP** - Set default to call function to get today's datetime.
- **NOQUOTE** - Prevents autoquoting of default string values.
- **CONSTRAINTS** - Additional constraints defined at the end of the field definition.

_(Resource: [Creating and Working with db.xml Files](http://www.concrete5.org/documentation/how-tos/developers/creating-and-working-with-db-xml-files/))_

### Usage

In the **schema** folder, contains all databases, each folder follow the database name. Then put the schema files inside the database name folder.

See the structure

```
├── ajax.php
├── assets
│   ├── css
│   ├── font-awesome
│   ├── fonts
│   └── js
├── config.php
├── database_model.php
├── helper.php
├── index.php
├── login.php
├── schema
│   ├── dbfoo
│   │   ├── table1.xml
│   │   └── table2.xml
│   │   └── table3.xml
│   └── dbbar
└── vendors
```

Is recommend that **ONE** schema file for **ONE** table.

Example schema file

```xml
<?xml version="1.0"?>
<schema version="0.3">
    <table name="user">
        <desc>A typical users table for our application.</desc>
        <field name="id" type="I">
            <descr>A unique ID assigned to each user.</descr>
            <UNSIGNED/>
            <KEY/>
            <AUTOINCREMENT/>
        </field>
        <field name="username" type="C" size="30"><NOTNULL/></field>
        <field name="description" type="X"></field>
        <field name="gender" type="C" size="1">
            <NOTNULL/>
            <DEFAULT value="m"/>
        </field>
        <field name="age" type="I4" size="3"></field>
        <field name="point" type="F" size="8,2"></field>
        <field name="created_at" type="T"></field>
    </table>
</schema>
```

**HINT:** 

- _The table must be exists before it can be sync_
- _A column deleted from schema file won't affect the database schema, unless you drop the table and re-sync_
