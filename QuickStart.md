

# Installation #
  * Download latest stable/unstable release.
  * Unpack to a desirable directory (e.q. ~/public\_html/sphorm).
  * (Optional) Define/change _SPHORM\_HOME_ constant for a custom installation directory. Default is DOCUMENT\_ROOT/sphorm.
  * Edit SPHORM\_HOME/conf/DataSource.php
  * List **all** your beans/classes in SPHORM\_HOME/conf/Beans.php file. See a sample inside.
  * Copy **all** your domain classes to SPHORM\_HOME/domains or define your own path using _SPHORM\_DOMAIN\_DIR_ constant.
  * **all** your domain classes should be 1 class per 1 file. By default file name should match class name. If you want custom file naming, change/define your own _SPHORM\_DOMAIN\_PATTERN_ constant.
  * Check samples directory with for more examples.

# Configuration #

All sphorm config files are located in _SPHORM\_HOME_/conf directory. So far there are 3 files: `DataSource.php`, `Beans.php`, `Config.php`

> `DataSource.php` - contains database related settings. Currently it supports 3 environments: development, test, production

> `Beans.php` - contains all beans/classes you want to have Sphorm capabilities.

> `Config.php` - core settings that determine Sphorm behavior (in progress...)

# Start using sphorm #
To start using Sphorm, you need to extend Sphorm class and add a constructor to your class, that will delegate request to base class (see samples dir for examples)

# What next? #
Check the samples directory that comes with sphorm package, it will contain many samples that would help you to get most of sphorm. And of course, HAVE FUN!
