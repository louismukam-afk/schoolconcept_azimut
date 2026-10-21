@extends('skeleton')
@section('sub-content')
<div class="module-print">
@include('eleves.anonymats._print_layout_style')
<div class="no-print" style="margin-bottom:15px"><button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Imprimer / Enregistrer en PDF</button></div>
<style>
    @page{size:A3 landscape;margin:8mm}
    .module-document{page-break-after:always;font-size:8px}
    .module-document:last-child{page-break-after:auto}
    .module-document .module-sheet{margin:0;page-break-after:auto}
    .module-sheet-header{page-break-inside:avoid}
    .module-custom-header{min-height:50mm}
    .module-custom-header .school-nav,.module-custom-header .other-nav{width:95mm;font-size:9px;line-height:1.15}
    .module-custom-header .logo-nav{width:108px!important;height:108px!important;left:45%!important;top:2px}
    .module-header-extra{margin-top:2px;font-size:7.5px;line-height:1.12}
    .module-header-extra-logo{max-width:42px;max-height:32px;width:auto;height:auto;object-fit:contain;margin:1px auto;display:block}
    .module-header-extra-name{font-weight:bold;text-transform:uppercase}
    .module-header-extra-line{font-size:7px}
    .module-print .titre-ecole{margin-top:8mm!important}
    .module-print .div-bar{margin-top:0!important}
    .module-title{text-align:center;font-size:14px;margin:8px 0;padding:6px;border:1px solid #222;background:#ddd!important;color:#000!important}
    .module-context{width:100%;border-collapse:collapse;margin-bottom:8px}
    .module-context th,.module-context td{border:1px solid #222;padding:4px}
    .module-table{width:100%;border-collapse:collapse;table-layout:auto}
    .module-table th,.module-table td{border:1px solid #222;padding:3px;text-align:center;vertical-align:middle}
    .module-table th{background:#eee!important}
    .module-table .student-name{text-align:left;min-width:130px}
    .module-table .student-number{min-width:70px}
    .module-table .anonymat-cell{white-space:nowrap;font-family:monospace}
    .module-legend{border:1px solid #222;border-top:0;padding:5px}
    .module-legend span{display:inline-block;margin-right:12px}
    .decision-v{font-weight:bold}.decision-nv{font-weight:bold}
    @media print{.no-print,.navbar,.sidebar-collapse{display:none!important}}
</style>
@forelse($modules as $module)
<section class="module-document">
    <div class="module-sheet-header">
        @include('eleves.anonymats._module_tutelle_header')
        <h3 style="text-align:center;margin:8px 0 3px">PROCES-VERBAL DES NOTES PAR MODULE</h3>
        <p style="text-align:center;margin:0 0 8px">{{$examLabel}} | {{$moduleNoteModeLabel}} | {{$selection['semestre']}} | {{session('year')}}</p>
        <table class="module-context"><tbody>
        <tr><th>Campus</th><td>{{$campus?$campus->nom:''}}</td><th>Cycle</th><td>{{$cycle?$cycle->nom:''}}</td><th>Niveau</th><td>{{$nniveau?$nniveau->nom:''}}</td></tr>
        <tr><th>Filiere</th><td colspan="2">{{$filiere?$filiere->nom:''}}</td><th>Specialite</th><td colspan="2">{{$classe?$classe->nom:''}}</td></tr>
        </tbody></table>
    </div>
    @include('eleves.anonymats._module_table', array('printMode' => true))
</section>
@empty
<div class="alert alert-warning">Aucun groupe de matieres configure pour cette selection.</div>
@endforelse
</div>
@endsection
