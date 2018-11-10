Article Of The Day - Tweet
==========================

This is a Joomla plugin for the Article Of The Day module.

This plugin is triggered whenever the Article Of The Day module selects a new article of the day. The plugin will take the article that has just been selected, and post it (or more likely, part of it) to your twitter account.

Article Of The Day allows you to keep your site fresh by surfacing different content every day; this plugin allows you to do the same for your twitter feed, with no manual intervention required.


Version History
----------------

* 1.0.0


Installation
----------------

Important: In order for this plugin to do anything, you will need to have first installed the [Article Of The Day module](https://github.com/Spudley/mod_articleoftheday).

Once that is done, this plugin is a standard Joomla plugin. Installation is via Joomla's extension manager. As with all plugins, remember that it must also be configured and activated after being installed.


Usage
----------------

Before using this plugin, you must set up a developer account with Twitter. Once you have an account, you will need to create an "Application" in your Twitter dev account. And having done that, you will be able to get two sets of credentials. These are your "Consumer API Keys" and your "Access Token". Both have a public and private key, for a total of four keys altogether. All four of these keys will be needed when configuring this plugin.

Once the plugin is installed and you have your Twitter keys, go to the config page for the plugin.

There are two tabs of config paramters. The second tab is for your Twitter keys: You should be able to copy and paste these keys from your Twitter dev account.

The first tab in the config page is to control the behaviour of the plugin, mainly the formatting of the tweets it will generate. Here you will be able to configure the following parameters:

* *Template*

    This allows you to specify the format of the tweets that the system will send. It should include some or all of  the following markers:

    - `{content}`: This will be replaced with the content from your site (see 'Content From' below).
    - `{tags}`: This will be replaced with the generated hash tags (see 'Hashtags' below).
    - `{url}`: This will be replaced by the URL for the content that has been selected as Article Of The Day.

    Any other text in the template will be output exactly as is in the final tweets.

* *Content From*

    This specifies what content will be used to generate the tweet. This can be:

    - None: This means that the tweet will not include any content. You may use this for example if you prefer your tweet only to include the URL of your page without giving away any of the content.
    - Article Title: This will put the article's title into the `{content}` section of the tweet.
    - Article Body: This will put as much of the article's body text into the tweet as possible.
    - Field: Selecting this option will present an addtional field, where you can enter the name of a data field that is associated with your Joomla content.

    Note that for all of these options, the content may end up being truncated due to Twitter's constrained tweet length. Obviously, the full article body is more likely to be truncated than the title, but it will truncate any of them as necessary.

    Content will have HTML tags and excess white space stripped. `<br>` tags will be converted to a single line feed.

* Hashtags and Max Hashtags

    This allows you to specify some hashtags that will be used in your tweets, and to rotate them randomly. This allows you to inject a bit of variety into your tweets while still having them automated.

    For example, you could specify Hashtags as `#tagA #tagB #tagC`, and Max Hashtags as 2. The plugin will then pick two of your three tags at random each time it runs.


Motivation
----------------

Having written the Article Of The Day module, I wanted to use the selected articles to push people to my site. Tweeting about them was an obvious way of doing that, but I didn't want to be manually copy+pasting a new tweet every day, so I wrote this plugin to help me to automate it.


Todo List and Known Issues
--------------------------

* It's possible that users of this plugin may want more flexibility in formatting the tweets; eg to include the title and the body content, or to include data from more than one field, etc. The constrained length of a tweet makes this flexibility less useful than it sounds, but if anyone does want this, please feel free to ask or to submit a PR with suitable changes.

* Hashtags could be loaded from the article's Joomla tags, to allow them to be more specific to the content.

* Similar plugins could be written for other social media APIs such as Facebook. I'm open to suggestions, and also happy if anyone wants to write one for themselves (instructions for writing a plugin for Article Of The Day module can be found in the module's documentation).

* It would be preferable to use Composer to pull in the TwitterOAuth library rather than bundling it. However Joomla doesn't make that easy right now.

* If the tweet fails to send, the end user whose visit triggered the article of the day to be updated will get an error page instead of the normal site front page.


License
----------------
As with all Joomla extensions and Joomla itself, this plugin is licensed under the GPL, specifically in this case, GPLv2. The full license document should have been included with the source code.

The plugin uses and includes a third party PHP library called [TwitterOAuth](https://github.com/abraham/twitteroauth), which is licensed under MIT license.

