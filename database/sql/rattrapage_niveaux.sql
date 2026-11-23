-- A executer juste apres le deploiement du nouveau code (sans risque, peut etre relance).
-- Classe les comptes et documents crees entre-temps par l'ancien code ; ne touche pas ceux deja classes.
UPDATE `users` u JOIN `filieres` f ON f.`nom` = u.`entity` SET u.`niveau_id` = f.`niveau_id` WHERE u.`niveau_id` IS NULL;
UPDATE `tves` t JOIN `filieres` f ON f.`nom` = t.`groupe_pedagogique` SET t.`niveau_id` = f.`niveau_id`, t.`filiere_id` = f.`id` WHERE t.`niveau_id` IS NULL;

-- Cles d'unicite des comptes crees par l'ancien code (apres la migration add_cles_uniques_users).
UPDATE `users` u JOIN (SELECT MIN(`id`) id FROM `users` GROUP BY LOWER(TRIM(`email`))) p ON p.id = u.`id` SET u.`email_cle` = LOWER(TRIM(u.`email`)) WHERE u.`email_cle` IS NULL;
UPDATE `users` u JOIN (SELECT MIN(`id`) id FROM `users` WHERE TRIM(`matricule`) <> '' GROUP BY UPPER(REPLACE(TRIM(`matricule`),' ',''))) p ON p.id = u.`id` SET u.`matricule_cle` = UPPER(REPLACE(TRIM(u.`matricule`),' ','')) WHERE u.`matricule_cle` IS NULL;
