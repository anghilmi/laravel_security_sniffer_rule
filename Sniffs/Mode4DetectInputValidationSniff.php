<?php

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class Mode4DetectInputValidationSniff implements Sniff
{

    public function register()
    {
        return array(T_STRING,T_OPEN_PARENTHESIS,T_OPEN_SQUARE_BRACKET);


    }//end register()

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
        //count validate([ or Validator::make
        /*
        example:
        $this/request->validate([   
            'title' => 'required|unique:posts|max:255',                
            'body' => 'required',   
            'publish_at' => 'nullable|date',     
            'name' => $request->input('name'),             
        ]);               

        $validator = Validator::make($data, [
            'has_appointment' => 'required|boolean',
            'appointment_date' => 'exclude_if:has_appointment,false|required|date',
            'doctor_name' => 'exclude_if:has_appointment,false|required|string',
        ]);
        */            
        $totalToken = $phpcsFile->numTokens;

            $counterValidate = 0;
            for($i = 0; $i < $totalToken; $i ++) { 
         
                if( ($tokens[$i]['content']==='validate' || $tokens[$i]['content']==='Validator')
                    && $tokens[$i+1]['content'] === '('
                    && ($tokens[$i+2]['content'] === '[' || $tokens[$i+2]['content'] === '$request' )
                ){ 
                    $counterValidate = $counterValidate + 1;
                }
            }
        //detect controllerName::create() 
        /*
        example:
        $user = User::create([                   
                'username'          => $request->username,
                'email'             => $request->email,
                'password'          => bcrypt($request->password)
            ])->assignRole('buyer');           
        */  
        if ($counterValidate === 0 //di file, tidak terdeteksi adanya proses validasi
            && $tokens[$stackPtr]['code'] === T_STRING 
            && $tokens[$stackPtr]['content'] === 'create'            
            && $tokens[$stackPtr+1]['content'] === '('
            && ($tokens[$stackPtr-1]['content'] === '::')
        ) {         
            $warning = 'Terdeteksi adanya proses input data mode '.$tokens[$stackPtr-2]['content'].'::create(), baca rekom https://s.id/laravelCS';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);

        } 
        
            
        

    }//end process()


}//end class

?>