--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

DROP TABLE IF EXISTS groups CASCADE;
CREATE TABLE groups (
    id integer NOT NULL,
    name character varying(20) NOT NULL,
    description character varying(100) NOT NULL
);


--
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- Name: layer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

DROP TABLE IF EXISTS layer CASCADE;
CREATE TABLE layer (
    layer_id integer NOT NULL,
    creator character varying(100) NOT NULL,
    layer_name_ws character varying(250) NOT NULL,
    layer_type character varying(50),
    layer_description text
);


--
-- Name: COLUMN layer.layer_name_ws; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN layer.layer_name_ws IS 'format  ws:layer';


--
-- Name: layer_layer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE layer_layer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: layer_layer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE layer_layer_id_seq OWNED BY layer.layer_id;


--
-- Name: login_attempts; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

DROP TABLE IF EXISTS login_attempts CASCADE;
CREATE TABLE login_attempts (
    id integer NOT NULL,
    ip_address character varying(15) NOT NULL,
    login character varying(100) NOT NULL,
    "time" integer
);


--
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE login_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: login_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE login_attempts_id_seq OWNED BY login_attempts.id;


--
-- Name: user_layers; Type: TABLE; Schema: public; Owner: progci; Tablespace: 
--

DROP TABLE IF EXISTS user_layers CASCADE;
CREATE TABLE user_layers (
    user_email character varying(100) NOT NULL,
    layer character varying NOT NULL,
    granted_by character varying(100),
    granted_when date,
    config_visible integer DEFAULT 1,
    config_opacity integer DEFAULT 100,
    config_order integer,
    config_loaded integer DEFAULT 0
);

--
-- Name: COLUMN user_layers.layer; Type: COMMENT; Schema: public; Owner: fulgen
--

COMMENT ON COLUMN user_layers.layer IS 'format  workspace:layer';


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
    id integer NOT NULL,
    ip_address character varying(15) NOT NULL,
    username character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    salt character varying(255) DEFAULT NULL::character varying,
    email character varying(100) NOT NULL,
    activation_code character varying(40),
    forgotten_password_code character varying(40),
    forgotten_password_time integer,
    remember_code character varying(40),
    created_on integer NOT NULL,
    last_login integer,
    active integer,
    first_name character varying(50),
    last_name character varying(50),
    company character varying(100),
    phone character varying(20)
);


--
-- Name: users_groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

DROP TABLE IF EXISTS users_groups CASCADE;
CREATE TABLE users_groups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


--
-- Name: users_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_groups_id_seq OWNED BY users_groups.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- Name: layer_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY layer ALTER COLUMN layer_id SET DEFAULT nextval('layer_layer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY login_attempts ALTER COLUMN id SET DEFAULT nextval('login_attempts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups ALTER COLUMN id SET DEFAULT nextval('users_groups_id_seq'::regclass);


--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY groups (id, name, description) FROM stdin;
1	admin	Administrators group
2	member	General user group---
\.


--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('groups_id_seq', 4, true);


--
-- Data for Name: layer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY layer (layer_id, creator, layer_name_ws, layer_type, layer_description) FROM stdin;
\.


--
-- Name: layer_layer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('layer_layer_id_seq', 32, true);


--
-- Data for Name: login_attempts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY login_attempts (id, ip_address, login, "time") FROM stdin;
\.


--
-- Name: login_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('login_attempts_id_seq', 1, false);


--
-- Data for Name: user_layers; Type: TABLE DATA; Schema: public; Owner: fulgen
--

COPY user_layers (user_email, layer, granted_by, granted_when, config_visible, config_opacity, config_order, config_loaded) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (id, ip_address, username, password, salt, email, activation_code, forgotten_password_code, forgotten_password_time, remember_code, created_on, last_login, active, first_name, last_name, company, phone) FROM stdin;
1	127.0.0.1	administrator	$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36		admin@admin.com		\N	\N	\N	1268889823	1435676856	1	Admin	istrator	ADMIN	0
\.


--
-- Data for Name: users_groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users_groups (id, user_id, group_id) FROM stdin;
1	1	1
\.


--
-- Name: users_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_groups_id_seq', 12, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- Name: email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT email_unique UNIQUE (email);


--
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- Name: layer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY layer
    ADD CONSTRAINT layer_pkey PRIMARY KEY (layer_id);


--
-- Name: login_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- Name: uc_users_groups; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT uc_users_groups UNIQUE (user_id, group_id);


--
-- Name: unique_layer_name; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY layer
    ADD CONSTRAINT unique_layer_name UNIQUE (layer_name_ws);


--
-- Name: user_layers_pkey; Type: CONSTRAINT; Schema: public; Owner: progci; Tablespace: 
--

ALTER TABLE ONLY user_layers
    ADD CONSTRAINT user_layers_pkey PRIMARY KEY (user_email, layer);


--
-- Name: users_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT users_groups_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: fki_layer; Type: INDEX; Schema: public; Owner: progci; Tablespace: 
--

CREATE INDEX fki_layer ON user_layers USING btree (layer);


--
-- Name: fk_layer; Type: FK CONSTRAINT; Schema: public; Owner: fulgen
--

ALTER TABLE ONLY user_layers
    ADD CONSTRAINT fk_layer FOREIGN KEY (layer) REFERENCES layer(layer_name_ws) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: CONSTRAINT fk_layer ON user_layers; Type: COMMENT; Schema: public; Owner: fulgen
--

COMMENT ON CONSTRAINT fk_layer ON user_layers IS 'CASCADE';


--
-- Name: fk_user_email; Type: FK CONSTRAINT; Schema: public; Owner: fulgen
--

ALTER TABLE ONLY user_layers
    ADD CONSTRAINT fk_user_email FOREIGN KEY (user_email) REFERENCES users(email);


--
-- Name: layer_creator_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY layer
    ADD CONSTRAINT layer_creator_fkey FOREIGN KEY (creator) REFERENCES users(email);


--
-- Name: users_groups_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT users_groups_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE;


--
-- Name: users_groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT users_groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO progci;


--
-- Name: groups; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE groups FROM PUBLIC;
REVOKE ALL ON TABLE groups FROM postgres;
GRANT ALL ON TABLE groups TO postgres;
GRANT ALL ON TABLE groups TO progci;


--
-- Name: groups_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE groups_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE groups_id_seq FROM postgres;
GRANT ALL ON SEQUENCE groups_id_seq TO postgres;
GRANT ALL ON SEQUENCE groups_id_seq TO progci;


--
-- Name: layer; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE layer FROM PUBLIC;
REVOKE ALL ON TABLE layer FROM postgres;
GRANT ALL ON TABLE layer TO postgres;
GRANT ALL ON TABLE layer TO progci;


--
-- Name: layer_layer_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE layer_layer_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE layer_layer_id_seq FROM postgres;
GRANT ALL ON SEQUENCE layer_layer_id_seq TO postgres;
GRANT ALL ON SEQUENCE layer_layer_id_seq TO progci;


--
-- Name: login_attempts; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE login_attempts FROM PUBLIC;
REVOKE ALL ON TABLE login_attempts FROM postgres;
GRANT ALL ON TABLE login_attempts TO postgres;
GRANT ALL ON TABLE login_attempts TO progci;


--
-- Name: login_attempts_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE login_attempts_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE login_attempts_id_seq FROM postgres;
GRANT ALL ON SEQUENCE login_attempts_id_seq TO postgres;
GRANT ALL ON SEQUENCE login_attempts_id_seq TO progci;


--
-- Name: user_layers; Type: ACL; Schema: public; Owner: progci
--

REVOKE ALL ON TABLE user_layers FROM PUBLIC;
REVOKE ALL ON TABLE user_layers FROM postgres;
GRANT ALL ON TABLE user_layers TO postgres;
GRANT ALL ON TABLE user_layers TO progci;


--
-- Name: users; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE users FROM PUBLIC;
REVOKE ALL ON TABLE users FROM postgres;
GRANT ALL ON TABLE users TO postgres;
GRANT ALL ON TABLE users TO progci;


--
-- Name: users_groups; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE users_groups FROM PUBLIC;
REVOKE ALL ON TABLE users_groups FROM postgres;
GRANT ALL ON TABLE users_groups TO postgres;
GRANT ALL ON TABLE users_groups TO progci;


--
-- Name: users_groups_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE users_groups_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE users_groups_id_seq FROM postgres;
GRANT ALL ON SEQUENCE users_groups_id_seq TO postgres;
GRANT ALL ON SEQUENCE users_groups_id_seq TO progci;


--
-- Name: users_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE users_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE users_id_seq FROM postgres;
GRANT ALL ON SEQUENCE users_id_seq TO postgres;
GRANT ALL ON SEQUENCE users_id_seq TO progci;


--
-- PostgreSQL database dump complete
--

