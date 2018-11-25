
{combine_css path=$captcha.thispath|cat:'include/colorpicker/colorpicker.css'}
{combine_script id='jquery.colorpicker' load='footer' path=$captcha.thispath|cat:'/include/colorpicker/colorpicker.js'}

{footer_script}
    $('.color-picker')
        .ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
            },
            onChange: function(hsb, hex, rgb, el) {
                $(el).val(hex).trigger('change');
                changeColor(el, hex);
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            }
        })
        .bind('keyup', function() {
            $(this).ColorPickerSetColor(this.value);
            changeColor(this, $(this).val());
        })
        .each(function() {
            changeColor(this, $(this).val());
        });

    function changeColor(target, color) {
        if (color == 'random') {
            color = '808080';
        }
        if (parseInt(color, 16) > 16777215/2) {
            $(target).css('color', '#222');
        }
        else {
            $(target).css('color', '#ddd');
        }
        $(target).css('background', '#'+color)
    }

{/footer_script}

<!-- Show the title of the plugin -->
<div class="titlePage">
    <h2>{'PHP Captcha for Piwigo'|@translate}</h2>
</div>

<div>

    <form method="post" class="properties">

    <h4>{'Captcha content'|translate}</h4>
        <fieldset>
            <label for="stringlength">
                <input type="text" id="stringlength" name="stringlength"  value="{$captcha.settings.stringlength}"  />
                <span>{'Number of characters'|translate} ({'Integer'|translate})</span>
            </label>
            <br />&nbsp;<br />
            <label for="charstouse">
                <input type="text" id="charstouse" name="charstouse"  value="{$captcha.settings.charstouse}"  />
                <span>{'Characters allowed'|translate} ({'String'|translate})</span>
            </label>
            <br />&nbsp;<br />
            <label for="strictlowercase">
                <input type="checkbox" id="strictlowercase" name="strictlowercase"
                       value="1" {if $captcha.settings.strictlowercase eq true}checked{/if} />
                <span>{'Strict lower case'|translate}</span>
            </label>
        </fieldset>


        <h4>{'Captcha colors'|translate}</h4>
        <fieldset class="wp_cbf-admin-colors">
            <span>{'Background color'|translate} ({'Hex Color (RRGGBB, a-f, 0-9)'|translate})</span><br />
            <label for="bgcolor">
                <input type="text" class="color-picker" id="bgcolor" name="bgcolor"
                       value="{$captcha.settings.bgcolor}" maxlength="6" />
            </label>
            <br />&nbsp;<br />


            <span>{'Text color'|translate} ({'Hex Color (RRGGBB, a-f, 0-9)'|translate})</span><br />
            <label for="textcolor">
                <input type="text" class="color-picker" id="textcolor" name="textcolor"
                       value="{$captcha.settings.textcolor}" maxlength="6" />
            </label>
            <br />&nbsp;<br />

            <span>{'Line color'|translate} ({'Hex Color (RRGGBB, a-f, 0-9)'|translate})</span><br />
            <label for="linecolor">
                <input type="text" class="color-picker" id="linecolor" name="linecolor"
                       value="{$captcha.settings.linecolor}" maxlength="6" />
            </label>
        </fieldset>

        <h4>{'Captcha appearance'|translate}</h4>
        <fieldset>
            <label for="sizewidth">
                <input type="text" id="sizewidth" name="sizewidth"  value="{$captcha.settings.sizewidth}"  />
                <span>{'Image width'|translate} ({'Integer'|translate})</span>
            </label>
            <br />&nbsp;<br />

            <label for="sizeheight">
                <input type="text" id="sizeheight" name="sizeheight"  value="{$captcha.settings.sizeheight}"  />
                <span>{'Image height'|translate} ({'Integer'|translate})</span>
            </label>
            <br />&nbsp;<br />

            <label for="fontsize">
                <input type="text" id="fontsize" name="fontsize"  value="{$captcha.settings.fontsize}"  />
                <span>{'Font size'|translate} ({'Integer'|translate})</span>
            </label>
            <br />&nbsp;<br />
            <label for="guestonly">
                <input type="checkbox" id="guestonly" name="guestonly"
                       value="1" {if $captcha.settings.guestonly eq true}checked{/if} />
                <span>{'Only not logged-in users see Captchas'|translate}</span>
            </label>
        </fieldset>

        <h4>{'Captcha use'|translate}</h4>
        <fieldset>
            <label for="picture">
                <input type="checkbox" id="picture" name="picture"
                       value="1" {if $captcha.settings.picture eq true}checked{/if} />
                <span>{'Secure picture pages'|translate}</span>
            </label>
            <br />&nbsp;<br />
            <label for="category">
                <input type="checkbox" id="category" name="category"
                       value="1" {if $captcha.settings.category eq true}checked{/if} />
                <span>{'Secure category pages'|translate}</span>
            </label>
            <br />&nbsp;<br />
            <label for="register">
                <input type="checkbox" id="register" name="register"
                       value="1" {if $captcha.settings.register eq true}checked{/if} />
                <span>{'Secure registration form'|translate}</span>
            </label>
        </fieldset>

        <h4>{'OCR confusion'|translate}</h4>
        <fieldset>
            <label for="numberoflines">
                <input type="text" id="numberoflines" name="numberoflines"
                       value="{$captcha.settings.numberoflines}"  />
                <span>{'Number of lines'|translate} ({'Integer'|translate})</span>
            </label>
            <br />&nbsp;<br />
            <label for="thicknessoflines">
                <input type="text" id="thicknessoflines" name="thicknessoflines"
                       value="{$captcha.settings.thicknessoflines}" />
                <span>{'Thickness of lines'|translate} ({'Integer'|translate})</span>
            </label>
        </fieldset>

        <h4>{'Allow advertisement for Plugin'|translate}</h4>
        <fieldset>
            <label for="allowad">
                <input type="checkbox" id="allowad" name="allowad"
                       value="1" {if $captcha.settings.allowad eq true}checked{/if} />
                <span>{'Allow small advertisement below Captcha image'|translate}</span>
            </label>
        </fieldset>

        <p class="formButtons">
            <input class="submit" type="submit" value="{'Save all changes'|translate}" name="submit">
        </p>

    </form>

    <h4>{'Example captcha'|translate}</h4>

    <p>
        <b></b>
        <img src="{$captcha.webroot}renderimage.php?hash=void" alt="{'PHP Captcha for Piwigo'|translate}" title="{'PHP Captcha for Piwigo'|translate}"/>
        {if $captcha.settings.allowad eq true}
            <br />
            <small><a href="https://github.com/pstimpel/phpcaptchapiwigo" target="_blank">{'PHP Captcha for Piwigo'|translate}</a></small>
        {/if}

    </p>

    <h4>{'Reset settings'|translate}</h4>

    <form method="post" class="properties">

        <input type="hidden"
               name="stringlength" value="{$captcha.presets.stringlength}">
        <input type="hidden"
               name="charstouse" value="{$captcha.presets.charstouse}">
        <input type="hidden"
               name="strictlowercase" value="{$captcha.presets.strictlowercase}">
        <input type="hidden"
               name="bgcolor" value="{$captcha.presets.bgcolor}">
        <input type="hidden"
               name="textcolor" value="{$captcha.presets.textcolor}>">
        <input type="hidden"
               name="linecolor" value="{$captcha.presets.linecolor}">
        <input type="hidden"
               name="sizewidth" value="{$captcha.presets.sizewidth}">
        <input type="hidden"
               name="sizeheight" value="{$captcha.presets.sizeheight}">
        <input type="hidden"
               name="fontsize" value="{$captcha.presets.fontsize}">
        <input type="hidden"
               name="numberoflines" value="{$captcha.presets.numberoflines}">
        <input type="hidden"
               name="thicknessoflines" value="{$captcha.presets.thicknessoflines}">
        <input type="hidden"
               name="picture" value="{$captcha.presets.picture}">
        <input type="hidden"
               name="category" value="{$captcha.presets.category}">
        <input type="hidden"
               name="register" value="{$captcha.presets.register}">

        <p class="formButtons">
            <input class="submit" type="submit" value="{'Set to defaults'|translate}" name="submit">
        </p>

    </form>

</div>
