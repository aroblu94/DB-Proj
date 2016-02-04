-- 6
--------------
-- LIBERO 2 --
--------------
-- Gare
insert into gara (data, IDtorneo) values ('2015-12-02',5);
insert into gara (data, IDtorneo) values ('2015-12-03',5);
insert into gara (data, IDtorneo) values ('2015-12-04',5);
insert into gara (data, IDtorneo) values ('2015-12-05',5);
insert into gara (data, IDtorneo) values ('2015-12-06',5);
insert into gara (data, IDtorneo) values ('2015-12-07',5);
insert into gara (data, IDtorneo) values ('2015-12-08',5);
insert into gara (data, IDtorneo) values ('2015-12-09',5);
insert into gara (data, IDtorneo) values ('2015-12-10',5);
insert into gara (data, IDtorneo) values ('2015-12-11',5);
insert into gara (data, IDtorneo) values ('2015-12-12',5);
insert into gara (data, IDtorneo) values ('2015-12-13',5);

-- Partecipanti
insert into partecipa_a (IDutente, IDgara) values (3, 159);
insert into partecipa_a (IDutente, IDgara) values (11, 159);
insert into partecipa_a (IDutente, IDgara) values (12, 160);
insert into partecipa_a (IDutente, IDgara) values (16, 160);
insert into partecipa_a (IDutente, IDgara) values (14, 161);
insert into partecipa_a (IDutente, IDgara) values (3, 161);
insert into partecipa_a (IDutente, IDgara) values (11, 162);
insert into partecipa_a (IDutente, IDgara) values (12, 162);
insert into partecipa_a (IDutente, IDgara) values (16, 163);
insert into partecipa_a (IDutente, IDgara) values (14, 163);
insert into partecipa_a (IDutente, IDgara) values (3, 164);
insert into partecipa_a (IDutente, IDgara) values (16, 164);
insert into partecipa_a (IDutente, IDgara) values (14, 165);
insert into partecipa_a (IDutente, IDgara) values (11, 165);
insert into partecipa_a (IDutente, IDgara) values (16, 166);
insert into partecipa_a (IDutente, IDgara) values (12, 166);
insert into partecipa_a (IDutente, IDgara) values (11, 167);
insert into partecipa_a (IDutente, IDgara) values (16, 167);
insert into partecipa_a (IDutente, IDgara) values (3, 168);
insert into partecipa_a (IDutente, IDgara) values (14, 168);
insert into partecipa_a (IDutente, IDgara) values (11, 169);
insert into partecipa_a (IDutente, IDgara) values (12, 169);
insert into partecipa_a (IDutente, IDgara) values (3, 170);
insert into partecipa_a (IDutente, IDgara) values (16, 170);

-- faccio finire la gara
select set_fine(5);