<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace gesecole{
/**
 * gesecole\classe
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\classeMatiere[] $classe_matieres
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
 * @property-read \gesecole\fonction_personnel $scolarite
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\titulaire[] $titulaires
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $id_niveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereIdNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereNom($value)
 * @property int $capacite
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereCapacite($value)
 */
	class classe extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\classeMatiere
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_classe
 * @property int $id_matiere
 * @property int $coef
 * @property int|null $max
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereCoef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereMax($value)
 */
	class classeMatiere extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\cycle
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\niveau[] $niveaux
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\cycle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\cycle whereNom($value)
 */
	class cycle extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\decaissement
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property float $variation
 * @property string $motif
 * @property float $montant
 * @property float $total
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereMotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereVariation($value)
 */
	class decaissement extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\discipline
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property int $abscence
 * @property int $retard
 * @property int $avertissement
 * @property int $blame
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAbscence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAvertissement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereBlame($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereRetard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereSequence($value)
 */
	class discipline extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\dispense
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property int $id_matiere
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdPersonnel($value)
 */
	class dispense extends \Eloquent {}
}

namespace gesecole{
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
	class eleve extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\fonction
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\fonction_personnel[] $fonction_personnels
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction whereNom($value)
 */
	class fonction extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\fonction_personnel
 *
 * @mixin \Eloquent
 */
	class fonction_personnel extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\inscription
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $date_inscription
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property int $id_classe
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereDateInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdEleve($value)
 * @property int $montant
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereMontant($value)
 * @property-read \gesecole\classe $classe
 * @property-read \gesecole\eleve $eleve
 */
	class inscription extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\matiere
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\note[] $notes
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereNom($value)
 */
	class matiere extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\niveau
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\classe[] $classes
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereIdCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereNom($value)
 */
	class niveau extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\note
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_matiere
 * @property int $id_eleve
 * @property float $note
 * @property int $sequence
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\note whereSequence($value)
 */
	class note extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\parametres
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string $valeur
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereValeur($value)
 */
	class parametres extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\parents
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string $profession
 * @property string $ville
 * @property string $quartier
 * @property string $tel1
 * @property string|null $tel2
 * @property int $type
 * @property int $id_eleve
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereTel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereTel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereVille($value)
 */
	class parents extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\payement
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property string $date_payement
 * @property float $montant
 * @property string $motif
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereDatePayement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereMotif($value)
 * @property string $numero
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereNumero($value)
 */
	class payement extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\personnel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\discipline[] $disciplines
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\fonction_personnel[] $fonction_personnels
 * @property-read \gesecole\salaire $salaire
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string $date_naiss
 * @property string $lieu_naiss
 * @property string $diplome
 * @property string $sexe
 * @property string $date_entree
 * @property string $tel1
 * @property string $tel2
 * @property string $adresse
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereDateEntree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereDateNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereDiplome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereLieuNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereTel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereTel2($value)
 */
	class personnel extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\salaire
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property float $montant
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereMontant($value)
 */
	class salaire extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\scolarite
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_classe
 * @property float|null $tranche1
 * @property float|null $tranche2
 * @property float|null $tranche3
 * @property float|null $tranche4
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche4($value)
 */
	class scolarite extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\titulaire
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereIdPersonnel($value)
 */
	class titulaire extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\trimestre
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $seq1
 * @property int $seq2
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereSeq1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereSeq2($value)
 */
	class trimestre extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $role
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereRole($value)
 */
	class User extends \Eloquent {}
}

