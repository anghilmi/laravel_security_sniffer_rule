<?php

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class Mode3DetectInputValidationSniff implements Sniff
{
    
    public function register() 
    {
        return array(T_STRING, T_DOUBLE_COLON);


    }//end register()

 
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        //detect Validator::make()
        /*
        example:
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);            
        */
            
        if ($tokens[$stackPtr]['code'] === T_STRING
            && $tokens[$stackPtr]['content'] === 'Validator'
            && $tokens[$stackPtr+1]['code'] === T_DOUBLE_COLON 
            && $tokens[$stackPtr+2]['content'] === 'make'       
        ) {
            $warning = 'Terdeteksi adanya proses validasi input mode Validator::make(), baca rekom https://s.id/laravelCS';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);
        }
           

           

    }//end process()


}//end class

?>
