--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.3
-- Dumped by pg_dump version 9.5.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bulletin_genere; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE bulletin_genere (
    id integer NOT NULL,
    id_classe integer NOT NULL,
    sequence integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE bulletin_genere OWNER TO schoolconcept;

--
-- Name: bulletin_genere_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE bulletin_genere_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE bulletin_genere_id_seq OWNER TO schoolconcept;

--
-- Name: bulletin_genere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE bulletin_genere_id_seq OWNED BY bulletin_genere.id;


--
-- Name: classe; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE classe (
    id integer NOT NULL,
    code character varying(255) NOT NULL,
    nom character varying(255) NOT NULL,
    nom_anglais character varying(255) NOT NULL,
    id_niveau integer NOT NULL,
    capacite integer NOT NULL
);


ALTER TABLE classe OWNER TO schoolconcept;

--
-- Name: classe_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE classe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE classe_id_seq OWNER TO schoolconcept;

--
-- Name: classe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE classe_id_seq OWNED BY classe.id;


--
-- Name: classe_matiere; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE classe_matiere (
    id integer NOT NULL,
    id_cycle integer NOT NULL,
    id_classe integer NOT NULL,
    id_matiere integer NOT NULL,
    coef integer NOT NULL,
    max integer NOT NULL,
    semestre integer DEFAULT 0 NOT NULL,
    type integer DEFAULT 0 NOT NULL,
    id_nniveau integer DEFAULT 0 NOT NULL,
    id_code character varying(255)
);


ALTER TABLE classe_matiere OWNER TO schoolconcept;

--
-- Name: classe_matiere_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE classe_matiere_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE classe_matiere_id_seq OWNER TO schoolconcept;

--
-- Name: classe_matiere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE classe_matiere_id_seq OWNED BY classe_matiere.id;


--
-- Name: codages; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE codages (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE codages OWNER TO schoolconcept;

--
-- Name: codages_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE codages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE codages_id_seq OWNER TO schoolconcept;

--
-- Name: codages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE codages_id_seq OWNED BY codages.id;


--
-- Name: cycle; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE cycle (
    id integer NOT NULL,
    nom character varying(255) NOT NULL
);


ALTER TABLE cycle OWNER TO schoolconcept;

--
-- Name: cycle_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE cycle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cycle_id_seq OWNER TO schoolconcept;

--
-- Name: cycle_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE cycle_id_seq OWNED BY cycle.id;


--
-- Name: decaissement; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE decaissement (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    motif character varying(2048) NOT NULL,
    montant double precision NOT NULL,
    date date NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE decaissement OWNER TO schoolconcept;

--
-- Name: decaissement_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE decaissement_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE decaissement_id_seq OWNER TO schoolconcept;

--
-- Name: decaissement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE decaissement_id_seq OWNED BY decaissement.id;


--
-- Name: discipline; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE discipline (
    id integer NOT NULL,
    id_eleve integer DEFAULT 0 NOT NULL,
    id_personnel integer DEFAULT 0 NOT NULL,
    annee_scolaire character varying(9) NOT NULL,
    sequence integer DEFAULT 0 NOT NULL,
    type integer NOT NULL,
    nb_heures integer DEFAULT 0 NOT NULL,
    just integer DEFAULT 0 NOT NULL,
    retard integer DEFAULT 0 NOT NULL,
    nb_jours integer DEFAULT 0 NOT NULL,
    exclus_def integer DEFAULT 0 NOT NULL,
    description character varying(255),
    ddate date NOT NULL
);


ALTER TABLE discipline OWNER TO schoolconcept;

--
-- Name: discipline_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE discipline_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE discipline_id_seq OWNER TO schoolconcept;

--
-- Name: discipline_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE discipline_id_seq OWNED BY discipline.id;


--
-- Name: discipline_personnel; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE discipline_personnel (
    id integer NOT NULL,
    heure_arrive character varying(50),
    id_personnel integer NOT NULL,
    type integer NOT NULL,
    date_d date NOT NULL,
    nombre integer DEFAULT 0 NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE discipline_personnel OWNER TO schoolconcept;

--
-- Name: discipline_personnel_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE discipline_personnel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE discipline_personnel_id_seq OWNER TO schoolconcept;

--
-- Name: discipline_personnel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE discipline_personnel_id_seq OWNED BY discipline_personnel.id;


--
-- Name: dispense; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE dispense (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    id_matiere integer NOT NULL,
    id_classe integer NOT NULL,
    jour integer NOT NULL,
    id_horaire integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL,
    id_nniveau integer DEFAULT 0 NOT NULL,
    id_salle integer DEFAULT 0 NOT NULL
);


ALTER TABLE dispense OWNER TO schoolconcept;

--
-- Name: dispense_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE dispense_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE dispense_id_seq OWNER TO schoolconcept;

--
-- Name: dispense_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE dispense_id_seq OWNED BY dispense.id;


--
-- Name: eleve; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE eleve (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255),
    date_naiss date NOT NULL,
    lieu_naiss character varying(255) NOT NULL,
    sexe character varying(1) NOT NULL,
    nationalite character varying(255) NOT NULL,
    quartier character varying(255) NOT NULL,
    parent_ens character varying(255),
    lien_tuteur character varying(255),
    matricule character varying(255),
    email character varying(255),
    tel character varying(255),
    status_matrimonial character varying(255),
    region_origine character varying(255),
    departement_origine character varying(255),
    diplome_preinscription character varying(255),
    annee_obtention character varying(255),
    pays_obtention character varying(255),
    dernier_etablissement character varying(255),
    annee_sortie character varying(255),
    diplome_obtenu character varying(255),
    premiere_langue character varying(255),
    deuxieme_langue character varying(255),
    autre_langue character varying(255),
    profession character varying(255)
);


ALTER TABLE eleve OWNER TO schoolconcept;

--
-- Name: eleve_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE eleve_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE eleve_id_seq OWNER TO schoolconcept;

--
-- Name: eleve_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE eleve_id_seq OWNED BY eleve.id;


--
-- Name: fonction; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE fonction (
    id integer NOT NULL,
    nom character varying(255) NOT NULL
);


ALTER TABLE fonction OWNER TO schoolconcept;

--
-- Name: fonction_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE fonction_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fonction_id_seq OWNER TO schoolconcept;

--
-- Name: fonction_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE fonction_id_seq OWNED BY fonction.id;


--
-- Name: fonction_personnel; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE fonction_personnel (
    id integer NOT NULL,
    id_fonction integer NOT NULL,
    id_personnel integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE fonction_personnel OWNER TO schoolconcept;

--
-- Name: fonction_personnel_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE fonction_personnel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fonction_personnel_id_seq OWNER TO schoolconcept;

--
-- Name: fonction_personnel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE fonction_personnel_id_seq OWNED BY fonction_personnel.id;


--
-- Name: groupe_matieres; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE groupe_matieres (
    id integer NOT NULL,
    id_classe_matiere integer NOT NULL,
    id_matiere_mere integer NOT NULL,
    id_classe integer NOT NULL,
    id_nniveau integer NOT NULL,
    semestre integer NOT NULL
);


ALTER TABLE groupe_matieres OWNER TO schoolconcept;

--
-- Name: groupe_matieres_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE groupe_matieres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE groupe_matieres_id_seq OWNER TO schoolconcept;

--
-- Name: groupe_matieres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE groupe_matieres_id_seq OWNED BY groupe_matieres.id;


--
-- Name: heure_realise; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE heure_realise (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    nombre integer DEFAULT 0 NOT NULL,
    date_heure_realisee date NOT NULL,
    taux double precision DEFAULT '0'::double precision NOT NULL,
    jour integer DEFAULT 0 NOT NULL,
    intervalle character varying(255),
    date_enregistrement date NOT NULL,
    id_classe integer DEFAULT 0 NOT NULL,
    id_nniveau integer DEFAULT 0 NOT NULL,
    id_matiere integer DEFAULT 0 NOT NULL,
    annee_scolaire character varying(255) NOT NULL
);


ALTER TABLE heure_realise OWNER TO schoolconcept;

--
-- Name: heure_realise_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE heure_realise_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE heure_realise_id_seq OWNER TO schoolconcept;

--
-- Name: heure_realise_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE heure_realise_id_seq OWNED BY heure_realise.id;


--
-- Name: heures_prevues; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE heures_prevues (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    id_classe integer NOT NULL,
    id_nniveau integer NOT NULL,
    id_matiere integer NOT NULL,
    total integer DEFAULT 0 NOT NULL,
    annee_scolaire character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE heures_prevues OWNER TO schoolconcept;

--
-- Name: heures_prevues_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE heures_prevues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE heures_prevues_id_seq OWNER TO schoolconcept;

--
-- Name: heures_prevues_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE heures_prevues_id_seq OWNED BY heures_prevues.id;


--
-- Name: horaire; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE horaire (
    id integer NOT NULL,
    intervalle character varying(11) NOT NULL,
    type integer DEFAULT 0 NOT NULL,
    nb_heures double precision DEFAULT '1'::double precision NOT NULL,
    ordre integer NOT NULL
);


ALTER TABLE horaire OWNER TO schoolconcept;

--
-- Name: horaire_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE horaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE horaire_id_seq OWNER TO schoolconcept;

--
-- Name: horaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE horaire_id_seq OWNED BY horaire.id;


--
-- Name: inscription; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE inscription (
    id integer NOT NULL,
    date_inscription date NOT NULL,
    id_eleve integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL,
    id_classe integer NOT NULL,
    id_cycle integer NOT NULL,
    montant double precision NOT NULL,
    id_nniveau integer DEFAULT 0 NOT NULL,
    mode_paye character varying(255),
    ref_versement character varying,
    lettre character varying(255)
);


ALTER TABLE inscription OWNER TO schoolconcept;

--
-- Name: inscription_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE inscription_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inscription_id_seq OWNER TO schoolconcept;

--
-- Name: inscription_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE inscription_id_seq OWNED BY inscription.id;


--
-- Name: matiere; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE matiere (
    id integer NOT NULL,
    groupe integer DEFAULT 0 NOT NULL,
    nom character varying(255) NOT NULL,
    nom_anglais character varying(255) NOT NULL
);


ALTER TABLE matiere OWNER TO schoolconcept;

--
-- Name: matiere_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE matiere_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE matiere_id_seq OWNER TO schoolconcept;

--
-- Name: matiere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE matiere_id_seq OWNED BY matiere.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE migrations (
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE migrations OWNER TO schoolconcept;

--
-- Name: moyenne_classe; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE moyenne_classe (
    id integer NOT NULL,
    id_classe integer NOT NULL,
    sequence integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL,
    moyenne double precision NOT NULL,
    max double precision NOT NULL,
    min double precision NOT NULL
);


ALTER TABLE moyenne_classe OWNER TO schoolconcept;

--
-- Name: moyenne_classe_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE moyenne_classe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE moyenne_classe_id_seq OWNER TO schoolconcept;

--
-- Name: moyenne_classe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE moyenne_classe_id_seq OWNED BY moyenne_classe.id;


--
-- Name: moyenne_eleve; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE moyenne_eleve (
    id integer NOT NULL,
    id_eleve integer NOT NULL,
    id_classe integer NOT NULL,
    moyenne double precision NOT NULL,
    annee_scolaire character varying(255) NOT NULL,
    sequence integer NOT NULL
);


ALTER TABLE moyenne_eleve OWNER TO schoolconcept;

--
-- Name: moyenne_eleve_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE moyenne_eleve_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE moyenne_eleve_id_seq OWNER TO schoolconcept;

--
-- Name: moyenne_eleve_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE moyenne_eleve_id_seq OWNED BY moyenne_eleve.id;


--
-- Name: moyenne_matiere; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE moyenne_matiere (
    id integer NOT NULL,
    id_matiere integer NOT NULL,
    id_classe integer NOT NULL,
    max double precision NOT NULL,
    min double precision NOT NULL,
    moyenne double precision NOT NULL,
    sequence integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE moyenne_matiere OWNER TO schoolconcept;

--
-- Name: moyenne_matiere_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE moyenne_matiere_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE moyenne_matiere_id_seq OWNER TO schoolconcept;

--
-- Name: moyenne_matiere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE moyenne_matiere_id_seq OWNED BY moyenne_matiere.id;


--
-- Name: n_niveaus; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE n_niveaus (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    id_cycle integer DEFAULT 0 NOT NULL
);


ALTER TABLE n_niveaus OWNER TO schoolconcept;

--
-- Name: n_niveaus_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE n_niveaus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE n_niveaus_id_seq OWNER TO schoolconcept;

--
-- Name: n_niveaus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE n_niveaus_id_seq OWNED BY n_niveaus.id;


--
-- Name: niveau; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE niveau (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    nom_anglais character varying(255) NOT NULL
);


ALTER TABLE niveau OWNER TO schoolconcept;

--
-- Name: niveau_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE niveau_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE niveau_id_seq OWNER TO schoolconcept;

--
-- Name: niveau_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE niveau_id_seq OWNED BY niveau.id;


--
-- Name: note; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE note (
    id integer NOT NULL,
    id_matiere integer NOT NULL,
    id_eleve integer NOT NULL,
    sequence integer NOT NULL,
    note double precision NOT NULL,
    annee_scolaire character varying(255) NOT NULL
);


ALTER TABLE note OWNER TO schoolconcept;

--
-- Name: note_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE note_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE note_id_seq OWNER TO schoolconcept;

--
-- Name: note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE note_id_seq OWNED BY note.id;


--
-- Name: parametres; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE parametres (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    valeur character varying(500)
);


ALTER TABLE parametres OWNER TO schoolconcept;

--
-- Name: parametres_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE parametres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE parametres_id_seq OWNER TO schoolconcept;

--
-- Name: parametres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE parametres_id_seq OWNED BY parametres.id;


--
-- Name: parent; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE parent (
    id integer NOT NULL,
    nom character varying(255),
    profession character varying(255),
    ville character varying(255),
    quartier character varying(255),
    tel1 character varying(255),
    tel2 character varying(255),
    type integer NOT NULL,
    id_eleve integer NOT NULL
);


ALTER TABLE parent OWNER TO schoolconcept;

--
-- Name: parent_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE parent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE parent_id_seq OWNER TO schoolconcept;

--
-- Name: parent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE parent_id_seq OWNED BY parent.id;


--
-- Name: payement; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE payement (
    id integer NOT NULL,
    id_eleve integer NOT NULL,
    annee_scolaire character varying(255) NOT NULL,
    date_payement date NOT NULL,
    montant double precision NOT NULL,
    motif character varying(1000) NOT NULL,
    numero character varying(20),
    mode_paye character varying(255),
    ref_versement character varying(255),
    lettre character varying(255)
);


ALTER TABLE payement OWNER TO schoolconcept;

--
-- Name: payement_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE payement_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payement_id_seq OWNER TO schoolconcept;

--
-- Name: payement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE payement_id_seq OWNED BY payement.id;


--
-- Name: payement_salaire; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE payement_salaire (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    id_user integer NOT NULL,
    montant double precision NOT NULL,
    date_p date NOT NULL,
    numero character varying(20),
    mois integer NOT NULL,
    heure_realise integer NOT NULL,
    primes double precision DEFAULT '0'::double precision NOT NULL,
    acomptes double precision DEFAULT '0'::double precision NOT NULL,
    cnss double precision DEFAULT '0'::double precision NOT NULL,
    cas_sociales double precision DEFAULT '0'::double precision NOT NULL,
    assurances double precision DEFAULT '0'::double precision NOT NULL,
    autre_retenues double precision DEFAULT '0'::double precision NOT NULL,
    taux double precision DEFAULT '0'::double precision NOT NULL,
    total double precision DEFAULT '0'::double precision NOT NULL,
    total_retenu double precision DEFAULT '0'::double precision NOT NULL,
    net_a_payer double precision DEFAULT '0'::double precision NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE payement_salaire OWNER TO schoolconcept;

--
-- Name: payement_salaire_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE payement_salaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payement_salaire_id_seq OWNER TO schoolconcept;

--
-- Name: payement_salaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE payement_salaire_id_seq OWNED BY payement_salaire.id;


--
-- Name: personnel; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE personnel (
    id integer NOT NULL,
    email character varying(255),
    nom character varying(255) NOT NULL,
    date_naiss date NOT NULL,
    lieu_naiss character varying(255) NOT NULL,
    nationalite character varying(255),
    diplome character varying(255) NOT NULL,
    sexe character varying(1) NOT NULL,
    date_entree date NOT NULL,
    tel1 character varying(255) NOT NULL,
    tel2 character varying(255),
    adresse character varying(255) NOT NULL,
    type integer DEFAULT 0 NOT NULL,
    matieres character varying(2000),
    autre_information character varying(2000),
    matricule character varying(255),
    num_contribuable character varying(255),
    num_ss character varying(255),
    categorie character varying(255),
    echelon character varying(255),
    mode_paye character varying(255),
    ref_versement character varying(255)
);


ALTER TABLE personnel OWNER TO schoolconcept;

--
-- Name: personnel_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE personnel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE personnel_id_seq OWNER TO schoolconcept;

--
-- Name: personnel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE personnel_id_seq OWNED BY personnel.id;


--
-- Name: photos; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE photos (
    id integer NOT NULL,
    eleve_id integer NOT NULL,
    annee_scolaire character varying(255) NOT NULL,
    url character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE photos OWNER TO schoolconcept;

--
-- Name: photos_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE photos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE photos_id_seq OWNER TO schoolconcept;

--
-- Name: photos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE photos_id_seq OWNED BY photos.id;


--
-- Name: salaire; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE salaire (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    type integer DEFAULT 0 NOT NULL,
    montant double precision DEFAULT '0'::double precision NOT NULL
);


ALTER TABLE salaire OWNER TO schoolconcept;

--
-- Name: salaire_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE salaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE salaire_id_seq OWNER TO schoolconcept;

--
-- Name: salaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE salaire_id_seq OWNED BY salaire.id;


--
-- Name: salles; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE salles (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE salles OWNER TO schoolconcept;

--
-- Name: salles_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE salles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE salles_id_seq OWNER TO schoolconcept;

--
-- Name: salles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE salles_id_seq OWNED BY salles.id;


--
-- Name: scolarite; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE scolarite (
    id integer NOT NULL,
    id_cycle integer NOT NULL,
    tranche1 double precision DEFAULT '0'::double precision NOT NULL,
    tranche2 double precision DEFAULT '0'::double precision NOT NULL,
    tranche3 double precision DEFAULT '0'::double precision NOT NULL,
    tranche4 double precision DEFAULT '0'::double precision NOT NULL,
    tranche5 double precision DEFAULT '0'::double precision NOT NULL,
    total double precision DEFAULT '0'::double precision NOT NULL,
    id_niveau integer NOT NULL,
    inscription character varying(255)
);


ALTER TABLE scolarite OWNER TO schoolconcept;

--
-- Name: scolarite_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE scolarite_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE scolarite_id_seq OWNER TO schoolconcept;

--
-- Name: scolarite_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE scolarite_id_seq OWNED BY scolarite.id;


--
-- Name: taux; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE taux (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    id_matiere integer DEFAULT 0 NOT NULL,
    id_classe integer DEFAULT 0 NOT NULL,
    id_nniveau integer DEFAULT 0 NOT NULL,
    taux double precision NOT NULL
);


ALTER TABLE taux OWNER TO schoolconcept;

--
-- Name: taux_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE taux_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE taux_id_seq OWNER TO schoolconcept;

--
-- Name: taux_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE taux_id_seq OWNED BY taux.id;


--
-- Name: titulaire; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE titulaire (
    id integer NOT NULL,
    id_personnel integer NOT NULL,
    id_classe integer NOT NULL,
    annee_scolaire character varying(9) NOT NULL
);


ALTER TABLE titulaire OWNER TO schoolconcept;

--
-- Name: titulaire_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE titulaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE titulaire_id_seq OWNER TO schoolconcept;

--
-- Name: titulaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE titulaire_id_seq OWNED BY titulaire.id;


--
-- Name: trimestre; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE trimestre (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    seq1 integer NOT NULL,
    seq2 integer NOT NULL
);


ALTER TABLE trimestre OWNER TO schoolconcept;

--
-- Name: trimestre_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE trimestre_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE trimestre_id_seq OWNER TO schoolconcept;

--
-- Name: trimestre_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE trimestre_id_seq OWNED BY trimestre.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    fullname character varying(255) NOT NULL,
    email character varying(255),
    password character varying(255) NOT NULL,
    remember_token character varying(255),
    role double precision DEFAULT '20'::double precision NOT NULL,
    active integer DEFAULT 0 NOT NULL,
    id_personnel integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE users OWNER TO schoolconcept;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO schoolconcept;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: verrou_note; Type: TABLE; Schema: public; Owner: schoolconcept
--

CREATE TABLE verrou_note (
    id integer NOT NULL,
    sequence integer NOT NULL,
    annee_scolaire character varying(255) NOT NULL
);


ALTER TABLE verrou_note OWNER TO schoolconcept;

--
-- Name: verrou_note_id_seq; Type: SEQUENCE; Schema: public; Owner: schoolconcept
--

CREATE SEQUENCE verrou_note_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE verrou_note_id_seq OWNER TO schoolconcept;

--
-- Name: verrou_note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: schoolconcept
--

ALTER SEQUENCE verrou_note_id_seq OWNED BY verrou_note.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY bulletin_genere ALTER COLUMN id SET DEFAULT nextval('bulletin_genere_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY classe ALTER COLUMN id SET DEFAULT nextval('classe_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY classe_matiere ALTER COLUMN id SET DEFAULT nextval('classe_matiere_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY codages ALTER COLUMN id SET DEFAULT nextval('codages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY cycle ALTER COLUMN id SET DEFAULT nextval('cycle_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY decaissement ALTER COLUMN id SET DEFAULT nextval('decaissement_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY discipline ALTER COLUMN id SET DEFAULT nextval('discipline_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY discipline_personnel ALTER COLUMN id SET DEFAULT nextval('discipline_personnel_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY dispense ALTER COLUMN id SET DEFAULT nextval('dispense_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY eleve ALTER COLUMN id SET DEFAULT nextval('eleve_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY fonction ALTER COLUMN id SET DEFAULT nextval('fonction_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY fonction_personnel ALTER COLUMN id SET DEFAULT nextval('fonction_personnel_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY groupe_matieres ALTER COLUMN id SET DEFAULT nextval('groupe_matieres_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY heure_realise ALTER COLUMN id SET DEFAULT nextval('heure_realise_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY heures_prevues ALTER COLUMN id SET DEFAULT nextval('heures_prevues_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY horaire ALTER COLUMN id SET DEFAULT nextval('horaire_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY inscription ALTER COLUMN id SET DEFAULT nextval('inscription_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY matiere ALTER COLUMN id SET DEFAULT nextval('matiere_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_classe ALTER COLUMN id SET DEFAULT nextval('moyenne_classe_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_eleve ALTER COLUMN id SET DEFAULT nextval('moyenne_eleve_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_matiere ALTER COLUMN id SET DEFAULT nextval('moyenne_matiere_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY n_niveaus ALTER COLUMN id SET DEFAULT nextval('n_niveaus_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY niveau ALTER COLUMN id SET DEFAULT nextval('niveau_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY note ALTER COLUMN id SET DEFAULT nextval('note_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY parametres ALTER COLUMN id SET DEFAULT nextval('parametres_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY parent ALTER COLUMN id SET DEFAULT nextval('parent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY payement ALTER COLUMN id SET DEFAULT nextval('payement_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY payement_salaire ALTER COLUMN id SET DEFAULT nextval('payement_salaire_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY personnel ALTER COLUMN id SET DEFAULT nextval('personnel_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY photos ALTER COLUMN id SET DEFAULT nextval('photos_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY salaire ALTER COLUMN id SET DEFAULT nextval('salaire_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY salles ALTER COLUMN id SET DEFAULT nextval('salles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY scolarite ALTER COLUMN id SET DEFAULT nextval('scolarite_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY taux ALTER COLUMN id SET DEFAULT nextval('taux_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY titulaire ALTER COLUMN id SET DEFAULT nextval('titulaire_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY trimestre ALTER COLUMN id SET DEFAULT nextval('trimestre_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY verrou_note ALTER COLUMN id SET DEFAULT nextval('verrou_note_id_seq'::regclass);


--
-- Data for Name: bulletin_genere; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY bulletin_genere (id, id_classe, sequence, annee_scolaire) FROM stdin;
\.


--
-- Name: bulletin_genere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('bulletin_genere_id_seq', 1, false);


--
-- Data for Name: classe; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY classe (id, code, nom, nom_anglais, id_niveau, capacite) FROM stdin;
8	GSI	GESTION DES SYSTEMES D'INFORMATIONS		11	30
26	FE	FISCALITE D'ENTREPRISE		11	30
1	GHM	Gestion ET Management Hotelier	ANGLAIS	1	200
2	CGE	COMPTABILITE ET GESTION DES ENTREPRISES		11	200
3	AM	ASSISTANCE MANAGER		11	200
6	J	journalisme		10	200
7	GU	GENIE CULILINAIRE		1	200
9	A	ASSURANCE		11	200
10	CMV	COMMERCE MARKETING VENTE		11	200
11	B	BANQUE		11	200
12	CO	COMMUNICATION ET ORGANISATION		10	200
13	PG	PUERICULTURE ET GERONTOLOGIE		9	200
14	B	BATIMENT		8	200
15	TB	TRAVAUX PUBLIC		8	200
16	MVA	MAINTENANCE ET APRES VENTE AUTOMOBILE		7	200
17	FMC	FABRICATION MECANIQUE ET CONSTRUCTION		7	200
18	FC	FROID ET CLIMATISATION		6	200
19	ELEC	ELECTRONIQUE		5	200
20	ELEC	ELECTROTECHNIQUE		5	200
21	MSE	MAINTENANCE DES SYSTEMES ELECTRONIQUES		5	200
22	EA	ENTREPRENEURIAT AGROPASTORAL		4	200
23	CA	CONSEILER AGROPASTORAL		4	200
24	CCA	COMPTABILITE ET CONTROLE AUDIT		11	200
25	BIF	BANQUE ET INSTITUTIONS FINANCIERES		11	200
29	MMO	MATKETING MANAGER OPERATIONNEL		11	200
27	MOCI	MANAGEMENT DES OPERATIONS DU COMMERCE INTERNATIONAL		1	200
28	MMRD	MANAGEMENT ET MARKETING DES RESEAUX DE DISTRIBUTION		11	200
30	GHR	GESTION DES RESOURCES HUMAINES		11	200
31	MEH	MANAGEMENT DES ENTREPRISES HOTELERES		1	200
32	MP	MARKETING PUBLICITE		10	200
33	CRP	COMMUNICATION ET RELATIONS PUBLIQUES		10	200
34	MET	MANAGEMENT DES ENTREPRISES TOURISTIQUES		1	200
35	GTN	GUIDE TOURISTIQUE NATIONAL		1	200
36	DAT	DEVELOPPEMENT ET AMENAGEMENT TOURISTIQUE		1	200
37	ITA	INGENIEURS DES TRAVAUX AGRICOLES		4	200
38	PV	PRODUCTION VEGETALES		4	200
39	PA	PRODUCTIONS ANIMALES		4	200
40	ES	ECONOMIE SOCIOLOGIES RURALES		4	200
41	GR	GENIE RURAL		4	200
42	GMP	GENIE MECANIQUE ET PRODUCTIQUE		7	200
43	GE	GENIE ELECTRIQUE		5	200
44	GIR	GENIE INFORMATIQUE ET RESEAUX		8	200
45	GC	GENIE CIVIL		8	200
46	TR	TELECOMMUNICATION ET RESEAUX		8	200
47	GB	GENIE BOIS		6	200
48	HR3	HOTELLERIE ET RESTAURATION		1	200
51	DR	DROIT		14	200
50	IIA	INFORMATIQUE INDUSTRIELLE ET AUTOMATISME		15	200
\.


--
-- Name: classe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('classe_id_seq', 51, true);


--
-- Data for Name: classe_matiere; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY classe_matiere (id, id_cycle, id_classe, id_matiere, coef, max, semestre, type, id_nniveau, id_code) FROM stdin;
39	1	2	524	2	2	1	0	5	\N
40	1	2	525	2	2	2	0	5	\N
49	1	2	540	3	3	1	2	5	\N
53	1	2	545	2	2	1	2	5	\N
54	1	2	547	2	2	2	2	5	\N
55	1	2	532	5	5	1	2	5	\N
56	1	2	535	4	4	2	2	5	\N
57	1	2	533	2	2	1	2	5	\N
58	1	2	534	3	3	2	2	5	\N
61	1	2	536	2	2	1	2	5	\N
62	1	2	537	2	2	2	2	5	\N
63	1	2	538	3	3	1	2	5	\N
64	1	2	539	2	2	2	2	5	\N
65	1	2	546	2	2	1	2	5	\N
66	1	2	548	2	2	2	2	5	\N
67	1	2	526	2	2	1	0	5	\N
68	1	2	527	2	2	2	0	5	\N
69	1	2	450	2	2	2	0	5	\N
70	1	2	517	3	3	1	0	5	\N
71	1	2	519	3	3	2	0	5	\N
74	1	8	193	4	4	1	0	5	\N
75	1	8	200	5	5	2	0	5	\N
73	1	8	441	4	4	2	0	5	\N
76	1	8	194	4	4	1	2	5	\N
77	1	8	195	5	5	1	2	5	\N
78	1	8	202	4	4	2	2	5	\N
79	1	8	234	2	2	1	2	5	\N
80	1	8	236	2	2	2	2	5	\N
81	1	8	196	4	4	1	2	5	\N
82	1	8	1461	6	6	2	2	5	\N
83	1	8	1462	4	4	2	2	5	\N
84	1	8	215	2	2	1	0	5	\N
85	1	8	217	3	3	2	0	5	\N
86	1	8	197	5	5	1	2	5	\N
87	1	8	204	4	4	2	2	5	\N
88	1	8	579	3	3	1	1	5	\N
89	1	8	609	3	3	2	1	5	\N
90	1	8	1371	3	3	1	0	5	\N
91	1	8	208	3	3	2	0	5	\N
92	1	8	526	2	2	1	0	5	\N
93	1	8	527	2	2	2	0	5	\N
94	1	8	1373	2	2	1	0	5	\N
95	1	8	216	2	2	2	0	5	\N
96	1	8	228	2	2	2	2	5	\N
97	1	8	223	1	100	1	0	5	\N
98	1	8	226	3	3	2	2	5	\N
99	1	8	222	2	2	1	2	5	\N
100	1	8	225	1	100	1	0	5	\N
101	1	8	1463	2	2	1	2	5	\N
102	1	8	227	2	2	1	2	5	\N
103	1	8	229	2	2	2	2	5	\N
104	1	8	912	1	1	2	2	5	\N
105	1	8	230	2	2	1	2	5	\N
106	1	8	232	2	2	2	2	5	\N
107	1	8	1465	2	2	1	2	5	\N
108	1	8	1466	2	2	2	2	5	\N
109	1	8	235	3	3	1	2	5	\N
110	1	8	237	2	2	2	2	5	\N
111	1	8	2	1	1	2	1	5	\N
112	1	8	238	1	1	1	1	5	\N
113	1	8	45	2	2	2	1	5	\N
114	1	8	764	2	2	1	1	5	\N
119	1	8	343	2	2	3	2	6	\N
120	1	8	345	2	2	3	2	6	\N
123	1	8	825	2	2	3	2	6	\N
124	1	8	835	2	2	4	2	6	\N
128	1	8	243	1	1	3	2	6	\N
129	1	8	329	2	2	4	2	6	\N
130	1	8	327	2	2	3	2	6	\N
131	1	8	332	2	2	4	2	6	\N
132	1	8	239	2	2	3	2	6	\N
133	1	8	241	2	2	4	2	6	\N
134	1	8	339	1	1	3	2	6	\N
135	1	8	342	1	1	4	2	6	\N
136	1	8	240	2	2	3	2	6	\N
137	1	8	242	2	2	4	2	6	\N
115	1	8	184	5	5	3	0	6	GSI241
118	1	8	189	4	4	3	2	6	GSI233
117	1	8	182	4	4	4	2	6	GSI243
127	1	8	1470	4	4	4	2	6	GSI244
122	1	8	180	4	4	4	2	6	GSI245
121	1	8	186	7	7	3	2	6	GSI236
42	1	2	199	4	4	2	0	5	CGE122
41	1	2	192	4	4	1	0	5	CGE112
43	1	2	499	7	7	1	2	5	CGE113
44	1	2	500	7	7	2	2	5	CGE123
45	1	2	501	5	5	1	2	5	CGE114
46	1	2	502	4	4	2	2	5	CGE124
47	1	2	504	4	4	2	0	5	CGE125
51	1	2	544	4	4	1	2	5	CGE116
52	1	2	508	4	4	1	2	5	CGE126
5	1	20	10	4	4	1	0	5	ELT111
6	1	20	17	4	4	2	0	5	ELT121
36	1	14	275	3	3	1	0	5	
35	1	14	11	5	5	1	0	5	BAT112
33	1	14	164	2	2	1	0	5	
34	1	14	162	4	4	1	0	5	BAT111
20	1	23	11	5	100	1	0	5	CAP111
18	1	23	52	4	100	1	0	5	CAP112
14	1	23	53	4	100	1	2	5	CAP113
21	1	23	54	4	100	1	2	5	CAP114
9	1	23	55	5	100	1	2	5	CAP115
19	1	23	65	6	100	2	2	5	CAP125
8	1	23	59	5	100	2	0	5	CAP121
17	1	23	66	3	100	2	2	5	CAP126
16	1	23	67	3	100	2	1	5	CAP127
22	1	23	60	5	5	2	0	5	CAP122
138	1	8	338	1	1	3	2	6	\N
139	1	8	341	1	1	4	2	6	\N
140	1	8	344	1	1	3	2	6	\N
141	1	8	346	2	2	3	2	6	\N
145	1	8	570	1	1	3	1	6	\N
146	1	8	493	1	1	3	1	6	\N
147	1	8	843	1	1	4	1	6	\N
148	1	8	569	2	2	4	1	6	\N
149	1	8	1433	1	1	3	1	6	\N
150	1	8	381	1	1	4	1	6	\N
151	1	8	1039	3	3	3	0	6	\N
152	1	8	1041	2	2	4	0	6	\N
153	1	8	211	2	2	3	0	6	\N
154	1	8	213	3	3	4	0	6	\N
72	1	8	434	5	100	1	0	5	\N
155	1	8	286	3	1001	5	0	5	AUT2556
156	1	8	190	4	4	3	0	6	GSI232
157	1	8	183	2	2	4	0	6	GSI242
158	1	8	218	2	2	3	0	6	
159	1	8	220	2	2	4	0	6	
160	1	8	393	2	2	3	0	6	
161	1	8	988	2	2	4	0	6	
116	1	8	191	5	5	4	0	6	GSI231
126	1	8	1467	3	3	3	2	6	GSI234
142	1	8	187	4	4	3	2	6	GSI235
162	1	8	842	6	6	3	2	6	GSI246
143	1	8	185	3	3	3	1	6	GSI237
144	1	8	692	3	3	4	1	6	GSI247
37	1	2	495	1	100	1	0	5	CGE111
163	1	2	732	2	2	1	0	5	
164	1	2	8	2	2	2	0	5	
165	1	2	580	5	5	2	0	5	GSI121
48	1	2	503	3	3	1	2	5	CGE115
166	1	2	912	1	1	2	2	5	
167	1	2	543	3	3	2	2	5	
168	1	2	579	3	3	1	1	5	CGE117
169	1	2	609	3	3	2	1	5	CGE127
170	1	2	765	1	1	2	1	5	
171	1	2	763	1	1	1	1	5	
172	1	2	116	2	2	2	1	5	
173	1	2	550	2	2	1	1	5	
174	1	2	184	5	5	4	0	6	CGE241
175	1	2	191	5	5	3	0	6	CGE231
176	1	2	511	4	4	3	0	6	CGE233
177	1	2	516	4	4	4	0	6	CGE243
179	1	2	1028	4	4	4	0	6	CGE242
180	1	2	1035	2	2	3	0	6	
181	1	2	1037	2	2	3	0	6	
182	1	2	509	3	3	3	2	6	CGE234
183	1	2	1050	3	3	4	2	6	CGE244
184	1	2	557	2	2	3	2	6	
185	1	2	560	2	2	4	2	6	
186	1	2	553	2	2	3	2	6	
187	1	2	554	2	2	4	2	6	
188	1	2	555	2	2	3	2	6	
189	1	2	556	2	2	4	2	6	
190	1	2	561	2	2	3	2	6	
191	1	2	562	2	2	4	2	6	
192	1	2	512	6	6	3	2	6	CGE235
193	1	2	563	1	1	3	2	6	
194	1	2	564	1	1	4	2	6	
195	1	2	513	5	5	3	0	6	CGE236
196	1	2	565	5	5	4	2	6	CGE245
197	1	2	825	2	2	3	2	6	
198	1	2	340	2	2	4	2	6	
199	1	2	568	3	3	4	2	6	
200	1	2	567	3	3	3	2	6	
201	1	2	34	6	6	4	2	6	CGE246
202	1	2	692	3	3	3	1	6	CGE237
203	1	2	700	3	3	4	1	6	CGE247
204	1	2	570	2	2	3	1	6	
205	1	2	493	1	1	3	1	6	
206	1	2	843	1	1	4	1	6	
207	1	2	394	1	1	4	1	6	
208	1	2	381	1	1	3	1	6	
209	1	2	1036	2	2	3	0	6	
210	1	2	985	2	2	4	0	6	
211	1	2	211	3	3	3	0	6	
212	1	2	213	3	3	4	0	6	
213	1	2	1039	2	2	3	0	6	
214	1	2	1041	2	2	3	0	6	
215	1	2	1472	4	4	3	0	6	CGE232
216	1	2	558	1	1	3	2	6	
217	1	2	559	1	1	4	2	6	
218	1	50	84	4	4	2	0	5	IIA121
219	1	50	77	4	4	1	0	5	IIA111
220	1	50	71	5	5	1	0	5	IIA112
221	1	50	100	3	3	1	0	5	
223	1	50	102	2	2	1	0	5	
224	1	50	101	2	2	2	0	5	
225	1	50	103	1	1	2	0	5	
226	1	50	104	2	2	2	0	5	
227	1	50	96	2	2	2	0	5	
228	1	50	1473	2	2	1	0	5	
229	1	50	97	2	2	2	0	5	
230	1	50	1474	2	2	1	0	5	
231	1	50	108	3	3	1	2	5	
232	1	50	107	2	2	1	2	5	
233	1	50	79	5	5	1	2	5	IIA113
234	1	50	72	5	5	2	2	5	IIA123
235	1	50	106	2	2	1	2	5	
236	1	50	1185	2	2	1	2	5	
237	1	50	80	5	5	1	2	5	IIA114
238	1	50	73	5	5	2	2	5	IIA124
239	1	50	105	3	3	1	2	5	
240	1	50	109	3	3	2	2	5	
241	1	50	111	3	3	1	2	5	
242	1	50	113	2	2	1	2	5	
243	1	50	74	4	4	1	2	5	II115
244	1	50	81	3	3	2	2	5	IIA125
245	1	50	75	4	4	1	2	5	IIA116
246	1	50	82	3	3	2	2	5	IIA126
248	1	50	83	3	3	2	1	5	IIA127
249	1	50	2	2	2	1	1	5	
251	1	50	318	1	1	1	1	5	
252	1	50	116	2	2	2	1	5	
253	1	50	78	5	5	2	0	5	IIA122
254	1	50	110	2	2	2	2	5	
255	1	50	1475	2	2	2	2	5	
247	1	50	76	3	3	1	0	5	IIA117
256	1	50	70	4	4	3	0	6	IIA231
257	1	50	90	4	4	4	0	6	IIA241
258	1	50	85	5	5	3	0	6	IIA232
259	1	50	91	5	5	4	0	6	IIA242
260	1	50	135	2	2	3	2	6	
261	1	50	130	2	2	3	2	6	
262	1	50	26	5	5	3	2	6	IIA234
263	1	50	126	2	2	3	2	6	
264	1	50	86	5	5	3	2	6	IIA233
265	1	50	94	5	5	4	2	6	IIA245
266	1	50	87	5	5	3	2	6	IIA235
267	1	50	134	3	3	3	2	6	
268	1	50	136	2	2	4	2	6	
269	1	50	88	3	3	3	2	6	IIA236
270	1	50	137	2	2	4	2	6	
271	1	50	132	1	1	4	2	6	
272	1	50	131	3	3	3	2	6	
273	1	50	133	2	2	4	2	6	
274	1	50	93	3	3	4	2	6	IIA244
275	1	50	127	3	3	3	2	6	
276	1	50	138	1	1	4	2	6	
277	1	50	124	3	3	4	0	6	
278	1	50	1473	2	2	3	0	6	
279	1	50	96	2	2	4	0	6	
280	1	50	122	3	3	3	2	6	
281	1	50	123	2	2	3	2	6	
282	1	50	121	2	2	3	0	6	
283	1	50	118	2	2	3	0	6	
284	1	50	92	4	4	4	2	6	IIA243
285	1	50	129	2	2	4	2	6	
286	1	50	128	2	2	4	2	6	
287	1	50	34	6	6	4	2	6	IIA246
288	1	50	320	2	2	3	1	6	
289	1	50	258	3	3	3	1	6	IIA237
290	1	50	381	1	1	3	1	6	
291	1	50	95	3	3	4	1	6	IIA247
292	1	50	1476	2	2	4	1	6	
293	1	16	1371	4	4	1	0	5	MAV111
295	1	16	301	5	5	1	0	5	MAV112
296	1	16	252	5	5	2	0	5	MAV122
297	1	16	275	3	3	1	0	5	
298	1	16	165	2	2	2	0	5	
294	1	16	208	4	4	2	0	5	MAV121
299	1	16	776	2	2	1	0	5	
300	1	16	782	3	3	2	0	5	
301	1	16	279	3	3	2	2	5	
302	1	16	254	6	6	2	2	5	MAV123
303	1	16	280	3	3	2	2	5	
304	1	16	1478	3	3	1	2	5	MAV113
305	1	16	247	3	3	1	2	5	MAV114
306	1	16	256	5	5	2	2	5	MAV125
307	1	16	248	6	6	1	2	5	MAV115
308	1	16	284	3	3	1	2	5	
309	1	16	255	4	4	2	2	5	MAV124
310	1	16	282	3	3	1	2	5	
311	1	16	249	6	6	1	2	5	MAV116
312	1	16	285	3	3	1	2	5	
313	1	16	286	3	3	1	2	5	
314	1	16	288	1	1	1	1	5	
315	1	16	156	3	3	1	1	5	MAV117
318	1	16	1433	1	1	2	1	5	
320	1	16	257	3	3	2	2	5	MAV126
323	1	16	150	5	5	3	0	6	MAV232
321	1	16	1090	4	4	3	0	6	MAV231
322	1	16	1096	4	4	4	0	6	MAV241
324	1	16	954	5	5	4	0	6	MAV242
325	1	16	293	2	2	3	0	6	
326	1	16	262	4	4	3	2	6	MAV233
327	1	16	269	4	4	4	2	6	MAV243
328	1	16	1477	3	3	3	2	6	MAV234
329	1	16	294	3	3	4	2	6	
330	1	16	270	5	5	4	2	6	MAV244
331	1	16	295	2	2	4	2	6	
332	1	16	264	4	4	3	2	6	MAV235
333	1	16	271	3	3	4	2	6	MAV245
334	1	16	296	4	4	3	2	6	
335	1	16	265	7	7	3	2	6	MAV236
336	1	16	1228	3	3	3	2	6	
337	1	16	34	6	6	4	2	6	MAV246
338	1	16	224	3	3	3	1	6	MAV237
339	1	16	266	3	3	3	1	6	
340	1	16	140	1	1	4	1	6	MAV247
341	1	16	178	1	1	4	1	6	
342	1	16	35	2	2	4	1	6	
343	1	16	174	3	3	3	0	6	
344	1	20	301	5	5	1	0	5	ELT112
345	1	20	18	5	5	2	0	5	ELT122
346	1	20	275	3	3	1	0	5	
347	1	20	165	2	2	2	0	5	
348	1	20	164	2	2	1	0	5	
349	1	20	782	3	3	2	0	5	
350	1	20	108	2	2	1	2	5	
351	1	20	13	5	5	1	2	5	ELT114
353	1	20	14	5	5	1	2	5	ELT115
354	1	20	1479	3	3	1	2	5	
355	1	20	1480	5	5	1	2	5	ELT113
356	1	20	1481	5	5	2	2	5	ELT123
357	1	20	1482	2	2	2	2	5	
358	1	20	20	5	5	2	2	5	ELT124
359	1	20	106	4	4	2	2	5	ELT125
360	1	20	80	2	2	1	2	5	
361	1	20	73	3	3	2	2	5	
362	1	20	1174	3	3	1	2	5	
363	1	20	1483	5	5	1	2	5	ELT115
364	1	20	15	3	3	1	2	5	ELT116
365	1	20	21	4	4	2	2	5	ELT126
366	1	20	288	1	1	1	1	5	
367	1	20	400	3	3	1	1	5	ELT117
368	1	20	337	2	2	1	1	5	
370	1	20	89	3	3	2	1	5	ELT127
373	1	20	1484	2	2	2	1	5	
374	1	20	1090	4	4	3	0	6	ELT231
375	1	20	1096	4	4	4	0	6	ELT241
376	1	20	301	5	5	3	0	6	ELT232
377	1	20	174	3	3	3	0	6	
378	1	20	382	2	2	3	0	6	
379	1	20	769	5	5	4	0	6	ELT242
380	1	20	24	2	5	3	2	6	ELT233
381	1	20	31	4	4	4	2	6	ELT243
382	1	20	1486	2	2	3	2	6	
383	1	20	1485	3	3	3	2	6	
384	1	20	25	5	5	3	2	6	ELT234
385	1	20	1490	2	2	3	2	6	
386	1	20	1489	2	2	4	2	6	
387	1	20	1491	3	3	3	2	6	
388	1	20	32	4	4	3	2	6	ELT244
389	1	20	26	4	4	3	2	6	ELT235
390	1	20	1492	4	4	3	2	6	
391	1	20	27	4	4	3	2	6	ELT236
392	1	20	33	4	4	4	2	6	ELT245
393	1	20	272	6	6	4	2	6	ELT246
394	1	20	766	3	3	3	1	6	ELT237
395	1	20	393	2	2	3	1	6	
396	1	20	394	1	1	3	1	6	
397	1	20	35	2	2	4	1	6	ELT247
398	1	20	767	2	2	4	1	6	
399	1	20	1488	2	2	4	2	6	
400	1	14	680	4	4	2	0	5	BAT121
401	1	14	252	2	2	2	0	5	BAT122
402	1	14	372	2	2	2	0	5	
403	1	14	783	3	3	2	0	5	
406	1	14	374	2	2	2	2	5	
407	1	14	681	5	5	2	2	5	BAT123
408	1	14	373	3	3	2	2	5	
409	1	14	356	3	3	1	2	5	BAT114
410	1	14	801	3	3	2	2	5	BAT124
411	1	14	806	4	4	2	2	5	BAT125
412	1	14	375	4	4	1	2	5	
413	1	14	358	8	8	1	2	5	BAT116
414	1	14	376	4	4	1	2	5	
415	1	14	379	3	3	2	2	5	
416	1	14	362	6	6	2	2	5	BAT126
417	1	14	683	3	3	2	2	5	
418	1	14	58	3	3	1	1	5	BAT117
419	1	14	89	3	3	2	1	5	BAT127
420	1	14	1169	2	2	2	1	5	
422	1	14	1090	4	4	3	0	6	BAT231
423	1	14	1096	4	4	4	0	6	BAT241
424	1	14	301	5	5	3	0	6	BAT232
425	1	14	292	3	3	3	0	6	
426	1	14	1168	2	2	3	0	6	
427	1	14	769	5	5	4	0	6	BAT242
428	1	14	383	2	2	3	2	6	
429	1	14	363	5	5	3	2	6	BAT233
430	1	14	384	3	3	3	2	6	
431	1	14	721	5	5	4	2	6	BAT243
432	1	14	385	2	2	4	2	6	
433	1	14	387	3	3	4	2	6	
434	1	14	379	3	3	3	2	6	
435	1	14	364	3	3	3	2	6	BAT234
436	1	14	369	3	3	4	2	6	BAT244
437	1	14	365	7	7	3	2	6	BAT235
438	1	14	841	3	3	3	2	6	
439	1	14	390	4	4	3	2	6	
440	1	14	392	2	2	4	2	6	
441	1	14	370	4	4	4	2	6	BAT245
442	1	14	845	2	2	4	2	6	
443	1	14	366	3	3	3	2	6	BAT236
444	1	14	34	6	6	4	2	6	BAT246
445	1	14	393	2	2	3	1	6	
446	1	14	766	3	3	3	1	6	BAT237
447	1	14	394	1	1	3	1	6	
449	1	14	767	2	2	4	1	6	
448	1	14	35	2	2	1	1	6	BAT247
450	1	15	1371	4	4	1	0	5	TPU111
451	1	15	208	4	4	2	0	5	TPU121
452	1	15	161	5	5	1	0	5	TPU112
453	1	15	275	3	3	1	0	5	
455	1	15	164	2	2	1	0	5	
456	1	15	18	5	5	2	0	5	TPU122
457	1	15	277	2	2	2	0	5	
458	1	15	783	3	3	2	0	5	
460	1	15	359	3	3	2	2	5	
461	1	15	681	5	5	2	2	5	TPU123
459	1	15	675	3	3	1	2	5	TPU113
462	1	15	411	2	2	2	2	5	
463	1	15	798	4	4	1	2	5	
464	1	15	676	8	8	1	2	5	TPU114
465	1	15	357	4	4	1	2	5	
466	1	15	360	3	3	2	2	5	
467	1	15	682	7	7	2	2	5	TPU124
468	1	15	806	4	4	2	2	5	
469	1	15	398	3	3	1	2	5	TPU115
470	1	15	683	3	3	2	2	5	TPU125
471	1	15	399	4	4	1	2	5	TPU116
472	1	15	684	3	3	2	2	5	TPU126
473	1	15	288	1	1	1	1	5	
474	1	15	401	3	3	1	1	5	TPU117
475	1	15	337	2	2	1	1	5	
476	1	15	258	3	3	2	1	5	TPU127
477	1	15	1169	2	2	2	1	5	
478	1	15	381	1	1	2	1	5	
479	1	15	1090	4	4	3	0	6	TPU231
480	1	15	1096	4	4	4	0	6	TPU241
481	1	15	382	2	2	3	0	6	
482	1	15	260	5	5	3	0	6	TPU232
483	1	15	174	3	3	3	0	6	
484	1	15	769	5	5	4	0	6	TPU242
485	1	15	824	2	2	3	2	6	
486	1	15	759	5	5	3	2	6	TPU233
487	1	15	384	3	3	3	2	6	
488	1	15	721	4	4	4	2	6	TPU243
489	1	15	385	2	2	4	2	6	
490	1	15	996	2	2	4	2	6	
491	1	15	839	3	3	3	2	6	
492	1	15	406	6	6	3	2	6	TPU234
493	1	15	841	3	3	4	2	6	
494	1	15	369	3	3	4	2	6	TPU244
495	1	15	687	4	4	3	2	6	TPU235
496	1	15	417	2	2	4	2	6	
497	1	15	1496	4	4	4	2	6	TPU245
498	1	15	845	2	2	4	2	6	
499	1	15	688	3	3	3	2	6	TPU236
500	1	15	333	6	6	4	2	6	TPU246
501	1	15	266	3	3	3	1	6	
502	1	15	912	3	3	3	1	6	TPU237
503	1	15	274	3	3	4	1	6	TPU247
504	1	15	1030	1	1	4	1	6	
505	1	15	35	2	2	4	1	6	
507	1	1	1300	4	4	1	0	5	GEH112
506	1	1	1299	5	5	1	0	5	GEH111
508	1	1	1304	4	4	1	2	5	GEH116
509	1	1	1301	5	5	1	2	5	GEH113
510	1	1	1302	5	5	1	2	5	GEH114
511	1	1	1303	4	4	1	2	5	GRH115
512	1	1	1306	5	5	2	0	5	GEH121
513	1	1	65	4	4	2	0	5	GEH122
514	1	1	1307	5	5	2	2	5	GEH123
515	1	1	1308	5	5	2	2	5	GEH124
516	1	1	1497	3	3	1	1	5	GEH117
517	1	1	1310	4	4	2	2	5	GEH126
518	1	1	1311	3	3	2	1	5	GEH127
519	1	1	1339	1	1	1	2	5	
520	1	1	1338	1	1	1	2	5	
521	1	1	1368	1	1	1	2	5	
522	1	1	1336	1	1	1	2	5	
523	1	1	1433	1	1	1	1	5	
524	1	1	318	1	1	1	1	5	
525	1	1	1333	1	1	1	1	5	
527	1	1	1371	1	1	2	2	5	
528	1	1	735	1	1	2	2	5	
529	1	1	449	2	2	2	2	5	
530	1	1	1330	2	2	2	2	5	
531	1	1	1329	2	2	2	2	5	
532	1	1	763	1	1	2	2	5	
533	1	1	1328	1	1	2	1	5	
534	1	1	1327	1	1	2	1	5	
535	1	1	1498	4	4	2	2	5	GEH125
536	1	1	1312	4	4	1	0	6	GEH231
537	1	1	1351	5	5	1	0	6	GEH232
538	1	1	1350	5	5	3	2	6	GEH233
539	1	1	1302	5	5	3	2	6	GEH234
540	1	1	1304	4	4	3	2	6	GEH236
541	1	1	1499	4	4	3	2	6	GEH235
542	1	1	1344	5	5	4	0	6	GEH242
543	1	1	1501	4	4	4	0	6	GEH241
544	1	1	1500	3	3	3	1	6	GEH237
545	1	1	1321	5	5	4	2	6	GEH243
546	1	1	1322	3	3	4	2	6	GEH244
547	1	1	1323	4	4	4	2	6	GEH245
548	1	1	34	6	6	4	2	6	GEH246
549	1	1	782	1	1	3	1	6	
550	1	1	1502	3	3	4	2	6	GEH247
551	1	1	912	1	1	4	1	6	
552	1	1	2	1	1	4	1	6	
553	1	1	1503	1	1	4	1	6	
554	1	1	913	1	1	3	1	6	
555	1	1	381	1	1	3	1	6	
556	1	12	1388	5	5	2	0	5	COM121
557	1	12	1504	4	4	1	0	5	COM111
558	1	12	1507	5	5	1	0	5	COM112
559	1	12	1448	2	2	1	2	5	
560	1	12	1506	5	5	1	2	5	COM113
561	1	12	1442	3	3	1	2	5	
562	1	12	1447	2	2	1	2	5	
563	1	12	121	2	2	1	2	5	
564	1	12	1508	4	4	1	2	5	COM114
565	1	12	1446	3	3	1	2	5	
566	1	12	1406	1	1	1	2	5	
567	1	12	1509	1	1	1	2	5	COM115
568	1	12	67	1	1	1	2	5	
569	1	12	1510	5	5	1	2	5	COM116
570	1	12	1511	4	4	1	2	5	
571	1	12	336	1	1	1	1	5	
572	1	12	1513	1	1	1	1	5	
573	1	12	1512	3	3	1	1	5	COM117
574	1	12	1514	1	1	1	1	5	
575	1	12	1376	4	4	2	0	5	COM122
576	1	12	1441	2	2	2	2	5	
577	1	12	1415	2	2	2	2	5	
578	1	12	1394	4	4	2	2	5	COM125
579	1	12	1416	2	2	2	2	5	
581	1	12	1516	5	5	2	2	5	COM124
582	1	12	1428	5	5	2	2	5	COM126
583	1	12	1399	3	3	2	1	5	COM127
584	1	12	764	1	1	2	1	5	
585	1	12	763	1	1	2	1	5	
586	1	12	1518	1	1	2	1	5	
587	1	12	1459	4	4	3	0	6	COM231
588	1	12	1439	2	2	3	0	6	
589	1	12	1440	2	2	3	0	6	
590	1	12	1458	4	4	3	0	6	COM232
591	1	12	1437	2	2	3	2	6	
592	1	12	1457	5	5	3	2	6	COM233
593	1	12	1438	3	3	3	2	6	
594	1	12	1429	1	1	4	2	6	
595	1	12	1451	4	4	4	2	6	COM243
596	1	12	1407	3	3	4	2	6	
597	1	12	1436	2	2	3	2	6	
598	1	12	1456	5	5	3	2	6	COM234
599	1	12	1435	3	3	3	2	6	
600	1	12	1413	2	2	3	2	6	
601	1	12	1455	4	4	3	2	6	COM235
602	1	12	1432	2	2	3	2	6	
603	1	12	1431	2	2	3	2	6	
604	1	12	1454	4	4	3	2	6	COM236
605	1	12	1423	2	2	3	2	6	
606	1	12	1400	3	3	3	1	6	COM237
607	1	12	476	1	1	3	1	6	
608	1	12	1433	1	1	3	1	6	
609	1	12	1373	1	1	3	1	6	
610	1	12	1382	4	4	4	0	6	COM241
611	1	12	1383	5	5	4	0	6	COM242
612	1	12	1395	4	4	4	2	6	COM244
613	1	12	1326	2	2	4	2	6	
614	1	12	1418	1	1	4	2	6	
615	1	12	1417	1	1	4	2	6	
616	1	12	1379	2	2	4	2	6	
617	1	12	1450	4	4	4	2	6	COM245
618	1	12	1428	2	2	4	2	6	
619	1	12	34	6	6	4	2	6	COM246
620	1	12	1449	3	3	4	1	6	COM247
621	1	12	2	1	1	4	1	6	
622	1	12	1520	1	1	4	1	6	
623	1	12	394	1	1	4	1	6	
624	1	23	56	5	5	1	2	5	CAP116
625	1	23	1521	3	3	1	1	5	CAP117
626	1	23	61	4	4	2	2	5	CAP123
627	1	23	449	2	2	2	0	5	
628	1	23	118	3	3	2	0	5	
629	1	23	68	5	5	2	2	5	CAP124
630	1	23	62	2	2	2	2	5	
631	1	23	63	2	2	2	2	5	
632	1	23	64	1	1	2	2	5	
633	1	23	291	1	1	1	1	5	
634	1	23	337	2	2	1	1	5	
635	1	23	38	6	6	3	0	6	CAP231
637	1	23	1522	4	4	4	0	6	CAP241
636	1	23	39	3	3	3	0	6	CAP232
638	1	23	325	3	3	4	0	6	CAP242
639	1	23	40	3	3	3	2	6	
640	1	23	1523	5	5	3	2	6	CAP233
641	1	23	41	2	2	3	2	6	
642	1	23	48	4	4	4	2	6	CAP243
643	1	23	42	5	5	3	2	6	CAP234
644	1	23	328	4	4	4	2	6	CAP244
645	1	23	43	5	5	3	2	6	CAP235
646	1	23	331	4	4	4	2	6	CAP245
647	1	23	333	6	6	4	2	6	CAP246
648	1	23	1524	3	3	3	2	6	CAP236
649	1	23	45	4	4	3	1	6	CAP237
650	1	23	35	2	2	3	1	6	
651	1	23	50	2	2	4	1	6	
652	1	23	1525	2	2	3	1	6	
653	1	23	1526	3	3	4	1	6	CAP247
654	1	23	1433	1	1	4	1	6	
655	1	22	301	5	5	1	0	5	EAP111
656	1	22	275	3	3	1	0	5	
657	1	22	164	2	2	1	0	5	
658	1	22	309	5	5	2	0	5	EAP121
659	1	22	52	4	4	1	0	5	EAP112
660	1	22	121	2	2	1	0	5	
661	1	22	60	5	5	2	0	5	EAP122
662	1	22	118	3	3	2	0	5	
663	1	22	54	4	4	3	2	5	EAP114
664	1	22	312	5	5	3	2	5	EAP124
665	1	22	1527	4	4	3	2	5	EAP113
666	1	22	61	4	4	2	2	5	EAP123
667	1	22	55	5	5	1	2	5	EAP115
668	1	22	65	5	5	2	2	5	EAP125
669	1	22	56	5	5	1	2	5	EAP116
670	1	22	314	3	3	2	2	5	EAP126
671	1	22	571	1	1	1	1	5	
672	1	22	1521	3	3	1	1	5	EAP117
673	1	22	58	2	2	1	1	5	
674	1	22	1528	3	3	1	2	5	
675	1	22	67	3	3	2	1	5	EAP127
676	1	22	38	6	6	3	0	6	EAP231
677	1	22	39	3	3	3	0	6	EAP232
678	1	22	1433	5	5	3	2	6	EAP233
679	1	22	42	5	5	3	2	6	EAP234
680	1	22	43	5	5	3	2	6	EAP235
681	1	22	1524	3	3	3	2	6	
682	1	22	326	4	4	4	2	6	EAP243
683	1	22	328	4	4	4	2	6	EAP244
684	1	22	331	4	4	4	2	6	EAP245
685	1	22	333	6	6	4	2	6	EAP246
686	1	22	35	2	2	3	1	6	
687	1	22	45	4	4	3	1	6	EAP237
688	1	22	1525	2	2	3	1	6	
689	1	22	50	3	3	4	1	6	EAP247
690	1	22	1529	3	3	3	2	6	EAP236
691	1	22	325	3	3	4	0	6	EAP242
693	1	15	1531	2	2	1	0	5	
694	1	15	1532	2	2	2	0	5	
695	1	15	1530	2	2	1	0	5	
696	1	15	1534	2	2	2	0	5	
697	1	15	570	1	1	1	1	5	
699	1	15	354	2	2	1	2	5	
700	1	15	373	2	2	2	2	5	
698	1	15	1535	2	2	1	1	5	
701	1	15	1536	2	2	2	1	5	
702	1	14	1531	2	2	1	0	5	
703	1	14	1532	2	2	2	0	5	
704	1	14	1530	2	2	1	0	5	
705	1	14	1534	2	2	2	0	5	
707	1	14	570	1	100	1	1	5	
708	1	14	1535	1	1	0	1	5	
709	1	14	800	2	2	0	2	5	
710	1	14	413	2	2	1	2	5	
713	1	14	399	2	2	1	2	5	
714	1	14	549	1	1	1	1	5	
715	1	14	675	2	2	1	2	5	
711	1	14	676	4	4	1	2	5	BAT115
717	1	14	1537	3	3	1	2	5	BAT113
718	1	16	1531	2	2	1	0	5	
719	1	16	1532	2	2	2	0	5	
720	1	16	1530	2	2	1	0	5	
721	1	16	1534	2	2	2	0	5	
722	1	16	570	1	1	1	1	5	
723	1	16	1535	1	1	1	1	5	
724	1	16	2	1	1	1	1	5	
725	1	16	1539	3	3	1	1	5	MAV127
726	1	50	570	1	1	1	1	5	
727	1	50	1535	1	1	1	1	5	
728	1	50	549	1	1	1	1	5	
729	1	20	1531	2	2	1	0	5	
730	1	20	1532	2	2	2	0	5	
731	1	20	1530	2	2	1	0	5	
732	1	20	1534	2	2	2	0	5	
733	1	20	570	1	1	1	0	5	
734	1	20	1535	1	1	1	0	5	
741	1	48	1540	1	100	1	0	16	
742	1	48	1301	1	100	1	2	16	
743	1	48	552	1	100	2	1	16	
744	1	48	764	1	100	1	1	16	
745	1	48	1544	1	100	2	0	16	
746	1	48	1543	1	100	1	0	16	
747	1	48	1547	1	100	1	2	16	
751	1	48	1550	1	100	1	0	16	
752	1	48	1542	1	100	2	2	16	
753	1	48	765	1	100	2	1	16	
754	1	48	549	1	100	1	1	16	
758	1	48	1326	1	100	1	0	16	
756	1	48	740	1	100	1	0	16	
740	1	48	1312	1	100	1	2	16	HR116
749	1	48	1329	1	100	2	2	16	HR118
750	1	48	1548	1	100	1	2	16	HR119
755	1	48	1549	1	100	1	0	16	HR121
761	1	48	838	1	100	2	1	16	
762	1	48	394	1	100	1	1	16	
765	1	48	501	1	100	2	1	16	
766	1	48	1535	1	100	2	1	16	
767	1	48	570	1	100	2	1	16	
768	1	48	58	1	100	1	1	16	HR117
769	1	48	1536	1	100	2	1	16	HR127
770	1	48	1555	1	100	1	0	16	HR111
771	1	48	1554	1	100	1	0	16	HR112
764	1	48	1553	1	100	1	0	16	
763	1	48	1552	1	100	1	0	16	
772	1	48	1558	1	100	1	2	16	HR113
774	1	48	1556	1	100	1	0	16	HR115
773	1	48	1557	1	100	2	2	16	HR114
760	1	48	1551	1	100	1	1	16	
775	1	48	178	1	100	2	1	16	HR115
759	1	48	1545	1	100	1	2	16	HR120
776	1	12	570	1	1	1	1	5	
777	1	12	2	1	1	1	1	5	
778	1	12	1535	1	1	1	1	5	
779	1	12	499	1	1	1	1	5	
780	1	12	1433	1	1	1	1	5	
\.


--
-- Name: classe_matiere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('classe_matiere_id_seq', 780, true);


--
-- Data for Name: codages; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY codages (id, nom, created_at, updated_at) FROM stdin;
3	GSI112	\N	\N
4	GSI113	\N	\N
5	GSI114	\N	\N
6	GSI111	\N	\N
7	CGE111	\N	\N
8	CGE112	\N	\N
9	CGE113	\N	\N
10	CGE113	\N	\N
12	ELT111	\N	\N
13	ELT112	\N	\N
14	ELT113	\N	\N
15	ELT114	\N	\N
16	ELT115	\N	\N
17	ELT116	\N	\N
18	ELT117	\N	\N
19	ELT121	\N	\N
20	ELT122	\N	\N
21	ELT123	\N	\N
22	ELT124	\N	\N
23	ELT125	\N	\N
24	ELT126	\N	\N
25	ELT127	\N	\N
26	ELT231	\N	\N
27	ELT232	\N	\N
28	ELT233	\N	\N
29	ELT234	\N	\N
30	ELT235	\N	\N
31	ELT236	\N	\N
32	ELT237	\N	\N
33	ELT241	\N	\N
34	ELT242	\N	\N
35	ELT243	\N	\N
36	ELT244	\N	\N
37	ELT245	\N	\N
38	ELT246	\N	\N
39	ELT247	\N	\N
40	CAP 121	\N	\N
41	CAP111	\N	\N
42	CAP112	\N	\N
43	CAP113	\N	\N
44	CAP114	\N	\N
45	CAP115	\N	\N
46	CAP116	\N	\N
47	CAP117	\N	\N
48	CAP122	\N	\N
49	CAP123	\N	\N
50	CAP124	\N	\N
51	CAP125	\N	\N
52	CAP126	\N	\N
53	CAP127	\N	\N
54	CAP231	\N	\N
55	CAP232	\N	\N
56	CAP233	\N	\N
57	CAP233	\N	\N
58	CAP234	\N	\N
59	CAP235	\N	\N
60	CAP236	\N	\N
61	CAP237	\N	\N
62	CAP241	\N	\N
63	CAP242	\N	\N
64	CAP243	\N	\N
65	CAP243	\N	\N
66	CAP244	\N	\N
67	CAP245	\N	\N
68	CAP246	\N	\N
69	CAP247	\N	\N
70	IIA111	\N	\N
71	IIA112	\N	\N
72	IIA113	\N	\N
73	IIA114	\N	\N
74	IIA115	\N	\N
75	IIA116	\N	\N
76	IIA117	\N	\N
77	IIA121	\N	\N
78	IIA122	\N	\N
79	IIA123	\N	\N
80	IIA124	\N	\N
81	IIA125	\N	\N
82	IIA126	\N	\N
83	IIA127	\N	\N
84	IIA231	\N	\N
85	IIA232	\N	\N
86	IIA233	\N	\N
87	IIA234	\N	\N
88	IIA235	\N	\N
89	IIA236	\N	\N
90	IIA237	\N	\N
91	IIA241	\N	\N
92	IIA242	\N	\N
93	IIA243	\N	\N
94	IIA244	\N	\N
95	IIA245	\N	\N
96	IIA246	\N	\N
97	IIA247	\N	\N
98	FCL111	\N	\N
99	FCL112	\N	\N
100	FCL113	\N	\N
101	FCL114	\N	\N
102	FCL115	\N	\N
103	FCL116	\N	\N
104	FCL117	\N	\N
105	FCL121	\N	\N
106	FCL122	\N	\N
107	FCL123	\N	\N
108	FCL124	\N	\N
109	FCL125	\N	\N
110	FCL126	\N	\N
111	FCL127	\N	\N
112	FCL231	\N	\N
113	FCL232	\N	\N
114	FCL233	\N	\N
115	FCL234	\N	\N
116	FCL235	\N	\N
117	FCL236	\N	\N
118	FCL237	\N	\N
119	FCL241	\N	\N
120	FCL242	\N	\N
121	FCL243	\N	\N
122	FCL244	\N	\N
123	FCL245	\N	\N
124	FCL246	\N	\N
125	FCL247	\N	\N
126	GSI115	\N	\N
127	GSI116	\N	\N
128	GSI117	\N	\N
129	GSI121	\N	\N
130	GSI122	\N	\N
131	GSI123	\N	\N
132	GSI124	\N	\N
133	GSI125	\N	\N
134	GSI126	\N	\N
135	GSI127	\N	\N
136	GSI231	\N	\N
137	GSI232	\N	\N
138	GSI233	\N	\N
139	GSI234	\N	\N
140	GSI235	\N	\N
141	GSI236	\N	\N
142	GSI237	\N	\N
143	GSI241	\N	\N
144	GSI242	\N	\N
145	GSI243	\N	\N
146	GSI244	\N	\N
147	GSI245	\N	\N
149	GSI246	\N	\N
150	GSI247	\N	\N
151	MAV 111	\N	\N
152	MAV 112	\N	\N
153	MAV 113	\N	\N
154	MAV 114	\N	\N
155	MAV 115	\N	\N
156	MAV 116	\N	\N
157	MAV 117	\N	\N
158	MAV 121	\N	\N
159	MAV 121	\N	\N
160	MAV 122	\N	\N
161	MAV 122	\N	\N
162	MAV 123	\N	\N
163	MAV 124	\N	\N
164	MAV 125	\N	\N
165	MAV 126	\N	\N
166	MAV 126	\N	\N
167	MAV 127	\N	\N
168	MAV 231	\N	\N
169	MAV 232	\N	\N
170	MAV 233	\N	\N
171	MAV 234	\N	\N
172	MAV 235	\N	\N
173	MAV 236	\N	\N
174	MAV 237	\N	\N
175	MAV 241	\N	\N
176	MAV 242	\N	\N
177	MAV 243	\N	\N
178	MAV 244	\N	\N
179	MAV 245	\N	\N
180	MAV 246	\N	\N
181	MAV 247	\N	\N
182	EAP 111	\N	\N
183	EAP 112	\N	\N
184	EAP 112	\N	\N
185	EAP 112	\N	\N
186	EAP 113	\N	\N
187	EAP 114	\N	\N
188	EAP 115	\N	\N
0	/	\N	\N
189	EAP 116	\N	\N
190	EAP 117	\N	\N
191	EAP 121	\N	\N
192	EAP 122	\N	\N
193	EAP 123	\N	\N
194	EAP 124	\N	\N
195	EAP 125	\N	\N
196	EAP 126	\N	\N
197	EAP 127	\N	\N
198	EAP 231	\N	\N
199	EAP 232	\N	\N
200	EAP 233	\N	\N
201	EAP 234	\N	\N
202	EAP 235	\N	\N
203	EAP 236	\N	\N
204	EAP 237	\N	\N
205	EAP 241	\N	\N
206	EAP 242	\N	\N
207	EAP 243	\N	\N
208	EAP 244	\N	\N
209	EAP 245	\N	\N
210	EAP 246	\N	\N
211	EAP 246	\N	\N
212	EAP 247	\N	\N
214	BAT 111	\N	\N
215	BATT 112	\N	\N
216	BATT 113	\N	\N
217	BAT 114	\N	\N
218	BAT 115	\N	\N
219	BAT 116	\N	\N
220	BAT 117	\N	\N
221	BAT 121	\N	\N
222	BAT 122	\N	\N
223	BAT 123	\N	\N
224	BAT 124	\N	\N
225	BAT 125	\N	\N
226	BAT 126	\N	\N
228	BAT 127	\N	\N
229	BAT 231	\N	\N
230	BAT 232	\N	\N
231	BAT 233	\N	\N
232	BAT 234	\N	\N
233	BAT 235	\N	\N
236	BAT 236	\N	\N
237	BAT 237	\N	\N
238	BAT 241	\N	\N
239	BAT242	\N	\N
240	BAT 243	\N	\N
242	BAT 244	\N	\N
243	BAT 245	\N	\N
244	BAT 246	\N	\N
245	BAT 247	\N	\N
246	MCV111	\N	\N
247	MCV112	\N	\N
248	MCV113	\N	\N
249	MCV114	\N	\N
250	MCV115	\N	\N
251	MCV116	\N	\N
252	MCV117	\N	\N
253	MCV121	\N	\N
254	MCV122	\N	\N
255	MCV123	\N	\N
256	MCV124	\N	\N
257	MCV125	\N	\N
258	MCV126	\N	\N
259	MCV127	\N	\N
260	MCV231	\N	\N
261	MCV232	\N	\N
262	MCV233	\N	\N
263	MCV234	\N	\N
264	MCV235	\N	\N
265	MCV236	\N	\N
266	MCV237	\N	\N
267	MCV241	\N	\N
268	MCV242	\N	\N
269	MCV243	\N	\N
270	MCV244	\N	\N
271	MCV245	\N	\N
272	MCV246	\N	\N
273	MCV247	\N	\N
274	AMA111	\N	\N
275	AMA112	\N	\N
276	AMA113	\N	\N
277	AMA114	\N	\N
278	AMA115	\N	\N
279	AMA116	\N	\N
280	AMA117	\N	\N
282	AMA121	\N	\N
283	AMA122	\N	\N
284	AMA123	\N	\N
285	AMA124	\N	\N
286	AMA125	\N	\N
287	AMA126	\N	\N
288	AMA127	\N	\N
289	AMA231	\N	\N
290	AMA232	\N	\N
291	AMA233	\N	\N
292	AMA234	\N	\N
293	AMA235	\N	\N
294	AMA236	\N	\N
295	AMA237	\N	\N
296	AMA241	\N	\N
297	AMA242	\N	\N
298	AMA243	\N	\N
299	AMA244	\N	\N
300	AMA245	\N	\N
301	AMA246	\N	\N
302	AMA247	\N	\N
303	CGE237	\N	\N
304	CGE236	\N	\N
305	CGE247	\N	\N
306	CGE235	\N	\N
307	CGE234	\N	\N
308	CGE126	\N	\N
309	CGE127	\N	\N
310	CGE125	\N	\N
311	CGE117	\N	\N
312	CGE116	\N	\N
313	CGE114	\N	\N
314	CGE121	\N	\N
315	CGE124	\N	\N
316	CGE111	\N	\N
317	CGE122	\N	\N
318	CGE123	\N	\N
319	CGE124	\N	\N
320	CGE125	\N	\N
321	CGE115	\N	\N
322	CGE116	\N	\N
323	TPU111	\N	\N
324	TPU112	\N	\N
325	TPU112	\N	\N
326	TPU112	\N	\N
327	TPU113	\N	\N
328	TPU114	\N	\N
329	TPU115	\N	\N
330	TPU116	\N	\N
331	TPU117	\N	\N
332	TPU121	\N	\N
333	TPU122	\N	\N
334	TPU123	\N	\N
335	TPU124	\N	\N
336	TPU125	\N	\N
337	TPU126	\N	\N
338	TPU127	\N	\N
339	TPU231	\N	\N
340	TPU232	\N	\N
341	TPU233	\N	\N
342	TPU234	\N	\N
343	TPU235	\N	\N
344	TPU236	\N	\N
345	TPU237	\N	\N
346	TPU241	\N	\N
347	TPU242	\N	\N
348	TPU243	\N	\N
349	TPU244	\N	\N
350	TPU245	\N	\N
351	TPU246	\N	\N
352	TPU247	\N	\N
353	FME111	\N	\N
354	FME112	\N	\N
357	FME113	\N	\N
359	FME114	\N	\N
360	FME115	\N	\N
361	FME116	\N	\N
362	FME117	\N	\N
363	FME121	\N	\N
364	FME122	\N	\N
365	FME123	\N	\N
366	FME124	\N	\N
367	FME125	\N	\N
368	FME126	\N	\N
369	FME127	\N	\N
370	FME231	\N	\N
371	FME232	\N	\N
372	FME233	\N	\N
373	FME234	\N	\N
374	FME235	\N	\N
375	FME236	\N	\N
376	FME237	\N	\N
377	FME241	\N	\N
378	FME242	\N	\N
379	FME243	\N	\N
381	FME245	\N	\N
382	FME246	\N	\N
383	FME247	\N	\N
385	FME244	\N	\N
391	ASS111	\N	\N
392	ASS112	\N	\N
393	ASS113	\N	\N
394	ASS114	\N	\N
395	ASS115	\N	\N
396	ASS116	\N	\N
397	ASS117	\N	\N
398	ASS121	\N	\N
399	ASS122	\N	\N
400	ASS123	\N	\N
401	ASS124	\N	\N
402	ASS125	\N	\N
403	ASS126	\N	\N
404	ASS127	\N	\N
405	ASS231	\N	\N
406	ASS232	\N	\N
407	ASS233	\N	\N
408	ASS234	\N	\N
409	ASS235	\N	\N
410	ASS236	\N	\N
412	ASS237	\N	\N
413	ASS241	\N	\N
414	ASS242	\N	\N
415	ASS243	\N	\N
416	ASS244	\N	\N
417	ASS245	\N	\N
418	ASS246	\N	\N
419	ASS247	\N	\N
420	MSE111	\N	\N
421	MSE112	\N	\N
422	MSE113	\N	\N
423	MSE114	\N	\N
424	MSE115	\N	\N
425	MSE116	\N	\N
426	MSE117	\N	\N
427	MSE121	\N	\N
428	MSE122	\N	\N
429	MSE123	\N	\N
430	MSE124	\N	\N
431	MSE125	\N	\N
432	MSE126	\N	\N
433	MSE127	\N	\N
434	MSE231	\N	\N
435	MSE232	\N	\N
436	MSE233	\N	\N
437	MSE234	\N	\N
438	MSE235	\N	\N
439	MSE236	\N	\N
440	MSE237	\N	\N
441	MSE241	\N	\N
442	MSE242	\N	\N
443	MSE243	\N	\N
444	MSE244	\N	\N
445	MSE245	\N	\N
446	MSE246	\N	\N
447	MSE247	\N	\N
448	CME111	\N	\N
449	CME111	\N	\N
450	CME112	\N	\N
451	CME112	\N	\N
452	CME112	\N	\N
453	CME112	\N	\N
454	CME112	\N	\N
455	CME112	\N	\N
456	CME112	\N	\N
457	CME112	\N	\N
458	CME112	\N	\N
459	CME112	\N	\N
460	CME112	\N	\N
461	CME112	\N	\N
462	CME112	\N	\N
463	CME112	\N	\N
464	CME112	\N	\N
465	CME113	\N	\N
466	CME113	\N	\N
467	CME113	\N	\N
468	CME114	\N	\N
469	CME115	\N	\N
470	CME116	\N	\N
471	CME117	\N	\N
472	CME121	\N	\N
473	CME121	\N	\N
474	CME121	\N	\N
475	CME122	\N	\N
476	CME123	\N	\N
477	CME123	\N	\N
478	CME117	\N	\N
479	CME124	\N	\N
480	CME125	\N	\N
481	CME126	\N	\N
482	CME127	\N	\N
483	CME127	\N	\N
484	CME127	\N	\N
485	CME231	\N	\N
486	CME232	\N	\N
487	CME233	\N	\N
488	CME234	\N	\N
489	CME235	\N	\N
490	CME236	\N	\N
491	CME237	\N	\N
492	CME241	\N	\N
493	CME242	\N	\N
494	CME243	\N	\N
495	CME244	\N	\N
496	CME245	\N	\N
497	CME246	\N	\N
498	CME247	\N	\N
499	BQF111	\N	\N
500	BQF112	\N	\N
501	BQF113	\N	\N
502	BQF114	\N	\N
503	BQF115	\N	\N
504	BQF116	\N	\N
505	BQF117	\N	\N
506	BQF121	\N	\N
507	BQF122	\N	\N
508	BQF123	\N	\N
509	BQF124	\N	\N
510	BQF125	\N	\N
511	BQF126	\N	\N
512	BQF127	\N	\N
513	BQF231	\N	\N
514	BQF232	\N	\N
515	BQF233	\N	\N
516	BQF234	\N	\N
517	BQF235	\N	\N
518	BQF236	\N	\N
519	BQF237	\N	\N
520	BQF241	\N	\N
521	BQF242	\N	\N
522	BQF243	\N	\N
523	BQF244	\N	\N
524	BQF245	\N	\N
525	BQF246	\N	\N
526	BQF247	\N	\N
527	GEH111	\N	\N
528	GEH112	\N	\N
529	GEH113	\N	\N
530	GEH114	\N	\N
531	GEH115	\N	\N
532	GEH116	\N	\N
533	GEH117	\N	\N
534	GEH121	\N	\N
535	GEH122	\N	\N
536	GEH123	\N	\N
537	GEH124	\N	\N
538	GEH125	\N	\N
539	GEH126	\N	\N
540	GEH127	\N	\N
541	GEH231	\N	\N
542	GEH232	\N	\N
543	GEH233	\N	\N
544	GEH234	\N	\N
545	GEH235	\N	\N
546	GEH236	\N	\N
547	GEH237	\N	\N
548	GEH241	\N	\N
549	GEH242	\N	\N
550	GEH243	\N	\N
551	GEH244	\N	\N
552	GEH245	\N	\N
553	GEH246	\N	\N
554	GEH247	\N	\N
555	GEC111	\N	\N
556	GEC112	\N	\N
557	GEC113	\N	\N
558	GEC114	\N	\N
559	GEC115	\N	\N
560	GEC116	\N	\N
561	GEC117	\N	\N
562	GEC121	\N	\N
563	GEC122	\N	\N
564	GEC123	\N	\N
565	GEC124	\N	\N
566	GEC125	\N	\N
567	GEC126	\N	\N
568	GEC127	\N	\N
569	GEC231	\N	\N
570	GEC232	\N	\N
571	GEC233	\N	\N
572	GEC234	\N	\N
573	GEC235	\N	\N
574	GEC236	\N	\N
575	GEC237	\N	\N
576	GEC241	\N	\N
577	GEC242	\N	\N
578	GEC243	\N	\N
579	GEC244	\N	\N
580	GEC245	\N	\N
581	GEC246	\N	\N
582	GEC247	\N	\N
583	JOU111	\N	\N
584	JOU112	\N	\N
585	JOU113	\N	\N
586	JOU114	\N	\N
587	JOU115	\N	\N
588	JOU116	\N	\N
589	JOU117	\N	\N
590	JOU121	\N	\N
591	JOU122	\N	\N
592	JOUI123	\N	\N
593	JOU123	\N	\N
594	JOU124	\N	\N
595	JOU125	\N	\N
596	JOU126	\N	\N
597	JOU127	\N	\N
598	JOU231	\N	\N
599	JOU232	\N	\N
600	JOU233	\N	\N
601	JOU234	\N	\N
602	JOU235	\N	\N
603	JOU236	\N	\N
604	JOU237	\N	\N
605	JOU241	\N	\N
606	JOU241	\N	\N
607	JOU242	\N	\N
608	JOU243	\N	\N
609	JOU244	\N	\N
610	JOU245	\N	\N
611	JOU246	\N	\N
612	JOU247	\N	\N
613	GSI111	\N	\N
614	GSI112	\N	\N
615	GSI113	\N	\N
616	GSI114	\N	\N
617	GSI115	\N	\N
618	GSI116	\N	\N
619	GSI117	\N	\N
620	GSI121	\N	\N
621	GSI122	\N	\N
622	GSI123	\N	\N
623	GSI124	\N	\N
624	GSI125	\N	\N
625	GSI126	\N	\N
626	GSI127	\N	\N
627	GSI231	\N	\N
628	GSI232	\N	\N
629	GSI233	\N	\N
630	GSI234	\N	\N
631	GSI236	\N	\N
632	GSI237	\N	\N
633	GSI241	\N	\N
634	GSI242	\N	\N
635	GSI243	\N	\N
636	GSI244	\N	\N
637	GSI245	\N	\N
638	GSI246	\N	\N
639	GSI247	\N	\N
640	GSI235	\N	\N
\.


--
-- Name: codages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('codages_id_seq', 640, true);


--
-- Data for Name: cycle; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY cycle (id, nom) FROM stdin;
1	BTS
3	CAPACITE
6	MASTER PRO
7	LICENCE PRO
8	FORMATION 
10	LICENCE
\.


--
-- Name: cycle_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('cycle_id_seq', 11, true);


--
-- Data for Name: decaissement; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY decaissement (id, id_personnel, motif, montant, date, annee_scolaire) FROM stdin;
\.


--
-- Name: decaissement_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('decaissement_id_seq', 1, false);


--
-- Data for Name: discipline; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY discipline (id, id_eleve, id_personnel, annee_scolaire, sequence, type, nb_heures, just, retard, nb_jours, exclus_def, description, ddate) FROM stdin;
\.


--
-- Name: discipline_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('discipline_id_seq', 1, false);


--
-- Data for Name: discipline_personnel; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY discipline_personnel (id, heure_arrive, id_personnel, type, date_d, nombre, annee_scolaire) FROM stdin;
\.


--
-- Name: discipline_personnel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('discipline_personnel_id_seq', 1, false);


--
-- Data for Name: dispense; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY dispense (id, id_personnel, id_matiere, id_classe, jour, id_horaire, annee_scolaire, id_nniveau, id_salle) FROM stdin;
1	3	517	2	1	13	2018-2019	5	0
2	3	517	2	1	14	2018-2019	5	0
3	3	517	2	1	16	2018-2019	5	0
4	3	517	2	1	17	2018-2019	5	0
5	17	532	2	2	11	2018-2019	5	0
6	17	532	2	2	12	2018-2019	5	0
7	24	763	2	2	13	2018-2019	5	0
8	24	763	2	2	14	2018-2019	5	0
9	13	526	2	2	16	2018-2019	5	0
10	13	526	2	2	17	2018-2019	5	0
11	11	545	2	3	11	2018-2019	5	0
12	11	545	2	3	12	2018-2019	5	0
\.


--
-- Name: dispense_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('dispense_id_seq', 12, true);


--
-- Data for Name: eleve; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY eleve (id, nom, prenom, date_naiss, lieu_naiss, sexe, nationalite, quartier, parent_ens, lien_tuteur, matricule, email, tel, status_matrimonial, region_origine, departement_origine, diplome_preinscription, annee_obtention, pays_obtention, dernier_etablissement, annee_sortie, diplome_obtenu, premiere_langue, deuxieme_langue, autre_langue, profession) FROM stdin;
4	TAKAM NSOLO Didier	\N	1998-04-01	LOUM	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-B0004		698071361	\N	OUEST								Francais			
3	ABADA NGANA Jean Marie	\N	1999-08-27	OBALA	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-ELEC0003		694757986	\N	centre	LEKIE	BACCALAUREAT 	2018					Francais			ETUDIANT
13	ALIMA Anne	\N	1997-09-29	yaounde	F	Camerounaise	yaounde 	\N		\N		655871685	\N			BACCALAUREAT 									
38	GOUFAN Agathe Davina	\N	1996-03-27	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0038	/	656510060	\N	/	/	BACCALAUREAT A	2018	Cameroun	COLLEGE BILINGUE FOCHE	/	/	Francais	ANGLAIS	ESPAGNOL	ETUDIANTE
40	MENOUNGA  ATANGANA Pierre	\N	1997-06-26	OKOK	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-ELEC0040	/	653952772	\N	/	/	BACCALAUREAT  ELECTROTECHNIQUE	2018	Cameroun	/			Francais	/	/	ETUDIANT
26	AFSA NTAINTIE TOUIKOUO	\N	1997-09-15	NJINKA_FOUMBA	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0026	/	655505600	\N	OUEST	NOUN	BACCALAUREAT ESF	2018	Cameroun	/	/	/	Francais	ANGLAIS	/	ETUDIANT
34	AMBOMBO OLOMO Marie Laure	\N	1994-07-09	SA'A	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-CGE0034	marielaureolomo@gmail.com	670817285	\N	/	/	BACCALAUREAT CG	2017	Cameroun	/	/	/	Francais	ANGLAIS	/	ETUDIANTE
21	NTSANA CATHERINE Marina	\N	1996-10-23	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0021	/	691574391	\N	/	/	BACCALAUREAT A	2018	Cameroun	LYCEE D'ELIG ESSONO	/	/	Francais	/	/	ETUDIANT
9	WATE WATE Bodoin	\N	1994-08-05	BAZOU	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-ELEC0009		690829274	\N	OUEST		BACCALAUREAT 									
8	BESSONG A DANG Stephane 	\N	1996-02-20	GOUFAN I 	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-B0008		695281308	\N												
11	NGOLA Catherine Merveille	\N	1998-12-15	NTOUESSONG	F	Camerounaise	yaounde 	\N		18IS-HINTEL-ELEC0011		694170089	\N	centre		BACCALAUREAT 						Francais			
17	NGO BAPPA Georgette Laurence	\N	1995-10-15	yaounde	F	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0017		697695900	\N			BACCALAUREAT 						Francais			
22	ONGUETOU BOYOMO Chanceline	\N	2000-02-07	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0022	/	698720630	\N	/	/	BACCALAUREAT ESF	2017	Cameroun	COLLEGE NOTRE DAME DE VICTOIRE	/	/	Francais	/	/	ETUDIANT
7	EBENE  MOUKOUDI Hannah Claudette	\N	2002-12-27	NKONGSAMBA	F	Camerounaise	yaounde 	\N		18IS-HINTEL-EA0007		696863278	\N												
41	KOUNTCHIE  KAMDEM Auriane	\N	1991-06-02	BAFOUSSAM	F	Camerounaise	yaounde 	\N	oui	18IS-HINTEL-GHM0041	aurianekountchiekamdem@yahoo.com	656257395	\N	/	/	BACCALAUREAT A	2018	Cameroun	/	/	/	Francais	ANGLAIS	ESPAGNOL	ETUDIANTE
6	METAFE SOUDJA Melaine Erica	\N	1999-08-26	MBOUO	F	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0006		694709924	\N												
23	MENGUE NGAH ROSALIE Brenda	\N	2001-01-03	OBALA	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0023	/	691544812	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANT
20	BAMA BAYIHA Odette Vanessa	\N	1997-01-08	MAMBANDO	F	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0020		691980079	\N			BACCALAUREAT  D	2016	Cameroun				Francais			
31	PAMENI Laurence Mireille	\N	1985-01-24	BANKA_BAFANG	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-CGE0031	pamenilaurence@yahoo.fr	675647957	\N	OUEST	/	BACCALAUREAT A	2007	Cameroun	/	/	/	Francais	ANGLAIS	ESPAGNOL	ETUDIANTE
10	MOUHAMADOU Moctar	\N	1996-07-27	NGAOUNDERE	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-B0010		673586667	\N	NORD		BACCALAUREAT 						Francais			ETUDIANT
5	ILOGA Issac Christian	\N	1997-10-06	EDEA	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-GHM0005		698324091	\N			BACCALAUREAT 									
36	MPONGO EBOME InGrid Anais	\N	1997-07-12	FOUMBAN	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0036	/	683900080	\N	/	/	BACCALAUREAT A	2018	Cameroun	/	/	/	Francais	ANGLAIS	allemand	ETUDIANTE
2	NGONO NOAH Diane Christine	\N	1996-09-28	yaounde	F	Camerounaise	yaounde 	\N		18IS-HINTEL-J0002		655585982	\N	centre								Francais			
14	NANGA BESSALA Fidelle	\N	1995-02-06	MEYOS	M	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0014		695103382	\N			BACCALAUREAT 						Francais			
12	EWODO TSANGA Nestor Toussaint	\N	1992-11-09	yaounde	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-TB0012		655918651	\N			BACCALAUREAT 									
19	MAFOUMA AMBASSA Claudine Murielle	\N	1992-04-12	yaounde	F	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0019		657895166	\N			BACCALAUREAT 						Francais			
28	KENNE SONGNA Martial	\N	1990-07-30	BAFOUSSAM	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-B0028	/	652784072	\N	OUEST	MIFI	BACCALAUREAT  D	2010	Cameroun	/	/	/	Francais	/	/	ETUDIANT
27	MBELEG Adele Carole	\N	2000-04-09	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GSI0027	/	6933307401	\N	/	/	BACCALAUREAT A	2018	Cameroun	COLLEGE PRIVE POLYVALENT	/	/	Francais	/	/	ETUDIANT
32	LAH KOUMOU Jean Bertrand	\N	1987-12-27	NLONG	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-EA0032	eyimiromeo@gmail.com	691505530	\N	centre	LEKIE	BACCALAUREAT A	2010	Cameroun	universite de yaounde 1	/	/	Francais	ANGLAIS	allemand	ETUDIANT
33	LESSOUGA NKANA  GERTRUDE YVANNA	\N	1999-01-04	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-EA0033	geryvalessouga@gmail.com	698909925	\N	/	/	BACCALAUREAT A	2018	Cameroun	/	/	/	Francais	ANGLAIS	ESPAGNOL	ETUDIANTE
25	NANA NGUEFO TATIANA Doriane	\N	1998-01-02	BAMENA	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0025	/	681356811	\N	/	/	BACCALAUREAT A	2018	Cameroun	LYCEE DE NGOUSSO NGOULEMEKONG	/	/	Francais	ANGLAIS	ESPAGNOL	ETUDIANT
35	NGO NGUE Agnes Elvire Sandrine	\N	1989-07-24	MAROUA	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0035	/	695593883	\N	/	/	BACCALAUREAT  D	2014	Cameroun	/	/	/	Francais	ANGLAIS	/	ETUDIANTE
24	MBA MBANG Deogracias salvador nguema	\N	1999-03-08	AYENE	M	GUINE EQUATORIAL	yaounde 	\N	/	18IS-HINTEL-GSI0024	/	693836445	\N	/	/	BACCALAUREAT INDUSTRIE ET TECHNOLOGIE	/	GUINEE EQUATORIAL	/	/	/	/	/	/	ETUDIANT
37	ASSOGOMA BOYOMO Wilfrane	\N	1996-01-12	BAKOA	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-CO0037		656849472	\N	/	/	BACCALAUREAT A	2018	Cameroun	LYCEE BILINGUE D'EMANA	/	/	Francais	ANGLAIS	allemand	ETUDIANTE
42	TAGANG Patrik Junior	\N	1998-03-02	BABADJOU	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-ELEC0042	/	693548177	\N	OUEST	/	BACCALAUREAT F3	2018	Cameroun	COLLEGE FADIMATOU AADJA	/	/	Francais	/	/	ETUDIANT
15	TOUA Francine Corine Bernadette	\N	1986-06-02	yaounde	F	Camerounaise	yaounde 	\N		18IS-HINTEL-GHM0015		679631507	\N			BACCALAUREAT 						Francais			
16	NDE SAAH Franklin Landor 	\N	1995-02-19	MBOUDA	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-B0016	franklinnde@gmail.com	655343180	\N	OUEST	/	BACCALAUREAT D	/	/	/	/	/	Francais	/	/	ETUDIANT
30	EDOUA TIMOTHEE Hariston Ulrich	\N	1997-10-23	OBALA	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-ELEC0030	/	673842559	\N	/	/	BACCALAUREAT ELEC	2018	Cameroun	LYCEE TECHNIQUE D'OBALA	/	/	Francais	ANGLAIS	/	ETUDIANT
43	ADIBIA BOTOMOYO Carole 	\N	1987-10-02	yaounde	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0043	/	676860093	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
44	ZOBO Christelle ma Concience	\N	1998-10-10	NKOLO	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0044	/	674204329	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
46	MONKAM TCHOUMBOUEN Francky 	\N	1994-03-11	BANKA	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-GHM0046	f.tchoumboun@gmail.com	674203364	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANT
47	MELI TEDOUNG Raïssa 	\N	1997-05-05	BANGANG	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0047	/	696432153	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
49	NGODA NGOUANA Liliane Raïssa 	\N	1993-05-16	BALESSING	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0049	/	695450149	\N	OUEST	/	BACCALAUREAT ESF	2018	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
51	TEMFACK Ngoune Yvan Carmel	\N	1999-12-13	BABADJOU	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-B0051	/	694436254	\N	/	/	BACCALAUREAT BAT	2018	Cameroun	LYCEE TECHNIQUE DE NSAM	/	/	Francais	/	/	ETUDIANT
53	TJECK BIYAGA TEDDY IVAN	\N	1996-07-18	yaounde	M	CAMEROUNAIS	yaounde 	\N	/	18IS-HINTEL-EA0053	/	656404576	\N	/	/	BACCALAUREAT  D	2018	Cameroun	/	/	/	Francais	/	/	ETUDIANT
54	NJEUMO JORDAN Loïc 	\N	1999-07-29	BATIE	M	Camerounaise	yaounde 	\N	/	18IS-HINTEL-B0054	/	678097124	\N	OUEST	HAUTS PLATEAUX	BACCALAUREAT BAT	2018	Cameroun	/	/	/	Francais	/	/	ETUDIANT
56	LONTCHI KENNE Merlin	\N	1996-02-22	BALESSING	M	CAMEROUNAIS	yaounde 	\N	oui	18IS-HINTEL-PA0056	/	683938288	\N	/	/	BTS	2018	Cameroun	/	/	/	Francais	/	/	ETUDIANT
57	HALEU NOUALEU Kevin Rostand	\N	1995-09-20	YAOUNDE	M	CAMEROUNAIS	YAOUNDE 	\N	/	18IS-HINTEL-B0057	/	698170669	\N	/	/	BACCALAUREAT BAT 	2018	CAMEROUN	LYCEE TECHNIQUE DE NSAM	/	/	FRANÇAIS 	/	/	ETUDIANT
58	TCHOUTA Astride Julie 	\N	1996-01-03	DOUALA	F	CAMEROUNAISE	YAOUNDE 	\N	OUI	18IS-HINTEL-GHM0058	astridetchouta@gmail.com	693726384	\N	/	/	BACCALAUREAT A	2018	CAMEROUN	/	/	/	FRANÇAIS 	/	/	ETUDIANTE
59	MAKOUZOUH Brinda Jefryle	\N	1999-09-09	BAFOUNDA	F	CAMEROUNAISE	EMANA	\N		18IS-HINTEL-GHM0059		699630319	\N	OUEST	BAMBOUTOS	B		CAMEROUN	LYCEE DE MBOUGONG	2018	BACCALAURIAT	FRANCAIS	ANGLAIS		
60	FOTSING YOUMSI Loic Cabrel	\N	2000-01-13	BAMENDJOU	M	CAMEROUNAIS	NGOUSSO	\N		18IS-HINTEL-B0060	loicfotsing1234@gmail.com	696928828	\N	OUEST	HAUT-PLATEAU	BACCALAUREAT D	2018	CAMEROUN	LYCEE TECHNIQUE DE NSAM	2018	BACCALAURIAT	FRANCAIS			ETUDIANT
61	AYISSI NDZOU SYMPHORIEM JOEL	\N	1990-08-16	YAOUNDE	M	CAMEROUNAIS	NGOUSSO	\N		18IS-HINTEL-CGE0061	Ayissisym@gmail.com	656892265	\N	centre	sa'a	rélévé niveau 1	2018	cameroun	ismat	2018		français			
70	NGOM AMASSAKA  Christine Pascaline	\N	1994-03-28	DOUALA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CO0070	lbby0111@gmail.com	6909813	\N	/	/	BACCALAUREAT A4	2014	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ALLEMAND	ETUDIANTE
39	OMBANGU'A NTSOLI Constantine	\N	1998-09-22	ASSALA I	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-GHM0039	/	650388682	\N	/	/	BACCALAUREAT A	2016	Cameroun	/	/	/	Francais	ANGLAIS	allemand	ETUDIANTE
63	LEUWE NAYANG ANAIS	\N	1994-04-20	DOUALA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0063	anisleuwe@gmail.com	696731062	\N	/	/	BACCALAUREAT A4	2018	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ALLEMAND	ETUDIANT
64	BOUDA FOUNJOU SODETOU SHEIKA	\N	1991-10-06	YAOUNDE	F	NFOUNDI	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0064	/	655069677	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	/	ETUDIANTE
65	KEMEGNE Dannielle Raissa 	\N	1998-02-05	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0065	/	696485428	\N	/	/	rélévé niveau 1	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
67	MAGNE Vanelle Felicite 	\N	2002-02-26	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-TB0067	/	697926001	\N	/	/	BACCALAUREAT C	2018	CAMEROUN	COLLEGE SAINT AUGUSTIN	2018	BACCALAURIAT	FRANCAIS	ANGLAIS	/	ETUDIANTE
66	NGAH Juliette Charlotte 	\N	1993-06-15	NKOMETOU II	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0066	/	656563505	\N	/	/	BACCALAUREAT A4	/	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ALLEMAND	ETUDIANTE
69	BIYANG BOUIKISSAM THOMAS	\N	1999-04-08	HARA	M	TCHADIENNE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0069	/	693150057	\N	/	/	BACCALAUREAT 	2018	TCHAD	/	/	/	FRANCAIS	/	/	ETUDIANT
18	MABBYALAS François I Stacy	\N	1996-01-30	LIBREVILLE	M	GABONAIS	yaounde 	\N		18IS-HINTEL-GHM0018		650495609	\N									Francais			
68	BASSOKOMENA PAULE-LAURENCE	\N	1996-06-15	BEGNI-BORITO	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0068	laurencebasso@yahomail	672652898	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
72	SIMO NKOUNGA VANESSA	\N	1995-11-20	MAKENENE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0072	/	676403154	\N	/	/	BACCALAUREAT A4	2018	CAMEROUN	LYCCE BILINGUE DE NDIKI	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANTE
73	NGUEMCHE MEYOU Kesia Manuela	\N	1997-10-04	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0073	/	699899245	\N	/	/	ADVANCE LEVEL	2018	CAMEROUN	/	/	/	/	ANGLAIS	/	ETUDIANTE
74	DONGMO NGOKENG CARIN	\N	1996-07-25	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-EA0074	/	697073629	\N	OUEST	/	BACCALAUREAT A4	2018	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANT
75	NGA KOUNA Valerie Mirabelle 	\N	1995-11-22	LEKIE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0075	/	652356631	\N	/	/	rélévé niveau 1	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
52	FOUODJI MANFOUO Falonne	\N	1998-01-02	BATCHAM	F	Camerounaise	yaounde 	\N	/	18IS-HINTEL-CGE0052	/	680432776	\N	OUEST	/	BACCALAUREAT C	2018	Cameroun	LYCEE BILINGUE DE BATCHAM	/	/	Francais	ANGLAIS	/	ETUDIANTE
62	ALIMA BAGA  MARIE ANNE	\N	1997-09-29	YAOUNDE	F	CAMEROUNAIS	NGOUSSO	\N		18IS-HINTEL-GHM0062		655871685	\N	CENTRE	MFOUNDI	BACCALAUREAT D	2018	CAMEROUN		2018	BACCALAURIAT	FRANCAIS	ANGL	ANGLAIS	
77	HALEU YOUALEU CEDRIC	\N	1993-03-01	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0077	/	691321550	\N	CENTRE	MFOUNDI	BACCALAUREAT D	2016	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	/	ETUDIANT
78	FOUDA Laurence Tatiana	\N	1998-03-15	SA'A	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GSI0078	/	/	\N	/	/	BACCALAUREAT ESF	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
55	AMAH  OTTOU  Therese  Fleur	\N	1998-01-04	yaounde	F	Camerounaise	yaounde 	\N	/	17IS-HINTEL-CO0055	fleurottou@gmail.com	653148260	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
45	MBOUGNIA MASSEHE Michelle Jodelle	\N	1999-11-26	BABADJOU	F	Camerounaise	yaounde 	\N	/	17IS-HINTEL-CO0045	/		\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANTE
71	NTSAMA MESSI Pascaline Gaëlle 	\N	1999-03-24	SA'A	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0071	/	657648028	\N	/	/	BACCALAUREAT ESF	2018	CAMEROUN	LYCEE DE SA'A	/	/	FRANCAIS	ANGLAIS	/	ETUDIANTE
50	ANABA Jean Pierre	\N	1995-06-07	yaounde	M	CAMEROUNAIS	yaounde 	\N	/	17IS-HINTEL-TB0050		696250156	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANT
76	NGUEWOU NANFACK Raïssa 	\N	1990-10-07	FOSSONG	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-HR30076	/	651489628	\N	OUEST	MENOUA	rélévé niveau 1	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
79	AYONG BITIMBE Eulalie Martine	\N	1996-06-18	LABLE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0079	BEMEGNE.NARCISSE@yahoo.fr	657767995	\N	/	MBAM ENOUBOU	BACCALAUREAT A4	2018	CAMEROUN	/	2018	BACCALAURIAT	Francais	/	/	ETUDIANTE
80	YOUDOM YOUDOM Fleury TORDAN	\N	1996-11-15	BATIE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0080	/	676227026	\N	/	/	BACCALAUREAT F3	2018	CAMEROUN	COLLEGE POLIVALENT SAINT AUGUSTIN	/	/	/	/	/	ETUDIANTE
81	MBAZOA MISSA RODOLPHO LERICHE 	\N	1996-01-10	KOUDANDENG	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0081	/	694075158	\N	/	/	BREVET DE TECCHNICIEN ESF	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
82	TSAMO DIDEROT JEEF 	\N	1998-01-10	FOKAMEZO	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0082	jeeftsamater@gmail.com	698398931	\N	/	/	BACCALAUREAT 	2015	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
83	MBALLA ONDOA  Bernadette Ghislaine	\N	1998-11-10	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0083	/	697939096	\N	/	/	BACCALAUREAT A4	2018	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANTE
84	BINIGA OKALA Rosalie 	\N	1991-11-08	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-EA0084	/	694395333	\N	/	/	BACCALAUREAT A4	2017	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANTE
86	NGNABIA ARNOLD RODRIGUE 	\N	1989-06-08	DOUALA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0086	agnbio@gmail.com	679576688	\N	/	/	BREVET DES TECHNICIENS SUPERIEURS 	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
87	NGO ITJOKO Emilie	\N	1994-03-26	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0087	/	690440239	\N	/	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
88	NEME ZOGO Marie Louise 	\N	1995-03-27	SA'A	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0088	alinezogo96@yahoo.com	656785178	\N	/	lekie	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
89	TCHUINDEM LAURE VANESSA	\N	1993-06-03	BAHAM	F	CAMEROUNAISE	YAOUNDÉ	\N	OUI 	18IS-HINTEL-CGE0089	/	679419166	\N	OUEST	HAUT-PLATEAU	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
90	NGASSA MBASSI GILLES XAVIER PABLO 	\N	1997-05-06	EBE	M	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0090	/	673270926	\N	/	/	GCE A LEVEL	2018	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	/	ETUDIANT
92	TIATI NDEME Eric	\N	1997-01-20	ASSALA II	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0092	/	690172680	\N	/	/	BACCALAUREAT F3	2018	CAMEROUN	LYCEE TECHNIQUE OMBESSA	/	/	FRANCAIS	/	/	ETUDIANT
93	NGONO BERTIN BOBY	\N	2000-05-03	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-GSI0093	ngonobertin6@gmail.com	678043348	\N	/	/	BACCALAUREAT A4	2018	CAMEROUN	COLLEGE ENFANT D'AFRIQUE 	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANT
96	BALOGOG YVES Bertrand	\N	1992-10-03	SAKBAYEME	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0096	/	670250146	\N	/	/	BACCALAUREAT D	2017	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
97	CHECK Daibou	\N	1994-08-01	FOYET	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-GE0097	checkdailou@gmail.com	655820110	\N	/	/	rélévé du bts	2017	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
104	AKONO ANYOU VALERIE	\N	1996-08-02	OBALA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-DR0104	/	690521141	\N	/	/	BEPC	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
105	ALI ISSIDIDI LAMINOU 	\N	1990-11-15	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0105	/	69853231	\N	/	/	BACCALAUREAT F4	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
107	NOAH BESSALA  RODRIGUE	\N	1998-01-23	MBANGASSINA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0107	/	697356537	\N	/	/	BACCALAUREAT D	2017	CAMEROUN	LYCEE DE MBANGASSINA	/	/	FRANCAIS	/	/	ETUDIANT
108	ENAM KOUMAKIGNO INGRID MIRABELLE	\N	1993-05-18	NDIKINIMEKI	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0108	/	698340879	\N	/	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
111	BETCHEN PRINCE	\N	1994-05-08	DOUALA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0111	/	695347358	\N	/	MBAM-INOUBOU	BACCALAUREAT F4	2015	CAMEROUN	LYCEE TECHNIQUE DE NSAM	/	/	FRANCAIS	/	/	ETUDIANT
112	ABOMO BESSALA JOSEPH 	\N	1992-10-14	MINKAMA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0112	josephabomo07@yahoo.com	698397362	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
116	BAMAYAMINA CHRISTELLE NOEL 	\N	1995-03-12	BALAMBA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-DR0116	/	656477211	\N	/	/	BEPC	2013	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
85	FOUDA MBARGA  Albertine Nicaise	\N	2000-01-02	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-CO0085	mbargaolivia5@gmail.com 	693057992	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	/	/	/	ETUDIANTE
109	NGONO ABENA Blandine 	\N	1998-04-16	NKOLASSA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-CO0109	/	651537775	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
102	NANFACK KEMKOUM Geordan Stegue	\N	1999-08-14	BAFOU-BASSESSA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0102	geordannanfacko@gmail.com	695313279	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
95	WATCHOU KENGNE Alain Giress	\N	1997-01-06	BALENG	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0095	alainwatchou@gmail.com	655333654	\N	/	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
110	FOMEKONG YONTAK FABRICE 	\N	1994-11-08	BANGANTE 	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0110	fabriceyontak@gmail.com	680286131	\N	OUEST	nde	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
98	TCHOFFO FOTSO Eric	\N	1995-11-05	MBOUDA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0098	/	970143120	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
101	YOUBI TCHOUANKENG GUY Marcel	\N	1996-07-04	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0101	youbiguymarcel1996@gmail.com	655306498	\N	/	/	BACCALAUREAT F4	2017	CAMEROUN	COLLEGE SAINT AUGUSTIN	/	/	FRANCAIS	/	/	ETUDIANT
91	BIDZANGA ENGOLO Jean-Jurice 	\N	1991-12-12	KOUTABA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0091	/	671631989	\N	/	/	relevé LICENCE	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
100	NANGMO DORICETTE MIMI	\N	1998-10-13	NKONGSAMBA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0100	/	653064397	\N	/	/	/	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
103	NANKENG NANMETSOP Lucile  Brunelle 	\N	1996-08-03	BALEVENG	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-TB0103	senateuricelucile@gmail.com	691761793	\N	OUEST	/	BACCALAUREAT D	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
106	DIFFO DJIMELL ROMIAL 	\N	1997-10-12	MBOUDA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-TB0106	/	678120046	\N	OUEST	HAUT-PLATEAU	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
99	ADZENGUE MALKO Junior 	\N	2001-01-03	LABAM	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-TB0099	/	698751737	\N	/	/	BACCALAUREAT F4	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
115	KANYIM -NOUMBISSI FRANK ADRIEN	\N	1994-12-05	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-TB0115	aaronhanyim7@gmail.com	655125137	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
94	NKANDA TCHATCHOU Donald Gullit 	\N	1993-10-29	BARE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-IIA0094	/	677687178	\N	/	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
114	TSANANG  MALAGHO YANICK 	\N	1995-09-10	BAMENDOU 	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-ELEC0114	yannick95tsanang@yahoo.fr	679712555	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
118	KALDJOB MATHIAS 	\N	1978-04-17	SAKBAYEME	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-DR0118	mathiaskaldjob@gmali.com	679140617	\N	/	/	BEPC	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
121	ASSOMO AMANG SIAPRI	\N	1994-10-06	BAFIA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-DR0121	/	697592745	\N	/	/	PROBATOIRE A 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
122	DJOUMESSI KUINZE IDRIS LIONNEL 	\N	1999-01-01	BAFOUSSAM	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0122	IDRISDJOUMESSI1@GMAIL.COM 	699788093	\N	OUEST	MIFI	BACCALAUREAT D	2015	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
123	BIYONG JOSEPH ARISTIDE 	\N	1993-04-25	ESEKA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-MVA0123	josephbiyong@yahoo.com	694389791	\N	/	NYONG-EKELLE	BACCALAUREAT MA	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
124	MABARRO TENE EDWIGE 	\N	1993-04-13	BAFOUSSAM	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0124	/	679456640	\N	OUEST	MIFI	rélévé niveau 1	/	CAMEROUN	IS MATAMFEN	/	/	FRANCAIS	/	/	ETUDIANTE
126	BEYINA BEYINA EDWIGE	\N	1995-03-04	NGOKA 	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0126	/	656866474	\N	/	lekie	BACCALAUREAT ESF	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
128	MOLO NGONO CHRISTINE SOLANGE	\N	1983-06-04	NGAOUNDERE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	\N	/	696966247	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
130	ATEBA PATRICK LANDRY	\N	1997-07-21	GUIENTSING	M	CAMEROUNAIS	YAOUNDÉ	\N	/	\N	/	690437611	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
132	KIYETAT FRANCINE BECKY 	\N	1995-01-29	MAKENENE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHR0132	/	691819972	\N	/	/	DSEP	2016	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
134	BOTONDI GASTON 	\N	1990-02-22	EDIOLOMO	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-EA0134	/	672029299	\N	/	/	BACCALAUREAT A4	2016	CAMEROUN	/	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANT
135	BESSALA EBENE  ALPHONSE 	\N	1993-08-04	EKABITA 	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-CCA0135	bessalaalphonse32@gmail.com	679350356	\N	/	/	bts	2018	CAMEROUN	is hintel	/	/	FRANCAIS	/	/	ETUDIANT
136	FOUEGO GHOZANG  AURIOLE SORELLE	\N	1996-07-01	BALEVENG	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0136	sorelleauriole@gmail.com	655095968	\N	OUEST	menoire	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
140	SIGNE KENGNE JOSETTE 	\N	1996-10-14	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0140	/	673313440	\N	/	/	rélévé niveau 1	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
141	ENGAMA FRANCOIS XAVIER 	\N	1995-02-28	BACHENGA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0141	/	671519809	\N	/	lekie	BACCALAUREAT F4	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
142	ANABA  BEYIDA DIEUDONNE 	\N	2000-10-10	MENDOUGA-MOKLA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-B0142	/	683057899	\N	/	LEKIE	BACCALAUREAT F4	2018	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
145	MOTO OBOCK LARISSA GLADYS	\N	1994-07-20	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-DR0145	/	657214533	\N	/	MBAM	BEPC	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
146	NGAH ZOGO CORINNE 	\N	1996-05-29	SA'A	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0146	cocozogo@gmail.com	695638444	\N	CENTRE	lekie	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
147	TSASSE NGUINPEA KEVINE JORDAN	\N	1998-09-28	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0147	/	698999136	\N	/	/	BACCALAUREAT ESF	2018	CAMEROUN	LYCEE TECHNIQUE DE NSAM	/	/	FRANCAIS	/	/	ETUDIANTE
148	YAKAM TAPTCHOU MALVINE	\N	1994-09-28	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0148	malvineyakam3@gmail.com	675129272	\N	OUEST	nde	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
150	LUKALA YVONNE 	\N	1997-08-28	KISANGANI	F	CONGOLAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0150	/	698531784	\N	/	/	BACCALAUREAT HOTESSE	2017	CONGO	/	/	/	FRANCAIS	/	/	ETUDIANTE
151	DJUFFO TAMBELLA VANIELLE DIANE	\N	1998-11-18	BALENG	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0151	/	674270222	\N	OUEST	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
152	ANDERA BITEA JULIE CARELLE 	\N	1997-10-10	BOURA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0152	julieaholera@yahoo.com	673788872	\N	/	/	BACCALAUREAT ESF	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
153	NOAH SERAPHINE EDITH	\N	1999-03-23	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0153	/	678610555	\N	CENTRE	MFOUNDI	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
154	DJUIDJE ISABELLE PRISCA 	\N	1997-07-20	BAYANGAM	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0154	/	696525299	\N	/	/	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
155	YOMO MAYI SOPHIE LYNDA	\N	1998-03-16	DOUALA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0155	yomomayi@gmail.com	657360908	\N	/	/	rélévé niveau 1	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
143	MENGUINA ZONGO EMILE LIONEL	\N	1992-10-31	YAOUNDE	M	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-B0143	emilemenguina@gmail.com	656002106	\N	CENTRE	nfoundi	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
144	FOKGOUO NOUPITCHOUM FALONE LUCRESSE	\N	1997-01-08	BANSOA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-IIA0144	/	678295374	\N	OUEST	MENOUA	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
117	BASSASSA JEAN LOUIS 	\N	1991-03-27	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0117	jeanlouibassassa@gmail.com	690166658	\N	/	/	BACCALAUREAT  c	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
137	KAYIM NEKAM PAUL ELISEE	\N	1998-01-30	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0137	paulkayim@gmail.com	690905519	\N	/	/	BACCALAUREAT 	/	/	is hintel	/	/	FRANCAIS	/	/	ETUDIANT
127	DJIMAROUM DATOLOUM BASILE	\N	1998-01-01	YAOUNDE	M	TCHADIENNE	YAOUNDÉ	\N	/	17IS-HINTEL-TB0127	bdatoloum7@.com	656831051	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
119	MBANDJI SIMPLICE MARTIAL 	\N	1988-07-22	BANDOUNGA	M	CAMEROUNAIS	YAOUNDÉ	\N	OUI 	17IS-HINTEL-IIA0119	simplicembandji2017@gmail.com	673300037	\N	OUEST	nde	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
129	ATEBA PATRICK LANDRY	\N	1997-07-21	GUIENTSING	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-ELEC0129	/	690437611	\N	/	/	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
133	FEA DANIEL FABIEN	\N	1992-04-14	DOUALA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-ELEC0133	feadaniel43@gmail.com	693542859	\N	/	nfoundi	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
131	NCHANGE YACOUBA	\N	1995-06-16	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-ELEC0131	/	693548185	\N	OUEST	NOUN	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
125	MBOGNI JIOZANG SAMIRA 	\N	1997-05-14	NGAOUNDERE	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-HR30125	jiozangs5@gmail.com	697345664	\N	nord	vina	BACCALAUREAT ESF	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
138	NJANGOU KWAYEP RUTH	\N	1994-02-21	YAOUNDE	F	CAMEROUNAISE	YAOUNDÉ	\N	OUI 	17IS-HINTEL-HR30138	njangouruth@gmail.com	698383050	\N	OUEST	nde	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
149	ONDOBO NGOLA SYLVIE 	\N	1989-07-25	MENDOUGA-MOKLA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-HR30149	/	672457539	\N	/	/	BACCALAUREAT ESF	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
139	TAMO MICHELLE LINDA 	\N	1999-01-29	BAFIA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-HR30139	/	/	\N	/	MBAM-INOUBOU	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
156	MELLA TAGNE MIREILLE LARISSA 	\N	1997-06-14	MBOUDA 	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0156	/	698704451	\N	OUEST	MBAMBOUTOS	/	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
157	NDJODO GUY OLIVIER 	\N	1990-01-26	BERTOUA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-ELEC0157	ondjodo@gmail.com	699444219	\N	/	lon et jerem	BACCALAUREAT F3			/	/	/	FRANCAIS	/	/	ETUDIANT
159	NGAH TOUNA  MARIE-NOËL  	\N	1996-08-17	NKOLBOGO	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-HR30159	/	670326968	\N	/	lekie	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
160	HELES NKOU MARTIN FINNEY 	\N	1999-06-16	YAOUNDE	M	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-B0160	martinhels@yahoo.com	696623834	\N	/	/	rélévé niveau 1	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
161	JEANNE DE VICTOIRE  ONGBASSILEK	\N	1985-03-18	NDIKINIMEKI	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0161	/	677360969	\N	/	MBAM ET INOUBOU	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
163	DJUIKUI WAMBA MICHELLE LYTITIA	\N	1998-04-04	BAMENKOMBO 	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0163	/	691492182	\N	OUEST	BAMBOUTOS	BACCALAUREAT CG	2017	CAMEROUN	COLLEGE LE CEPLAN	/	/	FRANCAIS	/	/	ETUDIANTE
166	NYEMECK III JOSEPH GODFROY	\N	1992-01-02	BIYOUHA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	18IS-HINTEL-DR0166	nyemeckjoseph2017@gmail.com	672309373	\N	/	NYONG-EKELLE	BEPC	2012	CAMEROUN	COLLEGE ADVENTIS	/	/	FRANCAIS	/	/	ETUDIANT
167	NDAM ARLETTE JOELLE 	\N	1997-04-26	DJA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHR0167	ndamajo@gmail.com	693058100	\N	/	KOUNG-KHI	DIPET I	2018	CAMEROUN	ENSET DE BAMBILI	/	/	FRANCAIS	/	/	ETUDIANTE
168	TCHANTCHOU NDAMEN  DORINE	\N	1995-08-02	BANDJOUN	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHR0168	dorinetchantchou@gmail.com	674520452	\N	/	KOUNG-KHI	DIPET I	/	CAMEROUN	ENSET DE BAMBILI	/	/	FRANCAIS	/	/	ETUDIANTE
169	NGO KWANG MADELEINE 	\N	1995-10-10	MAKAK	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0169	/	655601881	\N	/	NYONG-EKELLE	BACCALAUREAT A4	2017	CAMEROUN	COLLEGE MARIE ALBERT D'ESEKA	/	/	FRANCAIS	ANGLAIS	ESPAGNOL	ETUDIANTE
170	NGATE WATE HUGUETTE	\N	1991-04-10	BAZOU	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-IIA0170	ngatehuguette@gmail.com	671832425	\N	OUEST	nde	rélévé niveau 2	/	/	ISTAG	/	/	FRANCAIS	/	/	ETUDIANTE
171	NGA ALIGNA PELAGIE GAELLE	\N	1994-08-18	NGOYA 	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0171	/	691352496	\N	/	lekie	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
173	DJUIKOM FALLONE DIANE	\N	1994-09-17	MBO	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0173	/	671225410	\N	/	NKOUNKUI	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
175	BODIE KAMDEM ODILE ZUSIE 	\N	1996-01-17	KRIBI	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-CGE0175	/	691412140	\N	/	OCEAN	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
176	NGUEFO TCHINDA LARISSA 	\N	1992-05-22	MBOUDA	F	CAMEROUNAISE	YAOUNDÉ	\N	/	18IS-HINTEL-GHM0176	/	698025452	\N	OUEST	BAMBOUTOS	BACCALAUREAT 	/	CAMEROUN	is hintel	/	/	FRANCAIS	/	/	ETUDIANTE
177	MAGNE  DOMGUIA YASMINE	\N	1993-08-12	BAHAM	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-CGE0177		674183321	\N	OUEST	HAUT-PLATEAUX	BAC CG	2017	CAMEROUN	COLLEGE DE L'AGAPE	2017	BAC CG	FRANCAIS	ANGLAIS		
120	ANONG DIEUDONNE PASCAL	\N	1994-01-12	ASSALA I	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-MVA0120	/	695242123	\N	/	MBAM ET NOUBOU	BACCALAUREAT D	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
178	TOMBI NGON CORINE NICAISE	\N	1998-11-20	BAFIA	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-MVA0178	SYLIMINAJ@YAHOO.FR	699119697	\N	CENTRE	MBAM ET INOUBOU	BACCALAURIAT 		CAMEROUN	LYCEE TECHNIQUE DE BAFIA			FRANCAIS			
179	MANGSOUNA HAISSOU SALVADOR	\N	1998-01-22	GOUNOU GAVA	M	TCHADIENNE	YAOUNDE	\N		17IS-HINTEL-MVA0179		690577374	\N		KABBIA	BACCALAURIAT D			COLLEGE SAINTETIENNE			FRANCAIS			
48	FOAKUM KAMGA Merlin Narcis	\N	1995-05-10	BAFOUSSAM	M	CAMEROUNAIS	yaounde 	\N	/	17IS-HINTEL-MVA0048	merlinfoakum03@gmail.com	652574671	\N	/	/	BACCALAUREAT 	/	Cameroun	/	/	/	Francais	/	/	ETUDIANT
187	SOTCHINDA TALLA NELSON STEVE 	\N	1996-06-23	YAOUNDE	M	CAMEROUNAIS	YAOUNDE	\N	/	17IS-HINTEL-TB0187	stevetalla6@yahoo.fr 	655140904	\N	ouest	haut-plateaux	baccalauréat	/	/	IS HINTEL	/	/	FRANÇAIS	/	/	ETUDIANT
181	TOUBOI DOUMRAM	\N	1981-03-19	SARH	M	TCHADIENNE	YAOUNDE	\N		17IS-HINTEL-DAT0181	Doumram2002@gmail.com	691366004	\N		MCHARI	BACCALAURIAT D						FRANCAIS			
113	ADIANA EDJOGO MARGUERITE DIVINE	\N	1995-10-23	MVOMNNAM	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-CO0113	/	678072295	\N	/	/	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANTE
182	SAMNICK MARIE REINE	\N	1997-05-01	YAOUNDE	F	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-CA0182	samnickmariereine@gmail.com	696743871	\N		NYON EKELE	RELEVE NIVEAU 1	2017	CAMEROUN	IS-HINTEL			FRANCAIS			
183	MVONDO MVONDO DIEUDONNE	\N	1992-04-13	NKOLMVAK	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-CA0183	divimvondo@yahoo.fr	656253129/651643745	\N	CENTRE	LEKIE	RELEVE NIVEAU 1	2017	CAMEROUN	IS-HINTEL			FRANCAIS	ANGLAIS		
184	BELINGA NGADENA GEORGES STEEVE	\N	1997-04-11	YAOUNDE I	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-CA0184		691677827	\N	CENTRE	MFOUNDI	BACCALAURIAT 	2016	CAMEROUN	IS-HINTEL			FRANCAIS			
185	NLOMNGOI JEANNE GWLADYS	\N	1996-01-16	DOUALA	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-CA0185		694613094	\N	CENTRE	NYON-EKELLE	RELEVE NIVEAU 1	2017	CAMEROUN	IS-HINTEL			FRANCAIS			
186	FOYOUSSA ULRICH MARDOCHEE	\N	1994-04-08	FOUMBAN	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-CA0186	foyouvaulrich@gmail.com	697610948	\N	OUEST	noun	RELEVE NIVEAU 1	2017	CAMEROUN	IS-HINTEL			anglais	français		
158	KELLENG ESSOMBA FLORENT FABRICE 	\N	1995-02-15	EPEOA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-ELEC0158	/	652517028	\N	/	/	BACCALAUREAT F3	/	/	/	/	/	FRANCAIS	/	/	ETUDIANT
188	ASSEN DE NYAM LYNA LARISTHA	\N	1996-07-31	NIETE	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0188	/	655054375	\N	/	/	baccalauréat	/	/	/	/	/	FRANÇAIS	/	/	ETUDIANTE
162	BILIMINA OLOUME PIERRE LE GRAND 	\N	1992-07-28	DONEMKENG	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0162	/	650390804	\N	/	MBAM ET INOUBOU	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
172	MINDJA JUNIOR FRANK	\N	1996-12-12	NKOL-AKAM 	M	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-B0172	mindjajunior 54@gmail.com	672753043	\N	/	/	BACCALAUREAT F4	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
164	NDEME MOUMPEGNA GILBERT 	\N	1994-01-02	NINGOANG	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-B0164	/	694633660	\N	/	MBAM ET INOUBOU	BACCALAUREAT 	/	CAMEROUN	/	/	/	FRANCAIS	/	/	ETUDIANT
180	TCHINDE CEDRICK	\N	1995-01-23	BANSOA-CHEFFERIE	M	CAMEROUNAIS	YAOUNDE	\N		18IS-HINTEL-MVA0180		677208772	\N	OUEST	HAUT-PLATEAUX	BACCALAURIAT 		CAMEROUN				FRANCAIS			
165	MAGNE TAKOUSSA JEANNE-MANUELLE 	\N	1994-05-10	BAMENDJOU	F	CAMEROUNAISE	YAOUNDÉ	\N	/	17IS-HINTEL-HR30165	/	695890737	\N	OUEST	HAUT-PLATEAU	BACCALAUREAT 	/	/	/	/	/	FRANCAIS	/	/	ETUDIANTE
174	AZIBO TAFOR JOSEPH 	\N	1991-10-12	YOKADOUMA	M	CAMEROUNAIS	YAOUNDÉ	\N	/	17IS-HINTEL-HR30174	/	/	\N	/	BOUMBA ET NGOKO	/	/	/	is hintel	/	/	FRANCAIS	/	/	ETUDIANT
189	BEMELIGUINE NDIOMO	\N	1992-10-15	TCHEKOS	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-CGE0189	/	699600184	\N	/	MBAM ET INOUBOU	baccalauréat	/	CAMEROUN	IS HINTEL	/	/	FRANÇAIS	/	/	ETUDIANTE
205	FRANK CHRISTIAN DANG 	\N	1992-10-13	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-PV0205	f.dang@yahoo.com	694660838	\N	centre	NFOUNDI	BTS EN CONSEIL AGROPASTORAL	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
191	ESSIMBI MBIDA JEAN MARIE BERTRAND	\N	1994-06-18	YAOUNDE	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-B0191		655438836	\N	CENTRE	MFOUNDI	BACCALAURIAT 		CAMEROUN				FRANCAIS			
190	NDE A NKEMI MILENE JOELLE 	\N	1999-08-25	BAFIA 	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0190	/	693781514	\N	/	MBAM ET INOUBOU	baccalauréat	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANTE
192	KOAH ESTELLE MARIE 	\N	1984-09-13	YAOUNDE	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-CGE0192	estayissi@gmail.com	674190919	\N	/	MFOUNDI	baccalauréat	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANTE
193	NOTONG TAMAGUELONG ANVINO	\N	1996-07-26	BABADJOU	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-B0193		694687822	\N	OUEST	BAMBOUTOS	BACCALAURIAT C		CAMEROUN	COLOMBE D'OR			FRANCAIS			
194	ABOUEM A YAKAN	\N	1997-11-12	NGAOUNDERE	M	CAMEROUNAIS	ELEVEUR	\N		17IS-HINTEL-B0194		696233665	\N	EXTREME NORD	VINA	BACCALAURIAT D	2017	CAMEROUN	COLLEGE COLOMBE D'OR	2017	BACCALAURIAT	FRANCAIS			
195	MOUNKPOUDAP MEFIRE TARIE JSSOFA	\N	1997-01-02	NGABA	M	CAMEROUNAIS	NKOLMESSENG	\N		17IS-HINTEL-B0195			\N	OUEST	NOUN	BACCALAURIAT 	2017	CAMEROUN			BACCALAURIAT	FRANCAIS			
196	DJIMANI CEDRIC 	\N	1998-06-29	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-TB0196	/	655931482	\N	/	/	baccalauréat C	2017	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
197	AMOUGOU NKOULOU RENE FERNAND	\N	1996-05-11	NKOMO	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0197	fernandkoulou@yahoo.com	699651519	\N	/	/	bts	2018	CAMEROUN	IS HINTEL	/	/	FRANÇAIS	/	/	ETUDIANT
198	TCHOUATA TEDONZONG HERMANN	\N	1995-11-14	BATCHAM	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-CCA0198	bolchovic:@gmail.com	674528749	\N	ouest	BAMBOUTOS	LICENCE PROS	2018	CAMEROUN	FOTSO VICTOR	2018	LICENCE PROS	FRANÇAIS	/	/	ETUDIANTE
199	TOUKO TCHOUMO MICHELLE	\N	1990-12-22	YAOUNDE	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-ELEC0199			\N			BACCALAURIAT  F3			LYCEE TECHNIQUE DE NSAM			FRANCAIS			
201	KAMELEU JOSEPH 	\N	1993-09-10	EBOLOWA 	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-AM0201	kamelenj@gmail.com	695405317	\N	/	/	baccalauréat F3	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANTE
203	AMBASSA GUIEGOU LAURRAINE PASCALINE 	\N	1992-01-20	ESSENDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0203	/	656387291	\N	/	/	baccalauréat	2014	CAMEROUN	/	/	/	FRANÇAIS	anglais	ESPAGNOL	ETUDIANTE
204	TSANO ARNAUD JOEL 	\N	1993-08-05	BABADJOU 	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-B0204	/	676756698	\N	ouest	BAMBOUTOS	baccalauréat	/	CAMEROUN	COLLEGE FADIMATOU HADJA	/	/	FRANÇAIS	/	/	ETUDIANTE
29	TAMEMO DETEMBOU Arnaud	\N	1995-03-18	MBOUDA	M	CAMEROUNAIS	yaounde 	\N		18IS-HINTEL-MVA0029	/	673630276	\N	OUEST	/	BACCALAUREAT MA	2013	Cameroun	COLLEGE PRIVE POLYVALENT	/	/	Francais	ANGLAIS	/	ETUDIANT
206	NDJMBI HAWA 	\N	1999-03-18	BELABO	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0206	/	693137248	\N	/	LOM ET DJEREM	baccalauréat ESF	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANTE
207	BALKISSA ALKALI BARKA	\N	1994-07-25	GAROUA 	F	CAMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-CO0207	/	699496539	\N	NORD	BENOUE	relevé 	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANTE
208	FOTIE TAMBOU RALPHIN AUDONI 	\N	1996-05-20	BAFOUSSAM	M	CAMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-GHM0208	/	697847333	\N	ouest	MIFI	baccalauréat	/	/	IS HINTEL	/	/	FRANÇAIS	/	/	ETUDIANT
209	PIEBECK ENGELBERT	\N	1995-08-25	DOUALA	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-TB0209		6983671687	\N	LITTORAL	WOURI	BACCALAURIAT D						FRANCAIS			
210	DJOMO  YAMENI YVAN KESSEL	\N	1997-11-17	BAFANG	M	CAMEROUNAIS	YAOUNDE	\N		17IS-HINTEL-TB0210	kesseldjomo@gmail.com	696947491	\N	ouest	haut-kam	BACCALAURIAT D		CAMEROUN	lycee d'akwa nord			FRANCAIS			
211	ATANGANA II CHARLES 	\N	1993-05-23	ABONO 	M	CAMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-CO0211	/	695000277	\N	/	LEKIE 	baccalauréat D	2016	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
202	KOOH PAUL FABRICE 	\N	1997-10-29	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	17IS-HINTEL-IIA0202	/	65546718	\N	/	/	baccalauréat	/	CAMEROUN	IS HINTEL	/	/	FRANÇAIS			ETUDIANT
212	MANGA JEAN CHRISTIAN 	\N	1995-08-15	YAOUNDE	M	CAMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-ELEC0212	mangadetiji1@gmail.com	690976658	\N	/	LEKIE 	baccalauréat F3	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
213	ESSAM MELIGUI MICHEL LEONEL 	\N	1994-10-20	MEKONE I	M	CAMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-ELEC0213	essaleonel@gmail.com	676627684	\N	/	haute-sanaga	baccalauréat TI	2015	CAMEROUN	UNIVERSITE DE YAOUNDE II SOA 	/	/	FRANÇAIS	/	/	ETUDIANT
214	LAYSLYE CHANTAL VIGOUROUX	\N	1994-10-16	NKOLOTOUTOU	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-IIA0214		656905418	\N	CENTRE	DJA ET LOBO	BACCALAURIAT C	2015	CAMEROUN	LYCEE D'ESSAZOK	2015	BAC	FRANCAIS			
215	MOUDIO II JOSEPH FRANCIS 	\N	1989-02-28	YAOUNDE	M	CAMEROUNAIS	YAOUNDÉ	\N	/	\N	moudiojobel@gmail.com	690831717	\N	/	MBAM ET INOUBOU	DSEP	2017	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
216	MGBA JEAN BERNARD	\N	1996-03-12	YAOUNDE	M	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-ELEC0216		672233784	\N	CENTRE	LEKIE	BACCALAURIAT C		CAMEROUN				FRANCAIS			
217	SEKOUE SIME DIEUDONNE	\N	1999-05-01	BAFIA	M	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-ELEC0217		698688113	\N	CENTRE	MBAM ET INOUBOU	BACCALAURIAT C		CAMEROUN	LYCEE TECHNIQUE DE BAFIA	2017	BACCALAURIAT	FRANCAIS			
219	MOLO NGONO CHRISTINE SOLANGE 	\N	1983-06-04	NGAOUNDERE	F	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-DR0219	/	696966247	\N	/	/	BEPC	/	/	/	/	/	FRANÇAIS	/	/	ETUDIANTE
200	YEHOU DOMO FELIX SERGE	\N	1996-10-20	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	17IS-HINTEL-HR30200	/	694596029	\N	ouest	haut-plateaux	baccalauréat A	/	/	IS HINTEL	/	/	FRANÇAIS	anglais	ESPAGNOL	ETUDIANT
221	MBIADAM FLORENCE 	\N	1993-04-27	BAKONG	F	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-HR30221	Florancembiadam@gmail.com	670522681	\N	ouest	ndé	BACCALAURIAT A	2016	CAMEROUN	collège saint jean	2016	BACCALAURIAT	FRANCAIS			
222	TCHATOUO GUY MARCEL	\N	1994-11-14	BAFOUSSAM	M	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-HR30222	tchatomarcel4444@yahoo.com	695869152	\N	ouest	mifi	BACCALAURIAT C						FRANCAIS			
218	GAMENI NJILE JEAN PIERRE 	\N	1997-09-29	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-B0218	/	651573004	\N	/	/	baccalauréat F4	/	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
224	AHANDA JOSEPH MARTEL	\N	2018-11-11	/	M	/	/	\N		17IS-HINTEL-HR30224	/		\N	/		/		/							
223	BELL COLLETE CHRISTELLE	\N	2018-01-01	YAOUNDE	M	CAMEROUNAISE	YAOUNDE	\N		17IS-HINTEL-HR30223			\N												
225	NGONO NGONO EMILE GHISLAIN	\N	1993-03-07	YAOUNDE	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-DR0225	/	697361733	\N	/	MFOUNDI	BEPC	2010	CAMEROUN	/	/	/	FRANÇAIS	/	/	ETUDIANT
226	NTYAME EKOTTO BERTRAND 	\N	1988-01-01	SANGMELIMA 	M	CAMMEROUNAISE	YAOUNDE	\N	/	18IS-HINTEL-PV0226	n-bertrand98	664119211	\N	/	dja et làobo	bts	2015	CAMEROUN	ETA DE SANGMELIMA 	/	/	FRANÇAIS	/	/	ETUDIANT
227	MUKAM LOUIS HERVE 	\N	1995-10-31	BATHUKANG	M	CAMEROUNAIS	YAOUNDE	\N	/	18IS-HINTEL-GSI0227	louismukam@gmail.com	697618828	\N	ouest	haut-plateaux	bts	/	CAMEROUN	azimut 	/	/	FRANÇAIS	/	/	ETUDIANT
\.


--
-- Name: eleve_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('eleve_id_seq', 227, true);


--
-- Data for Name: fonction; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY fonction (id, nom) FROM stdin;
1	DIRECTEUR
2	RESPONSABLE DES AFFAIRES ACADEMIQUES
3	CHEF DE DEPARTEMENT 
4	SECRETAIRE
\.


--
-- Name: fonction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('fonction_id_seq', 4, true);


--
-- Data for Name: fonction_personnel; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY fonction_personnel (id, id_fonction, id_personnel, annee_scolaire) FROM stdin;
1	3	4	2018-2019
2	3	15	2018-2019
\.


--
-- Name: fonction_personnel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('fonction_personnel_id_seq', 2, true);


--
-- Data for Name: groupe_matieres; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY groupe_matieres (id, id_classe_matiere, id_matiere_mere, id_classe, id_nniveau, semestre) FROM stdin;
3	15	57	23	5	1
4	24	69	23	5	1
5	25	69	23	5	1
8	26	69	23	5	1
9	27	69	23	5	1
17	33	11	14	5	1
18	36	11	14	5	1
19	90	434	8	5	1
20	92	434	8	5	1
21	91	441	8	5	2
22	93	441	8	5	2
23	84	193	8	5	1
24	94	193	8	5	1
25	85	200	8	5	2
26	95	200	8	5	2
27	97	194	8	5	1
28	99	194	8	5	1
29	98	1461	8	5	1
30	100	1461	8	5	1
31	104	1461	8	5	2
32	101	195	8	5	1
33	102	195	8	5	1
34	96	202	8	5	1
35	103	202	8	5	1
36	105	196	8	5	1
37	107	196	8	5	1
38	106	1462	8	5	2
39	108	1462	8	5	2
40	79	197	8	5	1
41	109	197	8	5	1
42	80	204	8	5	2
43	110	204	8	5	2
44	112	579	8	5	1
45	114	579	8	5	1
46	111	609	8	5	2
47	113	609	8	5	2
48	151	191	8	6	3
49	153	191	8	6	3
50	152	184	8	6	4
51	154	184	8	6	4
53	136	189	8	6	3
54	133	182	8	6	4
55	137	182	8	6	4
56	132	189	8	6	3
57	128	1467	8	6	3
58	130	1467	8	6	3
59	129	1470	8	6	4
60	131	1470	8	6	4
61	123	187	8	6	3
62	134	187	8	6	3
64	138	187	8	6	3
65	124	180	8	6	4
66	135	180	8	6	4
67	139	180	8	6	4
68	119	186	8	6	3
69	120	186	8	6	3
70	140	186	8	6	3
71	141	186	8	6	3
72	145	185	8	6	3
73	146	185	8	6	3
74	149	185	8	6	3
75	147	692	8	6	4
76	148	692	8	6	4
77	150	692	8	6	4
78	158	190	8	6	3
79	160	190	8	6	3
80	159	183	8	6	4
81	161	183	8	6	4
82	70	495	2	5	1
83	69	580	2	5	2
84	164	580	2	5	2
85	39	192	2	5	1
86	67	192	2	5	1
87	40	199	2	5	2
88	68	199	2	5	2
89	55	499	2	5	1
90	57	499	2	5	1
91	56	500	2	5	2
92	58	500	2	5	2
93	61	501	2	5	1
94	63	501	2	5	1
95	62	502	2	5	2
96	64	502	2	5	2
97	49	503	2	5	1
98	166	504	2	5	2
99	167	504	2	5	2
100	53	544	2	5	1
101	65	544	2	5	1
102	54	508	2	5	2
103	66	508	2	5	2
104	171	579	2	5	1
105	173	579	2	5	1
106	170	609	2	5	2
107	172	609	2	5	2
108	180	1472	2	6	3
109	209	1472	2	6	3
110	181	1028	2	6	4
111	210	1028	2	6	4
112	211	191	2	6	3
113	213	191	2	6	3
114	212	184	2	6	4
115	214	184	2	6	4
116	186	511	2	6	3
117	188	511	2	6	3
118	187	516	2	6	4
119	189	516	2	6	4
120	184	509	2	6	3
121	216	509	2	6	3
122	185	1050	2	6	4
123	217	1050	2	6	4
124	190	512	2	6	3
125	191	512	2	6	3
126	193	512	2	6	3
127	194	512	2	6	3
128	197	513	2	6	3
129	200	513	2	6	3
130	198	565	2	6	4
131	199	565	2	6	4
132	204	692	2	6	3
133	205	692	2	6	3
134	206	700	2	6	4
135	207	700	2	6	4
136	208	700	2	6	4
137	228	77	50	5	1
138	230	77	50	5	1
139	227	84	50	5	2
140	229	84	50	5	2
141	221	71	50	5	1
142	223	71	50	5	1
143	224	78	50	5	2
144	225	78	50	5	2
145	226	78	50	5	2
146	231	79	50	5	1
147	232	79	50	5	1
148	235	72	50	5	2
149	239	72	50	5	2
150	241	80	50	5	1
151	242	80	50	5	1
152	236	73	50	5	2
153	240	73	50	5	2
154	254	82	50	5	2
155	255	82	50	5	2
156	249	76	50	5	1
157	251	76	50	5	1
159	252	83	50	5	2
160	278	70	50	6	3
161	283	70	50	6	3
162	279	90	50	6	4
163	282	90	50	6	4
164	280	85	50	6	3
165	281	85	50	6	3
166	277	91	50	6	4
167	292	91	50	6	4
168	263	86	50	6	3
169	275	86	50	6	3
170	285	92	50	6	4
171	286	92	50	6	4
173	272	26	50	6	3
174	261	26	50	6	3
175	271	93	50	6	4
176	273	93	50	6	4
177	260	87	50	6	3
178	267	87	50	6	3
179	268	94	50	6	4
180	270	94	50	6	4
181	288	258	50	6	3
182	290	258	50	6	3
183	297	301	16	5	1
184	299	301	16	5	1
185	298	252	16	5	2
186	300	252	16	5	2
187	301	254	16	5	2
188	303	254	16	5	2
189	308	248	16	5	1
190	310	248	16	5	1
191	312	249	16	5	1
192	313	249	16	5	1
195	314	156	16	5	1
197	341	140	16	6	4
198	342	140	16	6	4
199	339	224	16	6	3
200	334	265	16	6	3
201	336	265	16	6	3
202	329	270	16	6	4
203	331	270	16	6	3
204	325	150	16	6	3
205	343	150	16	6	3
207	348	301	20	5	1
208	346	301	20	5	1
209	347	18	20	5	2
210	349	18	20	5	2
211	350	1480	20	5	1
212	354	1480	20	5	1
213	351	1481	20	5	2
214	357	1481	20	5	2
215	361	20	20	5	2
216	373	20	20	5	1
217	360	14	20	5	1
218	362	14	20	5	1
219	366	400	20	5	1
220	368	400	20	5	1
222	398	35	20	6	4
223	395	766	20	6	3
224	396	766	20	6	3
225	390	26	20	6	3
226	385	25	20	6	3
227	387	25	20	6	3
228	386	31	20	6	4
229	399	31	20	6	4
230	383	24	20	6	3
231	382	24	20	6	3
232	377	301	20	6	3
233	378	301	20	6	3
234	420	89	14	5	2
236	415	362	14	5	2
237	417	362	14	5	2
238	412	358	14	5	1
239	414	358	14	5	1
240	406	681	14	5	2
241	408	681	14	5	2
242	405	354	14	5	1
243	402	252	14	5	2
244	403	252	14	5	2
245	449	35	14	6	4
246	445	766	14	6	3
247	447	766	14	6	3
248	440	370	14	6	4
249	442	370	14	6	4
250	439	365	14	6	3
251	438	365	14	6	3
252	425	301	14	6	3
253	426	301	14	6	3
254	428	363	14	6	4
255	430	363	14	6	4
256	432	721	14	6	4
257	433	721	14	6	4
258	434	364	14	6	3
259	477	258	15	5	2
260	478	258	15	5	2
261	473	401	15	5	2
262	475	401	15	5	2
263	466	682	15	5	2
264	468	682	15	5	2
265	463	676	15	5	1
266	465	676	15	5	1
267	460	681	15	5	2
268	462	681	15	5	2
270	458	18	15	5	2
271	453	161	15	5	1
272	455	161	15	5	1
273	504	274	15	6	4
274	505	274	15	6	4
275	501	912	15	6	3
276	496	1496	15	6	4
277	498	1496	15	6	4
278	491	406	15	6	3
279	493	406	15	6	3
280	489	721	15	6	4
281	490	721	15	6	4
282	485	759	15	6	3
283	487	759	15	6	3
284	481	260	15	6	3
285	483	260	15	6	3
286	532	1311	1	5	2
287	533	1311	1	5	2
288	534	1311	1	5	2
289	527	1498	1	5	2
290	528	1498	1	5	2
291	529	1498	1	5	2
292	530	1310	1	5	2
293	531	1310	1	5	2
294	523	1497	1	5	1
295	524	1497	1	5	1
297	525	1497	1	5	1
298	519	1303	1	5	1
299	520	1303	1	5	1
300	521	1303	1	5	1
301	522	1303	1	5	1
302	551	1502	1	6	4
303	552	1502	1	6	4
304	553	1502	1	6	4
305	549	1500	1	6	3
306	554	1500	1	6	3
307	555	1500	1	6	3
308	584	1399	12	5	2
309	585	1399	12	5	2
310	586	1399	12	5	2
311	577	1394	12	5	2
312	579	1394	12	5	2
313	568	1510	12	5	1
314	570	1510	12	5	1
315	571	1512	12	5	1
316	572	1512	12	5	1
317	574	1512	12	5	1
318	562	1508	12	5	1
319	563	1508	12	5	1
320	565	1509	12	5	1
321	566	1509	12	5	1
322	559	1506	12	5	1
323	561	1506	12	5	1
324	621	1449	12	6	4
325	623	1449	12	6	4
326	622	1449	12	6	4
327	616	1450	12	6	4
328	618	1450	12	6	4
329	613	1395	12	6	4
330	615	1395	12	6	4
331	614	1395	12	6	4
332	596	1451	12	6	4
333	594	1451	12	6	4
334	607	1400	12	6	3
335	609	1400	12	6	3
336	608	1400	12	6	3
337	605	1454	12	6	3
338	603	1454	12	6	3
339	600	1455	12	6	3
340	602	1455	12	6	3
341	599	1456	12	6	3
342	597	1456	12	6	3
343	591	1457	12	6	3
344	593	1457	12	6	3
345	588	1458	12	6	3
346	589	1458	12	6	3
347	633	1521	23	5	1
348	634	1521	23	5	1
349	627	60	23	5	2
350	628	60	23	5	2
351	630	68	23	5	2
352	631	68	23	5	2
353	632	68	23	5	2
354	671	1521	22	5	1
355	673	1521	22	5	1
356	674	314	22	5	2
357	660	60	22	5	2
358	662	60	22	5	2
359	656	301	22	5	1
360	657	301	22	5	1
361	686	45	22	6	3
362	688	45	22	6	3
363	681	1529	22	6	3
366	694	208	15	5	2
367	696	208	15	5	2
368	695	1371	15	5	1
369	693	1371	15	5	1
370	457	18	15	5	2
371	697	1536	15	5	1
372	698	1536	15	5	1
374	700	684	15	5	1
375	699	398	15	5	1
378	703	680	14	5	2
379	705	680	14	5	2
380	704	162	14	5	1
381	702	162	14	5	1
382	710	676	14	5	1
383	709	676	14	5	1
384	713	356	14	5	1
385	714	58	14	5	1
386	707	89	14	5	1
387	708	89	14	5	1
388	715	801	14	5	2
391	719	208	16	5	1
392	721	208	16	5	1
393	720	1371	16	5	1
394	718	1371	16	5	1
395	722	156	16	5	1
396	723	156	16	5	1
397	724	1539	16	5	1
398	318	1539	16	5	1
400	727	76	50	5	1
401	728	83	50	5	1
402	726	83	50	5	1
403	730	17	20	5	2
404	732	17	20	5	2
405	731	10	20	5	1
406	729	10	20	5	1
408	734	89	20	5	1
409	733	400	20	5	1
410	743	58	48	16	1
411	744	58	48	16	1
412	753	58	48	16	1
413	754	58	48	16	1
414	761	1536	48	16	1
415	762	1536	48	16	1
416	766	1536	48	16	1
417	767	1536	48	16	1
418	758	1555	48	16	2
419	756	1555	48	16	2
420	741	1554	48	16	1
421	745	1554	48	16	1
422	746	1554	48	16	1
423	764	1556	48	16	1
424	763	1556	48	16	1
425	751	1558	48	16	2
426	752	1558	48	16	2
427	742	1557	48	16	1
428	747	1557	48	16	1
430	765	178	48	16	2
431	760	178	48	16	2
\.


--
-- Name: groupe_matieres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('groupe_matieres_id_seq', 431, true);


--
-- Data for Name: heure_realise; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY heure_realise (id, id_personnel, nombre, date_heure_realisee, taux, jour, intervalle, date_enregistrement, id_classe, id_nniveau, id_matiere, annee_scolaire) FROM stdin;
2	24	14	2018-09-30	0	0	\N	2018-10-04	0	0	0	2018-2019
3	4	4	2018-09-17	2000	0	\N	2018-10-04	0	0	0	2018-2019
4	4	3	2018-09-20	2000	0	\N	2018-10-04	0	0	0	2018-2019
5	4	4	2018-09-18	2000	0	\N	2018-10-04	0	0	0	2018-2019
6	4	3	2018-09-27	2000	0	\N	2018-10-04	0	0	0	2018-2019
11	15	4	2018-09-17	2000	0	\N	2018-10-04	0	0	0	2018-2019
12	15	6	2018-09-19	2000	0	\N	2018-10-04	0	0	0	2018-2019
13	15	2	2018-09-20	2000	0	\N	2018-10-04	0	0	0	2018-2019
14	15	6	2018-09-24	2000	0	\N	2018-10-04	0	0	0	2018-2019
15	15	4	2018-09-26	2000	0	\N	2018-10-04	0	0	0	2018-2019
16	15	2	2018-09-27	2000	0	\N	2018-10-04	0	0	0	2018-2019
17	21	2	2018-09-26	0	0	\N	2018-10-04	0	0	0	2018-2019
18	6	2	2018-09-19	0	0	\N	2018-10-04	0	0	0	2018-2019
19	6	3	2018-09-24	0	0	\N	2018-10-04	0	0	0	2018-2019
20	6	4	2018-09-26	0	0	\N	2018-10-04	0	0	0	2018-2019
21	6	6	2018-09-29	0	0	\N	2018-10-04	0	0	0	2018-2019
22	25	3	2018-09-20	0	0	\N	2018-10-04	0	0	0	2018-2019
23	25	3	2018-09-25	0	0	\N	2018-10-04	0	0	0	2018-2019
24	25	6	2019-09-27	0	0	\N	2018-10-04	0	0	0	2018-2019
25	25	4	2018-09-29	0	0	\N	2018-10-04	0	0	0	2018-2019
26	5	6	2018-09-19	0	0	\N	2018-10-05	0	0	0	2018-2019
27	5	4	2018-09-20	0	0	\N	2018-10-05	0	0	0	2018-2019
28	5	6	2018-09-26	0	0	\N	2018-10-05	0	0	0	2018-2019
29	5	2	2018-09-27	0	0	\N	2018-10-05	0	0	0	2018-2019
30	5	2	2018-09-28	0	0	\N	2018-10-05	0	0	0	2018-2019
31	27	2	2018-09-18	0	0	\N	2018-10-05	0	0	0	2018-2019
32	27	4	2018-09-25	0	0	\N	2018-10-05	0	0	0	2018-2019
33	27	4	2018-09-25	0	0	\N	2018-10-05	0	0	0	2018-2019
34	3	4	2018-09-24	0	0	\N	2018-10-05	0	0	0	2018-2019
36	3	3	2018-09-25	0	0	\N	2018-10-05	0	0	0	2018-2019
\.


--
-- Name: heure_realise_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('heure_realise_id_seq', 41, true);


--
-- Data for Name: heures_prevues; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY heures_prevues (id, id_personnel, id_classe, id_nniveau, id_matiere, total, annee_scolaire, created_at, updated_at) FROM stdin;
1	3	2	5	517	0	2018-2019	2018-11-13 12:42:22	2018-11-13 12:42:22
2	17	2	5	532	0	2018-2019	2018-11-13 12:44:57	2018-11-13 12:44:57
3	24	2	5	763	0	2018-2019	2018-11-13 12:46:13	2018-11-13 12:46:13
4	13	2	5	526	0	2018-2019	2018-11-13 12:47:00	2018-11-13 12:47:00
5	11	2	5	545	0	2018-2019	2018-11-13 12:47:42	2018-11-13 12:47:42
\.


--
-- Name: heures_prevues_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('heures_prevues_id_seq', 5, true);


--
-- Data for Name: horaire; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY horaire (id, intervalle, type, nb_heures, ordre) FROM stdin;
11	8h-9h	0	1	1
12	9h-10h	0	1	2
13	10h-11h	0	1	3
14	11h-12h	0	1	4
15	12h-12h30	1	1	5
16	12h30-13h30	0	1	6
17	13h30-14h30	0	1	7
18	14h30-15h30	0	1	8
19	15h30-16h30	0	1	9
20	16h30-17h30	0	1	10
\.


--
-- Name: horaire_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('horaire_id_seq', 20, true);


--
-- Data for Name: inscription; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY inscription (id, date_inscription, id_eleve, annee_scolaire, id_classe, id_cycle, montant, id_nniveau, mode_paye, ref_versement, lettre) FROM stdin;
3	2018-09-17	3	2018-2019	20	1	40000	5	espece		\N
5	2018-09-19	29	2018-2019	16	1	40000	5	espece		\N
6	2018-09-19	38	2018-2019	1	1	40000	5	espece		\N
8	2018-09-19	40	2018-2019	20	1	40000	5	espece		\N
9	2018-09-19	26	2018-2019	1	1	40000	5	espece		\N
10	2018-09-19	21	2018-2019	1	1	40000	5	espece		\N
11	2018-09-19	34	2018-2019	2	1	40000	5	espece		\N
12	2018-09-19	9	2018-2019	20	1	40000	5	espece		\N
13	2018-09-19	8	2018-2019	14	1	40000	5	espece		\N
14	2018-09-19	11	2018-2019	20	1	40000	5	espece		\N
15	2018-09-19	16	2018-2019	14	1	40000	5	espece		\N
16	2018-09-19	17	2018-2019	1	1	40000	5	espece		\N
17	2018-09-19	22	2018-2019	1	1	40000	5	espece		\N
18	2018-09-19	41	2018-2019	1	1	40000	5	espece		\N
19	2018-09-19	7	2018-2019	22	1	40000	5	espece		\N
20	2018-09-19	6	2018-2019	1	1	40000	5	espece		\N
21	2018-09-19	23	2018-2019	1	1	40000	5	espece		\N
22	2018-09-19	20	2018-2019	1	1	40000	5	espece		\N
24	2018-09-19	42	2018-2019	20	1	40000	5	espece		\N
25	2018-09-19	10	2018-2019	14	1	40000	5	espece		\N
26	2018-09-19	36	2018-2019	1	1	40000	5	espece		\N
27	2018-09-20	5	2018-2019	1	1	40000	5	espece		\N
28	2018-09-20	4	2018-2019	14	1	40000	5	bancaire		\N
29	2018-09-20	12	2018-2019	15	1	40000	5	espece		\N
31	2018-09-20	14	2018-2019	1	1	40000	5	espece		\N
32	2018-09-20	19	2018-2019	1	1	40000	5	espece		\N
35	2018-09-20	27	2018-2019	8	1	40000	5	espece		\N
36	2018-09-20	30	2018-2019	20	1	40000	5	espece		\N
37	2018-09-20	32	2018-2019	22	1	40000	5	espece		\N
38	2018-09-20	25	2018-2019	1	1	40000	5	espece		\N
39	2018-09-20	33	2018-2019	22	1	40000	5	espece		\N
40	2018-09-20	35	2018-2019	1	1	40000	5	espece		\N
41	2018-09-20	24	2018-2019	8	1	40000	5	espece		\N
42	2018-09-20	43	2018-2019	1	1	40000	6	espece		\N
4	2018-09-19	37	2018-2019	12	1	40000	5	espece		\N
34	2018-09-20	28	2018-2019	14	1	40000	6	espece		\N
43	2018-09-20	44	2018-2019	1	1	40000	6	espece		\N
44	2018-09-20	45	2018-2019	12	1	40000	6	espece		\N
45	2018-09-20	46	2018-2019	1	1	40000	6	espece		\N
46	2018-09-20	47	2018-2019	1	1	40000	6	espece		\N
47	2018-09-20	48	2018-2019	16	1	40000	6	espece		\N
23	2018-09-19	31	2018-2019	2	1	40000	6	espece		\N
48	2018-09-21	49	2018-2019	1	1	40000	5	espece		\N
49	2018-09-24	50	2018-2019	15	1	40000	6	espece		\N
50	2018-09-24	51	2018-2019	14	1	40000	5	espece		\N
52	2018-09-24	53	2018-2019	22	1	40000	5	espece		\N
53	2018-09-24	54	2018-2019	14	1	40000	5	espece		\N
54	2018-09-24	55	2018-2019	12	1	40000	6	bancaire		\N
55	2018-09-24	56	2018-2019	39	7	50000	12	espece		\N
56	2018-09-25	57	2018-2019	14	1	40000	5	espece		\N
57	2018-09-25	58	2018-2019	1	1	40000	5	espece		\N
58	2018-09-27	59	2018-2019	1	1	40000	5	bancaire		\N
59	2018-09-27	60	2018-2019	14	1	40000	5	espece		\N
60	2018-09-27	61	2018-2019	2	1	40000	6	espece		\N
33	2018-09-20	18	2018-2019	1	1	40000	5	espece		\N
7	2018-09-19	39	2018-2019	1	1	40000	5	espece		\N
30	2018-09-20	15	2018-2019	1	1	40000	5	espece		\N
62	2018-09-28	63	2018-2019	1	1	40000	5	espece		\N
63	2018-09-28	64	2018-2019	1	1	40000	6	espece		\N
64	2018-10-01	65	2018-2019	1	1	40000	6	espece		\N
65	2018-10-01	67	2018-2019	15	1	40000	5	espece		\N
66	2018-10-01	66	2018-2019	1	1	40000	6	espece		\N
68	2018-10-01	69	2018-2019	1	1	40000	5	espece		\N
70	2018-10-01	71	2018-2019	1	1	40000	5	espece		\N
71	2018-10-01	72	2018-2019	1	1	40000	5	espece		\N
72	2018-10-02	73	2018-2019	1	1	40000	5	espece		\N
73	2018-10-02	74	2018-2019	22	1	40000	5	espece		\N
74	2018-10-02	75	2018-2019	2	1	40000	6	espece		\N
51	2018-09-24	52	2018-2019	2	1	40000	5	espece		\N
76	2018-10-03	77	2018-2019	20	1	40000	5	espece		\N
77	2018-10-03	78	2018-2019	8	1	40000	5	espece		\N
78	2018-10-03	79	2018-2019	1	1	40000	5	espece	40000	\N
79	2018-10-03	80	2018-2019	20	1	40000	5	bancaire		\N
89	2018-10-06	90	2018-2019	20	1	40000	5	espece		\N
80	2018-10-03	81	2018-2019	1	1	40000	5	espece		\N
81	2018-10-03	82	2018-2019	14	1	40000	6	espece		\N
82	2018-10-04	83	2018-2019	2	1	40000	5	espece		\N
83	2018-10-04	84	2018-2019	22	1	40000	5	espece		\N
84	2018-10-04	85	2018-2019	12	1	40000	6	espece		\N
85	2018-10-04	86	2018-2019	14	7	50000	12	espece		\N
86	2018-10-05	87	2018-2019	1	1	40000	6	espece		\N
87	2018-10-06	88	2018-2019	2	1	40000	6	espece		\N
88	2018-10-06	89	2018-2019	2	1	40000	6	espece		\N
90	2018-10-08	91	2018-2019	1	1	40000	5	espece		\N
91	2018-10-08	92	2018-2019	20	1	40000	5	espece		\N
92	2018-10-09	93	2018-2019	8	1	40000	5	espece		\N
93	2018-10-09	94	2018-2019	50	1	40000	6	espece		\N
94	2018-10-09	96	2018-2019	14	1	40000	5	espece		\N
96	2018-10-09	98	2018-2019	14	1	40000	6	espece		\N
97	2018-10-09	99	2018-2019	15	1	40000	6	espece		\N
98	2018-10-09	100	2018-2019	2	1	40000	6	espece		\N
67	2018-10-01	68	2018-2019	1	1	40000	6	espece		\N
99	2018-10-09	101	2018-2019	14	1	40000	5	espece		\N
100	2018-10-09	102	2018-2019	14	1	40000	6	espece		\N
75	2018-10-02	76	2018-2019	48	1	40000	6	espece		\N
101	2018-10-09	95	2018-2019	14	1	40000	6	espece		\N
102	2018-10-09	62	2018-2019	1	1	40000	5	espece		\N
103	2018-10-11	103	2018-2019	15	1	40000	6	espece		\N
104	2018-10-11	104	2018-2019	51	3	40000	7	espece		\N
105	2018-10-11	105	2018-2019	14	1	40000	5	espece		\N
106	2018-10-12	106	2018-2019	15	1	40000	6	espece		\N
108	2018-10-12	108	2018-2019	1	1	40000	6	espece		\N
109	2018-10-12	109	2018-2019	12	1	40000	6	espece		\N
110	2018-10-15	110	2018-2019	14	1	40000	6	espece		\N
111	2018-10-15	111	2018-2019	14	1	40000	5	espece		\N
112	2018-10-15	112	2018-2019	20	1	40000	6	espece		\N
113	2018-10-15	113	2018-2019	12	1	40000	6	espece		\N
114	2018-10-15	114	2018-2019	20	1	40000	6	bancaire		\N
115	2018-10-15	115	2018-2019	15	1	40000	6	espece		\N
116	2018-10-15	116	2018-2019	51	3	40000	7	espece		\N
117	2018-10-15	117	2018-2019	14	1	40000	6	espece		\N
118	2018-10-15	118	2018-2019	51	3	40000	7	espece		\N
119	2018-10-15	119	2018-2019	50	1	40000	6	espece		\N
120	2018-10-15	120	2018-2019	16	1	40000	6	espece		\N
121	2018-10-15	121	2018-2019	51	3	40000	8	espece		\N
122	2018-10-16	122	2018-2019	14	1	40000	5	espece		\N
123	2018-10-16	123	2018-2019	16	1	40000	5	espece		\N
124	2018-10-17	124	2018-2019	2	1	40000	6	espece		\N
125	2018-10-17	125	2018-2019	48	1	40000	6	espece		\N
126	2018-10-17	126	2018-2019	1	1	40000	6	espece		\N
127	2018-10-17	127	2018-2019	15	1	40000	6	espece		\N
128	2018-10-17	129	2018-2019	20	1	40000	6	espece		\N
129	2018-10-17	131	2018-2019	20	1	40000	6	espece		\N
130	2018-10-17	132	2018-2019	30	7	50000	12	espece		\N
131	2018-10-18	133	2018-2019	20	1	40000	6	espece		\N
132	2018-10-18	134	2018-2019	22	1	40000	5	espece		\N
133	2018-10-18	135	2018-2019	24	7	50000	12	espece		\N
134	2018-10-18	136	2018-2019	1	1	40000	6	espece		\N
135	2018-10-18	137	2018-2019	14	1	40000	6	espece		\N
136	2018-10-18	138	2018-2019	48	1	40000	6	espece		\N
137	2018-10-20	139	2018-2019	48	1	40000	6	espece		\N
138	2018-10-20	140	2018-2019	1	1	40000	6	espece		\N
139	2018-10-20	141	2018-2019	14	1	40000	5	espece		\N
140	2018-10-20	142	2018-2019	14	1	40000	5	espece		\N
141	2018-10-22	143	2018-2019	14	1	40000	6	espece		\N
142	2018-10-22	144	2018-2019	50	1	40000	6	espece		\N
143	2018-10-22	145	2018-2019	51	1	40000	7	espece		\N
144	2018-10-22	146	2018-2019	2	1	40000	6	espece		\N
145	2018-10-22	147	2018-2019	1	1	40000	5	espece		\N
146	2018-10-22	148	2018-2019	1	1	40000	6	espece		\N
69	2018-10-01	70	2018-2019	12	1	40000	5	espece		\N
147	2018-10-23	149	2018-2019	48	1	40000	6	espece		\N
148	2018-10-24	150	2018-2019	1	1	40000	5	espece		\N
149	2018-10-24	151	2018-2019	1	1	40000	6	espece		\N
150	2018-10-24	152	2018-2019	1	1	40000	5	espece		\N
151	2018-10-24	153	2018-2019	1	1	40000	6	espece		\N
152	2018-10-24	154	2018-2019	2	1	40000	6	espece		\N
153	2018-10-24	155	2018-2019	1	1	40000	6	espece		\N
154	2018-10-25	156	2018-2019	1	1	40000	6	espece		\N
155	2018-10-25	157	2018-2019	20	1	40000	6	espece		\N
156	2018-10-25	158	2018-2019	20	1	40000	6	espece		\N
158	2018-10-26	160	2018-2019	14	1	40000	6	espece		\N
159	2018-10-27	161	2018-2019	1	1	40000	6	espece		\N
160	2018-10-29	162	2018-2019	14	1	40000	6	espece		\N
162	2018-10-29	164	2018-2019	14	1	40000	6	espece		\N
163	2018-10-29	165	2018-2019	48	1	40000	6	espece		\N
164	2018-10-29	166	2018-2019	51	3	40000	7	espece		\N
165	2018-10-29	167	2018-2019	30	7	50000	12	espece		\N
166	2018-10-29	168	2018-2019	30	7	50000	12	espece		\N
167	2018-10-30	169	2018-2019	1	1	40000	5	espece		\N
168	2018-10-30	170	2018-2019	50	1	40000	6	espece		\N
169	2018-10-30	171	2018-2019	1	1	40000	6	espece		\N
170	2018-10-31	172	2018-2019	14	1	40000	6	espece		\N
171	2018-11-01	173	2018-2019	1	1	40000	6	espece		\N
173	2018-11-02	175	2018-2019	2	1	40000	6	espece		\N
174	2018-11-02	176	2018-2019	1	1	40000	6	espece		\N
175	2018-11-08	177	2017-2018	2	1	40000	5	bancaire		quarante mille franc cfa
176	2018-11-09	120	2017-2018	16	1	50000	5	espece		CINQUANTE MILLE FRANC
177	2018-11-09	178	2017-2018	16	1	50000	5	espece		CINQUANTE MILLE FRANC
178	2018-11-09	179	2017-2018	16	1	40000	5	espece		CINQUANTE MILLE FRANC
179	2018-11-09	48	2017-2018	16	1	40000	5	espece		QUARANTE MILLE FRANC
180	2018-11-09	180	2017-2018	16	1	40000	5	bancaire		QUARANTE MILLE FRANC
181	2018-11-09	181	2017-2018	36	1	40000	5	espece		QUARANTE MILLE FRANC
182	2018-11-09	55	2017-2018	12	1	40000	5	espece		QUARANTE MILLE FRANC
183	2018-11-09	113	2017-2018	12	1	40000	5	espece		QUARANTE MILLE FRANC
184	2018-11-09	45	2017-2018	12	1	40000	5	espece		QUARANTE MILLE FRANC
185	2018-11-09	85	2017-2018	12	1	40000	5	espece		QUARANTE MILLE FRANC
186	2018-11-09	109	2017-2018	12	1	40000	5	espece		QUARANTE MILLE FRANC
187	2018-11-09	182	2017-2018	23	1	40000	6	bancaire		QUARANTE MILLE FRANC
188	2018-11-10	183	2017-2018	23	1	40000	6	espece		QUARANTE MILLE FRANC
189	2018-11-10	184	2017-2018	23	1	40000	6	espece		QUARANTE MILLE FRANC
190	2018-11-10	185	2017-2018	23	1	40000	6	bancaire		QUARANTE MILLE FRANC
191	2018-11-10	186	2017-2018	23	1	40000	6	bancaire		QUARANTE MILLE FRANC
157	2018-10-26	159	2018-2019	48	1	40000	6	espece		\N
172	2018-11-01	174	2018-2019	48	1	40000	6	espece		\N
192	2018-11-10	187	2018-2019	15	1	40000	6	espece		quarante mille fcfa
193	2018-11-10	188	2018-2019	1	1	40000	6	espece		quarante mille fcfa
194	2018-11-10	189	2018-2019	2	1	40000	6	espece		quarante mille fcfa
195	2018-11-10	190	2018-2019	1	1	40000	6	espece		quarante mille fcfa
196	2018-11-10	191	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
197	2018-11-10	102	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
199	2018-11-10	95	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
200	2018-11-10	192	2018-2019	2	1	40000	6	espece		quarante mille fcfa
201	2018-11-10	193	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
202	2018-11-10	162	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
203	2018-11-10	172	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
205	2018-11-10	110	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
207	2018-11-10	98	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
208	2018-11-10	195	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
209	2018-11-10	164	2017-2018	14	1	50000	5	espece		QUARANTE MILLE FRANC
210	2018-11-10	101	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
211	2018-11-10	117	2017-2018	14	1	40000	5	bancaire		QUARANTE MILLE FRANC
212	2018-11-10	196	2018-2019	15	1	40000	5	espece		quarante mille fcfa
213	2018-11-10	197	2018-2019	1	7	50000	12	espece		CINQUANTE MILLE FCFA 
214	2018-11-10	198	2018-2019	24	6	100000	13	espece		CENT MILLE fcfa
215	2018-11-12	199	2017-2018	20	1	40000	5	espece		QUARANTE MILLE FRANC
216	2018-11-12	180	2018-2019	16	1	40000	6	espece		quarante mille fcfa
217	2018-11-12	200	2018-2019	48	1	40000	6	espece		quarante mille fcfa
218	2018-11-12	201	2018-2019	3	1	40000	5	espece		quarante mille fcfa
219	2018-11-12	202	2018-2019	50	1	40000	6	espece		quarante mille fcfa
220	2018-11-12	203	2018-2019	1	1	40000	5	espece		quarante mille fcfa
221	2018-11-12	204	2018-2019	14	1	40000	5	espece		quarante mille fcfa
222	2018-11-12	206	2018-2019	1	1	40000	5	espece		quarante mille fcfa
223	2018-11-12	205	2018-2019	38	1	50000	12	espece		CINQUANTE  MILLE FCFA
224	2018-11-12	207	2018-2019	12	1	40000	6	espece		quarante mille fcfa
225	2018-11-12	208	2018-2019	1	1	40000	6	espece		quarante mille fcfa
226	2018-11-13	50	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
227	2018-11-13	103	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
228	2018-11-13	106	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
229	2018-11-13	99	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
230	2018-11-13	187	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
231	2018-11-13	127	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
232	2018-11-13	209	2017-2018	15	1	40000	5	bancaire		QUARANTE MILLE FRANC
233	2018-11-13	115	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
234	2018-11-13	210	2017-2018	15	1	40000	5	espece		QUARANTE MILLE FRANC
235	2018-11-13	211	2018-2019	12	1	40000	5	espece		quarante mille fcfa
236	2018-11-14	212	2018-2019	20	1	40000	5	espece		quarante mille fcfa
198	2018-11-10	137	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
204	2018-11-10	143	2017-2018	14	1	40000	5	espece		QUARANTE MILLE FRANC
238	2018-11-15	2	2018-2019	6	1	40000	5	espece		quarante mille FCFA
239	2018-11-15	194	2017-2018	14	1	40000	5	bancaire		quarante mille FCFA
240	2018-11-15	144	2017-2018	50	1	40000	5	espece		quarante mille FCFA
241	2018-11-15	202	2017-2018	50	1	40000	5	espece		quarante mille FCFA
242	2018-11-15	119	2017-2018	50	1	40000	5	bancaire		quarante mille FCFA
243	2018-11-15	94	2017-2018	50	1	40000	5	espece		quarante mille FCFA
244	2018-11-15	214	2017-2018	50	1	40000	5	espece		quarante mille FCFA
245	2018-11-16	129	2017-2018	20	1	40000	5	espece		quarante mille FCFA
246	2018-11-16	133	2017-2018	20	1	40000	5	espece		quarante mille FCFA
247	2018-11-16	158	2017-2018	20	1	40000	5	espece		quarante mille FCFA
248	2018-11-16	131	2017-2018	20	1	40000	5	espece		quarante mille FCFA
249	2018-11-16	114	2017-2018	20	1	40000	5	espece		quarante mille FCFA
250	2018-11-16	216	2017-2018	20	1	40000	5	espece		quarante mille FCFA
251	2018-11-16	217	2017-2018	20	1	40000	5	espece		quarante mille FCFA
252	2018-11-19	219	2018-2019	51	3	40000	8	espece		quarante mille fcfa
254	2018-11-19	165	2017-2018	48	1	40000	16	espece		quarante mille FCFA
255	2018-11-19	125	2017-2018	48	1	40000	16	espece		quarante mille FCFA
256	2018-11-19	76	2017-2018	48	1	40000	16	espece		quarante mille FCFA
257	2018-11-19	174	2017-2018	48	1	40000	16	espece		quarante mille FCFA
258	2018-11-19	138	2017-2018	48	1	40000	16	bancaire		quarante mille FCFA
259	2018-11-19	149	2017-2018	48	1	40000	16	bancaire		quarante mille FCFA
260	2018-11-19	139	2017-2018	48	1	40000	16	espece		quarante mille FCFA
261	2018-11-19	200	2017-2018	48	1	40000	16	espece		quarante mille FCFA
262	2018-11-19	221	2017-2018	48	1	40000	16	espece		quarante mille FCFA
263	2018-11-19	222	2017-2018	48	1	40000	16	espece		quarante mille FCFA
264	2018-11-19	223	2017-2018	48	1	40000	16	espece		quarante mille FCFA
265	2018-11-19	224	2017-2018	48	1	40000	16	espece		quarante mille FCFA
266	2018-11-19	225	2018-2019	51	3	40000	7	espece		quarante mille fcfa
267	2018-11-20	218	2018-2019	14	1	40000	5	espece		quarante mille fcfa
237	2018-11-14	213	2018-2019	20	1	40000	5	espece		quarante mille fcfa
268	2018-11-20	226	2018-2019	38	7	50000	12	espece		CINQUANTE MILLE FCFA 
269	2018-11-20	227	2018-2019	8	7	50000	12	espece		CINQUANTE  MILLE FCFA
253	2018-11-19	214	2018-2019	50	1	40000	6	espece		quarante mille fcfa
\.


--
-- Name: inscription_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('inscription_id_seq', 269, true);


--
-- Data for Name: matiere; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY matiere (id, groupe, nom, nom_anglais) FROM stdin;
1	0	français	french
2	0	Expression anglaise 	english
4	0	système d'exploitation	
5	0	mathématique financière I	
6	0	mathématique financière II	
7	0	Informatique générale I	
8	0	Informatique générale II	
10	0	Mathématique I	
11	0	Physique et chimie	
12	0	Electricité générale et Circuits électriques	
13	0	Machine Electrique I	
14	0	Electronique et Automatisme	
15	0	Technologie de Construction	
16	0	Expression écrite et Formation bilingue	
17	0	Mathématiques II	
18	0	Physique et informatique	
19	0	Machines électriques et techniques de mesures	
20	0	Electronique et traitement du signal	
21	0	CAO en Génie électrique	
22	0	Création d'entreprise et éducation civique et éthique	
23	0	Mathématiques III	
24	0	Productions et schémas	
25	0	Asservissements et communication des données	
26	0	Electronique III	
27	0	TP machines électrique I	
28	0	Comptabilité et droit du travail	
29	0	Mathématiques IV	
30	0	Informatique II	
31	0	Maintenances et réseaux industriels	
32	0	Installations électriques et appareillages	
33	0	TP Machines électrique II	
34	0	Stage professionnel	
35	0	Economie	
36	0	économie générale	
37	0	économie des organisations	
38	0	DENREES ALIMENTAIRES	
39	0	COMPTABILITE GENERALE	
40	0	TECHNIQUE PASTORALE	
41	0	TECHNIQUE HALIEUTIQUES	
42	0	TECHNIQUES DE COMMUNICATION	
43	0	METHODES DE TRANSFORMATION ET DE CONDITIONNEMENT	
44	0	TECHNIES AGRICOLES	
45	0	ECONOMIE ET GESTION DES ENTREPRISES	
46	0	MARCHES ET FILIÈRES DES PRODUITS AGRICOLES 	
47	0	SOCIOLOGIES RURALE ET VULGARISATION DES TECHNIQUES DE COMMUNICATION	
48	0	DÉVELOPPEMENT COMMUNAUTAIRE ET ORGANISATION RURALE	
49	0	PROCÉDÉS DE TRANSFORMATION DES PRODUITS	
50	0	INFORMATIQUE ET MULTIMÉDIA	
51	0	CRÉATION D'ENTREPRISE	
52	0	MATHEMATIQUES 	
53	0	ENVIRONNEMENT SOCIO-ÉCONOMIQUE DE L'ENTREPRISE AGROPASTORALE	
54	0	POLITIQUES COMMERCIALES	
55	0	AGRONOMIE GENERALE	
56	0	MARKETING AGRICOLE	
57	0	EDUCATION CIVIQUE ET ETHIQUE	
58	0	FORMATION BILINGUE 	
59	0	ACTIVITÉS AGROPASTORALES ET ENJEUX ENVIRONNEMENTAUX	
60	0	STATISTIQUES ET PROBABILITÉS 	
61	0	GESTION DES STOCKS	
62	0	CHAINE DE VALEURS DES PRODUITS AGRICOLES	
63	0	CHAINE DE VALEURS DES PRODUITS PASTORAUX	
64	0	CHAINE DE VALEUR DES PRODUITS HALIEUTIQUES 	
65	0	MERCATIQUE	
66	0	MARCHES ET FILIÈRES AGRICOLES	
67	0	INITIATION AU DROIT	
68	0	CHAINE DE VALEURS DES PRODUIT AGROPASTORAUX	
69	0	EDUCATION CIVIQUE ET ETHIQUE /FORMATION BILINGUE	
71	0	ALGORITHME ET PHYSIQUE	
72	0	ÉLECTRICITÉ II	
73	0	ÉLECTRONIQUE II	
74	0	RÉSEAUX ET TÉLÉINFORMATIQUES	
75	0	DOMOTIQUE I	
76	0	ANGLAIS ET COMPTABILITÉ GÉNÉRALE	
77	0	OUTILS SCIENTIFIQUES DE BASE I 	
78	0	PHYSIQUE ET INFORMATIQUE DE BASE	
79	0	ÉLECTRICITÉ I	
80	0	ÉLECTRONIQUE I	
81	0	TELECOMMUNICATIONS	
82	0	MESURES ET INSTRUMENTATIONS	
83	0	EOE ET TECHNIQUES D'EXPRESSION	
84	0	OUTILS SCIENTIFIQUE DE BASE II	
70	0	OUTILS SCIENTIFIQUE DE BASE III	
85	0	Physique et informatique III	
86	0	ÉLECTRICITÉ III	
87	0	AUTOMATISME ET ELECTRONIQUE DE PUISSANCE	
88	0	COMMANDE DE PROCESSUS INDUSTRIELS I	
89	0	CREATION D'ENTREPRISE ET EDUCATION CIVIQUE ET ETHIQUE	
90	0	OUTILS SCIENTIFIQUES DE BASE IV	
91	0	PHYSIQUE ET INFORMATIQUE IV	
92	0	INSTALLATION ET DOMOTIQUE	
93	0	PÉRIPHÉRIQUES ET MICROPROCESSEURS	
94	0	AUTOMATISME ET COMMANDES/REGULATIONS	
95	0	ECONOMIE GENERALE	
96	0	ANALYSE MATHEMATIQUE II	
97	0	ALGEBRE LINEAIRE II	
99	0	ANALYSE LINEAIRE I	
100	0	ALGORITHME ET PROGRAMMATION	
101	0	PHYSIQUES GENERALES II	
102	0	PHYSIQUES GENERALES I	
103	0	INITIATION A L'INFORMATIQUE	
104	0	ARCHITECTURE DES ORDINATEURS	
105	0	ÉLECTROTECHNIQUE I	
106	0	ÉLECTRONIQUE DE PUISSANCE I	
107	0	SCHÉMAS ÉLECTRIQUE	
108	0	CIRCUIT ÉLECTRIQUE	
109	0	ELECTRONIQUE  DE BASE II	
110	0	CIRCUITS LOGIQUES II	
111	0	ELECTRONIQUE DE BASE I	
112	0	ELECTRONIQUE GRAND PUBLIC	
113	0	CIRCUITS LOGIQUES I	
114	0	METRLOGIE 	
115	0	EXPRESSION ORALE	
116	0	ECONOMIE ET ORGANISATION DES ENTREPRISES	
117	0	TECHNIQUES D'EXPRESSION	
118	0	PROBABILITES	
120	0	ANALYSE MATHÉMATIQUE IV	
121	0	STATISTIQUE 	
122	0	SYSTÈME D'EXPLOITATION I	
123	0	PHYSIQUE DES CAPTEURS	
124	0	BASE DE DONNEES	
126	0	ELECTROTECHNIQUE II	
127	0	RÉGULATION ET ASSERVISSEMENTS	
128	0	INSTALLATION ET CONFIGURATION	
129	0	DOMOTIQUE II	
130	0	ELECTRONIQUE DES FONCTIONS	
131	0	MICROPROCESSEUR I	
132	0	PERIPHERIQUES D'E/S	
133	0	MICROPROCESSEUR II	
134	0	AUTOMATISME INDUSTRIEL	
135	0	electronique de puissance II	
136	0	AUTOMATISME INDUSTRIEL II	
137	0	COMMANDE DE PROCESSUS INDUSTRIELS II	
138	0	RÉGULATION ET ASSERVISSEMENTS II	
139	0	CRÉATION D'ENTREPRISE 	
140	0	COMPTABILITE ET ECONOMIE	
141	0	CONSERVATION FRIGORIFIQUE	
142	0	ELECTRICITE ET TECHNOLOGIE DES INSTALLATIONS FRIGORIFIQUES	
143	0	MATERIAUX	
144	0	MATHEMATIQUES IV	
145	0	METODOLOGIE DE REDACTION DU RDS	
146	0	PRODUCTION DU FROID	
147	0	ENTREPÔT FRIGORIFIQUE ET INSTALLATION	
148	0	TRAVAUX PRATIQUES 	
149	0	CAO ET DAO	
150	0	Physique et chimie II	
151	0	MATHEMATIQUES III	
152	0	HYDRAULIQUE ET SCHÉMAS ÉLECTRIQUES APPLIQUES AU FROID	
154	0	RÉGULATION ET GESTION D'ÉNERGIE	
155	0	CLIMATISATION 	
156	0	EXPRESSION ECRITE ET ENVIRONNEMENT JURIDIQUE	
157	0	MECANIQUE DES FLUIDES ET TECHNIQUE DE MESURE	
158	0	TRANSFERTS THERMIQUE	
159	0	THERMODYNAMIQUE	
160	0	ELECTRONIQUE ET ELECTROTECHNIQUE	
161	0	Physique et chimie I	
162	0	MATHEMATIQUES I	
163	0	Physique I	
164	0	CHIMIE	
165	0	Physique II	
166	0	Informatique I	
153	0	AUTOMATISME FRIGORIFIQUE	
167	0	MÉCANIQUE DES FLUIDES 	
168	0	TECHNIQUE DE MESURE	
169	0	HYDRAULIQUE	
170	0	SCHÉMAS ÉLECTRIQUES APPLIQUES AU FROID	
171	0	EXPRESSION ECRITE	
172	0	ENVIRONNEMENT JURIDIQUE	
173	0	CRÉATION D'ENTREPRISE 	
174	0	Physique III	
176	0	ÉLECTRICITÉ APPLIQUÉE AU FROID	
177	0	TECHNOLOGIE DES INSTALLATIONS FRIGORIFIQUES	
178	0	comptabilité 	
179	0	ENVIRONNEMENT JURIDIQUE ET ÉDUCATION CIVIQUE	
180	0	TRAVAUX DE SYNTHÈSE TE ADMINISTRATION RÉSEAUX II	
181	0	GESTION DES OPÉRATIONS DE SERVICES II	
182	0	GESTION  DE LA TRANSITION DES COMPOSANTS LOGICIELS II	
183	0	OUTILS DE GESTION IV	
184	0	TECHNIQUES DE QUANTITATIVES DE GESTION IV	
185	0	ENVIRONNEMENT JURIDIQUE ET CRÉATION D'ENTREPRISE	
186	0	ADMINISTRATION SYSTÈME I ET II	
187	0	TRAVAUX DE SYNTHÈSE ET ADMINISTRATION RÉSEAUX I	
188	0	GESTION DES OPÉRATIONS DE SERVICES I	
189	0	GESTION DE LA TRANSITION DES COMPOSANTS LOGICIELS I	
190	0	OUTILS DE GESTION III	
191	0	TECHNIQUES QUANTITATIVES DES GESTION III	
192	0	TECHNIQUES QUANTITATIVES DE GESTION I	
193	0	OUTILS DE GESTION I	
194	0	ACCES UTILISATEURS I	
195	0	GESTION DES SUPPORTS DE SERVICE I	
196	0	RESEAUX I	
197	0	PROGRAMMATION I 	
198	0	FORMATION BILINGUE I ET ENVIRONNEMENT ÉCONOMIQUE I	
199	0	TECHNIQUES QUANTITATIVES DE GESTION II	
200	0	OUTILS DE GESTION II	
201	0	MÉTHODOLOGIE ET ACCÈS UTILISATEURS II	
202	0	GESTION DES SUPPORTS DE SERVICES II	
203	0	RÉSEAUX II	
204	0	PROGRAMMATION II	
205	0	FORMATION BILINGUE II ET ENVIRONNEMENT ÉCONOMIQUE II	
206	0	MATHÉMATIQUES GÉNÉRALES I	
207	0	STATISTIQUES ET PROBABILITÉS I	
208	0	MATHEMATIQUES  GENERALES II	
209	0	STATISTIQUES ET PROBABILITÉS II	
210	0	PROBABILITES I	
211	0	RECHERCHE OPÉRATIONNELLE I	
212	0	PROBABILITÉS II	
213	0	RECHERCHE OPÉRATIONNELLE II	
214	0	COMPTABILITÉ GÉNÉRALE I	
215	0	BASES DE LA PROGRAMMATION ET  ALGORITHMIQUE APPLIQUÉE I	
216	0	COMPTABILITE GENERALE II	
217	0	BASES DE LE PROGRAMMATION ET ALGORITHMIQUE APPLIQUÉE II	
218	0	ANALYSE JURIDIQUE DES  SERVICES INFORMATIQUES I	
219	0	COMPTABILITÉ ANALYTIQUE ET GESTION BUDGÉTAIRE I	
220	0	ANALYSE JURIDIQUE DES  SERVICES INFORMATIQUES II	
221	0	COMPTABILITÉ ANALYTIQUE ET GESTION BUDGÉTAIRE II	
222	0	SUPPORT SYSTÈME DES ACCÈS UTILISATEURS I	
223	0	SUPPORT RÉSEAU DES ACCÈS UTILISATEURS I	
224	0	METHODOLOGIE DE LA REDACDTION DU RAPPORT DE STAGE	
225	0	SUPPORT SYSTÈME DES ACCÈS UTILISATEURS II	
226	0	SUPPORT RÉSEAU DES ACCÈS UTILISATEURS II	
227	0	EXPLOITATION D'UN SCHÉMA DE DONNÉES I	
228	0	SUPPORT DES SERVICES ET DES SERVEURS II 	
229	0	EXPLOITATION D'UN SCHÉMA DE DONNÉES II	
230	0	INTRODUCTION A LA MAINTENANCE DES ACCÈS UTILISATEURS I	
231	0	DÉVELOPPEMENT D'APPLICATIONS I	
232	0	INTRODUCTION A LA MAINTENANCE DES ACCÈS UTILISATEURS II	
233	0	DÉVELOPPEMENT D'APPLICATIONS II	
234	0	CONCEPTION DES INFRASTRUCTURES RESEAUX I	
235	0	PROGRAMMATION OBJET I	
236	0	CONCEPTION DES INFRASTRUCTURES RESEAUX II	
237	0	PROGRAMMATION OBJET II	
238	0	EXPRESSION FRANÇAISE	
239	0	CONCEPTION ET ADAPTATION DE SOLUTION APPLICATIVES I	
240	0	RÉALISATION ET MAINTENANCE DE COMPOSANTS LOGICIELS I	
241	0	CONCEPTION ET ADAPTATION DE SOLUTION APPLICATIVES II	
242	0	RÉALISATION ET MAINTENANCE DE COMPOSANTS LOGICIELS II	
243	0	EXPLOITATION  DES SERVICES I	
244	0	Mathematiques I	
245	0	Physique et Chimie I	
246	0	Electrique-electronique I (EE I)	
247	0	Gestion et organisation de la maintenance I	
248	0	Controstruction mecanique I Liaisons mecaniques et hydroliques I	
249	0	Motor management et Automatisme	
250	0	Expression ecrite et Environnement juridique	
251	0	Mathematiques II	
252	0	Physique et informatique	
253	0	Motor management et Automatisme II	
254	0	Motor management et Automatisme II	
255	0	Liaisons mecaniques et hydrauliques II	
256	0	Gestion et organisation de la maintenance II	
257	0	Climatisation	
258	0	Creation d entrprise et Education civique et ethique	
259	0	Mathematiques III	
260	0	Physique et Chimie II	
261	0	Physique et Chimie II	
262	0	Construction mecanique II	
263	0	Electrique-electronique II (EE II)	
264	0	Liaisons mecaniques et hydrauliques III	
265	0	Gestion et organisation de la maintenance III et automatisme	
266	0	Methodologie de rdaction du RDS	
267	0	Mathematiques IV	
268	0	Informatique II	
269	0	TP EE + utilisation outils diagnostics	
270	0	Carrosserie et Motor management III	
271	0	Methodologie gamme de travail	
272	0	Stage academique	
273	0	Stage academique	
274	0	Comptabilite et Economie	
275	0	Physique I	
277	0	Physique II	
278	0	Informatique I	
279	0	Motor management  II	
280	0	 Automatisme II	
282	0	Construction mecanique I	
283	0	Construction mecanique I	
284	0	Liaisons mecaniques et hydrauliques I	
285	0	Motor management I	
286	0	 Automatisme I	
287	0	Expression ecrit	
288	0	Expression ecrite	
289	0	Evironnement juridique	
290	0	Creation d entreprise	
291	0	Education civique et ethique	
292	0	Physique III	
293	0	Base  QSHE	
294	0	Carrosserie 	
295	0	Motor management  III	
296	0	Gestion et organisation de la maintenance III	
297	0	Gestion et organisation de la maintenance III	
300	0	Economie	
301	0	Physique et Chimie 	
302	0	Physique et Chimie 	
304	0	Evironnement socio-economique de l entreprise agropostoral	
305	0	Politiques commerciales	
307	0	Marketing agricole	
308	0	Education civiaue et ethique /Formation bilingue	
309	0	Activites agropastorales et enjeux environnementaux	
310	0	Statistiques et probabilites	
311	0	Gestion et stocks	
312	0	Chaines et valeurs et produits agropastoraux	
313	0	Mercatique	
314	0	Marches et filieres agropastoraux I	
315	0	Marches et filieres agropastoraux I	
316	0	Initiation du Droit	
317	0	Denrees alimentaires	
318	0	Comptabilite generale	
319	0	Creation d entreprise	
320	0	Creation d entreprise	
321	0	Techniques de communication	
322	0	Methodes de transformation et de conditionnement	
323	0	Techniaues agricoles et Entreprises	
324	0	Marches et filieres agropastoraux II	
325	0	Sociologie rurale et vulgarisation des techniques de commercialisation	
326	0	Techniques commerciales	
327	0	CONCEPTION ET ADAPTATION D'UNE BASE DE DONNÉES I	
328	0	Hygienes et qualite des produits agropastoraux	
329	0	EXPLOITATION  DES SERVICES II	
330	0	Procedes de transformation des produits	
331	0	Procedes de transformation des produits	
332	0	CONCEPTION ET ADAPTATION D'UNE BASE DE DONNÉES II	
333	0	Stage professionnel	
334	0	Informatique  et multimedia	
335	0	TRAVAUX DE SYNTHÈSE  I	
336	0	Education civiaue et ethique 	
337	0	Formation bilingue	
338	0	INTÉGRATION ET ADAPTATION D'UN SERVICE I	
339	0	MAINTENANCE DES ACCÈS UTILISATEURS I	
340	0	TRAVAUX DE SYNTHÈSE  II	
341	0	INTÉGRATION ET ADAPTATION D'UN SERVICE II	
342	0	MAINTENANCE DES ACCÈS UTILISATEURS II	
343	0	ADMINISTRATION DES SYSTÈMES I 	
344	0	SUPERVISION DES RÉSEAUX I	
345	0	ADMINISTRATION DES SYSTÈMES II	
346	0	SUPERVISION DES RÉSEAUX II	
347	0	DROIT DES SOCIÉTÉS COMMERCIALES I	
348	0	DROIT CIVIL 	
349	0	CRÉATION D'ENTREPRISE	
350	0	Mat	
351	0	Mat	
352	0	Mat	
353	0	DROIT DES SOCIÉTÉS COMMERCIALES II	
354	0	Procedes  generaux de construction	
355	0	DROIT DU TRAVAIL 	
356	0	Dessin et technologie de batiment	
357	0	Organisation des chantiers	
358	0	Architecture et Resistance des materiaux	
359	0	Bureau d etude et projets	
360	0	Topographie I	
362	0	Geotechnique et Resistances des materaux II	
363	0	Equipements et Materiaux	
364	0	Resistance III	
365	0	Beton arme II et Dessin assiste par ordinateur	
366	0	Procedes et techniques de realisation	
367	0	Comptabilite et Droit du travail	
368	0	Bureau d etudeII et Dessin assiste par ordinateur	
369	0	Fondations et soutenements	
370	0	Topographie II et Essais	
371	0	Mecanique	
372	0	Physique II	
373	0	Projet beton arme	
374	0	Procedes  generaux de construction II	
375	0	Architecture 	
376	0	 Resistance des materiaux I	
377	0	Geotechnique et Resistances des materaux II	
378	0	Geotechnique 	
379	0	 Resistances des materaux II	
380	0	Entreprenariat	
381	0	Education civique et ethique 	
382	0	Base de qualite hygiene sante securite environnement (QHSSE)	
383	0	Equipements du batiment	
384	0	Materiaux de construction	
385	0	Etude de prix	
386	0	Estimation des couts en integrant	
387	0	Dessin assiste par ordinateur II	
390	0	Dessin assiste par ordinateur I	
391	0	Essaie en laboratoire	
392	0	Topographie II	
393	0	Comptabilite analytique	
394	0	Droit du travail	
395	0	Techniques d amenagements et equipements	
396	0	Route I et organisation des chantiers	
397	0	Route I et organisation des chantiers	
398	0	Introduction a l architecture	
399	0	Mecanique theorique	
400	0	Expression ecrite et formation bilingue	
401	0	Expression ecrite et formation bilingue	
402	0	Topographie I et beton arme I	
403	0	Resistance des materiaux I	
404	0	Creation d entreprise education et ethique	
405	0	Topographie II et materiaux	
406	0	Resistance des materiaux III et beton arme II	
407	0	Dessin assiste par ordinateur	
408	0	Voires .Reseaux et Divers	
409	0	Route II et essais	
411	0	Ouvrage d Art	
412	0	Ouvrage d Art	
413	0	Route I	
414	0	Communication orale	
415	0	Physique III	
416	0	Resistance des materiaux II	
417	0	Route II	
418	0	MATHÉMATIQUE ET INFORMATIQUE I	
419	0	TECHNIQUE QUANTITATIVES DE GESTION	
420	0	ENVIRONNEMENT MARKETING I	
422	0	OUTILS MARKETING I	
423	0	TECHNIQUES MARKETING I	
424	0	MATHÉMATIQUES ET INFORMATIQUE II	
429	0	MATHÉMATIQUES ET COMPTABILITÉ	
430	0	ENVIRONNEMENT MARKETING II	
431	0	MIX-MARKETING II 	
421	0	MIX-MARKETING I 	
432	0	OUTILS MARKETING II	
433	0	TECHNIQUES MARKETING II ET METHODOLOGIE	
434	0	TECHNIQUE QUANTITATIVES ET INFORMATIQUE I	
435	0	COMPTABILITE ET SYSTEME D'INFORMATION I	
436	0	MANAGEMENT MARKETING I	
437	0	ENVIRONNEMENT MARKETING III	
438	0	OUTILS MARKETING III ET IV	
439	0	TRAVAUX DE SYNTHÈSE ET POLITIQUE COMMERCIALE I	
440	0	ENVIRONNEMENT JURIDIQUE ET ÉDUCATION CIVIQUE I	
441	0	TECHNIQUE QUANTITATIVES ET INFORMATIQUE II 	
442	0	COMPTABILITE ET SYSTEME D'INFORMATION II	
443	0	MANAGEMENT MARKETING II 	
444	0	ENVIRONNEMENT MARKETING IV	
445	0	TRAVAUX DE SYNTHÈSE ET POLITIQUE COMMERCIALE II	
446	0	ENVIRONNEMENT JURIDIQUE ET ÉDUCATION CIVIQUE II	
447	0	MATHÉMATIQUES  GÉNÉRALES I	
448	0	MATHEMATIQUES FINANCIERS I	
449	0	STATISTIQUES	
450	0	MATHÉMATIQUES FINANCIÈRES II	
451	0	MARKETING FONDAMENTAL I	
452	0	MARKETING INTERNATIONAL I	
453	0	MARKETING FONDAMENTAL II	
454	0	MARKETING INTERNATIONAL II	
455	0	MARKETING OPÉRATIONNEL I	
456	0	MARKETING RELATIONNEL I	
457	0	MARKETING OPÉRATIONNEL II	
458	0	MARKETING RELATIONNEL II	
459	0	POLITIQUES DE PRODUIT	
460	0	POLITIQUES DE PRIX	
461	0	POLITIQUES DE DISTRIBUTION	
462	0	POLITIQUES DE COMMUNICATION	
463	0	ÉTUDE  DE MARCHE 	
464	0	GESTION COMMERCIALE I	
465	0	ÉTUDE DE MARCHE II	
466	0	GESTION COMMERCIALE II	
467	0	ETUDE DE MARCHE III	
468	0	GESTION COMMERCIALE III	
469	0	GESTION COMMERCIALE IV	
470	0	ETUDE DE MARCHE IV	
471	0	TECHNIQUE DE VENTE	
472	0	VEILLE CONCURRENTIELLE	
473	0	FORCE DE VENTE I	
474	0	TECHNIQUE DE NEGOCIATION COMMERCIALE	
475	0	FORCE DE VENTE II	
476	0	MÉTHODOLOGIE DE LA RÉDACTION DU RAPPORT DE STAGE	
477	0	PROBABILITÉS ET RECHERCHE OPÉRATIONNELLE I	
478	0	Informatique APPLIQUÉE I	
479	0	PROBABILITÉS ET RECHERCHE OPÉRATIONNELLE II	
480	0	Informatique APPLIQUÉE II	
481	0	COMPTABILITÉ ANALYTIQUE  ET GESTION BUDGÉTAIRE I	
482	0	SYSTÈME D'INFORMATION I	
483	0	SYSTÈME D'INFORMATION II	
484	0	MANAGEMENT DE L'ÉQUIPE COMMERCIALE I	
485	0	MERCHANDISING I	
486	0	MANAGEMENT DE L'ÉQUIPE COMMERCIALE II	
487	0	MERCHANDISING II	
488	0	POLITIQUES COMMERCIALE I	
489	0	PROSPECTION COMMERCIALE	
490	0	POLITIQUES COMMERCIALE II	
491	0	PROMOTION SUR LIEU DE VENTE	
492	0	TRAVAUX DE SYNTHÈSE II	
493	0	DROIT DE SOCIÉTÉS COMMERCIALES I	
494	0	ÉDUCATION CIVIQUE ET ÉTHIQUE  	
495	0	Mathématiques et Informatique I	
496	0	Mathématiques et Informatique II	
497	0	Mathématiques et Informatique III	
498	0	Mathématiques et Informatique IV	
499	0	Comptabilité Générale I	
500	0	Comptabilité Générale II	
501	0	Comptabilité Analytique I	
502	0	Comptabilité Analytique II	
503	0	Fiscalité I	
504	0	Méthodologie et Fiscalité II	
505	0	Introduction à l'Analyse Financière et Comptabilité à l'Ordinateur	
506	0	Formation bilingue I et Environnement	
507	0	Formation bilingue II et Environnement	
508	0	Introduction à l'Analyse Financière et Comptabilité à l'Ordinateur II	
509	0	Analyse Financière I	
511	0	Comptabilité des Sociétés I	
512	0	Gestion Prévisionnelle I et II	
513	0	Fiscalité et travaux de Synthèse  I	
514	0	Environnement Juridique et Ethique I	
515	0	Techniques Quantitatives IV	
516	0	Comptabilité des Sociétés II	
517	0	Mathématiques Générales I	
518	0	Informatique Générale I	
519	0	Mathématiques Générales II	
520	0	Mathématiques Générales III	
521	0	Mathématiques Financières III	
522	0	Informatique Appliquée I	
523	0	Mathématiques Financières IV	
524	0	Mathématiques Financières I	
525	0	Mathématiques Financières II	
526	0	Statistiques I	
527	0	Statistiques II	
528	0	Recherche Opérationnelle I	
529	0	Recherche Opérationnelle II	
530	0	Probabilité II	
531	0	Probabilité I	
532	0	Opérations Courantes I	
533	0	Opérations Spécifiques et Travaux de Fin d'exercice I	
534	0	Opérations Spécifiques et Travaux de Fin d'exercice II	
535	0	Opérations Courantes II	
536	0	Couts complets I	
537	0	Couts Complets II	
538	0	Couts Partiels et seuil de rentabilité I	
539	0	Couts Partiels et seuil de rentabilité II	
540	0	Introduction à la Fiscalité	
541	0	Méthodologie et Fiscalité II	
542	0	Méthodologie de Rédaction du rapport de stage 	
543	0	TVA et IRRP	
544	0	Introduction à l'analyse  financière et comptabilité à l'ordinateur I	
545	0	Introduction à l'Analyse financière I	
546	0	Comptabilité assistée par l'ordinateur I	
547	0	Introduction à l'analyse financière II	
548	0	Comptabilité assistée par l'ordinateur II	
549	0	Expression française	
550	0	Economie générale	
551	0	Formation bilingue I et Environnement économique II	
552	0	Economie et organisations des entreprises	
553	0	Constitution des sociétés I	
554	0	Constitution des sociétés II	
555	0	Dissolution des sociétés I	
556	0	Dissolution des sociétés II	
557	0	Analyse du compte de résultat I	
558	0	Analyse du bilan I	
559	0	Analyse du bilan II	
560	0	Analyse du compte de résultat II	
561	0	Gestion budgétaire I	
562	0	Gestion budgétaire II	
563	0	Seuil de Rentabilité et Cout Préétablis I	
564	0	Seuil de Rentabilité et Cout Préétablis II	
565	0	Fiscalité et travaux de Synthèse  II	
566	0	Travaux de Synthèse I	
567	0	Impots sur les sociétés	
568	0	Calcul du résultat fiscal et impots	
569	0	Droit du travail	
570	0	Droit civil	
571	0	EDUCATION CIVIQUE ET ETHIQUE	
572	0	Environnement juridique et éducation civique II	
573	0	Mathematiques et informatique I	
574	0	Techniques quanditative et gestion I	
575	0	Initiation fiscalo-comptable I	
576	0	Fondamentaux de l assurance I	
577	0	Fiscalite et AIARD I	
578	0	Theorie juridique et l assurance I	
579	0	Formation bilingue I et environnement economique I	
580	0	Mathematiques et informatique II	
581	0	Techniques quanditative et gestion II	
582	0	Initiation fiscalo-comptable II	
583	0	Initiation fiscalo-comptable II	
584	0	Fondamentaux de l assurance II	
585	0	Fondamentaux de l assurance II	
586	0	Fiscalite et AIARD II	
587	0	Fiscalite et AIARD II	
588	0	Fiscalite et AIARD II	
589	0	Fiscalite et AIARD II	
590	0	Fiscalite et AIARD II	
591	0	Fiscalite et AIARD II	
592	0	Fiscalite et AIARD II	
593	0	Fiscalite et AIARD II	
594	0	Fiscalite et AIARD II	
595	0	Fiscalite et AIARD II	
596	0	Methodologie et Theorie juridique et l assurance II	
597	0	Methodologie et Theorie juridique et l assurance II	
598	0	Methodologie et Theorie juridique et l assurance II	
599	0	Methodologie et Theorie juridique et l assurance II	
600	0	Methodologie et Theorie juridique et l assurance II	
601	0	Methodologie et Theorie juridique et l assurance II	
602	0	Methodologie et Theorie juridique et l assurance II	
603	0	Methodologie et Theorie juridique et l assurance II	
604	0	Methodologie et Theorie juridique et l assurance II	
605	0	Methodologie et Theorie juridique et l assurance II	
606	0	Methodologie et Theorie juridique et l assurance II	
607	0	Methodologie et Theorie juridique et l assurance II	
608	0	Methodologie et Theorie juridique et l assurance II	
609	0	Formation bilingue I et environnement economique II	
610	0	Formation bilingue I et environnement economique II	
611	0	Formation bilingue I et environnement economique II	
612	0	Formation bilingue I et environnement economique II	
613	0	Techniques quanditative et informatique	
614	0	Techniques quanditative et informatique	
615	0	Techniques quanditative et informatique	
616	0	Economie et TIC	
617	0	ec	
618	0	Economie et TIC	
619	0	Economie et TIC	
620	0	Pratique comptable et assurance I	
621	0	Pratique comptable et assurance I	
622	0	Pratique comptable et assurance I	
623	0	Pratique comptable et assurance I	
624	0	Pratique comptable et assurance I	
625	0	Pratique comptable et assurance I	
626	0	Pratique comptable et assurance I	
627	0	Pratique comptable et assurance I	
628	0	Pratique comptable et assurance I	
629	0	Pratique comptable et assurance I	
630	0	Pratique comptable et assurance I	
631	0	Pratique comptable et assurance I	
632	0	Pratique comptable et assurance I	
633	0	Pratique comptable et assurance I	
634	0	Pratique comptable et assurance I	
635	0	Pratique comptable et assurance I	
636	0	Pratique comptable et assurance I	
637	0	Pratique comptable et assurance I	
638	0	Pratique comptable et assurance I	
639	0	Pratique comptable et assurance I	
640	0	Pratique comptable et assurance I	
642	0	Comptabilite des intermediaire d assurance I	
643	0	Comptabilite des intermediaire d assurance I	
644	0	Comptabilite des intermediaire d assurance I	
645	0	Comptabilite des intermediaire d assurance I	
646	0	Comptabilite des intermediaire d assurance I	
648	0	Comptabilite des intermediaire d assurance I	
649	0	Comptabilite des intermediaire d assurance I	
650	0	Comptabilite des intermediaire d assurance I	
651	0	Comptabilite des intermediaire d assurance I	
652	0	Comptabilite des intermediaire d assurance I	
653	0	Comptabilite des intermediaire d assurance I	
654	0	Comptabilite des intermediaire d assurance I	
656	0	Comptabilite des intermediaire d assurance I	
657	0	Comptabilite des intermediaire d assurance I	
658	0	Comptabilite des intermediaire d assurance I	
659	0	Comptabilite des intermediaire d assurance I	
662	0	Comptabilite des intermediaire d assurance I	
663	0	Comptabilite des intermediaire d assurance I	
665	0	Comptabilite des intermediaire d assurance I	
666	0	Comptabilite des intermediaire d assurance I	
667	0	approche internationale et technique d"	
675	0	TECHNIQUE D'AMENAGENENTS ET EQUIPEMENTS	
676	0	ROUTE I ET ORGANISATION DES CHANTIERS 	
677	0	INTRODUCTION A L'ARCHITECTURE	
678	0	MECANIQUE  THÉORIQUE	
679	0	EXPRESSION ÉCRITE ET FORMATION BILINGUE	
680	0	MATHEMATIQUES II	
681	0	BUREAU D'ÉTUDES ET PROJETS	
682	0	TOPOGRAPHIE I ET BETON ARME I	
683	0	GEOTECHNIQUE	
684	0	RESISTANCE DES MATERIAUX I	
685	0	TOPOGRAPHIE IIN ET MATÉRIAUX 	
686	0	RÉSISTANCE DES MATÉRIAUX III ET BÉTON ARME II	
687	0	DESSIN ASSISTE PAR ORDINATEUR	
688	0	VOIRIES, RÉSEAUX ET DIVERS 	
689	0	COMPTABILITÉ ET DROIT DU TRAVAIL	
690	0	COMPTABILITÉ ET DROIT DU TRAVAIL	
691	0	Travaéux de synthese et assurance multimodal I	
692	0	environnement juridique et education civique I	
693	0	Techniques quanditative et informatique I	
694	0	Comptabilite et TIC	
695	0	Pratique comptable et assurance II	
696	0	Comptabilite des intermediaire d assurance II	
697	0	Travaéux de synthese et assurance multimodal II	
700	0	environnement juridique et education civique II	
721	0	BUREAU D'ÉTUDES II ET DESSIN ASSISTE PAR ORDINATEUR	
732	0	Informatique generale I	
733	0	Mathematiques generales II	
734	0	Mathematiques generales II	
735	0	Mathematiques generales II	
736	0	Mathematiques generales II	
737	0	Mathematiques generales II	
738	0	Mathematiques financiere I	
739	0	Mathematiques financiere I	
740	0	Statistiques I	
741	0	Mathematiques financiere II	
742	0	Statistiques II	
743	0	Comptabilite generale I	
744	0	Introduction a la fiscalité I	
745	0	Comptabilité generale I	
746	0	Comptabilité generale II	
747	0	Introduction a la fiscalité II	
748	0	Introduction a la gestion des assurances et des risques	
749	0	Introduction a l'assurance 	
750	0	Introduction a la gestion des assurances et des risques II	
751	0	Assurance automobile	
752	0	fiscalite des contrats d'assurance et des entreprises I	
753	0	assurance incendie risques divers I	
754	0	fiscalite des contrats d'assurance et des entreprises II	
755	0	assurance incendie risques divers II	
756	0	Droit du contrat d'assurance I	
757	0	Droit du contrat d'assurance I	
758	0	Cadre reglementaire et législation des entreprises d'assurance	
759	0	TOPOGRAPHIE II ET MATERIAUX 	
760	0	Methodologie de redaction du Rapport De Stage I	
761	0	Droit du contrat d'assurance II	
762	0	RESISTANCE DES MATERIAUX III ET BETON ARME II	
763	0	Expression francaise	
764	0	Economie generale	
765	0	Expression anglaise	
766	0	COMPTABILITE ET DROIT DU TRAVAIL 	
767	0	Economie et organisation des entreprises	
768	0	Probabilite et recherche operationnelle I	
769	0	Informatique  II	
770	0	Information appliquée aux assurance I	
771	0	ROUTE II ET ESSAIS	
772	0	Informatique appliquée aux assurance I	
773	0	Probabilite et recherche operationnelle II	
774	0	Information appliquée aux assurance II	
775	0	Economie appliquée a l'assurance	
776	0	CHIMIE  I	
777	0	systeme informatique I	
778	0	systeme informatique II	
779	0	Comptabilité analytique et gestion budgétaire	
780	0	Physique II	
781	0	Physique II	
782	0	Informatique  I	
783	0	Informatique  I	
784	0	Informatique  I	
785	0	comptabilité des entreprises d'assurance I	
787	0	Assurance des personnes I	
788	0	OUVRAGE D'ART	
789	0	OUVRAGE D'ART	
790	0	OUVRAGE D'ART	
791	0	OUVRAGE D'ART	
792	0	OUVRAGE D'ART	
793	0	OUVRAGE D'ART	
794	0	OUVRAGE D'ART	
795	0	OUVRAGE D'ART	
796	0	OUVRAGE D'ART	
797	0	OUVRAGE D'ART	
798	0	ROUTE I 	
799	0	Assurance de personne II 	
800	0	ORGANISATION DES CHANTIERS 	
801	0	TOPOGRAPHIE I 	
802	0	comptabilité des entreprises d'intermediaire en assurances	
803	0	TOPOGRAPHIE I 	
806	0	BETON ARME I 	
807	0	comptabilité des entreprises d'intermediaire en assurances : agents généraux I	
809	0	comptabilité des entreprises d'intermediaire en assurances : societé de courtage I	
810	0	comptabilité des entreprises d'intermediaire en assurances : agents généraux II	
812	0	ENTREPRENEURIAT 	
813	0	ENTREPRENEURIAT 	
814	0	ENTREPRENEURIAT 	
816	0	comptabilité des entreprises d'intermediaire en assurances : societé de courtage II	
817	0	réassurance  I	
818	0	Reassurance II	
819	0	BASE DE QUALITÉ HYGIÈNE SANTE SÉCURITÉ ENVIRONNEMENT	
821	0	Assurance risque technique I	
822	0	Assurance risque technique II	
824	0	TOPOGRAPHIE II	
825	0	Travaux de synthese I	
826	0	Travaux de synthese I	
828	0	Assurance maritime I	
829	0	MATÉRIAUX DE CONSTRUCTION	
830	0	ÉTUDE DE PRIX	
831	0	ÉTUDE DE PRIX	
832	0	ÉTUDE DE PRIX	
834	0	Assurance transport (aérien et terrestre)	
835	0	Travaux de synthese II	
836	0	Assurance maritime II	
837	0	Assurance transport (aérien et terrestre) II	
838	0	Droit des société commerciales I	
839	0	RESISTANCE DES MATERIAUX II	
840	0	Droit civil	
841	0	BÉTON ARME II	
842	0	le stage professionnel	
843	0	Droit des société commerciales II	
844	0	Droit des société commerciales II	
845	0	ESSAIS EN LABORATOIRE	
846	0	ESSAIS EN LABORATOIRE	
847	0	ESSAIS EN LABORATOIRE	
848	0	ESSAIS EN LABORATOIRE	
849	0	ESSAIS EN LABORATOIRE	
850	0	Mathematiques et information I	
851	0	Mathematiques et information I	
852	0	Techniques quanditative et gestion	
853	0	ROUTE II	
854	0	environnement marketing I	
855	0	Mix-marketing I	
856	0	Outils marketing	
857	0	Techniques marketing I	
858	0	mathematique et comptabilité	
859	0	environnement marketing II	
860	0	Mix-marketing II	
861	0	Outils marketing I	
862	0	Outils marketing II	
863	0	Techniques marketing II et méthodologie	
864	0	Formation bilingue II et environnement economique II	
865	0	Techniques quanditative et informatique I	
866	0	Comptabilite et systeme d'information I	
867	0	Management marketing I	
868	0	Management marketing I	
869	0	environnement marketing III	
870	0	environnement marketing III	
871	0	Outils marketing III et IV	
872	0	Travaux de synthese et politique commerciale I	
873	0	Techniques quanditative et informatique II	
874	0	Comptabilité et synthese d'informatique II	
875	0	Comptabilité et synthese d'informatique II	
876	0	Management marketing II	
877	0	Management marketing II	
878	0	environnement marketing IV	
879	0	Travaux de synthese et politique commerciale II	
880	0	Mathematiques financiere II	
881	0	Marketing fondamental I	
882	0	Marketing fondamental I	
883	0	Marketing international I	
884	0	Marketing international I	
885	0	Marketing fondamental II	
886	0	Marketing fondamental II	
887	0	Marketing international II	
888	0	Marketing opérationnel I	
889	0	Marketing opérationnel I	
890	0	Marketing rationnel I	
891	0	Marketing opérationnel II	
892	0	Marketing rationnel II	
893	0	Politiaue de produit	
894	0	Politiaue de produit	
895	0	politique de prix	
896	0	politique de distribution	
897	0	politique de communication	
898	0	etude de marché I	
899	0	Gestion commerciale I	
900	0	etude de marché II	
901	0	Gestion commerciale II	
902	0	etude de marché III	
903	0	Gestion commerciale III	
904	0	etude de marché IV	
905	0	Gestion commerciale IV	
906	0	Techniques de vente	
907	0	veille concurrentielle	
908	0	force de vente I	
909	0	Techniques de négociation commerciale	
910	0	force de vente II	
911	0	force de vente II	
912	0	Methodologie de redaction du Rapport De Stage 	
913	0	Informatique appliquée 	
914	0	Informatique appliquée II	
915	0	Comptabilité analytique et gestion budgétaire I	
916	0	Synthese d'information I	
918	0	management de l' equipe commerciale I	
919	0	Merchandising I	
920	0	management de l' equipe commerciale II	
921	0	management de l' equipe commerciale II	
922	0	Merchandising II	
923	0	Politiques commerciale I	
924	0	Politiques commerciale I	
925	0	Prospection commerciale	
926	0	Prospection commerciale	
927	0	Politiques commerciale II	
928	0	Politiques commerciale II	
929	0	Promotion sur le lieu de vente	
930	0	Techniques quanditative de gestion I	
931	0	outils de communication I	
932	0	Techniques professionnel I	
933	0	Relations professionnelles I	
934	0	Organisation et gestion I	
935	0	comptabilité et TIC I	
936	0	Techniques professionnel II	
937	0	Techniques professionnel III	
938	0	Relations professionnelles II	
939	0	Relations professionnelles III	
940	0	Organisation et gestion II	
941	0	Organisation et gestion III	
942	0	Organisation et gestion III	
943	0	Gestion des resources humaines et des evenements I et II	
944	0	Comptabilite et TIC II	
945	0	Techniques professionnelles IV	
946	0	Techniques professionnelles IV	
947	0	Relations professionnelles IV	
948	0	Relations professionnelles IV	
949	0	Organisation et gestion IV	
950	0	outils de techniques deI communication	
951	0	outils de techniques de communication I	
952	0	Information I	
953	0	outils de techniques de communication II	
954	0	Information II	
956	0	Commerce I	
957	0	Commerce II	
958	0	classement II	
959	0	Organisation et methodes administratives I	
960	0	Classement et materiel de bureau I	
961	0	Organisation et methodes administratives II	
962	0	Classement et materiel de bureau II	
963	0	Relations professionnelles internes I	
964	0	Relations professionnelles internes I	
965	0	Relations professionnelles externes I	
966	0	Relations professionnelles internes II	
967	0	Relations professionnelles externes II	
968	0	Redaction professionnelle I	
969	0	Relations professionnelles internes et externes	
970	0	MATHÉMATIQUES I 	
971	0	FABRICATION MÉCANIQUE 	
972	0	MATÉRIAUX ET MÉCANIQUE APPLIQUÉE	
973	0	MAINTENANCE ET AUTOMATISME	
974	0	Redaction professionnelle II	
975	0	Organisation de l'action I	
976	0	ANALYSE ET CONCEPTION DESSIN	
978	0	Travaux d'application et de synthese I	
979	0	Organisation de l'action II	
980	0	Travaux d'application et de synthese II	
981	0	Organisation de l'action III	
982	0	Travaux d'application et de synthese III	
983	0	Organisation de l'action IV	
984	0	Travaux d'application et de synthese IV	
985	0	Informatique appliquée  II	
988	0	Comptabilite analytique et gestion budgetaire II	
989	0	Introduction a la gestion des ressources humaines I	
990	0	Organisation d'un evenement I	
991	0	Organisation d'un evenement I	
992	0	Introduction a la gestion des ressources humaines II	
993	0	Organisation d'un evenement II	
994	0	Organisation d'un evenement II	
995	0	ANALYSE MÉCANIQUE I	
996	0	DESSIN ASSISTE PAR ORDINATEUR (DAO)	
997	0	TRAVAUX PRATIQUE FABRICATION	
998	0	FABRICATION ASSISTE PAR ORDINATEUR (FAO)	
999	0	ANALYSE MÉCANIQUE II	
1000	0	FAO-CAO I	
1001	0	USINAGE NON CONVENTIONNEL	
1002	0	TRAVAUX PRATIQUE FABRICATION II	
1003	0	MÉCANIQUE  ET MATÉRIAUX	
1004	0	CONCEPTION ASSISTE PAR ORDINATEUR II (CAOII)	
1005	0	OUTILLAGE ET AUTOMATISME	
1006	0	Techniques quanditatives I	
1007	0	comptabilité I éthique, déontologie et réglementation	
1008	0	Opérations et Techniques bancaires I	
1009	0	Finances I	
1010	0	Economie Monetaire et Bancaire I	
1011	0	Mathematiques et informatique II	
1012	0	Technique quanditative II	
1013	0	Comptabilité II et Méthodologie	
1014	0	Comptabilité II et Méthodologie	
1015	0	Opérations et Techniques bancaires II	
1016	0	Finances II	
1017	0	Economie Monetaire et Bancaire II	
1018	0	Technique quanditative III	
1019	0	Mathématique et informatique III	
1020	0	Comptabilité III	
1021	0	Opérations et Techniques bancaires III	
1022	0	Finances et travaux de synthèse I	
1023	0	Economie Monetaire et Bancaire III et IV	
1024	0	Technique quanditative IV	
1025	0	Mathematiques et information II	
1026	0	Mathematiques generales III	
1027	0	Mathematiques generales III	
1028	0	Mathematiques et informatique IV	
1029	0	Mathematiques et informatique IV	
1030	0	Comptabilite IV	
1031	0	Comptabilite IV	
1032	0	Comptabilite IV	
1033	0	Operation et technique bancaire IV	
1034	0	Finances et travaux de synthèse II	
1035	0	Mathematiques financiere III	
1036	0	Informatique appliquée  I	
1037	0	Mathematiques financiere IV	
1038	0	recherche opérationnelle I	
1039	0	Probabilites  I	
1040	0	recherche opérationnelle II	
1041	0	Probabilites  II	
1042	0	ethique et déontologie bancaires	
1043	0	reglementation bancaire	
1044	0	comptabilité des operations de banque I	
1045	0	Fiscalite des opérations de banque I	
1046	0	Fiscalite  des opérations de Banque	
1047	0	Comptabilité des opérations de banque I	
1049	0	Comptabilite des operations de banque I	
1050	0	Analyse Financière II	
1051	0	Techniques Bancaires et marches des particuliers	
1052	0	Opérations bancaires transfrontières I	
1053	0	Techniques Bancaires et marches des particuliers I	
1054	0	Stratégie et marketing bancaires	
1055	0	Stratégie et marketing bancaires	
1056	0	operation bancaires transfrontières II	
1057	0	operation bancaires transfrontières III	
1058	0	operation bancaires transfrontières IV	
1059	0	Techniques  bancaires marches des entrprises	
1060	0	Techniques  bancaires marches des entreprises	
1061	0	technique bancaire des marchés des entreprises III	
1062	0	Opérations bancaire transfrontières IV	
1063	0	Système financière décentralisés I	
1064	0	Finance islamique I	
1065	0	Finance islamique I	
1066	0	Marchés des capitaux I	
1067	0	Systèmes financiers décentralisés II	
1068	0	Finance islamique II	
1069	0	Marchés des capitaux II	
1070	0	Marchés des capitaux II	
1071	0	Systèmes financiers décentralisés III	
1072	0	Marchés des capitaux III	
1073	0	Système financière décentralisés IV	
1074	0	Marches des capitaux IV	
1075	0	Economie Monetaire I	
1076	0	Economie  Bancaire I	
1077	0	Economie  Bancaire II	
1078	0	Economie  Bancaire II	
1079	0	Economie  Bancaire II	
1080	0	Economie  Bancaire II	
1081	0	Economie  monetaire II	
1082	0	Economie  Bancaire III	
1083	0	Economie  Bancaire IV	
1084	0	Economie  monetaire III	
1085	0	Economie  monetaire III	
1086	0	Economie  monetaire III	
1087	0	Economie  monetaire III	
1088	0	Economie  monetaire III	
1089	0	Economie  monetaire IV	
1090	0	Mathematiques  III	
1091	0	Analyse mecanique II	
1092	0	FAO-CAO I	
1093	0	Usinage non conventionnel	
1094	0	travaux fatiques de fabrication II	
1095	0	Méthodologie de redaction du RDS	
1096	0	Mathematiques  IV	
1097	0	Information I	
1098	0	mecanique et materiaux	
1099	0	mecanique et materiaux	
1100	0	CAO II	
1101	0	Outillage et Automatisme	
1102	0	comptabilité et economie	
1103	0	technologie de fabrication	
1104	0	technologie de fabrication	
1105	0	Analyse de fabrication I	
1107	0	Analyse et conception dessin I	
1108	0	Analyse et conception dessin II	
1114	0	Analyse de fabrication II	
1116	0	sciences des materiaux I	
1117	0	Mecanique appliquée I	
1118	0	DAO (Autocard)	
1119	0	Maintenance industrielle	
1121	0	environnement juridique	
1124	0	Analyse et conception dessin III	
1140	0	Analyse de fabrication III	
1147	0	sciences des materiaux II	
1148	0	FAO  II	
1149	0	FAO  II	
1150	0	FAO  II	
1151	0	FAO  II	
1152	0	FAO  II	
1153	0	FAO  II	
1154	0	FAO  II	
1155	0	TRAITEMENT ANALOGIQUE ET TRAITEMENT NUMÉRIQUE DE L'INFORMATION,STOCKAGE DES DONNÉES	
1156	0	RÉSEAUX ET TELECOMMUNICATION	
1157	0	CAO I ( SOLIDWORK)	
1158	0	Outillage	
1159	0	TP CIRCUITS commande de puissance	
1160	0	installation et maintenance des systèmes complexes	
1161	0	Asservissements LINÉAIRE	
1162	0	CONSTRUCTION ELECTRONIQUE +TP MICROCONTROLEURS	
1163	0	SERVOMÉCANISME ET ROBOTIQUE	
1164	0	BASES D'ASSERVISSEMENT NUMÉRIQUE 	
1165	0	MAINTENANCE INFORMATIQUE  DES SYSTÈMES COMPLEXES 	
1166	0	TP ÉLECTRONIQUE ANALOGIQUE II 	
1167	0	TP ÉLECTRONIQUE NUMÉRIQUE II	
1168	0	BASES DE QUALITÉ HYGIÈNE SANTE SÉCURITÉ ENVIRONNEMENT (QHSSE)	
1169	0	ENTREPRENARIAT 	
1170	0	TP ÉLECTRONIQUE ANALOGIQUE I	
1171	0	TP ÉLECTRONIQUE NUMÉRIQUE I	
1172	0	MICROPROCESSEUR ET MICROCONTROLEURS	
1173	0	électronique de puissance ET COMMANDE DES ACTIONNEURS	
1174	0	AUTOMATISME +TP	
1176	0	MAINTENANCE ET FIABILITÉ	
1177	0	FONCTION DE L'ÉLECTRONIQUE ANALOGIQUE	
1178	0	BASE DE ÉLECTRONIQUE NUMÉRIQUE	
1179	0	ELECTRONIQUE DE BASE 	
1180	0	SUPPORT DE TRANSMISSION	
1181	0	TRANSPORT ET TRANSMISSION DE L'INFORMATION	
1182	0	CHAINE DE MESURE ET INSTRUMENTATION	
1183	0	Machine Électrique 	
1184	0	TP D'ÉLECTRONIQUE NUMÉRIQUE ET COMMANDE DE PUISSANCE	
1185	0	ÉLECTRONIQUE GRAND PUBLIC	
1186	0	MAINTENANCE INFORMATIQUE  DES SYSTÈMES COMPLEXES ET ASSERVISSEMENT NUMÉRIQUE	
1187	0	COMPATABILITE ET DROIT DU TRAVAIL	
1188	0	TRAITEMENT DE L'INFORMATION ET RÉSEAUX DE TELECOMMUNICATION	
1189	0	ASSERVISSEMENT ET MAINTENANCE DES  SYSTÈMES COMPLEXES	
1190	0	ROBOTIQUE ET  CONSTRUCTION ÉLECTRONIQUE	
1191	0	TP ÉLECTRONIQUE II	
1192	0	TP ELECTRONIQUE I 	
1193	0	électronique de puissance ET MICROPROCESSEURS	
1194	0	FONCTION DE L'ÉLECTRONIQUE ANALOGIQUE ET MAINTENANCE	
1195	0	EXPRESSION ÉCRITE ET FORMATION BILINGUE	
1196	0	BASE DE L'ÉLECTRONIQUE	
1197	0	Machine Électrique ET CHAINE DE MESURE	
1198	0	MATÉRIAUX ET MÉCANIQUE APPLIQUÉE	
1199	0	EXPRESSION ÉCRITE ET ENVIRONNEMENT JURIDIQUE	
1200	0	DESSIN ASSISTE PAR ORDINATEUR (DAO)	
1201	0	CAO (SOLIDWORK)	
1202	0	 MATÉRIAUX	
1203	0	FAO-USINAGE	
1204	0	MÉCANIQUE  APPLIQUÉE	
1205	0	MÉCANIQUE  APPLIQUÉE ET RÉSISTANCE DES MATÉRIAUX	
1206	0	DESSIN ET AUTOMATISME	
1207	0	FAO 	
1208	0	TECHNOLOGIE DE FABRICATION	
1209	0	ANALYSE DE FABRICATION I	
1210	0	ANALYSE ET CONCEPTION DESSIN II	
1211	0	AUTOMATISME  II	
1212	0	SCIENCES DES MATÉRIAUX I	
1213	0	MÉCANIQUE APPLIQUE I	
1214	0	MÉCANIQUE APPLIQUE I	
1215	0	MAINTENANCE INDUSTRIELLE  	
1216	0	AUTOMATISME  I	
1217	0	ENVIRONNEMENT JURIDIQUE	
1218	0	ENVIRONNEMENT JURIDIQUE	
1219	0	ENVIRONNEMENT JURIDIQUE	
1220	0	ENVIRONNEMENT JURIDIQUE	
1221	0	ENVIRONNEMENT JURIDIQUE	
1222	0	ÉDUCATION CIVIQUE ET ÉTHIQUE 	
1223	0	ANALYSE ET CONCEPTION DESSIN III	
1224	0	ANALYSE DE FABRICATION II	
1225	0	MÉCANIQUE  APPLIQUÉE II	
1226	0	SCIENCES DES MATÉRIAUX II	
1227	0	ANALYSE ET CONCEPTION DESSIN IV	
1228	0	AUTOMATISME  III	
1229	0	FABRICATION ASSISTE PAR ORDINATEUR I	
1230	0	TP D'USINAGE II	
1231	0	TECHNIQUES QUANTITATIVES I	
1232	0	COMPTABILITÉ I ,ÉTHIQUE, DÉONTOLOGIE ET RÉGLEMENTATION 	
1233	0	OPE	
1234	0	Opérations ET  TECHNIQUES BANCAIRES I	
1235	0	FINANCES I	
1236	0	Économie MONÉTAIRE ET BANCAIRE I	
1237	0	COMPTABILITÉ II MÉTHODOLOGIE	
1238	0	OPÉRATIONS  ET TECHNIQUES BANCAIRES II	
1239	0	FINANCES II	
1240	0	ÉCONOMIE MONÉTAIRE ET BANCAIRE II	
1241	0	TECHNIQUES QUANTITATIVES III	
1242	0	MATHÉMATIQUES ET INFORMATIQUE III	
1243	0	COMPTABILITÉ III 	
1244	0	OPÉRATION ET TECHNIQUES BANCAIRES III	
1245	0	OPERATION ET TECHNIQUES BANCAIRES III	
1246	0	FINANCES ET TRAVAUX DE SYNTHÈSE I	
1247	0	ÉCONOMIE MONÉTAIRE ET BANCAIRE III ET IV	
1248	0	MATHÉMATIQUES ET INFORMATIQUES IV	
1249	0	COMPTABILITÉ IV	
1250	0	OPÉRATION ET TECHNIQUES BANCAIRES IV	
1251	0	FINANCES ET TRAVAUX DE SYNTHÈSE II	
1252	0	MATHÉMATIQUE GÉNÉRALE II	
1253	0	MATHÉMATIQUES FINANCIERS III	
1254	0	MATHÉMATIQUES FINANCIERS II	
1255	0	COMPTABILITÉ GÉNÉRALE I	
1256	0	ÉTHIQUE ET DÉONTOLOGIE BANCAIRE	
1257	0	RÈGLEMENT BANCAIRE	
1258	0	FISCALITÉ DES OPÉRATIONS DE BANQUE	
1259	0	COMPTABILITÉ DES OPÉRATIONS DE BANQUE I	
1260	0	ANALYSE FINANCIÈRES I	
1261	0	COMPTABILITÉ DES OPÉRATIONS DE BANQUE II	
1262	0	TECHNIQUES BANCAIRES ET MARCHE DES PARTICULIERS 	
1263	0	OPÉRATION BANCAIRES TRANSFRONTIERES I	
1264	0	STRATÉGIE ET MARKETING BANCAIRES	
1265	0	TECHNIQUES BANCAIRES ET MARCHE DES ENTREPRISES I	
1266	0	OPÉRATION BANCAIRES TRANSFRONTIERES II	
1267	0	TECHNIQUE BANCAIRE MARCHE DES ENTREPRISES II	
1268	0	OPÉRATION BANCAIRES TRANSFRONTIERES III	
1269	0	TEV	
1270	0	TECHNIQUE BANCAIRE MARCHE DES ENTREPRISES III	
1271	0	OPÉRATION BANCAIRES TRANSFRONTIERES IV	
1272	0	SYSTÈME FINANCIERS DÉCENTRALISES I	
1273	0	FINANCE ISLAMIQUE I	
1274	0	MARCHES DES CAPITAUX I	
1275	0	SYSTÈMES FINANCIERS DÉCENTRALISES II	
1276	0	FINANCE ISLAMIQUE II	
1277	0	SYSTÈME FINANCIERS DÉCENTRALISES III	
1278	0	SYSTÈMES FINANCIERS DÉCENTRALISES II	
1279	0	MARCHES DES CAPITAUX II	
1280	0	SYSTÈMES  FINANCIERS DÉCENTRALISES III	
1281	0	MARCHES DES CAPITAUX III 	
1282	0	SYSTÈMES FINANCER DÉCENTRALISES IV	
1283	0	MARCHES DES CAPITAUX IV	
1284	0	ÉCONOMIE MONÉTAIRE I	
1285	0	ÉCONOMIE BANCAIRE I	
1286	0	ÉCONOMIE MONÉTAIRE II	
1287	0	ÉCONOMIE BANCAIRE II	
1288	0	ÉCONOMIE MONÉTAIRE III	
1289	0	ÉCONOMIE BANCAIRE III	
1290	0	ÉCONOMIE MONÉTAIRE  I	
1291	0	ÉCONOMIE BANCAIRE I 	
1292	0	ÉCONOMIE MONÉTAIRE II	
1293	0	ÉCONOMIE  MONÉTAIRE II	
1294	0	ÉCONOMIE BANCAIRE II	
1295	0	ÉCONOMIE MONÉTAIRE III	
1296	0	ÉCONOMIE BANCAIRE III	
1297	0	ÉCONOMIE MONÉTAIRE IV	
1298	0	ECONOMIE BANCAIRE IV	
1299	0	INITIATION AU TOURISME	
1300	0	SCIENCES APPLIQUÉES A L'HÔTELLERIE	
1301	0	TECHNOLOGIE DE RESTAURANT I	
1302	0	TECHNIQUE D'HÉBERGEMENT 	
1303	0	GESTION DES ÉTABLISSEMENTS D'HÉBERGEMENT	
1304	0	TECHNOLOGIE CULINAIRE I	
1305	0	CRÉATION D'ENTREPRISE , COMPTABILITÉ GÉNÉRALE ET FISCALITÉ	
1306	0	DROIT ET RÉGLEMENTATION APPLIQUÉE	
1307	0	ARTS CULINAIRE ET TECHNIQUES DE PÂTISSERIE	
1308	0	CONNAISSANCE ET TECHNIQUES DE BAR	
1309	0	MATHÉMATIQUES ET STATISTIQUES	
1310	0	CONNAISSANCE DES FROMAGES ET ŒNOLOGIE 	
1311	0	FORMATION BILINGUE I ,LANGUES VIVANTES ÉTRANGÈRES I (ALLEMAND ET ESPAGNOL)	
1312	0	INGÉNIERIE HÔTELIÈRE	
1313	0	RELATION HUMAINES 	
1314	0	TECHNOLOGIE DE RESTAURANT II	
1315	0	TECHNIQUE D'HÉBERGEMENT II	
1316	0	TECHNIQUE DE PÂTISSERIE	
1317	0	TECHNIQUE CULINAIRE II	
1318	0	INFORMATIQUE GÉNÉRALE ET APPLIQUÉE, ÉDUCATION CIVIQUE ET ÉTHIQUE	
1319	0	ÉCONOMIE ET ENJEUX DU TOURISME 	
1320	0	MÉDIATION CULTURELLE	
1321	0	TECHNIQUES DE VENTE ET YIED MANAGEMENT	
1322	0	INTRODUCTION A LA GESTION DE PROJETS	
1323	0	NORMES ET PROCÉDURES  HÔTELIÈRES	
1324	0	FORMATION BILINGUE II, MÉTHODOLOGIE, LANGUE VIVANTE ÉTRANGÈRE II ( CHINOIS OU ITALIEN)	
1325	0	LANGUE VIVANTE ETRANGERE II ( ITALIEN OU CHINOIS) 	
1326	0	Informatique  	
1327	0	ESPAGNOL PROFESSIONNEL	
1328	0	ALLEMAND PROFESSIONNEL	
1329	0	OENOLOGIE	
1330	0	CONNAISSANCE DES FROMAGES 	
1331	0	MATHÉMATIQUES GÉNÉRALES II	
1332	0	MATHÉMATIQUES GÉNÉRALES I 	
1333	0	FISCALITÉ 	
1334	0	COMPTABILITÉ GÉNÉRALE I	
1335	0	CRÉATION D'ENTREPRISE 	
1336	0	ACTION ET GESTION COMMERCIALE	
1337	0	GESTION ADMINISTRATION	
1338	0	GESTION COMPTABLE ET FINANCIÈRE 	
1339	0	GESTION DES RESSOURCES HUMAINES	
1340	0	FORMATION BILINGUE II ,LANGUES VIVANTES ÉTRANGÈRES II (CHINOIS OU ITALIEN)	
1341	0	CONNAISSANCE DES FROMAGES ET ŒNOLOGIE II	
1342	0	CONNAISSANCE ET TECHNIQUES DE BAR II	
1343	0	TECHNIQUE PÂTISSERIE II	
1344	0	MÉDIATION CULTURELLE	
1345	0	ÉCONOMIE ET ENJEUX DU TOURISME	
1346	0	INFORMATIQUE GÉNÉRALE ET APPLIQUÉE , EDUATION CIVIQUE ET ETHIQUE	
1347	0	CUISINE COMPARÉES 	
1348	0	PRODUCTION CULINAIRE DT SERVICES	
1349	0	TECHNIQUES HÉBERGEMENT	
1350	0	TECHNOLOGIE  DE RESTAURANT II	
1351	0	RELATIONS HUMAINES 	
1352	0	GESTION DE RESTAURANT	
1353	0	CONNAISSANCE DES FROMAGES ET ŒNOLOGIE  I	
1354	0	MATHEMATIQUES ET CHIMIE	
1355	0	CONNAISSANCE ET TECHNIQUES DE BAR I	
1356	0	TECHNIQUE DE PÂTISSERIE I	
1357	0	CRÉATION D'ENTREPRISE , COMPTABILITÉ GÉNÉRALE ET FISCALITÉ	
1358	0	DIETETIQUE 	
1359	0	ARTS CULINAIRES	
1360	0	TECHNIQUE DE SERVICE	
1361	0	SCIENCES APPLIQUÉES A LA RESTAURATION	
1362	0	INITIATION AU TOURISME	
1363	0	OENOLOGIE II	
1364	0	CONNAISSANCE DES FROMAGES II	
1365	0	Informatique Appliquée 	
1366	0	SERVICE 	
1367	0	PRODUCTION CULINAIRE 	
1368	0	GESTION ADMINISTRATIVE	
1369	0	OENOLOGIE I	
1370	0	CONNAISSANCE DES FROMAGES I	
1371	0	MATHEMATIQUES  GENERALES I	
1372	0	FISCALITÉ 	
1373	0	COMPTABILITE GENERALE I	
1374	0	CRÉATION D'ENTREPRISE 	
1375	0	PSYCHO-SOCIOLOGIE ET MEDIA	
1376	0	SCIENCES DE L'INFORMATION ET DE LA COMMUNICATION	
1377	0	PRATIQUE DU JOURNALISME RTV II	
1378	0	PRATIQUE DU JOURNALISME PRESSE ECRITE II	
1379	0	AUDIOVISUEL D'ENTREPRISE ET NOUVEAUX MEDIA	
1380	0	ANALYSE DE L'ACTUALITÉ ET SCIENCES DE L'OPINION	
1381	0	MÉTHODOLOGIE, CRÉATION D'ENTREPRISE, COMPTABILITÉ GÉNÉRALE	
1382	0	ACTION EN SITUATION PROFESSIONNELLE	
1383	0	EPREUVE PROFESSIONNELLE DE SYNTHÈSE 	
1384	0	RÉALISATION RADIO ET TÉLÉVISION II	
1385	0	SECRÉTARIAT DE DIRECTION + PAO II	
1386	0	GÉOGRAPHIE ÉCONOMIQUE ET PSYCHOLOGIE GÉNÉRALE	
1387	0	HISTOIRE ET ORGANISATION DE LA PRESSE	
1388	0	HISTOIRE DES CIVILISATION DU MONDE CONTEMPORAIN	
1389	0	PRATIQUE DU JOURNALISME RADIO ET TÉLÉVISION I	
1390	0	PRATIQUE DU JOURNALISME PRESSE ECRITE I	
1391	0	AGENCE ET ÉCRITURE D'AGENCE ET PRATIQUE DE L'INTERNET	
1392	0	CARREFOUR DE L'ACTUALITÉ ET GÉOPOLITIQUE	
1393	0	DROIT DE LA PRESSE, ÉTHIQUE ET DÉONTOLOGIE, ÉDUCATION CIVIQUE, ÉCONOMIE ET ORGANISATION DES ENTREPRISES	
1394	0	SOCIOLOGIE GÉNÉRALE ET LOGIQUE FORMELLE	
1395	0	INFORMATIQUE, INITIATION A LA PROGRAMMATION WEB ET INFOGRAPHIE	
1396	0	RÉALISATION RADIO ET TÉLÉVISION I	
1397	0	TECHNIQUES RÉDACTIONNELLES	
1398	0	PHOTO JOURNALISME	
1399	0	FORMATION BILINGUE I ,LANGUES VIVANTES ÉTRANGÈRES  (ALLEMAND) ÉCONOMIE GÉNÉRALE	
1400	0	MÉTHODOLOGIE , CRÉATION D'ENTREPRISE, COMPTABILITÉ GÉNÉRALE	
1401	0	RÉALISATION RADIO ET TÉLÉVISION II	
1402	0	SECRÉTARIAT DE RÉDACTION + PAO II 	
1403	0	GÉOGRAPHIE ÉCONOMIQUE ET PSYCHOLOGIE GÉNÉRALE	
1404	0	FORMATION BILINGUE II ,LANGUES VIVANTES ÉTRANGÈRES  (ESPAGNOL), DROIT DU TRAVAIL	
1405	0	AGENCE ET ÉCRITURE D'AGENCE 	
1406	0	PRATIQUE DE L'INTERNET	
1407	0	CARREFOUR DE L'ACTUALITÉ 	
1408	0	GÉOPOLITIQUE	
1409	0	DROIT ÉTHIQUE ET DÉONTOLOGIE DE LA PRESSE 	
1410	0	ÉDUCATION CIVIQUE ET ÉTHIQUE	
1411	0	ÉCONOMIE ET ORGANISATION DES ENTREPRISES DE PRESSE (ÉCONOMIE DES MÉDIAS)	
1412	0	PRATIQUE DE L'INTERNET	
1413	0	GEOPOLITIQUE	
1414	0	DROIT ÉTHIQUE ET DÉONTOLOGIE DE LA PRESSE	
1415	0	SOCIOLOGIE GÉNÉRALE 	
1416	0	LOGIQUE FORMELLE	
1417	0	INITIATION A LA PROGRAMMATION WEB	
1418	0	INFOGRAPHIE: INITIATION AU LOGICIEL PHOTOSHOP 	
1419	0	SECRETARIAT DE REDACTION + PAO I 	
1420	0	INITIATION AUX MÉTHODE BIBLIOGRAPHIQUES ET DOCUMENTAIRES	
1422	0	ANALYSE DE L'ACTUALITÉ 	
1423	0	SCIENCES DE L'OPINION	
1424	0	CRÉATION D'ENTREPRISE 	
1425	0	GÉOGRAPHIE  ÉCONOMIQUE	
1426	0	PSYCHOLOGIE GÉNÉRALE	
1427	0	LANGUE VIVANTE ÉTRANGÈRE  ( ESPAGNOL) 	
1428	0	TIC ET RÉVOLUTION ÉCONOMIQUE	
1429	0	TECHNIQUE DE FABRICATION ET RÉALISATION DES SUPPORTS II	
1430	0	CRÉATION D'ENTREPRISE 	
1431	0	TECHNIQUES D'ÉTUDE DE MARCHE  	
1432	0	HISTOIRE GÉNÉRALE DE L'INFORMATION	
1433	0	CREATION D'ENTREPRISE 	
1434	0	GÉOPOLITIQUE 	
1435	0	PSYCHOLOGIE DES MÉDIAS 	
1436	0	CHAMPS D'APPLICATION DE LA COMMUNICATION DES ORGANISATIONS	
1437	0	COMMUNICATION ÉVÉNEMENTIELLE ET PARRAINAGE 	
1438	0	TECHNIQUE DE FABRICATION ET RÉALISATION DES SUPPORTS I	
1439	0	COMMUNICATION EXTERNE	
1440	0	COMMUNICATION INTERNE	
1441	0	INITIATION AU MARKETING	
1442	0	INITIATION AUX RELATIONS PUBLIQUES 	
1443	0	DROIT DE COMMUNICATION,ÉTHIQUE ET DÉONTOLOGIE 	
1444	0	NITIATION AU DROIT 	
1445	0	GÉOGRAPHIE ÉCONOMIQUE 	
1446	0	PSYCHOLOGIE GÉNÉRALE 	
1447	0	INITIATION AUX MÉTHODES BIBLIOGRAPHIQUES ET DOCUMENTAIRES 	
1448	0	MIX COMMUNICATION 	
1449	0	FORMATION BILINGUE I ,LANGUES VIVANTES ÉTRANGÈRES  ( ESPAGNOL) DROIT DU TRAVAIL	
1450	0	AUDIOVISUEL D'ENTREPRISE ET NOUVEAUX MÉDIA, TIC ET RÉVOLUTION	
1451	0	TECHNIQUE DE FABRICATION ET RÉALISATION DES SUPPORTS II ET CARREFOUR DE L'ACTUALITÉ	
1453	0	MÉTHODOLOGIE, CRÉATION D'ENTREPRISE, COMPTABILITÉ 	
1454	0	TECHNIQUES D'ÉTUDE DE MARCHE ET SCIENCES DE L'OPINION	
1455	0	GÉOPOLITIQUE ET HISTOIRE GÉNÉRALE DE L'INFORMATION 	
1456	0	CHAMPS D'APPLICATION DE LA COMMUNICATION DES ORGANISATIONS ET PSYCHOLOGIE DES MÉDIAS 	
1457	0	TECHNIQUES DE FABRICATION ET RÉALISATION DES SUPPORTS I ET COMMUNICATION ÉVÉNEMENTIELLE ET PARRAINAGE	
1458	0	COMMUNICATION INTERNE ET COMMUNICATION INTERNE 	
1459	0	COMMUNICATION DES ORGANISATIONS II 	
1460	0	approche internationale et technique d'assurance I + approche internationale et technique  d'assurance II	
1461	0	METHODOLOGIE DES ACCES UTILISATEURS	
1462	0	RESEAUX II	
1463	0	SUPPORT DES SERVICES ET DES SERVEURS I	
1464	0	DEVELOPPEMENT D'APPLICATION	
1465	0	DEVELOPPEMENT D'APPLICATION I	
1466	0	DEVELOPPEMENT D'APPLICATION II	
1467	0	GESTIONS DES OPERATIONS DE SERVICE I	
1468	0	GESTIONS DES OPERATIONS DE SERVICE II 	
1469	0	GESTION DES OPERATIONS DE SERVICE I	
1470	0	GESTIONS DES OPERATIONS DE SERVICE II  	
1471	0	INFORMATIQUE GENERALE II	
1472	0	MATHEMATIQUE ET INFORMATIQUE III	
1473	0	ANALYSE MATHEMATIQUE I	
1474	0	ALGEBRE LINEAIRE I	
1475	0	METROLOGIE	
1476	0	INSTRUMENTATION	
1477	0	ELECTRICITE-ELECTRONIQUE	
1478	0	ELECTRICITE-ELECTRONIQUE I (EEI)	
1479	0	ELECTRICITE GENERALE	
1480	0	ELECTRICITE GENERALE ET CIRCUIT ELECTRIQUE	
1481	0	MACHINES ELECTRIQUES ET TECHNIQUES DE MESURES	
1482	0	TECHNIQUES DE MESURES	
1483	0	ELECTRONIQUE ET AUTOMATISME	
1484	0	TRAITEMENT Du SIGNAL 	
1485	0	SCHEMAS ELECTRIQUES ET COMMANDE	
1486	0	TECHNIQUE DE PRODUCTION, TRANSPORT ET DISTRIBUTION	
1487	0	SCHEMAS ELECTRIQUES ET COMMANDE	
1488	0	MAINTENANCE EN GENIE ELECTRIQUE	
1489	0	RESEAUX INDUSTRIELS	
1490	0	COMMUNICATION DES DONNEES	
1491	0	ASSERVISSEMENTS	
1492	0	ELECTRONIQUE III + TP	
1493	0	PROCEDES GENERAUX DE CONSTRUCTION I	
1494	0	ORGANISATION DES CHANTIERS	
1495	0	DESSIN ASSISTE PAR ORDINATEUR/ CONCEPTION ASSISTEE PAR ORDINATEUR(DAO/CAO)	
1496	0	ROUTE II ET ESSAIS EN LABORATOIRE	
1497	0	CREATION D'ENTREPRISE, COMPTABILITE GENERALE ET FISCALITE	
1498	0	MATHEMATIQUES ET STATISTIQUES	
1499	0	TECHNIQUE DE PATISSERIE	
1500	0	INFORMATIQUE GENERALE ET APPLIQUEE, EDUCATION CIVIQUE ET ETHIQUE	
1501	0	ECONOMIE ET ENJEUX DU TOURISME	
1502	0	FORMATION BILINGUE II, METHOLOGIE, LANGUE VIVANTE ETRANGERE II(CHINOIS OU ITALIEN)	
1503	0	LANGUE VIVANTE ETRANGERE II(ITALIEN OU CHINOIS	
1504	0	Communication des organisations I	
1505	0	HISTOIRE DE LA COMMUNICATION ET DES RP	
1506	0	MIX DE COMMUNICATION I ET INITIATION AUX RELATIONS PUBLIQUES	
1507	0	HISTOIRE DE LA COMMUNICATION ET DES RELATIONS PUBLIQUES	
1508	0	Statistiques générales et Initiation aux méthodes bibliographiques et documentaire	
1509	0	PSYCHOLOGIE GENERALE ET PRATIQUE DE L'INTERNET	
1510	0	GEOGRAPHIE ECONOMIQUE ET INITIATION AU DROIT	
1511	0	GEOGRAPHIE ECONOMIQUE	
1512	0	DROIT DE LA COMMUNICATION, ETHIQUE ET DEONTOLOGIE, ECONOMIE ET ORGANISATION DES ENTREPRISES	
1513	0	DROIT DE LA COMMUNICATION, ETHIQUE ET DEONTOLOGIE 	
1514	0	ECONOMIE ET ORGANISATION DES ENTREPRISES (ECONOMIE MEDIAS)	
1515	0	MIX DE COMMUNICATION II	
1516	0	INITIATION AUX RELATIONS PUBLIQUES ET INITIATION AU MARKETING	
1517	0	INITIATION AUX RELATIONS PUBLIQUES	
1519	0	INITIATION A LA PROGRAMMATION WEB	
1520	0	LANGUE VIVANTE ETRANGERE (ESPAGNOL)	
1521	0	EDUCATION CIVIQUE ET ETHIQUE/FORMATION BILINGUE	
1522	0	MARCHES ET FILIERES DES PRODUITS PASTORAUX ET HALIEUTIQUES	
1523	0	TECHNIQUES PASTORALES ET HALIEUTIQUES 	
1524	0	TECHNIQUES AGRICOLES	
1525	0	GESTION DES ENTREPRISES	
1526	0	INFORMATIQUE, MULTIMEDIA ET CREATION D'ENTREPRISE	
1527	0	ENVIRONNEMENT SOCIO-ECONOMIQUE DE L'ENTREPRISE	
1528	0	Marchés des filières agricoles I	
1529	0	TECHNIQUE AGRICOLE ET PASTORALE	
1530	0	Mathématique Analyse	
1531	0	Mathématique Algèbre	
1532	0	Mathématique Algèbre II	
1533	0	Mathématique Algèbre II	
1534	0	Mathématique Analyse II	
1535	0	Droit commercial	
1536	0	DROIT	
1537	0	Procédés Généraux de Constructions	
1538	0	Formation bilingue et expréssion écrite	
1539	0	Formation bilingue et Création d'Entreprise	
1540	0	Management hotélier	
1541	0	Marquéting Touristique	
1542	0	Législation touristique	
1543	0	Etude de projet	
1544	0	Etude de cas	
1545	0	Sciences Appliquées	
1546	0	Technologie de patisserie	
1547	0	technologie de cuisine	
1548	0	Connaissance des boissons	
1549	0	Espagnol/Allemend	
1550	0	Markéting Touristique	
1551	0	Gestion Comptable	
1552	0	Gestion des Projets	
1553	0	Relation sociale	
1554	0	Management hotélier ,etudes de cas et etude de projet	
1555	0	Statistique et Informatique	
1556	0	Gestion des Projets et Relation Sociale	
1557	0	Technologie de restauration	
1558	0	touristiques	
1421	0	LANGUE VIVANTE ÉTRANGÈRE  ( ALLEMAND/ESPAGNOL) 	
1518	0	LANGUE VIVANTE ETRANGERE (ALLEMAND/ESPAGNOL)	
\.


--
-- Name: matiere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('matiere_id_seq', 1558, true);


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY migrations (migration, batch) FROM stdin;
2017_09_26_115836_bulletin_genere	1
2017_09_26_120129_classe	1
2017_09_26_120147_classe_matiere	1
2017_09_26_120204_cycle	1
2017_09_26_120239_decaissement	1
2017_09_26_120309_discipline	1
2017_09_26_120333_discipline_personnel	1
2017_09_26_120355_dispense	1
2017_09_26_120408_eleve	1
2017_09_26_120428_fonction	1
2017_09_26_120445_fonction_personnel	1
2017_09_26_120459_horaire	1
2017_09_26_120517_inscription	1
2017_09_26_120811_matiere	1
2017_09_26_121010_moyenne_classe	1
2017_09_26_121024_moyenne_eleve	1
2017_09_26_121042_moyenne_matiere	1
2017_09_26_121138_parametres	1
2017_09_26_121315_payement_salaire	1
2017_09_26_121406_salaire	1
2017_09_26_121442_taux	1
2017_09_26_121505_titulaire	1
2017_09_26_121523_trimestre	1
2017_09_26_121540_users	1
2017_09_27_155005_create_heure_realises_table	1
2017_10_19_152912_create_verrou_notes_table	1
2017_10_27_073638_create_parent	1
2017_10_28_043401_create_payement	1
2017_10_28_043623_create_niveau	1
2017_10_28_043721_create_note	1
2017_10_28_050011_create_scolarite	1
2017_10_28_074500_create_personnel	1
2018_01_26_133020_create_n_niveaus_table	1
2018_05_04_153612_create_groupe_matieres_table	1
2018_06_21_155025_create_photos_table	1
2018_07_17_022409_create_heures_prevues_table	1
2018_07_17_123019_create_salles_table	1
2018_08_11_115253_create_codages_table	2
\.


--
-- Data for Name: moyenne_classe; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY moyenne_classe (id, id_classe, sequence, annee_scolaire, moyenne, max, min) FROM stdin;
\.


--
-- Name: moyenne_classe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('moyenne_classe_id_seq', 1, false);


--
-- Data for Name: moyenne_eleve; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY moyenne_eleve (id, id_eleve, id_classe, moyenne, annee_scolaire, sequence) FROM stdin;
\.


--
-- Name: moyenne_eleve_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('moyenne_eleve_id_seq', 1, false);


--
-- Data for Name: moyenne_matiere; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY moyenne_matiere (id, id_matiere, id_classe, max, min, moyenne, sequence, annee_scolaire) FROM stdin;
\.


--
-- Name: moyenne_matiere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('moyenne_matiere_id_seq', 1, false);


--
-- Data for Name: n_niveaus; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY n_niveaus (id, nom, id_cycle) FROM stdin;
5	bts1	1
6	bts2	1
7	CAPACITE1	3
8	CAPACITE2	3
9	LICENCE L1	10
10	LICENCE L2	10
11	LICENCE L3	10
12	LICENCE3	7
13	MASTER 1	6
14	MASTER 2	6
15	HR3	1
16	HR2	1
\.


--
-- Name: n_niveaus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('n_niveaus_id_seq', 16, true);


--
-- Data for Name: niveau; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY niveau (id, nom, nom_anglais) FROM stdin;
1	HOTELLERIE	
5	GENIE ELECTRIQUE	
6	GENIE THERMIQUE	
7	GENIE MECANIQUE ET PRODUCTIQUE	
8	GENIE CIVIL	
9	ECONOMIE SOCIALE ET FAMILIALE	
10	INFORMATION ET COMMUNICATION	
11	GESTION	
12	CAPACITE EN DROIT	
4	AGRICULTURE ET ELEVAGE	
13	RESTAURATION	
14	DROIT	
15	GENIE INFORMATIQUE	
\.


--
-- Name: niveau_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('niveau_id_seq', 15, true);


--
-- Data for Name: note; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY note (id, id_matiere, id_eleve, sequence, note, annee_scolaire) FROM stdin;
1	164	105	1	25	2018-2019
2	164	96	1	10	2018-2019
3	164	8	1	20	2018-2019
4	164	60	1	15	2018-2019
5	164	57	1	15	2018-2019
6	164	10	1	20	2018-2019
7	164	16	1	20	2018-2019
8	164	54	1	20	2018-2019
9	164	107	1	20	2018-2019
10	164	4	1	25	2018-2019
11	164	51	1	25	2018-2019
12	164	101	1	25	2018-2019
13	162	105	1	10	2018-2019
14	162	96	1	10	2018-2019
15	162	8	1	10	2018-2019
16	162	60	1	10	2018-2019
17	162	57	1	10	2018-2019
18	162	10	1	10	2018-2019
19	162	16	1	10	2018-2019
20	162	54	1	10	2018-2019
21	162	107	1	10	2018-2019
22	162	4	1	10	2018-2019
23	162	51	1	10	2018-2019
24	162	101	1	10	2018-2019
25	275	105	1	20	2018-2019
26	275	96	1	20	2018-2019
27	275	8	1	20	2018-2019
28	275	60	1	20	2018-2019
29	275	57	1	20	2018-2019
30	275	10	1	20	2018-2019
31	275	16	1	20	2018-2019
32	275	54	1	20	2018-2019
33	275	107	1	20	2018-2019
34	275	4	1	20	2018-2019
35	275	51	1	20	2018-2019
36	275	101	1	20	2018-2019
37	288	99	3	52.5	2017-2018
38	288	50	3	57.75	2017-2018
39	288	106	3	40	2017-2018
40	288	127	3	49	2017-2018
41	288	210	3	57.75	2017-2018
42	288	115	3	55	2017-2018
43	288	103	3	52.5	2017-2018
44	288	209	3	35	2017-2018
45	288	187	3	52.5	2017-2018
46	288	99	1	15	2017-2018
47	288	50	1	16.5	2017-2018
48	288	127	1	21	2017-2018
49	288	210	1	16.5	2017-2018
50	288	115	1	18	2017-2018
51	288	103	1	21	2017-2018
52	288	187	1	15	2017-2018
53	1535	99	3	31.5	2017-2018
54	1535	50	3	29.75	2017-2018
55	1535	106	3	43.75	2017-2018
56	1535	127	3	40.25	2017-2018
57	1535	210	3	24.5	2017-2018
58	1535	115	3	52.5	2017-2018
59	1535	103	3	54.25	2017-2018
60	1535	187	3	50.75	2017-2018
61	1535	99	1	18	2017-2018
62	1535	50	1	12.75	2017-2018
63	1535	106	1	10.5	2017-2018
64	1535	127	1	21	2017-2018
65	1535	210	1	13.5	2017-2018
66	1535	115	1	16.5	2017-2018
67	1535	103	1	17.25	2017-2018
68	1535	209	1	15	2017-2018
69	1535	187	1	22.5	2017-2018
70	570	99	1	12	2017-2018
71	570	50	1	8.25	2017-2018
72	570	106	1	9.75	2017-2018
73	570	127	1	15.75	2017-2018
74	570	210	1	13	2017-2018
75	570	115	1	13.5	2017-2018
76	570	103	1	4.5	2017-2018
77	570	209	1	12.75	2017-2018
78	570	187	1	23.25	2017-2018
79	570	99	3	36.75	2017-2018
80	570	50	3	28	2017-2018
81	570	106	3	45.5	2017-2018
82	570	127	3	47.25	2017-2018
83	570	210	3	26.25	2017-2018
84	570	115	3	47.25	2017-2018
85	570	103	3	24.5	2017-2018
86	570	187	3	42	2017-2018
87	337	99	1	12	2017-2018
88	337	50	1	15	2017-2018
89	337	106	1	9	2017-2018
90	337	127	1	14	2017-2018
91	337	210	1	15	2017-2018
92	337	115	1	13	2017-2018
93	337	103	1	14	2017-2018
94	337	209	1	13.5	2017-2018
95	337	187	1	16	2017-2018
96	337	99	3	21	2017-2018
97	337	50	3	28	2017-2018
98	337	106	3	31.5	2017-2018
99	337	127	3	31.5	2017-2018
100	337	210	3	31.5	2017-2018
101	337	115	3	31.5	2017-2018
102	337	103	3	42	2017-2018
103	337	187	3	28	2017-2018
104	798	99	1	23	2017-2018
105	798	50	1	24	2017-2018
106	798	127	1	25.5	2017-2018
107	798	210	1	21	2017-2018
108	798	115	1	24	2017-2018
109	798	103	1	21	2017-2018
110	798	209	1	18	2017-2018
111	798	187	1	25.5	2017-2018
112	798	99	3	39	2017-2018
113	798	50	3	39	2017-2018
114	798	106	3	42	2017-2018
115	798	127	3	53	2017-2018
116	798	210	3	35	2017-2018
117	798	115	3	39	2017-2018
118	798	103	3	39	2017-2018
119	798	187	3	42	2017-2018
120	164	99	1	18	2017-2018
121	164	50	1	24	2017-2018
122	164	127	1	26.25	2017-2018
123	164	210	1	21	2017-2018
124	164	115	1	25.5	2017-2018
125	164	103	1	23.5	2017-2018
126	164	187	1	28.5	2017-2018
127	164	99	3	52.5	2017-2018
128	164	50	3	49	2017-2018
129	164	106	3	26.25	2017-2018
130	164	127	3	45.5	2017-2018
131	164	210	3	52.5	2017-2018
132	164	115	3	66.5	2017-2018
133	164	103	3	42	2017-2018
134	164	187	3	49	2017-2018
135	684	99	1	21	2017-2018
136	684	50	1	17.75	2017-2018
137	684	127	1	17.25	2017-2018
138	684	210	1	9.75	2017-2018
139	684	115	1	14.75	2017-2018
140	684	103	1	16.5	2017-2018
141	684	209	1	13.5	2017-2018
142	684	187	1	19.25	2017-2018
143	684	99	3	59.5	2017-2018
144	684	50	3	44.75	2017-2018
145	684	106	3	38.5	2017-2018
146	684	127	3	37.75	2017-2018
147	684	210	3	47.25	2017-2018
148	684	115	3	49	2017-2018
149	684	103	3	44.75	2017-2018
150	684	187	3	35	2017-2018
151	1531	99	1	13.5	2017-2018
152	1531	50	1	7.5	2017-2018
153	1531	127	1	15	2017-2018
154	1531	210	1	11.25	2017-2018
155	1531	115	1	20.25	2017-2018
156	1531	103	1	21	2017-2018
157	1531	209	1	14.25	2017-2018
158	1531	187	1	21.75	2017-2018
159	1531	99	3	35	2017-2018
160	1531	50	3	21	2017-2018
161	1531	106	3	17.5	2017-2018
162	1531	127	3	35	2017-2018
163	1531	210	3	14	2017-2018
164	1531	115	3	12.5	2017-2018
165	1531	103	3	17.5	2017-2018
166	1531	187	3	2	2017-2018
167	1530	99	1	19.5	2017-2018
168	1530	50	1	15	2017-2018
169	1530	127	1	18	2017-2018
170	1530	210	1	11.25	2017-2018
171	1530	115	1	24	2017-2018
172	1530	103	1	13	2017-2018
173	1530	209	1	11.25	2017-2018
174	1530	187	1	23	2017-2018
175	1530	99	3	43.75	2017-2018
176	1530	50	3	29.75	2017-2018
177	1530	106	3	50.75	2017-2018
178	1530	127	3	26.25	2017-2018
179	1530	210	3	33.25	2017-2018
180	1530	115	3	38.5	2017-2018
181	1530	103	3	35	2017-2018
182	1530	187	3	43.75	2017-2018
183	354	99	1	15	2017-2018
184	354	50	1	17.25	2017-2018
185	354	127	1	21	2017-2018
186	354	210	1	15	2017-2018
187	354	115	1	15	2017-2018
188	354	103	1	25.5	2017-2018
189	354	209	1	13.5	2017-2018
190	354	187	1	16.5	2017-2018
191	354	99	3	29.75	2017-2018
192	354	50	3	26.25	2017-2018
193	354	106	3	50.75	2017-2018
194	354	127	3	43.75	2017-2018
195	354	210	3	36.75	2017-2018
196	354	115	3	56	2017-2018
197	354	103	3	42	2017-2018
198	354	187	3	38.5	2017-2018
199	275	99	1	21.5	2017-2018
200	275	50	1	18.5	2017-2018
201	275	127	1	22.5	2017-2018
202	275	210	1	17.5	2017-2018
203	275	115	1	16	2017-2018
204	275	103	1	19	2017-2018
205	275	187	1	17.5	2017-2018
206	275	99	3	52.5	2017-2018
207	275	50	3	22.75	2017-2018
208	275	106	3	47.25	2017-2018
209	275	127	3	35	2017-2018
210	275	210	3	21	2017-2018
211	275	115	3	26.25	2017-2018
212	275	103	3	28	2017-2018
213	275	187	3	38.5	2017-2018
214	357	99	1	20.5	2017-2018
215	357	50	1	10.75	2017-2018
216	357	127	1	18.75	2017-2018
217	357	115	1	15	2017-2018
218	357	103	1	19.5	2017-2018
219	357	209	1	18.75	2017-2018
220	357	187	1	21	2017-2018
221	357	99	3	42	2017-2018
222	357	50	3	36	2017-2018
223	357	106	3	49	2017-2018
224	357	127	3	35	2017-2018
225	357	210	3	35	2017-2018
226	357	115	3	35	2017-2018
227	357	103	3	37.25	2017-2018
228	357	187	3	35	2017-2018
229	398	99	1	7.5	2017-2018
230	398	50	1	15.6	2017-2018
231	398	127	1	24.5	2017-2018
232	398	210	1	20.5	2017-2018
233	398	115	1	18.600000000000001	2017-2018
234	398	103	1	14.699999999999999	2017-2018
235	398	209	1	13.5	2017-2018
236	398	187	1	15.6	2017-2018
237	398	99	3	36.25	2017-2018
238	398	50	3	49	2017-2018
239	398	106	3	31.5	2017-2018
240	398	127	3	50	2017-2018
241	398	210	3	33.25	2017-2018
242	398	115	3	36.75	2017-2018
243	398	103	3	57.75	2017-2018
244	398	187	3	55.5	2017-2018
245	359	99	1	17	2017-2018
246	359	50	1	16.5	2017-2018
247	359	127	1	22.5	2017-2018
248	359	210	1	20	2017-2018
249	359	115	1	20.5	2017-2018
250	359	103	1	15.75	2017-2018
251	359	209	1	13	2017-2018
252	359	187	1	18	2017-2018
253	359	99	3	57.75	2017-2018
254	359	50	3	42	2017-2018
255	359	106	3	38.5	2017-2018
256	359	127	3	54.25	2017-2018
257	359	210	3	57.75	2017-2018
258	359	115	3	60.5	2017-2018
259	359	103	3	38.5	2017-2018
260	359	187	3	42	2017-2018
261	675	99	1	24	2017-2018
262	675	50	1	25.5	2017-2018
263	675	127	1	27	2017-2018
264	675	210	1	23	2017-2018
265	675	115	1	25.5	2017-2018
266	675	103	1	23	2017-2018
267	675	209	1	20	2017-2018
268	675	187	1	27	2017-2018
269	675	99	3	53	2017-2018
270	675	50	3	42	2017-2018
271	675	106	3	39	2017-2018
272	675	127	3	46	2017-2018
273	675	210	3	46	2017-2018
274	675	115	3	53	2017-2018
275	675	103	3	42	2017-2018
276	675	187	3	35	2017-2018
277	399	99	1	22	2017-2018
278	399	50	1	13	2017-2018
279	399	127	1	16.5	2017-2018
280	399	115	1	25	2017-2018
281	399	103	1	19	2017-2018
282	399	209	1	13	2017-2018
283	399	187	1	19	2017-2018
284	399	99	3	34.5	2017-2018
285	399	50	3	49	2017-2018
286	399	106	3	30	2017-2018
287	399	127	3	35	2017-2018
288	399	210	3	35	2017-2018
289	399	115	3	50.75	2017-2018
290	399	103	3	36.75	2017-2018
291	399	187	3	35	2017-2018
292	806	99	2	17	2017-2018
293	806	50	2	17	2017-2018
294	806	106	2	20	2017-2018
295	806	127	2	18	2017-2018
296	806	115	2	20	2017-2018
297	806	103	2	21	2017-2018
298	806	187	2	18	2017-2018
299	373	99	2	20	2017-2018
300	373	50	2	20	2017-2018
301	373	106	2	20	2017-2018
302	373	127	2	20	2017-2018
303	373	115	2	21	2017-2018
304	373	103	2	21	2017-2018
305	373	187	2	20	2017-2018
306	337	99	2	17	2017-2018
307	337	50	2	22	2017-2018
308	337	127	2	16	2017-2018
309	337	115	2	8	2017-2018
310	337	103	2	22	2017-2018
311	337	187	2	22	2017-2018
312	1535	99	2	9	2017-2018
313	1535	50	2	23.25	2017-2018
314	1535	106	2	21.75	2017-2018
315	1535	127	2	23.25	2017-2018
316	1535	115	2	7.5	2017-2018
317	1535	103	2	13.5	2017-2018
318	1535	187	2	20.25	2017-2018
319	570	99	2	21	2017-2018
320	570	50	2	23.25	2017-2018
321	570	106	2	12.75	2017-2018
322	570	127	2	23.5	2017-2018
323	570	115	2	15	2017-2018
324	570	103	2	24.75	2017-2018
325	570	187	2	22.5	2017-2018
326	277	99	2	27	2017-2018
327	277	50	2	30	2017-2018
328	277	106	2	22.5	2017-2018
329	277	127	2	24	2017-2018
330	277	115	2	30	2017-2018
331	277	103	2	25.5	2017-2018
332	277	187	2	30	2017-2018
333	288	99	2	18	2017-2018
334	288	50	2	19.5	2017-2018
335	288	106	2	22.5	2017-2018
336	288	127	2	22.5	2017-2018
337	288	115	2	20	2017-2018
338	288	103	2	18	2017-2018
339	288	187	2	16.5	2017-2018
340	360	99	2	27	2017-2018
341	360	50	2	18.5	2017-2018
342	360	106	2	15	2017-2018
343	360	127	2	23.5	2017-2018
344	360	115	2	21.25	2017-2018
345	360	103	2	21	2017-2018
346	360	187	2	26.5	2017-2018
347	1532	99	2	16	2017-2018
348	1532	50	2	16.5	2017-2018
349	1532	127	2	16	2017-2018
350	1532	210	2	11.5	2017-2018
351	1532	103	2	14	2017-2018
352	1532	187	2	24.5	2017-2018
353	411	99	2	27	2017-2018
354	411	50	2	18	2017-2018
355	411	106	2	27	2017-2018
356	411	127	2	25.5	2017-2018
357	411	115	2	21	2017-2018
358	411	103	2	27	2017-2018
359	411	187	2	24	2017-2018
360	684	99	2	19.75	2017-2018
361	684	50	2	12	2017-2018
362	684	127	2	19.25	2017-2018
363	684	115	2	17.5	2017-2018
364	684	103	2	15.25	2017-2018
365	684	187	2	22.5	2017-2018
366	683	99	2	19.25	2017-2018
367	683	50	2	20	2017-2018
368	683	106	2	13.25	2017-2018
369	683	127	2	22.5	2017-2018
370	683	115	2	18	2017-2018
371	683	103	2	17.75	2017-2018
372	683	187	2	21.75	2017-2018
373	354	99	2	27	2017-2018
374	354	50	2	18	2017-2018
375	354	106	2	27	2017-2018
376	354	127	2	25.5	2017-2018
377	354	115	2	21	2017-2018
378	354	103	2	27	2017-2018
379	354	187	2	24	2017-2018
380	783	99	2	25.5	2017-2018
381	783	50	2	25.5	2017-2018
382	783	127	2	19.5	2017-2018
383	783	115	2	16	2017-2018
384	783	103	2	18.5	2017-2018
385	783	187	2	28	2017-2018
386	570	99	4	21	2017-2018
387	570	50	4	23.25	2017-2018
388	570	106	4	12.75	2017-2018
389	570	127	4	23.5	2017-2018
390	570	115	4	15	2017-2018
391	570	103	4	24.75	2017-2018
392	570	187	4	22.5	2017-2018
393	1535	99	4	61.5	2017-2018
394	1535	50	4	65.25	2017-2018
395	1535	106	4	53.25	2017-2018
396	1535	127	4	68.75	2017-2018
397	1535	115	4	49.5	2017-2018
398	1535	103	4	52	2017-2018
399	1535	187	4	55.25	2017-2018
400	288	99	4	60	2017-2018
401	288	50	4	47.5	2017-2018
402	288	127	4	68	2017-2018
403	288	115	4	52.5	2017-2018
404	288	103	4	60	2017-2018
405	288	187	4	58.5	2017-2018
406	277	99	4	90	2017-2018
407	277	50	4	75.5	2017-2018
408	277	106	4	75	2017-2018
409	277	127	4	90.5	2017-2018
410	277	115	4	93	2017-2018
411	277	103	4	67.5	2017-2018
412	277	187	4	96.5	2017-2018
413	684	99	4	61.75	2017-2018
414	684	50	4	33	2017-2018
415	684	106	4	21	2017-2018
416	684	127	4	43.75	2017-2018
417	684	115	4	48	2017-2018
418	684	103	4	39.75	2017-2018
419	684	187	4	57.5	2017-2018
420	354	99	4	76	2017-2018
421	354	50	4	65.25	2017-2018
422	354	106	4	67.25	2017-2018
423	354	127	4	83.25	2017-2018
424	354	115	4	82.25	2017-2018
425	354	103	4	84.75	2017-2018
426	354	187	4	74.75	2017-2018
427	1532	99	4	44	2017-2018
428	1532	50	4	48	2017-2018
429	1532	106	4	31.5	2017-2018
430	1532	127	4	49.25	2017-2018
431	1532	210	4	11.5	2017-2018
432	1532	115	4	26.25	2017-2018
433	1532	103	4	35	2017-2018
434	1532	187	4	84	2017-2018
435	1534	99	4	42.5	2017-2018
436	1534	50	4	51.25	2017-2018
437	1534	106	4	24.5	2017-2018
438	1534	127	4	65	2017-2018
439	1534	210	4	6.5	2017-2018
440	1534	115	4	38.5	2017-2018
441	1534	103	4	52	2017-2018
442	1534	187	4	80.75	2017-2018
443	783	99	4	60.5	2017-2018
444	783	50	4	58.5	2017-2018
445	783	106	4	29	2017-2018
446	783	127	4	53.5	2017-2018
447	783	115	4	52	2017-2018
448	783	103	4	53.5	2017-2018
449	783	187	4	58	2017-2018
450	360	99	4	58.5	2017-2018
451	360	50	4	38.75	2017-2018
452	360	106	4	32.75	2017-2018
453	360	127	4	77.25	2017-2018
454	360	115	4	61.5	2017-2018
455	360	103	4	46.75	2017-2018
456	360	187	4	72	2017-2018
457	683	99	4	52.5	2017-2018
458	683	50	4	70.75	2017-2018
459	683	106	4	51.75	2017-2018
460	683	127	4	73.25	2017-2018
461	683	115	4	60	2017-2018
462	683	103	4	65	2017-2018
463	683	187	4	67.25	2017-2018
464	337	99	4	27	2017-2018
465	337	50	4	49	2017-2018
466	337	106	4	15	2017-2018
467	337	127	4	49	2017-2018
468	337	115	4	45	2017-2018
469	337	103	4	59	2017-2018
470	337	187	4	62	2017-2018
471	806	99	4	80	2017-2018
472	806	50	4	74	2017-2018
473	806	106	4	34	2017-2018
474	806	127	4	71	2017-2018
475	806	115	4	80	2017-2018
476	806	103	4	78	2017-2018
477	806	187	4	71	2017-2018
478	373	99	4	69	2017-2018
479	373	50	4	62	2017-2018
480	373	106	4	48	2017-2018
481	373	127	4	69	2017-2018
482	373	115	4	60	2017-2018
483	373	103	4	60	2017-2018
484	373	187	4	76	2017-2018
485	1169	99	2	22	2017-2018
486	1169	50	2	16.5	2017-2018
487	1169	106	2	15	2017-2018
488	1169	127	2	19.5	2017-2018
489	1169	115	2	20	2017-2018
490	1169	103	2	25	2017-2018
491	1169	187	2	18	2017-2018
492	1169	99	4	35	2017-2018
493	1169	50	4	39	2017-2018
494	1169	106	4	18	2017-2018
495	1169	127	4	39	2017-2018
496	1169	115	4	18	2017-2018
497	1169	103	4	35	2017-2018
498	1169	187	4	14	2017-2018
499	411	99	4	88.25	2017-2018
500	411	50	4	77.5	2017-2018
501	411	106	4	90	2017-2018
502	411	127	4	81.5	2017-2018
503	411	115	4	73.5	2017-2018
504	411	103	4	90	2017-2018
505	411	187	4	85.25	2017-2018
506	1530	194	1	13.5	2017-2018
507	1530	117	1	21	2017-2018
508	1530	162	1	6	2017-2018
509	1530	110	1	10.5	2017-2018
510	1530	137	1	8	2017-2018
511	1530	143	1	16.5	2017-2018
512	1530	172	1	15.75	2017-2018
513	1530	195	1	11.25	2017-2018
514	1530	102	1	17.25	2017-2018
515	1530	164	1	9	2017-2018
516	1530	193	1	12.75	2017-2018
517	1530	98	1	8.25	2017-2018
518	1530	95	1	10.5	2017-2018
519	1531	194	1	16.25	2017-2018
520	1531	117	1	8	2017-2018
521	1531	162	1	11	2017-2018
522	1531	137	1	14	2017-2018
523	1531	143	1	20	2017-2018
524	1531	172	1	9	2017-2018
525	1531	102	1	17	2017-2018
526	1531	164	1	9	2017-2018
527	1531	193	1	16	2017-2018
528	1531	98	1	7.5	2017-2018
529	58	194	1	16.5	2017-2018
530	58	117	1	16.5	2017-2018
531	58	162	1	14.5	2017-2018
532	58	110	1	17	2017-2018
533	58	137	1	15	2017-2018
534	58	143	1	16	2017-2018
535	58	172	1	12	2017-2018
536	58	195	1	10	2017-2018
537	58	102	1	14	2017-2018
538	58	164	1	11.5	2017-2018
539	58	193	1	16	2017-2018
540	58	98	1	14.5	2017-2018
541	58	95	1	12	2017-2018
542	570	194	1	21	2017-2018
543	570	162	1	17.25	2017-2018
544	570	110	1	19.5	2017-2018
545	570	137	1	3.75	2017-2018
546	570	143	1	22.5	2017-2018
547	570	172	1	3	2017-2018
548	570	102	1	6.75	2017-2018
549	570	164	1	12	2017-2018
550	570	193	1	16.5	2017-2018
551	570	98	1	15	2017-2018
552	570	95	1	9	2017-2018
553	354	194	1	17.25	2017-2018
554	354	162	1	18	2017-2018
555	354	110	1	16.5	2017-2018
556	354	137	1	22.5	2017-2018
557	354	143	1	15	2017-2018
558	354	102	1	20.25	2017-2018
559	354	164	1	20.25	2017-2018
560	354	193	1	15	2017-2018
561	354	98	1	21.75	2017-2018
562	354	95	1	12	2017-2018
563	356	194	1	17	2017-2018
564	356	117	1	15.75	2017-2018
565	356	162	1	13.25	2017-2018
566	356	110	1	13	2017-2018
567	356	137	1	18.5	2017-2018
568	356	143	1	15.5	2017-2018
569	356	172	1	15	2017-2018
570	356	102	1	20.5	2017-2018
571	356	164	1	16.5	2017-2018
572	356	193	1	14.5	2017-2018
573	356	98	1	17.5	2017-2018
574	358	194	1	10.199999999999999	2017-2018
575	358	117	1	5.5999999999999996	2017-2018
576	358	162	1	15.300000000000001	2017-2018
577	358	110	1	6.75	2017-2018
578	358	137	1	15	2017-2018
579	358	143	1	15.6	2017-2018
580	358	172	1	5.5999999999999996	2017-2018
581	358	102	1	15.300000000000001	2017-2018
582	358	164	1	12.6	2017-2018
583	358	193	1	12	2017-2018
584	358	98	1	12	2017-2018
585	376	194	1	12	2017-2018
586	376	162	1	16.5	2017-2018
587	376	191	1	23.25	2017-2018
588	376	110	1	23.25	2017-2018
589	376	137	1	16.25	2017-2018
590	376	143	1	15.75	2017-2018
591	376	172	1	12.75	2017-2018
592	376	102	1	21.75	2017-2018
593	376	164	1	16.75	2017-2018
594	376	193	1	16.5	2017-2018
595	376	98	1	17.25	2017-2018
596	376	95	1	10.75	2017-2018
597	375	194	1	10.199999999999999	2017-2018
598	375	117	1	5.5999999999999996	2017-2018
599	375	162	1	15.300000000000001	2017-2018
600	375	110	1	6.75	2017-2018
601	375	137	1	15	2017-2018
602	375	143	1	15.6	2017-2018
603	375	172	1	5.5999999999999996	2017-2018
604	375	102	1	15.300000000000001	2017-2018
605	375	164	1	12.6	2017-2018
606	375	193	1	12	2017-2018
607	375	98	1	12	2017-2018
608	800	194	1	14.5	2017-2018
609	800	117	1	3.75	2017-2018
610	800	162	1	25.75	2017-2018
611	800	110	1	18	2017-2018
612	800	137	1	19	2017-2018
613	800	143	1	12.75	2017-2018
614	800	172	1	16.25	2017-2018
615	800	195	1	14.5	2017-2018
616	800	102	1	21	2017-2018
617	800	164	1	21.75	2017-2018
618	800	193	1	14.25	2017-2018
619	800	98	1	22.5	2017-2018
620	800	95	1	4	2017-2018
621	413	194	1	15	2017-2018
622	413	117	1	18	2017-2018
623	413	162	1	23	2017-2018
624	413	110	1	20	2017-2018
625	413	137	1	20	2017-2018
626	413	143	1	20	2017-2018
627	413	172	1	24	2017-2018
628	413	195	1	18	2017-2018
629	413	102	1	23	2017-2018
630	413	193	1	18	2017-2018
631	413	98	1	20	2017-2018
632	413	95	1	17	2017-2018
633	399	194	1	25	2017-2018
634	399	162	1	9	2017-2018
635	399	110	1	19	2017-2018
636	399	137	1	11	2017-2018
637	399	143	1	19	2017-2018
638	399	172	1	13	2017-2018
639	399	102	1	25	2017-2018
640	399	164	1	9	2017-2018
641	399	193	1	20	2017-2018
642	399	98	1	20	2017-2018
643	399	95	1	18	2017-2018
644	275	194	1	18.5	2017-2018
645	275	162	1	15	2017-2018
646	275	110	1	21.5	2017-2018
647	275	137	1	18.5	2017-2018
648	275	143	1	19	2017-2018
649	275	172	1	13	2017-2018
650	275	102	1	23.5	2017-2018
651	275	164	1	9	2017-2018
652	275	193	1	16	2017-2018
653	275	98	1	20	2017-2018
654	275	95	1	11.5	2017-2018
655	164	194	1	23.25	2017-2018
656	164	117	1	15.75	2017-2018
657	164	162	1	21.75	2017-2018
658	164	110	1	24.75	2017-2018
659	164	137	1	30	2017-2018
660	164	143	1	22.5	2017-2018
661	164	172	1	18	2017-2018
662	164	102	1	22.5	2017-2018
663	164	164	1	13.5	2017-2018
664	164	193	1	18.75	2017-2018
665	164	98	1	18	2017-2018
666	164	95	1	21.75	2017-2018
667	549	194	1	15	2017-2018
668	549	162	1	16.5	2017-2018
669	549	110	1	21	2017-2018
670	549	137	1	21	2017-2018
671	549	143	1	21	2017-2018
672	549	102	1	18	2017-2018
673	549	164	1	16.5	2017-2018
674	549	193	1	12	2017-2018
675	549	98	1	15	2017-2018
676	549	95	1	16.5	2017-2018
677	1535	194	1	17.25	2017-2018
678	1535	117	1	10.5	2017-2018
679	1535	162	1	16.5	2017-2018
680	1535	110	1	18	2017-2018
681	1535	137	1	14.25	2017-2018
682	1535	143	1	21	2017-2018
683	1535	172	1	14.25	2017-2018
684	1535	102	1	16.5	2017-2018
685	1535	164	1	15.75	2017-2018
686	1535	193	1	15	2017-2018
687	1535	98	1	15	2017-2018
688	1535	95	1	13.5	2017-2018
689	570	194	3	47.25	2017-2018
690	570	117	3	26.25	2017-2018
691	570	162	3	50.5	2017-2018
692	570	110	3	61.5	2017-2018
693	570	137	3	37	2017-2018
694	570	143	3	68	2017-2018
695	570	172	3	17	2017-2018
696	570	195	3	12.25	2017-2018
697	570	102	3	45.25	2017-2018
698	570	164	3	29.5	2017-2018
699	570	193	3	36.5	2017-2018
700	570	98	3	58.75	2017-2018
701	570	95	3	24.75	2017-2018
702	1535	194	3	50.5	2017-2018
703	1535	117	3	57.75	2017-2018
704	1535	162	3	69	2017-2018
705	1535	110	3	70.5	2017-2018
706	1535	137	3	45.75	2017-2018
707	1535	143	3	71.25	2017-2018
708	1535	172	3	56.25	2017-2018
709	1535	195	3	43.75	2017-2018
710	1535	102	3	56.75	2017-2018
711	1535	164	3	70	2017-2018
712	1535	193	3	46.5	2017-2018
713	1535	98	3	58.75	2017-2018
714	1535	95	3	43.25	2017-2018
715	549	194	3	43	2017-2018
716	549	117	3	25	2017-2018
717	549	162	3	44.5	2017-2018
718	549	110	3	60	2017-2018
719	549	137	3	39	2017-2018
720	549	143	3	60	2017-2018
721	549	172	3	49	2017-2018
722	549	195	3	28	2017-2018
723	549	102	3	46	2017-2018
724	549	164	3	44.5	2017-2018
725	549	193	3	44	2017-2018
726	549	98	3	50	2017-2018
727	549	95	3	44.5	2017-2018
728	800	194	3	61.75	2017-2018
729	800	117	3	23	2017-2018
730	800	162	3	78.25	2017-2018
731	800	110	3	33.75	2017-2018
732	800	137	3	47	2017-2018
733	800	143	3	47.75	2017-2018
734	800	172	3	40.75	2017-2018
735	800	195	3	67	2017-2018
736	800	102	3	84	2017-2018
737	800	164	3	60.25	2017-2018
738	800	193	3	37	2017-2018
739	800	98	3	68	2017-2018
740	800	95	3	33.75	2017-2018
741	399	194	3	63.5	2017-2018
742	399	117	3	49	2017-2018
743	399	162	3	40.5	2017-2018
744	399	110	3	82	2017-2018
745	399	137	3	67	2017-2018
746	399	143	3	68	2017-2018
747	399	172	3	58.5	2017-2018
748	399	195	3	49	2017-2018
749	399	102	3	81	2017-2018
750	399	164	3	40.5	2017-2018
751	399	193	3	79.5	2017-2018
752	399	98	3	69	2017-2018
753	399	95	3	46	2017-2018
754	376	194	3	40	2017-2018
755	376	117	3	12.25	2017-2018
756	376	162	3	53.25	2017-2018
757	376	191	3	23.25	2017-2018
758	376	110	3	58.25	2017-2018
759	376	137	3	53	2017-2018
760	376	143	3	47.25	2017-2018
761	376	172	3	56.5	2017-2018
762	376	195	3	56	2017-2018
763	376	102	3	84	2017-2018
764	376	164	3	51	2017-2018
765	376	193	3	55	2017-2018
766	376	98	3	59.75	2017-2018
767	376	95	3	23.5	2017-2018
768	675	194	5	60	2017-2018
769	675	117	5	63	2017-2018
770	675	162	5	65	2017-2018
771	675	110	5	83	2017-2018
772	675	137	5	72	2017-2018
773	675	143	5	72	2017-2018
774	675	172	5	83	2017-2018
775	675	195	5	70	2017-2018
776	675	102	5	72	2017-2018
777	675	164	5	66	2017-2018
778	675	193	5	70	2017-2018
779	675	98	5	76	2017-2018
780	675	95	5	48	2017-2018
781	354	194	3	47	2017-2018
782	354	117	3	44.75	2017-2018
783	354	162	3	56.5	2017-2018
784	354	110	3	69	2017-2018
785	354	137	3	59.25	2017-2018
786	354	143	3	57	2017-2018
787	354	172	3	53.5	2017-2018
788	354	195	3	31.5	2017-2018
789	354	102	3	69.25	2017-2018
790	354	164	3	57	2017-2018
791	354	193	3	60.5	2017-2018
792	354	98	3	65.5	2017-2018
793	354	95	3	47	2017-2018
794	1531	194	3	37.25	2017-2018
795	1531	117	3	20.5	2017-2018
796	1531	162	3	18	2017-2018
797	1531	110	3	53	2017-2018
798	1531	137	3	31.5	2017-2018
799	1531	143	3	55	2017-2018
800	1531	172	3	33.5	2017-2018
801	1531	195	3	24.5	2017-2018
802	1531	102	3	52	2017-2018
803	1531	164	3	12.5	2017-2018
804	1531	193	3	30	2017-2018
805	1531	98	3	25	2017-2018
806	1531	95	3	14	2017-2018
807	1530	194	3	45	2017-2018
808	1530	117	3	54.25	2017-2018
809	1530	162	3	39.25	2017-2018
810	1530	110	3	45.5	2017-2018
811	1530	137	3	43	2017-2018
812	1530	143	3	62	2017-2018
813	1530	172	3	33.25	2017-2018
814	1530	195	3	41.25	2017-2018
815	1530	102	3	57.5	2017-2018
816	1530	164	3	35.25	2017-2018
817	1530	193	3	44.25	2017-2018
818	1530	98	3	41.5	2017-2018
819	1530	95	3	28	2017-2018
820	413	194	3	47	2017-2018
821	413	117	3	46	2017-2018
822	413	162	3	55	2017-2018
823	413	110	3	59	2017-2018
824	413	137	3	48	2017-2018
825	413	143	3	73	2017-2018
826	413	172	3	56	2017-2018
827	413	195	3	53	2017-2018
828	413	102	3	62	2017-2018
829	413	164	3	35	2017-2018
830	413	193	3	60	2017-2018
831	413	98	3	66	2017-2018
832	413	95	3	45	2017-2018
833	356	194	3	55.5	2017-2018
834	356	117	3	41.100000000000001	2017-2018
835	356	162	3	44.75	2017-2018
836	356	110	3	54.149999999999999	2017-2018
837	356	137	3	50.850000000000001	2017-2018
838	356	143	3	44.399999999999999	2017-2018
839	356	172	3	71	2017-2018
840	356	195	3	35	2017-2018
841	356	102	3	66	2017-2018
842	356	164	3	51.5	2017-2018
843	356	193	3	41.649999999999999	2017-2018
844	356	98	3	77	2017-2018
845	356	95	3	36.75	2017-2018
846	164	194	3	68.75	2017-2018
847	164	117	3	57.75	2017-2018
848	164	162	3	62	2017-2018
849	164	110	3	66.75	2017-2018
850	164	137	3	86	2017-2018
851	164	143	3	68	2017-2018
852	164	172	3	42.5	2017-2018
853	164	195	3	35	2017-2018
854	164	102	3	62.75	2017-2018
855	164	164	3	41.5	2017-2018
856	164	193	3	62.5	2017-2018
857	164	98	3	58.25	2017-2018
858	164	95	3	74.25	2017-2018
859	58	194	3	52.75	2017-2018
860	58	117	3	56.25	2017-2018
861	58	162	3	49.75	2017-2018
862	58	110	3	74.5	2017-2018
863	58	137	3	64.5	2017-2018
864	58	143	3	66.5	2017-2018
865	58	172	3	49.5	2017-2018
866	58	195	3	43	2017-2018
867	58	102	3	45.5	2017-2018
868	58	164	3	48.75	2017-2018
869	58	193	3	55	2017-2018
870	58	98	3	53.25	2017-2018
871	58	95	3	46	2017-2018
872	375	194	3	53.950000000000003	2017-2018
873	375	117	3	38.850000000000001	2017-2018
874	375	162	3	42.450000000000003	2017-2018
875	375	110	3	56.600000000000001	2017-2018
876	375	137	3	64.849999999999994	2017-2018
877	375	143	3	61.100000000000001	2017-2018
878	375	172	3	38	2017-2018
879	375	195	3	33.25	2017-2018
880	375	102	3	73.049999999999997	2017-2018
881	375	164	3	49.25	2017-2018
882	375	193	3	34.75	2017-2018
883	375	98	3	61	2017-2018
884	375	95	3	23.649999999999999	2017-2018
885	373	194	2	27	2017-2018
886	373	117	2	20	2017-2018
887	373	162	2	27	2017-2018
888	373	110	2	21	2017-2018
889	373	137	2	20	2017-2018
890	373	143	2	21	2017-2018
891	373	172	2	21	2017-2018
892	373	102	2	20	2017-2018
893	373	164	2	27	2017-2018
894	373	193	2	27	2017-2018
895	373	98	2	20	2017-2018
896	806	194	2	21	2017-2018
897	806	117	2	17	2017-2018
898	806	162	2	21	2017-2018
899	806	110	2	21	2017-2018
900	806	137	2	18	2017-2018
901	806	143	2	20	2017-2018
902	806	172	2	18	2017-2018
903	806	195	2	20	2017-2018
904	806	102	2	21	2017-2018
905	806	164	2	21	2017-2018
906	806	193	2	18	2017-2018
907	806	98	2	20	2017-2018
908	806	95	2	18	2017-2018
909	1535	194	2	21	2017-2018
910	1535	162	2	18	2017-2018
911	1535	110	2	27	2017-2018
912	1535	137	2	22.75	2017-2018
913	1535	143	2	27	2017-2018
914	1535	102	2	22.5	2017-2018
915	1535	164	2	12	2017-2018
916	1535	193	2	23.25	2017-2018
917	1535	98	2	9	2017-2018
918	1535	95	2	11.25	2017-2018
919	570	194	2	25.5	2017-2018
920	570	117	2	16.5	2017-2018
921	570	162	2	22.5	2017-2018
922	570	110	2	19.5	2017-2018
923	570	137	2	18	2017-2018
924	570	143	2	10.5	2017-2018
925	570	172	2	15	2017-2018
926	570	102	2	16.5	2017-2018
927	570	193	2	22.5	2017-2018
928	570	98	2	24.75	2017-2018
929	570	95	2	12.75	2017-2018
930	372	194	2	27	2017-2018
931	372	117	2	24	2017-2018
932	372	162	2	13.5	2017-2018
933	372	110	2	30	2017-2018
934	372	137	2	24	2017-2018
935	372	143	2	30	2017-2018
936	372	172	2	22.5	2017-2018
937	372	195	2	18	2017-2018
938	372	102	2	22.5	2017-2018
939	372	164	2	24	2017-2018
940	372	193	2	24	2017-2018
941	372	98	2	24	2017-2018
942	372	95	2	16.5	2017-2018
943	58	194	2	17	2017-2018
944	58	117	2	21	2017-2018
945	58	162	2	11	2017-2018
946	58	110	2	25	2017-2018
947	58	137	2	15	2017-2018
948	58	143	2	23	2017-2018
949	58	195	2	15	2017-2018
950	58	102	2	12	2017-2018
951	58	164	2	15	2017-2018
952	58	193	2	11	2017-2018
953	58	98	2	20	2017-2018
954	58	95	2	20	2017-2018
955	374	194	2	21	2017-2018
956	374	117	2	21	2017-2018
957	374	162	2	21	2017-2018
958	374	110	2	27	2017-2018
959	374	137	2	24	2017-2018
960	374	172	2	20.25	2017-2018
961	374	102	2	29.25	2017-2018
962	374	164	2	18	2017-2018
963	374	193	2	27	2017-2018
964	374	98	2	18	2017-2018
965	683	194	2	16.75	2017-2018
966	683	117	2	16.5	2017-2018
967	683	162	2	14.75	2017-2018
968	683	110	2	20.25	2017-2018
969	683	137	2	20.5	2017-2018
970	683	143	2	15	2017-2018
971	683	172	2	17.25	2017-2018
972	683	102	2	22.75	2017-2018
973	683	164	2	15.75	2017-2018
974	683	193	2	15	2017-2018
975	683	98	2	18	2017-2018
976	683	95	2	13.5	2017-2018
977	683	101	2	1	2017-2018
978	379	194	2	13.25	2017-2018
979	379	117	2	8.25	2017-2018
980	379	162	2	13.5	2017-2018
981	379	110	2	22.25	2017-2018
982	379	137	2	21	2017-2018
983	379	143	2	14.25	2017-2018
984	379	172	2	14.25	2017-2018
985	379	102	2	25.5	2017-2018
986	379	164	2	15.5	2017-2018
987	379	193	2	12	2017-2018
988	379	98	2	15	2017-2018
989	379	95	2	5.25	2017-2018
990	1534	194	2	18	2017-2018
991	1534	162	2	14.5	2017-2018
992	1534	110	2	20.5	2017-2018
993	1534	137	2	19	2017-2018
994	1534	102	2	23.5	2017-2018
995	1534	164	2	13	2017-2018
996	1534	193	2	16.5	2017-2018
997	1534	98	2	12.5	2017-2018
998	1534	95	2	12	2017-2018
999	1532	194	2	16.25	2017-2018
1000	1532	162	2	7	2017-2018
1001	1532	191	2	15	2017-2018
1002	1532	110	2	20	2017-2018
1003	1532	137	2	12	2017-2018
1004	1532	102	2	15	2017-2018
1005	1532	164	2	9	2017-2018
1006	1532	193	2	13	2017-2018
1007	1532	98	2	11	2017-2018
1008	1532	95	2	7	2017-2018
1009	783	194	2	20	2017-2018
1010	783	162	2	15	2017-2018
1011	783	110	2	15	2017-2018
1012	783	137	2	23	2017-2018
1013	783	143	2	19	2017-2018
1014	783	172	2	19	2017-2018
1015	783	102	2	17	2017-2018
1016	783	164	2	15.5	2017-2018
1017	783	193	2	17.5	2017-2018
1018	783	98	2	19.5	2017-2018
1019	783	95	2	8	2017-2018
1020	801	194	2	17.5	2017-2018
1021	801	162	2	19.5	2017-2018
1022	801	110	2	22	2017-2018
1023	801	137	2	21	2017-2018
1024	801	143	2	20	2017-2018
1025	801	172	2	25.5	2017-2018
1026	801	102	2	25.5	2017-2018
1027	801	164	2	17.5	2017-2018
1028	801	193	2	17.5	2017-2018
1029	801	98	2	19.25	2017-2018
1030	801	95	2	17.5	2017-2018
1031	570	194	4	78	2017-2018
1032	570	117	4	55	2017-2018
1033	570	162	4	52.25	2017-2018
1034	570	110	4	65	2017-2018
1035	570	137	4	75.25	2017-2018
1036	570	143	4	56	2017-2018
1037	570	172	4	50	2017-2018
1038	570	195	4	35	2017-2018
1039	570	102	4	65.5	2017-2018
1040	570	164	4	33.25	2017-2018
1041	570	193	4	47	2017-2018
1042	570	98	4	70.25	2017-2018
1043	570	95	4	37.25	2017-2018
1044	1535	194	4	61.25	2017-2018
1045	1535	117	4	54.25	2017-2018
1046	1535	162	4	46	2017-2018
1047	1535	110	4	86.5	2017-2018
1048	1535	137	4	63	2017-2018
1049	1535	143	4	69	2017-2018
1050	1535	172	4	24.5	2017-2018
1051	1535	195	4	38.5	2017-2018
1052	1535	102	4	68	2017-2018
1053	1535	164	4	33	2017-2018
1054	1535	193	4	60	2017-2018
1055	1535	98	4	52.75	2017-2018
1056	1535	95	4	32.25	2017-2018
1057	549	194	4	58.5	2017-2018
1058	549	117	4	46	2017-2018
1059	549	162	4	60	2017-2018
1060	549	110	4	38.5	2017-2018
1061	549	137	4	67	2017-2018
1062	549	143	4	18	2017-2018
1063	549	172	4	42.5	2017-2018
1064	549	195	4	31.5	2017-2018
1065	549	102	4	52.5	2017-2018
1066	549	164	4	46	2017-2018
1067	549	193	4	55	2017-2018
1068	549	98	4	42.5	2017-2018
1069	549	95	4	42	2017-2018
1070	372	194	4	86.5	2017-2018
1071	372	117	4	80	2017-2018
1072	372	162	4	53.5	2017-2018
1073	372	110	4	96.5	2017-2018
1074	372	137	4	80	2017-2018
1075	372	143	4	93	2017-2018
1076	372	172	4	71.5	2017-2018
1077	372	195	4	70.5	2017-2018
1078	372	102	4	85.5	2017-2018
1079	372	164	4	55.5	2017-2018
1080	372	193	4	87	2017-2018
1081	372	98	4	71.25	2017-2018
1082	372	95	4	16.5	2017-2018
1083	379	194	4	57	2017-2018
1084	379	117	4	39.75	2017-2018
1085	379	162	4	45	2017-2018
1086	379	110	4	66	2017-2018
1087	379	137	4	45.5	2017-2018
1088	379	143	4	47.5	2017-2018
1089	379	172	4	54.5	2017-2018
1090	379	195	4	40	2017-2018
1091	379	102	4	65.75	2017-2018
1092	379	164	4	40	2017-2018
1093	379	193	4	52.25	2017-2018
1094	379	98	4	44.75	2017-2018
1095	379	95	4	4.25	2017-2018
1096	683	194	4	65	2017-2018
1097	683	117	4	55	2017-2018
1098	683	162	4	28.75	2017-2018
1099	683	110	4	71	2017-2018
1100	683	137	4	45	2017-2018
1101	683	143	4	58.75	2017-2018
1102	683	172	4	58.5	2017-2018
1103	683	195	4	60.25	2017-2018
1104	683	102	4	74.25	2017-2018
1105	683	164	4	34	2017-2018
1106	683	193	4	65.75	2017-2018
1107	683	98	4	63.5	2017-2018
1108	683	95	4	13.5	2017-2018
1109	58	194	4	45	2017-2018
1110	58	117	4	53	2017-2018
1111	58	162	4	51	2017-2018
1112	58	110	4	70	2017-2018
1113	58	137	4	45	2017-2018
1114	58	143	4	63	2017-2018
1115	58	172	4	40	2017-2018
1116	58	195	4	42	2017-2018
1117	58	102	4	27	2017-2018
1118	58	164	4	46	2017-2018
1119	58	193	4	33	2017-2018
1120	58	98	4	48	2017-2018
1121	58	95	4	53	2017-2018
1122	806	194	4	77	2017-2018
1123	806	117	4	45	2017-2018
1124	806	162	4	74	2017-2018
1125	806	110	4	77	2017-2018
1126	806	137	4	67	2017-2018
1127	806	143	4	76	2017-2018
1128	806	172	4	53	2017-2018
1129	806	195	4	48	2017-2018
1130	806	102	4	81	2017-2018
1131	806	164	4	49	2017-2018
1132	806	193	4	78	2017-2018
1133	806	98	4	80	2017-2018
1134	806	95	4	18	2017-2018
1135	373	194	4	69	2017-2018
1136	373	117	4	38	2017-2018
1137	373	162	4	73	2017-2018
1138	373	110	4	63	2017-2018
1139	373	137	4	59	2017-2018
1140	373	143	4	63	2017-2018
1141	373	172	4	63	2017-2018
1142	373	195	4	39	2017-2018
1143	373	102	4	76	2017-2018
1144	373	164	4	69	2017-2018
1145	373	193	4	62	2017-2018
1146	373	98	4	59	2017-2018
1147	1169	194	6	35	2017-2018
1148	1169	117	6	35	2017-2018
1149	1169	162	6	11	2017-2018
1150	1169	137	6	14	2017-2018
1151	1169	143	6	39	2017-2018
1152	1169	172	6	18	2017-2018
1153	1169	195	6	14	2017-2018
1154	1169	102	6	53	2017-2018
1155	1169	164	6	11	2017-2018
1156	1169	193	6	35	2017-2018
1157	1169	98	6	35	2017-2018
1158	374	194	4	61.25	2017-2018
1159	374	117	4	59.5	2017-2018
1160	374	162	4	57.75	2017-2018
1161	374	110	4	81.25	2017-2018
1162	374	137	4	76.5	2017-2018
1163	374	143	4	40.25	2017-2018
1164	374	172	4	57	2017-2018
1165	374	195	4	71.25	2017-2018
1166	374	102	4	71.25	2017-2018
1167	374	164	4	63.5	2017-2018
1168	374	193	4	67.25	2017-2018
1169	374	98	4	77.5	2017-2018
1170	1532	194	4	54.75	2017-2018
1171	1532	117	4	42	2017-2018
1172	1532	162	4	35	2017-2018
1173	1532	191	4	15	2017-2018
1174	1532	110	4	74.25	2017-2018
1175	1532	137	4	27.25	2017-2018
1176	1532	143	4	21	2017-2018
1177	1532	172	4	17.5	2017-2018
1178	1532	195	4	21	2017-2018
1179	1532	102	4	69.25	2017-2018
1180	1532	164	4	23	2017-2018
1181	1532	193	4	56.75	2017-2018
1182	1532	98	4	33.75	2017-2018
1183	1532	95	4	7	2017-2018
1184	1534	194	4	60	2017-2018
1185	1534	117	4	38.5	2017-2018
1186	1534	162	4	42.5	2017-2018
1187	1534	110	4	67.25	2017-2018
1188	1534	137	4	19.25	2017-2018
1189	1534	143	4	28	2017-2018
1190	1534	102	4	77.25	2017-2018
1191	1534	164	4	25.25	2017-2018
1192	1534	193	4	58.5	2017-2018
1193	1534	98	4	37	2017-2018
1194	1534	95	4	12	2017-2018
1195	783	194	4	29	2017-2018
1196	783	117	4	32	2017-2018
1197	783	162	4	34	2017-2018
1198	783	110	4	34	2017-2018
1199	783	137	4	35	2017-2018
1200	783	143	4	33	2017-2018
1201	783	172	4	36	2017-2018
1202	783	195	4	28.5	2017-2018
1203	783	102	4	32.5	2017-2018
1204	783	164	4	29.5	2017-2018
1205	783	193	4	32	2017-2018
1206	783	98	4	36	2017-2018
1207	801	194	4	56	2017-2018
1208	801	117	4	35	2017-2018
1209	801	162	4	75.5	2017-2018
1210	801	110	4	78	2017-2018
1211	801	137	4	70.849999999999994	2017-2018
1212	801	143	4	55	2017-2018
1213	801	172	4	47.350000000000001	2017-2018
1214	801	195	4	27.149999999999999	2017-2018
1215	801	102	4	77.150000000000006	2017-2018
1216	801	164	4	31.5	2017-2018
1217	801	193	4	60.350000000000001	2017-2018
1218	801	98	4	29.75	2017-2018
1219	801	95	4	17.5	2017-2018
1220	1493	194	1	17.25	2017-2018
1221	1493	162	1	18	2017-2018
1222	1493	110	1	16.5	2017-2018
1223	1493	137	1	22.5	2017-2018
1224	1493	143	1	15	2017-2018
1225	1493	102	1	20.25	2017-2018
1226	1493	164	1	20.25	2017-2018
1227	1493	193	1	15	2017-2018
1228	1493	98	1	21.75	2017-2018
1229	1493	95	1	12	2017-2018
1230	1493	194	3	47	2017-2018
1231	1493	117	3	44.75	2017-2018
1232	1493	162	3	56.5	2017-2018
1233	1493	110	3	69	2017-2018
1234	1493	137	3	59.25	2017-2018
1235	1493	143	3	57	2017-2018
1236	1493	172	3	53.5	2017-2018
1237	1493	195	3	31.5	2017-2018
1238	1493	102	3	69.25	2017-2018
1239	1493	164	3	57	2017-2018
1240	1493	193	3	60.5	2017-2018
1241	1493	98	3	65.5	2017-2018
1242	1493	95	3	47	2017-2018
1243	1537	194	1	17.25	2017-2018
1244	1537	162	1	18	2017-2018
1245	1537	110	1	16.5	2017-2018
1246	1537	137	1	22.5	2017-2018
1247	1537	143	1	15	2017-2018
1248	1537	102	1	20.25	2017-2018
1249	1537	164	1	20.25	2017-2018
1250	1537	193	1	15	2017-2018
1251	1537	98	1	21.75	2017-2018
1252	1537	95	1	12	2017-2018
1253	1537	194	3	47	2017-2018
1254	1537	117	3	44.75	2017-2018
1255	1537	162	3	56.5	2017-2018
1256	1537	110	3	69	2017-2018
1257	1537	137	3	59.25	2017-2018
1258	1537	143	3	57	2017-2018
1259	1537	172	3	53.5	2017-2018
1260	1537	195	3	31.5	2017-2018
1261	1537	102	3	69.25	2017-2018
1262	1537	164	3	57	2017-2018
1263	1537	193	3	60.5	2017-2018
1264	1537	98	3	65.5	2017-2018
1265	1537	95	3	47	2017-2018
1266	1531	120	1	6.5	2017-2018
1267	1531	48	1	8	2017-2018
1268	1531	180	1	11.25	2017-2018
1269	1531	178	1	3	2017-2018
1270	1530	120	1	10	2017-2018
1271	1530	48	1	8.25	2017-2018
1272	1530	179	1	3.75	2017-2018
1273	1530	180	1	5.25	2017-2018
1274	286	120	1	8	2017-2018
1275	286	48	1	27	2017-2018
1276	286	179	1	8	2017-2018
1277	286	180	1	23.5	2017-2018
1278	286	178	1	15	2017-2018
1279	1478	120	1	14	2017-2018
1280	1478	48	1	23	2017-2018
1281	1478	179	1	15.5	2017-2018
1282	1478	180	1	26	2017-2018
1283	1478	178	1	13	2017-2018
1284	285	120	1	15	2017-2018
1285	285	48	1	21	2017-2018
1286	285	179	1	15	2017-2018
1287	285	180	1	22	2017-2018
1288	285	178	1	15	2017-2018
1289	284	120	1	16	2017-2018
1290	284	48	1	18	2017-2018
1291	284	179	1	15	2017-2018
1292	284	180	1	24	2017-2018
1293	284	178	1	15	2017-2018
1294	570	120	1	5.25	2017-2018
1295	570	48	1	6.75	2017-2018
1296	570	179	1	11.25	2017-2018
1297	570	180	1	20.25	2017-2018
1298	570	178	1	5.25	2017-2018
1299	1535	120	1	12.75	2017-2018
1300	1535	48	1	16.5	2017-2018
1301	1535	179	1	12	2017-2018
1302	1535	180	1	19.5	2017-2018
1303	1535	178	1	15	2017-2018
1304	275	120	1	17	2017-2018
1305	275	48	1	17	2017-2018
1306	275	179	1	15	2017-2018
1307	275	180	1	18	2017-2018
1308	275	178	1	15	2017-2018
1309	282	120	1	11	2017-2018
1310	282	48	1	23	2017-2018
1311	282	179	1	11	2017-2018
1312	282	180	1	23	2017-2018
1313	282	178	1	12	2017-2018
1314	247	120	1	15	2017-2018
1315	247	48	1	19	2017-2018
1316	247	179	1	15.5	2017-2018
1317	247	180	1	22.5	2017-2018
1318	247	178	1	15	2017-2018
1319	2	120	1	8.5	2017-2018
1320	2	48	1	15.5	2017-2018
1321	2	179	1	12.5	2017-2018
1322	2	180	1	14	2017-2018
1323	2	178	1	11.5	2017-2018
1324	288	179	1	18	2017-2018
1325	288	180	1	13.5	2017-2018
1326	288	178	1	13.5	2017-2018
1327	255	120	2	22.5	2017-2018
1328	255	48	2	21	2017-2018
1329	255	180	2	27	2017-2018
1330	279	120	2	16.5	2017-2018
1331	279	48	2	19.5	2017-2018
1332	279	180	2	25.5	2017-2018
1333	2	120	2	13	2017-2018
1334	2	48	2	19	2017-2018
1335	2	180	2	18	2017-2018
1336	1535	120	2	9	2017-2018
1337	1535	48	2	13.5	2017-2018
1338	1535	180	2	16.5	2017-2018
1339	570	120	2	5.25	2017-2018
1340	570	48	2	19.5	2017-2018
1341	570	180	2	9	2017-2018
1342	165	120	2	21	2017-2018
1343	165	48	2	20.25	2017-2018
1344	165	180	2	21	2017-2018
1345	570	120	3	35	2017-2018
1346	570	48	3	34.75	2017-2018
1347	570	179	3	11.25	2017-2018
1348	570	180	3	67.5	2017-2018
1349	570	178	3	5.25	2017-2018
1350	1535	120	3	46	2017-2018
1351	1535	48	3	60.25	2017-2018
1352	1535	179	3	12	2017-2018
1353	1535	180	3	61.5	2017-2018
1354	1535	178	3	15	2017-2018
1355	288	120	3	35	2017-2018
1356	288	48	3	53	2017-2018
1357	288	180	3	48.5	2017-2018
1358	288	178	3	13.5	2017-2018
1359	275	120	3	38	2017-2018
1360	275	48	3	59	2017-2018
1361	275	179	3	15	2017-2018
1362	275	180	3	42.5	2017-2018
1363	275	178	3	15	2017-2018
1364	1531	120	3	29.5	2017-2018
1365	1531	48	3	20.5	2017-2018
1366	1531	180	3	13.25	2017-2018
1367	1531	178	3	3	2017-2018
1368	1530	120	3	24	2017-2018
1369	1530	48	3	36.25	2017-2018
1370	1530	179	3	3.75	2017-2018
1371	1530	180	3	22.75	2017-2018
1372	1478	120	3	60.350000000000001	2017-2018
1373	1478	48	3	73.75	2017-2018
1374	1478	179	3	15.5	2017-2018
1375	1478	180	3	80.25	2017-2018
1376	1478	178	3	13	2017-2018
1377	286	120	3	42.149999999999999	2017-2018
1378	286	48	3	88.25	2017-2018
1379	286	179	3	8	2017-2018
1380	286	180	3	69	2017-2018
1381	286	178	3	15	2017-2018
1382	776	120	3	33.5	2017-2018
1383	776	48	3	42.25	2017-2018
1384	776	179	3	16.5	2017-2018
1385	776	180	3	41	2017-2018
1386	776	178	3	12	2017-2018
1387	247	120	3	46.5	2017-2018
1388	247	48	3	54	2017-2018
1389	247	179	3	15.5	2017-2018
1390	247	180	3	57.5	2017-2018
1391	247	178	3	15	2017-2018
1392	282	120	3	28.5	2017-2018
1393	282	48	3	75	2017-2018
1394	282	179	3	11	2017-2018
1395	282	180	3	72	2017-2018
1396	282	178	3	12	2017-2018
1397	2	120	3	58.25	2017-2018
1398	2	48	3	65.25	2017-2018
1399	2	179	3	18.75	2017-2018
1400	2	180	3	49	2017-2018
1401	2	178	3	17.25	2017-2018
1402	285	120	3	54	2017-2018
1403	285	48	3	74	2017-2018
1404	285	179	3	15	2017-2018
1405	285	180	3	85	2017-2018
1406	285	178	3	15	2017-2018
1407	284	120	3	40.5	2017-2018
1408	284	48	3	66	2017-2018
1409	284	179	3	15	2017-2018
1410	284	180	3	65	2017-2018
1411	284	178	3	15	2017-2018
1412	570	120	4	5.25	2017-2018
1413	570	48	4	65	2017-2018
1414	570	180	4	33.5	2017-2018
1415	1535	120	4	21.25	2017-2018
1416	1535	48	4	53.75	2017-2018
1417	1535	180	4	33.5	2017-2018
1418	288	120	4	41	2017-2018
1419	288	48	4	54	2017-2018
1420	288	180	4	56	2017-2018
1421	165	120	4	42	2017-2018
1422	165	48	4	62.25	2017-2018
1423	165	180	4	66.5	2017-2018
1424	1531	120	2	60.75	2017-2018
1425	1531	48	2	64.25	2017-2018
1426	1531	180	2	53	2017-2018
1427	1534	120	4	26.5	2017-2018
1428	1534	48	4	39.5	2017-2018
1429	1534	180	4	42	2017-2018
1430	280	120	4	56.850000000000001	2017-2018
1431	280	48	4	81	2017-2018
1432	280	180	4	78.5	2017-2018
1433	256	120	4	88.75	2017-2018
1434	256	48	4	90.5	2017-2018
1435	256	180	4	91.5	2017-2018
1436	782	120	4	43.5	2017-2018
1437	782	48	4	50	2017-2018
1438	782	180	4	63.5	2017-2018
1439	2	120	4	51	2017-2018
1440	2	48	4	64	2017-2018
1441	2	180	4	53	2017-2018
1442	279	120	4	41.5	2017-2018
1443	279	48	4	58.5	2017-2018
1444	279	180	4	60.5	2017-2018
1445	255	120	4	61.5	2017-2018
1446	255	48	4	67	2017-2018
1447	255	180	4	80	2017-2018
1448	257	120	4	51.75	2017-2018
1449	257	48	4	67.5	2017-2018
1450	257	180	4	59.75	2017-2018
1451	1433	120	4	3.5	2017-2018
1452	1433	48	4	7	2017-2018
1453	1433	180	4	18	2017-2018
1454	256	120	2	24	2017-2018
1455	256	48	2	24	2017-2018
1456	256	180	2	25	2017-2018
1457	288	120	2	16.5	2017-2018
1458	288	48	2	12	2017-2018
1459	288	180	2	21	2017-2018
1460	280	120	2	14	2017-2018
1461	280	48	2	25	2017-2018
1462	280	180	2	26	2017-2018
1463	257	120	2	17.25	2017-2018
1464	257	48	2	18.5	2017-2018
1465	257	180	2	21.25	2017-2018
1466	1532	120	2	17	2017-2018
1467	1532	48	2	17	2017-2018
1468	1532	180	2	18	2017-2018
1469	782	120	2	12.5	2017-2018
1470	782	48	2	16	2017-2018
1471	782	180	2	25	2017-2018
1472	1474	144	1	21.5	2017-2018
1473	1474	202	1	19.5	2017-2018
1474	1474	214	1	16.5	2017-2018
1475	1474	119	1	4.5	2017-2018
1476	1474	94	1	20.25	2017-2018
1477	1473	144	1	19.5	2017-2018
1478	1473	202	1	12.75	2017-2018
1479	1473	214	1	15.75	2017-2018
1480	1473	119	1	2.25	2017-2018
1481	1473	94	1	19.5	2017-2018
1482	113	144	1	19.5	2017-2018
1483	113	202	1	15.35	2017-2018
1484	113	214	1	18.75	2017-2018
1485	113	119	1	17.25	2017-2018
1486	113	94	1	15	2017-2018
1487	2	144	1	14	2017-2018
1488	2	202	1	12	2017-2018
1489	2	214	1	14	2017-2018
1490	2	119	1	13	2017-2018
1491	2	94	1	11	2017-2018
1492	549	144	1	16.5	2017-2018
1493	549	202	1	15	2017-2018
1494	549	214	1	18	2017-2018
1495	549	119	1	13.5	2017-2018
1496	549	94	1	16.5	2017-2018
1497	105	144	1	10	2017-2018
1498	105	202	1	13.5	2017-2018
1499	105	214	1	18	2017-2018
1500	105	119	1	16.5	2017-2018
1501	105	94	1	17.25	2017-2018
1502	74	144	1	21	2017-2018
1503	74	202	1	12	2017-2018
1504	74	214	1	23	2017-2018
1505	74	119	1	10.5	2017-2018
1506	74	94	1	16	2017-2018
1507	106	144	1	22.75	2017-2018
1508	106	202	1	15.5	2017-2018
1509	106	214	1	23.25	2017-2018
1510	106	119	1	22	2017-2018
1511	106	94	1	21.5	2017-2018
1512	75	144	1	26	2017-2018
1513	75	202	1	21.75	2017-2018
1514	75	214	1	29	2017-2018
1515	75	119	1	18.75	2017-2018
1516	75	94	1	19.75	2017-2018
1517	570	144	1	13.5	2017-2018
1518	570	202	1	23	2017-2018
1519	570	214	1	16.5	2017-2018
1520	570	119	1	13	2017-2018
1521	570	94	1	17.25	2017-2018
1522	1535	144	1	13.5	2017-2018
1523	1535	202	1	9.75	2017-2018
1524	1535	214	1	16.5	2017-2018
1525	1535	119	1	6.75	2017-2018
1526	1535	94	1	15	2017-2018
1527	100	144	1	21	2017-2018
1528	100	202	1	18.25	2017-2018
1529	100	214	1	15.5	2017-2018
1530	100	119	1	8	2017-2018
1531	100	94	1	18	2017-2018
1532	318	144	1	20.25	2017-2018
1533	318	202	1	20.25	2017-2018
1534	318	214	1	25	2017-2018
1535	318	119	1	3	2017-2018
1536	318	94	1	21	2017-2018
1537	111	144	1	26.25	2017-2018
1538	111	202	1	21	2017-2018
1539	111	214	1	26.25	2017-2018
1540	111	119	1	16.5	2017-2018
1541	111	94	1	24	2017-2018
1542	102	144	1	19.5	2017-2018
1543	102	214	1	27	2017-2018
1544	102	119	1	6	2017-2018
1545	102	94	1	4.5	2017-2018
1546	1535	144	2	21.75	2017-2018
1547	1535	202	2	20.25	2017-2018
1548	1535	214	2	24	2017-2018
1549	1535	94	2	19.5	2017-2018
1550	116	144	2	18	2017-2018
1551	116	202	2	19.5	2017-2018
1552	116	214	2	21	2017-2018
1553	116	94	2	7.5	2017-2018
1554	110	144	2	15	2017-2018
1555	110	202	2	11.25	2017-2018
1556	110	214	2	12	2017-2018
1557	110	94	2	13.5	2017-2018
1558	2	144	2	24	2017-2018
1559	2	214	2	14	2017-2018
1560	2	94	2	23	2017-2018
1561	1185	144	2	27.5	2017-2018
1562	1185	202	2	19	2017-2018
1563	1185	214	2	24.5	2017-2018
1564	1185	94	2	19.5	2017-2018
1565	109	144	2	18	2017-2018
1566	109	202	2	21	2017-2018
1567	109	214	2	9.75	2017-2018
1568	109	94	2	8.25	2017-2018
1569	101	144	2	27	2017-2018
1570	101	202	2	7.5	2017-2018
1571	101	214	2	26.25	2017-2018
1572	101	94	2	26.25	2017-2018
1573	570	144	2	21	2017-2018
1574	570	202	2	10.5	2017-2018
1575	570	214	2	18.75	2017-2018
1576	570	94	2	10.5	2017-2018
1577	104	144	2	24.75	2017-2018
1578	104	202	2	18	2017-2018
1579	104	214	2	23.25	2017-2018
1580	104	94	2	15.75	2017-2018
1581	570	144	4	52.5	2017-2018
1582	570	202	4	17.5	2017-2018
1583	570	214	4	55.5	2017-2018
1584	570	94	4	38.5	2017-2018
1585	1535	144	4	51.5	2017-2018
1586	1535	202	4	58.75	2017-2018
1587	1535	214	4	53.75	2017-2018
1588	1535	94	4	56.25	2017-2018
1589	549	144	4	55	2017-2018
1590	549	202	4	42.75	2017-2018
1591	549	214	4	66.5	2017-2018
1592	549	94	4	41	2017-2018
1593	97	144	4	41	2017-2018
1594	97	202	4	44.5	2017-2018
1595	97	214	4	55.5	2017-2018
1596	97	94	4	68	2017-2018
1601	110	144	4	51	2017-2018
1602	110	202	4	42.75	2017-2018
1603	110	214	4	45	2017-2018
1604	110	94	4	42.5	2017-2018
1605	109	144	4	71	2017-2018
1606	109	202	4	60	2017-2018
1607	109	214	4	76.75	2017-2018
1608	109	94	4	26.25	2017-2018
1609	101	144	4	97	2017-2018
1610	101	202	4	42.5	2017-2018
1611	101	214	4	75.25	2017-2018
1612	101	94	4	58.25	2017-2018
1613	108	144	4	75.5	2017-2018
1614	108	202	4	38	2017-2018
1615	108	214	4	69.5	2017-2018
1616	108	94	4	51.5	2017-2018
1617	1475	144	4	56.850000000000001	2017-2018
1618	1475	202	4	41.649999999999999	2017-2018
1619	1475	214	4	47.350000000000001	2017-2018
1620	1475	94	4	38.5	2017-2018
1621	103	144	4	74	2017-2018
1622	103	202	4	75	2017-2018
1623	103	214	4	87.75	2017-2018
1624	103	94	4	70.75	2017-2018
1625	81	144	4	45.5	2017-2018
1626	81	202	4	50.75	2017-2018
1627	81	214	4	57.75	2017-2018
1628	81	94	4	42	2017-2018
1629	13	129	1	22.5	2017-2018
1630	13	133	1	21.75	2017-2018
1631	13	158	1	24.75	2017-2018
1632	13	216	1	15	2017-2018
1633	13	131	1	20.25	2017-2018
1634	13	217	1	20.25	2017-2018
1635	13	199	1	21.75	2017-2018
1636	13	114	1	21.75	2017-2018
1637	1531	129	1	11.25	2017-2018
1638	1531	133	1	9.5	2017-2018
1639	1531	158	1	15	2017-2018
1640	1531	131	1	13.25	2017-2018
1641	1531	217	1	7.5	2017-2018
1642	1531	199	1	11	2017-2018
1643	1531	114	1	30	2017-2018
1644	1530	129	1	10.5	2017-2018
1645	1530	133	1	5.25	2017-2018
1646	1530	158	1	15	2017-2018
1647	1530	216	1	15	2017-2018
1648	1530	131	1	6.75	2017-2018
1649	1530	217	1	4.5	2017-2018
1650	1530	199	1	10.5	2017-2018
1651	1530	114	1	15	2017-2018
1652	337	129	1	14	2017-2018
1653	337	133	1	14.5	2017-2018
1654	337	158	1	15	2017-2018
1655	337	216	1	9.5	2017-2018
1656	337	131	1	10.5	2017-2018
1657	337	217	1	14	2017-2018
1658	337	199	1	15	2017-2018
1659	337	114	1	15	2017-2018
1660	275	129	1	16.5	2017-2018
1661	275	133	1	14	2017-2018
1662	275	158	1	21	2017-2018
1663	275	216	1	13	2017-2018
1664	275	131	1	16	2017-2018
1665	275	217	1	15	2017-2018
1666	275	199	1	20.5	2017-2018
1667	275	114	1	22.5	2017-2018
1668	1479	129	1	6.75	2017-2018
1669	1479	133	1	10.5	2017-2018
1670	1479	158	1	30	2017-2018
1671	1479	131	1	13.75	2017-2018
1672	1479	217	1	10.5	2017-2018
1673	1479	199	1	19.5	2017-2018
1674	1479	114	1	23.25	2017-2018
1675	80	129	1	16.5	2017-2018
1676	80	133	1	13.5	2017-2018
1677	80	158	1	18.75	2017-2018
1678	80	131	1	9.75	2017-2018
1679	80	217	1	10.5	2017-2018
1680	80	199	1	17.25	2017-2018
1598	74	202	4	49	2017-2018
1599	74	214	4	49	2017-2018
1681	80	114	1	11.25	2017-2018
1682	164	129	1	18	2017-2018
1683	164	133	1	22.5	2017-2018
1684	164	158	1	26.25	2017-2018
1685	164	131	1	21	2017-2018
1686	164	217	1	16.5	2017-2018
1687	164	199	1	23.25	2017-2018
1688	164	114	1	27.75	2017-2018
1689	1174	129	1	22.5	2017-2018
1690	1174	133	1	25.5	2017-2018
1691	1174	158	1	27.75	2017-2018
1692	1174	216	1	22.5	2017-2018
1693	1174	131	1	26.25	2017-2018
1694	1174	217	1	20.25	2017-2018
1695	1174	199	1	24	2017-2018
1696	1174	114	1	26.25	2017-2018
1697	15	129	1	15.800000000000001	2017-2018
1698	15	133	1	18.199999999999999	2017-2018
1699	15	158	1	20.600000000000001	2017-2018
1700	15	216	1	14.6	2017-2018
1701	15	131	1	17	2017-2018
1702	15	217	1	22.399999999999999	2017-2018
1703	15	199	1	20.600000000000001	2017-2018
1704	15	114	1	24.800000000000001	2017-2018
1705	288	129	1	19.5	2017-2018
1706	288	133	1	18	2017-2018
1707	288	158	1	16.5	2017-2018
1708	288	216	1	18	2017-2018
1709	288	131	1	18	2017-2018
1710	288	217	1	16.5	2017-2018
1711	288	199	1	16.5	2017-2018
1712	288	114	1	15	2017-2018
1713	108	129	1	16	2017-2018
1714	108	133	1	21.5	2017-2018
1715	108	158	1	27	2017-2018
1716	108	216	1	15	2017-2018
1717	108	131	1	14	2017-2018
1718	108	217	1	16.5	2017-2018
1719	108	199	1	24	2017-2018
1720	108	114	1	23	2017-2018
1721	1535	133	1	18	2017-2018
1722	1535	158	1	19.5	2017-2018
1723	1535	216	1	8.25	2017-2018
1724	1535	217	1	9	2017-2018
1725	1535	199	1	13.5	2017-2018
1726	1535	114	1	12.75	2017-2018
1727	570	129	1	14.25	2017-2018
1728	570	133	1	15.75	2017-2018
1729	570	158	1	15.75	2017-2018
1730	570	216	1	14.25	2017-2018
1731	570	131	1	14.25	2017-2018
1732	570	217	1	18	2017-2018
1733	570	199	1	10.5	2017-2018
1734	570	114	1	17.25	2017-2018
1735	570	129	2	16.5	2017-2018
1736	570	133	2	8.25	2017-2018
1737	570	131	2	11.25	2017-2018
1738	570	217	2	13	2017-2018
1739	570	114	2	12	2017-2018
1740	165	129	2	21	2017-2018
1741	165	133	2	6	2017-2018
1742	165	131	2	24	2017-2018
1743	165	217	2	12	2017-2018
1744	165	114	2	23.25	2017-2018
1745	1535	129	2	10.5	2017-2018
1746	1535	133	2	3.5	2017-2018
1747	1535	131	2	12	2017-2018
1748	1535	217	2	9	2017-2018
1749	21	129	2	24.5	2017-2018
1750	21	133	2	25	2017-2018
1751	21	131	2	26	2017-2018
1752	21	217	2	25	2017-2018
1753	21	114	2	23.5	2017-2018
1754	337	129	2	17	2017-2018
1755	337	133	2	14	2017-2018
1756	337	199	2	16	2017-2018
1757	337	114	2	19	2017-2018
1758	1484	129	2	15	2017-2018
1759	1484	133	2	4.5	2017-2018
1760	1484	216	2	7.5	2017-2018
1761	1484	131	2	13.5	2017-2018
1762	1484	217	2	4.5	2017-2018
1763	1484	114	2	15	2017-2018
1764	1482	129	2	21.75	2017-2018
1765	1482	133	2	19.5	2017-2018
1766	1482	216	2	19.5	2017-2018
1767	1482	131	2	19.5	2017-2018
1768	1482	217	2	15	2017-2018
1769	1482	114	2	24.75	2017-2018
1770	106	129	2	11.5	2017-2018
1771	106	133	2	17	2017-2018
1772	106	158	2	10	2017-2018
1773	106	131	2	15	2017-2018
1774	106	217	2	19.25	2017-2018
1775	106	114	2	21.25	2017-2018
1776	73	129	2	12	2017-2018
1777	73	133	2	13.5	2017-2018
1778	73	216	2	6.75	2017-2018
1779	73	131	2	22.5	2017-2018
1780	73	217	2	12.75	2017-2018
1781	73	114	2	6.75	2017-2018
1782	570	129	3	38.75	2017-2018
1783	570	133	3	28	2017-2018
1784	570	158	3	45.5	2017-2018
1785	570	216	3	23	2017-2018
1786	570	131	3	26.5	2017-2018
1787	570	217	3	25	2017-2018
1788	570	199	3	10.5	2017-2018
1789	570	114	3	36.5	2017-2018
1790	1535	129	3	42.25	2017-2018
1791	1535	133	3	51.25	2017-2018
1792	1535	158	3	73.75	2017-2018
1793	1535	216	3	34.5	2017-2018
1794	1535	131	3	36.75	2017-2018
1795	1535	217	3	42.25	2017-2018
1796	1535	199	3	13.5	2017-2018
1797	1535	114	3	47.75	2017-2018
1798	288	129	3	47.5	2017-2018
1799	288	133	3	57	2017-2018
1800	288	158	3	51.5	2017-2018
1801	288	216	3	46	2017-2018
1802	288	131	3	57	2017-2018
1803	288	217	3	41.5	2017-2018
1804	288	199	3	16.5	2017-2018
1805	288	114	3	43	2017-2018
1806	275	129	3	39.25	2017-2018
1807	275	133	3	31.5	2017-2018
1808	275	158	3	52.5	2017-2018
1809	275	216	3	35.75	2017-2018
1810	275	131	3	44	2017-2018
1811	275	217	3	41.25	2017-2018
1812	275	199	3	20.5	2017-2018
1813	275	114	3	45.25	2017-2018
1814	1531	129	3	27.25	2017-2018
1815	1531	133	3	29	2017-2018
1816	1531	158	3	34.5	2017-2018
1817	1531	216	3	2	2017-2018
1818	1531	131	3	23.75	2017-2018
1819	1531	217	3	42.5	2017-2018
1820	1531	199	3	11	2017-2018
1821	1531	114	3	79	2017-2018
1822	1530	129	3	57.75	2017-2018
1823	1530	133	3	40.25	2017-2018
1824	1530	158	3	55.25	2017-2018
1825	1530	216	3	23.75	2017-2018
1826	1530	131	3	41.75	2017-2018
1827	1530	217	3	30.75	2017-2018
1828	1530	199	3	10.5	2017-2018
1829	1530	114	3	67.5	2017-2018
1830	164	129	3	40.75	2017-2018
1831	164	133	3	43.5	2017-2018
1832	164	158	3	52.5	2017-2018
1833	164	216	3	28	2017-2018
1834	164	131	3	47.25	2017-2018
1835	164	217	3	51.5	2017-2018
1836	164	199	3	23.25	2017-2018
1837	164	114	3	80.25	2017-2018
1838	1174	129	3	68	2017-2018
1839	1174	133	3	60.5	2017-2018
1840	1174	158	3	73.25	2017-2018
1841	1174	216	3	45.25	2017-2018
1842	1174	131	3	77	2017-2018
1843	1174	217	3	69.25	2017-2018
1844	1174	199	3	24	2017-2018
1845	1174	114	3	75.25	2017-2018
1846	108	129	3	64	2017-2018
1847	108	133	3	56.5	2017-2018
1848	108	158	3	81.25	2017-2018
1849	108	216	3	31	2017-2018
1850	108	131	3	24.5	2017-2018
1851	108	217	3	43	2017-2018
1852	108	199	3	24	2017-2018
1853	108	114	3	74.5	2017-2018
1854	15	129	3	15.800000000000001	2017-2018
1855	15	133	3	18.199999999999999	2017-2018
1856	15	158	3	20.600000000000001	2017-2018
1857	15	216	3	14.6	2017-2018
1858	15	131	3	17	2017-2018
1859	15	217	3	22.399999999999999	2017-2018
1860	15	199	3	20.600000000000001	2017-2018
1861	15	114	3	24.800000000000001	2017-2018
1862	1479	129	3	43.5	2017-2018
1863	1479	133	3	38.5	2017-2018
1864	1479	158	3	65	2017-2018
1865	1479	216	3	19.25	2017-2018
1866	1479	131	3	31.25	2017-2018
1867	1479	217	3	28	2017-2018
1868	1479	199	3	19.5	2017-2018
1869	1479	114	3	53	2017-2018
1870	13	129	3	45.25	2017-2018
1871	13	133	3	25.25	2017-2018
1872	13	158	3	62.5	2017-2018
1873	13	216	3	43	2017-2018
1874	13	131	3	55.25	2017-2018
1875	13	217	3	37.75	2017-2018
1876	13	199	3	21.75	2017-2018
1877	13	114	3	46.25	2017-2018
1878	80	129	3	56.75	2017-2018
1879	80	133	3	38	2017-2018
1880	80	158	3	73	2017-2018
1881	80	216	3	38.5	2017-2018
1882	80	131	3	44.75	2017-2018
1883	80	217	3	54.25	2017-2018
1884	80	199	3	17.25	2017-2018
1885	80	114	3	63.75	2017-2018
1886	337	129	3	56	2017-2018
1887	337	133	3	63.75	2017-2018
1888	337	158	3	47	2017-2018
1889	337	216	3	31.75	2017-2018
1890	337	131	3	33.25	2017-2018
1891	337	217	3	49	2017-2018
1892	337	199	3	22.5	2017-2018
1893	337	114	3	57.5	2017-2018
1894	570	129	4	41	2017-2018
1895	570	133	4	11.75	2017-2018
1896	570	216	4	28	2017-2018
1897	570	131	4	23.5	2017-2018
1898	570	217	4	37.5	2017-2018
1899	570	114	4	40	2017-2018
1900	1535	129	4	45.5	2017-2018
1901	1535	133	4	22.75	2017-2018
1902	1535	131	4	33	2017-2018
1903	1535	217	4	19.5	2017-2018
1904	1535	114	4	22.75	2017-2018
1905	288	129	4	45.5	2017-2018
1906	288	133	4	44	2017-2018
1907	288	216	4	24.5	2017-2018
1908	288	131	4	60	2017-2018
1909	288	217	4	41	2017-2018
1910	288	114	4	61.5	2017-2018
1911	165	129	4	59.5	2017-2018
1912	165	133	4	48	2017-2018
1913	165	131	4	62.5	2017-2018
1914	165	217	4	47	2017-2018
1915	165	114	4	89.75	2017-2018
1916	1532	129	4	25.25	2017-2018
1917	1532	133	4	10	2017-2018
1918	1532	216	4	11	2017-2018
1919	1532	131	4	32.5	2017-2018
1920	1532	217	4	13.75	2017-2018
1921	1532	114	4	71.5	2017-2018
1922	337	129	4	42	2017-2018
1923	337	133	4	49	2017-2018
1924	337	216	4	15	2017-2018
1925	337	131	4	22	2017-2018
1926	337	217	4	30	2017-2018
1927	337	199	4	16	2017-2018
1928	337	114	4	44	2017-2018
1929	106	129	4	50	2017-2018
1930	106	133	4	48.5	2017-2018
1931	106	158	4	10	2017-2018
1932	106	216	4	43.75	2017-2018
1933	106	131	4	50	2017-2018
1934	106	217	4	57.75	2017-2018
1935	106	114	4	63.25	2017-2018
1936	1482	129	4	42.75	2017-2018
1937	1482	133	4	51.5	2017-2018
1938	1482	216	4	19.5	2017-2018
1939	1482	131	4	59.75	2017-2018
1940	1482	217	4	57	2017-2018
1941	1482	114	4	73.75	2017-2018
1942	1484	129	4	36	2017-2018
1943	1484	133	4	15.5	2017-2018
1944	1484	216	4	7.5	2017-2018
1945	1484	131	4	40.5	2017-2018
1946	1484	217	4	31.5	2017-2018
1947	1484	114	4	82	2017-2018
1948	21	129	4	71.5	2017-2018
1949	21	133	4	59	2017-2018
1950	21	131	4	62	2017-2018
1951	21	217	4	70	2017-2018
1952	21	114	4	63.5	2017-2018
1953	1183	129	4	35	2017-2018
1954	1183	133	4	10.5	2017-2018
1955	1183	131	4	20.149999999999999	2017-2018
1956	1183	217	4	36.75	2017-2018
1957	1183	114	4	14	2017-2018
1958	73	129	4	12	2017-2018
1959	73	133	4	13.5	2017-2018
1960	73	216	4	6.75	2017-2018
1961	73	131	4	22.5	2017-2018
1962	73	217	4	12.75	2017-2018
1963	73	114	4	6.75	2017-2018
1964	782	129	4	43.5	2017-2018
1965	782	133	4	44	2017-2018
1966	782	131	4	55.5	2017-2018
1967	782	217	4	50.5	2017-2018
1968	782	114	4	61	2017-2018
1969	1534	129	4	52	2017-2018
1970	1534	133	4	8	2017-2018
1971	1534	216	4	20	2017-2018
1972	1534	131	4	34	2017-2018
1973	1534	217	4	17	2017-2018
1974	1534	114	4	59.5	2017-2018
1975	111	129	4	12	2017-2018
1976	111	133	4	13.5	2017-2018
1977	111	216	4	6.75	2017-2018
1978	111	131	4	22.5	2017-2018
1979	111	217	4	12.75	2017-2018
1980	111	114	4	6.75	2017-2018
1981	109	129	4	27	2017-2018
1982	109	133	4	27	2017-2018
1983	109	131	4	49	2017-2018
1984	109	217	4	21	2017-2018
1985	109	114	4	63	2017-2018
1986	356	129	4	33.299999999999997	2017-2018
1987	356	133	4	48.149999999999999	2017-2018
1988	356	158	4	62.600000000000001	2017-2018
1989	356	216	4	51.350000000000001	2017-2018
1990	356	131	4	55.5	2017-2018
1991	356	217	4	55.700000000000003	2017-2018
1992	356	199	4	20.5	2017-2018
1993	356	114	4	68.549999999999997	2017-2018
1994	111	129	3	27	2017-2018
1995	111	133	3	27	2017-2018
1996	111	131	3	49	2017-2018
1997	111	217	3	21	2017-2018
1998	111	114	3	63	2017-2018
1999	782	129	2	12.5	2017-2018
2000	782	133	2	15	2017-2018
2001	782	131	2	22.5	2017-2018
2002	782	217	2	20	2017-2018
2003	782	114	2	19	2017-2018
2004	1534	129	2	20.5	2017-2018
2005	1534	133	2	4.5	2017-2018
2006	1534	216	2	20	2017-2018
2007	1534	131	2	20	2017-2018
2008	1534	217	2	10	2017-2018
2009	1534	114	2	21	2017-2018
2010	1532	129	2	9.5	2017-2018
2011	1532	133	2	6.5	2017-2018
2012	1532	216	2	11	2017-2018
2013	1532	131	2	15	2017-2018
2014	1532	217	2	8.5	2017-2018
2015	1532	114	2	21	2017-2018
2016	113	144	3	35	2017-2018
2017	113	202	3	28	2017-2018
2018	113	214	3	38.5	2017-2018
2019	113	119	3	31.5	2017-2018
2020	113	94	3	21	2017-2018
2023	100	214	3	52.5	2017-2018
2026	102	144	3	61.25	2017-2018
2027	102	202	3	35	2017-2018
2028	102	214	3	63	2017-2018
2029	102	119	3	14	2017-2018
2030	102	94	3	24.5	2017-2018
2031	106	144	3	63	2017-2018
2032	106	202	3	45.5	2017-2018
2033	106	214	3	54.25	2017-2018
2034	106	119	3	29.75	2017-2018
2035	106	94	3	29.75	2017-2018
2036	318	144	3	38.5	2017-2018
2037	318	202	3	18	2017-2018
2038	318	214	3	42	2017-2018
2039	318	119	3	23	2017-2018
2040	318	94	3	35	2017-2018
2022	100	202	3	50.75	2017-2018
2024	100	119	3	10.5	2017-2018
2041	74	144	3	43.75	2017-2018
2042	74	202	3	38.5	2017-2018
2043	74	214	3	36.75	2017-2018
2044	74	119	3	19.25	2017-2018
2045	74	94	3	47.25	2017-2018
2046	105	144	3	38.5	2017-2018
2047	105	202	3	36.75	2017-2018
2048	105	214	3	49	2017-2018
2049	105	119	3	25.75	2017-2018
2050	105	94	3	28.75	2017-2018
2021	100	144	3	49	2017-2018
2025	100	94	3	36.75	2017-2018
2051	570	144	3	15.75	2017-2018
2052	570	202	3	29.75	2017-2018
2053	570	214	3	31.5	2017-2018
2054	570	119	3	14	2017-2018
2055	570	94	3	15.75	2017-2018
2056	1535	144	3	49	2017-2018
2057	1535	202	3	21	2017-2018
2058	1535	214	3	38.5	2017-2018
2059	1535	119	3	33.25	2017-2018
2060	1535	94	3	40.25	2017-2018
2061	549	144	3	35	2017-2018
2062	549	202	3	28	2017-2018
2063	549	214	3	42	2017-2018
2064	549	119	3	25	2017-2018
2065	549	94	3	35	2017-2018
2066	75	144	3	64.75	2017-2018
2067	75	202	3	52.5	2017-2018
2068	75	214	3	66.5	2017-2018
2069	75	119	3	61.25	2017-2018
2070	75	94	3	63	2017-2018
2071	2	144	3	42.5	2017-2018
2072	2	202	3	31.5	2017-2018
2073	2	214	3	15.75	2017-2018
2074	2	119	3	17.5	2017-2018
2075	2	94	3	24.5	2017-2018
2076	1474	144	3	36.75	2017-2018
2077	1474	202	3	38.5	2017-2018
2078	1474	214	3	50	2017-2018
2079	1474	119	3	45.5	2017-2018
2080	1474	94	3	49	2017-2018
2081	111	144	3	61.25	2017-2018
2082	111	202	3	49	2017-2018
2083	111	214	3	61.25	2017-2018
2084	111	119	3	38.5	2017-2018
2085	111	94	3	56	2017-2018
2086	1185	144	4	64.5	2017-2018
2087	1185	202	4	44.5	2017-2018
2088	1185	214	4	56	2017-2018
2089	1185	94	4	45.5	2017-2018
2090	116	144	4	42	2017-2018
2091	116	202	4	45.5	2017-2018
2092	116	214	4	49	2017-2018
2093	116	94	4	35	2017-2018
2094	96	144	2	16.5	2017-2018
2095	96	202	2	20	2017-2018
2096	96	214	2	20.5	2017-2018
2097	96	94	2	22.5	2017-2018
2098	96	144	4	38.5	2017-2018
2099	96	202	4	45.5	2017-2018
2100	96	214	4	48	2017-2018
2101	96	94	4	52.5	2017-2018
2102	97	144	2	16.5	2017-2018
2103	97	202	2	14	2017-2018
2104	97	214	2	16	2017-2018
2105	97	94	2	25	2017-2018
2106	1475	144	2	18.5	2017-2018
2107	1475	202	2	11.65	2017-2018
2108	1475	214	2	12.35	2017-2018
2109	1475	94	2	10.5	2017-2018
2110	72	144	2	22	2017-2018
2111	72	202	2	20	2017-2018
2112	72	214	2	24	2017-2018
2113	72	94	2	22	2017-2018
2114	72	144	4	42	2017-2018
2115	72	202	4	45.5	2017-2018
2116	72	214	4	56	2017-2018
2117	72	94	4	42	2017-2018
2118	81	144	2	19	2017-2018
2119	81	202	2	21	2017-2018
2120	81	214	2	21	2017-2018
2121	81	119	2	28.5	2017-2018
2122	81	94	2	29.5	2017-2018
2123	107	144	2	22	2017-2018
2124	107	202	2	20	2017-2018
2125	107	214	2	24	2017-2018
2126	107	94	2	22	2017-2018
2127	103	144	2	25	2017-2018
2128	103	202	2	22.5	2017-2018
2129	103	214	2	23	2017-2018
2130	103	119	2	3	2017-2018
2131	103	94	2	20	2017-2018
2132	104	144	4	57.75	2017-2018
2133	104	202	4	42	2017-2018
2134	104	214	4	54.25	2017-2018
2135	104	94	4	37	2017-2018
2136	108	144	2	23.5	2017-2018
2137	108	202	2	17	2017-2018
2138	108	214	2	20.5	2017-2018
2139	108	94	2	20	2017-2018
1597	74	144	4	44.5	2017-2018
1600	74	94	4	45.5	2017-2018
2140	74	144	2	19	2017-2018
2141	74	202	2	21	2017-2018
2142	74	214	2	21	2017-2018
2143	74	94	2	19.5	2017-2018
2144	1301	224	1	26	2017-2018
2145	1301	174	1	17	2017-2018
2146	1301	165	1	12	2017-2018
2147	1301	221	1	23	2017-2018
2148	1301	125	1	21	2017-2018
2149	1301	138	1	12	2017-2018
2150	1301	149	1	15	2017-2018
2151	1301	139	1	17	2017-2018
2152	1301	200	1	18	2017-2018
2153	1548	224	1	20	2017-2018
2154	1548	174	1	23	2017-2018
2155	1548	165	1	26	2017-2018
2156	1548	221	1	26	2017-2018
2157	1548	125	1	23	2017-2018
2158	1548	76	1	12	2017-2018
2159	1548	138	1	27	2017-2018
2160	1548	149	1	23	2017-2018
2161	1548	139	1	15	2017-2018
2162	1548	200	1	20	2017-2018
2163	552	224	1	12	2017-2018
2164	552	165	1	21	2017-2018
2165	552	221	1	15	2017-2018
2166	552	125	1	13.5	2017-2018
2167	552	76	1	10.5	2017-2018
2168	552	138	1	18	2017-2018
2169	552	149	1	10.5	2017-2018
2170	552	139	1	1.5	2017-2018
2171	552	222	1	6	2017-2018
2172	552	200	1	13.5	2017-2018
2173	1549	200	1	15	2017-2018
2174	1326	224	1	8.75	2017-2018
2175	1326	174	1	5	2017-2018
2176	1326	223	1	5.25	2017-2018
2177	1326	165	1	13.25	2017-2018
2178	1326	221	1	12.5	2017-2018
2179	1326	125	1	11	2017-2018
2180	1326	76	1	10.5	2017-2018
2181	1326	138	1	13.25	2017-2018
2182	1326	149	1	7.25	2017-2018
2183	1326	139	1	12	2017-2018
2184	1326	200	1	8.75	2017-2018
2185	765	224	1	13	2017-2018
2186	765	174	1	11	2017-2018
2187	765	165	1	12.5	2017-2018
2188	765	221	1	14	2017-2018
2189	765	125	1	12	2017-2018
2190	765	149	1	10	2017-2018
2191	765	139	1	11	2017-2018
2192	765	200	1	14	2017-2018
2193	1551	224	1	16.5	2017-2018
2194	1551	174	1	16	2017-2018
2195	1551	165	1	8	2017-2018
2196	1551	221	1	15	2017-2018
2197	1551	125	1	18	2017-2018
2198	1551	138	1	11	2017-2018
2199	1551	149	1	3	2017-2018
2200	1551	139	1	13	2017-2018
2201	1551	200	1	13	2017-2018
2202	1540	224	1	18	2017-2018
2203	1540	223	1	14.4	2017-2018
2204	1540	165	1	21	2017-2018
2205	1540	221	1	13.199999999999999	2017-2018
2206	1540	76	1	20.399999999999999	2017-2018
2207	1540	138	1	19.199999999999999	2017-2018
2208	1540	149	1	15	2017-2018
2209	1540	139	1	19.199999999999999	2017-2018
2210	1540	222	1	15	2017-2018
2211	1540	200	1	16.800000000000001	2017-2018
2212	1549	224	1	21	2017-2018
2213	1549	165	1	24	2017-2018
2214	1549	221	1	20.25	2017-2018
2215	1549	138	1	24	2017-2018
2216	1549	149	1	21	2017-2018
2217	1549	139	1	23.25	2017-2018
2218	838	224	1	5.25	2017-2018
2219	838	174	1	3	2017-2018
2220	838	165	1	4.5	2017-2018
2221	838	221	1	12	2017-2018
2222	838	125	1	13.5	2017-2018
2223	838	138	1	11.25	2017-2018
2224	838	149	1	5.25	2017-2018
2225	838	139	1	7.5	2017-2018
2226	838	222	1	9	2017-2018
2227	838	200	1	7.5	2017-2018
2228	394	224	1	17.25	2017-2018
2229	394	165	1	18	2017-2018
2230	394	221	1	18	2017-2018
2231	394	125	1	21	2017-2018
2232	394	76	1	26.5	2017-2018
2233	394	138	1	22.5	2017-2018
2234	394	149	1	18.75	2017-2018
2235	394	139	1	9	2017-2018
2236	394	222	1	18	2017-2018
2237	394	200	1	12	2017-2018
2238	764	224	1	13.5	2017-2018
2239	764	221	1	12	2017-2018
2240	764	125	1	16.5	2017-2018
2241	764	76	1	4.5	2017-2018
2242	764	138	1	10.5	2017-2018
2243	764	149	1	9	2017-2018
2244	764	139	1	12	2017-2018
2245	764	222	1	16.5	2017-2018
2246	764	200	1	13.5	2017-2018
2247	1544	224	1	17.399999999999999	2017-2018
2248	1544	223	1	17.399999999999999	2017-2018
2249	1544	165	1	18	2017-2018
2250	1544	221	1	18	2017-2018
2251	1544	125	1	19.800000000000001	2017-2018
2252	1544	76	1	19.800000000000001	2017-2018
2253	1544	138	1	17.399999999999999	2017-2018
2254	1544	149	1	17.399999999999999	2017-2018
2255	1544	139	1	16.800000000000001	2017-2018
2256	1544	222	1	17.399999999999999	2017-2018
2257	1544	200	1	15	2017-2018
2258	1550	224	1	18.600000000000001	2017-2018
2259	1550	223	1	14.4	2017-2018
2260	1550	165	1	18.600000000000001	2017-2018
2261	1550	221	1	13.199999999999999	2017-2018
2262	1550	125	1	20.399999999999999	2017-2018
2263	1550	76	1	18.600000000000001	2017-2018
2264	1550	138	1	18	2017-2018
2265	1550	149	1	20.399999999999999	2017-2018
2266	1550	139	1	18.600000000000001	2017-2018
2267	1550	222	1	21	2017-2018
2268	1550	200	1	15	2017-2018
2269	549	224	1	21	2017-2018
2270	549	223	1	12	2017-2018
2271	549	165	1	12	2017-2018
2272	549	221	1	16.5	2017-2018
2273	549	125	1	18	2017-2018
2274	549	76	1	18	2017-2018
2275	549	138	1	15	2017-2018
2276	549	149	1	15	2017-2018
2277	549	139	1	18	2017-2018
2278	549	222	1	12	2017-2018
2279	549	200	1	15	2017-2018
2280	1552	224	1	15.6	2017-2018
2281	1552	223	1	14.4	2017-2018
2282	1552	165	1	18	2017-2018
2283	1552	221	1	18	2017-2018
2284	1552	125	1	18.600000000000001	2017-2018
2285	1552	76	1	19.199999999999999	2017-2018
2286	1552	138	1	14.4	2017-2018
2287	1552	149	1	13.199999999999999	2017-2018
2288	1552	139	1	19.800000000000001	2017-2018
2289	1552	222	1	15.6	2017-2018
2290	1552	200	1	18.600000000000001	2017-2018
2291	1542	224	1	18.75	2017-2018
2292	1542	223	1	12.75	2017-2018
2293	1542	165	1	9	2017-2018
2294	1542	221	1	7.5	2017-2018
2295	1542	125	1	13.5	2017-2018
2296	1542	76	1	15.75	2017-2018
2297	1542	138	1	13.5	2017-2018
2298	1542	149	1	11.25	2017-2018
2299	1542	139	1	6.75	2017-2018
2300	1542	200	1	8.25	2017-2018
2301	1553	224	1	17.399999999999999	2017-2018
2302	1553	223	1	16.199999999999999	2017-2018
2303	1553	165	1	15.6	2017-2018
2304	1553	221	1	15	2017-2018
2305	1553	125	1	21	2017-2018
2306	1553	76	1	18.600000000000001	2017-2018
2307	1553	138	1	16.800000000000001	2017-2018
2308	1553	149	1	15	2017-2018
2309	1553	139	1	14.4	2017-2018
2310	1553	222	1	16.800000000000001	2017-2018
2311	1553	200	1	15	2017-2018
2312	1547	224	1	7.5	2017-2018
2313	1547	138	1	4.5	2017-2018
2314	1547	149	1	7.5	2017-2018
2315	1548	224	2	19.5	2017-2018
2316	1548	165	2	18	2017-2018
2317	1548	125	2	19.5	2017-2018
2318	1548	138	2	19.5	2017-2018
2319	1548	149	2	19.5	2017-2018
2320	1548	139	2	18	2017-2018
2321	1548	200	2	21	2017-2018
2322	1301	224	2	25.5	2017-2018
2323	1301	165	2	19.5	2017-2018
2324	1301	125	2	19.5	2017-2018
2325	1301	138	2	10.5	2017-2018
2326	1301	149	2	18	2017-2018
2327	1301	139	2	22.5	2017-2018
2328	1301	200	2	12	2017-2018
2329	1329	224	2	18	2017-2018
2330	1329	165	2	18	2017-2018
2331	1329	125	2	19.5	2017-2018
2332	1329	138	2	19.5	2017-2018
2333	1329	149	2	19.5	2017-2018
2334	1329	139	2	18	2017-2018
2335	1329	200	2	21	2017-2018
2336	552	224	2	19.5	2017-2018
2337	552	174	2	21	2017-2018
2338	552	165	2	16.5	2017-2018
2339	552	125	2	19.5	2017-2018
2340	552	76	2	19.5	2017-2018
2341	552	138	2	16.5	2017-2018
2342	552	149	2	21	2017-2018
2343	552	139	2	19.5	2017-2018
2344	552	200	2	19.5	2017-2018
2345	764	224	2	18	2017-2018
2346	764	174	2	18	2017-2018
2347	764	165	2	18	2017-2018
2348	764	125	2	16.5	2017-2018
2349	764	76	2	19.5	2017-2018
2350	764	138	2	18	2017-2018
2351	764	149	2	15	2017-2018
2352	764	139	2	16.5	2017-2018
2353	764	200	2	15	2017-2018
2354	765	224	2	20	2017-2018
2355	765	165	2	13	2017-2018
2356	765	125	2	24	2017-2018
2357	765	76	2	19	2017-2018
2358	765	138	2	17	2017-2018
2359	765	139	2	14	2017-2018
2360	765	200	2	17	2017-2018
2361	501	224	2	15	2017-2018
2362	501	174	2	12	2017-2018
2363	501	165	2	17.25	2017-2018
2364	501	125	2	25.5	2017-2018
2365	501	76	2	22.5	2017-2018
2366	501	138	2	24	2017-2018
2367	501	149	2	12	2017-2018
2368	501	139	2	19.5	2017-2018
2369	501	200	2	21	2017-2018
2370	1535	224	2	22.5	2017-2018
2371	1535	174	2	21	2017-2018
2372	1535	165	2	15	2017-2018
2373	1535	125	2	17.25	2017-2018
2374	1535	76	2	18	2017-2018
2375	1535	138	2	19.5	2017-2018
2376	1535	149	2	19.5	2017-2018
2377	1535	139	2	15.5	2017-2018
2378	1535	200	2	11.25	2017-2018
2379	1553	224	2	18.75	2017-2018
2380	1553	174	2	18.75	2017-2018
2381	1553	165	2	18.75	2017-2018
2382	1553	125	2	24	2017-2018
2383	1553	76	2	24	2017-2018
2384	1553	138	2	22.5	2017-2018
2385	1553	149	2	22.5	2017-2018
2386	1553	139	2	20.25	2017-2018
2387	1553	200	2	24	2017-2018
2388	1540	224	2	19.5	2017-2018
2389	1540	165	2	15.75	2017-2018
2390	1540	125	2	30	2017-2018
2391	1540	76	2	3	2017-2018
2392	1540	138	2	18.75	2017-2018
2393	1540	149	2	22.5	2017-2018
2394	1540	139	2	28.5	2017-2018
2395	1540	200	2	19.5	2017-2018
2396	570	224	2	19.5	2017-2018
2397	570	174	2	21	2017-2018
2398	570	165	2	9	2017-2018
2402	570	149	2	13.5	2017-2018
2399	570	125	2	9	2017-2018
2400	570	76	2	7.5	2017-2018
2401	570	138	2	9	2017-2018
2403	570	139	2	13.5	2017-2018
2404	570	200	2	9	2017-2018
2405	549	224	3	56	2017-2018
2406	549	174	3	28	2017-2018
2407	549	223	3	12	2017-2018
2408	549	165	3	47	2017-2018
2409	549	221	3	41.5	2017-2018
2410	549	125	3	57	2017-2018
2411	549	76	3	43	2017-2018
2412	549	138	3	50	2017-2018
2413	549	149	3	40	2017-2018
2414	549	139	3	46	2017-2018
2415	549	222	3	12	2017-2018
2416	549	200	3	40	2017-2018
2417	1551	224	3	51.5	2017-2018
2418	1551	174	3	44	2017-2018
2419	1551	165	3	43	2017-2018
2420	1551	221	3	47	2017-2018
2421	1551	125	3	48	2017-2018
2422	1551	76	3	18	2017-2018
2423	1551	138	3	46	2017-2018
2424	1551	149	3	24	2017-2018
2425	1551	139	3	36	2017-2018
2426	1551	200	3	36	2017-2018
2427	1549	125	3	51	2017-2018
2428	1549	200	3	53.5	2017-2018
2429	552	224	3	36.5	2017-2018
2430	552	174	3	17.5	2017-2018
2431	552	165	3	59.5	2017-2018
2432	552	221	3	50	2017-2018
2433	552	125	3	48.5	2017-2018
2434	552	76	3	35	2017-2018
2435	552	138	3	39	2017-2018
2436	552	149	3	31.5	2017-2018
2437	552	139	3	26	2017-2018
2438	552	222	3	6	2017-2018
2439	552	200	3	30.699999999999999	2017-2018
2440	1326	224	3	40.25	2017-2018
2441	1326	174	3	35	2017-2018
2442	1326	223	3	5.25	2017-2018
2443	1326	165	3	58.25	2017-2018
2444	1326	221	3	50.5	2017-2018
2445	1326	125	3	43.5	2017-2018
2446	1326	76	3	47	2017-2018
2447	1326	138	3	47.75	2017-2018
2448	1326	149	3	30.75	2017-2018
2449	1326	139	3	38.5	2017-2018
2450	1326	200	3	47.25	2017-2018
2451	838	224	3	31.5	2017-2018
2452	838	174	3	45	2017-2018
2453	838	165	3	48.25	2017-2018
2454	838	221	3	52.25	2017-2018
2455	838	125	3	52	2017-2018
2456	838	76	3	31.5	2017-2018
2457	838	138	3	41	2017-2018
2458	838	149	3	29.75	2017-2018
2459	838	139	3	33.75	2017-2018
2460	838	222	3	9	2017-2018
2461	838	200	3	44.25	2017-2018
2462	394	224	3	45.25	2017-2018
2463	394	174	3	38.5	2017-2018
2464	394	165	3	28.5	2017-2018
2465	394	221	3	44.75	2017-2018
2466	394	125	3	63	2017-2018
2467	394	76	3	61.5	2017-2018
2468	394	138	3	43.5	2017-2018
2469	394	149	3	55.5	2017-2018
2470	394	139	3	40.5	2017-2018
2471	394	222	3	18	2017-2018
2472	394	200	3	29.75	2017-2018
2473	764	224	3	41.5	2017-2018
2474	764	174	3	35	2017-2018
2475	764	165	3	35	2017-2018
2476	764	221	3	40	2017-2018
2477	764	125	3	55	2017-2018
2478	764	76	3	43	2017-2018
2479	764	138	3	38.5	2017-2018
2480	764	149	3	26.5	2017-2018
2481	764	139	3	47	2017-2018
2482	764	222	3	16.5	2017-2018
2483	764	200	3	27.5	2017-2018
2484	1301	224	3	26	2017-2018
2485	1301	174	3	17	2017-2018
2486	1301	165	3	12	2017-2018
2487	1301	221	3	23	2017-2018
2488	1301	125	3	21	2017-2018
2489	1301	138	3	12	2017-2018
2490	1301	149	3	15	2017-2018
2491	1301	139	3	17	2017-2018
2492	1301	200	3	18	2017-2018
2493	1544	224	3	38.399999999999999	2017-2018
2494	1544	174	3	24.5	2017-2018
2495	1544	223	3	17.399999999999999	2017-2018
2496	1544	165	3	60	2017-2018
2497	1544	221	3	54.75	2017-2018
2498	1544	125	3	72.299999999999997	2017-2018
2499	1544	76	3	32.549999999999997	2017-2018
2500	1544	138	3	64.549999999999997	2017-2018
2501	1544	149	3	48.899999999999999	2017-2018
2502	1544	139	3	48.299999999999997	2017-2018
2503	1544	222	3	17.399999999999999	2017-2018
2504	1544	200	3	46.5	2017-2018
2505	1542	224	3	60.75	2017-2018
2506	1542	174	3	34.149999999999999	2017-2018
2507	1542	223	3	12.75	2017-2018
2508	1542	165	3	49.25	2017-2018
2509	1542	221	3	58.25	2017-2018
2510	1542	125	3	52	2017-2018
2511	1542	76	3	54.25	2017-2018
2512	1542	138	3	55.5	2017-2018
2513	1542	149	3	48.899999999999999	2017-2018
2514	1542	139	3	38.25	2017-2018
2515	1542	200	3	55.5	2017-2018
2516	1553	224	3	43.649999999999999	2017-2018
2517	1553	174	3	27.149999999999999	2017-2018
2518	1553	223	3	16.199999999999999	2017-2018
2519	1553	165	3	58.5	2017-2018
2520	1553	221	3	60.5	2017-2018
2521	1553	125	3	56	2017-2018
2522	1553	76	3	36.100000000000001	2017-2018
2523	1553	138	3	58.799999999999997	2017-2018
2524	1553	149	3	47.399999999999999	2017-2018
2525	1553	139	3	43.299999999999997	2017-2018
2526	1553	222	3	16.800000000000001	2017-2018
2527	1553	200	3	34.25	2017-2018
2528	1550	224	3	30	2017-2018
2529	1550	174	3	42	2017-2018
2530	1550	223	3	14.4	2017-2018
2531	1550	165	3	69.349999999999994	2017-2018
2532	1550	221	3	62.200000000000003	2017-2018
2533	1550	125	3	50.149999999999999	2017-2018
2534	1550	76	3	49.25	2017-2018
2535	1550	138	3	47.75	2017-2018
2536	1550	149	3	45.799999999999997	2017-2018
2537	1550	139	3	33	2017-2018
2538	1550	222	3	21	2017-2018
2539	1550	200	3	37.75	2017-2018
2540	1548	224	3	48	2017-2018
2541	1548	174	3	48	2017-2018
2542	1548	165	3	51	2017-2018
2543	1548	221	3	54	2017-2018
2544	1548	125	3	69	2017-2018
2545	1548	76	3	40	2017-2018
2546	1548	138	3	48	2017-2018
2547	1548	149	3	41	2017-2018
2548	1548	139	3	36	2017-2018
2549	1548	200	3	48	2017-2018
2550	1545	224	3	67.5	2017-2018
2551	1545	174	3	59.75	2017-2018
2552	1545	223	3	18.75	2017-2018
2553	1545	165	3	53	2017-2018
2554	1545	221	3	59	2017-2018
2555	1545	125	3	59	2017-2018
2556	1545	76	3	67	2017-2018
2557	1545	138	3	49.5	2017-2018
2558	1545	149	3	57.5	2017-2018
2559	1545	139	3	32.25	2017-2018
2560	1545	222	3	3.75	2017-2018
2561	1545	200	3	53.5	2017-2018
2562	765	224	3	54.5	2017-2018
2563	765	174	3	44.5	2017-2018
2564	765	165	3	50.25	2017-2018
2565	765	221	3	60	2017-2018
2566	765	125	3	53	2017-2018
2567	765	138	3	53	2017-2018
2568	765	149	3	19	2017-2018
2569	765	139	3	51.5	2017-2018
2570	765	200	3	53	2017-2018
2571	1549	224	3	43.75	2017-2018
2572	1549	174	3	15.75	2017-2018
2573	1549	165	3	55.5	2017-2018
2574	1549	221	3	55.25	2017-2018
2575	1549	76	3	24.5	2017-2018
2576	1549	138	3	50.25	2017-2018
2577	1549	149	3	43.75	2017-2018
2578	1549	139	3	49.5	2017-2018
2579	1552	224	3	52.350000000000001	2017-2018
2580	1552	174	3	40.25	2017-2018
2581	1552	223	3	14.4	2017-2018
2582	1552	165	3	51.25	2017-2018
2583	1552	221	3	49.5	2017-2018
2584	1552	125	3	56.25	2017-2018
2585	1552	76	3	48.950000000000003	2017-2018
2586	1552	138	3	46.799999999999997	2017-2018
2587	1552	149	3	42.100000000000001	2017-2018
2588	1552	139	3	51.299999999999997	2017-2018
2589	1552	222	3	15.6	2017-2018
2590	1552	200	3	48.350000000000001	2017-2018
2591	1547	224	3	7.5	2017-2018
2592	1547	138	3	4.5	2017-2018
2593	1547	149	3	7.5	2017-2018
2594	1540	224	3	54.75	2017-2018
2595	1540	174	3	43	2017-2018
2596	1540	223	3	14.4	2017-2018
2597	1540	165	3	76.150000000000006	2017-2018
2598	1540	221	3	42.950000000000003	2017-2018
2599	1540	125	3	64.200000000000003	2017-2018
2600	1540	76	3	48.399999999999999	2017-2018
2601	1540	138	3	65.549999999999997	2017-2018
2602	1540	149	3	63.149999999999999	2017-2018
2603	1540	139	3	52.450000000000003	2017-2018
2604	1540	222	3	15	2017-2018
2605	1540	200	3	52.700000000000003	2017-2018
2606	1312	224	3	57.75	2017-2018
2607	1312	174	3	52.25	2017-2018
2608	1312	223	3	24	2017-2018
2609	1312	165	3	44.25	2017-2018
2610	1312	221	3	43	2017-2018
2611	1312	125	3	42.5	2017-2018
2612	1312	76	3	45.5	2017-2018
2613	1312	138	3	49.5	2017-2018
2614	1312	149	3	58.5	2017-2018
2615	1312	139	3	50	2017-2018
2616	1312	222	3	17.25	2017-2018
2617	1312	200	3	22	2017-2018
2618	570	224	4	59.75	2017-2018
2619	570	174	4	47.25	2017-2018
2620	570	165	4	42.25	2017-2018
2621	570	125	4	56.5	2017-2018
2622	570	76	4	59.75	2017-2018
2623	570	138	4	52	2017-2018
2624	570	149	4	48.5	2017-2018
2625	570	139	4	42.25	2017-2018
2626	570	200	4	47	2017-2018
2627	1535	224	4	36.5	2017-2018
2628	1535	174	4	56	2017-2018
2629	1535	165	4	60.5	2017-2018
2630	1535	125	4	59.25	2017-2018
2631	1535	76	4	60	2017-2018
2632	1535	138	4	54.5	2017-2018
2633	1535	149	4	61.5	2017-2018
2634	1535	139	4	41.75	2017-2018
2635	1535	200	4	58.5	2017-2018
2636	549	224	4	68.5	2017-2018
2637	549	174	4	65	2017-2018
2638	549	165	4	54.5	2017-2018
2639	549	125	4	61.5	2017-2018
2640	549	76	4	24.5	2017-2018
2641	549	138	4	61.5	2017-2018
2642	549	149	4	38.5	2017-2018
2643	549	139	4	72	2017-2018
2644	549	200	4	65	2017-2018
2645	501	224	4	30.75	2017-2018
2646	501	174	4	36.5	2017-2018
2647	501	165	4	62.75	2017-2018
2648	501	125	4	71	2017-2018
2649	501	76	4	64.5	2017-2018
2650	501	138	4	45	2017-2018
2651	501	149	4	43.5	2017-2018
2652	501	139	4	37	2017-2018
2653	501	200	4	56	2017-2018
2654	740	224	6	31.5	2017-2018
2655	740	174	6	28	2017-2018
2656	740	165	6	42.75	2017-2018
2657	740	125	6	63.25	2017-2018
2658	740	76	6	57.75	2017-2018
2659	740	138	6	55	2017-2018
2660	740	149	6	58.5	2017-2018
2661	740	139	6	70.75	2017-2018
2662	740	200	6	24.5	2017-2018
2664	1549	125	4	63.5	2017-2018
2665	1549	200	4	50	2017-2018
2666	552	224	4	19.5	2017-2018
2667	552	174	4	39	2017-2018
2668	552	165	4	53	2017-2018
2669	552	125	4	51.5	2017-2018
2670	552	76	4	40.5	2017-2018
2671	552	138	4	42.5	2017-2018
2672	552	149	4	39.5	2017-2018
2673	552	139	4	58.5	2017-2018
2674	552	200	4	46.5	2017-2018
2675	1301	224	4	60.5	2017-2018
2676	1301	174	4	35	2017-2018
2677	1301	223	4	5	2017-2018
2678	1301	165	4	58	2017-2018
2679	1301	125	4	70.5	2017-2018
2680	1301	76	4	35	2017-2018
2681	1301	138	4	52.5	2017-2018
2682	1301	149	4	32	2017-2018
2683	1301	139	4	47	2017-2018
2684	1301	200	4	52.5	2017-2018
2685	1553	224	4	36.25	2017-2018
2686	1553	174	4	45	2017-2018
2687	1553	165	4	48.5	2017-2018
2688	1553	125	4	39.75	2017-2018
2689	1553	76	4	46.75	2017-2018
2690	1553	138	4	40	2017-2018
2691	1553	149	4	50.5	2017-2018
2692	1553	139	4	29	2017-2018
2693	1553	200	4	37.149999999999999	2017-2018
2694	1548	224	4	19.5	2017-2018
2695	1548	165	4	18	2017-2018
2696	1548	125	4	19.5	2017-2018
2697	1548	138	4	19.5	2017-2018
2698	1548	149	4	19.5	2017-2018
2699	1548	139	4	18	2017-2018
2700	1548	200	4	21	2017-2018
2701	1545	224	4	49.5	2017-2018
2702	1545	174	4	38.5	2017-2018
2703	1545	165	4	49.75	2017-2018
2704	1545	125	4	71.5	2017-2018
2705	1545	76	4	52.25	2017-2018
2706	1545	138	4	48.5	2017-2018
2707	1545	149	4	44.5	2017-2018
2708	1545	139	4	39	2017-2018
2709	1545	200	4	37.25	2017-2018
2710	765	224	4	57	2017-2018
2711	765	174	4	32	2017-2018
2712	765	165	4	47	2017-2018
2713	765	125	4	60	2017-2018
2714	765	76	4	51	2017-2018
2715	765	138	4	59	2017-2018
2716	765	149	4	30	2017-2018
2717	765	139	4	47	2017-2018
2718	765	200	4	47	2017-2018
2663	1549	224	4	61.5	2017-2018
2719	1549	174	4	42	2017-2018
2720	1549	165	4	42	2017-2018
2721	1549	76	4	61.5	2017-2018
2722	1549	138	4	64.5	2017-2018
2723	1549	149	4	63	2017-2018
2724	1549	139	4	69.5	2017-2018
2725	1540	224	4	54.5	2017-2018
2726	1540	174	4	35.850000000000001	2017-2018
2727	1540	165	4	57.75	2017-2018
2728	1540	125	4	71.150000000000006	2017-2018
2729	1540	76	4	38	2017-2018
2730	1540	138	4	43.25	2017-2018
2731	1540	149	4	61	2017-2018
2732	1540	139	4	53	2017-2018
2733	1540	200	4	51	2017-2018
2734	1312	224	4	49	2017-2018
2735	1312	174	4	56	2017-2018
2736	1312	165	4	47.25	2017-2018
2737	1312	125	4	53.5	2017-2018
2738	1312	76	4	40.25	2017-2018
2739	1312	138	4	59.5	2017-2018
2740	1312	149	4	59.5	2017-2018
2741	1312	139	4	47.25	2017-2018
2742	1312	200	4	43.75	2017-2018
2743	1329	224	4	18	2017-2018
2744	1329	165	4	18	2017-2018
2745	1329	125	4	19.5	2017-2018
2746	1329	138	4	19.5	2017-2018
2747	1329	149	4	19.5	2017-2018
2748	1329	139	4	18	2017-2018
2749	1329	200	4	21	2017-2018
2750	1550	224	4	32	2017-2018
2751	1550	174	4	35	2017-2018
2752	1550	165	4	37	2017-2018
2753	1550	125	4	56	2017-2018
2754	1550	76	4	39	2017-2018
2755	1550	138	4	39	2017-2018
2756	1550	149	4	41	2017-2018
2757	1550	139	4	25	2017-2018
2758	1550	200	4	35	2017-2018
2759	1326	224	4	60	2017-2018
2760	1326	174	4	35	2017-2018
2761	1326	165	4	50	2017-2018
2762	1326	125	4	63	2017-2018
2763	1326	76	4	55	2017-2018
2764	1326	138	4	47.25	2017-2018
2765	1326	149	4	38.5	2017-2018
2766	1326	139	4	40	2017-2018
2767	1326	200	4	46.5	2017-2018
2768	1448	55	1	18	2017-2018
2769	1448	85	1	13.5	2017-2018
2770	1448	45	1	15.5	2017-2018
2771	1448	109	1	20	2017-2018
2772	1446	55	1	25.5	2017-2018
2773	1446	85	1	22.5	2017-2018
2774	1446	45	1	25.5	2017-2018
2775	1446	109	1	25.5	2017-2018
2776	1513	55	1	24	2017-2018
2777	1513	85	1	29.75	2017-2018
2778	1513	45	1	25.25	2017-2018
2779	1513	109	1	24	2017-2018
2780	1511	55	1	19.5	2017-2018
2781	1511	85	1	18.5	2017-2018
2782	1511	45	1	17	2017-2018
2783	1511	109	1	18.5	2017-2018
2784	1406	55	1	25.5	2017-2018
2785	1406	85	1	25.5	2017-2018
2786	1406	45	1	21	2017-2018
2787	1406	109	1	22.5	2017-2018
2788	1518	85	1	21	2017-2018
2789	1518	45	1	21	2017-2018
2790	1518	109	1	23.25	2017-2018
2791	1442	55	1	20.5	2017-2018
2792	1442	85	1	18.5	2017-2018
2793	1442	45	1	22	2017-2018
2794	1442	109	1	25.5	2017-2018
2795	570	55	1	8.25	2017-2018
2796	570	85	1	5.25	2017-2018
2797	570	45	1	16.5	2017-2018
2798	570	109	1	9	2017-2018
2799	2	113	1	15	2017-2018
2800	2	55	1	15.5	2017-2018
2801	2	85	1	13.5	2017-2018
2802	2	45	1	14.5	2017-2018
2803	2	109	1	14	2017-2018
2804	1518	55	1	25	2017-2018
2805	763	55	1	19.5	2017-2018
2806	763	85	1	21	2017-2018
2807	763	45	1	13.5	2017-2018
2808	763	109	1	15	2017-2018
2809	121	55	1	16.5	2017-2018
2810	121	85	1	9	2017-2018
2811	121	45	1	16.5	2017-2018
2812	121	109	1	18	2017-2018
2813	1447	55	1	24.5	2017-2018
2814	1447	85	1	24	2017-2018
2815	1447	45	1	25.5	2017-2018
2816	1447	109	1	22.5	2017-2018
2817	1507	55	1	25.5	2017-2018
2818	1507	85	1	18	2017-2018
2819	1507	45	1	16.5	2017-2018
2820	1507	109	1	21	2017-2018
2821	336	55	1	21.75	2017-2018
2822	336	85	1	15	2017-2018
2823	336	45	1	18	2017-2018
2824	336	109	1	15	2017-2018
2825	1535	85	1	8.25	2017-2018
2826	499	85	1	10.5	2017-2018
2827	499	45	1	15	2017-2018
2828	499	109	1	16.5	2017-2018
2829	1504	55	1	23.5	2017-2018
2830	1504	85	1	15	2017-2018
2831	1504	45	1	21.5	2017-2018
2832	1504	109	1	22	2017-2018
2833	1441	113	2	15	2017-2018
2834	1441	55	2	19.5	2017-2018
2835	1441	85	2	12	2017-2018
2836	1441	45	2	12	2017-2018
2837	1441	109	2	22.5	2017-2018
2838	1388	55	2	18.5	2017-2018
2839	1388	109	2	20.5	2017-2018
2840	1514	113	2	19.5	2017-2018
2841	1514	55	2	19.5	2017-2018
2842	1514	85	2	12	2017-2018
2843	1514	45	2	19.5	2017-2018
2844	1514	109	2	16.5	2017-2018
2845	1442	113	2	19.5	2017-2018
2846	1442	55	2	25.5	2017-2018
2847	1442	45	2	22.5	2017-2018
2848	1442	109	2	24	2017-2018
2849	1448	113	2	18	2017-2018
2850	1448	55	2	24	2017-2018
2851	1448	85	2	16.5	2017-2018
2852	1448	45	2	27	2017-2018
2853	1448	109	2	27	2017-2018
2854	1376	113	2	15	2017-2018
2855	1376	55	2	26.5	2017-2018
2856	1376	45	2	18	2017-2018
2857	1376	109	2	25.5	2017-2018
2858	1428	55	2	21	2017-2018
2859	1428	85	2	16.5	2017-2018
2860	1428	109	2	18	2017-2018
2861	1415	113	2	16.5	2017-2018
2862	1415	55	2	24	2017-2018
2863	1415	85	2	19.5	2017-2018
2864	1415	45	2	21	2017-2018
2865	1415	109	2	25.5	2017-2018
2866	764	113	2	15	2017-2018
2867	764	55	2	19.5	2017-2018
2868	764	45	2	18	2017-2018
2869	764	109	2	18	2017-2018
2870	1416	113	2	19.5	2017-2018
2871	1416	55	2	24	2017-2018
2872	1416	45	2	18	2017-2018
2873	1416	109	2	24	2017-2018
2874	570	55	2	12.75	2017-2018
2875	570	85	2	10.5	2017-2018
2876	570	45	2	11.25	2017-2018
2877	2	55	2	18	2017-2018
2878	2	85	2	14	2017-2018
2879	2	45	2	8	2017-2018
2880	2	109	2	20	2017-2018
2881	1518	85	2	6	2017-2018
2882	1518	45	2	3.75	2017-2018
2883	1518	109	2	22.5	2017-2018
2884	570	113	4	33.25	2017-2018
2885	570	55	4	47.75	2017-2018
2886	570	85	4	36.75	2017-2018
2887	570	45	4	27	2017-2018
2888	570	109	4	43.75	2017-2018
2889	763	113	4	17.5	2017-2018
2890	763	55	4	24.5	2017-2018
2891	763	85	4	45.5	2017-2018
2892	763	45	4	42	2017-2018
2893	763	109	4	38.5	2017-2018
2894	1518	85	4	69	2017-2018
2895	1518	45	4	61.75	2017-2018
2896	1518	109	4	75.5	2017-2018
2897	1518	113	4	38.5	2017-2018
2898	1518	55	4	62.5	2017-2018
2899	764	113	4	36	2017-2018
2900	764	55	4	44	2017-2018
2901	764	85	4	21	2017-2018
2902	764	45	4	35.5	2017-2018
2903	764	109	4	28.5	2017-2018
2904	2	113	4	29	2017-2018
2905	2	55	4	58	2017-2018
2906	2	85	4	45	2017-2018
2907	2	45	4	36	2017-2018
2908	2	109	4	49	2017-2018
2909	1442	113	4	61.5	2017-2018
2910	1442	55	4	71	2017-2018
2911	1442	85	4	36.75	2017-2018
2912	1442	45	4	69.75	2017-2018
2913	1442	109	4	62.5	2017-2018
2914	1448	113	4	49.5	2017-2018
2915	1448	55	4	76.25	2017-2018
2916	1448	85	4	48	2017-2018
2917	1448	45	4	65.5	2017-2018
2918	1448	109	4	74.25	2017-2018
2919	1388	113	4	59	2017-2018
2920	1388	55	4	58.75	2017-2018
2921	1388	85	4	52.5	2017-2018
2922	1388	45	4	59.75	2017-2018
2923	1388	109	4	67.75	2017-2018
2924	1428	113	4	46.5	2017-2018
2925	1428	55	4	73.5	2017-2018
2926	1428	85	4	62	2017-2018
2927	1428	45	4	62.75	2017-2018
2928	1428	109	4	58.25	2017-2018
2929	1376	113	4	44.75	2017-2018
2930	1376	55	4	52.75	2017-2018
2931	1376	85	4	17.5	2017-2018
2932	1376	45	4	46	2017-2018
2933	1376	109	4	65.75	2017-2018
2934	1415	113	4	44.5	2017-2018
2935	1415	55	4	53.75	2017-2018
2936	1415	85	4	56.25	2017-2018
2937	1415	45	4	56	2017-2018
2938	1415	109	4	65.75	2017-2018
2939	1416	113	4	54.5	2017-2018
2940	1416	55	4	73	2017-2018
2941	1416	85	4	36.75	2017-2018
2942	1416	45	4	68.75	2017-2018
2943	1416	109	4	69.5	2017-2018
2944	1441	113	4	50	2017-2018
2945	1441	55	4	52.75	2017-2018
2946	1441	85	4	48.75	2017-2018
2947	1441	45	4	59.25	2017-2018
2948	1441	109	4	52.25	2017-2018
2949	1514	113	4	51	2017-2018
2950	1514	55	4	47.5	2017-2018
2951	1514	85	4	57.5	2017-2018
2952	1514	45	4	47.5	2017-2018
2953	1514	109	4	51.5	2017-2018
2954	1433	113	4	7	2017-2018
2955	1433	55	4	11	2017-2018
2956	1433	85	4	11	2017-2018
2957	1433	45	4	11	2017-2018
2958	570	113	3	21	2017-2018
2959	570	55	3	39.75	2017-2018
2960	570	85	3	5.25	2017-2018
2961	570	45	3	39.25	2017-2018
2962	570	109	3	31.75	2017-2018
2963	1535	85	3	8.25	2017-2018
2964	763	113	3	14	2017-2018
2965	763	55	3	61.5	2017-2018
2966	763	85	3	56	2017-2018
2967	763	45	3	41.5	2017-2018
2968	763	109	3	57	2017-2018
2969	1518	113	3	32.5	2017-2018
2970	1518	55	3	61	2017-2018
2971	1518	85	3	52.5	2017-2018
2972	1518	45	3	57.75	2017-2018
2973	1518	109	3	54.75	2017-2018
2974	499	113	3	20	2017-2018
2975	499	55	3	18	2017-2018
2976	499	85	3	47.5	2017-2018
2977	499	45	3	32.5	2017-2018
2978	499	109	3	37.5	2017-2018
2979	2	113	3	64.5	2017-2018
2980	2	55	3	51.25	2017-2018
2981	2	85	3	41.25	2017-2018
2982	2	45	3	59.75	2017-2018
2983	2	109	3	52	2017-2018
2984	336	113	3	42	2017-2018
2985	336	55	3	63.75	2017-2018
2986	336	85	3	15	2017-2018
2987	336	45	3	49.5	2017-2018
2988	336	109	3	43	2017-2018
2989	1442	113	3	60	2017-2018
2990	1442	55	3	81.75	2017-2018
2991	1442	85	3	18.5	2017-2018
2992	1442	45	3	69.25	2017-2018
2993	1442	109	3	85	2017-2018
2994	1448	113	3	43.5	2017-2018
2995	1448	55	3	53	2017-2018
2996	1448	85	3	13.5	2017-2018
2997	1448	45	3	54	2017-2018
2998	1448	109	3	49	2017-2018
2999	1447	113	3	56.5	2017-2018
3000	1447	55	3	75.25	2017-2018
3001	1447	85	3	41.5	2017-2018
3002	1447	45	3	74.5	2017-2018
3003	1447	109	3	57.5	2017-2018
3004	1446	113	3	66.25	2017-2018
3005	1446	55	3	71	2017-2018
3006	1446	85	3	50.5	2017-2018
3007	1446	45	3	60.5	2017-2018
3008	1446	109	3	71	2017-2018
3009	1513	113	3	43	2017-2018
3010	1513	55	3	62.5	2017-2018
3011	1513	85	3	57.75	2017-2018
3012	1513	45	3	42.75	2017-2018
3013	1513	109	3	60.75	2017-2018
3014	1511	113	3	42	2017-2018
3015	1511	55	3	61.5	2017-2018
3016	1511	85	3	62.25	2017-2018
3017	1511	45	3	52	2017-2018
3018	1511	109	3	71	2017-2018
3019	1406	113	3	45.5	2017-2018
3020	1406	55	3	74.5	2017-2018
3021	1406	85	3	81.5	2017-2018
3022	1406	45	3	56	2017-2018
3023	1406	109	3	61	2017-2018
\.


--
-- Name: note_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('note_id_seq', 3023, true);


--
-- Data for Name: parametres; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY parametres (id, nom, valeur) FROM stdin;
1	nom_etablissement	INSTITUT SUPERIEUR HINTEL
2	nom_directeur	INSTITUT SUPERIEUR HINTEL
4	initiales_etablissement	IS-HINTEL
5	telephone	683252783 /695320131
6	adresse	FABRIQUE NGOUSSO
7	web	WWW.IS-hintel.cm
8	email	pascal.tchana@gmail.com
9	configuration	OK
10	dc	2018-11-21
3	annee_scolaire	2017-2018
\.


--
-- Name: parametres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('parametres_id_seq', 11, true);


--
-- Data for Name: parent; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY parent (id, nom, profession, ville, quartier, tel1, tel2, type, id_eleve) FROM stdin;
1	EBENDZE Rosalie	menagere	yaounde		699338721		0	1
2	BAGA Zephyrin	chauffeur	yaounde		651454627		1	1
3	FOUDA Bernadette		yaounde		694616367		0	2
4	NOAH William		yaounde		677614320		1	2
5	EDZIMBI ATSAMA Saome	menagere	NKOAYOS		653196991		0	3
6	NGANA ATANGANA PASCAL	ELECTRICIEN	OBALA		652415261		1	3
7	WENKAM MARIE		yaounde		699904151		0	4
8	DEFO Rene	MAÇON	yaounde		699303268		1	4
9	NGO NGOMBI THÉRÈSE	menagere	POUMA		696788824		0	5
10	ILOGA Philipe	AGENT TECHNIQUE DES EAUX ET FORETS	POUMA		677193323		1	5
11	MAFOMDJO AGATA		BAFOUSSAM		694332648		0	6
12	SOUDJA Joseph		yaounde		697122974		1	6
13	MOUNGUENGUI. I		yaounde		677645242		0	7
14	EBENE Jean Jacques		NKOMOTOU		698798671		1	7
15	AGOUNG à BEBEN VICTOIRE	CULTIVATRICE	TARO		655124029		0	8
16	DANG Emmanuel	CULTIVATEUR	GOUFAN I		695913585		1	8
17	NGATE HUGUETTE	menagere	yaounde		693060800		0	9
18	DJAMEN CHRISTOPHERE	ENSEIGNANT	yaounde		670562700		1	9
19	BILKISSOU	menagere					0	10
20	SOUAIBOU HAYATOU	COMMERÇANT			679804844		1	10
21	AMOGO IFIGENIE	menagere	yaounde		679245890		0	11
22	ENOBO ONDOUA T	CULTIVATEUR	yaounde		679245890		1	11
23	FOUDA MARIE H	menagere	yaounde		655306094		0	12
24	TSANA LIBOIRE		yaounde		699111939		1	12
25	EBENDZE Rosalie	menagere			699338721		0	13
26	BAGA Zephyrin	chauffeur	yaounde		651454627		1	13
27	NGONO MEKONGO 	menagere	MFOMAKAP		652753210		0	14
28	BESSALA NTEDE MICHELI						1	14
29	MOLO Appolonie				675359108		0	15
30	AWANA Diuedonne	ENSEIGNANT					1	15
31	NOUBOSSE Eurodine	menagere	MBOUDA		695443949		0	16
32	SAAH Salomon	COMMERÇANT	yaounde		680770995		1	16
33	NGO NGUI THERESSE	COMMERÇANTE	yaounde		697884017		0	17
34	BAPPA Gaston	CONSULTANT EN INFORMATIQUE	yaounde				1	17
35	NGONO DZENGA Elisabeth				+24107601839		0	18
36	MABBYALAS (FEU)						1	18
37	BEDOMBO Ybette Therese		yaounde				0	19
38	AMBASSA Laurent		yaounde		696938663		1	19
39	NGO MANGUELE Marie Paule	menagere	yaounde	EMANA	677209266		0	20
40	BANA François 	MANOEUVRE	yaounde	EMANA	674329266		1	20
41	BIDZOGO ENGUENE 	/	yaounde	SOA	677943199	/	0	21
42	/	/	/	/	/	/	1	21
43	/	/	/	/	/	/	2	21
44	KOSSO Madeleine	menagere	yaounde	OMNISPORT	674707535	/	0	22
45	BOYOMO MOUBITANG Etien	COMMERÇANT	yaounde	MIBOMAN	/	/	1	22
46	/	/	/	/	/	/	2	22
47	MAYONG Michelle	/	GABON	/	*24105129641	/	0	23
48	OWANA Jean Claude	/	BERTOUA	/	697659445	/	1	23
49	/	/	/	/	/	/	2	23
50	ETSIGUE Cecile	/	yaounde	SOA	694442511	/	0	24
51	ETSIGUE MOUMO	/	yaounde	SOA	678114207	/	1	24
52	/	/	/	/	/	/	2	24
53	NGEUFIO Felicite	menagere	yaounde	NGOUSSO	675313765	/	0	25
54	NGUEFIO ANDRE 	SOUDEUR	yaounde	NGOUSSO	/	/	1	25
55	/	/	/	/	/	/	2	25
56	NGOUNGOURE Fouh	menagere	FOUMBAN	/	693220092	/	0	26
57	TOUIKOUO	/	FOUMBAN	/	696912113	/	1	26
58	/	/	/	/	/	/	2	26
59	MBELEG MARGUERITE	menagere	yaounde	/	678077568	/	0	27
60	/	/	/	/	/	/	1	27
61	/	/	/	/	/	/	2	27
62	NGNIGACHIE Jenevieve	menagere	/	/	/	/	0	28
63	SONGWA Maurice	MECANICIEN	/	/	/	/	1	28
64	/	/	/	/	/	/	2	28
65	YEMETIO Oriane	/	yaounde	/	650093264	/	0	29
66	DJOUMESSI Ghislin	/	yaounde	/	674813493	/	1	29
67	/	/	/	/	/	/	2	29
68	MANGA Suzanne Angele	menagere	OBALA	/	674501635	/	0	30
69	/	/	/	/	/	/	1	30
70	/	/	/	/	/	/	2	30
71	MONKAM Therese	menagere	BANA	/	675275121	/	0	31
72	DJUIMO MICHEL	chauffeur	/	/	/	/	1	31
73	/	/	/	/	/	/	2	31
74	NSEE ELA Agnes	menagere	GUINNEE . E	/	240222673703	/	0	32
75	LAH Claude	PROFFESSEUR	yaounde	/	699349494	/	1	32
76	/	/	/	/	/	/	2	32
77	LESSOUGA Naomie	menagere	/	/	/	/	0	33
78	SOULEMANOU AMADOU 	CADRE COMMERCIALE	yaounde	/	694964156	/	1	33
79	/	/	/	/	/	/	2	33
80	AMBOMBO Marie	menagere	yaounde	/	677421099	/	0	34
81	OLOMO NDZESSA	ENSEIGNANT	/	/	678581059	/	1	34
82	/	/	/	/	/	/	2	34
83	NGO  BAYONG Claudine	menagere	yaounde	/	698372412	/	0	35
84	NGEE SIMON Pierre	GENDARME	yaounde	/	695593883	/	1	35
85	/	/	/	/	/	/	2	35
86	EBANDJI Louise	menagere	BANGANGTE	/	699603778	/	0	36
87	EBOME Samuel	GARDIEN DE PAIX	/	/	/	/	1	36
88	/	/	/	/	/	/	2	36
89	MPEGNI ALOGO Barbara	CULTIVATRICE	BAFIA	/	655138676	/	0	37
90	BOYOMO BOADE Laurent	CULTIVATEUR	BAFIA	/	660271637	/	1	37
91	/	/	/	/	/	/	2	37
92	AWOE Pauline	menagere	yaounde	/	666425393	/	0	38
93	DONG Gabriel	chauffeur	yaounde	NKOLEMESSENG	677769393	/	1	38
94	/	/	/	/	/	/	2	38
95	GUEHOADA Marie	CULTIVATRICE	ASSALA	/	/	/	0	39
96	NTSOLI ONANINA Jean Brice	CULTIVATEUR	ASSALA I	/	660673373	/	1	39
97	/	/	/	/	/	/	2	39
98	NGAH BIDJOGO Brigitte	CULTIVATRICE	OKOK	/	/	/	0	40
99	ATANGANA Eugene	CULTIVATEUR	OKOK	/	674758437	/	1	40
100	/	/	/	/	/	/	2	40
101	GWANOM NOUSSINA Elisabeth	COUTURIERE	BAFOUSSAM	/	696221215	/	0	41
102	/	/	/	/	/	/	1	41
103	/	/	YAOUNDE	/	699221825	/	2	41
104	KOLONG GISETTE	menagere	yaounde	NGOUSSO	670011945	/	0	42
105	TATSILONG Andre	MENUSIER	yaounde	NGOUSSO	677803891	/	1	42
106	/	/	/	/	/	/	2	42
107	AMASIGA Seraphine	/	/	/	679183647	/	0	43
108	BOTOMOYO Emmanuel	/	/	/	/	/	1	43
109	/	/	/	/	/	/	2	43
110	AMBOMO ODILE	/	NVOUNDOU	/	679720680	/	0	44
111	NOGO Joseph	/	NVOUNDOU	/	/	/	1	44
112	/	/	/	/	/	/	2	44
113	LONTCHI T Marie Claude	/	/	/	674868323	/	0	45
114	MBOUGNIA	/	/	/	672316112	/	1	45
115	/	/	/	/	/	/	2	45
116	TCHOUMBOUEN Madeleine	/	DSCHANG	/	699660454	/	0	46
117	MONKAM RAPHAEL 	/	DSCHANG	/	699840706	/	1	46
118	/	/	/	/	/	/	2	46
119	TCHOUALA Delphine	/	/	/	699932578	/	0	47
120	DOUANLA Maurice	/	/	/	/	/	1	47
121	/	/	/	/	/	/	2	47
122	NGUENGNE Cardine	/	yaounde	/	675120469	/	0	48
123	TAMOUYA Leopauld	/	yaounde	/	666343121	/	1	48
124	/	/	/	/	/	/	2	48
125	NGOUNGANG Elise	/	/	/	694924537	/	0	49
126	NGOUANA Martin  	/	/	/	/	/	1	49
127	/	/	/	/	/	/	2	49
128	ODJOLO Veronique 	/	/	/	677075386	/	0	50
129	ANABA Sebastien	/	/	/	677612595	/	1	50
130	/	/	/	/	/	/	2	50
131	DOUNGLA Junie	SECRETAIRE	yaounde	NGOUSSO	677153548	/	0	51
132	FOTOUO Valentin	/	yaounde	NGOUSSO	677738420	/	1	51
133	/	/	/	/	/	/	2	51
134	WOUAMBA Ivonne	menagere	BATCHAM	/	675200829	/	0	52
135	MANFOUO RAYMOND 	CULTIVATEUR	BATHAM	/	675203917	/	1	52
136	/	/	/	/	/	/	2	52
137	NGOTJECK Evelyne	SECRETAIRE	yaounde	EKOUNOU	698926036	/	0	53
138	PAGBE Thomas 	/	yaounde	EKOUNOU	694842671	/	1	53
139	/	/	/	/	/	/	2	53
140	DJEUNOU REGINE	menagere	yaounde	NGOUSSO	679973186	/	0	54
141	KAMGANG JEAN-M	MECANICIEN	yaounde	NGOUSSO	678097124	/	1	54
142	/	/	/	/	/	/	2	54
143	MPESSE ONANA Angele	/	ELOM	/	675541630	/	0	55
144	OTTOU TABI Pierre	/	NYOM	/	677984528	/	1	55
145	/	/	/	/	/	/	2	55
146	LONTSI KENANG	menagere	/	/	/	/	0	56
147	KENNE LONTCHI R	chauffeur	yaounde	/	/	/	1	56
148	/	/	/	/	675169198	/	2	56
149	SIDJIE Victorine	MENAGER	YAOUNDE 	NGOUSSO	691265570	/	0	57
150	HALEU JEAN 	ELECTROTECCNICIEN	YAOUNDE	NGOUSSO	691661055	/	1	57
151	/	/	/	/	/	/	2	57
152	KETCHOU Emilienne	MENAGER	/	/	/	/	0	58
153	TCHOUTA Aime	COMMERCANT	/	/	/	/	1	58
154	TCHALEU CHRISTIAN	/	/	/	696514735	/	2	58
155	FOBASSO Josiane		YAOUN	EMANA	674610010		0	59
156	FOBASSO ALEXANDRE 		YAOUNDE	EMANA	677415764		1	59
157	KEGNE Florette		YAOUNDE	NGOUSSO	674623829		0	60
158	YOUMSI SamueL		YAOUNDE	NGOUSSO	676301624		1	60
159	YEBI  CLARINE		SA'A				0	61
160	NDZOU ANTOINE		SA'A		677924290		1	61
161	EBENDZE ROSAL 		YAOUNDE		699338721		0	62
162	BAGA ZEPHYRINA		YAOUNDE		651454627		1	62
163	KAMGUEM BERNADETTE (FEU)	/	/	/	/	/	0	63
164	NAYANG MOISE 	/	DOUALA	/	677872605	/	1	63
165	/	/	/	/	/	/	2	63
166	BOUDA NDANE Assiatou	/	YAOUNDE	/	/	/	0	64
167	/	/	/	/	/	/	1	64
168	/	/	/	/	/	/	2	64
169	POUENE LYDIE	/	/	/	/	/	0	65
170	KEMEGNE Jean	/	/	/	678583426	/	1	65
171	/	/	/	/	/	/	2	65
172	NOAH TSALA E	/	NKOMETOU II	/	/	/	0	66
173	NGA GASPARD 	/	NKOMETOU II	/	651180474	/	1	66
174	/	/	/	/	/	/	2	66
175	BOUDIE Sandrine	/	YAOUNDE	/	677568476	/	0	67
176	WAHUA GASTON	/	YAOUNDE	/	677547143	/	1	67
177	/	/	/	/	/	/	2	67
178	NBAMBA MADELEINE	/	YAOUNDE	BASTOS	696460944	/	0	68
179	BASSOKOMENA	/	YAOUNDE	ELEVEUR	691164991	/	1	68
180	/	/	/	/	/	/	2	68
181	APTE MONIQUE	/	YAOUNDE	NGOUSSO	694997045	/	0	69
182	BOUIKISSAM VALWOUKREO		YAOUNDE	NGOUSSO	977124698	/	1	69
183	/	/	/	/	/	/	2	69
184	EKETI AMASSAKA	/	DOUALA	/	699299005	/	0	70
185	kenfack eTIENNE	/	DOUALA	/	697924046	/	1	70
186	/	/	/	/	/	/	2	70
187	NTSANA MARIE PASCALINE	/	SA'A	/	674179115	/	0	71
188	MESSI REMY (FEU)	/	/	/	/	/	1	71
189	/	/	/	/	/	/	2	71
190	NDIOKOU TATIANA	MENAGERE	NDIKINIMEKI	/	676757168	/	0	72
191	BASSOBEN BERNARD	COMMERÇANT	NDIKINIMEKI	/	672701019	/	1	72
192	/	/	/	/	/	/	2	72
193	TCHOUIELA Celestine	MENAGERE	YAOUNDE	/	674902191	/	0	73
194	MEYOU Felix	/	YAOUNDE	/	699397076	/	1	73
195	/	/	/	/	/	/	2	73
196	NDONGMO Denise	MENAGERE	YAOUNDE	NKOLFOULOU	695049217	/	0	74
197	DONGMO Antoine	/	YAOUNDE	NKOLFOULOU	670914005	/	1	74
198	/	/	/	/	/	/	2	74
199	NGUELE OLINGA Cecile	/	/	/	/	/	0	75
200	KOUMA ONANA Jean	/	/	/	677141641	/	1	75
201	/	/	/	/	/	/	2	75
202	DONFACK Clarisse	/	/	/	677434788	/	0	76
203	NGEUWOU Esaie 	/	/	/	696361970	/	1	76
204	/	/	/	/	/	/	2	76
205	SIDJIE VICTORINE	MENAGERE	YAOUNDE	NGOUSSO	691265570	/	0	77
206	HALEU JEAN	ELECTROCTHNICIEN	YAOUNDE	NGOUSSO	691661055	/	1	77
207	/	/	/	/	/	/	2	77
208	MBALA ROSINE 	/	/	/	665349072	/	0	78
209	MENANGA ATEBA 	/	/	/	668638931	/	1	78
210	/	/	/	/	/	/	2	78
211	KOSSOCK Amandine	MENAGERE	kiboum	/	/	/	0	79
212	BITIMBE Marius	cultivateur	kiboum	/	699943231		1	79
213	/	/	/	/	/	/	2	79
214	KENMEO ODETTE	MENAGERE	YAOUNDE	NGOUSSO	674437920	/	0	80
215	YOUDOM JEAN	/	YAOUNDE	NGOUSSO	/	/	1	80
216	/	/	/	/	/	/	2	80
217	MBAZOA ZO'O	MENAGERE	MBALMAYO	/	699179479	/	0	81
218	MISSA RAINIER	ENSEIGNANT	MBALMAYO	/	696145854	/	1	81
219	/	/	/	/	/	/	2	81
220	megnimang doris 	/	/	fokamezo	/	/	0	82
221	dongmo etienne	/	YAOUNDE	nkolmesseng	699560460	/	1	82
222	/	/	/	/	/	/	2	82
223	ABESSOLO MBIDA Lydie	/	/	/	678797284	/	0	83
224	ONDOA Gregoire 	/	/	/	677819029	/	1	83
225	/	/	/	/	/	/	2	83
226	BINIGA Rosalie	/	/	/	/	/	0	84
227	OKALA BILOA	/	/	/	/	/	1	84
228	/	/	/	/	/	/	2	84
229	BELLA EDZEIMBI	/	YAOUNDE	/	/	/	0	85
230	MBARGA FOE Daniel 	/	YAOUNDE	/	677416471	/	1	85
231	/	/	/	/	/	/	2	85
232	LEUTEU MARIANE	/	/	/	696288532	/	0	86
233	KOUNKEU Luc	/	TONGA	/	699580874	/	1	86
234	/	/	/	/	/	/	2	86
235	NGO MAHOP DAMARIS	/	/	/	699507369	/	0	87
236	ETCHOK0 II SALEMON	/	/	/	677058050	/	1	87
237	/	/	/	/	/	/	2	87
238	ELOUNDOU Lydie	/	YAOUNDE	/	679291184	/	0	88
239	ZOGO Denis	/	LYON/FRANCE	/	+33616672347	/	1	88
240	/	/	/	/	/	/	2	88
241	/	/	/	/	/	/	0	89
242	/	/	/	/	/	/	1	89
243	NGUEMDJO MARIE 	/	YAOUNDE	MANGUIER	677248752	/	2	89
244	EZEMBE ATYAM	/	/	EBE	666473880	/	0	90
245	MBASSI GILBERT 		YAOUNDE	EMANA	670621467	/	1	90
246	/	/	/	/	/	/	2	90
247	MENYOMO JULIETTE	/	YAOUNDE	/	675052895	/	0	91
248	ENGOLO Dieudonne	/	YAOUNDE	/	/		1	91
249	/	/	/	/	/	/	2	91
250	ESSIENE LAURENTINE	/	ASSALA II	/	694944273	/	0	92
251	NDEME ALPHONCE 	/	ASSALA II	/	/	/	1	92
252	/	/	/	/	/	/	2	92
253	BILOA FIDELIA 	MENAGERE	/	NKOLONDOM	676323439	/	0	93
254	NGAHNGONO DAVID	CHAUFFEUR	/	NKOLONDOM	675917218	/	1	93
255	/	/	/	/	/	/	2	93
256	TEUKOU	/	/	/	679208563	/	0	94
257	TCHATCHOU Richard	/	/	/	/	/	1	94
258	/	/	/	/	/	/	2	94
259	MADIESSE Felicite	/	/	/	690644955	/	0	95
260	KENGNE	/	/	/	699193618	/	1	95
261	/	/	/	/	/	/	2	95
262	NGO DJON ANASTASIE	/	DOUALA	/	677575914	/	0	96
263	/	/	/	/	/	/	1	96
264	/	/	/	/	/	/	2	96
265	AMINA OUMAROU 	/	MAGBA	/	/	/	0	97
266	ISSA SALIM 	/	MAGBA	/	675002934	/	1	97
267	/	/	/	/	/	/	2	97
268	TCHINDA BRIGITTE 	/	MBOUDA	/	676734718	/	0	98
269	FOTSO DANIEL 	/	MBOUDA 	/	669312735	/	1	98
270	/		/	/	/	/	2	98
271	NYASSA ANASTASIE 	/	YAOUNDE	MANGUIER	/	/	0	99
272	ADZENGUE 	/	YAOUNDE	MANGUIER	681618101	/	1	99
273	/	/	/	/	/	/	2	99
274	NGUISSI THERESE	/	YAOUNDE	OLEMBE	/	/	0	100
275	MEKONTCHOU JACOB	/	YAOUNDE	OLEMBE	677642641	/	1	100
276	/	/	/	/	/	/	2	100
277	WOUGANG MEUTCHEBONG	MENAGERE	YAOUNDE	NGOUSSO	674624634	/	0	101
278	CTHOUANKENG JEAN	MAÇON 	YAOUNDE	NGOUSSO	675879291	/	1	101
279	/	/	/	/	/	/	2	101
280	tsafack viviane	/	YAOUNDE	NGOUSSO	693498216	/	0	102
281	nanfack martin	/	YAOUNDE	/	675682151	/	1	102
282	/	/	/	/	/	/	2	102
283	NANKENG Pauline	/	YAOUNDE	NGOUSSO	/	/	0	103
284	NANKENG Jean	/	YAOUNDE	NGOUSSO	/	/	1	103
285	/	/	/	/	/	/	2	103
286	MENVOUTA ANNE	/	SANGMELIMA	/	656297325	/	0	104
287	ANYOU FRANCOIS 	/	/	/	/	/	1	104
288	/	/	/	/	/	/	2	104
289	FADIMATOU YARORO	/	/	/	677183311	/	0	105
290	LAMINOU	/	/	/	670908775	/	1	105
291	/	/	/	/	/	/	2	105
292	FOUMICH JACQUELINE 	/	MBOUDA	/	676168145	/	0	106
293	DJIMELI MAURICE	/	MBOUDA 	/	674581675	/	1	106
294	/	/	/	/	/	/	2	106
295	OMASSALA CLEMENTINE	/	MBANGASSINA	/	/	/	0	107
296	BESSALA CLAUDE PHILBERT	/	MBANGASSINA 	/	699645400	/	1	107
297	/	/	/	/	/	/	2	107
298	POTCHO CATHERINE	/	NDIKINIMEKI	/	695254166	/	0	108
299	ENAM 	/	NDIKINIMEKI	/	675873411	/	1	108
300	/	/	/	/	/	/	2	108
301	NGONO	/	NKOLASSA	/	/	/	0	109
302	ABENE DIEUDONNE	/	NKOLASSA	/	678462400	/	1	109
303	/	/	/	/	/	/	2	109
304	KENNE CHARLOTTE	/	/	/	/	/	0	110
305	YONTAK MOISE 	/	YAOUNDE	/	675724653	/	1	110
306	/	/	/	/	/	/	2	110
307	BARAN VICTORINE	/	/	/	679673731	/	0	111
308	NWAFOR CHRISTIAN 	/	/	MANGUIER	675162889	/	1	111
309	/	/	/	/	/	/	2	111
310	LEBA CHANTAL	/	MINKAMA	/	/	/	0	112
311	BESSALA VENANT	/	MINKAMA	/	699004733	/	1	112
312	/	/	/	/	/	/	2	112
313	ADIANA MARGUERITE	/	YAOUNDE	/	675253319	/	0	113
314	EDJOKO ALPHONSE	/	MONATELLE	/	677987796	/	1	113
315	/	/	/	/	/	/	2	113
316	mefouedong louise	/	/	/	/	/	0	114
317	malagho joseph	/	DOUALA	/	675995862	/	1	114
318	/	/	/	/	/	/	2	114
319	NOUMBISSI  LYSETTE	/	YAOUNDE	/	675626822	/	0	115
320	NOUMBISSI ISAAC	/	YAOUNDE	/	699548422	/	1	115
321	/	/	/	/	/	/	2	115
322	EBIEGNE TOJETTE	/	/	/	694198754	/	0	116
323	BEBINE ALEX (FEU)	/	/	/	/	/	1	116
324	/	/	/	/	/		2	116
325	NYISSANG MADELEINE 	/	BIKITO	/	/	/	0	117
326	AMADAMA BOBONGO 	/	/	/	/	/	1	117
327	/	/	/	/	/	/	2	117
328	NGON NDJIB JEANNE (FEU) 	/	/	/	/	/	0	118
329	NJOCK ALPHONSE 	/	YAOUNDE	/	695132894	/	1	118
330	/	/	/	/	/	/	2	118
331	/	/	/	/	/	/	0	119
332	/	/	/	/	/	/	1	119
333	NZOUAKEU JACQUELINE	/	YAOUNDE	ELIG-EDZOA	699786360	/	2	119
334	ABONA MARIE 	/	ASSALA I	/	/	/	0	120
335	MOUSSONGBIJABE JOSEH	/	ASSALA I	/	/	/	1	120
336	/	/	/	/	/	/	2	120
337	ESSILA ALIMAMS 	/	YAOUNDE	/	/	/	0	121
338	BIDIAS A'SEYI 	/	YAOUNDE	/	699566435	/	1	121
339	/	/	/	/	/	/	2	121
340	MAGNIDIONG CLARISSE	/	BAFOUSSAM	/	677753932	/	0	122
341	KUINZE RIGOBERT NATAN 	/	BAFOUSSAM	/	699474719	/	1	122
342	/	/	/	/	/	/	2	122
343	NGO MAHOP COLETTE	/	/	/	/	/	0	123
344	SOHNA BIYONG 	/	/	/	675702762	/	1	123
345	/	/	/	/	/	/	2	123
346	MAFO JEANNETTE 	/	BANGANGTE	/	676140652	/	0	124
347	/	/	/	/	/	/	1	124
348	/	/	/	/	/	/	2	124
349	NDONTSOP ANASTASIE	/	DSCHANG	/	/	/	0	125
350	MBOGNING EDOUAR 	/	DSCHANG	/	670140738	/	1	125
351	/	/	/	/	/	/	2	125
352	BEYINA NDZIE	/	EBEBDA	/	650461225	/	0	126
353	ONANA BEYINA 	/	EBEBDA	/	652100219	/	1	126
354	/	/	/	/	/	/	2	126
355	LODENE MADYI ADELINE	/	YAOUNDE	NGOUSSO	/	/	0	127
356	DATOLOUM JEROME	/	YAOUNDE	NGOUSSO	697141933	/	1	127
357	/	/	/	/	/	/	2	127
358	/	/	/	/	/	/	0	128
359	BEKONO JEAN CALVIN	/	/	/	676043808	/	1	128
360	/	/	/	/	/	/	2	128
361	ABATSONG 	/	YAOUNDE	NGOUSSO	/	/	0	129
362	ABATSONG 	/	YAOUNDE	NGOUSSO	/	/	0	130
363	BONTJEME ATEBA	/	YAOUNDE	NGOUSSO	677613280	/	1	129
364	BONTJEME ATEBA	/	YAOUNDE	NGOUSSO	677613280	/	1	130
365	/	/	/	/	/	/	2	129
366	/	/	/	/	/	/	2	130
367	MALIFOUKOYEN AMINA	/	/	KOUTABA	678958385	/	0	131
368	MEFIRE MAMA	/	YAOUNDE	MANGUIER	693548185	/	1	131
369	/	/	/	/	/	/	2	131
370	EWANDJE SIDONIE	/	YAOUNDE	NGOUSSO	977270171	/	0	132
371	KETCHATET DESIRE	/	YAOUNDE	NGOUSSO	677828183	/	1	132
372	/	/	/	/	/	/	2	132
373	BELA NGONO MESSI	/	DOUALA	/	699248853	/	0	133
374	ngawe philipe	/	DOUALA	/	69962280	/	1	133
375	/	/	/	/	/	/	2	133
376	BOUGNOTEKE THERESE	/	BATCHENGA	/	672029299	/	0	134
377	OLOKO DAVID	/	BATCHENGA	/	677449083	/	1	134
378	/	/	/	/	/	/	2	134
379	MBALLA BERNADETTE 	/	/	NKOMETOU	663901869	/	0	135
380	BESSALA LUC	/	/	NKOMETOU	673106869	/	1	135
381	/	/	/	/	/	/	2	135
382	LOTSE ODEHE SYLVIE	/	/	/	0024105382877	/	0	136
383	FOUEGO THOMAS 	/	/	/	697610817	/	1	136
384	/	/	/	/	/	/	2	136
385	NEKAM MARIE	/	YAOUNDE	/	697274761	/	0	137
386	NEKAM EMMANUEL		YAOUNDE	/	676155173	/	1	137
387	/	/	/	/	/	/	2	137
388	/	/	/	/	/	/	0	138
389	KWAYEP CHRISTOPHE	/	/	/	/	/	1	138
390	YASSSE NDOYA GERARD	/	/	/	676012203	/	2	138
391	ADAWA SOLANGE	/	/	NGOUSSO	675769207	/	0	139
392	KAMGANG FELIX	/	/	/	675310091	/	1	139
393	/	/	/	/	/	/	2	139
394	SIGNE MAFOFKU	/	YAOUNDE	ETOUDI	677208583	/	0	140
395	SIGNE RENE	/	YAOUNDE	ETOUDI	677225239	/	1	140
396	/	/	/	/	/	/	2	140
397	EYILI BELIBI	/	/	NTUI	670190734	/	0	141
398	NGONO ABE JEAN	/	/	NTUI	675710271	/	1	141
399	/	/	/	/	/	/	2	141
400	EMBOLO MARIE	/	YAOUNDE	/	675248263	/	0	142
401	BEYIDA YVES	/	YAOUNDE	/	675248263	/	1	142
402	/	/	/	/	/	/	2	142
403	NGONO PEGUE	/	/	MONATELE	677659729	/	0	143
404	MENGUINA ZONGO ZACHARIE	/	/	NLONG	677581162	/	1	143
405	/	/	/	/	/	/	2	143
406	KENGNE ABERTINE 	/	/	/	677610254	/	0	144
407	NOUPITCHOUM CHRISTOPH	/	/	/	674701509	/	1	144
408	/	/	/	/	/	/	2	144
409	KOUBA ABESSONG	/	/	/	699657356	/	0	145
410	OBOCK JEAN BLAISE	/	/	/	677423699	/	1	145
411	/	/	/	/	/	/	2	145
412	ELOUNDOU Lydie	/	YAOUNDE	/	679291184	/	0	146
413	ZOGO Denis	/	/	LYON	+33756905101	/	1	146
414	/	/	/	/	/	/	2	146
415	YENENE ADELINE	/	/	/	699238807	/	0	147
416	NGUINPEA ANTOINE	/	/	/	/	/	1	147
417	/	/	/	/	/	/	2	147
418	PETNZOUA BRIGITTE	/	BANGANGTE	/	/	/	0	148
419	TAPTCHOU DAVID	/	YAOUNDE	/	699272122	/	1	148
420	/	/	/	/	/	/	2	148
421	NGOLA SABINE	/	/	/	/	/	0	149
422	NKIE OKALA JOSEPH	/	/	/	/	/	1	149
423	/	/	/	/	/	/	2	149
424	SENA KALUME	/	AKISANGANI	/	/	/	0	150
425	LUKALA ALBERT 	AKISANGANI	/	/	/	/	1	150
426	/	/	/	/	/	/	2	150
427	PROWO SARA	/	BAFOUSSAM	/	677204456	/	0	151
428	TAMBELA JOSEPHE	/	BAFOUSSAM	/	/	/	1	151
429	/	/	/	/	/	/	2	151
430	ATSENA SAMARA	/	BAFIA	/	699263517	/	0	152
431	/	/	/	/	/	/	1	152
432	/	/	/	/	/	/	2	152
433	EYENGA YVELINE	/	/	EBOLOWA	655384891	/	0	153
434	NGOGO AWONO 	/	YAOUNDE	MESSASSI	677425389	/	1	153
435	/	/	/	/	/	/	2	153
436	TUEGUUEM BRIGITTE	/	/	/	696387434	/	0	154
437	TCHUNMEGNE BERTIN	/	/	/	697972361	/	1	154
438	/	/	/	/	/	/	2	154
439	eyenga therese	/	YAOUNDE	pakita	698510792	/	0	155
440	/	/	/	/	/	/	1	155
441	/	/	/	/	/	/	2	155
442	KENGNE SIMO 	/	MBOUDA	/	675418401	/	0	156
443	TAGNE JEAN RABERT 	/	/	/	/	/	1	156
444	/	/	/	/	/	/	2	156
445	ngono awono	/	EST	/	679631050	/	0	157
446	eye nestor 	/	/	/	/	/	1	157
447	/	/	/	/	/	/	2	157
448	NYANGONE SALOME	/	MBANDJOCK	/	674487050	/	0	158
449	ESSOMBA BERNARD 	/	MBANDJOCK	/	/	/	1	158
450	/	/	/	/	/	/	2	158
451	MANGA  TOUNA 	/	/	/	676964852	/	0	159
452	TOUNA LEBOMO JEAN-PIERRE	/	/	/	666365871	/	1	159
453	/	/	/	/	/	/	2	159
454	NGO KANA  CATHERINE	/	YAOUNDE	/	699752098	/	0	160
455	NKOU AYINDA RAPHAEL 	/	YAOUNDE	NKOLFOULOU	670752098	/	1	160
456	/	/	/	/	/	/	2	160
457	MALONGO MARIE 	/	/	NDIKINIMEKI	675057039	/	0	161
458	ENGAND ANDRE 	/	/	NDIKINIMEKI	674760398	/	1	161
459	/	/	/	/	/	/	2	161
460	ADOMMANE RICHARDE	/	ASSALA II	/	/	/	0	162
461	OLOUME ALEXANDRE	/	ASSALA II	/	678424160	/	1	162
462	/	/	/	/	/	/	2	162
463	MANKHA PKILOMENE	/	YAOUNDE	/	699134042	/	0	163
464	WAMBA MAGNOUJD (FEU)	/	/	/	/	/	1	163
465	/	/	/	/	/	/	2	163
466	TOUNDE PAULINE	/	OMBESSA	/	/	/	0	164
467	MOUMPEGNA KIKI 	/	OMBESSA	/	675513818	/	1	164
468	/	/	/	/	/	/	2	164
469	/	/	/	/	/	/	0	165
470	TAKOUSSA	/	YAOUNDE	NGOUSSO	675836906	/	1	165
471	/	/	/	/	/	/	2	165
472	NGO BIYONG VICTOIRE	/	BIYOUHA	/	693688076	/	0	166
473	MAHOPNJEG JONATHAN	/	BIYOUHA	/	693289393	/	1	166
474	/	/	/	/	/	/	2	166
475	NJAYA NICOLE	/	BAFOUSSAM	/	699670160	/	0	167
476	NDAMEN EMMANUEL 	/	BAFOUSSAM	/	674414111	/	1	167
477	/	/	/	/	/	/	2	167
478	NJAYA NICOLE	/	BAFOUSSAM	/	699670160	/	0	168
479	NDAMEN EMMANUEL 	/	BAFOUSSAM	/	697712268	/	1	168
480	/	/	/	/	/	/	2	168
481	NGO TECK PAULINE	/	ESEKA	/	699595678	/	0	169
482	KWANG ANTOINE	/	ESEKA	/	693816635	/	1	169
483	/	/	/	/	/	/	2	169
484	NOUWOO JULIEN	/	BAZOU	/	670562700	/	0	170
485	WATE JEAN	/	/	/	/	/	1	170
486	/	/	/	/	/	/	2	170
487	EYENGA MARIE	/	EKOUMDOUM	/	696239549	/	0	171
488	MBETTE NSONG 	/	ODZA	/	699696545	/	1	171
489	/	/	/	/	/	/	2	171
490	MEYO MINDJA	/	YAOUNDE	SANTA-BARBARA	690623606	/	0	172
491	NKOGO JOSEPH 	/	YAOUNDE	SANTA-BARBARA	/	/	1	172
492	/	/	/	/	/	/	2	172
493	METCHUM DESIRE	/	BANDJOUN	/	653562026	/	0	173
494	KAPCHOM JEAN PIERRE	/	BANDJOUN	/	670871050	/	1	173
495	/	/	/	/	/	/	2	173
496	ALULA EMILIENNE	/	YOKA 	/	698654877	/	0	174
497	TAFOR ERU	/	YOKA 	/	/	/	1	174
498	/	/	/	/	/	/	2	174
499	MOKAM MARIE 	/	KRIBI	/	698703212	/	0	175
500	KAMDEM DIEUDONNE 	/	KRIBI	/	696031122	/	1	175
501	/	/	/	/	/	/	2	175
502	MACTHINDA THERESE	/	BAFOUSSAM	/	676401722	/	0	176
503	TCHINDA ETIENNE	/	BAFOUSSAM	/	/	/	1	176
504	/	/	/	/	/	/	2	176
505	SIMO MARIE THERESE 		BAHAM	BAHAM	674903160		0	177
506	DOMGUIA BATHELEMY 		BAHAM	BAHAM	674903160		1	177
507	AMBADIAN ANNE						0	178
508	NGON MARTIN 		BAFIA				1	178
509	SANISSA HANDRA 		NDJAMENA		+23566266179		0	179
510	HAISSOU DOUKSIDI						1	179
511	MASSOH ROSE				675663272		0	180
512	OUMBE JEAN						1	180
513	MARIE						0	181
514	DOUMRAM ANDRE						1	181
515	NGO NKOI FRANCOISE		OBOBOGO		697999750		0	182
516	ESSOUNGA GABRIEL		OBOBOGO		699391518		1	182
517	MVONDO ONANA		NKOLMVACK		671496668		0	183
518	NOMMO BILOA		NKOLMVACK		678637603		1	183
519	MOLO OCIAVIE		YAOUNDE	NKOLMBONG	696466057		0	184
520	NGADENA DURVILLE		YAOUNDE	NKOLMBONG	695351926		1	184
521	PAGAL AUGUSTINE		DOUALA		679659073		0	185
522	NLOMNGOI NDJOCK		DOUALA		696043413		1	185
523	NZIE DENISE				693646871		0	186
524	MOUNGETYI ZEBEDEE						1	186
525	SOTCHINDA 	/	YAOUNDE	NGOUSSO	674847699	/	0	187
526	SOTCHINDA 	/	/	/	/	/	1	187
527	/	/	/	/	/	/	2	187
528	KEMBANG EVELYNE 	/	DOUALA	/	699747010	/	0	188
529	/	/	/	/	/	/	1	188
530	/	/	/	/	/	/	2	188
531	AMBILANG MAIMOUNA 	/	TCHEKOS	/	/	/	0	189
532	BOTSOMO OMSROU	/	TCHEKOS	/	/	/	1	189
533	/	/	/	/	/	/	2	189
534	BETANG 	/	YAOUNDE	NLONGKAK	675944428	/	0	190
535	NKEMI LUIS 	/	/	/	/	/	1	190
536	/	/	/	/	/	/	2	190
537	MIDZIE MARIE 		YAOUNDE	ETOUDI			0	191
538	MBIDA  GUILLAUME 		YAOUNDE	ETOUDI			1	191
539	MESSINA ENGOLO	/	YAOUNDE	/	677742362	/	0	192
540	EBODE MVONDO 	/	YAOUNDE	/	674161880	/	1	192
541	/	/	/	/	/	/	2	192
542	GNOULAYE NICOLE		YAOUNDE		694232372		0	193
543	TAMAGUELONG GABRIEL		BAFOUSSAM		677642704		1	193
544	ABOUEM A YAKAN ALPHONSE		YAOUNDE	ELEVEUR	695018242		1	194
545	LIMI ALIMA 		YAOUNDE	NKOLMESSENG	668227878		0	195
546	TARIE AMIDOU		NKOLMESSENG		668227878		1	195
547	NGUEDJO SOLANGE	/	YAOUNDE	/	051766922	/	0	196
548	DJIMANDEU DAVID	/	YAOUNDE	/	677535771	/	1	196
549	/	/	/	/	/	/	2	196
550	/	/	/	/	/	/	0	197
551	AMOUGOU DANIEL	/	YAOUNDE	/	651667893	/	1	197
552	/	/	/	/	/	/	2	197
553	GUYA ODETTE	/	BATCHAM	/	/	/	0	198
554	TEDONZONG ZACHE	/	BATCHAM	/	677578124	/	1	198
555	/	/	/	/	/	/	2	198
556	NOUBISSIE AGNES		YAOUNDE	TONGOLO	699583665		0	199
557	TCHOUMO DJAMBOU 		YAOUNDE	TONGOLO			1	199
558	MBUNEN INES		YAOUNDE	FOUGEROLLE	699181403	/	0	200
559	MBUMEN GUY	/	YAOUNDE	FOUGEROLLE	697788520	/	1	200
560	/	/	/	/	/	/	2	200
561	NGALEU JEANNA 	/	OKOLA	/	694909434	/	0	201
562	MENE ETOUNDI	/	OKOLA 	/	6943229146	/	1	201
563	/	/	/	/	/	/	2	201
564	NGO NGUUCK	/	YAOUNDE	NKOLMESSENG	/	/	0	202
565	KOOH PAUL HERVE	/	YAOUNDE	NKOLMESSENG	/	/	1	202
566	/	/	/	/	/	/	2	202
567	MAGIP BADANG 	/	OMBESSA	/	/	/	0	203
568	GUIEGOU NDONGO	/	OMBESSA	/	693346632	/	1	203
569	/	/	/	/	/	/	2	203
570	PUBEN BIBIANE 	/	/	/	677041550	/	0	204
571	/	/	/	/	/	/	1	204
572	/	/	/	/	/	/	2	204
573	NGAMBIA JACQUELINE	/	YAOUNDE	/	/	242140047	0	205
574	MBANG ZINTCHEM 	/	BAFIA 	/	/	/	1	205
575	/	/	/	/	/	/	2	205
576	ADIOME BERNADETTE	/	YAOUNDE	/	696422733	/	0	206
577	MOUSSA BAWA 	/	BELABO	/	698757115	/	1	206
578	/	/	/	/	/	/	2	206
579	DIDJATOU 	/	/	/	/	/	0	207
580	ALKILI BARKA	/	/	/	699426539	/	1	207
581	/	/	/	/	/	/	2	207
582	MEGNE FRANÇOISE	/	/	/	675047114	/	0	208
583	TAMBOU DIEUDONNE	/	/	/	/	/	1	208
584	/	/	/	/	/	/	2	208
585	DJOUELA ADELINE 		DOUALA		675261276		0	209
586	KUEDJO JEAN-PIERRE		DOUALA				1	209
587	HAPPI NANCI CLAUDIA 		DOUALA		698882533		0	210
588	YAMENI HERVE BRUNO 		YAOUNDE		694698638		1	210
589	OLA COLETTE 	/	OBALA 	/	657658195	/	0	211
590	ATANGANA CHARLES 	MAÇON	OBALA	/	658736672	/	1	211
591	/	/	/	/	/	/	2	211
592	EWOLEGA MARTINE 	/	YAOUNDE	NGOUSSO	670025609	/	0	212
593	/	/	/	/	/	/	1	212
594	/	/	/	/	/	/	2	212
595	NYANGONE MBOM JUSTINE 		NANGA-EBOKO	/	651165386	/	0	213
596	MELIGUI GUY DESIRE 	/	NANGA-EBOKO	/	676167521	/	1	213
597	/	/	/	/	/	/	2	213
598	NDJAKOMO AGATHE		YAOUNDE	CHAPELLE NGOUSSO	242687399		0	214
599	NDEME CATHERINE 	/	YAOUNDE	/	655419584	/	0	215
600	MOUDIO JOSEPH 	/	YAOUNDE	/	694853920	/	1	215
601	/	/	/	/	/	/	2	215
602	FOUDA C ECILE		YAOUNDE	NKOLBIYEM	696023178		0	216
603	NOGO TARCICIUS		LEKA		670900518		1	216
604	OLOA DELPHINE 		YAOUNDE				0	217
605	BIDIAS AMBASSA DIDIL		YAOUNDE		699908681		1	217
606	WEPE PAULYVETTE	/	YAOUNDE	NGOUSSO	652087475	/	0	218
607	NJILE ISAAC 	/	YAOUNDE	NGOUSSO	677853263	/	1	218
608	/	/	/	/	/	/	2	218
609	/	/	/	/	/	/	0	219
610	BEKONO JEAN CALVIN 	/	/	/	676043808	/	1	219
611	/	/	/	/	/	/	2	219
612	NDJAKOMO	/	YAOUNDE	/	242687399	/	0	220
613	/	/	/	/	/	/	1	220
614	/	/	/	/	/	/	2	220
615	ndassa marie		YAOUNDE				0	221
616	NDASSA ADOLPHE 		YAOUNDE				1	221
617	YODJEU NGASSAM VIRGINIE  		BAFOUSSAM				0	222
618	FOPA INNOCENT 		BAFOUSSAM				1	222
619	/		/	/			1	223
620	/	/	/				1	224
621	MENDOUGA	/	/	/	/	/	0	225
622	NGONO EMILE 	/	/	/	676043808	/	1	225
623	/	/	/	/	/	/	2	225
624	AVOTTO BELLA 	/	BENGBIS	/	/	/	0	226
625	EKOTTO JACOB	/	SANGMELIMA 	/	667530411	/	1	226
626	/	/	/	/	/	/	2	226
627	kouda madeleine	/	bamendjou	/	/	/	0	227
628	tchoupe remi	/	bamendjou	/	/	/	1	227
629	/	/	/	/	/	/	2	227
\.


--
-- Name: parent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('parent_id_seq', 629, true);


--
-- Data for Name: payement; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY payement (id, id_eleve, annee_scolaire, date_payement, montant, motif, numero, mode_paye, ref_versement, lettre) FROM stdin;
4	3	2018-2019	2018-09-17	40000	Inscription	00000004	\N	\N	\N
5	37	2018-2019	2018-09-19	40000	Inscription	00000005	\N	\N	\N
6	37	2018-2019	2018-09-19	105000	1 ere tranche	00000006	espece		\N
7	29	2018-2019	2018-09-19	40000	Inscription	00000007	\N	\N	\N
8	29	2018-2019	2018-09-19	80000	AVANCE PREMIERE TRANCHE	00000008	espece		\N
9	38	2018-2019	2018-09-19	40000	Inscription	00000009	\N	\N	\N
10	38	2018-2019	2018-09-19	40000	AVANCE PREMIERE TRANCHE	00000010	espece		\N
11	39	2018-2019	2018-09-19	40000	Inscription	00000011	\N	\N	\N
12	39	2018-2019	2018-09-19	105000	1 ere tranche	00000012	espece		\N
13	40	2018-2019	2018-09-19	40000	Inscription	00000013	\N	\N	\N
14	26	2018-2019	2018-09-19	40000	Inscription	00000014	\N	\N	\N
15	21	2018-2019	2018-09-19	40000	Inscription	00000015	\N	\N	\N
16	21	2018-2019	2018-09-19	60000	AVANCE PREMIÈRE TRANCHE	00000016	espece		\N
17	34	2018-2019	2018-09-19	40000	Inscription	00000017	\N	\N	\N
18	9	2018-2019	2018-09-19	40000	Inscription	00000018	\N	\N	\N
19	9	2018-2019	2018-09-19	345000	TOTALITE	00000019	espece		\N
20	8	2018-2019	2018-09-19	40000	Inscription	00000020	\N	\N	\N
21	8	2018-2019	2018-09-19	115000	1 ere tranche	00000021	espece		\N
22	11	2018-2019	2018-09-19	40000	Inscription	00000022	\N	\N	\N
23	16	2018-2019	2018-09-19	40000	Inscription	00000023	\N	\N	\N
24	16	2018-2019	2018-09-19	115000	1 ere tranche	00000024	espece		\N
25	17	2018-2019	2018-09-19	40000	Inscription	00000025	\N	\N	\N
26	17	2018-2019	2018-09-19	45000	AVANCE PREMIÈRE TRANCHE	00000026	espece		\N
27	22	2018-2019	2018-09-19	40000	Inscription	00000027	\N	\N	\N
28	22	2018-2019	2018-09-19	60000	AVANCE PREMIÈRE TRANCHE	00000028	espece		\N
29	41	2018-2019	2018-09-19	40000	Inscription	00000029	\N	\N	\N
30	41	2018-2019	2018-09-19	105000	1 ere tranche	00000030	espece		\N
31	7	2018-2019	2018-09-19	40000	Inscription	00000031	\N	\N	\N
32	6	2018-2019	2018-09-19	40000	Inscription	00000032	\N	\N	\N
33	6	2018-2019	2018-09-19	100000	AVANCE PREMIÈRE TRANCHE	00000033	espece		\N
34	23	2018-2019	2018-09-19	40000	Inscription	00000034	\N	\N	\N
35	23	2018-2019	2018-09-19	105000	1 ere tranche	00000035	espece		\N
36	20	2018-2019	2018-09-19	40000	Inscription	00000036	\N	\N	\N
37	31	2018-2019	2018-09-19	40000	Inscription	00000037	\N	\N	\N
38	31	2018-2019	2018-09-19	105000	1 ere tranche	00000038	espece		\N
39	42	2018-2019	2018-09-19	40000	Inscription	00000039	\N	\N	\N
40	10	2018-2019	2018-09-19	40000	Inscription	00000040	\N	\N	\N
41	36	2018-2019	2018-09-19	40000	Inscription	00000041	\N	\N	\N
42	36	2018-2019	2018-09-19	105000	1 ere tranche	00000042	espece		\N
43	5	2018-2019	2018-09-20	40000	Inscription	00000043	\N	\N	\N
44	5	2018-2019	2018-09-20	105000	1 ere tranche	00000044	espece		\N
45	4	2018-2019	2018-09-20	40000	Inscription	00000045	\N	\N	\N
46	12	2018-2019	2018-09-20	40000	Inscription	00000046	\N	\N	\N
47	12	2018-2019	2018-09-20	115000	1 ere tranche	00000047	espece		\N
48	15	2018-2019	2018-09-20	40000	Inscription	00000048	\N	\N	\N
49	14	2018-2019	2018-09-20	40000	Inscription	00000049	\N	\N	\N
50	14	2018-2019	2018-09-20	185000	1 ère tranche + AVANCE 2me TRANCHE	00000050	espece		\N
52	19	2018-2019	2018-09-20	40000	Inscription	00000052	\N	\N	\N
53	19	2018-2019	2018-09-20	105000	1 ere tranche	00000053	espece		\N
54	18	2018-2019	2018-09-20	40000	Inscription	00000054	\N	\N	\N
55	18	2018-2019	2018-09-20	105000	1 ere tranche	00000055	espece		\N
56	28	2018-2019	2018-09-20	40000	Inscription	00000056	\N	\N	\N
57	27	2018-2019	2018-09-20	40000	Inscription	00000057	\N	\N	\N
58	27	2018-2019	2018-09-20	105000	1 ere tranche	00000058	espece		\N
59	30	2018-2019	2018-09-20	40000	Inscription	00000059	\N	\N	\N
60	30	2018-2019	2018-09-20	106000	1 ère tranche + AVANCE 2me TRANCHE	00000060	espece		\N
61	32	2018-2019	2018-09-20	40000	Inscription	00000061	\N	\N	\N
62	32	2018-2019	2018-09-20	95000	AVANCE PREMIÈRE TRANCHE	00000062	espece		\N
63	3	2018-2019	2018-09-20	115000	1 ere tranche	00000063	espece		\N
64	25	2018-2019	2018-09-20	40000	Inscription	00000064	\N	\N	\N
65	25	2018-2019	2018-09-20	105000	1 ere tranche	00000065	espece		\N
66	33	2018-2019	2018-09-20	40000	Inscription	00000066	\N	\N	\N
67	33	2018-2019	2018-09-20	110000	AVANCE PREMIÈRE TRANCHE	00000067	espece		\N
68	35	2018-2019	2018-09-20	40000	Inscription	00000068	\N	\N	\N
69	24	2018-2019	2018-09-20	40000	Inscription	00000069	\N	\N	\N
70	43	2018-2019	2018-09-20	40000	Inscription	00000070	\N	\N	\N
71	43	2018-2019	2018-09-20	40000	AVANCE PREMIÈRE TRANCHE	00000071	espece		\N
72	30	2018-2019	2018-09-20	54000	AVANCE DEUXIÈME  TRANCHE	00000072	espece		\N
73	44	2018-2019	2018-09-20	40000	Inscription	00000073	\N	\N	\N
74	45	2018-2019	2018-09-20	40000	Inscription	00000074	\N	\N	\N
75	46	2018-2019	2018-09-20	40000	Inscription	00000075	\N	\N	\N
76	47	2018-2019	2018-09-20	40000	Inscription	00000076	\N	\N	\N
77	48	2018-2019	2018-09-20	40000	Inscription	00000077	\N	\N	\N
78	48	2018-2019	2018-09-20	110000	AVANCE PREMIÈRE TRANCHE	00000078	espece		\N
79	24	2018-2019	2018-09-21	150000	1 ère tranche + AVANCE 2me TRANCHE	00000079	espece		\N
80	49	2018-2019	2018-09-21	40000	Inscription	00000080	\N	\N	\N
81	42	2018-2019	2018-09-24	115000	1 ere tranche	00000081	espece		\N
82	50	2018-2019	2018-09-24	40000	Inscription	00000082	\N	\N	\N
83	50	2018-2019	2018-09-24	115000	1 ere tranche	00000083	espece		\N
84	51	2018-2019	2018-09-24	40000	Inscription	00000084	\N	\N	\N
85	52	2018-2019	2018-09-24	40000	Inscription	00000085	\N	\N	\N
86	53	2018-2019	2018-09-24	40000	Inscription	00000086	\N	\N	\N
87	53	2018-2019	2018-09-24	150000	1 ere tranche	00000087	espece		\N
88	54	2018-2019	2018-09-24	40000	Inscription	00000088	\N	\N	\N
89	55	2018-2019	2018-09-24	40000	Inscription	00000089	\N	\N	\N
90	56	2018-2019	2018-09-24	50000	Inscription	00000090	\N	\N	\N
91	56	2018-2019	2018-09-24	20000	ETUDE DE DOSSIER	00000091	espece		\N
92	57	2018-2019	2018-09-25	40000	Inscription	00000092	\N	\N	\N
93	57	2018-2019	2018-09-25	60000	AVANCE PREMIÈRE TRANCHE	00000093	espece		\N
94	58	2018-2019	2018-09-25	40000	Inscription	00000094	\N	\N	\N
95	58	2018-2019	2018-09-25	50000	AVANCE PREMIÈRE TRANCHE	00000095	espece		\N
96	59	2018-2019	2018-09-27	40000	Inscription	00000096	\N	\N	\N
97	59	2018-2019	2018-09-27	105000	AVANCE PREMIÈRE TRANCHE	00000097	espece		\N
98	60	2018-2019	2018-09-27	40000	Inscription	00000098	\N	\N	\N
99	61	2018-2019	2018-09-27	40000	Inscription	00000099	\N	\N	\N
101	63	2018-2019	2018-09-28	40000	Inscription	00000101	\N	\N	\N
102	64	2018-2019	2018-09-28	40000	Inscription	00000102	\N	\N	\N
103	65	2018-2019	2018-10-01	40000	Inscription	00000103	\N	\N	\N
104	67	2018-2019	2018-10-01	40000	Inscription	00000104	\N	\N	\N
105	66	2018-2019	2018-10-01	40000	Inscription	00000105	\N	\N	\N
106	66	2018-2019	2018-10-01	60000	AVANCE PREMIÈRE TRANCHE	00000106	espece		\N
107	68	2018-2019	2018-10-01	40000	Inscription	00000107	\N	\N	\N
108	68	2018-2019	2018-10-01	405000	AVANCE PREMIÈRE TRANCHE	00000108	espece		\N
109	69	2018-2019	2018-10-01	40000	Inscription	00000109	\N	\N	\N
110	69	2018-2019	2018-10-01	105000	PREMIÈRE TRANCHE	00000110	espece		\N
111	70	2018-2019	2018-10-01	40000	Inscription	00000111	\N	\N	\N
112	71	2018-2019	2018-10-01	40000	Inscription	00000112	\N	\N	\N
113	72	2018-2019	2018-10-01	40000	Inscription	00000113	\N	\N	\N
114	72	2018-2019	2018-10-01	65000	AVANCE PREMIÈRE TRANCHE	00000114	espece		\N
115	73	2018-2019	2018-10-02	40000	Inscription	00000115	\N	\N	\N
116	74	2018-2019	2018-10-02	40000	Inscription	00000116	\N	\N	\N
117	74	2018-2019	2018-10-02	110000	AVANCE PREMIÈRE TRANCHE	00000117	espece		\N
118	75	2018-2019	2018-10-02	40000	Inscription	00000118	\N	\N	\N
119	76	2018-2019	2018-10-02	40000	Inscription	00000119	\N	\N	\N
120	76	2018-2019	2018-10-02	71000	AVANCE PREMIÈRE TRANCHE	00000120	espece		\N
121	20	2018-2019	2018-10-02	105000	PREMIÈRE TRANCHE	00000121	espece		\N
122	17	2018-2019	2018-10-02	40000	AVANCE PREMIÈRE TRANCHE	00000122	espece		\N
123	17	2018-2019	2018-10-02	20000	PREMIÈRE TRANCHE	00000123	espece		\N
124	55	2018-2019	2018-10-02	50000	AVANCE PREMIÈRE TRANCHE	00000124	espece		\N
125	77	2018-2019	2018-10-03	40000	Inscription	00000125	\N	\N	\N
127	78	2018-2019	2018-10-03	40000	Inscription	00000127	\N	\N	\N
128	78	2018-2019	2018-10-03	30000	AVANCE PREMIÈRE TRANCHE	00000128	espece		\N
129	79	2018-2019	2018-10-03	40000	Inscription	00000129	\N	\N	\N
130	80	2018-2019	2018-10-03	40000	Inscription	00000130	\N	\N	\N
131	79	2018-2019	2018-10-03	20000	AVANCE PREMIÈRE TRANCHE	00000131	espece		\N
132	79	2018-2019	2018-10-03	20000	AVANCE PREMIÈRE TRANCHE	00000132	espece		\N
133	81	2018-2019	2018-10-03	40000	Inscription	00000133	\N	\N	\N
134	81	2018-2019	2018-10-03	100000	AVANCE PREMIÈRE TRANCHE	00000134	espece		\N
135	82	2018-2019	2018-10-03	40000	Inscription	00000135	\N	\N	\N
136	83	2018-2019	2018-10-04	40000	Inscription	00000136	\N	\N	\N
137	83	2018-2019	2018-10-04	50000	AVANCE PREMIÈRE TRANCHE	00000137	espece		\N
138	84	2018-2019	2018-10-04	40000	Inscription	00000138	\N	\N	\N
139	84	2018-2019	2018-10-04	50000	AVANCE PREMIÈRE TRANCHE	00000139	espece		\N
140	85	2018-2019	2018-10-04	40000	Inscription	00000140	\N	\N	\N
141	85	2018-2019	2018-10-04	105000	PREMIÈRE TRANCHE	00000141	espece		\N
142	86	2018-2019	2018-10-04	50000	Inscription	00000142	\N	\N	\N
143	87	2018-2019	2018-10-05	40000	Inscription	00000143	\N	\N	\N
144	87	2018-2019	2018-10-05	50000	AVANCE PREMIÈRE TRANCHE	00000144	espece		\N
145	88	2018-2019	2018-10-06	40000	Inscription	00000145	\N	\N	\N
146	89	2018-2019	2018-10-06	40000	Inscription	00000146	\N	\N	\N
147	89	2018-2019	2018-10-06	105000	PREMIÈRE TRANCHE	00000147	espece		\N
148	90	2018-2019	2018-10-06	40000	Inscription	00000148	\N	\N	\N
149	33	2018-2019	2018-10-08	100000	fin de la PREMIÈRE TRANCHE + avance deuxième tranche	00000149	espece		\N
150	91	2018-2019	2018-10-08	40000	Inscription	00000150	\N	\N	\N
151	92	2018-2019	2018-10-08	40000	Inscription	00000151	\N	\N	\N
152	93	2018-2019	2018-10-09	40000	Inscription	00000152	\N	\N	\N
153	94	2018-2019	2018-10-09	40000	Inscription	00000153	\N	\N	\N
155	96	2018-2019	2018-10-09	40000	Inscription	00000155	\N	\N	\N
156	96	2018-2019	2018-10-09	65000	AVANCE PREMIÈRE TRANCHE	00000156	espece		\N
157	97	2018-2019	2018-10-09	50000	Inscription	00000157	\N	\N	\N
158	4	2018-2019	2018-10-09	115000	PREMIÈRE TRANCHE	00000158	espece		\N
159	98	2018-2019	2018-10-09	40000	Inscription	00000159	\N	\N	\N
160	98	2018-2019	2018-10-09	330000	PREMIÈRE TRANCHE + DEUXIÈME TRANCHE + AVANCE TROISIÈMEMENT TRANCHE	00000160	espece		\N
161	99	2018-2019	2018-10-09	40000	Inscription	00000161	\N	\N	\N
162	100	2018-2019	2018-10-09	40000	Inscription	00000162	\N	\N	\N
163	101	2018-2019	2018-10-09	40000	Inscription	00000163	\N	\N	\N
164	102	2018-2019	2018-10-09	40000	Inscription	00000164	\N	\N	\N
165	95	2018-2019	2018-10-09	40000	Inscription	00000165	\N	\N	\N
166	62	2018-2019	2018-10-09	40000	Inscription	00000166	\N	\N	\N
167	62	2018-2019	2018-10-09	165000	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000167	espece		\N
168	103	2018-2019	2018-10-11	40000	Inscription	00000168	\N	\N	\N
169	104	2018-2019	2018-10-11	40000	Inscription	00000169	\N	\N	\N
170	104	2018-2019	2018-10-11	40000	PREMIÈRE TRANCHE	00000170	espece		\N
171	105	2018-2019	2018-10-11	40000	Inscription	00000171	\N	\N	\N
172	105	2018-2019	2018-10-11	145000	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000172	espece		\N
173	46	2018-2019	2018-10-11	85000	AVANCE PREMIÈRE TRANCHE	00000173	espece		\N
174	106	2018-2019	2018-10-12	40000	Inscription	00000174	\N	\N	\N
175	107	2018-2019	2018-10-12	40000	Inscription	00000175	\N	\N	\N
176	108	2018-2019	2018-10-12	40000	Inscription	00000176	\N	\N	\N
177	109	2018-2019	2018-10-12	40000	Inscription	00000177	\N	\N	\N
178	78	2018-2019	2018-10-15	70000	AVANCE PREMIÈRE TRANCHE	00000178	espece		\N
179	110	2018-2019	2018-10-15	40000	Inscription	00000179	\N	\N	\N
180	111	2018-2019	2018-10-15	40000	Inscription	00000180	\N	\N	\N
181	111	2018-2019	2018-10-15	115000	PREMIÈRE TRANCHE	00000181	espece		\N
182	111	2018-2019	2018-10-15	115000	PREMIÈRE TRANCHE	00000182	espece		\N
183	112	2018-2019	2018-10-15	40000	Inscription	00000183	\N	\N	\N
184	112	2018-2019	2018-10-15	100000	AVANCE PREMIÈRE TRANCHE	00000184	espece		\N
185	113	2018-2019	2018-10-15	40000	Inscription	00000185	\N	\N	\N
186	114	2018-2019	2018-10-15	40000	Inscription	00000186	\N	\N	\N
187	115	2018-2019	2018-10-15	40000	Inscription	00000187	\N	\N	\N
188	116	2018-2019	2018-10-15	40000	Inscription	00000188	\N	\N	\N
189	55	2018-2019	2018-10-15	45000	AVANCE PREMIÈRE TRANCHE	00000189	espece		\N
190	117	2018-2019	2018-10-15	40000	Inscription	00000190	\N	\N	\N
191	118	2018-2019	2018-10-15	40000	Inscription	00000191	\N	\N	\N
192	119	2018-2019	2018-10-15	40000	Inscription	00000192	\N	\N	\N
193	120	2018-2019	2018-10-15	40000	Inscription	00000193	\N	\N	\N
194	121	2018-2019	2018-10-15	40000	Inscription	00000194	\N	\N	\N
195	122	2018-2019	2018-10-16	40000	Inscription	00000195	\N	\N	\N
196	122	2018-2019	2018-10-16	115000	PREMIÈRE TRANCHE	00000196	espece		\N
197	123	2018-2019	2018-10-16	40000	Inscription	00000197	\N	\N	\N
198	48	2018-2019	2018-10-16	5000	PREMIÈRE TRANCHE	00000198	espece		\N
199	124	2018-2019	2018-10-17	40000	Inscription	00000199	\N	\N	\N
200	124	2018-2019	2018-10-17	60000	AVANCE PREMIÈRE TRANCHE	00000200	espece		\N
201	125	2018-2019	2018-10-17	40000	Inscription	00000201	\N	\N	\N
202	125	2018-2019	2018-10-17	117500	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000202	espece		\N
203	126	2018-2019	2018-10-17	40000	Inscription	00000203	\N	\N	\N
204	126	2018-2019	2018-10-17	80000	AVANCE PREMIÈRE TRANCHE	00000204	espece		\N
205	127	2018-2019	2018-10-17	40000	Inscription	00000205	\N	\N	\N
206	129	2018-2019	2018-10-17	40000	Inscription	00000206	\N	\N	\N
207	129	2018-2019	2018-10-17	110000	AVANCE PREMIÈRE TRANCHE	00000207	espece		\N
208	11	2018-2019	2018-10-17	95000	AVANCE PREMIÈRE TRANCHE	00000208	espece		\N
209	131	2018-2019	2018-10-17	40000	Inscription	00000209	\N	\N	\N
210	132	2018-2019	2018-10-17	50000	Inscription	00000210	\N	\N	\N
211	133	2018-2019	2018-10-18	40000	Inscription	00000211	\N	\N	\N
212	134	2018-2019	2018-10-18	40000	Inscription	00000212	\N	\N	\N
213	134	2018-2019	2018-10-18	150000	PREMIÈRE TRANCHE	00000213	espece		\N
214	135	2018-2019	2018-10-18	50000	Inscription	00000214	\N	\N	\N
215	136	2018-2019	2018-10-18	40000	Inscription	00000215	\N	\N	\N
216	137	2018-2019	2018-10-18	40000	Inscription	00000216	\N	\N	\N
217	137	2018-2019	2018-10-18	60000	AVANCE PREMIÈRE TRANCHE	00000217	espece		\N
218	138	2018-2019	2018-10-18	40000	Inscription	00000218	\N	\N	\N
219	138	2018-2019	2018-10-18	70000	AVANCE PREMIÈRE TRANCHE	00000219	espece		\N
220	139	2018-2019	2018-10-20	40000	Inscription	00000220	\N	\N	\N
221	140	2018-2019	2018-10-20	40000	Inscription	00000221	\N	\N	\N
222	141	2018-2019	2018-10-20	40000	Inscription	00000222	\N	\N	\N
223	142	2018-2019	2018-10-20	40000	Inscription	00000223	\N	\N	\N
224	143	2018-2019	2018-10-22	40000	Inscription	00000224	\N	\N	\N
225	143	2018-2019	2018-10-22	115000	PREMIÈRE TRANCHE	00000225	espece		\N
226	144	2018-2019	2018-10-22	40000	Inscription	00000226	\N	\N	\N
227	144	2018-2019	2018-10-22	115000	PREMIÈRE TRANCHE	00000227	espece		\N
228	145	2018-2019	2018-10-22	40000	Inscription	00000228	\N	\N	\N
229	146	2018-2019	2018-10-22	40000	Inscription	00000229	\N	\N	\N
230	147	2018-2019	2018-10-22	40000	Inscription	00000230	\N	\N	\N
231	147	2018-2019	2018-10-22	100000	AVANCE PREMIÈRE TRANCHE	00000231	espece		\N
232	148	2018-2019	2018-10-22	40000	Inscription	00000232	\N	\N	\N
233	148	2018-2019	2018-10-22	85000	AVANCE PREMIÈRE TRANCHE	00000233	espece		\N
234	55	2018-2019	2018-10-22	100000	fin de la PREMIÈRE TRANCHE + avance deuxième tranche	00000234	espece		\N
235	73	2018-2019	2018-10-23	105000	PREMIÈRE TRANCHE	00000235	espece		\N
236	149	2018-2019	2018-10-23	40000	Inscription	00000236	\N	\N	\N
237	149	2018-2019	2018-10-23	130000	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000237	espece		\N
238	150	2018-2019	2018-10-24	40000	Inscription	00000238	\N	\N	\N
239	69	2018-2019	2018-10-24	160000	RESTE PENSION	00000239	espece		\N
240	148	2018-2019	2018-10-24	20000	PREMIÈRE TRANCHE	00000240	espece		\N
241	148	2018-2019	2018-10-24	20000	PREMIÈRE TRANCHE	00000241	espece		\N
242	151	2018-2019	2018-10-24	40000	Inscription	00000242	\N	\N	\N
243	140	2018-2019	2018-10-24	160000	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000243	espece		\N
244	113	2018-2019	2018-10-24	70000	AVANCE PREMIÈRE TRANCHE	00000244	espece		\N
245	71	2018-2019	2018-10-24	90000	AVANCE PREMIÈRE TRANCHE	00000245	espece		\N
246	152	2018-2019	2018-10-24	40000	Inscription	00000246	\N	\N	\N
247	153	2018-2019	2018-10-24	40000	Inscription	00000247	\N	\N	\N
248	154	2018-2019	2018-10-24	40000	Inscription	00000248	\N	\N	\N
249	155	2018-2019	2018-10-24	40000	Inscription	00000249	\N	\N	\N
250	155	2018-2019	2018-10-24	100000	AVANCE PREMIÈRE TRANCHE	00000250	espece		\N
251	50	2018-2019	2018-10-25	115000	 DEUXIÈME TRANCHE 	00000251	espece		\N
252	156	2018-2019	2018-10-25	40000	Inscription	00000252	\N	\N	\N
253	157	2018-2019	2018-10-25	40000	Inscription	00000253	\N	\N	\N
254	157	2018-2019	2018-10-25	115000	PREMIÈRE TRANCHE	00000254	espece		\N
255	158	2018-2019	2018-10-25	40000	Inscription	00000255	\N	\N	\N
256	159	2018-2019	2018-10-26	40000	Inscription	00000256	\N	\N	\N
257	159	2018-2019	2018-10-26	120000	PREMIÈRE TRANCHE +  AVANCE deuxième  TRANCHE	00000257	espece		\N
258	156	2018-2019	2018-10-26	55000	AVANCE PREMIÈRE TRANCHE	00000258	espece		\N
259	160	2018-2019	2018-10-26	40000	Inscription	00000259	\N	\N	\N
260	160	2018-2019	2018-10-26	115000	PREMIÈRE TRANCHE	00000260	espece		\N
261	161	2018-2019	2018-10-27	40000	Inscription	00000261	\N	\N	\N
262	161	2018-2019	2018-10-27	40000	AVANCE PREMIÈRE TRANCHE	00000262	espece		\N
263	162	2018-2019	2018-10-29	40000	Inscription	00000263	\N	\N	\N
264	162	2018-2019	2018-10-29	60000	AVANCE PREMIÈRE TRANCHE	00000264	espece		\N
265	35	2018-2019	2018-10-29	105000	PREMIÈRE TRANCHE	00000265	espece		\N
266	163	2018-2019	2018-10-29	40000	Inscription	00000266	\N	\N	\N
267	164	2018-2019	2018-10-29	40000	Inscription	00000267	\N	\N	\N
268	61	2018-2019	2018-10-29	40000	AVANCE PREMIÈRE TRANCHE	00000268	espece		\N
269	165	2018-2019	2018-10-29	40000	Inscription	00000269	\N	\N	\N
270	165	2018-2019	2018-10-29	90000	AVANCE PREMIÈRE TRANCHE	00000270	espece		\N
271	166	2018-2019	2018-10-29	40000	Inscription	00000271	\N	\N	\N
272	167	2018-2019	2018-10-29	50000	Inscription	00000272	\N	\N	\N
273	168	2018-2019	2018-10-29	50000	Inscription	00000273	\N	\N	\N
274	55	2018-2019	2018-10-30	50000	fin de la deuxième tranche + avance troisième tranche	00000274	espece		\N
275	117	2018-2019	2018-10-30	115000	PREMIÈRE TRANCHE	00000275	espece		\N
276	63	2018-2019	2018-10-30	105000	PREMIÈRE TRANCHE	00000276	espece		\N
277	10	2018-2019	2018-10-30	115000	PREMIÈRE TRANCHE	00000277	espece		\N
278	43	2018-2019	2018-10-30	65000	PREMIÈRE TRANCHE	00000278	espece		\N
279	169	2018-2019	2018-10-30	40000	Inscription	00000279	\N	\N	\N
280	170	2018-2019	2018-10-30	40000	Inscription	00000280	\N	\N	\N
281	171	2018-2019	2018-10-30	40000	Inscription	00000281	\N	\N	\N
282	171	2018-2019	2018-10-30	60000	AVANCE PREMIÈRE TRANCHE	00000282	espece		\N
283	84	2018-2019	2018-10-31	75000	AVANCE PREMIÈRE TRANCHE	00000283	espece		\N
284	137	2018-2019	2018-10-31	140000	fin de la PREMIÈRE TRANCHE + avance deuxième tranche	00000284	espece		\N
285	146	2018-2019	2018-10-31	105000	PREMIÈRE TRANCHE	00000285	espece		\N
286	172	2018-2019	2018-10-31	40000	Inscription	00000286	\N	\N	\N
287	154	2018-2019	2018-11-01	105000	PREMIÈRE TRANCHE	00000287	espece		\N
288	64	2018-2019	2018-11-01	60000	AVANCE PREMIÈRE TRANCHE	00000288	espece		\N
289	45	2018-2019	2018-11-01	50000	AVANCE PREMIÈRE TRANCHE	00000289	espece		\N
290	173	2018-2019	2018-11-01	40000	Inscription	00000290	\N	\N	\N
291	173	2018-2019	2018-11-01	105000	PREMIÈRE TRANCHE	00000291	espece		\N
292	174	2018-2019	2018-11-01	40000	Inscription	00000292	\N	\N	\N
293	174	2018-2019	2018-11-01	44000	AVANCE PREMIÈRE TRANCHE	00000293	espece		\N
294	22	2018-2019	2018-11-01	40000	AVANCE PREMIÈRE TRANCHE	00000294	espece		\N
295	87	2018-2019	2018-11-01	55000	PREMIÈRE TRANCHE	00000295	espece		\N
296	88	2018-2019	2018-11-01	95000	AVANCE PREMIÈRE TRANCHE	00000296	espece		\N
297	108	2018-2019	2018-11-01	90000	AVANCE PREMIÈRE TRANCHE	00000297	espece		\N
298	136	2018-2019	2018-11-02	30000	AVANCE PREMIÈRE TRANCHE	00000298	espece		\N
299	175	2018-2019	2018-11-02	40000	Inscription	00000299	\N	\N	\N
300	175	2018-2019	2018-11-02	105000	PREMIÈRE TRANCHE	00000300	espece		\N
301	176	2018-2019	2018-11-02	40000	Inscription	00000301	\N	\N	\N
302	176	2018-2019	2018-11-02	80000	AVANCE PREMIÈRE TRANCHE	00000302	espece		\N
303	177	2017-2018	2018-11-08	40000	Inscription	00000303	\N	\N	quarante mille franc cfa
304	79	2018-2019	2018-11-08	15000	avance première tranche	00000304	espece		
305	79	2018-2019	2018-11-08	50000	solde première tranche	00000305	espece		
306	49	2018-2019	2018-11-08	105000	 première tranche	00000306	espece		
317	93	2018-2019	2018-11-08	105000	 première tranche	00000317	espece		CENT cinq MILLE fcfa
308	177	2017-2018	2018-11-08	50000	AVANCE 1ère tranche	00000308	espece		CINQUANTE MILLE
309	177	2017-2018	2018-11-08	100000	avance première tranche	00000309	espece		CENT MILLE
310	44	2018-2019	2018-11-08	100000	avance première tranche	00000310	espece		
311	80	2018-2019	2018-11-08	115000	 première tranche	00000311	espece		CENT QUINZE MILLE FCFA
312	138	2018-2019	2018-11-08	35000	 première tranche	00000312	espece		trente cinq mille fcfa
313	26	2018-2019	2018-11-08	105000	 première tranche	00000313	espece		CENT cinq MILLE fcfa
314	95	2018-2019	2018-11-08	80000	avance première tranche	00000314	espece		quatre vingt mille fcfa
315	67	2018-2019	2018-11-08	115000	solde première tranche	00000315	espece		CENT QUINZE MILLE FCFA
316	94	2018-2019	2018-11-08	110000	AVANCE 1ère tranche	00000316	espece		CENT DIX MILLE FRANC
318	46	2018-2019	2018-11-08	20000	solde première tranche	00000318	espece		vingt mille fcfa
319	117	2018-2019	2018-11-08	100000	avance deuxième tranche	00000319	espece		CENT MILLE
320	158	2018-2019	2018-11-08	40000	avance première tranche	00000320	espece		quarante mille fcfa
321	155	2018-2019	2018-11-08	5000	solde première tranche	00000321	espece		 cinq mille fcfa
322	94	2018-2019	2018-11-08	5000	1 ère tranche terminée	00000322	espece		cinq mille
323	153	2018-2019	2018-11-09	105000	 première tranche	00000323	espece		CENT cinq MILLE fcfa
324	129	2018-2019	2018-11-09	50000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000324	espece		CINQUANTE MILLE FCFA
325	71	2018-2019	2018-11-09	98000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000325	espece		quatre vingt dix huit  mille fcfa
326	54	2018-2019	2018-11-09	150000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000326	espece		CENT CINQUANTE  MILLE fcfa
327	172	2018-2019	2018-11-09	115000	 première tranche	00000327	espece		CENT QUINZE MILLE FCFA
328	96	2018-2019	2018-11-09	35000	avance première tranche	00000328	espece		trente cinq mille fcfa
329	70	2018-2019	2018-11-09	90000	avance première tranche	00000329	espece		quatre vingt dix mille fcfa
330	150	2018-2019	2018-11-09	85000	avance première tranche	00000330	espece		quatre vingt cinq  mille fcfa
331	15	2018-2019	2018-11-09	40000	avance première tranche	00000331	espece		quarante mille fcfa
332	120	2017-2018	2018-11-09	50000	Inscription	00000332	\N	\N	CINQUANTE MILLE FRANC
333	178	2017-2018	2018-11-09	50000	Inscription	00000333	\N	\N	CINQUANTE MILLE FRANC
334	179	2017-2018	2018-11-09	40000	Inscription	00000334	\N	\N	CINQUANTE MILLE FRANC
335	48	2017-2018	2018-11-09	40000	Inscription	00000335	\N	\N	QUARANTE MILLE FRANC
336	180	2017-2018	2018-11-09	40000	Inscription	00000336	\N	\N	QUARANTE MILLE FRANC
337	181	2017-2018	2018-11-09	40000	Inscription	00000337	\N	\N	QUARANTE MILLE FRANC
338	55	2017-2018	2018-11-09	40000	Inscription	00000338	\N	\N	QUARANTE MILLE FRANC
339	113	2017-2018	2018-11-09	40000	Inscription	00000339	\N	\N	QUARANTE MILLE FRANC
340	45	2017-2018	2018-11-09	40000	Inscription	00000340	\N	\N	QUARANTE MILLE FRANC
341	85	2017-2018	2018-11-09	40000	Inscription	00000341	\N	\N	QUARANTE MILLE FRANC
342	109	2017-2018	2018-11-09	40000	Inscription	00000342	\N	\N	QUARANTE MILLE FRANC
343	182	2017-2018	2018-11-09	40000	Inscription	00000343	\N	\N	QUARANTE MILLE FRANC
344	183	2017-2018	2018-11-10	40000	Inscription	00000344	\N	\N	QUARANTE MILLE FRANC
345	184	2017-2018	2018-11-10	40000	Inscription	00000345	\N	\N	QUARANTE MILLE FRANC
346	185	2017-2018	2018-11-10	40000	Inscription	00000346	\N	\N	QUARANTE MILLE FRANC
347	186	2017-2018	2018-11-10	40000	Inscription	00000347	\N	\N	QUARANTE MILLE FRANC
348	187	2018-2019	2018-11-10	40000	Inscription	00000348	\N	\N	quarante mille fcfa
349	187	2018-2019	2018-11-10	115000	 première tranche	00000349	espece		CENT QUINZE MILLE FCFA
350	188	2018-2019	2018-11-10	40000	Inscription	00000350	\N	\N	quarante mille fcfa
351	189	2018-2019	2018-11-10	40000	Inscription	00000351	\N	\N	quarante mille fcfa
352	189	2018-2019	2018-11-10	15000	avance première tranche	00000352	espece		 QUINZE MILLE FCFA
353	190	2018-2019	2018-11-10	40000	Inscription	00000353	\N	\N	quarante mille fcfa
354	191	2017-2018	2018-11-10	40000	Inscription	00000354	\N	\N	QUARANTE MILLE FRANC
355	190	2018-2019	2018-11-10	20000	avance première tranche	00000355	espece		vingt mille fcfa
356	102	2017-2018	2018-11-10	40000	Inscription	00000356	\N	\N	QUARANTE MILLE FRANC
357	137	2017-2018	2018-11-10	40000	Inscription	00000357	\N	\N	QUARANTE MILLE FRANC
358	95	2017-2018	2018-11-10	40000	Inscription	00000358	\N	\N	QUARANTE MILLE FRANC
359	192	2018-2019	2018-11-10	40000	Inscription	00000359	\N	\N	quarante mille fcfa
360	192	2018-2019	2018-11-10	35000	avance première tranche	00000360	espece		trente cinq mille fcfa
361	193	2017-2018	2018-11-10	40000	Inscription	00000361	\N	\N	QUARANTE MILLE FRANC
362	162	2017-2018	2018-11-10	40000	Inscription	00000362	\N	\N	QUARANTE MILLE FRANC
363	172	2017-2018	2018-11-10	40000	Inscription	00000363	\N	\N	QUARANTE MILLE FRANC
364	143	2017-2018	2018-11-10	40000	Inscription	00000364	\N	\N	QUARANTE MILLE FRANC
365	110	2017-2018	2018-11-10	40000	Inscription	00000365	\N	\N	QUARANTE MILLE FRANC
366	194	2017-2018	2018-11-10	40000	Inscription	00000366	\N	\N	QUARANTE MILLE FRANC
367	98	2017-2018	2018-11-10	40000	Inscription	00000367	\N	\N	QUARANTE MILLE FRANC
368	109	2018-2019	2018-11-10	105000	 première tranche	00000368	espece		CENT cinq MILLE fcfa
369	85	2018-2019	2018-11-10	80000	 DEUXIÈME TRANCHE	00000369	espece		quatre vingt mille fcfa
370	195	2017-2018	2018-11-10	40000	Inscription	00000370	\N	\N	QUARANTE MILLE FRANC
371	164	2017-2018	2018-11-10	50000	Inscription	00000371	\N	\N	QUARANTE MILLE FRANC
372	101	2017-2018	2018-11-10	40000	Inscription	00000372	\N	\N	QUARANTE MILLE FRANC
373	117	2017-2018	2018-11-10	40000	Inscription	00000373	\N	\N	QUARANTE MILLE FRANC
374	51	2018-2019	2018-11-10	100000	avance première tranche	00000374	espece		CENT MILLE fcfa
375	127	2018-2019	2018-11-10	50000	avance première tranche	00000375	espece		CINQUANTE  MILLE FCFA
376	91	2018-2019	2018-11-10	100000	avance première tranche	00000376	espece		CENT MILLE fcfa
377	22	2018-2019	2018-11-10	5000	solde première tranche	00000377	espece		 cinq MILLE fcfa
378	7	2018-2019	2018-11-10	150000	solde première tranche	00000378	espece		CENT CINQUANTE  MILLE FCFA
379	100	2018-2019	2018-11-10	30000	avance première tranche	00000379	espece		trente mille fcfa
380	151	2018-2019	2018-11-10	35000	avance première tranche	00000380	espece		trente cinq mille fcfa
381	196	2018-2019	2018-11-10	40000	Inscription	00000381	\N	\N	quarante mille fcfa
382	197	2018-2019	2018-11-10	50000	Inscription	00000382	\N	\N	CINQUANTE MILLE FCFA 
383	198	2018-2019	2018-11-10	100000	Inscription	00000383	\N	\N	CENT MILLE fcfa
384	106	2018-2019	2018-11-12	100000	avance première tranche	00000384	espece		CENT MILLE fcfa
385	106	2018-2019	2018-11-12	10000	avance première tranche	00000385	espece		dix mille fcfa
386	199	2017-2018	2018-11-12	40000	Inscription	00000386	\N	\N	QUARANTE MILLE FRANC
387	21	2018-2019	2018-11-12	215000	solde première tranche + AVANCE DEUXIÈME TRANCHE+ avance troisieme tranche 	00000387	espece		deux cent quinze mille fcfa
388	88	2018-2019	2018-11-12	10000	solde première tranche	00000388	espece		dix mille fcfa
389	115	2018-2019	2018-11-12	115000	 première tranche	00000389	espece		CENT QUINZE MILLE FCFA
390	103	2018-2019	2018-11-12	115000	 première tranche	00000390	espece		CENT QUINZE MILLE FCFA
391	100	2018-2019	2018-11-12	75000	solde première tranche	00000391	espece		soixante quinze mille fcfa
392	136	2018-2019	2018-11-12	90000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000392	espece		quatre vingt dix mille
393	106	2018-2019	2018-11-12	5000	solde première tranche	00000393	espece		cinq mille fcfa 
394	40	2018-2019	2018-11-12	115000	solde première tranche	00000394	espece		CENT QUINZE MILLE FCFA
395	116	2018-2019	2018-11-12	40000	 première tranche	00000395	espece		quarante mille fcfa
396	171	2018-2019	2018-11-12	20000	avance première tranche	00000396	espece		vingt mille fcfa
397	110	2018-2019	2018-11-12	50000	avance première tranche	00000397	espece		CENT CINQUANTE  MILLE fcfa
398	83	2018-2019	2018-11-12	55000	solde première tranche	00000398	espece		CINQUANTE cinq  MILLE FCFA
399	52	2018-2019	2018-11-12	105000	solde première tranche	00000399	espece		CENT cinq MILLE fcfa
400	180	2018-2019	2018-11-12	40000	Inscription	00000400	\N	\N	quarante mille fcfa
401	180	2018-2019	2018-11-12	230000	 première tranche + DEUXIÈME TRANCHE	00000401	espece		deux cent trente mille fcfa 
402	38	2018-2019	2018-11-12	65000	solde première tranche	00000402	espece		soixante cinq  mille fcfa
403	133	2018-2019	2018-11-12	30000	avance première tranche	00000403	espece		trente mille fcfa
404	200	2018-2019	2018-11-12	40000	Inscription	00000404	\N	\N	quarante mille fcfa
405	200	2018-2019	2018-11-12	105000	 première tranche	00000405	espece		CENT cinq MILLE fcfa
406	201	2018-2019	2018-11-12	40000	Inscription	00000406	\N	\N	quarante mille fcfa
407	201	2018-2019	2018-11-12	25000	avance première tranche	00000407	espece		vingtCINQ  mille fcfa
408	121	2018-2019	2018-11-12	120000	solde 	00000408	espece		CENT VINGT MILLE FCFA
409	60	2018-2019	2018-11-12	100000	avance première tranche	00000409	\N	\N	\N
410	202	2018-2019	2018-11-12	40000	Inscription	00000410	\N	\N	quarante mille fcfa
411	202	2018-2019	2018-11-12	105000	avance première tranche	00000411	\N	\N	\N
412	203	2018-2019	2018-11-12	40000	Inscription	00000412	\N	\N	quarante mille fcfa
413	203	2018-2019	2018-11-12	105000	 première tranche	00000413	\N	\N	\N
414	114	2018-2019	2018-11-12	115000	 première tranche	00000414	\N	\N	\N
415	204	2018-2019	2018-11-12	40000	Inscription	00000415	\N	\N	quarante mille fcfa
416	29	2018-2019	2018-11-12	35000	solde première tranche	00000416	\N	\N	\N
417	72	2018-2019	2018-11-12	40000	solde première tranche	00000417	\N	\N	\N
418	204	2018-2019	2018-11-12	50000	avance première tranche	00000418	\N	\N	\N
419	78	2018-2019	2018-11-12	5000	solde première tranche	00000419	\N	\N	\N
434	187	2017-2018	2018-11-13	40000	Inscription	00000434	\N	\N	QUARANTE MILLE FRANC
435	127	2017-2018	2018-11-13	40000	Inscription	00000435	\N	\N	QUARANTE MILLE FRANC
436	209	2017-2018	2018-11-13	40000	Inscription	00000436	\N	\N	QUARANTE MILLE FRANC
437	115	2017-2018	2018-11-13	40000	Inscription	00000437	\N	\N	QUARANTE MILLE FRANC
427	147	2018-2019	2018-11-13	5000	solde première tranche	00000427	\N	\N	\N
426	65	2018-2019	2018-11-13	105000	 première tranche	00000426	\N	\N	\N
425	208	2018-2019	2018-11-13	40000	Inscription	00000425	\N	\N	quarante mille fcfa
424	207	2018-2019	2018-11-13	60000	avance première tranche	00000424	\N	\N	\N
423	207	2018-2019	2018-11-13	40000	Inscription	00000423	\N	\N	quarante mille fcfa
422	205	2018-2019	2018-11-13	50000	Inscription	00000422	\N	\N	CINQUANTE  MILLE FCFA
421	206	2018-2019	2018-11-13	105000	 première tranche	00000421	\N	\N	\N
420	206	2018-2019	2018-11-13	40000	Inscription	00000420	\N	\N	quarante mille fcfa
428	84	2018-2019	2018-11-13	25000	PREMIÈRE TRANCHE	00000428	\N	\N	\N
429	150	2018-2019	2018-11-13	15000	PREMIÈRE TRANCHE	00000429	\N	\N	\N
430	50	2017-2018	2018-11-13	40000	Inscription	00000430	\N	\N	QUARANTE MILLE FRANC
431	103	2017-2018	2018-11-13	40000	Inscription	00000431	\N	\N	QUARANTE MILLE FRANC
432	106	2017-2018	2018-11-13	40000	Inscription	00000432	\N	\N	QUARANTE MILLE FRANC
433	99	2017-2018	2018-11-13	40000	Inscription	00000433	\N	\N	QUARANTE MILLE FRANC
438	210	2017-2018	2018-11-13	40000	Inscription	00000438	\N	\N	QUARANTE MILLE FRANC
439	211	2018-2019	2018-11-13	40000	Inscription	00000439	\N	\N	quarante mille fcfa
440	211	2018-2019	2018-11-13	105000	 première tranche	00000440	\N	\N	\N
441	102	2018-2019	2018-11-13	80000	avance première tranche	00000441	\N	\N	\N
442	162	2018-2019	2018-11-13	20000	avance première tranche	00000442	\N	\N	\N
443	212	2018-2019	2018-11-14	40000	Inscription	00000443	\N	\N	quarante mille fcfa
444	212	2018-2019	2018-11-14	90000	avance première tranche	00000444	\N	\N	\N
445	131	2018-2019	2018-11-14	140000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000445	\N	\N	\N
446	213	2018-2019	2018-11-14	40000	Inscription	00000446	\N	\N	quarante mille fcfa
447	213	2018-2019	2018-11-14	60000	avance première tranche	00000447	\N	\N	\N
448	188	2018-2019	2018-11-14	105000	 première tranche	00000448	\N	\N	\N
449	2	2018-2019	2018-11-15	40000	Inscription	00000449	\N	\N	quarante mille FCFA
450	2	2018-2019	2018-11-15	105000	PREMIERE TRANCHE	00000450	\N	\N	\N
451	194	2017-2018	2018-11-15	40000	Inscription	00000451	\N	\N	quarante mille FCFA
452	144	2017-2018	2018-11-15	40000	Inscription	00000452	\N	\N	quarante mille FCFA
453	202	2017-2018	2018-11-15	40000	Inscription	00000453	\N	\N	quarante mille FCFA
454	119	2017-2018	2018-11-15	40000	Inscription	00000454	\N	\N	quarante mille FCFA
455	94	2017-2018	2018-11-15	40000	Inscription	00000455	\N	\N	quarante mille FCFA
456	214	2017-2018	2018-11-15	40000	Inscription	00000456	\N	\N	quarante mille FCFA
457	30	2018-2019	2018-11-15	185000	solde PENSION 	00000457	\N	\N	\N
458	129	2017-2018	2018-11-16	40000	Inscription	00000458	\N	\N	quarante mille FCFA
459	133	2017-2018	2018-11-16	40000	Inscription	00000459	\N	\N	quarante mille FCFA
460	158	2017-2018	2018-11-16	40000	Inscription	00000460	\N	\N	quarante mille FCFA
461	131	2017-2018	2018-11-16	40000	Inscription	00000461	\N	\N	quarante mille FCFA
462	114	2017-2018	2018-11-16	40000	Inscription	00000462	\N	\N	quarante mille FCFA
463	216	2017-2018	2018-11-16	40000	Inscription	00000463	\N	\N	quarante mille FCFA
464	217	2017-2018	2018-11-16	40000	Inscription	00000464	\N	\N	quarante mille FCFA
465	96	2018-2019	2018-11-16	15000	solde première tranche	00000465	\N	\N	\N
466	45	2018-2019	2018-11-19	55000	solde première tranche	00000466	\N	\N	\N
467	139	2018-2019	2018-11-19	105000	 première tranche	00000467	\N	\N	\N
468	176	2018-2019	2018-11-19	15000	avance première tranche	00000468	\N	\N	\N
469	34	2018-2019	2018-11-19	120000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000469	\N	\N	\N
470	219	2018-2019	2018-11-19	40000	Inscription	00000470	\N	\N	quarante mille fcfa
471	219	2018-2019	2018-11-19	30000	avance première tranche	00000471	\N	\N	\N
474	92	2018-2019	2018-11-19	115000	solde première tranche	00000474	\N	\N	\N
475	15	2018-2019	2018-11-19	100000	avance première tranche	00000475	\N	\N	\N
476	165	2017-2018	2018-11-19	40000	Inscription	00000476	\N	\N	quarante mille FCFA
477	125	2017-2018	2018-11-19	40000	Inscription	00000477	\N	\N	quarante mille FCFA
478	76	2017-2018	2018-11-19	40000	Inscription	00000478	\N	\N	quarante mille FCFA
479	174	2017-2018	2018-11-19	40000	Inscription	00000479	\N	\N	quarante mille FCFA
480	138	2017-2018	2018-11-19	40000	Inscription	00000480	\N	\N	quarante mille FCFA
481	149	2017-2018	2018-11-19	40000	Inscription	00000481	\N	\N	quarante mille FCFA
482	139	2017-2018	2018-11-19	40000	Inscription	00000482	\N	\N	quarante mille FCFA
483	200	2017-2018	2018-11-19	40000	Inscription	00000483	\N	\N	quarante mille FCFA
484	221	2017-2018	2018-11-19	40000	Inscription	00000484	\N	\N	quarante mille FCFA
485	222	2017-2018	2018-11-19	40000	Inscription	00000485	\N	\N	quarante mille FCFA
486	223	2017-2018	2018-11-19	40000	Inscription	00000486	\N	\N	quarante mille FCFA
487	224	2017-2018	2018-11-19	40000	Inscription	00000487	\N	\N	quarante mille FCFA
488	225	2018-2019	2018-11-19	40000	Inscription	00000488	\N	\N	quarante mille fcfa
489	225	2018-2019	2018-11-19	10000	avance première tranche	00000489	\N	\N	\N
490	27	2018-2019	2018-11-19	50000	avance deuxième tranche	00000490	\N	\N	\N
491	123	2018-2019	2018-11-19	50000	avance première tranche	00000491	\N	\N	\N
492	218	2018-2019	2018-11-20	40000	Inscription	00000492	\N	\N	quarante mille fcfa
493	226	2018-2019	2018-11-20	50000	Inscription	00000493	\N	\N	CINQUANTE MILLE FCFA 
494	227	2018-2019	2018-11-20	50000	Inscription	00000494	\N	\N	CINQUANTE  MILLE FCFA
495	66	2018-2019	2018-11-20	40000	avance première tranche	00000495	\N	\N	\N
472	214	2018-2019	2018-11-19	40000	Inscription	00000472	\N	\N	quarante mille fcfa
473	214	2018-2019	2018-11-19	105000	avance première tranche	00000473	\N	\N	\N
496	126	2018-2019	2018-11-21	40000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000496	\N	\N	\N
497	152	2018-2019	2018-11-21	50000	avance première tranche	00000497	\N	\N	\N
498	110	2018-2019	2018-11-21	65000	solde première tranche	00000498	\N	\N	\N
499	95	2018-2019	2018-11-21	10000	avance première tranche	00000499	\N	\N	\N
500	189	2018-2019	2018-11-21	60000	avance première tranche	00000500	\N	\N	\N
501	11	2018-2019	2018-11-21	105000	solde première tranche + AVANCE DEUXIÈME TRANCHE	00000501	\N	\N	\N
502	44	2018-2019	2018-11-21	5000	solde première tranche	00000502	\N	\N	\N
\.


--
-- Name: payement_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('payement_id_seq', 502, true);


--
-- Data for Name: payement_salaire; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY payement_salaire (id, id_personnel, id_user, montant, date_p, numero, mois, heure_realise, primes, acomptes, cnss, cas_sociales, assurances, autre_retenues, taux, total, total_retenu, net_a_payer, annee_scolaire) FROM stdin;
\.


--
-- Name: payement_salaire_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('payement_salaire_id_seq', 1, false);


--
-- Data for Name: personnel; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY personnel (id, email, nom, date_naiss, lieu_naiss, nationalite, diplome, sexe, date_entree, tel1, tel2, adresse, type, matieres, autre_information, matricule, num_contribuable, num_ss, categorie, echelon, mode_paye, ref_versement) FROM stdin;
1		makam louis hervé	1995-03-01	douala	CAMEROUNAIS	bts	H	2018-10-03	697618828		ngousso	0			18IS-HINTELP001					\N	\N
2		pascal tchana	2015-01-11	yaoundé	Camerounaise	master	H	2018-10-03	266589696541		douala	0			18IS-HINTELP002		b252555	bd		\N	\N
3	Ktchimoes@yahoo.com	TCHIMOE KEMLE Symphorien	1994-06-02	BANGOU	CAMEROUNAIS	MASTER II	H	2018-10-04	691588937		YAOUNDE NGOUSSO (fin cimetiere)	0	transversales		18IS-HINTELP003	/	/	/		\N	\N
4	bitoutouga@yahoo.fr	ETOUNDI Bonaventure	1956-05-30	YAOUNDE	CAMEROUNAIS	Ingenieur Technologies et Ingenieur Agroalimentaire	H	2018-10-04	674500088	696353972	yaounde	0	Transversales		18IS-HINTELP004	/	/	Enseignant Vacataire	/	\N	\N
5	/	ESSOMBA EYENGA Joseph	1983-08-12	NTSAN/OBALA	CAMEROUNAIS	MASTER Professionnel en Demographie	H	2018-10-04	699479417	677544705	yaounde	0	Transversales		18IS-HINTELP005	/	/	Statisticien/Demographe	/	\N	\N
6	teufackjules@gmail.com	TEUFACK TEUSSO Jules Calvin	1977-10-20	YAOUNDE	CAMEROUNAIS	DIPET II	H	2018-10-04	94664733	677646058	yaounde	0	Industrie et Technologie		18IS-HINTELP006	/	/	/	/	\N	\N
7	franklinsambou@yahoo.fr	SAMBOU KANYOU Franklin	1980-02-02	BAZOU	CAMEROUNAIS	DIPESII/GEOGRAPHIE	H	2018-10-04	677031562	697984352	BP 994 YAOUNDE	0	Communication		18IS-HINTELP007	/	/		/	\N	\N
8	douala@gmail.com	DOUALLA NYAMBI Madeleine	1983-03-15	YAOUNDE	CAMEROUNAISE	BAC+1	F	2018-10-04	699107130	676773866	YAOUNDE -EMANA	1			18IS-HINTELP008	/	/	/	/	\N	\N
9	ongonobrice@gmail.com	ONGONO OTABELA Brice Gaetan	1993-04-12	douala	CAMEROUNAIS	Ingenieur de Conception en Genie Electrique	H	2018-10-04	697701756	/	yaounde	0	Industrie et Technologie		18IS-HINTELP009	/	/		/	\N	\N
10	Yannickpondi@gmail.com	PONDI Joseph	1990-05-15	MAKAK	CAMEROUNAIS	Ingenieur de Conception en Genie Mecanique	H	2018-10-04	693343634	676091319	yaounde	0	Industrie et Technologie		18IS-HINTELP010	/	/	/	/	\N	\N
11	Jimgangfabrice@gmail.com	JIMGANG SIBICHO Fabrice	1987-05-09	BANKA	CAMEROUNAIS	maitrise en comptabilite et finance	H	2018-10-04	674370569	698974138	yaounde	0	Commercial et Gestion		18IS-HINTELP011	/	/	/	/	\N	\N
12	/	FOUMANE ZE Collins Roger	1970-12-12	YAOUNDE	CAMEROUNAISE	DIPES II GRADE	H	2018-10-04	651364622	/	yaounde	0	Tansversales		18IS-HINTELP012	/	/	A2	/	\N	\N
13	tchendjou1972@yahoo.fr	TCHENDJOU Leon Maxime	1972-09-26	Mbalmayo	CAMEROUNAISE	DEPA GRH/DESS FISCALITE	H	2018-10-04	699222806	677340879	yaounde	0	Commercial et Gestion		18IS-HINTELP013	/	/		/	\N	\N
14	christ.njuiko@gmail.com	NJUIKO TCHO Christelle epse MBIANGANG	1987-11-06	YAOUNDE	CAMEROUNAISE	Licence professionnelle IG	H	2018-10-04	674559604	694559604	FOUGEROLLE	1			18IS-HINTELP014	/	/	/	/	\N	\N
15	pierrepaulowona@yahoo.fr	OWONA Pierre-Paul	1987-07-19	NKONGNEN I 	CAMEROUNAISE	Licence Professionnelle en Tourisme option DATT	H	2018-10-04	697904460	/	yaounde	0	Hotellerie et Restauration		18IS-HINTELP015	/	/	/	/	\N	\N
16	mballamargueritesolange@yahoo.fr	MBALLA Marguerite Solange	1977-04-14	YAOUNDE	CAMEROUNAISE	MASTER II	F	2018-10-04	699392211	681599741	yaounde	0	Communication		18IS-HINTELP016	/	/	/	/	\N	\N
17	/	LEUTCHA POUAMOU Raphael	1980-10-18	Bafoussam	CAMEROUNAISE	Comptabilite	H	2018-10-04	699702098		yaounde	0	Commercial et Gestion		18IS-HINTELP017	/	/	permanent	/	\N	\N
18	/	TCHOUTEZO LECPA Severin	1978-11-09	YAOUNDE	CAMEROUNAISE	PLET	H	2018-10-04	677531433	697339297	yaounde	0			18IS-HINTELP018	/	/	/	/	\N	\N
19	megne.arlette@yahoo.fr	MEGNE OUAFO Arlette Lors	1983-01-04	YAOUNDE	CAMEROUNAISE	MASTER	F	2018-10-04	675788698	699047864	Yaounde-ODZA	0	Transversale		18IS-HINTELP019	326-104337 12	/	/	/	\N	\N
20	snmaffo@gmail.com	MAFFO Emmily Sylviane	1988-12-31	BANDJOUN	CAMEROUNAISE	MASTER II	H	2018-10-04	655711614		Yaounde-MESSASSI	0	Transversales		18IS-HINTELP020	/	/	/	/	\N	\N
21	leharasgerard@gmail.com	AMASSOGA  Gerard Charly	1981-10-03	YAOUNDE	CAMEROUNAIS	BTS	H	2018-10-04	691951719	674309698	NKOABANG	0	Hotellerie et Restauration		18IS-HINTELP021	/	/	/	/	\N	\N
22	mariebangoup@yahoo.fr	BANGOUP   TABOU Pulcherie angeline Marie	1982-10-12	BELABO	CAMEROUNAISE	MASTER II/Communication des Organisations	H	2018-10-04	699354245		yaounde	0	Communication		18IS-HINTELP022	/	/	/	/	\N	\N
23	Wadouf@gmail.com	WADOUFEY Abbel	1991-08-13	NGONG	CAMEROUNAISE	MASTER II en Informatique	H	2018-10-04	696472400	650125212	ngousso	0	Commercial et Gestion		18IS-HINTELP023	/	/	/	/	\N	\N
24	/	ETOUNDI ELOUNDI Victor Christian	1977-10-14	OTELE	CAMEROUNAISE	PLEG/MASTER II en science de l information et de la communication	H	2018-10-04	699166675	/	yaounde	0	Transversales		18IS-HINTELP024	/	/	enseignant	/	\N	\N
25	dodokeub@gmail.com	KEUBENG Dominique	1977-08-27	MBOUDA	CAMEROUNAISE	DIPET 2	H	2018-09-16	677384864	698956958	BP 2959 SOA	0	Industrie et Technologie		18IS-HINTELP025	/	/	/	/	\N	\N
26	talla.guybernard@yahoo.fr	TALLA Guy Bernard	1988-10-20	YAOUNDE	CAMEROUNAISE	MASTER II professionel en Banque et  Finance DGC en comptabilite de l' INTEC de Paris (FRANCE)	H	2018-10-04	697546972	673302181	Omnisport-yaounde	0	Commercial et Gestion		18IS-HINTELP026	/	/	/	/	\N	\N
27	noel.meke@gmail.com	MEKE Noel Romuald	1986-12-25	AKONOLINGA	CAMEROUNAISE	D-E-A	H	2018-10-04	696000617	690184854	BP 14 747 YAOUNDE	0	Capacite en Droit et Economie		18IS-HINTELP027	/	/	/	/	\N	\N
29	ndangapierre@yahoo.fr	NDANGA ABESSOLO Pierre Severin 	1980-03-14	NKOLYA	CAMEROUNAISE	DIPET II Genie Civil option Batiment et Travaux	H	2018-10-05	675559953	696990247	yaounde	0	Industrie et Technologie		18IS-HINTELP029	/	/	/	/	\N	\N
28	ndangapierre@yahoo.fr	NDANGA ABESSOLO Pierre Severin 	1980-03-14	NKOLYA	CAMEROUNAISE	DIPET II Genie Civil option Batiment et Travaux	H	2018-10-05	675559953	696990247	yaounde	0	Industrie et Technologie		18IS-HINTELP028	/	/	/	/	\N	\N
30	kouamguysylvain@gmail.com	KOUAM TCHUNEKOUO Guy Sylvain	1983-07-30	BAHAM	CAMEROUNAISE	DIPET II	H	2018-10-05	675840236	695479800	Tropicana Mvan	0	Industrie et Technologie		18IS-HINTELP030	/	/	A2	echelon 2/2	\N	\N
31	kouamguysylvain@gmail.com	KOUAM TCHUNEKOUO Guy Sylvain	1983-07-30	BAHAM	CAMEROUNAISE	DIPET II	H	2018-10-05	675840236	695479800	Tropicana Mvan	0	Industrie et Technologie		18IS-HINTELP031	/	/	A2	echelon 2/2	\N	\N
32	kouamguysylvain@gmail.com	KOUAM TCHUNEKOUO Guy Sylvain	1983-07-30	BAHAM	CAMEROUNAISE	DIPET II	H	2018-10-05	675840236	695479800	Tropicana Mvan	0	Industrie et Technologie		18IS-HINTELP032	/	/	A2	echelon 2/2	\N	\N
33	kouamguysylvain@gmail.com	KOUAM TCHUNEKOUO Guy Sylvain	1983-07-30	BAHAM	CAMEROUNAISE	DIPET II	H	2018-10-05	675840236	695479800	Tropicana Mvan	0	Industrie et Technologie		18IS-HINTELP033	/	/	A2	echelon 2/2	\N	\N
\.


--
-- Name: personnel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('personnel_id_seq', 33, true);


--
-- Data for Name: photos; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY photos (id, eleve_id, annee_scolaire, url, created_at, updated_at) FROM stdin;
\.


--
-- Name: photos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('photos_id_seq', 1, false);


--
-- Data for Name: salaire; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY salaire (id, id_personnel, type, montant) FROM stdin;
1	5	0	40000
2	27	0	12000
\.


--
-- Name: salaire_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('salaire_id_seq', 2, true);


--
-- Data for Name: salles; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY salles (id, nom, created_at, updated_at) FROM stdin;
\.


--
-- Name: salles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('salles_id_seq', 1, false);


--
-- Data for Name: scolarite; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY scolarite (id, id_cycle, tranche1, tranche2, tranche3, tranche4, tranche5, total, id_niveau, inscription) FROM stdin;
3	1	115000	115000	115000	0	0	345000	5	40000
4	1	150000	150000	100000	0	0	400000	4	40000
5	1	115000	115000	115000	0	0	345000	6	40000
6	1	115000	115000	115000	0	0	345000	7	40000
7	1	115000	115000	115000	0	0	345000	8	40000
8	1	105000	80000	80000	0	0	265000	13	40000
9	1	105000	80000	80000	0	0	265000	1	40000
10	1	105000	80000	80000	0	0	265000	9	40000
11	1	105000	80000	80000	0	0	265000	10	40000
12	1	105000	80000	80000	0	0	265000	11	40000
13	3	40000	40000	40000	0	0	120000	1	40000
14	3	40000	40000	40000	0	0	120000	14	40000
15	1	115000	115000	115000	0	0	345000	15	40000
\.


--
-- Name: scolarite_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('scolarite_id_seq', 15, true);


--
-- Data for Name: taux; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY taux (id, id_personnel, id_matiere, id_classe, id_nniveau, taux) FROM stdin;
1	4	0	0	0	2000
2	24	0	0	0	2000
3	15	0	0	0	2000
4	21	0	0	0	2000
5	6	0	0	0	2000
6	25	0	0	0	2000
7	5	0	0	0	2000
8	27	0	0	0	2000
\.


--
-- Name: taux_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('taux_id_seq', 8, true);


--
-- Data for Name: titulaire; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY titulaire (id, id_personnel, id_classe, annee_scolaire) FROM stdin;
\.


--
-- Name: titulaire_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('titulaire_id_seq', 1, false);


--
-- Data for Name: trimestre; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY trimestre (id, nom, seq1, seq2) FROM stdin;
\.


--
-- Name: trimestre_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('trimestre_id_seq', 1, false);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY users (id, name, fullname, email, password, remember_token, role, active, id_personnel, created_at, updated_at) FROM stdin;
1	tamanois	tamanois	\N	$2y$10$SswXQVoERqPmUHzdMGlky.o7nTw1ySt3J4ZyHBi8nd/mAdUX2rt0q	\N	0	1	0	2018-09-13 18:54:11	2018-09-13 18:54:11
7	mukam	mukam louis hervé	\N	$2y$10$inEFwUGIkvA0.co9sSYVy.COpnysocFLn78dBbLxj2NY0/TXR1Yh6	o69RjwBDVyhCeTxpe0N5LdYpUefS8LQYnsw34ktKHf6sBtK6dkIB4OHm73l6	0	1	0	2018-11-09 13:18:51	2018-11-21 08:29:15
3	larissa	larissa tchuente	\N	$2y$10$3ANVc8Elj2dyeEFOzloVcuk4MigaUEzwwzWuCnAJldx13Z2BXxivm	o6djgVroEV9kvbcGJyrq0qPXN01N3AeuU628uIeViHHXnuCfiIrJJKLnfZhe	2	0	0	2018-10-05 08:38:00	2018-11-13 12:15:32
8	NJUIKO	NJUIKO TCHO CHRISTELLE	\N	$2y$10$Y2J9Gx0ObAPTWwdDzFzXTu2O55N4kVAmQ9fS2J.8E8DcE4zz0dRfK	XenG6MFUuj4cjZXstaKzqS5LonKYJwwTQ3QpnZZFTC9QG21qtBb2kP7BemaI	18.5	1	14	2018-11-13 12:33:34	2018-11-13 12:36:31
2	pascal	tchana pascal	\N	$2y$10$0z.TZKAdLmz8LtaRPqvMHOHVFaE4u6O7Fqmyp6aB1ziDpybiy94iC	MGNL68n1MoA7Z5HlAfJWqoaJBtzd2qqUUkXvv6mFssiDNdJbqAGYQCP1R6wf	1	1	0	2018-09-13 18:54:11	2018-11-13 14:58:53
6	Roni	Mohab 	\N	$2y$10$lWbmcqVEOKjSuKdL2q35RuC5/TMC1L5LbcMJZiUyKggJ/zcDv3.r2	chwioQNbVzB2VlD5g3xpSwI8G5Fx5fvyVjNOQ2sRTEhqq3Q9dqNHMkPiWaPy	2	1	0	2018-11-09 13:12:32	2018-11-09 13:15:06
4	christelle	njuiko tcho christelle 	\N	$2y$10$i03s95a.KGtYA/C/Bgl.H.W7CtTS4DUU86sC.kL38KOeMhqnk1llW	XJq1jPyJVOrjnOKGRBU6Qjyfz8phGJ61Vnz4LJSbLFUj2xle2CDKIZgE5SM3	2	1	0	2018-11-08 09:04:00	2018-11-19 15:17:21
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('users_id_seq', 8, true);


--
-- Data for Name: verrou_note; Type: TABLE DATA; Schema: public; Owner: schoolconcept
--

COPY verrou_note (id, sequence, annee_scolaire) FROM stdin;
\.


--
-- Name: verrou_note_id_seq; Type: SEQUENCE SET; Schema: public; Owner: schoolconcept
--

SELECT pg_catalog.setval('verrou_note_id_seq', 2, true);


--
-- Name: bulletin_genere_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY bulletin_genere
    ADD CONSTRAINT bulletin_genere_pkey PRIMARY KEY (id);


--
-- Name: classe_matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY classe_matiere
    ADD CONSTRAINT classe_matiere_pkey PRIMARY KEY (id);


--
-- Name: classe_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY classe
    ADD CONSTRAINT classe_pkey PRIMARY KEY (id);


--
-- Name: codages_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY codages
    ADD CONSTRAINT codages_pkey PRIMARY KEY (id);


--
-- Name: cycle_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY cycle
    ADD CONSTRAINT cycle_pkey PRIMARY KEY (id);


--
-- Name: decaissement_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY decaissement
    ADD CONSTRAINT decaissement_pkey PRIMARY KEY (id);


--
-- Name: discipline_personnel_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY discipline_personnel
    ADD CONSTRAINT discipline_personnel_pkey PRIMARY KEY (id);


--
-- Name: discipline_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY discipline
    ADD CONSTRAINT discipline_pkey PRIMARY KEY (id);


--
-- Name: dispense_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY dispense
    ADD CONSTRAINT dispense_pkey PRIMARY KEY (id);


--
-- Name: eleve_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY eleve
    ADD CONSTRAINT eleve_pkey PRIMARY KEY (id);


--
-- Name: fonction_personnel_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY fonction_personnel
    ADD CONSTRAINT fonction_personnel_pkey PRIMARY KEY (id);


--
-- Name: fonction_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY fonction
    ADD CONSTRAINT fonction_pkey PRIMARY KEY (id);


--
-- Name: groupe_matieres_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY groupe_matieres
    ADD CONSTRAINT groupe_matieres_pkey PRIMARY KEY (id);


--
-- Name: heure_realise_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY heure_realise
    ADD CONSTRAINT heure_realise_pkey PRIMARY KEY (id);


--
-- Name: heures_prevues_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY heures_prevues
    ADD CONSTRAINT heures_prevues_pkey PRIMARY KEY (id);


--
-- Name: horaire_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY horaire
    ADD CONSTRAINT horaire_pkey PRIMARY KEY (id);


--
-- Name: inscription_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY inscription
    ADD CONSTRAINT inscription_pkey PRIMARY KEY (id);


--
-- Name: matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY matiere
    ADD CONSTRAINT matiere_pkey PRIMARY KEY (id);


--
-- Name: moyenne_classe_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_classe
    ADD CONSTRAINT moyenne_classe_pkey PRIMARY KEY (id);


--
-- Name: moyenne_eleve_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_eleve
    ADD CONSTRAINT moyenne_eleve_pkey PRIMARY KEY (id);


--
-- Name: moyenne_matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY moyenne_matiere
    ADD CONSTRAINT moyenne_matiere_pkey PRIMARY KEY (id);


--
-- Name: n_niveaus_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY n_niveaus
    ADD CONSTRAINT n_niveaus_pkey PRIMARY KEY (id);


--
-- Name: niveau_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY niveau
    ADD CONSTRAINT niveau_pkey PRIMARY KEY (id);


--
-- Name: note_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY note
    ADD CONSTRAINT note_pkey PRIMARY KEY (id);


--
-- Name: parametres_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY parametres
    ADD CONSTRAINT parametres_pkey PRIMARY KEY (id);


--
-- Name: parent_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY parent
    ADD CONSTRAINT parent_pkey PRIMARY KEY (id);


--
-- Name: payement_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY payement
    ADD CONSTRAINT payement_pkey PRIMARY KEY (id);


--
-- Name: payement_salaire_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY payement_salaire
    ADD CONSTRAINT payement_salaire_pkey PRIMARY KEY (id);


--
-- Name: personnel_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY personnel
    ADD CONSTRAINT personnel_pkey PRIMARY KEY (id);


--
-- Name: photos_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY photos
    ADD CONSTRAINT photos_pkey PRIMARY KEY (id);


--
-- Name: salaire_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY salaire
    ADD CONSTRAINT salaire_pkey PRIMARY KEY (id);


--
-- Name: salles_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY salles
    ADD CONSTRAINT salles_pkey PRIMARY KEY (id);


--
-- Name: scolarite_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY scolarite
    ADD CONSTRAINT scolarite_pkey PRIMARY KEY (id);


--
-- Name: taux_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY taux
    ADD CONSTRAINT taux_pkey PRIMARY KEY (id);


--
-- Name: titulaire_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY titulaire
    ADD CONSTRAINT titulaire_pkey PRIMARY KEY (id);


--
-- Name: trimestre_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY trimestre
    ADD CONSTRAINT trimestre_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: verrou_note_pkey; Type: CONSTRAINT; Schema: public; Owner: schoolconcept
--

ALTER TABLE ONLY verrou_note
    ADD CONSTRAINT verrou_note_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: schoolconcept
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM schoolconcept;
GRANT ALL ON SCHEMA public TO schoolconcept;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

