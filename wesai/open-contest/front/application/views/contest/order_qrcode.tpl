<script type="text/javascript" src="{'js/jquery.qrcode.min.js'|cdnurl}"></script>
<script type="text/javascript" src="{'js/qrcode.js'|cdnurl}"></script>

<script type="text/javascript">
	{if !empty($qrCodeData)}
	jQuery(function () {
		jQuery('#qrcode').qrcode(
				{
					text  : "{$qrCodeData}",
					width : 250,
					height: 250
				});
	})
	{/if}
</script>
