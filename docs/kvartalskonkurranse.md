# LSSK – Kvartalskonkurranse

Bruker- og driftsveiledning for WordPress-pluginen **LSSK Kvartalskonkurranse 1.1.0**. Dokumentert 14. november 2026.

Løsningen er satt opp på **dev.lssk.no**. Arbeidet omfatter ikke utrulling til hovednettstedet lssk.no.

## Åpne konkurransen

- Deltakerside: <https://dev.lssk.no/kvartalskonkurranse/>
- Endringspanel: <https://dev.lssk.no/kvartalskonkurranse/?ansvarlig=1>
- Passordinnstillinger: WordPress → **Kvartalskonkurranse**.

Velg konkurranserunde og kategori på deltakersiden. Konkurranseinformasjonen gjelder hele runden. Banebeskrivelsen under kategorivalget gjelder den valgte kategorien.

## For deltakere

1. Åpne deltakersiden og skriv inn deltakerpassordet.
2. Velg riktig runde og kategori. Les konkurransereglene og banebeskrivelsen.
3. Skriv navn og tid i sekunder, for eksempel `4,33` eller `4.33`.
4. Trykk **Lagre forsøk**. Vent på bekreftelsen om at forsøket er lagret.
5. Trykk ditt eget navn i resultatlisten for å registrere flere forsøk eller se forsøkshistorikken i den valgte kategorien og runden.

Alle gyldige forsøk telles, også når tiden er dårligere. Laveste tid avgjør plasseringen. Like beste tider gir delt plassering, for eksempel 1, 1, 3. Annullerte feilføringer holdes utenfor resultat og forsøksantall.

Tiden må være positiv, med maksimalt fem sifre før komma og tre desimaler. Nulltid godtas ikke. Resultatene lagres på serveren og er felles for alle. Last siden på nytt for å hente andres siste registreringer; løsningen har ikke automatisk sanntidsoppdatering eller frakoblet registrering.

### Navn og mulige dubletter

Store og små bokstaver og ekstra mellomrom gjenkjennes. `KENNETH  ANDRE GRANÅS` og `Kenneth Andre Granås` bruker samme deltaker.

Ved mulige treff med utelatt mellomnavn, som `Kenneth Granås` og `Kenneth Andre Granås`, vises **Er dette deg?** Velg eksisterende deltaker eller **Jeg er en annen person**, og bekreft før forsøket lagres. Usikre treff slås ikke sammen automatisk. To personer med helt identisk normalisert navn kan ikke skilles som egne deltakere i dagens modell.

### Logg ut og WordPress-administratorer

**Logg ut** avslutter konkurransens nettlesertilgang for både deltaker- og administratorrollen. Knappen logger ikke ut av WordPress. En innlogget WordPress-bruker med `manage_options` får automatisk administrasjonstilgang, og ser derfor ikke konkurransens utloggingsknapp. Logg ut via WordPress, eller bruk et privat nettleservindu, for å kontrollere den vanlige deltakeropplevelsen.

## Kategorier

| Nøkkel | Kategori | Særtrekk i The rack-beskrivelsen fra SSI |
| --- | --- | --- |
| `hg` | DSSN HG – Handgun | Ladd og i hylster |
| `lw` | NSF LW – Low Ready | Ladd i low ready, 45 grader mot bakken |
| `mr` | DSSN HG MR – Handgun Mandatory Reload | Én obligatorisk omlading mellom første og siste skudd |
| `rifle` | DSSN Rifle | Klar tilstand «Option 1»; egen merknad for minirifle |
| `dsh` | DSSN SH – Strong Hand | Kun sterk hånd; ladd og i hylster som oppgitt i SSI |
| `nsh` | NSF SH – Strong Hand | Kun sterk hånd; ladd og i hylster som oppgitt i SSI |

Dette gjengir de registrerte SSI-beskrivelsene. «Option 1» og sikkerhetsvinklene `L/R60-60` er beholdt uten ny tolkning. Ansvarlig må avklare eventuelle uklarheter i briefingen før gjennomføring.

## For ansvarlige

Bruk administrasjonspassordet i det samme passordfeltet som deltakerne. Dette åpner endringspanelet uten WordPress-innlogging. Adgangen gjelder konkurransen og gir ikke tilgang til andre WordPress-funksjoner.

### Opprette og avslutte en runde

1. Åpne **Endringspanel → Ny konkurranserunde**.
2. Skriv rundens navn og trykk **Opprett og åpne runde**.
3. Legg inn rundens beskrivelse, periode, regler og kildelenke i konkurranseinformasjonen.
4. Fyll inn banebeskrivelsene for alle aktuelle kategorier.
5. Kontroller deltakersiden, også på mobil.
6. Ved avslutning: sett runden til **Lukket** under **Konkurranserunder**, og lagre.

En ny runde opprettes åpen. Periodeteksten er informasjon; den åpner eller lukker ikke runden automatisk. Lukkede runder beholder resultater og historikk, men avviser nye forsøk. Uten valgt runde vises normalt den nyeste åpne runden, ellers den nyeste runden.

### Regler og banebeskrivelser

Rundens beskrivelse, periode, regler og kildelenke lagres separat fra kategorienes informasjon. Skriv én regel per linje.

Under **Kategorienes banebeskrivelser** velges runden og deretter kategorien. Hver kategori har fire felt:

| Felt | Innhold |
| --- | --- |
| Målopplysninger | Bane, skudd, plater og andre fakta; bruk gjerne én `Etikett: verdi` per linje |
| Gjennomføring | Prosedyre, utgangsposisjon, klar tilstand, start/stopp og sikkerhetsvinkler |
| Bilde | Full HTTPS-adresse til bildet |
| Kilde | Lenke til den aktuelle SSI-banen eller annen originalbeskrivelse |

Trykk **Lagre banebeskrivelser**. Skjemaet lagrer kategoriene samlet for den aktuelle runden. Opplysningene kopieres ikke automatisk til nye runder.

### Rette en feilføring

Under **Siste 100 forsøk** åpnes det aktuelle forsøket. Rett tid eller kategori, eller sett status til **Annullert feilføring**, og lagre. En annullering kan gjenopprettes. Rettingene beholder før- og etterverdier i endringshistorikken; panelet viser de siste 50 endringene.

Historikken gjelder retting av forsøk. Den er ikke en full revisjonslogg for passord, rundetitler, regler og banebeskrivelser, og felles administrasjonspassord identifiserer ikke hvilken ansvarlig som gjorde endringen. Panelet har ingen funksjon for å slå sammen deltakere eller redigere deltakernavn.

## Tilgang og personopplysninger

WordPress-administrator setter separate deltaker- og administrasjonspassord under **Kvartalskonkurranse**. Minstekravene er henholdsvis 7 og 8 tegn; passordene må være forskjellige. Ingen faktiske passord skal legges i GitHub eller dokumentasjonen.

| Tilgang | Kan gjøre | Varighet i nettleseren |
| --- | --- | --- |
| Deltakerpassord | Se beskyttet konkurranse og registrere forsøk | 30 dager |
| Administrasjonspassord | I tillegg administrere runder, informasjon og forsøk | 8 timer |
| WordPress `manage_options` | Administrere konkurransen og sette passord | Styres av WordPress-innloggingen |

Passordbytte tilbakekaller tidligere konkurransetilgang for den aktuelle rollen. Fellespassord er ikke personlige brukerkontoer: alle med deltakerpassordet kan registrere forsøk på et navn. Navneklikk bekrefter derfor ikke deltakerens identitet.

Løsningen lagrer navn, tider, tilhørende runde/kategori, tidspunkt og endringshistorikk. Den har ingen automatisk slettefrist eller egen eksport-/slettefunksjon i panelet. Klubben må fastsette behandling og oppbevaring av disse opplysningene. Databasekopier og faktiske resultatlister skal holdes utenfor det offentlige repoet.

## Teknisk oppsett og lagring

Pluginfilen er `lssk-quarterly/lssk-quarterly.php` under WordPress sin pluginmappe. Den oppgir PHP 8.0 som minimum, bruker `mbstring`, og bruker `intl` til Unicode-normalisering når utvidelsen finnes. Databasen må støtte WordPress og transaksjonene som brukes ved retting av forsøk. HTTPS kreves.

Ruten `/kvartalskonkurranse/` håndteres av pluginen via `template_redirect`. Den bruker temaets topp og bunn, med eget innhold og utforming for konkurranseruten. Tilpasninger av topp/bunn er avgrenset med rutens body-klasse. En vanlig Elementor-redigering er derfor ikke kilden til konkurranseskjemaet. Det er ingen REST-API-integrasjon; registrering og administrasjon bruker serverskjemaer med POST og omdirigering etter lagring.

### Databasetabeller

`{prefix}` betyr det faktiske WordPress-tabellprefikset, som ikke nødvendigvis er `wp_`.

| Tabell | Formål |
| --- | --- |
| `{prefix}lssk_q_rounds` | Runde-ID, tittel, åpen/lukket og opprettet tidspunkt |
| `{prefix}lssk_q_people` | Deltakernavn og unik normalisert navnenøkkel; felles på tvers av runder |
| `{prefix}lssk_q_attempts` | Person, runde, kategori, tid i hele millisekunder, request-ID, tidspunkt og annulleringsstatus |
| `{prefix}lssk_q_changes` | Forsøks-ID, før-/etterverdier og tidspunkt for retting |

Resultatene beregnes med laveste tid og antall ikke-annullerte forsøk per person, runde og kategori. Unik request-ID hindrer at samme skjema telles to ganger; nye forsøk med identisk tid telles separat.

### WordPress-innstillinger

| Innstilling | Formål |
| --- | --- |
| `lssk_q_schema` | Databaseskjemaversjon; nå 2 |
| `lssk_q_access` | Hash og tilgangsgenerasjon for deltakerpassord |
| `lssk_q_admin_access` | Hash og tilgangsgenerasjon for administrasjonspassord |
| `lssk_q_round_info_<runde-ID>` | `description`, `rules`, `period`, `source` |
| `lssk_q_stage_info_<runde-ID>` | Kategorinøkkel → `facts`, `briefing`, `image`, `source` |
| `lssk_q_operation_<ID>` | Beskytter mot gjentatt opprettelse med samme operasjons-ID |

Innlogging bruker WordPress-passordhasher og signerte Secure/HttpOnly/SameSite=Lax-informasjonskapsler. Signaturen bindes til rolle, tilgangsgenerasjon, rute og WordPress sin auth-salt. Endringsskjemaer kontrolleres på serveren med rolle og signert CSRF-verdi. Innlogging begrenses etter gjentatte feilforsøk fra samme IP/generasjon.

Ruten sender `Cache-Control: private, no-store` og `X-Robots-Tag: noindex, nofollow, noarchive`, og setter `DONOTCACHEPAGE`. Kontroller også at eventuell proxy/CDN ikke mellomlagrer konkurranseruten.

## Installasjon, oppdatering og tilbakeføring

1. Ta en privat sikkerhetskopi av pluginfilene og WordPress-databasen før oppdatering.
2. Kontroller at målet er **dev.lssk.no**.
3. Installer/oppdater pluginmappen, og aktiver pluginen ved førstegangsinstallasjon.
4. Åpne WordPress-administrasjonen. Skjemaversjon kontrolleres der og nødvendige tabeller opprettes/oppgraderes.
5. Sett de to passordene i WordPress ved førstegangsoppsett. Kontroller deretter besøkendes innlogging i et privat nettleservindu.
6. Kontroller eksisterende runder, resultater og banebeskrivelser før nye registreringer åpnes.

En plugin-ZIP inneholder kode og referansefiler, ikke de registrerte resultatene, passordinnstillingene eller rundenes levende innhold. En filoppdatering skal beholde tabeller og innstillinger. Installerer man på et nytt nettsted, følger ikke The rack eller resultatene automatisk med.

Ved feil kan tidligere pluginfiler gjenopprettes. Dette tilbakefører ikke databaseendringer. Full tilbakeføring krever en kontrollert gjenoppretting av den tilhørende private databasekopien; registreringer etter kopiens tidspunkt kan da gå tapt. Flytting av nettstedadresse eller endring av WordPress-salter kan kreve ny konkurranseinnlogging.

## The rack – innhold og kilder

Runden **Kvartal Challenge – The rack** er opprettet manuelt på utviklingsnettstedet. Kilde: <https://shootnscoreit.com/event/22/30378/>. SSI-poeng, Comstock og matchprosent vises som kildeopplysninger; lokal plassering beregnes etter beste tid.

| Kategori | SSI-bane |
| --- | --- |
| DSSN HG | <https://shootnscoreit.com/event/stage/24/104871/> |
| NSF LW | <https://shootnscoreit.com/event/stage/24/104872/> |
| DSSN HG MR | <https://shootnscoreit.com/event/stage/24/104873/> |
| DSSN Rifle | <https://shootnscoreit.com/event/stage/24/104874/> |
| DSSN SH | <https://shootnscoreit.com/event/stage/24/104875/> |
| NSF SH | <https://shootnscoreit.com/event/stage/24/104876/> |

Alle seks har seks plater og samme kildebilde. Bildet lastes fra SSIs offentlige bildelenke og er avhengig av at denne fortsatt er tilgjengelig. Gjeldende registrerte referansedata ligger i arbeidsutgaven av `the-rack-kategorier.json`; filen er ingen automatisk importjobb.

Rundens periode er gjengitt som **til 1. april**, uten å anta årstall. De generelle reglene oppgir at kun tid teller, alle seks plater skal ned, ubegrenset antall skudd/forsøk og bruk av shot timer. Rifle har en egen SSI-merknad om minirifle, og SSI har en generell straffhenvisning. Ansvarlig må avklare eventuelle motsetninger mellom invitasjonen og banebeskrivelsen; dokumentasjonen innfører ingen nye skyteregler.

Innledende resultater er manuelt overført fra oppgitte skjermbilder i HG og Rifle. En SSI-rad med 0,00 er utelatt som manglende gyldig tid. Det finnes ingen automatisk resultatsynkronisering med SSI eller kobling til The Events Calendar i denne pluginen.

## Kontroll og feilsøking

Tidligere kontroll omfatter PHP 8.4-syntaks og 46 funksjonskontroller av navn, tider, signering, tilgangsroller, CSRF og passordrotasjon. I WordPress er serverlagring, navnebekreftelse, flere forsøk, sortering, retting/annullering, historikk og de seks banebeskrivelsene kontrollert. Desktop og mobil er visuelt kontrollert. Dette er dokumenterte gjennomførte kontroller, ikke en påstand om at testene kjøres automatisk i GitHub. Besøkendes innlogging med de endelig valgte passordene må kontrolleres separat.

| Symptom | Kontroll |
| --- | --- |
| Administrasjon vises når du vil være deltaker | Logg ut av WordPress eller åpne et privat vindu |
| Ingen innloggingsskjema / konkurransen er låst | Kontroller at et konkurransepassord er satt i WordPress |
| Skjemaet er utløpt | Last siden på nytt; dette kan følge av utlogging eller passordbytte |
| Nye forsøk avvises | Kontroller rundens åpen/lukket-status og at tiden er positiv |
| Andre ser ikke ny tid | Last siden på nytt; kontroller runde, kategori og eventuell mellomlagring |
| Banebeskrivelsen mangler | Kontroller riktig runde og kategori i endringspanelet |
| Bildet mangler | Kontroller at bildelenken fortsatt virker og bruker HTTPS |
| For mange passordforsøk | Vent fem minutter før nytt forsøk |

Videre arbeid: verifiser endelig passordoppsett, avklar eventuell regelkonflikt med ansvarlig, fastsett oppbevaringstid for personopplysninger, og planlegg eventuell utrulling til produksjon som et eget arbeid.
