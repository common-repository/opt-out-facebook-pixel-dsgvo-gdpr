=== Opt-Out Facebook Pixel (DSGVO / GDPR) ===
Contributors: schweizersolutions
Tags: facebook, facebook pixel, opt out, opt-out, dsgvo, eu-dsgvo, gdpr, ds-gvo
Requires at least: 3.3
Tested up to: 4.9.5
Requires PHP: 5.3
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://schweizersolutions.com/donate

Der Seitenbesucher kann der Erfassung durch den Facebook Pixel widersprechen (Opt-Out).

== Description ==

Die Datenschutz Grundverordnung (DSGVO, EU-DSGVO, GDPR) sieht vor, dass ein Seitenbesucher die Möglichkeit haben muss, der Erfassung durch den Facebook Pixel zu widersprechen.
Bisher war dieses nur mit komplizierten JavaScript-Code-Einbindungen auf der eigenen Webseite möglich. Mit diesem Plugin geht dies kinderlich und der Benutzer hat auch noch die Möglichkeit den Widerspruch rückgängig zu machen.

**Funktionsumfang**

* **Vollständige Integration der neuen WordPress DSGVO-Funktionen **
* Wöchentliche Überprüfung, ob die Einstellungen noch datenschutzkonform sind!
* Kompatibel mit dem Google Tag Manager
* Seitenbesucher kann den Facebook Pixel für sich deaktivieren und auch nachträglich wieder aktivieren
* Linktext für den Aktivierungs- und Deaktivierungslink kann individuell verändert werden
* Es kann ein Popup eingerichtet werden, welches nach dem Klick des Links erscheint
* Der Code vom Facebook Pixel kann direkt im Plugin eingefügt werden. Keine weiteren Plugins mehr notwendig.
* HTML5 Local Storage Fallback: Löscht ein Nutzer seine Cookies, so kann der Opt-Out Cookie wiederhergestellt werden, wenn der Local Storage vom Browser nicht zusätzlich gelöscht wurde.
* Falls mehr Funktionen (Events auf bestimmten Seiten feuern) gewünscht sind, dann kann ein kompatibles Plugin genutzt werden (siehe Liste unten)
* Wordpress Multisite kompatibel
* [WPML](https://wpml.org/?utm_source=wordpressorg&utm_medium=opt-out-for-google-analytics) & [Polylang](https://de.wordpress.org/plugins/polylang/) kompatibel
* Funktioniert auch auf dem Smartphone, sofern der Browser Cookies unterstützt.
* Übersetzungsdateien für andere Sprachen vorhanden

**🡺 Google Analytics ebenfalls in Verwendung?** Dann sollte dieses Plugin nicht fehlen: [Opt-Out für Google Analytics](https://wordpress.org/plugins/opt-out-for-google-analytics/)

**Wöchentliche Überprüfung, ob die Einstellungen noch datenschutzkonform sind!**

Um hier die höchste Sicherheit zu gewähren, prüft das Plugin wöchentlich die Einstellungen. Sollte eine Einstellung nicht mehr passen, dann erscheint im WP Admin (Backend / Dashboad) eine Fehlermeldung.

Folgende Einstellungen werden geprüft:

* Opt-Out Funktion aktiviert
* Opt-Out Shortcode auf der Seite vorhanden
* Seite mit dem Shortcode öffentlich zugänglich (Veröffentlicht und kein Passwortschutz)

**Kompatibel mit folgenden Plugins**

* [PixelYourSite](https://wordpress.org/plugins/pixelyoursite/)
* [PixelYourSite Pro](http://www.pixelyoursite.com/facebook-pixel-plugin/?utm_source=wordpressorg&utm_medium=opt-out-for-google-analytics)
* [Pixel Cat – Facebook Pixel (Formerly "Facebook Conversion Pixel")](https://wordpress.org/plugins/facebook-conversion-pixel/)
* [Pixel Caffeine](https://wordpress.org/plugins/pixel-caffeine/)
* [Facebook Pixel for WP](https://wordpress.org/plugins/ns-facebook-pixel-for-wp/)

**Vorhandene Übersetzungen**

* Deutsch (de_DE)
* Englisch (en_EN)

**Gefällt dir?**
Es motiviert uns sehr, weiter an unseren kostenlosen Plugins zu arbeiten, wenn Du uns eine [positive Bewertung](https://wordpress.org/support/plugin/fb-opt-out/reviews/#new-post) hinterlässt.

**Coded with love by** [Schweizer Solutions GmbH](https://schweizersolutions.com/?utm_source=wordpressorg&utm_medium=plugin)

== Installation ==

**Installation über Wordpress**

1. Gehe ins Dashboard: `Plugins > Installaieren`
2. Suche nach: `Opt-Out Facebook`
3. Klicke dort auf den grauen Button `Installieren`
4. Aktiviere das Plugin

**Manuelle Installation**

1. Lade das Verzeichnis `fb-opt-out` ins `/wp-content/plugins/` Verzeichnis deiner Wordpress Insallation
2. Aktiviere das Plugin

**Konfiguration**

1. Gehe ins Dashboard: `Einstellungen > FB Opt-Out`
2. Aktiviere das Plugin, falls nicht aktiviert
3. Wähle ein kompatibles Plugin aus oder trag den Facebook Pixel Code direkt ein
4. Änderungen speichern, fertig!

**Anmerkung zum Code:**

Falls Du den Code für den Facebook Pixel händisch in dieses Plugin einfügst, dann stell bitte sicher, dass du diesen Code nicht noch an einer anderen Stelle eingebunden hast.
Wir können nur diesen Code oder von den kompatiblen Plugins entfernen, wenn der Seitenbesucher ein Opt-Out gemacht hat.

== Frequently Asked Questions ==

= Warum sollte ich dieses Plugin verwenden? =

Die Datenschutz Grundverordnung (DSGVO, EU-DSGVO, GDPR) sieht vor, dass ein Seitenbesucher die Möglichkeit haben muss, der Erfassung durch den Facebook Pixel zu widersprechen.
Bisher war dieses nur mit einer komplizierten JavaScript-Code-Einbindungen auf der eigenen Webseite möglich. Mit diesem Plugin geht dies kinderlich und der Benutzer hat auch noch die Möglichkeit den Widerspruch rückgängig zu machen.

= Ich habe den Facebook Pixel Code über das Theme / ein Plugin hänidsch eingefügt. Kann ich dieses Plugin trotzdem nutzen? =

Wenn es ein Plugin ist, welches mit diesem Plugin kompatibel ist, dann ja. Solltest Du den Code händisch in dein Theme eingebunden haben, so muss dieser dort entfernt werden und bei diesem Plugin eingefügt werden.
Ansonsten haben wir keine Möglichkeit, bei einem Opt-Out des Seitenbesuchers, den Code zu entfernen.

= Wie lange behält das Austragen seine Gültigkeit? =

Klickt der Seitenbesucher auf den Opt-Out Link, dann wird ein Cookie gesetzt. Mit diesem Cookie weiß das System, dass dieser Seitenbesucher auf der Webseite nicht getrackt werden soll.

Dieses Cookie ist nur in dem Browser gültig, mit dem der Seitenbesucher auf der Webseite war und auf den Opt-Out Link geklickt hat. Nutzt dieser einen anderen Browser, müsste er auch hier noch mal auf den Link klicken.

Leert der Seitenbesucher seine Browserdaten (Cookies, Downloadverlauf etc.), dann ist das Cookie ebenfalls gelöscht und der Seitenbesucher müsste erneut auf den Opt-Out Link klicken.

= Woher weiß ich, dass der Opt-Out funktioniert? =

Mit dem "Facebook Pixel Helper", einem Addon für den Browser, kannst Du die Funktionalität von diesem Plugin testen. Der Helper zeigt Dir an, ob ein Facebook Pixel gefeuert wurde bzw. eingebunden ist.

Solltest Du nun auf den Opt-Out-Link geklickt und dadurch dem Tracking widersprochen haben, dann musst Du die Webseite einmal neu laden lassen. Nun sollte der Helper keinen Facebook Pixel mehr finden.
Klick erneut auf den Link, um den Widerspruch rückgängig zu machen, und lade die Webseite neu. Jetzt sollte der Helper wieder einen Facebook Pixel finden.

Mehr Infos zum Addon: (https://developers.facebook.com/docs/facebook-pixel/pixel-helper?locale=de_DE)[https://developers.facebook.com/docs/facebook-pixel/pixel-helper?locale=de_DE]

= Wo kann ich den Shortcode verwenden? =

Den Shortcode `[fboo_optout]` kannst du in den Beiträgen, auf den Seiten und bei den Widgets (Text-Widget) verwenden.

= Kann ich als Entwickler ins Plugin eingreiffen? =

Ja, du kannst. Dazu haben wir entsprechende Filter und Action Hooks eingebaut.

`// Bevor der Shortcode aufgelöst wird
add_action( 'fboo_before_shortcode', 'my_before_shortcode', 10, 1);

function my_before_shortcode( $current_status ) {
	// $current_status - Der aktuelle Status vom Seitenbesucher: activate oder deactivate
}

// Nachdem der Shortcode aufgelöst wird
add_action( 'fboo_after_shortcode', 'my_after_shortcode', 10, 1);

function my_after_shortcode( $current_status ) {
	// $current_status - Der aktuelle Status vom Seitenbesucher: activate oder deactivate
}

// Bevor der händisch eingetragene JS-Code, zum Deaktivieren vom Facebook Pixel, ausgegeben wird
add_action( 'fboo_before_head_script', 'my_before_script', 10, 1);

function my_before_script( $code ) {
	// $code - Der JS-Code
}

// Nachdem der JS-Code, zum Deaktivieren von GA, ausgegeben wird
add_action( 'fboo_after_head_script', 'my_after_script', 10, 1);

function my_after_script( $code ) {
	// $code - Der JS-Code
}

// Ob die Seite nach dem Klick neu geladen werden soll
add_filter( 'fboo_force_reload', 'my_force_reload', 10, 1);

function my_force_reload( $force ) {
	// $force - "true" = Neuladen erzwingen; "false" = nicht neuladen
}
`

= Mein Theme löst keine Shortcodes auf. Wie kann ich die Funktion über PHP nutzen? =

Es kommt vor, dass einige selbstentwickelten Themes keine Shortcodes auflösen und das Plugin dadurch nicht funktioniert.
Um den Shortcode trotzdem ausführen zu lassen, muss im PHP-Code vom Theme, an der gewünschten Stelle dieser Code verwendet werden:

`echo do_shortcode('[fb_optout]');`

= Es wird angezeigt, dass die Einstellungen nicht korrekt sind, obwohl ich diese geändert habe! =

Die Prüfung erfolgt automatisch einmal die Woche, entsprechend wird auch das Ergebnis für eine Woche gespeichert.
Es besteht die Möglichkeit, die Ergebnisse vorher zu aktualisieren. Dazu müsste unter "Einstellungen > FB Opt-Out" der Button "Änderungen speichern" geklickt werden.

Dadurch werden die neuen Einstellungen eingelesen und enstprechend für eine Woche gespeichert, bis zur nächsten autom. Prüfung.

= Wie verändere ich das Aussehen von dem Link? =

Du hast die Möglichkeit über CSS die Darstellung von dem Link zu verändern. Dazu stehen dir folgende CSS-Klassen zur Verfügung:

**Der Link selbst:**
`#fboo-link { ... }`

**Der Link, wenn der Seitenbesucher dem Tracking widersprochen hat:**
`.fboo-link-activate { ... }`

**Der Link, wenn der Seitenbesucher dem Tracking NICHT widersprochen hat:**
`.fboo-link-deactivate { ... }`

= Google Tag Manager verwenden =

Bei einem Opt-Out vom Facebook Pixel, sollte der ganze JavaScript-Code des Pixels nicht geladen werden. Für die Überprüfung steht  ein Cookie und  ein Eintrag in der Local Storage zur Verfügung.
Der Name vom Cookie und der Eintrag in der Local Storage sind indentisch: fb-opt-out ...

Beim Google Tag Manager sollte geprüft werden ob eins von beidem gesetzt ist. Ist kein Eintrag oder Cookie verohanden, dann ist kein Opt-Out erfolgt. Dies ist ebenfalls der Fall, wenn der Wert "false" zurückgeben wird.
Der Opt-Out ist erst erfolgt, wenn der Wert "true" zurückgegeben wird.

= Haftung / Disclaimer =

Die Verwendung dieses Plugins erfolgt auf eigener Gefahr. Der Webseitenbetreiber muss die Funktionalität des Plugins selber sicherstellen können.
Lies bitte dazu die FAQ "Woher weiß ich, dass der Opt-Out funktioniert?".

== Screenshots ==

1. Einstelungsübersicht des Plugins (Dashboard: Einstellungen > FB Opt-Out)
2. Shortcode im Texteditor eingefügt
3. Aktivierungs-/Deaktivierungslink mit Popup, auf der Seite

== Changelog ==

= 1.1 =
* Added: Support for WordPress 4.9.6 GDPR - Icons for the shortcode on the privacy page & sync. the page id for the shortcode.
* Added: HTML5 local storage fallback. Cookies deleted, but the local storage not, restore cookie.
* Added: Check if the page with the shortcode is accessibile
* Added: Google Tag Manager support. Use this plugin for the opt-out and handle the pixel code on GTM.
* Added: WPML compatibility
* Added: Support for the plugin "PixelYourSite Pro".
* Added: Possibility to disable the notice on dashboard, the settings aren't right.
* Added: Possibility to force page reload, after link click.
* Fix: PHP error generated on older PHP (<5.3) versions
* Several code and usability optimazation

= 1.0 =
* 15. Mai 2018
* Initial Release