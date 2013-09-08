cronjob
=======

PHP is an open source class

Belirlene zaman aralığında çalışan bir cronjob sınıfıdır.

Kurulumu
=======

Cron dosyamızın içine çağırılır.
<pre>
require ("cronjob.php");
</pre>
Veritabanı İle Bağlantısı Kurulur
<pre>
$cronjob = new cronjob("cronjob","root","");
</pre>
Çalıştırılacak Fonksiyon Hazırlanır
<pre>
function fonksiyonadi(){
  .....
}
</pre>
Çalışacağı Zaman Aralığı Saniye Cinsinden Yazılır ve Cron Tanımlanır.
<pre>
$cronjob->defineCron("fonksiyonadi","10");
</pre>
