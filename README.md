error potential:
* 1 kelas status checkin dan checkout dibuka bersamaan
* 1 praktikan/asisten memiliki 2 kelas yang checkinya dibuka bersamaan
* 1 praktikan/asisten memiliki 2 kelas yang checkoutnys dibuka bersamaan


update potential:
* ui/ux
* security
* error handling
* source code


note:
* checkin dibuka 15mnt sblm waktu mulai dan dittup 15mnt setelahnya, bgtu jg dgn checkout
* dashboard refresh setiap 1mnt, krn klian serverny ga pk linux jd ubah manual aj di databasenya, kl pk linux pk cron job, bs aj pk windows ada task scheduler 
* rfid & fid pk id, harusnya di scan,  tp bsa input manual, di masing lab ada alatnya 1(rfid,fid)
* bisa request dan ngirim data dari infotech (krn klian blm ada akses nntibjelasin aja gmn carany buat api(