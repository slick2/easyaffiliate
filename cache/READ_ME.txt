IMPORTANT!

Do not rename your cache or scache folders!

these folders are for your mysql caching to reduce 
database resource useage on your site.

If your cache isn't working, you may need to change the folder permissions to
600 or higher, and finally 777 if nothing else works (the htaccess stops anyone 
from accessing the cache or scache folders.)

ALSO, don't delete the cron.txt file or change it.. The file contains 
the last time your cache was emptied