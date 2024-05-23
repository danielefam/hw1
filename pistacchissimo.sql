-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 06:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pistacchissimo`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `acquista` (IN `email_acquisto` VARCHAR(320))   begin
	start transaction;
    
    insert into acquistiPassati (email_utente, id_prodotto, quantita)
    select * 
    from carrello
    where email_utente = email_acquisto;
    
    delete from carrello
    where email_utente = email_acquisto;    
    commit work;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `optag` (IN `tagDaCercare` VARCHAR(32))   begin
	drop temporary table if exists tagID;
    create temporary table tagID(
		id integer primary key AUTO_INCREMENT,
		nome varchar(256),
		descrizione text,
		img_src varchar(512),
		prezzo varchar(16),
		quantita integer,
        prezzo_scontato varchar(16)
    );
    
   
    insert into tagID
    select distinct prodotti.*, prezzo_scontato
	from prodotti left join inSconto on prodotti.id = inSconto.id_prodotto
	where prodotti.nome LIKE CONCAT('%',tagDaCercare,'%') or prodotti.descrizione LIKE CONCAT('%',tagDaCercare,'%');
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `acquistinascosti`
--

CREATE TABLE `acquistinascosti` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acquistinascosti`
--

INSERT INTO `acquistinascosti` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(8),
(9),
(13),
(18);

-- --------------------------------------------------------

--
-- Table structure for table `acquistipassati`
--

CREATE TABLE `acquistipassati` (
  `id` int(11) NOT NULL,
  `email_utente` varchar(320) DEFAULT NULL,
  `id_prodotto` int(11) DEFAULT NULL,
  `quantita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acquistipassati`
--

INSERT INTO `acquistipassati` (`id`, `email_utente`, `id_prodotto`, `quantita`) VALUES
(1, 'daniele@gmail.com', 8, 1),
(2, 'daniele@gmail.com', 9, 2),
(3, 'daniele@gmail.com', 12, 3),
(4, 'daniele@gmail.com', 7, 1),
(5, 'daniele@gmail.com', 8, 2),
(6, 'daniele@gmail.com', 17, 1),
(8, 'daniele@gmail.com', 9, 1),
(9, 'daniele@gmail.com', 9, 1),
(12, 'daniele260318@gmail.com', 10, 1),
(13, 'daniele@gmail.com', 12, 1),
(14, 'cheandrabene@gmail.com', 8, 1),
(15, 'daniele@gmail.com', 7, 1),
(16, 'daniele@gmail.com', 8, 1),
(17, 'daniele@gmail.com', 7, 2),
(18, 'daniele@gmail.com', 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `carrello`
--

CREATE TABLE `carrello` (
  `email_utente` varchar(320) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `quantita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrello`
--

INSERT INTO `carrello` (`email_utente`, `id_prodotto`, `quantita`) VALUES
('cheandrabene@gmail.com', 7, 1),
('daniele@gmail.com', 7, 1),
('daniele@gmail.com', 9, 1),
('cheandrabene@gmail.com', 10, 6);

--
-- Triggers `carrello`
--
DELIMITER $$
CREATE TRIGGER `deleteProdottoSaldo` AFTER DELETE ON `carrello` FOR EACH ROW begin
	if exists (select * from insconto where insconto.id_prodotto = old.id_prodotto) then
		update spesaAttuale
		set spesa = spesa - old.quantita * (
			SELECT CAST(REPLACE(prezzo_scontato, ',', '.') AS DECIMAL(10, 2)) 
            from inSconto 
            where insconto.id_prodotto = old.id_prodotto
		)
        where email_utente = old.email_utente;
    else
		update spesaAttuale
		set spesa = spesa - old.quantita * (
			SELECT CAST(REPLACE(prezzo, ',', '.') AS DECIMAL(10, 2))
            from prodotti 
            where id = old.id_prodotto
		)
        where email_utente = old.email_utente;
    end if;	
    
    if not exists (select * from carrello where email_utente = old.email_utente) then
		update spesaAttuale
        set spesa = 0
        where email_utente = old.email_utente;
	end if;
    
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertProdottoSaldo` AFTER INSERT ON `carrello` FOR EACH ROW begin

	if not exists (select * from spesaAttuale where email_utente = new.email_utente) then
		if exists (select * from insconto where insconto.id_prodotto = new.id_prodotto) then
			
			insert into spesaAttuale values(new.email_utente, new.quantita *(
				SELECT CAST(REPLACE(prezzo_scontato, ',', '.') AS DECIMAL(10, 2))
                from inSconto 
                where insconto.id_prodotto = new.id_prodotto
			));
		
        else
			insert into spesaAttuale values(new.email_utente, new.quantita *(
				SELECT CAST(REPLACE(prezzo, ',', '.') AS DECIMAL(10, 2))
                from prodotti 
                where id = new.id_prodotto
			));
        end if;    
    else
		if exists (select * from insconto where insconto.id_prodotto = new.id_prodotto) then
			update spesaAttuale
			set spesa = spesa + new.quantita * (
				SELECT CAST(REPLACE(prezzo_scontato, ',', '.') AS DECIMAL(10, 2))
                from inSconto 
                where insconto.id_prodotto = new.id_prodotto
			)
			where email_utente = new.email_utente;
        else
			update spesaAttuale
			set spesa = spesa + new.quantita * (
				SELECT CAST(REPLACE(prezzo, ',', '.') AS DECIMAL(10, 2)) 
                from prodotti 
                where id = 
                new.id_prodotto
			)
            where email_utente = new.email_utente;
        end if;     
    end if;
	
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateProdottoSaldo` AFTER UPDATE ON `carrello` FOR EACH ROW begin
	if exists (select * from insconto where insconto.id_prodotto = old.id_prodotto) then
		update spesaAttuale
		set spesa = spesa - old.quantita * (
			SELECT CAST(REPLACE(prezzo_scontato, ',', '.') AS DECIMAL(10, 2)) 
            from inSconto 
            where insconto.id_prodotto = old.id_prodotto
		) + new.quantita * (
			SELECT CAST(REPLACE(prezzo_scontato, ',', '.') AS DECIMAL(10, 2)) 
            from inSconto 
            where insconto.id_prodotto = new.id_prodotto
		) 
        where email_utente = old.email_utente;
    else
		update spesaAttuale
		set spesa = spesa - old.quantita * (
			SELECT CAST(REPLACE(prezzo, ',', '.') AS DECIMAL(10, 2))
            from prodotti 
            where id = old.id_prodotto
		) + new.quantita * (
			SELECT CAST(REPLACE(prezzo, ',', '.') AS DECIMAL(10, 2))
            from prodotti 
            where id = new.id_prodotto
		)
        where email_utente = old.email_utente;
    end if;	
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `insconto`
--

CREATE TABLE `insconto` (
  `id_prodotto` int(11) NOT NULL,
  `prezzo_scontato` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insconto`
--

INSERT INTO `insconto` (`id_prodotto`, `prezzo_scontato`) VALUES
(7, '8,55'),
(8, '35,91'),
(9, '8,10'),
(10, '31,41'),
(11, '11,40'),
(12, '40,41'),
(24, '30,88');

-- --------------------------------------------------------

--
-- Table structure for table `preferiti`
--

CREATE TABLE `preferiti` (
  `email_utente` varchar(320) NOT NULL,
  `id_prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preferiti`
--

INSERT INTO `preferiti` (`email_utente`, `id_prodotto`) VALUES
('daniele@gmail.com', 7),
('daniele260318@gmail.com', 8),
('daniele@gmail.com', 8),
('daniele260318@gmail.com', 9),
('cheandrabene@gmail.com', 11),
('cheandrabene@gmail.com', 12),
('daniele260318@gmail.com', 12),
('cheandrabene@gmail.com', 15),
('daniele@gmail.com', 17),
('cheandrabene@gmail.com', 19);

-- --------------------------------------------------------

--
-- Table structure for table `prodotti`
--

CREATE TABLE `prodotti` (
  `id` int(11) NOT NULL,
  `nome` varchar(256) DEFAULT NULL,
  `descrizione` text DEFAULT NULL,
  `img_src` varchar(512) DEFAULT NULL,
  `prezzo` varchar(16) DEFAULT NULL,
  `quantita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodotti`
--

INSERT INTO `prodotti` (`id`, `nome`, `descrizione`, `img_src`, `prezzo`, `quantita`) VALUES
(7, 'Crema pistacchissimo - crema di pistacchio super intensa!', 'Gusto di pistacchio intenso e autentico, con una piacevole tostatura. Senza latte o cioccolato bianco.', 'https://shop.pistacchissimo.it/75-home_default/crema-pistacchissimo-crema-di-pistacchio-super-intensa.jpg', '9,50', 4),
(8, '1 KG Crema pistacchissimo - crema di pistacchio super intensa!', 'Gusto di pistacchio intenso e autentico, con una piacevole tostatura. Senza latte o cioccolato bianco.', 'https://shop.pistacchissimo.it/219-home_default/crema-pistacchissimo-crema-di-pistacchio-super-intensa.jpg', '39,90', 18),
(9, 'Pesto pistacchissimo - pesto di pistacchio tradizionale', 'Gusto di pistacchio intenso e autentico, dal sapore spiccatamente naturale.', 'https://shop.pistacchissimo.it/73-home_default/pesto-pistacchissimo-pesto-di-pistacchio-dal-gusto-intenso.jpg', '9,00', 23),
(10, '1 KG pesto pistacchissimo - pesto di pistacchio tradizionale', 'Gusto di pistacchio intenso e autentico, dal sapore spiccatamente naturale.', 'https://shop.pistacchissimo.it/210-home_default/pesto-pistacchissimo-pesto-di-pistacchio-dal-gusto-intenso.jpg', '34,90', 34),
(11, 'Pasta pura di pistacchio - 100% pistacchio', 'Gusto di pistacchio intenso e autentico, con una leggerissima tostatura.', 'https://shop.pistacchissimo.it/71-home_default/pasta-pura-di-pistacchio-100-pistacchio.jpg', '12,00', 0),
(12, '1 KG Pasta pura di pistacchio - 100% pistacchio', 'Gusto di pistacchio intenso e autentico, con una tostatura media.', 'https://shop.pistacchissimo.it/216-home_default/pasta-pura-di-pistacchio-100-pistacchio.jpg', '44,90', 7),
(13, '1 KG Pistacchio intero sgusciato', 'Pistacchi interi sgusciati sottovuoto.\n\nAl naturale, senza sale e tostatura.\n\nGustosi accuratamente selezionati, caratterizzati dalla loro semi-croccantezza naturale.', 'https://shop.pistacchissimo.it/327-home_default/1-kg-pistacchio-intero-sgusciato.jpg', '39,99', 6),
(14, '1 KG pistacchio in granella', 'Granella di pistacchio sottovuoto.\n\nPistacchi tritati, sotto forma di granella di pistacchio.\n\nAl naturale, senza sale e tostatura.', 'https://shop.pistacchissimo.it/329-home_default/1-kg-pistacchio-intero-sgusciato.jpg', '39,99', 24),
(15, '1 KG pistacchio in farina', 'Farina di pistacchio sottovuoto.\n\nPistacchi finemente macinati, sotto forma di farina di pistacchio.\n\nAl naturale, senza sale e tostatura.', 'https://shop.pistacchissimo.it/331-home_default/1-kg-pistacchio-intero-sgusciato.jpg', '39,99', 11),
(16, 'Gift card - buono regalo pistacchissimo', 'Un buono regalo dedicato ai veri amanti del pistacchio, spendibile su tutti prodotti dello Shop Pistacchissimo.', 'https://shop.pistacchissimo.it/312-home_default/gift-card-buono-regalo-pistacchissimo.jpg', '10,00', 23),
(17, 'Paste di pistacchio - pistacchio purissimo, senza mandorla!', 'Gusto di pistacchio intenso ed autentico, con lavorazione 100% artigianale.\n\nSolo pistacchio purissimo, senza alcuna traccia di mandorle!', 'https://shop.pistacchissimo.it/176-home_default/paste-di-pistacchio-solo-pistacchio-purissimo-senza-mandorla.jpg', '24,99', 29),
(18, 'Granita pistacchissimo - Kit granita al pistacchio 1.4 KG + extra', 'Facilissima da preparare, con un gusto di pistacchio davvero intenso.\n\nEcco il kit definitivo per realizzare in casa una vera granita siciliana al pistacchio, in pochi minuti, anche senza gelatiera.\n\nInoltre avrai 70 g di pasta pura extra per preparare altri dolci!', 'https://shop.pistacchissimo.it/190-home_default/granita-pistacchissimo-kit-granita-al-pistacchio-14-kg-extra.jpg', '19,90', 29),
(19, 'Libro pistacchissimo - tutti i segreti del pistacchio', 'Tutto quello che vorresti sapere sul pistacchio è racchiuso in questo libro, firmato Pistacchissimo!', 'https://shop.pistacchissimo.it/260-home_default/libro-pistacchissimo.jpg', '15,00', 47),
(24, 'Box dolcezza - cioccolattini e crema pistacchissimo', 'Tutto ciò che ti serve per una serata all\'insegna della dolcezza, ovviamente a base di pistacchio!', 'https://shop.pistacchissimo.it/114-home_default/san-valentino-pistacchissimo-kit-dolcezza-cioccolatini-crema.jpg', '32,50', 11);

-- --------------------------------------------------------

--
-- Table structure for table `spesaattuale`
--

CREATE TABLE `spesaattuale` (
  `email_utente` varchar(320) NOT NULL,
  `spesa` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spesaattuale`
--

INSERT INTO `spesaattuale` (`email_utente`, `spesa`) VALUES
('cheandrabene@gmail.com', 197.01),
('daniele260318@gmail.com', 0),
('daniele@gmail.com', 16.65);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(320) NOT NULL,
  `password_utente` varchar(256) DEFAULT NULL,
  `nome` varchar(256) DEFAULT NULL,
  `cognome` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password_utente`, `nome`, `cognome`) VALUES
('admin@gmail.com', '$2y$10$8A3jfgoIMp7o4GDGgnw1uu51U5IoG2vr1TQORAoutmmsJU4gsSdK.', 'admin', 'capo'),
('cheandrabene@gmail.com', '$2y$10$UY7RaWTDt4DW0EsIi0fSee/K5iDrChcyzU.yswcT9EqwgCL4BXgyy', 'Prova', 'Ancora'),
('daniele260318@gmail.com', '$2y$10$CgqN02kRA3P5cZMsn.c8CuW7Lsi0wHGqUE4LvyypIpGInR8m7gKWG', 'Daniele', 'Fama'),
('daniele@gmail.com', '$2y$10$TQfEsG5OjzPgBdYq9KMYYe2acbni/Ev.Iu.LnIqAmnD8D4eCDghuO', 'Daniele', 'Fama');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acquistinascosti`
--
ALTER TABLE `acquistinascosti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx` (`id`);

--
-- Indexes for table `acquistipassati`
--
ALTER TABLE `acquistipassati`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idemail` (`email_utente`),
  ADD KEY `idprod` (`id_prodotto`);

--
-- Indexes for table `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`id_prodotto`,`email_utente`),
  ADD KEY `idemail` (`email_utente`),
  ADD KEY `idprod` (`id_prodotto`);

--
-- Indexes for table `insconto`
--
ALTER TABLE `insconto`
  ADD PRIMARY KEY (`id_prodotto`),
  ADD KEY `idprod` (`id_prodotto`);

--
-- Indexes for table `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`id_prodotto`,`email_utente`),
  ADD KEY `idx_email` (`email_utente`),
  ADD KEY `idx_id_prodotto` (`id_prodotto`);

--
-- Indexes for table `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spesaattuale`
--
ALTER TABLE `spesaattuale`
  ADD PRIMARY KEY (`email_utente`),
  ADD KEY `idemail` (`email_utente`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acquistipassati`
--
ALTER TABLE `acquistipassati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `prodotti`
--
ALTER TABLE `prodotti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acquistinascosti`
--
ALTER TABLE `acquistinascosti`
  ADD CONSTRAINT `acquistinascosti_ibfk_1` FOREIGN KEY (`id`) REFERENCES `acquistipassati` (`id`);

--
-- Constraints for table `acquistipassati`
--
ALTER TABLE `acquistipassati`
  ADD CONSTRAINT `acquistipassati_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id`),
  ADD CONSTRAINT `acquistipassati_ibfk_2` FOREIGN KEY (`email_utente`) REFERENCES `user` (`email`);

--
-- Constraints for table `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `carrello_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id`),
  ADD CONSTRAINT `carrello_ibfk_2` FOREIGN KEY (`email_utente`) REFERENCES `user` (`email`);

--
-- Constraints for table `insconto`
--
ALTER TABLE `insconto`
  ADD CONSTRAINT `insconto_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id`);

--
-- Constraints for table `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `fk_prodotto` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotti` (`id`),
  ADD CONSTRAINT `fk_utente` FOREIGN KEY (`email_utente`) REFERENCES `user` (`email`);

--
-- Constraints for table `spesaattuale`
--
ALTER TABLE `spesaattuale`
  ADD CONSTRAINT `spesaattuale_ibfk_1` FOREIGN KEY (`email_utente`) REFERENCES `user` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
