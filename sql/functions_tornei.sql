------------------------------
-- FUNZIONI GENERALI TORNEI --
------------------------------
create or replace function get_nomet(idt INT)
RETURNS CHAR(20) as $$
	DECLARE n CHAR(20);
	BEGIN
		select nome into n from torneo where id=idt;
		return n;
	END;
$$ language plpgsql;

create or replace function get_edizione(idt INT)
RETURNS INT as $$
	DECLARE e INT;
	BEGIN
		select ed into e from torneo where id=idt;
		return e;
	END;
$$ language plpgsql;

create or replace function get_tipo(idt INT)
RETURNS INT as $$
	DECLARE ret INT;
	BEGIN
		select tipo into ret from torneo where id=idt;
		return ret;
	END;
$$ language plpgsql;

create or replace function get_fine(idt INT)
RETURNS DATE as $$
	DECLARE ret DATE;
	BEGIN
		select data_fine into ret from torneo where id=idt;
		return ret;
	END;
$$ language plpgsql;

create or replace function get_inizio(idt INT)
RETURNS DATE as $$
	DECLARE ret DATE;
	BEGIN
		select data_inizio into ret from torneo where id=idt;
		return ret;
	END;
$$ language plpgsql;

-- imposta la fine del torneo (tornei liberi)
create or replace function set_fine(idt INT)
RETURNS INT as $$
	DECLARE datazz DATE;
	BEGIN
		select max(data) into datazz
			from gara
			where IDtorneo=idt;
		UPDATE torneo SET data_fine = datazz
			WHERE id=idt;
		return 1; 
	END;
$$ language plpgsql;

-- controlla se il torneo libero è stato creato del tutto
create or replace function has_fine(idt INT)
RETURNS INT as $$
	DECLARE dat DATE;
	BEGIN
		select data_fine into dat
			from torneo
			where id=idt;
		if dat is null then
			return 0;
		else
			return 1;
		end if;
	END;
$$ language plpgsql;

create or replace function elimina_torneo(idt INT)
RETURNS INT as $$
	DECLARE u INT;
	DECLARE d CHAR(255);
	BEGIN
		for u in select IDutente from iscritto_a
			where IDtorneo=idt
		loop
			if u>2 then
				select concat('Il torneo ',nome,' ed.',ed,' è stato annullato') into d from torneo where id=idt;
				perform create_notification(u, 'Torneo annullato', d, idt);
			end if;
		end loop;
		delete from torneo where id=idt;
		return 1;
	END;
$$ language plpgsql;

------ INSERIMENTO/MODIFICA -----
-- creazione torneo
create or replace function new_torneo(n CHAR(20), e INT, t INT, inizio DATE, chiusura DATE, part INT, adm INT, q DECIMAL(5,2))
RETURNS INT as $$
	BEGIN
		insert into torneo (nome,ed,tipo,data_inizio,chiusura_iscr,partecipanti,admin,quota_iscr)
			values (n,e,t,inizio,chiusura,part,adm,q);
		return 1;
	END;
$$ language plpgsql;

-- creazione torneo misto
create or replace function new_torneo_misto(n CHAR(20), e INT, t INT, inizio DATE, chiusura DATE, part INT, adm INT, q DECIMAL(5,2), g INT)
RETURNS INT as $$
	BEGIN
		insert into torneo (nome,ed,tipo,data_inizio,chiusura_iscr,partecipanti,admin,quota_iscr, num_gironi)
			values (n,e,t,inizio,chiusura,part,adm,q,g);
		return 1;
	END;
$$ language plpgsql;

-- creazione riedizione
create or replace function new_torneo_ried(idt INT, inizio DATE, chiusura DATE, q DECIMAL(5,2))
RETURNS INT as $$
	DECLARE ret INT;
	DECLARE t INT;
	DECLARE n CHAR(20);
	DECLARE e INT;
	DECLARE newe INT;
	DECLARE p INT;
	DECLARE adm INT;
	DECLARE ngir INT;
	BEGIN
		select tipo into t from torneo where id=idt;
		select max(ed), nome, partecipanti, admin, num_gironi
			into e, n, p, adm, ngir
			from torneo
			where tipo=t
			group by nome,partecipanti,admin,num_gironi;
		-- new edition
		newe = e + 1;
		if ngir is null then
			select into ret new_torneo(n,newe,t,inizio,chiusura,p,adm,q);
			update torneo set 
				IDriedizione=idt
				where id=currval('torneo_id_seq');
		else
			select into ret new_torneo_misto(n,newe,t,inizio,chiusura,p,adm,q,ngir);
			update torneo set 
				IDriedizione=idt
				where id=currval('torneo_id_seq');
		end if;
		return ret;
	END;
$$ language plpgsql;

-- modifica torneo
create or replace function edit_torneo(idt INT, t INT, inizio DATE, chiusura DATE, part INT, adm INT, q DECIMAL(5,2))
RETURNS INT as $$
	BEGIN
		update torneo set
				tipo=t,
				data_inizio=inizio,
				chiusura_iscr=chiusura,
				partecipanti=part,
				admin=adm,
				quota_iscr=q
			where id=idt;
		return 1;
	END;
$$ language plpgsql;

-- modifica torneo misto
create or replace function edit_torneo_misto(idt INT, t INT, inizio DATE, chiusura DATE, part INT, adm INT, q DECIMAL(5,2), g INT)
RETURNS INT as $$
	BEGIN
		update torneo set
				tipo=t,
				data_inizio=inizio,
				chiusura_iscr=chiusura,
				partecipanti=part,
				admin=adm,
				quota_iscr=q,
				num_gironi=g
			where id=idt;
		return 1;
	END;
$$ language plpgsql;


---- CHECK ----
-- il torneo è concluso? (= tutte le gare hanno un vincitore?)
create or replace function is_ended(idt INT)
RETURNS INT as $$
	DECLARE num INT;
	DECLARE tot INT;
	DECLARE ret INT;
	BEGIN
		select count(*) into num
			from gara
			where IDtorneo=idt
				and vincitore is not null;
		select count(*) into tot
			from gara
			where IDtorneo=idt;
		if tot=0 then
			ret = 0;
		else
			if num<tot then
				ret = 0;
			else
				ret = 1;
			end if;
		end if;
		return ret;
	END;
$$ language plpgsql;

-- ritorna 1 se chiusura_iscr <= oggi
create or replace function reached_iscr_date (idt INT)
RETURNS INT as $$
	DECLARE iscr DATE;
	BEGIN
		select chiusura_iscr into iscr FROM torneo WHERE id=idt;
		if now()<iscr then
			return 0;
		else 
			return 1;
		end if;
	END;
$$ language plpgsql;

-- ritorna 1 se data_inizio <= oggi
create or replace function reached_start_date (idt INT)
RETURNS INT as $$
	DECLARE start DATE;
	BEGIN
		select data_inizio into start FROM torneo WHERE id=idt;
		if now()<start then
			return 0;
		else 
			return 1;
		end if;
	END;
$$ language plpgsql;

-- ritorna 1 se iscritti>=max
create or replace function reached_max_iscr (idt INT)
RETURNS INT as $$
	DECLARE max INT;
	DECLARE curr INT;
	BEGIN
		select t.partecipanti,
				(select count(*) from iscritto_a
					where IDtorneo=idt
						and IDutente>2)
				into max, curr
			FROM torneo t
				left join iscritto_a ia on t.id=ia.IDtorneo				
			WHERE id=idt;
		if curr<max then
			return 0;
		else 
			return 1;
		end if;
	END;
$$ language plpgsql;

-- controlla se la fase è finita
create or replace function is_fase_ended(idt INT, f INT)
RETURNS INT as $$
	DECLARE num_gare_mancanti INT;
	BEGIN
		select count(*) into num_gare_mancanti
			from gara
			where IDtorneo=idt 
				and fase=f
				and vincitore is null;
		if num_gare_mancanti>0 then
			return 0;
		else
			return 1;
		end if;
	END; 
$$ language plpgsql;

-- controlla se la fase a gironi del torneo misto è conclusa
create or replace function is_fase_ita_ended(idt INT)
RETURNS INT as $$
	DECLARE num_gare_mancanti INT;
	BEGIN
		select count(*) into num_gare_mancanti
			from gara
			where IDtorneo=idt 
				and girone is not null
				and vincitore is null;
		if num_gare_mancanti>0 then
			return 0;
		else
			return 1;
		end if;
	END; 
$$ language plpgsql;

-- ritorna 1 se ha almeno una gara
create or replace function has_gare(idt INT)
RETURNS INT as $$
	DECLARE c INT;
	BEGIN
		select count(*) into c from gara
			where IDtorneo=idt;
		if c>0 then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- il torneo misto ha già le fasi?
create or replace function has_fasi(idt INT)
RETURNS INT as $$
	DECLARE res INT;
	BEGIN
		select count(*) into res
			from gara
			where IDtorneo=idt
				and fase is not null;
		if res>0 then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- registra il risultato di una gara
create or replace function registra_risultato(idg INT, g1 INT, pg1 INT, g2 INT, pg2 INT)
RETURNS INT as $$
	DECLARE ret INT DEFAULT 0;
	DECLARE f INT;
	DECLARE idt INT;
	DECLARE isc INT;
	BEGIN
		ret = 0;
		-- che tipo di torneo è? (mi prendo anche nome e ed torneo)
		select fase, IDtorneo into f,idt
			from gara
			where id=idg;

		if pg1>pg2 then
			update gara set
				vincitore=g1
				where id=idg;
			update partecipa_a set
				risultato=pg1,
				punteggio=3
				where IDgara=idg
					and IDutente=g1;
			update partecipa_a set
				risultato=pg2,
				punteggio=0
				where IDgara=idg
					and IDutente=g2;
			ret = 1;
		elsif pg2>pg1 then
			update gara set
				vincitore=g2
				where id=idg;
			update partecipa_a set
				risultato=pg2,
				punteggio=3
				where IDgara=idg
					and IDutente=g2;
			update partecipa_a set
				risultato=pg1,
				punteggio=0
				where IDgara=idg
					and IDutente=g1;
			ret = 1;
		else
			-- PAREGGIO!
			-- il pareggio è consentito se e solo se
			-- il torneo NON è a eliminazione diretta
			-- => se la gara NON ha fasi
			if f is null then
				-- HACK!!! Giocatore "Pareggio" (id=2)
				select into isc is_iscritto(2, idt);
				-- se è già iscritto ok, altrimenti lo iscrivo
				if isc<1 then
					perform iscrivi_torneo(2, idt);
					perform approve_iscr(2, idt);
				end if;
				update gara set
					vincitore=2
					where id=idg;	
				update partecipa_a set
					risultato=pg1,
					punteggio=1
					where IDgara=idg
						and IDutente=g1;
				update partecipa_a set
					risultato=pg1,
					punteggio=1
					where IDgara=idg
						and IDutente=g2;
				ret = 1;
			end if;
		end if;
		return ret;
	END;
$$ language plpgsql;

-- inserisce il vincitore nella tabella vince
create or replace function ins_vincitore(idt INT)
RETURNS INT as $$
	DECLARE win INT;
	DECLARE idg INT;
	DECLARE tipo INT;
	BEGIN
		select into tipo get_tipo(idt);
		if tipo<>2 then
			for win in
				select pa.IDutente
					from partecipa_a pa
					left join gara g on g.id=pa.IDgara
					where g.IDtorneo=idt
					group by pa.IDutente
					having sum(pa.punteggio) >= 
						all(select sum(pa1.punteggio)
								from partecipa_a pa1
									left join gara g1 on g1.id=pa1.IDgara
								where g1.IDtorneo=idt
								group by pa1.IDutente)
			loop
				insert into vince (IDutente, IDtorneo)
					values (win, idt);
			end loop;
		else
			select vincitore, id into win, idg
				from gara
				where IDtorneo=idt
					and data>=
						all(select data from gara
							where IDtorneo=idt);
			-- aumento il punteggio del vincitore
			-- così regolo la classifica in caso di pareggio
			-- dei punti
			update partecipa_a set punteggio=10
				where IDutente=win
					and IDgara=idg;
			-- inserisco il vincitore nella tabella vince
			insert into vince (IDutente, IDtorneo)
				values (win, idt);
		end if;
		return 1;
	END;
$$ language plpgsql;
