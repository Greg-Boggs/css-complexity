---
layout: default
---

<div class="home">
    <form action="scan.php" method="get">
        <fieldset>
            <legend>CSS Complexity Calculator</legend>
            <div id="divTxt">
                <div>
                    <label for="url">Website:</label>
                    <input class="input" name="url" id="url" />
                </div>
            </div>
            <div>
                <label for="email">Email:</label>
                <input class="input" name="email" id="email" />
            </div>
            <div>
                <label for="captcha"><img src="lib/captcha.php" alt="captcha image"></label>
                <input class="input" type="text" name="captcha" maxlength="6" id="captcha" />
            </div>
            <div>
                <input type="submit" class="submit" name="submit" id="submit" value="Scan CSS" />
            </div>
        </fieldset>
    </form>

  <h1>Posts</h1>

  <ul class="posts">
    {% for post in site.posts %}
      <li>
        <span class="post-date">{{ post.date | date: "%b %-d, %Y" }}</span>
        <a class="post-link" href="{{ post.url | prepend: site.baseurl }}">{{ post.title }}</a>
      </li>
    {% endfor %}
  </ul>

  <p class="rss-subscribe">subscribe <a href="{{ "/feed.xml" | prepend: site.baseurl }}">via RSS</a></p>

</div>
