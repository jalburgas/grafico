Busca tu archivo php.ini:

    Si usas XAMPP en Windows (como se ve en tus errores anteriores), normalmente se encuentra en:

    C:\xampp\php\php.ini

    También puedes abrirlo rápidamente desde el panel de control de XAMPP haciendo clic en Config > php.ini junto a Apache.


    Quita el punto y coma (;):

    El punto y coma al inicio de una línea en php.ini significa que está comentada (desactivada). Debes borrar el punto y coma para que quede así:
;extension=gd
