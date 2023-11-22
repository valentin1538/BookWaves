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
    password VARCHAR(30),
    admin boolean
    );


#CREATION DE LA TABLE BIBLIOTHEQUE
CREATE TABLE playlist(
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75),
idpersonne INT,
FOREIGN KEY (idpersonne) REFERENCES users(id)
);



#CREATION DE LA TABLE POUR LES LIVRES DANS UNE BIBLIO
CREATE TABLE listelivre(
idplaylist INT,
idlivre INT,
FOREIGN KEY (idplaylist) REFERENCES playlist(id),
FOREIGN KEY (idlivre) REFERENCES livre(id),
PRIMARY KEY (idplaylist,idlivre)
);


#CREATION DE LA TABLE LIVRE
CREATE TABLE livre (
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
nom VARCHAR(75),
image BLOB,
infos VARCHAR(250),
idepub INT,
idauteur INT,
idediteur INT,
idgenre INT,
idlangue INT,
FOREIGN KEY (idepub) REFERENCES epub(id),
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
INSERT INTO users VALUES (1 ,'admin','admin','admin',1);
INSERT INTO users VALUES (2 ,'test','test','test', 0);



#INSERTION PLAYLIST PAR DEFAUT
INSERT INTO playlist VALUES(1, 'test', 2);
INSERT INTO playlist VALUES(2, 'test2', 2);


#INSERTION LISTELIVRE PAR DEFAUT
INSERT INTO listelivre VALUES(1,2);
INSERT INTO listelivre VALUES(1,3);
INSERT INTO listelivre VALUES(1,4);

INSERT INTO listelivre VALUES(2,3);
INSERT INTO listelivre VALUES(2,4);


#INSERTION LIVRE
#Insertion par défaut
INSERT INTO livre(nom,infos,idauteur,idediteur,idgenre,idlangue) VALUES ('NO_NAME','NO_INFOS',0,0,0,0);
#Insertion des livres
INSERT INTO livre(nom,infos,idauteur,idediteur,idgenre,idlangue) VALUES
('ONE PIECE','L histoire suit principalement l équipage de Chapeau de paille, mené par son capitaine Monkey D. Luffy, un jeune homme ayant mangé le fruit du Gum Gum et dont le rêve est de devenir le Roi des pirates.',2,2,2,2),
('Comment se faire des amis', 'Ici par le biais de cette oeuvre l auteur cherche à apprendre au lecteur des manières de se faire es amis',3,3,3,2),
('Tintin au pays des Soviets', 'Le personnage principal Tintin se rend en Russie afin de résoudre une enquête des plus passionnantes',4,4,4,2)
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
#Insertion par défaut
INSERT INTO langue(nom) VALUES ('NO_NAME');
#Insertion des langues
INSERT INTO langue(nom) VALUES
('Français'),
('Anglais')
;

 SELECT username, playlist.nom AS nomplaylist, livre.nom AS nomlivre from users, playlist, listelivre, livre WHERE playlist.idpersonne=users.id AND listelivre.idlivre=livre.id AND listelivre.idplaylist=playlist.id  ;