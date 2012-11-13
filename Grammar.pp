%skip   space                    \s
%skip   comment:space            \s
%skip   block:newline           \n
%skip   block:retchar           \r
%skip   hash                     #
%skip   comma                    ,

// Variable

%token  arobase                  @                  -> string
%token  colon                    :                  -> string
%token  string:colon             :
%token  semicolon                ;

%token  string:semicolon         ;                  -> default
%token  string:string            [^:;\r\n]+         -> default

// Comment

%token  comment:string           [^\r\n]+           -> default
%token  comment                  //                 -> comment

// Block class

%token  class                    [^{]+
%token  brace_                   {                  -> block
%token  block:string             [^:;}{]+
%token  block:colon              :
%token  block:semicolon          ;
%token  block:_brace             }                  -> default




#parsing:
     (
        variable()
      | commentLine()
      | block()
     )*

#variable:
     (
         ::arobase::  key() ::colon:: value() ::semicolon::
     )

#block:
    <class> ::brace_:: blockKey()* ::_brace::


#commentLine:
    ::comment:: comment()

#blockKey:
    <string> ::colon:: <string> ::semicolon::

#mixKey:
    <string> ::colon:: <string> ::semicolon::

comment:
    <string>

key:
    <string>

value:
    <string>


