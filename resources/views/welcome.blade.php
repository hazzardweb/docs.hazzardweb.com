<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
		<title>HazzardWeb</title>
		<link href="//fonts.googleapis.com/css?family=Lato:100,300" rel="stylesheet" type="text/css">
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}
			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}
			.content {
				text-align: center;
				display: inline-block;
				margin-top: 300px;
			}
			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}
			@media (max-width: 560px) {
				.title {
					font-size: 66px;
				}
			}
			.title span {
				color: #008CBA;
			}
			.sub {
				font-size: 24px;
				 margin-bottom: 40px;
			}
			.links a {
				color: #008CBA;
				text-decoration: none;
				font-size: 20px;
				font-weight: 300;
				margin: 0px 5px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">Hazzard<span>Web</span></div>
				<div class="links">
					<a href="http://codecanyon.net/user/HazzardWeb/portfolio">portfolio</a> &#8226;
					<!-- <a href="{{ route('blog.index') }}">blog</a> -->
					<a href="mailto:hazzardweb@gmail.com">contact</a> &#8226;
					<a href="{{ route('docs.index') }}">docs</a>
					&#8226; <a href="http://git.hazzardweb.com">git</a>
				</div>
			</div>
		</div>
	</body>
</html>
