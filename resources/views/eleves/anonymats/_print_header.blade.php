<div class="no-print" style="margin-bottom:15px"><button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Imprimer / Enregistrer en PDF</button></div>
@include('eleves.anonymats._print_layout_style')
<style>
    @page { size: A4 portrait; margin: 10mm; }
    .anonymat-print table{width:100%;border-collapse:collapse}.anonymat-print th,.anonymat-print td{border:1px solid #222;padding:7px}.anonymat-print th{background:#eee;text-align:center}
    .anonymat-context th{width:14%}.anonymat-context td{width:36%}
    @media print{.no-print,.navbar,.sidebar-collapse{display:none!important}.anonymat-print{font-size:11px}.anonymat-print table{page-break-inside:auto}.anonymat-print tr,.anonymat-sheet-header{page-break-inside:avoid}.anonymat-subject-sheet+.anonymat-subject-sheet{page-break-before:always}}
</style>
