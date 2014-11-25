insert into category values (1, 'Literature', null);
insert into category values (2, 'Computers', null);
insert into category values (3, 'Databases', 2);
insert into category values (4, 'Classics', 1);

insert into book values (1, 'Database Design and Relational Theory: Normal Forms and All That Jazz (Theory in Practice)', 'What makes this book different from others on database design? Many resources on design practice do little to explain the underlying theory, and books on design theory are aimed primarily at theoreticians. In this book, renowned expert Chris Date bridges the gap by introducing design theory in ways practitioners can understand—drawing on lessons learned over four decades of experience to demonstrate why proper database design is so critical in the first place.', 3); -- C.J. Date
insert into book values (2, 'Effective Java', 'Are you looking for a deeper understanding of the Java™ programming language so that you can write code that is clearer, more correct, more robust, and more reusable? Look no further! Effective Java™, Second Edition, brings together seventy-eight indispensable programmer’s rules of thumb: working, best-practice solutions for the programming challenges you encounter e', 2); -- Joshua Bloch
insert into book values (3, 'To Kill a Mockingbird', '', 4); -- Harper Lee

insert into author values (1, 'C.J. Date');
insert into author values (2, 'Joshua Bloch');
insert into author values (3, 'Harper Lee');

insert into book_author values (1,1);
insert into book_author values (2,2);
insert into book_author values (3,3);
