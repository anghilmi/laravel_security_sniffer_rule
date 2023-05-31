
<?php

//namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;


use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;




class DetectWeakValidationSniff implements Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_CONSTANT_ENCAPSED_STRING, T_VARIABLE);


    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The current file being checked.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */

      
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
         
        if ($tokens[$stackPtr]['code'] === T_CONSTANT_ENCAPSED_STRING ) {
            // Find the next non-whitespace token.
            $getPrevOpenSquareBracket = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr-1), null, true);
            $getNextDoubleArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, ($getPrevOpenSquareBracket + 2));
            $getTokenAfterDoubleArrow = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+3),null, true);
 
            if ($tokens[$getPrevOpenSquareBracket]['content'] === '[' 
                && $tokens[$getNextDoubleArrow]['code'] === T_DOUBLE_ARROW
                && $tokens[$getTokenAfterDoubleArrow]['code'] !== T_VARIABLE //bukan array validasi, soalnya ada juga semacam 'name' => $request->input('name')  // 317 (kalau di register tidak diinput, pakai kode ini) //T_VARIABLE
            ) {
                

                $warning = 'cek apakah validasi input sudah baik? cek di doc laravel';//.$tokens[$getTokenAfterDoubleArrow]['code'];
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

            }
        }

    }//end process()


}//end class

?>