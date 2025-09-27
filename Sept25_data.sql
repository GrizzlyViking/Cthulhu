--
-- PostgreSQL database dump
--

\restrict 5dzvZLti1jZkOUJAnE1K0caivES6XhFnEnL9HuF0jSrUvZhOCW9vnNRx2BPOCP9

-- Dumped from database version 16.10 (Ubuntu 16.10-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.10 (Ubuntu 16.10-0ubuntu0.24.04.1)

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
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.cache (key, value, expiration) FROM stdin;
sebastian@schlossberg-edelmann.com|83.93.235.230:timer	i:1717749904;	1717749904
sebastian@schlossberg-edelmann.com|83.93.235.230	i:1;	1717749904
sebastian@edelmann.co.uk|80.62.55.166:timer	i:1718360520;	1718360520
sebastian@edelmann.co.uk|80.62.55.166	i:1;	1718360520
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: calendars; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.calendars (id, name, slug, created_at, updated_at) FROM stdin;
1	Ages of Madness	ages-of-madness	2024-07-18 16:09:01	2024-07-18 16:09:01
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, "imageUrl", deleted_at) FROM stdin;
1	Sebastian Edelmann	sebastian@schlossberg-edelmann.com	2024-05-29 08:59:04	$2y$12$ugqkMyfcbUV9DiL8GPvRA.hndCbbvvNBp0lwBz/etFfXnO763OnEO	y30n4ghNUPpRp4g2sS4FErYHnboe5HVTOpABUTiuOC9cAgjzfWRU8OR1LL8D	2024-05-29 08:59:04	2024-05-29 08:59:04	player	\N	\N
4	Toke	toke@wannarock.dk	\N	$2y$12$kwUOGDMq8wIzYmQnzcTnTOfLe2Dk.w4GLxk0gR0N.R1YCkw5mvtc2	\N	2024-06-08 19:19:39	2024-06-08 19:19:39	player	\N	\N
2	Natalia Schlossberg	natalia@edelmann.co.uk	\N	$2y$12$3TheGIX8ilAr98KsvC2r2OANLaTLk4T/XEJu2fdCYa1ZdNR.90UoG	N4gwnJjLfKzyhr0y6C6XfXBNnrzG2aJkitv5YHjRPhhQOlGZJAQd0L4dSx3j	2024-06-06 13:11:22	2024-06-06 13:11:22	player	\N	\N
3	Jesper Juhl	jesjuhdk@gmail.com	\N	$2y$12$Evt1MEOAM/uKsO2wcl7uM.Ev9nR1S/r6CDcKK.RcGqsQONqkW1vHi	u8RO8h1Ad5yL6aRL2EbQ5PN0VFFRdAVULdeI3a5hI7MxIS19xGjk5nIrWcqe	2024-06-08 19:08:09	2024-12-15 10:59:04	Keeper of Arcane Lore	\N	\N
5	benj	test1234@gmail.com	\N	$2y$12$sNnT3kZnSpzqp1FwgufMmePxC0UwrmtPNbOR45qoSkAH45AJ.Xc1S	\N	2025-08-28 08:34:42	2025-08-28 08:34:42	player	\N	\N
\.


--
-- Data for Name: characters; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.characters (id, name, slug, user_id, occupation, residence, birthplace, age, gender, strength, dexterity, intelligence, constitution, appearance, power, size, education, move_rate, temporary_insanity, indefinite_insanity, major_wound, unconscious, dying, hit_points, sanity, luck, magic_points, dodge, build, damage_bonus, created_at, updated_at, avatar, deleted_at) FROM stdin;
2	Testy McTestFace	testy-mctestface	1	Tester	TestVile	TestVile	30	Other	50	50	50	50	50	50	50	50	0	f	f	f	f	f	20	50	50	14	0	0	0	2024-06-03 19:58:37	2024-06-03 19:59:48	\N	\N
6	Otto Schneider	otto-schneider	3	Soldat	Tyskland	Ukendt	28	Male	50	70	75	70	60	55	65	75	0	f	f	f	f	f	13	55	15	11	0	0	0	2025-02-19 17:38:07	2025-02-19 18:01:38	\N	\N
5	Henry Mather	henry-mather	3	Farmer	Salem	Birmingham, Alabama	34	Male	70	65	75	75	50	55	70	75	0	f	f	f	f	f	11	42	7	11	0	0	0	2024-12-15 11:50:06	2024-12-15 14:45:09	avatars/henry-mather/NZVn2jTFAdxduWLChY1ArcflacAHYhmBzHvRZDuS.jpg	\N
4	Jonathan Lien	jonathan-lien	3	Arkæolog	New York	Arkham	67	Male	45	65	75	35	15	55	80	75	0	f	f	f	f	f	0	0	0	0	0	0	0	2024-06-08 19:10:40	2024-06-08 19:13:50	avatars/jonathan-lien/J1FXZKbjioPMLL4E1Yoy9LUoXPhtvuXGib5GOzAH.jpg	\N
3	Maddam Curry	maddam-curry	1	Scientist of food	Paris	Poland	50	Female	40	80	80	70	70	70	30	50	0	f	f	f	f	f	40	60	199	14	0	0	0	2024-06-06 13:12:12	2025-03-16 14:52:01	avatars/maddam-curry/9rMC18RgSVjciUvzXFCOsVx7LtKFwZBbwOVJlECy.png	\N
1	George Bartolemew	george-bartolemew	1	Doctor of Medicine	Boston Massachusetts	Boston Massachusetts	29	Male	50	80	75	70	55	40	60	82	0	f	f	f	f	f	12	40	62	8	30	0	0	2024-05-31 10:07:28	2025-07-02 19:10:40	avatars/george-bartolemew/lhgHhCHk7qvGmZSxEc3QNE9xx42h4PYjTMViLUAI.png	\N
\.


--
-- Data for Name: skills; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.skills (id, slug, display_name, starting_value, description, order_by) FROM stdin;
1	accounting	Accounting	5	\N	0
2	anthropology	Anthropology	1	\N	1
3	appraise	Appraise	5	\N	2
4	archeology	Archeology	1	\N	3
5	art_crafts	Art & Crafts	5	\N	4
6	charm	Charm	15	\N	5
7	climb	Climb	20	\N	6
8	credit_rating	Credit_rating	0	\N	7
9	cthulhu_mythos	Cthulhu_mythos	0	\N	8
10	disguise	Disguise	5	\N	9
11	dodge	Dodge	0	\N	10
12	drive_auto	Drive Auto	20	\N	11
13	electric_repair	Electric Repair	10	\N	12
14	fast_talking	Fast Talking	20	\N	13
15	fighting	Fighting	25	\N	14
16	firearms_handgun	Firearms Handgun	20	\N	15
17	firearms-rifle	Firearms (Rifle)	20	\N	16
18	firearms-shotgun	Firearms (Shotgun)	20	\N	17
19	firearms-bow	Firearms (Bow)	20	\N	18
20	fighting-whip	Fighting (Whip)	20	\N	19
21	fighting-brawl	Fighting (Brawl)	20	\N	20
22	fighting-mg	Fighting (MG)	20	\N	21
23	fighting-axe	Fighting (Axe)	20	\N	22
24	fighting-garrote	Fighting (Garrote)	20	\N	23
25	fighting-smg	Firearms (SMG)	20	\N	24
26	fighting-flail	Fighting (Flail)	20	\N	25
27	fighting-spear	Fighting (Spear)	20	\N	26
28	first_aid	First_aid	30	\N	27
29	history	History	5	\N	28
30	intimidate	Intimidate	15	\N	29
31	jump	Jump	20	\N	30
32	language_other	Language other	1	\N	31
33	language_own	Language own	0	\N	32
34	law	Law	5	\N	33
35	library_use	Library_use	20	\N	34
36	listen	Listen	20	\N	35
37	locksmith	Locksmith	1	\N	36
38	mech_repair	Mech. Repair	1	\N	37
39	medicine	Medicine	1	\N	38
40	natural_world	Natural_world	1	\N	39
41	navigate	Navigate	10	\N	40
42	occult	Occult	10	\N	41
43	Op_hv_machine	Op_hv_machine	1	\N	42
44	persuade	Persuade	10	\N	43
45	pilot	Pilot	1	\N	44
46	psychology	Psychology	10	\N	45
47	psychoanalysis	Psychoanalysis	1	\N	46
48	ride	Ride	5	\N	47
49	science_skill	Science Skill	1	\N	48
50	sleight_of_hand	Sleight of Hand	10	\N	49
52	stealth	Stealth	20	\N	51
53	survival	Survival	10	\N	52
54	swim	Swim	10	\N	53
55	throw	Throw	20	\N	54
56	track	Track	10	\N	55
57	latin	Latin	0	\N	0
58	biology	Biology	0	\N	0
59	pharmacy	Pharmacy	0	\N	0
60	firearms-handgun	Firearms (Handgun)	20	\N	0
51	spot-hidden	Spot Hidden	25	\N	50
61	science-geology	Science Geology	10	\N	0
63	language	Language: ??	25	\N	0
\.


--
-- Data for Name: character_skill; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.character_skill (character_id, skill_id, value, experience, "order") FROM stdin;
1	1	5	0	0
1	3	5	0	2
1	6	15	0	5
1	12	20	0	11
1	13	10	0	12
1	15	25	0	14
1	16	20	0	15
1	19	20	0	18
1	20	20	0	19
1	22	20	0	21
1	24	20	0	23
1	25	20	0	24
1	26	20	0	25
1	27	20	0	26
1	29	5	0	28
1	30	15	0	29
1	31	20	0	30
1	32	1	0	31
1	41	10	0	40
1	43	1	0	42
1	44	10	0	43
1	45	1	0	44
1	48	5	0	47
1	49	1	0	48
1	50	10	0	49
1	52	20	0	51
1	53	10	0	52
1	54	10	0	53
1	55	20	0	54
1	56	10	0	55
1	14	5	0	13
1	21	25	0	20
1	18	25	0	17
1	57	61	0	0
1	8	30	0	7
1	33	82	0	32
1	34	40	0	33
1	35	50	0	34
1	36	55	0	35
1	37	41	0	36
1	38	10	0	37
1	39	76	0	38
1	40	10	0	39
1	42	15	0	41
1	46	55	0	45
1	47	16	0	46
1	11	30	0	10
1	59	54	0	0
1	7	20	0	6
1	4	1	0	3
1	5	5	0	4
1	10	5	0	9
1	23	20	0	22
1	28	76	1	27
1	58	61	0	0
1	2	1	0	1
2	1	5	0	0
2	2	1	0	1
2	3	5	0	2
2	4	1	0	3
2	5	5	0	4
2	6	15	0	5
2	7	20	0	6
2	8	0	0	7
2	9	0	0	8
2	10	5	0	9
2	11	0	0	10
2	12	20	0	11
2	13	10	0	12
2	14	20	0	13
2	15	25	0	14
2	16	20	0	15
2	17	20	0	16
2	18	20	0	17
2	19	20	0	18
2	20	20	0	19
2	21	20	0	20
2	22	20	0	21
2	23	20	0	22
2	24	20	0	23
2	25	20	0	24
2	26	20	0	25
2	27	20	0	26
2	28	30	0	27
2	29	5	0	28
2	30	15	0	29
2	31	20	0	30
2	32	1	0	31
2	33	0	0	32
2	34	5	0	33
2	35	20	0	34
2	36	20	0	35
2	37	1	0	36
2	38	1	0	37
2	39	1	0	38
2	40	1	0	39
2	41	10	0	40
2	42	10	0	41
1	17	45	2	16
2	43	1	0	42
2	44	10	0	43
2	45	1	0	44
2	46	10	0	45
2	47	1	0	46
2	48	5	0	47
2	49	1	0	48
2	50	10	0	49
2	51	25	0	50
2	52	20	0	51
2	53	10	0	52
2	54	10	0	53
2	55	20	0	54
2	56	10	0	55
2	57	0	0	0
2	58	0	0	0
2	59	0	0	0
3	1	5	0	0
3	2	1	0	1
3	3	5	0	2
3	4	1	0	3
3	5	5	0	4
3	6	15	0	5
3	7	20	0	6
3	8	0	0	7
3	9	0	0	8
3	10	5	0	9
3	11	0	0	10
3	12	20	0	11
3	13	10	0	12
3	14	20	0	13
3	15	25	0	14
3	16	20	0	15
3	17	20	0	16
3	18	20	0	17
3	19	20	0	18
3	20	20	0	19
3	21	20	0	20
3	22	20	0	21
3	23	20	0	22
3	24	20	0	23
3	25	20	0	24
3	26	20	0	25
3	27	20	0	26
3	28	30	0	27
3	29	5	0	28
3	30	15	0	29
3	31	20	0	30
3	32	1	0	31
3	33	0	0	32
3	34	5	0	33
3	35	20	0	34
3	36	20	0	35
3	37	1	0	36
3	38	1	0	37
3	39	1	0	38
3	40	1	0	39
3	41	10	0	40
3	42	10	0	41
3	43	1	0	42
3	44	10	0	43
3	45	1	0	44
3	46	10	0	45
3	47	1	0	46
3	48	5	0	47
3	49	1	0	48
3	50	10	0	49
3	51	25	0	50
3	52	20	0	51
3	53	10	0	52
3	54	10	0	53
3	55	20	0	54
3	56	10	0	55
3	57	0	0	0
3	58	0	0	0
3	59	0	0	0
3	60	0	0	0
4	1	5	0	0
4	5	5	0	4
4	6	15	0	5
4	7	20	0	6
4	9	0	0	8
4	10	5	0	9
4	12	20	0	11
4	13	10	0	12
4	14	20	0	13
4	15	25	0	14
4	16	20	0	15
4	17	20	0	16
4	18	20	0	17
4	19	20	0	18
4	20	20	0	19
4	22	20	0	21
4	23	20	0	22
4	24	20	0	23
4	25	20	0	24
4	26	20	0	25
4	27	20	0	26
4	29	5	0	28
4	30	15	0	29
4	31	20	0	30
4	32	1	0	31
4	33	0	0	32
4	34	5	0	33
4	36	20	0	35
4	37	1	0	36
4	38	1	0	37
4	39	1	0	38
4	40	1	0	39
4	43	1	0	42
4	46	10	0	45
4	48	5	0	47
4	50	10	0	49
4	52	20	0	51
4	53	10	0	52
4	55	20	0	54
4	56	10	0	55
4	57	0	0	0
4	58	0	0	0
4	59	0	0	0
4	4	73	0	3
4	2	26	0	1
4	60	25	0	0
4	3	20	0	2
4	11	32	0	10
4	28	35	0	27
4	8	30	0	7
4	21	25	0	20
4	35	65	0	34
4	41	45	0	40
4	42	35	0	41
4	45	31	0	44
4	44	35	0	43
4	54	20	0	53
4	49	36	0	48
4	51	40	0	50
4	61	36	0	0
5	1	5	0	0
4	47	1	0	46
5	3	5	0	2
5	5	5	0	4
5	6	15	0	5
5	7	20	0	6
5	10	5	0	9
5	12	20	0	11
5	13	10	0	12
5	14	20	0	13
5	15	25	0	14
5	16	20	0	15
5	4	73	0	3
5	8	30	0	7
5	9	3	0	8
5	11	32	0	10
5	17	25	0	16
5	19	20	0	18
5	20	20	0	19
5	22	20	0	21
5	23	20	0	22
5	24	20	0	23
5	25	20	0	24
5	26	20	0	25
5	27	20	0	26
5	30	15	0	29
5	31	20	0	30
5	34	5	0	33
5	37	1	0	36
5	38	1	0	37
5	39	1	0	38
5	40	1	0	39
5	43	1	0	42
5	45	1	0	44
5	46	10	0	45
5	48	5	0	47
5	49	1	0	48
5	50	10	0	49
5	52	20	0	51
5	53	10	0	52
5	55	20	0	54
5	56	10	0	55
5	57	0	0	0
5	58	0	0	0
5	59	0	0	0
5	60	20	0	0
5	2	36	0	1
6	8	30	0	7
6	9	5	0	8
5	18	25	0	17
5	28	35	0	27
5	29	50	0	28
6	1	5	0	0
5	32	46	0	31
5	33	75	0	32
6	11	32	0	10
5	35	65	0	34
6	23	25	0	22
5	36	24	0	35
6	21	25	0	20
5	41	45	0	40
6	26	25	0	25
5	42	45	0	41
6	24	25	0	23
5	44	38	0	43
5	47	17	0	46
6	22	25	0	21
6	25	25	0	24
5	61	36	0	0
5	51	48	0	50
5	54	20	0	53
6	27	25	0	26
5	21	25	0	20
6	5	5	0	4
6	6	15	0	5
6	7	20	0	6
6	10	5	0	9
6	12	20	0	11
6	13	10	0	12
6	14	20	0	13
6	15	25	0	14
6	19	20	0	18
6	20	20	0	19
6	30	15	0	29
6	31	20	0	30
6	34	5	0	33
6	35	20	0	34
6	36	20	0	35
6	37	1	0	36
6	38	1	0	37
6	39	1	0	38
6	40	1	0	39
6	41	10	0	40
6	42	10	0	41
6	43	1	0	42
6	44	10	0	43
6	45	1	0	44
6	46	10	0	45
6	47	1	0	46
6	48	5	0	47
6	49	1	0	48
6	50	10	0	49
6	52	20	0	51
6	53	10	0	52
6	54	10	0	53
6	55	20	0	54
6	56	10	0	55
6	57	0	0	0
6	58	0	0	0
6	59	0	0	0
6	51	25	0	50
6	61	10	0	0
6	60	25	0	0
6	3	20	0	2
6	17	35	0	16
6	2	36	0	1
6	4	73	0	3
6	18	35	0	17
6	16	35	0	15
6	28	35	0	27
6	29	50	0	28
6	32	20	0	31
6	33	75	0	32
1	51	45	3	50
1	9	10	0	8
1	63	25	0	0
\.


--
-- Data for Name: equipables; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.equipables (id, character_id, equipable_type, equipable_id, ammo, created_at, updated_at) FROM stdin;
2	2	App\\Models\\Weapon	9	0	\N	\N
3	3	App\\Models\\Weapon	21	0	\N	\N
6	4	App\\Models\\Weapon	12	0	\N	\N
7	4	App\\Models\\Weapon	19	0	\N	\N
8	4	App\\Models\\Weapon	21	0	\N	\N
9	1	App\\Models\\Weapon	11	0	\N	\N
10	1	App\\Models\\Weapon	1	0	\N	\N
11	5	App\\Models\\Weapon	13	0	\N	\N
12	5	App\\Models\\Weapon	19	0	\N	\N
13	5	App\\Models\\Weapon	27	0	\N	\N
14	5	App\\Models\\Weapon	32	0	\N	\N
\.


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.events (id, user_id, summary, description, type, calendar_id, start_at, end_at, created_at, updated_at) FROM stdin;
1	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-02 17:00:00	2024-09-02 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
2	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-03 17:00:00	2024-09-03 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
3	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-08 17:00:00	2024-09-08 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
4	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-09 17:00:00	2024-09-09 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
5	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-10 17:00:00	2024-09-10 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
6	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-15 17:00:00	2024-09-15 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
7	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-16 17:00:00	2024-09-16 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
8	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-23 17:00:00	2024-09-23 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
9	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-24 17:00:00	2024-09-24 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
10	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-29 17:00:00	2024-09-29 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
11	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-09-30 17:00:00	2024-09-30 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
12	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-07 17:00:00	2024-10-07 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
13	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-08 17:00:00	2024-10-08 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
14	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-01 17:00:00	2024-10-01 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
15	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-14 17:00:00	2024-10-14 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
16	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-15 17:00:00	2024-10-15 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
17	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-20 17:00:00	2024-10-20 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
18	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-21 17:00:00	2024-10-21 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
19	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-22 17:00:00	2024-10-22 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
20	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-27 17:00:00	2024-10-27 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
21	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-28 17:00:00	2024-10-28 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
22	3	AoM Spil	This is a date, suggested by Jesper Juhl	suggestion	1	2024-10-29 17:00:00	2024-10-29 22:00:00	2024-07-20 11:47:38	2024-07-20 11:47:38
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
1	2	1	f	Hej, håber i er ok?	2024-06-13 19:15:05	2024-06-13 19:15:05
2	2	3	f	Hej, håber i er ok?	2024-06-13 19:15:05	2024-06-13 19:15:05
3	2	4	f	Hej, håber i er ok?	2024-06-13 19:15:05	2024-06-13 19:15:05
5	2	3	f	This is a test	2024-06-14 10:07:28	2024-06-14 10:07:28
6	2	4	f	This is a test	2024-06-14 10:07:28	2024-06-14 10:07:28
7	2	2	f	test	2024-06-14 10:07:51	2024-06-14 10:07:51
4	2	2	t	This is a test	2024-06-14 10:07:28	2024-06-14 10:10:24
8	2	3	f	This is another test	2024-06-14 10:10:43	2024-06-14 10:10:43
9	2	4	f	This is another test	2024-06-14 10:10:43	2024-06-14 10:10:43
10	2	2	t	This is another test	2024-06-14 10:10:43	2024-06-14 10:10:51
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
11	2024_06_16_072005_create_calendars_table	1
12	2024_06_23_074203_add_soft_delete_to_characters	1
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
91	1718359200	1440	processing	database:default	count	20.00	\N
92	1718357760	10080	processing	database:default	count	20.00	\N
61	1718359620	60	queued	database:default	count	4.00	\N
97	1718359740	60	processed	database:default	count	14.00	\N
257	1718359800	60	processed	database:default	count	6.00	\N
98	1718359560	360	processed	database:default	count	20.00	\N
65	1718359620	60	user_job	2	count	4.00	\N
99	1718359200	1440	processed	database:default	count	20.00	\N
100	1718357760	10080	processed	database:default	count	20.00	\N
229	1718359800	60	user_request	2	count	4.00	\N
45	1718359620	60	user_request	2	count	7.00	\N
349	1718360340	60	user_request	2	count	4.00	\N
1	1718306040	60	user_request	2	count	5.00	\N
47	1718359200	1440	user_request	2	count	24.00	\N
325	1718360220	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360258.00	\N
25	1718306100	60	queued	database:default	count	3.00	\N
26	1718305920	360	queued	database:default	count	3.00	\N
27	1718305920	1440	queued	database:default	count	3.00	\N
28	1718297280	10080	queued	database:default	count	3.00	\N
29	1718306100	60	user_job	2	count	3.00	\N
30	1718305920	360	user_job	2	count	3.00	\N
31	1718305920	1440	user_job	2	count	3.00	\N
32	1718297280	10080	user_job	2	count	3.00	\N
313	1718359860	60	user_request	2	count	1.00	\N
46	1718359560	360	user_request	2	count	12.00	\N
21	1718306100	60	user_request	2	count	3.00	\N
41	1718306160	60	user_request	2	count	1.00	\N
2	1718305920	360	user_request	2	count	9.00	\N
3	1718305920	1440	user_request	2	count	9.00	\N
4	1718297280	10080	user_request	2	count	9.00	\N
326	1718359920	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360258.00	\N
323	1718359200	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	3.00	\N
405	1718360580	60	user_request	1	count	4.00	\N
390	1718360280	360	user_request	1	count	8.00	\N
324	1718357760	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	3.00	\N
237	1718359800	60	queued	database:default	count	6.00	\N
93	1718359740	60	queued	database:default	count	7.00	\N
62	1718359560	360	queued	database:default	count	17.00	\N
63	1718359200	1440	queued	database:default	count	17.00	\N
64	1718357760	10080	queued	database:default	count	17.00	\N
89	1718359740	60	processing	database:default	count	14.00	\N
391	1718359200	1440	user_request	1	count	8.00	\N
241	1718359800	60	user_job	2	count	3.00	\N
66	1718359560	360	user_job	2	count	7.00	\N
67	1718359200	1440	user_job	2	count	7.00	\N
68	1718357760	10080	user_job	2	count	7.00	\N
317	1718360220	60	user_request	2	count	4.00	\N
318	1718359920	360	user_request	2	count	4.00	\N
365	1718360340	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360372.00	\N
366	1718360280	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360372.00	\N
321	1718360220	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	2.00	\N
322	1718359920	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	2.00	\N
373	1718360400	60	user_request	2	count	4.00	\N
48	1718357760	10080	user_request	2	count	24.00	\N
249	1718359800	60	processing	database:default	count	6.00	\N
90	1718359560	360	processing	database:default	count	20.00	\N
350	1718360280	360	user_request	2	count	8.00	\N
361	1718360340	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	1.00	\N
362	1718360280	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	count	1.00	\N
327	1718359200	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360372.00	\N
328	1718357760	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	max	1718360372.00	\N
389	1718360460	60	user_request	1	count	3.00	\N
421	1718361660	60	user_request	1	count	5.00	\N
423	1718360640	1440	user_request	1	count	12.00	\N
401	1718360520	60	user_request	1	count	1.00	\N
392	1718357760	10080	user_request	1	count	25.00	\N
422	1718361360	360	user_request	1	count	5.00	\N
437	1718361660	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	1.00	\N
438	1718361360	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	1.00	\N
439	1718360640	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	1.00	\N
2971	1741546080	1440	user_request	1	count	1.00	\N
440	1718357760	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	1.00	\N
441	1718361660	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718361703.00	\N
442	1718361360	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718361703.00	\N
443	1718360640	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718361703.00	\N
444	1718357760	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718361703.00	\N
519	1718448480	1440	user_request	1	count	4.00	\N
552	1718519040	10080	user_request	1	count	2.00	\N
449	1718361720	60	user_request	1	count	3.00	\N
533	1718456280	60	user_request	1	count	1.00	\N
534	1718456040	360	user_request	1	count	1.00	\N
535	1718455680	1440	user_request	1	count	1.00	\N
520	1718448480	10080	user_request	1	count	5.00	\N
537	1718459760	60	user_request	1	count	1.00	\N
538	1718459640	360	user_request	1	count	1.00	\N
461	1718361840	60	user_request	1	count	4.00	\N
450	1718361720	360	user_request	1	count	7.00	\N
477	1718363400	60	user_request	1	count	2.00	\N
478	1718363160	360	user_request	1	count	2.00	\N
479	1718362080	1440	user_request	1	count	2.00	\N
485	1718364540	60	user_request	1	count	1.00	\N
486	1718364240	360	user_request	1	count	1.00	\N
487	1718363520	1440	user_request	1	count	1.00	\N
489	1718365440	60	user_request	1	count	1.00	\N
490	1718365320	360	user_request	1	count	1.00	\N
491	1718364960	1440	user_request	1	count	1.00	\N
493	1718366460	60	user_request	1	count	1.00	\N
494	1718366400	360	user_request	1	count	1.00	\N
495	1718366400	1440	user_request	1	count	1.00	\N
497	1718372460	60	user_request	1	count	1.00	\N
498	1718372160	360	user_request	1	count	1.00	\N
501	1718372880	60	user_request	1	count	1.00	\N
502	1718372880	360	user_request	1	count	1.00	\N
499	1718372160	1440	user_request	1	count	2.00	\N
539	1718458560	1440	user_request	1	count	1.00	\N
505	1718373840	60	user_request	1	count	1.00	\N
506	1718373600	360	user_request	1	count	1.00	\N
507	1718373600	1440	user_request	1	count	1.00	\N
509	1718377860	60	user_request	1	count	1.00	\N
510	1718377560	360	user_request	1	count	1.00	\N
511	1718376480	1440	user_request	1	count	1.00	\N
500	1718367840	10080	user_request	1	count	4.00	\N
513	1718378700	60	user_request	1	count	1.00	\N
514	1718378640	360	user_request	1	count	1.00	\N
515	1718377920	1440	user_request	1	count	1.00	\N
516	1718377920	10080	user_request	1	count	1.00	\N
541	1718465820	60	user_request	1	count	1.00	\N
542	1718465760	360	user_request	1	count	1.00	\N
543	1718465760	1440	user_request	1	count	1.00	\N
540	1718458560	10080	user_request	1	count	2.00	\N
517	1718449440	60	user_request	1	count	3.00	\N
518	1718449200	360	user_request	1	count	3.00	\N
557	1718552100	60	user_request	1	count	1.00	\N
558	1718551800	360	user_request	1	count	1.00	\N
529	1718449740	60	user_request	1	count	1.00	\N
530	1718449560	360	user_request	1	count	1.00	\N
545	1718475960	60	user_request	1	count	1.00	\N
546	1718475840	360	user_request	1	count	1.00	\N
547	1718475840	1440	user_request	1	count	1.00	\N
548	1718468640	10080	user_request	1	count	1.00	\N
549	1718519340	60	user_request	1	count	1.00	\N
550	1718519040	360	user_request	1	count	1.00	\N
551	1718519040	1440	user_request	1	count	1.00	\N
553	1718521740	60	user_request	1	count	1.00	\N
554	1718521560	360	user_request	1	count	1.00	\N
555	1718520480	1440	user_request	1	count	1.00	\N
559	1718550720	1440	user_request	1	count	1.00	\N
561	1718552700	60	user_request	1	count	2.00	\N
562	1718552520	360	user_request	1	count	2.00	\N
563	1718552160	1440	user_request	1	count	2.00	\N
560	1718549280	10080	user_request	1	count	3.00	\N
569	1718565120	60	user_request	1	count	1.00	\N
570	1718565120	360	user_request	1	count	1.00	\N
571	1718565120	1440	user_request	1	count	1.00	\N
572	1718559360	10080	user_request	1	count	1.00	\N
573	1718637300	60	user_request	1	count	1.00	\N
574	1718637120	360	user_request	1	count	1.00	\N
575	1718637120	1440	user_request	1	count	1.00	\N
576	1718629920	10080	user_request	1	count	1.00	\N
577	1718648400	60	user_request	1	count	1.00	\N
578	1718648280	360	user_request	1	count	1.00	\N
579	1718647200	1440	user_request	1	count	1.00	\N
580	1718640000	10080	user_request	1	count	1.00	\N
581	1718780220	60	user_request	1	count	1.00	\N
582	1718780040	360	user_request	1	count	1.00	\N
583	1718779680	1440	user_request	1	count	1.00	\N
584	1718771040	10080	user_request	1	count	1.00	\N
585	1718787360	60	user_request	1	count	1.00	\N
586	1718787240	360	user_request	1	count	1.00	\N
587	1718786880	1440	user_request	1	count	1.00	\N
589	1718789580	60	user_request	1	count	1.00	\N
590	1718789400	360	user_request	1	count	1.00	\N
591	1718788320	1440	user_request	1	count	1.00	\N
588	1718781120	10080	user_request	1	count	2.00	\N
593	1718798040	60	user_request	1	count	2.00	\N
594	1718798040	360	user_request	1	count	2.00	\N
595	1718796960	1440	user_request	1	count	2.00	\N
666	1718830800	360	user_request	1	count	2.00	\N
601	1718800920	60	user_request	1	count	1.00	\N
665	1718830800	60	user_request	1	count	1.00	\N
605	1718801220	60	user_request	1	count	1.00	\N
602	1718800920	360	user_request	1	count	2.00	\N
603	1718799840	1440	user_request	1	count	2.00	\N
596	1718791200	10080	user_request	1	count	4.00	\N
609	1718801820	60	user_request	1	count	1.00	\N
610	1718801640	360	user_request	1	count	1.00	\N
613	1718802060	60	user_request	1	count	1.00	\N
614	1718802000	360	user_request	1	count	1.00	\N
611	1718801280	1440	user_request	1	count	2.00	\N
645	1718807820	60	user_request	1	count	1.00	\N
617	1718803500	60	user_request	1	count	1.00	\N
618	1718803440	360	user_request	1	count	1.00	\N
619	1718802720	1440	user_request	1	count	1.00	\N
646	1718807760	360	user_request	1	count	1.00	\N
621	1718804280	60	user_request	1	count	1.00	\N
622	1718804160	360	user_request	1	count	1.00	\N
623	1718804160	1440	user_request	1	count	1.00	\N
625	1718806380	60	user_request	1	count	1.00	\N
626	1718806320	360	user_request	1	count	1.00	\N
627	1718805600	1440	user_request	1	count	1.00	\N
663	1718830080	1440	user_request	1	count	4.00	\N
629	1718807100	60	user_request	1	count	1.00	\N
649	1718808360	60	user_request	1	count	1.00	\N
633	1718807220	60	user_request	1	count	1.00	\N
650	1718808120	360	user_request	1	count	1.00	\N
631	1718807040	1440	user_request	1	count	6.00	\N
612	1718801280	10080	user_request	1	count	11.00	\N
637	1718807280	60	user_request	1	count	1.00	\N
630	1718807040	360	user_request	1	count	3.00	\N
660	1718821440	10080	user_request	1	count	5.00	\N
669	1718831040	60	user_request	1	count	1.00	\N
641	1718807580	60	user_request	1	count	1.00	\N
642	1718807400	360	user_request	1	count	1.00	\N
653	1718818980	60	user_request	1	count	1.00	\N
654	1718818920	360	user_request	1	count	1.00	\N
655	1718818560	1440	user_request	1	count	1.00	\N
656	1718811360	10080	user_request	1	count	1.00	\N
657	1718822940	60	user_request	1	count	1.00	\N
658	1718822880	360	user_request	1	count	1.00	\N
659	1718822880	1440	user_request	1	count	1.00	\N
661	1718830740	60	user_request	1	count	1.00	\N
662	1718830440	360	user_request	1	count	1.00	\N
673	1718831340	60	user_request	1	count	1.00	\N
674	1718831160	360	user_request	1	count	1.00	\N
677	1718832600	60	user_request	1	count	1.00	\N
678	1718832600	360	user_request	1	count	1.00	\N
679	1718831520	1440	user_request	1	count	1.00	\N
680	1718831520	10080	user_request	1	count	1.00	\N
681	1718896080	60	user_request	1	count	1.00	\N
682	1718895960	360	user_request	1	count	1.00	\N
683	1718894880	1440	user_request	1	count	1.00	\N
685	1718898780	60	user_request	1	count	1.00	\N
686	1718898480	360	user_request	1	count	1.00	\N
687	1718897760	1440	user_request	1	count	1.00	\N
891	1719420480	1440	user_request	1	count	2.00	\N
689	1718899320	60	user_request	1	count	1.00	\N
690	1718899200	360	user_request	1	count	1.00	\N
899	1719421920	1440	user_request	1	count	4.00	\N
693	1718899620	60	user_request	1	count	1.00	\N
694	1718899560	360	user_request	1	count	1.00	\N
738	1718977680	360	user_request	1	count	10.00	\N
739	1718976960	1440	user_request	1	count	10.00	\N
697	1718900160	60	user_request	1	count	1.00	\N
698	1718899920	360	user_request	1	count	1.00	\N
691	1718899200	1440	user_request	1	count	3.00	\N
684	1718892000	10080	user_request	1	count	5.00	\N
701	1718908380	60	user_request	1	count	1.00	\N
702	1718908200	360	user_request	1	count	1.00	\N
703	1718907840	1440	user_request	1	count	1.00	\N
704	1718902080	10080	user_request	1	count	1.00	\N
705	1718912640	60	user_request	1	count	1.00	\N
706	1718912520	360	user_request	1	count	1.00	\N
707	1718912160	1440	user_request	1	count	1.00	\N
753	1718977740	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	2.00	\N
709	1718915400	60	user_request	1	count	2.00	\N
710	1718915400	360	user_request	1	count	2.00	\N
754	1718977680	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	2.00	\N
755	1718976960	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	2.00	\N
717	1718916000	60	user_request	1	count	1.00	\N
718	1718915760	360	user_request	1	count	1.00	\N
756	1718972640	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	count	2.00	\N
757	1718977740	60	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718977782.00	\N
721	1718916360	60	user_request	1	count	1.00	\N
722	1718916120	360	user_request	1	count	1.00	\N
711	1718915040	1440	user_request	1	count	4.00	\N
749	1718977740	60	user_request	1	count	3.00	\N
725	1718916480	60	user_request	1	count	1.00	\N
726	1718916480	360	user_request	1	count	1.00	\N
740	1718972640	10080	user_request	1	count	10.00	\N
729	1718917200	60	user_request	1	count	1.00	\N
730	1718917200	360	user_request	1	count	1.00	\N
727	1718916480	1440	user_request	1	count	2.00	\N
860	1719154080	10080	user_request	1	count	1.00	\N
733	1718920920	60	user_request	1	count	1.00	\N
734	1718920800	360	user_request	1	count	1.00	\N
735	1718920800	1440	user_request	1	count	1.00	\N
708	1718912160	10080	user_request	1	count	8.00	\N
861	1719211920	60	user_request	1	count	1.00	\N
862	1719211680	360	user_request	1	count	1.00	\N
863	1719211680	1440	user_request	1	count	1.00	\N
737	1718977680	60	user_request	1	count	3.00	\N
897	1719422760	60	user_request	1	count	1.00	\N
898	1719422640	360	user_request	1	count	1.00	\N
877	1719385020	60	user_request	1	count	3.00	\N
865	1719213180	60	user_request	1	count	1.00	\N
866	1719213120	360	user_request	1	count	1.00	\N
867	1719213120	1440	user_request	1	count	1.00	\N
878	1719384840	360	user_request	1	count	3.00	\N
864	1719204480	10080	user_request	1	count	2.00	\N
869	1719234780	60	user_request	1	count	1.00	\N
870	1719234720	360	user_request	1	count	1.00	\N
871	1719234720	1440	user_request	1	count	1.00	\N
873	1719242940	60	user_request	1	count	1.00	\N
874	1719242640	360	user_request	1	count	1.00	\N
875	1719241920	1440	user_request	1	count	1.00	\N
872	1719234720	10080	user_request	1	count	2.00	\N
879	1719384480	1440	user_request	1	count	3.00	\N
880	1719375840	10080	user_request	1	count	3.00	\N
889	1719421320	60	user_request	1	count	1.00	\N
890	1719421200	360	user_request	1	count	1.00	\N
893	1719421680	60	user_request	1	count	1.00	\N
894	1719421560	360	user_request	1	count	1.00	\N
901	1719423120	60	user_request	1	count	1.00	\N
758	1718977680	360	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718977782.00	\N
759	1718976960	1440	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718977782.00	\N
760	1718972640	10080	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	max	1718977782.00	\N
777	1718977860	60	user_request	1	count	4.00	\N
793	1718983380	60	user_request	1	count	1.00	\N
794	1718983080	360	user_request	1	count	1.00	\N
795	1718982720	1440	user_request	1	count	1.00	\N
797	1718989860	60	user_request	1	count	1.00	\N
798	1718989560	360	user_request	1	count	1.00	\N
799	1718988480	1440	user_request	1	count	1.00	\N
796	1718982720	10080	user_request	1	count	2.00	\N
801	1719002460	60	user_request	1	count	4.00	\N
802	1719002160	360	user_request	1	count	4.00	\N
803	1719001440	1440	user_request	1	count	4.00	\N
804	1718992800	10080	user_request	1	count	4.00	\N
817	1719002880	60	user_request	1	count	1.00	\N
821	1719003000	60	user_request	1	count	2.00	\N
818	1719002880	360	user_request	1	count	3.00	\N
819	1719002880	1440	user_request	1	count	3.00	\N
829	1719005880	60	user_request	1	count	1.00	\N
830	1719005760	360	user_request	1	count	1.00	\N
833	1719007080	60	user_request	1	count	1.00	\N
834	1719006840	360	user_request	1	count	1.00	\N
831	1719005760	1440	user_request	1	count	2.00	\N
820	1719002880	10080	user_request	1	count	5.00	\N
837	1719036480	60	user_request	1	count	1.00	\N
838	1719036360	360	user_request	1	count	1.00	\N
839	1719036000	1440	user_request	1	count	1.00	\N
841	1719038400	60	user_request	1	count	1.00	\N
842	1719038160	360	user_request	1	count	1.00	\N
843	1719037440	1440	user_request	1	count	1.00	\N
840	1719033120	10080	user_request	1	count	2.00	\N
845	1719060000	60	user_request	1	count	1.00	\N
846	1719059760	360	user_request	1	count	1.00	\N
847	1719059040	1440	user_request	1	count	1.00	\N
848	1719053280	10080	user_request	1	count	1.00	\N
849	1719118020	60	user_request	1	count	1.00	\N
850	1719117720	360	user_request	1	count	1.00	\N
851	1719116640	1440	user_request	1	count	1.00	\N
852	1719113760	10080	user_request	1	count	1.00	\N
853	1719124980	60	user_request	1	count	1.00	\N
854	1719124920	360	user_request	1	count	1.00	\N
855	1719123840	1440	user_request	1	count	1.00	\N
856	1719123840	10080	user_request	1	count	1.00	\N
857	1719161460	60	user_request	1	count	1.00	\N
858	1719161280	360	user_request	1	count	1.00	\N
859	1719161280	1440	user_request	1	count	1.00	\N
905	1719423180	60	user_request	1	count	1.00	\N
1034	1720093680	360	user_request	3	count	2.00	\N
1003	1720092960	1440	user_request	3	count	10.00	\N
909	1719423300	60	user_request	1	count	1.00	\N
902	1719423000	360	user_request	1	count	3.00	\N
973	1719955380	60	user_request	1	count	3.00	\N
913	1719423600	60	user_request	1	count	1.00	\N
914	1719423360	360	user_request	1	count	1.00	\N
974	1719955080	360	user_request	1	count	3.00	\N
917	1719424620	60	user_request	1	count	1.00	\N
918	1719424440	360	user_request	1	count	1.00	\N
915	1719423360	1440	user_request	1	count	2.00	\N
892	1719416160	10080	user_request	1	count	8.00	\N
921	1719463020	60	user_request	1	count	1.00	\N
922	1719462960	360	user_request	1	count	1.00	\N
923	1719462240	1440	user_request	1	count	1.00	\N
925	1719463740	60	user_request	1	count	1.00	\N
975	1719954720	1440	user_request	1	count	3.00	\N
929	1719463860	60	user_request	1	count	1.00	\N
926	1719463680	360	user_request	1	count	2.00	\N
927	1719463680	1440	user_request	1	count	2.00	\N
924	1719456480	10080	user_request	1	count	3.00	\N
933	1719496740	60	user_request	1	count	1.00	\N
934	1719496440	360	user_request	1	count	1.00	\N
935	1719495360	1440	user_request	1	count	1.00	\N
936	1719486720	10080	user_request	1	count	1.00	\N
937	1719522300	60	user_request	1	count	1.00	\N
938	1719522000	360	user_request	1	count	1.00	\N
939	1719521280	1440	user_request	1	count	1.00	\N
940	1719516960	10080	user_request	1	count	1.00	\N
976	1719950400	10080	user_request	1	count	3.00	\N
941	1719679440	60	user_request	1	count	3.00	\N
942	1719679320	360	user_request	1	count	3.00	\N
943	1719678240	1440	user_request	1	count	3.00	\N
944	1719678240	10080	user_request	1	count	3.00	\N
1001	1720093440	60	user_request	3	count	3.00	\N
1004	1720091520	10080	user_request	3	count	10.00	\N
1041	1720616940	60	user_request	1	count	1.00	\N
953	1719722820	60	user_request	1	count	3.00	\N
954	1719722520	360	user_request	1	count	3.00	\N
955	1719721440	1440	user_request	1	count	3.00	\N
1042	1720616760	360	user_request	1	count	1.00	\N
965	1719723780	60	user_request	1	count	1.00	\N
966	1719723600	360	user_request	1	count	1.00	\N
967	1719722880	1440	user_request	1	count	1.00	\N
985	1720069440	60	user_request	1	count	3.00	\N
969	1719724800	60	user_request	1	count	1.00	\N
970	1719724680	360	user_request	1	count	1.00	\N
971	1719724320	1440	user_request	1	count	1.00	\N
956	1719718560	10080	user_request	1	count	5.00	\N
1043	1720615680	1440	user_request	1	count	1.00	\N
986	1720069200	360	user_request	1	count	3.00	\N
987	1720068480	1440	user_request	1	count	3.00	\N
988	1720061280	10080	user_request	1	count	3.00	\N
997	1720075800	60	user_request	1	count	1.00	\N
998	1720075680	360	user_request	1	count	1.00	\N
999	1720075680	1440	user_request	1	count	1.00	\N
1000	1720071360	10080	user_request	1	count	1.00	\N
1013	1720093620	60	user_request	3	count	5.00	\N
1002	1720093320	360	user_request	3	count	8.00	\N
1044	1720615680	10080	user_request	1	count	1.00	\N
1045	1721318820	60	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	count	1.00	\N
1046	1721318760	360	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	count	1.00	\N
1047	1721318400	1440	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	count	1.00	\N
1048	1721311200	10080	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	count	1.00	\N
1049	1721318820	60	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	max	1721318849.00	\N
1050	1721318760	360	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	max	1721318849.00	\N
1051	1721318400	1440	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	max	1721318849.00	\N
1033	1720093680	60	user_request	3	count	2.00	\N
1469	1725022860	60	user_request	3	count	1.00	\N
1052	1721311200	10080	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	max	1721318849.00	\N
1117	1721476020	60	user_request	3	count	3.00	\N
1106	1721475720	360	user_request	3	count	6.00	\N
1341	1723301760	60	user_request	3	count	2.00	\N
1169	1721513100	60	user_request	1	count	4.00	\N
1053	1721318820	60	user_request	1	count	3.00	\N
1158	1721512800	360	user_request	1	count	7.00	\N
1394	1723302000	360	user_request	3	count	6.00	\N
1129	1721476080	60	user_request	3	count	2.00	\N
1065	1721318880	60	user_request	1	count	2.00	\N
1185	1721513940	60	user_request	1	count	1.00	\N
1189	1721514120	60	user_request	1	count	1.00	\N
1073	1721318940	60	user_request	1	count	2.00	\N
1054	1721318760	360	user_request	1	count	7.00	\N
1055	1721318400	1440	user_request	1	count	7.00	\N
1186	1721513880	360	user_request	1	count	2.00	\N
1081	1721320200	60	user_request	1	count	1.00	\N
1082	1721320200	360	user_request	1	count	1.00	\N
1083	1721319840	1440	user_request	1	count	1.00	\N
1056	1721311200	10080	user_request	1	count	8.00	\N
1159	1721512800	1440	user_request	1	count	9.00	\N
1349	1723301820	60	user_request	3	count	1.00	\N
1193	1721514840	60	user_request	1	count	1.00	\N
1194	1721514600	360	user_request	1	count	1.00	\N
1195	1721514240	1440	user_request	1	count	1.00	\N
1325	1723301700	60	user_request	3	count	4.00	\N
1197	1721516580	60	user_request	1	count	1.00	\N
1198	1721516400	360	user_request	1	count	1.00	\N
1199	1721515680	1440	user_request	1	count	1.00	\N
1160	1721512800	10080	user_request	1	count	11.00	\N
1201	1721551980	60	user_request	1	count	1.00	\N
1137	1721476260	60	user_request	3	count	5.00	\N
1085	1721475660	60	user_request	3	count	5.00	\N
1086	1721475360	360	user_request	3	count	5.00	\N
1130	1721476080	360	user_request	3	count	7.00	\N
1087	1721475360	1440	user_request	3	count	18.00	\N
1088	1721472480	10080	user_request	3	count	18.00	\N
1105	1721475840	60	user_request	3	count	2.00	\N
1113	1721475960	60	user_request	3	count	1.00	\N
1202	1721551680	360	user_request	1	count	1.00	\N
1203	1721551680	1440	user_request	1	count	1.00	\N
1204	1721543040	10080	user_request	1	count	1.00	\N
1157	1721513040	60	user_request	1	count	3.00	\N
1313	1723301640	60	user_request	3	count	3.00	\N
1271	1723301280	1440	user_request	3	count	37.00	\N
1353	1723301880	60	user_request	3	count	6.00	\N
1393	1723302000	60	user_request	3	count	3.00	\N
1205	1722671640	60	user_request	1	count	3.00	\N
1206	1722671640	360	user_request	1	count	3.00	\N
1207	1722670560	1440	user_request	1	count	3.00	\N
1272	1723296960	10080	user_request	3	count	37.00	\N
1417	1723311720	60	user_request	3	count	1.00	\N
1418	1723311720	360	user_request	3	count	1.00	\N
1293	1723301580	60	user_request	3	count	5.00	\N
1269	1723301520	60	user_request	3	count	6.00	\N
1208	1722661920	10080	user_request	1	count	3.00	\N
1249	1722712020	60	user_request	3	count	5.00	\N
1419	1723311360	1440	user_request	3	count	1.00	\N
1270	1723301280	360	user_request	3	count	11.00	\N
1217	1722711960	60	user_request	3	count	8.00	\N
1218	1722711960	360	user_request	3	count	13.00	\N
1219	1722710880	1440	user_request	3	count	13.00	\N
1220	1722702240	10080	user_request	3	count	13.00	\N
1377	1723301940	60	user_request	3	count	4.00	\N
1314	1723301640	360	user_request	3	count	20.00	\N
1421	1725022500	60	user_request	3	count	4.00	\N
1437	1725022560	60	user_request	3	count	4.00	\N
1453	1725022620	60	user_request	3	count	2.00	\N
1461	1725022680	60	user_request	3	count	2.00	\N
1405	1723302060	60	user_request	3	count	3.00	\N
1420	1723307040	10080	user_request	3	count	1.00	\N
1423	1725022080	1440	user_request	3	count	13.00	\N
1424	1725020640	10080	user_request	3	count	15.00	\N
1422	1725022440	360	user_request	3	count	12.00	\N
1470	1725022800	360	user_request	3	count	1.00	\N
1473	1725028800	60	user_request	3	count	1.00	\N
1474	1725028560	360	user_request	3	count	1.00	\N
1475	1725027840	1440	user_request	3	count	1.00	\N
1477	1725030120	60	user_request	3	count	1.00	\N
1478	1725030000	360	user_request	3	count	1.00	\N
1479	1725029280	1440	user_request	3	count	1.00	\N
1693	1727028420	60	user_request	1	count	2.00	\N
1694	1727028360	360	user_request	1	count	2.00	\N
1695	1727028000	1440	user_request	1	count	2.00	\N
1711	1727169120	1440	user_request	1	count	3.00	\N
1701	1727031000	60	user_request	1	count	1.00	\N
1537	1727015100	60	user_request	1	count	5.00	\N
1526	1727015040	360	user_request	1	count	8.00	\N
1653	1727021760	60	user_request	1	count	6.00	\N
1634	1727021520	360	user_request	1	count	11.00	\N
1702	1727030880	360	user_request	1	count	1.00	\N
1703	1727030880	1440	user_request	1	count	1.00	\N
1696	1727026560	10080	user_request	1	count	3.00	\N
1705	1727036700	60	user_request	1	count	1.00	\N
1557	1727016240	60	user_request	1	count	3.00	\N
1633	1727021520	60	user_request	1	count	5.00	\N
1706	1727036640	360	user_request	1	count	1.00	\N
1707	1727036640	1440	user_request	1	count	1.00	\N
1708	1727036640	10080	user_request	1	count	1.00	\N
1481	1727012040	60	user_request	1	count	8.00	\N
1569	1727016300	60	user_request	1	count	2.00	\N
1558	1727016120	360	user_request	1	count	5.00	\N
1527	1727015040	1440	user_request	1	count	13.00	\N
1484	1727006400	10080	user_request	1	count	24.00	\N
1577	1727017440	60	user_request	1	count	1.00	\N
1513	1727012100	60	user_request	1	count	2.00	\N
1482	1727011800	360	user_request	1	count	10.00	\N
1483	1727010720	1440	user_request	1	count	10.00	\N
1578	1727017200	360	user_request	1	count	1.00	\N
1521	1727014620	60	user_request	1	count	1.00	\N
1522	1727014320	360	user_request	1	count	1.00	\N
1523	1727013600	1440	user_request	1	count	1.00	\N
1581	1727017560	60	user_request	1	count	1.00	\N
1582	1727017560	360	user_request	1	count	1.00	\N
1579	1727016480	1440	user_request	1	count	2.00	\N
1677	1727022000	60	user_request	1	count	3.00	\N
1525	1727015040	60	user_request	1	count	3.00	\N
1585	1727018160	60	user_request	1	count	1.00	\N
1678	1727021880	360	user_request	1	count	3.00	\N
1589	1727018220	60	user_request	1	count	1.00	\N
1586	1727017920	360	user_request	1	count	2.00	\N
1597	1727020320	60	user_request	1	count	7.00	\N
1635	1727020800	1440	user_request	1	count	14.00	\N
1689	1727022660	60	user_request	1	count	1.00	\N
1690	1727022600	360	user_request	1	count	1.00	\N
1587	1727017920	1440	user_request	1	count	2.00	\N
1593	1727019780	60	user_request	1	count	1.00	\N
1594	1727019720	360	user_request	1	count	1.00	\N
1737	1734260340	60	user_request	3	count	3.00	\N
1726	1734260040	360	user_request	3	count	6.00	\N
1625	1727020380	60	user_request	1	count	2.00	\N
1598	1727020080	360	user_request	1	count	9.00	\N
1595	1727019360	1440	user_request	1	count	10.00	\N
1691	1727022240	1440	user_request	1	count	1.00	\N
1727	1734259680	1440	user_request	3	count	6.00	\N
1709	1727169600	60	user_request	1	count	3.00	\N
1580	1727016480	10080	user_request	1	count	29.00	\N
1710	1727169480	360	user_request	1	count	3.00	\N
1712	1727167680	10080	user_request	1	count	3.00	\N
1721	1727336580	60	user_request	1	count	1.00	\N
1722	1727336520	360	user_request	1	count	1.00	\N
1723	1727336160	1440	user_request	1	count	1.00	\N
1724	1727328960	10080	user_request	1	count	1.00	\N
1725	1734260220	60	user_request	3	count	3.00	\N
1751	1734262560	1440	user_request	3	count	60.00	\N
1749	1734262800	60	user_request	3	count	1.00	\N
1750	1734262560	360	user_request	3	count	1.00	\N
1728	1734253920	10080	user_request	3	count	66.00	\N
1753	1734263220	60	user_request	1	count	3.00	\N
1754	1734262920	360	user_request	1	count	3.00	\N
1755	1734262560	1440	user_request	1	count	3.00	\N
1756	1734253920	10080	user_request	1	count	3.00	\N
1765	1734263280	60	user_request	3	count	1.00	\N
1865	1734263580	60	user_request	3	count	5.00	\N
1766	1734263280	360	user_request	3	count	28.00	\N
1885	1734263640	60	user_request	3	count	2.00	\N
2062	1734271920	360	user_request	1	count	3.00	\N
1957	1734263880	60	user_request	3	count	7.00	\N
2033	1734264120	60	user_request	3	count	2.00	\N
2063	1734271200	1440	user_request	1	count	3.00	\N
2110	1734273360	360	user_request	3	count	3.00	\N
1893	1734263700	60	user_request	3	count	4.00	\N
2073	1734272880	60	user_request	1	count	1.00	\N
2074	1734272640	360	user_request	1	count	1.00	\N
2041	1734264180	60	user_request	3	count	2.00	\N
1985	1734263940	60	user_request	3	count	6.00	\N
1886	1734263640	360	user_request	3	count	31.00	\N
1909	1734263760	60	user_request	3	count	6.00	\N
1769	1734263400	60	user_request	3	count	14.00	\N
2009	1734264000	60	user_request	3	count	2.00	\N
2049	1734264240	60	user_request	3	count	1.00	\N
1825	1734263460	60	user_request	3	count	5.00	\N
1845	1734263460	60	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	count	1.00	\N
1846	1734263280	360	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	count	1.00	\N
1847	1734262560	1440	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	count	1.00	\N
1848	1734253920	10080	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	count	1.00	\N
1849	1734263460	60	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	max	1734263515.00	\N
1850	1734263280	360	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	max	1734263515.00	\N
1851	1734262560	1440	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	max	1734263515.00	\N
1852	1734253920	10080	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	max	1734263515.00	\N
2010	1734264000	360	user_request	3	count	11.00	\N
2011	1734264000	1440	user_request	3	count	11.00	\N
1933	1734263820	60	user_request	3	count	6.00	\N
1853	1734263520	60	user_request	3	count	3.00	\N
2053	1734264780	60	user_request	1	count	1.00	\N
2054	1734264720	360	user_request	1	count	1.00	\N
2055	1734264000	1440	user_request	1	count	1.00	\N
2057	1734265620	60	user_request	3	count	1.00	\N
2058	1734265440	360	user_request	3	count	1.00	\N
2059	1734265440	1440	user_request	3	count	1.00	\N
2089	1734273240	60	user_request	1	count	2.00	\N
2017	1734264060	60	user_request	3	count	4.00	\N
2090	1734273000	360	user_request	1	count	2.00	\N
2056	1734264000	10080	user_request	1	count	9.00	\N
2129	1734273840	60	user_request	3	count	1.00	\N
2077	1734273120	60	user_request	3	count	3.00	\N
2130	1734273720	360	user_request	3	count	2.00	\N
2079	1734272640	1440	user_request	3	count	11.00	\N
2121	1734273540	60	user_request	1	count	1.00	\N
2122	1734273360	360	user_request	1	count	1.00	\N
2101	1734273300	60	user_request	3	count	2.00	\N
2061	1734272100	60	user_request	1	count	3.00	\N
2012	1734264000	10080	user_request	3	count	23.00	\N
2144	1734274080	10080	user_request	3	count	9.00	\N
2093	1734273240	60	user_request	3	count	1.00	\N
2078	1734273000	360	user_request	3	count	6.00	\N
2137	1734274200	60	user_request	1	count	1.00	\N
2133	1734273900	60	user_request	3	count	1.00	\N
2109	1734273360	60	user_request	3	count	3.00	\N
2125	1734273840	60	user_request	1	count	1.00	\N
2126	1734273720	360	user_request	1	count	1.00	\N
2075	1734272640	1440	user_request	1	count	5.00	\N
2138	1734274080	360	user_request	1	count	1.00	\N
2141	1734275220	60	user_request	3	count	1.00	\N
2142	1734275160	360	user_request	3	count	1.00	\N
2143	1734274080	1440	user_request	3	count	1.00	\N
2145	1734275220	60	user_request	1	count	2.00	\N
2139	1734274080	1440	user_request	1	count	3.00	\N
2146	1734275160	360	user_request	1	count	2.00	\N
2153	1734276180	60	user_request	3	count	1.00	\N
2154	1734275880	360	user_request	3	count	1.00	\N
2155	1734275520	1440	user_request	3	count	1.00	\N
2157	1734276180	60	user_request	1	count	1.00	\N
2158	1734275880	360	user_request	1	count	1.00	\N
2326	1736963640	360	user_request	1	count	2.00	\N
2161	1734276780	60	user_request	1	count	1.00	\N
2162	1734276600	360	user_request	1	count	1.00	\N
2159	1734275520	1440	user_request	1	count	2.00	\N
2277	1734289020	60	user_request	1	count	1.00	\N
2165	1734278760	60	user_request	1	count	1.00	\N
2321	1736963400	60	user_request	1	count	1.00	\N
2322	1736963280	360	user_request	1	count	1.00	\N
2323	1736962560	1440	user_request	1	count	3.00	\N
2325	1736963820	60	user_request	1	count	1.00	\N
2169	1734278820	60	user_request	1	count	3.00	\N
2166	1734278760	360	user_request	1	count	4.00	\N
2167	1734278400	1440	user_request	1	count	4.00	\N
2181	1734279840	60	user_request	1	count	1.00	\N
2182	1734279840	360	user_request	1	count	1.00	\N
2337	1736964480	60	user_request	1	count	2.00	\N
2301	1736960220	60	user_request	1	count	4.00	\N
2281	1734289140	60	user_request	1	count	3.00	\N
2185	1734280380	60	user_request	1	count	2.00	\N
2186	1734280200	360	user_request	1	count	2.00	\N
2183	1734279840	1440	user_request	1	count	3.00	\N
2278	1734288840	360	user_request	1	count	4.00	\N
2193	1734280620	60	user_request	3	count	1.00	\N
2194	1734280560	360	user_request	3	count	1.00	\N
2195	1734279840	1440	user_request	3	count	1.00	\N
2197	1734281400	60	user_request	1	count	1.00	\N
2198	1734281280	360	user_request	1	count	1.00	\N
2221	1734283260	60	user_request	1	count	6.00	\N
2201	1734282180	60	user_request	1	count	1.00	\N
2222	1734283080	360	user_request	1	count	6.00	\N
2219	1734282720	1440	user_request	1	count	7.00	\N
2140	1734274080	10080	user_request	1	count	24.00	\N
2245	1734283560	60	user_request	3	count	1.00	\N
2279	1734288480	1440	user_request	1	count	4.00	\N
2302	1736960040	360	user_request	1	count	4.00	\N
2205	1734282240	60	user_request	1	count	3.00	\N
2202	1734282000	360	user_request	1	count	4.00	\N
2199	1734281280	1440	user_request	1	count	5.00	\N
2293	1734290640	60	user_request	1	count	1.00	\N
2217	1734282960	60	user_request	1	count	1.00	\N
2218	1734282720	360	user_request	1	count	1.00	\N
2294	1734290640	360	user_request	1	count	1.00	\N
2295	1734289920	1440	user_request	1	count	1.00	\N
2272	1734284160	10080	user_request	1	count	7.00	\N
2297	1734290940	60	user_request	3	count	1.00	\N
2298	1734290640	360	user_request	3	count	1.00	\N
2249	1734283680	60	user_request	3	count	3.00	\N
2299	1734289920	1440	user_request	3	count	1.00	\N
2300	1734284160	10080	user_request	3	count	1.00	\N
2261	1734283740	60	user_request	3	count	2.00	\N
2246	1734283440	360	user_request	3	count	6.00	\N
2247	1734282720	1440	user_request	3	count	6.00	\N
2269	1734285540	60	user_request	1	count	1.00	\N
2270	1734285240	360	user_request	1	count	1.00	\N
2271	1734284160	1440	user_request	1	count	1.00	\N
2273	1734287640	60	user_request	1	count	1.00	\N
2274	1734287400	360	user_request	1	count	1.00	\N
2275	1734287040	1440	user_request	1	count	1.00	\N
2303	1736959680	1440	user_request	1	count	4.00	\N
2347	1736965440	1440	user_request	1	count	2.00	\N
2317	1736961180	60	user_request	1	count	1.00	\N
2318	1736961120	360	user_request	1	count	1.00	\N
2319	1736961120	1440	user_request	1	count	1.00	\N
2329	1736963880	60	user_request	1	count	1.00	\N
2333	1736964360	60	user_request	1	count	1.00	\N
2334	1736964360	360	user_request	1	count	3.00	\N
2335	1736964000	1440	user_request	1	count	3.00	\N
2304	1736955360	10080	user_request	1	count	11.00	\N
2345	1736965920	60	user_request	1	count	1.00	\N
2346	1736965800	360	user_request	1	count	1.00	\N
2349	1736966700	60	user_request	1	count	1.00	\N
2350	1736966520	360	user_request	1	count	1.00	\N
2466	1737909720	360	user_request	1	count	4.00	\N
2353	1736967600	60	user_request	1	count	1.00	\N
2467	1737908640	1440	user_request	1	count	4.00	\N
2357	1736967660	60	user_request	1	count	1.00	\N
2354	1736967600	360	user_request	1	count	2.00	\N
2563	1739985120	1440	user_request	1	count	5.00	\N
2481	1737910740	60	user_request	1	count	1.00	\N
2361	1736967960	60	user_request	1	count	1.00	\N
2362	1736967960	360	user_request	1	count	1.00	\N
2355	1736966880	1440	user_request	1	count	3.00	\N
2482	1737910440	360	user_request	1	count	1.00	\N
2365	1736970060	60	user_request	1	count	1.00	\N
2366	1736969760	360	user_request	1	count	1.00	\N
2369	1736970120	60	user_request	1	count	1.00	\N
2370	1736970120	360	user_request	1	count	1.00	\N
2381	1736970720	60	user_request	1	count	13.00	\N
2564	1739979360	10080	user_request	1	count	5.00	\N
2485	1737910800	60	user_request	1	count	1.00	\N
2373	1736970660	60	user_request	1	count	2.00	\N
2489	1737911040	60	user_request	1	count	1.00	\N
2486	1737910800	360	user_request	1	count	2.00	\N
2517	1737912540	60	user_request	1	count	3.00	\N
2493	1737911220	60	user_request	1	count	1.00	\N
2506	1737912240	360	user_request	1	count	6.00	\N
2503	1737911520	1440	user_request	1	count	7.00	\N
2497	1737911400	60	user_request	1	count	1.00	\N
2494	1737911160	360	user_request	1	count	2.00	\N
2483	1737910080	1440	user_request	1	count	5.00	\N
2468	1737902880	10080	user_request	1	count	16.00	\N
2501	1737911580	60	user_request	1	count	1.00	\N
2433	1736970780	60	user_request	1	count	5.00	\N
2374	1736970480	360	user_request	1	count	20.00	\N
2367	1736969760	1440	user_request	1	count	22.00	\N
2502	1737911520	360	user_request	1	count	1.00	\N
2453	1736972100	60	user_request	1	count	1.00	\N
2454	1736971920	360	user_request	1	count	1.00	\N
2455	1736971200	1440	user_request	1	count	1.00	\N
2348	1736965440	10080	user_request	1	count	28.00	\N
2457	1737706320	60	user_request	1	count	2.00	\N
2458	1737706320	360	user_request	1	count	2.00	\N
2459	1737705600	1440	user_request	1	count	2.00	\N
2460	1737701280	10080	user_request	1	count	2.00	\N
2465	1737909780	60	user_request	1	count	2.00	\N
2529	1738521360	60	user_request	1	count	1.00	\N
2530	1738521360	360	user_request	1	count	1.00	\N
2531	1738520640	1440	user_request	1	count	1.00	\N
2532	1738517760	10080	user_request	1	count	1.00	\N
2473	1737910020	60	user_request	1	count	2.00	\N
2605	1739986680	60	user_request	3	count	2.00	\N
2505	1737912420	60	user_request	1	count	3.00	\N
2613	1739986740	60	user_request	3	count	6.00	\N
2545	1739974200	60	user_request	1	count	4.00	\N
2546	1739973960	360	user_request	1	count	4.00	\N
2547	1739973600	1440	user_request	1	count	4.00	\N
2548	1739969280	10080	user_request	1	count	4.00	\N
2533	1739877420	60	user_request	3	count	3.00	\N
2534	1739877120	360	user_request	3	count	3.00	\N
2535	1739877120	1440	user_request	3	count	3.00	\N
2536	1739868480	10080	user_request	3	count	3.00	\N
2561	1739985840	60	user_request	1	count	4.00	\N
2562	1739985840	360	user_request	1	count	4.00	\N
2584	1739979360	10080	user_request	3	count	47.00	\N
2577	1739986200	60	user_request	1	count	1.00	\N
2578	1739986200	360	user_request	1	count	1.00	\N
2637	1739986860	60	user_request	3	count	6.00	\N
2661	1739986920	60	user_request	3	count	2.00	\N
2597	1739986620	60	user_request	3	count	2.00	\N
2581	1739986560	60	user_request	3	count	4.00	\N
2582	1739986560	360	user_request	3	count	20.00	\N
2669	1739987040	60	user_request	3	count	2.00	\N
2677	1739987100	60	user_request	3	count	2.00	\N
2662	1739986920	360	user_request	3	count	8.00	\N
2583	1739986560	1440	user_request	3	count	40.00	\N
2685	1739987220	60	user_request	3	count	2.00	\N
2693	1739987640	60	user_request	3	count	2.00	\N
2774	1739989440	360	user_request	3	count	18.00	\N
2775	1739989440	1440	user_request	3	count	18.00	\N
2881	1739997720	60	user_request	1	count	3.00	\N
2845	1739991960	60	user_request	1	count	1.00	\N
2701	1739987700	60	user_request	3	count	3.00	\N
2882	1739997720	360	user_request	1	count	3.00	\N
2849	1739992140	60	user_request	1	count	1.00	\N
2875	1739996640	1440	user_request	1	count	5.00	\N
2713	1739987880	60	user_request	3	count	4.00	\N
2853	1739992260	60	user_request	1	count	1.00	\N
2846	1739991960	360	user_request	1	count	3.00	\N
2847	1739990880	1440	user_request	1	count	3.00	\N
2729	1739987940	60	user_request	3	count	3.00	\N
2694	1739987640	360	user_request	3	count	12.00	\N
2741	1739988000	60	user_request	3	count	2.00	\N
2857	1739992740	60	user_request	1	count	2.00	\N
2858	1739992680	360	user_request	1	count	2.00	\N
2749	1739988060	60	user_request	3	count	3.00	\N
2742	1739988000	360	user_request	3	count	5.00	\N
2859	1739992320	1440	user_request	1	count	2.00	\N
2761	1739988420	60	user_request	3	count	1.00	\N
2762	1739988360	360	user_request	3	count	1.00	\N
2765	1739989380	60	user_request	3	count	1.00	\N
2766	1739989080	360	user_request	3	count	1.00	\N
2743	1739988000	1440	user_request	3	count	7.00	\N
2769	1739989500	60	user_request	1	count	1.00	\N
2770	1739989440	360	user_request	1	count	1.00	\N
2771	1739989440	1440	user_request	1	count	1.00	\N
2773	1739989560	60	user_request	3	count	1.00	\N
2865	1739993040	60	user_request	3	count	1.00	\N
2866	1739993040	360	user_request	3	count	1.00	\N
2867	1739992320	1440	user_request	3	count	1.00	\N
2777	1739989620	60	user_request	3	count	2.00	\N
2869	1739994120	60	user_request	3	count	1.00	\N
2870	1739994120	360	user_request	3	count	1.00	\N
2785	1739989680	60	user_request	3	count	11.00	\N
2871	1739993760	1440	user_request	3	count	1.00	\N
2776	1739989440	10080	user_request	3	count	20.00	\N
2873	1739996640	60	user_request	1	count	1.00	\N
2874	1739996640	360	user_request	1	count	1.00	\N
2877	1739997420	60	user_request	1	count	1.00	\N
2878	1739997360	360	user_request	1	count	1.00	\N
2893	1739998140	60	user_request	1	count	3.00	\N
2894	1739998080	360	user_request	1	count	3.00	\N
2905	1739999280	60	user_request	1	count	1.00	\N
2906	1739999160	360	user_request	1	count	1.00	\N
2829	1739989740	60	user_request	3	count	4.00	\N
2895	1739998080	1440	user_request	1	count	4.00	\N
2772	1739989440	10080	user_request	1	count	15.00	\N
2909	1739999520	60	user_request	3	count	1.00	\N
2910	1739999520	360	user_request	3	count	1.00	\N
2911	1739999520	1440	user_request	3	count	1.00	\N
2912	1739999520	10080	user_request	3	count	1.00	\N
2929	1741541760	60	user_request	1	count	2.00	\N
2930	1741541760	360	user_request	1	count	2.00	\N
2963	1741544640	1440	user_request	1	count	2.00	\N
2953	1741542900	60	user_request	1	count	1.00	\N
2932	1741541760	10080	user_request	1	count	11.00	\N
2937	1741542120	60	user_request	1	count	4.00	\N
2913	1741541700	60	user_request	1	count	4.00	\N
2914	1741541400	360	user_request	1	count	4.00	\N
2915	1741540320	1440	user_request	1	count	4.00	\N
2916	1741531680	10080	user_request	1	count	4.00	\N
2954	1741542840	360	user_request	1	count	1.00	\N
2931	1741541760	1440	user_request	1	count	7.00	\N
2957	1741543860	60	user_request	1	count	1.00	\N
2958	1741543560	360	user_request	1	count	1.00	\N
2959	1741543200	1440	user_request	1	count	1.00	\N
2938	1741542120	360	user_request	1	count	4.00	\N
2961	1741544640	60	user_request	1	count	1.00	\N
2962	1741544640	360	user_request	1	count	1.00	\N
2965	1741545000	60	user_request	1	count	1.00	\N
2966	1741545000	360	user_request	1	count	1.00	\N
3629	1742746680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2969	1741547400	60	user_request	1	count	1.00	\N
2970	1741547160	360	user_request	1	count	1.00	\N
2973	1741570260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2974	1741570200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2975	1741569120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2976	1741561920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2977	1741570260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2879.00	\N
2978	1741570200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2879.00	\N
2979	1741569120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2879.00	\N
2980	1741561920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2879.00	\N
2981	1741622100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2982	1741622040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2983	1741620960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2984	1741612320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2985	1741622100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1091.00	\N
2986	1741622040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1091.00	\N
2987	1741620960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1091.00	\N
2988	1741612320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1091.00	\N
2989	1741668000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2990	1741667760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2991	1741667040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2993	1741668000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9069.00	\N
2994	1741667760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9069.00	\N
2995	1741667040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9069.00	\N
3633	1742746680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2390.00	\N
2997	1741668600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2998	1741668480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3014	1741669200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3001	1741668600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5341.00	\N
3002	1741668480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5341.00	\N
3005	1741668960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3006	1741668840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3041	1741669920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3027.00	\N
3025	1741669380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1034.00	\N
3009	1741668960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2692.00	\N
3010	1741668840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2692.00	\N
3018	1741669200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
3630	1742746680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3013	1741669260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2999	1741668480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
3038	1741669920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3017	1741669260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
3033	1741669740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3384.00	\N
3034	1741669560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3384.00	\N
3021	1741669380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3634	1742746680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2390.00	\N
3029	1741669740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3030	1741669560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3003	1741668480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5341.00	\N
3039	1741669920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
3037	1741669920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3677	1742748660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6157	1748444340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6158	1748444040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3045	1741670100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3049	1741670100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2913.00	\N
3042	1741669920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3027.00	\N
3053	1741670460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3637	1742746800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3057	1741670460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1618.00	\N
3641	1742746800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1575.00	\N
3061	1741670520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3054	1741670280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3065	1741670520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4122.00	\N
3058	1741670280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4122.00	\N
3102	1741672440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
3087	1741671360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	11.00	\N
3069	1741670640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3070	1741670640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3073	1741670640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
3074	1741670640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
2992	1741662720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	23.00	\N
3077	1741671180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3078	1741671000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3081	1741671180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1894.00	\N
3082	1741671000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1894.00	\N
3043	1741669920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4122.00	\N
3101	1741672440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3085	1741671780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3086	1741671720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3089	1741671780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3157.00	\N
3090	1741671720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3157.00	\N
3117	1741672560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3093	1741672140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3094	1741672080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3169	1741672740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4444.00	\N
3097	1741672140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
3098	1741672080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
3106	1741672440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8397.00	\N
3091	1741671360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8397.00	\N
3149	1741672680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3105	1741672440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4106.00	\N
3125	1741672620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3121	1741672560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5219.00	\N
3129	1741672620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7900.00	\N
3153	1741672680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8397.00	\N
3681	1742748660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1035.00	\N
6989	1751476860	60	user_request	1	count	1.00	\N
3165	1741672740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
2996	1741662720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9069.00	\N
3173	1741746780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3174	1741746600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3175	1741746240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4896	1744031520	10080	user_request	1	count	5.00	\N
4923	1744045920	1440	user_request	1	count	2.00	\N
4922	1744046280	360	user_request	1	count	1.00	\N
4933	1744049160	60	user_request	1	count	1.00	\N
4934	1744049160	360	user_request	1	count	1.00	\N
3176	1741743360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3177	1741746780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11992.00	\N
3178	1741746600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11992.00	\N
3179	1741746240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11992.00	\N
3180	1741743360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11992.00	\N
3181	1741762020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3182	1741761720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3183	1741760640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3185	1741762020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1921.00	\N
3186	1741761720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1921.00	\N
3187	1741760640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1921.00	\N
3189	1741762140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3226	1741762800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4721.00	\N
3193	1741762140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1358.00	\N
3225	1741762860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1900.00	\N
3197	1741762380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3190	1741762080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3246	1741763160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3201	1741762380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1066.00	\N
3194	1741762080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1358.00	\N
3191	1741762080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	11.00	\N
3229	1741762980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3205	1741762680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3645	1742747520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3646	1742747400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3209	1741762680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3283.00	\N
3253	1741763220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3233	1741762980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4721.00	\N
3213	1741762740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3206	1741762440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3245	1741763160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3217	1741762740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1077.00	\N
3210	1741762440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3283.00	\N
6159	1748443680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3265	1741763280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3550.00	\N
3221	1741762860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3249	1741763160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3556.00	\N
3184	1741753440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	12.00	\N
3273	1741763400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11715.00	\N
3250	1741763160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11715.00	\N
3237	1741763100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3222	1741762800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3195	1741762080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11715.00	\N
3261	1741763280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3241	1741763100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2957.00	\N
3257	1741763220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10906.00	\N
3649	1742747520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1148.00	\N
3650	1742747400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1148.00	\N
3269	1741763400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3188	1741753440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11715.00	\N
3277	1741776300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4909	1744041420	60	user_request	1	count	1.00	\N
3278	1741776120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3279	1741775040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3280	1741773600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3281	1741776300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
3282	1741776120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
3283	1741775040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
3284	1741773600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
3285	1741787880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3286	1741787640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3287	1741786560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3288	1741783680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3289	1741787880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1249.00	\N
3290	1741787640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1249.00	\N
3291	1741786560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1249.00	\N
3292	1741783680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1249.00	\N
3293	1741800060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3294	1741799880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3295	1741799520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3296	1741793760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3297	1741800060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1378.00	\N
3298	1741799880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1378.00	\N
3299	1741799520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1378.00	\N
3300	1741793760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1378.00	\N
3301	1741901760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3302	1741901760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3303	1741901760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3304	1741894560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3305	1741901760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7202.00	\N
3306	1741901760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7202.00	\N
3307	1741901760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7202.00	\N
3308	1741894560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7202.00	\N
3349	1742067900	60	user_request	3	count	4.00	\N
3337	1742052780	60	user_request	1	count	1.00	\N
3338	1742052600	360	user_request	1	count	1.00	\N
3335	1742051520	1440	user_request	1	count	2.00	\N
3653	1742748120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3341	1742053080	60	user_request	1	count	1.00	\N
3342	1742052960	360	user_request	1	count	1.00	\N
3343	1742052960	1440	user_request	1	count	1.00	\N
3309	1742046420	60	user_request	1	count	4.00	\N
3310	1742046120	360	user_request	1	count	4.00	\N
3311	1742045760	1440	user_request	1	count	4.00	\N
3312	1742045760	10080	user_request	1	count	9.00	\N
3345	1742058000	60	user_request	1	count	1.00	\N
3325	1742047200	60	user_request	1	count	2.00	\N
3326	1742047200	360	user_request	1	count	2.00	\N
3327	1742047200	1440	user_request	1	count	2.00	\N
6160	1748436480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3333	1742051940	60	user_request	1	count	1.00	\N
3334	1742051880	360	user_request	1	count	1.00	\N
3346	1742058000	360	user_request	1	count	1.00	\N
3347	1742057280	1440	user_request	1	count	1.00	\N
3348	1742055840	10080	user_request	1	count	1.00	\N
3350	1742067720	360	user_request	3	count	5.00	\N
3351	1742067360	1440	user_request	3	count	5.00	\N
3352	1742065920	10080	user_request	3	count	5.00	\N
3369	1742067960	60	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3365	1742067960	60	user_request	3	count	1.00	\N
3370	1742067720	360	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3450	1742182200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10162.00	\N
3371	1742067360	1440	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3372	1742065920	10080	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3373	1742067960	60	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742067985.00	\N
3374	1742067720	360	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742067985.00	\N
3375	1742067360	1440	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742067985.00	\N
3376	1742065920	10080	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742067985.00	\N
3377	1742109840	60	user_request	1	count	1.00	\N
3378	1742109840	360	user_request	1	count	1.00	\N
3379	1742109120	1440	user_request	1	count	1.00	\N
3380	1742106240	10080	user_request	1	count	1.00	\N
3381	1742117520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3382	1742117400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3383	1742116320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3384	1742116320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3385	1742117520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11687.00	\N
3386	1742117400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11687.00	\N
3387	1742116320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11687.00	\N
3388	1742116320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11687.00	\N
3657	1742748120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1015.00	\N
3389	1742136360	60	user_request	1	count	3.00	\N
3679	1742748480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
6161	1748444340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16513.00	\N
3413	1742136480	60	user_request	1	count	6.00	\N
3401	1742136420	60	user_request	1	count	1.00	\N
3390	1742136120	360	user_request	1	count	4.00	\N
3391	1742135040	1440	user_request	1	count	4.00	\N
3392	1742126400	10080	user_request	1	count	4.00	\N
3405	1742136420	60	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3406	1742136120	360	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3407	1742135040	1440	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3408	1742126400	10080	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	count	1.00	\N
3409	1742136420	60	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742136426.00	\N
3410	1742136120	360	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742136426.00	\N
3411	1742135040	1440	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742136426.00	\N
3412	1742126400	10080	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	max	1742136426.00	\N
3709	1742749500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3647	1742747040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3658	1742748120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1321.00	\N
3651	1742747040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1321.00	\N
3685	1742748780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3678	1742748480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3689	1742748780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1291.00	\N
3682	1742748480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1291.00	\N
3713	1742749500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1608.00	\N
3437	1742136720	60	user_request	1	count	2.00	\N
3414	1742136480	360	user_request	1	count	8.00	\N
3415	1742136480	1440	user_request	1	count	8.00	\N
3416	1742136480	10080	user_request	1	count	8.00	\N
3445	1742182380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3446	1742182200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3447	1742181120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3448	1742176800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3449	1742182380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10162.00	\N
3451	1742181120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10162.00	\N
3452	1742176800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10162.00	\N
3453	1742195580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3454	1742195520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3455	1742195520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3456	1742186880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3457	1742195580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
3458	1742195520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
3459	1742195520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
3460	1742186880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
3461	1742206620	60	user_request	1	count	1.00	\N
3462	1742206320	360	user_request	1	count	1.00	\N
3463	1742205600	1440	user_request	1	count	1.00	\N
3464	1742196960	10080	user_request	1	count	1.00	\N
3465	1742207580	60	user_request	1	count	1.00	\N
3466	1742207400	360	user_request	1	count	1.00	\N
3467	1742207040	1440	user_request	1	count	1.00	\N
3468	1742207040	10080	user_request	1	count	1.00	\N
3469	1742212380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3470	1742212080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3471	1742211360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3472	1742207040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3473	1742212380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1325.00	\N
3474	1742212080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1325.00	\N
3475	1742211360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1325.00	\N
3476	1742207040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1325.00	\N
3477	1742222820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3478	1742222520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3479	1742221440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3480	1742217120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3481	1742222820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2244.00	\N
3482	1742222520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2244.00	\N
3483	1742221440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2244.00	\N
3484	1742217120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2244.00	\N
3485	1742236140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3486	1742235840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3487	1742235840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3488	1742227200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3489	1742236140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1586.00	\N
3490	1742235840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1586.00	\N
3491	1742235840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1586.00	\N
3492	1742227200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1586.00	\N
3493	1742284920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3494	1742284800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3495	1742284800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3496	1742277600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3497	1742284920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1810.00	\N
3498	1742284800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1810.00	\N
3499	1742284800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1810.00	\N
3500	1742277600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1810.00	\N
3501	1742312820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3502	1742312520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3503	1742312160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3504	1742307840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3505	1742312820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1002.00	\N
3506	1742312520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1002.00	\N
3507	1742312160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1002.00	\N
3508	1742307840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1002.00	\N
3509	1742405040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3513	1742405040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1691.00	\N
3517	1742405280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3510	1742405040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3546	1742432040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4425.00	\N
3661	1742748180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3521	1742405280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2513.00	\N
3514	1742405040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2513.00	\N
3557	1742432640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3525	1742405700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3526	1742405400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3511	1742404320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3558	1742432400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3529	1742405700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1382.00	\N
3530	1742405400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1382.00	\N
3515	1742404320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2513.00	\N
3543	1742431680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3533	1742406720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3534	1742406480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3535	1742405760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3512	1742398560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3537	1742406720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6343.00	\N
3538	1742406480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6343.00	\N
3539	1742405760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6343.00	\N
3516	1742398560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6343.00	\N
3541	1742432040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3542	1742432040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3665	1742748180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1088.00	\N
3654	1742748120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3545	1742432040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4425.00	\N
3544	1742428800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3561	1742432640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1094.00	\N
3562	1742432400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1094.00	\N
3547	1742431680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4425.00	\N
3548	1742428800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4425.00	\N
3565	1742503500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3566	1742503320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3567	1742502240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3568	1742499360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3569	1742503500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1609.00	\N
3693	1742749020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3694	1742748840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3697	1742749020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2180.00	\N
3698	1742748840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2180.00	\N
6162	1748444040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16513.00	\N
3683	1742748480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2180.00	\N
3717	1742751060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3596	1742741280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8255.00	\N
4910	1744041240	360	user_request	1	count	1.00	\N
3570	1742503320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1609.00	\N
3571	1742502240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1609.00	\N
3572	1742499360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1609.00	\N
3573	1742522100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3574	1742522040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3575	1742520960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3576	1742519520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3577	1742522100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2528.00	\N
3578	1742522040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2528.00	\N
3579	1742520960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2528.00	\N
3580	1742519520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2528.00	\N
3581	1742644860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3582	1742644800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3583	1742644800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3584	1742640480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3585	1742644860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2655.00	\N
3586	1742644800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2655.00	\N
3587	1742644800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2655.00	\N
3588	1742640480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2655.00	\N
3589	1742745540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3590	1742745240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3591	1742744160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3593	1742745540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1017.00	\N
3594	1742745240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1017.00	\N
3595	1742744160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1017.00	\N
6163	1748443680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16513.00	\N
3597	1742745600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3702	1742749200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3601	1742745600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1045.00	\N
3592	1742741280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	20.00	\N
3605	1742745720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3598	1742745600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3599	1742745600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
3669	1742748300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3609	1742745720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1031.00	\N
3602	1742745600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1045.00	\N
6164	1748436480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16513.00	\N
3705	1742749200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1049.00	\N
3613	1742745960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3614	1742745960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3603	1742745600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8255.00	\N
3701	1742749200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3617	1742745960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1263.00	\N
3618	1742745960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1263.00	\N
3621	1742746440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3622	1742746320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3673	1742748300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1321.00	\N
3625	1742746440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8255.00	\N
3626	1742746320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8255.00	\N
6293	1748680380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3706	1742749200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1608.00	\N
9145	1758749040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3721	1742751060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1011.00	\N
3766	1742753520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3751	1742752800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3782	1742754240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3777	1742753640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1053.00	\N
3725	1742751180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3770	1742753520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2766.00	\N
3755	1742752800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9557.00	\N
3729	1742751180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
3810	1742754600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1653.00	\N
3781	1742754240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3741	1742751300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3718	1742751000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3719	1742749920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
3745	1742751300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1414.00	\N
3722	1742751000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
3723	1742749920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
3749	1742752800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3753	1742752800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1031.00	\N
6165	1748446800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3757	1742752920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3750	1742752800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3761	1742752920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9557.00	\N
3754	1742752800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9557.00	\N
3822	1742754960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3785	1742754240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1110.00	\N
3765	1742753520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3801	1742754540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7464.00	\N
3786	1742754240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7464.00	\N
3769	1742753520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2766.00	\N
3752	1742751360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	23.00	\N
3821	1742755140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3773	1742753640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3805	1742754720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3789	1742754420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3783	1742754240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	8.00	\N
3813	1742754900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3793	1742754420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2982.00	\N
3806	1742754600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4861	1744018860	60	user_request	1	count	4.00	\N
3833	1742755260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1482.00	\N
3797	1742754540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3825	1742755140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11161.00	\N
3809	1742754720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1205.00	\N
3817	1742754900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1653.00	\N
3829	1742755260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3826	1742754960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11161.00	\N
4862	1744018560	360	user_request	1	count	4.00	\N
4863	1744018560	1440	user_request	1	count	8.00	\N
4864	1744011360	10080	user_request	1	count	8.00	\N
4911	1744040160	1440	user_request	1	count	1.00	\N
4925	1744047240	60	user_request	1	count	1.00	\N
4926	1744047000	360	user_request	1	count	1.00	\N
4935	1744048800	1440	user_request	1	count	1.00	\N
3837	1742755620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3838	1742755320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3841	1742755620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1135.00	\N
3842	1742755320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1135.00	\N
3787	1742754240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11161.00	\N
3875	1742757120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6857.00	\N
3845	1742755740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3849	1742755740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1006.00	\N
3914	1742758560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1381.00	\N
3853	1742755980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3846	1742755680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3885	1742758140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3857	1742755980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1169.00	\N
3850	1742755680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1169.00	\N
3878	1742757840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3915	1742758560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1381.00	\N
3861	1742756160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3862	1742756040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3847	1742755680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3865	1742756160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5542.00	\N
3866	1742756040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5542.00	\N
3851	1742755680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5542.00	\N
3889	1742758140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6857.00	\N
3869	1742757180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3870	1742757120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3873	1742757180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1028.00	\N
3874	1742757120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1028.00	\N
3882	1742757840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6857.00	\N
3877	1742757840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3909	1742758740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3881	1742757840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1661.00	\N
3913	1742758740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1233.00	\N
6166	1748446560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3925	1742760960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3901	1742758440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3902	1742758200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3871	1742757120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
3905	1742758440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1061.00	\N
3906	1742758200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1061.00	\N
3756	1742751360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11161.00	\N
3917	1742758860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3910	1742758560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3911	1742758560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3921	1742758860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1381.00	\N
3926	1742760720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3927	1742760000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3929	1742760960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3074.00	\N
3930	1742760720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3074.00	\N
6167	1748446560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6168	1748446560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6169	1748446800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19579.00	\N
3931	1742760000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3074.00	\N
3933	1742761620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3934	1742761440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3935	1742761440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3937	1742761620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12035.00	\N
3938	1742761440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12035.00	\N
3939	1742761440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12035.00	\N
3987	1742765760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8093.00	\N
3981	1742765940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3941	1742765400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3982	1742765760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4029	1742839800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
3945	1742765400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11971.00	\N
3985	1742765940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2364.00	\N
3986	1742765760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2364.00	\N
4005	1742768700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3989	1742766180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3990	1742766120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4030	1742839560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4017	1742768880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1054.00	\N
3993	1742766180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8093.00	\N
3957	1742765520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4007	1742768640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
3961	1742765520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7484.00	\N
4009	1742768700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3641.00	\N
3973	1742765700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3942	1742765400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
3943	1742764320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
3936	1742761440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	12.00	\N
3977	1742765700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1101.00	\N
3946	1742765400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11971.00	\N
3947	1742764320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11971.00	\N
3994	1742766120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8093.00	\N
4010	1742768640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3641.00	\N
3997	1742766660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3998	1742766480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
3983	1742765760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4001	1742766660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4000.00	\N
4002	1742766480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4000.00	\N
4013	1742768880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4006	1742768640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4021	1742769000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4022	1742769000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4025	1742769000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1193.00	\N
4026	1742769000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1193.00	\N
4011	1742768640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3641.00	\N
3940	1742761440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12035.00	\N
4877	1744019520	60	user_request	1	count	2.00	\N
4878	1744019280	360	user_request	1	count	4.00	\N
4915	1744044480	1440	user_request	1	count	2.00	\N
4913	1744044660	60	user_request	1	count	1.00	\N
4914	1744044480	360	user_request	1	count	1.00	\N
4929	1744047720	60	user_request	1	count	1.00	\N
4930	1744047720	360	user_request	1	count	1.00	\N
4031	1742839200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4032	1742832000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4033	1742839800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4180.00	\N
4034	1742839560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4180.00	\N
4035	1742839200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4180.00	\N
4036	1742832000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4180.00	\N
4045	1742845320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4046	1742845320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4047	1742844960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4048	1742842080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4049	1742845320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6389.00	\N
4050	1742845320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6389.00	\N
4051	1742844960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6389.00	\N
4052	1742842080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6389.00	\N
4053	1742925060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4054	1742924880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4055	1742924160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4056	1742922720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4057	1742925060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7183.00	\N
4058	1742924880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7183.00	\N
4059	1742924160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7183.00	\N
4060	1742922720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7183.00	\N
4061	1742955120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4062	1742955120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4063	1742954400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4064	1742952960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4065	1742955120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8565.00	\N
4066	1742955120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8565.00	\N
4067	1742954400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8565.00	\N
4068	1742952960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8565.00	\N
4069	1742976300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4070	1742976000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4073	1742976300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2260.00	\N
4074	1742976000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2260.00	\N
6170	1748446560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19579.00	\N
6171	1748446560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19579.00	\N
4077	1742976600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4078	1742976360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4086	1742976720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4071	1742976000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
4081	1742976600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4030.00	\N
4082	1742976360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4030.00	\N
6172	1748446560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19579.00	\N
4097	1742976840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6512.00	\N
4085	1742976720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4072	1742973120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
9146	1758749040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4089	1742976720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1083.00	\N
9149	1758749040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1838.00	\N
6297	1748680380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3424.00	\N
4093	1742976840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4090	1742976720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6512.00	\N
4075	1742976000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6512.00	\N
4101	1742978040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4102	1742977800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4103	1742977440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4105	1742978040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
4106	1742977800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
4107	1742977440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
4166	1742983560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4109	1742979180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4110	1742978880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4113	1742979180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3405.00	\N
4114	1742978880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3405.00	\N
4153	1742983260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5206.00	\N
4117	1742979540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4118	1742979240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4111	1742978880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4121	1742979540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10379.00	\N
4122	1742979240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10379.00	\N
4115	1742978880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10379.00	\N
4125	1742982900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4129	1742982900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1419.00	\N
4133	1742983020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4126	1742982840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4127	1742981760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4137	1742983020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1225.00	\N
4130	1742982840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1419.00	\N
4131	1742981760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1419.00	\N
4076	1742973120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10379.00	\N
4141	1742983200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4145	1742983200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3271.00	\N
4149	1742983260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4885	1744019580	60	user_request	1	count	2.00	\N
4917	1744045560	60	user_request	1	count	1.00	\N
4165	1742983740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4157	1742983380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4142	1742983200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4143	1742983200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
4144	1742983200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	27.00	\N
4161	1742983380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8463.00	\N
4146	1742983200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8463.00	\N
4147	1742983200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8463.00	\N
4169	1742983740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7282.00	\N
4185	1742983800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3187.00	\N
4148	1742983200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12744.00	\N
4918	1744045560	360	user_request	1	count	1.00	\N
4181	1742983800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4170	1742983560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7282.00	\N
6173	1748465760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4941	1744051080	60	user_request	1	count	2.00	\N
4189	1742984040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4931	1744047360	1440	user_request	1	count	1.00	\N
4937	1744050360	60	user_request	1	count	1.00	\N
4938	1744050240	360	user_request	1	count	1.00	\N
4939	1744050240	1440	user_request	1	count	3.00	\N
4193	1742984040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3037.00	\N
4197	1742984220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4190	1742983920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4201	1742984220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1009.00	\N
4194	1742983920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3037.00	\N
4205	1742984520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4206	1742984280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4209	1742984520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2377.00	\N
4210	1742984280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2377.00	\N
4213	1742984820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4214	1742984640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4217	1742984820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
4218	1742984640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
4221	1742985000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4238	1742985360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
4225	1742985000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
6174	1748465640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4229	1742985240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4222	1742985000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4265	1742985660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1841.00	\N
4233	1742985240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4122.00	\N
4226	1742985000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4122.00	\N
4242	1742985360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8086.00	\N
4237	1742985360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6175	1748465280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4241	1742985360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8086.00	\N
4269	1742985780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4245	1742985540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4249	1742985540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1016.00	\N
4273	1742985780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3956.00	\N
4253	1742985600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4303	1742986080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
4307	1742986080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12744.00	\N
4257	1742985600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2388.00	\N
4285	1742985960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4270	1742985720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
4261	1742985660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4215	1742984640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	11.00	\N
4277	1742985840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4274	1742985720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3956.00	\N
4219	1742984640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8086.00	\N
4281	1742985840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2081.00	\N
4301	1742986080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4302	1742986080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4289	1742985960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1229.00	\N
4305	1742986080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2887.00	\N
4306	1742986080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2887.00	\N
4309	1742986500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6176	1748456640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6177	1748465760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18722.00	\N
6178	1748465640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18722.00	\N
6179	1748465280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18722.00	\N
4313	1742986500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2168.00	\N
4317	1742986620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4310	1742986440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4321	1742986620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12744.00	\N
4314	1742986440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12744.00	\N
4325	1742987040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4326	1742986800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4329	1742987040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5495.00	\N
4330	1742986800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5495.00	\N
4333	1742987160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4337	1742987160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1147.00	\N
6180	1748456640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18722.00	\N
4341	1742987340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4334	1742987160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4345	1742987340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11136.00	\N
4338	1742987160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11136.00	\N
4357	1743123480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4358	1743123240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4359	1743122880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4360	1743114240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4361	1743123480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6329.00	\N
4362	1743123240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6329.00	\N
4363	1743122880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6329.00	\N
4364	1743114240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6329.00	\N
4365	1743128340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4366	1743128280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4367	1743127200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4368	1743124320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4369	1743128340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9828.00	\N
4370	1743128280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9828.00	\N
4371	1743127200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9828.00	\N
4372	1743124320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9828.00	\N
4373	1743172920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4374	1743172920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4375	1743171840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4376	1743164640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4377	1743172920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6900.00	\N
4378	1743172920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6900.00	\N
4379	1743171840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6900.00	\N
4380	1743164640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6900.00	\N
4381	1743182640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4382	1743182640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4383	1743181920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4384	1743174720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4385	1743182640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10216.00	\N
6300	1748678400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
9150	1758749040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1838.00	\N
6299	1748679840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5396.00	\N
6298	1748680200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3424.00	\N
6357	1748682900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6361	1748682900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3077.00	\N
6389	1748683920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4386	1743182640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10216.00	\N
4387	1743181920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10216.00	\N
4388	1743174720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10216.00	\N
4389	1743192540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4390	1743192360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4391	1743192000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4392	1743184800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4393	1743192540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11762.00	\N
4394	1743192360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11762.00	\N
4395	1743192000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11762.00	\N
4396	1743184800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11762.00	\N
4397	1743202320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4398	1743202080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4399	1743202080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4400	1743194880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4401	1743202320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1178.00	\N
4402	1743202080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1178.00	\N
4403	1743202080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1178.00	\N
4404	1743194880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1178.00	\N
4405	1743282300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4406	1743282000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4407	1743281280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4408	1743275520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4409	1743282300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6316.00	\N
4410	1743282000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6316.00	\N
4411	1743281280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6316.00	\N
4412	1743275520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6316.00	\N
4413	1743334800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4414	1743334560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4415	1743334560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4416	1743325920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4417	1743334800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2376.00	\N
4418	1743334560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2376.00	\N
4419	1743334560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2376.00	\N
4420	1743325920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2376.00	\N
4421	1743359160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4422	1743359040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4423	1743359040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4424	1743356160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4425	1743359160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11708.00	\N
4426	1743359040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11708.00	\N
4427	1743359040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11708.00	\N
4428	1743356160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11708.00	\N
4429	1743395460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4430	1743395400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4431	1743395040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4432	1743386400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4433	1743395460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
4434	1743395400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
4435	1743395040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
4436	1743386400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
4437	1743436140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4438	1743436080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4439	1743435360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4440	1743426720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4441	1743436140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3945.00	\N
4442	1743436080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3945.00	\N
4443	1743435360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3945.00	\N
4444	1743426720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3945.00	\N
4445	1743441240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4446	1743441120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4447	1743441120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4448	1743436800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4449	1743441240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2808.00	\N
4450	1743441120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2808.00	\N
4451	1743441120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2808.00	\N
4452	1743436800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2808.00	\N
4453	1743452700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4454	1743452640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4455	1743452640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4456	1743446880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4457	1743452700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17766.00	\N
4458	1743452640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17766.00	\N
4459	1743452640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17766.00	\N
4460	1743446880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17766.00	\N
4461	1743460020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4462	1743459840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4463	1743459840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4464	1743456960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4465	1743460020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1404.00	\N
4466	1743459840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1404.00	\N
4467	1743459840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1404.00	\N
4468	1743456960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1404.00	\N
4469	1743614580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4470	1743614280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4471	1743613920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4472	1743608160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4473	1743614580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3656.00	\N
4474	1743614280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3656.00	\N
4475	1743613920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3656.00	\N
4476	1743608160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3656.00	\N
4477	1743651840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4478	1743651720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4479	1743651360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4480	1743648480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4481	1743651840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4878.00	\N
4482	1743651720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4878.00	\N
4483	1743651360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4878.00	\N
4484	1743648480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4878.00	\N
6181	1748470800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6182	1748470680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6183	1748469600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6184	1748466720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6185	1748470800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14997.00	\N
4485	1743674460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4486	1743674400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4487	1743674400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4488	1743668640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4489	1743674460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
4490	1743674400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
4491	1743674400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
4492	1743668640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
4501	1743716520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4502	1743716520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4503	1743716160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4504	1743708960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4505	1743716520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1812.00	\N
4506	1743716520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1812.00	\N
4507	1743716160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1812.00	\N
4508	1743708960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1812.00	\N
4509	1743721680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4513	1743721680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1030.00	\N
4514	1743721560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3537.00	\N
4515	1743720480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3537.00	\N
4551	1743723360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4541	1743723240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4542	1743723000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4543	1743721920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4517	1743721740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4557	1743723600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4550	1743723360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4512	1743719040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	17.00	\N
4521	1743721740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3537.00	\N
4516	1743719040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10493.00	\N
4561	1743723600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2771.00	\N
4554	1743723360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8599.00	\N
4533	1743721800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4510	1743721560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
4511	1743720480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
4893	1744039200	60	user_request	1	count	4.00	\N
4537	1743721800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2648.00	\N
4894	1744039080	360	user_request	1	count	4.00	\N
4545	1743723240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1651.00	\N
4546	1743723000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1651.00	\N
4547	1743721920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1651.00	\N
4565	1743724140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4549	1743723480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4553	1743723480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8599.00	\N
4566	1743724080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4569	1743724140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10493.00	\N
4570	1743724080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10493.00	\N
4555	1743723360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10493.00	\N
4573	1743724920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6186	1748470680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14997.00	\N
4895	1744038720	1440	user_request	1	count	4.00	\N
6187	1748469600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14997.00	\N
4921	1744046280	60	user_request	1	count	1.00	\N
4574	1743724800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4577	1743724920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1021.00	\N
4578	1743724800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1021.00	\N
4581	1743725220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4630	1743728760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4585	1743725220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1059.00	\N
4623	1743727680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4589	1743725460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4582	1743725160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4575	1743724800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4593	1743725460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2254.00	\N
4586	1743725160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2254.00	\N
4579	1743724800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2254.00	\N
4597	1743726420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4601	1743726420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1057.00	\N
4605	1743726540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4598	1743726240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4641	1743728880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1090.00	\N
4609	1743726540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1323.00	\N
4602	1743726240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1323.00	\N
4634	1743728760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1161.00	\N
4613	1743727260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4614	1743726960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4599	1743726240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4617	1743727260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1169.00	\N
4618	1743726960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1169.00	\N
4603	1743726240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1323.00	\N
4621	1743728340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4622	1743728040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4625	1743728340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6148.00	\N
4626	1743728040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6148.00	\N
4629	1743728760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4648	1743729120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	8.00	\N
4633	1743728760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1161.00	\N
4657	1743730860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2259.00	\N
4637	1743728880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4627	1743727680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6148.00	\N
4645	1743729240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4646	1743729120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4647	1743729120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4649	1743729240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
4650	1743729120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
4651	1743729120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
6188	1748466720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14997.00	\N
4653	1743730860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4654	1743730560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4658	1743730560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2259.00	\N
6301	1748680440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9153	1758749880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4661	1743731940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4655	1743730560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
9154	1758749760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4662	1743731640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4665	1743731940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2980.00	\N
4666	1743731640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2980.00	\N
4659	1743730560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2980.00	\N
4669	1743732540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4670	1743732360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4673	1743732540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1387.00	\N
4674	1743732360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1387.00	\N
4709	1743754080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4677	1743733140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4710	1743753960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4681	1743733140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1010.00	\N
4711	1743753600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4712	1743749280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4685	1743733260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4678	1743733080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4671	1743732000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4689	1743733260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8464.00	\N
4682	1743733080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8464.00	\N
4675	1743732000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8464.00	\N
4713	1743754080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2300.00	\N
4693	1743733620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4694	1743733440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4697	1743733620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12452.00	\N
4698	1743733440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12452.00	\N
4714	1743753960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2300.00	\N
4701	1743734700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4702	1743734520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4695	1743733440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4705	1743734700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2432.00	\N
4706	1743734520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2432.00	\N
4699	1743733440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12452.00	\N
4652	1743729120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12452.00	\N
4715	1743753600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2300.00	\N
4716	1743749280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2300.00	\N
4725	1743762000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4726	1743761880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4727	1743760800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4728	1743759360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4729	1743762000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7105.00	\N
4730	1743761880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7105.00	\N
4731	1743760800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7105.00	\N
4732	1743759360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7105.00	\N
4733	1743955320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6189	1748481000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6190	1748480760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6191	1748479680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9155	1758749760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6193	1748481000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13460.00	\N
6194	1748480760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13460.00	\N
6195	1748479680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13460.00	\N
6957	1751476020	60	user_request	1	count	4.00	\N
4737	1743955320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3026.00	\N
4741	1743955440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4736	1743950880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	16.00	\N
4785	1743956640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1009.00	\N
4786	1743956640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1009.00	\N
4745	1743955440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1805.00	\N
4740	1743950880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11002.00	\N
4789	1743957000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4749	1743955500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4734	1743955200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4809	1743957240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
4753	1743955500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1062.00	\N
4738	1743955200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3026.00	\N
4794	1743957000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1289.00	\N
4793	1743957000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1249.00	\N
4757	1743955620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4758	1743955560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4829	1743957900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4761	1743955620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1217.00	\N
4762	1743955560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1217.00	\N
4822	1743957720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4797	1743957180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4765	1743956340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4783	1743956640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
4769	1743956340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1002.00	\N
4821	1743957780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4801	1743957180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1289.00	\N
4773	1743956460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4766	1743956280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
4735	1743955200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
4813	1743957480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4777	1743956460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10311.00	\N
4770	1743956280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10311.00	\N
4739	1743955200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10311.00	\N
4814	1743957360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4781	1743956640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4782	1743956640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6196	1748476800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13460.00	\N
4805	1743957240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4790	1743957000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4833	1743957900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1085.00	\N
4826	1743957720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8068.00	\N
4817	1743957480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3581.00	\N
4818	1743957360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3581.00	\N
4825	1743957780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8068.00	\N
4787	1743956640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8068.00	\N
6305	1748680440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1514.00	\N
4837	1743958080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9157	1758749880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1131.00	\N
6296	1748678400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	14.00	\N
6365	1748683140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6973	1751476140	60	user_request	1	count	4.00	\N
4841	1743958080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7989.00	\N
4845	1743958320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4838	1743958080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6197	1748485440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4849	1743958320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1730.00	\N
4842	1743958080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7989.00	\N
6198	1748485440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4853	1743959160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4854	1743959160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4839	1743958080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
4857	1743959160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11002.00	\N
4858	1743959160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11002.00	\N
4843	1743958080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11002.00	\N
6199	1748485440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6192	1748476800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6201	1748485440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11956.00	\N
6202	1748485440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11956.00	\N
6203	1748485440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11956.00	\N
6309	1748680500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6294	1748680200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6313	1748680500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1372.00	\N
9158	1758749760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1131.00	\N
6295	1748679840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
6369	1748683140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
6366	1748683080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6370	1748683080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
6393	1748683920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1035.00	\N
6453	1748838060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6454	1748837880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6455	1748836800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6456	1748829600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6457	1748838060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4027.00	\N
6458	1748837880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4027.00	\N
6459	1748836800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4027.00	\N
6460	1748829600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4027.00	\N
6469	1749081540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6470	1749081240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6471	1749080160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6472	1749071520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6473	1749081540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1254.00	\N
6474	1749081240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1254.00	\N
6475	1749080160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1254.00	\N
6476	1749071520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1254.00	\N
6483	1749085920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2321.00	\N
6484	1749081600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2321.00	\N
6485	1749131880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6486	1749131640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6487	1749130560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6488	1749121920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9159	1758749760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1131.00	\N
4942	1744050960	360	user_request	1	count	2.00	\N
4916	1744041600	10080	user_request	1	count	9.00	\N
4949	1744052100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4950	1744052040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4951	1744051680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4952	1744051680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4953	1744052100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
4954	1744052040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
4955	1744051680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
4956	1744051680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
4957	1744053660	60	user_request	1	count	1.00	\N
4958	1744053480	360	user_request	1	count	1.00	\N
4959	1744053120	1440	user_request	1	count	1.00	\N
4960	1744051680	10080	user_request	1	count	1.00	\N
5000	1744313760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
6205	1748494200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6206	1748494080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6207	1748494080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4961	1744097880	60	user_request	1	count	3.00	\N
4962	1744097760	360	user_request	1	count	3.00	\N
4963	1744097760	1440	user_request	1	count	3.00	\N
4964	1744092000	10080	user_request	1	count	3.00	\N
4973	1744099320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4974	1744099200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4975	1744099200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4976	1744092000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4977	1744099320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1316.00	\N
4978	1744099200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1316.00	\N
4979	1744099200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1316.00	\N
4980	1744092000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1316.00	\N
4981	1744166160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4982	1744166160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4983	1744165440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4984	1744162560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4985	1744166160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1903.00	\N
4986	1744166160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1903.00	\N
4987	1744165440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1903.00	\N
4988	1744162560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1903.00	\N
4989	1744201200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4990	1744201080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4991	1744200000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4992	1744192800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4993	1744201200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5890.00	\N
4994	1744201080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5890.00	\N
4995	1744200000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5890.00	\N
4996	1744192800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5890.00	\N
4997	1744317780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4998	1744317720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
4999	1744316640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5001	1744317780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5002	1744317720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5003	1744316640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
6208	1748486880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6209	1748494200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14981.00	\N
6210	1748494080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14981.00	\N
5005	1744318920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5006	1744318800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5007	1744318080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5009	1744318920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5516.00	\N
5010	1744318800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5516.00	\N
5011	1744318080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5516.00	\N
6211	1748494080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14981.00	\N
5013	1744319520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5014	1744319520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5017	1744319520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1137.00	\N
5018	1744319520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1137.00	\N
6212	1748486880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14981.00	\N
5021	1744320000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5022	1744319880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5015	1744319520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5025	1744320000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1071.00	\N
5026	1744319880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1071.00	\N
5019	1744319520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1137.00	\N
5004	1744313760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5516.00	\N
5029	1744354200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5030	1744354080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5031	1744354080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5032	1744354080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5033	1744354200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5456.00	\N
5034	1744354080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5456.00	\N
5035	1744354080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5456.00	\N
5036	1744354080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5456.00	\N
5037	1744461660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5038	1744461360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5039	1744460640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5040	1744454880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5041	1744461660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3976.00	\N
5042	1744461360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3976.00	\N
5043	1744460640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3976.00	\N
5044	1744454880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3976.00	\N
5045	1744521900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5049	1744521900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6395.00	\N
6317	1748680560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5053	1744521960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5046	1744521840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5057	1744521960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1959.00	\N
5050	1744521840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6395.00	\N
6321	1748680560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5396.00	\N
5073	1744522320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2033.00	\N
5061	1744522200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5062	1744522200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5047	1744521120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
5065	1744522200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7795.00	\N
5048	1744515360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
5069	1744522320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5077	1744522440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5081	1744522440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11300.00	\N
5066	1744522200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11300.00	\N
5051	1744521120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11300.00	\N
5052	1744515360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11300.00	\N
5085	1744611540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5086	1744611480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5087	1744610400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5088	1744606080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5089	1744611540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1186.00	\N
5090	1744611480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1186.00	\N
5091	1744610400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1186.00	\N
5092	1744606080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1186.00	\N
5093	1744670160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5094	1744670160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5095	1744669440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5096	1744666560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5097	1744670160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4102.00	\N
5098	1744670160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4102.00	\N
5099	1744669440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4102.00	\N
5100	1744666560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4102.00	\N
5101	1744755000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5102	1744754760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5103	1744754400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5104	1744747200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5105	1744755000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6823.00	\N
5106	1744754760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6823.00	\N
5107	1744754400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6823.00	\N
5108	1744747200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6823.00	\N
5109	1744764960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5110	1744764840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5111	1744764480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5112	1744757280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5113	1744764960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4544.00	\N
5114	1744764840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4544.00	\N
5115	1744764480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4544.00	\N
5116	1744757280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4544.00	\N
5117	1744774560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5118	1744774560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5119	1744774560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5121	1744774560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5122	1744774560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5123	1744774560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
6213	1748507340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5125	1744777140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5126	1744777080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5127	1744776000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5120	1744767360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5129	1744777140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1671.00	\N
5130	1744777080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1671.00	\N
5131	1744776000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1671.00	\N
6214	1748507040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5124	1744767360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5133	1744808640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5134	1744808400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5135	1744807680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5137	1744808640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1006.00	\N
5138	1744808400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1006.00	\N
5139	1744807680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1006.00	\N
5141	1744809360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5142	1744809120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5143	1744809120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6215	1748507040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5145	1744809360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1024.00	\N
5146	1744809120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1024.00	\N
5147	1744809120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1024.00	\N
6216	1748507040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5149	1744813020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5150	1744812720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5151	1744812000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6217	1748507340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16046.00	\N
5153	1744813020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4026.00	\N
5154	1744812720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4026.00	\N
5155	1744812000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4026.00	\N
5181	1745247960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6218	1748507040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16046.00	\N
6219	1748507040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16046.00	\N
5157	1744814160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5158	1744814160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5159	1744813440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5136	1744807680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
5161	1744814160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
5162	1744814160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
5163	1744813440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
5140	1744807680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4026.00	\N
5173	1745029020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5174	1745028720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5175	1745028000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5176	1745019360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5177	1745029020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1465.00	\N
5178	1745028720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1465.00	\N
5179	1745028000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1465.00	\N
5180	1745019360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1465.00	\N
5182	1745247960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
5183	1745246880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
6220	1748507040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16046.00	\N
6325	1748680800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6318	1748680560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6329	1748680800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2459.00	\N
6322	1748680560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5396.00	\N
6373	1748683320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6377	1748683320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2689.00	\N
6397	1748684100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6401	1748684100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3469.00	\N
5232	1745311680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5233	1745320620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9202.00	\N
5234	1745320320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9202.00	\N
5235	1745320320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9202.00	\N
5236	1745311680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9202.00	\N
5245	1745375100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5185	1745247960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5116.00	\N
5246	1745375040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5247	1745375040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5248	1745372160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5205	1745248200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5184	1745241120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
5209	1745248200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1860.00	\N
5186	1745247960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5116.00	\N
5187	1745246880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5116.00	\N
5188	1745241120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5116.00	\N
5213	1745251920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5214	1745251920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5215	1745251200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5217	1745251920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22512.00	\N
5218	1745251920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22512.00	\N
5219	1745251200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22512.00	\N
5221	1745259420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5222	1745259120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5223	1745258400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5216	1745251200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5225	1745259420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26155.00	\N
5226	1745259120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26155.00	\N
5227	1745258400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26155.00	\N
5220	1745251200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26155.00	\N
5229	1745320620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5230	1745320320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5231	1745320320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5249	1745375100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1776.00	\N
5250	1745375040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1776.00	\N
5251	1745375040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1776.00	\N
5252	1745372160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1776.00	\N
5253	1745402160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5254	1745402040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5255	1745400960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5256	1745392320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5257	1745402160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1868.00	\N
5258	1745402040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1868.00	\N
5259	1745400960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1868.00	\N
6221	1748519340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6222	1748519280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6223	1748518560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6224	1748517120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6225	1748519340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12356.00	\N
6226	1748519280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12356.00	\N
6227	1748518560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12356.00	\N
5260	1745392320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1868.00	\N
5287	1745412480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
5309	1745413560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5350	1745415000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5329	1745413800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5896.00	\N
5313	1745413560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2749.00	\N
5314	1745413560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7712.00	\N
5261	1745404560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5262	1745404560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5263	1745403840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5264	1745402400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5265	1745404560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3519.00	\N
5266	1745404560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3519.00	\N
5267	1745403840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3519.00	\N
5268	1745402400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3519.00	\N
5285	1745413020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5286	1745412840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5289	1745413020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1647.00	\N
5290	1745412840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1647.00	\N
6228	1748517120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12356.00	\N
5293	1745413260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5291	1745412480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7712.00	\N
5335	1745413920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
5297	1745413260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3400.00	\N
5333	1745414160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5334	1745413920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5301	1745413500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5294	1745413200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5288	1745412480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	21.00	\N
5305	1745413500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1001.00	\N
5298	1745413200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3400.00	\N
5337	1745414160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12861.00	\N
5338	1745413920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12861.00	\N
5317	1745413680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5345	1745414760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8544.00	\N
5346	1745414640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8544.00	\N
6333	1748681640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5321	1745413680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7712.00	\N
5361	1745415300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4973.00	\N
5349	1745415120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5325	1745413800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5310	1745413560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6334	1748681640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5341	1745414760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5342	1745414640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5353	1745415120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1863.00	\N
6337	1748681640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1864.00	\N
6338	1748681640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1864.00	\N
5357	1745415300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5354	1745415000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4973.00	\N
6959	1751474880	1440	user_request	1	count	8.00	\N
5339	1745413920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12861.00	\N
5365	1745415660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5366	1745415360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5369	1745415660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4929.00	\N
5370	1745415360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4929.00	\N
5399	1745416800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
5373	1745415780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5374	1745415720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5441	1745418120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9131.00	\N
5377	1745415780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1231.00	\N
5378	1745415720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1231.00	\N
5421	1745417760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5414	1745417520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5381	1745416620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6229	1748527920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5385	1745416620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2722.00	\N
5425	1745417760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2427.00	\N
5418	1745417520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4307.00	\N
5389	1745416740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5382	1745416440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5367	1745415360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
5393	1745416740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1019.00	\N
5386	1745416440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2722.00	\N
5371	1745415360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4929.00	\N
5437	1745418120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5397	1745416920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5398	1745416800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5401	1745416920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4166.00	\N
5402	1745416800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4166.00	\N
5430	1745417880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5405	1745417400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5406	1745417160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5409	1745417400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7793.00	\N
5410	1745417160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7793.00	\N
6230	1748527920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6231	1748527200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5413	1745417580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5417	1745417580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4307.00	\N
5429	1745418000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5434	1745417880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9131.00	\N
5433	1745418000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1960.00	\N
6232	1748527200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6233	1748527920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16426.00	\N
5403	1745416800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9131.00	\N
5292	1745412480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12861.00	\N
5453	1745428320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5454	1745428320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5455	1745428320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5456	1745422560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5457	1745428320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10745.00	\N
6234	1748527920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16426.00	\N
6235	1748527200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16426.00	\N
5458	1745428320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10745.00	\N
5459	1745428320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10745.00	\N
5460	1745422560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10745.00	\N
5461	1745446260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5462	1745445960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5463	1745445600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5464	1745442720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5465	1745446260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
5466	1745445960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
5467	1745445600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
5468	1745442720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
5469	1745491680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5470	1745491680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5471	1745491680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5472	1745483040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5473	1745491680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7786.00	\N
5474	1745491680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7786.00	\N
5475	1745491680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7786.00	\N
5476	1745483040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7786.00	\N
5477	1745555400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5478	1745555400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5479	1745555040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5480	1745553600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5481	1745555400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1562.00	\N
5482	1745555400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1562.00	\N
5483	1745555040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1562.00	\N
5484	1745553600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1562.00	\N
5485	1745595900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5486	1745595720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5487	1745595360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5488	1745593920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5489	1745595900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2851.00	\N
5490	1745595720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2851.00	\N
5491	1745595360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2851.00	\N
5492	1745593920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2851.00	\N
5493	1745659620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5494	1745659440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5495	1745658720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5496	1745654400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5497	1745659620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1744.00	\N
5498	1745659440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1744.00	\N
5499	1745658720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1744.00	\N
5500	1745654400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1744.00	\N
5501	1745671380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5502	1745671320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5503	1745670240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5504	1745664480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5505	1745671380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1955.00	\N
5506	1745671320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1955.00	\N
5507	1745670240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1955.00	\N
5508	1745664480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1955.00	\N
5509	1745760660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5510	1745760600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5511	1745759520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5513	1745760660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4319.00	\N
5514	1745760600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4319.00	\N
5515	1745759520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4319.00	\N
6236	1748527200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16426.00	\N
5517	1745761020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5591	1745762400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5521	1745761020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10687.00	\N
5593	1745762820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4647.00	\N
5525	1745761260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5518	1745760960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5581	1745762220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5561	1745761860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9407.00	\N
5529	1745761260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11808.00	\N
5522	1745760960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11808.00	\N
5582	1745762040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5519	1745760960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
5533	1745761380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5565	1745761920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5537	1745761380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1328.00	\N
5594	1745762760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4647.00	\N
5541	1745761560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5534	1745761320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5512	1745755200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	15.00	\N
5569	1745761920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1020.00	\N
5545	1745761560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3964.00	\N
5538	1745761320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3964.00	\N
5597	1745763420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5598	1745763120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5549	1745761740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5585	1745762220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1096.00	\N
5586	1745762040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1096.00	\N
5553	1745761740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4471.00	\N
5523	1745760960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11808.00	\N
6339	1748681280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3399.00	\N
5557	1745761860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6381	1748683800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5573	1745761980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5550	1745761680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
5601	1745763420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7857.00	\N
5577	1745761980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1437.00	\N
5554	1745761680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9407.00	\N
5589	1745762820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5590	1745762760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5602	1745763120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7857.00	\N
5605	1745763480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5606	1745763480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5609	1745763480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
5610	1745763480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
5595	1745762400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7857.00	\N
5613	1745763840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5617	1745763840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7940.00	\N
5654	1746004680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5621	1745764080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5614	1745763840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5615	1745763840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5625	1745764080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6620.00	\N
5618	1745763840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7940.00	\N
5619	1745763840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7940.00	\N
5516	1745755200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11808.00	\N
5629	1745808120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5630	1745808120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5631	1745807040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5632	1745805600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5633	1745808120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9166.00	\N
5634	1745808120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9166.00	\N
5635	1745807040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9166.00	\N
5636	1745805600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9166.00	\N
5637	1745977320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5638	1745977320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5639	1745976960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5640	1745976960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5641	1745977320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2047.00	\N
5642	1745977320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2047.00	\N
5643	1745976960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2047.00	\N
5644	1745976960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2047.00	\N
5645	1745997420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5646	1745997120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5647	1745997120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5649	1745997420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1665.00	\N
5650	1745997120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1665.00	\N
5651	1745997120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1665.00	\N
6237	1748542860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6238	1748542680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5653	1746004860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5655	1746004320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5648	1745997120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5657	1746004860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
5658	1746004680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
5659	1746004320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
5652	1745997120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
5669	1746066360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5670	1746066240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5671	1746066240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5672	1746057600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5673	1746066360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1547.00	\N
6239	1748541600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6241	1748542860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14397.00	\N
6242	1748542680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14397.00	\N
6243	1748541600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14397.00	\N
6341	1748682180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5674	1746066240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1547.00	\N
5675	1746066240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1547.00	\N
5676	1746057600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1547.00	\N
5677	1746252780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5678	1746252720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5679	1746252000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5680	1746249120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5681	1746252780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1560.00	\N
5682	1746252720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1560.00	\N
5683	1746252000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1560.00	\N
5684	1746249120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1560.00	\N
5693	1746262560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5694	1746262440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5695	1746262080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5696	1746259200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5697	1746262560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2041.00	\N
5698	1746262440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2041.00	\N
5699	1746262080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2041.00	\N
5700	1746259200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2041.00	\N
5701	1746318960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5702	1746318960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5703	1746318240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5704	1746309600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5705	1746318960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
5706	1746318960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
5707	1746318240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
5708	1746309600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
5709	1746358860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5710	1746358560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5711	1746358560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5712	1746349920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5713	1746358860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1057.00	\N
5714	1746358560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1057.00	\N
5715	1746358560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1057.00	\N
5716	1746349920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1057.00	\N
5717	1746410040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5718	1746410040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5719	1746408960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5720	1746400320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5721	1746410040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1459.00	\N
5722	1746410040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1459.00	\N
5723	1746408960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1459.00	\N
5724	1746400320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1459.00	\N
5725	1746506280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6245	1748544420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6246	1748544120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6247	1748543040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6249	1748544420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2515.00	\N
6250	1748544120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2515.00	\N
6251	1748543040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2515.00	\N
5726	1746506160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5727	1746505440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5728	1746501120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5729	1746506280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2680.00	\N
5730	1746506160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2680.00	\N
5731	1746505440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2680.00	\N
5732	1746501120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2680.00	\N
5733	1746573900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5734	1746573840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5735	1746573120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5737	1746573900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
5738	1746573840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
5739	1746573120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
5741	1746579540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5742	1746579240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5743	1746578880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5736	1746571680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5745	1746579540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1600.00	\N
5746	1746579240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1600.00	\N
5747	1746578880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1600.00	\N
5740	1746571680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4214.00	\N
5749	1746586320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5750	1746586080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5751	1746586080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5752	1746581760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5753	1746586320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5754	1746586080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5755	1746586080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5756	1746581760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5747.00	\N
5757	1746625020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5758	1746624960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5759	1746624960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5761	1746625020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6032.00	\N
5762	1746624960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6032.00	\N
5763	1746624960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6032.00	\N
5765	1746626880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5766	1746626760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5767	1746626400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5760	1746622080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5769	1746626880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2686.00	\N
5770	1746626760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2686.00	\N
5771	1746626400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2686.00	\N
5764	1746622080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6032.00	\N
5773	1746740520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5774	1746740520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5775	1746740160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5776	1746732960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5777	1746740520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1014.00	\N
6253	1748544960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6254	1748544840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6255	1748544480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6240	1748537280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5778	1746740520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1014.00	\N
5779	1746740160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1014.00	\N
5780	1746732960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1014.00	\N
5781	1746851940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5785	1746851940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2215.00	\N
5789	1746852060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5782	1746851760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5783	1746851040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5784	1746843840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5793	1746852060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2405.00	\N
5786	1746851760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2405.00	\N
5787	1746851040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2405.00	\N
5788	1746843840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2405.00	\N
5797	1747090200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5798	1747090080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5799	1747090080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5801	1747090200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1973.00	\N
5802	1747090080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1973.00	\N
5803	1747090080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1973.00	\N
5805	1747095600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5806	1747095480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5807	1747094400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5800	1747085760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5809	1747095600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1106.00	\N
5810	1747095480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1106.00	\N
5811	1747094400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1106.00	\N
5804	1747085760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1973.00	\N
5813	1747156200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5814	1747155960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5815	1747154880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5816	1747146240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5817	1747156200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1747.00	\N
5818	1747155960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1747.00	\N
5819	1747154880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1747.00	\N
5820	1747146240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1747.00	\N
5821	1747185900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5822	1747185840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5823	1747185120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5824	1747176480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5825	1747185900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3450.00	\N
5826	1747185840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3450.00	\N
5827	1747185120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3450.00	\N
5828	1747176480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3450.00	\N
5829	1747188360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6257	1748544960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5622.00	\N
6258	1748544840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5622.00	\N
6259	1748544480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5622.00	\N
6244	1748537280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14397.00	\N
6342	1748682000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6335	1748681280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6345	1748682180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3399.00	\N
6346	1748682000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3399.00	\N
5830	1747188360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5831	1747188000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5832	1747186560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5833	1747188360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4745.00	\N
5834	1747188360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4745.00	\N
5835	1747188000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4745.00	\N
5836	1747186560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4745.00	\N
5837	1747263360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5838	1747263240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5839	1747262880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5840	1747257120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5841	1747263360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5842	1747263240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5843	1747262880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5844	1747257120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1023.00	\N
5853	1747271640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5854	1747271520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5855	1747271520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5856	1747267200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5857	1747271640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1255.00	\N
5858	1747271520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1255.00	\N
5859	1747271520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1255.00	\N
5860	1747267200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1255.00	\N
5861	1747357500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5862	1747357200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5863	1747356480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5864	1747347840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5865	1747357500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4480.00	\N
5866	1747357200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4480.00	\N
5867	1747356480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4480.00	\N
5868	1747347840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4480.00	\N
5869	1747383120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5870	1747383120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5871	1747382400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5872	1747378080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5873	1747383120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22391.00	\N
5874	1747383120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22391.00	\N
5875	1747382400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22391.00	\N
5876	1747378080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	22391.00	\N
5877	1747389180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5878	1747388880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5879	1747388160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5880	1747388160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5881	1747389180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4997.00	\N
6261	1748550240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6262	1748550240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6263	1748550240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6264	1748547360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6265	1748550240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14130.00	\N
6266	1748550240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14130.00	\N
6267	1748550240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14130.00	\N
6268	1748547360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14130.00	\N
5882	1747388880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4997.00	\N
5883	1747388160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4997.00	\N
5884	1747388160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4997.00	\N
5893	1747483320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5894	1747483200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5897	1747483320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1109.00	\N
5898	1747483200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1109.00	\N
5901	1747483560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5896	1747478880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
5937	1747484640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11011.00	\N
5905	1747483560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5284.00	\N
5909	1747483680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5902	1747483560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5961	1747486260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3647.00	\N
5941	1747484760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5913	1747483680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3680.00	\N
5906	1747483560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5284.00	\N
5934	1747484640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5962	1747486080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3647.00	\N
5917	1747484400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5963	1747486080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3647.00	\N
5945	1747484760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5246.00	\N
5921	1747484400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1025.00	\N
5938	1747484640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11011.00	\N
5900	1747478880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11011.00	\N
5925	1747484520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5918	1747484280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5895	1747483200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
5965	1747544820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5929	1747484520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4407.00	\N
5922	1747484280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4407.00	\N
5899	1747483200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5284.00	\N
5966	1747544760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5933	1747484640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5967	1747543680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5949	1747485780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5950	1747485720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5935	1747484640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
5968	1747539360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5953	1747485780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3272.00	\N
5954	1747485720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3272.00	\N
5939	1747484640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11011.00	\N
5972	1747539360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16609.00	\N
5957	1747486260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5958	1747486080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5959	1747486080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5969	1747544820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16609.00	\N
5970	1747544760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16609.00	\N
5971	1747543680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	16609.00	\N
6269	1748559420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6270	1748559240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5973	1747548060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5974	1747548000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5975	1747548000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5977	1747548060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6372.00	\N
5978	1747548000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6372.00	\N
5979	1747548000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6372.00	\N
5981	1747610580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5982	1747610280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5983	1747609920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5984	1747609920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
5985	1747610580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
5986	1747610280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
5987	1747609920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
5988	1747609920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
5997	1747701480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5998	1747701360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
5999	1747700640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6000	1747700640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6001	1747701480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5258.00	\N
6002	1747701360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5258.00	\N
6003	1747700640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5258.00	\N
6004	1747700640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5258.00	\N
6005	1747725720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6006	1747725480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6007	1747725120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6008	1747720800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6009	1747725720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1086.00	\N
6010	1747725480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1086.00	\N
6011	1747725120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1086.00	\N
6012	1747720800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1086.00	\N
6013	1747793220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6014	1747793160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6015	1747792800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6016	1747791360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6017	1747793220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5709.00	\N
6018	1747793160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5709.00	\N
6019	1747792800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5709.00	\N
6020	1747791360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5709.00	\N
6271	1748558880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6273	1748559420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1105.00	\N
6274	1748559240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1105.00	\N
6275	1748558880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1105.00	\N
6021	1747803600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6022	1747803600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6023	1747802880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6024	1747801440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6025	1747803600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2466.00	\N
6026	1747803600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2466.00	\N
6272	1748557440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6276	1748557440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2530.00	\N
6079	1748102400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6080	1748093760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6027	1747802880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2466.00	\N
6028	1747801440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2466.00	\N
6045	1747848060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6046	1747847880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6047	1747847520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6048	1747841760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6049	1747848060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1156.00	\N
6050	1747847880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1156.00	\N
6051	1747847520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1156.00	\N
6052	1747841760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1156.00	\N
6053	1747918500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6054	1747918440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6055	1747918080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6056	1747912320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6057	1747918500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1104.00	\N
6058	1747918440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1104.00	\N
6059	1747918080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1104.00	\N
6060	1747912320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1104.00	\N
6061	1747977060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6062	1747976760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6063	1747975680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6064	1747972800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6065	1747977060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26527.00	\N
6066	1747976760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26527.00	\N
6067	1747975680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26527.00	\N
6068	1747972800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26527.00	\N
6069	1748069640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6070	1748069640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6071	1748069280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6072	1748063520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6073	1748069640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19699.00	\N
6074	1748069640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19699.00	\N
6075	1748069280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19699.00	\N
6076	1748063520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19699.00	\N
6077	1748102580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6078	1748102400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6081	1748102580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7927.00	\N
6082	1748102400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7927.00	\N
6083	1748102400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7927.00	\N
6084	1748093760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7927.00	\N
6093	1748160240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6094	1748160000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6277	1748563920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6278	1748563920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6279	1748563200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6281	1748563920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2530.00	\N
6282	1748563920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2530.00	\N
6283	1748563200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2530.00	\N
6285	1748629200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6286	1748629080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6095	1748160000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6096	1748154240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6097	1748160240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1429.00	\N
6098	1748160000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1429.00	\N
6099	1748160000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1429.00	\N
6100	1748154240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1429.00	\N
6101	1748301900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6102	1748301840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6103	1748301120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6104	1748295360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6105	1748301900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14609.00	\N
6106	1748301840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14609.00	\N
6107	1748301120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14609.00	\N
6108	1748295360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14609.00	\N
6109	1748306820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6110	1748306520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6111	1748305440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6113	1748306820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1757.00	\N
6114	1748306520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1757.00	\N
6115	1748305440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1757.00	\N
6117	1748311980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6118	1748311920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6119	1748311200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6112	1748305440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6121	1748311980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19405.00	\N
6122	1748311920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19405.00	\N
6123	1748311200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19405.00	\N
6116	1748305440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19405.00	\N
6125	1748323620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6126	1748323440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6127	1748322720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6128	1748315520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6129	1748323620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15313.00	\N
6130	1748323440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15313.00	\N
6131	1748322720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15313.00	\N
6132	1748315520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15313.00	\N
6133	1748398440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6134	1748398320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6135	1748397600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6136	1748396160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6137	1748398440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3913.00	\N
6138	1748398320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3913.00	\N
6139	1748397600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3913.00	\N
6140	1748396160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3913.00	\N
6141	1748426820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6142	1748426760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6143	1748426400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6145	1748426820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9663.00	\N
6146	1748426760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9663.00	\N
6144	1748426400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6287	1748628000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6288	1748628000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6147	1748426400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9663.00	\N
6149	1748429400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6150	1748429280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6151	1748429280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6153	1748429400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11879.00	\N
6154	1748429280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11879.00	\N
6155	1748429280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11879.00	\N
6148	1748426400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11879.00	\N
6289	1748629200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3336.00	\N
6290	1748629080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3336.00	\N
6291	1748628000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3336.00	\N
6292	1748628000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3336.00	\N
6349	1748682720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6353	1748682720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2795.00	\N
6350	1748682720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6407	1748776320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
6354	1748682720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3077.00	\N
6408	1748769120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
6409	1748776320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2555.00	\N
6410	1748776320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2555.00	\N
6411	1748776320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2555.00	\N
6412	1748769120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2555.00	\N
6958	1751475960	360	user_request	1	count	8.00	\N
6385	1748683800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3814.00	\N
6461	1749005580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6462	1749005280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6463	1749005280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6464	1749000960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6465	1749005580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5084.00	\N
6382	1748683800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6351	1748682720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
6386	1748683800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3814.00	\N
6355	1748682720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8962.00	\N
6466	1749005280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5084.00	\N
6467	1749005280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5084.00	\N
6468	1749000960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5084.00	\N
6477	1749087240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6478	1749087000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6405	1748776320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
6406	1748776320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
6479	1749085920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6480	1749081600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6481	1749087240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2321.00	\N
6482	1749087000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2321.00	\N
6489	1749131880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2564.00	\N
6490	1749131640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2564.00	\N
6491	1749130560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2564.00	\N
6492	1749121920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2564.00	\N
6493	1749432000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6497	1749432000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
6501	1749432060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6494	1749431880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6495	1749431520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6496	1749424320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6505	1749432060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1059.00	\N
6498	1749431880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
6499	1749431520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
6500	1749424320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
6509	1749493920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6510	1749493800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6511	1749493440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6512	1749484800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6513	1749493920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4634.00	\N
6514	1749493800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4634.00	\N
6515	1749493440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4634.00	\N
6516	1749484800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4634.00	\N
6525	1749522720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6529	1749522720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3171.00	\N
6533	1749522780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6526	1749522600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6537	1749522780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2672.00	\N
6530	1749522600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3171.00	\N
6541	1749523380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6542	1749523320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6527	1749522240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6528	1749515040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6545	1749523380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5732.00	\N
6546	1749523320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5732.00	\N
6531	1749522240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5732.00	\N
6532	1749515040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5732.00	\N
6549	1749646200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6550	1749646080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6551	1749646080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6552	1749646080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6553	1749646200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1152.00	\N
6554	1749646080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1152.00	\N
6555	1749646080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1152.00	\N
6556	1749646080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1152.00	\N
6557	1749733320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6558	1749733200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6559	1749732480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6560	1749726720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6561	1749733320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2840.00	\N
6562	1749733200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2840.00	\N
6563	1749732480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2840.00	\N
6564	1749726720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2840.00	\N
6565	1749867180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6566	1749867120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6567	1749866400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6568	1749857760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6569	1749867180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1099.00	\N
6570	1749867120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1099.00	\N
6571	1749866400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1099.00	\N
6572	1749857760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1099.00	\N
6581	1749916260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6582	1749916080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6583	1749915360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6584	1749908160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6585	1749916260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1070.00	\N
6586	1749916080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1070.00	\N
6587	1749915360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1070.00	\N
6588	1749908160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1070.00	\N
6589	1750210800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6590	1750210560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6591	1750210560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6593	1750210800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4996.00	\N
6594	1750210560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4996.00	\N
6595	1750210560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4996.00	\N
6597	1750212660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6598	1750212360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6599	1750212000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6592	1750210560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6601	1750212660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4116.00	\N
6602	1750212360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4116.00	\N
6603	1750212000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4116.00	\N
6596	1750210560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4996.00	\N
6605	1750290840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6606	1750290840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6607	1750289760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6608	1750281120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6609	1750290840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
6610	1750290840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
6611	1750289760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
6612	1750281120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1005.00	\N
6613	1750291740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6614	1750291560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6615	1750291200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6616	1750291200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6617	1750291740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5009.00	\N
6618	1750291560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5009.00	\N
6619	1750291200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5009.00	\N
6620	1750291200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5009.00	\N
6629	1750304520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6630	1750304520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6631	1750304160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6633	1750304520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9591.00	\N
6634	1750304520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9591.00	\N
6635	1750304160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9591.00	\N
6637	1750306800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6638	1750306680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6639	1750305600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6632	1750301280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6641	1750306800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1216.00	\N
6642	1750306680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1216.00	\N
6643	1750305600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1216.00	\N
6636	1750301280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9591.00	\N
6645	1750405140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6646	1750404960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6647	1750404960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6648	1750402080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6649	1750405140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2361.00	\N
6650	1750404960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2361.00	\N
6651	1750404960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2361.00	\N
6652	1750402080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2361.00	\N
6653	1750419600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6654	1750419360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6655	1750419360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6657	1750419600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2000.00	\N
6658	1750419360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2000.00	\N
6659	1750419360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2000.00	\N
6661	1750420800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6662	1750420800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6663	1750420800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6656	1750412160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6665	1750420800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1207.00	\N
6666	1750420800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1207.00	\N
6667	1750420800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1207.00	\N
6660	1750412160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2000.00	\N
6669	1750457820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6670	1750457520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6671	1750456800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6672	1750452480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6673	1750457820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1688.00	\N
6674	1750457520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1688.00	\N
6675	1750456800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1688.00	\N
6676	1750452480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1688.00	\N
6677	1750514340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6678	1750514040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6679	1750512960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6680	1750512960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6681	1750514340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
6682	1750514040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
6683	1750512960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
6684	1750512960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
6685	1750619640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6686	1750619520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6687	1750619520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6688	1750613760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6689	1750619640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1623.00	\N
6690	1750619520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1623.00	\N
6691	1750619520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1623.00	\N
6692	1750613760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1623.00	\N
6693	1750653840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6694	1750653720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6695	1750652640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6696	1750644000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6697	1750653840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
6698	1750653720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
6699	1750652640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
6700	1750644000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1426.00	\N
6701	1750654140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6705	1750654140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1179.00	\N
6709	1750654260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6702	1750654080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6713	1750654260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4031.00	\N
6706	1750654080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4031.00	\N
6729	1750654500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1294.00	\N
6717	1750654440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6741	1750654680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6718	1750654440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
6721	1750654440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1747.00	\N
6703	1750654080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	11.00	\N
6737	1750654620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3997.00	\N
6725	1750654500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6704	1750654080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	11.00	\N
6733	1750654620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6707	1750654080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9673.00	\N
6745	1750654680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9673.00	\N
6722	1750654440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9673.00	\N
6708	1750654080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9673.00	\N
6757	1750654860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6761	1750654860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1798.00	\N
6765	1750654980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6769	1750654980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7831.00	\N
6773	1750655040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6777	1750655040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1113.00	\N
6781	1750655100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6758	1750654800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
6785	1750655100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2360.00	\N
6762	1750654800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7831.00	\N
6789	1750671780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6790	1750671720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6791	1750671360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6792	1750664160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6793	1750671780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13692.00	\N
6794	1750671720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13692.00	\N
6795	1750671360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13692.00	\N
6796	1750664160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13692.00	\N
6797	1750725180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6798	1750725000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6799	1750724640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6800	1750724640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6801	1750725180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3527.00	\N
6802	1750725000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3527.00	\N
6803	1750724640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3527.00	\N
6804	1750724640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3527.00	\N
6805	1750764900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6806	1750764600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6807	1750763520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6808	1750754880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6809	1750764900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17939.00	\N
6810	1750764600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17939.00	\N
6811	1750763520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17939.00	\N
6812	1750754880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	17939.00	\N
6813	1750950960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6814	1750950720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6815	1750950720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6816	1750946400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6817	1750950960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2295.00	\N
6818	1750950720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2295.00	\N
6819	1750950720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2295.00	\N
6820	1750946400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2295.00	\N
6821	1750960800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6822	1750960800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6823	1750960800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6824	1750956480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6825	1750960800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4051.00	\N
6826	1750960800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4051.00	\N
6827	1750960800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4051.00	\N
6828	1750956480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4051.00	\N
6829	1750971000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6830	1750970880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6831	1750970880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6832	1750966560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6833	1750971000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1238.00	\N
6834	1750970880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1238.00	\N
6835	1750970880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1238.00	\N
6836	1750966560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1238.00	\N
6837	1751054340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6838	1751054040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6839	1751052960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6840	1751047200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6841	1751054340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5694.00	\N
6842	1751054040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5694.00	\N
6843	1751052960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5694.00	\N
6844	1751047200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5694.00	\N
6845	1751058420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6849	1751058420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6488.00	\N
6853	1751058600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6846	1751058360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6847	1751057280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6867	1751058720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2488.00	\N
6857	1751058600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7241.00	\N
6850	1751058360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7241.00	\N
6851	1751057280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7241.00	\N
6861	1751059560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6862	1751059440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6848	1751057280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
6865	1751059560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2488.00	\N
6866	1751059440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2488.00	\N
6881	1751061420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1322.00	\N
6869	1751060100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6870	1751059800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6863	1751058720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6873	1751060100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1179.00	\N
6874	1751059800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1179.00	\N
6877	1751061420	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6878	1751061240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6879	1751060160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6882	1751061240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1322.00	\N
6883	1751060160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1322.00	\N
6852	1751057280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7241.00	\N
6885	1751132760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6886	1751132520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6887	1751132160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6888	1751127840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6889	1751132760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1244.00	\N
6890	1751132520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1244.00	\N
6891	1751132160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1244.00	\N
6892	1751127840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1244.00	\N
6893	1751348880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6920	1751349600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
6941	1751359080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6897	1751348880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1694.00	\N
6945	1751359080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1084.00	\N
6909	1751348940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6894	1751348880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6895	1751348160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6896	1751339520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6913	1751348940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2081.00	\N
6898	1751348880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2081.00	\N
6899	1751348160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2081.00	\N
6900	1751339520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2081.00	\N
6917	1751350200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6918	1751349960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6919	1751349600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6921	1751350200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3111.00	\N
6922	1751349960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3111.00	\N
6923	1751349600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3111.00	\N
6925	1751358120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6926	1751357880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6927	1751356800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6953	1751359200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1132.00	\N
6929	1751358120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1175.00	\N
6930	1751357880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1175.00	\N
6931	1751356800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1175.00	\N
6946	1751358960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1132.00	\N
6933	1751358600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6934	1751358600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6939	1751358240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4665.00	\N
6937	1751358600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4665.00	\N
6938	1751358600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4665.00	\N
6949	1751359200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6942	1751358960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
6935	1751358240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
6924	1751349600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4665.00	\N
6993	1751476920	60	user_request	1	count	1.00	\N
6990	1751476680	360	user_request	1	count	2.00	\N
6991	1751476320	1440	user_request	1	count	2.00	\N
6997	1751477940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6998	1751477760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
6999	1751477760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7000	1751470560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7001	1751477940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4900.00	\N
7002	1751477760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4900.00	\N
7003	1751477760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4900.00	\N
7004	1751470560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4900.00	\N
7005	1751478840	60	user_request	1	count	1.00	\N
7009	1751478900	60	user_request	1	count	1.00	\N
7057	1751482800	60	user_request	1	count	2.00	\N
7058	1751482800	360	user_request	1	count	2.00	\N
7065	1751483220	60	user_request	1	count	1.00	\N
7013	1751479020	60	user_request	1	count	2.00	\N
7006	1751478840	360	user_request	1	count	4.00	\N
7007	1751477760	1440	user_request	1	count	4.00	\N
7021	1751480160	60	user_request	1	count	1.00	\N
7022	1751479920	360	user_request	1	count	1.00	\N
7023	1751479200	1440	user_request	1	count	1.00	\N
6960	1751470560	10080	user_request	1	count	15.00	\N
7025	1751480940	60	user_request	1	count	2.00	\N
7026	1751480640	360	user_request	1	count	2.00	\N
7069	1751483400	60	user_request	1	count	3.00	\N
7066	1751483160	360	user_request	1	count	4.00	\N
7055	1751482080	1440	user_request	1	count	7.00	\N
7028	1751480640	10080	user_request	1	count	14.00	\N
7081	1751615880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7082	1751615640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7083	1751614560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7033	1751481240	60	user_request	1	count	5.00	\N
7034	1751481000	360	user_request	1	count	5.00	\N
7027	1751480640	1440	user_request	1	count	7.00	\N
7084	1751611680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7053	1751482620	60	user_request	1	count	1.00	\N
7054	1751482440	360	user_request	1	count	1.00	\N
7085	1751615880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5089.00	\N
7086	1751615640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5089.00	\N
7087	1751614560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5089.00	\N
7088	1751611680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5089.00	\N
7089	1751727360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7090	1751727240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7091	1751726880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7092	1751722560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7093	1751727360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1362.00	\N
7094	1751727240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1362.00	\N
7095	1751726880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1362.00	\N
7096	1751722560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1362.00	\N
7097	1751787540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7098	1751787360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7101	1751787540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
7102	1751787360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
7105	1751787720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7104	1751783040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13519.00	\N
7145	1751868660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7109	1751787720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2453.00	\N
7146	1751868360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7113	1751787840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7149	1751868660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2116.00	\N
7150	1751868360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2116.00	\N
7117	1751787840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1045.00	\N
7172	1751964480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7181	1751968920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2001.00	\N
7182	1751968800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2001.00	\N
7121	1751787900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7106	1751787720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
7151	1751868000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2850.00	\N
7152	1751863680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2850.00	\N
7125	1751787900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13519.00	\N
7110	1751787720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13519.00	\N
7169	1751965140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7170	1751964840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7137	1751788200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7138	1751788080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7099	1751787360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
7100	1751783040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
7141	1751788200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3898.00	\N
7142	1751788080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3898.00	\N
7103	1751787360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13519.00	\N
7183	1751968800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2001.00	\N
7153	1751868720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7154	1751868720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7147	1751868000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7148	1751863680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7157	1751868720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2850.00	\N
7158	1751868720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2850.00	\N
7171	1751964480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7173	1751965140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1209.00	\N
7174	1751964840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1209.00	\N
7175	1751964480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1209.00	\N
7177	1751968920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7178	1751968800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7179	1751968800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7176	1751964480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2001.00	\N
7185	1752028440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7186	1752028200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8524	1756369440	10080	user_request	5	count	20.00	\N
7187	1752027840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7188	1752024960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7189	1752028440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6021.00	\N
7190	1752028200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6021.00	\N
7191	1752027840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6021.00	\N
7192	1752024960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6021.00	\N
7193	1752100980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7194	1752100920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7195	1752099840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7197	1752100980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1029.00	\N
7198	1752100920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1029.00	\N
7199	1752099840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1029.00	\N
7201	1752101400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7252	1752105600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	12.00	\N
7205	1752101400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3564.00	\N
7237	1752105180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
7209	1752101520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7202	1752101280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7238	1752104880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1052.00	\N
7213	1752101520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
7206	1752101280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
7241	1752105300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7217	1752102180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7218	1752102000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7203	1752101280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7242	1752105240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7221	1752102180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2642.00	\N
7222	1752102000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2642.00	\N
7207	1752101280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
7235	1752104160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7225	1752103740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7226	1752103440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7227	1752102720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7200	1752095520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5241.00	\N
7229	1752103740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2726.00	\N
7230	1752103440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2726.00	\N
7231	1752102720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2726.00	\N
7249	1752106920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7233	1752105180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7234	1752104880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7196	1752095520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
7245	1752105300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1165.00	\N
7246	1752105240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1165.00	\N
7239	1752104160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1165.00	\N
7250	1752106680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7251	1752105600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7253	1752106920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5586.00	\N
7254	1752106680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5586.00	\N
7255	1752105600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5586.00	\N
7257	1752108060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7258	1752107760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7259	1752107040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7261	1752108060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1433.00	\N
7262	1752107760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1433.00	\N
7263	1752107040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1433.00	\N
7317	1752112560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8377.00	\N
7265	1752110520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7266	1752110280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7269	1752110520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3968.00	\N
7270	1752110280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3968.00	\N
7318	1752112440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8377.00	\N
7273	1752111120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7277	1752111120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1733.00	\N
7281	1752111240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7274	1752111000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7267	1752109920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7285	1752111240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1540.00	\N
7278	1752111000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1733.00	\N
7271	1752109920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3968.00	\N
7289	1752111600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7290	1752111360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7293	1752111600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
7294	1752111360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1698.00	\N
7297	1752112020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7298	1752111720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7295	1752111360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8377.00	\N
7301	1752112020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3758.00	\N
7302	1752111720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3758.00	\N
7331	1752112800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7329	1752113640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7305	1752112140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7306	1752112080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7309	1752112140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3842.00	\N
7310	1752112080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3842.00	\N
7337	1752113820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7330	1752113520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7313	1752112560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7314	1752112440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7291	1752111360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
7333	1752113640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1678.00	\N
7341	1752113820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1016.00	\N
7334	1752113520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1678.00	\N
7335	1752112800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1678.00	\N
7256	1752105600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8377.00	\N
7345	1752142620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7346	1752142320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7347	1752141600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7348	1752135840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7349	1752142620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3646.00	\N
7350	1752142320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3646.00	\N
7351	1752141600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3646.00	\N
7352	1752135840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3646.00	\N
7353	1752274320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7354	1752274080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7355	1752274080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7356	1752266880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7357	1752274320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1431.00	\N
7358	1752274080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1431.00	\N
7359	1752274080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1431.00	\N
7360	1752266880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1431.00	\N
7361	1752551640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7362	1752551640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7363	1752550560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7365	1752551640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5923.00	\N
7366	1752551640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5923.00	\N
7367	1752550560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5923.00	\N
7369	1752552120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7371	1752552000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
7373	1752552120	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
7364	1752549120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
7377	1752552300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7370	1752552000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7413	1752553380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8535.00	\N
7397	1752552840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2432.00	\N
7381	1752552300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7623.00	\N
7374	1752552000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7623.00	\N
7414	1752553080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8535.00	\N
7375	1752552000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8535.00	\N
7385	1752552720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7390	1752552720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4567.00	\N
7389	1752552720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4567.00	\N
7409	1752553380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7393	1752552840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7368	1752549120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8535.00	\N
7401	1752552960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7386	1752552720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7405	1752552960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1140.00	\N
7410	1752553080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7417	1752612060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7418	1752611760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7419	1752611040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7420	1752609600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7421	1752612060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2827.00	\N
7422	1752611760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2827.00	\N
7423	1752611040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2827.00	\N
7424	1752609600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2827.00	\N
7425	1752631440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7426	1752631200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7427	1752631200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7428	1752629760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7429	1752631440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4085.00	\N
7430	1752631200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4085.00	\N
7431	1752631200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4085.00	\N
7432	1752629760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4085.00	\N
7433	1752654780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7434	1752654600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7435	1752654240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7437	1752654780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15834.00	\N
7438	1752654600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15834.00	\N
7439	1752654240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15834.00	\N
7441	1752658200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7445	1752658200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4144.00	\N
7449	1752658320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7442	1752658200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7443	1752657120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7436	1752649920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7453	1752658320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1212.00	\N
7446	1752658200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4144.00	\N
7447	1752657120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4144.00	\N
7440	1752649920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	15834.00	\N
7457	1752675360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7458	1752675120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7459	1752674400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7460	1752670080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7461	1752675360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
7462	1752675120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
7463	1752674400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
7464	1752670080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2124.00	\N
7465	1752695940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7466	1752695640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7467	1752694560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7468	1752690240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7469	1752695940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5674.00	\N
7470	1752695640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5674.00	\N
7471	1752694560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5674.00	\N
7472	1752690240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5674.00	\N
7473	1752730680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7474	1752730560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7475	1752730560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7476	1752730560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7477	1752730680	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
7478	1752730560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
7479	1752730560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
7480	1752730560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
7481	1752742320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7482	1752742080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7483	1752742080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7484	1752740640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7485	1752742320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1717.00	\N
7486	1752742080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1717.00	\N
7487	1752742080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1717.00	\N
7488	1752740640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1717.00	\N
7489	1752822300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7490	1752822000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7491	1752821280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7492	1752821280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7493	1752822300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3363.00	\N
7494	1752822000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3363.00	\N
7495	1752821280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3363.00	\N
7496	1752821280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3363.00	\N
7521	1752873300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7522	1752873120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7523	1752873120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7524	1752871680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7525	1752873300	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3147.00	\N
7497	1752865260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7498	1752865200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7499	1752864480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7500	1752861600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7501	1752865260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1463.00	\N
7502	1752865200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1463.00	\N
7503	1752864480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1463.00	\N
7504	1752861600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1463.00	\N
7526	1752873120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3147.00	\N
7527	1752873120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3147.00	\N
7528	1752871680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3147.00	\N
7537	1753007820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7538	1753007760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7539	1753007040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7540	1753002720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7541	1753007820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6887.00	\N
7542	1753007760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6887.00	\N
7543	1753007040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6887.00	\N
7544	1753002720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6887.00	\N
7545	1753082040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7546	1753081920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7547	1753081920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7548	1753073280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7549	1753082040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1338.00	\N
7550	1753081920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1338.00	\N
7551	1753081920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1338.00	\N
7552	1753073280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1338.00	\N
7553	1753094040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7554	1753093800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7555	1753093440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7556	1753093440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7557	1753094040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3132.00	\N
7558	1753093800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3132.00	\N
7559	1753093440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3132.00	\N
7560	1753093440	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3132.00	\N
7577	1753129080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7578	1753129080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7579	1753128000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7580	1753123680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7581	1753129080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
7582	1753129080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
7583	1753128000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
7584	1753123680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1432.00	\N
7585	1753202220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7586	1753202160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7587	1753201440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7588	1753194240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7589	1753202220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2720.00	\N
7590	1753202160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2720.00	\N
7591	1753201440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2720.00	\N
7592	1753194240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2720.00	\N
7593	1753219560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7594	1753219440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7595	1753218720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7596	1753214400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7597	1753219560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10435.00	\N
7598	1753219440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10435.00	\N
7599	1753218720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10435.00	\N
7600	1753214400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10435.00	\N
7601	1753236780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7602	1753236720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7603	1753236000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7604	1753234560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7605	1753236780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4120.00	\N
7606	1753236720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4120.00	\N
7607	1753236000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4120.00	\N
7608	1753234560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4120.00	\N
7609	1753396980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7610	1753396920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7611	1753395840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7612	1753395840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7613	1753396980	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1075.00	\N
7614	1753396920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1075.00	\N
7615	1753395840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1075.00	\N
7616	1753395840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1075.00	\N
7617	1753456440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7621	1753456440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1310.00	\N
7625	1753456620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7618	1753456320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7619	1753456320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
7629	1753456620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10161.00	\N
7622	1753456320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10161.00	\N
7623	1753456320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7633	1753456800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7634	1753456680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7620	1753456320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	17.00	\N
7624	1753456320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7637	1753456800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7638	1753456680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7641	1753457520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7642	1753457400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7645	1753457520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5649.00	\N
7646	1753457400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5649.00	\N
7649	1753457940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7650	1753457760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7653	1753457940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9079.00	\N
7654	1753457760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9079.00	\N
7657	1753458240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7658	1753458120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7661	1753458240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9353.00	\N
7662	1753458120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9353.00	\N
7709	1753460040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1941.00	\N
7665	1753458960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7669	1753458960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1033.00	\N
7673	1753459140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7666	1753458840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7651	1753457760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
7677	1753459140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1765.00	\N
7670	1753458840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1765.00	\N
7655	1753457760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	9353.00	\N
7681	1753459380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7682	1753459200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7685	1753459380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2079.00	\N
7686	1753459200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2079.00	\N
7689	1753459620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7713	1753460220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7693	1753459620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1711.00	\N
7706	1753459920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7697	1753459740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7690	1753459560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7701	1753459740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4436.00	\N
7694	1753459560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4436.00	\N
7705	1753460040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7683	1753459200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
7717	1753460220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3810.00	\N
7710	1753459920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3810.00	\N
7687	1753459200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4436.00	\N
7721	1753460700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7725	1753460700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1355.00	\N
7729	1753460880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7722	1753460640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7723	1753460640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7733	1753460880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8972.00	\N
7726	1753460640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8972.00	\N
7727	1753460640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8972.00	\N
7745	1753465920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7746	1753465680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7747	1753464960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7749	1753465920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3167.00	\N
7750	1753465680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3167.00	\N
7751	1753464960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3167.00	\N
7753	1753567560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7757	1753567560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4628.00	\N
7761	1753567620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7754	1753567560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7755	1753567200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7756	1753567200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7765	1753567620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6777.00	\N
7758	1753567560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6777.00	\N
7759	1753567200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6777.00	\N
7760	1753567200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6777.00	\N
7796	1753617600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7769	1753585260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7770	1753585200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7771	1753584480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7772	1753577280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7773	1753585260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7774	1753585200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7775	1753584480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7776	1753577280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	10456.00	\N
7793	1753622760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7794	1753622640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7795	1753621920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7797	1753622760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
7798	1753622640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
7799	1753621920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
7801	1753625160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7802	1753625160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7803	1753624800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7805	1753625160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18654.00	\N
7806	1753625160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18654.00	\N
7807	1753624800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18654.00	\N
7800	1753617600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	18654.00	\N
7809	1753674720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7810	1753674480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7811	1753673760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7812	1753668000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7813	1753674720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
7814	1753674480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
7815	1753673760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
7816	1753668000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
7833	1753820340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7834	1753820280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7835	1753819200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7836	1753819200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7837	1753820340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2646.00	\N
7838	1753820280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2646.00	\N
7839	1753819200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2646.00	\N
7840	1753819200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2646.00	\N
7841	1753841220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7842	1753841160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7843	1753840800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7844	1753839360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7845	1753841220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3072.00	\N
7846	1753841160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3072.00	\N
7847	1753840800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3072.00	\N
7848	1753839360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3072.00	\N
7849	1753903920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7850	1753903800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7851	1753902720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7853	1753903920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
7854	1753903800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
7855	1753902720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
7857	1753904160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7858	1753904160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7910	1754126640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2554.00	\N
7861	1753904160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1085.00	\N
7862	1753904160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1085.00	\N
7901	1754126580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6349.00	\N
7865	1753904880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7866	1753904880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7859	1753904160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7852	1753899840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7869	1753904880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1055.00	\N
7870	1753904880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1055.00	\N
7863	1753904160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1085.00	\N
7856	1753899840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1748.00	\N
7873	1754115540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7874	1754115480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7875	1754114400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7876	1754111520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7877	1754115540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1095.00	\N
7878	1754115480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1095.00	\N
7879	1754114400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1095.00	\N
7880	1754111520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1095.00	\N
7881	1754126280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7885	1754126280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1584.00	\N
7889	1754126460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7884	1754121600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	18.00	\N
7913	1754126820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7893	1754126460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5945.00	\N
7906	1754126640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7922	1754127000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7883	1754125920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	7.00	\N
7897	1754126580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7882	1754126280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
7886	1754126280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6349.00	\N
7933	1754127180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2894.00	\N
7921	1754127060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7905	1754126640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7925	1754127060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
7909	1754126640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2554.00	\N
7917	1754126820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1064.00	\N
7929	1754127180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7926	1754127000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3093.00	\N
7887	1754125920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6349.00	\N
7937	1754127360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7938	1754127360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7941	1754127360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1048.00	\N
7942	1754127360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1048.00	\N
8025	1754147220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7945	1754127780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7946	1754127720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8026	1754147160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7949	1754127780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1548.00	\N
7950	1754127720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1548.00	\N
7993	1754128740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7962	1754128440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	5.00	\N
7953	1754128200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7954	1754128080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7939	1754127360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	8.00	\N
7957	1754128200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12500.00	\N
7958	1754128080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12500.00	\N
7997	1754128740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1661.00	\N
7966	1754128440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1661.00	\N
7961	1754128440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7943	1754127360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12500.00	\N
7965	1754128440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1034.00	\N
8017	1754129880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8001	1754129400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7969	1754128500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8002	1754129160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
7973	1754128500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1344.00	\N
8005	1754129400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1605.00	\N
8006	1754129160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1605.00	\N
8018	1754129880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8003	1754128800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8021	1754129880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2070.00	\N
8022	1754129880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2070.00	\N
8007	1754128800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2070.00	\N
7977	1754128620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
7888	1754121600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	12500.00	\N
7981	1754128620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1255.00	\N
8027	1754146080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8009	1754129520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8010	1754129520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8028	1754141760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8013	1754129520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1511.00	\N
8014	1754129520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1511.00	\N
8029	1754147220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7359.00	\N
8030	1754147160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7359.00	\N
8031	1754146080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7359.00	\N
8032	1754141760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7359.00	\N
8041	1754330040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8042	1754330040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8043	1754328960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8044	1754323200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8045	1754330040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1041.00	\N
8046	1754330040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1041.00	\N
8047	1754328960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1041.00	\N
8048	1754323200	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1041.00	\N
8049	1754408520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8050	1754408520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8051	1754408160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8052	1754403840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8053	1754408520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3687.00	\N
8054	1754408520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3687.00	\N
8055	1754408160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3687.00	\N
8056	1754403840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3687.00	\N
8057	1754440440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8058	1754440200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8059	1754439840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8060	1754434080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8061	1754440440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13950.00	\N
8062	1754440200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13950.00	\N
8063	1754439840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13950.00	\N
8064	1754434080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	13950.00	\N
8065	1754461800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8066	1754461800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8069	1754461800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1197.00	\N
8070	1754461800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1197.00	\N
8073	1754462580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8074	1754462520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8067	1754461440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8068	1754454240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8077	1754462580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3042.00	\N
8078	1754462520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3042.00	\N
8071	1754461440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3042.00	\N
8072	1754454240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3042.00	\N
8081	1754518620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8082	1754518320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8083	1754517600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8085	1754518620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
8086	1754518320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
8087	1754517600	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1039.00	\N
8089	1754519340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8084	1754514720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
8088	1754514720	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8765.00	\N
8090	1754519040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8093	1754519340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8765.00	\N
8094	1754519040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8765.00	\N
8097	1754519460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8098	1754519400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8091	1754519040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8101	1754519460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1915.00	\N
8102	1754519400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1915.00	\N
8095	1754519040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8765.00	\N
8105	1754521560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8106	1754521560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8107	1754520480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8109	1754521560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2228.00	\N
8110	1754521560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2228.00	\N
8111	1754520480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2228.00	\N
8113	1754643900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8114	1754643600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8115	1754642880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8116	1754635680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8117	1754643900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1036.00	\N
8118	1754643600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1036.00	\N
8119	1754642880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1036.00	\N
8120	1754635680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1036.00	\N
8121	1754819220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8122	1754818920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8123	1754818560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8125	1754819220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1938.00	\N
8126	1754818920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1938.00	\N
8127	1754818560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1938.00	\N
8129	1754826000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8130	1754825760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8131	1754825760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8124	1754817120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8133	1754826000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2631.00	\N
8134	1754825760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2631.00	\N
8135	1754825760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2631.00	\N
8128	1754817120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2631.00	\N
8137	1755029100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8138	1755028800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8139	1755028800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8140	1755028800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8141	1755029100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	42312.00	\N
8142	1755028800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	42312.00	\N
8143	1755028800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	42312.00	\N
8144	1755028800	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	42312.00	\N
8145	1755050580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8146	1755050400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8147	1755050400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8149	1755050580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3649.00	\N
8150	1755050400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3649.00	\N
8151	1755050400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3649.00	\N
8153	1755056940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8154	1755056880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8155	1755056160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8148	1755048960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8157	1755056940	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1550.00	\N
8158	1755056880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1550.00	\N
8159	1755056160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1550.00	\N
8152	1755048960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3649.00	\N
8161	1755168660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8162	1755168480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8163	1755168480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8164	1755159840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8165	1755168660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1684.00	\N
8166	1755168480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1684.00	\N
8167	1755168480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1684.00	\N
8168	1755159840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1684.00	\N
8169	1755172140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8173	1755172140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6304.00	\N
8177	1755172260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8197	1755173160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2941.00	\N
8181	1755172260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2627.00	\N
8201	1755173460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8185	1755172320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8170	1755172080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8171	1755171360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8213	1755173700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6012.00	\N
8189	1755172320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8614.00	\N
8174	1755172080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8614.00	\N
8175	1755171360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8614.00	\N
8193	1755173160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8194	1755173160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8210	1755173520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8195	1755172800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
8205	1755173460	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4164.00	\N
8198	1755173160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4164.00	\N
8172	1755169920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	9.00	\N
8221	1755173760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1195.00	\N
8209	1755173700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8217	1755173760	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8214	1755173520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6012.00	\N
8199	1755172800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6012.00	\N
8176	1755169920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8614.00	\N
8225	1755174060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8229	1755174060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2227.00	\N
8233	1755174180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8226	1755173880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8237	1755174180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3617.00	\N
8230	1755173880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3617.00	\N
8241	1755261480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8245	1755261480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3438.00	\N
8249	1755261540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8242	1755261360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8243	1755260640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8244	1755260640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8253	1755261540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1731.00	\N
8246	1755261360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3438.00	\N
8247	1755260640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3438.00	\N
8248	1755260640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3438.00	\N
8257	1755406500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8258	1755406440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8259	1755406080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8260	1755401760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8261	1755406500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1128.00	\N
8262	1755406440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1128.00	\N
8263	1755406080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1128.00	\N
8264	1755401760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1128.00	\N
8265	1755492060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8266	1755491760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8267	1755491040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8268	1755482400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8269	1755492060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14819.00	\N
8270	1755491760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14819.00	\N
8271	1755491040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14819.00	\N
8272	1755482400	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14819.00	\N
8273	1755500880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8274	1755500760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8275	1755499680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8276	1755492480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8277	1755500880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1191.00	\N
8278	1755500760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1191.00	\N
8279	1755499680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1191.00	\N
8280	1755492480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1191.00	\N
8281	1755550140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8282	1755550080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8283	1755550080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8284	1755542880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8285	1755550140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3829.00	\N
8402	1755772200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8345	1755770700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8330	1755770400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8286	1755550080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3829.00	\N
8287	1755550080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3829.00	\N
8288	1755542880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3829.00	\N
8305	1755770040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8309	1755770040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3280.00	\N
8313	1755770160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8397	1755771840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
8365	1755771000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2176.00	\N
8349	1755770700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6784.00	\N
8317	1755770160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2330.00	\N
8334	1755770400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8127.00	\N
8358	1755770760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2535.00	\N
8381	1755771540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3788.00	\N
8321	1755770280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8306	1755770040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8307	1755768960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8353	1755770820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8325	1755770280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2066.00	\N
8310	1755770040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3280.00	\N
8311	1755768960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3280.00	\N
8329	1755770580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8333	1755770580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8127.00	\N
8369	1755771360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8337	1755770640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8370	1755771120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8382	1755771480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3788.00	\N
8335	1755770400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8127.00	\N
8341	1755770640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1029.00	\N
8373	1755771360	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1673.00	\N
8374	1755771120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1673.00	\N
8398	1755771840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1038.00	\N
8357	1755770820	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2535.00	\N
8385	1755771600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8378	1755771480	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8361	1755771000	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8354	1755770760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8393	1755771840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8377	1755771540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8331	1755770400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	8.00	\N
8389	1755771600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1016.00	\N
8394	1755771840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8395	1755771840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8401	1755772320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8308	1755764640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	14.00	\N
8405	1755772320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2301.00	\N
8409	1755772440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8413	1755772440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4699.00	\N
8406	1755772200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4699.00	\N
8399	1755771840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4699.00	\N
8312	1755764640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8127.00	\N
8417	1755822720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8418	1755822600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8419	1755822240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8420	1755815040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8421	1755822720	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4865.00	\N
8422	1755822600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4865.00	\N
8423	1755822240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4865.00	\N
8424	1755815040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4865.00	\N
8425	1755859620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8426	1755859320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8427	1755858240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8428	1755855360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8429	1755859620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3313.00	\N
8430	1755859320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3313.00	\N
8431	1755858240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3313.00	\N
8432	1755855360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3313.00	\N
8433	1755956340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8434	1755956160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8435	1755956160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8436	1755956160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8437	1755956340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1574.00	\N
8438	1755956160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1574.00	\N
8439	1755956160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1574.00	\N
8440	1755956160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1574.00	\N
8441	1755999900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8445	1755999900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7479.00	\N
8449	1755999960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8442	1755999720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8443	1755999360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8444	1755996480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8453	1755999960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8071.00	\N
8446	1755999720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8071.00	\N
8447	1755999360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8071.00	\N
8448	1755996480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8071.00	\N
8457	1756163400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8458	1756163160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8459	1756162080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8460	1756157760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8461	1756163400	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6717.00	\N
8462	1756163160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6717.00	\N
8463	1756162080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6717.00	\N
8464	1756157760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6717.00	\N
8465	1756249440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8466	1756249200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8467	1756248480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8469	1756249440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
8470	1756249200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
8471	1756248480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1078.00	\N
8473	1756250040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8474	1756249920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8545	1756370160	60	user_request	5	count	4.00	\N
8477	1756250040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
8478	1756249920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1200.00	\N
8505	1756303320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8481	1756250280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8482	1756250280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8475	1756249920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8468	1756248480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8485	1756250280	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2148.00	\N
8486	1756250280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2148.00	\N
8479	1756249920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2148.00	\N
8472	1756248480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2148.00	\N
8489	1756260180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8490	1756260000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8491	1756260000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8492	1756258560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8493	1756260180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5397.00	\N
8494	1756260000	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5397.00	\N
8495	1756260000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5397.00	\N
8496	1756258560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5397.00	\N
8497	1756303020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8498	1756302840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8499	1756301760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8501	1756303020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2441.00	\N
8502	1756302840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2441.00	\N
8503	1756301760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2441.00	\N
8506	1756303200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8507	1756303200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8500	1756298880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8509	1756303320	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7074.00	\N
8510	1756303200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7074.00	\N
8511	1756303200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7074.00	\N
8504	1756298880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7074.00	\N
8585	1756371600	60	user_request	5	count	4.00	\N
8561	1756370220	60	user_request	5	count	6.00	\N
8537	1756370100	60	user_request	5	count	2.00	\N
8522	1756369800	360	user_request	5	count	6.00	\N
8521	1756370040	60	user_request	5	count	4.00	\N
8546	1756370160	360	user_request	5	count	10.00	\N
8586	1756371600	360	user_request	5	count	4.00	\N
8523	1756369440	1440	user_request	5	count	16.00	\N
8587	1756370880	1440	user_request	5	count	4.00	\N
8601	1756382340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8602	1756382040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8603	1756380960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8604	1756379520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8605	1756382340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1843.00	\N
8606	1756382040	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1843.00	\N
8607	1756380960	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1843.00	\N
8608	1756379520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1843.00	\N
8609	1756441560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8610	1756441440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8611	1756441440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8612	1756440000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8613	1756441560	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1481.00	\N
8614	1756441440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1481.00	\N
8615	1756441440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1481.00	\N
8616	1756440000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1481.00	\N
8617	1756452180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8618	1756451880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8619	1756451520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8620	1756450080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8621	1756452180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	20532.00	\N
8622	1756451880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	20532.00	\N
8623	1756451520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	20532.00	\N
8624	1756450080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	20532.00	\N
8625	1756514640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8626	1756514520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8627	1756513440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8628	1756510560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8629	1756514640	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5435.00	\N
8630	1756514520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5435.00	\N
8631	1756513440	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5435.00	\N
8632	1756510560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5435.00	\N
8633	1756579920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8634	1756579680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8635	1756579680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8636	1756571040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8637	1756579920	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2059.00	\N
8638	1756579680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2059.00	\N
8639	1756579680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2059.00	\N
8640	1756571040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2059.00	\N
8649	1756674600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8650	1756674360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8651	1756673280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8652	1756671840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8653	1756674600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
8654	1756674360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
8655	1756673280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
8656	1756671840	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2163.00	\N
8657	1756728480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8658	1756728360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8659	1756728000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8660	1756722240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8661	1756728480	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2210.00	\N
8662	1756728360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2210.00	\N
8663	1756728000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2210.00	\N
8664	1756722240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2210.00	\N
8665	1756780200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8666	1756780200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8669	1756780200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14349.00	\N
8670	1756780200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14349.00	\N
8673	1756780800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8674	1756780560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8667	1756779840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8668	1756772640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8677	1756780800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1400.00	\N
8678	1756780560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1400.00	\N
8671	1756779840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14349.00	\N
8672	1756772640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14349.00	\N
8681	1756820100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8682	1756819800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8683	1756818720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8685	1756820100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14117.00	\N
8686	1756819800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14117.00	\N
8687	1756818720	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14117.00	\N
8689	1756820160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8690	1756820160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8691	1756820160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8684	1756812960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8693	1756820160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	21250.00	\N
8694	1756820160	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	21250.00	\N
8695	1756820160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	21250.00	\N
8688	1756812960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	21250.00	\N
8705	1756864860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8706	1756864800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8707	1756864800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8708	1756863360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8709	1756864860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6195.00	\N
8710	1756864800	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6195.00	\N
8711	1756864800	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6195.00	\N
8712	1756863360	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	6195.00	\N
8713	1756902540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8714	1756902240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8717	1756902540	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2907.00	\N
8718	1756902240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2907.00	\N
8721	1756902600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8720	1756893600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11013.00	\N
8761	1756903860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8725	1756902600	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4430.00	\N
8762	1756903680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8763	1756903680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8729	1756902840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8722	1756902600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8765	1756903860	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1071.00	\N
8733	1756902840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1357.00	\N
8726	1756902600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4430.00	\N
8766	1756903680	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1071.00	\N
8767	1756903680	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1071.00	\N
8737	1756903020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8738	1756902960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8769	1756905900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8741	1756903020	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11013.00	\N
8742	1756902960	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11013.00	\N
8770	1756905840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8771	1756905120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8745	1756903500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8780	1756913760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	4.00	\N
8749	1756903500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1173.00	\N
8753	1756903620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8746	1756903320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8715	1756902240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
8716	1756893600	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
8757	1756903620	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3788.00	\N
8750	1756903320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3788.00	\N
8719	1756902240	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	11013.00	\N
8764	1756903680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8773	1756905900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1303.00	\N
8774	1756905840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1303.00	\N
8775	1756905120	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1303.00	\N
8768	1756903680	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1303.00	\N
8777	1756915080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8778	1756914840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8779	1756913760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8781	1756915080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1380.00	\N
8782	1756914840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1380.00	\N
8783	1756913760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1380.00	\N
8801	1756916880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8802	1756916640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8803	1756916640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8805	1756916880	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
8806	1756916640	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
8807	1756916640	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
8784	1756913760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1449.00	\N
8809	1756972140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8813	1756972140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	26989.00	\N
8825	1756972200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8810	1756972080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8811	1756971360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8812	1756964160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8829	1756972200	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	48719.00	\N
8814	1756972080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	48719.00	\N
8815	1756971360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	48719.00	\N
8816	1756964160	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	48719.00	\N
8833	1757006100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8834	1757005920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8835	1757005920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8836	1757004480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8837	1757006100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	29361.00	\N
8838	1757005920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	29361.00	\N
8839	1757005920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	29361.00	\N
8840	1757004480	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	29361.00	\N
8841	1757077800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8842	1757077560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8843	1757076480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8844	1757075040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8845	1757077800	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1102.00	\N
8846	1757077560	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1102.00	\N
8847	1757076480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1102.00	\N
8848	1757075040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1102.00	\N
8849	1757112960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8850	1757112840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8851	1757112480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8852	1757105280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8853	1757112960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2590.00	\N
8854	1757112840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2590.00	\N
8855	1757112480	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2590.00	\N
8856	1757105280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2590.00	\N
8857	1757238060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8858	1757237760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8859	1757237760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8860	1757236320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8861	1757238060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1971.00	\N
8862	1757237760	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1971.00	\N
8863	1757237760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1971.00	\N
8864	1757236320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1971.00	\N
8865	1757361060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8866	1757360880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8867	1757360160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8868	1757357280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8869	1757361060	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14687.00	\N
8870	1757360880	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14687.00	\N
8871	1757360160	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14687.00	\N
8872	1757357280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	14687.00	\N
8873	1757454900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8874	1757454840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8875	1757453760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8877	1757454900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1044.00	\N
8878	1757454840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1044.00	\N
8879	1757453760	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1044.00	\N
8881	1757455260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8885	1757455260	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8825.00	\N
8889	1757455440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8882	1757455200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8883	1757455200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8876	1757448000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8893	1757455440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1162.00	\N
8886	1757455200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8825.00	\N
8887	1757455200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8825.00	\N
8880	1757448000	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	8825.00	\N
8897	1757458440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8898	1757458440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8899	1757458080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8900	1757458080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8901	1757458440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1050.00	\N
8902	1757458440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1050.00	\N
8903	1757458080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1050.00	\N
8904	1757458080	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1050.00	\N
8905	1757483040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8906	1757482920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8907	1757482560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8908	1757478240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8909	1757483040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
8910	1757482920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
8911	1757482560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
8912	1757478240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2500.00	\N
8961	1757835660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8962	1757835360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8963	1757835360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8964	1757831040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8965	1757835660	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1800.00	\N
8966	1757835360	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1800.00	\N
8937	1757559780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8938	1757559600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8913	1757495520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8914	1757495520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8915	1757495520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8916	1757488320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8917	1757495520	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2120.00	\N
8918	1757495520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2120.00	\N
8919	1757495520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2120.00	\N
8920	1757488320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2120.00	\N
8939	1757558880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8940	1757558880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
8941	1757559780	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1343.00	\N
8942	1757559600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1343.00	\N
8943	1757558880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1343.00	\N
8944	1757558880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1343.00	\N
8967	1757835360	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1800.00	\N
8968	1757831040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1800.00	\N
8977	1757844900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8978	1757844720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8981	1757844900	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4489.00	\N
8982	1757844720	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4489.00	\N
8985	1757845140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8986	1757845080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8979	1757844000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8980	1757841120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
8989	1757845140	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1473.00	\N
8990	1757845080	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1473.00	\N
8983	1757844000	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4489.00	\N
8984	1757841120	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4489.00	\N
8993	1758023160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8994	1758022920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8995	1758022560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8996	1758022560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
8997	1758023160	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1026.00	\N
8998	1758022920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1026.00	\N
8999	1758022560	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1026.00	\N
9000	1758022560	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1026.00	\N
9001	1758074700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9002	1758074400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9003	1758074400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9004	1758072960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9005	1758074700	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
9006	1758074400	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
9007	1758074400	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
9008	1758072960	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2695.00	\N
9009	1758143580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9010	1758143520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9011	1758143520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9012	1758143520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9013	1758143580	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3971.00	\N
9014	1758143520	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3971.00	\N
9015	1758143520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3971.00	\N
9016	1758143520	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3971.00	\N
9017	1758194040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9018	1758193920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9019	1758193920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9020	1758193920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9021	1758194040	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1043.00	\N
9022	1758193920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1043.00	\N
9023	1758193920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1043.00	\N
9024	1758193920	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1043.00	\N
9025	1758244440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9026	1758244320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9027	1758244320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9028	1758244320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9029	1758244440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19226.00	\N
9030	1758244320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19226.00	\N
9031	1758244320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19226.00	\N
9032	1758244320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	19226.00	\N
9033	1758288840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9034	1758288600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9035	1758287520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9037	1758288840	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7363.00	\N
9038	1758288600	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7363.00	\N
9039	1758287520	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7363.00	\N
9041	1758291960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9058	1758292200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
9045	1758291960	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3505.00	\N
9036	1758284640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	8.00	\N
9049	1758292080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9042	1758291840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
9093	1758293340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1543.00	\N
9077	1758292500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1001.00	\N
9053	1758292080	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3825.00	\N
9046	1758291840	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3825.00	\N
9062	1758292200	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5059.00	\N
9094	1758293280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1543.00	\N
9057	1758292380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9095	1758293280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1543.00	\N
9081	1758293100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9061	1758292380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5059.00	\N
9082	1758292920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9043	1758291840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	6.00	\N
9065	1758292440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9040	1758284640	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	7363.00	\N
9097	1758320340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9098	1758320280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9069	1758292440	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3734.00	\N
9099	1758319200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9100	1758314880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9101	1758320340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1603.00	\N
9073	1758292500	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9085	1758293100	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2932.00	\N
9086	1758292920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2932.00	\N
9047	1758291840	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5059.00	\N
9089	1758293340	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9090	1758293280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9091	1758293280	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9102	1758320280	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1603.00	\N
9103	1758319200	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1603.00	\N
9104	1758314880	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1603.00	\N
9105	1758338220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9106	1758337920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9107	1758337920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9108	1758335040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9109	1758338220	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4156.00	\N
9110	1758337920	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4156.00	\N
9111	1758337920	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4156.00	\N
9112	1758335040	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	4156.00	\N
9113	1758372180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9114	1758372120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9115	1758371040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9116	1758365280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9117	1758372180	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
9118	1758372120	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
9119	1758371040	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
9120	1758365280	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	1060.00	\N
9121	1758682740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9122	1758682440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9123	1758682080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9124	1758677760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9125	1758682740	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2265.00	\N
9126	1758682440	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2265.00	\N
9127	1758682080	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2265.00	\N
9128	1758677760	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	2265.00	\N
9129	1758747240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9130	1758747240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9131	1758746880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9132	1758738240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9133	1758747240	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3823.00	\N
9134	1758747240	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3823.00	\N
9135	1758746880	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3823.00	\N
9136	1758738240	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	3823.00	\N
9137	1758748380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9138	1758748320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	1.00	\N
9141	1758748380	60	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5548.00	\N
9142	1758748320	360	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5548.00	\N
9139	1758748320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	2.00	\N
9143	1758748320	1440	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5548.00	\N
9140	1758748320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	count	3.00	\N
9144	1758748320	10080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	max	5548.00	\N
\.


--
-- Data for Name: pulse_entries; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.pulse_entries (id, "timestamp", type, key, value) FROM stdin;
1	1718306060	user_request	2	\N
2	1718306064	user_request	2	\N
3	1718306070	user_request	2	\N
4	1718306077	user_request	2	\N
5	1718306077	user_request	2	\N
6	1718306105	user_request	2	\N
7	1718306105	user_job	2	\N
8	1718306105	user_job	2	\N
9	1718306105	user_job	2	\N
10	1718306106	user_request	2	\N
11	1718306130	user_request	2	\N
12	1718306191	user_request	2	\N
13	1718359623	user_request	2	\N
14	1718359627	user_request	2	\N
15	1718359627	user_request	2	\N
16	1718359648	user_request	2	\N
17	1718359648	user_job	2	\N
18	1718359648	user_job	2	\N
19	1718359648	user_job	2	\N
20	1718359648	user_request	2	\N
21	1718359671	user_request	2	\N
22	1718359671	user_job	2	\N
23	1718359672	user_request	2	\N
24	1718359824	user_request	2	\N
25	1718359843	user_request	2	\N
26	1718359843	user_job	2	\N
27	1718359843	user_job	2	\N
28	1718359843	user_job	2	\N
29	1718359843	user_request	2	\N
30	1718359851	user_request	2	\N
31	1718359884	user_request	2	\N
32	1718360243	user_request	2	\N
33	1718360243	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	1718360243
34	1718360251	user_request	2	\N
35	1718360251	user_request	2	\N
36	1718360258	user_request	2	\N
37	1718360258	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	1718360258
38	1718360364	user_request	2	\N
39	1718360365	user_request	2	\N
40	1718360372	user_request	2	\N
41	1718360372	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:87"]	1718360372
42	1718360379	user_request	2	\N
43	1718360403	user_request	2	\N
44	1718360428	user_request	2	\N
45	1718360435	user_request	2	\N
46	1718360447	user_request	2	\N
47	1718360484	user_request	1	\N
48	1718360485	user_request	1	\N
49	1718360485	user_request	1	\N
50	1718360521	user_request	1	\N
51	1718360607	user_request	1	\N
52	1718360610	user_request	1	\N
53	1718360611	user_request	1	\N
54	1718360621	user_request	1	\N
55	1718361691	user_request	1	\N
56	1718361691	user_request	1	\N
57	1718361694	user_request	1	\N
58	1718361703	user_request	1	\N
59	1718361703	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	1718361703
60	1718361715	user_request	1	\N
61	1718361743	user_request	1	\N
62	1718361743	user_request	1	\N
63	1718361746	user_request	1	\N
64	1718361888	user_request	1	\N
65	1718361889	user_request	1	\N
66	1718361891	user_request	1	\N
67	1718361892	user_request	1	\N
68	1718363403	user_request	1	\N
69	1718363438	user_request	1	\N
70	1718364552	user_request	1	\N
71	1718365476	user_request	1	\N
72	1718366515	user_request	1	\N
73	1718372485	user_request	1	\N
74	1718372897	user_request	1	\N
75	1718373882	user_request	1	\N
76	1718377902	user_request	1	\N
77	1718378720	user_request	1	\N
78	1718449479	user_request	1	\N
79	1718449479	user_request	1	\N
80	1718449480	user_request	1	\N
81	1718449761	user_request	1	\N
82	1718456327	user_request	1	\N
83	1718459772	user_request	1	\N
84	1718465878	user_request	1	\N
85	1718475995	user_request	1	\N
86	1718519361	user_request	1	\N
87	1718521793	user_request	1	\N
88	1718552142	user_request	1	\N
89	1718552722	user_request	1	\N
90	1718552722	user_request	1	\N
91	1718565150	user_request	1	\N
92	1718637319	user_request	1	\N
93	1718648459	user_request	1	\N
94	1718780279	user_request	1	\N
95	1718787415	user_request	1	\N
96	1718789620	user_request	1	\N
97	1718798059	user_request	1	\N
98	1718798060	user_request	1	\N
99	1718800978	user_request	1	\N
100	1718801272	user_request	1	\N
101	1718801861	user_request	1	\N
102	1718802064	user_request	1	\N
103	1718803521	user_request	1	\N
104	1718804282	user_request	1	\N
105	1718806394	user_request	1	\N
106	1718807130	user_request	1	\N
107	1718807254	user_request	1	\N
108	1718807331	user_request	1	\N
109	1718807590	user_request	1	\N
110	1718807849	user_request	1	\N
111	1718808405	user_request	1	\N
112	1718819026	user_request	1	\N
113	1718822942	user_request	1	\N
114	1718830742	user_request	1	\N
115	1718830835	user_request	1	\N
116	1718831062	user_request	1	\N
117	1718831379	user_request	1	\N
118	1718832618	user_request	1	\N
119	1718896103	user_request	1	\N
120	1718898787	user_request	1	\N
121	1718899364	user_request	1	\N
122	1718899670	user_request	1	\N
123	1718900212	user_request	1	\N
124	1718908417	user_request	1	\N
125	1718912645	user_request	1	\N
126	1718915429	user_request	1	\N
127	1718915430	user_request	1	\N
128	1718916035	user_request	1	\N
129	1718916369	user_request	1	\N
130	1718916485	user_request	1	\N
131	1718917224	user_request	1	\N
132	1718920938	user_request	1	\N
133	1718977727	user_request	1	\N
134	1718977728	user_request	1	\N
135	1718977728	user_request	1	\N
136	1718977777	user_request	1	\N
137	1718977777	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	1718977777
138	1718977782	user_request	1	\N
139	1718977782	exception	["ErrorException","app\\/Http\\/Controllers\\/SkillController.php:88"]	1718977782
140	1718977789	user_request	1	\N
141	1718977871	user_request	1	\N
142	1718977871	user_request	1	\N
143	1718977877	user_request	1	\N
144	1718977877	user_request	1	\N
145	1718983412	user_request	1	\N
146	1718989884	user_request	1	\N
147	1719002469	user_request	1	\N
148	1719002491	user_request	1	\N
149	1719002495	user_request	1	\N
150	1719002495	user_request	1	\N
151	1719002937	user_request	1	\N
152	1719003056	user_request	1	\N
153	1719003056	user_request	1	\N
154	1719005920	user_request	1	\N
155	1719007120	user_request	1	\N
156	1719036495	user_request	1	\N
157	1719038451	user_request	1	\N
158	1719060048	user_request	1	\N
159	1719118029	user_request	1	\N
160	1719124997	user_request	1	\N
161	1719161486	user_request	1	\N
162	1719211947	user_request	1	\N
163	1719213236	user_request	1	\N
164	1719234800	user_request	1	\N
165	1719242991	user_request	1	\N
166	1719385037	user_request	1	\N
167	1719385040	user_request	1	\N
168	1719385041	user_request	1	\N
169	1719421341	user_request	1	\N
170	1719421716	user_request	1	\N
171	1719422811	user_request	1	\N
172	1719423155	user_request	1	\N
173	1719423233	user_request	1	\N
174	1719423357	user_request	1	\N
175	1719423643	user_request	1	\N
176	1719424666	user_request	1	\N
177	1719463031	user_request	1	\N
178	1719463752	user_request	1	\N
179	1719463873	user_request	1	\N
180	1719496756	user_request	1	\N
181	1719522308	user_request	1	\N
182	1719679497	user_request	1	\N
183	1719679498	user_request	1	\N
184	1719679498	user_request	1	\N
185	1719722877	user_request	1	\N
186	1719722878	user_request	1	\N
187	1719722878	user_request	1	\N
188	1719723795	user_request	1	\N
189	1719724825	user_request	1	\N
190	1719955422	user_request	1	\N
191	1719955422	user_request	1	\N
192	1719955423	user_request	1	\N
193	1720069496	user_request	1	\N
194	1720069496	user_request	1	\N
195	1720069496	user_request	1	\N
196	1720075817	user_request	1	\N
197	1720093474	user_request	3	\N
198	1720093474	user_request	3	\N
199	1720093474	user_request	3	\N
200	1720093654	user_request	3	\N
201	1720093654	user_request	3	\N
202	1720093656	user_request	3	\N
203	1720093662	user_request	3	\N
204	1720093670	user_request	3	\N
205	1720093695	user_request	3	\N
206	1720093700	user_request	3	\N
207	1720616982	user_request	1	\N
208	1721318849	exception	["Illuminate\\\\Database\\\\QueryException","app\\/Http\\/Middleware\\/HandleInertiaRequests.php:38"]	1721318849
209	1721318875	user_request	1	\N
210	1721318877	user_request	1	\N
211	1721318878	user_request	1	\N
212	1721318888	user_request	1	\N
213	1721318903	user_request	1	\N
214	1721318946	user_request	1	\N
215	1721318947	user_request	1	\N
216	1721320228	user_request	1	\N
217	1721475688	user_request	3	\N
218	1721475689	user_request	3	\N
219	1721475689	user_request	3	\N
220	1721475694	user_request	3	\N
221	1721475694	user_request	3	\N
222	1721475870	user_request	3	\N
223	1721475884	user_request	3	\N
224	1721475970	user_request	3	\N
225	1721476057	user_request	3	\N
226	1721476058	user_request	3	\N
227	1721476058	user_request	3	\N
228	1721476119	user_request	3	\N
229	1721476130	user_request	3	\N
230	1721476268	user_request	3	\N
231	1721476268	user_request	3	\N
232	1721476272	user_request	3	\N
233	1721476272	user_request	3	\N
234	1721476272	user_request	3	\N
235	1721513067	user_request	1	\N
236	1721513072	user_request	1	\N
237	1721513073	user_request	1	\N
238	1721513144	user_request	1	\N
239	1721513145	user_request	1	\N
240	1721513157	user_request	1	\N
241	1721513159	user_request	1	\N
242	1721513985	user_request	1	\N
243	1721514122	user_request	1	\N
244	1721514869	user_request	1	\N
245	1721516610	user_request	1	\N
246	1721552014	user_request	1	\N
247	1722671676	user_request	1	\N
248	1722671680	user_request	1	\N
249	1722671681	user_request	1	\N
250	1722711965	user_request	3	\N
251	1722711975	user_request	3	\N
252	1722711993	user_request	3	\N
253	1722711995	user_request	3	\N
254	1722711995	user_request	3	\N
255	1722712010	user_request	3	\N
256	1722712011	user_request	3	\N
257	1722712012	user_request	3	\N
258	1722712022	user_request	3	\N
259	1722712022	user_request	3	\N
260	1722712023	user_request	3	\N
261	1722712023	user_request	3	\N
262	1722712045	user_request	3	\N
263	1723301536	user_request	3	\N
264	1723301537	user_request	3	\N
265	1723301538	user_request	3	\N
266	1723301545	user_request	3	\N
267	1723301577	user_request	3	\N
268	1723301578	user_request	3	\N
269	1723301592	user_request	3	\N
270	1723301595	user_request	3	\N
271	1723301608	user_request	3	\N
272	1723301609	user_request	3	\N
273	1723301624	user_request	3	\N
274	1723301684	user_request	3	\N
275	1723301695	user_request	3	\N
276	1723301696	user_request	3	\N
277	1723301715	user_request	3	\N
278	1723301719	user_request	3	\N
279	1723301757	user_request	3	\N
280	1723301758	user_request	3	\N
281	1723301785	user_request	3	\N
282	1723301809	user_request	3	\N
283	1723301862	user_request	3	\N
284	1723301884	user_request	3	\N
285	1723301895	user_request	3	\N
286	1723301907	user_request	3	\N
287	1723301908	user_request	3	\N
288	1723301928	user_request	3	\N
289	1723301932	user_request	3	\N
290	1723301953	user_request	3	\N
291	1723301954	user_request	3	\N
292	1723301965	user_request	3	\N
293	1723301984	user_request	3	\N
294	1723302032	user_request	3	\N
295	1723302033	user_request	3	\N
296	1723302050	user_request	3	\N
297	1723302069	user_request	3	\N
298	1723302071	user_request	3	\N
299	1723302072	user_request	3	\N
300	1723311770	user_request	3	\N
301	1725022535	user_request	3	\N
302	1725022536	user_request	3	\N
303	1725022536	user_request	3	\N
304	1725022548	user_request	3	\N
305	1725022564	user_request	3	\N
306	1725022564	user_request	3	\N
307	1725022580	user_request	3	\N
308	1725022581	user_request	3	\N
309	1725022629	user_request	3	\N
310	1725022629	user_request	3	\N
311	1725022696	user_request	3	\N
312	1725022697	user_request	3	\N
313	1725022877	user_request	3	\N
314	1725028849	user_request	3	\N
315	1725030148	user_request	3	\N
316	1727012053	user_request	1	\N
317	1727012054	user_request	1	\N
318	1727012054	user_request	1	\N
319	1727012061	user_request	1	\N
320	1727012067	user_request	1	\N
321	1727012068	user_request	1	\N
322	1727012088	user_request	1	\N
323	1727012098	user_request	1	\N
324	1727012121	user_request	1	\N
325	1727012122	user_request	1	\N
326	1727014641	user_request	1	\N
327	1727015044	user_request	1	\N
328	1727015064	user_request	1	\N
329	1727015066	user_request	1	\N
330	1727015109	user_request	1	\N
331	1727015110	user_request	1	\N
332	1727015111	user_request	1	\N
333	1727015111	user_request	1	\N
334	1727015113	user_request	1	\N
335	1727016267	user_request	1	\N
336	1727016296	user_request	1	\N
337	1727016297	user_request	1	\N
338	1727016348	user_request	1	\N
339	1727016350	user_request	1	\N
340	1727017474	user_request	1	\N
341	1727017591	user_request	1	\N
342	1727018219	user_request	1	\N
343	1727018220	user_request	1	\N
344	1727019797	user_request	1	\N
345	1727020341	user_request	1	\N
346	1727020361	user_request	1	\N
347	1727020361	user_request	1	\N
348	1727020372	user_request	1	\N
349	1727020373	user_request	1	\N
350	1727020375	user_request	1	\N
351	1727020375	user_request	1	\N
352	1727020437	user_request	1	\N
353	1727020437	user_request	1	\N
354	1727021529	user_request	1	\N
355	1727021541	user_request	1	\N
356	1727021550	user_request	1	\N
357	1727021555	user_request	1	\N
358	1727021556	user_request	1	\N
359	1727021762	user_request	1	\N
360	1727021764	user_request	1	\N
361	1727021765	user_request	1	\N
362	1727021781	user_request	1	\N
363	1727021782	user_request	1	\N
364	1727021792	user_request	1	\N
365	1727022039	user_request	1	\N
366	1727022042	user_request	1	\N
367	1727022046	user_request	1	\N
368	1727022703	user_request	1	\N
369	1727028430	user_request	1	\N
370	1727028440	user_request	1	\N
371	1727031050	user_request	1	\N
372	1727036709	user_request	1	\N
373	1727169640	user_request	1	\N
374	1727169643	user_request	1	\N
375	1727169644	user_request	1	\N
376	1727336607	user_request	1	\N
377	1734260277	user_request	3	\N
378	1734260278	user_request	3	\N
379	1734260278	user_request	3	\N
380	1734260343	user_request	3	\N
381	1734260344	user_request	3	\N
382	1734260349	user_request	3	\N
383	1734262805	user_request	3	\N
384	1734263255	user_request	1	\N
385	1734263256	user_request	1	\N
386	1734263256	user_request	1	\N
387	1734263327	user_request	3	\N
388	1734263406	user_request	3	\N
389	1734263406	user_request	3	\N
390	1734263431	user_request	3	\N
391	1734263432	user_request	3	\N
392	1734263437	user_request	3	\N
393	1734263439	user_request	3	\N
394	1734263442	user_request	3	\N
395	1734263444	user_request	3	\N
396	1734263447	user_request	3	\N
397	1734263449	user_request	3	\N
398	1734263452	user_request	3	\N
399	1734263453	user_request	3	\N
400	1734263456	user_request	3	\N
401	1734263458	user_request	3	\N
402	1734263461	user_request	3	\N
403	1734263462	user_request	3	\N
404	1734263465	user_request	3	\N
405	1734263467	user_request	3	\N
406	1734263515	user_request	3	\N
407	1734263515	exception	["Illuminate\\\\Database\\\\UniqueConstraintViolationException","app\\/Http\\/Controllers\\/SkillController.php:30"]	1734263515
408	1734263540	user_request	3	\N
409	1734263544	user_request	3	\N
410	1734263554	user_request	3	\N
411	1734263583	user_request	3	\N
412	1734263584	user_request	3	\N
413	1734263590	user_request	3	\N
414	1734263637	user_request	3	\N
415	1734263638	user_request	3	\N
416	1734263663	user_request	3	\N
417	1734263688	user_request	3	\N
418	1734263700	user_request	3	\N
419	1734263721	user_request	3	\N
420	1734263756	user_request	3	\N
421	1734263757	user_request	3	\N
422	1734263780	user_request	3	\N
423	1734263781	user_request	3	\N
424	1734263785	user_request	3	\N
425	1734263786	user_request	3	\N
426	1734263805	user_request	3	\N
427	1734263808	user_request	3	\N
428	1734263827	user_request	3	\N
429	1734263858	user_request	3	\N
430	1734263859	user_request	3	\N
431	1734263864	user_request	3	\N
432	1734263865	user_request	3	\N
433	1734263878	user_request	3	\N
434	1734263880	user_request	3	\N
435	1734263889	user_request	3	\N
436	1734263890	user_request	3	\N
437	1734263909	user_request	3	\N
438	1734263910	user_request	3	\N
439	1734263923	user_request	3	\N
440	1734263928	user_request	3	\N
441	1734263944	user_request	3	\N
442	1734263944	user_request	3	\N
443	1734263953	user_request	3	\N
444	1734263968	user_request	3	\N
445	1734263969	user_request	3	\N
446	1734263970	user_request	3	\N
447	1734264009	user_request	3	\N
448	1734264034	user_request	3	\N
449	1734264068	user_request	3	\N
450	1734264068	user_request	3	\N
451	1734264118	user_request	3	\N
452	1734264118	user_request	3	\N
453	1734264178	user_request	3	\N
454	1734264178	user_request	3	\N
455	1734264219	user_request	3	\N
456	1734264219	user_request	3	\N
457	1734264257	user_request	3	\N
458	1734264783	user_request	1	\N
459	1734265666	user_request	3	\N
460	1734272110	user_request	1	\N
461	1734272110	user_request	1	\N
462	1734272117	user_request	1	\N
463	1734272915	user_request	1	\N
464	1734273165	user_request	3	\N
465	1734273165	user_request	3	\N
466	1734273166	user_request	3	\N
467	1734273278	user_request	1	\N
468	1734273286	user_request	3	\N
469	1734273286	user_request	1	\N
470	1734273307	user_request	3	\N
471	1734273310	user_request	3	\N
472	1734273370	user_request	3	\N
473	1734273371	user_request	3	\N
474	1734273371	user_request	3	\N
475	1734273570	user_request	1	\N
476	1734273853	user_request	1	\N
477	1734273866	user_request	3	\N
478	1734273909	user_request	3	\N
479	1734274228	user_request	1	\N
480	1734275239	user_request	3	\N
481	1734275239	user_request	1	\N
482	1734275250	user_request	1	\N
483	1734276207	user_request	3	\N
484	1734276217	user_request	1	\N
485	1734276803	user_request	1	\N
486	1734278807	user_request	1	\N
487	1734278821	user_request	1	\N
488	1734278822	user_request	1	\N
489	1734278825	user_request	1	\N
490	1734279865	user_request	1	\N
491	1734280380	user_request	1	\N
492	1734280401	user_request	1	\N
493	1734280631	user_request	3	\N
494	1734281436	user_request	1	\N
495	1734282216	user_request	1	\N
496	1734282280	user_request	1	\N
497	1734282286	user_request	1	\N
498	1734282286	user_request	1	\N
499	1734282961	user_request	1	\N
500	1734283292	user_request	1	\N
501	1734283294	user_request	1	\N
502	1734283296	user_request	1	\N
503	1734283299	user_request	1	\N
504	1734283301	user_request	1	\N
505	1734283302	user_request	1	\N
506	1734283578	user_request	3	\N
507	1734283690	user_request	3	\N
508	1734283699	user_request	3	\N
509	1734283716	user_request	3	\N
510	1734283758	user_request	3	\N
511	1734283758	user_request	3	\N
512	1734285578	user_request	1	\N
513	1734287643	user_request	1	\N
514	1734289042	user_request	1	\N
515	1734289176	user_request	1	\N
516	1734289179	user_request	1	\N
517	1734289197	user_request	1	\N
518	1734290657	user_request	1	\N
519	1734290981	user_request	3	\N
520	1736960228	user_request	1	\N
521	1736960229	user_request	1	\N
522	1736960229	user_request	1	\N
523	1736960238	user_request	1	\N
524	1736961198	user_request	1	\N
525	1736963426	user_request	1	\N
526	1736963872	user_request	1	\N
527	1736963881	user_request	1	\N
528	1736964376	user_request	1	\N
529	1736964527	user_request	1	\N
530	1736964535	user_request	1	\N
531	1736965941	user_request	1	\N
532	1736966716	user_request	1	\N
533	1736967603	user_request	1	\N
534	1736967674	user_request	1	\N
535	1736967981	user_request	1	\N
536	1736970112	user_request	1	\N
537	1736970155	user_request	1	\N
538	1736970716	user_request	1	\N
539	1736970718	user_request	1	\N
540	1736970726	user_request	1	\N
541	1736970728	user_request	1	\N
542	1736970731	user_request	1	\N
543	1736970732	user_request	1	\N
544	1736970733	user_request	1	\N
545	1736970742	user_request	1	\N
546	1736970743	user_request	1	\N
547	1736970744	user_request	1	\N
548	1736970753	user_request	1	\N
549	1736970754	user_request	1	\N
550	1736970770	user_request	1	\N
551	1736970771	user_request	1	\N
552	1736970771	user_request	1	\N
553	1736970797	user_request	1	\N
554	1736970800	user_request	1	\N
555	1736970810	user_request	1	\N
556	1736970811	user_request	1	\N
557	1736970812	user_request	1	\N
558	1736972141	user_request	1	\N
559	1737706325	user_request	1	\N
560	1737706325	user_request	1	\N
561	1737909785	user_request	1	\N
562	1737909786	user_request	1	\N
563	1737910062	user_request	1	\N
564	1737910063	user_request	1	\N
565	1737910748	user_request	1	\N
566	1737910840	user_request	1	\N
567	1737911070	user_request	1	\N
568	1737911268	user_request	1	\N
569	1737911404	user_request	1	\N
570	1737911592	user_request	1	\N
571	1737912468	user_request	1	\N
572	1737912473	user_request	1	\N
573	1737912473	user_request	1	\N
574	1737912567	user_request	1	\N
575	1737912568	user_request	1	\N
576	1737912585	user_request	1	\N
577	1738521391	user_request	1	\N
578	1739877476	user_request	3	\N
579	1739877476	user_request	3	\N
580	1739877477	user_request	3	\N
581	1739974205	user_request	1	\N
582	1739974205	user_request	1	\N
583	1739974205	user_request	1	\N
584	1739974215	user_request	1	\N
585	1739985872	user_request	1	\N
586	1739985872	user_request	1	\N
587	1739985873	user_request	1	\N
588	1739985878	user_request	1	\N
589	1739986232	user_request	1	\N
590	1739986597	user_request	3	\N
591	1739986603	user_request	3	\N
592	1739986604	user_request	3	\N
593	1739986610	user_request	3	\N
594	1739986665	user_request	3	\N
595	1739986665	user_request	3	\N
596	1739986687	user_request	3	\N
597	1739986687	user_request	3	\N
598	1739986747	user_request	3	\N
599	1739986751	user_request	3	\N
600	1739986759	user_request	3	\N
601	1739986760	user_request	3	\N
602	1739986765	user_request	3	\N
603	1739986796	user_request	3	\N
604	1739986871	user_request	3	\N
605	1739986872	user_request	3	\N
606	1739986879	user_request	3	\N
607	1739986881	user_request	3	\N
608	1739986912	user_request	3	\N
609	1739986916	user_request	3	\N
610	1739986973	user_request	3	\N
611	1739986976	user_request	3	\N
612	1739987080	user_request	3	\N
613	1739987080	user_request	3	\N
614	1739987135	user_request	3	\N
615	1739987136	user_request	3	\N
616	1739987246	user_request	3	\N
617	1739987247	user_request	3	\N
618	1739987658	user_request	3	\N
619	1739987674	user_request	3	\N
620	1739987700	user_request	3	\N
621	1739987702	user_request	3	\N
622	1739987709	user_request	3	\N
623	1739987882	user_request	3	\N
624	1739987883	user_request	3	\N
625	1739987926	user_request	3	\N
626	1739987929	user_request	3	\N
627	1739987958	user_request	3	\N
628	1739987959	user_request	3	\N
629	1739987961	user_request	3	\N
630	1739988005	user_request	3	\N
631	1739988013	user_request	3	\N
632	1739988079	user_request	3	\N
633	1739988082	user_request	3	\N
634	1739988098	user_request	3	\N
635	1739988479	user_request	3	\N
636	1739989381	user_request	3	\N
637	1739989502	user_request	1	\N
638	1739989619	user_request	3	\N
639	1739989624	user_request	3	\N
640	1739989659	user_request	3	\N
641	1739989682	user_request	3	\N
642	1739989689	user_request	3	\N
643	1739989693	user_request	3	\N
644	1739989696	user_request	3	\N
645	1739989701	user_request	3	\N
646	1739989704	user_request	3	\N
647	1739989712	user_request	3	\N
648	1739989718	user_request	3	\N
649	1739989721	user_request	3	\N
650	1739989724	user_request	3	\N
651	1739989737	user_request	3	\N
652	1739989743	user_request	3	\N
653	1739989760	user_request	3	\N
654	1739989767	user_request	3	\N
655	1739989768	user_request	3	\N
656	1739991970	user_request	1	\N
657	1739992170	user_request	1	\N
658	1739992315	user_request	1	\N
659	1739992752	user_request	1	\N
660	1739992783	user_request	1	\N
661	1739993045	user_request	3	\N
662	1739994144	user_request	3	\N
663	1739996666	user_request	1	\N
664	1739997472	user_request	1	\N
665	1739997737	user_request	1	\N
666	1739997746	user_request	1	\N
667	1739997751	user_request	1	\N
668	1739998163	user_request	1	\N
669	1739998171	user_request	1	\N
670	1739998172	user_request	1	\N
671	1739999328	user_request	1	\N
672	1739999538	user_request	3	\N
673	1741541708	user_request	1	\N
674	1741541709	user_request	1	\N
675	1741541709	user_request	1	\N
676	1741541718	user_request	1	\N
677	1741541773	user_request	1	\N
678	1741541774	user_request	1	\N
679	1741542144	user_request	1	\N
680	1741542150	user_request	1	\N
681	1741542154	user_request	1	\N
682	1741542163	user_request	1	\N
683	1741542907	user_request	1	\N
684	1741543864	user_request	1	\N
685	1741544687	user_request	1	\N
686	1741545002	user_request	1	\N
687	1741547424	user_request	1	\N
688	1741570308	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2879
689	1741622132	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1091
690	1741668040	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9069
691	1741668639	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5341
692	1741668985	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2692
693	1741669288	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1748
694	1741669420	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1034
695	1741669773	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3384
696	1741669946	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3027
697	1741670126	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2913
698	1741670488	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1618
699	1741670551	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4122
700	1741670666	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1052
701	1741671210	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1894
702	1741671810	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3157
703	1741672185	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4214
704	1741672468	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1543
705	1741672493	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4106
706	1741672617	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5219
707	1741672651	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7900
708	1741672658	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3477
709	1741672668	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6276
710	1741672716	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5640
711	1741672724	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8397
712	1741672740	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4444
713	1741746806	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11992
714	1741762066	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1921
715	1741762186	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1358
716	1741762429	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1066
717	1741762730	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3283
718	1741762790	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1077
719	1741762908	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1900
720	1741763026	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4721
721	1741763147	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2957
722	1741763207	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3556
723	1741763268	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10906
724	1741763285	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3550
725	1741763402	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11715
726	1741776349	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1038
727	1741787889	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1249
728	1741800112	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1378
729	1741901812	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7202
730	1742046439	user_request	1	\N
731	1742046443	user_request	1	\N
732	1742046444	user_request	1	\N
733	1742046447	user_request	1	\N
734	1742047235	user_request	1	\N
735	1742047235	user_request	1	\N
736	1742051965	user_request	1	\N
737	1742052791	user_request	1	\N
738	1742053108	user_request	1	\N
739	1742058056	user_request	1	\N
740	1742067937	user_request	3	\N
741	1742067937	user_request	3	\N
742	1742067937	user_request	3	\N
743	1742067950	user_request	3	\N
744	1742067985	user_request	3	\N
745	1742067985	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	1742067985
746	1742109856	user_request	1	\N
747	1742117539	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11687
748	1742136362	user_request	1	\N
749	1742136376	user_request	1	\N
750	1742136383	user_request	1	\N
751	1742136426	user_request	1	\N
752	1742136426	exception	["League\\\\Flysystem\\\\UnableToCreateDirectory","app\\/Http\\/Controllers\\/CharacterController.php:125"]	1742136426
753	1742136487	user_request	1	\N
754	1742136488	user_request	1	\N
755	1742136520	user_request	1	\N
756	1742136520	user_request	1	\N
757	1742136538	user_request	1	\N
758	1742136539	user_request	1	\N
759	1742136721	user_request	1	\N
760	1742136721	user_request	1	\N
761	1742182395	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10162
762	1742195630	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1449
763	1742206657	user_request	1	\N
764	1742207582	user_request	1	\N
765	1742212425	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1325
766	1742222857	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2244
767	1742236145	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1586
768	1742284960	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1810
769	1742312849	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1002
770	1742405054	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1691
771	1742405291	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2513
772	1742405711	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1382
773	1742406733	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6343
774	1742432041	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2238
775	1742432043	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4425
776	1742432644	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1094
777	1742503551	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1609
778	1742522122	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2528
779	1742644898	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2655
780	1742745574	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1017
781	1742745618	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1045
782	1742745732	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1031
783	1742745974	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1263
784	1742746454	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8255
785	1742746692	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2390
786	1742746811	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1575
787	1742747562	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1148
788	1742748134	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1015
789	1742748193	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1088
790	1742748320	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1321
791	1742748673	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1035
792	1742748793	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1291
793	1742749037	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2180
794	1742749217	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1049
795	1742749514	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1608
796	1742751090	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1011
797	1742751204	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1123
798	1742751211	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2163
799	1742751319	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1414
800	1742752818	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1031
801	1742752937	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9557
802	1742753537	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2766
803	1742753659	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1053
804	1742754257	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1110
805	1742754439	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2982
806	1742754558	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7464
807	1742754741	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1205
808	1742754926	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1653
809	1742755158	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11161
810	1742755278	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1482
811	1742755655	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1135
812	1742755759	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1006
813	1742756002	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1169
814	1742756181	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5542
815	1742757202	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1028
816	1742757866	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1661
817	1742758170	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6857
818	1742758176	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5269
819	1742758465	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1061
820	1742758778	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1233
821	1742758894	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1381
822	1742760984	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3074
823	1742761645	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12035
824	1742765429	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11971
825	1742765443	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3977
826	1742765547	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7484
827	1742765565	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1632
828	1742765733	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1101
829	1742765969	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2364
830	1742766211	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8093
831	1742766689	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4000
832	1742768732	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3641
833	1742768913	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1054
834	1742769032	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1193
835	1742839823	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4180
836	1742839829	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1336
837	1742845362	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6389
838	1742925060	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7183
839	1742955153	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8565
840	1742976315	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2260
841	1742976628	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4030
842	1742976746	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1083
843	1742976868	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6512
844	1742978068	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1060
845	1742979210	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3405
846	1742979571	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10379
847	1742982936	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1419
848	1742983054	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1225
849	1742983202	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3271
850	1742983293	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5206
851	1742983413	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8463
852	1742983774	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1107
853	1742983775	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7282
854	1742983849	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3187
855	1742984040	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3037
856	1742984258	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1009
857	1742984557	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2377
858	1742984855	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1005
859	1742985035	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1052
860	1742985278	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4122
861	1742985396	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8086
862	1742985575	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1016
863	1742985636	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2388
864	1742985696	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1841
865	1742985816	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3956
866	1742985876	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2081
867	1742986003	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1229
868	1742986010	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1145
869	1742986117	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2887
870	1742986537	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2168
871	1742986656	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12744
872	1742987080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5495
873	1742987200	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1147
874	1742987381	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4743
875	1742987393	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11136
876	1743123538	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6329
877	1743128386	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9828
878	1743172979	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6900
879	1743182658	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10216
880	1743192555	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11762
881	1743202340	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1178
882	1743282332	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6316
883	1743334844	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2376
884	1743359180	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11708
885	1743395479	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2500
886	1743436149	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3945
887	1743441291	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2808
888	1743452725	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	17766
889	1743460061	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1404
890	1743614625	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3656
891	1743651842	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4878
892	1743674467	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1324
893	1743674470	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1449
894	1743716521	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1812
895	1743721702	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1030
896	1743721765	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2319
897	1743721766	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3537
898	1743721818	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2648
899	1743723258	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1651
900	1743723498	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8599
901	1743723621	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2771
902	1743724162	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10493
903	1743724955	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1021
904	1743725239	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1059
905	1743725481	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2254
906	1743726476	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1057
907	1743726561	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1323
908	1743727281	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1169
909	1743728365	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6148
910	1743728782	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1161
911	1743728902	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1090
912	1743729264	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5241
913	1743730888	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2259
914	1743731965	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2980
915	1743732566	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1387
916	1743733168	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1010
917	1743733287	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8464
918	1743733646	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12452
919	1743734744	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2432
920	1743754112	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2300
921	1743754121	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1413
922	1743762003	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7105
923	1743955335	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3026
924	1743955444	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1805
925	1743955525	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1062
926	1743955625	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1217
927	1743956346	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1002
928	1743956480	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10311
929	1743956671	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1009
930	1743957023	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1249
931	1743957186	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1289
932	1743957247	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1052
933	1743957502	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3581
934	1743957792	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8068
935	1743957909	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1085
936	1743958090	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7989
937	1743958327	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1730
938	1743959184	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11002
939	1744018896	user_request	1	\N
940	1744018896	user_request	1	\N
941	1744018897	user_request	1	\N
942	1744018909	user_request	1	\N
943	1744019559	user_request	1	\N
944	1744019559	user_request	1	\N
945	1744019622	user_request	1	\N
946	1744019622	user_request	1	\N
947	1744039239	user_request	1	\N
948	1744039240	user_request	1	\N
949	1744039240	user_request	1	\N
950	1744039245	user_request	1	\N
951	1744041457	user_request	1	\N
952	1744044686	user_request	1	\N
953	1744045615	user_request	1	\N
954	1744046288	user_request	1	\N
955	1744047295	user_request	1	\N
956	1744047729	user_request	1	\N
957	1744049184	user_request	1	\N
958	1744050360	user_request	1	\N
959	1744051112	user_request	1	\N
960	1744051122	user_request	1	\N
961	1744052130	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1426
962	1744053681	user_request	1	\N
963	1744097928	user_request	1	\N
964	1744097928	user_request	1	\N
965	1744097928	user_request	1	\N
966	1744099342	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1316
967	1744166187	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1903
968	1744201258	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5890
969	1744317822	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1023
970	1744318962	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5516
971	1744319561	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1137
972	1744320039	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1071
973	1744354236	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5456
974	1744461662	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3976
975	1744521929	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6395
976	1744521996	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1959
977	1744522222	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7795
978	1744522343	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2033
979	1744522479	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11300
980	1744611571	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1186
981	1744670207	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4102
982	1744755019	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6823
983	1744765010	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4544
984	1744774612	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5747
985	1744777198	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1671
986	1744808675	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1006
987	1744809404	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1024
988	1744813040	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4026
989	1744814162	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2124
990	1744814164	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2037
991	1745029062	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1465
992	1745247997	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3379
993	1745248005	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1848
994	1745248011	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5116
995	1745248206	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1860
996	1745251931	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	22512
997	1745259439	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	26155
998	1745320660	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9202
999	1745320670	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1437
1000	1745375121	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1776
1001	1745402218	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1868
1002	1745404584	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3519
1003	1745404589	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1703
1004	1745404592	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1267
1005	1745413079	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1647
1006	1745413319	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3400
1007	1745413555	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1001
1008	1745413618	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2749
1009	1745413735	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7712
1010	1745413856	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5896
1011	1745414217	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12861
1012	1745414771	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8544
1013	1745415124	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1863
1014	1745415356	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4973
1015	1745415719	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4929
1016	1745415838	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1231
1017	1745416678	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2722
1018	1745416799	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1019
1019	1745416920	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4166
1020	1745417401	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7793
1021	1745417582	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4307
1022	1745417763	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2427
1023	1745418002	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1960
1024	1745418124	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9131
1025	1745418138	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5526
1026	1745428377	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10745
1027	1745446285	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1039
1028	1745491718	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7786
1029	1745555409	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1562
1030	1745595925	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2851
1031	1745659659	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1744
1032	1745671402	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1955
1033	1745760709	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4319
1034	1745761070	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10687
1035	1745761311	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11808
1036	1745761430	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1328
1037	1745761610	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3964
1038	1745761792	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4471
1039	1745761914	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9407
1040	1745761925	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1020
1041	1745762030	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1437
1042	1745762271	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1096
1043	1745762871	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4647
1044	1745763475	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7857
1045	1745763486	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2695
1046	1745763893	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7940
1047	1745764132	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6620
1048	1745808129	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9166
1049	1745977349	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2047
1050	1745997463	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1665
1051	1746004885	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2958
1052	1746004887	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8962
1053	1746066366	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1547
1054	1746252805	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1560
1055	1746252811	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1371
1056	1746262566	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2041
1057	1746318979	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1432
1058	1746358874	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1057
1059	1746410094	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1459
1060	1746506294	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2680
1061	1746573953	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4214
1062	1746579587	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1600
1063	1746586363	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5747
1064	1746625045	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6032
1065	1746626902	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2686
1066	1746740559	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1014
1067	1746851966	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2215
1068	1746852101	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2405
1069	1747090259	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1973
1070	1747095647	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1106
1071	1747156247	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1747
1072	1747185949	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3450
1073	1747188361	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4745
1074	1747263416	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1023
1075	1747263419	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1017
1076	1747271677	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1255
1077	1747357524	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4480
1078	1747383165	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	22391
1079	1747389225	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4997
1080	1747389232	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2742
1081	1747483352	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1109
1082	1747483583	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5284
1083	1747483704	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3680
1084	1747484439	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1025
1085	1747484545	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4407
1086	1747484664	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11011
1087	1747484784	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5246
1088	1747485808	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3272
1089	1747486286	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3647
1090	1747544866	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	16609
1091	1747548074	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6372
1092	1747610617	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3093
1093	1747610622	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1356
1094	1747701493	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5258
1095	1747725761	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1086
1096	1747793258	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5709
1097	1747803602	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2350
1098	1747803607	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2273
1099	1747803612	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2466
1100	1747848079	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1156
1101	1747918540	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1104
1102	1747977065	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	26527
1103	1748069649	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	19699
1104	1748102591	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4261
1105	1748102615	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7927
1106	1748160297	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1429
1107	1748301949	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14609
1108	1748306871	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1757
1109	1748311989	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	19405
1110	1748323628	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	15313
1111	1748398457	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3913
1112	1748426857	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9663
1113	1748429447	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11879
1114	1748444340	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	16513
1115	1748446816	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	19579
1116	1748465809	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	18722
1117	1748470843	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14997
1118	1748481005	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	13460
1119	1748485441	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11956
1120	1748494216	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14981
1121	1748507367	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	16046
1122	1748519355	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12356
1123	1748527956	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	16426
1124	1748542913	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14397
1125	1748544459	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2515
1126	1748544972	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5622
1127	1748550293	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14130
1128	1748559474	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1105
1129	1748563924	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2530
1130	1748629249	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3336
1131	1748680385	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3424
1132	1748680453	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1514
1133	1748680512	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1372
1134	1748680613	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5396
1135	1748680824	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2459
1136	1748681661	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1864
1137	1748682203	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3399
1138	1748682739	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2795
1139	1748682921	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3077
1140	1748683154	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8962
1141	1748683333	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2689
1142	1748683814	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3814
1143	1748683931	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1035
1144	1748684122	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3469
1145	1748776320	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1215
1146	1748776322	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1605
1147	1748776324	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1184
1148	1748776325	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2555
1149	1748776327	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1568
1150	1748776330	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1946
1151	1748838100	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4027
1152	1749005606	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5084
1153	1749081594	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1254
1154	1749087260	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2321
1155	1749131937	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2564
1156	1749432059	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1698
1157	1749432062	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1059
1158	1749493931	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4634
1159	1749493938	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2236
1160	1749522726	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3171
1161	1749522803	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2672
1162	1749523389	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5732
1163	1749646217	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1152
1164	1749733346	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2840
1165	1749867219	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1076
1166	1749867224	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1099
1167	1749916302	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1070
1168	1750210827	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4996
1169	1750212688	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4116
1170	1750290883	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1005
1171	1750291783	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5009
1172	1750291794	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1163
1173	1750304530	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9591
1174	1750306818	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1216
1175	1750405153	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2361
1176	1750419624	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2000
1177	1750420814	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1207
1178	1750457824	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1688
1179	1750514351	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1064
1180	1750619687	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1623
1181	1750653874	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1426
1182	1750654173	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1179
1183	1750654297	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4031
1184	1750654475	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1747
1185	1750654518	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1294
1186	1750654639	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3997
1187	1750654715	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9673
1188	1750654730	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5904
1189	1750654894	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1798
1190	1750654988	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7831
1191	1750655065	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1113
1192	1750655134	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2360
1193	1750671801	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	13692
1194	1750725215	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3527
1195	1750764935	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	17939
1196	1750950997	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2295
1197	1750960835	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4051
1198	1750971034	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1238
1199	1751054374	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5694
1200	1751058456	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6488
1201	1751058640	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7241
1202	1751059603	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2488
1203	1751060138	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1179
1204	1751061460	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1322
1205	1751132761	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1244
1206	1751348935	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1694
1207	1751348938	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1478
1208	1751348943	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2081
1209	1751350223	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3111
1210	1751358163	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1175
1211	1751358643	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4665
1212	1751359123	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1084
1213	1751359245	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1132
1214	1751476059	user_request	1	\N
1215	1751476059	user_request	1	\N
1216	1751476060	user_request	1	\N
1217	1751476076	user_request	1	\N
1218	1751476160	user_request	1	\N
1219	1751476160	user_request	1	\N
1220	1751476161	user_request	1	\N
1221	1751476170	user_request	1	\N
1222	1751476917	user_request	1	\N
1223	1751476931	user_request	1	\N
1224	1751477975	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4900
1225	1751478881	user_request	1	\N
1226	1751478935	user_request	1	\N
1227	1751479065	user_request	1	\N
1228	1751479066	user_request	1	\N
1229	1751480206	user_request	1	\N
1230	1751480960	user_request	1	\N
1231	1751480980	user_request	1	\N
1232	1751481240	user_request	1	\N
1233	1751481240	user_request	1	\N
1234	1751481253	user_request	1	\N
1235	1751481254	user_request	1	\N
1236	1751481255	user_request	1	\N
1237	1751482664	user_request	1	\N
1238	1751482832	user_request	1	\N
1239	1751482833	user_request	1	\N
1240	1751483249	user_request	1	\N
1241	1751483426	user_request	1	\N
1242	1751483431	user_request	1	\N
1243	1751483440	user_request	1	\N
1244	1751615928	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5089
1245	1751727388	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1362
1246	1751787550	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1064
1247	1751787753	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2453
1248	1751787873	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1045
1249	1751787933	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	13519
1250	1751787949	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1146
1251	1751788233	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3898
1252	1751868718	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2116
1253	1751868724	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2850
1254	1751868728	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1298
1255	1751965145	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1209
1256	1751968976	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2001
1257	1752028450	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6021
1258	1752101015	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1029
1259	1752101422	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3564
1260	1752101542	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5241
1261	1752102204	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2642
1262	1752103763	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2726
1263	1752105204	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1052
1264	1752105330	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1165
1265	1752106946	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5586
1266	1752108085	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1433
1267	1752110563	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3968
1268	1752111149	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1733
1269	1752111269	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1540
1270	1752111629	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1698
1271	1752112050	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3758
1272	1752112169	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3842
1273	1752112595	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8377
1274	1752112605	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7510
1275	1752113669	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1678
1276	1752113849	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1016
1277	1752142653	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3646
1278	1752274338	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1431
1279	1752551675	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5923
1280	1752552154	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1039
1281	1752552348	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7623
1282	1752552763	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4567
1283	1752552874	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2432
1284	1752552995	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1140
1285	1752553417	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8535
1286	1752612092	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2827
1287	1752631478	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4085
1288	1752654798	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	15834
1289	1752658236	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4144
1290	1752658370	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1212
1291	1752675405	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2124
1292	1752695996	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5674
1293	1752730701	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1200
1294	1752742377	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1717
1295	1752822354	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3363
1296	1752865271	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1266
1297	1752865273	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1225
1298	1752865276	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1463
1299	1752873318	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2229
1300	1752873318	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3147
1301	1753007871	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6887
1302	1753082048	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1338
1303	1753094040	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2180
1304	1753094046	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3132
1305	1753094052	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2202
1306	1753129116	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1432
1307	1753202278	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2720
1308	1753219596	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10435
1309	1753236805	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4120
1310	1753396993	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1075
1311	1753456489	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1310
1312	1753456656	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10161
1313	1753456839	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10456
1314	1753457559	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5649
1315	1753457993	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9079
1316	1753458280	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	9353
1317	1753459001	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1033
1318	1753459193	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1765
1319	1753459420	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2079
1320	1753459658	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1711
1321	1753459781	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4436
1322	1753460080	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1941
1323	1753460259	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3810
1324	1753460747	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1355
1325	1753460927	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8972
1326	1753460938	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7931
1327	1753465951	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3167
1328	1753567592	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4628
1329	1753567636	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6777
1330	1753585279	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8629
1331	1753585292	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6317
1332	1753585302	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	10456
1333	1753622791	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1078
1334	1753625160	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	18654
1335	1753674758	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1060
1336	1753674759	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1042
1337	1753674762	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1044
1338	1753820368	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2646
1339	1753841248	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3072
1340	1753903943	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1748
1341	1753904174	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1085
1342	1753904897	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1055
1343	1754115558	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1095
1344	1754126331	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1584
1345	1754126485	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5945
1346	1754126606	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6349
1347	1754126649	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2554
1348	1754126843	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1064
1349	1754127068	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3093
1350	1754127191	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2894
1351	1754127383	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1048
1352	1754127789	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1548
1353	1754128243	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	12500
1354	1754128452	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1034
1355	1754128546	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1344
1356	1754128630	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1255
1357	1754128663	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1165
1358	1754128752	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1661
1359	1754129425	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1605
1360	1754129537	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1511
1361	1754129890	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2070
1362	1754147263	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7359
1363	1754147274	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3708
1364	1754330064	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1041
1365	1754408529	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3687
1366	1754440477	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	13950
1367	1754461814	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1197
1368	1754462630	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3042
1369	1754518674	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1039
1370	1754519347	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8765
1371	1754519468	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1915
1372	1754521588	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2228
1373	1754643956	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1036
1374	1754819275	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1938
1375	1754826026	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2631
1376	1755029107	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	42312
1377	1755050616	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3649
1378	1755056973	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1550
1379	1755168661	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1684
1380	1755172193	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6304
1381	1755172298	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2627
1382	1755172372	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8614
1383	1755173214	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2941
1384	1755173514	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4164
1385	1755173741	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6012
1386	1755173816	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1195
1387	1755174099	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2227
1388	1755174218	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3617
1389	1755261529	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3438
1390	1755261554	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1731
1391	1755406553	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1128
1392	1755492069	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14819
1393	1755500905	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1191
1394	1755550165	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3829
1395	1755550172	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2210
1396	1755550176	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1373
1397	1755770042	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3280
1398	1755770178	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2330
1399	1755770288	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2066
1400	1755770608	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8127
1401	1755770659	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1029
1402	1755770718	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6784
1403	1755770847	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2535
1404	1755771028	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2176
1405	1755771402	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1673
1406	1755771558	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3788
1407	1755771642	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1016
1408	1755771851	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1038
1409	1755772340	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2301
1410	1755772451	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4699
1411	1755822748	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4865
1412	1755859636	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3313
1413	1755956377	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1574
1414	1755999954	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7479
1415	1755999967	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8071
1416	1756163453	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6717
1417	1756249496	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1078
1418	1756250043	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1200
1419	1756250299	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2148
1420	1756260210	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5397
1421	1756303056	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2441
1422	1756303371	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1502
1423	1756303372	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7074
1424	1756370082	user_request	5	\N
1425	1756370082	user_request	5	\N
1426	1756370082	user_request	5	\N
1427	1756370098	user_request	5	\N
1428	1756370153	user_request	5	\N
1429	1756370156	user_request	5	\N
1430	1756370166	user_request	5	\N
1431	1756370167	user_request	5	\N
1432	1756370184	user_request	5	\N
1433	1756370184	user_request	5	\N
1434	1756370226	user_request	5	\N
1435	1756370228	user_request	5	\N
1436	1756370229	user_request	5	\N
1437	1756370230	user_request	5	\N
1438	1756370231	user_request	5	\N
1439	1756370279	user_request	5	\N
1440	1756371610	user_request	5	\N
1441	1756371612	user_request	5	\N
1442	1756371612	user_request	5	\N
1443	1756371619	user_request	5	\N
1444	1756382350	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1843
1445	1756441599	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1481
1446	1756452184	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	20532
1447	1756514673	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5435
1448	1756579943	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1835
1449	1756579949	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2059
1450	1756674634	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2163
1451	1756728511	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2210
1452	1756780259	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14349
1453	1756780817	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1400
1454	1756820141	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14117
1455	1756820160	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	21250
1456	1756820190	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4123
1457	1756864893	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	6195
1458	1756902584	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2907
1459	1756902632	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4430
1460	1756902842	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1357
1461	1756903055	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11013
1462	1756903545	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1173
1463	1756903654	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3788
1464	1756903898	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1071
1465	1756905930	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1303
1466	1756915115	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1299
1467	1756915117	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1314
1468	1756915120	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1380
1469	1756916893	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1449
1470	1756972170	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	26989
1471	1756972199	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	11057
1472	1756972221	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	48719
1473	1757006103	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	29361
1474	1757077830	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1102
1475	1757112991	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2590
1476	1757238066	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1971
1477	1757361077	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	14687
1478	1757454929	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1044
1479	1757455287	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	8825
1480	1757455467	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1162
1481	1757458469	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1050
1482	1757483093	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2500
1483	1757495543	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1758
1484	1757495547	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2120
1485	1757495552	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1923
1486	1757559785	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1144
1487	1757559787	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1343
1488	1757559790	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1333
1489	1757835686	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1800
1490	1757835690	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1067
1491	1757844911	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4489
1492	1757845155	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1473
1493	1758023215	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1026
1494	1758074703	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2695
1495	1758143602	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3971
1496	1758194065	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1043
1497	1758244456	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	19226
1498	1758288875	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	7363
1499	1758292007	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3505
1500	1758292125	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3825
1501	1758292384	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5059
1502	1758292486	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3734
1503	1758292546	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1001
1504	1758293146	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2932
1505	1758293341	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1543
1506	1758320348	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1603
1507	1758338256	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	4156
1508	1758372187	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1060
1509	1758682776	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	2265
1510	1758747284	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	3823
1511	1758748420	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	5548
1512	1758749078	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1838
1513	1758749920	slow_request	["GET","\\/","App\\\\Http\\\\Controllers\\\\PageController@welcome"]	1131
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
1YydqX2UeXs73WfK2pOMpXSnGucoJU6ED4phSbUj	\N	206.168.34.44	Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)	YTozOntzOjY6Il90b2tlbiI7czo0MDoidWlrdmhyWTZXcklHSFFzaEVuV3ltaHlFY3YzNFdKN1pQa2ZQUXJFNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8yMTcuMTU0LjE0LjEwMS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758819124
XvcrebkOpqlmnZRm5rzHrLxcJHYljDx7retigWEj	\N	92.63.197.197	Mozilla/5.0 (Windows NT 5.2; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoic1lESGJ3N2NkdjB5OFIySWpJRXBNWXNDcVZqbGRlS1Fka2xNUjd0dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8yMTcuMTU0LjE0LjEwMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758817924
quVo81Q0Ol9X2hoWzt9dLEDkBljhmz5Zmoa2Kzz8	\N	206.168.34.44	Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)	YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0tVZ2NqVGcwQnRPRVkwS0xabU9vMVFGYmNFRG1EYlZDNHpORm5JMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8yMTcuMTU0LjE0LjEwMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758819103
FBNbNM7W5okGB4T8gz5FbilTvb5l06AxrY5osFA4	\N	89.187.164.96	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoic2FpZ1BCdndBMlJyYWNYTDkwQjR2aEQwQ09OSUJZdkJTcWNXNkRxWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vd2VibWFpbC5lZGVsbWFubi5jby51ayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758820136
HCKwBlwnAhWN6xKkFkuIRyhPACiBfLwIzJgdGvbm	\N	109.234.161.199	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.5615.138 Safari/537.36 Edg/112.0.1722.68	YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUFpMWhUY2RwS0VtbHNVVkZZU3V0YmZYSHE5TE15WVQyVUxsc1NqNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9lZGVsbWFubi5jby51ayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758817948
WinIOu7kCBwNWn8GdQeWwnJ7ootzAi1zbGRVqiqO	\N	198.235.24.217	Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity	YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkF5U0xqbUkxVzhQcjg1c2dHVnVLODFKd1JlSmxxOXRqSzB4cGJLaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8yMTcuMTU0LjE0LjEwMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758821292
LWy6nZQ6S8wgSAH7toerMMSH92FgZaENUSx7CuBB	\N	168.181.236.243		YTozOntzOjY6Il90b2tlbiI7czo0MDoiNldMTzRDazhiUXhrNk85S0pndExhTlNDVGVzV2dJcFo0MkRuT0l1TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMjE3LjE1NC4xNC4xMDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1758818286
48nixiD16HcbNRINY1ijDwsEMbWxxiXKkHKh0z0I	\N	86.38.98.57		YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnFmMUs2Z3Z5UnRnZnRueVBqcExNNml5UFBTeTJ4Z1kwbTRTU2JEbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMjE3LjE1NC4xNC4xMDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1758818406
gTCdqjjPizO51ECyAXGTAOuDjMZG6EHsCwTA88Zg	\N	86.38.98.57	Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzdUZ3NEcG5lVm5xYW1KQ3VJcmVzdkdQR25TNlJyTjJ3eHVnUThOTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMjE3LjE1NC4xNC4xMDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1758818411
cGm4tmFIpPWl4J8deFhgo2aojqi6ctE1fQJajDH5	\N	169.150.203.237	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFI0RWgzNGdNM1l6TjRzc0plS01MaXVXcUR4U3VaRVNsNEVVbVpHYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9lZGVsbWFubi5jby51ayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758814988
99yeK4QcsiLVMP9EiE8i7UUvl0s0MUgKf12xOC3u	\N	86.38.98.57	Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoieWZnZkMySVRuNTlSejRaaEJ3TVRyVEdtSWRRSHdYazNROXRPWTIzdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vMjE3LjE1NC4xNC4xMDEvP3BocGluZm89MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758818801
BPXld0pXIruEtZzFvtbjCvq9VKkbhL27NFA4Pz7O	\N	3.134.148.59	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoid0NnRXUxdnhBMW5uNWp6eDd5SE5zdHN2RG9CZ1R2WTZCekliVk5QWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMjE3LjE1NC4xNC4xMDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1758815045
Oz4mgOMsakTmzhsVhGR41dfN1xMeoiApWxQABU0d	\N	167.94.138.200	Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)	YTozOntzOjY6Il90b2tlbiI7czo0MDoidzFEVHRqQ2phSWl2dGlOUFhQNDV1RlJhanBSSll1MkFSdVRTY1BNQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8yMTcuMTU0LjE0LjEwMS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1758818899
\.


--
-- Data for Name: weapons; Type: TABLE DATA; Schema: public; Owner: cthulhu
--

COPY public.weapons (id, name, skill, damage, base_range, uses_per_round, bullets_in_mag, cost, malfunction, created_at, updated_at) FROM stdin;
1	Bow and Arrows	firearms-bow	1D6+half DB	30 yards	1	1	$7	97	2024-05-05 08:17:30	2024-05-29 08:59:04
2	Brass Knuckles	fighting-brawl	1D3+1+DB	Touch	1	-	$1	-	2024-05-05 08:17:30	2024-05-29 08:59:04
3	Bullwhip	fighting-whip	1D3+half	DB 10 feet	1	-	$5	-	2024-05-05 08:17:30	2024-05-29 08:59:04
4	Burning Torch	fighting-brawl	1D6+burn	Touch	1	=	$0.05	-	2024-05-05 08:17:30	2024-05-29 08:59:04
5	Blackjack (Cosh, life-preserver)	fighting-brawl	1D8+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-05-29 08:59:04
6	Club, large (baseball, cricket bat, poker)	fighting-brawl	1D8+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-05-29 08:59:04
7	Club,small (nightstick)	fighting-brawl	1D6+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-05-29 08:59:04
8	Crossbow (i)	firearms-bow	1D8+2	50 yards	1/2	1	$10	96	2024-05-05 08:17:30	2024-05-29 08:59:04
9	Garrote*(i)	fighting-garrote	1D6+DB	Touch	1	-	$0.50	-	2024-05-05 08:17:30	2024-05-29 08:59:04
10	Hatchet/Sickle (i)	fighting-axe	1D6+1+DB	Touch	1	-	$3	-	2024-05-05 08:17:30	2024-05-29 08:59:04
11	Knife, Large (machete, etc.) (i)	fighting-brawl	1D8+DB	Touch	1	-	$4	-	2024-05-05 08:17:30	2024-05-29 08:59:04
12	Knife,Medium (carving knife, etc.) (i)	fighting-brawl	1D4+2+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-05-29 08:59:04
13	Knife, Small (switchblade, etc.) (i)	fighting-brawl	1D4+DB	Touch	1	-	$2	-	2024-05-05 08:17:30	2024-05-29 08:59:04
14	Nunchaku	fighting-flail	1D8+DB	Touch	1	-	$1	-	2024-05-05 08:17:30	2024-05-29 08:59:04
15	Rock, Thrown	throw	1D4+half DB	STR/ 5 yards	1	-	-	-	2024-05-05 08:17:30	2024-05-29 08:59:04
16	Shuriken (i)	throw	1D3+half DB	STR/ 5 yards	2	One Use	$0.50	100	2024-05-05 08:17:30	2024-05-29 08:59:04
17	Spear (cavalry lance)(i)	fighting-spear	1D8+1	Touch	1	-	$25	-	2024-05-05 08:17:30	2024-05-29 08:59:04
18	Spear, Thrown (i)	throw	1D8+half DB	STR/ 5 yards	1	-	$1		2024-05-05 08:17:30	2024-05-29 08:59:04
19	.22 Short Automatic	firearms-handgun	1D6	10 yards	1 (3)	6	$25	100	2024-05-05 08:17:30	2024-05-29 08:59:04
20	.25 Derringer (1B)	firearms-handgun	1D6	3 yards	1	1	$12	100	2024-05-05 08:17:30	2024-05-29 08:59:04
21	.32 or 7.65mm Revolver	firearms-handgun	1D8	15 yards	1 (3)	6	$15	100	2024-05-05 08:17:30	2024-05-29 08:59:04
22	.32 or 7.65mm Automatic	firearms-handgun	1D8	15 yards	1 (3)	8	$20	99	2024-05-05 08:17:30	2024-05-29 08:59:04
23	Model P08 Luger	firearms-handgun	1D10	15 yards	1 (3)	8	$75	99	2024-05-05 08:17:30	2024-05-29 08:59:04
24	.45 Revolver	firearms-handgun	1D10+2	15 yards	1 (3)	6	$30	100	2024-05-05 08:17:30	2024-05-29 08:59:04
25	.45 Automatic	firearms-handgun	1D10+2	15 yards	1 (3)	7	$40	100	2024-05-05 08:17:30	2024-05-29 08:59:04
26	20-gauge Shotgun (2B)	firearms-shotgun	2D6/1D6/1D3	10/20/50 yards	1 or 2	2	$35	100	2024-05-05 08:17:30	2024-05-29 08:59:04
27	16-gauge Shotgun (2B)	firearms-shotgun	2D6+2/1D6+1/1D4	10/20/50 yards	1 or 2	2	$40	100	2024-05-05 08:17:30	2024-05-29 08:59:04
28	12-gauge Shotgun (2B)	firearms-shotgun	4D6/2D6/1D6	10/20/50 yards	1 or 2	2	$40	100	2024-05-05 08:17:30	2024-05-29 08:59:04
29	12-gauge Shotgun (semi-auto)	firearms-shotgun	4D6/2D6/1D6	10/20/50 yards	1 (2)	5	$45	100	2024-05-05 08:17:30	2024-05-29 08:59:04
30	12-gauge Shotgun (2B sawed off)	firearms-shotgun	4D6/1D6	5/10 yards	1 or 2	2	N/A	100	2024-05-05 08:17:30	2024-05-29 08:59:04
31	Bergmann MP181/MP2811	firearms-smg	1D10	20 yards	1 (2) or full auto	20/30/32	$1,000	96	2024-05-05 08:17:30	2024-05-29 08:59:04
32	Thompson	firearms-smg	1D10+2	20 yards	1 or full auto	20/30/50	$200+	96	2024-05-05 08:17:30	2024-05-29 08:59:04
33	Browning Auto	firearms-mg	2D6+4	90 yards	1 (2) or full	auto	20 $800	100	2024-05-05 08:17:30	2024-05-29 08:59:04
34	.30 Browning M1917A1	firearms-mg	2D6+4	150 yards	Full auto	250	$3,000	96	2024-05-05 08:17:30	2024-05-29 08:59:04
35	Bren Gun	firearms-mg	2D6+4	110 yards	1 or full auto	30/100	$3,000	96	2024-05-05 08:17:30	2024-05-29 08:59:04
36	Mark I Lewis Gun	firearms-mg	2D6+4	110 yards	Full auto	47/97	$3,000	96	2024-05-05 08:17:30	2024-05-29 08:59:04
37	Vickers .303	firearms-mg	2D6+4	110 yards	Full auto	250	$3,000	96	2024-05-05 08:17:30	2024-05-29 08:59:04
\.


--
-- Name: calendars_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.calendars_id_seq', 1, true);


--
-- Name: characters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.characters_id_seq', 6, true);


--
-- Name: equipables_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.equipables_id_seq', 14, true);


--
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.events_id_seq', 22, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.jobs_id_seq', 20, true);


--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.messages_id_seq', 10, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.migrations_id_seq', 12, true);


--
-- Name: pulse_aggregates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_aggregates_id_seq', 9160, true);


--
-- Name: pulse_entries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_entries_id_seq', 1513, true);


--
-- Name: pulse_values_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.pulse_values_id_seq', 1, false);


--
-- Name: skills_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.skills_id_seq', 63, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.users_id_seq', 5, true);


--
-- Name: weapons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: cthulhu
--

SELECT pg_catalog.setval('public.weapons_id_seq', 37, true);


--
-- PostgreSQL database dump complete
--

\unrestrict 5dzvZLti1jZkOUJAnE1K0caivES6XhFnEnL9HuF0jSrUvZhOCW9vnNRx2BPOCP9

