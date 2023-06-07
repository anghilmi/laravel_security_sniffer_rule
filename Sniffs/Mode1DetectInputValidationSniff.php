<?php

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class Mode1DetectInputValidationSniff implements Sniff
{

    public function register()
    {
        return array(T_STRING,T_OPEN_PARENTHESIS,T_OPEN_SQUARE_BRACKET);


    }//end register()

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
        $getNextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+1), null, true);
        $getNextNextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+2), null, true);
        //detect validate([ 
        /*
        example:
        $this/request->validate([   
            'title' => 'required|unique:posts|max:255',                
            'body' => 'required',   
            'publish_at' => 'nullable|date',     
            'name' => $request->input('name'),             
        ]);               
        */
        if ($tokens[$stackPtr]['code'] === T_STRING 
            && $tokens[$stackPtr]['content'] === 'validate'
            && $tokens[$getNextToken]['content'] === '('
            && ($tokens[$getNextNextToken]['content'] === '[' || $tokens[$getNextNextToken]['content'] === '$request' )
        ) {         
            $warning = 'Terdeteksi adanya proses input data mode $this/request->validate, baca rekom https://s.id/laravelCS';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

        } 
        
            
        

    }//end process()


}//end class

?>
