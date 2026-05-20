<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EtatAbsenceExport implements FromArray, WithHeadings
{
protected $data;
protected $date_debut;
protected $date_fin;

public function __construct($data, $date_debut, $date_fin)
{
$this->data = $data;
$this->date_debut = $date_debut;
$this->date_fin = $date_fin;
}

public function headings(): array
{
return [
'N°',
'Nom & Prénom',
'Cycle',
'Filière',
'Niveau',
'Spécialité',
'Nombre total d\'heures d\'absence'
];
}

public function array(): array
{
$result = [];
$i = 1;

foreach ($this->data as $row) {
$result[] = [
$i++,
$row->nom . ' ' . $row->prenom,
$row->cycle,
$row->filiere,
$row->n_niveau,
$row->classe,
$row->total_absence . ' H'
];
}

return $result;
}
}
?>