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

#CREATION DE LA TABLE DE GESTION DES LECTURES
CREATE TABLE lecture(
idpersonne INT,
idlivre INT, 
lu BOOL, 
avancement DECIMAL,
PRIMARY KEY (idpersonne,idlivre),
FOREIGN KEY (idpersonne) REFERENCES personne(id),
FOREIGN KEY(idlivre) REFERENCES livre(id)
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

CREATE TABLE avis(
idpersonne INT,
idlivre INT,
note DECIMAL,
commentaire VARCHAR(250),
PRIMARY KEY (idpersonne,idlivre),
FOREIGN KEY (idpersonne) REFERENCES personne(id),
FOREIGN KEY(idlivre) REFERENCES livre(id)

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
nom VARCHAR (250),
infos VARCHAR(250),
idediteur INT,
FOREIGN KEY (idediteur) REFERENCES editeur(id)
);

CREATE TABLE forum(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(250),
description VARCHAR(250),
lienimage VARCHAR(250)
);

CREATE TABLE sujet(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(250),
idforum INT,
idpersonne INT,
  date_dernier_message DATETIME,
FOREIGN KEY (idpersonne) REFERENCES personne(id),
FOREIGN KEY (idforum) REFERENCES forum(id)
);

CREATE TABLE message(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
contenu VARCHAR(250),
  date_creation DATETIME,
idsujet int,
idpersonne INT,
FOREIGN KEY (idpersonne) REFERENCES personne(id),
FOREIGN KEY (idsujet) REFERENCES sujet(id)
);

###############################
###############################
#####INSERTION DES DONNEES#####
###############################
###############################


INSERT INTO forum (nom, description, lienimage) VALUES 
('Fonctionnement de BookWaves', 'Retrouvez différentes aides à l utilisation de notre site BookWaves', 'image1.jpg'),
('Forums sur les livres', 'Discutez librement au sujet de vos livres préferés', 'image2.jpg'),
('Discussions libres', 'Sujets divers', 'image3.jpg'),
('Questions et aide communautaire', 'Forum d entraide entre utilisateurs de BookWaves', 'image4.jpg');


INSERT INTO sujet (nom, idforum, idpersonne) VALUES 
('Comment ajouter des livres ?', 1, 1),
('Comment lire des livres en ligne ?', 1, 1),
('Comment ajouter des livres ?', 1, 1),
('Comment lire des livres en ligne ?', 1, 1),
('Comment ajouter des livres ?', 1, 1),
('Comment lire des livres en ligne ?', 1, 1),
('Comment ajouter des livres ?', 1, 1),
('Comment lire des livres en ligne ?', 1, 1),
('Comment ajouter des livres ?', 1, 1),
('Comment lire des livres en ligne ?', 1, 1),

('Meilleur arc de one piece ?', 2, 1),
('Comment ...? ', 3, 1),
('Je n arrive pas à visualiser mes livres', 4, 1);


INSERT INTO message (contenu, date_creation, idsujet, idpersonne) VALUES 
('Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1','1998-01-23 12:45:56', 1 , 3),
('Message 2 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1Message 1 - Sujet 1', 1,'1998-01-23 12:45:56', 3),
('Message 1 - Sujet 2', '1998-01-23 12:45:56',  2, 2),
('Message 1 - Sujet 3', '1998-01-23 12:45:56', 3, 3),
('Message 2 - Sujet 3', '1998-01-23 12:45:56' , 3, 4);



#INSERTION UTILISATEUR ADMINISTRATEUR
INSERT INTO users (id,username,email,password,idprofil)  VALUES (1 ,'valentin','valentin@gmail.com',SHA2('valentin', 256),4);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (2 ,'hugo','hugo@gmail.com',SHA2('hugo', 256),3);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (3 ,'tim','tim@gmail.com',SHA2('tim', 256),2);
INSERT INTO users (id,username,email,password,idprofil)  VALUES (4 ,'achille','achille@gmail.com',SHA2('achille', 256),1);


#INSERTION DES PROFILS 
INSERT INTO profil VALUES (0 ,'Invité');
INSERT INTO profil VALUES (1 ,'Connecté');
INSERT INTO profil VALUES (2 ,'Modérateur');
INSERT INTO profil VALUES (3 ,'DBA');
INSERT INTO profil VALUES (4 ,'Administrateur');


INSERT INTO collection(nom,infos,idediteur) VALUES

('inconnu','inconnu',1),
('ONE PIECE','L histoire suit principalement l équipage de Chapeau de paille, mené par son capitaine Monkey D. Luffy, 
un jeune homme ayant mangé le fruit du Gum Gum et dont le rêve est de devenir le Roi des pirates.',2),
('TWO PIECE','L histoire suit principalement l équipage de Chapeau de paille, mené par son capitaine Monkey D. Loufi, 
un jeune homme ayant mangé le fruit du Gum Gum et dont le rêve est de devenir le Roi des pirates.',2),
('Tintin','Le T',4)
;




#INSERTION AUTEUR
#Insertion par défaut
INSERT INTO auteur(id,nom) VALUES ('NULL','inconnu');
#Insertion des auteurs
INSERT INTO auteur(nom) VALUES 
('Eichiro Oda'),
('Carnegie Dale'),
('Hergé')
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
('Anglais');