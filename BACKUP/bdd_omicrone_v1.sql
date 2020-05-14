--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 12.2

-- Started on 2020-04-16 23:47:55

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 229 (class 1255 OID 49410)
-- Name: truncate_tables(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.truncate_tables(_username text) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
   RAISE NOTICE '%', 
   -- EXECUTE  -- dangerous, test before you execute!
  (SELECT 'TRUNCATE TABLE '
       || string_agg(quote_ident(schemaname) || '.' || quote_ident(tablename), ', ')
       || ' CASCADE'
   FROM   pg_tables
   WHERE  tableowner = _username
   AND    schemaname = 'public'
   );
END
$$;


ALTER FUNCTION public.truncate_tables(_username text) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 202 (class 1259 OID 49411)
-- Name: client; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.client (
    id integer NOT NULL,
    idcontact integer NOT NULL,
    raisonsocial character varying(32),
    siret character varying(14),
    adr character(32),
    ville character(32),
    codepostal integer,
    cacher boolean DEFAULT false
);


ALTER TABLE public.client OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 49415)
-- Name: client_idclient_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.client_idclient_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.client_idclient_seq OWNER TO postgres;

--
-- TOC entry 2994 (class 0 OID 0)
-- Dependencies: 203
-- Name: client_idclient_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.client_idclient_seq OWNED BY public.client.id;


--
-- TOC entry 204 (class 1259 OID 49417)
-- Name: commercial; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commercial (
    idutilisateur integer NOT NULL,
    cacher boolean DEFAULT false
);


ALTER TABLE public.commercial OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 49421)
-- Name: commission_idcommission_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commission_idcommission_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


ALTER TABLE public.commission_idcommission_seq OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 49423)
-- Name: commission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commission (
    id integer DEFAULT nextval('public.commission_idcommission_seq'::regclass) NOT NULL,
    idcommercial integer NOT NULL,
    cacher boolean DEFAULT false
);


ALTER TABLE public.commission OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 49428)
-- Name: consultant; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.consultant (
    idutilisateur integer NOT NULL,
    salaire integer,
    tarif integer,
    cacher boolean DEFAULT false,
    idtypecontrat integer
);


ALTER TABLE public.consultant OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 49432)
-- Name: contact; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact (
    id integer NOT NULL,
    email1 character(32),
    email2 character(32),
    email3 character(32),
    bureau integer,
    fax integer,
    tel3 integer,
    cacher boolean DEFAULT false
);


ALTER TABLE public.contact OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 49436)
-- Name: contact_idcontact_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contact_idcontact_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contact_idcontact_seq OWNER TO postgres;

--
-- TOC entry 2995 (class 0 OID 0)
-- Dependencies: 209
-- Name: contact_idcontact_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contact_idcontact_seq OWNED BY public.contact.id;


--
-- TOC entry 210 (class 1259 OID 49438)
-- Name: contrat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contrat (
    id integer NOT NULL,
    idclient integer NOT NULL,
    idutilisateur integer NOT NULL,
    datedebut date,
    datefin date,
    mission text,
    cacher boolean DEFAULT false
);


ALTER TABLE public.contrat OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 49445)
-- Name: contrat_idcontrat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contrat_idcontrat_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contrat_idcontrat_seq OWNER TO postgres;

--
-- TOC entry 2996 (class 0 OID 0)
-- Dependencies: 211
-- Name: contrat_idcontrat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contrat_idcontrat_seq OWNED BY public.contrat.id;


--
-- TOC entry 212 (class 1259 OID 49447)
-- Name: cra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cra (
    id integer NOT NULL,
    idcontrat integer NOT NULL,
    totaljfacturable real,
    totaljmaladie real,
    totaljconge real,
    astreinte character varying(128),
    periode character varying(128),
    intervention character varying(128),
    chemin text
);


ALTER TABLE public.cra OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 49453)
-- Name: cra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cra_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cra_id_seq OWNER TO postgres;

--
-- TOC entry 2997 (class 0 OID 0)
-- Dependencies: 213
-- Name: cra_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cra_id_seq OWNED BY public.cra.id;


--
-- TOC entry 228 (class 1259 OID 73772)
-- Name: dateferie; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dateferie (
    datejour date,
    libelle character(32)
);


ALTER TABLE public.dateferie OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 49455)
-- Name: depense_iddepense_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.depense_iddepense_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.depense_iddepense_seq OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 49457)
-- Name: depense; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.depense (
    id integer DEFAULT nextval('public.depense_iddepense_seq'::regclass) NOT NULL,
    libelle character(32),
    montant integer,
    cacher boolean DEFAULT false
);


ALTER TABLE public.depense OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 49462)
-- Name: facture; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.facture (
    id integer NOT NULL,
    idcra integer NOT NULL,
    libelle character(32),
    montant integer,
    datef date
);


ALTER TABLE public.facture OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 49465)
-- Name: facture_idfacture_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.facture_idfacture_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.facture_idfacture_seq OWNER TO postgres;

--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 217
-- Name: facture_idfacture_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.facture_idfacture_seq OWNED BY public.facture.id;


--
-- TOC entry 218 (class 1259 OID 49467)
-- Name: infob; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.infob (
    id integer NOT NULL,
    idclient integer NOT NULL,
    idcommercial integer NOT NULL,
    codeagence character(32),
    compte character(32),
    iban character(32),
    bic character(32),
    codebanque character(32),
    clerib integer
);


ALTER TABLE public.infob OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 49470)
-- Name: infob_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.infob_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.infob_id_seq OWNER TO postgres;

--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 219
-- Name: infob_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infob_id_seq OWNED BY public.infob.id;


--
-- TOC entry 220 (class 1259 OID 49472)
-- Name: oneshot; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oneshot (
    idcommission integer NOT NULL,
    montant integer
);


ALTER TABLE public.oneshot OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 49475)
-- Name: payer; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payer (
    idfacture integer NOT NULL,
    idcontrat integer NOT NULL,
    idclient integer NOT NULL
);


ALTER TABLE public.payer OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 49478)
-- Name: pourcentage; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pourcentage (
    idcommission integer NOT NULL,
    valeur integer
);


ALTER TABLE public.pourcentage OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 49481)
-- Name: prendre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.prendre (
    idcontrat integer NOT NULL,
    idcommission integer NOT NULL
);


ALTER TABLE public.prendre OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 49484)
-- Name: typecontrat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.typecontrat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


ALTER TABLE public.typecontrat_id_seq OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 49486)
-- Name: typecontrat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.typecontrat (
    idtype integer DEFAULT nextval('public.typecontrat_id_seq'::regclass) NOT NULL,
    libelle character(32)
);
ALTER TABLE ONLY public.typecontrat ALTER COLUMN libelle SET STORAGE PLAIN;


ALTER TABLE public.typecontrat OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 49490)
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utilisateur (
    id integer NOT NULL,
    nom character varying(32),
    prenom character varying(32),
    adresse character varying(32),
    ville character varying(32),
    cp integer,
    tel integer,
    email character varying(32),
    login character varying(32),
    mdp character varying(32)
);


ALTER TABLE public.utilisateur OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 49493)
-- Name: utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utilisateur_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.utilisateur_id_seq OWNER TO postgres;

--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 227
-- Name: utilisateur_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utilisateur_id_seq OWNED BY public.utilisateur.id;


--
-- TOC entry 2773 (class 2604 OID 49495)
-- Name: client id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.client ALTER COLUMN id SET DEFAULT nextval('public.client_idclient_seq'::regclass);


--
-- TOC entry 2779 (class 2604 OID 49496)
-- Name: contact id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact ALTER COLUMN id SET DEFAULT nextval('public.contact_idcontact_seq'::regclass);


--
-- TOC entry 2781 (class 2604 OID 49497)
-- Name: contrat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat ALTER COLUMN id SET DEFAULT nextval('public.contrat_idcontrat_seq'::regclass);


--
-- TOC entry 2782 (class 2604 OID 49498)
-- Name: cra id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cra ALTER COLUMN id SET DEFAULT nextval('public.cra_id_seq'::regclass);


--
-- TOC entry 2785 (class 2604 OID 49499)
-- Name: facture id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.facture ALTER COLUMN id SET DEFAULT nextval('public.facture_idfacture_seq'::regclass);


--
-- TOC entry 2786 (class 2604 OID 49500)
-- Name: infob id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infob ALTER COLUMN id SET DEFAULT nextval('public.infob_id_seq'::regclass);


--
-- TOC entry 2788 (class 2604 OID 49501)
-- Name: utilisateur id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id SET DEFAULT nextval('public.utilisateur_id_seq'::regclass);


--
-- TOC entry 2962 (class 0 OID 49411)
-- Dependencies: 202
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.client (id, idcontact, raisonsocial, siret, adr, ville, codepostal, cacher) FROM stdin;
\.


--
-- TOC entry 2964 (class 0 OID 49417)
-- Dependencies: 204
-- Data for Name: commercial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commercial (idutilisateur, cacher) FROM stdin;
\.


--
-- TOC entry 2966 (class 0 OID 49423)
-- Dependencies: 206
-- Data for Name: commission; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commission (id, idcommercial, cacher) FROM stdin;
\.


--
-- TOC entry 2967 (class 0 OID 49428)
-- Dependencies: 207
-- Data for Name: consultant; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.consultant (idutilisateur, salaire, tarif, cacher, idtypecontrat) FROM stdin;
\.


--
-- TOC entry 2968 (class 0 OID 49432)
-- Dependencies: 208
-- Data for Name: contact; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contact (id, email1, email2, email3, bureau, fax, tel3, cacher) FROM stdin;
\.


--
-- TOC entry 2970 (class 0 OID 49438)
-- Dependencies: 210
-- Data for Name: contrat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contrat (id, idclient, idutilisateur, datedebut, datefin, mission, cacher) FROM stdin;
\.


--
-- TOC entry 2972 (class 0 OID 49447)
-- Dependencies: 212
-- Data for Name: cra; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cra (id, idcontrat, totaljfacturable, totaljmaladie, totaljconge, astreinte, periode, intervention, chemin) FROM stdin;
\.


--
-- TOC entry 2988 (class 0 OID 73772)
-- Dependencies: 228
-- Data for Name: dateferie; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dateferie (datejour, libelle) FROM stdin;
2020-01-01	jour de lAN                     
2020-05-01	fete du travail                 
2020-05-08	victoire des allies             
2020-07-14	fete nationale                  
2020-08-15	assomption                      
2020-11-01	toussaint                       
2020-11-11	armistice                       
2020-12-25	noel                            
\.


--
-- TOC entry 2975 (class 0 OID 49457)
-- Dependencies: 215
-- Data for Name: depense; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.depense (id, libelle, montant, cacher) FROM stdin;
\.


--
-- TOC entry 2976 (class 0 OID 49462)
-- Dependencies: 216
-- Data for Name: facture; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.facture (id, idcra, libelle, montant, datef) FROM stdin;
\.


--
-- TOC entry 2978 (class 0 OID 49467)
-- Dependencies: 218
-- Data for Name: infob; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infob (id, idclient, idcommercial, codeagence, compte, iban, bic, codebanque, clerib) FROM stdin;
\.


--
-- TOC entry 2980 (class 0 OID 49472)
-- Dependencies: 220
-- Data for Name: oneshot; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.oneshot (idcommission, montant) FROM stdin;
\.


--
-- TOC entry 2981 (class 0 OID 49475)
-- Dependencies: 221
-- Data for Name: payer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payer (idfacture, idcontrat, idclient) FROM stdin;
\.


--
-- TOC entry 2982 (class 0 OID 49478)
-- Dependencies: 222
-- Data for Name: pourcentage; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pourcentage (idcommission, valeur) FROM stdin;
\.


--
-- TOC entry 2983 (class 0 OID 49481)
-- Dependencies: 223
-- Data for Name: prendre; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.prendre (idcontrat, idcommission) FROM stdin;
\.


--
-- TOC entry 2985 (class 0 OID 49486)
-- Dependencies: 225
-- Data for Name: typecontrat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.typecontrat (idtype, libelle) FROM stdin;
1	Soustraitant                    
2	Salarie                         
3	Independant                     
\.


--
-- TOC entry 2986 (class 0 OID 49490)
-- Dependencies: 226
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utilisateur (id, nom, prenom, adresse, ville, cp, tel, email, login, mdp) FROM stdin;
\.


--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 203
-- Name: client_idclient_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.client_idclient_seq', 1, false);


--
-- TOC entry 3002 (class 0 OID 0)
-- Dependencies: 205
-- Name: commission_idcommission_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.commission_idcommission_seq', 1, false);


--
-- TOC entry 3003 (class 0 OID 0)
-- Dependencies: 209
-- Name: contact_idcontact_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_idcontact_seq', 1, false);


--
-- TOC entry 3004 (class 0 OID 0)
-- Dependencies: 211
-- Name: contrat_idcontrat_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contrat_idcontrat_seq', 1, false);


--
-- TOC entry 3005 (class 0 OID 0)
-- Dependencies: 213
-- Name: cra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cra_id_seq', 1, false);


--
-- TOC entry 3006 (class 0 OID 0)
-- Dependencies: 214
-- Name: depense_iddepense_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.depense_iddepense_seq', 1, false);


--
-- TOC entry 3007 (class 0 OID 0)
-- Dependencies: 217
-- Name: facture_idfacture_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.facture_idfacture_seq', 1, false);


--
-- TOC entry 3008 (class 0 OID 0)
-- Dependencies: 219
-- Name: infob_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infob_id_seq', 1, false);


--
-- TOC entry 3009 (class 0 OID 0)
-- Dependencies: 224
-- Name: typecontrat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.typecontrat_id_seq', 1, false);


--
-- TOC entry 3010 (class 0 OID 0)
-- Dependencies: 227
-- Name: utilisateur_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utilisateur_id_seq', 1, false);


--
-- TOC entry 2790 (class 2606 OID 49503)
-- Name: client pk_client; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT pk_client PRIMARY KEY (id);


--
-- TOC entry 2792 (class 2606 OID 49505)
-- Name: commercial pk_commercial; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commercial
    ADD CONSTRAINT pk_commercial PRIMARY KEY (idutilisateur);


--
-- TOC entry 2794 (class 2606 OID 49507)
-- Name: commission pk_commission; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commission
    ADD CONSTRAINT pk_commission PRIMARY KEY (id);


--
-- TOC entry 2796 (class 2606 OID 49509)
-- Name: consultant pk_consultant; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consultant
    ADD CONSTRAINT pk_consultant PRIMARY KEY (idutilisateur);


--
-- TOC entry 2798 (class 2606 OID 49511)
-- Name: contact pk_contact; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact
    ADD CONSTRAINT pk_contact PRIMARY KEY (id);


--
-- TOC entry 2800 (class 2606 OID 49513)
-- Name: contrat pk_contrat; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT pk_contrat PRIMARY KEY (id);


--
-- TOC entry 2802 (class 2606 OID 49515)
-- Name: cra pk_cra; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cra
    ADD CONSTRAINT pk_cra PRIMARY KEY (id);


--
-- TOC entry 2804 (class 2606 OID 49517)
-- Name: depense pk_depense; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.depense
    ADD CONSTRAINT pk_depense PRIMARY KEY (id);


--
-- TOC entry 2806 (class 2606 OID 49519)
-- Name: facture pk_facture; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.facture
    ADD CONSTRAINT pk_facture PRIMARY KEY (id);


--
-- TOC entry 2808 (class 2606 OID 49521)
-- Name: infob pk_infob; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infob
    ADD CONSTRAINT pk_infob PRIMARY KEY (id);


--
-- TOC entry 2810 (class 2606 OID 49523)
-- Name: oneshot pk_oneshot; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oneshot
    ADD CONSTRAINT pk_oneshot PRIMARY KEY (idcommission);


--
-- TOC entry 2812 (class 2606 OID 49525)
-- Name: payer pk_payer; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payer
    ADD CONSTRAINT pk_payer PRIMARY KEY (idfacture, idcontrat, idclient);


--
-- TOC entry 2814 (class 2606 OID 49527)
-- Name: pourcentage pk_pourcentage; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pourcentage
    ADD CONSTRAINT pk_pourcentage PRIMARY KEY (idcommission);


--
-- TOC entry 2816 (class 2606 OID 49529)
-- Name: prendre pk_prendre; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prendre
    ADD CONSTRAINT pk_prendre PRIMARY KEY (idcontrat, idcommission);


--
-- TOC entry 2818 (class 2606 OID 49531)
-- Name: utilisateur pk_utilisateur; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT pk_utilisateur PRIMARY KEY (id);


--
-- TOC entry 2819 (class 2606 OID 49532)
-- Name: client fk_client_contact; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT fk_client_contact FOREIGN KEY (idcontact) REFERENCES public.contact(id);


--
-- TOC entry 2820 (class 2606 OID 49537)
-- Name: commercial fk_commercial_utilisateur; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commercial
    ADD CONSTRAINT fk_commercial_utilisateur FOREIGN KEY (idutilisateur) REFERENCES public.utilisateur(id);


--
-- TOC entry 2821 (class 2606 OID 49542)
-- Name: commission fk_commission_commercial; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commission
    ADD CONSTRAINT fk_commission_commercial FOREIGN KEY (idcommercial) REFERENCES public.commercial(idutilisateur);


--
-- TOC entry 2822 (class 2606 OID 49547)
-- Name: consultant fk_consultant_utilisateur; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consultant
    ADD CONSTRAINT fk_consultant_utilisateur FOREIGN KEY (idutilisateur) REFERENCES public.utilisateur(id);


--
-- TOC entry 2823 (class 2606 OID 49552)
-- Name: contrat fk_contrat_client; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT fk_contrat_client FOREIGN KEY (idclient) REFERENCES public.client(id);


--
-- TOC entry 2824 (class 2606 OID 49557)
-- Name: contrat fk_contrat_consultant; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT fk_contrat_consultant FOREIGN KEY (idutilisateur) REFERENCES public.consultant(idutilisateur);


--
-- TOC entry 2825 (class 2606 OID 49562)
-- Name: cra fk_cra_contrat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cra
    ADD CONSTRAINT fk_cra_contrat FOREIGN KEY (idcontrat) REFERENCES public.contrat(id);


--
-- TOC entry 2826 (class 2606 OID 49567)
-- Name: facture fk_facture_cra; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.facture
    ADD CONSTRAINT fk_facture_cra FOREIGN KEY (idcra) REFERENCES public.cra(id);


--
-- TOC entry 2827 (class 2606 OID 49572)
-- Name: infob fk_infob_client; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infob
    ADD CONSTRAINT fk_infob_client FOREIGN KEY (idclient) REFERENCES public.client(id);


--
-- TOC entry 2828 (class 2606 OID 49577)
-- Name: infob fk_infob_commercial; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infob
    ADD CONSTRAINT fk_infob_commercial FOREIGN KEY (idcommercial) REFERENCES public.commercial(idutilisateur);


--
-- TOC entry 2829 (class 2606 OID 49582)
-- Name: oneshot fk_oneshot_commission; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oneshot
    ADD CONSTRAINT fk_oneshot_commission FOREIGN KEY (idcommission) REFERENCES public.commission(id);


--
-- TOC entry 2830 (class 2606 OID 49587)
-- Name: payer fk_payer_client; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payer
    ADD CONSTRAINT fk_payer_client FOREIGN KEY (idclient) REFERENCES public.client(id);


--
-- TOC entry 2831 (class 2606 OID 49592)
-- Name: payer fk_payer_contrat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payer
    ADD CONSTRAINT fk_payer_contrat FOREIGN KEY (idcontrat) REFERENCES public.contrat(id);


--
-- TOC entry 2832 (class 2606 OID 49597)
-- Name: payer fk_payer_facture; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payer
    ADD CONSTRAINT fk_payer_facture FOREIGN KEY (idfacture) REFERENCES public.facture(id);


--
-- TOC entry 2833 (class 2606 OID 49602)
-- Name: pourcentage fk_pourcentage_commission; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pourcentage
    ADD CONSTRAINT fk_pourcentage_commission FOREIGN KEY (idcommission) REFERENCES public.commission(id);


--
-- TOC entry 2834 (class 2606 OID 49607)
-- Name: prendre fk_prendre_commission; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prendre
    ADD CONSTRAINT fk_prendre_commission FOREIGN KEY (idcommission) REFERENCES public.commission(id);


--
-- TOC entry 2835 (class 2606 OID 49612)
-- Name: prendre fk_prendre_contrat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prendre
    ADD CONSTRAINT fk_prendre_contrat FOREIGN KEY (idcontrat) REFERENCES public.contrat(id);


-- Completed on 2020-04-16 23:47:56

--
-- PostgreSQL database dump complete
--

