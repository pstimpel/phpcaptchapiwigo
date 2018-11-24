
<label for"captcha_code">{'Code'|translate}</label>
  </span>
  <img id="captcha" src="{$PHPCAPTCHA_PATH}renderimage.php?hash={$captchahash}" alt="PHP CAPTCHA for Piwigo">
  {if $captcha.allowad eq true}
    <br />
    <small><a href="https://github.com/pstimpel/phpcaptchapiwigo" target="_blank">PHP Captcha for Piwigo</a></small>
  {/if}
</li>
<li>
  <span class="property"><label for"captcha_code">{'Enter code'|translate}</label></span>
  <input type="text" id="captcha_code" name="captcha_code" maxlength="{$captcha.stringlength}" value="">
  <input type="hidden" name="captcha_hash" value="{$captchahash}">
</li>
<li>
  <span class="property">