@extends('layouts.app')

@section('content')
{{--  TODO: ALS JE NOG EXTRA INFORMATIE OF UITLEG HEBT, GRAAG TOEVOEGEN OF VERANDEREN WAAR NODIG  --}}
{{--  If you want to add a new accordion be sure to change the id,data,aria from the accordion so the right accordion will open at all times and won't open 2 at the same time :)  --}}
    <!--suppress ALL -->
<h1>Hier vind je alle uitleg over JGPlanning</h1>
    {{--  Help for only admins  --}}
    @if($user['role_id'] == App\Models\Role::getRoleID('admin') || $user['role_id'] == App\Models\Role::getRoleID('maintainer'))
        <br><h4>Administrator Functies</h4><hr>
        <h6>Gebruikers</h6>
        <div class="accordion" id="helpAdminAccordion">
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminOne" aria-expanded="true" aria-controls="collapseAdminOne">
                        Nieuwe Gebruiker Aanmaken
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminOne" class="accordion-collapse collapse show" aria-labelledby="headingAdminOne" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Om een nieuwe gebruiker aan te maken <strong>klik dan op het gebruiker icoontje met een plusje</strong> naast de titel van de tabel.<br>
                        Als je hierop hebt geklikt, voer dan de juiste gegevens in van de nieuwe gebruiker.<br>
                        @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                        Als Beheerder kies de bijpassende rol van de nieuwe gebruiker.<br>
                        @endif
                        Klik daarna op de grote knop <strong>Creëer</strong> onderaan.<br>
                        <strong>Let Op!</strong> Een gebruiker kan <strong>niet</strong> dezelfde E-mail hebben als een huidige gebruiker.
                    </div>
                </div>
            </div>
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminTwo" aria-expanded="false" aria-controls="collapseAdminTwo">
                        Huidige Gebruiker Bewerken
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminTwo" class="accordion-collapse collapse" aria-labelledby="headingAdminTwo" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Om een huidige gebruiker te veranderen, <strong>klik dan op het gebruiker icoontje met een pennetje</strong> aan het einde van de rij.<br>
                        Als je hierop hebt geklikt kan je alle relvante informatie zien van de gekozen gebruiker.<br>
                        Voer nieuwe informatie in of verander huidige gegevens in van de gebruiker.<br>
                        @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                            Als Beheerder kies de bijpassende rol van de nieuwe gebruiker.<br>
                        @endif
                        Als je klaar bent, klik dan op de grote <strong>Bewerk</strong> knop onderaan om op te slaan.<br>
                        <strong>Let Op!</strong> Een gebruiker kan <strong>niet</strong> dezelfde E-mail hebben als een huidige gebruiker.
                    </div>
                </div>
            </div>
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminThree" aria-expanded="false" aria-controls="collapseAdminThree">
                        Verwijder/Herstel een Huidige Gebruiker
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminThree" class="accordion-collapse collapse" aria-labelledby="headingAdminThree" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        <strong>Verwijderen</strong><br>
                        Om een huidige gebruiker te verwijderen <strong>klik dan op het rode gebruiker icoontje met een streep er doorheen.</strong><br>
                        Hierdoor zal de gebruiker <strong>niet meer kunnen inloggen</strong> en dus heeft geen toegang tot zijn/haar account.
                        @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                            <br><strong>Let Op!</strong> <strong>Beheerders</strong> kunnen <strong>niet</strong> Verwijderd worden.<br>
                            Wil je dat wel doen, geef dan eerst de gebruiker een andere rol.
                        @endif
                        <br><br>
                        <strong>Herstellen</strong><br>
                        Om een verwijderde gebruiker te herstellen, <strong>klik dan op het groene gebruiker icoontje met een vinkje.</strong><br>
                        Hierdoor zal de gebruiker weer met zijn/haar gegevens kunnen inloggen.
                    </div>
                </div>
            </div>
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminFour" aria-expanded="false" aria-controls="collapseAdminFour">
                        Bekijk Alle Informatie van de Gebruiker
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminFour" class="accordion-collapse collapse" aria-labelledby="headingAdminFour" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Om een huidige gebruiker te bekijken, <strong>klik dan op het gebruiker icoontje met het tandwieltje.</strong><br>
                        Hierdoor zal je <strong>alle</strong> informatie te zien krijgen van de gekozen gebruiker.<br>
                        @if($user['role_id'] == App\Models\Role::getRoleID('maintainer'))
                            Als Beheerder kan je <strong>iedereen</strong> aanpassen.
                        @endif
                        @if($user['role_id'] == App\Models\Role::getRoleID('admin'))
                            Als Admin kan je alleen <strong>Medewerkers</strong> aanpassen.
                        @endif
                    </div>
                </div>
            </div><br>
            <h6>Overige</h6>
            <div class="accordion">
                {{-- Accordion item title --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdminFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminFive" aria-expanded="false" aria-controls="collapseAdminFive">
                            Bekijk Alle Ingeklokte Gebruikers
                        </button>
                    </h2>
                    {{-- Accordion Contents --}}
                    <div id="collapseAdminFive" class="accordion-collapse collapse" aria-labelledby="headingAdminFive" data-bs-parent="#helpAdminAccordion">
                        <div class="accordion-body">
                            Hier staat een overzicht van alle gebruikers die <strong>op dit moment</strong> zijn ingeklokt.<br>
                            Je kan <strong>sorteren op Datum en op Gebruiker.</strong><br>
                            Om toe te passen, klik op de <strong>'SELECTEER'</strong> knop.
                        </div>
                    </div>
                </div>
                {{-- Accordion item title --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdminSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminSix" aria-expanded="false" aria-controls="collapseAdminSix">
                            Bekijken/Dichtzetten van het Rooster van Alle Gebruikers
                        </button>
                    </h2>
                    {{-- Accordion Contents --}}
                    <div id="collapseAdminSix" class="accordion-collapse collapse" aria-labelledby="headingAdminSix" data-bs-parent="#helpAdminAccordion">
                        <div class="accordion-body">
                            Zodra je op Rooster klikt, krijg je een tabel met daarin alle gebruikers.<br>
                            Klik op een gebruiker waarvan je het rooster wilt <strong>bekijken/dichtzetten.</strong><br>
                            Daarna zal je het hele overzicht kunnen zien van het rooster.<br>
                            Met de knoppen naast de dagen kun je de tijden <strong>dichtzetten.</strong><br>
                            Ook kun je bovenaan weer een andere gebruiker selecteren om het rooster te zien.<br>
                        </div>
                    </div>
                </div>
                {{-- Accordion item title --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdminSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminSeven" aria-expanded="false" aria-controls="collapseAdminSeven">
                            Vergelijk Roostertijden met Gewerkte tijden
                        </button>
                    </h2>
                    {{-- Accordion Contents --}}
                    <div id="collapseAdminSeven" class="accordion-collapse collapse" aria-labelledby="headingAdminSeven" data-bs-parent="#helpAdminAccordion">
                        <div class="accordion-body">
                            Zodra je op Vergelijken klikt, krijg je een tabel met daarin alle gebruikers.<br>
                            Hier zijn de Tijden van de gebruiker weergegeven.<br>
                            Je kan <strong>sorteren op Gebruiker, Datum, Maand en Week.</strong><br>
                            Om toe te passen, klik op de <strong>'SELECTEER'</strong> knop.
                        </div>
                    </div>
                </div>
            </div>
        </div><br>
    @endif


    <h4>Algemene Functies</h4>
    <hr>
    {{--  Help for all users to see  --}}
    <div class="accordion" id="helpAccordion">
        {{-- Accordion item title --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Inklokken
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                    Zodra je bent ingelogd en je wilt inklokken, klik dan op de knop <strong>'CLOCK IN'</strong> op het dashboard. Indien nodig geef een aantekening mee.
                </div>
            </div>
        </div>
        {{-- Accordion item title --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Uitklokken
                </button>
            </h2>
            {{-- Accordion Contents --}}
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                    Zodra je bent ingeklokt en je werktijd zit erop, klik dan op de knop <strong>'CLOCK OUT'</strong>. Hier kan je niet een aantekening aan meegeven. Kloktijden worden doorgestuurde naar de Administrators.
                </div>
            </div>
        </div>
        {{-- Accordion item title --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Rooster van de Gebruiker
                </button>
            </h2>
            {{-- Accordion Contents --}}
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                    Als eerste kan de gebruiker een <strong>overzicht zien van het rooster.</strong><br>
                    Als tweede kan de gebruiker zijn/haar <strong>beschikbaarheid aangeven</strong> met gebruik van het <strong>potloodje</strong> en weer <strong>verwijderen</strong> met de <strong>prullenbak.</strong><br>
                    <strong>Verdere Informatie:</strong> Nadat u uw beschikbaarheid heeft aangegeven, zal deze te zien zijn voor alle Administrators, die deze tijden kunnen afwijzen of goedkeuren. Als dit process over is zal de uitkomst wat de Administrator heeft gekozen uw echte rooster worden.
                </div>
            </div>
        </div>
        {{-- Accordion item title --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Profiel Bekijken en Bewerken
                </button>
            </h2>
            {{-- Accordion Contents --}}
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                    Op deze pagina kunt u uw profiel <strong>bekijken</strong> en <strong>bewerken.</strong><br>
                    Om te bewerken, klik dan op de <strong>Bewerk</strong> knop.<br>
                    Vul daarna de correcte gegevens in en klik dan vervolgens op Bewerk.<br>
                    <strong>Let Op!</strong> Een gebruiker kan <strong>niet</strong> dezelfde E-mail hebben als een huidige gebruiker.
                </div>
            </div>
        </div>
    </div>
    <hr>
@endsection
