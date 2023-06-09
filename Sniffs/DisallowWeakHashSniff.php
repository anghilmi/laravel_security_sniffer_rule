<?php
/**
 * This sniff prohibits the use of Perl style hash comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Your Name <you@domain.net>
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace PHP_CodeSniffer\Standards\MyStandard\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowWeakHashSniff implements Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        // return array(T_COMMENT);
        return array(T_STRING);


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

            $str = $tokens[$stackPtr]['content'];
            $patternMD5 = "/md5/i";
            $patternSHA1 = "/sha1/i";

            if((preg_match($patternMD5, $str)==1)){ //regex
                $warning = 'hash yang digunakan, tidak aman; Found %s';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);
            }

            if((preg_match($patternSHA1, $str)==1)){ //regex
                $warning = 'hash yang digunakan, tidak aman; Found %s, baca rekom https://s.id/laravelCS';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);
            }

            //bisa 2 fungsi seperti di atas, juga bisa seperti di bawah ini
            /*
            if((preg_match($patternMD5, $str)==1) || (preg_match($patternSHA1, $str)==1)){ //regex
                $warning = 'hash yang digunakan, tidak aman; Found %s';
                $data  = array(trim($tokens[$stackPtr]['content']));
                $phpcsFile->addWarning($warning, $stackPtr, 'Found', $data);
            }
            */

           

    }//end process()


}//end class

?>
