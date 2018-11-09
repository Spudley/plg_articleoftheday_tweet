<?php
/**
 * @package   Tweet
 * @type      Plugin (Articleoftheday)
 * @version   1.0.0
 * @author    Simon Champion
 * @copyright (C) 2018 Simon Champion
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

use Abraham\TwitterOAuth\TwitterOAuth;

require "twitteroauth/autoload.php";

defined('_JEXEC') or die;

class plgArticleofthedayTweet extends JPlugin
{
    const TWEET_LENGTH = 280;

    public function onNewArticleOfTheDay($articleID, $fieldName)
    {
        $connection = $this->connect();
        $statues = $connection->post("statuses/update", ["status" => $this->generateTweet($articleID)]);
    }

    private function connect()
    {
        $consumer_key = $this->params->get('consumer_key', '');
        $consumer_secret = $this->params->get('consumer_secret', '');
        $access_token = $this->params->get('access_token', '');
        $access_secret = $this->params->get('access_secret', '');

        if (!$consumer_key || !$consumer_secret || !$access_token || !$access_secret) {
            throw new Exception('Invalid AOTD/TW config.');
        }

        return new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);
    }

    private function generateTweet($articleID)
    {
        $template = $this->params->get('tweet_template', "{content}\n{tags}\n{url}");

        $content = $this->cleanContent($this->loadContent($articleID));
        $hashtags = $this->hashtags();
        $url = $this->getURL($articleID);

        $output = str_replace(['{tags}', '{url}'], [$hashtags, $url], $template);

        $remainder = self::TWEET_LENGTH - strlen($output);
        if (strlen($content) > $remainder) {
            $content = substr($content, 0, $remainder - 1) . '…';
        }
        return str_replace('{content}', $content, $output);
    }

    private function hashtags()
    {
        $hashtags = explode(' ',$this->params->get('hashtag_roulette', ''));
        $hashtagMax = (int)$this->params->get('hashtag_max', 1);

        if (!count($hashtags) || $hashtagMax < 1) {
            return '';
        }

        shuffle($hashtags);
        return implode(' ', array_slice($hashtags, 0, mt_rand(1, $hashtagMax)));
    }

    private function getURL($articleID)
    {
        $article = $this->loadArticle($articleID);
        return JURI::root().JRoute::_(ContentHelperRoute::getArticleRoute($articleID, $article->catid));
    }

    private function cleanContent($content)
    {
        $content = preg_replace('~(<br ?/?>|\s)+~', "\n", $content);
        $output = strip_tags($content);
        return $output;
    }

    private function loadContent($articleID)
    {
        switch ($this->params->get('content_from', 'none')) {
            case 'title' :  return $this->contentFromTitle($articleID);
            case 'body' :   return $this->contentFromBody($articleID);
            case 'field' :  return $this->contentFromField($articleID);
            default :       return '';
        }
    }

    private function contentFromTitle($articleID)
    {
        $article = $this->loadArticle($articleID);
        return $article->title;
    }

    private function contentFromBody($articleID)
    {
        $article = $this->loadArticle($articleID);
        return $article->introtext;
    }

    private function contentFromField($articleID)
    {
        $contentField = $this->params->get('content_field', '');
        $field = $this->loadFieldForArticle($articleID, $contentField);
        return $field->value;
    }

    private function loadArticle($articleID) {
        static $obj = null;
        if ($obj && $obj->id == $articleID) {
            return $obj;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['a.id','a.catid','a.title','a.alias','a.introtext','a.fulltext']);
        $query->from($db->quoteName('#__content').' as a');
        $query->where('a.id = '.$db->quote($articleID));

        $db->setQuery($query);
        $obj = $db->loadObject();
        return $obj;
    }

    private function loadFieldForArticle($articleID, $fieldName) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['f.label','f.params','f.fieldparams','v.value']);
        $query->from($db->quoteName('#__fields').' as f');
        $query->join('inner', $db->quoteName('#__fields_values').' AS v ON f.id = v.field_id');
        $query->where('f.context = "com_content.article"');
        $query->where('f.state = 1');
        $query->where('v.item_id = '.$db->quote($articleID));
        $query->where('f.name = '.$db->quote($fieldName));
        $query->order('f.ordering');

        $db->setQuery($query);
        return $db->loadObject();
    }

}

