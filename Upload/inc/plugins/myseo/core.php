<?php

class Core{
    private $language;
    private $howToInstall;
    public $description;

    public function __construct($language){
        $this->language = $language;
    }

    private function getLanguageID(){
        if($this->language == 'espanol'){
            return 'es';
        }else{
            return 'en';
        }
    }

    private function languageActions(){
        switch($this->getLanguageID()){
            case 'es':
                $this->howToInstall[0] = "#como-instalar-myseo";
                $this->howToInstall[1] = "#instalar-sistema-nofollow";
                $this->howToInstall[2] = "#install-extras-for-plugin-google-seo";
                break;
            default:
                $this->howToInstall[0] = "#how-to-install-myseo";
                $this->howToInstall[1] = "#install-nofollow-system";
                $this->howToInstall[2] = "#instalar-extras-para-el-plugin-google-seo";
        }

        return $this->howToInstall;
    }

    public function getDescription(){
        global $mybb, $db, $lang;

        $this->description = $lang->pluginDescription.'<br/>';

        $this->description .= '<a target="_blank" href="https://github.com/bit-dev/MySEO/blob/master/README.'.$this->getLanguageID().'.md'.$this->howToInstall[0].'">'.$lang->howToInstall.'</a></span>';
        $this->description .= ' | <a target="_blank" href="index.php?module=config&action=change&search=myseo">'.$lang->settingsLink.'</a> |';

        $this->description .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="display: inline;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="FEC8RBQ2DJUCW">
            <input type="hidden" name="os0" value="Donacion 1">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="on0" value="Donaciones">
            <input alt="'.$lang->Cafe.'" title="'.$lang->Cafe.'" style="max-height: 20px; vertical-align: -5px; margin-left: 10px;" type="image" src="'.$mybb->settings['bburl'].'/inc/plugins/myseo/images/donar.'.$this->getLanguageID().'.gif" border="0" name="submit" alt="Donar">
            <img border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
        </form>';

        return $this->description;
    }
}
