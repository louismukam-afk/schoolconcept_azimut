<?php
namespace database\seeds;
use gesecole\matiere;
use gesecole\niveau;
use gesecole\NNiveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class matiereTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $n=sizeof($this->matieres);
        for ($i=0;$i<$n;$i++)
            matiere::insert(['nom'=>$this->matieres[$i],'nom_anglais'=>$this->matieres_a[$i]]);

    }


    protected $matieres=[
        'Algorithmique de base',
        'Droit civil',
        'Droit commercial',
        'Français',
        'Organisation des chantiers',
        'Physique',
        'Mécanique théorique',
        'Résistance des matériaux',
        'Expression écrite',
        'Technique d\'aménagement et d\'équipement',
        'Procédés généraux de construction',
        'Algèbre I',
        'Analyse I',
        'Route',
        'Formation bilingue',
        'Chimie',
        'Architecture',
        'Comptabilité analytique',
        'Informatique générale',
        'Fiscalité I',
    ];
    protected $matieres_a=[
        'Basic algorithm',
        'Civil right',
        'Commercial right',
        'French',
        'Construction organisation',
        'Physics',
        'Theoretical mecanics',
        'Materials',
        'Written expression',
        'Equipment technic',
        'General construction method',
        'Algebra I',
        'Analysis I',
        'Road',
        'Bilingual formation',
        'Chemistry',
        'Architecture',
        'Analytic accounting',
        'General computer',
        'Tax system I',
    ];
}

