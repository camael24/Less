%skip   space                    \s
%token  arobase                  @
%token  colon                    :
%token  semicolon                ;
%token  key                     [^:]+
%token  val                     [^;]+



#parsing:
     (
        ::arobase:: key() ::colon::                         #variable
     )+

key:
    <key>

value:
    <val>


