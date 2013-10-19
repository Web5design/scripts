#!/bin/sh
## #!/usr/bin/env sh

## Closure Options
##
##
# --accept_const_keyword                 : Allows usage of const keyword.
# --charset VAL                          : Input and output charset for all files
#                                          . By default, we accept UTF-8 as input
#                                           and output US_ASCII
# --closure_entry_point VAL              : Entry points to the program. Must be g
#                                          oog.provide'd symbols. Any goog.provid
#                                          e'd symbols that are not a transitive 
#                                          dependency of the entry points will be
#                                           removed. Files without goog.provides,
#                                           and their dependencies, will always b
#                                          e left in. If any entry points are spe
#                                          cified, then the manage_closure_depend
#                                          encies option will be set to true and 
#                                          all files will be sorted in dependency
#                                           order.
# --compilation_level [WHITESPACE_ONLY | : Specifies the compilation level to use
#  SIMPLE_OPTIMIZATIONS | ADVANCED_OPTIM : . Options: WHITESPACE_ONLY, SIMPLE_OPT
# IZATIONS]                              : IMIZATIONS, ADVANCED_OPTIMIZATIONS
# --create_name_map_files                : If true, variable renaming and propert
#                                          y renaming map files will be produced 
#                                          as {binary name}_vars_map.out and {bin
#                                          ary name}_props_map.out. Note that thi
#                                          s flag cannot be used in conjunction w
#                                          ith either variable_map_output_file or
#                                           property_map_output_file
# --create_source_map VAL                : If specified, a source map file mappin
#                                          g the generated source files back to t
#                                          he original source file will be output
#                                           to the specified path. The %outname% 
#                                          placeholder will expand to the name of
#                                           the output file that the source map c
#                                          orresponds to.
# --debug                                : Enable debugging options
# --define (--D, -D) VAL                 : Override the value of a variable annot
#                                          ated @define. The format is <name>[=<v
#                                          al>], where <name> is the name of a @d
#                                          efine variable and <val> is a boolean,
#                                           number, or a single-quoted string tha
#                                          t contains no single quotes. If [=<val
#                                          >] is omitted, the variable is marked 
#                                          true
# --externs VAL                          : The file containing javascript externs
#                                          . You may specify multiple
# --flagfile VAL                         : A file containing additional command-l
#                                          ine options.
# --formatting [PRETTY_PRINT | PRINT_INP : Specifies which formatting options, if
# UT_DELIMITER]                          :  any, should be applied to the output 
#                                          JS. Options: PRETTY_PRINT, PRINT_INPUT
#                                          _DELIMITER
# --generate_exports                     : Generates export code for those marked
#                                           with @export
# --help                                 : Displays this message
# --js VAL                               : The javascript filename. You may speci
#                                          fy multiple
# --js_output_file VAL                   : Primary output filename. If not specif
#                                          ied, output is written to stdout
# --jscomp_error VAL                     : Make the named class of warnings an er
#                                          ror. Options:accessControls, ambiguous
#                                          FunctionDecl, checkRegExp,checkTypes, 
#                                          checkVars, constantProperty, deprecate
#                                          d, externsValidation, fileoverviewTags
#                                          , globalThis, internetExplorerChecks, 
#                                          invalidCasts, missingProperties, nonSt
#                                          andardJsDocs, strictModuleDepCheck, ty
#                                          peInvalidation, undefinedVars, unknown
#                                          Defines, uselessCode, visibility
# --jscomp_off VAL                       : Turn off the named class of warnings. 
#                                          Options:accessControls, ambiguousFunct
#                                          ionDecl, checkRegExp,checkTypes, check
#                                          Vars, constantProperty, deprecated, ex
#                                          ternsValidation, fileoverviewTags, glo
#                                          balThis, internetExplorerChecks, inval
#                                          idCasts, missingProperties, nonStandar
#                                          dJsDocs, strictModuleDepCheck, typeInv
#                                          alidation, undefinedVars, unknownDefin
#                                          es, uselessCode, visibility
# --jscomp_warning VAL                   : Make the named class of warnings a nor
#                                          mal warning. Options:accessControls, a
#                                          mbiguousFunctionDecl, checkRegExp,chec
#                                          kTypes, checkVars, constantProperty, d
#                                          eprecated, externsValidation, fileover
#                                          viewTags, globalThis, internetExplorer
#                                          Checks, invalidCasts, missingPropertie
#                                          s, nonStandardJsDocs, strictModuleDepC
#                                          heck, typeInvalidation, undefinedVars,
#                                           unknownDefines, uselessCode, visibili
#                                          ty
# --language_in VAL                      : Sets what language spec that input sou
#                                          rces conform. Options: ECMASCRIPT3 (de
#                                          fault), ECMASCRIPT5, ECMASCRIPT5_STRIC
#                                          T
# --logging_level VAL                    : The logging level (standard java.util.
#                                          logging.Level values) for Compiler pro
#                                          gress. Does not control errors or warn
#                                          ings for the JavaScript code under com
#                                          pilation
# --manage_closure_dependencies          : Automatically sort dependencies so tha
#                                          t a file that goog.provides symbol X w
#                                          ill always come before a file that goo
#                                          g.requires symbol X. If an input provi
#                                          des symbols, and those symbols are nev
#                                          er required, then that input will not 
#                                          be included in the compilation.
# --module VAL                           : A javascript module specification. The
#                                           format is <name>:<num-js-files>[:[<de
#                                          p>,...][:]]]. Module names must be uni
#                                          que. Each dep is the name of a module 
#                                          that this module depends on. Modules m
#                                          ust be listed in dependency order, and
#                                           js source files must be listed in the
#                                           corresponding order. Where --module f
#                                          lags occur in relation to --js flags i
#                                          s unimportant
# --module_output_path_prefix VAL        : Prefix for filenames of compiled js mo
#                                          dules. <module-name>.js will be append
#                                          ed to this prefix. Directories will be
#                                           created as needed. Use with --module
# --module_wrapper VAL                   : An output wrapper for a javascript mod
#                                          ule (optional). The format is <name>:<
#                                          wrapper>. The module name must corresp
#                                          ond with a module specified using --mo
#                                          dule. The wrapper must contain %s as t
#                                          he code placeholder
# --output_manifest VAL                  : Prints out a list of all the files in 
#                                          the compilation. If --manage_closure_d
#                                          ependencies is on, this will not inclu
#                                          de files that got dropped because they
#                                           were not required. The %outname% plac
#                                          eholder expands to the js output file.
#                                           If you're using modularization, using
#                                           %outname% will create a manifest for 
#                                          each module.
# --output_wrapper VAL                   : Interpolate output into this string at
#                                           the place denoted by the marker token
#                                           %output%. See --output_wrapper_marker
# --print_ast                            : Prints a dot file describing the inter
#                                          nal abstract syntax tree and exits
# --print_pass_graph                     : Prints a dot file describing the passe
#                                          s that will get run and exits
# --print_tree                           : Prints out the parse tree and exits
# --process_closure_primitives           : Processes built-ins from the Closure l
#                                          ibrary, such as goog.require(), goog.p
#                                          rovide(), and goog.exportSymbol()
# --property_map_input_file VAL          : File containing the serialized version
#                                           of the property renaming map produced
#                                           by a previous compilation
# --property_map_output_file VAL         : File where the serialized version of t
#                                          he property renaming map produced shou
#                                          ld be saved
# --summary_detail_level N               : Controls how detailed the compilation 
#                                          summary is. Values: 0 (never print sum
#                                          mary), 1 (print summary only if there 
#                                          are errors or warnings), 2 (print summ
#                                          ary if type checking is on, see --chec
#                                          k_types), 3 (always print summary). Th
#                                          e default level is 1
# --third_party                          : Check source validity but do not enfor
#                                          ce Closure style rules and conventions
# --use_only_custom_externs              : Specifies whether the default externs 
#                                          should be excluded
# --variable_map_input_file VAL          : File containing the serialized version
#                                           of the variable renaming map produced
#                                           by a previous compilation
# --variable_map_output_file VAL         : File where the serialized version of t
#                                          he variable renaming map produced shou
#                                          ld be saved
# --version                              : Prints the compiler version to stderr.
# --warning_level [QUIET | DEFAULT | VER : Specifies the warning level to use. Op
# BOSE]                                  : tions: QUIET, DEFAULT, VERBOSE

#java -jar ./compiler/compiler.jar --language_in=ECMASCRIPT5_STRICT --js "$1"



## UglifyJS Options
##
##
# uglifyjs [input files] [options]
# --source-map       Specify an output file where to generate source map.
#                                                                       [string]
# --source-map-root  The path to the original source to be included in the
#                    source map.                                        [string]
# --source-map-url   The path to the source map to be added in //#
#                    sourceMappingURL.  Defaults to the value passed with
#                    --source-map.                                      [string]
# --in-source-map    Input source map, useful if you're compressing JS that was
#                    generated from some other original code.
# --screw-ie8        Pass this flag if you don't care about full compliance
#                    with Internet Explorer 6-8 quirks (by default UglifyJS
#                    will try to be IE-proof).                         [boolean]
# --expr             Parse a single expression, rather than a program (for
#                    parsing JSON)                                     [boolean]
# -p, --prefix       Skip prefix for original filenames that appear in source
#                    maps. For example -p 3 will drop 3 directories from file
#                    names and ensure they are relative paths. You can also
#                    specify -p relative, which will make UglifyJS figure out
#                    itself the relative paths between original sources, the
#                    source map and the output file.                    [string]
# -o, --output       Output file (default STDOUT).
# -b, --beautify     Beautify output/specify output options.            [string]
# -m, --mangle       Mangle names/pass mangler options.                 [string]
# -r, --reserved     Reserved names to exclude from mangling.
# -c, --compress     Enable compressor/pass compressor options. Pass options
#                    like -c hoist_vars=false,if_return=false. Use -c with no
#                    argument to use the default compression options.   [string]
# -d, --define       Global definitions                                 [string]
# -e, --enclose      Embed everything in a big function, with a configurable
#                    parameter/argument list.                           [string]
# --comments         Preserve copyright comments in the output. By default this
#                    works like Google Closure, keeping JSDoc-style comments
#                    that contain "@license" or "@preserve". You can optionally
#                    pass one of the following arguments to this flag:
#                    - "all" to keep all comments
#                    - a valid JS regexp (needs to start with a slash) to keep
#                    only comments that match.
#                    Note that currently not *all* comments can be kept when
#                    compression is on, because of dead code removal or
#                    cascading statements into sequences.               [string]
# --stats            Display operations run time on STDERR.            [boolean]
# --acorn            Use Acorn for parsing.                            [boolean]
# --spidermonkey     Assume input files are SpiderMonkey AST format (as JSON).
#                                                                      [boolean]
# --self             Build itself (UglifyJS2) as a library (implies
#                    --wrap=UglifyJS --export-all)                     [boolean]
# --wrap             Embed everything in a big function, making the “exports”
#                    and “global” variables available. You need to pass an
#                    argument to this option to specify the name that your
#                    module will take when included in, say, a browser.
#                                                                       [string]
# --export-all       Only used when --wrap, this tells UglifyJS to add code to
#                    automatically export all globals.                 [boolean]
# --lint             Display some scope warnings                       [boolean]
# -v, --verbose      Verbose                                           [boolean]
# -V, --version      Print version number and exit.                    [boolean]
#
uglifyjs "$1" -m -c