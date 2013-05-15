%skip           space                   [ \t]+
%skip           endl                    \v+

// URL
%token          http                    http://

// COMMENT
%skip           slash                   //[^\v$]*
%skip           block_comment           /\*(.|\n)*?\*/

// DECLARATION
%token          at_charset              @charset
%token          at_namespace            @namespace
%token          at_importonce           @import-once
%token          at_import               @import

// VARIABLE
%token          at                      @                   -> variable
%token          variable:brace_         {
%token          variable:_brace         }
%token          variable:at             @
%token          variable:name           [a-z0-9A-Z\-_]+      -> default

// STRING
%token          stringInQuote           ("|'|`)(.*?)\1

// FUNCTION
%token          parenthesis_            \(
%token          comma                   ,+
%token          _parenthesis            \)

// GENERIC
%token          brace_                  {+
%token          _brace                  }+
%token          colon                   :+
%token          child                   >+
%token          semicolon               ;+
%token          string                  [^@'"(\);,{}:\v]+

// PRIMARY RULES
#root:
    (
       function()
      | declaration()
      | getVariable()
      | ruleset()
    )*

// PARSES RULES
string:
    (function() |getVariable() | <stringInQuote> | <http>? <string>)+

#function:
    (<string> | <string>? #parens)  ::parenthesis_:: (::comma:: | ::semicolon:: | <colon> | <child> |  function() | string())* ::_parenthesis::  ::semicolon::?

selector:
    (<comma> | <colon> | <child> | string())*
// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce | ::at_namespace:: #namespace) string() ::semicolon::

#getVariable:
    ::at:: ((::at:: <name> #getVariableRelative) | ::brace_:: <name> ::_brace:: | <name> ( ::colon:: (<comma> | string())* ) ?) ::semicolon::?

#rule:
    <string> ::colon:: (::comma:: | string())* ::semicolon::?

#ruleset:
    selector() ::brace_:: ( declaration() | getVariable() | function() | rule() | ruleset() | <string> ::semicolon::? )* ::_brace:: ::semicolon::?
