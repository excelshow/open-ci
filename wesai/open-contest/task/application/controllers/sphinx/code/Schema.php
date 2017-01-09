<?php

class Schema
{

	public static function getDocHeader()
	{
		return '<?xml version="1.0" encoding="utf-8"?><sphinx:docset>' . PHP_EOL;
	}

	public static function getDocFooter()
	{
		return '</sphinx:docset>' . PHP_EOL;
	}

	public static function getDocSchemaContest()
	{
		return <<<EOF
<sphinx:schema>
<sphinx:attr name="fk_corp" type="int" default="0"/>
<sphinx:field name="name" attr="string"/>
<sphinx:attr name="gtype" type="int" default="0"/>
<sphinx:attr name="publish_state" type="multi" default="0"/>
<sphinx:attr name="sdate_start" type="int" default="0"/>
<sphinx:attr name="sdate_end" type="int" default="0"/>
<sphinx:attr name="location" type="multi" default="0"/>
<sphinx:attr name="level" type="multi" default="0"/>
<sphinx:attr name="seller_fk_corp" type="multi" default="0"/>
<sphinx:attr name="ctime" type="int" default="0"/>
<sphinx:attr name="template" type="int" default="0"/>
<sphinx:attr name="sort_weight" type="int" default="0"/>
<sphinx:attr name="utime" type="int" default="0"/>
</sphinx:schema>
EOF;
	}

	public static function getDocFormatContest()
	{
		return <<<EOF
<sphinx:document id="%d">
<fk_corp>%d</fk_corp>
<name>![CDATA[%s]]</name>
<gtype>%d</gtype>
<publish_state>%d</publish_state>
<sdate_start>%d</sdate_start>
<sdate_end>%d</sdate_end>
<location>![CDATA[%s]]</location>
<level>![CDATA[%s]]</level>
<seller_fk_corp>![CDATA[%s]]</seller_fk_corp>
<ctime>%d</ctime>
<template>%d</template>
<sort_weight>%d</sort_weight>
<utime>%d</utime>
</sphinx:document>
EOF;
	}

	public static function getDocSchemaOrder()
	{
		return <<<EOF
<sphinx:schema>
<sphinx:attr name="seller_fk_corp" attr="int"/>
<sphinx:attr name="owner_fk_corp" attr="int"/>
<sphinx:attr name="is_dist" attr="int"/>
<sphinx:attr name="dist_contest_plan_id" attr="int"/>
<sphinx:attr name="dist_contest_choose_id" attr="int"/>
<sphinx:field name="cname" attr="string"/>
<sphinx:field name="corp_name"/>
<sphinx:field name="app_name"/>
<sphinx:field name="channel_transaction_id"/>
<sphinx:field name="out_trade_no" />
<sphinx:field name="corp_full_name" />
<sphinx:attr name="app_name" type="string"/>
<sphinx:attr name="corp_name" type="string"/>
<sphinx:attr name="corp_full_name" type="string"/>
<sphinx:attr name="channel_transaction_id" type="string"/>
<sphinx:attr name="corp_id" type="string"/>
<sphinx:attr name="corp_charge_type" type="int" default="0"/>
<sphinx:attr name="out_trade_no" type="string"/>
<sphinx:attr name="amount" type="int" default="0"/>
<sphinx:attr name="amount_pay" type="int" default="0"/>
<sphinx:attr name="type" type="int" default="0"/>
<sphinx:attr name="state" type="int" default="0"/>
<sphinx:attr name="paid_time" type="int" default="0"/>
<sphinx:attr name="ctime" type="int" default="0"/>
</sphinx:schema>
EOF;
	}

	public static function getDocFormatOrder()
	{
		return <<<EOF
<sphinx:document id="%d">
<seller_fk_corp>%d</seller_fk_corp>
<owner_fk_corp>%d</owner_fk_corp>
<is_dist>%d</is_dist>
<dist_contest_plan_id>%d</dist_contest_plan_id>
<dist_contest_choose_id>%d</dist_contest_choose_id>
<cname>![CDATA[%s]]</cname>
<app_name>%s</app_name>
<corp_name>%s</corp_name>
<corp_full_name>%s</corp_full_name>
<channel_transaction_id>%s</channel_transaction_id>
<corp_id>%s</corp_id>
<corp_charge_type>%d</corp_charge_type>
<out_trade_no>%s</out_trade_no>
<amount_pay>%d</amount_pay>
<amount>%d</amount>
<type>%d</type>
<state>%d</state>
<paid_time>%d</paid_time>
<ctime>%d</ctime>
</sphinx:document>
EOF;
	}
}
