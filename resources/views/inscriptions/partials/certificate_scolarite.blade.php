<div class="certificate-page">
    @include('perso.azimut_header')

    <div class="certificate-reference">N &deg; {{ isset($inscription) && $inscription ? sprintf('%06d', $inscription->id) : '........' }}/DI/RAAC/SC</div>

    <div class="certificate-title">CERTIFICAT DE SCOLARITE</div>

    <div class="certificate-body">
        <p>
            Je, soussign&eacute;(e) M
            <span class="certificate-line">{{ isset($nom_directeur) && $nom_directeur ? $nom_directeur : '' }}</span>
        </p>
        <p><strong>Directeur de l'Institut Sup&eacute;rieur AZIMUT de Commerce, de Technologie et de la Sant&eacute; ;</strong></p>

        <p class="certificate-spaced">
            Atteste que l'&eacute;tudiant(e)
            <strong>{{ trim($eleve->nom.' '.$eleve->prenom) }}</strong>
        </p>
        <p>
            n&eacute;(e) le <strong>{{ $eleve->date_naiss }}</strong>
            &agrave; <strong>{{ $eleve->lieu_naiss }}</strong>,
            Matricule : <strong>{{ $eleve->matricule }}</strong>
        </p>
        <p>
            est un(e) &eacute;tudiant(e) r&eacute;gulier(&egrave;re) de l'Institut AZIMUT pour l'ann&eacute;e
        </p>
        <p>
            acad&eacute;mique en cours <strong>{{ session('year') }}</strong>.
        </p>
        <p>Fili&egrave;re: <strong>{{ $niveau ? $niveau->getNom() : '' }}</strong></p>
        <p>Sp&eacute;cialit&eacute; : <strong>{{ $classe ? $classe->getNom() : '' }}</strong></p>
        <p>Niveau : <strong>{{ $nniveau ? $nniveau->nom : '' }}</strong></p>
        <p>Cette attestation est d&eacute;livr&eacute;e pour servir et valoir ce que de droit. /-</p>

        <div class="certificate-date">Yaound&eacute;, le <span></span></div>
        <div class="certificate-signature">Le Directeur</div>
    </div>

    <div class="certificate-footer">
        Yaound&eacute; Cameroun, Ngousso face CMC Polyclinique - www.azimutsup.com<br>
        La charte d'un monde meilleur
    </div>
</div>
