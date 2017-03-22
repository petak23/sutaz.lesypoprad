DELETE FROM `udaje`
WHERE ((`id` = '20'));

INSERT INTO `udaje` (`id`, `id_registracia`, `id_druh`, `id_udaje_typ`, `nazov`, `text`, `comment`) VALUES
(26,	4,	8,	1,	'max_pocet_foto',	'5',	'Maximálny počet fotiek pre jedného užívateľa. Ak je 0 tak neobmedzený.');
