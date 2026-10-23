<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><title>PV Admission — {{ $payload['cycle'] }}</title>
<style>
body{font-family:Arial,sans-serif;background:#eee;margin:0;color:#111;font-size:12px}.toolbar{padding:16px;background:white;position:sticky;top:0}.toolbar a,.toolbar button{padding:10px;margin-right:8px;cursor:pointer}.sheet{background:white;max-width:740px;margin:20px auto;padding:30px}.header{display:flex;justify-content:space-between;text-align:center;font-size:9px}.header>div{width:43%}.header img{width:65px;object-fit:contain}h2,h3{text-align:center}h2{font-size:18px}h3{font-size:15px}table{width:100%;border-collapse:collapse;font-size:10px;margin-top:18px}th,td{border:1px solid #555;padding:4px}th{background:#ddd}td:first-child,td:nth-child(n+4){text-align:center}.meta{line-height:1.5}.empty{text-align:center;padding:30px}
@page{size:A4 portrait;margin:12mm}@media print{body{background:white}.toolbar{display:none}.sheet{margin:0;padding:0;max-width:none;break-after:page}.sheet:last-child{break-after:auto}thead{display:table-header-group}tr{break-inside:avoid}}
</style></head><body>
<div class="toolbar"><a href="{{ url('/eleves/admissions') }}">Nouvelle sélection</a>@if(count($payload['groups']))<button id="download-pdf">Télécharger le PDF global — {{ $payload['cycle'] }}</button>@endif<span>{{ $total }} admis · {{ count($payload['groups']) }} groupe(s)</span><span id="pdf-status" role="status"></span></div>
@forelse($payload['groups'] as $group)
<section class="sheet">
<div class="header"><div>RÉPUBLIQUE DU CAMEROUN<br>Paix – Travail – Patrie<br>MINISTÈRE DE L’ENSEIGNEMENT SUPÉRIEUR<br>{{ $payload['campus'] }}<br>{{ $payload['adresse'] }}</div>@if($payload['logo'])<img src="{{ $payload['logo'] }}" alt="Logo du campus">@endif<div>REPUBLIC OF CAMEROON<br>Peace – Work – Fatherland<br>MINISTRY OF HIGHER EDUCATION<br>{{ $payload['campus'] }}<br>{{ $payload['adresse'] }}</div></div>
<h2>Procès-verbal d’admission<br>Année académique : {{ $payload['annee'] }}</h2>
<div class="meta">Cycle : {{ $payload['cycle'] }}<br>Filière : {{ $group['filiere'] }}<br>Spécialité : {{ $group['specialite'] }} — Niveau : {{ $group['niveau'] }}<br>{{ $payload['session'] }}<br>Effectif : {{ $group['effectif'] }} — Admis : {{ count($group['rows']) }}</div>
<h3>PV Admission</h3><table><thead><tr><th>Rang</th><th>Matricule</th><th>Nom(s) et Prénom(s)</th><th>Moyenne /20</th><th>Crédit /{{ $group['credits'] }}</th><th>Décision finale</th></tr></thead><tbody>@foreach($group['rows'] as $row)<tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>@endforeach</tbody></table>
</section>
@empty<div class="sheet empty">Aucun étudiant admis pour ce cycle et cette période.</div>@endforelse
<script src="{{ asset('js/datatables/pdfmake-0.1.36/pdfmake.min.js') }}"></script><script src="{{ asset('js/datatables/pdfmake-0.1.36/vfs_fonts.js') }}"></script><script src="{{ asset('js/admissions_pdf.js') }}"></script>
<script>
(function(){var data = {!! json_encode($payload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};var button=document.getElementById('download-pdf');if(button)button.onclick=function(){try{pdfMake.createPdf(AdmissionPdf.buildDocument(data)).download('PV_Admission_'+data.cycle.replace(/[^a-z0-9]/gi,'_')+'_'+data.annee.replace(/[^a-z0-9]/gi,'_')+'.pdf');}catch(error){document.getElementById('pdf-status').textContent='Le PDF n’a pas pu être généré. Veuillez réessayer.';}};})();
</script></body></html>
