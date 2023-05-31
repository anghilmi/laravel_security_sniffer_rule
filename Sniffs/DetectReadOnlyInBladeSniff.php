<?php

namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;


use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;




class DetectReadOnlyInBladeSniff implements Sniff
{

    

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_INLINE_HTML);


    }//end register()


    //mendeteksi adakah penulisan id raw yang akan tampil pada URL browser
    //pola : mengikuti yang ada pada laravel doc tentang raw SQL query
    /**
     * cek ekstensi blade
     * cek pola $data->id)
     * 
     */

    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        // ^(?![0-9._])(?!.*[0-9._]$)(?!.*\d_)(?!.*_\d)[a-zA-Z0-9_]+$
        // /[^A-Za-z0-9\-]/

        return preg_replace('/[^A-Za-z0-9_\-]/', '', $string); // Removes special chars except _
     }
    
    // $fullstring = 'this is my [tag]dog[/tag]';
    // $parsed = get_string_between($fullstring, '[tag]', '[/tag]');

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
        $fileName  = $phpcsFile->getFilename();
        $isBladeExtension = substr($fileName, -10); //check .blade.php from end of string
        

        $getVarString = self::get_string_between($tokens[$stackPtr]['content'],'$','->');
        $getAfterVarString = substr($tokens[$stackPtr]['content'],strpos($tokens[$stackPtr]['content'],'->')+2);
        $getAfterVarString = str_replace('"','',$getAfterVarString);
        $fixGetAfterVarString = strtok(self::clean($getAfterVarString),'-');
        

        if ($tokens[$stackPtr]['code'] === T_INLINE_HTML &&
            $isBladeExtension === '.blade.php' &&
            stripos($tokens[$stackPtr]['content'],'readonly') !== FALSE 
            
            // stripos($fixGetAfterVarString,'id') !== FALSE

        ){
            
           
            //proses yang afterstring berbau id dan bukan berbau form dalam baris kodenya
        
            $warning = 'readonly detected in blade; elemen data yang readonly tidak boleh diproses di bagian controller, rentan diubah dengan inspect element,'.' read more: https://laravuln.id#bladeLogic';
            $data  = array(trim($tokens[$stackPtr]['content'])); 
            $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

        
        }

    }//end process()


}//end class

?>