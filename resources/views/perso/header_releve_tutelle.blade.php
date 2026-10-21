<?php
    $headerCampus = isset($campus) ? $campus : null;
    $headerTutelle = isset($tutelle) ? $tutelle : null;

    if (!$headerCampus && session('campus_id')) {
        $headerCampus = \gesecole\Campus::find(session('campus_id'));
    }

    $cleanHeader = function ($value) {
        return trim((string) $value);
    };

    $logoPath = function ($value, $fallback = '') use ($cleanHeader) {
        $path = str_replace('\\', '/', $cleanHeader($value));
        if ($path === '') return $fallback;
        if (strpos($path, 'public/') === 0) $path = substr($path, 7);
        if (strpos($path, '/public/') !== false) $path = substr($path, strpos($path, '/public/') + 8);
        return ltrim($path, '/');
    };

    $campusName = $headerCampus && $cleanHeader(isset($headerCampus->nom) ? $headerCampus->nom : '') !== ''
        ? $headerCampus->nom
        : 'INSTITUT SUPERIEUR AZIMUT DE COMMERCE, DE TECHNOLOGIE ET DE SANTE';
    $campusLogo = $logoPath($headerCampus && isset($headerCampus->logo) ? $headerCampus->logo : '', 'img/azimut.png');
    $tutelleName = $headerTutelle && $cleanHeader(isset($headerTutelle->nom) ? $headerTutelle->nom : '') !== ''
        ? $headerTutelle->nom
        : '';
    $tutelleNameEn = $headerTutelle && $cleanHeader(isset($headerTutelle->tutelle_anglophone) ? $headerTutelle->tutelle_anglophone : '') !== ''
        ? $headerTutelle->tutelle_anglophone
        : '';
    $tutelleFaculty = $headerTutelle && $cleanHeader(isset($headerTutelle->faculte) ? $headerTutelle->faculte : '') !== ''
        ? $headerTutelle->faculte
        : '';
    $tutelleFacultyEn = $headerTutelle && $cleanHeader(isset($headerTutelle->faculte_anglophone) ? $headerTutelle->faculte_anglophone : '') !== ''
        ? $headerTutelle->faculte_anglophone
        : '';
    $tutelleLogo = $logoPath($headerTutelle && isset($headerTutelle->logo) ? $headerTutelle->logo : '', '');
    $facultyLogo = $logoPath($headerTutelle && isset($headerTutelle->logo_faculte) ? $headerTutelle->logo_faculte : '', '');

    $ministereFr = $headerTutelle && $cleanHeader(isset($headerTutelle->ministere_tutelle) ? $headerTutelle->ministere_tutelle : '') !== ''
        ? $headerTutelle->ministere_tutelle
        : "MINISTERE DE L'ENSEIGNEMENT SUPERIEUR";
    $ministereEn = $headerTutelle && $cleanHeader(isset($headerTutelle->ministere_tutelle_anglophone) ? $headerTutelle->ministere_tutelle_anglophone : '') !== ''
        ? $headerTutelle->ministere_tutelle_anglophone
        : 'MINISTRY OF HIGHER EDUCATION';
    $republiqueFr = $headerTutelle && $cleanHeader(isset($headerTutelle->republique_francophone) ? $headerTutelle->republique_francophone : '') !== ''
        ? $headerTutelle->republique_francophone : 'REPUBLIQUE DU CAMEROUN';
    $republiqueEn = $headerTutelle && $cleanHeader(isset($headerTutelle->republique_anglophone) ? $headerTutelle->republique_anglophone : '') !== ''
        ? $headerTutelle->republique_anglophone : 'REPUBLIC OF CAMEROON';
    $campusAddress = $headerCampus && isset($headerCampus->localisation) ? $headerCampus->localisation : '';
    $campusApproval = $headerCampus && isset($headerCampus->arrete_creation) ? $headerCampus->arrete_creation : '';
    $campusAuthorization = $headerCampus && isset($headerCampus->autorisation_ouverture) ? $headerCampus->autorisation_ouverture : '';
    $campusPostal = $headerCampus && isset($headerCampus->boite_postale) ? $headerCampus->boite_postale : '';
    $campusPhone = $headerCampus && isset($headerCampus->telephone) ? $headerCampus->telephone : '';
    $tutellePostal = $headerTutelle && isset($headerTutelle->localisation) ? $headerTutelle->localisation : '';
    $tutellePhone = $headerTutelle && isset($headerTutelle->telephone) ? $headerTutelle->telephone : '';
    $campusContact = $campusPostal;
    if ($campusAddress) $campusContact .= ($campusContact ? ', ' : '').$campusAddress;
    if ($campusPhone) $campusContact .= ($campusContact ? ', ' : '').'Tél : '.$campusPhone;
    $tutelleContact = $tutellePostal;
    if ($tutellePhone) $tutelleContact .= ($tutelleContact ? ', ' : '').'Tél : '.$tutellePhone;
    $tutelleFacultyDisplay = $tutelleFaculty;
    if ($tutelleFacultyEn) $tutelleFacultyDisplay .= ($tutelleFacultyDisplay ? ' / ' : '').$tutelleFacultyEn;

    $campusLines = array();
    foreach (array('autorisation_ouverture', 'arrete_creation', 'boite_postale', 'telephone', 'email', 'site_web') as $field) {
        if ($headerCampus && $cleanHeader(isset($headerCampus->$field) ? $headerCampus->$field : '') !== '') {
            $campusLines[] = $headerCampus->$field;
        }
    }
?>
<style>
    .releve-dynamic-header { font-family: Arial, sans-serif; text-align: center; margin-bottom: 8px; }
    .releve-dynamic-ministry { font-size: 11px; font-weight: bold; line-height: 1.15; margin-bottom: 8px; }
    .releve-dynamic-top { display: table; width: 100%; font-size: 9px; font-weight: bold; line-height: 1.15; }
    .releve-dynamic-col { display: table-cell; vertical-align: top; padding: 2px 5px; }
    .releve-dynamic-left { width: 36%; text-align: center; }
    .releve-dynamic-center { width: 32%; text-align: center; }
    .releve-dynamic-right { width: 32%; text-align: center; }
    .releve-dynamic-logo { max-width: 76px; max-height: 68px; object-fit: contain; margin: 0 auto 5px; vertical-align: middle; }
    .releve-dynamic-school { font-family: "Arial Black", Arial, sans-serif; font-size: 11px; text-transform: uppercase; margin: 2px 0; }
    .releve-dynamic-small { font-size: 9px; margin: 1px 0; }
    .releve-dynamic-bar { border-top: 1px solid #000; margin: 5px auto 4px; width: 100%; }
</style>
<div class="releve-dynamic-header">
    <div class="releve-dynamic-ministry">{{ $republiqueFr }} / {{ $ministereFr }}<br><em>{{ $republiqueEn }} / {{ $ministereEn }}</em></div>
    <div class="releve-dynamic-top">
        <div class="releve-dynamic-col releve-dynamic-left">
            @if($campusLogo)<img src="{{ URL::asset($campusLogo) }}" class="releve-dynamic-logo" alt="Logo campus">@endif
            <p class="releve-dynamic-school">{{ $campusName }}</p>
            @if($campusApproval)<p>{{ $campusApproval }}</p>@endif
            @if($campusAuthorization)<p>{{ $campusAuthorization }}</p>@endif
            @if($campusContact)
                <p>{{ $campusContact }}</p>
            @endif
        </div>
        <div class="releve-dynamic-col releve-dynamic-center">
            @if($tutelleLogo)<img src="{{ URL::asset($tutelleLogo) }}" class="releve-dynamic-logo" alt="Logo tutelle">@endif
            <p>{{ $tutelleName }}</p>
            @if($tutelleContact)<p>{{ $tutelleContact }}</p>@endif
        </div>
        <div class="releve-dynamic-col releve-dynamic-right">
            @if($facultyLogo)<img src="{{ URL::asset($facultyLogo) }}" class="releve-dynamic-logo" alt="Logo faculté">@endif
            @if($tutelleFaculty)<p class="releve-dynamic-school">{{ $tutelleFaculty }}</p>@endif
            @if($tutelleFacultyEn)<p class="releve-dynamic-small">{{ $tutelleFacultyEn }}</p>@endif
        </div>
    </div>
    <div class="releve-dynamic-bar"></div>
</div>
