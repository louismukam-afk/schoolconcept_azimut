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
 * gesecole\bulletin_genere
 *
 * @property int $id
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereIdClasse($value)
 * @mixin \Eloquent
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereSequence($value)
 */
	class bulletin_genere extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\classe
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\classeMatiere[] $classe_matieres
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
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
 * @property-read \gesecole\niveau $niveau
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\inscription[] $inscriptions
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereCode($value)
 * @property int $id_cycle
 * @property string $nom_anglais
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereNomAnglais($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereIdCycle($value)
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
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdCycle($value)
 * @property string|null $semestre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereSemestre($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereType($value)
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdNniveau($value)
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
 * @property string $date
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereDate($value)
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereAnneeScolaire($value)
 */
	class decaissement extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\discipline
 *
 * @property int $id
 * @property int $id_eleve
 * @property int $id_personnel
 * @property string $annee_scolaire
 * @property int $sequence
 * @property int $type
 * @property int $nb_heures
 * @property int $just
 * @property int $retard
 * @property int $nb_jours
 * @property int $exclus_def
 * @property string|null $description
 * @property string $ddate
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereDdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereExclusDef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereJust($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereNbHeures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereNbJours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereRetard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereType($value)
 */
	class discipline extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\discipline_personnel
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $type
 * @property string $date_d
 * @property int $nombre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereDateD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereType($value)
 * @mixin \Eloquent
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereAnneeScolaire($value)
 * @property string|null $heure_arrive
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline_personnel whereHeureArrive($value)
 */
	class discipline_personnel extends \Eloquent {}
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
 * @property int $jour
 * @property int $id_horaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdHoraire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereJour($value)
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdNniveau($value)
 * @property int $id_salle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdSalle($value)
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
 * @property string|null $email
 * @property string|null $tel
 * @property string|null $status_matrimonial
 * @property string|null $region_origine
 * @property string|null $departement_origine
 * @property string|null $diplome_preinscription
 * @property string|null $annee_obtention
 * @property string|null $pays_obtention
 * @property string|null $dernier_etablissement
 * @property string|null $annee_sortie
 * @property string|null $diplome_obtenu
 * @property string|null $premiere_langue
 * @property string|null $deuxieme_langue
 * @property string|null $autre_langue
 * @property string|null $profession
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereAnneeObtention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereAnneeSortie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereAutreLangue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDepartementOrigine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDernierEtablissement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDeuxiemeLangue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDiplomeObtenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereDiplomePreinscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve wherePaysObtention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve wherePremiereLangue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereRegionOrigine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereStatusMatrimonial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\eleve whereTel($value)
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
 * @property int $id
 * @property int $id_fonction
 * @property int $id_personnel
 * @property int $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereIdFonction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereIdPersonnel($value)
 */
	class fonction_personnel extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\groupeMatieres
 *
 * @property int $id
 * @property int $id_classe_matiere
 * @property int $id_matiere_mere
 * @property int $id_classe
 * @property int $semestre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdClasseMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdMatiereMere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereSemestre($value)
 * @mixin \Eloquent
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdNniveau($value)
 */
	class groupeMatieres extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\heuresPrevues
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $id_classe
 * @property int $id_nniveau
 * @property int $id_matiere
 * @property int $total
 * @property string $annee_scolaire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereIdNniveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heuresPrevues whereUpdatedAt($value)
 */
	class heuresPrevues extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\heure_realise
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $nombre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereNombre($value)
 * @mixin \Eloquent
 * @property string $date_heure_realisee
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereDateHeureRealisee($value)
 * @property float $taux
 * @property int $jour
 * @property string|null $intervalle
 * @property string $date_enregistrement
 * @property int $id_classe
 * @property int $id_nniveau
 * @property int $id_matiere
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereDateEnregistrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIdNniveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIntervalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereJour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereTaux($value)
 */
	class heure_realise extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\horaire
 *
 * @property int $id
 * @property string $intervalle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereIntervalle($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereType($value)
 * @property int $ordre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereOrdre($value)
 * @property float $nb_heures
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereNbHeures($value)
 */
	class horaire extends \Eloquent {}
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
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdNniveau($value)
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
 * @property string $nom_anglais
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereNomAnglais($value)
 * @property int $groupe
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereGroupe($value)
 */
	class matiere extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\moyenne_classe
 *
 * @property int $id
 * @property int $id_classe
 * @property int $sequence
 * @property string $annee_scolaire
 * @property float $moyenne
 * @property float $max
 * @property float $min
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereSequence($value)
 * @mixin \Eloquent
 */
	class moyenne_classe extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\moyenne_eleve
 *
 * @property int $id
 * @property int $id_eleve
 * @property float $moyenne
 * @property string $annee_scolaire
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereSequence($value)
 * @mixin \Eloquent
 * @property int $id_classe
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereIdClasse($value)
 */
	class moyenne_eleve extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\moyenne_matiere
 *
 * @property int $id
 * @property int $id_matiere
 * @property int $id_classe
 * @property float $max
 * @property float $min
 * @property float $moyenne
 * @property int $sequence
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereSequence($value)
 * @mixin \Eloquent
 */
	class moyenne_matiere extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereNom($value)
 * @property-read \gesecole\scolarite $scolarite
 * @property string $nom_anglais
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereNomAnglais($value)
 */
	class niveau extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\NNiveau
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereNom($value)
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereIdCycle($value)
 */
	class NNiveau extends \Eloquent {}
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
 * gesecole\payement_salaire
 *
 * @property int $id
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property string $date_payement
 * @property float $montant
 * @property string $motif
 * @property string $numero
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereNumero($value)
 * @mixin \Eloquent
 * @property int $id_personnel
 * @property int $id_user
 * @property string $date_p
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereDateP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereIdUser($value)
 * @property int $heure_realise
 * @property float $primes
 * @property float $acomptes
 * @property float $cnss
 * @property float $cas_sociales
 * @property float $assurances
 * @property float $autre_retenues
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereAcomptes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereAssurances($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereAutreRetenues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCasSociales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCnss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereHeureRealise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire wherePrimes($value)
 * @property float $taux
 * @property float $total
 * @property float $total_retenu
 * @property float $net_a_payer
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereNetAPayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereTaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereTotalRetenu($value)
 * @property int $mois
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereMois($value)
 * @property float $cnps
 * @property float $cas_sociaux
 * @property float $IRPP
 * @property float $CAC
 * @property float $CFC
 * @property float $CRTV
 * @property float $pension
 * @property string|null $periode
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCAC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCFC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCRTV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCasSociaux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereCnps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire whereIRPP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire wherePension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement_salaire wherePeriode($value)
 */
	class payement_salaire extends \Eloquent {}
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
 * @property string|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereEmail($value)
 * @property string|null $matricule
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereMatricule($value)
 * @property int|null $type
 * @property string|null $matieres
 * @property string|null $autre_information
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereAutreInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereMatieres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereType($value)
 * @property string $nationalite
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereNationalite($value)
 * @property string|null $num_contribuable
 * @property string|null $num_ss
 * @property string|null $categorie
 * @property string|null $echelon
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereEchelon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereNumContribuable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\personnel whereNumSs($value)
 */
	class personnel extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\Photo
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $eleve_id
 * @property string $annee_scolaire
 * @property string $url
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereEleveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereUrl($value)
 */
	class Photo extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\presence_e
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $id_dispense
 * @property string $date_p
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereDateP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereIdDispense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereIdPersonnel($value)
 * @mixin \Eloquent
 * @property int $jour
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\presence_e whereJour($value)
 */
	class presence_e extends \Eloquent {}
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
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereType($value)
 */
	class salaire extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\salle
 *
 * @property int $id
 * @property string $nom
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salle whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salle whereUpdatedAt($value)
 */
	class salle extends \Eloquent {}
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
 * @property int $id_niveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdNiveau($value)
 * @property float $total
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTotal($value)
 * @property int $tranche5
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche5($value)
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdCycle($value)
 * @property float|null $inscription
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereInscription($value)
 */
	class scolarite extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\taux
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $id_matiere
 * @property float $taux
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereTaux($value)
 * @mixin \Eloquent
 * @property int $id_classe
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\taux whereIdNniveau($value)
 */
	class taux extends \Eloquent {}
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
 * @property string $fullname
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereUpdatedAt($value)
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereActive($value)
 * @property int|null $id_personnel
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereIdPersonnel($value)
 */
	class User extends \Eloquent {}
}

namespace gesecole{
/**
 * gesecole\verrouNote
 *
 * @property int $id
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereSequence($value)
 * @mixin \Eloquent
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereAnneeScolaire($value)
 */
	class verrouNote extends \Eloquent {}
}

