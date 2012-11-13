%skip   space                    \s
%skip   comment:space            \s
%skip   block:newline           \n
%skip   block:retchar           \r
%skip   hash                     #
%skip   comma                    ,

%token  arobase                  @                  -> string
%token  colon                    :                  -> string
%token  string:colon             :                  -> string
%token  semicolon                ;                  -> default

%token  string:semicolon         ;                  -> default
%token  string:string            [^:;\r\n]+         -> default
%token  comment:string           [^\r\n]+           -> default
%token  comment                  //                 -> comment

%token  class                    [^{]+

%token  brace_                   {                  -> block
%token  block:string             [^:;}{]+           -> block
%token  block:colon              :                  -> block
%token  block:semicolon          ;                  -> block
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


