-- 2
--------------
-- LIBERO 1 --
--------------
-- Gare
insert into gara (data, IDtorneo, vincitore) values ('2014-10-13', 1, 3);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-14', 1, 12);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-15', 1, 14);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-16', 1, 11);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-17', 1, 16);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-18', 1, 3);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-19', 1, 14);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-20', 1, 16);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-21', 1, 11);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-22', 1, 3);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-23', 1, 11);
insert into gara (data, IDtorneo, vincitore) values ('2014-10-24', 1, 3);

-- Partecipanti
insert into partecipa_a values (3, 46, 3, 2);
insert into partecipa_a values (11, 46, 0, 1);
insert into partecipa_a values (12, 47, 3, 2);
insert into partecipa_a values (16, 47, 0, 1);
insert into partecipa_a values (14, 48, 3, 2);
insert into partecipa_a values (3, 48, 0, 1);
insert into partecipa_a values (11, 49, 3, 2);
insert into partecipa_a values (12, 49, 0, 1);
insert into partecipa_a values (16, 50, 3, 2);
insert into partecipa_a values (14, 50, 0, 1);
insert into partecipa_a values (3, 51, 3, 2);
insert into partecipa_a values (16, 51, 0, 1);
insert into partecipa_a values (14, 52, 3, 2);
insert into partecipa_a values (11, 52, 0, 1);
insert into partecipa_a values (16, 53, 3, 2);
insert into partecipa_a values (12, 53, 0, 1);
insert into partecipa_a values (11, 54, 3, 2);
insert into partecipa_a values (16, 54, 0, 1);
insert into partecipa_a values (3, 55, 3, 2);
insert into partecipa_a values (14, 55, 0, 1);
insert into partecipa_a values (11, 56, 3, 2);
insert into partecipa_a values (12, 56, 0, 1);
insert into partecipa_a values (3, 57, 3, 2);
insert into partecipa_a values (16, 57, 0, 1);

-- faccio finire la gara
select set_fine(1);

-- inserisco il vincitore
select ins_vincitore(1);