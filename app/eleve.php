<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\eleve
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\discipline[] $disciplines
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\inscription[] $inscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\parents[] $parents
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\payement[] $payements
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string|null $prenom
 * @property string $date_naiss
 * @property string $lieu_naiss
 * @property string $sexe
 * @property string $nationalite
 * @property string $quartier
 * @property int $transport
 * @property int $parent_ens
 * @property string|null $lien_tuteur
 * @property string $matricule
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDateNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereLienTuteur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereLieuNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereMatricule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereNationalite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereParentEns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereTransport($value)
 */
class eleve extends Model {

    protected $table = 'eleve';

    public $fillable = ['nom', 'prenom', 'date_naiss','lieu_naiss','sexe','nationalite','quartier','transport','parent_ens','lien_tuteur','matricule'];

    public $timestamps=false;

    public function parents(){
        return $this->hasMany(parents::class,'id_eleve');
    }
    public function inscriptions(){
        return $this->hasMany(inscription::class,'id_eleve');
    }
    public function payements(){
        return $this->hasMany(payement::class,'id_eleve');
    }
    public function notes(){
        return $this->hasMany(note::class,'id_eleve');
    }
    public function disciplines(){
        return $this->hasMany(discipline::class,'id_eleve');
    }
}

