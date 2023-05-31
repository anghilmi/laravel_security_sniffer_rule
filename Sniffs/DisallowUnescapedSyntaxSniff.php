
<?php

//namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;


use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;



class DisallowUnescapedSyntaxSniff implements Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_CURLY_BRACKET);


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

    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
   
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens(); 
         
        if (($tokens[$stackPtr]['content'] ==="{") && ($tokens[$stackPtr+1]['content'] === "!") && ($tokens[$stackPtr+2]['content'] ==="!")) { //cek !! setelah ada { 

            // $warning = self::get_string_between($warning,"{!!", "!!}");
            // di atas bisa, tapi mungkin get per line-nya
            // $warning = '{!!'.$warning.'!!}';
            // $warning = 'Found unescaped syntax {!! ... !!}, replace with {{ ... }}'; 
            // $warning = 'line '.$tokens[$stackPtr]['line'];

            //ini jadi
            //$warning = 'ada Found %s '.$tokens[$stackPtr+4]['content'];
            /*
            {!!uu!!} 
            {!! yo !!}
            ada Found { !
            ada Found { yo
            */
            //cari posisi ! penutup !!}

            /*
            <?php {!!!!}{!!uud!!}{!! yoggg->nganukkk !!}     
            ?>  dapet $stackPtr 1,7,14
            */
            $panjangToken = count($tokens); //$phpcsFile->numTokens
            $warning = '';
            $temp = 0;
            for($i = 2; $i < $panjangToken; $i ++) { //$i = 2 adalah masih {!! awal; history: magic number problem, causing error when > than char in the line
                // $temp = $temp + $i;
                $warning = $warning.$tokens[$stackPtr+$i]['content'];
                if(($tokens[$stackPtr+$i]['content']==='!')&&($i !== 1)&&($i !== 2)){ //$i ke-1 dan ke-2 adalah ! masih di bag awal {!! , bukan penutup
                    break;
                }
            }
            // echo $temp;

           
            $warningAkhir = '{!'.$warning.'!}, baca rekom https://s.id/laravelCS';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addWarning($warningAkhir, $stackPtr, 'Found', $data);

           
        }

    }//end process()


}//end class

?>
