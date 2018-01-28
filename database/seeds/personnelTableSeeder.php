<?php
namespace database\seeds;
use gesecole\personnel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class personnelTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       personnel::insert(
            [
                'nom'=>$this->getNom().' '.$this->getPrenom(),
                'date_naiss'=>random_int(1955,1990).'-'.random_int(1,12).'-'.random_int(1,28),
                'lieu_naiss'=>$this->getQuartier(),
                'sexe'=>$this->makeSex(random_int(0,1)),
                'diplome'=>'Doctorat',
                'date_entree'=>random_int(1980,2016).'-'.random_int(1,12).'-'.random_int(1,28),
                'tel1'=>str_random(7),
                'tel2'=>str_random(7),
                'adresse'=>$this->getQuartier(),
                'email'=>$this->getPrenom().random_int(1,99).'@gmail.com',
        ]
        );
    }

    public function makeSex($n){
        if ($n==0)
            return 'M';
        else
            return 'F';
    }

    protected $noms=[
        'Afande',
        'Akombe',
        'Atangana',
        'Ambang',
        'Akwa',
        'Abega',
        'Asoka',
        'Angula',
        "Ango'o",
        "Alima",
        "Ayesa",
        "Bandza",
        "Babende",
        "Bolingo",
        "Boboto",
        "Bella",
        "Biko'o",
        "Bingono",
        "Boloko",
        "Bazenga",
        "Biyela",
        "Chinda",
        "Chedom",
        "Chemgne",
        "Chekem",
        "Douma",
        "Dikoume",
        "Dipoko",
        "Dipanda",
        "Dizangue",
        "Embolo",
        "Etoundi",
        "Esoka",
        "Essaba",
        "Eyenga",
        "Ekanga",
        "Essolo",
        "Fani",
        "Fokam",
        "Fokou",
        "Fonkem",
        "Kamgang",
        "Kamdem",
        "Kibînza", "Kina", "Kuundi", "Kumu", "Kidzuunu", "Kifuki",
        "Luumbu", "Lugu", "Lekunzu", "Lukuzi", "Lebeka", "Lubelo",
        "Malembe", "Manga", "Mongo", "Mimi", "Mambila", "Matobo", "Mungo",
        "Obalambo", "Obambè", "Okwangela", "Okapi", "Obito", "Oyono","Onyumbe", "Onana",
        "Sidiki", "Sukè", "Susungi", "Sipa", "Seenga", "Sînga", "Soompa",
        "Zola", "Zanga", "Zambo", "Zula", "Zipayo", "Zuma", "Zèmbla", "Zamengwé", "Zambi", "Zaabulu", "Zîngu", "Zînga", "Zulu",
    ];

    protected $prenoms=["Joel","Jordan","William","David","Ulrich","Kevin","Kevain","Laurence","Vannelle","Vanel","Joceline","Danielle","Bertin","Léontine","Valdès","Victoire","Patrick","Kellian","Styve","Josué","David","Christian","Channelle","Priscille","Chancelle","Vivianne","Vivien","Margerie","Ted","Stella"];
    protected $quartiers=["Messassi","Emana","Lonk kak","Warda","Ngoa-Ekelle","Pakita","Mini-ferme","Bastos","Mokolo","Cité-verte","Madagascar","Bonas","Ekounou","Ngousso","Etoudi","Santa-Barbara","Nyom"];

    public function getNom(){
        $size_nom=sizeof($this->noms)-1;
        $nom=$this->noms[random_int(0,$size_nom)].' '.$this->noms[random_int(0,$size_nom)];
        return $nom;
    }
    public function getPrenom(){
        $size_nom=sizeof($this->prenoms)-1;
        $nom=$this->prenoms[random_int(0,$size_nom)];
        return $nom;
    }
    public function getQuartier(){
        $size_nom=sizeof($this->quartiers)-1;
        $nom=$this->quartiers[random_int(0,$size_nom)];
        return $nom;
    }
}
