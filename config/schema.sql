
SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE DATABASE "news.php" WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.utf8' LC_CTYPE = 'en_US.utf8';


ALTER DATABASE "news.php" OWNER TO {owner};

\connect "news.php"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 176 (class 3079 OID 11855)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2016 (class 0 OID 0)
-- Dependencies: 176
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 172 (class 1259 OID 16799)
-- Name: groups; Type: TABLE; Schema: public; Owner: {owner}; Tablespace: 
--

CREATE TABLE groups (
    name character varying(255) NOT NULL,
    low_watermark integer NOT NULL,
    high_watermark integer NOT NULL,
    is_writable boolean NOT NULL,
    is_moderated boolean NOT NULL
);


ALTER TABLE groups OWNER TO {owner};

--
-- TOC entry 175 (class 1259 OID 16842)
-- Name: messages; Type: TABLE; Schema: public; Owner: {owner}; Tablespace: 
--

CREATE TABLE messages (
    id character varying(255) NOT NULL,
    thread integer NOT NULL,
    watermark integer NOT NULL,
    author_name character varying(128) NOT NULL,
    author_emailaddress character varying(255) NOT NULL,
    "timestamp" timestamp without time zone NOT NULL,
    lines smallint NOT NULL,
    bytes integer NOT NULL,
    extra character varying(255)
);


ALTER TABLE messages OWNER TO {owner};

--
-- TOC entry 174 (class 1259 OID 16812)
-- Name: threads; Type: TABLE; Schema: public; Owner: {owner}; Tablespace: 
--

CREATE TABLE threads (
    id integer NOT NULL,
    subject character varying(255) NOT NULL,
    "group" character varying(255) NOT NULL
);


ALTER TABLE threads OWNER TO {owner};

--
-- TOC entry 173 (class 1259 OID 16810)
-- Name: threads_id_seq; Type: SEQUENCE; Schema: public; Owner: {owner}
--

CREATE SEQUENCE threads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE threads_id_seq OWNER TO {owner};

--
-- TOC entry 2017 (class 0 OID 0)
-- Dependencies: 173
-- Name: threads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: {owner}
--

ALTER SEQUENCE threads_id_seq OWNED BY threads.id;


--
-- TOC entry 1891 (class 2604 OID 16815)
-- Name: id; Type: DEFAULT; Schema: public; Owner: {owner}
--

ALTER TABLE ONLY threads ALTER COLUMN id SET DEFAULT nextval('threads_id_seq'::regclass);


--
-- TOC entry 1893 (class 2606 OID 16803)
-- Name: PK_groups; Type: CONSTRAINT; Schema: public; Owner: {owner}; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT "PK_groups" PRIMARY KEY (name);


--
-- TOC entry 1897 (class 2606 OID 16849)
-- Name: PK_messages; Type: CONSTRAINT; Schema: public; Owner: {owner}; Tablespace: 
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT "PK_messages" PRIMARY KEY (id);


--
-- TOC entry 1895 (class 2606 OID 16817)
-- Name: PK_threads; Type: CONSTRAINT; Schema: public; Owner: {owner}; Tablespace: 
--

ALTER TABLE ONLY threads
    ADD CONSTRAINT "PK_threads" PRIMARY KEY (id);


--
-- TOC entry 1899 (class 2606 OID 16850)
-- Name: FK_messages_threads; Type: FK CONSTRAINT; Schema: public; Owner: {owner}
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT "FK_messages_threads" FOREIGN KEY (thread) REFERENCES threads(id);


--
-- TOC entry 1898 (class 2606 OID 16825)
-- Name: FK_threads_groups; Type: FK CONSTRAINT; Schema: public; Owner: {owner}
--

ALTER TABLE ONLY threads
    ADD CONSTRAINT "FK_threads_groups" FOREIGN KEY ("group") REFERENCES groups(name);


--
-- TOC entry 2015 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;

--
-- PostgreSQL database dump complete
--

