# php-nim-scraper

Naive approach in scraping all of Institut Teknologi Bandung NIMs (Nomor Induk Mahasiswa) from ITB Network Information Center (nic.itb.ac.id). To see all of the scraped data in action, see [ITB NIM Finder](https://ashura.id/nim).

### How-to

* cURL is needed for curl-crawl.php, whereas fgc-crawl.php only uses file_get_contents (but is considerably slower).
* Include your logged-in cookie from nic.itb.ac.id to the source (try making a HTTP call [here](https://nic.itb.ac.id/manajemen-akun/pengecekan-user)).
* Run the following command via CLI:

``` bash
$ php curl-crawl.php
or
$ php fgc-crawl.php
```
* Sample output (without emails to avoid spam): crawled.out.
