-- 3
--------------------
-- ELIMINAZIONE 1 --
--------------------
-- Gare
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-13', 1, 3, 3);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-14', 1, 3, 11);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-15', 1, 3, 16);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-16', 1, 3, 17);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-17', 1, 3, 14);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-18', 1, 3, 5);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-19', 1, 3, 13);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-20', 1, 3, 4);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-21', 2, 3, 3);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-22', 2, 3, 11);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-23', 2, 3, 16);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-24', 2, 3, 14);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-25', 3, 3, 3);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-26', 3, 3, 11);
insert into gara (data, fase, IDtorneo, vincitore) values ('2014-10-27', 4, 3, 3);

-- Partecipanti
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (3, 58, 3, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (1, 58, 0, 0);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (11, 59, 3, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (15, 59, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (16, 60, 3, 3);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (10, 60, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (17, 61, 3, 3);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (12, 61, 0, 0);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (14, 62, 3, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (6, 62, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (5, 63, 3, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (9, 63, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (8, 64, 0, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (13, 64, 3, 4);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (4, 65, 3, 3);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (7, 65, 0, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (3, 66, 3, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (4, 66, 0, 0);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (11, 67, 3, 4);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (13, 67, 0, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (5, 68, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (16, 68, 3, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (14, 69, 3, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (17, 69, 0, 0);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (3, 70, 3, 3);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (14, 70, 0, 2);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (11, 71, 3, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (16, 71, 0, 0);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (11, 72, 0, 1);
insert into partecipa_a (IDutente, IDgara, punteggio, risultato) values (3, 72, 3, 2);

-- Termino il torneo
select set_fine(3);

-- inserisco il vincitore
select ins_vincitore(3);