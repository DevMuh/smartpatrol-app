<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>

<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<style type="text/css">

	$bgColor: #f7f7f7;
	$jaggedDistance: 32px;

	// boo
	$booSize: $jaggedDistance * 5;
	$booBg: $bgColor;
	$booShadow: darken($booBg, 5%);

	// face
	$booFaceSize: round($jaggedDistance / 1.3333);
	$booFaceColor: #9b9b9b;

	// ===========

	@keyframes floating {
		0% {
			transform: translate3d(0, 0, 0);	
		}
		45% {
			transform: translate3d(0, -10%, 0);	
		}
	  55% {
			transform: translate3d(0, -10%, 0);	
		}	
		100% {
			transform: translate3d(0, 0, 0);
		}			
	}

	@keyframes floatingShadow {
		0% {
			transform: scale(1);	
		}
		45% {
			transform: scale(.85);	
		}
	  55% {
			transform: scale(.85);	
		}	
		100% {
			transform: scale(1);
		}			
	}

	// ===========

	@mixin border-bottom-jagged($size, $color-outer) {
	  position: relative;
	  padding-bottom: $size; 

	  &::after {
	    content: '';
	    display: block;
	    position: absolute;
	    left: -($size / 1.7);
	    bottom: -($jaggedDistance / 3.85);
	    width: calc(100% + #{$size});
	    height: $size;
	    background-repeat: repeat-x;
	    background-size: $size $size;
	    background-position: left bottom;
	    background-image: linear-gradient(-45deg, $color-outer ($size / 2), transparent 0),
	                      linear-gradient(45deg, $color-outer ($size / 2), transparent 0),
	                      linear-gradient(-45deg, $booFaceColor ($size / 1.7), transparent 0),
	                      linear-gradient(45deg, $booFaceColor ($size / 1.7), transparent 0);
	  }
	}

	// ===========

	body {
	  background-color: $bgColor;
	}

	.container {
	  font-family: 'Varela Round', sans-serif;
	  color: $booFaceColor;
	  position: relative;
	  height: 100vh;
	  text-align: center;
	  font-size: $jaggedDistance / 2;
	  
	  h1 {
	    font-size: $jaggedDistance;
	    margin-top: $jaggedDistance;
	  }
	}

	.boo-wrapper {
	  width: 100%;
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%, -50%);
	  paddig-top: $jaggedDistance * 2;
	  paddig-bottom: $jaggedDistance * 2;
	}

	.boo {
	  width: $booSize;
	  height: $booSize + ($booSize * .15);
	  background-color: $booBg;
	  margin-left: auto;
	  margin-right: auto;
	  border: (($jaggedDistance / 1.65)  - ($jaggedDistance /2)) solid $booFaceColor;
	  border-bottom: 0;
	  overflow: hidden;
	  border-radius: ($booSize / 2) ($booSize / 2) 0 0;
	  box-shadow: -($jaggedDistance / 2) 0 0 2px rgba($booShadow, .5) inset;
	  @include border-bottom-jagged($jaggedDistance, $bgColor);
	  animation: floating 3s ease-in-out infinite;

	  .face {
	    width: $booFaceSize;
	    height: $jaggedDistance / 10;
	    border-radius: 5px;
	    background-color: $booFaceColor;
	    position: absolute;
	    left: 50%;
	    bottom: $jaggedDistance + $booFaceSize;
	    transform: translateX(-50%);
	    
	    &::before,
	    &::after {
	      content: '';
	      display: block;
	      width: $booFaceSize / 4;
	      height: $booFaceSize / 4;
	      background-color: $booFaceColor;
	      border-radius: 50%;
	      position: absolute;
	      bottom: $jaggedDistance + ($booFaceSize / 3);
	    }
	    
	    &::before {
	      left: -$booFaceSize;
	    }
	    
	    &::after {
	      right: -$booFaceSize;
	    }
	  } 
	}

	.shadow {
	  width: $booSize - $jaggedDistance;
	  height: $jaggedDistance / 2;
	  background-color: rgba($booShadow, .75);
	  margin-top: $jaggedDistance * 1.25;  
	  margin-right: auto;
	  margin-left: auto;
	  border-radius: 50%;
	  animation: floatingShadow 3s ease-in-out infinite;
	}
</style>
</head>
<body>
	<div class="container">
	  <div class="boo-wrapper">
	    <div class="boo">
	      <div class="face"></div>
	    </div>
	    <div class="shadow"></div>

	    <img src="<?=base_url('assets/apps/assets/page_not_found.svg')?>" width="450px">

	    <h1>Whoops!</h1>
	    <p>
	      We couldn't find the page you
	      <br />
	      were looking for.
	    </p>
	  </div>
	</div>
</body>
</html>