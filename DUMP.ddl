
CREATE TABLE Kliento_grupes
(
	Kliento_grupes_kodas int AUTO_INCREMENT,
	pavadinimas varchar (255),
	PRIMARY KEY(Kliento_grupes_kodas)
);

CREATE TABLE Kompiuteriai
(
	id int AUTO_INCREMENT,
	pavadinimas varchar (255),
	procesorius varchar (255),
	vaizdo_plokste varchar (255),
	motinine_plokste varchar (255),
	operatyvioji_atmintis int,
	disko_talpa int,
	ekrano_istrizaine double,
	operacine_sistema varchar (255),
	pagaminimo_data date,
	svoris double,
	baterijos_talpa varchar (255),
	jungiamumas varchar (255),
	kitos_savybes varchar (255),
	garantijos_laikotarpis int,
	pristatymo_laikas int,
	kaina double,
	PRIMARY KEY(id)
);

CREATE TABLE Miestai
(
	miesto_kodas int AUTO_INCREMENT,
	pavadinimas varchar (255),
	PRIMARY KEY(miesto_kodas)
);

CREATE TABLE Mokejimo_busenos
(
	mokejimo_busenos_numeris int AUTO_INCREMENT,
	pavadinimas varchar (255),
	PRIMARY KEY(mokejimo_busenos_numeris)
);

CREATE TABLE Mokejimo_tipai
(
	mokejimo_tipo_numeris int AUTO_INCREMENT,
	pavadinimas varchar (255),
	PRIMARY KEY(mokejimo_tipo_numeris)
);

CREATE TABLE Remonto_paslaugos
(
	id int AUTO_INCREMENT,
	paslauga varchar (255),
	kaina varchar (255),
	PRIMARY KEY(id)
);

CREATE TABLE vartotojai
(
	userid int AUTO_INCREMENT,
	username varchar (255),
	password varchar (255),
	userlevel int,
	PRIMARY KEY(userid)
);

CREATE TABLE dalies_tipai
(
	id_dalies_tipas integer,
	name char (20) NOT NULL,
	PRIMARY KEY(id_dalies_tipas)
);
INSERT INTO dalies_tipai(id_dalies_tipas, name) 
VALUES(1, 'Processor'),
(2, 'Video Card'),
(3, 'RAM'),
(4, 'Hard Disc'),
(5, 'Motherboard'),
(6, 'Power supply'),
(7, 'Cooler'),
(8, 'Optical device'),
(9, 'Case'),
(10, 'PC mouse'),
(11, 'Mouse pad'),
(12, 'Keyboard'),
(13, 'Headphones'),
(14, 'Speakers'),
(15, 'Camera'),
(16, 'USB device');

CREATE TABLE sutarties_busenos
(
	id_sutarties_busena integer,
	name char (11) NOT NULL,
	PRIMARY KEY(id_sutarties_busena)
);
INSERT INTO sutarties_busenos(id_sutarties_busena, name) 
VALUES(1, 'Confirmed'),
(2, 'Rejected'),
(3, 'Finished');

CREATE TABLE Dalys
(
	id int AUTO_INCREMENT,
	gamintojas varchar (255),
	aprasymas varchar (255),
	svoris double,
	pagaminimo_data date,
	kiekis int,
	garantijos_laikotarpis int,
	pristatymo_laikas int,
	kaina double,
	dalies_tipas integer,
	PRIMARY KEY(id),
	FOREIGN KEY(dalies_tipas) REFERENCES dalies_tipai (id_dalies_tipas)
);

CREATE TABLE Klientai
(
	id int AUTO_INCREMENT,
	vardas varchar (255),
	pavarde varchar (255),
	el_pastas varchar (255),
	lytis varchar (255),
	telefonas varchar (255),
	adresas varchar (255),
	sukurimo_data date,
	gimimo_data date,
	fk_Vartotojas int NOT NULL,
	fk_Miestas int NOT NULL,
	fk_Kliento_grupe int NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(fk_Vartotojas),
	CONSTRAINT fkc_Vartotojai FOREIGN KEY(fk_Vartotojas) REFERENCES vartotojai (userid),
	CONSTRAINT fkc_Miestai FOREIGN KEY(fk_Miestas) REFERENCES Miestai (miesto_kodas),
	CONSTRAINT fkc_Kliento_grupes FOREIGN KEY(fk_Kliento_grupe) REFERENCES Kliento_grupes (Kliento_grupes_kodas)
);

CREATE TABLE Mokejimai
(
	mokejimo_numeris int AUTO_INCREMENT,
	data date,
	suma double,
	fk_Mokejimo_tipas int NOT NULL,
	fk_Mokejimo_busena int NOT NULL,
	PRIMARY KEY(mokejimo_numeris),
	CONSTRAINT fkc_Mokejimo_tipas FOREIGN KEY(fk_Mokejimo_tipas) REFERENCES Mokejimo_tipai (mokejimo_tipo_numeris),
	CONSTRAINT fkc_Mokejimo_busena FOREIGN KEY(fk_Mokejimo_busena) REFERENCES Mokejimo_busenos (mokejimo_busenos_numeris)
);

CREATE TABLE Parduotuves
(
	parduotuves_kodas int AUTO_INCREMENT,
	pavadinimas varchar (255),
	miestas varchar (255),
	darbo_laikas varchar (255),
	darbo_pabaiga date,
	telefonas varchar (255),
	el_pastas varchar (255),
	adresas varchar (255),
	fk_Miestas int NOT NULL,
	PRIMARY KEY(parduotuves_kodas),
	CONSTRAINT fkc_Miestas FOREIGN KEY(fk_Miestas) REFERENCES Miestai (miesto_kodas)
);

CREATE TABLE Darbuotojai
(
	tabelio_nr int AUTO_INCREMENT,
	vardas varchar (255),
	pavarde varchar (255),
	telefono_numeris varchar (255),
	el_pastas varchar (255),
	adresas varchar (255),
	gimimo_data date,
	lytis varchar (255),
	darbo_pradzios_data date,
	fk_Parduotuve int NOT NULL,
	PRIMARY KEY(tabelio_nr),
	CONSTRAINT fkc_Parduotuve FOREIGN KEY(fk_Parduotuve) REFERENCES Parduotuves (parduotuves_kodas)
);

CREATE TABLE Remontai
(
	pristatymo_vieta varchar (255),
	id int AUTO_INCREMENT,
	gamintojas varchar (255),
	modelis varchar (255),
	kompiuterio_tipas varchar (255),
	gedimo_informacija varchar (255),
	pristatymo_data_laikas date,
	atlikti_darbai varchar (255),
	remonto_laikas varchar (255),
	remonto_garantija int,
	atsiemimo_data date,
	fk_Parduotuve int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT fkc_Parduotuves FOREIGN KEY(fk_Parduotuve) REFERENCES Parduotuves (parduotuves_kodas)
);

CREATE TABLE Sutartys
(
	nr int AUTO_INCREMENT,
	sutarties_data date,
	kaina double,
	pristatymas varchar (255),
	sutarties_busena integer,
	fk_Klientas int NOT NULL,
	fk_Mokejimas int,
	fk_Darbuotojas int NOT NULL,
	fk_Remontas int NOT NULL,
	PRIMARY KEY(nr),
	FOREIGN KEY(sutarties_busena) REFERENCES sutarties_busenos (id_sutarties_busena),
	CONSTRAINT fkc_Klientas FOREIGN KEY(fk_Klientas) REFERENCES Klientai (id),
	CONSTRAINT fkc_Mokejimas FOREIGN KEY(fk_Mokejimas) REFERENCES Mokejimai (mokejimo_numeris),
	CONSTRAINT fkc_Darbuotojas FOREIGN KEY(fk_Darbuotojas) REFERENCES Darbuotojai (tabelio_nr),
	CONSTRAINT fkc_Remontas FOREIGN KEY(fk_Remontas) REFERENCES Remontai (id)
);

CREATE TABLE visi_pc
(
	fk_Sutartis int,
	fk_Kompiuteris int,
	PRIMARY KEY(fk_Sutartis, fk_Kompiuteris),
	CONSTRAINT fkc_Sutartis FOREIGN KEY(fk_Sutartis) REFERENCES Sutartys (nr),
	CONSTRAINT fkc_Kompiuteris FOREIGN KEY(fk_Kompiuteris) REFERENCES Kompiuteriai (id)
);

CREATE TABLE visos_dalys
(
	fk_Sutartis int,
	fk_Dalis int,
	kiekis int,
	PRIMARY KEY(fk_Sutartis, fk_Dalis),
	CONSTRAINT fkc_Sutartys FOREIGN KEY(fk_Sutartis) REFERENCES Sutartys (nr),
	CONSTRAINT fkc_Dalys FOREIGN KEY(fk_Dalis) REFERENCES Dalys (id)
);
