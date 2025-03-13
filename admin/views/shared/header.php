<?php
if (!defined('BASEPATH')) {
	header('Location:/404');
}
require_once __DIR__ . '/../../../src/Utils.php';
$site_name = Config::APP['name'];
$site_url = Utils::getCurrentUrl();
$url_text = Utils::getUrlText($site_url)["msg"];
$page_title = $url_text ? "$url_text | $site_name" : $site_name;

if (CORE->userLoggedIn()['type'] != 'admin') {
	echo "<script>window.location='" . BASEPATH . "'</script>";
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $page_title; ?></title>
	<?php echo Config::IMPORT['header']; ?>
	<style>
		body {
			background: white;
		}

		.navbar[data-bs-theme=dark] {
			background: #000000;
		}

		.active {
			background: #000409 !important;
		}

		.add-new {
			background-color: #0c0c0c;
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-direction: row-reverse;
			margin-left: 3px;
		}

		.dropdown-item {
			margin-left: 3px;
		}

		.dropdown-item.active {
			background: #032f50 !important;
			color: white !important;

		}
	</style>
</head>

<body>
	<div class="page">
		<!-- Sidebar -->
		<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
			<div class="container-fluid">
				<div class="navbar-brand navbar-brand-autodark ">
					<a href="." class="d-flex justify-content-center">
						<img class="mt-0 mt-lg-2" src="<?= config::APP['adminPath']; ?>/assets/img/icon_white.svg" width="100px">
					</a>
				</div>
				<div class="d-flex">
				<button data-bs-toggle="modal" data-bs-target="#fileManager" class="navbar-toggler d-sm-block d-md-block d-lg-none mr-5 p-1">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
						<path stroke="none" d="M0 0h24v24H0z" fill="none" />
						<path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
					</svg>
				</button>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
					aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button></div>

				<div class="collapse navbar-collapse" id="sidebar-menu">
					<ul class="navbar-nav pt-lg-3">
						<li class="nav-item ">
							<a class="nav-link" href="./">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M5 12l-2 0l9 -9l9 9l-2 0" />
										<path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
										<path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
									</svg>
								</span>
								<span class="nav-link-title">
									Home
								</span>
							</a>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#navbar-layout" data-bs-toggle="dropdown"
								data-bs-auto-close="false" role="button" aria-expanded="true">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-sitemap">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M3 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
										<path d="M15 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
										<path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
										<path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
										<path d="M12 9l0 3" />
									</svg>
								</span>
								<span class="nav-link-title">
									Pages
								</span>
							</a>
							<div class="dropdown-menu ">
								<div class="dropdown-menu-columns">

									<div class="dropdown-menu-column">
										<a class="dropdown-item active" href="./layout-vertical.html">
											Company information
											<span class="badge badge bg-gray-lt p-1 text-uppercase ms-auto"><i class="ri-settings-line"></i></span>
										</a>
										<a class="dropdown-item" href="./layout-vertical.html">
											Home Page
										</a>
										<a class="dropdown-item " href="./layout-vertical.html">
											About Us
										</a>
										<a class="dropdown-item add-new" href="./layout-vertical.html">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M12 5l0 14" />
												<path d="M5 12l14 0" />
											</svg> Add New
										</a>
									</div>
								</div>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#navbar-layout" data-bs-toggle="dropdown"
								data-bs-auto-close="false" role="button" aria-expanded="true">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-settings-check">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M11.445 20.913a1.665 1.665 0 0 1 -1.12 -1.23a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.31 .318 1.643 1.79 .997 2.694" />
										<path d="M15 19l2 2l4 -4" />
										<path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
									</svg>
								</span>
								<span class="nav-link-title">
									My Services
								</span>
							</a>
							<div class="dropdown-menu ">
								<div class="dropdown-menu-columns">

									<div class="dropdown-menu-column">
										<a class="dropdown-item" href="./layout-vertical.html">
											Categories
											<span class="badge badge bg-gray-lt p-1 text-uppercase ms-auto"><i class="ri-layout-grid-line"></i></span>
										</a>
										<a class="dropdown-item" href="./layout-vertical.html">
											All Services
										</a>
										<a class="dropdown-item add-new" href="./layout-vertical.html">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M12 5l0 14" />
												<path d="M5 12l14 0" />
											</svg> New Service
										</a>
									</div>
								</div>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#navbar-layout" data-bs-toggle="dropdown"
								data-bs-auto-close="false" role="button" aria-expanded="true">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-pinned">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M16 3a1 1 0 0 1 .117 1.993l-.117 .007v4.764l1.894 3.789a1 1 0 0 1 .1 .331l.006 .116v2a1 1 0 0 1 -.883 .993l-.117 .007h-4v4a1 1 0 0 1 -1.993 .117l-.007 -.117v-4h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-2a1 1 0 0 1 .06 -.34l.046 -.107l1.894 -3.791v-4.762a1 1 0 0 1 -.117 -1.993l.117 -.007h8z" />
									</svg>
								</span>
								<span class="nav-link-title">
									Posts
								</span>
							</a>
							<div class="dropdown-menu ">
								<div class="dropdown-menu-columns">

									<div class="dropdown-menu-column">
										<a class="dropdown-item" href="./layout-vertical.html">
											All Posts
										</a>
										<a class="dropdown-item add-new" href="./layout-vertical.html">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M12 5l0 14" />
												<path d="M5 12l14 0" />
											</svg> New Post
										</a>
									</div>
								</div>
							</div>

						<li class="nav-item">
							<a class="nav-link" href="./">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
										<path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
									</svg>
								</span>
								<span class="nav-link-title">
									Account
								</span>
							</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="./">
								<span class="nav-link-icon d-md-none d-lg-inline-block">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout-2">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M10 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
										<path d="M15 12h-12l3 -3" />
										<path d="M6 15l-3 -3" />
									</svg>
								</span>
								<span class="nav-link-title">
									Sign Out
								</span>
							</a>
						</li>

						</li>

					</ul>
				</div>
			</div>
		</aside>
		<div class="page-wrapper">