--
-- PostgreSQL database dump
--

-- Dumped from database version 15.0
-- Dumped by pg_dump version 15.0

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
-- Name: status_t; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_t AS ENUM (
    'PENDING',
    'ACCEPTED',
    'REJECTED'
);


ALTER TYPE public.status_t OWNER TO postgres;

--
-- Name: calculate_total_time(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.calculate_total_time() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
UPDATE album
SET total_duration = (SELECT COALESCE(SUM(duration),0)
FROM song
WHERE album_id = NEW.album_id)
WHERE album_id = NEW.album_id;

RETURN NEW;
END;
$$;


ALTER FUNCTION public.calculate_total_time() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: album; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.album (
    album_id integer NOT NULL,
    judul character varying(64) NOT NULL,
    penyanyi character varying(128) NOT NULL,
    total_duration integer DEFAULT 0 NOT NULL,
    image_path character varying(256) NOT NULL,
    tanggal_terbit date NOT NULL,
    genre character varying(64)
);


ALTER TABLE public.album OWNER TO postgres;

--
-- Name: album_album_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.album_album_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.album_album_id_seq OWNER TO postgres;

--
-- Name: album_album_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.album_album_id_seq OWNED BY public.album.album_id;


--
-- Name: song; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.song (
    song_id integer NOT NULL,
    judul character varying(64) NOT NULL,
    penyanyi character varying(128),
    tanggal_terbit date NOT NULL,
    genre character varying(64),
    duration integer NOT NULL,
    audio_path character varying(256) NOT NULL,
    image_path character varying(256),
    album_id integer
);


ALTER TABLE public.song OWNER TO postgres;

--
-- Name: song_song_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.song_song_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.song_song_id_seq OWNER TO postgres;

--
-- Name: song_song_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.song_song_id_seq OWNED BY public.song.song_id;


--
-- Name: subscription; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.subscription (
    creator_id integer NOT NULL,
    subscriber_id integer NOT NULL,
    status public.status_t DEFAULT 'PENDING'::public.status_t
);


ALTER TABLE public.subscription OWNER TO postgres;

--
-- Name: user_account; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_account (
    user_id integer NOT NULL,
    email character varying(256) NOT NULL,
    password character varying(256) NOT NULL,
    username character varying(256) NOT NULL,
    isadmin boolean DEFAULT false NOT NULL
);


ALTER TABLE public.user_account OWNER TO postgres;

--
-- Name: user_account_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_account_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_account_user_id_seq OWNER TO postgres;

--
-- Name: user_account_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_account_user_id_seq OWNED BY public.user_account.user_id;


--
-- Name: album album_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.album ALTER COLUMN album_id SET DEFAULT nextval('public.album_album_id_seq'::regclass);


--
-- Name: song song_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.song ALTER COLUMN song_id SET DEFAULT nextval('public.song_song_id_seq'::regclass);


--
-- Name: user_account user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_account ALTER COLUMN user_id SET DEFAULT nextval('public.user_account_user_id_seq'::regclass);


--
-- Data for Name: album; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.album (album_id, judul, penyanyi, total_duration, image_path, tanggal_terbit, genre) FROM stdin;
1	Versus	Hakita	378	/assets/img/ultrakill.svg	2022-01-01	\N
2	The Ink Spot	The Ink Spot	349	/assets/img/theinkspot.jpg	2000-01-01	Jazz
3	Jazz	multiple	388	/assets/img/trumpet.jpg	2000-01-01	Jazz
4	Favorite	someone	459	/assets/img/doge.jpg	2000-01-01	Jazz
\.


--
-- Data for Name: song; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.song (song_id, judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) FROM stdin;
3	The Fire Is Gone	Hakita	2022-01-01	\N	158	/assets/audio/The Fire Is Gone.mp3	/assets/img/ultrakill.svg	\N
4	A Shattered Illusion	Hakita	2022-01-01	\N	191	/assets/audio/A Shattered Illusion.mp3	/assets/img/ultrakill.svg	\N
1	Versus 2	Hakita	2022-01-01	\N	135	/assets/audio/Versus 2.mp3	/assets/img/ultrakill.svg	1
2	Versus	Hakita	2022-01-01	\N	243	/assets/audio/Versus.mp3	/assets/img/ultrakill.svg	1
5	I Dont Want to Set the World on Fire	The Ink Spot	2000-01-01	Jazz	180	/assets/audio/I Dont Want to Set the World on Fire.mp3	/assets/img/theinkspot.jpg	2
6	Its All Over But The Crying	The Ink Spot	2000-01-01	Jazz	169	/assets/audio/Its All Over But The Crying.mp3	/assets/img/theinkspot.jpg	2
7	Just the Two of Us	The Ink Spot	2000-03-01	Jazz	236	/assets/audio/Just the Two of Us.mp3	/assets/img/trumpet.jpg	3
9	ProleteR - April Showers	The Ink Spot	2000-04-10	Electro Jazz	270	/assets/audio/ProleteR - April Showers.mp3	/assets/img/doge.jpg	4
10	Russ Morgan Orchestra Were you foolin	The Ink Spot	2000-01-01	Jazz	189	/assets/audio/Russ Morgan Orchestra Were you foolin.mp3	/assets/img/doge.jpg	4
8	Nat King Cole - Orange Colored Sky	The Ink Spot	2000-03-01	Jazz	152	/assets/audio/Nat King Cole - Orange Colored Sky.mp3	/assets/img/trumpet.jpg	3
12	aaa	bbb	2022-01-01	abc	100	-	-	1
\.


--
-- Data for Name: subscription; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.subscription (creator_id, subscriber_id, status) FROM stdin;
\.


--
-- Data for Name: user_account; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_account (user_id, email, password, username, isadmin) FROM stdin;
1	abc@gmail.com	abcde	abcde	f
2	aa@gmail.com	aa	bb	f
3	bbb@gmail.com	bbb	bbb	f
4	ade@gmail.com	ade	ade	f
5	abcde@gmail.com	def	def	f
6	ccc@gmail.com	ccc	ccc	f
\.


--
-- Name: album_album_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.album_album_id_seq', 4, true);


--
-- Name: song_song_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.song_song_id_seq', 10, true);


--
-- Name: user_account_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_account_user_id_seq', 1, false);


--
-- Name: album album_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.album
    ADD CONSTRAINT album_pkey PRIMARY KEY (album_id);


--
-- Name: song song_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.song
    ADD CONSTRAINT song_pkey PRIMARY KEY (song_id);


--
-- Name: subscription subscription_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.subscription
    ADD CONSTRAINT subscription_pkey PRIMARY KEY (creator_id, subscriber_id);


--
-- Name: user_account user_account_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_email_key UNIQUE (email);


--
-- Name: user_account user_account_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_pkey PRIMARY KEY (user_id);


--
-- Name: user_account user_account_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_username_key UNIQUE (username);


--
-- Name: song total_time; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER total_time AFTER INSERT ON public.song FOR EACH STATEMENT EXECUTE FUNCTION public.calculate_total_time();


--
-- Name: song total_time2; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER total_time2 AFTER DELETE ON public.song FOR EACH STATEMENT EXECUTE FUNCTION public.calculate_total_time();



--
-- Name: subscription Identity; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.subscription
    ADD CONSTRAINT "Identity" FOREIGN KEY (subscriber_id) REFERENCES public.user_account(user_id) NOT VALID;


--
-- Name: song song_album_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.song
    ADD CONSTRAINT song_album_id_fkey FOREIGN KEY (album_id) REFERENCES public.album(album_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

