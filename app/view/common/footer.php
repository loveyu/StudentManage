</div>
<div class="footer text-center">
	<p class="help-block">页面加载耗时：<?php echo c()->getTimer()->get_second() ?>s，数据库查询：<?php echo db_class()->getDriver()->get_query_count() ?> 次。</p>
</div></div>
</div>
</div>
<?php include __DIR__."/footer.php"?>
</body>
</html>