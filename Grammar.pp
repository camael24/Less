%skip   space                    \s
%token  arobase                  \@
%token  colon                    :
%token  semicolon                ;
%token  id                       [a-zA-Z0-9]+
%token  hash                     #
%token  true                     true
%token  false                    false
%token  null                     null


id:
    <id> ::semicolon::

key:
    <id> ::colon::

color:
    ::hash:: id()

value:
   color() | id()

variable:
    ::arobase:: key()

#pair:
    ( variable() | key() ) value()






