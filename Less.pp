%skip           space                   [ \t]+
%token          endl                    \v+

// Generic
%token          string                  [^@;:{}\v]+
%token          semicolon               ;+
%token          colon                   :+
%token          brace_                  {+
%token          _brace                  }+
%token          arobase                 \@+

// Keyword
%token          keyword                 (import\-once|import|charset)

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
%token          commentB:star           \*+
%token          commentB:_commentBlock  [\*]{1,}/           -> default






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

#class:
    selector() ::brace_:: (class() | properties() | ::endl::)+ ::_brace:: (comment() | ::endl::))?

#comment:
    (commentLine() | commentBlock())

#commentBlock:
    <commentBlock_> (::endl:: | commentString())+ <_commentBlock>

#commentLine:
    ::comment:: commentString()? ::endl::+

#setVariable:
    <arobase> <string> (::colon:: <string>)? ::semicolon::
