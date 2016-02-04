------------
-- GRUPPI --
------------
insert into gruppi (nome) values ('admin');
insert into gruppi (nome) values ('organizzatori');
insert into gruppi (nome) values ('giocatori');

------------
-- UTENTI --
------------
insert into utenti (username, password, nome, cognome, IDorg) values ('-', '336d5ebc5436534e61d16e63ddfca327', '(Direttamente alla', 'fase successiva)', 1);
insert into utenti (username, password, nome, cognome, IDorg) values ('pari', '0291d0ee809eb60deedc864e0c10380d', 'Pareggio', '', 1);
insert into utenti (username, password, nome, cognome, IDorg, avatar) values ('aaron', '449a36b6689d841d7d27f31b4b7cc73a', 'Aronne', 'Brivio', 1, 'aaron.jpg');
insert into utenti (username, password, nome, cognome, IDorg) values ('fiore', 'de43660386db04fa5dce2030568416b0', 'Fiorenzo', 'Mangone', 2);
insert into utenti (username, password, nome, cognome, IDorg) values ('foo', '37b51d194a7513e45b56f6524f2d51f2', 'Foo', 'Bar', 3);
insert into utenti (username, password, nome, cognome, IDorg, avatar) values ('mrossi', 'd9394066970e44ae252fd0347e58c03e', 'Mario', 'Rossi', 3, 'mrossi.png');
insert into utenti (username, password, nome, cognome, IDorg) values ('pinco', '38938c3cdc38161f1720b086575f7208', 'Pinco', 'Pallino', 3);
insert into utenti (username, password, nome, cognome, IDorg, avatar) values ('orga', '768a1c79ae306fadf0d22e7c2200a130', 'Organizza', 'Tore', 2, 'orga.png');
insert into utenti (username, password, nome, cognome, IDorg, avatar) values ('foobar', '3858f62230ac3c915f300c664312c63f', 'Foobar', 'Foobarism', 3, 'foobar.jpg');
insert into utenti (username, password, nome, cognome, IDorg) values ('ivano', '53d5b3c6e4408e869dd3c8374a0ffe98', 'Ivano', 'Tonoli', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('fede', '7d11810cf99c74a1f3fa22c3879ea39d', 'Federico', 'Almaviva', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('vecchio', 'ad01977d7d311d5b5898e274efeb211b', 'Lorenzo', 'Colombo', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('gio', '2bb55d712c4dcbda95497e811b696352', 'Giovanni', 'Milani', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('mirko', '13592f2caf86af30572a825229a2a8dc', 'Mirko', 'Lanzoni', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('dade', '47cd431b64156b1b227622ea33419fa6', 'Davide', 'Prete', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('teo', 'e827aa1ed78e96a113182dce12143f9f', 'Matteo', 'Di Tullio', 3);
insert into utenti (username, password, nome, cognome, IDorg) values ('giuscri', '1d29a2d00807660c3dc603305a56c66b', 'Giuseppe', 'Crinò', 3);

-----------------
-- TIPO TORNEO --
-----------------
insert into tipo_torneo (nome) values ('libero');
insert into tipo_torneo (nome) values ('misto');
insert into tipo_torneo (nome) values ('eliminazione diretta');
insert into tipo_torneo (nome) values ('italiana');

------------
-- TORNEO --
------------
-- PASSATI
-- Libero
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Libero', 1, 1, '2014-10-13', '2014-10-10', 5, 8, 1.50, null);
-- Misto
insert into torneo (nome, ed, tipo, num_gironi, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Misto', 1, 2, 3, '2014-10-13', '2014-10-10', 15, 3, 3.50, null);
-- Eliminazione diretta
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Eliminazione', 1, 3, '2014-10-13', '2014-10-10', 16, 8, 4.20, null);
-- All'italiana
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Italiano', 1, 4, '2014-10-13', '2014-10-10', 10, 3, 5.00, null);

-- IN CORSO
-- Libero
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Libero', 2, 1, '2015-12-02', '2015-12-01', 5, 8, 1.50, 1);
-- Misto
insert into torneo (nome, ed, tipo, num_gironi, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Misto', 2, 2, 3, '2015-12-02', '2015-12-01', 15, 3, 3.50, 2);
-- Eliminazione diretta
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Eliminazione', 2, 3, '2015-12-02', '2015-12-01', 16, 8, 4.20, 3);
-- All'italiana
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Italiano', 2, 4, '2015-12-02', '2015-12-01', 10, 3, 5.00, 4);

-- FUTURI
-- Libero
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Libero', 3, 1, '2016-06-13', '2016-06-10', 5, 8, 1.50, 1);
-- Misto
insert into torneo (nome, ed, tipo, num_gironi, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Misto', 3, 2, 3, '2016-06-13', '2016-06-10', 15, 3, 3.50, 2);
-- Eliminazione diretta
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Eliminazione', 3, 3, '2016-06-13', '2016-06-10', 16, 8, 4.20, 3);
-- All'italiana
insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
			values ('Italiano', 3, 4, '2016-06-13', '2016-06-10', 10, 3, 5.00, 4);

-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
-- 			values ('Risiko', 1, 3, '2015-12-13', '2015-12-10', 10, 'orga', 3.50, null, null);
-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
-- 			values ('Risiko', 2, 3, '2016-12-13', '2016-12-10', 10, 'orga', 3.50, 'Risiko', 1);
-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
-- 			values ('Futuro', 1, 1, '2016-10-13', '2016-10-10', 10, 'orga', 5, null, null);
-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
-- 			values ('Italiano', 1, 4, '2015-11-13', '2015-11-12', 10, 'orga', 5, null, null);
-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, quota_iscr, IDriedizione) 
-- 			values ('Liberooo', 1, 1, '2015-12-1', '2015-11-30', 16, 'orga', 1.50, null, null);
-- insert into torneo (nome, ed, tipo, data_inizio, chiusura_iscr, partecipanti, admin, num_gironi, quota_iscr, IDriedizione) 
-- 			values ('Misto', 1, 2, '2015-12-2', '2015-12-1', 16, 'orga', 3, 1.50, null, null);

INSERT INTO torneo (nome, ed, tipo, data_inizio, data_fine, chiusura_iscr, quota_iscr, partecipanti, admin, num_gironi, IDriedizione) 
	VALUES ('Provazzz', '1', '1', '2015-12-14', NULL, '2015-12-13', '1.50', '15', 8, NULL, NULL);


--------------------------
-- ISCRIZIONI AI TORNEI --
--------------------------
-- PASSATI
-- Libero 1
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 1, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 1, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 1, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 1, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 1, 1, 0, 0);
-- Misto 1
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 2, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 2, 1, 0, 0);
-- Eliminazione 1
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 3, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 3, 1, 0, 0);
-- Italiano 1
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 4, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 4, 1, 0, 0);

-- IN CORSO
-- Libero 2
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 5, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 5, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 5, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 5, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 5, 1, 0, 0);
-- Misto 2
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 6, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 6, 1, 0, 0);
-- Eliminazione 2
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 7, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 7, 1, 0, 0);
-- Italiano 2
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 8, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 8, 1, 0, 0);

-- FUTURI
-- Libero 3
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 9, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 9, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 9, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 9, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 9, 1, 0, 0);
-- Misto 3
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 10, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 10, 1, 0, 0);
-- Eliminazione 3
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (3, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (11, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (16, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (4, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (13, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 11, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 11, 1, 0, 0);
-- Italiano 3
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (12, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (14, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (5, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (8, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (7, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (9, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (6, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (17, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (10, 12, 1, 0, 0);
insert into iscritto_a (IDutente, IDtorneo, approvato, sconto_rincaro, ban) values (15, 12, 1, 0, 0);