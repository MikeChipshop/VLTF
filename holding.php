<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title><?php bloginfo("name"); ?></title>
<link rel='stylesheet' href='https://use.typekit.net/dfb1hpf.css' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo get_template_directory_uri() . '/common.css'?>' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/style.css' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/responsive.css' type='text/css' media='all' />
</head>
<body style="background:#e40346 url(<?php echo get_template_directory_uri(); ?>/images/171201_Vaul1_webBG_1920x1080_Jv1.jpg); no-repeat center;background-size:cover;">

<div id="holding">
	<img id="logo" src="<?php echo get_template_directory_uri(); ?>/images/vault-v-black.svg">
	
	<div id="clockdiv">
      <div>
        <span class="days"></span>
        <div class="smalltext">Days</div>
      </div>
      <div>
        <span class="hours"></span>
        <div class="smalltext">Hours</div>
      </div>
      <div>
        <span class="minutes"></span>
        <div class="smalltext">Minutes</div>
      </div>
      <div>
        <span class="seconds"></span>
        <div class="smalltext">Seconds</div>
      </div>
    </div>
	
	<?php 
	vault_social();
	?>
    
    <div class="quicklinks">
    	<?php
		foreach(array(866,918,983,237) as $link){
    		?>
            <a href="<?php echo get_the_permalink($link); ?>"><?php echo get_the_title($link); ?></a>
            <?php
		}
		?>
    </div>
</div>

<script type="text/javascript">
function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector('.days');
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);

    daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = new Date("2017-12-05 10:00");
initializeClock('clockdiv', deadline);	
</script>
	
</body>
</html>