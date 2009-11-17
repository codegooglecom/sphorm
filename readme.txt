[Requirements]
 - PHP 5.2.x (probably is true for 5.x too, wasn't tested)
 - MySQL (for now)
 - PDO & PDO_MYSQL

[Installation]
 - Download latest stable/unstable release.
 - Unpack to a desirable directory.
 - Define 'SPHORM_HOME' constant for custom installation directory. (Default is {DOCUMENT_ROOT}/sphorm).
 - Change [sphorm-install-dir]/conf/DataSource.php 
 - List _all_ your beans/classes to [sphorm-install-dir]/conf/Beans.php file. See a sample inside.
 - Copy _all_ your domain classes to [sphorm-install-dir]/domains or define your own path using SPHORM_DOMAIN_DIR const.
 - _all_ your domain classes should be 1 class per 1 file. Filename should be the same as class name (by default). 
   If you want custom filenaming, change define your own SPHORM_DOMAIN_PATTERN const.
 - Check samples dir with for more examples.
 - Have fun!

  Thank you for using sphorm!
 