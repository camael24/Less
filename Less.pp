%skip           space                   [ \t]+
%token          endl                    \v+



// Keyword
%token          keyword                 ^\@(import\-once|import|charset)$

// String
%token          quote_                  ("|')                -> string
%token          string:string           [^"']+
%token          string:_quote           ("|')                -> default

// CommentLine
%token          comment                 [/]{2,}[\\]{0,}     -> commentL
%skip           commentL:space          [ \t]+
%token          commentL:string         [^\v]+
%token          commentL:endl           \v+                 -> default

%token          commentBlock_           /[\*]{1,}           -> commentB
%token          commentB:string         [^\*\v]+
%skip           commentB:space          [ \t]+
%token          commentB:endl           \v+
%token          commentB:_commentBlock  [\*]{1,}/           -> default
%token          commentB:star           \*+

// Generic
%token          string                  [^;:{}\v]+
%token          arobase                 @
%token          semicolon               ;
%token          colon                   :
%token          brace_                  {
%token          _brace                  }


// PRIMARY RULES
#root:
    (
        setVariable()
      | comment()
      | class()
      | ::endl::
    )+

// PARSES RULES
string:
    ::quote_:: <string> ::_quote::

selector:
    <string>

#name:
    <string>

#value:
    <string>

#properties:
    name() ::colon:: value() (::semicolon:: | ::endl:: | comment())?

commentString:
    ::star::? <string> ::star::?

// LESS RULES
#keyword:
    <keyword> string() ::semicolon::

#class:
    selector() ::brace_:: ::endl::? (class() | properties() | ::endl::)+ ::_brace:: (comment() | ::endl::))?

#comment:
    (commentLine() | commentBlock())

#commentBlock:
    <commentBlock_> (::endl:: | commentString())+ <_commentBlock>

#commentLine:
    ::comment:: commentString()? ::endl::+

#setVariable:
    ::arobase:: <string>