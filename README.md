# PA036-Pharmacy

## Stažení aplikace

*Pro vývoj doporučuji používat Netbeans, pokud ještě nemáte, ke stažení zde https://netbeans.org/downloads/index.html (pro jistotu verzi "All").*

*Můžete vyvíjet i pod Windows, ale než to rozjedete, bude to dlouho trvat a padne mnoho nepěkných slov. Používám Ubuntu 14.10, tak s případnými problémy můžu poradit.*

### 1) Inicializace git repozitáře

V Netbeans zvolit **Team -> Git -> Clone**.

Repository URL: https://github.com/Alien-x/PA036-Pharmacy
User: *váš login na github*
Password: *heslo na github*

Clone into: *umístění projektu*

A **Finish**.

### 2) Temp

Složce **temp** je třeba přiřadit plná přístupová práva pro všechny skupiny (čtení i zápis).

### 3) Připojení k databázi

Ve složce **app/config** existuje soubor **config.local.neon** s přihlašovacími údaji k databázi:

*...dsn: 'pgsql:host=127.0.0.1;dbname=pa036' user: 'postgres' password: 'postgres'...*

přičemž jméno databáze je **pa036**, uživatel **postgres** a heslo **postgres**. 







