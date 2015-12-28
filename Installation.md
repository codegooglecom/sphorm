# Installation #
  * Download latest stable/unstable release.
  * Unpack to a desirable directory (e.q. ~/public\_html/sphorm).
  * (Optional) Define/change _SPHORM\_HOME_ constant for a custom installation directory. Default is DOCUMENT\_ROOT/sphorm.
  * Edit SPHORM\_HOME/conf/DataSource.php
  * List **all** your beans/classes to SPHORM\_HOME/conf/Beans.php file. See a sample inside.
  * Copy **all** your domain classes to SPHORM\_HOME/domains or define your own path using _SPHORM\_DOMAIN\_DIR_ constant.
  * **all** your domain classes should be 1 class per 1 file. By default file name should match class name. If you want custom file naming, change/define your own _SPHORM\_DOMAIN\_PATTERN_ constant.
  * Check samples directory with for more examples.