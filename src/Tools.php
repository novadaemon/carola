<?php
class Tools
{

    public static function removeWearAccent($word) {
            $vocales = array('a','e','i','o','u');
            
            $word = str_replace(array('ñ', 'ç'), array('n','c'), $word);
            $word = str_replace(array('ä','ë','ï','ö','ü'), $vocales, $word);
            $word = str_replace(array('à','è','ì','ò','ù'), $vocales, $word);
            return str_replace(array('â','ê','î','ô','û'), $vocales, $word);
    }
    

    public static function toAscii($str) {
       //setlocale(LC_ALL, 'en_US.UTF8');
        $clean = mb_strtolower(trim($str), 'UTF-8');
        $clean = Tools::removeAccent($clean);
        $clean = Tools::removeWearAccent($clean);
        
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
	   $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
    
	   $clean = trim($clean, '-');
	   $clean = preg_replace("/[\/_| -]+/", '-', $clean);
       
        if(is_numeric($clean))
            $clean = $clean.'_'; 
	return $clean;
    }

    public static function fromSlug($str){
        setlocale(LC_ALL, 'en_US.UTF8');
        $str = preg_replace("/[\/_| -]+/", ' ', $str);
        
        return $str;
    }

    public static function removeStopWordsFromArray($words)
    {
        
        
      $en_stop_words = array(
        'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 
        'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 
        'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 
        'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 
        'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 
        'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 
        'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 
        'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 
        'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 
        'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 
        'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 
        'than', 'too', 'very',
      );
      $es_stop_words =  array(
        'yo', 'mi', 'mismo', 'nosotros', 'nuestro', 'nuestros', 'mismos', 'tu','usted', 'ustedes',
        'tuyo', 'tuyos','suyo', 'suyos', 
        'el','ellos', 'su', 'del', 'ella', 'ellas', 
        'esto', 'estos', 'eso', 'los', 'les', 'su', 'sus', 'se', 
        'que', 'cuál', 'quien', 'este', 'ese', 'aquellos', 'soy', 'es', 'son','estoy','estan', 
        'fue', 'fueron', 'ser', 'sido', 'siendo', 'tener', 'tiene', 'tuvo', 'teniendo', 'hacer', 'hace', 
        'hizo', 'haciendo', 'un','una', 'el','la', 'y', 'pero', 'si', 'ó','o' ,'por', 'como', 'hasta', 
        'mientras', 'de', 'en', 'por', 'para', 'con', 'acerca', 'contra', 'entre', 'en','dentro', 
        'traves', 'durante', 'antes', 'despues', 'encima', 'debajo', 'a', 'desde', 'arriba', 'abajo', 
        'fuera', 'sin', 'acabado', 'debajo', 'contra', 'ademas', 'entonces', 'una', 'aquí', 
        'ahí', 'cuando', 'donde', 'por','qué', 'como', 'todo', 'algún', 'ambos', 'cada', 'pocos', 'más', 
        'muchos', 'otros', 'pocos', 'tal', 'no', 'ni',  'solo', 'propio', 'mismo', 'entonces', 
        'tambien', 'mucho',
      );

      return array_diff($words, $es_stop_words);
    }

//**************STEM Functions*************///
    public static function is_vowel($c) {
            return ($c == 'a' || $c == 'e' || $c == 'i' || $c == 'o' || $c == 'u' || $c == 'á' || $c == 'é' ||
                    $c == 'í' || $c == 'ó' || $c == 'ú');
    }

    public static function getNextVowelPos($word, $start = 0) {
            $len = strlen($word);
            for ($i = $start; $i < $len; $i++)
                    if (Tools::is_vowel($word[$i])) return $i;
            return $len;
    }

    public static function getNextConsonantPos($word, $start = 0) {
            $len = strlen($word);
            for ($i = $start; $i < $len; $i++)
                    if (!Tools::is_vowel($word[$i])) return $i;
            return $len;		
    }

    public static function endsin($word, $suffix) {
            if (strlen($word) < strlen($suffix)) return false;
            return (substr($word, -strlen($suffix)) == $suffix);
    }

    public static function endsinArr($word, $suffixes) {
            foreach ($suffixes as $suff) {
                    if (Tools::endsin($word, $suff)) return $suff;
            }
            return '';
    }

    public static function removeAccent($word) {
            return str_replace(array('á','é','í','ó','ú'), array('a','e','i','o','u'), $word);
    }

    public static function stemm($word) {
            $len = strlen($word);
            if ($len <=2) return $word;

            $word = strtolower($word);

            $r1 = $r2 = $rv = $len;
            //R1 is the region after the first non-vowel following a vowel, or is the null region at the end of the word if there is no such non-vowel.
            for ($i = 0; $i < ($len-1) && $r1 == $len; $i++) {
                    if (Tools::is_vowel($word[$i]) && !Tools::is_vowel($word[$i+1])) { 
                                    $r1 = $i+2;
                    }
            }

            //R2 is the region after the first non-vowel following a vowel in R1, or is the null region at the end of the word if there is no such non-vowel. 
            for ($i = $r1; $i < ($len -1) && $r2 == $len; $i++) {
                    if (Tools::is_vowel($word[$i]) && !Tools::is_vowel($word[$i+1])) { 
                            $r2 = $i+2; 
                    }
            }

            if ($len > 3) {
                    if(!Tools::is_vowel($word[1])) {
                            // If the second letter is a consonant, RV is the region after the next following vowel
                            $rv = Tools::getNextVowelPos($word, 2) +1;
                    } elseif (Tools::is_vowel($word[0]) && Tools::is_vowel($word[1])) { 
                            // or if the first two letters are vowels, RV is the region after the next consonant
                            $rv = Tools::getNextConsonantPos($word, 2) + 1;
                    } else {
                            //otherwise (consonant-vowel case) RV is the region after the third letter. But RV is the end of the word if these positions cannot be found.
                            $rv = 3;
                    }
            }

            $r1_txt = substr($word,$r1);
            $r2_txt = substr($word,$r2);
            $rv_txt = substr($word,$rv);

            $word_orig = $word;

            // Step 0: Attached pronoun
            $pronoun_suf = array('me', 'se', 'sela', 'selo', 'selas', 'selos', 'la', 'le', 'lo', 'las', 'les', 'los', 'nos');	
            $pronoun_suf_pre1 = array('éndo', 'ándo', 'ár', 'ér', 'ír');	
            $pronoun_suf_pre2 = array('ando', 'iendo', 'ar', 'er', 'ir');
            $suf = Tools::endsinArr($word, $pronoun_suf);
            if ($suf != '') {
                    $pre_suff = Tools::endsinArr(substr($rv_txt,0,-strlen($suf)),$pronoun_suf_pre1);
                    if ($pre_suff != '') {
                            $word = Tools::removeAccent(substr($word,0,-strlen($suf)));
                    } else {
                            $pre_suff = Tools::endsinArr(substr($rv_txt,0,-strlen($suf)),$pronoun_suf_pre2);
                            if ($pre_suff != '' ||
                                    (Tools::endsin($word, 'yendo' ) && 
                                    (substr($word, -strlen($suf)-6,1) == 'u'))) {
                                    $word = substr($word,0,-strlen($suf));
                            }
                    }
            }

            if ($word != $word_orig) {
                    $r1_txt = substr($word,$r1);
                    $r2_txt = substr($word,$r2);
                    $rv_txt = substr($word,$rv);
            }
            $word_after0 = $word;

            if (($suf = Tools::endsinArr($r2_txt, array('anza', 'anzas', 'ico', 'ica', 'icos', 'icas', 'ismo', 'ismos', 'able', 'ables', 'ible', 'ibles', 'ista', 'istas', 'oso', 'osa', 'osos', 'osas', 'amiento', 'amientos', 'imiento', 'imientos'))) != '') {
                    $word = substr($word,0, -strlen($suf));	
            } elseif (($suf = Tools::endsinArr($r2_txt, array('icadora', 'icador', 'icación', 'icadoras', 'icadores', 'icaciones', 'icante', 'icantes', 'icancia', 'icancias', 'adora', 'ador', 'ación', 'adoras', 'adores', 'aciones', 'ante', 'antes', 'ancia', 'ancias'))) != '') {
                    $word = substr($word,0, -strlen($suf));	
            } elseif (($suf = Tools::endsinArr($r2_txt, array('logía', 'logías'))) != '') {
                    $word = substr($word,0, -strlen($suf)) . 'log';
            } elseif (($suf = Tools::endsinArr($r2_txt, array('ución', 'uciones'))) != '') {
                    $word = substr($word,0, -strlen($suf)) . 'u';
            } elseif (($suf = Tools::endsinArr($r2_txt, array('encia', 'encias'))) != '') {
                    $word = substr($word,0, -strlen($suf)) . 'ente';
            } elseif (($suf = Tools::endsinArr($r2_txt, array('ativamente', 'ivamente', 'osamente', 'icamente', 'adamente'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            } elseif (($suf = Tools::endsinArr($r1_txt, array('amente'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            } elseif (($suf = Tools::endsinArr($r2_txt, array('antemente', 'ablemente', 'iblemente', 'mente'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            } elseif (($suf = Tools::endsinArr($r2_txt, array('abilidad', 'abilidades', 'icidad', 'icidades', 'ividad', 'ividades', 'idad', 'idades'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            } elseif (($suf = Tools::endsinArr($r2_txt, array('ativa', 'ativo', 'ativas', 'ativos', 'iva', 'ivo', 'ivas', 'ivos'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            }

            if ($word != $word_after0) {
                    $r1_txt = substr($word,$r1);
                    $r2_txt = substr($word,$r2);
                    $rv_txt = substr($word,$rv);
            }
            $word_after1 = $word;

            if ($word_after0 == $word_after1) {
                    // Do step 2a if no ending was removed by step 1. 
                    if (($suf = Tools::endsinArr($rv_txt, array('ya', 'ye', 'yan', 'yen', 'yeron', 'yendo', 'yo', 'yó', 'yas', 'yes', 'yais', 'yamos'))) != '' && (substr($word,-strlen($suf)-1,1) == 'u')) {
                            $word = substr($word,0, -strlen($suf));
                    }

                    if ($word != $word_after1) {
                            $r1_txt = substr($word,$r1);
                            $r2_txt = substr($word,$r2);
                            $rv_txt = substr($word,$rv);
                    }
                    $word_after2a = $word;

                    // Do Step 2b if step 2a was done, but failed to remove a suffix. 
                    if ($word_after2a == $word_after1) {
                            if (($suf = Tools::endsinArr($rv_txt, array('en', 'es', 'éis', 'emos'))) != '') {
                                    $word = substr($word,0, -strlen($suf));
                                    if (Tools::endsin($word, 'gu')) {
                                            $word = substr($word,0,-1);
                                    }
                            } elseif (($suf = Tools::endsinArr($rv_txt, array('arían', 'arías', 'arán', 'arás', 'aríais', 'aría', 'aréis', 'aríamos', 'aremos', 'ará', 'aré', 'erían', 'erías', 'erán', 'erás', 'eríais', 'ería', 'eréis', 'eríamos', 'eremos', 'erá', 'eré', 'irían', 'irías', 'irán', 'irás', 'iríais', 'iría', 'iréis', 'iríamos', 'iremos', 'irá', 'iré', 'aba', 'ada', 'ida', 'ía', 'ara', 'iera', 'ad', 'ed', 'id', 'ase', 'iese', 'aste', 'iste', 'an', 'aban', 'ían', 'aran', 'ieran', 'asen', 'iesen', 'aron', 'ieron', 'ado', 'ido', 'ando', 'iendo', 'ió', 'ar', 'er', 'ir', 'as', 'abas', 'adas', 'idas', 'ías', 'aras', 'ieras', 'ases', 'ieses', 'ís', 'áis', 'abais', 'íais', 'arais', 'ierais', '  aseis', 'ieseis', 'asteis', 'isteis', 'ados', 'idos', 'amos', 'ábamos', 'íamos', 'imos', 'áramos', 'iéramos', 'iésemos', 'ásemos'))) != '') {
                                    $word = substr($word,0, -strlen($suf));
                            }
                    }
            }

            // Always do step 3. 
            $r1_txt = substr($word,$r1);
            $r2_txt = substr($word,$r2);
            $rv_txt = substr($word,$rv);

            if (($suf = Tools::endsinArr($rv_txt, array('os', 'a', 'o', 'á', 'í', 'ó'))) != '') {
                    $word = substr($word,0, -strlen($suf));
            } elseif (($suf = Tools::endsinArr($rv_txt ,array('e','é'))) != '') {
                    $word = substr($word,0,-1);
                    $rv_txt = substr($word,$rv);
                    if (Tools::endsin($rv_txt,'u') && Tools::endsin($word,'gu')) {
                            $word = substr($word,0,-1);
                    }
            }

            return Tools::removeAccent($word);
    }

    /**
     * stem a Phrase
     *
     * @param string $phrase
     * @return array $stemmed_words
     * 
     * Array of stem words
     */
    public static function stemPhrase($phrase)
  {
    // split into words
    $words = str_word_count(strtolower($phrase), 1);

    // ignore stop words
    $words = Tools::removeStopWordsFromArray($words);

    // stem words
    $stemmed_words = array();
    foreach ($words as $word)
    {
      // ignore 1 and 2 letter words
      if (strlen($word) <= 2)
      {
        continue;
      }

      $stemmed_words[] = Tools::stemm($word, true);
    }

    return $stemmed_words;
  }
  
  /**
     * get Words
     *
     * @param string $title, string $body, string $tags
     * @return array $words
     * 
     * Array of stem words
     */
     public static function getWords($title, $body, $tags = null)
    {
      // body
      $raw_text =  str_repeat(' '.strip_tags(html_entity_decode($body)), 1);

      // title
      $raw_text .= str_repeat(' '.$title, 3);

      // title and body stemming
      $stemmed_words = Tools::stemPhrase($raw_text);

      // unique words with weight
      $words = array_count_values($stemmed_words);

      // add tags
//      $max = 0;
//      foreach ($tags as $tag => $count)
//      {
//        if (!$max)
//        {
//          $max = $count;
//        }
//
//        $stemmed_tag = Tools::stemm($tag);
//
//        if (!isset($words[$stemmed_tag]))
//        {
//          $words[$stemmed_tag] = 0;
//        }
//        $words[$stemmed_tag] += ceil(($count / $max) * 3);
//      }

      return $words;
    }
}