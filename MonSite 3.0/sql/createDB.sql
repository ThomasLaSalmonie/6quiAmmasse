/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  22/03/2017 11:27:48                      */
/*==============================================================*/


drop table if exists CARTE;

drop table if exists CARTEDISTRIBUEES;

drop table if exists CARTEJOUEE;

drop table if exists CONTIENT;

drop table if exists INFOPARTIE;

drop table if exists INVITER;

drop table if exists JOUERCOUP;

drop table if exists MAIN;

drop table if exists PARTIE;

drop table if exists POSSEDE;

drop table if exists RANGEE;

drop table if exists REJOINDRE;

drop table if exists UTILISATEUR;

/*==============================================================*/
/* Table : CARTE                                                */
/*==============================================================*/
create table CARTE
(
   VALEUR               int,
   NB_POINTS            int,
   ID_CARTE             bigint not null,
   primary key (ID_CARTE)
);

INSERT INTO `CARTE` (`VALEUR`, `NB_POINTS`, `ID_CARTE`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 3, 10),
(11, 5, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 2, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 3, 20),
(21, 1, 21),
(22, 5, 22),
(23, 1, 23),
(24, 1, 24),
(25, 2, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 3, 30),
(31, 1, 31),
(32, 1, 32),
(33, 5, 33),
(34, 1, 34),
(35, 2, 35),
(36, 1, 36),
(37, 1, 37),
(38, 1, 38),
(39, 1, 39),
(40, 3, 40),
(41, 1, 41),
(42, 1, 42),
(43, 1, 43),
(44, 5, 44),
(45, 2, 45),
(46, 1, 46),
(47, 1, 47),
(48, 1, 48),
(49, 1, 49),
(50, 3, 50),
(51, 1, 51),
(52, 1, 52),
(53, 1, 53),
(54, 1, 54),
(55, 7, 55),
(56, 1, 56),
(57, 1, 57),
(58, 1, 58),
(59, 1, 59),
(60, 3, 60),
(61, 1, 61),
(62, 1, 62),
(63, 1, 63),
(64, 1, 64),
(65, 2, 65),
(66, 5, 66),
(67, 1, 67),
(68, 1, 68),
(69, 1, 69),
(70, 3, 70),
(71, 1, 71),
(72, 1, 72),
(73, 1, 73),
(74, 1, 74),
(75, 2, 75),
(76, 1, 76),
(77, 5, 77),
(78, 1, 78),
(79, 1, 79),
(80, 3, 80),
(81, 1, 81),
(82, 1, 82),
(83, 1, 83),
(84, 1, 84),
(85, 2, 85),
(86, 1, 86),
(87, 1, 87),
(88, 5, 88),
(89, 1, 89),
(90, 3, 90),
(91, 1, 91),
(92, 1, 92),
(93, 1, 93),
(94, 1, 94),
(95, 2, 95),
(96, 1, 96),
(97, 1, 97),
(98, 1, 98),
(99, 5, 99),
(100, 3, 100),
(101, 1, 101),
(102, 1, 102),
(103, 1, 103);

/*==============================================================*/
/* Table : CARTEDISTRIBUEES                                     */
/*==============================================================*/
create table CARTEDISTRIBUEES
(
   ID_CARTE             bigint,
   ID_PARTIE            bigint
);

/*==============================================================*/
/* Table : CARTEJOUEE                                           */
/*==============================================================*/
create table CARTEJOUEE
(
   ID_PARTIE            bigint,
   ID_JOUEUR            bigint,
   ID_CARTE             bigint
);

/*==============================================================*/
/* Table : CONTIENT                                             */
/*==============================================================*/
create table CONTIENT
(
   ID_MAIN              bigint not null,
   ID_CARTE             bigint not null,
   primary key (ID_MAIN, ID_CARTE)
);

/*==============================================================*/
/* Table : INFOPARTIE                                           */
/*==============================================================*/
create table INFOPARTIE
(
   ID_JOUEUR            bigint not null,
   NB_PARTIE_G          bigint,
   NB_PARTIE_J          bigint,
   NB_PARTIE_ENCOURS    bigint,
   primary key (ID_JOUEUR)
);

/*==============================================================*/
/* Table : INVITER                                              */
/*==============================================================*/
create table INVITER
(
   ID_JOUEUR            bigint not null,
   ID_PARTIE            bigint not null,
   primary key (ID_JOUEUR, ID_PARTIE)
);

/*==============================================================*/
/* Table : JOUERCOUP                                            */
/*==============================================================*/
create table JOUERCOUP
(
   ID_PARTIE            bigint,
   NB_COUPS             bigint
);

/*==============================================================*/
/* Table : MAIN                                                 */
/*==============================================================*/
create table MAIN
(
   NB_CARTES            int,
   ID_MAIN              bigint not null auto_increment,
   ID_JOUEUR            bigint not null,
   ID_PARTIE            bigint not null,
   primary key (ID_MAIN)
);

/*==============================================================*/
/* Table : PARTIE                                               */
/*==============================================================*/
create table PARTIE
(
   ETAT                 char(10),
   NB_JOUEURS           int,
   ID_PARTIE            bigint not null auto_increment,
   ID_JOUEUR            bigint not null,
   STATUT               char(10),
   primary key (ID_PARTIE)
);

/*==============================================================*/
/* Table : PARTIEARCHIVEE                                       */
/*==============================================================*/

create table PARTIEARCHIVEE 
(
  ID_PARTIE bigint(20) NOT NULL,
  ID_JOUEUR bigint(20) NOT NULL,
  SCORE bigint(20) NOT NULL
) ;



/*==============================================================*/
/* Table : POSSEDE                                              */
/*==============================================================*/
create table POSSEDE
(
   ID_RANGEE            bigint not null,
   ID_CARTE             bigint not null,
   primary key (ID_RANGEE, ID_CARTE)
);

/*==============================================================*/
/* Table : RANGEE                                               */
/*==============================================================*/
create table RANGEE
(
   NB_CARTES            int,
   ID_RANGEE            bigint not null auto_increment,
   ID_PARTIE            bigint not null,
   primary key (ID_RANGEE)
);

/*==============================================================*/
/* Table : REJOINDRE                                            */
/*==============================================================*/
create table REJOINDRE
(
   ID_JOUEUR            bigint not null,
   ID_PARTIE            bigint not null,
   SCORE                int,
   primary key (ID_JOUEUR, ID_PARTIE)
);

/*==============================================================*/
/* Table : UTILISATEUR                                          */
/*==============================================================*/
create table UTILISATEUR
(
   LOGIN                varchar(10),
   PASSWORD             varchar(10),
   NOM                  varchar(20),
   PRENOM               varchar(20),
   ID_JOUEUR            bigint not null auto_increment,
   MAIL                 char(40),
   primary key (ID_JOUEUR)
);

alter table CARTEDISTRIBUEES add constraint FK_REFERENCE_14 foreign key (ID_CARTE)
      references CARTE (ID_CARTE) on delete restrict on update restrict;

alter table CARTEDISTRIBUEES add constraint FK_REFERENCE_15 foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table CARTEJOUEE add constraint FK_REFERENCE_16 foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table CARTEJOUEE add constraint FK_REFERENCE_17 foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table CARTEJOUEE add constraint FK_REFERENCE_18 foreign key (ID_CARTE)
      references CARTE (ID_CARTE) on delete restrict on update restrict;

alter table CONTIENT add constraint FK_CONTIENT foreign key (ID_MAIN)
      references MAIN (ID_MAIN) on delete restrict on update restrict;

alter table CONTIENT add constraint FK_CONTIENT2 foreign key (ID_CARTE)
      references CARTE (ID_CARTE) on delete restrict on update restrict;

alter table INFOPARTIE add constraint FK_INFORMER foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table INVITER add constraint FK_INVITER foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table INVITER add constraint FK_INVITER2 foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table JOUERCOUP add constraint FK_REFERENCE_19 foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table MAIN add constraint FK_APPARTENIR foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table MAIN add constraint FK_ETRE_COMPOSE foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table PARTIE add constraint FK_CREER foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table POSSEDE add constraint FK_POSSEDE foreign key (ID_RANGEE)
      references RANGEE (ID_RANGEE) on delete restrict on update restrict;

alter table POSSEDE add constraint FK_POSSEDE2 foreign key (ID_CARTE)
      references CARTE (ID_CARTE) on delete restrict on update restrict;

alter table RANGEE add constraint FK_CORRESPOND foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

alter table REJOINDRE add constraint FK_REJOINDRE foreign key (ID_JOUEUR)
      references UTILISATEUR (ID_JOUEUR) on delete restrict on update restrict;

alter table REJOINDRE add constraint FK_REJOINDRE2 foreign key (ID_PARTIE)
      references PARTIE (ID_PARTIE) on delete restrict on update restrict;

