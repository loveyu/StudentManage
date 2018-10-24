<?php if($_SERVER['HTTP_HOST'] === "demo.loveyu.net"): ?>
	<script type="text/javascript">
		var ishttps = 'https:' == document.location.protocol ? true : false;
		var _paq = _paq || [];
		_paq.push(["setCookieDomain", "*.loveyu.net"]);
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		if (ishttps) {
			_paq.push(['setCustomVariable', 1, "HTTPS", "YES", "visit"]);
		}
		(function () {
			var u = "//tj.loveyu.info/";
			_paq.push(['setTrackerUrl', u + 't_' + Math.ceil(Math.random() * 1000000) + '_piwik.jt']);
			_paq.push(['setSiteId', 4]);
			var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
			g.type = 'text/javascript';
			g.async = true;
			g.defer = true;
			g.src = u + 't_5464563432534_piwik.js';
			s.parentNode.insertBefore(g, s);
		})();
	</script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-10035134-12"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}

		gtag('js', new Date());

		gtag('config', 'UA-10035134-12');
	</script>
<?php endif; ?>