%skip           space                   [ \t]+
%skip           endl                    \v+

// DECLARATION
%token          at_charset              @charset            -> key
%token          at_importonce           @import-once        -> key
%token          at_import               @import             -> key
%skip           key:colon               :
%token          key:string              [^;]                -> default


// VARIABLE
%token          at                      @                   -> variable
%token          variable:name           \w+[-]?\w+          -> default

// GENERIC
%token          colon                   :
%token          semicolon               ;
%token          string                  [^;:\v]+





// PRIMARY RULES
#root:
    (
        declaration()
      | getVariable()
    )+

// PARSES RULES

// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce) <string>+ ::semicolon::

#getVariable:
    ::at:: <name> (::colon:: <string> #setVariable)? ::semicolon::?