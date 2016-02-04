-------------------------
----- GENERA TORNEO -----
-------------------------
-- genera i tornei usando le funzioni scritte sotto
-- (per il torneo libero è tutto delegato al PHP)
create or replace function genera_torneo(idt INT)
RETURNS INT as $$
	DECLARE t INT;
	DECLARE part INT;
	DECLARE res INT;
	DECLARE closed DATE;

	BEGIN
		select tipo, chiusura_iscr into t, closed
			from torneo
			where id=idt;
		select count(*) into part
			from iscritto_a
			where IDtorneo=idt
				and approvato=1
				and ban=0;
		-- chiudo le iscrizioni (se non sono gia chiuse)
		if closed>now() then
			update torneo set chiusura_iscr=now() where id=idt;
		end if;

		-- 1 vs 1
		if t = 2 then -- torneo misto
			SELECT into res genera_torneo_misto(idt, part);
		elsif t = 3 then -- torneo eliminazione diretta
			SELECT into res genera_torneo_eliminazione(idt, part);
		else -- torneo all'italiana
			SELECT into res genera_torneo_italiana(idt, part);
		end if;
		return res;
	END;    
$$ language plpgsql;


------------------------
-- GENERA GIRONI/GARE --
------------------------

-- genera torneo all'italiana
create or replace function genera_torneo_italiana (idt INT, num_part INT)
RETURNS INT as $$
	DECLARE ngare INT;
	DECLARE IDgara INT;
	DECLARE datazz DATE DEFAULT NOW();
	DECLARE gioc1 INT;
	DECLARE gioc2 INT;
	
	BEGIN
		ngare = (num_part*(num_part-1))/2;
		UPDATE torneo SET num_gare = ngare
			WHERE id=idt;

		-- correggo la data di inizio gare
		select data_inizio into datazz
			from torneo
			where id=idt;
		datazz = datazz - INTERVAL '1 DAY';

		for gioc1 in SELECT IDutente
					FROM iscritto_a
					WHERE IDtorneo=idt 
						and ban=0
					order by random()
		loop
			for gioc2 in SELECT IDutente
					FROM iscritto_a
					WHERE IDtorneo=idt
						and ban=0
					order by random() 
			loop
				-- devono essere diversi
				-- e non devono essersi già scontrati tra loro
				if gioc1<gioc2 then
					datazz = datazz + INTERVAL '1 DAY';
					-- inserisco la gara
					INSERT INTO gara(IDtorneo, data)
						VALUES (idt,datazz);
					-- salvo il suo id
					IDgara = currval('gara_id_seq');
					-- popolo la tabella partecipa_a
					INSERT INTO partecipa_a(IDutente, IDgara)
						VALUES(gioc1, IDgara);
					INSERT INTO partecipa_a(IDutente, IDgara)
						VALUES(gioc2, IDgara);
				end if;
			END LOOP;
		END LOOP;
		UPDATE torneo SET data_fine = datazz
			WHERE id=idt; 
		-- le gare ora però sono troppo "ordinate"
		-- hack per disordinarle:
		for IDgara in select id
					from gara
					where IDtorneo=idt
					order by random()
		loop
			update gara set data = datazz
				where id = IDgara;
			datazz = datazz - INTERVAL '1 DAY';
		end loop;
		return 1;
	END;
$$ language plpgsql;

-- genera torneo a eliminazione diretta
create or replace function genera_torneo_eliminazione (idt INT, num_part INT)
RETURNS INT as $$
	DECLARE res INT DEFAULT 0;
	BEGIN
		select into res genera_fase(idt, 0, num_part);
		return res;
	END;
$$ language plpgsql;

create or replace function genera_fase (idt INT, f INT, num_part INT)
RETURNS INT as $$
	DECLARE id_gara INT;
	DECLARE datazz DATE DEFAULT NOW();
	DECLARE numgare INT;
	DECLARE parts INT[];
	DECLARE c INT DEFAULT 0;
	DECLARE crev INT;
	DECLARE iscr INT DEFAULT 0;
	DECLARE fine INT DEFAULT 0;
	DECLARE ret INT DEFAULT 0;
	DECLARE res INT DEFAULT 0;

	BEGIN
		ret = 1;
		-- correggo la data di inizio gare
		select data_inizio into datazz
			from torneo
			where id=idt;
		datazz = datazz - INTERVAL '1 DAY';

		if f > 0 then
			-- numero di giocatori che passano alla fase succesiva
			select count(*), array_agg(p.IDutente), max(data) into num_part, parts, datazz
			from partecipa_a as p 
				join gara as g on p.IDgara = g.id
			where g.IDtorneo=idt
				and g.fase = f
				and p.punteggio >= ALL(
					select p2.punteggio
						from partecipa_a as p2
							left join iscritto_a ia on ia.IDtorneo=g.IDtorneo
						where p2.IDgara = g.id
							and g.IDtorneo=idt
							and ia.ban=0)
				order by random();
		else
			-- se è la prima fase prendo tutti i giocatori iscritti
			select count(*), array_agg(ia.IDutente) into num_part, parts
			from iscritto_a as ia
			where ia.IDtorneo=idt
				and ia.approvato=1
				and ia.ban=0
			order by random();
		end if;

		-- numero gare = numero partecipanti / 2
		numgare = num_part/2;
		-- dispari?
		if num_part%2 <> 0 then
			if num_part<2 then
				-- VINCITORE!
				select into res ins_vincitore(idt);
				ret = 0;
			else
				-- numero gare++
				numgare = numgare + 1;
				-- aggiungo giocatore "farlocco" in coda (id=1)
				select into iscr iscrivi_torneo(1, idt);
				if iscr>0 then
					perform approve_iscr(1, idt);
				end if;
				parts = array_append(parts,1);
				num_part = num_part + 1;
			end if;
		end if;

		-- uso crev come indice dell'array a ritroso
		-- ad una gara partecipano il primo e l'ultimo:
		-- parts[count] e parts[crev]
		crev = num_part;
		c = 1;
		if crev=c+1 then
			fine=1;
		end if;

		while c < crev AND ret = 1 loop
			-- creazione gara
			datazz = datazz + INTERVAL '1 DAY';
			insert into gara (IDtorneo, data, fase)
				values (idt, datazz, f+1);
			id_gara = currval('gara_id_seq');
			-- inserisci i giocatori nelle gare
			insert into partecipa_a (IDutente, IDgara)
				values (parts[c], id_gara);
			insert into partecipa_a (IDutente, IDgara)
				values (parts[crev], id_gara);
			-- se in questa gara è presente il giocatore farlocco
			-- => vince l'altro a tavolino
			-- (= passa alla fase successiva)
			if parts[c]=1 then
				perform registra_risultato(id_gara, parts[crev], 1, parts[c], 0);
			elsif parts[crev]=1 then
				perform registra_risultato(id_gara, parts[c], 1, parts[crev], 0); 
			end if;
			-- se la fase è composta da una sola gara
			-- => è l'ultima gara del torneo
			if fine=1 then
				update torneo set data_fine=datazz
					where id=idt;
			end if;
			-- contatori
			c = c + 1;
			crev = crev - 1;
			res = 1;
		end loop;

		return res;
	END;
$$ language plpgsql;

-- genera fase successiva
create or replace function genera_fase_succ(idt INT)
RETURNS INT as $$
	DECLARE checkk INT DEFAULT 0;
	DECLARE curr_fase INT DEFAULT 0;
	DECLARE ret INT DEFAULT 0;
	BEGIN
		select max(fase) into curr_fase
			from gara
			where IDtorneo=idt;
		select into checkk is_fase_ended(idt, curr_fase);
		if checkk>0 then
			select into ret genera_fase(idt, curr_fase, 0);
		end if;
		return ret;
	END;
$$ language plpgsql;


-------------------------
-- GENERA TORNEO MISTO --
-------------------------
create or replace function genera_torneo_misto(idt INT, p INT)
RETURNS INT as $$
	DECLARE ngir INT;
	-- numero giocatori per girone
	DECLARE g_per_girone INT;
	-- rimanenti dalla divisione intera
	DECLARE resto INT;
	DECLARE idgirone INT;
	DECLARE uout INT;
	-- contatore gironi
	DECLARE counter INT DEFAULT 1;
	DECLARE offsetzz INT;
	DECLARE ret INT;
	BEGIN
		ret = 1;
		-- numero gironi
		select num_gironi into ngir from torneo
			where id=idt;

		-- creo i gironi
		select into ret crea_gironi(idt);

		-- numero di giocatori per girone
		g_per_girone = p/ngir;
		resto = p % ngir;

		offsetzz = 0;
		counter = 1;
		-- per sicurezza rimuovo la tabella temporanea, poi la ricreo
		-- la tabella serve per gestire i giocatori
		-- dell'attuale girone
		drop table if exists partecipa_girone;
		create temporary table partecipa_girone (
			id int primary key not null default nextval('partg_id_seq'),
			IDutente int not null,
			IDgirone int not null
		);
		-- genero i gironi
		while counter<=ngir and ret=1 loop
			-- id girone
			select id into idgirone 
				from girone
				where numero=counter
					and IDtorneo=idt;

			if counter=ngir then
				-- metto tutti i giocatori restanti nell'ultimo girone
				g_per_girone = g_per_girone + resto;
			end if;

			-- update del girone
			update girone set num_giocatori=g_per_girone
				where id=idgirone;

			insert into partecipa_girone (IDutente, IDgirone)
				select ia.IDutente, idgirone
				from iscritto_a as ia
				where ia.IDtorneo=idt
					and ia.ban=0
				limit g_per_girone
					offset offsetzz;
			-- creo le gare del girone e ci iscrivo gli utenti
			select into ret crea_gare_girone(idgirone, g_per_girone, counter);

			offsetzz = offsetzz + g_per_girone;
			counter = counter + 1;
		end loop;

		return ret;

	END;
$$ language plpgsql;

-- creazione gironi per torneo misto
create or replace function crea_gironi(idt INT)
RETURNS INT as $$
	DECLARE max INT;
	DECLARE count INT DEFAULT 1;
	DECLARE datazz DATE;
	BEGIN
		count = 1;
		select num_gironi, data_inizio into max, datazz
			from torneo
			where id=idt;

		while count <= max loop
			insert into girone (numero, data_inizio, IDtorneo)
				values (count, datazz, idt);
			count = count + 1;
		end loop;
		return 1;
	END;
$$ language plpgsql;

-- creazione gare del girone
create or replace function crea_gare_girone(idg INT, num_part INT, ngir INT)
RETURNS INT as $$
	DECLARE ngare INT;
	DECLARE IDgara INT;
	DECLARE datazz DATE DEFAULT NOW();
	DECLARE gioc1 INT;
	DECLARE gioc2 INT;
	DECLARE idt INT;
	DECLARE ret INT;
	
	BEGIN
		-- identifico il torneo
		select IDtorneo into idt 
			from girone
			where id=idg;

		ngare = (num_part*(num_part-1))/2;
		UPDATE girone SET num_gare = ngare
			WHERE id = idg;

		-- correggo la data di inizio gare
		select data_inizio into datazz
			from girone
			where id=idg;
		datazz = datazz - INTERVAL '1 DAY';

		for gioc1 in SELECT IDutente
					FROM partecipa_girone
					WHERE IDgirone=idg 
					order by random()
		loop
			for gioc2 in SELECT IDutente
					FROM partecipa_girone
					WHERE IDgirone=idg
					order by random() 
			loop
				-- devono essere diversi
				-- e non devono essersi già scontrati tra loro
				if gioc1<gioc2 then
					datazz = datazz + INTERVAL '1 DAY';
					-- inserisco la gara
					INSERT INTO gara(IDtorneo, data, girone)
						VALUES (idt,datazz, ngir);
					-- salvo il suo id
					IDgara = currval('gara_id_seq');
					-- popolo la tabella partecipa_a
					INSERT INTO partecipa_a(IDutente, IDgara)
						VALUES(gioc1, IDgara);
					INSERT INTO partecipa_a(IDutente, IDgara)
						VALUES(gioc2, IDgara);
					ret = 1;
				end if;
			END LOOP;
		END LOOP;
		UPDATE girone SET data_fine = datazz
			WHERE id=idg; 
		-- le gare ora però sono troppo "ordinate"
		-- hack per disordinarle:
		for IDgara in select id
					from gara
					where IDtorneo=idt
					and girone=ngir
					order by random()
		loop
			update gara set data = datazz
				where id = IDgara;
			datazz = datazz - INTERVAL '1 DAY';
		end loop;
		return ret;

	END;
$$ language plpgsql;

-- genera la seconda parte del torneo misto
-- => torneo a eliminazione diretta tra i vincitori dei gironi
create or replace function genera_ita_misto(idt INT)
RETURNS INT as $$
	DECLARE ok INT DEFAULT 0;
	DECLARE okk INT DEFAULT 0;
	DECLARE fase_att INT DEFAULT 0;
	DECLARE fine_fase INT DEFAULT 0;
	DECLARE res INT;
	DECLARE num INT;
	BEGIN
		-- il torneo misto ha già fasi?
		-- => è terminata la fase a gironi?
		select into okk has_fasi(idt);
		if okk<1 then
			-- tutte le gare dei gironi hanno un vincitore?
			select into ok is_fase_ita_ended(idt);
		else
			-- a che fase siamo?
			select max(fase) into fase_att from gara where IDtorneo=idt;
			-- => le gare dell'ultima fase hanno tutte un vincitore?
			select into fine_fase is_fase_ended(idt,fase_att);
			ok=0;
		end if;

		if ok>0 then
			-- numero partecipanti che accedono alla seconda parte
			-- del torneo misto (quella all'italiana)
			-- => 1 per ogni gara dei gironi
			-- => conto le gare
			select count(*) into num
				from gara
				where IDtorneo=idt 
					and girone is not null;
			select into res genera_fase_misto(idt,0,num);
		elsif fine_fase>0 then
			-- non è la prima fase?
			-- vado avanti...
			select count(*) into num
				from gara
				where IDtorneo=idt 
					and fase=fase_att;
			select into res genera_fase_misto(idt,fase_att,num);
		else
		   res = 0;
		end if;
		return res;
	END;
$$ language plpgsql;

-- genera le fasi della parte all'italiana del torneo misto
create or replace function genera_fase_misto(idt INT, f INT, num_part INT)
RETURNS INT as $$
	DECLARE id_gara INT;
	DECLARE datazz DATE DEFAULT NOW();
	DECLARE numgare INT;
	DECLARE parts INT[];
	DECLARE c INT DEFAULT 0;
	DECLARE crev INT;
	DECLARE iscr INT DEFAULT 0;
	DECLARE fine INT DEFAULT 0;
	DECLARE ret INT DEFAULT 0;
	DECLARE res INT DEFAULT 0;
	BEGIN
		ret = 1;
		if f > 0 then
			-- correggo la data di inizio gare
			select max(data) into datazz
				from gara
				where IDtorneo=idt
					and fase=f;
			-- giocatori che passano alla fase succesiva
			select count(*), array_agg(p.IDutente), max(data) into num_part, parts, datazz
			from partecipa_a as p 
				join gara as g on p.IDgara = g.id
			where g.IDtorneo=idt
				and g.fase = f
				and p.punteggio >= ALL(
					select p2.punteggio
						from partecipa_a as p2
							left join iscritto_a ia on ia.IDtorneo=g.IDtorneo 
						where p2.IDgara = g.id
							and g.IDtorneo=idt
							and ia.ban=0)
				order by random();
		else
			-- correggo la data di inizio gare
			select max(data) into datazz
				from gara
				where IDtorneo=idt
					and girone is not null;
			-- se è la prima fase prendo i vincitori dei gironi
			select count(*), array_agg(p.IDutente) into num_part, parts
			from partecipa_a as p 
				join gara as g on p.IDgara = g.id
			where g.IDtorneo=idt
				and g.fase is null
				and p.punteggio >= ALL(
					select p2.punteggio
						from partecipa_a as p2 
							left join iscritto_a ia on ia.IDtorneo=g.IDtorneo
						where p2.IDgara = g.id
							and g.IDtorneo=idt
							and ia.ban=0);
			-- tolgo i doppioni
			select array(select distinct unnest(parts::INT[])) into parts;
			select array_length(parts, 1) into num_part;
		end if;

		-- numero gare = numero partecipanti / 2
		numgare = num_part/2;
		-- dispari?
		if num_part%2 <> 0 then
			if num_part<2 then
				-- VINCITORE!
				select into res ins_vincitore(idt);
				ret = 0;
			else
				-- numero gare++
				numgare = numgare + 1;
				-- aggiungo giocatore "farlocco" in coda
				select into iscr iscrivi_torneo(1, idt);
				if iscr>0 then
					perform approve_iscr(1, idt);
					num_part = num_part + 1;
				end if;
				parts = array_append(parts,1);
			end if;
		end if;

		-- uso crev come indice dell'array a ritroso
		-- ad una gara partecipano il primo e l'ultimo:
		-- parts[count] e parts[crev]
		crev = num_part;
		c = 1;
		if crev=c+1 then
			fine=1;
		end if;

		while c < crev AND ret = 1 loop
			-- creazione gara
			datazz = datazz + INTERVAL '1 DAY';
			insert into gara (IDtorneo, data, fase)
				values (idt, datazz, f+1);
			id_gara = currval('gara_id_seq');
			-- inserisci i giocatori nelle gare
			insert into partecipa_a (IDutente, IDgara)
				values (parts[c], id_gara);
			insert into partecipa_a (IDutente, IDgara)
				values (parts[crev], id_gara);
			-- se in questa gara è presente farloc
			-- => vince l'altro a tavolino
			-- (= passa alla fase successiva)
			if parts[c]=1 then
				perform registra_risultato(id_gara, parts[crev], 1, parts[c], 0);
			elsif parts[crev]=1 then
				perform registra_risultato(id_gara, parts[c], 1, parts[crev], 0); 
			end if;
			-- se la fase è composta da una sola gara
			-- => è l'ultima gara del torneo
			if fine=1 then
				update torneo set data_fine=datazz
					where id=idt;
			end if;
			-- contatori
			c = c + 1;
			crev = crev - 1;
			res = 1;
		end loop;

		return res;
	END;
$$ language plpgsql;

-- genera fase successiva del torneo misto
create or replace function genera_fase_misto_succ(idt INT)
RETURNS INT as $$
	DECLARE checkk INT DEFAULT 0;
	DECLARE curr_fase INT DEFAULT 0;
	DECLARE ret INT DEFAULT 0;
	BEGIN
		select max(fase) into curr_fase
			from gara
			where IDtorneo=idt;
		select into checkk is_fase_ended(idt, curr_fase);
		if checkk>0 then
			select into ret genera_fase(idt, curr_fase, 0);
		end if;
		return ret;
	END;
$$ language plpgsql;

------------------------
-- GARE TORNEO LIBERO --
------------------------
-- creazione gara
create or replace function ins_gara(idt INT, p1 INT, p2 INT, d DATE)
RETURNS INT as $$
	DECLARE idg INT;
	BEGIN
		-- inserisco la gara
		insert into gara (data, IDtorneo)
			values (d, idt);
		-- inserisco i due giocatori
		idg = currval('gara_id_seq');
		insert into partecipa_a (IDutente, IDgara)
			values (p1, idg);
		insert into partecipa_a (IDutente, IDgara)
			values (p2, idg);
		return 1;    
	END;
$$ language plpgsql;
