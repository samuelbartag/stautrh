BEGIN TRANSACTION;
DROP TABLE IF EXISTS "users";
CREATE TABLE IF NOT EXISTS "users" (
	"id" INTEGER PRIMARY KEY AUTOINCREMENT,
	"name" TEXT NOT NULL,
	"email"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"token" TEXT
);
INSERT INTO users(name, email, password, token)
	VALUES
		("João", "joao@email.com.br", "joao", null),
		("Sebastião", "sebastiao@email.com.br", "sebastiao", null),
		("Luiza", "luiza@email.com.br", "luiza", null),
		("Maria", "maria@email.com.br", "maria", null),
		("Antonio", "antonio@email.com.br", "antonio", null),
		("Eduardo", "eduardo@email.com.br", "eduardo", null),
		("Tania", "tania@email.com.br", "tania", null),
		("Olívia", "olivia@email.com.br", "olivia", null);
DROP TABLE IF EXISTS "drink";
CREATE TABLE IF NOT EXISTS "drink" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
	"idUser" INTEGER NOT NULL,
	"amount" INTEGER NOT NULL,
	"createdAt" INTEGER NOT NULL
);
COMMIT;
