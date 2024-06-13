--
-- PostgreSQL database dump
--

-- Dumped from database version 14.12 (Homebrew)
-- Dumped by pg_dump version 14.12 (Homebrew)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO cthulhu;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO cthulhu;

--
-- Name: character_skill; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.character_skill (
    character_id bigint NOT NULL,
    skill_id bigint NOT NULL,
    value smallint DEFAULT '0'::smallint NOT NULL,
    experience smallint DEFAULT '0'::smallint NOT NULL,
    "order" smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.character_skill OWNER TO cthulhu;

--
-- Name: characters; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.characters (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    user_id bigint,
    occupation character varying(255),
    residence character varying(255),
    birthplace character varying(255),
    age integer,
    gender character varying(255),
    strength smallint DEFAULT '0'::smallint NOT NULL,
    dexterity smallint DEFAULT '0'::smallint NOT NULL,
    intelligence smallint DEFAULT '0'::smallint NOT NULL,
    constitution smallint DEFAULT '0'::smallint NOT NULL,
    appearance smallint DEFAULT '0'::smallint NOT NULL,
    power smallint DEFAULT '0'::smallint NOT NULL,
    size smallint DEFAULT '0'::smallint NOT NULL,
    education smallint DEFAULT '0'::smallint NOT NULL,
    move_rate smallint DEFAULT '0'::smallint NOT NULL,
    temporary_insanity boolean DEFAULT false NOT NULL,
    indefinite_insanity boolean DEFAULT false NOT NULL,
    major_wound boolean DEFAULT false NOT NULL,
    unconscious boolean DEFAULT false NOT NULL,
    dying boolean DEFAULT false NOT NULL,
    hit_points smallint DEFAULT '0'::smallint NOT NULL,
    sanity smallint DEFAULT '0'::smallint NOT NULL,
    luck smallint DEFAULT '0'::smallint NOT NULL,
    magic_points smallint DEFAULT '0'::smallint NOT NULL,
    dodge smallint DEFAULT '0'::smallint NOT NULL,
    build smallint DEFAULT '0'::smallint NOT NULL,
    damage_bonus character varying(255) DEFAULT '0'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    avatar character varying(255),
    CONSTRAINT characters_gender_check CHECK (((gender)::text = ANY ((ARRAY['Male'::character varying, 'Female'::character varying, 'Other'::character varying])::text[])))
);


ALTER TABLE public.characters OWNER TO cthulhu;

--
-- Name: characters_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.characters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.characters_id_seq OWNER TO cthulhu;

--
-- Name: characters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.characters_id_seq OWNED BY public.characters.id;


--
-- Name: equipables; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.equipables (
    id bigint NOT NULL,
    character_id bigint NOT NULL,
    equipable_type character varying(255) NOT NULL,
    equipable_id bigint NOT NULL,
    ammo smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.equipables OWNER TO cthulhu;

--
-- Name: equipables_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.equipables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.equipables_id_seq OWNER TO cthulhu;

--
-- Name: equipables_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.equipables_id_seq OWNED BY public.equipables.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO cthulhu;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO cthulhu;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO cthulhu;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO cthulhu;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO cthulhu;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: messages; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.messages (
    id bigint NOT NULL,
    sender_id bigint NOT NULL,
    receiver_id bigint NOT NULL,
    read boolean DEFAULT false NOT NULL,
    content text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.messages OWNER TO cthulhu;

--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messages_id_seq OWNER TO cthulhu;

--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.messages_id_seq OWNED BY public.messages.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO cthulhu;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO cthulhu;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO cthulhu;

--
-- Name: pulse_aggregates; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.pulse_aggregates (
    id bigint NOT NULL,
    bucket integer NOT NULL,
    period integer NOT NULL,
    type character varying(255) NOT NULL,
    key text NOT NULL,
    key_hash uuid GENERATED ALWAYS AS ((md5(key))::uuid) STORED NOT NULL,
    aggregate character varying(255) NOT NULL,
    value numeric(20,2) NOT NULL,
    count integer
);


ALTER TABLE public.pulse_aggregates OWNER TO cthulhu;

--
-- Name: pulse_aggregates_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.pulse_aggregates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pulse_aggregates_id_seq OWNER TO cthulhu;

--
-- Name: pulse_aggregates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.pulse_aggregates_id_seq OWNED BY public.pulse_aggregates.id;


--
-- Name: pulse_entries; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.pulse_entries (
    id bigint NOT NULL,
    "timestamp" integer NOT NULL,
    type character varying(255) NOT NULL,
    key text NOT NULL,
    key_hash uuid GENERATED ALWAYS AS ((md5(key))::uuid) STORED NOT NULL,
    value bigint
);


ALTER TABLE public.pulse_entries OWNER TO cthulhu;

--
-- Name: pulse_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.pulse_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pulse_entries_id_seq OWNER TO cthulhu;

--
-- Name: pulse_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.pulse_entries_id_seq OWNED BY public.pulse_entries.id;


--
-- Name: pulse_values; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.pulse_values (
    id bigint NOT NULL,
    "timestamp" integer NOT NULL,
    type character varying(255) NOT NULL,
    key text NOT NULL,
    key_hash uuid GENERATED ALWAYS AS ((md5(key))::uuid) STORED NOT NULL,
    value text NOT NULL
);


ALTER TABLE public.pulse_values OWNER TO cthulhu;

--
-- Name: pulse_values_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.pulse_values_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pulse_values_id_seq OWNER TO cthulhu;

--
-- Name: pulse_values_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.pulse_values_id_seq OWNED BY public.pulse_values.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO cthulhu;

--
-- Name: skills; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.skills (
    id bigint NOT NULL,
    slug character varying(20) NOT NULL,
    display_name character varying(50) NOT NULL,
    starting_value smallint DEFAULT '0'::smallint NOT NULL,
    description text,
    order_by smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.skills OWNER TO cthulhu;

--
-- Name: skills_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.skills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.skills_id_seq OWNER TO cthulhu;

--
-- Name: skills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.skills_id_seq OWNED BY public.skills.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'player'::character varying NOT NULL,
    "imageUrl" character varying(255),
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['Keeper of Arcane Lore'::character varying, 'player'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO cthulhu;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO cthulhu;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: weapons; Type: TABLE; Schema: public; Owner: cthulhu
--

CREATE TABLE public.weapons (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    skill character varying(255) NOT NULL,
    damage character varying(255) NOT NULL,
    base_range character varying(255) NOT NULL,
    uses_per_round character varying(255) DEFAULT '1'::character varying NOT NULL,
    bullets_in_mag character varying(255),
    cost character varying(255) NOT NULL,
    malfunction character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.weapons OWNER TO cthulhu;

--
-- Name: weapons_id_seq; Type: SEQUENCE; Schema: public; Owner: cthulhu
--

CREATE SEQUENCE public.weapons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.weapons_id_seq OWNER TO cthulhu;

--
-- Name: weapons_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: cthulhu
--

ALTER SEQUENCE public.weapons_id_seq OWNED BY public.weapons.id;


--
-- Name: characters id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.characters ALTER COLUMN id SET DEFAULT nextval('public.characters_id_seq'::regclass);


--
-- Name: equipables id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.equipables ALTER COLUMN id SET DEFAULT nextval('public.equipables_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: messages id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.messages ALTER COLUMN id SET DEFAULT nextval('public.messages_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: pulse_aggregates id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_aggregates ALTER COLUMN id SET DEFAULT nextval('public.pulse_aggregates_id_seq'::regclass);


--
-- Name: pulse_entries id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_entries ALTER COLUMN id SET DEFAULT nextval('public.pulse_entries_id_seq'::regclass);


--
-- Name: pulse_values id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_values ALTER COLUMN id SET DEFAULT nextval('public.pulse_values_id_seq'::regclass);


--
-- Name: skills id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.skills ALTER COLUMN id SET DEFAULT nextval('public.skills_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: weapons id; Type: DEFAULT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.weapons ALTER COLUMN id SET DEFAULT nextval('public.weapons_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: character_skill; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.character_skill (character_id, skill_id, value, experience, "order") FROM stdin;
9	57	5	0	0
9	58	1	0	1
9	59	5	0	2
9	60	1	0	3
9	61	5	0	4
9	63	20	0	6
9	65	0	0	8
9	66	5	0	9
9	68	20	0	11
9	69	10	0	12
9	71	25	0	14
9	75	20	0	18
9	76	20	0	19
9	77	20	0	20
9	78	20	0	21
9	79	20	0	22
9	80	20	0	23
9	81	20	0	24
9	82	20	0	25
9	83	20	0	26
9	85	5	0	28
9	86	15	0	29
9	87	20	0	30
9	88	1	0	31
9	101	1	0	44
9	104	5	0	47
9	105	1	0	48
9	106	10	0	49
9	107	25	0	50
9	108	20	0	51
9	109	10	0	52
9	110	10	0	53
9	111	20	0	54
9	112	10	0	55
9	99	1	0	42
9	64	30	0	7
9	67	23	0	10
9	70	5	0	13
9	72	20	0	15
9	73	25	0	16
9	74	25	0	17
9	84	70	0	27
9	113	61	0	0
9	89	82	0	32
9	90	40	0	33
9	91	50	0	34
9	92	55	0	35
9	93	41	0	36
9	94	10	0	37
9	95	76	0	38
9	96	10	0	39
9	97	10	0	40
9	98	15	0	41
9	100	10	0	43
9	115	54	0	0
9	103	16	0	46
9	102	45	0	45
9	114	61	0	0
9	62	15	0	5
10	57	5	0	0
10	58	1	0	1
10	59	5	0	2
10	60	1	0	3
10	61	5	0	4
10	62	15	0	5
10	63	20	0	6
10	64	0	0	7
10	65	0	0	8
10	66	5	0	9
10	67	0	0	10
10	68	20	0	11
10	69	10	0	12
10	70	20	0	13
10	71	25	0	14
10	72	20	0	15
10	73	20	0	16
10	74	20	0	17
10	75	20	0	18
10	76	20	0	19
10	77	20	0	20
10	78	20	0	21
10	79	20	0	22
10	80	20	0	23
10	81	20	0	24
10	82	20	0	25
10	83	20	0	26
10	84	30	0	27
10	85	5	0	28
10	86	15	0	29
10	87	20	0	30
10	88	1	0	31
10	89	0	0	32
10	90	5	0	33
10	91	20	0	34
10	92	20	0	35
10	93	1	0	36
10	94	1	0	37
10	95	1	0	38
10	96	1	0	39
10	97	10	0	40
10	98	10	0	41
10	99	1	0	42
10	100	10	0	43
10	101	1	0	44
10	102	10	0	45
10	103	1	0	46
10	104	5	0	47
10	105	1	0	48
10	106	10	0	49
10	107	25	0	50
10	108	20	0	51
10	109	10	0	52
10	110	10	0	53
10	111	20	0	54
10	112	10	0	55
10	113	1	0	0
10	114	1	0	0
10	115	1	0	0
11	57	5	0	0
11	58	1	0	1
11	59	5	0	2
11	60	1	0	3
11	61	5	0	4
11	62	15	0	5
11	63	20	0	6
11	64	0	0	7
11	65	0	0	8
11	66	5	0	9
11	67	0	0	10
11	68	20	0	11
11	69	10	0	12
11	70	20	0	13
11	71	25	0	14
11	72	20	0	15
11	73	20	0	16
11	74	20	0	17
11	75	20	0	18
11	76	20	0	19
11	77	20	0	20
11	78	20	0	21
11	79	20	0	22
11	80	20	0	23
11	81	20	0	24
11	82	20	0	25
11	83	20	0	26
11	84	30	0	27
11	85	5	0	28
11	86	15	0	29
11	87	20	0	30
11	88	1	0	31
11	89	0	0	32
11	90	5	0	33
11	91	20	0	34
11	92	20	0	35
11	93	1	0	36
11	94	1	0	37
11	95	1	0	38
11	96	1	0	39
11	97	10	0	40
11	98	10	0	41
11	99	1	0	42
11	100	10	0	43
11	101	1	0	44
11	102	10	0	45
11	103	1	0	46
11	104	5	0	47
11	105	1	0	48
11	106	10	0	49
11	107	25	0	50
11	108	20	0	51
11	109	10	0	52
11	110	10	0	53
11	111	20	0	54
11	112	10	0	55
11	113	1	0	0
11	114	1	0	0
11	115	1	0	0
12	57	5	0	0
12	58	1	0	1
12	59	5	0	2
12	60	1	0	3
12	61	5	0	4
12	62	15	0	5
12	63	20	0	6
12	64	0	0	7
12	65	0	0	8
12	66	5	0	9
12	67	0	0	10
12	68	20	0	11
12	69	10	0	12
12	70	20	0	13
12	71	25	0	14
12	72	20	0	15
12	73	20	0	16
12	74	20	0	17
12	75	20	0	18
12	76	20	0	19
12	77	20	0	20
12	78	20	0	21
12	79	20	0	22
12	80	20	0	23
12	81	20	0	24
12	82	20	0	25
12	83	20	0	26
12	84	30	0	27
12	85	5	0	28
12	86	15	0	29
12	87	20	0	30
12	88	1	0	31
12	89	0	0	32
12	90	5	0	33
12	91	20	0	34
12	92	20	0	35
12	93	1	0	36
12	94	1	0	37
12	95	1	0	38
12	96	1	0	39
12	97	10	0	40
12	98	10	0	41
12	99	1	0	42
12	100	10	0	43
12	101	1	0	44
12	102	10	0	45
12	103	1	0	46
12	104	5	0	47
12	105	1	0	48
12	106	10	0	49
12	107	25	0	50
12	108	20	0	51
12	109	10	0	52
12	110	10	0	53
12	111	20	0	54
12	112	10	0	55
12	113	1	0	0
12	114	1	0	0
12	115	1	0	0
13	57	5	0	0
13	58	1	0	1
13	59	5	0	2
13	60	1	0	3
13	61	5	0	4
13	62	15	0	5
13	63	20	0	6
13	64	0	0	7
13	65	0	0	8
13	66	5	0	9
13	67	0	0	10
13	68	20	0	11
13	69	10	0	12
13	70	20	0	13
13	71	25	0	14
13	72	20	0	15
13	73	20	0	16
13	74	20	0	17
13	75	20	0	18
13	76	20	0	19
13	77	20	0	20
13	78	20	0	21
13	79	20	0	22
13	80	20	0	23
13	81	20	0	24
13	82	20	0	25
13	83	20	0	26
13	84	30	0	27
13	85	5	0	28
13	86	15	0	29
13	87	20	0	30
13	88	1	0	31
13	89	0	0	32
13	90	5	0	33
13	91	20	0	34
13	92	20	0	35
13	93	1	0	36
13	94	1	0	37
13	95	1	0	38
13	96	1	0	39
13	97	10	0	40
13	98	10	0	41
13	99	1	0	42
13	100	10	0	43
13	101	1	0	44
13	102	10	0	45
13	103	1	0	46
13	104	5	0	47
13	105	1	0	48
13	106	10	0	49
13	107	25	0	50
13	108	20	0	51
13	109	10	0	52
13	110	10	0	53
13	111	20	0	54
13	112	10	0	55
13	113	1	0	0
13	114	1	0	0
13	115	1	0	0
14	57	5	0	0
14	58	1	0	1
14	59	5	0	2
14	60	1	0	3
14	61	5	0	4
14	62	15	0	5
14	63	20	0	6
14	64	0	0	7
14	65	0	0	8
14	66	5	0	9
14	67	0	0	10
14	68	20	0	11
14	69	10	0	12
14	70	20	0	13
14	71	25	0	14
14	72	20	0	15
14	73	20	0	16
14	74	20	0	17
14	75	20	0	18
14	76	20	0	19
14	77	20	0	20
14	78	20	0	21
14	79	20	0	22
14	80	20	0	23
14	81	20	0	24
14	82	20	0	25
14	83	20	0	26
14	84	30	0	27
14	85	5	0	28
14	86	15	0	29
14	87	20	0	30
14	88	1	0	31
14	89	0	0	32
14	90	5	0	33
14	91	20	0	34
14	92	20	0	35
14	93	1	0	36
14	94	1	0	37
14	95	1	0	38
14	96	1	0	39
14	97	10	0	40
14	98	10	0	41
14	99	1	0	42
14	100	10	0	43
14	101	1	0	44
14	102	10	0	45
14	103	1	0	46
14	104	5	0	47
14	105	1	0	48
14	106	10	0	49
14	107	25	0	50
14	108	20	0	51
14	109	10	0	52
14	110	10	0	53
14	111	20	0	54
14	112	10	0	55
14	113	1	0	0
14	114	1	0	0
14	115	1	0	0
\.


--
-- Data for Name: characters; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.characters (id, name, slug, user_id, occupation, residence, birthplace, age, gender, strength, dexterity, intelligence, constitution, appearance, power, size, education, move_rate, temporary_insanity, indefinite_insanity, major_wound, unconscious, dying, hit_points, sanity, luck, magic_points, dodge, build, damage_bonus, created_at, updated_at, avatar) FROM stdin;
10	Khalid Bahringer	khalid-bahringer	7	Administrative Support Supervisors	Donnellview	Elodybury	25	Female	60	60	65	80	90	75	80	65	7	f	f	f	f	f	32	75	65	15	30	1	+1D4	2024-06-11 12:04:34	2024-06-12 20:03:29	\N
14	Heath Koch	heath-koch	8	Assessor	West Elmochester	Daughertymouth	41	Female	80	85	70	90	75	75	70	65	9	f	f	f	f	f	32	75	85	15	42	1	+1D4	2024-06-11 12:04:34	2024-06-12 20:03:55	\N
11	Miss Robyn Jast I	miss-robyn-jast-i	5	Buyer	Mertzburgh	West Linwood	72	Female	95	85	85	85	105	95	65	75	9	f	f	f	f	f	30	95	85	19	42	1	+1D4	2024-06-11 12:04:34	2024-06-12 20:04:10	\N
12	Prof. Wilburn Collier Jr.	prof-wilburn-collier-jr	4	Meter Mechanic	Schummville	Lake Bryonshire	24	Male	80	80	55	95	75	85	85	75	7	f	f	f	f	f	36	85	85	17	40	2	+1D6	2024-06-11 12:04:34	2024-06-12 20:04:32	\N
13	Jayce Schamberger	jayce-schamberger	6	Logging Equipment Operator	Matildeburgh	Olsonchester	1	Female	90	90	50	95	80	105	65	85	9	f	f	f	f	f	32	105	55	21	45	1	+1D4	2024-06-11 12:04:34	2024-06-12 20:04:56	\N
9	George Bartholemew	george-bartholemew	3	Puritan Priest	Salem Village	Salem Village	38	Male	40	45	75	50	50	40	55	82	0	f	f	f	f	f	21	40	58	8	0	0	0	2024-06-11 09:56:17	2024-06-11 10:36:35	\N
\.


--
-- Data for Name: equipables; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.equipables (id, character_id, equipable_type, equipable_id, ammo, created_at, updated_at) FROM stdin;
1	9	App\\Models\\Weapon	45	1	\N	\N
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.messages (id, sender_id, receiver_id, read, content, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2024_04_27_121501_create_characters_table	1
5	2024_04_27_121548_create_skills_table	1
6	2024_05_03_204200_create_weapons_table	1
7	2024_05_26_063827_add_avatar_column_to_characters	1
8	2024_06_03_185103_add_fields_to_users	1
9	2024_06_06_125912_create_pulse_tables	1
10	2024_06_06_162011_create_messages_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: pulse_aggregates; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.pulse_aggregates (id, bucket, period, type, key, aggregate, value, count) FROM stdin;
13	1718098260	60	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	count	1.00	\N
14	1718098200	360	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	count	1.00	\N
15	1718097120	1440	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	count	1.00	\N
16	1718095680	10080	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	count	1.00	\N
17	1718098260	60	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	max	1718098273.00	\N
18	1718098200	360	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	max	1718098273.00	\N
19	1718097120	1440	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	max	1718098273.00	\N
20	1718095680	10080	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	max	1718098273.00	\N
5	1718098260	60	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	count	2.00	\N
6	1718098200	360	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	count	2.00	\N
7	1718097120	1440	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	count	2.00	\N
8	1718095680	10080	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	count	2.00	\N
9	1718098260	60	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	max	1718098281.00	\N
10	1718098200	360	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	max	1718098281.00	\N
11	1718097120	1440	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	max	1718098281.00	\N
12	1718095680	10080	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	max	1718098281.00	\N
85	1718099820	60	user_request	3	count	6.00	\N
29	1718098320	60	user_request	3	count	6.00	\N
30	1718098200	360	user_request	3	count	6.00	\N
31	1718097120	1440	user_request	3	count	6.00	\N
61	1718099760	60	user_request	3	count	6.00	\N
53	1718098980	60	user_request	3	count	1.00	\N
54	1718098920	360	user_request	3	count	1.00	\N
159	1718100000	1440	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	4.00	\N
153	1718100960	60	user_request	3	count	5.00	\N
125	1718100600	60	user_request	3	count	4.00	\N
57	1718099580	60	user_request	3	count	1.00	\N
58	1718099280	360	user_request	3	count	1.00	\N
109	1718099880	60	user_request	3	count	2.00	\N
62	1718099640	360	user_request	3	count	14.00	\N
55	1718098560	1440	user_request	3	count	16.00	\N
154	1718100720	360	user_request	3	count	5.00	\N
117	1718100540	60	user_request	3	count	2.00	\N
160	1718095680	10080	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	4.00	\N
141	1718100660	60	user_request	3	count	3.00	\N
118	1718100360	360	user_request	3	count	9.00	\N
32	1718095680	10080	user_request	3	count	98.00	\N
119	1718100000	1440	user_request	3	count	19.00	\N
157	1718100960	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	3.00	\N
158	1718100720	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	3.00	\N
161	1718100960	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101018.00	\N
162	1718100720	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101018.00	\N
499	1718108640	1440	user_request	3	count	6.00	\N
485	1718107500	60	user_request	3	count	2.00	\N
197	1718101140	60	user_request	3	count	2.00	\N
462	1718107200	360	user_request	3	count	8.00	\N
535	1718111520	1440	user_request	3	count	5.00	\N
401	1718102700	60	user_request	3	count	6.00	\N
205	1718101200	60	user_request	3	count	3.00	\N
198	1718101080	360	user_request	3	count	5.00	\N
217	1718101200	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	1.00	\N
218	1718101080	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	count	1.00	\N
221	1718101200	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101247.00	\N
222	1718101080	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101247.00	\N
163	1718100000	1440	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101247.00	\N
164	1718095680	10080	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	max	1718101247.00	\N
505	1718109480	60	user_request	3	count	2.00	\N
493	1718107560	60	user_request	3	count	1.00	\N
225	1718101680	60	user_request	3	count	3.00	\N
226	1718101440	360	user_request	3	count	3.00	\N
285	1718102400	60	user_request	3	count	5.00	\N
237	1718101980	60	user_request	3	count	1.00	\N
494	1718107560	360	user_request	3	count	1.00	\N
241	1718102100	60	user_request	3	count	1.00	\N
238	1718101800	360	user_request	3	count	2.00	\N
463	1718107200	1440	user_request	3	count	9.00	\N
245	1718102160	60	user_request	3	count	1.00	\N
425	1718102760	60	user_request	3	count	2.00	\N
322	1718102520	360	user_request	3	count	28.00	\N
345	1718102580	60	user_request	3	count	8.00	\N
227	1718101440	1440	user_request	3	count	52.00	\N
433	1718103840	60	user_request	3	count	1.00	\N
434	1718103600	360	user_request	3	count	1.00	\N
435	1718102880	1440	user_request	3	count	1.00	\N
305	1718102460	60	user_request	3	count	4.00	\N
246	1718102160	360	user_request	3	count	19.00	\N
437	1718104440	60	user_request	3	count	1.00	\N
438	1718104320	360	user_request	3	count	1.00	\N
249	1718102280	60	user_request	3	count	6.00	\N
441	1718104800	60	user_request	3	count	1.00	\N
442	1718104680	360	user_request	3	count	1.00	\N
506	1718109360	360	user_request	3	count	2.00	\N
445	1718105100	60	user_request	3	count	1.00	\N
273	1718102340	60	user_request	3	count	3.00	\N
449	1718105160	60	user_request	3	count	1.00	\N
446	1718105040	360	user_request	3	count	2.00	\N
439	1718104320	1440	user_request	3	count	4.00	\N
377	1718102640	60	user_request	3	count	6.00	\N
453	1718105760	60	user_request	3	count	1.00	\N
454	1718105760	360	user_request	3	count	1.00	\N
321	1718102520	60	user_request	3	count	6.00	\N
457	1718106480	60	user_request	3	count	1.00	\N
458	1718106480	360	user_request	3	count	1.00	\N
455	1718105760	1440	user_request	3	count	2.00	\N
461	1718107260	60	user_request	3	count	1.00	\N
497	1718108880	60	user_request	3	count	2.00	\N
469	1718107440	60	user_request	3	count	4.00	\N
465	1718107380	60	user_request	3	count	1.00	\N
498	1718108640	360	user_request	3	count	2.00	\N
537	1718111640	60	user_request	3	count	1.00	\N
529	1718110620	60	user_request	3	count	1.00	\N
534	1718111520	360	user_request	3	count	2.00	\N
513	1718109780	60	user_request	3	count	2.00	\N
514	1718109720	360	user_request	3	count	2.00	\N
521	1718110380	60	user_request	3	count	2.00	\N
522	1718110080	360	user_request	3	count	2.00	\N
530	1718110440	360	user_request	3	count	1.00	\N
523	1718110080	1440	user_request	3	count	3.00	\N
533	1718111520	60	user_request	3	count	1.00	\N
456	1718105760	10080	user_request	3	count	29.00	\N
541	1718111880	60	user_request	3	count	1.00	\N
542	1718111880	360	user_request	3	count	1.00	\N
545	1718112540	60	user_request	3	count	1.00	\N
546	1718112240	360	user_request	3	count	1.00	\N
549	1718112780	60	user_request	3	count	1.00	\N
550	1718112600	360	user_request	3	count	1.00	\N
553	1718113920	60	user_request	3	count	1.00	\N
554	1718113680	360	user_request	3	count	1.00	\N
557	1718114100	60	user_request	3	count	1.00	\N
558	1718114040	360	user_request	3	count	1.00	\N
555	1718112960	1440	user_request	3	count	2.00	\N
561	1718114400	60	user_request	3	count	2.00	\N
562	1718114400	360	user_request	3	count	2.00	\N
563	1718114400	1440	user_request	3	count	2.00	\N
569	1718117580	60	user_request	3	count	2.00	\N
570	1718117280	360	user_request	3	count	2.00	\N
623	1718125920	1440	user_request	3	count	3.00	\N
658	1718168760	360	user_request	3	count	2.00	\N
577	1718118480	60	user_request	3	count	1.00	\N
578	1718118360	360	user_request	3	count	1.00	\N
571	1718117280	1440	user_request	3	count	3.00	\N
581	1718119500	60	user_request	3	count	1.00	\N
582	1718119440	360	user_request	3	count	1.00	\N
583	1718118720	1440	user_request	3	count	1.00	\N
585	1718120460	60	user_request	3	count	1.00	\N
586	1718120160	360	user_request	3	count	1.00	\N
589	1718121420	60	user_request	3	count	1.00	\N
590	1718121240	360	user_request	3	count	1.00	\N
587	1718120160	1440	user_request	3	count	2.00	\N
647	1718167680	1440	user_request	3	count	5.00	\N
593	1718121720	60	user_request	3	count	1.00	\N
594	1718121600	360	user_request	3	count	1.00	\N
633	1718130180	60	user_request	3	count	2.00	\N
597	1718122140	60	user_request	3	count	1.00	\N
598	1718121960	360	user_request	3	count	1.00	\N
595	1718121600	1440	user_request	3	count	2.00	\N
634	1718129880	360	user_request	3	count	2.00	\N
601	1718123100	60	user_request	3	count	1.00	\N
635	1718128800	1440	user_request	3	count	2.00	\N
605	1718123160	60	user_request	3	count	1.00	\N
675	1718208000	1440	user_request	3	count	6.00	\N
641	1718130720	60	user_request	3	count	1.00	\N
642	1718130600	360	user_request	3	count	1.00	\N
609	1718123280	60	user_request	3	count	1.00	\N
602	1718123040	360	user_request	3	count	3.00	\N
643	1718130240	1440	user_request	3	count	1.00	\N
624	1718125920	10080	user_request	3	count	6.00	\N
613	1718124120	60	user_request	3	count	1.00	\N
617	1718124420	60	user_request	3	count	1.00	\N
614	1718124120	360	user_request	3	count	2.00	\N
603	1718123040	1440	user_request	3	count	5.00	\N
572	1718115840	10080	user_request	3	count	13.00	\N
621	1718125980	60	user_request	3	count	1.00	\N
622	1718125920	360	user_request	3	count	1.00	\N
625	1718126280	60	user_request	3	count	1.00	\N
626	1718126280	360	user_request	3	count	1.00	\N
665	1718169900	60	user_request	3	count	1.00	\N
666	1718169840	360	user_request	3	count	1.00	\N
629	1718126760	60	user_request	3	count	1.00	\N
630	1718126640	360	user_request	3	count	1.00	\N
667	1718169120	1440	user_request	3	count	1.00	\N
648	1718166240	10080	user_request	3	count	6.00	\N
669	1718204280	60	user_request	3	count	1.00	\N
670	1718204040	360	user_request	3	count	1.00	\N
645	1718167980	60	user_request	3	count	3.00	\N
646	1718167680	360	user_request	3	count	3.00	\N
671	1718203680	1440	user_request	3	count	1.00	\N
672	1718196480	10080	user_request	3	count	1.00	\N
657	1718168760	60	user_request	3	count	1.00	\N
698	1718211600	360	user_request	3	count	2.00	\N
661	1718168880	60	user_request	3	count	1.00	\N
701	1718211840	60	user_request	3	count	1.00	\N
699	1718210880	1440	user_request	3	count	2.00	\N
689	1718209020	60	user_request	3	count	2.00	\N
674	1718208720	360	user_request	3	count	6.00	\N
673	1718208720	60	user_request	3	count	4.00	\N
697	1718211720	60	user_request	3	count	1.00	\N
713	1718215560	60	user_request	3	count	2.00	\N
705	1718212380	60	user_request	3	count	1.00	\N
706	1718212320	360	user_request	3	count	1.00	\N
707	1718212320	1440	user_request	3	count	1.00	\N
709	1718213760	60	user_request	3	count	1.00	\N
710	1718213760	360	user_request	3	count	1.00	\N
711	1718213760	1440	user_request	3	count	1.00	\N
714	1718215560	360	user_request	3	count	2.00	\N
763	1718222400	1440	user_request	3	count	38.00	\N
990	1718226360	360	user_request	3	count	3.00	\N
841	1718222760	60	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	count	1.00	\N
721	1718215920	60	user_request	3	count	2.00	\N
722	1718215920	360	user_request	3	count	2.00	\N
715	1718215200	1440	user_request	3	count	4.00	\N
676	1718206560	10080	user_request	3	count	14.00	\N
729	1718217300	60	user_request	3	count	2.00	\N
730	1718217000	360	user_request	3	count	2.00	\N
761	1718222580	60	user_request	3	count	9.00	\N
842	1718222760	360	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	count	1.00	\N
737	1718217600	60	user_request	3	count	1.00	\N
738	1718217360	360	user_request	3	count	1.00	\N
731	1718216640	1440	user_request	3	count	3.00	\N
843	1718222400	1440	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	count	1.00	\N
741	1718218140	60	user_request	3	count	1.00	\N
742	1718218080	360	user_request	3	count	1.00	\N
844	1718216640	10080	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	count	1.00	\N
745	1718219100	60	user_request	3	count	1.00	\N
746	1718218800	360	user_request	3	count	1.00	\N
743	1718218080	1440	user_request	3	count	2.00	\N
749	1718220600	60	user_request	3	count	1.00	\N
750	1718220600	360	user_request	3	count	1.00	\N
751	1718219520	1440	user_request	3	count	1.00	\N
845	1718222760	60	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	max	1718222803.00	\N
846	1718222760	360	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	max	1718222803.00	\N
753	1718221260	60	user_request	3	count	2.00	\N
754	1718220960	360	user_request	3	count	2.00	\N
755	1718220960	1440	user_request	3	count	2.00	\N
847	1718222400	1440	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	max	1718222803.00	\N
848	1718216640	10080	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	max	1718222803.00	\N
897	1718223600	60	user_request	3	count	2.00	\N
865	1718223300	60	user_request	3	count	5.00	\N
849	1718223000	60	user_request	3	count	2.00	\N
838	1718222760	360	user_request	3	count	3.00	\N
858	1718223120	360	user_request	3	count	7.00	\N
961	1718226180	60	user_request	3	count	3.00	\N
885	1718223540	60	user_request	3	count	1.00	\N
857	1718223180	60	user_request	3	count	2.00	\N
889	1718223540	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	count	1.00	\N
890	1718223480	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	count	1.00	\N
891	1718222400	1440	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	count	1.00	\N
892	1718216640	10080	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	count	1.00	\N
893	1718223540	60	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	max	1718223546.00	\N
894	1718223480	360	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	max	1718223546.00	\N
895	1718222400	1440	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	max	1718223546.00	\N
945	1718226000	60	user_request	3	count	2.00	\N
947	1718225280	1440	user_request	3	count	14.00	\N
905	1718223660	60	user_request	3	count	6.00	\N
896	1718216640	10080	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	max	1718223546.00	\N
989	1718226420	60	user_request	3	count	2.00	\N
797	1718222640	60	user_request	3	count	9.00	\N
886	1718223480	360	user_request	3	count	9.00	\N
929	1718223840	60	user_request	3	count	4.00	\N
833	1718222700	60	user_request	3	count	1.00	\N
762	1718222400	360	user_request	3	count	19.00	\N
930	1718223840	360	user_request	3	count	4.00	\N
931	1718223840	1440	user_request	3	count	4.00	\N
837	1718222760	60	user_request	3	count	1.00	\N
953	1718226060	60	user_request	3	count	1.00	\N
973	1718226300	60	user_request	3	count	4.00	\N
946	1718226000	360	user_request	3	count	11.00	\N
957	1718226120	60	user_request	3	count	1.00	\N
997	1718226480	60	user_request	3	count	1.00	\N
732	1718216640	10080	user_request	3	count	64.00	\N
1001	1718226840	60	user_request	3	count	1.00	\N
1002	1718226720	360	user_request	3	count	1.00	\N
1005	1718227320	60	user_request	3	count	1.00	\N
1006	1718227080	360	user_request	3	count	1.00	\N
1003	1718226720	1440	user_request	3	count	2.00	\N
1076	1718256960	10080	user_request	3	count	8.00	\N
1009	1718228520	60	user_request	3	count	1.00	\N
1010	1718228520	360	user_request	3	count	1.00	\N
1097	1718264460	60	user_request	3	count	1.00	\N
1013	1718229480	60	user_request	3	count	1.00	\N
1014	1718229240	360	user_request	3	count	1.00	\N
1011	1718228160	1440	user_request	3	count	2.00	\N
1081	1718262180	60	user_request	3	count	1.00	\N
1017	1718230320	60	user_request	3	count	1.00	\N
1018	1718230320	360	user_request	3	count	1.00	\N
1019	1718229600	1440	user_request	3	count	1.00	\N
1078	1718262000	360	user_request	3	count	2.00	\N
1021	1718233260	60	user_request	3	count	1.00	\N
1022	1718233200	360	user_request	3	count	1.00	\N
1023	1718232480	1440	user_request	3	count	1.00	\N
1004	1718226720	10080	user_request	3	count	6.00	\N
1025	1718238840	60	user_request	3	count	1.00	\N
1026	1718238600	360	user_request	3	count	1.00	\N
1027	1718238240	1440	user_request	3	count	1.00	\N
1029	1718240820	60	user_request	3	count	1.00	\N
1030	1718240760	360	user_request	3	count	1.00	\N
1031	1718239680	1440	user_request	3	count	1.00	\N
1098	1718264160	360	user_request	3	count	1.00	\N
1033	1718241960	60	user_request	3	count	1.00	\N
1034	1718241840	360	user_request	3	count	1.00	\N
1037	1718242380	60	user_request	3	count	1.00	\N
1038	1718242200	360	user_request	3	count	1.00	\N
1035	1718241120	1440	user_request	3	count	2.00	\N
1085	1718262480	60	user_request	3	count	1.00	\N
1041	1718242680	60	user_request	3	count	1.00	\N
1042	1718242560	360	user_request	3	count	1.00	\N
1043	1718242560	1440	user_request	3	count	1.00	\N
1086	1718262360	360	user_request	3	count	1.00	\N
1045	1718245320	60	user_request	3	count	1.00	\N
1046	1718245080	360	user_request	3	count	1.00	\N
1047	1718244000	1440	user_request	3	count	1.00	\N
1028	1718236800	10080	user_request	3	count	6.00	\N
1049	1718250600	60	user_request	3	count	1.00	\N
1050	1718250480	360	user_request	3	count	1.00	\N
1053	1718251140	60	user_request	3	count	1.00	\N
1054	1718250840	360	user_request	3	count	1.00	\N
1051	1718249760	1440	user_request	3	count	2.00	\N
1075	1718261280	1440	user_request	3	count	4.00	\N
1057	1718252100	60	user_request	3	count	1.00	\N
1058	1718251920	360	user_request	3	count	1.00	\N
1059	1718251200	1440	user_request	3	count	1.00	\N
1108	1718267040	10080	user_request	3	count	6.00	\N
1061	1718253060	60	user_request	3	count	1.00	\N
1062	1718253000	360	user_request	3	count	1.00	\N
1089	1718262780	60	user_request	3	count	1.00	\N
1065	1718253540	60	user_request	3	count	1.00	\N
1066	1718253360	360	user_request	3	count	1.00	\N
1063	1718252640	1440	user_request	3	count	2.00	\N
1090	1718262720	360	user_request	3	count	1.00	\N
1069	1718254560	60	user_request	3	count	1.00	\N
1070	1718254440	360	user_request	3	count	1.00	\N
1071	1718254080	1440	user_request	3	count	1.00	\N
1052	1718246880	10080	user_request	3	count	6.00	\N
1073	1718261940	60	user_request	3	count	1.00	\N
1074	1718261640	360	user_request	3	count	1.00	\N
1077	1718262120	60	user_request	3	count	1.00	\N
1105	1718269320	60	user_request	3	count	1.00	\N
1093	1718263320	60	user_request	3	count	1.00	\N
1094	1718263080	360	user_request	3	count	1.00	\N
1091	1718262720	1440	user_request	3	count	2.00	\N
1101	1718265540	60	user_request	3	count	1.00	\N
1102	1718265240	360	user_request	3	count	1.00	\N
1099	1718264160	1440	user_request	3	count	2.00	\N
1106	1718269200	360	user_request	3	count	1.00	\N
1109	1718269560	60	user_request	3	count	1.00	\N
1113	1718269680	60	user_request	3	count	1.00	\N
1110	1718269560	360	user_request	3	count	2.00	\N
1107	1718268480	1440	user_request	3	count	3.00	\N
1117	1718271000	60	user_request	3	count	1.00	\N
1118	1718271000	360	user_request	3	count	1.00	\N
1119	1718269920	1440	user_request	3	count	1.00	\N
1121	1718272080	60	user_request	3	count	1.00	\N
1122	1718272080	360	user_request	3	count	1.00	\N
1123	1718271360	1440	user_request	3	count	1.00	\N
1125	1718273160	60	user_request	3	count	1.00	\N
1126	1718273160	360	user_request	3	count	1.00	\N
1127	1718272800	1440	user_request	3	count	1.00	\N
1129	1718281740	60	user_request	3	count	1.00	\N
1130	1718281440	360	user_request	3	count	1.00	\N
1131	1718281440	1440	user_request	3	count	1.00	\N
1133	1718283000	60	user_request	3	count	1.00	\N
1134	1718282880	360	user_request	3	count	1.00	\N
1229	1718295960	60	user_request	3	count	2.00	\N
1137	1718283720	60	user_request	3	count	1.00	\N
1138	1718283600	360	user_request	3	count	1.00	\N
1193	1718290200	60	user_request	3	count	1.00	\N
1190	1718290080	360	user_request	3	count	2.00	\N
1141	1718284140	60	user_request	3	count	1.00	\N
1142	1718283960	360	user_request	3	count	1.00	\N
1135	1718282880	1440	user_request	3	count	3.00	\N
1209	1718295720	60	user_request	3	count	2.00	\N
1145	1718284440	60	user_request	3	count	1.00	\N
1146	1718284320	360	user_request	3	count	1.00	\N
1149	1718285100	60	user_request	3	count	1.00	\N
1150	1718285040	360	user_request	3	count	1.00	\N
1147	1718284320	1440	user_request	3	count	2.00	\N
1132	1718277120	10080	user_request	3	count	6.00	\N
1153	1718287980	60	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	count	1.00	\N
1154	1718287920	360	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	count	1.00	\N
1155	1718287200	1440	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	count	1.00	\N
1156	1718287200	10080	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	count	1.00	\N
1157	1718287980	60	slow_user_request	3	count	1.00	\N
1158	1718287920	360	slow_user_request	3	count	1.00	\N
1159	1718287200	1440	slow_user_request	3	count	1.00	\N
1160	1718287200	10080	slow_user_request	3	count	1.00	\N
1165	1718287980	60	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	max	1026.00	\N
1166	1718287920	360	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	max	1026.00	\N
1167	1718287200	1440	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	max	1026.00	\N
1168	1718287200	10080	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	max	1026.00	\N
1161	1718287980	60	user_request	3	count	2.00	\N
1162	1718287920	360	user_request	3	count	2.00	\N
1163	1718287200	1440	user_request	3	count	2.00	\N
1197	1718290620	60	user_request	3	count	1.00	\N
1173	1718289120	60	user_request	3	count	1.00	\N
1174	1718289000	360	user_request	3	count	1.00	\N
1198	1718290440	360	user_request	3	count	1.00	\N
1177	1718289360	60	user_request	3	count	1.00	\N
1225	1718295840	60	user_request	3	count	1.00	\N
1181	1718289540	60	user_request	3	count	1.00	\N
1201	1718291400	60	user_request	3	count	1.00	\N
1202	1718291160	360	user_request	3	count	1.00	\N
1191	1718290080	1440	user_request	3	count	4.00	\N
1185	1718289660	60	user_request	3	count	1.00	\N
1178	1718289360	360	user_request	3	count	3.00	\N
1175	1718288640	1440	user_request	3	count	4.00	\N
1189	1718290080	60	user_request	3	count	1.00	\N
1205	1718292480	60	user_request	3	count	1.00	\N
1206	1718292240	360	user_request	3	count	1.00	\N
1207	1718291520	1440	user_request	3	count	1.00	\N
1217	1718295780	60	user_request	3	count	2.00	\N
1210	1718295480	360	user_request	3	count	4.00	\N
1211	1718294400	1440	user_request	3	count	4.00	\N
1226	1718295840	360	user_request	3	count	3.00	\N
1227	1718295840	1440	user_request	3	count	3.00	\N
1164	1718287200	10080	user_request	3	count	18.00	\N
\.


--
-- Data for Name: pulse_entries; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.pulse_entries (id, "timestamp", type, key, value) FROM stdin;
2	1718098266	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	1718098266
3	1718098273	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\CommandNotFoundException","vendor\\/symfony\\/console\\/Application.php:702"]	1718098273
4	1718098281	exception	["Symfony\\\\Component\\\\Console\\\\Exception\\\\NamespaceNotFoundException","vendor\\/symfony\\/console\\/Application.php:636"]	1718098281
5	1718098322	user_request	3	\N
6	1718098323	user_request	3	\N
7	1718098338	user_request	3	\N
8	1718098338	user_request	3	\N
9	1718098338	user_request	3	\N
10	1718098351	user_request	3	\N
11	1718099023	user_request	3	\N
12	1718099581	user_request	3	\N
13	1718099776	user_request	3	\N
14	1718099777	user_request	3	\N
15	1718099800	user_request	3	\N
16	1718099801	user_request	3	\N
17	1718099810	user_request	3	\N
18	1718099819	user_request	3	\N
19	1718099824	user_request	3	\N
20	1718099839	user_request	3	\N
21	1718099860	user_request	3	\N
22	1718099867	user_request	3	\N
23	1718099868	user_request	3	\N
24	1718099877	user_request	3	\N
25	1718099882	user_request	3	\N
26	1718099884	user_request	3	\N
27	1718100586	user_request	3	\N
28	1718100586	user_request	3	\N
29	1718100602	user_request	3	\N
30	1718100603	user_request	3	\N
31	1718100614	user_request	3	\N
32	1718100614	user_request	3	\N
33	1718100678	user_request	3	\N
34	1718100679	user_request	3	\N
35	1718100708	user_request	3	\N
36	1718100988	user_request	3	\N
37	1718100988	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	1718100988
38	1718101003	user_request	3	\N
39	1718101004	user_request	3	\N
40	1718101017	user_request	3	\N
41	1718101017	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	1718101017
42	1718101018	user_request	3	\N
43	1718101018	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	1718101018
44	1718101180	user_request	3	\N
45	1718101196	user_request	3	\N
46	1718101237	user_request	3	\N
47	1718101238	user_request	3	\N
48	1718101247	user_request	3	\N
49	1718101247	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:46"]	1718101247
50	1718101717	user_request	3	\N
51	1718101718	user_request	3	\N
52	1718101729	user_request	3	\N
53	1718101982	user_request	3	\N
54	1718102125	user_request	3	\N
55	1718102195	user_request	3	\N
56	1718102285	user_request	3	\N
57	1718102285	user_request	3	\N
58	1718102287	user_request	3	\N
59	1718102291	user_request	3	\N
60	1718102293	user_request	3	\N
61	1718102325	user_request	3	\N
62	1718102350	user_request	3	\N
63	1718102354	user_request	3	\N
64	1718102355	user_request	3	\N
65	1718102426	user_request	3	\N
66	1718102443	user_request	3	\N
67	1718102444	user_request	3	\N
68	1718102445	user_request	3	\N
69	1718102458	user_request	3	\N
70	1718102460	user_request	3	\N
71	1718102470	user_request	3	\N
72	1718102476	user_request	3	\N
73	1718102499	user_request	3	\N
74	1718102542	user_request	3	\N
75	1718102555	user_request	3	\N
76	1718102556	user_request	3	\N
77	1718102561	user_request	3	\N
78	1718102573	user_request	3	\N
79	1718102579	user_request	3	\N
80	1718102583	user_request	3	\N
81	1718102588	user_request	3	\N
82	1718102594	user_request	3	\N
83	1718102609	user_request	3	\N
84	1718102620	user_request	3	\N
85	1718102626	user_request	3	\N
86	1718102631	user_request	3	\N
87	1718102637	user_request	3	\N
88	1718102648	user_request	3	\N
89	1718102653	user_request	3	\N
90	1718102672	user_request	3	\N
91	1718102673	user_request	3	\N
92	1718102693	user_request	3	\N
93	1718102695	user_request	3	\N
94	1718102702	user_request	3	\N
95	1718102720	user_request	3	\N
96	1718102720	user_request	3	\N
97	1718102747	user_request	3	\N
98	1718102747	user_request	3	\N
99	1718102751	user_request	3	\N
100	1718102761	user_request	3	\N
101	1718102761	user_request	3	\N
102	1718103841	user_request	3	\N
103	1718104466	user_request	3	\N
104	1718104806	user_request	3	\N
105	1718105120	user_request	3	\N
106	1718105213	user_request	3	\N
107	1718105807	user_request	3	\N
108	1718106495	user_request	3	\N
109	1718107277	user_request	3	\N
110	1718107407	user_request	3	\N
111	1718107481	user_request	3	\N
112	1718107481	user_request	3	\N
113	1718107483	user_request	3	\N
114	1718107483	user_request	3	\N
115	1718107540	user_request	3	\N
116	1718107540	user_request	3	\N
117	1718107566	user_request	3	\N
118	1718108923	user_request	3	\N
119	1718108923	user_request	3	\N
120	1718109522	user_request	3	\N
121	1718109523	user_request	3	\N
122	1718109787	user_request	3	\N
123	1718109787	user_request	3	\N
124	1718110433	user_request	3	\N
125	1718110434	user_request	3	\N
126	1718110622	user_request	3	\N
127	1718111577	user_request	3	\N
128	1718111653	user_request	3	\N
129	1718111889	user_request	3	\N
130	1718112560	user_request	3	\N
131	1718112811	user_request	3	\N
132	1718113940	user_request	3	\N
133	1718114117	user_request	3	\N
134	1718114416	user_request	3	\N
135	1718114416	user_request	3	\N
136	1718117581	user_request	3	\N
137	1718117581	user_request	3	\N
138	1718118525	user_request	3	\N
139	1718119535	user_request	3	\N
140	1718120502	user_request	3	\N
141	1718121458	user_request	3	\N
142	1718121769	user_request	3	\N
143	1718122172	user_request	3	\N
144	1718123103	user_request	3	\N
145	1718123196	user_request	3	\N
146	1718123320	user_request	3	\N
147	1718124130	user_request	3	\N
148	1718124428	user_request	3	\N
149	1718126001	user_request	3	\N
150	1718126326	user_request	3	\N
151	1718126783	user_request	3	\N
152	1718130215	user_request	3	\N
153	1718130216	user_request	3	\N
154	1718130766	user_request	3	\N
155	1718168019	user_request	3	\N
156	1718168019	user_request	3	\N
157	1718168020	user_request	3	\N
158	1718168779	user_request	3	\N
159	1718168904	user_request	3	\N
160	1718169929	user_request	3	\N
161	1718204283	user_request	3	\N
162	1718208725	user_request	3	\N
163	1718208729	user_request	3	\N
164	1718208730	user_request	3	\N
165	1718208748	user_request	3	\N
166	1718209050	user_request	3	\N
167	1718209050	user_request	3	\N
168	1718211722	user_request	3	\N
169	1718211846	user_request	3	\N
170	1718212425	user_request	3	\N
171	1718213804	user_request	3	\N
172	1718215616	user_request	3	\N
173	1718215616	user_request	3	\N
174	1718215957	user_request	3	\N
175	1718215963	user_request	3	\N
176	1718217355	user_request	3	\N
177	1718217356	user_request	3	\N
178	1718217640	user_request	3	\N
179	1718218142	user_request	3	\N
180	1718219145	user_request	3	\N
181	1718220637	user_request	3	\N
182	1718221275	user_request	3	\N
183	1718221275	user_request	3	\N
184	1718222584	user_request	3	\N
185	1718222584	user_request	3	\N
186	1718222609	user_request	3	\N
187	1718222609	user_request	3	\N
188	1718222623	user_request	3	\N
189	1718222631	user_request	3	\N
190	1718222631	user_request	3	\N
191	1718222635	user_request	3	\N
192	1718222635	user_request	3	\N
193	1718222644	user_request	3	\N
194	1718222650	user_request	3	\N
195	1718222650	user_request	3	\N
196	1718222657	user_request	3	\N
197	1718222672	user_request	3	\N
198	1718222672	user_request	3	\N
199	1718222685	user_request	3	\N
200	1718222696	user_request	3	\N
201	1718222696	user_request	3	\N
202	1718222717	user_request	3	\N
203	1718222802	user_request	3	\N
204	1718222803	exception	["BadMethodCallException","app\\/Http\\/Controllers\\/SkillController.php:113"]	1718222803
205	1718223039	user_request	3	\N
206	1718223039	user_request	3	\N
207	1718223228	user_request	3	\N
208	1718223228	user_request	3	\N
209	1718223301	user_request	3	\N
210	1718223301	user_request	3	\N
211	1718223350	user_request	3	\N
212	1718223355	user_request	3	\N
213	1718223355	user_request	3	\N
214	1718223546	user_request	3	\N
215	1718223546	exception	["TypeError","app\\/Http\\/Controllers\\/SkillController.php:114"]	1718223546
216	1718223619	user_request	3	\N
217	1718223619	user_request	3	\N
218	1718223665	user_request	3	\N
219	1718223665	user_request	3	\N
220	1718223677	user_request	3	\N
221	1718223677	user_request	3	\N
222	1718223690	user_request	3	\N
223	1718223690	user_request	3	\N
224	1718223851	user_request	3	\N
225	1718223851	user_request	3	\N
226	1718223884	user_request	3	\N
227	1718223884	user_request	3	\N
228	1718226022	user_request	3	\N
229	1718226046	user_request	3	\N
230	1718226103	user_request	3	\N
231	1718226139	user_request	3	\N
232	1718226183	user_request	3	\N
233	1718226211	user_request	3	\N
234	1718226239	user_request	3	\N
235	1718226333	user_request	3	\N
236	1718226334	user_request	3	\N
237	1718226341	user_request	3	\N
238	1718226342	user_request	3	\N
239	1718226442	user_request	3	\N
240	1718226443	user_request	3	\N
241	1718226535	user_request	3	\N
242	1718226882	user_request	3	\N
243	1718227351	user_request	3	\N
244	1718228527	user_request	3	\N
245	1718229529	user_request	3	\N
246	1718230338	user_request	3	\N
247	1718233291	user_request	3	\N
248	1718238870	user_request	3	\N
249	1718240878	user_request	3	\N
250	1718241964	user_request	3	\N
251	1718242388	user_request	3	\N
252	1718242681	user_request	3	\N
253	1718245349	user_request	3	\N
254	1718250630	user_request	3	\N
255	1718251198	user_request	3	\N
256	1718252104	user_request	3	\N
257	1718253104	user_request	3	\N
258	1718253541	user_request	3	\N
259	1718254593	user_request	3	\N
260	1718261947	user_request	3	\N
261	1718262132	user_request	3	\N
262	1718262225	user_request	3	\N
263	1718262508	user_request	3	\N
264	1718262812	user_request	3	\N
265	1718263328	user_request	3	\N
266	1718264508	user_request	3	\N
267	1718265577	user_request	3	\N
268	1718269361	user_request	3	\N
269	1718269609	user_request	3	\N
270	1718269733	user_request	3	\N
271	1718271014	user_request	3	\N
272	1718272124	user_request	3	\N
273	1718273210	user_request	3	\N
274	1718281769	user_request	3	\N
275	1718283037	user_request	3	\N
276	1718283773	user_request	3	\N
277	1718284190	user_request	3	\N
278	1718284466	user_request	3	\N
279	1718285138	user_request	3	\N
280	1718287988	slow_request	["GET","\\/dashboard","App\\\\Http\\\\Controllers\\\\PageController@dashboard"]	1026
281	1718287988	slow_user_request	3	\N
282	1718287988	user_request	3	\N
283	1718287989	user_request	3	\N
284	1718289122	user_request	3	\N
285	1718289418	user_request	3	\N
286	1718289554	user_request	3	\N
287	1718289677	user_request	3	\N
288	1718290131	user_request	3	\N
289	1718290224	user_request	3	\N
290	1718290658	user_request	3	\N
291	1718291406	user_request	3	\N
292	1718292482	user_request	3	\N
293	1718295777	user_request	3	\N
294	1718295777	user_request	3	\N
295	1718295784	user_request	3	\N
296	1718295791	user_request	3	\N
297	1718295890	user_request	3	\N
298	1718295980	user_request	3	\N
299	1718295983	user_request	3	\N
\.


--
-- Data for Name: pulse_values; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.pulse_values (id, "timestamp", type, key, value) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
A7LQKPZ1mluv3qa23Ym08xLOYVB3PPKhyIEvOmdw	3	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVnpTbzVYamE0V1A4a1BDSndGcDh5dzFwVjBKOFFvUG44Mzh2THlaWiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMDoiaHR0cHM6Ly9jdGh1bGh1LnRlc3QvZGFzaGJvYXJkIjt9fQ==	1718295983
\.


--
-- Data for Name: skills; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.skills (id, slug, display_name, starting_value, description, order_by) FROM stdin;
57	accounting	Accounting	5	\N	0
58	anthropology	Anthropology	1	\N	1
59	appraise	Appraise	5	\N	2
60	archeology	Archeology	1	\N	3
61	art_crafts	Art & Crafts	5	\N	4
62	charm	Charm	15	\N	5
63	climb	Climb	20	\N	6
64	credit_rating	Credit_rating	0	\N	7
65	cthulhu_mythos	Cthulhu_mythos	0	\N	8
66	disguise	Disguise	5	\N	9
67	dodge	Dodge	0	\N	10
68	drive_auto	Drive Auto	20	\N	11
69	electric_repair	Electric Repair	10	\N	12
70	fast_talking	Fast Talking	20	\N	13
71	fighting	Fighting	25	\N	14
72	firearms_handgun	Firearms Handgun	20	\N	15
73	firearms-rifle	Firearms (Rifle)	20	\N	16
74	firearms-shotgun	Firearms (Shotgun)	20	\N	17
75	firearms-bow	Firearms (Bow)	20	\N	18
76	fighting-whip	Fighting (Whip)	20	\N	19
77	fighting-brawl	Fighting (Brawl)	20	\N	20
78	fighting-mg	Fighting (MG)	20	\N	21
79	fighting-axe	Fighting (Axe)	20	\N	22
80	fighting-garrote	Fighting (Garrote)	20	\N	23
81	fighting-smg	Firearms (SMG)	20	\N	24
82	fighting-flail	Fighting (Flail)	20	\N	25
83	fighting-spear	Fighting (Spear)	20	\N	26
84	first_aid	First_aid	30	\N	27
85	history	History	5	\N	28
86	intimidate	Intimidate	15	\N	29
87	jump	Jump	20	\N	30
88	language_other	Language other	1	\N	31
89	language_own	Language own	0	\N	32
90	law	Law	5	\N	33
91	library_use	Library_use	20	\N	34
92	listen	Listen	20	\N	35
93	locksmith	Locksmith	1	\N	36
94	mech_repair	Mech. Repair	1	\N	37
95	medicine	Medicine	1	\N	38
96	natural_world	Natural_world	1	\N	39
97	navigate	Navigate	10	\N	40
98	occult	Occult	10	\N	41
99	Op_hv_machine	Op_hv_machine	1	\N	42
100	persuade	Persuade	10	\N	43
101	pilot	Pilot	1	\N	44
102	psychology	Psychology	10	\N	45
103	psychoanalysis	Psychoanalysis	1	\N	46
104	ride	Ride	5	\N	47
105	science_skill	Science Skill	1	\N	48
106	sleight_of_hand	Sleight of Hand	10	\N	49
107	spot-hidden	Spot Hidden	25	\N	50
108	stealth	Stealth	20	\N	51
109	survival	Survival	10	\N	52
110	swim	Swim	10	\N	53
111	throw	Throw	20	\N	54
112	track	Track	10	\N	55
113	latin	Latin	1	\N	0
114	biology	Biology	1	\N	0
115	pharmacy	Pharmacy	1	\N	0
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, "imageUrl") FROM stdin;
3	Sebastian Edelmann	sebastian@schlossberg-edelmann.com	2024-06-11 09:31:25	$2y$12$FEFM63D1FOxLr2X2a72dCe2PfKKeanbyS.dVMY5iQMaxnz4jQkR1W	xerscEJsVk	2024-06-11 09:31:26	2024-06-11 09:31:26	player	\N
4	Ms. Margarette Hyatt	josue.lehner@example.org	2024-06-11 12:05:32	$2y$12$vKPrhG5leR78VXOiJC5QSeoqSKBB1Iyh9LgTf7avZLAO3pRgQGcS2	qMF9tuCUlT	2024-06-11 12:05:32	2024-06-11 12:05:32	player	\N
5	Lesly Jerde	lucious72@example.com	2024-06-11 12:05:32	$2y$12$vKPrhG5leR78VXOiJC5QSeoqSKBB1Iyh9LgTf7avZLAO3pRgQGcS2	KvCMpymERQ	2024-06-11 12:05:32	2024-06-11 12:05:32	player	\N
6	Tracy Donnelly	donny.ward@example.net	2024-06-11 12:05:32	$2y$12$vKPrhG5leR78VXOiJC5QSeoqSKBB1Iyh9LgTf7avZLAO3pRgQGcS2	jilYzhtrIp	2024-06-11 12:05:32	2024-06-11 12:05:32	player	\N
7	Dameon Hintz	imurazik@example.net	2024-06-11 12:05:32	$2y$12$vKPrhG5leR78VXOiJC5QSeoqSKBB1Iyh9LgTf7avZLAO3pRgQGcS2	huPNavQnUs	2024-06-11 12:05:32	2024-06-11 12:05:32	player	\N
8	Maya Altenwerth	schinner.sammy@example.net	2024-06-11 12:05:32	$2y$12$vKPrhG5leR78VXOiJC5QSeoqSKBB1Iyh9LgTf7avZLAO3pRgQGcS2	GewNDEzho6	2024-06-11 12:05:32	2024-06-11 12:05:32	player	\N
\.


--
-- Data for Name: weapons; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.weapons (id, name, skill, damage, base_range, uses_per_round, bullets_in_mag, cost, malfunction, created_at, updated_at) FROM stdin;
38	Bow and Arrows	firearms-bow	1D6+half DB	30 yards	1	1	$7	97	2024-05-05 08:17:30	2024-06-11 09:31:25
39	Brass Knuckles	fighting-brawl	1D3+1+DB	Touch	1	-	$1	-	2024-05-05 08:17:30	2024-06-11 09:31:25
40	Bullwhip	fighting-whip	1D3+half	DB 10 feet	1	-	$5	-	2024-05-05 08:17:30	2024-06-11 09:31:25
41	Burning Torch	fighting-brawl	1D6+burn	Touch	1	=	$0.05	-	2024-05-05 08:17:30	2024-06-11 09:31:25
42	Blackjack (Cosh, life-preserver)	fighting-brawl	1D8+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-06-11 09:31:25
43	Club, large (baseball, cricket bat, poker)	fighting-brawl	1D8+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-06-11 09:31:25
44	Club,small (nightstick)	fighting-brawl	1D6+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-06-11 09:31:25
45	Crossbow (i)	firearms-bow	1D8+2	50 yards	1/2	1	$10	96	2024-05-05 08:17:30	2024-06-11 09:31:25
46	Garrote*(i)	fighting-garrote	1D6+DB	Touch	1	-	$0.50	-	2024-05-05 08:17:30	2024-06-11 09:31:25
47	Hatchet/Sickle (i)	fighting-axe	1D6+1+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-06-11 09:31:25
48	Knife, Large (machete, etc.) (i)	fighting-brawl	1D8+DB	Touch	1	-	$4	-	2024-05-05 08:17:30	2024-06-11 09:31:25
49	Knife,Medium (carving knife, etc.) (i)	fighting-brawl	1D4+2+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-06-11 09:31:25
50	Knife, Small (switchblade, etc.) (i)	fighting-brawl	1D4+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-06-11 09:31:25
51	Nunchaku	fighting-flail	1D8+DB	Touch	1	-	$1	-	2024-05-05 08:17:30	2024-06-11 09:31:25
52	Rock, Thrown	throw	1D4+half DB	STR/ 5 yards	1	-	-	-	2024-05-05 08:17:30	2024-06-11 09:31:25
53	Shuriken (i)	throw	1D3+half DB	STR/ 5 yards	2	One Use	$0.50	100	2024-05-05 08:17:30	2024-06-11 09:31:25
54	Spear (cavalry lance)(i)	fighting-spear	1D8+1	Touch	1	-	$25	-	2024-05-05 08:17:30	2024-06-11 09:31:25
55	Spear, Thrown (i)	throw	1D8+half DB	STR/ 5 yards	1	-	$1		2024-05-05 08:17:30	2024-06-11 09:31:25
56	.22 Short Automatic	firearms-handgun	1D6	10 yards	1 (3)	6	$25	100	2024-05-05 08:17:30	2024-06-11 09:31:25
57	.25 Derringer (1B)	firearms-handgun	1D6	3 yards	1	1	$12	100	2024-05-05 08:17:30	2024-06-11 09:31:25
58	.32 or 7.65mm Revolver	firearms-handgun	1D8	15 yards	1 (3)	6	$15	100	2024-05-05 08:17:30	2024-06-11 09:31:25
59	.32 or 7.65mm Automatic	firearms-handgun	1D8	15 yards	1 (3)	8	$20	99	2024-05-05 08:17:30	2024-06-11 09:31:25
60	Model P08 Luger	firearms-handgun	1D10	15 yards	1 (3)	8	$75	99	2024-05-05 08:17:30	2024-06-11 09:31:25
61	.45 Revolver	firearms-handgun	1D10+2	15 yards	1 (3)	6	$30	100	2024-05-05 08:17:30	2024-06-11 09:31:25
62	.45 Automatic	firearms-handgun	1D10+2	15 yards	1 (3)	7	$40	100	2024-05-05 08:17:30	2024-06-11 09:31:25
63	20-gauge Shotgun (2B)	firearms-shotgun	2D6/1D6/1D3	10/20/50 yards	1 or 2	2	$35	100	2024-05-05 08:17:30	2024-06-11 09:31:25
64	16-gauge Shotgun (2B)	firearms-shotgun	2D6+2/1D6+1/1D4	10/20/50 yards	1 or 2	2	$40	100	2024-05-05 08:17:30	2024-06-11 09:31:25
65	12-gauge Shotgun (2B)	firearms-shotgun	4D6/2D6/1D6	10/20/50 yards	1 or 2	2	$40	100	2024-05-05 08:17:30	2024-06-11 09:31:25
66	12-gauge Shotgun (semi-auto)	firearms-shotgun	4D6/2D6/1D6	10/20/50 yards	1 (2)	5	$45	100	2024-05-05 08:17:30	2024-06-11 09:31:25
67	12-gauge Shotgun (2B sawed off)	firearms-shotgun	4D6/1D6	5/10 yards	1 or 2	2	N/A	100	2024-05-05 08:17:30	2024-06-11 09:31:25
68	Bergmann MP181/MP2811	firearms-smg	1D10	20 yards	1 (2) or full auto	20/30/32	$1,000	96	2024-05-05 08:17:30	2024-06-11 09:31:25
69	Thompson	firearms-smg	1D10+2	20 yards	1 or full auto	20/30/50	$200+	96	2024-05-05 08:17:30	2024-06-11 09:31:25
70	Browning Auto	firearms-mg	2D6+4	90 yards	1 (2) or full	auto	20 $800	100	2024-05-05 08:17:30	2024-06-11 09:31:25
71	.30 Browning M1917A1	firearms-mg	2D6+4	150 yards	Full auto	250	$3,000	96	2024-05-05 08:17:30	2024-06-11 09:31:25
72	Bren Gun	firearms-mg	2D6+4	110 yards	1 or full auto	30/100	$3,000	96	2024-05-05 08:17:30	2024-06-11 09:31:25
73	Mark I Lewis Gun	firearms-mg	2D6+4	110 yards	Full auto	47/97	$3,000	96	2024-05-05 08:17:30	2024-06-11 09:31:25
74	Vickers .303	firearms-mg	2D6+4	110 yards	Full auto	250	$3,000	96	2024-05-05 08:17:30	2024-06-11 09:31:25
\.


--
-- Name: characters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.characters_id_seq', 14, true);


--
-- Name: equipables_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.equipables_id_seq', 1, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.messages_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.migrations_id_seq', 10, true);


--
-- Name: pulse_aggregates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_aggregates_id_seq', 1236, true);


--
-- Name: pulse_entries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_entries_id_seq', 299, true);


--
-- Name: pulse_values_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_values_id_seq', 1, false);


--
-- Name: skills_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.skills_id_seq', 115, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.users_id_seq', 8, true);


--
-- Name: weapons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.weapons_id_seq', 74, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: character_skill character_skill_character_id_skill_id_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.character_skill
    ADD CONSTRAINT character_skill_character_id_skill_id_unique UNIQUE (character_id, skill_id);


--
-- Name: characters characters_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.characters
    ADD CONSTRAINT characters_pkey PRIMARY KEY (id);


--
-- Name: characters characters_slug_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.characters
    ADD CONSTRAINT characters_slug_unique UNIQUE (slug);


--
-- Name: equipables equipables_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.equipables
    ADD CONSTRAINT equipables_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: messages messages_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: pulse_aggregates pulse_aggregates_bucket_period_type_aggregate_key_hash_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_aggregates
    ADD CONSTRAINT pulse_aggregates_bucket_period_type_aggregate_key_hash_unique UNIQUE (bucket, period, type, aggregate, key_hash);


--
-- Name: pulse_aggregates pulse_aggregates_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_aggregates
    ADD CONSTRAINT pulse_aggregates_pkey PRIMARY KEY (id);


--
-- Name: pulse_entries pulse_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_entries
    ADD CONSTRAINT pulse_entries_pkey PRIMARY KEY (id);


--
-- Name: pulse_values pulse_values_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_values
    ADD CONSTRAINT pulse_values_pkey PRIMARY KEY (id);


--
-- Name: pulse_values pulse_values_type_key_hash_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.pulse_values
    ADD CONSTRAINT pulse_values_type_key_hash_unique UNIQUE (type, key_hash);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: skills skills_display_name_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.skills
    ADD CONSTRAINT skills_display_name_unique UNIQUE (display_name);


--
-- Name: skills skills_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.skills
    ADD CONSTRAINT skills_pkey PRIMARY KEY (id);


--
-- Name: skills skills_slug_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.skills
    ADD CONSTRAINT skills_slug_unique UNIQUE (slug);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: weapons weapons_pkey; Type: CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.weapons
    ADD CONSTRAINT weapons_pkey PRIMARY KEY (id);


--
-- Name: equipables_equipable_type_equipable_id_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX equipables_equipable_type_equipable_id_index ON public.equipables USING btree (equipable_type, equipable_id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: messages_sender_id_receiver_id_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX messages_sender_id_receiver_id_index ON public.messages USING btree (sender_id, receiver_id);


--
-- Name: pulse_aggregates_period_bucket_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_aggregates_period_bucket_index ON public.pulse_aggregates USING btree (period, bucket);


--
-- Name: pulse_aggregates_period_type_aggregate_bucket_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_aggregates_period_type_aggregate_bucket_index ON public.pulse_aggregates USING btree (period, type, aggregate, bucket);


--
-- Name: pulse_aggregates_type_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_aggregates_type_index ON public.pulse_aggregates USING btree (type);


--
-- Name: pulse_entries_key_hash_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_entries_key_hash_index ON public.pulse_entries USING btree (key_hash);


--
-- Name: pulse_entries_timestamp_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_entries_timestamp_index ON public.pulse_entries USING btree ("timestamp");


--
-- Name: pulse_entries_timestamp_type_key_hash_value_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_entries_timestamp_type_key_hash_value_index ON public.pulse_entries USING btree ("timestamp", type, key_hash, value);


--
-- Name: pulse_entries_type_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_entries_type_index ON public.pulse_entries USING btree (type);


--
-- Name: pulse_values_timestamp_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_values_timestamp_index ON public.pulse_values USING btree ("timestamp");


--
-- Name: pulse_values_type_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX pulse_values_type_index ON public.pulse_values USING btree (type);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: cthulhu
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: character_skill character_skill_character_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.character_skill
    ADD CONSTRAINT character_skill_character_id_foreign FOREIGN KEY (character_id) REFERENCES public.characters(id) ON DELETE CASCADE;


--
-- Name: character_skill character_skill_skill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.character_skill
    ADD CONSTRAINT character_skill_skill_id_foreign FOREIGN KEY (skill_id) REFERENCES public.skills(id) ON DELETE CASCADE;


--
-- Name: characters characters_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.characters
    ADD CONSTRAINT characters_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: messages messages_receiver_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_receiver_id_foreign FOREIGN KEY (receiver_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: messages messages_sender_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: cthulhu
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_sender_id_foreign FOREIGN KEY (sender_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

