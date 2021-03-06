<?php
/**
 * Funcionalidades de Strings
 * 
 * @package Gamelena_Utils 
 * @version $Id:$
 * @since   0.1
 */

class Gamelena_Utils_String
{
    
    public static function mask($haystack, $char_list,$cut_chars=false) 
    {
        $haystack = str_split($haystack);
        $newstr = array();
        foreach($haystack as $letter) { 
            if((boolean)strpos($char_list, $letter)!==(boolean) $cut_chars) { 
                $newstr[] = $letter; 
            } 
        }
        return implode('', $newstr);
    }
    
    /**
     * Reemplaza caracteres especiales por no especiales. 
     * @param $string string
     * @param $nospaces boolean, reemplazar espacios por underscores.
     * @return string
     */
    
    public static function stripAccents($string, $nospaces=false)
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
        $string = str_replace($a, $b, $string);
        if ($nospaces) { 
            $string = str_replace(" ", "_", $string); 
        }
        return $string;
    }    
    
    /**
     * Transforma un string a UpperCamelCase
     *
     * @param  $string
     * @param  $limiter
     * @return string
     */
    
    public static function toClassWord($string, $limiter="_")
    {
        $string = explode($limiter, $string);
        
        if (is_array($string)) {
            $return = "";
            foreach ($string as $s) {
                $return .= ucfirst($s); 
            }
        } else {
            $return = ucfirst($string);
        }
        return $return;        
    }
    
    /**
     * Transforma un string lowerCamelCase
     * @param $string
     * @param $limiter
     * @return string
     */
    
    public static function toFunctionWord($string, $limiter="_")
    {
        $string = explode($limiter, $string);
        
        if (is_array($string)) {
            $return = "";
            $i = 0;
            foreach ($string as $s) {
                $return .= ($i==0) ? strtolower($s) : ucfirst($s);
                $i++; 
            }
        } else {
            $return=$string;
        }
        return $return;
    }
    
    /**
     * Transforma una variable a un string a nombres de variables simple 
     * compatible con identificadores Dom HTML y variables Javascript
     * @param $string
     * @param $limiter
     * @return string
     */    
    
    public static function toVarWord($string)
    {
        $string = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", "_", $string);
        $string = preg_replace("/[\.\*-\/]/", "_", $string);
        return $string;
    }
    
    /**
     * Elimina los caracteres especiales de un string
     * @param string $text
     * @return string
     */
    static public function slugify($text)
    { 
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
    
    /**
     * Elimina caracteres no texto de un string, util para casos de basura hexadecimal o binaria en un string.
     * 
     * @param  string $text
     * @return string
     */
    static public function textify($text)
    {
        return preg_replace('/[^(\x20-\x7F)]*/', '', $text);
    }
    
    
    /**
     * 
     * @param mixed $input
     * @return string
     */
    function decodeAsciiHex($input)
    {
        $output = "";
    
        $isOdd = true;
        $isComment = false;
    
        for($i = 0, $codeHigh = -1; $i < strlen($input) && $input[$i] != '>'; $i++) {
            $c = $input[$i];
    
            if($isComment) {
                if ($c == '\r' || $c == '\n') {
                    $isComment = false; 
                }
                continue;
            }
    
            switch($c) {
            case '\0': case '\t': case '\r': case '\f': case '\n': case ' ': 
                break;
            case '%':
                $isComment = true;
                break;
    
            default:
                $code = hexdec($c);
                if($code === 0 && $c != '0') {
                    return ""; 
                }
    
                if($isOdd) {
                    $codeHigh = $code; 
                }
                else {
                    $output .= chr($codeHigh * 16 + $code); 
                }
    
                $isOdd = !$isOdd;
                break;
            }
        }
    
        if($input[$i] != '>') {
            return ""; 
        }
    
        if($isOdd) {
            $output .= chr($codeHigh * 16); 
        }
    
        return $output;
    }
    
    function decodeAscii85($input)
    {
        $output = "";
    
        $isComment = false;
        $ords = array();
    
        for($i = 0, $state = 0; $i < strlen($input) && $input[$i] != '~'; $i++) {
            $c = $input[$i];
    
            if($isComment) {
                if ($c == '\r' || $c == '\n') {
                    $isComment = false; 
                }
                continue;
            }
    
            if ($c == '\0' || $c == '\t' || $c == '\r' || $c == '\f' || $c == '\n' || $c == ' ') {
                continue; 
            }
            if ($c == '%') {
                $isComment = true;
                continue;
            }
            if ($c == 'z' && $state === 0) {
                $output .= str_repeat(chr(0), 4);
                continue;
            }
            if ($c < '!' || $c > 'u') {
                return ""; 
            }
    
            $code = ord($input[$i]) & 0xff;
            $ords[$state++] = $code - ord('!');
    
            if ($state == 5) {
                $state = 0;
                for ($sum = 0, $j = 0; $j < 5; $j++) {
                    $sum = $sum * 85 + $ords[$j]; 
                }
                for ($j = 3; $j >= 0; $j--) {
                    $output .= chr($sum >> ($j * 8)); 
                }
            }
        }
        if ($state === 1) {
            return ""; 
        }
        elseif ($state > 1) {
            for ($i = 0, $sum = 0; $i < $state; $i++) {
                $sum += ($ords[$i] + ($i == $state - 1)) * pow(85, 4 - $i); 
            }
            for ($i = 0; $i < $state - 1; $i++) {
                $ouput .= chr($sum >> ((3 - $i) * 8)); 
            }
        }
    
        return $output;
    }
    function decodeFlate($input)
    {
        return @gzuncompress($input);
    }
    
    function getObjectOptions($object)
    {
        $options = array();
        if (preg_match("#<<(.*)>>#ismU", $object, $options)) {
            $options = explode("/", $options[1]);
            @array_shift($options);
    
            $o = array();
            for ($j = 0; $j < @count($options); $j++) {
                $options[$j] = preg_replace("#\s+#", " ", trim($options[$j]));
                if (strpos($options[$j], " ") !== false) {
                    $parts = explode(" ", $options[$j]);
                    $o[$parts[0]] = $parts[1];
                } else {
                    $o[$options[$j]] = true; 
                }
            }
            $options = $o;
            unset($o);
        }
    
        return $options;
    }
    
    
    function getDecodedStream($stream, $options) 
    {
        $data = "";
        if (empty($options["Filter"])) {
            $data = $stream; 
        }
        else {
            $length = !empty($options["Length"]) ? $options["Length"] : strlen($stream);
            $_stream = substr($stream, 0, $length);
    
            foreach ($options as $key => $value) {
                if ($key == "ASCIIHexDecode") {
                    $_stream = decodeAsciiHex($_stream); 
                }
                if ($key == "ASCII85Decode") {
                    $_stream = decodeAscii85($_stream); 
                }
                if ($key == "FlateDecode") {
                    $_stream = decodeFlate($_stream); 
                }
            }
            $data = $_stream;
        }
        return $data;
    }
    
    
    function getDirtyTexts(&$texts, $textContainers)
    {
        for ($j = 0; $j < count($textContainers); $j++) {
            if (preg_match_all("#\[(.*)\]\s*TJ#ismU", $textContainers[$j], $parts)) {
                $texts = array_merge($texts, @$parts[1]); 
            }
            elseif(preg_match_all("#Td\s*(\(.*\))\s*Tj#ismU", $textContainers[$j], $parts))
            $texts = array_merge($texts, @$parts[1]);
        }
    }
    
    
    function getCharTransformations(&$transformations, $stream)
    {
        preg_match_all("#([0-9]+)\s+beginbfchar(.*)endbfchar#ismU", $stream, $chars, PREG_SET_ORDER);
        preg_match_all("#([0-9]+)\s+beginbfrange(.*)endbfrange#ismU", $stream, $ranges, PREG_SET_ORDER);
    
        for ($j = 0; $j < count($chars); $j++) {
            $count = $chars[$j][1];
            $current = explode("\n", trim($chars[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0-9a-f]{2,4})>\s+<([0-9a-f]{4,512})>#is", trim($current[$k]), $map)) {
                    $transformations[str_pad($map[1], 4, "0")] = $map[2]; 
                }
            }
        }
        for ($j = 0; $j < count($ranges); $j++) {
            $count = $ranges[$j][1];
            $current = explode("\n", trim($ranges[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+<([0-9a-f]{4})>#is", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $_from = hexdec($map[3]);
    
                    for ($m = $from, $n = 0; $m <= $to; $m++, $n++) {
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", $_from + $n); 
                    }
                } elseif (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+\[(.*)\]#ismU", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $parts = preg_split("#\s+#", trim($map[3]));
    
                    for ($m = $from, $n = 0; $m <= $to && $n < count($parts); $m++, $n++) {
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", hexdec($parts[$n])); 
                    }
                }
            }
        }
    }
    
    
    function getTextUsingTransformations($texts, $transformations)
    {
        $document = "";
        for ($i = 0; $i < count($texts); $i++) {
            $isHex = false;
            $isPlain = false;
    
            $hex = "";
            $plain = "";
            for ($j = 0; $j < strlen($texts[$i]); $j++) {
                $c = $texts[$i][$j];
                switch($c) {
                case "<":
                    $hex = "";
                    $isHex = true;
                    break;
                case ">":
                    $hexs = str_split($hex, 4);
                    for ($k = 0; $k < count($hexs); $k++) {
                        $chex = str_pad($hexs[$k], 4, "0");
                        if (isset($transformations[$chex])) {
                            $chex = $transformations[$chex]; 
                        }
                        $document .= html_entity_decode("&#x".$chex.";");
                    }
                    $isHex = false;
                    break;
                case "(":
                    $plain = "";
                    $isPlain = true;
                    break;
                case ")":
                    $document .= $plain;
                    $isPlain = false;
                    break;
                case "\\":
                    $c2 = $texts[$i][$j + 1];
                    if (in_array($c2, array("\\", "(", ")"))) { $plain .= $c2; 
                    }
                    elseif ($c2 == "n") $plain .= '\n';
                    elseif ($c2 == "r") $plain .= '\r';
                    elseif ($c2 == "t") $plain .= '\t';
                    elseif ($c2 == "b") $plain .= '\b';
                    elseif ($c2 == "f") $plain .= '\f';
                    elseif ($c2 >= '0' && $c2 <= '9') {
                        $oct = preg_replace("#[^0-9]#", "", substr($texts[$i], $j + 1, 3));
                        $j += strlen($oct) - 1;
                        $plain .= html_entity_decode("&#".octdec($oct).";");
                    }
                    $j++;
                    break;
    
                default:
                    if ($isHex) {
                        $hex .= $c; 
                    }
                    if ($isPlain) {
                        $plain .= $c; 
                    }
                    break;
                }
            }
            $document .= "\n";
        }
    
        return $document;
    }
    
    /**
     * Extrae texto de un archivo PDF
     * @param string $filename
     * @return string
     */
    function pdf2text($filename)
    {
        // Read the data from pdf file
        $infile = @file_get_contents($filename, FILE_BINARY);
        if (empty($infile)) {
            return ""; 
        }
    
        // Get all text data.
        $transformations = array();
        $texts = array();
    
        // Get the list of all objects.
        preg_match_all("#obj(.*)endobj#ismU", $infile, $objects);
        $objects = @$objects[1];
    
        // Select objects with streams.
        for ($i = 0; $i < count($objects); $i++) {
            $currentObject = $objects[$i];
    
            // Check if an object includes data stream.
            if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) {
                $stream = ltrim($stream[1]);
    
                // Check object parameters and look for text data.
                $options = getObjectOptions($currentObject);
                if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"]))) {
                    continue; 
                }
    
                // So, we have text data. Decode it.
                $data = getDecodedStream($stream, $options);
                if (strlen($data)) {
                    if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) {
                        $textContainers = @$textContainers[1];
                        getDirtyTexts($texts, $textContainers);
                    } else {
                        getCharTransformations($transformations, $data); 
                    }
                }
            }
    
        }
    
        // Analyze text blocks taking into account character transformations and return results.
        return getTextUsingTransformations($texts, $transformations);
    }
}
