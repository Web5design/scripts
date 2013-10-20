#!/usr/bin/env php
<?php
/*
#########################################################################################
#
#   Build a (js,css) package library based, 
#   on a dependencies file, 
#   using various compilers (UglifyJS, Closure)
#
#   PHP: 5.2+ (ca. 2012-2013)
#########################################################################################
*/
error_reporting(E_ALL);

if (!class_exists('BuildPackage'))
{

define('CURRENTDIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

//
// auxilliary functions
//
// simulate python's "startswith" string method
function __startsWith($s, $prefix) { return (0===strpos($s, $prefix)); }
// http://stackoverflow.com/questions/5144583/getting-filename-or-deleting-file-using-file-handle
function __tmpfile() { $tmp=tmpfile(); $meta_data=stream_get_meta_data($tmp); $tmpname=realpath($meta_data["uri"]); return array($tmp, $tmpname); }
/**
 * parseArgs Command Line Interface (CLI) utility function.
 * @author              Patrick Fisher <patrick@pwfisher.com>
 * @see                 https://github.com/pwfisher/CommandLine.php
 */
function __parseArgs($argv = null) 
{
    $argv = $argv ? $argv : $_SERVER['argv']; array_shift($argv); $o = array();
    for ($i = 0, $j = count($argv); $i < $j; $i++) 
    { 
        $a = $argv[$i];
        if (substr($a, 0, 2) == '--') 
        { 
            $eq = strpos($a, '=');
            if ($eq !== false) {  $o[substr($a, 2, $eq - 2)] = substr($a, $eq + 1); }
            else 
            { 
                $k = substr($a, 2);
                if ($i + 1 < $j && $argv[$i + 1][0] !== '-') { $o[$k] = $argv[$i + 1]; $i++; }
                else if (!isset($o[$k])) { $o[$k] = true; } 
            } 
        }
        else if (substr($a, 0, 1) == '-') 
        {
            if (substr($a, 2, 1) == '=') { $o[substr($a, 1, 1)] = substr($a, 3); }
            else 
            {
                foreach (str_split(substr($a, 1)) as $k) { if (!isset($o[$k])) { $o[$k] = true; } }
                if ($i + 1 < $j && $argv[$i + 1][0] !== '-') { $o[$k] = $argv[$i + 1]; $i++; } 
            } 
        }
        else { $o[] = $a; } }
    return $o;
}
    
class BuildPackage
{
    protected $outputToStdOut=true;
    protected $depsFile = '';
    protected $realpath = '';
    protected $enc = false;
    protected $inFiles = null;
    protected $doMinify = false;
    protected $useClosure = false;
    protected $optsUglify = '';
    protected $optsClosure = '';
    protected $outFile = '';
    
    protected function pathreal($file)
    {
        if (''!=$this->realpath && (__startsWith($file, './') || __startsWith($file, '../') || __startsWith($file, '.\\') || __startsWith($file, '..\\'))) 
            return $this->realpath . $file; 
        else return $file;
    }
    
    public function __construct()
    {
        $this->depsFile = '';
        $this->realpath = '';
        $this->enc = false;
        $this->inFiles = null;
        $this->doMinify = false;
        $this->useClosure = false;
        $this->optsUglify = '';
        $this->optsClosure = '';
        $this->outFile = null;
        $this->outputToStdOut = true;
    }
    
    public function BuildPackage() { $this->__construct();  }
    
    public function parseArgs($argv=null)
    {
        $defaultArgs=array(
            'deps'=>false,
            'closure'=>false,
            'enc'=>false
        );
        $args = __parseArgs($argv);
        $args = array_intersect_key($args, $defaultArgs);
        $args = array_merge($defaultArgs, $args);

        if (!isset($args['deps']) || !$args['deps'] || !is_string($args['deps']) || 0==strlen($args['deps']))
        {
            // If no dependencies have been passed, show the help message and exit
            $p=pathinfo(__FILE__);
            $thisFile=(isset($p['extension'])) ? $p['filename'].'.'.$p['extension'] : $p['filename'];
            /*
            $path_parts = pathinfo('/www/htdocs/inc/lib.inc.php');
            echo $path_parts['dirname'], "\n";
            echo $path_parts['basename'], "\n";
            echo $path_parts['extension'], "\n";
            echo $path_parts['filename'], "\n"; // since PHP 5.2.0            
            */
            echo "$thisFile --deps=DEPENDENCIES_FILE [--closure=0|1 --enc=ENCODING]" . PHP_EOL . PHP_EOL;
            echo "Build and Compress Javascript Packages" . PHP_EOL . PHP_EOL;
            echo "deps (String, REQUIRED): DEPENDENCIES_FILE" . PHP_EOL;
            echo "closure (Boolean, Optional): Use Java Closure, else UglifyJS Compiler (default)" . PHP_EOL;
            echo "enc (String, Optional): set text encoding (default utf8)" . PHP_EOL;
            exit(1);
        }
        return $args;
    }
    
    public function parseSettings()
    {
        // settings buffers
        $settings=array(
            // deps
            array(),
            // out
            array(),
            // optsUglify
            array(),
            // optsClosure
            array()
        );
        $deps=0; $out=1; $optsUglify=2; $optsClosure=3;
        $currentBuffer = -1;
        
        // settings options
        $doMinify = false;
        $inMinifyOptions = false;

        // read the dependencies file
        $lines=preg_split("/\\n\\r|\\r\\n|\\r|\\n/", file_get_contents($this->depsFile));
        $len=count($lines);
        
        // parse it line-by-line
        for ($i=0; $i<$len; $i++)
        {
            // strip the line of extra spaces
            $line=str_replace(array("\n", "\r"), "", trim($lines[$i]));
            $linestartswith=substr($line, 0, 1);
            
            // comment or empty line, skip it
            if ('#'==$linestartswith || ''==$line) continue;
            
            // directive line, parse it
            if ('@'==$linestartswith)
            {
                if (__startsWith($line, '@DEPENDENCIES')) // list of input dependencies files option
                {
                    // reference
                    $currentBuffer = $deps;
                    $inMinifyOptions=false;
                    continue;
                }
                else if (__startsWith($line, '@MINIFY')) // enable minification (default is UglifyJS Compiler)
                {
                    // reference
                    $currentBuffer = -1;
                    $doMinify=true;
                    $inMinifyOptions=true;
                    continue;
                }
                else if ($inMinifyOptions && __startsWith($line, '@UGLIFY')) // Node UglifyJS Compiler options (default)
                {
                    // reference
                    $currentBuffer = $optsUglify;
                    continue;
                }
                elseif ($inMinifyOptions && __startsWith($line, '@CLOSURE')) // Java Closure Compiler options
                {
                    // reference
                    $currentBuffer = $optsClosure;
                    continue;
                }
                /*
                elseif (__startsWith($line, '@PREPROCESS')) // allow preprocess options (todo)
                {
                    currentBuffer=-1;
                    inMinifyOptions=false;
                    continue;
                }
                elseif (__startsWith($line, '@POSTPROCESS')) // allow postprocess options (todo)
                {
                    currentBuffer=-1;
                    inMinifyOptions=false;
                    continue;
                }
                */
                elseif (__startsWith($line, '@OUT')) // output file option
                {
                    // reference
                    $currentBuffer = $out;
                    $inMinifyOptions=false;
                    continue;
                }
                else // unknown option or dummy separator option
                {
                    // reference
                    $currentBuffer = -1;
                    $inMinifyOptions=false;
                    continue;
                }
            }
            // if any settings need to be stored, store them in the appropriate buffer
            if ($currentBuffer>=0)  array_push($settings[$currentBuffer], $line);
        }
        // store the parsed settings
        if (isset($settings[$out][0]))
        {
            $this->outFile = $this->pathreal($settings[$out][0]);
            $this->outputToStdOut = false;
        }
        else
        {
            $this->outFile = null;
            $this->outputToStdOut = true;
        }
        $this->inFiles = $settings[$deps];
        $this->doMinify = $doMinify;
        $this->optsUglify = implode(" ", $settings[$optsUglify]);
        $this->optsClosure = implode(" ", $settings[$optsClosure]);
    }
    
    public function parse($argv=null)
    {
        $args = $this->parseArgs($argv);
        // if args are correct continue
        // get real-dir of deps file
        $full_path = $this->depsFile = realpath($args['deps']);
        $this->realpath = rtrim(dirname($full_path), "/\\").DIRECTORY_SEPARATOR;
        $this->enc = $args['enc'];
        $this->useClosure = $args['closure'];
        $this->parseSettings();
    }
    
    public function mergeFiles()
    {
        $files=$this->inFiles;
        if (is_array($files))
        {
            $count=count($files);
            $buffer = array();

            for ($i=0; $i<$count; $i++)
            {
                $filename=$this->pathreal($files[$i]);
                $buffer[]=file_get_contents($filename);
            }

            return implode('', $buffer);
        }
        return '';
    }
    
    public function extractHeader($text)
    {
        $header = '';
        if (__startsWith($text, '/**'))
        {
            $position = strpos($text, "**/");
            $header = substr($text, 0, $position+3);
        }
        return $header;
    }

    public function compress($text)
    {
        if ('' != $text)
        {
            $in_tuple = __tmpfile();
            $out_tuple = __tmpfile();
            
            fwrite($in_tuple[0], $text);
            

            if ($this->useClosure)
            {
                // use Java Closure compiler
                $cmd=escapeshellcmd(sprintf("java -jar %scompiler/compiler.jar %s --js %s --js_output_file %s", CURRENTDIR, $this->optsClosure, $in_tuple[1], $out_tuple[1]));
            }
            else
            {
                // use Node UglifyJS compiler (default)
                $cmd=escapeshellcmd(sprintf("uglifyjs %s %s -o %s", $in_tuple[1], $this->optsUglify, $out_tuple[1]));
            }
            
            exec($cmd, $out, $err=0);
            
            if (!$err) $compressed = file_get_contents($out_tuple[1]); //fread($out_tuple[0], filesize($out_tuple[1]));
            
            @fclose($in_tuple[0]);
            @fclose($out_tuple[0]);
            @unlink($in_tuple[1]);
            @unlink($out_tuple[1]);
            
            // some error occured
            if ($err) exit(1);
            
            return $compressed;
        }
        return '';
    }

    public function build()
    {
        $text = $this->mergeFiles();
        $header = '';
        $sepLine = str_repeat("=", 65) . PHP_EOL; //implode("", array_fill(0, 65, "=")).PHP_EOL;
        
        if ($this->doMinify)
        {
            if (!$this->outputToStdOut)
            {
                echo ($sepLine);
                echo ("Compiling and Minifying " . (($this->useClosure) ? "(Java Closure Compiler)" : "(Node UglifyJS Compiler)") . " " . $this->outFile).PHP_EOL;
                echo ($sepLine);
            }
            
            // minify and add any header
            $header = $this->extractHeader($text);
            $text = $this->compress($text);
        }
        else
        {
            if (!$this->outputToStdOut)
            {
                echo ($sepLine);
                echo ("Compiling " . $this->outFile).PHP_EOL;
                echo ($sepLine);
            }
        }
        
        // write the processed file
        if ($this->outputToStdOut)  echo ($header . $text);
        else file_put_contents($this->outFile, $header . $text);
    }
}
}

// if called directly from command-line
if (
    (php_sapi_name() === 'cli') &&
    (__FILE__ == realpath($_SERVER['SCRIPT_FILENAME']))
)
{
    // do the process
    $buildLib = new BuildPackage();
    $buildLib->parse($_SERVER['argv']);
    $buildLib->build();
    exit (0);
}