%skip           space                   [ \t]+
%skip           endl                    \v+

// COMMENT
%token          slash                   [/]{2,}
%token          block_                  /[\*]{1,}            -> cBlock
%skip           cBlock:space            \s
%skip           cBlock:aslash           \\
%token          cBlock:comment          [^\*/\v]+
%token          cBlock:_block           \*/                 -> default
%token          cBlock:single           /                   -> default


// DECLARATION
%token          at_charset              @charset
%token          at_importonce           @import-once
%token          at_import               @import


// VARIABLE
%token          at                      @                   -> variable
%token          variable:name           \w+                 -> default

// STRING
%token          quote_                  ("|')               -> string
%token          string:string           [^"']+
%token          string:_quote           ("|')               -> default

// FUNCTION
%token          parenthesis_            \(
%token          comma                   ,+
%token          _parenthesis            \)




// GENERIC
%token          brace_                  {
%token          _brace                  }
%token          colon                   :+
%token          semicolon               ;+
%token          string                  [^\(\);,{}:\v]+





// PRIMARY RULES
#root:
    (
        comment()
      | declaration()
      | getVariable()
      | ruleset()
      | endl()
    )+

// PARSES RULES
endl:
    ::endl::+

string:
    (getVariable() | function() | operation() | stringInQuote() | <string>)+

stringInQuote:
    ::quote_:: <string> ::_quote::

#function:
    <string> ::parenthesis_:: string() ( ::comma:: string() )* ::_parenthesis::

#operation:
    ::parenthesis_:: string() ( (::comma:: | ::colon::) string() )* ::_parenthesis::

// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce) string() ::semicolon::

#getVariable:
    ::at:: <name> (::colon:: string() #setVariable)? ::semicolon::?

#rule:
    <string> ::colon:: string() ::semicolon::

#ruleset:
    <string> ::brace_:: (ruleset() | rule() | getVariable() | <string> ::semicolon::?)+ ::_brace:: comment()?

#comment:
    commentLine() | commentBlock()

#commentLine:
    ::slash:: <string>?

#commentBlock:
    ::block_::  ( <comment>+ (::_block:: | ::single::))
