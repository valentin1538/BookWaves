#Second projet de deuxième année de BTS SIO
#Objectif : Création d'un site web de gestion d'ebooks
#Création d'une base de données Biblio pour la gestion des ebooks par une bibliothèque fictive 


DROP DATABASE IF EXISTS Biblio;
CREATE DATABASE Biblio; 
USE Biblio; 


###############################
###############################
######CREATION DES TABLES######
###############################
###############################

#CREATION DE LA TABLE UTILISATEUR
create table users(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(30),
    email VARCHAR(100),
    password VARCHAR(100),
    idprofil INT,
    urlpfp VARCHAR(250),
    FOREIGN KEY (idprofil) REFERENCES profil(id)
    );


#CREATION DE LA TABLE DE GESTION DES PROFILS
CREATE TABLE profil(
id INT NOT NULL PRIMARY KEY,
nom VARCHAR(35)
);


#CREATION DE LA TABLE LIVRE
CREATE TABLE livre (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(750),
lienfiles VARCHAR(750),
lienfolder Varchar(750),
idauteur INT,
idediteur INT,
idgenre INT,
idlangue INT,
idcollection INT,
FOREIGN KEY (idauteur) REFERENCES auteur(id),
FOREIGN KEY (idediteur) REFERENCES editeur(id),
FOREIGN KEY (idgenre) REFERENCES genre(id),
FOREIGN KEY (idlangue) REFERENCES langue(id),
FOREIGN KEY (idcollection) REFERENCES collection(id)
);


CREATE TABLE livreperso (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
idpersonne INT,
nom VARCHAR(750),
lienfiles VARCHAR(750),
lienfolder Varchar(750),
idauteur INT,
idediteur INT,
idgenre INT,
idlangue INT,
idcollection INT, 
FOREIGN KEY (idpersonne) REFERENCES users(id),
FOREIGN KEY (idauteur) REFERENCES auteur(id),
FOREIGN KEY (idediteur) REFERENCES editeur(id),
FOREIGN KEY (idgenre) REFERENCES genre(id),
FOREIGN KEY (idlangue) REFERENCES langue(id),
FOREIGN KEY (idcollection) REFERENCES collection(id)
);


#CREATION DE LA TABLE AUTEUR
CREATE TABLE auteur (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75),
datenaissance DATE,
datedeces DATE
);


#CREATION DE LA TABLE EDITEUR
CREATE TABLE editeur (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75)
);


#CREATION TABLE GENRE
CREATE TABLE genre (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75)
);


#CREATION TABLE LANGUE
CREATE TABLE langue (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75)
);


#CREATION TABLE COLLECTION EDITEUR
CREATE TABLE collection(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR (75),
infos VARCHAR(250),
idediteur INT,
FOREIGN KEY (idediteur) REFERENCES editeur(id)
);


#CREATION DE LA TABLE PERSONNAGE AUTEUR
CREATE TABLE personnage(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(75),
idauteur INT,
idcollection INT,
FOREIGN KEY (idcollection) REFERENCES collection(id),
FOREIGN KEY (idauteur) REFERENCES auteur(id)
);


###############################
###############################
#####INSERTION DES DONNEES#####
###############################
###############################


#INSERTION UTILISATEUR ADMINISTRATEUR
INSERT INTO users (id,username,email,password,idprofil)  VALUES (1 ,'valentin','valentin@gmail.com',SHA2('valentin', 256),4);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (2 ,'hugo','hugo@gmail.com',SHA2('hugo', 256),3);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (3 ,'tim','tim@gmail.com',SHA2('tim', 256),2);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (4 ,'achille','achille@gmail.com',SHA2('achille', 256),1);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (5 ,'brandon','brandon@gmail.com',SHA2('brandon', 256),1);


#INSERTION DES PROFILS 
INSERT INTO profil VALUES (0 ,'Invité');
INSERT INTO profil VALUES (1 ,'Connecté');
INSERT INTO profil VALUES (2 ,'Modérateur');
INSERT INTO profil VALUES (3 ,'DBA');
INSERT INTO profil VALUES (4 ,'Administrateur');


#INSERTION LIVRE
INSERT INTO livre(nom,lienfiles,lienfolder,idauteur,idediteur,idgenre,idlangue,idcollection) VALUES ('NO_NAME','NO_PATH','NO_PATH','NULL','NULL','NULL','NULL','NULL');






#INSERTION AUTEUR
#Insertion par défaut
INSERT INTO auteur(id,nom) VALUES ('NULL','inconnu');
#Insertion des auteurs
INSERT INTO auteur(nom) VALUES 
('Eichiro Oda'),
('Carnegie Dale'),
('Hergé')
;


#INSERTION PERSONNAGES
#Insertion des auteurs
INSERT INTO personnage(nom,idauteur,idcollection) VALUES 
('Luffy', 2,2),
('Loufi', 2,3),
('Tintin', 4, 4),
('Milou', 4, 4)
;


#INSERTION EDITEUR
#Insertion par défaut
INSERT INTO editeur(id,nom) VALUES ('NULL','inconnu');
#Insertion des editeurs
INSERT INTO editeur(nom) VALUES
('Glénat'),
('Lgf'),
('Casterman')
;


#INSERTION GENRE
#Insertion par défaut
INSERT INTO genre(id,nom) VALUES ('NULL','inconnu');
#Insertion des genres
INSERT INTO genre(nom) VALUES
('Shonen'),
('Self-help book'),
('Bande dessinée')
;


#INSERTION LANGUE
INSERT INTO langue(nom) VALUES
('Français'),
('Anglais')
;
