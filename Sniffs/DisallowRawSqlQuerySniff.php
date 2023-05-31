<?php

//namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;


use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;




class DisallowRawSqlQuerySniff implements Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_STRING, T_DOUBLE_COLON, T_OPEN_PARENTHESIS, T_CONSTANT_ENCAPSED_STRING);


    }//end register()


    //mendeteksi adakah penulisan SQL raw pada kode program
    //pola : mengikuti yang ada pada laravel doc tentang raw SQL query
    /**
     * DB::raw
     * selectRaw(''
     * whereRaw(''
     * havingRaw(''
     * orderByRaw(''
     * groupByRaw(''
     * pola 1: string double collon string raw
     * pola 2: string content list raw open paranthesis
     * 
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
         
        if ($tokens[$stackPtr]['code'] === T_STRING)
         {
            // Find the next non-whitespace token.
            $getTokenAfterString = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+1), null, true);
            $getTokenAfterAfterString = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+2),null, true);
            
 
            if (
                (
                    $tokens[$stackPtr]['content'] === 'DB'          
                    && $tokens[$getTokenAfterString]['code'] === T_DOUBLE_COLON
                    && $tokens[$getTokenAfterAfterString]['content'] === 'raw'
                    
                ) || 
                (
                    (
                        $tokens[$stackPtr]['content'] === 'selectRaw' ||
                        $tokens[$stackPtr]['content'] === 'whereRaw' ||
                        $tokens[$stackPtr]['content'] === 'havingRaw' ||
                        $tokens[$stackPtr]['content'] === 'orderByRaw' ||
                        $tokens[$stackPtr]['content'] === 'groupByRaw' 
                    ) 
                    // T_OPEN_PARENTHESIS, T_CONSTANT_ENCAPSED_STRING
                    && $tokens[$getTokenAfterString]['code'] === T_OPEN_PARENTHESIS
                    && $tokens[$getTokenAfterAfterString]['code'] === T_CONSTANT_ENCAPSED_STRING
                    
                ) 
                ){
                

                $warning = 'terdeteksi adanya syntax SQL raw, baca rekom https://s.id/laravelCS';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

            }
        }

    }//end process()


}//end class

?>
