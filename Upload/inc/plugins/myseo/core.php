<?php

class Core
{
    private $language;
    private $howToInstall;
    public $description;

    public function getLanguageID()
    {
        global $mybb;
        if ($mybb->settings['bblanguage'] == 'espanol') {
            return 'es';
        } else {
            return 'en';
        }
    }

    public function getDescription()
    {
        global $mybb, $db, $lang;

        $this->description = $lang->pluginDescription.'<br/>';

        $this->description .= '<a target="_blank" href="https://github.com/bit-dev/MySEO/">'.$lang->howToInstall.'</a></span>';
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
