CREATE TABLE user_config
(
  user_email character varying(100) NOT NULL,
  zoom integer DEFAULT 10,
  lat real DEFAULT -183143,
  lon real DEFAULT 3248420,
  bg1_visible integer DEFAULT 1,
  bg1_opacity integer DEFAULT 100,
  bg2_visible integer DEFAULT 1,
  bg2_opacity integer DEFAULT 100,
  bg3_visible integer DEFAULT 1,
  bg3_opacity integer DEFAULT 100,
  bg4_visible integer DEFAULT 1,
  bg4_opacity integer DEFAULT 100,
  CONSTRAINT user_config_pkey PRIMARY KEY (user_email),
  CONSTRAINT fk_config_user FOREIGN KEY (user_email)
      REFERENCES users (email) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE -- CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE user_configOWNER TO progci;
REVOKE ALL ON TABLE user_config FROM PUBLIC;
REVOKE ALL ON TABLE user_config FROM postgres;
GRANT ALL ON TABLE user_config TO postgres;
GRANT ALL ON TABLE user_config TO progci;
COMMENT ON CONSTRAINT fk_config_user ON user_config IS 'CASCADE';


CREATE TABLE user_favorite
(
  id integer NOT NULL,
  user_email character varying(100) NOT NULL,
  ts_name character varying(250) NOT NULL, 
  lat real DEFAULT 0.0,
  lon real DEFAULT 0.0,
  description text,
  created_on date not null default CURRENT_DATE, 
  CONSTRAINT user_fav_pkey PRIMARY KEY (id),
  CONSTRAINT fk_fav_ts FOREIGN KEY (ts_name)
      REFERENCES timeseries (ts_name) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE, -- CASCADE
  CONSTRAINT fk_fav_user FOREIGN KEY (user_email)
      REFERENCES users (email) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE -- CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE user_favorite OWNER TO progci;
REVOKE ALL ON TABLE user_favorite FROM PUBLIC;
REVOKE ALL ON TABLE user_favorite FROM postgres;
GRANT ALL ON TABLE user_favorite TO postgres;
GRANT ALL ON TABLE user_favorite TO progci;
COMMENT ON CONSTRAINT fk_fav_user ON user_favorite IS 'CASCADE';
COMMENT ON CONSTRAINT fk_fav_ts ON user_favorite IS 'CASCADE';

CREATE SEQUENCE users_fav_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE users_fav_id_seq OWNER TO progci;
ALTER SEQUENCE users_fav_id_seq OWNED BY user_favorite.id; -- if the column is dropped, so is the sequence
ALTER TABLE ONLY user_favorite ALTER COLUMN id SET DEFAULT nextval('users_fav_id_seq'::regclass);

