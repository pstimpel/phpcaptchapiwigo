
</tr>

<tr>
  <td class="title">
    <label for="captcha_code">{'Code'|translate}</label>
  </td>
  <td>
    <img id="captcha" src="{$PHPCAPTCHA_PATH}renderimage.php?hash={$captchahash}" alt="{'PHP Captcha for Piwigo'|translate}" title="{'PHP Captcha for Piwigo'|translate}">
    {if $captcha.allowad eq true}
      <br />
      <small><a href="https://github.com/pstimpel/phpcaptchapiwigo" target="_blank">{'PHP Captcha for Piwigo'|translate}</a></small>
    {/if}

  </td>

<tr>
  <td class="title">
    <label for="captcha_code">{'Enter code'|translate}</label>
  </td>
  <td>
    <input type="text" name="captcha_code" id="captcha_code" size="{$captcha.stringlength}" maxlength="{$captcha.stringlength}" />
    <input type="hidden" name="captcha_hash" value="{$captchahash}">

  </td>



