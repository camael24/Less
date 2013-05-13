%skip           space                   [ \t]+
%skip           endl                    \v+
// COMMENT
%token          slash                   [/]{2,}
%token          block_comment           /\*(.|\n)*?\*/


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
%token          string                  [^/\(\);,{}:\v]+






// PRIMARY RULES
#root:
    (
        comment()
      | function()
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
    <string> ::parenthesis_:: ( (::comma:: | comment() | function() | string())* )? ::_parenthesis:: ::semicolon::?

#operation:
    ::parenthesis_:: string() ( (::comma:: | ::colon::) string() )* ::_parenthesis::

selector:
    string() (::comma:: | comment() | function() | <string>)*
// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce) string() ::semicolon::

#getVariable:
    ::at:: <name> (::colon:: string() #setVariable)?  comment()* ::semicolon::? comment()*

#rule:
    <string> ::colon:: string() (::comma:: | comment() | string())* comment()* ::semicolon::? comment()*

#ruleset:
    selector() ::brace_:: (ruleset() | rule() | getVariable() | <string> ::semicolon::?)+ ::_brace:: comment()?

comment:
    (::slash:: <string>? #commentLine | <block_comment>+ #commentBlock)