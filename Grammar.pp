%skip   space                    \s
%skip   comment:space            \s
%skip   hash                     #
%skip   comma                    ,

%token  arobase                  @                  -> string
%token  colon                    :                  -> string
%token  string:colon             :                  -> string
%token  semicolon                ;                  -> default
%token  string:semicolon         ;                  -> default
%token  string:string            [^:;\r\n]+          -> default
%token  comment:string           [^\r\n]+             -> default
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
   <string>

#key:
    <string>

#value:
    <string>


