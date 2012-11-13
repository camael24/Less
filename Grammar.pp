%skip   space                    \s
%skip   comment:space            \s
%skip   hash                     #
%token  arobase                  @                  -> string
%token  colon                    :                  -> string
%token  string:colon             :                  -> string
%token  semicolon                ;                  -> default
%token  string:semicolon         ;                  -> default
%token  string:string            [^:;/]+            -> default
%token  comment:string           [a-zA-Z0-9\s-]+    -> default
%token  comment                  //                 -> comment




#parsing:
     (
        variable()
      | commentLine()
     )*

#variable:
     (
         ::arobase:: key() ::colon:: value() ::semicolon::
     )+


#commentLine:
    ::comment:: comment()

#comment:
   ::string::

#key:
    <string>

#value:
    <string>


