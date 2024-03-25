# Leírás
Az alapján hogy a felhasználó melyik táblázat melyik elemére hivatkozik, úgy kezeljük a requestjét.

## Requestek fölépítése:
request metódusa:
- POST      -> CREATE
- GET       -> READ
- PUT       -> UPDATE
- DELETE    -> DELETE

cím amire a request küldésre került:
^api/"táblázat"     vagy
^api/"táblázat"/"id"

## POST request:
CRUD alapjsán a create funkciót képviseli. Hogyha egy request érkezett az api-ra ezzel a metódussal, akkor a request body-jában található JSON kód alapján ellenőrizzük a kilétét a felhasználónak, és elvégezzük az új adat beszúrását.

## GET request:
CRUD alapján a read fuknciót képviseli. Hogyha egy get request érkezik a relaxandsea/api/"táblázat"/ címre akkor a megfelelő táblázat összes adatát vissza adjuk authentikáció és authorizációt követően.

## PUT request:
