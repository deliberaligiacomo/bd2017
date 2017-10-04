CREATE TABLE users 
             ( 
                          id SERIAL PRIMARY KEY, 
                          username TEXT NOT NULL UNIQUE, 
                          password TEXT NOT NULL, 
                          firstName TEXT, 
                          lastName TEXT, 
                          email TEXT UNIQUE, 
                          birthday DATE
             );
CREATE TABLE books 
             ( 
                          id SERIAL PRIMARY KEY, 
                          title TEXT, 
                          image text, 
                          genre text
             );

CREATE TABLE authors 
             ( 
                          id SERIAL PRIMARY KEY, 
                          firstName TEXT NOT NULL, 
                          lastName TEXT NOT NULL, 
                          birthday DATE, 
                          nationality TEXT
             );
CREATE TABLE books_authors 
             ( 
                          id_author INT NOT NULL, 
                          id_book  INT NOT NULL, 
                          PRIMARY KEY(id_author, id_book) 
             );
ALTER TABLE books_authors ADD FOREIGN KEY (id_author) REFERENCES authors ON DELETE SET NULL;
ALTER TABLE books_authors ADD FOREIGN KEY (id_book) REFERENCES books ON DELETE SET NULL;

CREATE TABLE reviews 
             ( 
                          id_review SERIAL PRIMARY KEY, 
                          title TEXT NOT NULL, 
                          text TEXT NOT NULL, 
                          grade      INT NOT NULL, 
                          score INT NOT NULL DEFAULT 0, 
                          id_author INT NOT NULL, 
                          id_book  INT NOT NULL
             );
ALTER TABLE reviews ADD FOREIGN KEY (id_author) REFERENCES users ON DELETE SET NULL;
ALTER TABLE reviews ADD FOREIGN KEY (id_book) REFERENCES books ON DELETE CASCADE;

CREATE TABLE grades_reviews 
             ( 
                          id_user     INT NOT NULL, 
                          id_review INT NOT NULL, 
                          grade          INT NOT NULL, 
                          PRIMARY KEY(id_user, id_review) 
             );
ALTER TABLE grades_reviews ADD FOREIGN KEY (id_user) REFERENCES users ON DELETE CASCADE;
ALTER TABLE grades_reviews ADD FOREIGN KEY (id_review) REFERENCES reviews ON DELETE CASCADE;

CREATE TABLE comments 
             ( 
                          id_comment SERIAL, 
                          text TEXT NOT NULL, 
                          score     INT DEFAULT 0, 
                          id_user       INT NOT NULL, 
                          id_review INT NOT NULL, 
                          id_ref_comm   INT, 
                          date_comment TIMESTAMP, 
                          PRIMARY KEY(id_comment) 
             );
ALTER TABLE comments ADD FOREIGN KEY (id_user) REFERENCES users ON DELETE SET NULL;
ALTER TABLE comments ADD FOREIGN KEY (id_ref_comm) REFERENCES comments ON DELETE CASCADE;
ALTER TABLE comments ADD FOREIGN KEY (id_review) REFERENCES reviews ON DELETE CASCADE;

CREATE TABLE grades_comments 
             ( 
                          id_user   INT NOT NULL, 
                          id_comment INT NOT NULL, 
                          grade        INT NOT NULL, 
                          PRIMARY KEY(id_user, id_comment) 
             );
             
ALTER TABLE grades_comments ADD FOREIGN KEY (id_user) REFERENCES users ON DELETE CASCADE;
ALTER TABLE grades_comments ADD FOREIGN KEY (id_comment) REFERENCES comments ON DELETE CASCADE;