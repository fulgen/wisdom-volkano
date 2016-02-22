CREATE TABLE ts_detrend
(
  id integer NOT NULL,
  ts_type character varying(10) NOT NULL,
  ts_name character varying(250) NOT NULL, 
  gnss_sub character varying(100),
  lat real DEFAULT 0.0,
  lon real DEFAULT 0.0,
  CONSTRAINT ts_detrend_pkey PRIMARY KEY (id),
  CONSTRAINT fk_ts_name FOREIGN KEY (ts_name)
      REFERENCES timeseries (ts_name) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE -- CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ts_detrend OWNER TO progci;
REVOKE ALL ON TABLE ts_detrend FROM PUBLIC;
REVOKE ALL ON TABLE ts_detrend FROM postgres;
GRANT ALL ON TABLE ts_detrend TO postgres;
GRANT ALL ON TABLE ts_detrend TO progci;
COMMENT ON CONSTRAINT fk_ts_name ON ts_detrend IS 'CASCADE';

CREATE SEQUENCE ts_detrend_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE ts_detrend_id_seq OWNER TO progci;
ALTER SEQUENCE ts_detrend_id_seq OWNED BY ts_detrend.id; -- if the column is dropped, so is the sequence
ALTER TABLE ONLY ts_detrend ALTER COLUMN id SET DEFAULT nextval('ts_detrend_id_seq'::regclass);

