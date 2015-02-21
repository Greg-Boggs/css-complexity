---
layout: default
---
<?php  session_start(); ?>
<div class="home">
    <?php
      if (isset($_GET['error']) && $_GET['error'] == 'url' || $_GET['error'] == 'content' || $_GET['error'] == 'captcha') {
        print ('<p>There was a problem with the ' . $_GET['error'] . '.</p>');
      }
    ?>
    <form action="scan.php" method="get">
        <fieldset>
            <legend>How complex is your CSS?</legend>
            <div id="divTxt">
                <div>
                    <label for="url">Target:</label>
                    <input class="input" name="url" id="url" placeholder="example.com" />
                </div>
            </div>
            <div>
                <label for="captcha"><img src="lib/captcha.php" alt="captcha image"></label>
                <input class="input" type="text" name="captcha" maxlength="6" id="captcha" placeholder="Retype the captcha" />
            </div>
            <div>
                <input type="submit" class="submit" name="submit" id="submit" value="Scan CSS" />
            </div>
        </fieldset>
    </form>
</div>
