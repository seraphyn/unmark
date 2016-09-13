<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Hook which loads language files for localized controllers
 * @author kip9
 *
 */
class Unmark_Localization
{
    private $CI   = null;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Load translations if controller is localized
     * TODO Think about using locales the other way that doesn't require
     * locale installation on server:
     * @see http://stackoverflow.com/questions/15541747/use-php-gettext-without-having-to-install-locales
     */
    public function loadLanguage()
    {
        if(!isset($this->CI->localized) || $this->CI->localized == true ){
            // Trying to set locale
            $lang = isset($this->CI->selected_language) ? $this->CI->selected_language.'.UTF-8' : 'C';
            $setLocaleOut = setlocale(LC_ALL, $lang);
            if($setLocaleOut !== false){
                // Locale setting success
                $lang_path = FCPATH.APPPATH.'language/locales';
                if ( function_exists('bindtextdomain') ) : // Check for Bindtext Added 1.7.1
                  bindtextdomain('unmark', $lang_path);
                  textdomain('unmark');
                else :
                  log_message('DEBUG', 'Setting language failed due to gettext not being compliled with PHP. Going with default.');
                endif;
            } else {
                // Locale setting failed - report error
                $errMsg = 'Setting language to '.$lang.' failed - no such locale';
                log_message('DEBUG', $errMsg);
                $this->CI->exceptional->createTrace(E_WARNING, $errMsg, __FILE__, __LINE__, array(
                    'language'  => $lang
                ));
            }
        }
    }
}

/* Added 1.7.1 - Still needs to be completely ripped out. */
if ( !function_exists('bindtextdomain') && !function_exists('_') ) :
  function _($v){
    return $v;
  }
endif;
