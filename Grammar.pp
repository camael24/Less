%skip   space                    \s
%skip   string:space             \s
%skip   comment:space            \s
%skip   block:space              \s
%skip   bString:space            \s
%skip   bString:c                :
%skip   bString:r                \r
%skip   bString:n                \n

%skip   hash                     #
%skip   comma                    ,

// Variable
%token  arobase                  @                  -> string
%token  string:colon             :
%token  string:string            [^:;\r\n]+
%token  string:semicolon         ;                  -> default



// Comment
%token  comment:string           [^\r\n]+           -> default
%token  comment                  //                 -> comment

// Block class
%token  class                    [^{]+
%token  brace_                   {                  -> block
%token  block:string             [^/:;}]+
%token  block:colon              :                  -> bString
%token  bString:string           [^;\r\n]+
%token  bString:semicolon        ;                  -> block
%token  block:semicolon          ;                  -> block
%token  bString:_brace           }                  -> block
%token  block:_brace             }                  -> default
%token  bString:comment          //                 -> bComment
%token  block:comment            //                 -> bComment
%token  bComment:string          [^\r\n]+           -> block


#parsing:
     ( variable() | commentLine() | block() )*

#variable:
     ::arobase::  <string> ::colon:: <string> ::semicolon::

#block:
    class() ::brace_:: ( blockKey()  )* ::_brace::

#commentLine:
    ::comment:: comment()

#bCommentLine:
    ::comment:: comment()

#blockKey:
        <string> (::colon:: <string> )? (::semicolon::)? bCommentLine()?

class:
    <class>

comment:
    <string>

key:
    <string>

value:
    <string>
