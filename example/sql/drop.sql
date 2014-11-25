-- Created by Vertabelo (http://vertabelo.com)
-- Script type: drop
-- Scope: [tables, references, sequences, views, procedures]
-- Generated at Mon Nov 24 08:19:45 UTC 2014



-- foreign keys
ALTER TABLE book_author DROP FOREIGN KEY book_author_author;
ALTER TABLE book_author DROP FOREIGN KEY book_author_book;
ALTER TABLE book DROP FOREIGN KEY book_category;
ALTER TABLE category DROP FOREIGN KEY category_category;

-- tables
-- Table author
DROP TABLE author;
-- Table book
DROP TABLE book;
-- Table book_author
DROP TABLE book_author;
-- Table category
DROP TABLE category;



-- End of file.

