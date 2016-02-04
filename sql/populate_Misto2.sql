-- 8
-------------
-- MISTO 2 --
-------------
-- Gironi
insert into girone (numero, data_inizio, data_fine, num_giocatori, num_gare, IDtorneo) values (1, '2015-12-02', '2015-12-11', 5, 10, 6);
insert into girone (numero, data_inizio, data_fine, num_giocatori, num_gare, IDtorneo) values (2, '2015-12-02', '2015-12-11', 5, 10, 6);
insert into girone (numero, data_inizio, data_fine, num_giocatori, num_gare, IDtorneo) values (3, '2015-12-02', '2015-12-11', 5, 10, 6);

-- Gare
insert into gara (data, girone, IDtorneo) values ('2015-12-11', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-10', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-09', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-08', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-07', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-06', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-05', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-04', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-03', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-02', 1, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-11', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-10', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-09', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-08', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-07', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-06', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-05', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-04', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-03', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-02', 2, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-11', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-10', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-09', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-08', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-07', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-06', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-05', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-04', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-03', 3, 6);
insert into gara (data, girone, IDtorneo) values ('2015-12-02', 3, 6);

-- Partecipanti
insert into partecipa_a (IDutente, IDgara) values (11, '179');
insert into partecipa_a (IDutente, IDgara) values (14, '179');
insert into partecipa_a (IDutente, IDgara) values (14, '180');
insert into partecipa_a (IDutente, IDgara) values (16, '180');
insert into partecipa_a (IDutente, IDgara) values (11, '181');
insert into partecipa_a (IDutente, IDgara) values (16, '181');
insert into partecipa_a (IDutente, IDgara) values (12, '182');
insert into partecipa_a (IDutente, IDgara) values (16, '182');
insert into partecipa_a (IDutente, IDgara) values (14, '183');
insert into partecipa_a (IDutente, IDgara) values (12, '183');
insert into partecipa_a (IDutente, IDgara) values (3, '184');
insert into partecipa_a (IDutente, IDgara) values (12, '184');
insert into partecipa_a (IDutente, IDgara) values (3, '185');
insert into partecipa_a (IDutente, IDgara) values (16, '185');
insert into partecipa_a (IDutente, IDgara) values (3, '186');
insert into partecipa_a (IDutente, IDgara) values (11, '186');
insert into partecipa_a (IDutente, IDgara) values (3, '187');
insert into partecipa_a (IDutente, IDgara) values (14, '187');
insert into partecipa_a (IDutente, IDgara) values (16, '188');
insert into partecipa_a (IDutente, IDgara) values (12, '188');

insert into partecipa_a (IDutente, IDgara) values (5, '189');
insert into partecipa_a (IDutente, IDgara) values (8, '189');
insert into partecipa_a (IDutente, IDgara) values (5, '190');
insert into partecipa_a (IDutente, IDgara) values (7, '190');
insert into partecipa_a (IDutente, IDgara) values (5, '191');
insert into partecipa_a (IDutente, IDgara) values (13, '191');
insert into partecipa_a (IDutente, IDgara) values (4, '192');
insert into partecipa_a (IDutente, IDgara) values (5, '192');
insert into partecipa_a (IDutente, IDgara) values (4, '193');
insert into partecipa_a (IDutente, IDgara) values (7, '193');
insert into partecipa_a (IDutente, IDgara) values (4, '194');
insert into partecipa_a (IDutente, IDgara) values (8, '194');
insert into partecipa_a (IDutente, IDgara) values (4, '195');
insert into partecipa_a (IDutente, IDgara) values (13, '195');
insert into partecipa_a (IDutente, IDgara) values (8, '196');
insert into partecipa_a (IDutente, IDgara) values (7, '196');
insert into partecipa_a (IDutente, IDgara) values (13, '197');
insert into partecipa_a (IDutente, IDgara) values (8, '197');
insert into partecipa_a (IDutente, IDgara) values (13, '198');
insert into partecipa_a (IDutente, IDgara) values (7, '198');
insert into partecipa_a (IDutente, IDgara) values (9, '199');
insert into partecipa_a (IDutente, IDgara) values (6, '199');
insert into partecipa_a (IDutente, IDgara) values (9, '200');
insert into partecipa_a (IDutente, IDgara) values (17, '200');
insert into partecipa_a (IDutente, IDgara) values (9, '201');
insert into partecipa_a (IDutente, IDgara) values (10, '201');
insert into partecipa_a (IDutente, IDgara) values (15, '202');
insert into partecipa_a (IDutente, IDgara) values (10, '202');
insert into partecipa_a (IDutente, IDgara) values (15, '203');
insert into partecipa_a (IDutente, IDgara) values (9, '203');
insert into partecipa_a (IDutente, IDgara) values (15, '204');
insert into partecipa_a (IDutente, IDgara) values (17, '204');
insert into partecipa_a (IDutente, IDgara) values (15, '205');
insert into partecipa_a (IDutente, IDgara) values (6, '205');
insert into partecipa_a (IDutente, IDgara) values (17, '206');
insert into partecipa_a (IDutente, IDgara) values (10, '206');
insert into partecipa_a (IDutente, IDgara) values (17, '207');
insert into partecipa_a (IDutente, IDgara) values (6, '207');
insert into partecipa_a (IDutente, IDgara) values (10, '208');
insert into partecipa_a (IDutente, IDgara) values (6, '208');
