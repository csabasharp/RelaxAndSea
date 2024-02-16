DROP DATABASE IF EXISTS relaxnsea;
CREATE DATABASE IF NOT EXISTS relaxnsea
COLLATE utf8_hungarian_ci
DEFAULT CHARACTER SET utf8;

USE relaxnsea;

CREATE TABLE userek(
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nev varchar(200) NOT NULL,
    szuldat date NOT NULL,
    telefon varchar(20) NOT NULL,
    email varchar(100) NOT NULL,
    jelszo varchar(50) NOT NULL
);

CREATE TABLE szobak(
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipus varchar(20) NOT NULL,
    meret int(2) NOT NULL
);

CREATE TABLE foglalasok(
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    szobaId smallint,
    userId smallint,
    felnott smallint DEFAULT 0, 
    fiatal smallint DEFAULT 0,
    mettol datetime NOT NULL,
    meddig datetime NOT NULL,
    FOREIGN KEY (szobaId) REFERENCES szobak(id),
    FOREIGN KEY (userId) REFERENCES userek(id)
);

CREATE TABLE dolgozok(
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId smallint,
    apiKulcs varchar(200) NOT NULL,
    FOREIGN KEY (userId) REFERENCES userek(id)
);

CREATE TABLE logs(
    id int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId smallint NOT NULL,
    uzenet varchar(200) NOT NULL,
    datum datetime DEFAULT now(),
    FOREIGN KEY (userId) REFERENCES userek(id)
);

INSERT INTO userek(id, nev, szuldat, telefon, email, jelszo) VALUES (1, "Amy Freeman", "2001-09-11", "+36604201337", "gaben@valvesoftware.com", "admin");
INSERT INTO dolgozok VALUES(1,1,"guest");


/*CREATE TABLE leltar(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    szobaId int NOT NULL,
    agy int NOT NULL,
    takaro int NOT NULL,
    parna int NOT NULL,
    szek int NOT NULL,
    asztal int NOT NULL,
    huto int NOT NULL,
    mikro int NOT NULL,
    szappan int NOT NULL,
    torulkozo int NOT NULL,
    FOREIGN KEY szobaId REFERENCES(szoba.id)
)*/


