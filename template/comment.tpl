<p>
  <img id="captcha" src="{$PHPCAPTCHA_PATH}renderimage.php?hash={$captchahash}" alt="{'PHP Captcha for Piwigo'|translate}" title="{'PHP Captcha for Piwigo'|translate}">
  {if $captcha.allowad eq true}
    <br />
    <small><a href="https://github.com/pstimpel/phpcaptchapiwigo" target="_blank">{'PHP Captcha for Piwigo'|translate}</a></small>
  {/if}
</p>

<p><label for="captcha_code">{'Enter code'|translate} :</label></p>
<p>
  <input type="text" id="captcha_code" name="captcha_code" maxlength="{$captcha.stringlength}" value="">
  <input type="hidden" name="captcha_hash" value="{$captchahash}">
</p>