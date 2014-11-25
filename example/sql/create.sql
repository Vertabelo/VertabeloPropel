-- Created by Vertabelo (http://vertabelo.com)
-- Script type: create
-- Scope: [tables, references, sequences, views, procedures]
-- Generated at Tue Nov 25 08:47:30 UTC 2014




-- tables
-- Table: author
CREATE TABLE author (
    id int    NOT NULL  AUTO_INCREMENT,
    name varchar(255)    NOT NULL ,
    CONSTRAINT author_pk PRIMARY KEY (id)
);

-- Table: book
CREATE TABLE book (
    id int    NOT NULL  AUTO_INCREMENT,
    title varchar(255)    NOT NULL ,
    description text    NOT NULL ,
    category_id int    NOT NULL ,
    CONSTRAINT book_pk PRIMARY KEY (id)
);

-- Table: book_author
CREATE TABLE book_author (
    book_id int    NOT NULL ,
    author_id int    NOT NULL ,
    CONSTRAINT book_author_pk PRIMARY KEY (book_id,author_id)
);

-- Table: category
CREATE TABLE category (
    id int    NOT NULL  AUTO_INCREMENT,
    name varchar(255)    NOT NULL ,
    parent_category_id int    NULL ,
    CONSTRAINT category_pk PRIMARY KEY (id)
);





-- foreign keys
-- Reference:  book_author_author (table: book_author)


ALTER TABLE book_author ADD CONSTRAINT book_author_author FOREIGN KEY book_author_author (author_id)
    REFERENCES author (id);
-- Reference:  book_author_book (table: book_author)


ALTER TABLE book_author ADD CONSTRAINT book_author_book FOREIGN KEY book_author_book (book_id)
    REFERENCES book (id);
-- Reference:  book_category (table: book)


ALTER TABLE book ADD CONSTRAINT book_category FOREIGN KEY book_category (category_id)
    REFERENCES category (id);
-- Reference:  category_category (table: category)


ALTER TABLE category ADD CONSTRAINT category_category FOREIGN KEY category_category (parent_category_id)
    REFERENCES category (id);



-- End of file.

