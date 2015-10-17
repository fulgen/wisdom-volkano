CREATE TABLE timeseries
(
  ts_id serial NOT NULL,
  creator character varying(100) NOT NULL,
  ts_name character varying(250) NOT NULL, 
  ts_type character varying(50) NOT NULL, -- msbas, histogram
  ts_file character(250) NOT NULL, -- folder for msbas, file for histogram
  ts_file_ts character(250), -- ex VVP_ML_1_Pixel_FullSerie_238_370test_EW_Detrended.dat
  ts_file_ts_ini_coord smallint NOT NULL DEFAULT 26, -- place in the ts_file_ts where the coords start - format is NNN_NNN
  ts_file_raster character(250), -- ex 20030116e.bin.hdr
  ts_file_raster_ini_date smallint NOT NULL DEFAULT 0, -- place in the ts_file_raster where the date starts - format YYMMDDDD
  ts_coord_lat_top numeric(20,10) DEFAULT (-1.1), -- negative is South, positive is North
  ts_coord_lon_left numeric(20,10) DEFAULT 29.0, -- negative is west Greenwich, positive is east
  ts_coord_lat_inc numeric(20,10) DEFAULT 0.0008333333, -- positive increment in degrees per pixel
  ts_coord_lon_inc numeric(20,10) DEFAULT 0.0008333333, -- positive increment in degrees per pixel
  ts_description text,
  ts_seism_station character(20) DEFAULT 'KBB'::bpchar, -- short code of the station, ex KBB -- FK
  CONSTRAINT ts_pkey PRIMARY KEY (ts_id),
  CONSTRAINT ts_creator_fkey FOREIGN KEY (creator)
      REFERENCES users (email) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT unique_ts_name UNIQUE (ts_name)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE timeseries
  OWNER TO progci;
GRANT ALL ON TABLE timeseries TO progci;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE timeseries TO progci;


CREATE TABLE user_timeseries
(
  user_email character varying(100) NOT NULL,
  ts_name character varying NOT NULL, 
  granted_by character varying(100),
  granted_when date,
  config_visible integer DEFAULT 1,
  config_opacity integer DEFAULT 100,
  config_order integer,
  config_loaded integer DEFAULT 0,
  CONSTRAINT user_ts_pkey PRIMARY KEY (user_email, ts_name),
  CONSTRAINT fk_timeseries FOREIGN KEY (ts_name)
      REFERENCES timeseries (ts_name) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE, -- CASCADE
  CONSTRAINT fk_user_email FOREIGN KEY (user_email)
      REFERENCES users (email) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE user_timeseries
  OWNER TO progci;
GRANT ALL ON TABLE user_timeseries TO progci;
COMMENT ON CONSTRAINT fk_timeseries ON user_timeseries IS 'CASCADE';

