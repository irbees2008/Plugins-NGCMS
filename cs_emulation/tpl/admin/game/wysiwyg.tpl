<script type="text/javascript" src="plugins/{plugin}/tpl/admin/game/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() {
		new nicEditor({iconsPath : 'plugins/{plugin}/tpl/admin/game/nicEditorIcons.gif'}).panelInstance('text');
	});
</script>
<br/>
<textarea id="text" name="team" style="width: 100%;">{text}</textarea>