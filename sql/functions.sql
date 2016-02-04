-- NOTIFICHE
-- crea una notifica per un determinato utente
create or replace function create_notification(idu INT, tit CHAR(20), d CHAR(255), idt INT)
RETURNS VOID as $$
	BEGIN
		insert into notifiche (IDutente, titolo, descr, IDtorneo)
			values (idu, tit, d, idt);
	END;
$$ language plpgsql;

-- legge notifica
create or replace function leggi_notifica (i INT)
RETURNS INT as $$
	BEGIN
		update notifiche set letto=1
			where id=i;
		return 1;
	END;
$$ language plpgsql;

-- leggi tutte le notifiche di un utente
create or replace function read_all_notifications(idu INT)
RETURNS INT as $$
	BEGIN
		update notifiche set letto=1
			where IDutente=idu
				and letto=0;
		return 1;
	END
$$ language plpgsql;