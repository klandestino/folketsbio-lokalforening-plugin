# Folketsbios lokalföreningar
WordPressplugin för att importera film- och visningsdata från folketsbio.se och Bioguiden.

## Installera
Installera och aktivera först pluginet [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/), sedan aktiverar du detta plugin. Därefter kan du fylla i din biografs inloggningsuppgifter till Bioguiden under Inställningar->Bioguiden.

## Använda
Pluginet skapar två posttyper, film och visning. För att importera data om en film från folketsbio.se och visningar från bioguiden så går du till Film->Lägg till och fyller i "Filmnummer (32-siffrigt)" och trycker sedan på spara utkast. Då kommer information om filmen att hämtas från folketsbio.se om den distribueras av Folkets bio, samt visningar från Bioguiden om din biograf har lagt in visningar där. Det går också bra att lägga till visningar manuellt ifall de inte är inlagda i Bioguiden.

## För utvecklare
Visningar sparas som en dold posttyp med filmen som post_parent, så för att lista visningar på en enskild filmsida så gör typ:
```php
$visningar = new WP_Query( [
  'post_type' => 'visning',
  'post_parent' => get_the_ID(),
  'post_status' => [ 'publish', 'future' ],
  'posts_per_page' => -1,
  'date_query' => [
    [
		  'after' => '-1 day', //Eller hur en nu vill sortera bort gamla visningar...
		],
	],
	'order' => 'ASC',
] );
```

Om ni upptäcker några problem med pluginet så skapa gärna issues här på github eller ännu bättre, gör en pull request. :)
