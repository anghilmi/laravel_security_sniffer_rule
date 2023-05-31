
<?php

//namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;


use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;




class DetectUploadFileProcess implements Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_VARIABLE, T_OBJECT_OPERATOR, T_STRING, T_OPEN_PARENTHESIS);


    }//end register()


    //mendeteksi adakah proses upload file pada suatu kode program
    //pola : T_VARIABLE->file( atau T_VARIABLE->image->
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
         
        if ($tokens[$stackPtr]['code'] === T_OBJECT_OPERATOR ) {
            // Find the next non-whitespace token.
            $getTokenBeforeObjectOperator = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr-1), null, true);
            $getTokenAfterObjectOperator = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+1),null, true);
            $getTokenAfterString = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+2),null, true);
 
            if (
                (
                    $tokens[$getTokenBeforeObjectOperator]['code'] === T_VARIABLE
                    && $tokens[$getTokenAfterObjectOperator]['content'] === 'file'
                    && $tokens[$getTokenAfterString]['code'] === T_OPEN_PARENTHESIS
                ) || 
                (
                    $tokens[$getTokenBeforeObjectOperator]['code'] === T_VARIABLE
                    && $tokens[$getTokenAfterObjectOperator]['content'] === 'image'
                    && $tokens[$getTokenAfterString]['code'] === T_OBJECT_OPERATOR
                ) 
                ){
                

                $warning = 'terdeteksi kode untuk upload file/gambar, perhatikan hal berikut: doc.laravuln.id';//.$tokens[$getTokenAfterDoubleArrow]['code'];
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

            }
        }

    }//end process()


}//end class

?>