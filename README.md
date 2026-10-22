# Partner upload-prototype

Prototype af en redesignet produktdata-upload til en partnerportal, lavet til mit bachelorprojekt på PBA Webudvikling, Erhvervsakademi Aarhus.

Prototypen undersøger, om en tydeligere upload med validering får leverandører til at levere komplette og korrekte produktdata første gang.

Al data i dette repo er fiktiv, og prototypen har ingen forbindelse til Diturs systemer.

Udviklet af: Jessica Ann Jensen

## Sådan kører du prototypen lokalt

Krav: Git, PHP 8.5, Composer og Node.js.

1. git clone https://github.com/Jessicaajensen/Bachelor-projekt_PartnerPortal
2. cd Bachelor-projekt_PartnerPortal
3. composer install
4. copy .env.example .env
5. php artisan key:generate
6. npm install
7. php artisan migrate (svar ja, hvis den spørger, om databasefilen skal oprettes)
8. composer run dev
9. Åbn http://127.0.0.1:8000 i browseren (localhost kan være langsom på Windows)

Stop appen med Ctrl+C.

## Arbejdsregler

- En branch pr. opgave, opkaldt efter opgaven (fx 2-3-login), som flettes ind i main, når opgaven er færdig og testene kører.
- Små commits med korte beskrivelser. Der lægges aldrig rigtige data eller .env i nogen commit.
