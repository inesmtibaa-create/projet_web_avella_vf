insert into users (nom,prenom,email,password,role,telephone)
values
('sghaier','chahd','chahd@gmail.com','2024','admin','40503551'),
('ben sassi','ahmed','ahmed@gmail.com','0000','client','2325566');

INSERT INTO categories (nom, photo) VALUES
('Vêtements', '../images/hanger.png');

INSERT INTO boutiques (user_id, nom, description, statut) VALUES
(1, 'Sha_boutique', 'Boutique spécialisée en pretes à porter', 'actif');

INSERT INTO produits (categorie_id, nom, description, prix, image) VALUES
(1, 'Robe Femme', 'Robe élégante pour soirée', 120.00, '../boutiques/robe.png');

