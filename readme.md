# How to use:

1. Run _createcsv.php_ for generate random names
You don't have to use this script if you already have the .csv file. 
The file will be uploaded to the root directoryю
> You can set custom value by using createcsv.php?count=**your_value**
2. Open _index.html_ and choose CSV-file.
3. Get a report on loaded strings by means of auto download. 
Rows with wrong characters contained in the name or code field will not be loaded into the table. 
A description of the error will appear in the column for all invalid rows.


# First:
> Change `upload_max_filesize` and `post_max_size` to **34M** e.g. in configuration file _php.ini_

> You need to crete table in your database using by _handbook.sql_

> Edit file _connect.php_ and paste your connection data