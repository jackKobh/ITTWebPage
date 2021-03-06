CREATE TABLE NAV_CONF(
    NC_ID_ELEMENTO    INT    NOT NULL   AUTO_INCREMENT,
    NC_DESCRIPCION    VARCHAR(100)    NOT NULL,
    NC_HREF           VARCHAR(230)    NOT NULL,
    NC_ID_PADRE       INT             NULL,
    PRIMARY KEY(NC_ID_ELEMENTO)
);

CREATE TABLE SLIDER_CONF(
    SC_ID_IMG        INT    NOT NULL   AUTO_INCREMENT,
    SC_NOMBRE        VARCHAR(150) NOT NULL,
    SC_HREF          VARCHAR(250) NOT NULL,
    SC_DESCRIPCION   VARCHAR(500) NOT NULL,
    SC_STATUS		 BOOLEAN 	  NOT NULL DEFAULT 0,
    PRIMARY KEY(SC_ID_IMG)
);

INSERT INTO SLIDER_CONF(SC_NOMBRE,SC_HREF,SC_DESCRIPCION,SC_STATUS) VALUES
("img/encabezado.jpg","https://www.google.com","IMAGEN DE PRUEBA",1);

CREATE TABLE NOTICE_CONF(
    NOC_ID_NOTICE        INT             NOT NULL        AUTO_INCREMENT,
    NOC_FECHA            TIMESTAMP       NOT NULL,
    NOC_URLIMG           VARCHAR(150)    NOT NULL,
    NOC_DESCRIPCION      VARCHAR(150)    NOT NULL,
    
    NOC_URLSHARED           VARCHAR(250)    NOT NULL,
    PRIMARY KEY(NOC_ID_NOTICE)
);

