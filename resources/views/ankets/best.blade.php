@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $titleId }} сайта знакомств "Виолетта"</h1>
<h3 class="titleSAnkets mrg4">{{ $countSearchAnkStr }}</h3>
{if $userdata.user_id > 0}
	{if $userdata.user_top100 > 0 && $userdata.user_fotos > 0}
	{elseif $userdata.user_top100 == 0 && $userdata.user_fotos > 0}
	<p align="center" class="blue inTop">Стань участником ТОПа</p>
	{else}
		<p align="center" class="blue inTop">Стань участником ТОПа</p>
		<p align="center" class="blue">тебе нужно всего лишь разместить фотографию</p>
	{/if}
{else}
	<p align="center" class="blue inTop">Стань участником ТОПа</p>
	<p align="center" class="blue">тебе нужно всего лишь заполнить анкету и разместить фотографию</p>
{/if}
<br />
<table id="mScreen">
	<tr>
		<td class="wth6">
			<div class="ankets2">
{if $ankets}
{section loop=$ankets name=j}
<dl>
<dt>
<a href="{$smarty.const.SITE_URL}index.php?mod=ank&amp;id={$ankets[j].user_id}">
<img alt="{$ankets[j].user_name},{$ankets[j].user_age}{$ankets[j].user_age_type},{$ankets[j].city}" src="{$smarty.const.SITE_URL}{$ankets[j].foto_url}" /></a>
</dt>
<dd>
<p>{if $ankets[j].user_reg_is}<img title="на сайте" class="online" alt="на сайте" src="{$smarty.const.SITE_URL}templates/image/on_line.gif" />{/if}<a href="{$smarty.const.SITE_URL}index.php?mod=ank&amp;id={$ankets[j].user_id}" {if $ankets[j].user_sex == $smarty.const.MEN}class="name_man"{else}class="name_woman"{/if}>{$ankets[j].user_name}</a>
{if $ankets[j].user_sex == $smarty.const.MEN}<img alt="Мужчина" src="{$smarty.const.SITE_URL}templates/image/sex_men.jpg" />{else}<img alt="Женщина" src="{$smarty.const.SITE_URL}templates/image/sex_women.jpg" />{/if}
<span>({$ankets[j].user_fotos} фото)</span></p>
<p><strong>{$ankets[j].user_age} {$ankets[j].user_age_type}</strong>, {$ankets[j].city}</p>
<table class="topReit">
<tr>
	<td>
		<p>Рейтинг:</p>
	</td>
	<td>
		<div class="divUn1">
		<div>
			<ul>
				<li class="current-rating2" style="width:{$ankets[j].user_reiting_str}px;">&nbsp;</li>
			</ul>
		</div>
		</div>
	</td>
</tr>
</table>			
<p><i>{$ankets[j].onTop}</p>
</dd>
</dl>
{/section}
{else}
<p class="pad5"><strong>по вашему запросу ничего не найдено</strong></p>
{/if}
				</div>
		</td>
		<td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 160x600 топ  100 (В) */
google_ad_slot = "1016034439";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</td>
	</tr>
</table>
{$pagination}
@overwrite