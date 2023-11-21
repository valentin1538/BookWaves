#Second projet de deuxième année de BTS SIO
#Objectif : Création d'un site web de gestion d'ebooks
#Création d'une base de données Biblio pour la gestion des ebooks par une bibliothèque fictive
#livre(id,nom,infos,idauteur,idediteur,idgenre,idlangue) auteur(id,nom) editeur(id,nom) genre(id,nom) langue(id,nom) 


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
nom VARCHAR(75),
lienimage VARCHAR(250),
infos VARCHAR(250),
lienepub VARCHAR(250),
idauteur INT,
idediteur INT,
idgenre INT,
idlangue INT,
FOREIGN KEY (idauteur) REFERENCES auteur(id),
FOREIGN KEY (idediteur) REFERENCES editeur(id),
FOREIGN KEY (idgenre) REFERENCES genre(id),
FOREIGN KEY (idlangue) REFERENCES langue(id)
);


CREATE TABLE livreperso (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
idpersonne INT,
nom VARCHAR(75),
lienimage VARCHAR(250),
infos VARCHAR(250),
lienepub VARCHAR(250),
idauteur INT,
idediteur INT,
idgenre INT,
idlangue INT,
FOREIGN KEY (idpersonne) REFERENCES users(id),
FOREIGN KEY (idauteur) REFERENCES auteur(id),
FOREIGN KEY (idediteur) REFERENCES editeur(id),
FOREIGN KEY (idgenre) REFERENCES genre(id),
FOREIGN KEY (idlangue) REFERENCES langue(id)
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


###############################
###############################
#####INSERTION DES DONNEES#####
###############################
###############################


#INSERTION UTILISATEUR ADMINISTRATEUR
INSERT INTO users VALUES (1 ,'valentin','valentin@gmail.com',SHA2('valentin', 256),4);
INSERT INTO users VALUES (2 ,'hugo','hugo@gmail.com',SHA2('hugo', 256),3);
INSERT INTO users VALUES (3 ,'tim','tim@gmail.com',SHA2('tim', 256),2);
INSERT INTO users VALUES (4 ,'achille','achille@gmail.com',SHA2('achille', 256),1);

#INSERTION DES PROFILS 
INSERT INTO profil VALUES (0 ,'Invité');
INSERT INTO profil VALUES (1 ,'Connecté');
INSERT INTO profil VALUES (2 ,'Modérateur');
INSERT INTO profil VALUES (3 ,'DBA');
INSERT INTO profil VALUES (4 ,'Administrateur');


#INSERTION LIVRE
INSERT INTO livre(nom,infos,idauteur,idediteur,idgenre,idlangue) VALUES ('NO_NAME','NO_INFOS','NULL','NULL','NULL','NULL');
#Insertion des livres
INSERT INTO livre(nom,infos,idauteur,idediteur,idgenre,idlangue) VALUES
('ONE PIECE','L histoire suit principalement l équipage de Chapeau de paille, mené par son capitaine Monkey D. Luffy, un jeune homme ayant mangé le fruit du Gum Gum et dont le rêve est de devenir le Roi des pirates.',2,2,2,1),
('TWO PIECE','Pareil mais moins bien.',2,2,2,1),
('Comment se faire des amis', 'Ici par le biais de cette oeuvre l auteur cherche à apprendre au lecteur des manières de se faire es amis',3,3,3,1),
('Tintin au pays des Soviets', 'Le personnage principal Tintin se rend en Russie afin de résoudre une enquête des plus passionnantes',4,4,4,1)
;

INSERT INTO livreperso(idpersonne,nom,infos,idauteur,idediteur,idgenre,idlangue) VALUES
(4,'ONE PIECE','L histoire suit principalement l équipage de Chapeau de paille, mené par son capitaine Monkey D. Luffy, un jeune homme ayant mangé le fruit du Gum Gum et dont le rêve est de devenir le Roi des pirates.',2,2,2,1),
(4,'Comment se faire des amis', 'Ici par le biais de cette oeuvre l auteur cherche à apprendre au lecteur des manières de se faire es amis',3,3,3,1),
(4,'Tintin au pays des Soviets', 'Le personnage principal Tintin se rend en Russie afin de résoudre une enquête des plus passionnantes',4,4,4,1)
;



#INSERTION AUTEUR
#Insertion par défaut
INSERT INTO auteur(id,nom) VALUES (0,'NO_NAME');
#Insertion des auteurs
INSERT INTO auteur(nom) VALUES 
('Eichiro Oda'),
('Carnegie Dale'),
('Hergé')
;


#INSERTION EDITEUR
#Insertion par défaut
INSERT INTO editeur(nom) VALUES ('NO_NAME');
#Insertion des editeurs
INSERT INTO editeur(nom) VALUES
('Glénat'),
('Lgf'),
('Casterman')
;


#INSERTION GENRE
#Insertion par défaut
INSERT INTO genre(nom) VALUES ('NO_NAME');
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
