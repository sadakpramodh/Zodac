<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
/*session_check();
check_email_verified($_SESSION["email_id"]);*/
?>

		<ul class="list-group">
		<?php	
		$a= file_get_contents("http://127.0.0.1/readytoupload/twitter.php?user_timeline");
		print_r($a);
$response = $a;

$hashtag_link_pattern = '<a href="http://twitter.com/search?q=%%23%s&src=hash" rel="nofollow" target="_blank">#%s</a>';
$url_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';
$user_mention_link_pattern = '<a href="http://twitter.com/%s" rel="nofollow" target="_blank" title="%s">@%s</a>';
$media_link_pattern = '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';

foreach($response as $tweet)
{
  echo "<li class=\"list-group-item\">
		<a href=\"{$tweet->user->profile_image_url}\" class=\"pull-left\">
			<img alt=\"{$tweet->user->screen_name}\" class=\"img-circle\" src=\"{$tweet->user->profile_image_url}\">
		</a>
		<a class=\"text-info\" href=\"{$tweet->user->profile_image_url}\">@{$tweet->user->screen_name}</a><p>";
  $text = $tweet->text;
    $entity_holder = array();
  foreach($tweet->entities->hashtags as $hashtag)
  {
    $entity = new stdclass();
    $entity->start = $hashtag->indices[0];
    $entity->end = $hashtag->indices[1];
    $entity->length = $hashtag->indices[1] - $hashtag->indices[0];
    $entity->replace = sprintf($hashtag_link_pattern, strtolower($hashtag->text), $hashtag->text);
    
    $entity_holder[$entity->start] = $entity;
  }
  
  foreach($tweet->entities->urls as $url)
  {
    $entity = new stdclass();
    $entity->start = $url->indices[0];
    $entity->end = $url->indices[1];
    $entity->length = $url->indices[1] - $url->indices[0];
    $entity->replace = sprintf($url_link_pattern, $url->url, $url->expanded_url, $url->display_url);
    
    $entity_holder[$entity->start] = $entity;
  }
  
  foreach($tweet->entities->user_mentions as $user_mention)
  {
    $entity = new stdclass();
    $entity->start = $user_mention->indices[0];
    $entity->end = $user_mention->indices[1];
    $entity->length = $user_mention->indices[1] - $user_mention->indices[0];
    $entity->replace = sprintf($user_mention_link_pattern, strtolower($user_mention->screen_name), $user_mention->name, $user_mention->screen_name);
    
    $entity_holder[$entity->start] = $entity;
  }
  if(isset($tweet->entities->media)){
  foreach($tweet->entities->media as $media)
  {
    $entity = new stdclass();
    $entity->start = $media->indices[0];
    $entity->end = $media->indices[1];
    $entity->length = $media->indices[1] - $media->indices[0];
    $entity->replace = sprintf($media_link_pattern, $media->url, $media->expanded_url, $media->display_url);
    
    $entity_holder[$entity->start] = $entity;
  }
  }
  
  krsort($entity_holder);
  foreach($entity_holder as $entity)
  {
    echo $text = substr_replace($text, $entity->replace, $entity->start, $entity->length)."";
  }
  echo"</p><small class=\"block text-muted\"><i class=\"fa fa-clock-o\"></i>{$tweet->created_at}</small>
			</li>
			";
}
?>

		</ul>


