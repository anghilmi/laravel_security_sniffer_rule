<?php

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class Mode2DetectInputValidationSniff implements Sniff
{

    public function register() 
    {
        return array(T_FUNCTION, T_RETURN, T_CONSTANT_ENCAPSED_STRING, T_OPEN_SQUARE_BRACKET);


    }//end register()

    
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
         
        if ($tokens[$stackPtr]['code'] === T_FUNCTION ) {
            
            // $getNextOpenSquareBracket = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr+1), null, true);
            // $getNextReturn = $phpcsFile->findNext(T_RETURN, ($getNextOpenSquareBracket));
            
            $functionNamePtr = $phpcsFile->findNext(T_STRING, $stackPtr);
            $functionName = $tokens[$functionNamePtr]['content'];

        
            $startPtr = $tokens[$stackPtr]['scope_opener'];
            $endPtr = $tokens[$stackPtr]['scope_closer'];
            //detect rules() return []
            /*
            example:
            public function rules()       
            {    
                return [ 
                    'nama' > 'required',
                    'email' > 'required|email' 
                ];
            }              
            */
            for ($i = $startPtr + 1; $i < $endPtr; $i++) {
                if ($functionName === 'rules' &&
                    $tokens[$i]['content'] === 'return'  
                    // $tokens[$i + 1]['content'] === ' ' &&
                    // $phpcsFile->findNext(T_OPEN_SQUARE_BRACKET, ($i))
                    // $tokens[$i + 2]['content'] === '[' //&&
                    // $tokens[$i + 3]['code'] === 'T_CONSTANT_ENCAPSED_STRING'
                )
            {
               
                $warning = 'Terdeteksi adanya proses input data mode rules() return [], baca rekom https://s.id/laravelCS';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

            }
        }}

    }//end process()


}//end class

?>