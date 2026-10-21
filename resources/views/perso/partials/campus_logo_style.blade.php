<?php
    $headerCampusLogo = isset($campus) ? $campus : null;
    if (!$headerCampusLogo && session('campus_id')) {
        $headerCampusLogo = \gesecole\Campus::find(session('campus_id'));
    }

    $resolveCampusLogoPath = function ($value, $fallback = 'img/logo.png') {
        $path = trim((string) $value);
        $path = str_replace('\\', '/', $path);
        if ($path === '') return $fallback;
        if (strpos($path, 'public/') === 0) $path = substr($path, 7);
        if (strpos($path, '/public/') !== false) $path = substr($path, strpos($path, '/public/') + 8);
        return ltrim($path, '/');
    };

    $campusLogoPath = $resolveCampusLogoPath($headerCampusLogo && isset($headerCampusLogo->logo) ? $headerCampusLogo->logo : '');
?>
<style>
    .custom-header .logo-nav {
        background: url('{{ URL::asset($campusLogoPath) }}') !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
    }
</style>
