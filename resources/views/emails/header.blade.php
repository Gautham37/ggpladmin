<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@600&family=Maven+Pro:wght@600&display=swap" rel="stylesheet">
<style>
#parent {
	font-family: 'Fira Sans', sans-serif;
	font-family: 'Maven Pro', sans-serif;
	padding: 0px;
	height: auto;
	background: linear-gradient(to top, transparent, rgba(193, 255, 130, 1)), linear-gradient(to bottom, transparent, rgba(254, 255, 253, 0)), url("<?= url('images/email-images/grocery-bg.png') ?>");
}

#child {
	height: 100px;
	width: 100%;
	margin-left: 0px;
	background: linear-gradient(to top, transparent, rgba(194, 255, 131, 0)), linear-gradient(to bottom, transparent, rgba(198, 255, 140, 0)), url("<?= url('/images/email-images/header-bg.png') ?>");
    background-size: cover;
}

#child img {
	width: 100px;
	height: 100px;
	display: block;
	margin: 15px auto;
}

#child_body {
	width: 90%;
	height: auto;
	margin: 10px auto;
	background-color: #fff;
	border-radius: 10px;
}

#child_head {
	height: 350px;
	width: 100%;
	border-radius: 10px 10px 0px 0px;
	background-color: green;
	background: url(" <?= url('images/mail-bg.png') ?>");
	background-size: cover;
}

#child_head img {
	height: 350px;
	width: 100%;
	border-radius: 10px 10px 0px 0px;
}

#child_content {
	height: 80px;
	width: 100%;
	margin-top: 50px;
}

#child_content img {
	width: 80px;
	height: 80px;
	display: block;
	margin: 50px auto;
}

#child_content1 {
	margin: 30px 180px 0px 180px;
	text-align: center;
}

#child_content1 h1 {
	font-size: 40px;
}

#child_content1 p {
	font-size: 14px;
}

#head_msg {
	text-align: center;
	font-size: 24px;
}

#head_msg1 {
	font-size: 14px;
}

#footer {
	margin: 30px 70px 0px 70px;
	text-align: center;
	color: green;
	text-decoration: none;
}

#footer span {
	font-size: 14px;
	vertical-align: middle;
	color: #9a6e3a;
}

#footer img {
	vertical-align: middle;
}

#footer .left {
	font-size: 14px;
	margin-right: 50px;
	text-decoration: none;
	color: #9a6e3a;
}

#footer_next {
	margin-top: 15px;
	color: #9a6e3a;
}

.child_msg {
	font-size: 14px;
	color: #282a35b3;
	text-align: left;
}

.child_msg1 {
	color: #167250;
}

.tiny {
	height: 20px;
	width: 20px;
}

h2 {
	color: green;
}

a {
	text-decoration: none;
}

a href {
	color: #9a6e3a;
}  
</style>
</head>

 <body>
       <div id="parent">

         <div id="child">
            <img src="{{ $app_logo }}" alt="{{ setting('app_name') }}" class="brand-image">
         </div>
         
         <div id="child_body">
         
          <div id="child_head"></div>