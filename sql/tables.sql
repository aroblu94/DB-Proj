-- sudo apt-get install postgresql php5-pgsql php5-cli
-- sudo -i -u postgres
-- createuser aronne
-- createdb aronne
-- per far partire il server (niente Apache):
-- 	apri il terminale nella cartella del progetto,
--	php -S 127.0.0.1:8000
--	da web ci accedi andando su 127.0.0.1:8000

create table gruppi (
	id int primary key not null default nextval('gruppi_id_seq'),
	nome varchar(20)
);

create table utenti (
	id int primary key not null default nextval('user_id_seq'),
	username varchar(20) not null,
	password varchar(32) not null,
	nome varchar(20) not null,
	cognome varchar(20) not null,
	IDorg int not null default 3 references gruppi(id),
	avatar char(20) null,
	ban int not null default 0,

	unique(username)
);

create table tipo_torneo (
	id int primary key not null default nextval('tipo_id_seq'),
	nome varchar(20) not null
);

create table torneo (
	id int primary key not null default nextval('torneo_id_seq'),
	nome varchar(20) not null,
	ed int not null,
	tipo int not null references tipo_torneo(id),
	data_inizio date not null,
	data_fine date null default null,
	chiusura_iscr date not null check (data_inizio > chiusura_iscr),
	quota_iscr decimal(5,2) not null default 0,
	partecipanti int not null check (partecipanti > 1) default 2,
	admin int not null references utenti(id),
	num_gironi int null,
	IDriedizione int null references torneo(id) ON DELETE CASCADE,

	unique (nome, ed)
);

create table girone (
	id int primary key not null default nextval('girone_id_seq'),
	numero int not null,
	data_inizio date not null,
	data_fine date null,
	num_giocatori int null,
	num_gare int null,
	IDtorneo int not null references torneo(id) ON DELETE CASCADE
);

create table gara (
	id int primary key not null default nextval('gara_id_seq'),
	data date not null,
	fase int null,
	girone int null,
	IDtorneo int not null references torneo(id) ON DELETE CASCADE,
	vincitore int null references utenti(id)
);

create table iscritto_a (
	IDutente int not null references utenti(id),
	IDtorneo int not null references torneo(id) ON DELETE CASCADE,
	approvato int not null default 0,
	sconto_rincaro int not null default 0,
	ban int not null default 0,

	primary key (IDutente, IDtorneo)
);

-- punteggio=punteggio ottenuto a fine gara dal partecipante -> alimentereà la classifica
-- risultato=risultato fatto dal giocatore (es numero di goal, punti della partita di scopa ecc)
create table partecipa_a (
	IDutente int not null references utenti(id),
	IDgara int not null references gara(id) ON DELETE CASCADE,
	punteggio int null,
	risultato int null,

	primary key (IDutente, IDgara)
);

create table vince (
	IDutente int not null references utenti(id),
	IDtorneo int not null references torneo(id) ON DELETE CASCADE,

	primary key (IDutente, IDtorneo)
);

-- NOTIFICHE?
create table notifiche (
	id int primary key not null default nextval('notifiche_id_seq'),
	titolo varchar(20) not null,
	descr varchar(255) null default null,
	data date not null default now(),
	IDutente int not null references utenti(id),
	IDtorneo int null references torneo(id) ON DELETE SET NULL,
	letto int not null default 0
);