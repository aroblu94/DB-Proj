drop table if exists gara cascade;
drop table if exists gruppi cascade;
drop table if exists iscritto_a cascade;
drop table if exists partecipa_a cascade;
drop table if exists tipo_torneo cascade;
drop table if exists torneo cascade;
drop table if exists utenti cascade;
drop table if exists vince cascade;
drop table if exists girone cascade;
drop table if exists notifiche cascade;

drop sequence if exists user_id_seq;
drop sequence if exists gruppi_id_seq;
drop sequence if exists tipo_id_seq;
drop sequence if exists torneo_id_seq;
drop sequence if exists gara_id_seq;
drop sequence if exists girone_id_seq;
drop sequence if exists partg_id_seq;
drop sequence if exists notifiche_id_seq;

-- funzioni utente 
drop function if exists login_corretto(idu INT, p char(32));
drop function if exists iscrivi (u CHAR(20), p CHAR(32), n CHAR(20), c CHAR(20));
drop function if exists esiste (u CHAR(20));
drop function if exists get_nome (idu INT);
drop function if exists get_img (idu INT);
drop function if exists get_org(idu INT);
drop function if exists mod_pass(idu INT, o CHAR(32), n CHAR(32));
drop function if exists set_nome(idu INT, n CHAR(20), c CHAR(20));
drop function if exists set_avatar (idu INT, a CHAR(24));

drop function if exists make_organizzatore(idu INT);

drop function if exists iscrivi_torneo(idu INT, idt int);
drop function if exists revoca_iscr(idu INT, idt INT);
drop function if exists approve_iscr(idu INT, idt INT);
drop function if exists is_iscr_approved(idu INT, idt INT);

drop function if exists ban_local(idu INT, idt INT);
drop function if exists is_ban_local(idu INT, idt INT);
drop function if exists ban(idu INT);
drop function if exists unban(idu INT);
drop function if exists is_ban(idu INT);
drop function if exists is_iscritto(idu INT, idt INT);
drop function if exists agg_quota(idu INT, idt INT, q INT);

-- funzioni torneo
drop function if exists new_torneo(n CHAR(20), e INT, t INT, inizio DATE, chiusura DATE, part INT, idu INT, q DECIMAL(5,2));
drop function if exists edit_torneo(idt INT, t INT, inizio DATE, chiusura DATE, part INT, idu INT, q DECIMAL(5,2));
drop function if exists new_torneo_misto(n CHAR(20), e INT, t INT, inizio DATE, chiusura DATE, part INT, idu INT, q DECIMAL(5,2), g INT);
drop function if exists edit_torneo_misto(idt INT, t INT, inizio DATE, chiusura DATE, part INT, idu INT, q DECIMAL(5,2), g INT);
drop function if exists new_torneo_ried(idt INT, inizio DATE, chiusura DATE, q DECIMAL(5,2));
drop function if exists elimina_torneo(idt INT);

drop function if exists get_nomet(idt INT);
drop function if exists get_edizione(idt INT);
drop function if exists get_tipo(idt INT);
drop function if exists get_fine(idt INT);
drop function if exists get_inizio(idt INT);
drop function if exists set_fine(idt INT);
drop function if exists registra_risultato(idg INT, g1 INT, pg1 INT, g2 INT, pg2 INT);
drop function if exists is_ended(idt INT);
drop function if exists ins_vincitore(idt INT);
drop function if exists is_fase_ended(idt INT, f INT);
drop function if exists is_fase_ita_ended(idt INT);
drop function if exists has_gare(idt INT);
drop function if exists has_fine(idt INT);
drop function if exists ins_gara(idt INT, p1 INT, p2 INT, d DATE);

drop function if exists reached_iscr_date(idt INT);
drop function if exists reached_start_date(idt INT);
drop function if exists reached_max_iscr (idt INT);
drop function if exists has_fasi(idt INT);

-- funzioni generazione torneo/gare/gironi/fasi
drop function if exists genera_torneo(idt INT);
drop function if exists genera_torneo_italiana (idt INT, num_part INT);
drop function if exists genera_torneo_eliminazione (idt INT, num_part INT);
drop function if exists genera_fase (idt INT, fase INT, num_part INT);
drop function if exists genera_fase_succ(idt INT);
drop function if exists genera_torneo_misto(idt INT, p INT);
drop function if exists crea_gironi(idt INT);
drop function if exists crea_gare_girone(idg INT, num_part INT, ngir INT);
drop function if exists genera_ita_misto(idt INT);
drop function if exists genera_fase_misto(idt INT, f INT, num_part INT);
drop function if exists genera_fase_misto_succ(idt INT);

-- notifiche 
drop function if exists create_notification(idu INT, tit CHAR(20), d CHAR(255), idt INT);
drop function if exists read_all_notifications(idu INT);
drop function if exists leggi_notifica (i INT);