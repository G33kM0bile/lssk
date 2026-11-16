# Informasjonskapsler og samtykke på lssk.no

Oppsett utført 16. november 2026 på **lssk.no**. Denne endringen er ikke rullet ut til dev.lssk.no.

## Hva som er satt opp

**Complianz GDPR/CCPA Cookie Consent 7.5.7**, gratisversjonen fra WordPress sitt utvidelsesbibliotek, håndterer banner og skriptblokkering. Det er ikke opprettet en ekstern skanningskonto, meldt på e-posttips eller installert ekstrautvidelser.

Banneret har norsk tekst og disse valgene:

| Valg | Virkning |
| --- | --- |
| Godta alle | Godtar statistikk og eksternt innhold |
| Kun nødvendige | Avslår de valgfrie kategoriene |
| Tilpass valg | Lar besøkende velge kategorier |
| Lagre valgene | Lagrer de valgte kategoriene |
| Endre samtykke | Åpner innstillingene på nytt |

Nødvendige funksjoner er alltid aktive. Dette omfatter blant annet WordPress-innlogging og konkurransens og medlemsfordelenes tilgangskapsler. Samtykkebanneret erstatter ikke passordbeskyttelsen.

## Innstillinger og vedlikehold

Åpne **WordPress → Complianz**. Under Wizard ligger kartlegging og integrasjoner. Under Consent Banner finnes tekst, utforming og Custom CSS.

- Standardregion: European Union (GDPR), brukt som standard for besøkende; ingen geolokasjonsbasert premiumfunksjon.
- Nettstedet har beskyttede områder med innlogging.
- Do Not Track og Global Privacy Control er satt til å respekteres.
- Banner og cookie/script blocker er aktivert.
- Kategorier: **Nødvendige**, **Statistikk**, **Eksternt innhold**. Sistnevnte er pluginens interne marketing-kategori med et navn som beskriver de valgte tjenestene.
- Samtykkekontrollen er konfigurert til **Show everywhere**, også for mobil.
- Eksisterende personvernerklæring brukes: <https://lssk.no/personvernerklaering/>.
- Generert kapselerklæring: <https://lssk.no/cookie-policy-eu/>. WordPress-side-ID **5773**, opprinnelig tittel Cookie Policy (EU).
- Kapselerklæringen ble koblet til Footer Bottom Menu i veiviseren. Banneret har også direkte dokumentlenker.
- Kontaktopplysningene i veiviseren er hentet fra nettstedets publiserte personvernerklæring. Oppdater begge steder hvis klubbens personvernkontakt endres.

### Utforming

Norske tekster er angitt manuelt under Consent Banner → Texts. Banneret har kremfarget bakgrunn **#f5f1e8**, mørk tekst **#2a2317** og gullfargede knapper **#bd944b**, med mørk knappetekst **#211b12**. Godta og avslå har samme visuelle vekt. Knappene har minimumshøyde 44 px. Stilen ligger under Complianz → Consent Banner → Custom CSS, med Use Custom CSS aktivert.

Ved senere endringer: bruk Complianz-innstillingene, lagre, og tøm relevant sidecache før kontroll som utlogget besøkende.

## Matomo

Matomo for WordPress er fortsatt aktivt, og eksisterende statistikkdata er beholdt. Pluginens **Tracking mode er Disabled**, slik at den ikke legger inn en parallell sporingskode. Complianz legger inn Matomo-koden etter samtykke til statistikk.

| Innstilling | Verdi |
| --- | --- |
| Statistics | Yes, with Matomo |
| Cookieless tracking | No |
| Complianz adds Matomo | Yes |
| Matomo URL | https://lssk.no/wp-content/plugins/matomo/app/ |
| Matomo site ID | 1 |

**Site ID må være 1.** Feltet ble kontrollert etter lagring og omlasting; ikke stol på et felt som bare er fylt ut uten å lagre. Ikke aktiver Auto-sporing i Matomo samtidig som Complianz legger inn koden.

Offisiell integrasjonsveiledning: <https://matomo.org/faq/how-to/using-complianz-for-wordpress-consent-manager-with-matomo/>. Oppsettet følger metoden med sporing først etter samtykke.

## Eksterne tjenester

Google Fonts og Google Maps er valgt i veiviseren. Google Fonts lastes fortsatt eksternt når besøkende godtar kategorien; lokalt vertede skrifter er ikke implementert. Før samtykke er kjente Google Fonts-kilder blokkert, og nettleseren kan vise reserveskrifter. Kontaktsidens kart bør kontrolleres ved endringer i Elementor eller kartleverandør.

Det er ikke oppgitt annonsekampanjer eller innebygde sosiale medier i kartleggingen. Dette må oppdateres dersom slike funksjoner legges til. Vanlige utgående lenker til sosiale medier er ikke det samme som innebygde sporingsskript.

## Gjennomførte kontroller

| Test | Observert resultat |
| --- | --- |
| Før samtykke | Matomo-koden stod som text/plain med data-category statistics; ingen lastet Matomo-script-URL |
| Kun nødvendige, deretter omlasting | Matomo-script ble ikke lastet; sporingskoden var fortsatt blokkert |
| Endre samtykke | Banneret kunne åpnes igjen |
| Godta alle | Matomo-script ble lastet og aktiv kode brukte site ID 1; Google Fonts-lenker ble aktivert |
| Tilbakekalling til kun nødvendige, deretter omlasting | Matomo-script ble ikke lenger lastet |
| Norsk tekst og farger | Norsk banner og kremfarget bakgrunn ble observert på forsiden |

Kontrollene er gjort i nettleserens sideinnhold og gjennom bannerets vanlige knapper. Dette er **ikke** en full nettverksrevisjon, en uttømmende cookieinventarliste eller en juridisk godkjenning. Ingen databaseeksport, passord eller innloggingskapsler inngår i dette repoet.

## Begrensninger og videre oppfølging

Den nye automatiske Website Scan og Cookiedatabase-synkroniseringen krevde ekstern domeneverifisering. Disse er ikke koblet til. Den genererte kapselerklæringens liste over individuelle cookies er derfor ikke komplett. Tjenestene er kartlagt manuelt fra installerte utvidelser, offentlige sider og observerte skript.

Følg opp ved endringer i nettstedet:

1. Kontroller nye skript, innebygd innhold og kapsler; oppdater tjenestelisten og kapselerklæringen med verifiserte navn, formål og lagringstider.
2. Test som utlogget besøkende med rene nettleserdata, på mobil og desktop: før valg, avslag, aksept, tilpasning og tilbakekalling.
3. Kontroller at konkurranse- og medlemsinnlogging fortsatt fungerer med kun nødvendige kapsler.
4. Vurder lokalt vertede skrifter, så nettstedets typografi ikke avhenger av samtykke til eksterne tjenester.
5. Oppdater personvernerklæringen når nye behandlinger eller tjenester settes i drift. Banneret alene erstatter ikke denne kartleggingen.

## Tilbakeføring

Ved feil: gjenopprett tidligere Complianz-innstillinger og tøm relevant sidecache. Hvis Complianz deaktiveres, opphører både banner og skriptstyring. Matomo er satt til Disabled i egen plugin, så statistikken forblir av til den settes opp igjen. Ikke gjenaktiver ukontrollert sporing som en rutinemessig feilretting. Eksisterende konkurransedata ligger separat og påvirkes ikke av bannerinnstillingene.
