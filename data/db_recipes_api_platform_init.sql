INSERT INTO user (email, password, roles, is_enabled) VALUES ('admin@email.com', '$2y$13$xSicPNrTx8B6El75k8yeb.GEzyXuJhVkgJOXzNiTIDEFqbJpIp5Zy', '["ROLE_ADMIN"]', true);
INSERT INTO user (email, password, roles, is_enabled) VALUES ('user@email.com', '$2y$13$6xjb41Jo7Fkyh7mfngFjFenRI.oBROHt48IHl6JDifqyHdF7xHaHu', '["ROLE_USER"]', true);

INSERT INTO ingredient (name) VALUES ('Potato');
INSERT INTO ingredient (name) VALUES ('Vinegar');
INSERT INTO ingredient (name) VALUES ('Cheese');
INSERT INTO ingredient (name) VALUES ('Ketchup');
INSERT INTO ingredient (name) VALUES ('Lettuce');
INSERT INTO ingredient (name) VALUES ('Onions');
INSERT INTO ingredient (name) VALUES ('Tomato');
INSERT INTO ingredient (name) VALUES ('Dough');
INSERT INTO ingredient (name) VALUES ('Pepperoni');
INSERT INTO ingredient (name) VALUES ('Oregano');
INSERT INTO ingredient (name) VALUES ('Pineapple');

INSERT INTO category (name) VALUES ('Breakfast');
INSERT INTO category (name) VALUES ('Lunch');
INSERT INTO category (name) VALUES ('Dinner');

INSERT INTO tag (name) VALUES ('Hot');
INSERT INTO tag (name) VALUES ('Sour');
INSERT INTO tag (name) VALUES ('Sweet');
INSERT INTO tag (name) VALUES ('Fruity');
INSERT INTO tag (name) VALUES ('Tangy');
INSERT INTO tag (name) VALUES ('Greasy');

INSERT INTO recipe (title, description, creation_date, category_id) VALUES ('Pepperoni Pizza', 'A pepperoni pizza with additional hot-sauce', '2019-03-03 16:33:11', (SELECT id FROM category WHERE name = 'Breakfast'));
INSERT INTO recipe (title, description, creation_date, category_id) VALUES ('Vegetarian Pizza', 'A vegetarian pizza rich with vitamins.', '2019-02-02 15:23:23', (SELECT id FROM category WHERE name = 'Dinner'));
INSERT INTO recipe (title, description, creation_date, category_id) VALUES ('Pineapple Pizza', 'A fruity pizza rich with a tropical twist.', '2019-01-01 09:11:57', (SELECT id FROM category WHERE name = 'Lunch'));

INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM ingredient WHERE name = 'Dough'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM ingredient WHERE name = 'Ketchup'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM ingredient WHERE name = 'Cheese'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM ingredient WHERE name = 'Pepperoni'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM ingredient WHERE name = 'Dough'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM ingredient WHERE name = 'Ketchup'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM ingredient WHERE name = 'Cheese'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM ingredient WHERE name = 'Oregano'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM ingredient WHERE name = 'Lettuce'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM ingredient WHERE name = 'Dough'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM ingredient WHERE name = 'Lettuce'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM ingredient WHERE name = 'Oregano'));
INSERT INTO recipe_ingredient (recipe_id, ingredient_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM ingredient WHERE name = 'Pineapple'));

INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM tag WHERE name = 'Hot'));
INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pepperoni Pizza'), (SELECT id FROM tag WHERE name = 'Greasy'));
INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM tag WHERE name = 'Sour'));
INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Vegetarian Pizza'), (SELECT id FROM tag WHERE name = 'Sweet'));
INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM tag WHERE name = 'Tangy'));
INSERT INTO recipe_tag (recipe_id, tag_id) VALUES ((SELECT id FROM recipe WHERE title = 'Pineapple Pizza'), (SELECT id FROM tag WHERE name = 'Sweet'));