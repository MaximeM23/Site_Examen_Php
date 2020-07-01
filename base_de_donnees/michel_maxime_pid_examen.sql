-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 03 mai 2019 à 21:28
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `michel_maxime_pid_examen`
--

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `role`) VALUES
(1, 'Utilisateur'),
(2, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(250) NOT NULL,
  `prenom` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `mot_de_passe` varchar(250) NOT NULL,
  `fk_id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `fk_id_role`) VALUES
(1, 'Michel', 'Maxime', 'Admin@a.a', 'ef0deaf2d59afdd29e0c80b2461b4a9256523af3a20d8c98beb81a34c4ca52d6cee85f00dcad933bf8fc9241eb46577bc22ec18ffb645a8ec1e3fc3a9da4d320', 2),
(2, 'Michel', 'Maxime', 'User@u.u', '32296406447a48a5b3a7b23c4f7cdbb1269deab5241358e3cd3d28976fc61bcc67ba14d9c1d6eff16e10a7fedc83b7f5b954f6f7826bfc99bc8a83d10df6fec5', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_vehicule`
--

CREATE TABLE `utilisateur_vehicule` (
  `id_utilisateur_vehicule` int(11) NOT NULL,
  `fk_id_vehicule` int(11) NOT NULL,
  `fk_id_utilisateur` int(11) NOT NULL,
  `date_emprunt` datetime NOT NULL,
  `date_retour` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `id_vehicule` int(11) NOT NULL,
  `modele` varchar(250) NOT NULL,
  `prix_journee` int(11) NOT NULL,
  `prix_demi_journee` int(11) NOT NULL,
  `nom_image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id_vehicule`, `modele`, `prix_journee`, `prix_demi_journee`, `nom_image`) VALUES
(1, 'BMW i8', 300, 200, 'Images/bmw_i8.png'),
(2, 'BMW x6', 250, 200, 'Images/bmw_x6.png'),
(3, 'BMW z4', 250, 200, 'Images/bmw_z4.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `unicite_email` (`email`),
  ADD KEY `fk_id_role` (`fk_id_role`);

--
-- Index pour la table `utilisateur_vehicule`
--
ALTER TABLE `utilisateur_vehicule`
  ADD PRIMARY KEY (`id_utilisateur_vehicule`),
  ADD KEY `fk_id_vehicule` (`fk_id_vehicule`),
  ADD KEY `fk_id_utilisateur` (`fk_id_utilisateur`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id_vehicule`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur_vehicule`
--
ALTER TABLE `utilisateur_vehicule`
  MODIFY `id_utilisateur_vehicule` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `id_vehicule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_id_role_id_role` FOREIGN KEY (`fk_id_role`) REFERENCES `role` (`id_role`);

--
-- Contraintes pour la table `utilisateur_vehicule`
--
ALTER TABLE `utilisateur_vehicule`
  ADD CONSTRAINT `fk_id_utilisateur_id_utilisateur` FOREIGN KEY (`fk_id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `fk_id_vehicule_id_vehicule` FOREIGN KEY (`fk_id_vehicule`) REFERENCES `vehicule` (`id_vehicule`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
