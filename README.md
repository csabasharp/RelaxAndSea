# RelaxAndSea
A Relax &amp; Sea hotel repo-ja

## Adatbázis megtervezése

A lehetséges felhasználók listája:
- látogató (felhasználó, fogyasztó)
- dolgozó (pl. recepciós)
- admin (adatbázis adminisztrátor)

### Kinek milyen elvárásai lehetnek?
- látogató
    - személyes, azonosításra használt adatok
        - azonositó, név, szül-év, telefon, email, banki adatok, jelszó
    - foglalt szobák tárolása (vissza teknitően is!)
    - szoba foglalása és a már foglalt szobák figyelése
        - ne tudjon olyan szobát foglalni ami arra a napra már foglalt

- dolgozó
    - személyes, azonosításra használt adatok (külön dolgozói adatok is!)
        - azonosító, név, szül-év, telefon, email, dolgozói jelszó, pozíció
    - mikor volt jelen
        - Kövessük amikor bejelentkezik, vagy ha nem volt aznap
    - felhasználói fiók készítése
        - ha recepciós, tudjon fiókot készíteni látogatónka
    - foglalt szobák és azok kezelése (látogatók nevében)
        - ha recepciós, tudjon szobát foglalni látogatónak
        - látható legyen mikor volt egy látogató a hotelben.
    - leltár (szobák tartalma)
        - valami elveszik, akkor az nyomon követhető legyen

- admin
    - dolgozók kezelése (új dolgozó létrehozása, nem web felületi)
    - táblák készítése, törlése

### Kellő táblázatok
- felhasználói (felhasználó adatai)
  - Dolgozókat is fogja tartalmazni

- szobafoglalás (melyik felhasználó és mikor foglalt meddig)
  - foglalás azonosítója
  - felhasználó azonosítója
  - szoba száma
  - felnőttek száma
  - 18 alattiak száma
  - kezdő és záró időpont

- szobák (típus, azonosító, méret(látogatók száma))
  - szoba száma (id)
  - szoba tipusa (ár)
  - szoba mérete (hány fő)

- ~~leltár (szoba azonosító, miből hány darab található, frissítés dátuma)~~ felesleges extra munka

- dolgozói (dolgozók adatai)
  - felhasználói azonosítóval köthető dolgozóhoz
  - Tartalmazni fog extra azonosító kulcsot API-hoz

- dolgozó jelenléti
  - Idő szerint elmenti hogy ki mikor jelentkezett be és milyen kérést futtatott.

php-ba fontos az sql injection kikerülése!
PDO-ezt megoldja helyettünk, úgyhogy PDO-t fogunk hasaználni.

### php adatbázis struktúra

#### pdoop.php működése

Meghíváskor felhasználja a conf/init.php-n belüli konfigurációs információkat, egyenlőre csak megadja a kezdő értékeket az adatbázis eléréséhez. Mivel függvényen belül hívtuk meg ezért ezeknek az értékei már csak az objektumon belül elmentve maradnak meg.

A "$pdoop->connect()" lefuttatása kötelező az adatbázissal való kapcsolat létrehozásához. Ezután lehet lefuttatni a többi függvényt is. Külömben hibát dobnak fel.
