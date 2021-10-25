@extends('layouts.app')

@section('content')
{{--  If you want to add a new accordion be sure to change the id,data,aria from the accordion so the right accordion will open at all times and won't open 2 at the same time :)  --}}
    <!--suppress ALL -->
<h1>Hier vind je alle uitleg over JGPlanning</h1>
    {{--  Help for only admins  --}}
    @if($user['role_id'] == App\Models\Role::getRoleID('admin') || $user['role_id'] == App\Models\Role::getRoleID('maintainer'))
        <h6>Administrator Functies</h6>
        <div class="accordion" id="helpAdminAccordion">
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminOne" aria-expanded="true" aria-controls="collapseAdminOne">
                        Nieuwe Gebruiker Aanmaken
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminOne" class="accordion-collapse collapse" aria-labelledby="headingAdminOne" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
                    </div>
                </div>
            </div>
            {{-- Accordion item title --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminThree" aria-expanded="false" aria-controls="collapseAdminThree">
                        Verwijder een Huidige Gebruiker
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminThree" class="accordion-collapse collapse" aria-labelledby="headingAdminThree" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminFive" aria-expanded="false" aria-controls="collapseAdminFive">
                        Bekijk Alle Ingeklokte Gebruikers
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminFive" class="accordion-collapse collapse" aria-labelledby="headingAdminFive" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdminSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdminSix" aria-expanded="false" aria-controls="collapseAdminSix">
                        Bekijk Alle Ingeklokte Gebruikers
                    </button>
                </h2>
                {{-- Accordion Contents --}}
                <div id="collapseAdminSix" class="accordion-collapse collapse" aria-labelledby="headingAdminSix" data-bs-parent="#helpAdminAccordion">
                    <div class="accordion-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra sapien nec lorem ultricies placerat. Pellentesque euismod bibendum est in dignissim. Sed est ex, egestas.
                    </div>
                </div>
            </div>
        </div><br>
    @endif


    <h6>Algemene Functies</h6>
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
                    Op deze pagina kunt u uw profiel <strong>bekijken</strong> en <strong>bewerken</strong>.
                </div>
            </div>
        </div>
    </div>
@endsection
