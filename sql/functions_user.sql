-- controlla password per utente
create or replace function login_corretto (idu INT, p CHAR(32))
RETURNS INT as $$
	DECLARE pass p%TYPE;
	BEGIN
		select password into pass FROM utenti WHERE id = idu;
		if p=pass then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- controlla se l'utente esiste già
create or replace function esiste (u CHAR(20))
RETURNS INT as $$
	DECLARE t INT;
	BEGIN
		select id into t from utenti where username=u;
		if t>0 then
			return t;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- restituisce il nome completo dell'utente
create or replace function get_nome (idu INT)
RETURNS CHAR(41) as $$
	DECLARE thename CHAR(41);
	BEGIN
		select concat(nome,' ',cognome) into thename
			from utenti
			where id=idu;
		return thename;
	END;
$$ language plpgsql;

-- restituisce il nome dell'avatar utente
create or replace function get_img (idu INT)
RETURNS CHAR(30) as $$
	DECLARE theavatar CHAR(30);
	BEGIN
		select avatar into theavatar
			from utenti
			where id=idu;
		return theavatar;
	END;
$$ language plpgsql;

-- restituisce il tipo dell'utente
create or replace function get_org (idu INT)
RETURNS INT as $$
	DECLARE theorg INT;
	BEGIN
		select idorg into theorg
			from utenti
			where id=idu;
		return theorg;
	END;
$$ language plpgsql;

-- modifica la password
create or replace function mod_pass(idu INT, o CHAR(32), n CHAR(32))
RETURNS INT as $$
	DECLARE ok INT;
	BEGIN
		select into ok login_corretto(idu,o);
		if ok=1 then
			update utenti set password=n
				where id=idu;
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- modifica il nome dell'utente
create or replace function set_nome(idu INT, n CHAR(20), c CHAR(20))
RETURNS INT as $$
	BEGIN
		update utenti set
			nome=n,
			cognome=c
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- modifica l'avatar dell'utente
create or replace function set_avatar (idu INT, a CHAR(24))
RETURNS INT as $$
	BEGIN
		update utenti set
			avatar=a
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- rende un utente organizzatore
create or replace function make_organizzatore(idu INT)
RETURNS INT as $$
	BEGIN
		update utenti set
			idorg=2
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- declassa ad utente un organizzatore
create or replace function decl_organizzatore(idu INT)
RETURNS INT as $$
	BEGIN
		update utenti set
			idorg=3
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- iscrive un utente al portale
create or replace function iscrivi (u CHAR(20), p CHAR(32), n CHAR(20), c CHAR(20))
RETURNS INT as $$
	BEGIN
		insert into utenti (username, password, nome, cognome, IDorg)
			values (u, p, n, c, 3);
		return 1;
	END;
$$ language plpgsql;



-- iscrive utente a un torneo
create or replace function iscrivi_torneo (idu INT, idt INT)
RETURNS INT as $$
	DECLARE iscritto INT;
	DECLARE org INT;
	DECLARE descr CHAR(255);
	BEGIN
		select concat(1) into iscritto from iscritto_a
			where IDutente = idu
				and IDtorneo = idt;
		if iscritto > 0 then
			return 0;
		else
			insert into iscritto_a (IDutente, IDtorneo) values (idu, idt);
			if idu>2 then
				select concat('Iscrizione di ',ut.nome,' ',ut.cognome,' per il torneo ',
								tr.nome,' ed.',tr.ed,' in attesa di approvazione.') into descr
					from utenti ut, torneo tr
					where ut.id=idu
						and tr.id=idt;
				select admin into org from torneo where id=idt;
				perform create_notification(org, 'Nuova iscrizione', descr, idt);
			end if;
			return 1;
		end if;
	END;
$$ language plpgsql;

-- revoca l'iscrizione a un torneo
create or replace function revoca_iscr(idu INT, idt INT)
RETURNS INT as $$
	BEGIN
		delete from iscritto_a 
			where IDutente=idu
				and IDtorneo=idt;
		return 1;
	END;
$$ language plpgsql;

-- approva l'iscrizione
create or replace function approve_iscr(idu INT, idt INT)
RETURNS INT as $$
	DECLARE res INT DEFAULT 0;
	DECLARE descr CHAR(255);
	BEGIN
		update iscritto_a set approvato=1
			where IDtorneo=idt
				and IDutente = idu;
		if idu>2 then
			select concat('Iscrizione per il torneo ',tr.nome,' ed.',tr.ed,' approvata.') into descr
				from utenti ut, torneo tr
				where ut.id=idu
					and tr.id=idt;
			perform create_notification(idu, 'Iscrizione approvata', descr, idt);
		end if;
		return 1;
	END;
$$ language plpgsql;

-- ritorna 1 se l'iscrizione è stata approvata
create or replace function is_iscr_approved(idu INT, idt INT)
RETURNS INT as $$
	DECLARE this INT;
	BEGIN
		select approvato into this from iscritto_a
			where IDtorneo=idt
			and IDutente = idu;
		if this>0 then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;



-- banna un utente ad un torneo
create or replace function ban_local(idu INT, idt INT)
RETURNS INT as $$
	DECLARE idg INT;
	DECLARE g2 CHAR(20);
	DECLARE inizio DATE;
	DECLARE fine DATE;
	BEGIN
		update iscritto_a set ban=1
			where IDutente=idu
				and IDtorneo=itd;
		-- deve perdere a tutte le gare future del torneo
		-- se il torneo è in corso
		select data_inizio, data_fine into inizio, fine
			from torneo
			where id=idt;
		if inizio<now() and (fine>now() or fine is null) then
			for idg in select g.id 
						from gara g
							left join partecipa_a pa on pa.IDgara=g.id
						where pa.IDutente=idu
							and g.data>=now()
			loop
				if idg is not null then
					select IDutente into g2
						from partecipa_a
						where IDgara=idg;
					-- aggiorno il vincitore della gara
					update gara set vincitore=g2
						where id=idg;
					-- aggiorno i risultati
					update partecipa_a set punteggio=3, risultato=1
						where IDgara=idg and IDutente=g2;
					update partecipa_a set punteggio=0, risultato=0
						where IDgara=idg and IDutente=u;
				end if;
			end loop;
		end if;
		return 1;
	END;
$$ language plpgsql;

-- ritorna 1 se il giocatore è bannato dal torneo
create or replace function is_ban_local(idu INT, idt INT)
RETURNS INT as $$
	DECLARE this INT;
	BEGIN
		select ban into this from iscritto_a
			where IDtorneo=idt
			and IDutente = idu;
		if this>0 then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- banna globalmente un utente
create or replace function ban(idu INT)
RETURNS INT as $$
	BEGIN
		update utenti set ban=1
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- sbanna globalmente un utente
create or replace function unban(idu INT)
RETURNS INT as $$
	BEGIN
		update utenti set ban=0
			where id=idu;
		return 1;
	END;
$$ language plpgsql;

-- ritorna 1 se il giocatore è bannato GLOBALMENTE
create or replace function is_ban(idu INT)
RETURNS INT as $$
	DECLARE this INT;
	BEGIN
		select ban into this from utenti
			where id = idu;
		if this>0 then
			return 1;
		else
			return 0;
		end if;
	END;
$$ language plpgsql;

-- ritorna 1 se il giocatore è iscritto
create or replace function is_iscritto(idu INT, idt INT)
RETURNS INT as $$
	DECLARE iscritto INT;
	BEGIN
		select concat(1) into iscritto from iscritto_a
			where IDutente = idu
				and IDtorneo=idt;
		if iscritto > 0 then
			return 1;
		else
			return 0;
		end if;       
	END;
$$ language plpgsql;

-- gestione della quota di iscrizione per singolo utente/torneo
create or replace function agg_quota(idu INT, idt INT, q INT)
RETURNS INT as $$
	BEGIN
		update iscritto_a set sconto_rincaro=q
			where IDutente=idu
				and IDtorneo=idt;
		return 1;
	END;
$$ language plpgsql;