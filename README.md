cronjob
=======

PHP is an open source class

Belirlene zaman aralığında çalışan bir cronjob sınıfıdır.

Kurulumu
=======

Cron dosyamızın içine çağırılır.
require ("cronjob.php");

Veritabanı İle Bağlantısı Kurulur
$cronjob = new cronjob("cronjob","root","");

Çalıştırılacak Fonksiyon Hazırlanır
function fonksiyonadi(){
  .....
}

Çalışacağı Zaman Aralığı Saniye Cinsinden Yazılır ve Cron Tanımlanır.
$cronjob->defineCron("fonksiyonadi","10");

