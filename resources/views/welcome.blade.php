<!DOCTYPE HTML>
<!--
	Stellar by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>{{ config('app.name') }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{{ asset('theme/dist/css/main.css') }}" />
		<noscript><link rel="stylesheet" href="{{ asset('theme/dist/css/noscript.css') }}" /></noscript>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header" class="alt">
                        <span class="logo"><img src="{{ asset('theme/dist/img/blogo.svg') }}" alt="" /></span>
						<h1>{{ config('app.name') }}</h1>
					</header>

					<div id="main">

						<!-- Introduction -->
							<section id="intro" class="main">
								<div class="spotlight">
									<div class="content">
										<header class="major">
											<h2>What is Lorem Ipsum?</h2>
										</header>
										<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
										<ul class="actions">
											<li><a href="#" class="button">Learn More</a></li>
										</ul>
									</div>
									<span class="image"><img src="images/pic01.jpg" alt="" /></span>
								</div>
							</section>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<section>
							<h2>About Us</h2>
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
							<ul class="actions">
								<li><a href="#" class="button">Learn More</a></li>
							</ul>
						</section>
						<p class="copyright">&copy; Untitled. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
					</footer>

			</div>

		<!-- Scripts -->
            <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/jquery.scrollex.min.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/jquery.scrolly.min.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/browser.min.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/breakpoints.min.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/util.js') }}"></script>
			<script src="{{ asset('theme/dist/js/landingpage/main.js') }}"></script>

	</body>
</html>